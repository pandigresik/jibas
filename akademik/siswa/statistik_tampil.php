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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

OpenDb();
$idangkatan=(int)$_REQUEST['idangkatan'];
$departemen=$_REQUEST['departemen'];
$dasar = $_REQUEST['dasar'];
$tabel = $_REQUEST['tabel'];
$judul = $_REQUEST['judul'];
$iddasar = $_REQUEST['iddasar'];
$keyword = $_REQUEST['keyword'];

$urut = "s.nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];


if ($iddasar==""){
	if ($dasar == 'Golongan Darah') {
		if ($judul == 'Tidak ada data'){ 
			$filter = "s.$tabel = '' AND s.aktif = 1";
		} else {
			$filter = "s.$tabel = '$judul' AND s.aktif = 1";
		}
	} elseif ($dasar == 'Jenis Kelamin' ) {	
		if ($judul == 'Laki-laki') 
			$filter = "s.$tabel = 'l' AND s.aktif = 1";		
		else 
			$filter = "s.$tabel = 'p' AND s.aktif = 1";		
	/*} elseif ($dasar == 'Penghasilan Orang Tua' ) {	
		$judul = array(1 => '< Rp 1000000','Rp 1000000 s/d Rp 2500000','Rp 2500000 s/d Rp 5000000','> Rp 5000000');		
		if ($judul == '< Rp 1000000') 
			$filter = "s.$tabel < 1000000 AND s.aktif = 1";		
		elseif ($judul == 'Rp 1000000 s/d Rp 2500000') 
			$filter = "1000000 < s.$tabel < 2500000 AND s.aktif = 1";		
		elseif ($judul == 'Rp 2500000 s/d Rp 5000000') 
			$filter = "2500000 < s.$tabel < 5000000 AND s.aktif = 1";
		else 
			$filter = "s.$tabel > 5000000 AND s.aktif = 1";	*/			
	} elseif ($dasar == 'Status Aktif') {
		if ($judul == 'Aktif') 
			$filter = "s.$tabel = 1 ";
		else 
			$filter = "s.$tabel = 0 ";			
	} elseif ($dasar == 'Tahun Kelahiran') {
		$filter = "YEAR(tgllahir) = $judul AND s.aktif = 1 ";
	} elseif ($dasar == 'Usia') {
		$filter =  "YEAR(now()) - YEAR(tgllahir) = $judul AND s.aktif = 1"; 	
	} elseif ($judul == NULL) {
		$filter = "s.$tabel is NULL AND s.aktif = 1";
		$judul = "Tidak ada data";
	} else {
		$filter = "s.$tabel = '$judul' AND s.aktif = 1";
	}
	
	if ($departemen=="-1" && $idangkatan<0) {	
		
		$query1 = "SELECT s.nis,s.nama,s.foto,k.kelas,s.replid,a.departemen,t.tingkat FROM siswa s, angkatan a, kelas k, tingkat t WHERE a.replid = s.idangkatan AND s.idkelas = k.replid AND k.idtingkat = t.replid AND $filter ORDER BY $urut $urutan";
	} if ($departemen<>"-1" && $idangkatan<0) {	
		$query1 = "SELECT s.nis,s.nama,s.foto,k.kelas,s.replid,a.departemen,t.tingkat FROM siswa s, angkatan a, kelas k, tingkat t WHERE a.replid = s.idangkatan AND s.idkelas = k.replid AND a.departemen = '$departemen' AND k.idtingkat = t.replid AND $filter ORDER BY $urut $urutan";	
	} if ($departemen<>"-1" && $idangkatan>0) {	
		$query1 = "SELECT s.nis,s.nama,s.foto,k.kelas,s.replid,a.departemen,t.tingkat FROM siswa s, angkatan a, kelas k, tingkat t WHERE a.replid = s.idangkatan AND s.idkelas = k.replid AND a.departemen = '$departemen' AND s.idangkatan = '$idangkatan' AND k.idtingkat = t.replid AND $filter ORDER BY $urut $urutan";	
	}
	//echo 'sql '.$query1;	
	$result1 = QueryDb($query1);
	$num = @mysqli_num_rows($result1);
	
} elseif ($iddasar=="12"){

	if ($departemen=="-1" && $idangkatan<0)
		$kondisi=" AND a.replid=s.idangkatan AND s.idkelas = k.replid AND k.idtingkat = t.replid";
	if ($departemen<>"-1" && $idangkatan<0)
		$kondisi=" AND a.departemen='$departemen' AND a.replid=s.idangkatan AND s.idkelas = k.replid AND k.idtingkat = t.replid";
	if ($departemen<>"-1" && $idangkatan>0)
		$kondisi=" AND s.idangkatan=$idangkatan AND a.replid=s.idangkatan AND a.departemen='$departemen' AND s.idkelas = k.replid AND k.idtingkat = t.replid ";
	
	if ($keyword=="1")
	$query1 = "SELECT s.nis,s.nama,s.foto,k.kelas,s.replid,a.departemen,t.tingkat FROM siswa s, angkatan a, kelas k, tingkat t WHERE s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu<1000000 $kondisi ORDER BY $urut $urutan";
	if ($keyword=="2")
	$query1 = "SELECT s.nis,s.nama,s.foto,k.kelas,s.replid,a.departemen,t.tingkat FROM siswa s, angkatan a, kelas k, tingkat t WHERE s.aktif = '1' AND k.idtingkat = t.replid AND s.penghasilanayah+s.penghasilanibu>=1000000 AND s.penghasilanayah+s.penghasilanibu<2500000 $kondisi ORDER BY $urut $urutan";
	if ($keyword=="3")
	$query1 = "SELECT s.nis,s.nama,s.foto,k.kelas,s.replid,a.departemen,t.tingkat FROM siswa s, angkatan a, kelas k, tingkat t WHERE s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu>=2500000 AND s.penghasilanayah+s.penghasilanibu<5000000 $kondisi ORDER BY $urut $urutan";
	if ($keyword=="4")
	$query1 = "SELECT s.nis,s.nama,s.foto,k.kelas,s.replid,a.departemen,t.tingkat FROM siswa s, angkatan a, kelas k, tingkat t WHERE s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu>=5000000 $kondisi ORDER BY $urut $urutan";
	if ($keyword=="5")
	$query1 = "SELECT s.nis,s.nama,s.foto,k.kelas,s.replid,a.departemen,t.tingkat FROM siswa s, angkatan a, kelas k, tingkat t WHERE s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu = 0 $kondisi ORDER BY $urut $urutan";
	$result1 = QueryDb($query1);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tampil Statistik</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/rupiah.js"></script>
<script language="javascript">
function change_urut(urut,urutan) {

	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "statistik_tampil.php?departemen=<?=$departemen?>&idangkatan=<?=$idangkatan?>&dasar=<?=$dasar?>&tabel=<?=$tabel?>&judul=<?=$judul?>&iddasar=<?=$iddasar?>&keyword=<?=$keyword?>&urut="+urut+"&urutan="+urutan;
}
</script>
</head>
<body topmargin="0" leftmargin="0">
<div align="right">
<?php $str = ["'", "+"];
	$str_replace = ["\'", "x123x"];	
  	$sql = str_replace($str, $str_replace, (string) $query1);
	//echo $sql;
?>
<!--<a href="#" onclick="newWindow('statistik_cetak_excel.php?idangkatan=<?=$idangkatan?>&departemen=<?=$departemen?>&dasar=<?=$dasar?>&tabel=<?=$tabel?>&judul=<?=$judul?>&iddasar=<?=$iddasar?>&keyword=<?=$keyword?>','CetakExcel',100,100,'');">-->
<a href="#" onclick="newWindow('statistik_cetak_excel.php?sql=<?=$sql?>&departemen=<?=$departemen?>&dasar=<?=$dasar?>&judul=<?=$judul?>&idangkatan=<?=$idangkatan?>','CetakExcel',100,100,'');"><img src="../images/ico/excel.png" border="0" onMouseOver="showhint('Cetak dalam format Excel!', this, event, '80px')"/>&nbsp;Cetak Excel</a></div>
<br />
<table width="100%" border="1" class="tab" id="table" align="center" bordercolor="#000000">
	<tr height="30" class="header" align="center">
  		<td width="5%" >No</td>
     	<td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nis','<?=$urutan?>')">N I S <?=change_urut('s.nis',$urut,$urutan)?></td>
    	<td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nama','<?=$urutan?>')">Nama <?=change_urut('s.nama',$urut,$urutan)?></td>
    	<td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('k.kelas','<?=$urutan?>')">Kelas <?=change_urut('k.kelas',$urut,$urutan)?></td>
        <td width="8%">&nbsp;</td>
	</tr> 
	<?php  if (@mysqli_num_rows($result1)<1) { ?> 
					<td colspan="5" align="center"><strong>Tidak Ada Data</strong></td>
					 
  	<?php } else{
	
    while ($row1 = @mysqli_fetch_row($result1)) { ?>
	<tr height="25">
  		<td align="center"><?=++$cnt?></td>
     	<td align="center"><?=$row1[0] ?></td>
    	<td><?=$row1[1] ?></td>
    	<td align="center"><?=$row1[5]?><br /><?=$row1[6]." - ".$row1[3] ?></td>
        <td ><div align="center"><a href="#" onclick="newWindow('../library/detail_siswa.php?replid=<?=$row1[4]?>','DetailSiswa',790,610,'resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="../images/ico/lihat.png" width="16" height="16" border="0" onMouseOver="showhint('Detail Data Siswa!', this, event, '80px')"/></a></div></td>
   </tr>
		<?php }
    }
		CloseDb();
  	?>
	</table>
	
	<script language='JavaScript'>
	    	Tables('table', 1, 0);
	</script>
	
</body>
</html>