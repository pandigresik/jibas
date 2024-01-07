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

if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
	
if (isset($_REQUEST['kelompok']))
	$kelompok = (int)$_REQUEST['kelompok'];
	
if (isset($_REQUEST['telat']))
	$telat = (int)$_REQUEST['telat'];
	
$tanggal = "";
if (isset($_REQUEST['tanggal']))
	$tanggal = $_REQUEST['tanggal'];
	
$tgl = MySqlDateFormat($tanggal);

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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>Untitled Document</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">

function refresh() {	
	document.location.href = "lapbayarcalon_nunggak_skr.php?kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal?>";	
}

function cetak() {
	var total = document.getElementById("tes").value;
	var addr = "lapbayarcalon_nunggak_skr_cetak.php?idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal ?>&kelompok=<?=$kelompok?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'CetakLapPembayaranNunggakSkrCalonSiswa','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function excel() {
	var total = document.getElementById("tes").value;
	var addr = "lapbayarcalon_nunggak_skr_excel.php?idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal ?>&kelompok=<?=$kelompok?>&urut=<?=$urut?>&urutan=<?=$urutan?>&total="+total;
	newWindow(addr, 'ExcelLapPembayaranNunggakSkrCalonSiswa','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {	
	var varbaris=document.getElementById("varbaris").value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "lapbayarcalon_nunggak_skr.php?kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal?>&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function change_page(page) {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarcalon_nunggak_skr.php?kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal?>&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarcalon_nunggak_skr.php?kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&page="+hal+"&hal="+hal;
}

function change_baris() {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarcalon_nunggak_skr.php?kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>
</head>
<body leftmargin="0" topmargin="0">

<?php
OpenDb();
if ($kelompok == -1)
	$sql = "SELECT p.idcalon, datediff('$tgl', max(tanggal)) AS x FROM penerimaaniurancalon p, jbsakad.calonsiswa c, jbsakad.prosespenerimaansiswa r WHERE p.idpenerimaan = $idpenerimaan AND c.replid = p.idcalon AND c.idproses = r.replid AND r.aktif = 1 GROUP BY p.idcalon HAVING x >= $telat ORDER BY tanggal DESC";
else
	$sql = "SELECT p.idcalon, datediff('$tgl', max(tanggal)) AS x FROM penerimaaniurancalon p, jbsakad.calonsiswa c WHERE p.idpenerimaan = $idpenerimaan AND c.replid = p.idcalon AND c.idkelompok = '$kelompok' GROUP BY p.idcalon HAVING x >= '$telat' ORDER BY tanggal DESC";

//echo  "$sql<br>";
$result = QueryDb($sql);
$nisstr = "";
while($row = mysqli_fetch_row($result)) {
	if (strlen($nisstr) > 0)
		$nisstr = $nisstr . ",";
	$nisstr = $nisstr . "'" . $row[0] . "'";
}


//Dapatkan namapenerimaan
$sql = "SELECT nama FROM datapenerimaan WHERE replid='$idpenerimaan'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
?>
<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
<?php if (strlen($nisstr) > 0) { 
	$sql = "SELECT MAX(jumlah) FROM (SELECT idcalon, count(replid) AS jumlah FROM penerimaaniurancalon WHERE idcalon IN ($nisstr) GROUP BY idcalon) AS X";
	//echo  "$sql<br>";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$max_n_cicilan = $row[0];
	$table_width = 810 + $max_n_cicilan * 90;
?>
	<table width="100%" border="0" align="center">
    <tr>
    	<td valign="bottom">
    <a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;
    <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
    <a href="JavaScript:excel()"><img src="images/ico/excel.png" border="0" onMouseOver="showhint('Buka di Ms Excel!', this, event, '50px')"/>&nbsp;Excel</a>&nbsp;
    	</td>
	</tr>
	</table>
	<br />
	<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
    <tr height="30" align="center" class="header">
        <td width="30">No</td>
        <td width="80" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nopendaftaran','<?=$urutan?>')">No. Reg <?=change_urut('nopendaftaran',$urut,$urutan)?></td>
        <td width="140" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?></td>
        <td width="50" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('kelompok','<?=$urutan?>')">Kel <?=change_urut('kelompok',$urut,$urutan)?></td>
        <?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
                $n = $i + 1; ?>
                <td width="120"><?="Bayaran-$n" ?></td>	
        <?php  } ?>
        <td width="50">Telat<br /><em>(hari)</em></td>
        <td width="100">Total Pembayaran</td>
    </tr>
<?php
$sql_tot = "SELECT c.replid, c.nopendaftaran, c.nama, k.kelompok FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k WHERE c.idkelompok = k.replid AND c.replid IN ($nisstr) ORDER BY c.nama";

$sql = "SELECT c.replid, c.nopendaftaran, c.nama, k.kelompok FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k WHERE c.idkelompok = k.replid AND c.replid IN ($nisstr) ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 

$result_tot = QueryDb($sql_tot);
$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
$jumlah = mysqli_num_rows($result_tot);
$akhir = ceil($jumlah/5)*5;

$result = QueryDb($sql);
if ($page==0)
	$cnt = 0;
else 
	$cnt = (int)$page*(int)$varbaris;

$totalbiayaall = 0;
$totalbayarall = 0;

$totalbiayaallB = 0;
while ($rowA = @mysqli_fetch_array($result_tot)) {
	$sqlB = "SELECT jumlah FROM penerimaaniurancalon WHERE idcalon = '".$rowA['replid']."' AND idpenerimaan = '$idpenerimaan' ";
	$resultB = QueryDb($sqlB);
	while ($rowB = mysqli_fetch_row($resultB)) {
		$totalbiayaallB += $rowB[0];
	}
}

while ($row = mysqli_fetch_array($result)) {
	$replid = $row['replid']; ?>
<tr height="40">
	<td align="center"><?=++$cnt ?></td>
    <td align="center"><?=$row['nopendaftaran'] ?></td>
    <td><?=$row['nama'] ?></td>
    <td align="center"><?=$row['kelompok'] ?></td>
<?php $sql = "SELECT count(*) FROM penerimaaniurancalon WHERE idcalon = '$replid' AND idpenerimaan = '".$idpenerimaan."'";
	//echo  "$sql<br>";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	$nbayar = $row2[0];
	$nblank = $max_n_cicilan - $nbayar;
	$totalbayar = 0;
	
	if ($nbayar > 0) {
		$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah FROM penerimaaniurancalon WHERE idcalon = '$replid' AND idpenerimaan = '$idpenerimaan' ORDER BY tanggal";
		$result2 = QueryDb($sql);
		while ($row2 = mysqli_fetch_row($result2)) {
			$totalbayar = $totalbayar + $row2[1]; ?>
            <td>
                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse" bordercolor="#000000">
                <tr height="20"><td align="center"><?=FormatRupiah($row2[1]) ?></td></tr>
                <tr height="20"><td align="center"><?=$row2[0] ?></td></tr>
                </table>
            </td>
 <?php 	}
 		$totalbayarall += $totalbayar;
	}	
	for ($i = 0; $i < $nblank; $i++) { ?>
	    <td>
            <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse" bordercolor="#000000">
            <tr height="20"><td align="center">&nbsp;</td></tr>
            <tr height="20"><td align="center">&nbsp;</td></tr>
            </table>
        </td>
    <?php }?>
    	<td align="center">
<?php $sql = "SELECT max(datediff('$tgl', tanggal)) FROM penerimaaniurancalon WHERE idcalon = '$replid' AND idpenerimaan = '".$idpenerimaan."'";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	echo  $row2[0]; ?>
        </td>
        <td align="right"><?=FormatRupiah($totalbayar) ?></td>
    </tr>
<?php
}
?>
    <input type="hidden" name="tes" id="tes" value="<?=$total?>"/>
    <?php if ($page==$total-1){ ?>
	<tr height="40">
        <td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbiayaallB) ?></strong></font></td>
    </tr>
	<?php } ?>
    </table>
    <script language='JavaScript'>
        Tables('table', 1, 0);
    </script>
    <?php CloseDb() ?>
    <?php if ($page==0){ 
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page<$total && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page==$total-1 && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:hidden;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:hidden;'";
		}
	?>
    </td>
</tr> 
<tr>
    <td>
    <table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left">Halaman
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
	  	dari <?=$total?> halaman
		
		<?php 
     // Navigasi halaman berikutnya dan sebelumnya
        ?>
        </td>
    	<td align="center">
    
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
            <font size = "2" color ="red"><b>Tidak ditemukan adanya calon siswa yang menunggak pembayaran.
            </font>
        </td>
    </tr>
    </table>  
<?php } ?>
    </td>
</tr>
</table>
</body>
</html>