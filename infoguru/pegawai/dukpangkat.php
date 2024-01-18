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
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

$pagenum = 1;
if (isset($_REQUEST['pagenum']))
	$pagenum = (int)$_REQUEST['pagenum'];

$PAGING_SIZE = 20;
if (isset($_REQUEST['PAGING_SIZE']))
	$PAGING_SIZE = (int)$_REQUEST['PAGING_SIZE'];	

$satker = "all";
if (isset($_REQUEST['satker']))
	$satker = $_REQUEST['satker'];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function ChangeSort(ob, dir) {
	var satker = document.getElementById("satker").value;
	var pagenum = document.getElementById("pagenum").value;
	var PAGING_SIZE = document.getElementById("PAGING_SIZE").value;
	document.location.href = "dukpangkat.php?pagenum="+pagenum+"&PAGING_SIZE="+PAGING_SIZE+"&satker="+satker;
}

function ChangePage() {
	var satker = document.getElementById("satker").value;
	var pagenum = document.getElementById("pagenum").value;
	var PAGING_SIZE = document.getElementById("PAGING_SIZE").value;
	document.location.href = "dukpangkat.php?pagenum="+pagenum+"&PAGING_SIZE="+PAGING_SIZE+"&satker="+satker;
}

function MoveToPage(pagenum) {
	var satker = document.getElementById("satker").value;
	var PAGING_SIZE = document.getElementById("PAGING_SIZE").value;
	document.location.href = "dukpangkat.php?pagenum="+pagenum+"&PAGING_SIZE="+PAGING_SIZE+"&satker="+satker;
}

function refresh() {
	var satker = document.getElementById("satker").value;
	var pagenum = document.getElementById("pagenum").value;
	var PAGING_SIZE = document.getElementById("PAGING_SIZE").value;
	document.location.href = "dukpangkat.php?pagenum="+pagenum+"&PAGING_SIZE="+PAGING_SIZE+"&satker="+satker;
}

function chg_paging_size() {
	var satker = document.getElementById("satker").value;
	var PAGING_SIZE = document.getElementById("PAGING_SIZE").value;
	document.location.href = "dukpangkat.php?pagenum=1&PAGING_SIZE="+PAGING_SIZE+"&satker="+satker;
}

function Cetak_Excel() {
	var satker = document.getElementById("satker").value;
	newWindow('duk_pangkat_excel.php?satker='+satker, 'CetakDUKExcel','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function Cetak() {
	var satker = document.getElementById("satker").value;
	var PAGING_SIZE = document.getElementById("PAGING_SIZE").value;
	newWindow('dukpangkat_cetak.php?pagenum=<?=$pagenum?>&PAGING_SIZE='+PAGING_SIZE+"&satker="+satker, 'CetakDUK','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function ChangeSatKer() {
	var satker = document.getElementById("satker").value;
	var PAGING_SIZE = document.getElementById("PAGING_SIZE").value;
	document.location.href = "dukpangkat.php?pagenum=1&PAGING_SIZE="+PAGING_SIZE+"&satker="+satker;
}
</script>
<style type="text/css">
<!--
.style1 {font-size: 11px}
-->
</style>
</head>

<body>
<?php
OpenDb();

$arridpeg;

if ($satker == "all")
{
$sql = "SELECT p.replid FROM jbssdm.pegawai p, jbssdm.peglastdata pl, jbssdm.peggol pg, jbssdm.golongan g 
        WHERE p.aktif=1 AND p.nip = pl.nip AND pl.idpeggol = pg.replid AND pg.golongan = g.golongan 
		ORDER BY g.urutan DESC";
}
else
{
$sql = "SELECT p.replid FROM jbssdm.pegawai p, jbssdm.peglastdata pl, jbssdm.peggol pg, jbssdm.golongan g, jbssdm.pegjab pj, jbssdm.jabatan j 
        WHERE p.aktif=1 AND p.nip = pl.nip AND pl.idpeggol = pg.replid AND pg.golongan = g.golongan 
		AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND j.satker = '$satker' 
		ORDER BY g.urutan DESC";
}

$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) 
	$arridpeg[] = $row[0];

$ndata = mysqli_num_rows($result);
$npage = floor($ndata / $PAGING_SIZE);
if (($ndata % $PAGING_SIZE) != 0) 
	$npage++;
	
$minrownum = ($pagenum - 1) * $PAGING_SIZE + 1;
$maxrownum = $pagenum * $PAGING_SIZE;
?>

<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Daftar Urut Kepangkatan</font><br />
        <a href="pegawai.php">Kepegawaian</a> &gt; Daftar Urut Kepangkatan<br />
    </td>
</tr>
</table>

<br /><br />
<table border="0" cellpadding="2" cellspacing="0" width="1000">
<tr>
	<td width="100%" colspan="2" align="left">
	Satuan Kerja:    
    <select name="satker" id="satker" onchange="JavaScript:ChangeSatKer()">
	    <option value="all" <?=StringIsSelected("all", $satker)?> >(semua)</option>
<?php $sql = "SELECT satker, nama FROM jbssdm.satker ORDER BY replid";    
	$result = QueryDb($sql);
	while($row = mysqli_fetch_row($result)) { ?>
		<option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $satker)?> ><?=$row[1]?></option>
<?php } ?>
    </select>
    </td>
</tr>
<tr>
<td  width="50%" align="left">
Halaman
<?php if ($pagenum != 1) { ?>
<input type="button" id="Left" class="but" onclick="MoveToPage(<?=$pagenum-1?>)" value=" < ">
<?php } ?>
<select id="pagenum" onchange="ChangePage()">
<?php for($i = 1; $i <= $npage; $i++) { ?>
	<option value="<?=$i?>" <?=IntIsSelected($i, $pagenum)?>><?=$i?></option>
<?php } ?>
</select>
<?php if ($pagenum != $npage) { ?>
<input type="button" id="Left" class="but" onclick="MoveToPage(<?=$pagenum+1?>)" value=" > ">
<?php } ?>
 dari <?= $npage ?>&nbsp;&nbsp;&nbsp;(banyaknya data: <?=$ndata?>)
</td>
<td width="50%" align="right">
<a href="JavaScript:refresh()" title="Refresh"><img src="../images/ico/refresh.png" border="0" />&nbsp;refresh</a>
&nbsp;&nbsp;<a href="JavaScript:Cetak_Excel()" title="Buka di Ms Excel"><img src="../images/ico/excel.png" border="0" />&nbsp;excel</a>
&nbsp;&nbsp;<a href="JavaScript:Cetak()" title="Cetak Halaman Ini"><img src="../images/ico/print.png" border="0" />&nbsp;cetak</a>
</td>
</tr>
</table>

<table border="1" cellpadding="2" cellspacing="0" width="1395" class="tab" id="table">
<tr height="20">
	<td class="header"  width="20" align="center" valign="middle" rowspan="2">No</td>
    <td class="header"  width="160" align="center" valign="middle" rowspan="2">Nama</td>
    <td class="header"  width="120" align="center" valign="middle" rowspan="2">NIP</td>
    <td class="header"  width="120" align="center" valign="middle" colspan="2">Pangkat</td>
    <td class="header"  width="200" align="center" valign="middle" colspan="2">Jabatan</td>
    <td class="header"  width="100" align="center" valign="middle" colspan="2">Masa Kerja</td>
    <td class="header"  width="100" align="center" valign="middle" colspan="2">Diklat</td>
    <td class="header"  width="125" align="center" valign="middle" colspan="3">Pendidikan</td>
    <td class="header"  width="40" align="center" valign="middle" rowspan="2">Usia</td>
    <td class="header"  width="120" align="center" valign="middle" rowspan="2">Kelahiran</td>
	<td class="header"  width="200" align="left" valign="middle" rowspan="2">Keterangan</td>
</tr>
<tr height="20">
	<td class="header"  width="60" align="center" valign="middle">GOL</td>
    <td class="header"  width="60" align="center" valign="middle">TMT</td>
    
    <td class="header"  width="140" align="center" valign="middle">NAMA</td>
    <td class="header"  width="60" align="center" valign="middle">TMT</td>
    
    <td class="header"  width="50" align="center" valign="middle">GOL</td>
    <td class="header"  width="50" align="center" valign="middle">SEL</td>
    
    <td class="header"  width="50" align="center" valign="middle">Nama</td>
    <td class="header"  width="50" align="center" valign="middle">Th</td>
    
    <td class="header"  width="65" align="center" valign="middle">Nama</td>
    <td class="header"  width="30" align="center" valign="middle">Lls</td>
    <td class="header"  width="30" align="center" valign="middle">Tk</td>
</tr>

<?php
for($i = $minrownum - 1; $i < $maxrownum && $i < $ndata; $i++)
{
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
    <td align="center" valign="middle">
<?php 		$thn = floor($row['tmtgol'] / 365);
		$bln = $row['tmtgol'] % 365;
		$bln = floor($bln / 30);
		echo "$thn-$bln"; ?>
    </td>
    <td align="center" valign="middle">
<?php 	$thn = floor($row['tglmulai'] / 365);
		$bln = $row['tglmulai'] % 365;
		$bln = floor($bln / 30);
		echo "$thn-$bln"; ?>
    </td>
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
	?>
    </td>
    <td align="left" valign="middle"><?=$row['tmplahir'] . ", " . $row['tgllahir'] ?></td>
	<td align="left" valign="middle"><?=$row['keterangan']?></td>
</tr>
<?php
}
?>
</table>
<span class="style1">Tampilkan</span> 
<select name="PAGING_SIZE" id="PAGING_SIZE" onchange="chg_paging_size()">
<?php
$i=5;
while ($i<=50){
?>
	<option value="<?=$i?>" <?=IntIsSelected($i,$PAGING_SIZE)?> ><?=$i?></option>
<?php
$i=$i+5;
}
?>
</select> 
<span class="style1">data per Halaman.</span>
<script language='JavaScript'>
	Tables('table', 1, 0);
</script>
</body>
</html>

