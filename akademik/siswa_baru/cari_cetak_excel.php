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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Data_Calon_Siswa.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$jenis=$_REQUEST['jenis'];
$departemen=$_REQUEST['departemen'];
$cari=$_REQUEST['cari'];

switch($jenis) {
	case 'nopendaftaran': $namajenis = 'No Pendaftaran';
		break;
	case 'kelompok' 	: $namajenis = 'Kelompok'; 
		break;
	case 'nama'			: $namajenis = 'Nama Calon Siswa';
		break;
}
$urut= $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

OpenDb();

if ($jenis != "kondisi" && $jenis != "status" && $jenis != "agama" && $jenis != "suku" && $jenis != "darah")
		$sql = "SELECT c.replid,c.nopendaftaran,c.nama,p.departemen,k.kelompok,c.aktif,c.nisn FROM calonsiswa c,kelompokcalonsiswa k, prosespenerimaansiswa p WHERE c.$jenis like '%$cari%' AND p.departemen='$departemen' AND c.idkelompok = k.replid AND c.idproses = p.replid AND p.replid = k.idproses ORDER BY $urut $urutan, nama ASC";
else 
		$sql = "SELECT c.replid,c.nopendaftaran,c.nama,p.departemen,k.kelompok,c.aktif,c.nisn FROM calonsiswa c,kelompokcalonsiswa k, prosespenerimaansiswa p WHERE c.$jenis = '$cari' AND p.departemen='$departemen' AND c.idkelompok = k.replid AND c.idproses = p.replid AND p.replid = k.idproses ORDER BY $urut $urutan, nama ASC";
$result = QueryDB($sql);
if (@mysqli_num_rows($result)<>0){
?>
<html>
<head>
<title>Pencarian Data Calon Siswa
</title>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style2 {font-size: 14px}
-->
</style>
</head>
<body>
<table width="700" border="0">
  <tr>
    <td>
    <table width="100%" border="0">
  <tr>
    <td colspan="4"><div align="center">Data Calon Siswa</div></td>
    </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><strong>Departemen :&nbsp;<?=$departemen?></strong></td>
  </tr>
  <tr>
    <td colspan="4">Pencarian berdasarkan <strong><?=$namajenis?></strong> dengan keyword <strong><?=$cari?></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

    </td>
  </tr>
  <tr>
    <td><table border="1">
<tr height="30">
<td width="1" valign="middle" bgcolor="#666666"><div align="center" class="style1">No.</div></td>
<td width="20" valign="middle" bgcolor="#666666"><div align="center" class="style1">No. Pendaftaran</div></td>
<td width="20" valign="middle" bgcolor="#666666"><div align="center" class="style1">NISN</div></td>
<td valign="middle" bgcolor="#666666"><div align="center" class="style1">Nama Calon Siswa</div></td>
<td width="20" valign="middle" bgcolor="#666666"><div align="center" class="style1">Kelompok</div></td>
<td width="10" valign="middle" bgcolor="#666666"><div align="center" class="style1">Status</div></td>
</tr>
<?php
	$cnt=1;
	while ($row=@mysqli_fetch_array($result)){
	?>
	<tr height="25">
	<td width="3" align="center"><?=$cnt?></td>
        <td align="center"><?=$row['nopendaftaran'] ?></td>
		<td align="center"><?=$row['nisn'] ?></td>
        <td><?=$row['nama']?></td>
        <td><?=$row['kelompok'] ?></td>
        <td align="center">
		<?php if ($row['aktif']==1){
				echo "Aktif";
			} elseif ($row['aktif']==0){
				echo "Tidak Aktif ";
			}
		?>	
        </td> 
    </tr>
	<?php
		$cnt++;
}
	?>
</table></td>
  </tr>
</table>


</body>
</html>
<?php
}
CloseDb();
?>