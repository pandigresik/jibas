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
//require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/getheader.php');
require_once('../library/dpupdate.php');
require_once('../cek.php');

$semester = $_REQUEST['semester'];
$kelas = $_REQUEST['kelas'];
$nip = $_REQUEST['nip'];
$pelajaran = $_REQUEST['pelajaran'];

OpenDb();
$sql = "SELECT k.kelas AS namakelas, s.semester AS namasemester, a.tahunajaran, a.departemen, 
			   l.nama, t.tingkat, t.replid AS idtingkat, p.nama AS guru, s.departemen as dep 
		  FROM kelas k, semester s, tahunajaran a, pelajaran l, tingkat t, jbssdm.pegawai p 
		 WHERE k.replid = $kelas AND s.replid = $semester AND  k.idtahunajaran = a.replid 
		   AND t.replid = k.idtingkat AND l.replid = $pelajaran AND p.nip = '".$nip."'";
$result = QueryDb($sql);
$rowinfo = mysqli_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Form Pengisian Nilai Rapor Siswa]</title>
<style type="text/css">
<!--
.style27 {color: #FF9900}
.style28 {color: #009900}
-->
</style>
</head>
<body>

<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($rowinfo['dep'])?>
<center>
  <font size="4"><strong>FORM PENGISIAN NILAI RAPOR SISWA</strong></font><br />
 </center><br /><br />
<br />

<table>
<tr>
    <td width="22%"><strong>Departemen</strong></td>
    <td width="45%"><strong>: <?=$rowinfo['departemen'] ?></strong></td>
    <td width="15%"><strong>Kelas</strong></td>
    <td><strong>: <?=$rowinfo['tingkat'].' - '. $rowinfo['namakelas'];?></strong></td>
</tr>
<tr>
    <td><strong>Tahun Ajaran</strong></td>
   	<td><strong>: <?=$rowinfo['tahunajaran']?></strong></td>
    <td><strong>Pelajaran</strong></td>
    <td><strong>: <?=$rowinfo['nama'];?></strong></td>
</tr>
<tr>
    <td><strong>Semester</strong></td>
    <td><strong>: <?=$rowinfo['namasemester']?></strong></td>
</tr>
</table>
<br />
<?php

$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
		  FROM aturannhb a, kelas k, dasarpenilaian d
		 WHERE a.nipguru = '$nip' AND a.idtingkat = k.idtingkat AND k.replid = $kelas AND d.aktif = 1
		   AND a.idpelajaran = $pelajaran AND a.dasarpenilaian = d.dasarpenilaian";
  
$resaspek = QueryDb($sql);
while($rowaspek = mysqli_fetch_row($resaspek))
{
	$aspek = $rowaspek[0];  
	$aspekket = $rowaspek[1];  
	
	// Hitung jumlah bobot dan banyaknya aturan
	$sql = "SELECT SUM(bobot) as bobotPK, COUNT(a.replid) 
			  FROM jbsakad.aturannhb a, kelas k 
			 WHERE a.nipguru = '$nip' AND a.idtingkat = k.idtingkat AND k.replid = $kelas 
			   AND a.idpelajaran = $pelajaran AND a.dasarpenilaian = '$aspek' AND a.aktif = 1";
	$res = QueryDb($sql);
	$row = @mysqli_fetch_row($res);
	$bobot_PK = $row[0];
	$jum_nhb = $row[1];

	// get jumlah pengujian
	$sql = "SELECT j.jenisujian as jenisujian, a.bobot as bobot, a.replid, a.idjenisujian 
			  FROM jbsakad.aturannhb a, jbsakad.jenisujian j, kelas k 
			 WHERE a.idtingkat = k.idtingkat AND k.replid = $kelas AND a.nipguru = '$nip' 
			   AND a.idpelajaran = $pelajaran AND a.dasarpenilaian = '$aspek' 
			   AND a.idjenisujian = j.replid AND a.aktif = 1 
		  ORDER BY a.replid"; 
	$result_get_aturan_PK = QueryDb($sql);
	$jum_PK = @mysqli_num_rows($result_get_aturan_PK);	?>
    
    <Strong>Aspek Penilaian: <?=$aspekket?></Strong>
    <table width="100%" border="1" class="tab" id="table" bordercolor="#000000">  
  	<tr align="center">
    	<td height="30" class="headerlong" width="4%" rowspan="2">No</td>
        <td height="30" class="headerlong" width="10%" rowspan="2">N I S</td>
        <td height="30" class="headerlong" width="*" rowspan="2">Nama</td>    	    
        <td height="15" colspan="<?=(int)$jum_PK?>" class="headerlong">Nilai Akhir</td>
		<td height="15" colspan="2" class="headerlong" width="13%"><span class="style1">Nilai <?=$aspekket?></span></td>
    </tr>
    <tr height="15" class="header" align="center">
	<?php $i = 0;
		$ujian = [];
		while ($row_PK = @mysqli_fetch_array($result_get_aturan_PK)) 
		{			
            $ujian[$i++] = [$row_PK['replid'], $row_PK['bobot'], $row_PK['idjenisujian'], $aspek];  ?>
    		<td width="8%" class="headerlong">
            	<span class="style1"><?= $row_PK['jenisujian']." (".$row_PK['bobot'].")" ?></span>
            </td>
    <?php } ?>
		<td align="center" class="headerlong"><span class="style1">Angka</span></td>
        <td align="center" class="headerlong"><span class="style1">Huruf</span></td>
	</tr>
<?php //Mulai perulangan siswa
	$sql = "SELECT replid, nis, nama 
	          FROM jbsakad.siswa 
			 WHERE idkelas='$kelas'
			   AND aktif=1
			   AND alumni = 0
		  ORDER BY nama";
  	$res_siswa = QueryDb($sql);
  	$cnt = 1;
	$total = mysqli_num_rows($res_siswa);
  	while ($row_siswa = @mysqli_fetch_array($res_siswa)) 
	{ ?>
  	<tr height="25">
    	<td align="center"><?=$cnt?></td>
    	<td align="center"><?=$row_siswa['nis']?></td>
    	<td><?=$row_siswa['nama']?></td>
	<?php foreach ($ujian as $value) 
		{ 
			$sql = "SELECT n.nilaiAU as nilaiujian 
			          FROM jbsakad.nau n, jbsakad.aturannhb a 
				     WHERE n.idpelajaran = $pelajaran AND n.idkelas = $kelas 
					   AND n.nis = '".$row_siswa['nis']."' AND n.idsemester = $semester 
				       AND n.idjenis = $value[2] AND n.idaturan = a.replid 
					   AND a.replid = $value[0]";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_array($res);
			echo "<td align='center'>" . $row['nilaiujian'] . "</td>";
		}  	?>
	   	<td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
  	  </tr>
<?php    $cnt++;
  	} ?>
	</table>
	<br /><br />
<?php
} // end while
?>

</td></tr>
<tr><td>

<table width="100%" border="0">
<tr>
	<td width="80%" align="left"></td>
    <td width="20%" align="center"><br><br>Guru</td>
</tr>
<tr>
	<td colspan="2" align="right">&nbsp;<br /><br /><br /><br /><br /></td>
</tr>
<tr>		
    <td></td>
    <td valign="bottom" align="center"><strong><?=$rowinfo['guru']?></strong>
    <br /><hr /><strong>NIP. <?=$nip?></strong>
</tr>
</table>

</td></tr>
</table>  

<?php CloseDb() ?>

</body>
<script language="javascript">
window.print();
</script>
</html>