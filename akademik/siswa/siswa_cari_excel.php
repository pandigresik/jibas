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
/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Data_Siswa.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$cari=$_REQUEST['cari'];
$jenis=$_REQUEST['jenis'];
$departemen=$_REQUEST['departemen'];
$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$tipe = ["nisn" => "N I S N", "nis" => "NIS", "nama" => "Nama", "panggilan" => "Nama Panggilan", "agama" =>"Agama", "suku" => "Suku", "status" => "Status", "kondisi"=>"Kondisi Siswa", "darah"=>"Golongan Darah", "alamatsiswa" => "Alamat Siswa", "asalsekolah" => "Asal Sekolah", "namaayah" => "Nama Ayah", "namaibu" => "Nama Ibu", "alamatortu" => "Alamat Orang Tua", "keterangan" => "Keterangan"];

if ($cari=="")
$namacari="";
else
$namacari=$cari;


OpenDb();
$sql = "SELECT s.replid,s.nis,s.nama,s.asalsekolah,s.tmplahir,DATE_FORMAT(s.tgllahir,'%d %M %Y') as tgllahir,s.status,t.tingkat,k.kelas,s.aktif,s.nisn from jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t WHERE s.".$jenis." LIKE '%$cari%' AND k.replid=s.idkelas AND k.idtingkat=t.replid AND t.departemen='$departemen' ORDER BY $urut $urutan";
$result=QueryDb($sql);

if (@mysqli_num_rows($result)<>0){
?>
<html>
<head>
<title>
Data Siswa
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
    <td colspan="7"><div align="center">PENCARIAN SISWA</div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
	<td colspan="7"><strong>Departemen :&nbsp;<?=$departemen?></strong></td>
  </tr>
  <tr>
    <td colspan="7">Pencarian berdasarkan <strong><?=$tipe[$jenis]?></strong> dengan keyword <strong><?=$cari?></strong></td>
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
<td width="3" valign="middle" bgcolor="#666666"><div align="center" class="style1">No.</div></td>
<td width="20" valign="middle" bgcolor="#666666"><div align="center" class="style1">NIS</div></td>
<td width="20" valign="middle" bgcolor="#666666"><div align="center" class="style1">N I S N</div></td>
<td valign="middle" bgcolor="#666666"><div align="center" class="style1">Nama</div></td>
<td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tempat, Tanggal Lahir</div></td>
<td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tingkat</div></td>
<td valign="middle" bgcolor="#666666"><div align="center" class="style1">Kelas</div></td>
<td valign="middle" bgcolor="#666666"><div align="center" class="style1">Status</div></td>
</tr>
<?php
	$cnt=1;
	while ($row_siswa=@mysqli_fetch_array($result)){
	?>
	<tr height="25">
	<td width="3" align="center"><?=$cnt?></td>
	<td align="left"><?=$row_siswa['nis']?></td>
	<td align="left"><?=$row_siswa['nisn']?></td>
	<td align="left"><?=$row_siswa['nama']?></td>
	<td align="left"><?=$row_siswa['tmplahir']?>, <?=LongDateFormat($row_siswa['tgllahir'])?></td>
	<td align="center"><?=$row_siswa['tingkat']?></td>
	<td align="center"><?=$row_siswa['kelas']?></td>
	<td align="center"><?php
		if ($row_siswa['aktif']==1){
			echo "Aktif";
		} elseif ($row_siswa['aktif']==0){
			echo "Tidak Aktif ";
			if ($row_siswa['alumni']==1){
				$sql_get_al="SELECT DATE_FORMAT(a.tgllulus, '%d %M %Y') as tgllulus FROM jbsakad.alumni a WHERE a.nis='".$row_siswa['nis']."'";	
				$res_get_al=QueryDb($sql_get_al);
				$row_get_al=@mysqli_fetch_array($res_get_al);
				echo "<br><a style='cursor:pointer;' title='Lulus Tgl: ".$row_get_al['tgllulus']."'><u>['Alumnus']</u></a>";
			}
			if ($row_siswa['statusmutasi']!=NULL){
				$sql_get_mut="SELECT DATE_FORMAT(m.tglmutasi, '%d %M %Y') as tglmutasi,j.jenismutasi FROM jbsakad.jenismutasi j, jbsakad.mutasisiswa m WHERE j.replid='".$row_siswa['statusmutasi']."' AND m.nis='".$row_siswa['nis']."' AND j.replid=m.jenismutasi";	
				$res_get_mut=QueryDb($sql_get_mut);
				$row_get_mut=@mysqli_fetch_array($res_get_mut);
				//echo "<br><a href=\"NULL\" onmouseover=\"showhint('".$row_get_mut['jenismutasi']."<br>".$row_get_mut['tglmutasi']."', this, event, '50px')\"><u>['Termutasi']</u></a>";
				echo "<br><a style='cursor:pointer;' title='".$row_get_mut['jenismutasi']."\n Tgl : ".$row_get_mut['tglmutasi']."'><u>['Termutasi']</u></a>";
			}
		}
		?></td>
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