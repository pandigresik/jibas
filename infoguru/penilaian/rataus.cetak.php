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
require_once('../include/getheader.php');
OpenDb();

$sem = $_REQUEST['sem'];
$kls = $_REQUEST['kls'];
$nis = $_REQUEST['nis'];
$pel = $_REQUEST['pel'];
$tkt = $_REQUEST['tkt'];
$dp = $_REQUEST['dp'];

$sql = "SELECT departemen FROM pelajaran WHERE replid='$pel'";
$res = QueryDb($sql);
$row = @mysqli_fetch_row($res);
$departemen = $row[0];

$sql = "SELECT tingkat FROM tingkat WHERE replid='$tkt'";
$res = QueryDb($sql);
$row = @mysqli_fetch_row($res);
$tingkat = $row[0];

$sql = "SELECT semester FROM semester WHERE replid='$sem'";
$res = QueryDb($sql);
$row = @mysqli_fetch_row($res);
$semester = $row[0];

$sql = "SELECT nama FROM pelajaran WHERE replid='$pel'";
$res = QueryDb($sql);
$row = @mysqli_fetch_row($res);
$pelajaran = $row[0];

$sql = "SELECT kelas FROM kelas WHERE replid='$kls'";
$res = QueryDb($sql);
$row = @mysqli_fetch_row($res);
$kelas = $row[0];

$sql = "SELECT nama FROM siswa WHERE nis='$nis'";
$res = QueryDb($sql);
$row = @mysqli_fetch_row($res);
$nama = $row[0];

$sql = "SELECT keterangan FROM dasarpenilaian WHERE dasarpenilaian = '".$dp."'";
$res = QueryDb($sql);
$row = @mysqli_fetch_row($res);
$namadp = $row[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS [Cetak Rata Rata Ujian Siswa]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
	<tr>
		<td align="left" valign="top">
		<?=getHeader($departemen)?>
		<center>
		  <font size="4"><strong>LAPORAN RATA RATA UJIAN SISWA</strong></font><br />
		 </center><br /><br />
		<br />
		<table>
		<tr>
			<td><strong>Departemen</strong> </td> 
			<td><strong>:&nbsp;<?=$departemen?></strong></td>
		</tr>
		<tr>
			<td><strong>Tingkat</strong></td>
			<td><strong>:&nbsp;<?=$tingkat?></strong></td>
		</tr>
		<tr>
			<td><strong>Semester</strong></td>
			<td><strong>:&nbsp;<?=$semester?></strong></td>
		</tr>
		<tr>
			<td><strong>Kelas</strong></td>
			<td><strong>:&nbsp;<?=$kelas?></strong></td>
		</tr>
		<tr>
			<td><strong>Pelajaran</strong></td>
			<td><strong>:&nbsp;<?=$pelajaran?></strong></td>
		</tr>
		<tr>
			<td><strong>Dasar Penilaian</strong></td>
			<td><strong>:&nbsp;<?=$namadp?></strong></td>
		</tr>
		<tr>
			<td><strong>Siswa</strong></td>
			<td><strong>:&nbsp;<?=$nis?> - <?=$nama?></strong></td>
		</tr>
		</table>
		<br />
		</span>
		<?php
		$sql = "SELECT j.replid, j.jenisujian, a.replid FROM aturannhb a, jenisujian j WHERE  a.idpelajaran='$pel' AND a.dasarpenilaian='$dp' AND a.idjenisujian=j.replid AND a.idtingkat='$tkt' ORDER BY j.jenisujian";
		//$sql = "SELECT replid, jenisujian FROM jenisujian WHERE idpelajaran = '$pel' ORDER BY jenisujian";
		$res = QueryDb($sql);
		while ($row = @mysqli_fetch_row($res)){
			$rata = 0;
			$numnilai = 0;
			$sql2 = "SELECT u.tanggal,u.deskripsi, n.nilaiujian,u.replid, n.keterangan FROM ujian u, nilaiujian n WHERE u.idkelas = '$kls' AND u.idsemester = '$sem' AND u.idjenis = '".$row[0]."' AND u.replid = n.idujian  AND u.idaturan='".$row[2]."' AND n.nis = '$nis' ORDER BY u.tanggal";
			$res2 = QueryDb($sql2);
			$num2 = @mysqli_num_rows($res2);
			$content = [];
			while ($row2 = @mysqli_fetch_row($res2)){
				$sql3 = "SELECT nilaiRK FROM ratauk WHERE idkelas='$kls' AND idsemester='$sem' AND idujian='".$row2[3]."'";
				$res3 = QueryDb($sql3);
				$row3 = @mysqli_fetch_row($res3);
				$ratauk = $row[0];
				$prosen = round((($row2[2]  - $row3[0]) / $row3[0]) * 100, 2) . "%";
				//if ($prosen>0)
				//	$prosen = "+".$prosen;	
				$numnilai += $row2[2];
				$content[] = [$row2[0], $row2[1], $row2[2], $row3[0], $prosen];	
			}
			
			if ($num2>0)
				$rata = round($numnilai/$num2,2);
			
			$sql2 = "SELECT nilaiAU FROM nau WHERE idkelas = '$kls' AND idsemester = '".$sem."' AND idjenis = '".$row[0]."' AND nis = '$nis' AND idpelajaran = '$pel' AND idaturan='".$row[2]."'";
			$res2 = QueryDb($sql2);
			$row2 = @mysqli_fetch_row($res2);
			$nilaiakhir = $row2[0];	
			?>
			<div style="padding-bottom:10px">
			  <fieldset>
			  <legend><?=$row[1]?></legend>
					<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab">
					  <tr>
						<td width="5%" align="center" class="header">No</td>
						<td width="*" align="center" class="header">Tanggal/Materi</td>
						<td width="12%" align="center" class="header">Nilai</td>
						<td width="12%" align="center" class="header">Rata-rata Kelas</td>
						<td width="12%" align="center" class="header">%</td>
						<td width="12%" align="center" class="header">Rata-rata Nilai</td>
						<td width="12%" align="center" class="header">Nilai Akhir</td>
					  </tr>
					<?php
					if ($num2>0){
						$cnt = 1;
						for ($x=0;$x<count($content);$x++){
						?>
						<tr>
							<td align="center"><?=$cnt?></td>
							<td class="td"><?=LongDateFormat($content[$x][0])?><br /><?=$content[$x][1]?></td>
							<td align="center" class="td"><?=$content[$x][2]?></td>
							<td align="center" class="td"><?=$content[$x][3]?></td>
							<td align="center" class="td"><?=$content[$x][4]?></td>
							<?php if ($x==0){ ?>
							<td align="center" class="td" rowspan="<?=count($content)?>"><?=$rata?></td>
							<td align="center" class="td" rowspan="<?=count($content)?>"><?=$nilaiakhir?></td>
							<?php } ?>
						</tr>
						<?php
						$cnt++;
						}
					} else {
						?>
						<tr>
							<td height="25" colspan="7" align="center" class="miring">Tidak ada data</td>
						</tr>
						<?php
					}	
					?>
			  </table>
			  </fieldset>
			</div>
		<?php
		} 
		?>      
		</td>
	<tr>
</table>
</body>
<?php CloseDb(); ?>
<script language="javascript">
window.print();
</script>
</html>
