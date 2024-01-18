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
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/common.php');
require_once('../include/db_functions.php');

$satker = "all";
if (isset($_REQUEST['satker']))
	$satker = $_REQUEST['satker'];	
	
/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=DUK_Pangkat.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<style type="text/css">
<!--
.style1 {
	font-size: 24px;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<?php
OpenDb();

$arridpeg;

$namasatker = "Semua";
if ($satker == "all")
{
	$sql = "SELECT p.replid FROM jbssdm.pegawai p, jbssdm.peglastdata pl, jbssdm.peggol pg, jbssdm.golongan g 
    	    WHERE p.aktif=1 AND p.nip = pl.nip AND pl.idpeggol = pg.replid AND pg.golongan = g.golongan 
			ORDER BY g.urutan DESC";
}
else
{
	$sql = "SELECT nama FROM jbssdm.satker WHERE satker='$satker'";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$namasatker = $row[0];
	
	$sql = "SELECT p.replid FROM jbssdm.pegawai p, jbssdm.peglastdata pl, jbssdm.peggol pg, jbssdm.golongan g, jbssdm.pegjab pj, jbssdm.jabatan j 
    	    WHERE p.aktif=1 AND p.nip = pl.nip AND pl.idpeggol = pg.replid AND pg.golongan = g.golongan 
			AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND j.satker = '$satker' 
			ORDER BY g.urutan DESC";
}
	
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) 
	$arridpeg[] = $row[0];
	
$ndata = mysqli_num_rows($result);
?>

<table border="1" cellpadding="2" cellspacing="0" width="1395" class="tab" id="table">
    <tr height="40">
      <td height="40" colspan="17" align="center" valign="top"><p class="style1">Daftar Urut Kepangkatan Pegawai Negeri Sipil</p></td>
    <tr height="20">
      <td height="20" colspan="17" align="left" valign="top">Satuan Kerja: <?=$namasatker?></td>
    </tr>
  <tr height="20">
    <td  width="20" rowspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td  width="160" rowspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Nama</strong></td>
    <td  width="120" rowspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>NIP</strong></td>
	<td  width="120" colspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Pangkat</strong></td>
    <td  width="200" colspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Jabatan</strong></td>
    <td  width="100" colspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Masa Kerja</strong></td>
    <td  width="100" colspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Diklat</strong></td>
    <td  width="125" colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Pendidikan</strong></td>
    <td  width="40" rowspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Usia</strong></td>
    <td  width="120" rowspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Kelahiran</strong></td>
	<td  width="200" rowspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Ket.</strong></td>
  </tr>
<tr height="20">
  <td  width="120" align="center" valign="middle" bgcolor="#CCCCCC"><strong>GOL</strong></td>
    <td  width="60" align="center" valign="middle" bgcolor="#CCCCCC"><strong>TMT</strong></td>
    
    <td  width="200" align="center" valign="middle" bgcolor="#CCCCCC"><strong>NAMA</strong></td>
    <td  width="60" align="center" valign="middle" bgcolor="#CCCCCC"><strong>TMT</strong></td>
    
    <td  width="100" align="center" valign="middle" bgcolor="#CCCCCC"><strong>GOL</strong></td>
    <td  width="50" align="center" valign="middle" bgcolor="#CCCCCC"><strong>SEL</strong></td>
    
    <td  width="100" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Nama</strong></td>
    <td  width="50" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Th</strong></td>
    
    <td  width="125" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Nama</strong></td>
    <td  width="30" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Lls</strong></td>
    <td  width="30" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Tk</strong></td>
  </tr>

<?php
for($i = 0; $i < $ndata; $i++) {
	$idpeg = $arridpeg[$i];
	if (strlen(trim((string) $idpeg)) == 0)
		continue;
	
	$cnt = $i;
	
	$sql = "SELECT CONCAT(p.gelarawal, ' ', p.nama, ' ', p.gelarakhir) AS pnama, p.nip, pg.golongan, 
				   DATE_FORMAT(pg.tmt, '%m %y') AS tgltmtgol, DATEDIFF(now(), pg.tmt) AS tmtgol,
				   j.jabatan, DATE_FORMAT(pj.tmt, '%d-%m-%y') AS tmtjab, DATEDIFF(now(), mulaikerja) AS tglmulai,
				   pl.idpegdiklat, pj.jenis,
				   ps.sekolah, ps.lulus, ps.tingkat,
				   DATEDIFF(now(), tgllahir) AS difflahir,
				   tmplahir, DATE_FORMAT(tgllahir, '%d-%m-%Y') AS tgllahir, p.keterangan
	          FROM jbssdm.pegawai p
			  LEFT JOIN jbssdm.peglastdata pl
			    ON p.nip = pl.nip
			  LEFT JOIN jbssdm.peggol pg
			    ON pl.idpeggol = pg.replid
			  LEFT JOIN jbssdm.golongan g
			    ON pg.golongan = g.golongan
			  LEFT JOIN jbssdm.pegjab pj
			    ON pl.idpegjab = pj.replid
			  LEFT JOIN jbssdm.jabatan j
			    ON pj.idjabatan = j.replid
			  LEFT JOIN jbssdm.pegsekolah ps	
			    ON pl.idpegsekolah = ps.replid
			 WHERE p.replid = $idpeg
			   AND p.aktif = 1 
			 ORDER BY g.urutan DESC, p.nama ASC";
			
	$result = QueryDb($sql);			
	$row = mysqli_fetch_array($result);
?>
<tr>
	<td align="center" valign="middle"><?=++$cnt?></td>
    <td align="left" valign="middle"><?=$row['pnama']?></td>
    <td align="left" valign="middle"><?=$row['nip']?></td>
    <td align="center" valign="middle"><?=$row['golongan']?></td>
    <td align="center" valign="middle"><?=$row['tgltmtgol']?></td>
    <td align="left" valign="middle"><?=$row['jenis'] . " " . $row['jabatan']?></td>
    <td align="center" valign="middle"><?=$row['tmtjab']?></td>
    <td align="center" valign="middle"><?php
    	$thn = floor($row['tmtgol'] / 365);
		$bln = $row['tmtgol'] % 365;
		$bln = floor($bln / 30);
		echo "$thn-$bln.";
	?></td>
    <td align="center" valign="middle">
	<?php $thn = floor($row['tglmulai'] / 365);
		$bln = $row['tglmulai'] % 365;
		$bln = floor($bln / 30);
		echo "$thn-$bln.";
	?>    </td>
    <?php
	$diklat = "&nbsp;";
	$thndiklat = "&nbsp;";
	if ($row['idpegdiklat'] != NULL) {
		$idpegdiklat = $row['idpegdiklat'];
		$sql = "SELECT d.diklat, pd.tahun FROM jbssdm.pegdiklat pd, jbssdm.diklat d WHERE pd.replid=$idpegdiklat AND pd.iddiklat=d.replid";
		$rs = QueryDb($sql);
		$rw = mysqli_fetch_row($rs);
		$diklat = $rw[0];
		$thndiklat = $rw[1];
	};
	?>
    <td align="center" valign="middle"><?=$diklat?></td>
    <td align="center" valign="middle"><?=$thndiklat?></td>
    <td align="center" valign="middle"><?=$row['sekolah']?></td>
    <td align="center" valign="middle"><?=$row['lulus']?></td>
    <td align="center" valign="middle"><?=$row['tingkat']?></td>
    <td align="center" valign="middle">
	<?php $thn = floor($row['difflahir'] / 365);
		$bln = $row['difflahir'] % 365;
		$bln = floor($bln / 30);
		echo "$thn,$bln";
	?>    </td>
    <td align="left" valign="middle"><?=$row['tmplahir'] . ", " . $row['tgllahir'] ?></td>
	<td align="left" valign="middle"><?=$row['keterangan']?></td>
</tr>
<?php
}
?>
</table>

</body>
</html>

