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
require_once('../inc/sessionchecker.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../lib/as-diagrams.php');
require_once('../lib/dpupdate.php');
require_once('../inc/numbertotext.class.php');

$NTT = new NumberToText();
$kelas = $_REQUEST['kelas'];
$nis = $_REQUEST['nis'];

OpenDb();
$sql = "SELECT t.departemen, a.tahunajaran, k.kelas, t.tingkat, s.nama, a.tglmulai, a.tglakhir FROM tahunajaran a, kelas k, tingkat t, siswa s WHERE k.idtingkat = t.replid AND k.idtahunajaran = a.replid AND k.replid = '$kelas' AND s.nis = '".$nis."'";  

$result = QueryDB($sql);	
$row = mysqli_fetch_array($result);
$tglmulai = $row['tglmulai'];
$tglakhir = $row['tglakhir'];
$nama = $row['nama'];
$departemen = $row['departemen'];
$tahunajaran = $row['tahunajaran'];
$kls = $row['kelas'];

$sql = "SELECT replid FROM semester WHERE departemen = '$departemen' AND aktif=1";  

$result = QueryDB($sql);	
$row = mysqli_fetch_array($result);
$semester = $row['replid'];

$sql_get_pelajaran_laporan=	"SELECT pel.replid as replid,pel.nama as nama FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel WHERE uji.replid=niluji.idujian AND niluji.nis=sis.nis AND uji.idpelajaran=pel.replid AND uji.idsemester='$semester' AND uji.idkelas='$kelas' AND sis.nis='$nis' ".
								"GROUP BY pel.nama";
//echo $sql_get_pelajaran_laporan;
$result_get_pelajaran_laporan=QueryDb($sql_get_pelajaran_laporan);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Rapor]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>STATISTIK PRESENSI PELAJARAN</strong></font><br />
 </center><br /><br />
<table>
<tr>
	<td width="25%" class="news_content1"><strong>Siswa</strong></td>
    <td class="news_content1">: 
      <?=$nis.' - '.$nama?></td>
</tr>
<tr>
	<td width="25%" class="news_content1"><strong>Departemen</strong></td>
    <td class="news_content1">: 
      <?=$departemen?></td>
</tr>
<tr>
	<td class="news_content1"><strong>Tahun Ajaran</strong></td>
    <td class="news_content1">: 
      <?=$tahunajaran?></td>
</tr>
<tr>
	<td class="news_content1"><strong>Kelas</strong></td>
    <td class="news_content1">: 
      <?=$kls ?></td>
</tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
    <fieldset><legend class="news_title2"><strong>Laporan Hasil Belajar</strong></legend>
<?php 		ShowRapor() ?>		
	 </fieldset>
    </td>
  </tr>
  <tr>
    <td>
    <fieldset>
    <legend class="news_title2"><strong>Komentar Hasil Belajar</strong></legend>
<?php 		ShowKomentar() ?>	
		</fieldset>
    </td>
  </tr>
</table>

	</td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>
</html>
<?php

function ShowKomentar()
{
	global $semester, $kelas, $nis;
	?>
		<table border="1" id="table3" class="tab" width="100%">
		<tr>
            <td width="27%" height="30" align="center" class="header">Pelajaran</td>
            <td width="73%" height="30" align="center" class="header">Komentar</td>
       	</tr>
<?php 		$sql_get_pelajaran_komentar = 
				"SELECT pel.replid as replid,pel.nama as nama 
				 FROM infonap info, komennap komen, siswa sis, pelajaran pel 
				 WHERE info.replid=komen.idinfo AND komen.nis=sis.nis AND info.idpelajaran=pel.replid 
				 AND info.idsemester='$semester' AND info.idkelas='$kelas' AND sis.nis='$nis' 
				 GROUP BY pel.nama";
			$result_get_pelajaran_komentar = QueryDb($sql_get_pelajaran_komentar);
			$cntpel_komentar=1;
			while ($row_get_pelajaran_komentar=@mysqli_fetch_array($result_get_pelajaran_komentar))
			{
				$sql_get_komentar = "SELECT k.komentar 
		          FROM jbsakad.komennap k, jbsakad.infonap i 
				 WHERE k.nis='$nis' AND i.idpelajaran='".$row_get_pelajaran_komentar['replid']."' AND i.replid = k.idinfo 
				   AND i.idsemester = '$semester' AND i.idkelas = '".$kelas."'";
				$result_get_komentar=QueryDb($sql_get_komentar);
				$row_get_komentar=@mysqli_fetch_row($result_get_komentar); ?>
        <tr>
        	<td height="25"><?=$row_get_pelajaran_komentar['nama']?></td>
        	<td height="25"><?=$row_get_komentar[0]?></td>
        </tr>
	<?php 		$cntpel_komentar++;
			}
	?>
		</table>
<?php      
}

function ShowRapor()
{
	global $semester, $kelas;
	
	$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
	  	      FROM infonap i, nap n, aturannhb a, dasarpenilaian d
			 WHERE i.replid = n.idinfo
			   AND i.idsemester = '$semester' 
			   AND i.idkelas = '$kelas'
			   AND n.idaturan = a.replid 	   
			   AND a.dasarpenilaian = d.dasarpenilaian
			   AND d.aktif = 1";
	$res = QueryDb($sql);
	$naspek = mysqli_num_rows($res); 
	
	if ($naspek > 2)
		ShowRaporRow();
	else
		ShowRaporColumn();
}

function ShowRaporColumn()
{
	global 	$semester, $kelas, $nis, $NTT;
	$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
		  	    FROM infonap i, nap n, aturannhb a, dasarpenilaian d
			   WHERE i.replid = n.idinfo AND n.nis = '$nis' 
			     AND i.idsemester = '$semester' 
			     AND i.idkelas = '$kelas'
			     AND n.idaturan = a.replid 	   
			     AND a.dasarpenilaian = d.dasarpenilaian
			     AND d.aktif = 1";
	$res = QueryDb($sql);
	$i = 0;
	while($row = mysqli_fetch_row($res))
	{
		$aspekarr[$i++] = [$row[0], $row[1]];
	} ?>  
	<table width="100%" border="1" class="tab" id="table" bordercolor="#000000">
	<tr>
		<td width="15%" rowspan="2" class="header"><div align="center">Pelajaran</div></td>
		<td width="10%" rowspan="2" class="header"><div align="center">KKM</div></td>
<?php 	for($i = 0; $i < count($aspekarr); $i++)
			echo "<td class='header' colspan='3' align='center' width='18%'>" . $aspekarr[$i][1] . "</td>"; ?>
		<td width="15%" rowspan="2" class="header"><div align="center">Predikat</div></td>
  	</tr>
	<tr>
<?php for($i = 0; $i < count($aspekarr); $i++)
		echo "<td class='header' align='center' width='7%'>Angka</td>
			   <td class='header' align='center' width='7%'>Huruf</td>
				<td class='header' align='center' width='20%'>Terbilang</td>"; ?>   
   </tr>
<?php $sql = "SELECT pel.replid, pel.nama
				 FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel 
				WHERE uji.replid = niluji.idujian 
				  AND niluji.nis = sis.nis 
				  AND uji.idpelajaran = pel.replid 
				  AND uji.idsemester = '$semester'
				  AND uji.idkelas = '$kelas'
				  AND sis.nis = '$nis' 
			GROUP BY pel.nama";
	$respel = QueryDb($sql);
	while($rowpel = mysqli_fetch_row($respel))
	{
		$idpel = $rowpel[0];
		$nmpel = $rowpel[1];
		
		$sql = "SELECT nilaimin 
					 FROM infonap
					WHERE idpelajaran = '$idpel'
					  AND idsemester = '$semester'
				     AND idkelas = '".$kelas."'";
		$res = QueryDb($sql);
		$row = mysqli_fetch_row($res);
		$nilaimin = $row[0];
				
		echo "<tr>";
		echo "<td align='left'>$nmpel</td>";
		echo "<td align='center'>$nilaimin</td>";
		
		for($i = 0; $i < count($aspekarr); $i++)
		{
			$na = "";
			$nh = "";
		
			$asp = $aspekarr[$i][0];
		
			$sql = "SELECT nilaiangka, nilaihuruf
						 FROM infonap i, nap n, aturannhb a 
						WHERE i.replid = n.idinfo 
						  AND n.nis = '$nis' 
						  AND i.idpelajaran = '$idpel' 
						  AND i.idsemester = '$semester' 
						  AND i.idkelas = '$kelas'
						  AND n.idaturan = a.replid 	   
						  AND a.dasarpenilaian = '".$asp."'";
			$res = QueryDb($sql);
			if (mysqli_num_rows($res) > 0)
			{
				$row = mysqli_fetch_row($res);
				$na = $row[0];
				$nh = $row[1];
			}
			$say = $NTT->Convert($na);
			echo "<td align='center'>$na</td><td align='center'>$nh</td><td align='left'>$say</td>"; 
		} 
		$pred = "";
		$sql = "SELECT predikat 
				  FROM infonap i, komennap k
				 WHERE i.replid = k.idinfo
				   AND k.nis = '$nis' 
				   AND i.idpelajaran = '$idpel' 
				   AND i.idsemester = '$semester' 
				   AND i.idkelas = '".$kelas."'";
		$res = QueryDb($sql);
		if (mysqli_num_rows($res) > 0)
		{
			$row = mysqli_fetch_row($res);
			$tmp = (int)$row[0];
			
			$pred = match ($tmp) {
       4 => "Istimewa",
       3 => "Baik",
       2 => "Cukup",
       1 => "Kurang",
       0 => "Buruk",
       default => "Baik",
   };
		}			
		echo "<td align='left'>$pred</td>"; 
		echo "</tr>";
	}
	echo "</table>";
}

function ShowRaporRow()
{ 
	global 	$semester, $kelas, $nis, $NTT; ?>
    <table width="100%" border="1" class="tab" bordercolor="#000000">
    <tr>
        <td width="4%" rowspan="2" class="header"><div align="center">No</div></td>
        <td width="12%" rowspan="2" class="header"><div align="center">Pelajaran</div></td>
        <td width="7%" rowspan="2" class="header"><div align="center">KKM</div></td>
        <td width="12%" rowspan="2" class="header"><div align="center">Aspek<br>Penilaian</div></td>
        <td width="35%" colspan="3" class="header"><div align="center">Nilai</div></td>
    </tr>
    <tr>
        <td width="5%" class="header"><div align="center">Angka</div></td>
        <td width="5%" class="header"><div align="center">Huruf</div></td>
        <td width="15%" class="header"><div align="center">Terbilang</div></td>
    </tr>
   
<?php 	$sql = "SELECT pel.replid, pel.nama
              FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel 
             WHERE uji.replid = niluji.idujian 
               AND niluji.nis = sis.nis 
               AND uji.idpelajaran = pel.replid 
               AND uji.idsemester = '$semester'
               AND uji.idkelas = '$kelas'
               AND sis.nis = '$nis' 
         GROUP BY pel.nama";    
    $res = QueryDb($sql);
    $i = 0;
    while($row = mysqli_fetch_row($res))
    {
        $pelarr[$i++] = [$row[0], $row[1]];
    }
    
    for($i = 0; $i < count($pelarr); $i++)
    {
        $idpel = $pelarr[$i][0];
        $nmpel = $pelarr[$i][1];
        
        $sql = "SELECT nilaimin 
                 FROM infonap
                WHERE idpelajaran = '$idpel'
                  AND idsemester = '$semester'
                  AND idkelas = '".$kelas."'";
        $res = QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $nilaimin = $row[0];
        
        $sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan 
                FROM infonap i, nap n, aturannhb a, dasarpenilaian d 
               WHERE i.replid = n.idinfo AND n.nis = '$nis' 
                 AND i.idpelajaran = '$idpel' 
                 AND i.idsemester = '$semester' 
                 AND i.idkelas = '$kelas' 
                 AND n.idaturan = a.replid  	   
                 AND a.dasarpenilaian = d.dasarpenilaian
                 AND d.aktif = 1";
        $res = QueryDb($sql);				 
        $aspekarr = [];				 
        $j = 0;
        while($row = mysqli_fetch_row($res))
        {
            $na = "";
            $nh = "";
            $asp = $row[0];
            
            $sql = "SELECT nilaiangka, nilaihuruf
                      FROM infonap i, nap n, aturannhb a 
                     WHERE i.replid = n.idinfo 
                       AND n.nis = '$nis' 
                       AND i.idpelajaran = '$idpel' 
                       AND i.idsemester = '$semester' 
                       AND i.idkelas = '$kelas'
                       AND n.idaturan = a.replid 	   
                       AND a.dasarpenilaian = '".$asp."'";
            $res2 = QueryDb($sql);
            if (mysqli_num_rows($res2) > 0)
            {
                $row2 = mysqli_fetch_row($res2);
                $na = $row2[0];
                $nh = $row2[1];
            }
            
            $aspekarr[$j++] = [$row[0], $row[1], $na, $nh];
        } 
        $naspek = count($aspekarr);
        
        if ($naspek > 0)
        { ?>
            <tr height="20">
                <td rowspan="<?=$naspek?>" align="center"><?=$i + 1?></td>
                <td rowspan="<?=$naspek?>" align="left"><?=$nmpel?></td>
                <td rowspan="<?=$naspek?>" align="center"><?=$nilaimin?></td>
                <td align="left"><?=$aspekarr[0][1]?></td>
                <td align="center"><?=$aspekarr[0][2]?></td>
                <td align="center"><?=$aspekarr[0][3]?></td>
                <td align="left"><?=$NTT->Convert($aspekarr[0][2])?></td>
            </tr>
<?php 		for($k = 1; $k < $naspek; $k++)
            { ?>
                <tr height="20">
                    <td align="left"><?=$aspekarr[$k][1]?></td>
                    <td align="center"><?=$aspekarr[$k][2]?></td>
                    <td align="center"><?=$aspekarr[$k][3]?></td>
                    <td align="left"><?=$NTT->Convert($aspekarr[$k][2])?></td>
                </tr>
<?php 		} // end for
        } 
        else
        { ?>
            <tr height="20">
                <td align="center"><?=$i + 1?></td>
                <td align="left"><?=$nmpel?></td>
                <td align="center"><?=$nilaimin?></td>
                <td align="left">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
            </tr>		
<?php 	}// end if
    } 
	 echo "</table>";
}
?>

<?php
CloseDb();
?>