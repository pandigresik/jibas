<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 2.6.0 (January 14, 2012)
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
require_once('../inc/errorhandler.php');
require_once('../inc/sessioninfo.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/db_functions.php');

$nis="";
$pelajaran="";
$nis=$_REQUEST['nis'];
$pelajaran=$_REQUEST['pelajaran'];

$bulan_pjg = [1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

OpenDb();
$sql_pp="SELECT pel.nama as namapelajaran, ppsiswa.statushadir as statushadir, pp.tanggal as tanggal, pp.jam as jam, pp.gurupelajaran as guru,ppsiswa.catatan as catatan FROM jbsakad.pelajaran pel, jbsakad.presensipelajaran pp, jbsakad.ppsiswa ppsiswa WHERE ppsiswa.nis='$nis' AND ppsiswa.idpp=pp.replid AND pel.replid=pp.idpelajaran AND ppsiswa.catatan<>'' AND pp.idpelajaran='$pelajaran'";
$res_pp=QueryDb($sql_pp);
?>
<table width="100%" border="1" cellspacing="0" class="tab">
<tr class="header" height="30">
    <td width="4%" align="center">No.</td>
    <td width="5%" align="center">Status</td>
    <td width="25%" align="center">Tanggal-Jam</td>
    <td width="38%" align="center">Guru</td>
</tr>
<?php
if (@mysqli_num_rows($res_pp)>0)
{
    $cnt=1;
	while ($row_pp=@mysqli_fetch_array($res_pp))
    {
    	$a="";
    	if ($cnt%2==0)
    		$a="style='background-color:#FFFFCC'"; ?>
  <tr height="25" <?=$a?> >
    <td align="center" rowspan="2"><?=$cnt?></td>
    <td align="center">
	<?php
	switch ($row_pp['statushadir']){
	case 0:
		echo "Hadir";
		break;
	case 1:
		echo "Sakit";
		break;
	case 2:
		echo "Ijin";
		break;
	case 3:
		echo "Alpa";
		break;
	case 4:
		echo "Cuti";
		break;
	}
	?>
	</td>
    <td><?=ShortDateFormat($row_pp['tanggal'])."-".$row_pp['jam']?></td>
    <td>[<?=$row_pp['guru']?>]&nbsp;
	<?php
	$res_gr=QueryDb("SELECT nama FROM jbssdm.pegawai WHERE nip='".$row_pp['guru']."'");
	$row_gr=@mysqli_fetch_array($res_gr);
	echo $row_gr['nama'];
	?>
	</td>
    </tr>
    <tr <?=$a?>>
    <td colspan="3"><?=$row_pp['catatan']?></td>
  </tr>
  <?php
  $cnt++;
  } } else { ?>
  ?>
  <tr>
    <td align="center" colspan="5">Tidak ada Catatan</td>
  </tr>
  <?php
  } ?>
</table>
<?php
CloseDb();
?>