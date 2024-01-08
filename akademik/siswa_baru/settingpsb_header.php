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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$proses = 0;
if (isset($_REQUEST['proses'])) 
	$proses = $_REQUEST['proses'];
	
$kelompok = 0;	
if (isset($_REQUEST['kelompok']))
	$kelompok = $_REQUEST['kelompok'];

OpenDb();	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Setting Pendataan PSB</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">
var win = null;
function newWindow(mypage,myname,w,h,features) {
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
}

function change_dep() 
{
	var departemen = document.getElementById("departemen").value;
	parent.header.location.href = "settingpsb_header.php?departemen="+departemen;
	parent.footer.location.href = "settingpsb_blank.php";
}

function change_proses() 
{
	parent.footer.location.href = "settingpsb_blank.php";
}

function show_calon() 
{	
	var departemen = document.getElementById("departemen").value;
	var proses = document.getElementById("proses").value;
	
	if (proses.length == 0) 
	{
		alert ('Pastikan Proses Penerimaan ada dan statusnya aktif!');	
		document.getElementById("departemen").focus();
		return false;
	}	
		
	parent.footer.location.href = "settingpsb_content.php?departemen="+departemen+"&proses="+proses;
}

function tampil_kelompok() {
	var departemen = document.getElementById("departemen").value;
	var proses = document.getElementById("proses").value;
	
	newWindow('kelompok_tampil.php?departemen='+departemen+'&proses='+proses,'tampilKelompok','750','450','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function kelompok_kiriman(a,b,c) {
	document.getElementById("a").value=a;
	document.getElementById("b").value=b;
	document.getElementById("c").value=c;
	setTimeout("change_kelompok(1)",1);	
}

function change_kelompok() 
{	
	parent.footer.location.href = "settingpsb_blank.php";
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel') {
			show_calon();
			panggil('tabel');
		} 
		return false;
    } 
    return true;
}

function panggil(elem){
	var lain = new Array('departemen','kelompok');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

</script>
</head>
<body topmargin="0" leftmargin="0" onLoad="document.getElementById('departemen').focus()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
    <td rowspan="3" width="53%">
	<table width = "100%" border = "0">
    <tr>
      	<td align="left" width = "30%"><strong>Departemen</strong>
      	<td width="*">
        <select name="departemen" id="departemen" onchange="change_dep()" style="width:280px" onKeyPress="return focusNext('proses', event)" onfocus="panggil('departemen')">
<?php 		$dep = getDepartemen(SI_USER_ACCESS());    
		foreach($dep as $value) 
		{
			if ($departemen == "")
				$departemen = $value; ?>
        	<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > <?=$value ?> </option>
<?php 	} ?>
        </select></td>
  	</tr>
	<tr>
    	<td align="left"><strong>Proses Penerimaan</strong>
        <td>
		<select name="proses" id="proses" onchange="change_proses()" style="width:280px" onKeyPress="return focusNext('kelompok', event)" onfocus="panggil('proses')">
<?php 		$sql = "SELECT replid, proses, aktif FROM prosespenerimaansiswa WHERE departemen='$departemen'";				
			$result = QueryDb($sql);
			$namaproses = "";
			while ($row = @mysqli_fetch_array($result)) 
			{
				$tag = "";
				if ($row['aktif'] == 1)
				{
					$tag = " (A)";
					if ($proses == 0)
						$proses = $row['replid'];
				}
				$sel = ($proses == $row['replid']) ? "selected" : ""; ?>
                <option value="<?=$row['replid']?>" <?= $sel ?> ><?= $row['proses'] . $tag ?></option>
<?php 		} ?>
			</select>
        </td>
    </tr>
    </table>
	</td>
	<td width="*" rowspan="2" valign="middle"><a href="#" onclick="show_calon()"><img src="../images/view.png" name="tabel" width="48" height="48" border="0" id="tabel" onmouseover="showhint('Klik untuk menampilkan data calon siswa!', this, event, '120px')"/></a></td>
    <td width="45%" colspan = "2" align="right" valign="top">
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Konfigurasi Pendataaan PSB</font><br />
    <a href="../siswa_baru.php" target="content">
        <font size="1" color="#000000"><b>Penerimaan Siswa Baru</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Konfigurasi Pendataaan PSB</b></font>	
    </td>  
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
</body>
</html>
<?php
CloseDb();
?>