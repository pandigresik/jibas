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
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');

if(isset($_REQUEST['departemen'])){
	$departemen = $_REQUEST['departemen'];
}

if(isset($_REQUEST['kelas'])){
	$kelas = $_REQUEST['kelas'];
}
if(isset($_REQUEST['jenis_penilaian'])){
	$jenis = $_REQUEST['jenis_penilaian'];
}
if(isset($_REQUEST['pelajaran'])){
	$pelajaran = $_REQUEST['pelajaran'];
}
if(isset($_REQUEST['semester'])){
	$semester = $_REQUEST['semester'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cari Siswa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/mainmenu.css" type="text/css">

<script language="javascript">

function changeSel(rno) {
	document.main.selected.value = rno;
}

function tekan() {
	var rno = document.main.selected.value;
	var departemen = document.main.departemen.value;
	var kelas = document.main.kelas.value;
	var jenis = document.main.jenis.value;
	var pelajaran = document.main.pelajaran.value;
	var semester = document.main.semester.value;
	
	//alert(rno);
	if (rno.length == 0) {
			alert('Anda belum menentukan Siswa!');
			return false;
		}
	eval("nis = document.main.nis" + rno + ".value;");
	eval("nama = document.main.nama" + rno + ".value;");
	
	document.location.href = "tambah_siswa_pp2.php?nis="+nis+"&nama="+nama+"&departemen="+departemen+"&kelas="+kelas+"&jenis="+jenis+"&pelajaran="+pelajaran+"&semester="+semester;

}

function change_sel() {
     var departemen = document.main.departemen.value;
     document.location.href="carisiswa.php?departemen="+departemen;
}
</script>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
</head>

<body topmargin="0" leftmargin="10" marginheight="0" marginwidth="10"><br>
<?php
openDB();
?>
<form action="tambah_siswa_pp.php" method="post" name="main" onSubmit="return tekan()">
<input type="hidden" name="selected">
<input type="hidden" value="<?=$departemen ?>" name="departemen">
<input type="hidden" value="<?=$kelas ?>" name="kelas">
<input type="hidden" value="<?=$jenis ?>" name="jenis">
<input type="hidden" value="<?=$pelajaran ?>" name="pelajaran">
<input type="hidden" value="<?=$semester ?>" name="semester">
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td class="header">Pilih Siswa</td>
		<td class="header" align="right">Langkah 1 dari 2</td>
	</tr>
</table>

<?php

		$query ="SELECT siswa.nis, siswa.nama FROM jbsakad.siswa ".
		        "WHERE idkelas = '$kelas' ".
				"AND siswa.aktif = '1' ".
				"AND NOT nis IN ".
				"(SELECT DISTINCT nis FROM ujian, nilaiujian ".
				"WHERE ujian.replid = nilaiujian.idujian ".
				"AND idpelajaran = '$pelajaran' ".
				"AND idkelas = '$kelas' ".
				"AND idsemester = '$semester' ".
				"AND idjenis = '$jenis' )";
	
	$result_sis = QueryDb($query) or die (mysqli_error($mysqlconnection));
	?>
	<p>
		<table border='1' cellspacing='0' cellpadding='0' bordercolor='#5A7594' width='100%'>
			<tr>
			  <td>
			 	<table border='1' width='100%' id="table" class="tab">
					<tr>
						<td class='header' align='center' height='30'>NIS</td>
						<td class='header' height='30'>Nama</td>
	<?php
	$jml_data = @mysqli_num_rows($result_sis);
	
	if($jml_data=="0"){
		?>
		<tr>
				<td colspan='3' align='center'>Data Siswa Tidak Ada</td>
		</tr>
		<?php 
	}else{	
	$cnt = 0;
	while($row = @mysqli_fetch_array($result_sis)){
	?>
	<tr <?="bgcolor=#".($cnt%2?"ffffff":"EAECEE").""; ?>>
		<td class='data'><input type="hidden" name="nis<?=$cnt; ?>" value="<?=$row['nis']; ?>">
		<input type="hidden" name="nama<?=$cnt; ?>" value="<?=$row['nama']; ?>"><input name='siswa' type='radio' value='<?=$row['nis']; ?>' onclick='changeSel(<?=$cnt; ?>)' width='30%'><?=$row['nis']; ?></td>
		<td width='70%'class='data'><?=$row['nama']; ?></td>
	</tr>
	<?php
	$cnt++;
	}
	CloseDb();
	?>
	<tr>
		<td colspan='3' align="right">
		<input type='button' class='but' value='Pilih >>' name='pilih' onClick="tekan()"></td>
		</form>
		</tr>
	</table>
	 <script language='JavaScript'>
            Tables('table', 1, 0);
     </script> 
   </td>
  </tr>
</table>
<?php
if(isset($_POST['pilih'])){
	?>
	<script language="javascript">
		document.location.href = "tambah_siswa_pp2.php?nim<?=$_POST['nim'] ?>";
	</script>
	<?php
	}
}	
?>
</body>
</html>