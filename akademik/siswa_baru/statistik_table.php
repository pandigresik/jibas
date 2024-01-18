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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

$lup="";
if (isset($_REQUEST['lup']))
	$lup=$_REQUEST['lup'];

//Ambil semua variabel yang dikirim halaman sebelumnya
$departemen=$_REQUEST['departemen'];
$idproses=$_REQUEST['idproses'];
$iddasar=$_REQUEST['iddasar'];
$dasar=$_REQUEST['dasar'];
$tabel=$_REQUEST['tabel'];
$nama_judul=$_REQUEST['nama_judul'];

OpenDb();
$i = 0;
if ($iddasar!="12"){
	if ($dasar == 'Golongan Darah') {
		$row = ['', 'A', 'AB', 'B', 'O'];
		$judul = [1=>'Tidak ada data', 'A', 'AB', 'B', 'O'];	
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
		$jum = @mysqli_num_rows($result)+1;
	} elseif ($dasar == 'Pendidikan Ayah' || $dasar == 'Pendidikan Ibu') {	
		$query = "SELECT pendidikan FROM jbsumum.tingkatpendidikan ORDER BY pendidikan ";
		$result = QueryDb($query);
		$jum = @mysqli_num_rows($result)+1;
	/*} elseif ($dasar == 'Penghasilan Orang Tua') {		
		$batas = array(0,1000000,2500000,5000000);
		$judul = array(1 => '< Rp 1000000','Rp 1000000 - Rp 2500000','Rp 2500000 - Rp 5000000','> Rp 5000000');
		$jum = count($judul);*/
	} elseif ($dasar == 'Agama' || $dasar == 'Suku') {	
		$str = strtolower((string) $dasar);	
		$query = "SELECT $tabel FROM jbsumum.$tabel ORDER BY $str";
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
			//$jum = 0;		
		} elseif ($dasar == 'Usia') {
			$field = ", YEAR(now()) - YEAR(tgllahir)";
			$filter = "1 GROUP BY YEAR(now()) - YEAR(tgllahir)";	
			$j = 1;
			//$jum = 0;
		} else {
			$field =", s.$tabel";
			$filter = "1 GROUP BY s.$tabel";
			$j = 1;
			//$jum = 0;
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
		
		$result1 = QueryDb($query1);
		while ($row1 = @mysqli_fetch_row($result1)) {
			if ($dasar=="Asal Sekolah"||$dasar=="Kode Pos Siswa"||$dasar=="Tahun Kelahiran" || $dasar=="Usia") { 
			
				$jdl = $row1[1];
				if ($row1[1] == "") {
					
					$jdl = "Tidak ada data";
				} 
				$data[$j] = $row1[0];
				$judul[$j] = $jdl;
				$j++;
			} else {
				$data[$i] = $row1[0];
			}
		} 
	}
	
	
	$sum = 0;
	for ($i=1;$i<=count($data);$i++) {
		$sum = $sum + $data[$i];
	}
	
} else {
	if ($departemen=="-1" && $idproses<0)
		$kondisi=" AND a.replid=s.idproses ";
	if ($departemen<>"-1" && $idproses<0)
		$kondisi=" AND a.departemen='$departemen' AND a.replid=s.idproses ";
	if ($departemen<>"-1" && $idproses>0)
		$kondisi=" AND s.idproses='$idproses' AND a.replid=s.idproses AND a.departemen='$departemen' ";
	
	$query1 = "SELECT COUNT(*) As Jum FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.replid=s.idproses AND s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu <> 0 AND s.penghasilanayah+s.penghasilanibu<1000000 $kondisi";
	
	$query2 = "SELECT COUNT(*) As Jum FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.replid=s.idproses  AND s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu>=1000000 AND s.penghasilanayah+s.penghasilanibu<2500000 $kondisi";
	
	$query3 = "SELECT COUNT(*) As Jum FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.replid=s.idproses  AND s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu>=2500000 AND s.penghasilanayah+s.penghasilanibu<5000000 $kondisi";
	
	$query4 = "SELECT COUNT(*) As Jum FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.replid=s.idproses  AND s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu>=5000000 $kondisi";
	
	$query5 = "SELECT COUNT(*) As Jum FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.replid=s.idproses  AND s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu = 0 $kondisi";

	$result1 = QueryDb($query1);
	$row1 = @mysqli_fetch_array($result1);
	$j1 = $row1['Jum'];
	
	$result2 = QueryDb($query2);
	$row2 = @mysqli_fetch_array($result2);
	$j2 = $row2['Jum'];
	
	$result3 = QueryDb($query3);
	$row3 = @mysqli_fetch_array($result3);
	$j3 = $row3['Jum'];
	
	$result4 = QueryDb($query4);
	$row4 = @mysqli_fetch_array($result4);
	$j4 = $row4['Jum'];

	$result5 = QueryDb($query5);
	$row5 = @mysqli_fetch_array($result5);
	$j5 = $row5['Jum'];
	
	//=====================================================
	
	$sum = $j1 + $j2 +$j3 + $j4 + $j5;
	$dasar="Penghasilan";
	$p[1]=round($j1/$sum*100,2);
	$p[2]=round($j2/$sum*100,2);
	$p[3]=round($j3/$sum*100,2);
	$p[4]=round($j4/$sum*100,2);
	$p[5]=round($j5/$sum*100,2);
	$j[1]=$j1;
	$j[2]=$j2;
	$j[3]=$j3;
	$j[4]=$j4;
	$j[5]=$j5;
	$jud[1]="< Rp 1.000.000";
	$jud[2]="Rp 1.000.000 - Rp 2.500.000";
	$jud[3]="Rp 2.500.000 - Rp 5.000.000";
	$jud[4]="> Rp 5.000.000";
	$jud[5]="Tidak ada data";

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tabel Statistik</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript">
</script>
</head>
<body topmargin="0" leftmargin="0">
<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
    <!-- TABLE CONTENT -->    
	<tr height="30" align="center" class="header">
    	<td width="*" ><?=$dasar?> </td>
    	<td width="20%">Jumlah </td>
    	<td width="25%">Prosentase</td>
    	<?php if ($lup==""){ ?>
        <td width="8%">&nbsp;</td>
  		<?php } ?>
    </tr>
<?php

if ($iddasar!="12"){
	for ($i=1;$i<=count($data);$i++) {
		$prosentase=($data[$i]/$sum)*100;		
?>
	<tr>
   		<td height="25"><?=$judul[$i]?></td>
    	<td height="25" align="center"><?=$data[$i]?></td>
        
    	<td height="25" align="center"><?=round($prosentase,1)?> %</td>
   	<?php if ($lup==""){ ?>
		<td height="25" align="center">
	<?php 	if ($data[$i] != 0) { 
				$judul1 = $judul[$i];
				if ($judul[$i] == "Tidak ada data")
					$judul1 = NULL;
	?>
             		
       <a href="statistik_tampil.php?departemen=<?=$departemen?>&idproses=<?=$idproses?>&dasar=<?=$dasar?>&tabel=<?=$tabel?>&judul=<?=$judul1?>" target="detail_statistik"><img src="../images/ico/lihat.png" border="0" onMouseOver="showhint('Lihat Detail!', this, event, '80px')"></a>         		
		<?php 		}	?>
        </td>
		<?php 	} ?>
        
  </tr>
<?php  }
} else {
	for ($i=1;$i<=5;$i++) {
	?>
<tr>
   		<td height="25"><?=$jud[$i] ?></td>
    	<td height="25" align="center"><?=$j[$i]?></td>
    	<td height="25" align="center"><?=round($j[$i]/$sum*100,1)?> %</td>
			<?php
		if ($lup==""){
		?>
        <td height="25" align="center">
        <?php if ($j[$i] != 0) { ?>
        <a href="statistik_tampil.php?dasar=<?=$dasar?>&departemen=<?=$departemen?>&idproses=<?=$idproses?>&iddasar=<?=$iddasar?>&keyword=<?=$i?>&judul=<?=$jud[$i]?>" target="detail_statistik"><img src="../images/ico/lihat.png" border="0" onMouseOver="showhint('Lihat Detail!', this, event, '80px')">   </a>    
         <?php }?>
        </td>
	<?php 	} ?>
      
</tr>
<?php 	}
}?>
</table></div>

<script language='JavaScript'>
	Tables('table', 1, 0);
</script>

<?php 
// } 
?> 
</body>
</html>