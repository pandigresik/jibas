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
require_once('../include/getheader.php');

if(isset($_POST["departemen"])){
	$departemen = $_POST["departemen"];
}elseif(isset($_GET["departemen"])){
	$departemen = $_GET["departemen"];
}


if(isset($_POST["semester"])){
	$semester = $_POST["semester"];
}elseif(isset($_GET["semester"])){
	$semester = $_GET["semester"];
}

if(isset($_POST["kelas"])){
	$kelas = $_POST["kelas"];
}elseif(isset($_GET["kelas"])){
	$kelas = $_GET["kelas"];
}

if(isset($_POST["nis"])){
	$nis = $_POST["nis"];
}elseif(isset($_GET["nis"])){
	$nis = $_GET["nis"];
}

if(isset($_POST["pelajaran"])){
	$pelajaran = $_POST["pelajaran"];
}elseif(isset($_GET["pelajaran"])){
	$pelajaran = $_GET["pelajaran"];
}
$jenis_penilaian = $_GET['jenis_penilaian'];
?>

<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript">
function print_this() {
    window.print();
}
</script>
</head>
<?php
OpenDb();
?>
<body onLoad="print_this()">
<?=getHeader($departemen)?>
<?php
CloseDb();
OpenDb();
?>
<table width="100%" border="0">
	<tr>
		<td align="center" colspan="9"><font size="4"><b>LAPORAN NILAI PELAJARAN</b></td>
	</tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
  </tr>
	<tr>
		<td width="94">Departemen</td>
		<td width="5">:</td>
		<td width="322"><?=$departemen ?></td>
	    <td width="94">Semester</td>
	    <td width="5">:</td>
	    <td width="330"><?php 
		$query_sem = "SELECT * FROM jbsakad.semester WHERE replid = '".$semester."'";
		$result_sem = QueryDb($query_sem);
		$row_sem = mysqli_fetch_array($result_sem);
		echo $row_sem['semester'] ?></td>
	</tr>
	<tr>
		<td>Kelas</td>
		<td>:</td>		
		<td>
		<?php 
		$query_kls = "SELECT * FROM jbsakad.kelas WHERE replid = '".$kelas."'";
		$result_kls = QueryDb($query_kls);
		$row_kls = mysqli_fetch_array($result_kls);
		echo $row_kls['kelas'] ?></td>
	    <td>NIS</td>
	    <td>:</td>
	    <td><?=$nis?></td>
	</tr>
	<tr>
		<td>Pelajaran</td>
		<td>:</td>		
		<td>
		<?php if($pelajaran == "all"){
				$pel = "Semua Pelajaran";
			}elseif($pelajaran != "all"){
				$query_pel = "SELECT nama FROM jbsakad.pelajaran WHERE replid = '".$pelajaran."'";
				$result_pel = QueryDb($query_pel);
				$row_pel = mysqli_fetch_array($result_pel);
				$pel = $row_pel['nama'];
			}
		echo $pel ?></td>
	    <td>Nama</td>
	    <td>:</td>
	    <td><?php 
		$query_nama = "SELECT * FROM jbsakad.siswa WHERE nis = '".$nis."'";
		$result_nama = QueryDb($query_nama);
		$row_nama = mysqli_fetch_array($result_nama);
		echo $row_nama['nama'] ?></td>
	</tr>
	
	<tr>
		<td align="center" colspan="7">&nbsp;</td>
	</tr>
</table>
<br>
<input type="hidden" name="departemen" value="<?=$departemen ?>">
<input type="hidden" name="pelajaran" value="<?=$pelajaran ?>">
<input type="hidden" name="kelas" value="<?=$kelas ?>">
<input type="hidden" name="semester" value="<?=$semester ?>">
<?php
OpenDb();
$sql = "SELECT s.replid, s.semester, p.nama FROM semester s, pelajaran p WHERE s.departemen = '$departemen' AND p.replid = $pelajaran AND p.departemen = '$departemen' AND s.replid='$semester'"; 
$result = QueryDb($sql);

$i = 0;
while ($row = @mysqli_fetch_row($result)) {
	$sem[$i]= [$row[0], $row[1]];
	$pel = $row[2];
	$i++;
}

?>
<table width="100%" border="0">
  


    
	<?php $sql = "SELECT j.replid, j.jenisujian FROM jenisujian j WHERE j.idpelajaran = '$pelajaran' GROUP BY j.jenisujian";
		$result = QueryDb($sql);
		while($row = @mysqli_fetch_array($result)){			
	?>
   	
		<tr>
    <td><fieldset><legend><strong> <?=$row['jenisujian']?> </strong></legend>
    
    
   
  <table border="1" width="100%" id="table" class="tab" bordercolor="#000000">
		<tr>		
			<td width="5" height="30" align="center" class="header"><div align="center">No</div></td>
			<td width="120" class="header" align="center" height="30">Tanggal</td>
            <td width="100" height="30" align="center" class="header">Nilai</td>
			<td width="400" class="header" align="center" height="30">Keterangan</td>
		</tr>
		<?php 	OpenDb();		
			$sql1 = "SELECT u.tanggal, n.nilaiujian, n.keterangan FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = $kelas AND u.idpelajaran = $pelajaran AND u.idsemester = ".$sem[0][0]." AND u.idjenis = ".$row['replid']." AND u.replid = n.idujian AND n.nis = '$nis' ORDER BY u.tanggal";
			$result1 = QueryDb($sql1);
			$sql2 = "SELECT AVG(n.nilaiujian) as rata FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = $kelas AND u.idpelajaran = $pelajaran AND u.idsemester = ".$sem[0][0]." AND u.idjenis = ".$row['replid']." AND u.replid = n.idujian AND n.nis = '$nis' ";
			$result2 = QueryDb($sql2);
			$row2 = @mysqli_fetch_array($result2);
			$rata = $row2['rata'];
			/*
			$sql3 = "SELECT nau.nilaiAU as nilaiAU FROM ujian u, pelajaran p, nilaiujian n, nau nau WHERE u.idpelajaran = p.replid AND u.idkelas = $kelas AND u.idpelajaran = $pelajaran AND u.idsemester = ".$sem[0][0]." AND u.idjenis = ".$row['replid']." AND u.replid = n.idujian AND n.nis = '$nis' AND nau.idjenis=$row['replid'] AND nau.idpelajaran = $pelajaran AND nau.idsemester = ".$sem[0][0];
			$result3 = QueryDb($sql3);
			$row3 = @mysqli_fetch_array($result3);
			$nilaiAU = $row3['nilaiAU'];		
			*/
			$cnt = 0;
			if (@mysqli_num_rows($result1)>0){
			while($row1 = @mysqli_fetch_array($result1)){			
        ?>
        <tr>        			
			<td height="25" align="center"><div align="center"><?=++$cnt?></div></td>
			<td height="25" align="center"><?=format_tgl($row1[0])?></td>
            <td height="25"><div align="center">
              <?=$row1[1]?>
            </div></td>
            <td height="25"><?=$row1[2]?></td>            
		</tr>	
        <?php } ?>
		<tr style="background-color:#E1FFFF">        			
			<td colspan="2" height="25"><div align="center">Nilai rata rata</div></td>
			<td height="25"><div align="center"><?=round($rata,2)?></div></td>
            <td height="25">&nbsp;</td>            
		</tr>
		<?php } else { ?>
		<tr>        			
			<td colspan="4" height="25" align="center">Tidak ada nilai</td>
		</tr>
		<?php }
			?>
		</table> 
        </fieldset>
    	</td>
  		</tr>
		<script language="javascript">
			Tables('table', 1, 0);
		</script>
		
    <?php } ?> 
    
    <!-- END TABLE CONTENT -->
       
</table>  	
	<br>
	
</body>
</html>