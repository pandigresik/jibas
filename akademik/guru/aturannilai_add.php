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
require_once('../include/theme.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');
require_once('../library/dpupdate.php');

if (isset($_REQUEST['idtingkat']))
	$idtingkat = $_REQUEST['idtingkat'];
if (isset($_REQUEST['nip']))
	$nip = $_REQUEST['nip'];
if (isset($_REQUEST['id']))
	$id = $_REQUEST['id'];	

$aspek = "";
if (isset($_REQUEST['aspek']))
	$aspek = $_REQUEST['aspek'];	

OpenDb();
$sql = "SELECT j.departemen, j.nama, p.nip, p.nama, t.tingkat 
		FROM guru g, jbssdm.pegawai p, pelajaran j, tingkat t 
		WHERE g.nip=p.nip AND g.idpelajaran = j.replid AND t.departemen = j.departemen AND t.replid = '$idtingkat' 
		AND j.replid = '$id' AND g.nip = '".$nip."'"; 
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$departemen = $row[0];
$pelajaran = $row[1];
$guru = $row[2].' - '.$row[3];
$tingkat = $row[4];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Tambah Aturan Penentuan Grading Nilai]</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function validate() {	
	var isi = 0;
	var aspek = document.getElementById('aspek').value;
	
	for (i=1;i<=10;i++) {			
		var nmin = document.getElementById('nmin'+i).value;
		var nmax = document.getElementById('nmax'+i).value;
		var grade = document.getElementById('grade'+i).value;
		
		if (nmin.length > 0){
			isi = 1;		
			if (isNaN(nmin)){
				alert("Nilai minimum harus berupa bilangan");
				document.getElementById('nmin'+i).focus();				
				return false;
			} else {
				if (nmin < 0 ) {
					alert("Nilai minimum tidak boleh negatif");
					document.getElementById('nmin'+i).focus();				
					return false;
				}						
				if (nmax.length > 0){
					if (isNaN(nmax)){
						alert("Nilai maksimum harus berupa bilangan");
						document.getElementById('nmax'+i).focus();				
						return false;
					} else {
						if (parseFloat(nmax) < parseFloat(nmin)) {
							alert("Nilai maksimum harus lebih besar dari nilai minimum");
							document.getElementById('nmax'+i).focus();				
							return false;
						}
					}
					
				} else {
					alert ("Anda harus mengisikan data untuk nilai maksimum"); 
					document.getElementById('nmax'+i).focus();				
					return false;
				} 				
				if (grade.length > 0){
					if (!isNaN(grade)){
						alert("Grade nilai harus berupa huruf");
						document.getElementById('grade'+i).focus();				
						return false;
					} 				
				} else {
					alert ("Anda harus mengisikan data untuk grade nilai"); 
					document.getElementById('grade'+i).focus();				
					return false;
				} 
			}
		}
		
		if (nmax.length > 0){			
			if (nmax < 0 ) {
				alert("Nilai maksimum tidak boleh negatif");
				document.getElementById('nmax'+i).focus();				
				return false;
			}	
			if (nmin.length == 0){
				alert ("Anda harus mengisikan data untuk nilai minimum"); 
				document.getElementById('nmin'+i).focus();				
				return false;
			} else {
				if (parseFloat(nmax) < parseFloat(nmin)) {
					alert("Nilai maksimum harus lebih besar dari nilai minimum");
					document.getElementById('nmax'+i).focus();				
					return false;
				}				
			}
			if (grade.length == 0){
				alert ("Anda harus mengisikan data untuk grade nilai"); 
				document.getElementById('grade'+i).focus();				
				return false;				 
			}
		}
		
		if (grade.length > 0){		
			if (nmin.length == 0){
				alert ("Anda harus mengisikan data untuk nilai minimum"); 
				document.getElementById('nmin'+i).focus();				
				return false;
			} else {
				if (parseFloat(nmax) < parseFloat(nmin)) {
					alert("Nilai maksimum harus lebih besar dari nilai minimum");
					document.getElementById('nmax'+i).focus();				
					return false;
				}				
			} 				
			
			if (nmax.length == 0){
				alert ("Anda harus mengisikan data untuk grade nilai"); 
				document.getElementById('grade'+i).focus();				
				return false;
				 
			}
		}
	}		
	
	if (isi == 0) {
		alert ("Anda harus mengisi setidaknya satu data untuk aturan grading");
		document.getElementById('nmin1').focus;
		return false; 
	}
	
	if (aspek.length == 0) {
		alert ('Aspek tidak boleh kosong');
		document.getElementById('aspek').focus;
		return false; 
	}
	
	document.getElementById('main').submit();
	
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

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('nmin1').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:13px; font-weight:bold">
    .: Tambah Aturan Penentuan Grading Nilai :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr>
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<form name="main" id="main" action="aturannilai_simpan.php" method="POST">
<input type="hidden" name="id" id="id" value="<?=$id ?>" />
<input type="hidden" name="action" id="action" value="Add" />
<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="120"><strong>Departemen</strong></td>
	<td><input type="text" size="10" maxlength="50" class="disabled" readonly value="<?=$departemen ?>" />
    	<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />    
	</td>
</tr>
<tr>
	<td><strong>Tingkat</strong></td>
	<td><input type="text" size="10" maxlength="50" class="disabled" readonly value="<?=$tingkat ?>" />
        <input type="hidden" name="idtingkat" id="idtingkat" value="<?=$idtingkat ?>" /> 
	
	</td>
</tr>
<tr>
	<td><strong>Pelajaran</strong></td>
	<td><input type="text" size="30" maxlength="50" class="disabled" readonly value="<?=$pelajaran ?>" /><input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran ?>" /> 	
	</td>
</tr>
<tr>
    <td><strong>Guru</strong></td>
    <td>
        <input type="text" name="guru" id="guru" size="30" class="disabled" readonly value="<?=$guru ?>" /> <input type="hidden" name="nip" id="nip" value="<?=$nip ?>" /> 
        </td>
</tr>
<tr>
	<td><strong>Aspek</strong></td>
	<td>
    	<select name="aspek" id="aspek" onKeyPress="focusNext('nmin1',event)">
<?php 	$sql = "SELECT dasarpenilaian, keterangan FROM dasarpenilaian 
				WHERE aktif = 1 AND  dasarpenilaian NOT IN 
					(SELECT dasarpenilaian FROM aturangrading g, tingkat t 
					 WHERE t.replid = g.idtingkat AND g.idpelajaran = $id 
					 AND g.idtingkat = '$idtingkat' AND g.nipguru = '$nip' GROUP BY g.dasarpenilaian)
				ORDER BY keterangan";    
		$result = QueryDb($sql);	
		while ($row = @mysqli_fetch_array($result)) 
		{
			if ($aspek == "")
				$aspek = $row['dasarpenilaian']; ?>
          <option value="<?=$row['dasarpenilaian'] ?>" <?=StringIsSelected($row['dasarpenilaian'], $aspek) ?> >
		  <?=$row['keterangan'] ?>
          </option>
<?php 		} ?>
        </select> </td>
</tr>
<tr>
	<td colspan = "2">
	<fieldset><legend><b>Aturan Grading</b></legend>
	<br />
	<table width="100%" id="table" class="tab" border="0">
	<tr height="30">		
		<td class="header" align="center" width="10%">No</td>
		<td class="header" align="center" width="70%" colspan="3">Nilai Min &nbsp;&nbsp;&nbsp; Nilai Maks</td>
     	<td class="header" align="center" width="20%">Grade</td>
	</tr>
<?php for ($cnt=1;$cnt<=10;$cnt++) 
	{ ?>		
	<tr height="25">
		<td align="center"><?=$cnt?></td>
		<td align="right"><input type="text" name=<?='nmin'.$cnt?> id=<?='nmin'.$cnt?> size="8" maxlength="5" onKeyPress="focusNext('nmax<?=$cnt?>',event)"/> </td>
        <td align="center" ><strong> - </strong></td>
		<td align="left"><input type="text" name=<?='nmax'.$cnt?> id=<?='nmax'.$cnt?> size="8" maxlength="5" onkeypress="focusNext('grade<?=$cnt?>',event)"/> </td>
		<td align="center"><input type="text" name=<?='grade'.$cnt?> id=<?='grade'.$cnt?> size="3" maxlength="2" <?php if ($cnt!=10) { ?> onKeyPress="focusNext('nmin<?=(int)$cnt+1?>',event)" <?php } else { ?> onkeypress="focusNext('Simpan',event)" <?php } ?> style="text-transform:uppercase"/> </td>
	</tr>
<?php }	?>
	</table> 
    <div align="left">
	<font color="red"><p><b>Ket: Nilai desimal harus berupa titik,
    		<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Grade berupa nilai mutu</b></p>
    </font>
	</div>
	</fieldset>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
    
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="button" name="Simpan" id="Simpan" value="Simpan" class="but" onClick="return validate();document.getElementById('main').submit();"/>&nbsp;
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />    </td>
</tr>
<!-- END OF TABLE CONTENT -->
</table>


</form>
<!-- END OF CONTENT //--->
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
</body>
<?php
CloseDb();
?>
</html>
<script language="javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("aspek");
var num=10;
var x;
for (x=1;x<=num;x++){
var sprytextfield1 = new Spry.Widget.ValidationTextField("nmin"+x);
var sprytextfield2 = new Spry.Widget.ValidationTextField("nmax"+x);
var sprytextfield3 = new Spry.Widget.ValidationTextField("grade"+x);
}
</script>