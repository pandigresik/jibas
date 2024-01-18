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

$idkategori = "";
if (isset($_REQUEST['idkategori']))
	$idkategori = $_REQUEST['idkategori'];

$idpenerimaan = 0;
if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$kelompok = -1;
if (isset($_REQUEST['kelompok']))
	$kelompok = (int)$_REQUEST['kelompok'];

$telat = 30;
if (isset($_REQUEST['telat']))
	$telat = (int)$_REQUEST['telat'];

$tanggal = date('d')."-".date('m')."-".date('Y');
if (isset($_REQUEST['tanggal']))
	$tanggal = $_REQUEST['tanggal'];
$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
OpenDb(); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/cal2.js"></script>
<script language="javascript" src="../script/cal_conf2.js"></script>
<script language="javascript">
var win = null;
function newWindow(mypage,myname,w,h,features) {
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
}

function change_kate() {
	var idkategori = document.getElementById('idkategori').value;
	var dep = document.getElementById('departemen').value;
	var kelompok = document.getElementById('kelompok').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	document.location.href = "lapbayarcalon_nunggak.php?idkategori="+idkategori+"&departemen="+dep+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok;
	//parent.content.location.href = "lapbayarcalon_nunggak_blank.php";
}

function change_dep() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var dep = document.getElementById('departemen').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	document.location.href = "lapbayarcalon_nunggak.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&departemen="+dep+"&telat="+telat+"&tanggal="+tanggal;
	//parent.content.location.href = "lapbayarcalon_nunggak_blank.php";
}

function change() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var dep = document.getElementById('departemen').value;
	var kelompok = document.getElementById('kelompok').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	document.location.href = "lapbayarcalon_nunggak.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&departemen="+dep+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok;
	//parent.content.location.href = "lapbayarcalon_nunggak_blank.php";
}

function change_penerimaan() {
	//parent.content.location.href = "lapbayarcalon_nunggak_blank.php";
}

function show_pembayaran() {
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var kelompok = document.getElementById('kelompok').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	if (kelompok.length == 0) {	
		alert ('Pastikan kelompok calon siswa sudah ada!');	
		document.getElementById('kelompok').focus();
		return false;		
	} else if (idkategori.length == 0) {
		alert ('Pastikan kategori pembayaran sudah ada!');
		document.getElementById('idkategori').focus();
		return false;	
	} else if (idpenerimaan.length == 0) {
		alert ('Pastikan penerimaan pembayaran sudah ada!');
		document.getElementById('idpenerimaan').focus();
		return false;	
	}
	var kat;
	if (idkategori == "CSWJB")
		kat="jtt";
	else
		kat="skr";
	//if (idkategori == "CSWJB")
		//parent.content.location.href = "lapbayarcalon_nunggak.php?idpenerimaan="+idpenerimaan+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok;
	//else
		//parent.content.location.href = "lapbayarcalon_nunggak.php?idpenerimaan="+idpenerimaan+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok;
	document.location.href = "lapbayarcalon_nunggak.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok+"&departemen="+dep+"&showpembayaran=true&kat="+kat;
}
function cetak() {
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var kelompok = document.getElementById('kelompok').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	if (kelompok.length == 0) {	
		alert ('Pastikan kelompok calon siswa sudah ada!');	
		document.getElementById('kelompok').focus();
		return false;		
	} else if (idkategori.length == 0) {
		alert ('Pastikan kategori pembayaran sudah ada!');
		document.getElementById('idkategori').focus();
		return false;	
	} else if (idpenerimaan.length == 0) {
		alert ('Pastikan penerimaan pembayaran sudah ada!');
		document.getElementById('idpenerimaan').focus();
		return false;	
	}
	var kat;
	if (idkategori == "CSWJB")
		var addr = "lapbayarcalon_nunggak_jtt_cetak.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok+"&departemen="+dep;
	else
		var addr = "lapbayarcalon_nunggak_skr_cetak.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok+"&departemen="+dep;
	newWindow(addr, 'CetakNeraca','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function change_page(page) {
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var kelompok = document.getElementById('kelompok').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	if (page == "XX")
		page=document.getElementById('page').value;
	var kat;
	if (idkategori == "CSWJB")
		kat="jtt";
	else
		kat="skr";
	
	document.location.href = "lapbayarcalon_nunggak.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok+"&departemen="+dep+"&showpembayaran=true&kat="+kat+"&page="+page;	
}
function focusNext(elemName, evt) {
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :
		((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel')
			show_pembayaran();
		return false;
	}
	return true;
}

</script>
<style type="text/css">
<!--
.style1 {font-size: 2px}
-->
</style>
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form name="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
	<td width="56%" rowspan="3">
    <table border="0" width="100%" >
    <tr>
        <td width="15%" class="news_content1">Departemen </td>
        <td>
    	<select name="departemen" class="cmbfrm" id="departemen" style="width:188px" onchange="change_dep()">
    	        <?php 	$sql = "SELECT departemen FROM departemen WHERE aktif = 1 ORDER BY urutan";
            $result = QueryDb($sql);
            while($row = mysqli_fetch_row($result)) {
                if ($departemen == "")
                    $departemen = $row[0]; ?>
    	      <option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $departemen)?> > 
    	        <?=$row[0]?>
   	          </option>
   	              <?php } ?>
          </select>
		<?php   $sql = "SELECT replid FROM jbsfina.tahunbuku WHERE departemen='$departemen' AND aktif=1";
	     $idtahunbuku = FetchSingle($sql); ?>
		  <input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku?>" /> 
		</td>
		</tr>
    <tr>
    	<td class="news_content1">Kelompok </td>
        <td>
        <select name="kelompok" class="cmbfrm" id="kelompok" style="width:188px;" onChange="change()" >
        <option value="-1">(Semua Kelompok)</option>
        <?php
           
			 $sql = "SELECT k.replid,kelompok FROM kelompokcalonsiswa k, prosespenerimaansiswa p  WHERE k.idproses = p.replid AND p.aktif = 1 AND p.departemen = '$departemen' ORDER BY kelompok";
            $result=QueryDb($sql);
			
            while ($row=@mysqli_fetch_array($result)){
        ?> 
            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $kelompok)?>><?=$row['kelompok']?></option>
        <?php 	} ?> 
        </select>
       
        <span class="news_content1">Telat bayar
    	<input name="telat" type="text" class="inputtxt" id="telat" style="text-align:center"  value="<?=$telat ?>" size="2" maxlength="3"/>
    	 hari, dari </span>
        <input name="tcicilan" type="text" class="inputtxt" id="tcicilan" style="text-align:center" onclick="showCal('Calendar1')"  value="<?=$tanggal?>" size="10" maxlength="10"/>
        <a href="JavaScript:showCal('Calendar1')"><img src="../img/calendar.jpg" border="0" /></a>        </td>
    </tr>
    <tr>
        <td class="news_content1">Pembayaran </td>
        <td> 
        <select name="idkategori" class="cmbfrm" id="idkategori" style="width:188px" onchange="change_kate()" >
        <?php  
			$sql = "SELECT kode, kategori FROM $db_name_fina.kategoripenerimaan WHERE kode IN ('CSWJB') ORDER BY urutan";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idkategori == "")
                    $idkategori = $row['kode']  ?>
                <option value="<?=$row['kode'] ?>" <?=StringIsSelected($idkategori, $row['kode']) ?> > <?=$row['kategori'] ?></option>
        <?php } ?>
        </select>
        <select name="idpenerimaan" class="cmbfrm" id="idpenerimaan" style="width:255px" onchange="change_penerimaan()" >
        <?php  $sql = "SELECT replid, nama FROM $db_name_fina.datapenerimaan WHERE aktif = 1 AND idkategori = '$idkategori' ORDER BY replid DESC";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idpenerimaan == 0) 
                    $idpenerimaan = $row['replid'];  ?>
                <option value="<?=$row['replid'] ?>" <?=IntIsSelected($row['replid'], $idpenerimaan) ?> > <?=$row['nama'] ?></option>
        <?php } ?>
        </select>        </td>
    </tr>
    </table>
    <td width="4%" rowspan="4" valign="middle">
        <a href="#" onclick="show_pembayaran()"><img src="../img/view.png" border="0" height="48" width="48" id="tabel" /></a>	</td>
    <td width="40%" align="right" valign="top">
   	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
    <span class="news_title2">Laporan Pembayaran<br />
    Calon Siswa Yang Menunggak </span></td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
</form>
<div id="contentarea">
<?php
if (isset($_REQUEST['showpembayaran'])){
	$tgl = MySqlDateFormat($_REQUEST['tanggal']);
	
	if ($_REQUEST['kat']=="jtt")
	{
		if ($kelompok == -1) 
		{
			$sql = "SELECT idbesarjttcalon, datediff('$tgl', max(tanggal)) as x 
					  FROM jbsfina.penerimaanjttcalon p , jbsfina.besarjttcalon b, jbsakad.calonsiswa c, jbsakad.prosespenerimaansiswa r 
					   WHERE p.idbesarjttcalon = b.replid AND b.lunas = 0 AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
						  AND c.replid = b.idcalon AND c.idproses = r.replid AND r.aktif = 1 
				   GROUP BY idbesarjttcalon  
					 HAVING x >= '$telat' ORDER BY idbesarjttcalon";
		} 
		else 
		{
			$sql = "SELECT idbesarjttcalon, datediff('$tgl', max(tanggal)) as x 
					  FROM jbsfina.penerimaanjttcalon p , jbsfina.besarjttcalon b, jbsakad.calonsiswa c 
						WHERE p.idbesarjttcalon = b.replid AND b.lunas = 0 AND b.info2='$idtahunbuku' AND c.replid = b.idcalon 
						  AND c.idkelompok = '$kelompok' AND b.idpenerimaan = '$idpenerimaan' 
				   GROUP BY idbesarjttcalon
					  HAVING x >= '$telat' ORDER BY idbesarjttcalon";
		} 	
		
		
		$result = QueryDb($sql);
		$idstr = "";
		while($row = mysqli_fetch_row($result)) 
		{
			if (strlen($idstr) > 0)
				$idstr = $idstr . ",";
			$idstr = $idstr . $row[0];
		}
		
		//Dapatkan namapenerimaan
		$sql = "SELECT nama FROM $db_name_fina.datapenerimaan WHERE replid='$idpenerimaan'";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$namapenerimaan = $row[0];
		?>
		
		<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
		<!-- TABLE CENTER -->
		<tr>
			<td>
		<?php if (strlen($idstr) > 0) { 
			$sql = "SELECT MAX(jumlah) FROM (SELECT idbesarjttcalon, count(replid) AS jumlah FROM $db_name_fina.penerimaanjttcalon WHERE idbesarjttcalon IN ($idstr) GROUP BY idbesarjttcalon) AS X";
			//echo "$sql<br>";
			$result = QueryDb($sql);
			$row = mysqli_fetch_row($result);
			$max_n_cicilan = $row[0];
			$table_width = 810 + $max_n_cicilan * 90;
		
		?>
			<table width="100%" border="0" align="center">
			<tr>
				<td align="right" valign="bottom">
			<!--<a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;-->
			<a href="JavaScript:cetak()"><img src="../img/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;				</td>
			</tr>
			</table>
			<br />
			<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
			<tr height="30" align="center" class="header">
				<td width="30">No</td>
				<td width="80">No. Reg </td>
				<td width="140">Nama </td>
				<td width="75">Kel </td>
				<?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
						$n = $i + 1; ?>
						<td class="header" width="120" align="center"><?="Bayaran-$n" ?></td>	
				<?php  } ?>
				<td width="80">Telat<br /><em>(hari)</em></td>
				<td width="125"><?=$namapenerimaan ?></td>
				<td width="125">Total Pembayaran</td>
				<td width="125">Total Tunggakan</td>
				<td width="200">Keterangan</td>
			</tr>
		<?php
		$sql_tot = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas FROM calonsiswa c, kelompokcalonsiswa k, $db_name_fina.besarjttcalon b WHERE c.replid = b.idcalon AND c.idkelompok = k.replid AND b.replid IN ($idstr) ORDER BY c.nama";
		
		$sql = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas FROM calonsiswa c, kelompokcalonsiswa k, $db_name_fina.besarjttcalon b WHERE c.replid = b.idcalon AND c.idkelompok = k.replid AND b.replid IN ($idstr) ORDER BY c.nama LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
		
		$result_tot = QueryDb($sql_tot);
		$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
		$jumlah = mysqli_num_rows($result_tot);
		$akhir = ceil($jumlah/5)*5;
		
		$totalbesarjtt = 0;
		$totalbiayaall2= 0;
		while ($rowall = mysqli_fetch_array($result_tot)) {
			$totalbesarjtt += $rowall['besar'];
			$sqlall2 = "SELECT jumlah FROM $db_name_fina.penerimaanjttcalon WHERE idbesarjttcalon = '".$rowall['id']."' ORDER BY tanggal";
			$resall2 = QueryDb($sqlall2);
			while ($rowall2 = mysqli_fetch_row($resall2)) {
				$totalbiayaall2 += $rowall2[0];
			}
		}

		$result = QueryDb($sql);
		if ($page==0)
			$cnt = 0;
		else 
			$cnt = (int)$page*(int)$varbaris;
		
		$totalbiayaall = 0;
		$totalbayarall = 0;
		
		while ($row = mysqli_fetch_array($result)) {
			$idbesarjtt = $row['id'];
			$besarjtt = $row['besar'];
			$ketjtt = $row['keterangan'];
			$totalbiayaall += $besarjtt;
				
		?>
			<tr height="40">
				<td align="center"><?=++$cnt ?></td>
				<td align="center"><?=$row['nopendaftaran'] ?></td>
				<td><?=$row['nama'] ?></td>
				<td align="center"><?=$row['kelompok'] ?></td>
			<?php
			$sql = "SELECT count(*) FROM $db_name_fina.penerimaanjttcalon WHERE idbesarjttcalon = '".$idbesarjtt."'";
			$result2 = QueryDb($sql);
			$row2 = mysqli_fetch_row($result2);
			$nbayar = $row2[0];
			$nblank = $max_n_cicilan - $nbayar;
			$totalbayar = 0;
			
			if ($nbayar > 0) {
				$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah FROM $db_name_fina.penerimaanjttcalon WHERE idbesarjttcalon = '$idbesarjtt' ORDER BY tanggal";
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
		<?php $sql = "SELECT max(datediff('$tgl', tanggal)) FROM $db_name_fina.penerimaanjttcalon WHERE idbesarjttcalon = '".$idbesarjtt."'";
			$result2 = QueryDb($sql);
			$row2 = mysqli_fetch_row($result2);
			echo $row2[0]; ?>
				</td>
				<td align="right"><?=FormatRupiah($besarjtt) ?></td>
				<td align="right"><?=FormatRupiah($totalbayar) ?></td>
				<td align="right"><?=FormatRupiah($besarjtt - $totalbayar) ?></td>
				<td><?=$ketjtt ?></td>
			</tr>
		<?php
		}
		?>
			 <input type="hidden" name="tes" id="tes" value="<?=$total?>"/>
			<?php if ($page==$total-1){ ?>
			<tr height="40">
				<td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
				<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbesarjtt) ?></strong></font></td>
				<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbiayaall2) ?></strong></font></td>
				<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbiayaall2 - $totalbesarjtt) ?></strong></font></td>
				<td bgcolor="#999900">&nbsp;</td>
			</tr>
			<?php } ?>
			</table>
			<script language='JavaScript'>
				Tables('table', 1, 0);
			</script>
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
				<td width="30%" align="left" class="news_content1">Halaman
				<select name="page" class="cmbfrm" id="page" onChange="change_page('XX')">
				<?php for ($m=0; $m<$total; $m++) {?>
					 <option value="<?=$m ?>" <?=IntIsSelected($page,$m) ?>><?=$m+1 ?></option>
				<?php } ?>
				</select>
				dari <?=$total?> halaman
				
				<?php 
			 // Navigasi halaman berikutnya dan sebelumnya
				?>				</td>
			  <td align="center">
			</td>
			</tr>
			</table>
<?php } else { ?>
			<table width="100%" border="0" align="center">          
			<tr>
				<td align="center" valign="middle" height="250">
					<font size = "2" color ="red"><b></font><font color ="red"><span class="err">Tidak ditemukan adanya calon siswa yang menunggak pembayaran.
					</span></font> </td>
			</tr>
			</table>  
		<?php } ?>
			</td>
		</tr>
		</table>
        <?php
	} else {
	//DISINI U/ SKR
		if ($kelompok == -1)
			$sql = "SELECT p.idcalon, datediff('$tgl', max(tanggal)) AS x FROM $db_name_fina.penerimaaniurancalon p, calonsiswa c, prosespenerimaansiswa r WHERE p.idpenerimaan = '$idpenerimaan' AND c.replid = p.idcalon AND c.idproses = r.replid AND r.aktif = 1 GROUP BY p.idcalon HAVING x >= $telat ORDER BY tanggal DESC";
		else
			$sql = "SELECT p.idcalon, datediff('$tgl', max(tanggal)) AS x FROM $db_name_fina.penerimaaniurancalon p, calonsiswa c WHERE p.idpenerimaan = '$idpenerimaan' AND c.replid = p.idcalon AND c.idkelompok = '$kelompok' GROUP BY p.idcalon HAVING x >= $telat ORDER BY tanggal DESC";
		
		//echo "$sql<br>";
		$result = QueryDb($sql);
		$nisstr = "";
		while($row = mysqli_fetch_row($result)) {
			if (strlen($nisstr) > 0)
				$nisstr = $nisstr . ",";
			$nisstr = $nisstr . "'" . $row[0] . "'";
		}
		
		
		//Dapatkan namapenerimaan
		$sql = "SELECT nama FROM $db_name_fina.datapenerimaan WHERE replid='$idpenerimaan'";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$namapenerimaan = $row[0];
		?>
		<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
		<!-- TABLE CENTER -->
		<tr>
			<td>
		<?php if (strlen($nisstr) > 0) { 
			$sql = "SELECT MAX(jumlah) FROM (SELECT idcalon, count(replid) AS jumlah FROM $db_name_fina.penerimaaniurancalon WHERE idcalon IN ($nisstr) GROUP BY idcalon) AS X";
			//echo "$sql<br>";
			$result = QueryDb($sql);
			$row = mysqli_fetch_row($result);
			$max_n_cicilan = $row[0];
			$table_width = 810 + $max_n_cicilan * 90;
		?>
			<table width="100%" border="0" align="center">
			<tr>
				<td valign="bottom" align="right">
			<!--<a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;-->
			<a href="JavaScript:cetak()"><img src="../img/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;				</td>
			</tr>
			</table>
			<br />
			<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
			<tr height="30" align="center" class="header">
				<td width="30">No</td>
				<td width="80">No. Reg </td>
				<td width="140">Nama </td>
				<td width="50">Kel </td>
				<?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
						$n = $i + 1; ?>
						<td width="120"><?="Bayaran-$n" ?></td>	
				<?php  } ?>
				<td width="50">Telat<br /><em>(hari)</em></td>
				<td width="100">Total Pembayaran</td>
			</tr>
		<?php
		$sql_tot = "SELECT c.replid, c.nopendaftaran, c.nama, k.kelompok FROM calonsiswa c, kelompokcalonsiswa k WHERE c.idkelompok = k.replid AND c.replid IN ($nisstr) ORDER BY c.nama";
		
		$sql = "SELECT c.replid, c.nopendaftaran, c.nama, k.kelompok FROM calonsiswa c, kelompokcalonsiswa k WHERE c.idkelompok = k.replid AND c.replid IN ($nisstr) ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
		
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
		
		while ($row = mysqli_fetch_array($result)) {
			$replid = $row['replid']; ?>
		<tr height="40">
			<td align="center"><?=++$cnt ?></td>
			<td align="center"><?=$row['nopendaftaran'] ?></td>
			<td><?=$row['nama'] ?></td>
			<td align="center"><?=$row['kelompok'] ?></td>
		<?php $sql = "SELECT count(*) FROM $db_name_fina.penerimaaniurancalon WHERE idcalon = '$replid' AND idpenerimaan = '".$idpenerimaan."'";
			//echo "$sql<br>";
			$result2 = QueryDb($sql);
			$row2 = mysqli_fetch_row($result2);
			$nbayar = $row2[0];
			$nblank = $max_n_cicilan - $nbayar;
			$totalbayar = 0;
			
			if ($nbayar > 0) {
				$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah FROM $db_name_fina.penerimaaniurancalon WHERE idcalon = '$replid' AND idpenerimaan = '$idpenerimaan' ORDER BY tanggal";
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
		<?php $sql = "SELECT max(datediff('$tgl', tanggal)) FROM $db_name_fina.penerimaaniurancalon WHERE idcalon = '$replid' AND idpenerimaan = '".$idpenerimaan."'";
			$result2 = QueryDb($sql);
			$row2 = mysqli_fetch_row($result2);
			echo $row2[0]; ?>
				</td>
				<td align="right"><?=FormatRupiah($totalbayar) ?></td>
			</tr>
		<?php
		}
		?>
			<input type="hidden" name="tes" id="tes" value="<?=$total?>"/>
			<tr height="40">
				<td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
				<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbayarall) ?></strong></font></td>
			</tr>
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
				<td width="30%" align="left" class="news_content1">Halaman
				<select name="page" class="cmbfrm" id="page" onChange="change_page('XX')">
				<?php for ($m=0; $m<$total; $m++) {?>
					 <option value="<?=$m ?>" <?=IntIsSelected($page,$m) ?>><?=$m+1 ?></option>
				<?php } ?>
				</select>
				dari <?=$total?> halaman
				
				<?php 
			 // Navigasi halaman berikutnya dan sebelumnya
				?>			  </td>
				<td align="center">
			<!--input <?=$disback?> type="button" class="cmbfrm2" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
				<?php
				for($a=0;$a<$total;$a++){
					if ($page==$a){
						echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
					} else { 
						echo "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
					}
						 
				}
				?>
				 <input <?=$disnext?> type="button" class="cmbfrm2" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')"-->
				</td>
				<td width="30%" align="right"><!--Jumlah baris per halaman
				<select name="varbaris" id="varbaris" onChange="change_baris()">
				<?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
					<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
				<?php 	} ?>
			   
				</select>--></td>
			</tr>
			</table>
<?php } else { ?>
			<table width="100%" border="0" align="center">          
			<tr>
				<td height="250" align="center" valign="middle" class="err">
					Tidak ditemukan adanya calon siswa yang menunggak pembayaran.				</td>
			</tr>
			</table>  
		<?php } ?>
			</td>
		</tr>
		</table>
		<?php
			//
	}
}
?>
</div>
<?php CloseDb() ?>
</body>
</html>