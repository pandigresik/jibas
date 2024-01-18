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
$nis=$_REQUEST['nis'];
$bulan_pjg = [1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
OpenDb();
$sql_pel="SELECT pel.nama as namapelajaran, ppsiswa.statushadir as statushadir, pp.tanggal as tanggal, pp.jam as jam, pp.gurupelajaran as guru,ppsiswa.catatan as catatan,pel.replid as pelajaran FROM jbsakad.pelajaran pel, jbsakad.presensipelajaran pp, jbsakad.ppsiswa ppsiswa WHERE ppsiswa.nis='$nis' AND ppsiswa.idpp=pp.replid AND pel.replid=pp.idpelajaran AND ppsiswa.catatan<>'' GROUP BY pel.replid";
$res_pel=QueryDb($sql_pel);
?>

<table width="100%" border="0" cellspacing="5">
  <tr>
    <td width="150" valign="top"><div id="thn_catatan">Pelajaran
      <select name="pel" id="pel" onChange="chg_pel_pp('<?=$nis?>')">
        <?php
	if (@mysqli_num_rows($res_pel)>0){
	while ($row_pel=@mysqli_fetch_array($res_pel)){
	if ($pelajaran=="")
		$pelajaran=$row_pel['pelajaran'];
	?>
        <option value="<?=$row_pel['pelajaran']?>">
          <?=$row_pel['namapelajaran']?>
        </option>
        <?php
	}
	} else {
	?>
        <option value="">Tidak ada data</option>
        <?php
	}
	?>
      </select></div></td>
    <td>
		<div id="content_pp">
		<?php
		if ($pelajaran!=""){
		$sql_pp="SELECT pel.nama as namapelajaran, ppsiswa.statushadir as statushadir, pp.tanggal as tanggal, pp.jam as jam, pp.gurupelajaran as guru,ppsiswa.catatan as catatan FROM jbsakad.pelajaran pel, jbsakad.presensipelajaran pp, jbsakad.ppsiswa ppsiswa WHERE ppsiswa.nis='$nis' AND ppsiswa.idpp=pp.replid AND pel.replid=pp.idpelajaran AND ppsiswa.catatan<>'' AND pp.idpelajaran='$pelajaran'";
		$res_pp=QueryDb($sql_pp);
		  if (@mysqli_num_rows($res_pp)>0){
		?>
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td align="right"><a href="#" onclick="cetak_pp('<?=$nis?>','<?=$pelajaran?>')"><img border="0" src="../images/ico/print.png" />&nbsp;Cetak</a></td>
  </tr>
</table>
<br />
        <?php } ?>
 <table width="100%" border="1" cellspacing="0" class="tab">
  <tr class="header" height="30">
    <td width="4%" align="center">No.</td>
    <td width="5%" align="center">Status</td>
    <td width="25%" align="center">Tanggal-Jam</td>
    <td width="38%" align="center">Guru</td>
  </tr>
  <?php
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
</table><?php } ?>

		</div>	</td>
  </tr>
</table>