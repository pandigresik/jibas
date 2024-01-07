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
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/getheader.php');
$departemen = $_REQUEST['departemen'];
$proses = $_REQUEST['proses'];
$kelompok = $_REQUEST['kelompok'];
$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

OpenDb();
$sql = "SELECT p.proses, k.kelompok, k.keterangan FROM kelompokcalonsiswa k, prosespenerimaansiswa p WHERE k.idproses = '$proses' AND k.replid = '".$kelompok."'";
$result = QueryDb($sql);
$row =@mysqli_fetch_array($result);
$namaproses = $row['proses'];
$namakelompok = $row['kelompok'];
$keterangan = $row['keterangan'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Calon Siswa]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center>
  <font size="4"><strong>DATA CALON SISWA</strong></font><br />
 </center><br /><br />
 <table width="100%">    
	<tr>
		<td width="20%"><strong>Departemen</strong> </td> 
		<td width="*"><strong>:&nbsp;<?=$departemen?></strong></td>
	</tr>
    <tr>
		<td><strong>Proses Penerimaan </strong></td>
		<td><strong>:&nbsp;<?=$namaproses?></strong></td>        		
    </tr>
    <tr>
		<td><strong>Kelompok Calon Siswa</strong></td>
		<td><strong>:&nbsp;<?=$namakelompok?></strong></td>        		
    </tr>
    <tr>
		<td><strong>Keterangan</strong></td>
		<td><strong>:&nbsp;<?=$keterangan?></strong></td>        		
    </tr>
	</table>
<br />
<table border="1" width="1020" id="table" class="tab" bordercolor="#000000">
<tr height="30">		
	<td align="center" class="header" width="20" rowspan="2">No</td>
	<td align="center" class="header" width="100" rowspan="2">No Pendaftaran</td>
	<td align="center" class="header" width="100" rowspan="2">NISN</td>
	<td align="center" class="header" width="160" rowspan="2">Nama</td>
    <td align="center" class="header" width="90">Sumb#1</td>
    <td align="center" class="header" width="90">Sumb#22</td>
    <td align="center" class="header" width="45">Uji#1</td>
	<td align="center" class="header" width="45">Uji#2</td>
    <td align="center" class="header" width="45">Uji#3</td>
    <td align="center" class="header" width="45">Uji#4</td>
    <td align="center" class="header" width="45">Uji#5</td>
	<td align="center" class="header" width="45">Uji#6</td>
	<td align="center" class="header" width="45">Uji#7</td>
	<td align="center" class="header" width="45">Uji#8</td>
	<td align="center" class="header" width="45">Uji#9</td>
	<td align="center" class="header" width="45">Uji#10</td>
    <td align="center" class="header" width="120" rowspan="2">Status</td>   
</tr>
<?php 	
	$sqlset = "SELECT COUNT(replid) FROM settingpsb WHERE idproses = '".$proses."'";
	$resset = QueryDb($sqlset);
	$rowset = mysqli_fetch_row($resset);
	$ndata = $rowset[0];
	
	if ($ndata > 0)
	{
		$sqlset = "SELECT * FROM settingpsb WHERE idproses = '".$proses."'";
		$resset = QueryDb($sqlset);
		$rowset = mysqli_fetch_array($resset);
		
		$kdsum1 = $rowset['kdsum1']; //$nmsum1 = $rowset['nmsum1'];
		$kdsum2 = $rowset['kdsum2']; //$nmsum2 = $rowset['nmsum2'];
		$kdujian1 = $rowset['kdujian1']; //$nmujian1 = $rowset['nmujian1'];
		$kdujian2 = $rowset['kdujian2']; //$nmujian2 = $rowset['nmujian2'];
		$kdujian3 = $rowset['kdujian3']; //$nmujian3 = $rowset['nmujian3'];
		$kdujian4 = $rowset['kdujian4']; //$nmujian4 = $rowset['nmujian4'];
		$kdujian5 = $rowset['kdujian5']; //$nmujian5 = $rowset['nmujian5'];
		$kdujian6 = $rowset['kdujian6'];
		$kdujian7 = $rowset['kdujian7'];
		$kdujian8 = $rowset['kdujian8'];
		$kdujian9 = $rowset['kdujian9'];
		$kdujian10 = $rowset['kdujian10'];
	} ?>
<tr height="30">		
    <td align="center" class="header"><?=$kdsum1?></td>
    <td align="center" class="header"><?=$kdsum2?></td>
    <td align="center" class="header"><?=$kdujian1?></td>
	<td align="center" class="header"><?=$kdujian2?></td>
    <td align="center" class="header"><?=$kdujian3?></td>
    <td align="center" class="header"><?=$kdujian4?></td>
    <td align="center" class="header"><?=$kdujian5?></td>
	<td align="center" class="header"><?=$kdujian6?></td>
	<td align="center" class="header"><?=$kdujian7?></td>
	<td align="center" class="header"><?=$kdujian8?></td>
	<td align="center" class="header"><?=$kdujian9?></td>
	<td align="center" class="header"><?=$kdujian10?></td>
</tr>    
<?php 	$sql = "SELECT nopendaftaran,nama,asalsekolah,sum1,sum2,ujian1,ujian2,ujian3,ujian4,ujian5,ujian6,ujian7,ujian8,ujian9,ujian10,". 
		   "c.aktif,c.replid,replidsiswa,c.nisn FROM calonsiswa c, kelompokcalonsiswa k, prosespenerimaansiswa p ".
		   "WHERE c.idproses = '$proses' AND c.idkelompok = '$kelompok' AND k.idproses = p.replid ".
		   "AND c.idproses = p.replid AND c.idkelompok = k.replid ORDER BY $urut $urutan ";
	$result = QueryDb($sql);
	if ($page==0)
		$cnt = 0;
	else
		$cnt = (int)$page*(int)$varbaris;
	while ($row = @mysqli_fetch_array($result)) {
		$siswa = "";
		if ($row["replidsiswa"] <> 0) {
			$sql3 = "SELECT nis FROM jbsakad.siswa WHERE replid = '".$row['replidsiswa']."'";
			$result3 = QueryDb($sql3);
			$row3 = @mysqli_fetch_array($result3);
			$siswa = "<br>NIS Siswa:<br><b>".$row3['nis']."</b>";
		}
	?>	
    
<tr>        			
		<td height="25" align="center"><?=++$cnt?></td>
		<td height="25" align="center"><?=$row["nopendaftaran"]?></td>
		<td height="25" align="center"><?=$row["nisn"]?></td>
  		<td height="25" align="left"><?=$row["nama"]?></td>
        <td height="25" align="right"><?=FormatRupiah($row["sum1"])?></td>
        <td height="25" align="right"><?=FormatRupiah($row["sum2"])?></td>
        <td height="25" align="center"><?=$row["ujian1"]?></td>
		<td height="25" align="center"><?=$row["ujian2"]?></td>
        <td height="25" align="center"><?=$row["ujian3"]?></td>
        <td height="25" align="center"><?=$row["ujian4"]?></td>
        <td height="25" align="center"><?=$row["ujian5"]?></td>
		<td height="25" align="center"><?=$row["ujian6"]?></td>
		<td height="25" align="center"><?=$row["ujian7"]?></td>
		<td height="25" align="center"><?=$row["ujian8"]?></td>
		<td height="25" align="center"><?=$row["ujian9"]?></td>
		<td height="25" align="center"><?=$row["ujian10"]?></td>
        <td height="25" align="center">
		<?php if ($row["aktif"] == 1) 
           		echo 'Aktif'.$siswa; 
			else
			echo 'Tidak Aktif'.$siswa;			
		?></td>
	  </tr>
	<?php 	}		?>			
	</table>
	</td>
</tr>
</table>
</body>
<?php
CloseDb();
?>
<script language="javascript">
window.print();
</script>
</html>