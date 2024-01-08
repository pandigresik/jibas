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

if(isset($_REQUEST['nis'])){
	$nis = $_REQUEST['nis'];
}
if(isset($_REQUEST['nama'])){
	$nama = $_REQUEST['nama'];
}
if(isset($_REQUEST['departemen'])){
	$departemen = $_REQUEST['departemen'];
}
if(isset($_REQUEST['kelas'])){
	$kelas = $_REQUEST['kelas'];
}
if(isset($_REQUEST['jenis'])){
	$jenis = $_REQUEST['jenis'];
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
<title>Tambah Siswa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/mainmenu.css" type="text/css">
<script language="javascript">

</script>
<link rel="stylesheet" type="text/css" href="../style/style.css">

<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
</head>

<body topmargin="0" leftmargin="10" marginheight="0" marginwidth="10"><br>
<?php
OpenDb();
?>
<form action="tambah_siswa_pp2.php" method="post" name="main" onSubmit="return tekan()">
<input type="hidden" name="selected">
<input type="hidden" value="<?=$departemen ?>" name="departemen">
<input type="hidden" value="<?=$kelas ?>" name="kelas">
<input type="hidden" value="<?=$jenis ?>" name="jenis">
<input type="hidden" value="<?=$pelajaran ?>" name="pelajaran">
<input type="hidden" value="<?=$semester ?>" name="semester">
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td class="header">Input Siswa</td>
		<td class="header" align="right">Langkah 2 dari 2</td>
	</tr>
</table>
<br>
<table>
	<tr>
		<td>NIS</td>
		<td><input type="text" name="nis" value="<?=$nis ?>" readonly></td>
	</tr>
	<tr>
		<td>Nama</td>
		<td><input type="text" name="nama" value="<?=$nama ?>" readonly></td>
	</tr>
</table>
<br>
		<table border='1' cellspacing='0' cellpadding='0' bordercolor='#5A7594' width='100%'>
			<tr>
			  <td>
			 	<table border='1' width='100%' id="table" class="tab">
					<tr>
						<td class='header' align='center' height='30'>No</td>
						<td class='header' height='30'>Tanggal</td>
						<td class="header" height='30'>Deskripsi</td>
						<td class="header" height='30'>Status</td>
						<td class="header" height='30'>Nilai</td>
						<td class="header" height='30'>Keterangan</td>
					</tr>
					<?php
					$query = "SELECT replid, tanggal, deskripsi ".
							 "FROM jbsakad.ujian ".
							 "WHERE idpelajaran = '$pelajaran' ".
							 "AND idkelas = '$kelas' ".
							 "AND idsemester = '$semester' ".
							 "AND idjenis = '$jenis' ";		
					$result = QueryDb($query);
					$jml_data = @mysqli_num_rows($result);
					
					if($jml_data=="0"){
					?>
		<tr>
			<td colspan='3' align='center' colspan='6'>Data Tidak Ada</td>
		</tr>
		<?php 
	}else{
	?>
	<input type="hidden" name="num_data" value="<?=$jml_data ?>">	
	<?php
	$i = 1;
		while($row = @mysqli_fetch_array($result)){
	?>
	<tr <?="bgcolor=#".($cnt%2?"ffffff":"EAECEE").""; ?>>
		<td class='data'><?=$i ?>
		<input type="hidden" name="ujian<?=$i ?>" value="<?=$row['replid'] ?>">
		</td>
		<td class='data'><?=$row['tanggal']; ?>
		<input type="hidden" name="tanggal<?=$i ?>" value="<?=$row['tanggal'] ?>">
		</td>
		<td class='data'><?=$row['deskripsi']; ?>
		<input type="hidden" name="deskripsi<?=$i ?>" value="<?=$row['deskripsi'] ?>">
		</td>
		<td class='data'><select name="status<?=$i ?>">
						<option value="0">Hadir/Mengumpulkan</option>
						<option value="1">Tidak Hadir</option>
						<option value="2">Tidak Mengumpulkan</option>
						<option value="3">Mencontek</option>
						<option value="4" selected>Lainnya</option>
						</select>
						</td>		
		<td class='data'><input type="text" name="nilai<?=$i ?>" size="2" value="0"></td>				
		<td class='data'><input type="text" name="keterangan<?=$i ?>" value="siswa baru, belum mengikuti ujian ini"></td>		
	</tr>
	<?php
	$i++;
	}
	?>
	<tr>
		<td colspan='6' align="right">
		<input type='submit' class='but' value='Simpan' name='simpan'></td>
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
if(isset($_POST['nis'])) {
	$i=1;
	while($i < ($_POST['num_data']+1)){
		$uj = "ujian$i";
		$sts = "status$i";
		$nuj = "nilai$i";
		$ket = "keterangan$i";		
	OpenDBi();
		
	$result = mysqli_query($conni,"CALL spTambahNilaiUjian('".$_POST[$uj]."','".$_POST['nis']."','".$_POST[$nuj]."','".$_POST['ket']."','$_POST[$sts]')") or die (mysqli_error($conni));
	$i++;
	}
	$i=1;
	while($i < ($_POST['num_data']+1)){
		$uj = "ujian$i";
		$sts = "status$i";
		$nuj = "nilai$i";
		$ket = "keterangan$i";	
			
			$query_nuj1 = "SELECT * FROM jbsakad.nilaiujian WHERE nilaiujian.idujian   = '".$_POST[$uj]."'";
			$result_nuj1 = QueryDb($query_nuj1) or die (mysqli_error($mysqlconnection));
	
				$t=1;
				while($row_nuj1 = @mysqli_fetch_array($result_nuj1)){
					$tota_nuj1 += $row_nuj1['nilaiujian'];
					$t++;
				}
				$ruk = $tota_nuj1/$t;	
				
				$query_ruk = "UPDATE jbsakad.ratauk SET nilaiRk = '$ruk' ".
							 "WHERE idkelas = '$kelas' ".
							 "AND idsemester = '$semester' ".
							 "AND idujian   = '".$_POST[$uj]."' ";
				
				$result_ruk = QueryDb($query_ruk) or die (mysqli_error($mysqlconnection));	
			$i++;		 
		}	
	
	?>
	<script language="javascript">
		opener.document.location.href = "tampil_nilai_pelajaran.php?departemen=<?=$departemen ?>&kelas=<?=$kelas ?>&pelajaran=<?=$pelajaran ?>&semester=<?=$semester ?>&jenis_penilaian=<?=$jenis ?>";
		window.close();
	</script>
	<?php
	}
}
	CloseDb();
?>
</body>
</html>