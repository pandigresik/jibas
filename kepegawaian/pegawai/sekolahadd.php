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

$tingkat = $_REQUEST['cbTingkatSekolah'];
$lulus = $_REQUEST['txThnSekolah'];
$sekolah = CQ($_REQUEST['txSekolah']);
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
		$sql = "UPDATE pegsekolah SET terakhir=0 WHERE nip='$nip'";
		QueryDbTrans($sql, $success);	
	}
	
	if ($success)
	{
		$sql = "INSERT INTO pegsekolah SET nip='$nip', tingkat='$tingkat', sekolah='$sekolah', lulus=$lulus, sk='$sk', terakhir='1', keterangan='$keterangan'";
		QueryDbTrans($sql, $success);	
	}
	
	$idpegsekolah = 0;
	if ($success)
	{
		$sql = "SELECT LAST_INSERT_ID()";
		$idpegsekolah = (int)FetchSingle($sql);
	}
	
	if ($success)
	{
		$sql = "UPDATE peglastdata SET idpegsekolah=$idpegsekolah WHERE nip='$nip'";
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
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function validate() {
	return validateEmptyText('txSekolah', 'Nama Sekolah') && 
		   validateEmptyText('txThnSekolah', 'Tahun Kelulusan Sekolah') && 
		   validateLength('txThnSekolah', 'Tahun Kelulusan Sekolah', 4);
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
</script>
</head>

<body>
<form name="main" method="post" onSubmit="return validate()">
<input type="hidden" name="nip" id="nip" value="<?=$nip?>" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr height="30">
	<td width="100%" class="header" align="center">Tambah Sekolah</td>
</tr>
<tr>
	<td width="100%" align="center">
    
    <table border="0" cellpadding="0" cellspacing="5" width="100%">
    <tr>
    	<td width="22%" align="right">Sekolah : </td>
	    <td width="*" align="left" valign="top">
    		<select name="cbTingkatSekolah" id="cbTingkatSekolah" onKeyPress="return focusNext('txSekolah', event)">
<?php 		OpenDb();
			$sql = "SELECT pendidikan FROM jbsumum.tingkatpendidikan";
			$result = QueryDb($sql);
			while ($row = mysqli_fetch_row($result)) { ?>	    
    			<option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $tingkat)?>><?=$row[0]?></option>
<?php  		} 
			CloseDb(); ?>    
    </select>
		    <input type="text" name="txSekolah" id="txSekolah" value="<?=$sekolah?>" onKeyPress="return focusNext('txThnSekolah', event)" size="25" maxlength="100"/>
    	</td>
	</tr>
    <tr>
    	<td align="right">Lulus : </td>
	    <td align="left" valign="top">
        <input type="text" name="txThnSekolah" id="txThnSekolah" value="<?=$lulus?>" size="4" maxlength="4" onKeyPress="return focusNext('txSK', event)" />
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