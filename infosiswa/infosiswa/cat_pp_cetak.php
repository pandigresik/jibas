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
$nis=$_REQUEST['nis'];
$pelajaran=$_REQUEST['pelajaran'];
OpenDb();
$sql = "SELECT departemen FROM pelajaran WHERE replid='$pelajaran'";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$departemen = $row[0];
CloseDb();
$bulan_pjg = [1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
OpenDb();
$sql_pp="SELECT pel.nama as namapelajaran, ppsiswa.statushadir as statushadir, pp.tanggal as tanggal, pp.jam as jam, pp.gurupelajaran as guru, ppsiswa.catatan as catatan FROM jbsakad.pelajaran pel, jbsakad.presensipelajaran pp, jbsakad.ppsiswa ppsiswa WHERE ppsiswa.nis='$nis' AND ppsiswa.idpp=pp.replid AND pel.replid=pp.idpelajaran AND ppsiswa.catatan<>'' AND pp.idpelajaran='$pelajaran'";
$res_pp=QueryDb($sql_pp);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS INFOGURU [Cetak Catatan Presensi Pelajaran]</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tables.js"></script>
</head>
<body >
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>Catatan Presensi Pelajaran</strong></font><br />
 </center><br /><br />
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td width="7%"><strong>Siswa</strong></td>
    <td width="93%">
	  <?php
	OpenDb();
	$r=QueryDb("SELECT nis,nama FROM jbsakad.siswa WHERE nis='$nis'");
	$row=@mysqli_fetch_array($r);
	echo "[".$row['nis']."] ".$row['nama'];
	CloseDb();
    ?>
	</td>
  </tr>
  <tr>
    <td><strong>Pelajaran</strong></td>
    <td>
      <?php
	OpenDb();
	$r=QueryDb("SELECT nama FROM jbsakad.pelajaran WHERE replid='$pelajaran'");
	$row=@mysqli_fetch_array($r);
	echo $row['nama'];
	CloseDb();
    ?>
    </td>
  </tr>
</table>
<br />
<table width="100%" border="1" cellspacing="0" class="tab">
  <tr class="header" height="30">
    <td width="4%" align="center">No.</td>
    <td width="5%" align="center">Status</td>
    <td width="25%" align="center">Tanggal-Jam</td>
    <td width="38%" align="center">Guru</td>
  </tr>
  <?php
  OpenDb();
  if (@mysqli_num_rows($res_pp)>0){
	  $cnt=1;
	while ($row_pp=@mysqli_fetch_array($res_pp)){
	$a="";
	if ($cnt%2==0)
		$a="style='background-color:#FFFFCC'";
  ?>
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
</td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>