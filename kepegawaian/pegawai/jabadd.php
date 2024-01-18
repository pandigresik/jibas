<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 29.0 (Sept 20, 2023)
 * @notes: JIBAS Education Community will be managed by Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 *  
 * Copyright (C) 2009 Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *  
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php
require_once("../include/sessionchecker.php");
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

$nip = $_REQUEST['nip'];

$eselon = $_REQUEST['cbEselon'];
$idjabatan = $_REQUEST['idJabatan'];
$jenis = $_REQUEST['cbJenisJabatan'];
$jabatan = CQ($_REQUEST['txJabatan']);
$tgltmtjab = $_REQUEST['cbTglTMTJab'];
$blntmtjab = $_REQUEST['cbBlnTMTJab'];
$thntmtjab = $_REQUEST['txThnTMTJab'];
$tmt = "$thntmtjab-$blntmtjab-$tgltmtjab";
$sk = $_REQUEST['txSK'];
$keterangan = CQ($_REQUEST['txKeterangan']);

if (isset($_REQUEST['btSubmit']))
{
	OpenDb();
	
	$success = true;
	BeginTrans();
	
	$sql = "SELECT COUNT(nip) FROM peglastdata WHERE nip='$nip'";
	$ndata = FetchSingle($sql);
	if ($ndata == 0)
	{
		$sql = "INSERT INTO peglastdata SET nip='$nip'";
		QueryDbTrans($sql, $success);
	}
	
	if ($success)
	{
		$sql = "UPDATE pegjab SET terakhir=0 WHERE nip='$nip'";
		QueryDbTrans($sql, $success);	
	}
	
	if ($success)
	{
		if (strlen(trim((string) $idjabatan)) == 0)
			$sql = "INSERT INTO pegjab SET namajab='$jabatan', nip='$nip', tmt='$tmt', sk='$sk', keterangan='$keterangan', terakhir='1', jenis='$jenis'";
		else
			$sql = "INSERT INTO pegjab SET namajab='$jabatan', nip='$nip', idjabatan=$idjabatan, tmt='$tmt', sk='$sk', keterangan='$keterangan', terakhir='1', jenis='$jenis'";
		QueryDbTrans($sql, $success);	
	}
	
	$idpegjab = 0;
	if ($success)
	{
		$sql = "SELECT LAST_INSERT_ID()";
		$idpegjab = (int)FetchSingle($sql);
	}
	
	if ($success)
	{
		$sql = "UPDATE peglastdata SET idpegjab=$idpegjab WHERE nip='$nip'";
		QueryDbTrans($sql, $success);	
	}
	
	if ($success)
	{
		CommitTrans();
		CloseDb(); ?>
		<script language="javascript">
			opener.Refresh();
			window.close();
		</script>
<?php 	exit();
	}	
	else
	{
		RollbackTrans();
		CloseDb();
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tambah Jabatan Pegawai</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function validate() {
	return validateEmptyText('txThnTMTJab', 'Tahun TMT Jabatan Pegawai') &&
		   validateInteger('txThnTMTJab', 'Tahun TMT Jabatan Pegawai') &&  
		   validateLength('txThnTMTJab', 'Tahun TMT Jabatan Pegawai', 4);
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function PilihJabatan() {
	var addr = "pilihjabatan.php";
    newWindow(addr, 'PilihJabatan','400','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function TerimaJabatan(id, jabatan) {
	document.getElementById("idJabatan").value = id;
	document.getElementById("txJabatan").value = jabatan;
	document.getElementById("cbTglTMTJab").focus();
}
</script>
</head>

<body>
<form name="main" method="post" onSubmit="return validate()">
<input type="hidden" name="nip" id="nip" value="<?=$nip?>" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr height="30">
	<td width="100%" class="header" align="center">Tambah Jabatan</td>
</tr>
<tr>
	<td width="100%" align="center">
    
    <table border="0" cellpadding="0" cellspacing="5" width="100%">
    <tr>
        <td align="right" valign="top" width="22%"><strong>Jabatan :</strong></td>
        <td width="*" align="left" valign="top">
        <select name="cbJenisJabatan" id="cbJenisJabatan" onKeyPress="return focusNext('btJabatan', event)">
<?php 	OpenDb();
		$sql = "SELECT jenis, jabatan FROM jenisjabatan ORDER BY urutan";
		$result = QueryDb($sql);
		while ($row = mysqli_fetch_row($result)) { ?>    
    		<option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $jenis)?>>(<?=$row[1]?>) <?=$row[0]?></option>
<?php 	}
		CloseDb(); ?>    
	    </select>
        <input type="hidden" name="idJabatan" id="idJabatan" value="<?=$idjabatan?>"/>
        <input type="text" name="txJabatan" id="txJabatan" value="<?=$jabatan?>" size="45" maxlength="100" /><input type="button" value="..." class="but" id="btJabatan" onClick="PilihJabatan()" />
        </td>
	</tr>
    <tr>
        <td align="right" valign="top"><strong>TMT Jabatan :</strong></td>
        <td width="*" align="left" valign="top">
        <select id="cbTglTMTJab" name="cbTglTMTJab" onKeyPress="return focusNext('cbBlnTMTJab', event)">
    <?php for ($i = 1; $i <= 31; $i++) { ?>    
            <option value="<?=$i?>" <?=IntIsSelected($i, $tgltmtjab)?>><?=$i?></option>	
    <?php } ?>    
        </select>
        <select id="cbBlnTMTJab" name="cbBlnTMTJab" onKeyPress="return focusNext('txThnTMTJab', event)">
    <?php $M = date("m");
        for ($i = 1; $i <= 12; $i++) { ?>    
            <option value="<?=$i?>" <?=IntIsSelected($i, $blntmtjab)?>><?=NamaBulan($i)?></option>	
    <?php } ?>    
        </select>
        <input type="text" name="txThnTMTJab" id="txThnTMTJab" onKeyPress="return focusNext('txSK', event)" size="4" maxlength="4" value="<?=$thntmtjab?>"/>
        </td>
    </tr>
    <tr>
    	<td align="right">SK : </td>
	    <td align="left" valign="top">
        <input type="text" name="txSK" value="<?=$sk?>" id="txSK" size="30" maxlength="100" onKeyPress="return focusNext('txKeterangan', event)" />
        </td>
    </tr>
    <tr>
    	<td align="right" valign="top">Keterangan : </td>
	    <td align="left" valign="top">
        <textarea id="txKeterangan" name="txKeterangan" rows="2" cols="40"><?=$keterangan?></textarea>
        </td>
    </tr>
    <tr>
    	<td align="right" valign="top">&nbsp;</td>
	    <td align="left" valign="top">
        <input type="submit" value="Simpan" name="btSubmit" class="but" />
        <input type="button" value="Tutup" onClick="window.close()" class="but" />
        </td> 
    </tr>
    </table>
    </td>
</tr>
</table>
</form>

</body>
</html>