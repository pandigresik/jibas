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
require_once('presensi_get_siswa.php');

global $nisa;
if (isset($_REQUEST['aktif']))
	$aktif = $_REQUEST['aktif'];	
echo 'aktif '.$aktif;
if (isset($_REQUEST['id']))
	$id = $_REQUEST['id'];	
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];	
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];	
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];	
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];	
if (isset($_REQUEST['total'])) {
	$total = $_REQUEST['total'];
}
//mudah();

if ($aktif) {
	//echo 'halooo lagi masuk nih';
	echo 'ada '.$_REQUEST['nis1'];
	//lagi();
	//lagi();
	//echo 'nisa '.$nisa[1];
	//echo '<br> '.$_REQUEST['nisa'][2];
	//echo '<br> '.$_REQUEST['nisa'][3];
	//echo '<br> '.$nisa[4];
	
	//lagi();
	////coba();
	//echo '<br> nisa'.$_REQUEST['nis'][1];
	//foreach ($_REQUEST['nis'] as $value) {
	//	echo '<br> nis'.$value;
	//}
}

$status = 0;
$st = ['Hadir', 'Ijin', 'Sakit', 'Alpha', '(tidak ada data)'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Tambah Siswa Pada Presensi Pelajaran]</title>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function siswa() {
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	newWindow('daftarsiswa_coba.php?flag=0&departemen='+departemen+'&tingkat='+tingkat+'&tahunajaran='+tahunajaran+'&kelas='+kelas, 'Siswa','600','500','resizable=1,scrollbars=1,status=0,toolbar=0');
	//newWindow('daftarsiswa.php?flag=0&departemen='+departemen+'&tingkat='+tingkat+'&tahunajaran='+tahunajaran+'&kelas='+kelas, 'Siswa','600','500','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptSiswa(nis, nama, i) {	
	//sendRequestText("presensi_get_siswa.php", show(), "nis"+i+"="+nis+"&nama"+i+"="+nama+"&i="+i);
	sendRequestText("presensi_get_siswa.php", show, "nis"+i+"="+nis+"&nama"+i+"="+nama+"&i="+i);
	
	alert ('ada siswa dan pilih'+nis+' '+nama);
	//document.location.href="presensi_tambah_siswa.php?total="+nis;
	//document.getElementById('nis'+i).value = nis;
	//document.getElementById('nama'+i).value = nama;
}

function tambah() {		
	alert ('udah kepilih');
	//var siswa = document.getElementById("siswa").value;
	//var pilih = document.getElementById("pilih").value;
	window.close();
	//opener.location.href = "../presensi/presensi_tambah_siswa.php?simpan=simpan&siswa="+siswa+"&pilih="+pilih;
	//window.close();
}

function show(x) {
    document.getElementById("Info").innerHTML = x;
	
}

</script>
</head>
<body>
<form name="main" method="post" enctype="multipart/form-data" onSubmit="return validate()">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>" />
<input type="hidden" name="flag" id="flag" value="<?=$flag?>" />
<input type="hidden" name="total" id="total" value="<?=$total?>" />

<?="ada total ".$total. " departemen ". $departemen; ?>

<table border="0" cellpadding="5" cellspacing="5" width="100%" align="center">
<!-- TABLE UTAMA -->
<tr>
  <td><strong>Tambah Siswa pada Presensi Pelajaran</strong>&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" name="pilih" class="but" id="cari" value="Cari Siswa" onclick="siswa()" /></td></tr>

<tr>
<td align="left">
	<table border="0" width="100%" id="table" class="tab">
		
        <tr>		
			<td class="header" align="center" width="5%"></td>
			<td class="header" align="center" width="10%">NIS</td>
			<td class="header" align="center" width="25%">Nama</td>
            <td class="header" align="center" width="5%">Presensi</td>
            <td class="header" align="center" width="53%">Catatan</td>
		</tr>
		<div id = "Info">
        	
        </div>
        
		<?php 
		for ($j=1;$j<=$total;$j++) { 
			//	$nis = $nis.$j;
			//	$nama = $nama.$j;	
			//	//echo 'nis '.$_REQUEST['nis1'];
			//	$nisa = $_REQUEST['nis'.$j];
			//	echo 'nis '.$nisa;				
		?>
        
        <tr>        			
			<td align="center">
            
            <!--<a href="#null" onClick="newWindow('../library/daftarsiswa.php','Cari Siswa','520','450','resizable=0,scrollbars=1,status=0,toolbar=0');">-->
            <a href="JavaScript:hapus()" title="Hapus"><img src="../images/ico/hapus.png" border="0"></a>
            </td>
			<td align="center">
            <input type="text" name="nis<?=$j?>" id="nis<?=$j?>" readonly style="background-color:#CCCCCC" size="10" />          
            <input type="hidden" name="nis<?=$j?>" id="nis<?=$j?>"  />          
            </td>
  			<td><input type="text" name="nama<?=$j?>" id="nama<?=$j?>" size="20" readonly style="background-color:#CCCCCC" value = "" /></td>
           	<td><select name="status<?=$cnt?>" > 
                <?php for ($i=0;$i<5;$i++){ ?>
           		<option value=<?=$i?> <?=IntIsSelected($i, $status) ?>><?=$st[$i]?></option>
            	<?php } ?>
           		</select></td>
           <td align="center">
           <input type="text" name="catatan<?=$cnt?>" id="catatan" size="80" value="<?=$catatan?>" /></td>
           
     	</tr>
        
<?php } ?>
 		</table>
		<script language='JavaScript'>
   			Tables('table', 1, 0);
		</script>
	
</td></tr>
<tr height="30">
	<td align="center">
    <input type="submit" name="simpan" value="Simpan" class="but" />
    <input type="button" class="but" name="tutup" id="tutup" value="Tutup" onClick="parent.tutup()" /></td>
</tr>	
<!-- END OF TABLE UTAMA -->
</table>
</form>
</body>
</html>