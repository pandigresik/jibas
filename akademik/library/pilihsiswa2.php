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
if(isset($_GET['tingkat'])){
	$tingkat = $_GET['tingkat'];
}elseif(isset($_POST['tingkat'])){
	$tingkat = $_POST['tingkat'];
}
if(isset($_GET['kelas'])){
	$kelas = $_GET['kelas'];
}elseif(isset($_POST['kelas'])){
	$kelas = $_POST['kelas'];
}
if(isset($_REQUEST['tahun_ajaran'])){
	$tahun_ajaran = $_REQUEST['tahun_ajaran'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Plih Siswa</title>
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
     document.location.href="pilihsiswa2.php?departemen="+departemen;
}
function change_sel2() {
     var departemen = document.main.departemen.value;
     var tingkat = document.main.tingkat.value;	
	 var tahun = document.main.tahun_ajaran.value; 
     document.location.href="pilihsiswa2.php?departemen="+departemen+"&tingkat="+tingkat+"&tahun_ajaran="+tahun;
}
</script>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="script/tooltips.js"></script>

<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style2 {
	color: #FFFFFF
}
-->
</style>
</head>

<body topmargin="0" leftmargin="10" marginheight="0" marginwidth="10" onload="init();"><br>
<?php
openDB();
?>
<form action="pilihsiswa2.php" method="post" name="main" onSubmit="return validate()">
<input type="hidden" name="selected">
<table>
  <tr>
		<td height="30" background="../style/formbg2.gif" onMouseOver="background='../style/formbg2agreen.gif'" onMouseOut="background='../style/formbg2.gif'"><a href="carisiswa2.php" class="headerlink style2">Cari Siswa</a></td>
		<td height="30" background="../style/formbg2agreen.gif" onMouseOver="background='../style/formbg2agreen.gif'" onMouseOut="background='../style/formbg2agreen.gif'"><span class="style1">Pilih Siswa</span></td>
	</tr>
</table>
  <fieldset><legend><b>Pilih Siswa Berdasarkan</b></legend>
			<!--  BEGIN TABLE FORM -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" >
			  <tr>
				<td>Departemen</td>
				<td colspan="2">
				<select name="departemen" onChange="change_sel()" style="width:100px ">
				<?php
		
				$query_dep = "SELECT departemen FROM jbsakad.departemen ORDER BY urutan ASC";
				$result_dep = QueryDb($query_dep);
		
				$i=0;
				while($row_dep = @mysqli_fetch_array($result_dep)){
				  if($departemen == ""){
						$departemen = $row_dep['departemen'];
						$sel[$i] = "selected";
                }elseif($departemen == $row_dep['departemen']){
						$sel[$i] = "selected";
					}
				?>
					<option value="<?=$row_dep['departemen'] ?>" <?=$sel[$i] ?>><?=$row_dep['departemen'] ?></option>
				<?php
				$i++;
					}
					
			$query_thn = "SELECT replid, tahunajaran FROM jbsakad.tahunajaran ".
						 "WHERE tahunajaran.departemen = '$departemen' ".
						 "AND tahunajaran.aktif = '1'";
            $result_thn = QueryDb($query_thn);
			$row_thn = @mysqli_fetch_array($result_thn);
			$rep = $row_thn['replid'];
				?>
				</select></td>
            <td>Tahun Ajaran</td>
             <td><input type="text" name="tahun_ajaran" value="<?=$row_thn['tahunajaran'] ?>" readonly size="25">
           </td>
			  </tr>
			  <tr>
			  	<td>Tingkat</td>
				<td colspan="2">
					<select name="tingkat" onChange="change_sel2();" style="width:100px ">
					<?php
					$query_tkt = "SELECT * FROM jbsakad.tingkat WHERE tingkat.departemen = '$departemen' AND aktif='1'";					
					$result_tkt = QueryDb($query_tkt);

					$i=0;
					while($row_tkt = mysqli_fetch_array($result_tkt)){
						if($tingkat == ""){
							$tingkat = $row_tkt['replid'];
							$sel[$i] = "selected";
						}elseif($tingkat == $row_tkt['replid']){
							$sel[$i] = "selected";
						}else{
							$sel[$i] = "";
						}
					?>
					<option value="<?=$row_tkt['replid'] ?>" <?=$sel[$i] ?>><?=$row_tkt['tingkat'] ?></option>
					<?php
					$i++;
					}
					?>
					</select>	

				</td>
				<td>Kelas</td>
				<td><select name="kelas" style="width:100px ">
				<?php
				$query_kls = "SELECT * FROM jbsakad.kelas WHERE kelas.departemen = '$departemen' ".
							 "AND kelas.idtingkat = '$tingkat' AND idtahunajaran = '".$row_thn['replid']."' ORDER BY kelas.kelas ASC";
				$result_kls = QueryDb($query_kls);
				$i=0;
				while($row_kls = mysqli_fetch_array($result_kls)){
					if($kelas == $row_kls['replid']){
						$sel[$i] = "selected";
					}else{
						$sel[$i] = "";
					}
				?>
				<option value="<?=$row_kls['replid'] ?>" <?=$sel[$i] ?>><?=$row_kls['kelas'] ?></option>
				<?php
				$i++;
				}
				?>
				</select></td>
				<td>
				<input type="submit" name="cari" value="Cari" class="but">
				</td>
			  </tr>
			  
			   <tr >
				 <td colspan="10" class="titlemenu">&nbsp;</td>
			   </tr>
			</table>
			<!-- END TABLE FORM -->
	</fieldset>

<?php
if ((isset($_POST["cari"]))){
		$selectSQL ="SELECT siswa.nis, siswa.nama FROM jbsakad.siswa ".
		            "WHERE siswa.idkelas = '$kelas' ".
					"AND siswa.aktif = '1' ";
		$result_sis = QueryDb($selectSQL);
	
	//echo $selectSQL;
	
	?>
	<p><table border='1' cellspacing='0' cellpadding='0' bordercolor='#5A7594' width='100%' id="table">
			<tr>
			  <td>
			 	<table border='0' width='100%'>
					<tr>
						<td class='header' align='center'>NIS</td>
						<td class='header'>Nama</td>
	<?php
	$jml_data = @mysqli_num_rows($result_sis);
	
	if($jml_data == "0"){
		echo "<tr>
				<td colspan='3' align='center' class='style4' bgcolor='#B3C0D0'>Data Siswa Tidak Ada</td>
			 </tr>";
			 
	}else{
	
	$cnt = 0;
	while($row = @mysqli_fetch_array($result_sis)){
	?>
		
	<tr <?="bgcolor=#".($cnt%2?"ffffff":"EAECEE").""; ?>>
		<td class='data'>
		<input type="hidden" name="nis<?=$cnt; ?>" value="<?=$row['nis']; ?>">
		<input type="hidden" name="nama<?=$cnt; ?>" value="<?=$row['nama']; ?>"><input name='siswa' type='radio' value='<?=$row['nis']; ?>' onclick='changeSel(<?=$cnt; ?>)' width='30%'><?=$row['nis']; ?></td>
		<td width='70%'class='data'><?=$row['nama']; ?></td>
	</tr>
	<?php
	$cnt++;
	}
	CloseDb();
	?>
	<tr>
		<td colspan='3' align="right" class="header">
		<input type='button' class='but' value='Pilih >>' name='pilih' onclick='tekan()'></td>
		</form>
		</tr>
	</table>
   </td>
  </tr>
</table>
<script language='JavaScript'>
            Tables('table', 1, 0);
</script>
<?php
	}
}	
?>
</body>
</html>