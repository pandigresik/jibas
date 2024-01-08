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
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once("../include/theme.php");

$tahunajaran = $_REQUEST['tahunajaran'];
$deskripsi=CQ($_REQUEST['deskripsi']);
$tglmulai=$_REQUEST['tglmulai'];
$tglakhir=$_REQUEST['tglakhir'];

if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];

if ($op=="simpan"){
	$tglmulaisimpan = TglDb($tglmulai);
	$tglakhirsimpan = TglDb($tglakhir);

	OpenDb();
	$sql_simpan_cek="SELECT * FROM jbsakad.infojadwal WHERE deskripsi='$deskripsi' AND tglmulai='$tglmulaisimpan' AND 	tglakhir='$tglakhirsimpan' AND idtahunajaran = '".$tahunajaran."'";  
	$result_simpan_cek=QueryDb($sql_simpan_cek);	
	if ($row_simpan_cek=@mysqli_num_rows($result_simpan_cek)){
	?>
		<SCRIPT type="text/javascript" language="javascript">
			alert ('Duplikasi Data, data sudah digunakan !');
			document.location.href="tambah_info_jadwal.php?op=simpan&deskripsi="+deskripsi+"&tglmulai="+tglmulai+"&tglakhir="+tglakhir;
		</SCRIPT>
	<?php
	} else {
		$sql_simpan="INSERT INTO jbsakad.infojadwal SET deskripsi='$deskripsi', tglmulai='$tglmulaisimpan', tglakhir='$tglakhirsimpan', idtahunajaran = '".$tahunajaran."'";  
		$result_simpan=QueryDb($sql_simpan);
		if ($result_simpan){
			$sql_simpan_ambil="SELECT LAST_INSERT_ID(replid) FROM infojadwal ORDER BY replid DESC LIMIT 1";  
			$result_simpan_ambil=QueryDb($sql_simpan_ambil);
			$row_simpan_ambil=@mysqli_fetch_row($result_simpan_ambil)
		?>
		<SCRIPT type="text/javascript" language="javascript">
			parent.opener.refresh_setelah_add(<?=$row_simpan_ambil[0]?>);
			window.close();
		</SCRIPT>
		<?php
		}
	}	
	CloseDb();
}

?>
<html>
<head>
<title>Tambah Info Jadwal</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<SCRIPT type="text/javascript" language="text/javascript" src="../script/tables.js"></SCRIPT>
<SCRIPT type="text/javascript" language="javascript" src="../script/common.js"></script>
<SCRIPT type="text/javascript" language="javascript" src="../script/ajax.js"></script>
<SCRIPT type="text/javascript" language="javascript" src="../script/tools.js"></script>
<SCRIPT type="text/javascript" language="javascript" src="../script/validasi.js"></script>
<SCRIPT type="text/javascript" language="javascript" src="../script/cal2.js"></script>
<SCRIPT type="text/javascript" language="javascript" src="../script/cal_conf2.js"></script>
<style type="text/css">
	.box {
		background-color:white;
		border-top: solid black 1px;
		border-bottom: solid black 1px;
		border-left: solid black 1px;
		border-right: solid black 1px;
	}
</style>
<SCRIPT type="text/javascript" language="javascript">
function simpan(){
	
	var tglmulai=document.main.tglmulai.value;
	var tglakhir=document.main.tglakhir.value;
	var deskripsi=document.main.deskripsi.value;
	var tahunajaran=document.main.tahunajaran.value;
	
	if (deskripsi==""){
		alert ('Informasi jadwal tidak boleh kosong !');
		document.main.deskipsi.focus();
		return false;
	}
	if (tglmulai==""){
		alert ('Tanggal mulai tidak boleh kosong !');
		document.main.tglmulai.focus();
		return false;
	}
	if (tglakhir==""){
		alert ('Tanggal selesai tidak boleh kosong !');
		document.main.tglakhir.focus();
		return false;
	}
	
	document.location.href="tambah_info_jadwal.php?op=simpan&deskripsi="+deskripsi+"&tglmulai="+tglmulai+"&tglakhir="+tglakhir+"&tahunajaran="+tahunajaran;
	
	}
	</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
	<form name="main" id="main" action="tambah_info_jadwal.php">
    <input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>">
	<table width="350px" style="border: 1px solid black; background-color:white ">
		<tr>
		  <td colspan="2" class="header"><div align="center">Input Info Jadwal</div></td>
		</tr>
		<tr>
			<td>Informasi Jadwal</td>
			<td><input type="text" name="deskripsi" id="deskripsi" style="width:200px " value="<?=$deskripsi?>"></td>
		</tr>
		
		<tr>
			<td>Periode</td>
			<td>
				<input type="text" name="tglmulai" id="tglmulai" style="width:80px" value="<?=$tglmulai?>">
				<a href="javascript:showCal('Calendartglmulai')"><img src="../images/calendar.jpg" border="0"></a>
				S/D
				<input type="text" name="tglakhir"  id="tglakhir" style="width:80px" value="<?=$tglakhir?>">
				<a href="javascript:showCal('Calendartglakhir')"><img src="../images/calendar.jpg" border="0"></a>
			</td>
		</tr>
		
		<tr>
			<td colspan="2" align="center">
				<input type="button" name="Simpan" value="Simpan" class="but" onClick="simpan()">
				<input type="button" name="Tutup" value="Tutup" class="but" onClick="window.close()">
			</td>
		</tr>
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
</html>