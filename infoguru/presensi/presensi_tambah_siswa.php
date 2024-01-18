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
require_once('../include/theme.php');
require_once('presensi_get_siswa.php');

if (isset($_REQUEST['id']))
	$id = $_REQUEST['id'];	
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];	
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];	
//if (isset($_REQUEST['tahunajaran']))
//	$tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];	
if (isset($_REQUEST['total'])) 
	$total = $_REQUEST['total'];


//mudah();
if (isset($_REQUEST['simpan'])) {
	for ($i=1;$i<=10;$i++) {
		$nis = $_REQUEST['nis'.$i];
		$catatan = $_REQUEST['catatan'.$i];

		if ($nis <> "") {
			OpenDb();
			$sql = "INSERT INTO ppsiswa SET idpp=$id, nis='$nis', statushadir=0, catatan='$catatan' ";
			$result = QueryDb($sql);
			CloseDb();
			
			
		}		
	}	
	if ($result) {  ?>
		<script language="javascript">
			var id = <?=$id?>;			
			opener.parent.footer.location.href = "presensi_footer.php?replid="+id;
			window.close();
		</script>
<?php }
}

//$status = 0;
//$st = array('Hadir', 'Ijin', 'Sakit', 'Alpha', '(tidak ada data)');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS INFOGURU [Tambah Siswa Pada Presensi Pelajaran]</title>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function siswa(id) {
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	//newWindow('daftarsiswa.php?flag=0&departemen='+departemen+'&tingkat='+tingkat+'&tahunajaran='+tahunajaran+'&kelas='+kelas, 'Siswa','600','500','resizable=1,scrollbars=1,status=0,toolbar=0');
	newWindow('../library/siswa.php?flag=0&departemen='+departemen+'&tingkat='+tingkat+'&tahunajaran='+tahunajaran+'&id='+id, 'CariSiswa','600','600','resizable=1,scrollbars=1,status=0,toolbar=0');	//newWindow('daftarsiswa.php?flag=0&departemen='+departemen+'&tingkat='+tingkat+'&tahunajaran='+tahunajaran+'&kelas='+kelas, 'Siswa','600','500','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptSiswa(nis, nama, i) {
	//document.location.href="presensi_tambah_siswa.php?total="+nis;
	document.getElementById('nis'+i).value = nis;
	document.getElementById('nis_siswa'+i).value = nis;
	document.getElementById('nama'+i).value = nama;	
	if (i == 1)
		sendRequestText("../presensi/presensi_gettambah.php", show, "");	
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
    document.getElementById("tambah").innerHTML = x;
	
}

</script>
<style type="text/css">
<!--
.style1 {
	font-family: Arial;
	font-weight: bold;
	font-size: 14px;
}
-->
</style>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" background-image:url(../images/bgpop.jpg); background-repeat:repeat-x">
<form name="main" method="post" enctype="multipart/form-data" >
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>" />
<input type="hidden" name="flag" id="flag" value="<?=$flag?>" />
<input type="hidden" name="total" id="total" value="<?=$total?>" />
<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr height="25">
	<!--<td class="header" colspan="5" align="center">Tambah Siswa Pada Presensi Pelajaran</td>-->
    <td align="left" colspan="2"><font size="+2" style="background-color:#FF9900">&nbsp;&nbsp;</font>&nbsp;<span class="style1">Tambah Siswa Pada Presensi Pelajaran</span></td>
</tr>
    <!--<input type="button" name="pilih" class="but" id="cari" value="Cari Siswa" onclick="siswa()" /></td></tr>-->

<tr>
	<td colspan="2">
	<table width="100%" id="table" class="tab" align="center" cellpadding="2" cellspacing="0" border="1">
	<tr height="30">		
        <td class="header" align="center" width="5%"></td>
        <td class="header" align="center" width="10%">NIS</td>
        <td class="header" align="center" width="20%">Nama</td>
        <td class="header" align="center" width="65%">Catatan</td>
    </tr>
		       
	<?php 
    for ($j=1;$j<=10;$j++) { 
        //	$nis = $nis.$j;
        //	$nama = $nama.$j;	
        //	//echo 'nis '.$_REQUEST['nis1'];
        //	$nisa = $_REQUEST['nis'.$j];
        //	echo 'nis '.$nisa;				
    ?>
    
    <tr height="25">        			
        <td align="center">
        
        <a href="JavaScript:siswa(<?=$j?>)">
        <!--<a href="JavaScript:hapus()" title="Hapus">--><img src="../images/ico/cari.png" border="0"></a>
        </td>
        <td align="center">
        <input type="text" name="nis_siswa<?=$j?>" id="nis_siswa<?=$j?>" readonly class="disabled" size="10" onClick="siswa(<?=$j?>)"/>          
        <input type="hidden" name="nis<?=$j?>" id="nis<?=$j?>" />          
        </td>
        <td><input type="text" name="nama<?=$j?>" id="nama<?=$j?>" size="30" readonly class="disabled" onClick="siswa(<?=$j?>)" /></td>
        
       <td align="center">
       <input type="text" name="catatan<?=$j?>" id="catatan<?=$j?>" size="65" value="<?=$catatan?>" /></td>
       
    </tr>
    
<?php } ?>
    </table>
    <script language='JavaScript'>
        Tables('table', 1, 0);
    </script>
	
</td></tr>
<tr height="30">
	<td align="right" id="tambah" width="50%">
   		
    </td>
    <!--<input type="submit" name="simpan" value="Simpan" class="but" />-->
    <td width="50%">
    <input type="button" class="but" name="tutup" id="tutup" value="Tutup" onClick="window.close()" /></td>
</tr>	
<!-- END OF TABLE UTAMA -->
</table>

</form>
</body>
</html>