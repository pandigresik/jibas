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

$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['kelompok'])) 
	$kelompok = $_REQUEST['kelompok'];
if (isset($_REQUEST['proses'])) 
	$proses = $_REQUEST['proses'];
if (isset($_REQUEST['angkatan'])) 
	$angkatan = $_REQUEST['angkatan'];	
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];	
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['no']))
	$no = $_REQUEST['no'];
if (isset($_REQUEST['nama']))
	$nama = $_REQUEST['nama'];
if (isset($_REQUEST['warna']))
	$warna = $_REQUEST['warna'];
if (isset($_REQUEST['cari']))
	$cari = $_REQUEST['cari'];

$input_awal = match ($cari) {
    'tampil' => "onload=\"document.getElementById('kelompok').focus()\"",
    'cari' => "onload=\"document.getElementById('no').focus()\"",
    default => "onload=\"document.getElementById('kelompok').focus()\"",
};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Penempatan Calon Siswa</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function refresh_menu() {	
	var proses = document.getElementById("proses").value;
	var departemen = document.getElementById("departemen").value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var cari = document.getElementById('cari').value;	
	var warna = "C0C0C0";
		
	document.location.href = "penempatan_menu.php?proses="+proses+"&departemen="+departemen+"&warna="+warna+"&cari="+cari+"&warna="+warna+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas;
}

function tampil(cari) {
	var kelompok = document.getElementById("kelompok").value;	
	var proses = document.getElementById("proses").value;
	var departemen = document.getElementById("departemen").value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var warna = "C0C0C0";
	
	document.location.href = "penempatan_menu.php?kelompok="+kelompok+"&proses="+proses+"&departemen="+departemen+"&warna="+warna+"&cari="+cari;
	parent.daftar.location.href = "penempatan_daftar.php?kelompok="+kelompok+"&proses="+proses+"&departemen="+departemen+"&aktif=1&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&cari="+cari;
	parent.isi.location.href = "penempatan_content.php?kelompok="+kelompok+"&proses="+proses+"&departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&aktif=1&cari="+cari+"&warna="+warna;
	//parent.isi.document.getElementById('angkatan').focus();
}

function change_kelompok() {
	var kelompok = document.getElementById("kelompok").value;
	var proses = document.getElementById("proses").value;
	var departemen = document.getElementById("departemen").value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	
	document.location.href = "penempatan_menu.php?kelompok="+kelompok+"&proses="+proses+"&departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas;
	parent.daftar.location.href = "blank_penempatan_daftar.php";
	//document.location.href = "penempatan_menu.php?kelompok="+kelompok+"&proses="+proses+"&departemen="+departemen;
}

function carilah(cari) {
	var nama = document.getElementById("nama").value;	
	var no = document.getElementById("no").value;	
	var proses = document.getElementById("proses").value;
	var departemen = document.getElementById("departemen").value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var warna = "C0C0C0";
	
	if (no.length == 0 && nama.length == 0){
		alert ("Masukkan no pendaftaran atau nama calon untuk melakukan pencarian!");
		document.getElementById("no").focus();
		return false;
	} 	
	
	document.location.href = "penempatan_menu.php?proses="+proses+"&departemen="+departemen+"&no="+no+"&nama="+nama+"&warna="+warna+"&cari="+cari;
	parent.daftar.location.href = "penempatan_daftar.php?proses="+proses+"&departemen="+departemen+"&aktif=1&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&no="+no+"&nama="+nama+"&cari="+cari;
	parent.isi.location.href = "penempatan_content.php?proses="+proses+"&departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&aktif=1&no="+no+"&nama="+nama+"&lihat=0&cari="+cari+"&warna="+warna;		
}

function lihat(cari) {
	var proses = document.getElementById("proses").value;
	var departemen = document.getElementById("departemen").value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var warna = "C0C0C0";
	
	document.location.href = "penempatan_menu.php?proses="+proses+"&departemen="+departemen+"&warna="+warna+"&cari="+cari;
	parent.daftar.location.href = "penempatan_daftar.php?proses="+proses+"&departemen="+departemen+"&aktif=1&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&cari="+cari;
	parent.isi.location.href = "penempatan_content.php?proses="+proses+"&departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&aktif=1&cari="+cari+"&warna="+warna;		
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

function panggil(elem){
	var lain = new Array('kelompok','no','nama');	
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
<body topmargin="0" leftmargin="0" style="background-color:#EEEEEE" <?=$input_awal?>>
<form name="penempatan_menu">
<input type="hidden" name="proses" id="proses" value="<?=$proses ?>" />
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="angkatan" id="angkatan" value="<?=$angkatan ?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran ?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat ?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>" />
<input type="hidden" name="cari" id="cari" value="<?=$cari ?>" />


    <fieldset><legend>Tampilkan daftar calon siswa berdasarkan</legend>  
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr style="background-color:#<?php if ($cari == 'tampil') echo $warna?>">
    	<td valign="middle" width="10"><img src="../images/ico/titik.png" height="5" width="5" align="top"></td>
        <td width="22%"> Kelompok </td>
    	<td width="*">
        <select name="kelompok" id="kelompok" onchange="change_kelompok()" style="width:225px;" onKeyPress="return focusNext('cari', event)" onfocus="panggil('kelompok')">
    <?php OpenDb();
		$sql = "SELECT replid, kelompok FROM jbsakad.kelompokcalonsiswa WHERE idproses='$proses' ORDER BY kelompok ASC";	
		$result = QueryDb($sql);
		while($row = @mysqli_fetch_array($result)) {
			if ($kelompok == "")
				$kelompok = $row['replid'];
	?>
    		<option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $kelompok) ?>>
              <?=$row['kelompok']?>
            </option>
            <?php
	} //while
	CloseDb();
	?>
    	</select>
    	</td>
    	<td width="50">
    	<input type="button" name="cari" id="cari" value="Tampil" class="but" onclick="tampil('tampil')" style="width:70px;" onmouseover="showhint('Tampilkan daftar calon siswa berdasarkan kelompok!', this, event, '135px')"/>
    	</td>
    </tr>
    <tr>
    	<td valign="middle"><img src="../images/ico/titik.png" height="5" width="5" align="top"></td>
        <td>Pencarian</td>
    </tr>
    <tr style="background-color:#<?php if ($cari == 'cari') echo $warna?>">    	
    	<td></td>
        <td><b>No Pendaftaran</b></td>
        <td>
        	<input type="text" name="no" id="no" size="10" value="<?=$no?>" onKeyPress="return focusNext('cari3', event)" onfocus="panggil('no')"/>
        	<b>Nama</b>&nbsp;<input type="text" name="nama" id="nama" size="20" value="<?=$nama?>" onKeyPress="return focusNext('cari3', event)" onfocus="panggil('nama')"/>
             
           </td>
            
        <td>
        	<input type="button" name="cari3" id="cari3" value="Cari" class="but" onclick="carilah('cari')" style="width:70px;" onmouseover="showhint('Tampilkan daftar calon siswa berdasarkan pencarian!', this, event, '135px')"/>					
        </td>
   	</tr>
   
    <tr style="background-color:#<?php if ($cari == 'lihat') echo $warna?>">
    	<td valign="middle"><img src="../images/ico/titik.png" height="5" width="5" align="top"></td>
    	<td colspan="2">Semua calon siswa yang belum memiliki kelas</td>
    	<td>
        	<input type="button" name="cari2" id="cari2" value="Lihat" class="but" onclick="lihat('lihat')" style="width:70px;" onmouseover="showhint('Tampilkan semua calon siswa yang belum memiliki kelas!', this, event, '135px')"/>
        </td>
    </tr>
    </table>
    </fieldset>
   </form>
</body>
</html>