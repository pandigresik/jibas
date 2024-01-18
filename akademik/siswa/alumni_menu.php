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
require_once('../cek.php');

if (isset($_REQUEST['departemen']))
$departemen=$_REQUEST['departemen'];
if (isset($_REQUEST['tahunajaran']))
$tahunajaran=$_REQUEST['tahunajaran'];
if (isset($_REQUEST['tingkat']))
$tingkat=$_REQUEST['tingkat'];
if (isset($_REQUEST['kelas']))
$kelas=$_REQUEST['kelas'];
if (isset($_REQUEST['nis']))
$nis=$_REQUEST['nis'];
if (isset($_REQUEST['nama']))
$nama=$_REQUEST['nama'];
if (isset($_REQUEST['pilihan']))
$pilihan=$_REQUEST['pilihan'];
$jenis = $_REQUEST['jenis'];

$input_awal = match ($jenis) {
    'combo' => "onload=\"document.getElementById('kelas').focus()\"",
    'text' => "onload=\"document.getElementById('nis').focus()\"",
    default => "onload=\"document.getElementById('kelas').focus()\"",
};	

OpenDb();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<title>Kelulusan Siswa [Menu]</title>
<script language = "javascript" type = "text/javascript">

function change_kelas() {	
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var tingkat = document.getElementById("tingkat").value;	
	var kelas = document.getElementById("kelas").value;	
		
	parent.alumni_menu.location.href = "alumni_menu.php?tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&departemen="+departemen+"&kelas="+kelas;
	parent.alumni_pilih.location.href = "blank_alumni.php";
}

function cari_siswa(){
	var nis = document.getElementById("nis").value;
	var nama = document.getElementById("nama").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var tingkat = document.getElementById("tingkat").value;
	var departemen = document.getElementById("departemen").value;
	var jenis = "text";
	
	if (nis.length == 0 && nama.length == 0){
		alert ("Masukkan NIS atau nama siswa untuk melakukan pencarian!");
		document.getElementById("nis").focus();
		return false;
	} else {	
		if ((nis.length < 3 && nis.length != 0) || (nama.length < 3 && nama.length != 0)){
			alert ('Keyword tidak boleh kurang dari 3 karakter');
			return false;
		}	
	}
	
	//alert ('Masuk');
	document.location.href="alumni_menu.php?pilihan=1&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&nis="+nis+"&nama="+nama+"&jenis="+jenis;	
	parent.alumni_pilih.location.href="alumni_pilih.php?pilihan=1&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&nisdicari="+nis+"&namadicari="+nama;

	//parent.alumni_content.location.href="alumni.php?departemen="+departemen+"&fromleft=1";
}

function lihat_siswa() {
	var kelas = document.getElementById("kelas").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var tingkat = document.getElementById("tingkat").value;
	var departemen = document.getElementById("departemen").value;
	var jenis = "combo";
	
	if (kelas.length == 0){
		alert ('Belum ada kelas yang aktif pada tingkat yang terpilih!');
		document.getElementById('kelas').focus();
		return false;
	}
	
	document.location.href="alumni_menu.php?pilihan=2&tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&kelas="+kelas+"&jenis="+jenis;
	parent.alumni_pilih.location.href="alumni_pilih.php?pilihan=2&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&jenis="+jenis;
	//parent.alumni_content.location.href="alumni.php?departemen="+departemen+"&fromleft=1";
}

function tampil_siswa() {
	var tingkat = document.getElementById("tingkat").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var departemen = document.getElementById("departemen").value;
	var jenis = "button";
	
	document.location.href="alumni_menu.php?pilihan=3&tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&jenis="+jenis;	
	parent.alumni_pilih.location.href="alumni_pilih.php?pilihan=3&tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&jenis="+jenis;
	//parent.alumni_content.location.href="alumni.php?departemen="+departemen+"&fromleft=1";
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
<body topmargin="0" leftmargin="0" style="background-color:#EEEEEE" <?=$input_awal?>>
<form name="menu" id="menu">
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>" />
<input type="hidden" name="jenis" id="jenis" value="<?=$jenis?>" />
<input type="hidden" name="pilihan" id="pilihan" value="<?=$pilihan?>" />
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>" />

	<fieldset><legend>Tampilkan daftar siswa berdasarkan</legend>
    <table width="100%" border="0" cellspacing="0">
   	<tr <?php if ($pilihan=="2") { ?> style="background-color:#C0C0C0" <?php } ?>>
    	<td align="center" valign="middle">
        <img src="../images/ico/titik.png" height="5" width="5" align="top"></td>
        <td width="20%">Kelas</td>
        <td colspan="2"><select name="kelas" id="kelas" style="width:145px;" onKeyPress="return focusNext('lihat', event)" onChange="change_kelas()">
        <?php 	OpenDb();
			$sql_kelas="SELECT k.replid,k.kelas FROM jbsakad.kelas k WHERE k.idtingkat='$tingkat' AND k.idtahunajaran='$tahunajaran' AND  k.aktif=1 ORDER BY k.kelas";
			$result_kelas=QueryDb($sql_kelas);

			while ($row_kelas=@mysqli_fetch_row($result_kelas)){
				if ($kelas=="")
					$kelas=$row_kelas[0];
		?>
          	<option value="<?=$row_kelas[0]?>"<?=IntIsSelected($row_kelas[0], $kelas) ?>>
          	<?=$row_kelas[1]?>
          	</option>
        <?php
			}
			CloseDb();
		?>
        	</select></td>
        <td><input type="button" class="but" name="lihat" id="lihat" value="Tampil" onclick="lihat_siswa()" style="width:70px;" onMouseOver="showhint('Tampilkan daftar siswa berdasarkan kelas!', this, event, '135px')"/></td>
	</tr>
    <tr <?php if ($pilihan=="1") { ?> style="background-color:#C0C0C0" <?php } ?>>
        <td align="center" valign="middle"><img src="../images/ico/titik.png" height="5" width="5" align="top"></td>
        <td>Pencarian</td> 
        <td width="10%"><strong>NIS</strong>&nbsp;</td>
        <td><input type="text" name="nis" id="nis" size="15" value="<?=$nis?>" onkeypress="return focusNext('cari', event)"/>&nbsp;</td>
        <td rowspan="2">
        <input type="button" class="but" name="cari" id="cari" value="Cari" onclick="cari_siswa();" style="width:70px;" onmouseover="showhint('Tampilkan daftar siswa berdasarkan pencarian!', this, event, '150px')"/></td>
    </tr>
    <tr <?php if ($pilihan=="1") { ?> style="background-color:#C0C0C0" <?php } ?>>
    	<td colspan="2"></td>
        <!--<td colspan="2"><strong>NIS</strong>&nbsp;<input type="text" name="nis" id="nis" size="15" value="<?=$nis?>" onkeypress="return focusNext('cari', event)"/>&nbsp;-->
        <td><strong>Nama</strong></td>
        <td><input type="text" name="nama" id="nama" size="15" value="<?=$nama?>" onkeypress="return focusNext('cari', event)"/>
       </td>
 	</tr>
    <tr <?php if ($pilihan=="3") { ?> style="background-color:#C0C0C0" <?php } ?>>
        <td align="center" valign="middle">
        <img src="../images/ico/titik.png" height="5" width="5" align="top"></td>
        <td colspan="3">Semua siswa yang aktif</td>
        <td><input type="button" class="but" name="tampil" id="tampil" value="Lihat" onclick="tampil_siswa()" style="width:70px;" onmouseover="showhint('Tampilkan semua siswa yang aktif!', this, event, '125px')"/></td>
 	</tr>
    </table> 
    </fieldset>
</form>
</body>
</html>
<script language="javascript">
	var spryselect2 = new Spry.Widget.ValidationSelect("kelas");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("nis");
	var sprytextfield2 = new Spry.Widget.ValidationTextField("nama");
</script>