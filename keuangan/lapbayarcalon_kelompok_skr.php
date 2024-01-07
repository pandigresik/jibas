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
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');

$idpenerimaan = 0;
if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
	
$kelompok = -1;
if (isset($_REQUEST['kelompok']))
	$kelompok = (int)$_REQUEST['kelompok'];
	
$departemen = "";
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
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Pembayaran Calon Siswa Per Kelompok</title>
<script language="javascript" src="script/tooltips.js" ></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function refresh() 
{	
	document.location.href = "lapbayarcalon_kelompok_skr.php?departemen=<?=$departemen?>&kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>";	
}

function cetak() 
{
	var total = document.getElementById("tes").value;
	var addr = "lapbayarcalon_kelompok_skr_cetak.php?kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'CetakLapPembayaranIuranCalonSiswa','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() 
{
	var total = document.getElementById("tes").value;
	var addr = "lapbayarcalon_kelompok_skr_excel.php?kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'ExcelLapPembayaranIuranCalonSiswa','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) 
{	
	var varbaris=document.getElementById("varbaris").value;
		
	if (urutan =="ASC")
		urutan="DESC"
	else 
		urutan="ASC"
	
	document.location.href = "lapbayarcalon_kelompok_skr.php?kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function change_page(page) 
{
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarcalon_kelompok_skr.php?kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() 
{
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarcalon_kelompok_skr.php?kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&page="+hal+"&hal="+hal;
}

function change_baris() 
{
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarcalon_kelompok_skr.php?kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<?php
OpenDb();

$sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
$idtahunbuku = FetchSingle($sql);

if ($kelompok == -1)
	$sql = "SELECT max(jml) FROM ((SELECT s.replid, COUNT(p.replid) as jml 
									 FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s 
									WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
									  AND p.idcalon = s.replid AND p.idpenerimaan = '$idpenerimaan' GROUP BY s.replid) as X)";
else
	$sql = "SELECT max(jml) FROM ((SELECT s.replid, COUNT(p.replid) as jml 
									 FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s 
									WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku'
									  AND p.idcalon = s.replid AND s.idkelompok = '$kelompok' AND p.idpenerimaan = '$idpenerimaan' GROUP BY s.replid) as X)";

$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$max_n_bayar = $row[0];
$table_width = 520 + $max_n_bayar * 100;

//Dapatkan namapenerimaan
$sql = "SELECT d.nama, d.departemen FROM datapenerimaan d WHERE d.replid='$idpenerimaan'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
$departemen = $row[1];

$sql = "SELECT kelompok FROM jbsakad.kelompokcalonsiswa WHERE replid='$kelompok'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namakelompok = $row[0];
?>

<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
<?php if ($max_n_bayar > 0) { ?>
    <table width="100%" border="0" align="center">
    <tr>
    	<td>
    	<a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;
    	<a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
        <a href="JavaScript:excel()"><img src="images/ico/excel.png" border="0" onMouseOver="showhint('Buka di Ms Excel!', this, event, '50px')"/>&nbsp;Excel</a>&nbsp;
    	</td>
	</tr>
	</table>
	<br />
	<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
    <tr height="30" align="center">
        <td class="header" width="30">No</td>
        <td class="header" width="90" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nopendaftaran','<?=$urutan?>')">No. Reg <?=change_urut('nopendaftaran',$urut,$urutan)?></td>
        <td class="header" width="160" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?></td>
        <td class="header" width="75" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('kelompok','<?=$urutan?>')">Kel <?=change_urut('kelompok',$urut,$urutan)?></td>
    <?php for($i = 0; $i < $max_n_bayar; $i++) { ?>
        <td class="header" width="125">Bayaran-<?=$i + 1 ?></td>
    <?php  } ?>
        <td class="header" width="125">Total Pembayaran</td>
        <!--<td class="header" width="200">Keterangan</td>-->
    </tr>
<?php

if ($kelompok == -1) 
{
	$sql_tot = "SELECT DISTINCT s.replid, s.nopendaftaran, s.nama, k.kelompok 
	              FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s, jbsakad.kelompokcalonsiswa k 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND p.idcalon = s.replid AND s.idkelompok = k.replid AND p.idpenerimaan = '$idpenerimaan' ORDER BY s.nama";
	
	$sql = "SELECT DISTINCT s.replid, s.nopendaftaran, s.nama, k.kelompok 
	          FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s, jbsakad.kelompokcalonsiswa k 
			 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
			   AND p.idcalon = s.replid AND s.idkelompok = k.replid AND p.idpenerimaan = '$idpenerimaan' 
		  ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
} 
else 
{
	$sql_tot = "SELECT DISTINCT s.replid, s.nopendaftaran, s.nama, k.kelompok 
	              FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s, jbsakad.kelompokcalonsiswa k 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku'
				   AND p.idcalon = s.replid AND s.idkelompok = k.replid AND s.idkelompok = '$kelompok' AND p.idpenerimaan = '$idpenerimaan' ORDER BY s.nama";
	
	$sql = "SELECT DISTINCT s.replid, s.nopendaftaran, s.nama, k.kelompok 
	          FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s, jbsakad.kelompokcalonsiswa k 
			 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku'
			   AND p.idcalon = s.replid AND s.idkelompok = k.replid AND s.idkelompok = '$kelompok' AND p.idpenerimaan = '$idpenerimaan' 
	      ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
}

$result_tot = QueryDb($sql_tot);
$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
$jumlah = mysqli_num_rows($result_tot);
$akhir = ceil($jumlah/5)*5;
while ($x = @mysqli_fetch_row($result_tot)){
	$sql5	= "SELECT jumlah 
	             FROM penerimaaniurancalon p, jurnal j 
             	WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				  AND idcalon = $x[0] AND idpenerimaan = '".$idpenerimaan."'";
	$result5 = QueryDb($sql5);
	while ($row5 = mysqli_fetch_array($result5)) {
	$TotalPembayaran += $row5[0];
	}
}
$result = QueryDb($sql);
if ($page==0)
	$cnt = 0;
else 
	$cnt = (int)$page*(int)$varbaris;
$totalall = 0;
while ($row = mysqli_fetch_array($result)) { 
	$replid = $row['replid'];
?>
	
    <tr height="40">
    	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['nopendaftaran'] ?></td>
        <td align="left"><?=$row['nama'] ?></td>
        <td align="center"><?=$row['kelompok'] ?></td>
<?php 	$sql = "SELECT date_format(p.tanggal, '%d-%b-%y') as tanggal, jumlah 
                  FROM penerimaaniurancalon p, jurnal j
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND idcalon = '$replid' AND idpenerimaan = '".$idpenerimaan."'";
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
                <table border="1" width="100%" style="border-collapse:collapse" bordercolor="#000000" cellpadding="0" cellspacing="0">
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
	<?php if ($total-1 == $page) { ?>
	<tr height="30">
    	<td bgcolor="#999900" align="center" colspan="<?=4 + $max_n_bayar ?>"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
        <td bgcolor="#999900" align="right"><font color="#FFFFFF"><strong><?=FormatRupiah($TotalPembayaran) ?></strong></font></td>
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
    <table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left" colspan="2">Halaman
		<input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
		<input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
	  	dari <?=$total?> halaman
		
		<?php 
     // Navigasi halaman berikutnya dan sebelumnya
        ?>
		<?php
		//for($a=0;$a<$total;$a++){
		//	if ($page==$a){
		//		echo  "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
		//	} else { 
		//		echo  "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
		//	}
		//		 
	    //}
		?>
	     
 		</td>
        <td width="30%" align="right">Jumlah baris per halaman
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>
<?php } else { ?>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="250">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
        <br />Tambah data pembayaran pada departemen <?=$departemen?> <?php if ($namakelompok) echo  ", kelompok ".$namakelompok ?> dan kategori <?=$namapenerimaan?> di menu Penerimaan Pembayaran pada bagian Penerimaan.
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