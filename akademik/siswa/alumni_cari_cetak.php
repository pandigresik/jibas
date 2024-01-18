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
$jenis=$_REQUEST['jenis'];
$departemen=$_REQUEST['departemen'];
$tahun=$_REQUEST['tahun'];
$cari=$_REQUEST['cari'];

$tipe = ["nis" => "NIS", "idangkatan" => "Angkatan", "tgllulus" => "Tahun Lulus", "nama" => "Nama", "panggilan" => "Nama Panggilan", "agama" =>"Agama", "suku" => "Suku", "status" => "Status", "kondisi"=>"Kondisi Siswa", "darah"=>"Golongan Darah", "alamatsiswa" => "Alamat Siswa", "asalsekolah" => "Asal Sekolah", "namaayah" => "Nama Ayah", "namaibu" => "Nama Ibu", "alamatortu" => "Alamat Orang Tua", "keterangan" => "Keterangan"];

$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Pencarian Data Alumni]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center>
  <font size="4"><strong>PENCARIAN DATA ALUMNI</strong></font><br />
 </center><br /><br />
<br />
<table>
<tr>
	<td><strong>Departemen: <?=$departemen?></strong></td>
</tr>
<tr>
	<td>Pencarian berdasarkan <strong><?=$tipe[$jenis]?></strong> dengan keyword <strong><?=$cari?></strong></td>
</table>
<br />
</span>
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="15%" class="header" align="center">N I S</td>
        <td width="*" class="header" align="center">Nama</td>
        <td width="15%" class="header" align="center">Kelas Terakhir</td>
        <td width="20%" class="header" align="center">Tahun Lulus</td>
    </tr>
<?php 	OpenDb();
	if ($jenis!="kondisi" && $jenis!="status" && $jenis!="agama" && $jenis!="suku" && $jenis!="darah" && $jenis!="idangkatan" && $jenis!="tgllulus") {
		$sql_siswa = "SELECT s.replid, s.nis, s.nama, s.idkelas, k.kelas, s.tmplahir, s.tgllahir, s.statusmutasi, s.aktif, s.alumni, t.tingkat, a.tgllulus from jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.alumni a WHERE s.$jenis LIKE '%$cari%' AND k.replid=a.klsakhir AND k.idtingkat=t.replid AND a.departemen='$departemen' AND s.nis = a.nis ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	} elseif ($jenis == "tgllulus") {
		$sql_siswa = "SELECT s.replid, s.nis, s.nama, s.idkelas, k.kelas, s.tmplahir, s.tgllahir, s.statusmutasi, s.aktif, s.alumni, t.tingkat, a.tgllulus from jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.alumni a WHERE YEAR(a.tgllulus) = '$cari' AND k.replid=a.klsakhir AND k.idtingkat=t.replid AND a.departemen='$departemen' AND s.nis = a.nis ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	} else { 
		$sql_siswa = "SELECT s.replid, s.nis, s.nama, s.idkelas, k.kelas, s.tmplahir, s.tgllahir, s.statusmutasi, s.aktif, s.alumni, t.tingkat, a.tgllulus from jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.alumni a WHERE s.$jenis = '$cari' AND k.replid=a.klsakhir AND k.idtingkat=t.replid AND a.departemen='$departemen' AND s.nis = a.nis ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	}
	
	$result = QueryDB($sql_siswa);
	//if ($page==0)
		$cnt = 0;
	//else
		//$cnt = (int)$page*(int)$varbaris;
		
	while ($row_siswa = mysqli_fetch_array($result)) { 
		?>
    <tr height="25">    	
    	<td align="center"><?=++$cnt?></td>
    	<td align="center"><?=$row_siswa['nis']?></td>
    	<td><?=$row_siswa['nama']?></td>
    	<td align="center"><?=$row_siswa['tingkat']?> - <?=$row_siswa['kelas']?></td>
        <td align="center"><?=LongDateFormat($row_siswa['tgllulus'])?></td> 
    </tr>
<?php } 
	CloseDb() ?>	
    </table>
<!-- END TABLE CENTER -->    
</table>
</body>
<script language="javascript">
window.print();
</script>
</html>