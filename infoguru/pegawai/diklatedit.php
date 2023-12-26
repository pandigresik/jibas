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
require_once("../include/sessioninfo.php");
require_once('../include/theme.php');

$id = $_REQUEST['id'];

$iddiklat = $_REQUEST['idDiklat'];
$diklat = $_REQUEST['txDiklat'];
$thndiklat = $_REQUEST['txTahunDiklat'];
$sk = $_REQUEST['txSK'];
$keterangan = $_REQUEST['txKeterangan'];
$alasan = $_REQUEST['txAlasan'];

if (isset($_REQUEST['btSubmit']))
{
	OpenDb();
		
	$sql = "UPDATE jbssdm.pegdiklat SET iddiklat=$iddiklat, tahun=$thndiklat, sk='$sk', keterangan='$keterangan', doaudit = 1 WHERE replid = $id";
	QueryDb($sql);
	CloseDb(); ?>
    <script language="javascript">
		opener.Refresh();
		window.close();
    </script>
<?php
} else {
	OpenDb();
	$sql = "SELECT p.iddiklat, d.diklat, p.tahun, p.sk, p.keterangan FROM jbssdm.pegdiklat p, jbssdm.diklat d WHERE p.replid = $id AND p.iddiklat = d.replid";	
	$result = QueryDb($sql);
	$row = mysqli_fetch_array($result);
	$iddiklat = $row['iddiklat'];
	$diklat = $row['diklat'];
	$thndiklat = $row['tahun'];
	$sk = $row['sk'];
	$keterangan = $row['keterangan'];
	CloseDb();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ubah Data Diklat</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function validate() {
	return validateEmptyText('txDiklat', 'Nama Diklat') && 
		   validateEmptyText('txTahunDiklat', 'Tahun Diklat') && 
		   validateNumber('txTahunDiklat', 'Tahun Diklat') &&
		   validateLength('txTahunDiklat', 'Tahun Diklat', 4);
}

function PilihDiklat() {
	var addr = "pilihdiklat.php";
    newWindow(addr, 'PilihDiklat','400','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function TerimaDiklat(id, diklat) {
	document.getElementById("idDiklat").value = id;
	document.getElementById("txDiklat").value = diklat;
	document.getElementById("txTahunDiklat").focus();
}
</script>
</head>

<body>
<form name="main" method="post" onSubmit="return validate()">
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr height="30">
	<td width="100%" class="header" align="center">Ubah Diklat</td>
</tr>
<tr>
	<td width="100%" align="center">
    
    <table border="0" cellpadding="0" cellspacing="5" width="100%">
    <tr>
    	<td width="22%" align="right"><strong>Diklat : </strong></td>
	    <td width="*" align="left" valign="top">
    		<input type="hidden" name="idDiklat" id="idDiklat" onKeyPress="return focusNext('tx', event)" value="<?=$iddiklat?>" />
		    <input type="text" name="txDiklat" id="txDiklat" value="<?=$diklat ?>" size="35" maxlength="100" readonly="readonly" style="background-color:#CCCCCC" /><input type="button" value="..." class="but" id="btDiklat" onClick="PilihDiklat()"  />&nbsp;
    	</td>
	</tr>
    <tr>
    	<td align="right"><strong>Tahun : </strong></td>
	    <td align="left" valign="top">
        <input type="text" name="txTahunDiklat" value="<?=$thndiklat?>" id="txTahunDiklat" size="4" maxlength="4" onKeyPress="return focusNext('txSK', event)" />
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
        <textarea id="txKeterangan" name="txKeterangan" rows="2" cols="40" onKeyPress="return focusNext('txAlasan', event)"><?=$keterangan?></textarea>
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
