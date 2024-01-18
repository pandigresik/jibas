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
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/rupiah.php');
require_once('include/common.php');

$key = $_REQUEST['key'];
$keyword = $_REQUEST['keyword'];
$ref = $_REQUEST['ref'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="style/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SISFO KUA</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function ShowDetail(ref) {
	parent.statdetail.location.href = "statdetail.php?ref="+ref+"&key=<?=$key?>&keyword=<?=$keyword?>";
}
function OpenDetail(id) {
	var addr = "browsepage.php?sel=A&id="+id;
	newWindow(addr, 'Openaktanikahgab','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function OpenBuku(id) {
	var addr = "bukunikahdetail.php?id="+id;
	newWindow(addr, 'OpenBukuNikah','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function OpenDetailPeg(id) {
	newWindow('pegawai_view.php?replid='+id, 'TampilDetaiPegawai','557','690','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function OpenDetailWakaf(id) {
	newWindow('daftarwakafright.php?id=fromstat&replid_wakaf='+id, 'TampilDetaiWakaf','827','350','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body style="background-color:#F5F5F5">
<?php
if ($key == 2) {
	$info = explode("-", (string) $keyword);
	$thn1 = $info[0];
	$thn2 = $info[1];

	$column = "Tahun";
	$sql = "SELECT replid, DATE_FORMAT(tanggal, '%d %M %Y') AS tgl, nomor, suami, istri, idbukunikah, idaktanikah FROM aktanikahgab WHERE status=1 AND YEAR(tanggal) = $ref ORDER BY tanggal";	
} else {
	$info = explode("-", (string) $keyword);
	$thn1 = $info[1] . "-" . $info[0] . "-1";
	$thn2 = $info[3] . "-" . $info[2] . "-31";
	
	if ($key == 1) {
		$column = "Bulan";
		$sql = "SELECT replid, DATE_FORMAT(tanggal, '%d %M %Y') AS tgl, nomor, suami, istri, idbukunikah, idaktanikah FROM aktanikahgab WHERE status=1 AND tanggal BETWEEN '$thn1' AND '$thn2' AND  MONTH(tanggal)='$ref' ORDER BY tanggal DESC ";	
	} elseif ($key == 3) {
		$column = "Usia Suami";
		$sql = "SELECT replid, DATE_FORMAT(tanggal, '%d %M %Y') AS tgl, nomor, suami, istri, idbukunikah, idaktanikah FROM aktanikahgab WHERE (YEAR(tanggal)-thnlahirsuami) = $ref AND status=1 AND tanggal BETWEEN '$thn1' AND '$thn2' ORDER BY tanggal DESC";
	} elseif ($key == 4) {
		$column = "Tahun Kelahiran Suami";
		$sql = "SELECT replid, DATE_FORMAT(tanggal, '%d %M %Y') AS tgl, nomor, suami, istri, idbukunikah, idaktanikah FROM aktanikahgab WHERE thnlahirsuami=$ref AND status=1 AND tanggal BETWEEN '$thn1' AND '$thn2' ORDER BY tanggal DESC";
	} elseif ($key == 5) {
		$column = "Usia Istri";
		$sql = "SELECT replid, DATE_FORMAT(tanggal, '%d %M %Y') AS tgl, nomor, suami, istri, idbukunikah, idaktanikah FROM aktanikahgab WHERE (YEAR(tanggal)-thnlahiristri) = $ref AND status=1 AND tanggal BETWEEN '$thn1' AND '$thn2' ORDER BY tanggal DESC";
	} elseif ($key == 6) {
		$column = "Tahun Kelahiran Istri";
		$sql = "SELECT replid, DATE_FORMAT(tanggal, '%d %M %Y') AS tgl, nomor, suami, istri, idbukunikah, idaktanikah FROM aktanikahgab WHERE thnlahiristri=$ref AND status=1 AND tanggal BETWEEN '$thn1' AND '$thn2' ORDER BY tanggal DESC";
	}
}
if ($key == 7 || $key == 8 || $key == 9 || $key == 10 || $key == 11) {
	if ($key==7){
	$column = "Jabatan";
	$sql = "SELECT p.nip,p.nama,p.replid FROM pegawai p, jabatan j WHERE p.aktif=1 AND p.jabatan=j.replid AND p.jabatan='$ref' ORDER BY j.replid  ";	
	}
	if ($key==8){
	$column = "Golongan";
	$sql = "SELECT p.nip,p.nama,p.replid FROM pegawai p, golongan g WHERE p.aktif=1 AND p.golongan=g.replid AND p.golongan='$ref' ORDER BY g.replid  ";	
	}
	if ($key==9){
	$column = "Pendidikan";
	$sql = "SELECT p.nip,p.nama,p.replid FROM pegawai p, pendidikan pend WHERE p.aktif=1 AND p.pendidikan=pend.replid AND p.pendidikan='$ref' ORDER BY pend.replid ";	
	}
	if ($key==10){
	$column = "Suku";
	$sql = "SELECT p.nip,p.nama,p.replid FROM pegawai p, suku s WHERE p.aktif=1 AND p.suku=s.suku AND p.suku='$ref' ORDER BY s.suku ";	
	}
	if ($key==11){
	$column = "Agama";
	$sql = "SELECT p.nip,p.nama,p.replid FROM pegawai p, agama a WHERE p.aktif=1 AND p.agama=a.agama AND p.agama='$ref' ORDER BY a.agama ";	
	}
}
if ($key == 12 || $key == 13 || $key == 14 || $key == 15) {
	if ($key==12){
		$info = explode("-", (string) $keyword);
		$thn1 = $info[0];
		$thn2 = $info[1];
	$column = "Jabatan";
	$column1 = "Tahun";
	$column2 = "Jumlah";
	$sql = "SELECT w.replid,w.tanggal,w.nilai FROM wakaf w WHERE YEAR(w.tanggal)='$thn2' ORDER BY w.replid  ";	
	}
	if ($key==13){
		$info = explode("-", (string) $ref);
		$thn1 = $info[1] . "-" . $info[0] . "-1";
		$thn2 = $info[3] . "-" . $info[2] . "-31";
		$bln1 = $info[1];
	$column = "Golongan";
	$column1 = "Bulan";
	$column2 = "Jumlah";
	$sql = "SELECT w.replid,w.tanggal,w.nilai FROM wakaf w WHERE MONTH(w.tanggal)='$bln1' ORDER BY w.replid  ";	
	}
	if ($key==14){
		$info = explode("-", (string) $ref);
		$thn1 = $info[0];
		$bln1 = $info[1];
	$column = "Pendidikan";
	$column1 = "Tahun";
	$column2 = "Nilai";
	$sql = "SELECT w.replid,w.tanggal,w.nilai FROM wakaf w WHERE YEAR(w.tanggal)='$thn1' ORDER BY w.replid ";	
	}
	if ($key==15){
		$info = explode("-", (string) $ref);
		$thn1 = $info[0];
		$bln1 = $info[1];
	$column = "Nilai";
	$column1 = "Tahun";
	$column2 = "Nilai";
	$sql = "SELECT w.replid,w.tanggal,w.nilai FROM wakaf w WHERE MONTH(w.tanggal)='$bln1' ORDER BY w.replid";	
	}
}
?>
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%" bordercolor="#000000">
<tr height="35">
	<td class="header" align="center" width="7%">No</td>
	<?php
	if ($key == 7 || $key == 8 || $key == 9 || $key == 10 || $key == 11) {
    ?>
	<td class="header" align="center" width="35%">NIP</td>
    <td class="header" align="center" width="35%">Nama</td>
    <?php
	}
	if ($key == 1 || $key == 2 || $key == 3 || $key == 4 || $key == 5 || $key == 6) {
	?>
	<td class="header" align="center" width="35%">Nomor/<br/>Tanggal</td>
    <td class="header" align="center" width="35%">Pasangan</td>
	<?php
	}
	if ($key == 12 || $key == 13 || $key == 14 || $key == 15) {
	?>
	<td class="header" align="center" width="35%">Tanggal</td>
    <td class="header" align="center" width="35%"><?=$column2?></td>
	<?php
	}
	?>
	<td class="header" align="center" width="10%">&nbsp;</td>
</tr>
<?php
OpenDb();
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_row($result)) {
?>
<tr height="20">
	<td align="center" valign="top"><?=++$cnt?></td>
	<?php
	if ($key == 7 || $key == 8 || $key == 9 || $key == 10 || $key == 11) {
    ?>
    <td align="center" valign="top"><?="<b>".$row[0]?></b></td>
    <td align="left" valign="top"><?=$row[1] ?></td>
    <td align="center" valign="top"><a href="JavaScript:OpenDetailPeg('<?=$row[2]?>')"><img src="Images/Ico/lihat.png" border="0" /></a> </td>
	<?php
	} 
	if ($key == 1 || $key == 2 || $key == 3 || $key == 4 || $key == 5 || $key == 6) {
		$idbuku = $row[5];
		$idakta = $row[6];
	?>
	<td align="center" valign="top"><?="<b>".$row[2]."</b><br>" . $row[1] ?></td>
    <td align="left" valign="top"><?=$row[3] . "<br>" . $row[4] ?></td>
    <td align="center" valign="top">
    <?php if ($idakta > 0) { ?>
	    <a href="JavaScript:OpenDetail('<?=$idakta?>')" title="Lihat Akta Nikah"><img src="Images/aktaico.png" border="0" /></a>&nbsp; 
    <?php } ?>
    <?php if ($idbuku > 0) { ?>
	    <a href="JavaScript:OpenBuku('<?=$idbuku?>')" title="Lihat Buku Nikah"><img src="Images/bukuico.png" border="0" /></a>
    <?php } ?>
    </td>
	<?php
	}
	if ($key == 12 || $key == 13 || $key == 14 || $key == 15) {
	?>
	<td align="center" valign="top"><?=LongDateFormat($row[1]) ?></td>
    <td align="left" valign="top"><?="<b>".$row[2]."</b>"?></td>
    <td align="center" valign="top"><a href="JavaScript:OpenDetailWakaf('<?=$row[0]?>')"><img src="Images/Ico/lihat.png" border="0" /></a> </td>
	<?php
	}
	?>
</tr>
<?php
}
CloseDb();
?>
</table>
<script language='JavaScript'>
   Tables('table', 1, 0);
</script>
</body>
</html>