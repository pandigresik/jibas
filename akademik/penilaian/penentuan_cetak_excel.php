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
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../library/dpupdate.php');

/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Penentuan_Rapor.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

OpenDb();

if(isset($_REQUEST["tahun"]))
	$tahun = $_REQUEST["tahun"];
if(isset($_REQUEST["departemen"]))
	$departemen = $_REQUEST["departemen"];
if(isset($_REQUEST["tingkat"]))
	$tingkat = $_REQUEST["tingkat"];
if(isset($_REQUEST["pelajaran"]))
	$pelajaran = $_REQUEST["pelajaran"];
if(isset($_REQUEST["nip"]))
	$nip = $_REQUEST["nip"];
if(isset($_REQUEST["kelas"]))
	$kelas = $_REQUEST["kelas"];
if(isset($_REQUEST["semester"]))
	$semester = $_REQUEST["semester"];
if(isset($_REQUEST["aspek"]))
	$aspek = $_REQUEST["aspek"];
if(isset($_REQUEST["aspekket"]))
	$aspekket = $_REQUEST["aspekket"];	

$sql = "SELECT nama FROM pelajaran WHERE replid = '".$pelajaran."'";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$namapel = $row[0];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
//cek keberadaan nap dan idinfo
$idinfo = 0;
$nap_ada = 0;
$sql = "SELECT replid FROM jbsakad.infonap WHERE idpelajaran='$pelajaran' AND idsemester='$semester' AND idkelas='$kelas'";
$res = QueryDb($sql);
if (mysqli_num_rows($res) > 0)
{
	$row = mysqli_fetch_row($res);
	$idinfo = $row[0];
	
	$sql = "SELECT COUNT(n.replid)
			  FROM jbsakad.aturannhb a, kelas k, nap n
			 WHERE n.idaturan = a.replid AND a.nipguru = '$nip' AND a.idtingkat = k.idtingkat AND k.replid = '$kelas'
			   AND a.idpelajaran = '$pelajaran' AND a.dasarpenilaian='$aspek' AND a.aktif = 1
			   AND n.idinfo = '$idinfo' ";		   
	$res = QueryDb($sql);
	$row = mysqli_fetch_row($res);
	$nap_ada = $row[0];
}

// Hitung jumlah bobot dan banyaknya aturan
$sql = "SELECT SUM(bobot) as bobotPK, COUNT(a.replid) 
		  FROM jbsakad.aturannhb a, kelas k 
		 WHERE a.nipguru='$nip' AND a.idtingkat=k.idtingkat AND k.replid='$kelas' 
		   AND a.idpelajaran='$pelajaran' AND a.dasarpenilaian='$aspek' AND a.aktif=1";
$res = QueryDb($sql);
$row = @mysqli_fetch_row($res);
$bobot_PK = $row[0];
$jum_nhb = $row[1];

// get jumlah pengujian
$sql = "SELECT j.jenisujian as jenisujian, a.bobot as bobot, a.replid, a.idjenisujian 
		  FROM jbsakad.aturannhb a, jbsakad.jenisujian j, kelas k 
		 WHERE a.idtingkat=k.idtingkat AND k.replid = '$kelas' AND a.nipguru='$nip' 
		   AND a.idpelajaran='$pelajaran' AND a.dasarpenilaian='$aspek' 
		   AND a.idjenisujian=j.replid AND a.aktif = 1 
	  ORDER BY a.replid"; 
$result_get_aturan_PK = QueryDb($sql);
$jum_PK = @mysqli_num_rows($result_get_aturan_PK);

//Ambil nilai grading
$sql = "SELECT grade 
		  FROM aturangrading a, kelas k 
		 WHERE a.idpelajaran = '$pelajaran' AND a.idtingkat = k.idtingkat AND k.replid = '$kelas' 
		   AND a.dasarpenilaian = '$aspek' AND a.nipguru = '$nip'
	  ORDER BY nmin DESC";
$res = QueryDb($sql);
$cntgrad = 0;
while ($row = @mysqli_fetch_array($res)) 
{
	$grading[$cntgrad] = $row['grade'];
	$cntgrad++;
}
?>

<font style="font-size:18px; color:#999; font-family:Verdana, Geneva, sans-serif"><strong><?=$namapel?></strong> - <?=$aspekket?></font><br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    
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
			 WHERE idkelas='$kelas' AND aktif=1 
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
				     WHERE n.idpelajaran = = '".$pelajaran."' AND n.idkelas='$kelas' AND n.nis='".$row_siswa['nis']."' AND n.idsemester='$semester' 
				       AND n.idjenis='".$value[2]."' AND n.idaturan=a.replid AND a.replid='".$value[0]."'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_array($res);
			echo "<td align='center'>" . $row['nilaiujian'] . "</td>";
		}  	?>
	   	<td align="center"><strong>
	<?php 	$ext_idinfo = "";
		if ($idinfo != "")
			$ext_idinfo = " AND i.replid = '".$idinfo."'";
			
		$sql = "SELECT n.nilaihuruf, n.nilaiangka, i.nilaimin 
				  FROM jbsakad.nap n, jbsakad.aturannhb a, jbsakad.infonap i 
				 WHERE n.idinfo = i.replid 
				   AND n.nis = '".$row_siswa['nis']."' 
				   AND i.idpelajaran = '$pelajaran' 
				   AND i.idsemester = '$semester' 
				   AND i.idkelas = '$kelas'
				   AND n.idaturan = a.replid   
				   AND a.dasarpenilaian = '$aspek' 
				       $ext_idinfo";
		$res = QueryDb($sql);
		$nilaiangka_pemkonsep = @mysqli_num_rows($res);
		$row_get_nap_pemkonsep = @mysqli_fetch_row($res);
		
		if ($nilaiangka_pemkonsep == 0) 
		{		
			//Belum ada data nilai di database
			$jumlah = 0;
			foreach ($ujian as $value) 
			{		
				$sql = "SELECT n.nilaiAU 
						  FROM jbsakad.nau n, jbsakad.aturannhb a 
						 WHERE n.idkelas = = '".$kelas."' AND n.nis = '".$row_siswa['nis']."' 
						   AND n.idsemester = '$semester' AND n.idpelajaran = '$pelajaran'
						   AND n.idjenis = '".$value[2]."' AND n.idaturan = a.replid 
						   AND a.dasarpenilaian = '".$aspek."'";
				$res = QueryDb($sql);
				$row = @mysqli_fetch_array($res);
				$nau = $row["nilaiAU"];
				$bobot = $value[1];
				$nap = $nau * $bobot;
				$jumlah = $jumlah + $nap;
			}
			$nilakhirpk = round($jumlah / $bobot_PK, 2); ?>		
            
            <?=$nilakhirpk?>
            
	<?php } 
		else 
		{ 
			//Ada data nilai di database
			$nilakhirpk = $row_get_nap_pemkonsep[1];
			$warna = "";
			if ($nilakhirpk < $row_get_nap_pemkonsep[2])
				$warna = "onMouseOver=\"showhint('Nilai di bawah nilai standar kelulusan', this, event, '100px')\" class='text_merah'";	?>
                
				<?=$nilakhirpk?>
                 
	<?php 	} ?>
			</strong>
    	</td>
        <!-- Grading Pemahaman konsep -->
        <td height="25" align="center"><strong>
	<?php  if ($nilakhirpk == "") 
		{
			$grade_PK = $grading[count($grading)-1];
		} 
		else 
		{
			if ($nilaiangka_pemkonsep == 0) 
			{ 
				$sql = "SELECT grade 
				          FROM aturangrading a, kelas k 
					 	 WHERE a.idpelajaran = '$pelajaran' AND a.idtingkat = k.idtingkat AND k.replid = '$kelas' 
						   AND a.dasarpenilaian = '$aspek' AND a.nipguru = '$nip' AND '$nilakhirpk' BETWEEN a.nmin AND a.nmax";
				$res = QueryDb($sql);
				$row = @mysqli_fetch_array($res);
				$grade_PK = $row['grade'];
			} 
			else 
			{
				$grade_PK = $row_get_nap_pemkonsep[0];
			} 
		}	
	?>
    
	    <?= $grade_PK ?>
         
		</strong>
        </td>
  	  </tr>
<?php  	$cnt++;
  	} ?>
</table>

</body>
</html>
<?php
CloseDb();
?>