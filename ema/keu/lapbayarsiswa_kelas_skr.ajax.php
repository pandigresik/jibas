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
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');

if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
	
if (isset($_REQUEST['idangkatan']))
	$idangkatan = (int)$_REQUEST['idangkatan'];

if (isset($_REQUEST['idtingkat']))
	$idtingkat = (int)$_REQUEST['idtingkat'];

if (isset($_REQUEST['idkelas']))
	$idkelas = (int)$_REQUEST['idkelas'];
	
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Pembayaran Siswa Per Kelas</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function refresh() 
{	
	document.location.href = "lapbayarsiswa_kelas_skr.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&idtingkat=<?=$idtingkat?>";	
}

function cetak() {
	var total = document.getElementById("tes").value;
	var addr = "lapbayarsiswa_kelas_skr_cetak.php?idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&departemen=<?=$departemen?>&total="+total;
	newWindow(addr, 'CetakLapPembayaranIuran','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() {
	var total = document.getElementById("tes").value;
	var addr = "lapbayarsiswa_kelas_skr_excel.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&departemen=<?=$departemen?>&total="+total;
	newWindow(addr, 'CetakLapPembayaranIuranExcel','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	var varbaris=document.getElementById("varbaris").value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "lapbayarsiswa_kelas_skr.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&idtingkat=<?=$idtingkat?>&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function change_page(page) {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarsiswa_kelas_skr.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&idtingkat=<?=$idtingkat?>&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarsiswa_kelas_skr.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&page="+hal+"&hal="+hal;
}

function change_baris() {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarsiswa_kelas_skr.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<?php
OpenDb();

QueryDb("USE jbsfina");

$sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
$res = QueryDb($sql);
$row = @mysqli_fetch_row($res);
$idtahunbuku = $row[0];
if ($idtahunbuku==""){
	echo "<script>";
	echo "alert ('Belum ada Tahun buku yang Aktif di departemen ".$departemen.". Silakan isi/aktifkan Tahun Buku di menu Referensi!');";
	echo "</script>";
	exit;
}
//$idtahunbuku = FetchSingle($sql);

if ($idtingkat == -1) 
{
	$sql = "SELECT MAX(jml) FROM ((SELECT p.nis, COUNT(p.replid) as jml 
								     FROM penerimaaniuran p, jurnal j, jbsakad.siswa s
									WHERE p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku
									  AND p.nis = s.nis AND s.idangkatan = $idangkatan
									  AND p.idpenerimaan = $idpenerimaan GROUP BY p.nis) as X)";
} 
else 
{
	if ($idkelas == -1)
		$sql = "SELECT MAX(jml) FROM ((SELECT p.nis, COUNT(p.replid) as jml 
										 FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k 
										WHERE p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku 
										  AND p.nis = s.nis AND s.idangkatan = $idangkatan AND p.idpenerimaan = $idpenerimaan 
										  AND s.idkelas = k.replid AND k.idtingkat = $idtingkat GROUP BY p.nis) as X)";
	else
		$sql = "SELECT MAX(jml) FROM ((SELECT p.nis, COUNT(p.replid) as jml 
								         FROM penerimaaniuran p, jurnal j, jbsakad.siswa s 
										WHERE p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku 
										  AND p.nis = s.nis AND s.idkelas = $idkelas AND s.idangkatan = $idangkatan 
										  AND p.idpenerimaan = $idpenerimaan GROUP BY p.nis) as X)";
}
	
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$max_n_bayar = $row[0];
$table_width = 520 + $max_n_bayar * 100;

$sql = "SELECT nama FROM datapenerimaan WHERE replid = $idpenerimaan";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
?>

<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
<?php if ($max_n_bayar > 0) { ?>
    <table width="100%" border="0" align="center">
    <tr>
    	<td>
    	<a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
    	</td>
	</tr>
	</table>
	<br />
	<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
    <tr height="30" align="center" class="header">
        <td width="30" >No</td>
        <td width="90" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nis','<?=$urutan?>')">N I S <?=change_urut('nis',$urut,$urutan)?></td>
        <td width="160" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?></td>
        <td width="50">Kelas</td>
    <?php for($i = 0; $i < $max_n_bayar; $i++) { ?>
        <td width="100">Bayaran-<?=$i + 1 ?></td>
    <?php  } ?>
        <td width="100">Total Pembayaran</td>
        <!--<td width="200">Keterangan</td>-->
    </tr>
<?php

if ($idtingkat == -1) 
{
	$sql_tot = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
	              FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku 
				   AND p.nis = s.nis AND s.idkelas = k.replid AND s.idangkatan = $idangkatan 
				   AND p.idpenerimaan = $idpenerimaan AND k.idtingkat = t.replid ORDER BY s.nama";
	
	$sql = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
	          FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
			 WHERE p.nis = s.nis AND s.idkelas = k.replid AND s.idangkatan = $idangkatan 
			   AND p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku 
			   AND p.idpenerimaan = $idpenerimaan AND k.idtingkat = t.replid 
		  ORDER BY $urut $urutan"; 
} 
else 
{
	if ($idkelas == -1)
	{
		$sql_tot = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
		              FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
					 WHERE p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku 
					   AND p.nis = s.nis AND s.idkelas = k.replid AND k.idtingkat = $idtingkat 
					   AND s.idangkatan = $idangkatan AND p.idpenerimaan = $idpenerimaan AND k.idtingkat = t.replid ORDER BY s.nama";
		
		$sql = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
		          FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku 
				   AND p.nis = s.nis AND s.idkelas = k.replid AND k.idtingkat = $idtingkat AND s.idangkatan = $idangkatan 
				   AND p.idpenerimaan = $idpenerimaan AND k.idtingkat = t.replid ORDER BY $urut $urutan"; 
	} 
	else 
	{
		$sql_tot = "SELECT DISTINCT p.nis, s.nama, k.kelas 
		              FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k 
					 WHERE p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku 
					   AND p.nis = s.nis AND s.idkelas = k.replid AND s.idkelas = $idkelas 
					   AND s.idangkatan = $idangkatan AND p.idpenerimaan = $idpenerimaan ORDER BY s.nama";
		
		$sql = "SELECT DISTINCT p.nis, s.nama, k.kelas 
		          FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku 
				   AND p.nis = s.nis AND s.idkelas = k.replid AND s.idkelas = $idkelas 
				   AND s.idangkatan = $idangkatan AND p.idpenerimaan = $idpenerimaan ORDER BY $urut $urutan"; 
	}
}

$result_tot = QueryDb($sql_tot);
$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
$jumlah = mysqli_num_rows($result_tot);
$akhir = ceil($jumlah/5)*5;

$result = QueryDb($sql);
if ($page==0)
	$cnt = 0;
else 
	$cnt = (int)$page*(int)$varbaris;
$totalall = 0;

$totalall2 = 0;
while ($row4 = mysqli_fetch_array($result_tot)) 
{
	$sql3 = "SELECT date_format(p.tanggal, '%d-%b-%y') as tanggal, jumlah 
	           FROM penerimaaniuran p, jurnal j
			  WHERE p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku 
			    AND nis = '".$row4['nis']."' AND idpenerimaan = $idpenerimaan";
	$result3 = QueryDb($sql3);
	$totalbayar2 = 0;
	while ($row3 = mysqli_fetch_array($result3)) 
	{
		$totalbayar2 += $row3['jumlah']; 		
	}  
	$totalall2 += $totalbayar2;
}

while ($row = mysqli_fetch_array($result)) 
{ 
	$nis = $row['nis'];
?>
	
    <tr height="40">
    	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['nis'] ?></td>
        <td align="left"><?=$row['nama'] ?></td>
        <td align="center"><?php if ($idkelas == -1) echo  $row['tingkat']." - "; ?><?=$row['kelas'] ?></td>
<?php 	$sql = "SELECT date_format(p.tanggal, '%d-%b-%y') as tanggal, jumlah 
                  FROM penerimaaniuran p, jurnal j
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku 
				   AND nis = '$nis' AND idpenerimaan = $idpenerimaan";
		$result2 = QueryDb($sql);
		$nbayar = mysqli_num_rows($result2);
		$nblank = $max_n_bayar - $nbayar;
		
		$totalbayar = 0;
		while ($row2 = mysqli_fetch_array($result2)) {
			$totalbayar += $row2['jumlah']; ?>
            <td>
                <table border="1" width="100%" style="border-collapse:collapse" cellpadding="0" cellspacing="0" bordercolor="#000000">
                <tr height="20"><td align="center"><?=FormatRupiah($row2['jumlah']) ?></td></tr>
                <tr height="20"><td align="center"><?=$row2['tanggal'] ?></td></tr>
                </table>
            </td>
<?php 	} //end for 
		$totalall += $totalbayar;

		for ($i = 0; $i < $nblank; $i++) { ?>        
            <td>
                <table border="1" width="100%" style="border-collapse:collapse" cellpadding="0" cellspacing="0" bordercolor="#000000">
                <tr height="20"><td align="center">&nbsp;</td></tr>
                <tr height="20"><td align="center">&nbsp;</td></tr>
                </table>
            </td>
<?php 	} //end for ?>        
		<td align="right"><?=FormatRupiah($totalbayar) ?></td>
        <!--<td align="right"><?=$row['keterangan'] ?></td>-->
    </tr>
<?php } //end for ?>
	<input type="hidden" name="tes" id="tes" value="<?=$total?>"/>
	<?php if ($page==$total-1){ ?>
	<tr height="30">
    	<td bgcolor="#999900" align="center" colspan="<?=4 + $max_n_bayar ?>"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
        <td bgcolor="#999900" align="right"><font color="#FFFFFF"><strong><?=FormatRupiah($totalall2) ?></strong></font></td>
        <!--<td bgcolor="#999900">&nbsp;</td>-->
    </tr>
	<?php } ?>
	</table>
	<?php
    CloseDb();
    ?>
    <script language='JavaScript'>
        Tables('table', 1, 0);
    </script>
    <?php if ($page==0){ 
		$disback="style='display:none;'";
		$disnext="style=''";
		}
		if ($page<$total && $page>0){
		$disback="style=''";
		$disnext="style=''";
		}
		if ($page==$total-1 && $page>0){
		$disback="style=''";
		$disnext="style='display:none;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='display:none;'";
		$disnext="style='display:none;'";
		}
	?>
    </td>
</tr> 
<tr>
    <td>
    
<?php } else { ?>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="250">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
        <br />Tambah data pembayaran pada departemen <?=$departemen?> <?php if ($namakelas) echo  ", kelas ".$namakelas ?> dan kategori <?=$namapenerimaan?> di menu Penerimaan Pembayaran pada bagian Penerimaan.
        
        </b></font>
	</td>
</tr>
</table>  

<?php } ?>
    </td>
</tr>
</table>    
</body>
</html>