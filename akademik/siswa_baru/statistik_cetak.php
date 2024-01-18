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

//Ambil semua variabel yang dikirim halaman sebelumnya
$departemen=$_REQUEST['departemen'];
$idproses=$_REQUEST['idproses'];
$dasar=$_REQUEST['dasar'];
$tabel=$_REQUEST['tabel'];
$nama_judul=$_REQUEST['nama_judul'];
$iddasar=$_REQUEST['iddasar'];

OpenDb();
$i = 0;

if ($dasar == 'Golongan Darah') {
	$row = ['A', '0', 'B', 'AB', ''];
	$judul = [1=>'A', '0', 'B', 'AB', 'Tidak ada data'];	
	$jum = count($row);	
} elseif ($dasar == 'Jenis Kelamin') {
	$row = ['l', 'p'];
	$judul = [1=>'Laki-laki', 'Perempuan'];	
	$jum = count($row);
} elseif ($dasar == 'Kewarganegaraan') {
	$row = ['WNI', 'WNA'];
	$judul = [1=>'WNI', 'WNA'];	
	$jum = count($row);
} elseif ($dasar == 'Status Aktif') {
	$row = [1, 0];
	$judul = [1 => 'Aktif', 'Tidak Aktif'];
	$jum = count($row);
} elseif ($dasar == 'Kondisi Siswa') {	
	$query = "SELECT $tabel FROM jbsakad.kondisisiswa ORDER BY $tabel ";
	$result = QueryDb($query);
	$jum = @mysqli_num_rows($result);
} elseif ($dasar == 'Status Siswa') {	
	$query = "SELECT $tabel FROM jbsakad.statussiswa ORDER BY $tabel ";
	$result = QueryDb($query);
	$jum = @mysqli_num_rows($result);
} elseif ($dasar == 'Pekerjaan Ayah' || $dasar == 'Pekerjaan Ibu') {	
	$query = "SELECT pekerjaan FROM jbsumum.jenispekerjaan ORDER BY pekerjaan ";
	$result = QueryDb($query);
	$jum = @mysqli_num_rows($result);
} elseif ($dasar == 'Pendidikan Ayah' || $dasar == 'Pendidikan Ibu') {	
	$query = "SELECT pendidikan FROM jbsumum.tingkatpendidikan ORDER BY pendidikan ";
	$result = QueryDb($query);
	$jum = @mysqli_num_rows($result);
} elseif ($dasar == 'Penghasilan Orang Tua') {		
	$batas = [0, 1_000_000, 2_500_000, 5_000_000];
	$judul = [1 => '< Rp 1000000', 'Rp 1000000 s/d Rp 2500000', 'Rp 2500000 s/d Rp 5000000', '> Rp 5000000'];
	$jum = count($judul);
} elseif ($dasar == 'Agama' || $dasar == 'Suku') {		
	$query = "SELECT $tabel FROM jbsumum.$tabel";
	$result = QueryDb($query);
	$jum = @mysqli_num_rows($result);	
} else {	
	$jum = 1;
}

for ($i=1;$i<=$jum;$i++) {	
	$field = "";
	if ($dasar == 'Golongan Darah' || $dasar == 'Jenis Kelamin' || $dasar == 'Kewarganegaraan' ) {		
		$filter = "1 AND s.$tabel = '".$row[$i-1]."'";
	} elseif ($dasar == 'Penghasilan Orang Tua' ) {			
		$field = ", penghasilanayah+penghasilanibu";
		$filter = "1 AND ".$batas[$i-1]." < penghasilanayah+penghasilanibu < ".$batas[$i]." GROUP BY penghasilanayah+penghasilanibu";
		if ($i == $jum) {
			$filter = "1 AND ".$batas[$i-1]." > penghasilanayah+penghasilanibu GROUP BY penghasilanayah+penghasilanibu";
		} 
	} elseif ($dasar == 'Status Aktif') {
		$filter = $row[$i-1];		
	} elseif ($dasar=='Agama' || $dasar=='Suku' || $dasar=='Status Siswa' || $dasar=='Kondisi Siswa' || $dasar=='Pekerjaan Ayah' || $dasar=='Pekerjaan Ibu' || $dasar=='Pendidikan Ayah' || $dasar=='Pendidikan Ibu') {
		$row = @mysqli_fetch_row($result);
		$judul[$i] = $row[0];		
		$filter = "1 AND s.$tabel = '".$row[0]."'";
		if ($dasar=='Pekerjaan Ayah' || $dasar=='Pekerjaan Ibu' || $dasar=='Pendidikan Ayah' || $dasar=='Pendidikan Ibu') {
			if ($i == $jum) {
				$judul[$i] = "Tidak ada data";
				$filter = "1 AND s.$tabel is NULL";			
			}
		}	
	} elseif ($dasar == 'Tahun Kelahiran') {
		$field = ", YEAR(tgllahir)";
		$filter = "1 GROUP BY YEAR(tgllahir)";	
		$j = 1;
		$jum = 0;		
	} elseif ($dasar == 'Usia') {
		$field = ", YEAR(now()) - YEAR(tgllahir)";
		$filter = "1 GROUP BY YEAR(now()) - YEAR(tgllahir)";	
		$j = 1;
		$jum = 0;		
	} else {
		$field =", s.$tabel";
		$filter = "1 GROUP BY s.$tabel";
		$j = 1;
		$jum = 0;
	}
		
	if ($departemen=="-1" && $idproses<0) {	
		$query1 = "SELECT COUNT(*) As Jum$field FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.replid=s.idproses AND s.aktif = $filter"; 		
	}  
	if ($departemen<>"-1" && $idproses<0) {	
		$query1 = "SELECT COUNT(*) As Jum$field FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.departemen='$departemen' AND a.replid=s.idproses AND s.aktif = $filter";
	} 
	if ($departemen<>"-1" && $idproses>0) {	
		$query1 = "SELECT COUNT(*) As Jum$field FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.idproses='$idproses' AND a.replid=s.idproses AND a.departemen='$departemen' AND s.aktif = $filter ";
	}
	
	$data[$i] = 0;	
	$result1 = QueryDb($query1);
	$num = @mysqli_num_rows($result1);
	
	while ($row1 = @mysqli_fetch_row($result1)) {
   		$data[$i] = $row1[0];
		if ($dasar=="Asal Sekolah" || $dasar=="Kode Pos Siswa" || $dasar=="Tahun Kelahiran" || $dasar=="Usia") { 
			//echo "judul ".$row1[1];
			$jdl = $row1[1];
			if ($row1[1] == "") {
				$jdl = "Tidak ada data";
			} 
			$data[$j] = $row1[0];
			$judul[$j] = $jdl;
			$j++;
			$jum = $jum+1;
		}
	} 
}

$sum = 0;
for ($i=1;$i<=$jum;$i++) {
	$sum = $sum + $data[$i];	
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Cetak Statistik Penerimaan Siswa Baru]</title>
<script language="javascript" src="../script/tables.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">
								
	<?php include("../library/headercetak.php") ?>
	<center>
  	<font size="4"><strong>STATISTIK PENERIMAN SISWA BARU<BR />BERDASARKAN <?=$nama_judul?></strong></font><br />
 	</center>
	<br /><br />    
	<table>
	<tr>
		<td><strong>Departemen</strong> </td> 
		<td><strong>:&nbsp;
	<?php 	if ($departemen=="-1") {
			echo "Semua Departemen"; 
		} else { 
			echo $departemen; 
		}
	?></strong></td>	
	</tr>
	<tr>
		<td><strong>Penerimaan</strong></td>
		<td><strong>:&nbsp;
	<?php 	if ($idproses=="-1") { 
			echo "Semua Penerimaan"; 
		} else {
 			OpenDb();
			$sql="SELECT proses FROM prosespenerimaansiswa WHERE replid='$idproses'";
			$result=QueryDb($sql);
			if ($row=@mysqli_fetch_array($result)){
				echo $row['proses'];
			}
			CloseDb();
		}    
	?></strong></td>
	</tr>
	</table>
    <br />
</td>
<tr>
    <td align="center">
    <img src="statistik_batang.php?iddasar=<?=$iddasar?>&departemen=<?=$departemen?>&idproses=<?=$idproses?>"/>
    <!--<img src="statistik_batang2.php?dasar=<?=$dasar?>&departemen=<?=$departemen?>&idproses=<?=$idproses?>&tabel=<?=$tabel?>" />--></td>
		
</tr>
<tr>
   	<td align="center">
    <img src="statistik_pie.php?iddasar=<?=$iddasar?>&departemen=<?=$departemen?>&idproses=<?=$idproses?>" />	
    <!--<img src="statistik_pie2.php?dasar=<?=$dasar?>&departemen=<?=$departemen?>&idproses=<?=$idproses?>&tabel=<?=$tabel?>" />-->            
    </td>
</tr>
<tr>
<td align="center">
	<table class="tab" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="0" width="100%" align="left" bordercolor="#000000">
	<tr>
    	<td height="30" align="center" class="header" width="*"><?=$dasar?></td>
    	<td height="30" align="center" class="header" width="20%">Jumlah</td>
    	<td height="30" align="center" class="header" width="20%">Prosentase</td>
    </tr>
<?php
for ($i=1;$i<=$jum;$i++) {    
	$prosentase=($data[$i]/$sum)*100;
?>

	<tr>
   		<td height="25" >
   		  <?=$judul[$i]?>
	    </td>
    	<td height="25" align="center">
    	  <?=$data[$i]?>
  	  </div></td>
    	<td height="25" align="center">
    	  <?=round($prosentase,2)?> 
    	  %</div></td>
  	</tr>
<?php  }  ?>
	</table>
    </td>
</tr>
    <!-- END TABLE CONTENT -->

</table>
</body>
<script language="javascript">
window.print();
</script>
</html>