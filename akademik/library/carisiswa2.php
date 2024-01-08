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

require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

if(isset($_GET['departemen'])){
	$departemen = $_GET['departemen'];
}elseif(isset($_POST['departemen'])){
	$departemen = $_POST['departemen'];
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cari Siswa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../style/style.css" type="text/css">

<script language="javascript">
function validate(){
var test;
	test = document.main.cr_nis.value;
	test2 = document.main.cr_nama.value;
	
	if(test.length == 0 && test2.length == 0){
		alert("NIS atau nama harus dimasukkan");
		document.main.cr_nis.focus();
		return false;
		}
	if(test.length < 3 && test2.length < 3){
		alert("NIS atau nama harus minimal 3 karakter");
		document.main.cr_nis.focus();
		return false;
		}
}
function changeSel(rno) {
	document.main.selected.value = rno;
}

function tekan() {
	var rno = document.main.selected.value;
	//alert(rno);
	if (rno.length == 0) {
			alert('Anda belum menentukan Siswa!');
			return false;
		}
	eval("nis = document.main.nis" + rno + ".value;");
	eval("nama = document.main.nama" + rno + ".value;");
	
	//alert(nama);
	opener.acceptSiswa(nis,nama);
	window.close();
}

function change_sel() {
     var departemen = document.main.departemen.value;
     document.location.href="carisiswa2.php?departemen="+departemen;
}
</script>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style2 {color: #FFFF00}
-->
</style>
</head>

<body topmargin="0" leftmargin="10" marginheight="0" marginwidth="10"><br>
<?php
openDB();
?>
<form action="carisiswa2.php" method="post" name="main" onSubmit="return validate()">
<input type="hidden" name="selected">
<table>
  <tr>
		<td height="30" background="../style/formbg2agreen.gif" onMouseOver="background='../style/formbg2agreen.gif'" onMouseOut="background='../style/formbg2agreen.gif'"><span class="style1">Cari Siswa</span></td>
	  <td height="30" background="../style/formbg2.gif" onMouseOver="background='../style/formbg2agreen.gif'" onMouseOut="background='../style/formbg2.gif'"><a href="pilihsiswa2.php" class="style2">Pilih Siswa</a></td>
	</tr>
</table>
  <fieldset><legend><b>Cari Siswa Berdasarkan</b></legend>
			<!--  BEGIN TABLE FORM -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
				<td width="150">Departemen</td>	
				<td colspan="2">
				<select name="departemen">
				<?php
				
					$query_dep = "SELECT departemen FROM jbsakad.departemen ORDER BY urutan ASC";
					$result_dep = QueryDb($query_dep);
				
				$i = 0;
				while($row_dep = @mysqli_fetch_array($result_dep)){
					if($departemen == $row_dep['departemen']){
						$sel[$i] = "selected";
					}else{
						$sel[$i] = "";
					}
				?>
					<option value="<?=$row_dep['departemen'] ?>" <?=$sel[$i] ?>><?=$row_dep['departemen'] ?></option>
				<?php
					$i++;
					}
				?>
				</select></td>
			  </tr>			  
			  <tr >
				<td valign="middle">NIS</td>		
				<td width="153"><input type="text" name="cr_nis" value="<?=$_POST['cr_nis'] ?>"></td>
				<td width="33">Nama</td>
				<td width="154"><input type="text" name="cr_nama" value="<?=$_POST['cr_nama'] ?>"></td>
				<td width="10">&nbsp;</td>
				<td width="57"><input type="submit" name="cari" value="Cari" class="but"></td>
			  </tr>
			   <tr >
				 <td colspan="10" class="titlemenu">&nbsp;</td>
			   </tr>
			</table>
			<!-- END TABLE FORM -->
	</fieldset>
<?php
if ((isset($_POST["cari"]))){
 	if((trim($_POST['cr_nis']!="")) && (trim($_POST['cr_nama']==""))) {
		$selectSQL ="SELECT siswa.nis, siswa.nama, kelas.kelas FROM jbsakad.siswa, jbsakad.kelas ".
		            "WHERE siswa.nis LIKE '". $_POST['cr_nis']."%' ".
					"AND siswa.aktif = '1' ".
					"AND siswa.idkelas = kelas.replid ".
					"AND kelas.departemen = '$departemen' ORDER BY nama";
	}elseif((isset($_POST["cari"])) && (trim($_POST['cr_nama']!="")) && (trim($_POST['cr_nis']==""))){
		$selectSQL ="SELECT siswa.nis, siswa.nama, kelas.kelas FROM jbsakad.siswa,  jbsakad.kelas ".
		            "WHERE siswa.nama LIKE '". $_POST['cr_nama']."%' ".
					"AND siswa.aktif = '1' ".
					"AND siswa.idkelas = kelas.replid ".
					"AND kelas.departemen = '$departemen' ORDER BY nama";
	}elseif((isset($_POST["cari"])) && (trim($_POST['cr_nama']!="")) && (trim($_POST['cr_nis']!=""))){
		$selectSQL ="SELECT siswa.nis, siswa.nama, kelas.kelas FROM jbsakad.siswa, jbsakad.kelas ".
		            "WHERE siswa.nama LIKE '". $_POST['cr_nama']."%' AND siswa.nis LIKE '".$_POST['cr_nis']."%' ".
					"AND siswa.aktif = '1' ".
					"AND siswa.idkelas = kelas.replid ".
					"AND kelas.departemen = '$departemen' ORDER BY nama";

	}elseif((isset($_POST["cari"])) && (trim($_POST['cr_nis']=="")) && (trim($_POST['cr_nama']==""))) {
		$selectSQL ="SELECT nis, nama FROM jbsakad.siswa WHERE nama='x'";
	}
	
	//echo $selectSQL;
	$result_sis = QueryDb($selectSQL) or die (mysqli_error($mysqlconnection));
	?>
	<p><table border='1' cellspacing='0' cellpadding='0' bordercolor='#5A7594' width='100%'>
			<tr>
			  <td>
			 	<table border='1' width='100%' id="table" class="tab">
					<tr>
						<td class='header' align='center'>NIS</td>
						<td class='header'>Nama</td>
						<td class='header'>Kelas</td>	
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
		<td width='50%'class='data'><?=$row['nama']; ?></td>
		<td width='20%'class='data'><?=$row['kelas']; ?></td>
	</tr>
	<?php
	$cnt++;
	}
	CloseDb();
	?>
	<tr>
		<td colspan='3' align="right">
		<input type='button' class='but' value='Pilih >>' name='pilih' onclick='tekan()'></td>
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
	}
}	
?>
</body>
</html>