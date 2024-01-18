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
	
$idangkatan = 0;
if (isset($_REQUEST['idangkatan']))
	$idangkatan = (int)$_REQUEST['idangkatan'];

$idtingkat = -1;
if (isset($_REQUEST['idtingkat']))
	$idtingkat = (int)$_REQUEST['idtingkat'];

$idkelas = -1;
if (isset($_REQUEST['idkelas']))
	$idkelas = (int)$_REQUEST['idkelas'];

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
	
$dis = "";
$focus = "return focusNext('idkelas',event)";
if ($idtingkat == -1) {
	$dis = "disabled";
	$focus = "return focusNext('telat',event)";
}
$tgl = MySqlDateFormat($_REQUEST['tanggal']);  	
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
	var idang = document.getElementById('idangkatan').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idkel = document.getElementById('idkelas').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	document.location.href = "lapbayarsiswa_nunggak.php?idkategori="+idkategori+"&departemen="+dep+"&idangkatan="+idang+"&idkelas="+idkel+"&telat="+telat+"&tanggal="+tanggal+"&idtingkat="+idtingkat;
	//parent.content.location.href = "lapbayarsiswa_nunggak_blank.php";
	
}

function change_dep() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var dep = document.getElementById('departemen').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	document.location.href = "lapbayarsiswa_nunggak.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&departemen="+dep+"&telat="+telat+"&tanggal="+tanggal;
	//parent.content.location.href = "lapbayarsiswa_nunggak_blank.php";
}

function change_ang() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var dep = document.getElementById('departemen').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idang = document.getElementById('idangkatan').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	document.location.href = "lapbayarsiswa_nunggak.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&departemen="+dep+"&idangkatan="+idang+"&telat="+telat+"&tanggal="+tanggal+"&idtingkat="+idtingkat;
	//parent.content.location.href = "lapbayarsiswa_nunggak_blank.php";
}

function change_penerimaan() {
	//parent.content.location.href = "lapbayarsiswa_nunggak_blank.php";
}

function change_kelas() {
	//parent.content.location.href = "lapbayarsiswa_nunggak_blank.php";
}

function change_status() {
	//parent.content.location.href = "lapbayarsiswa_nunggak_blank.php";
}
function change_page(page) {
	if (page=="XX")
		page = document.getElementById('page').value;
	
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var idangkatan = document.getElementById('idangkatan').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idkelas = document.getElementById('idkelas').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	var kat;
	if (idkategori == "JTT")
		kat="jtt";
	else
		kat="skr";
	document.location.href = "lapbayarsiswa_nunggak.php?idpenerimaan="+idpenerimaan+"&idkategori="+idkategori+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&telat="+telat+"&tanggal="+tanggal+"&idtingkat="+idtingkat+"&departemen="+dep+"&showpembayaran=true&kat="+kat+"&page="+page;		
}
function show_pembayaran() {
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var idangkatan = document.getElementById('idangkatan').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idkelas = document.getElementById('idkelas').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	if (idangkatan.length == 0) {	
		alert ('Pastikan angkatan sudah ada!');	
		document.getElementById('idangkatan').focus();
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
	if (idkategori == "JTT")
		kat="jtt";
	else
		kat="skr";	
	//if (idkategori == "JTT"){
		document.location.href = "lapbayarsiswa_nunggak.php?idpenerimaan="+idpenerimaan+"&idkategori="+idkategori+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&telat="+telat+"&tanggal="+tanggal+"&idtingkat="+idtingkat+"&departemen="+dep+"&showpembayaran=true&kat="+kat;
		//parent.content.location.href = "lapbayarsiswa_nunggak_jtt.php?idpenerimaan="+idpenerimaan+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&telat="+telat+"&tanggal="+tanggal+"&idtingkat="+idtingkat;
	//} else {
		//document.location.href = "lapbayarsiswa_nunggak.php?idpenerimaan="+idpenerimaan+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&telat="+telat+"&tanggal="+tanggal+"&idtingkat="+idtingkat+"&departemen="+dep+"&showpembayaran=true&kat="+kat;
		//parent.content.location.href = "lapbayarsiswa_nunggak_skr.php?idpenerimaan="+idpenerimaan+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&telat="+telat+"&tanggal="+tanggal+"&idtingkat="+idtingkat+"&departemen="+dep+"&showpembayaran=true&kat="+kat;
	//}
}
function excel(){
	cetak('excel');
}
function cetak(what) {
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var idangkatan = document.getElementById('idangkatan').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idkelas = document.getElementById('idkelas').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	if (idangkatan.length == 0) {	
		alert ('Pastikan angkatan sudah ada!');	
		document.getElementById('idangkatan').focus();
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
	if (idkategori == "JTT")
		kat="jtt";
	else
		kat="skr";	
	var addr;
	if (idkategori == "JTT"){
		if (what=='excel')
			addr = "lapbayarsiswa_nunggak_jtt_cetak_excel.php?idpenerimaan="+idpenerimaan+"&idkategori="+idkategori+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&telat="+telat+"&tanggal="+tanggal+"&idtingkat="+idtingkat+"&departemen="+dep;
		else
			addr = "lapbayarsiswa_nunggak_jtt_cetak.php?idpenerimaan="+idpenerimaan+"&idkategori="+idkategori+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&telat="+telat+"&tanggal="+tanggal+"&idtingkat="+idtingkat+"&departemen="+dep;
	} else {
		if (what=='excel')
			addr = "lapbayarsiswa_nunggak_skr_cetak_excel.php?idpenerimaan="+idpenerimaan+"&idkategori="+idkategori+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&telat="+telat+"&tanggal="+tanggal+"&idtingkat="+idtingkat+"&departemen="+dep;
		else
			addr = "lapbayarsiswa_nunggak_skr_cetak.php?idpenerimaan="+idpenerimaan+"&idkategori="+idkategori+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&telat="+telat+"&tanggal="+tanggal+"&idtingkat="+idtingkat+"&departemen="+dep;
	}
	newWindow(addr, 'CetakNeraca','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form name="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
	<td width="64%" rowspan="3">
    <table border="0" width="100%">
    <tr>
        <td width="10%" class="news_content1">Departemen </td>
        <td width="24%">
    	  <div align="justify">
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
    	  </div></td>
        <td width="9%"><span class="news_content1">Angkatan</span></td>
        <td width="57%">
        <select name="idangkatan" class="cmbfrm" id="idangkatan" style="width:175px" onchange="change_ang()">
        <?php 	$sql = "SELECT replid, angkatan FROM angkatan WHERE departemen = '$departemen' AND aktif = 1 ORDER BY angkatan DESC";
            $result = QueryDb($sql);
            while($row = mysqli_fetch_row($result)) {
                if ($idangkatan == 0)
                    $idangkatan = $row[0]; ?>
                <option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $idangkatan)?> > <?=$row[1]?></option>
        <?php } ?>
        </select>        </td>
    </tr>
    <tr>
    	<td class="news_content1">Kelas </td>
        <td>
          <div align="justify">
            <select name="idtingkat" class="cmbfrm" id="idtingkat" style="width:80px;" onChange="change_ang()">
              <option value="-1">(Semua)</option>
              <?php
           
			$sql="SELECT * FROM tingkat WHERE departemen='$departemen' AND aktif = 1 ORDER BY urutan";
            $result=QueryDb($sql);
			
            while ($row=@mysqli_fetch_array($result)){
        ?> 
              <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $idtingkat)?>>
                <?=$row['tingkat']?>
                </option>
                  <?php 	} ?> 
              </select>
            
              <select name="idkelas" class="cmbfrm" id="idkelas" style="width:103px" onchange="change_kelas()" <?=$dis?>>
                <option value="-1">(Semua)</option>
                <?php  $sql = "SELECT DISTINCT k.replid, k.kelas FROM tahunajaran t, kelas k WHERE t.replid = k.idtahunajaran AND k.aktif = 1 AND k.idtingkat = '$idtingkat' AND t.aktif = 1 ORDER BY k.kelas";
            $result = QueryDb($sql);
            while($row = mysqli_fetch_row($result)) {
        ?>       
                <option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $idkelas)?> > 
                  <?=$row[1]?>
                  </option>
                  <?php 	} ?>
                </select>
          </div></td>
        <td><span class="news_content1">Telat&nbsp;Bayar </span></td>
        <td>
        <input name="telat" type="text" class="inputtxt" id="telat" style="text-align:center" value="<?=$telat ?>" size="2" maxlength="3" /> 
        <span class="news_content1">hari, dari</span> 
        <input name="tcicilan" type="text" class="inputtxt" id="tcicilan" style="text-align:center" onclick="showCal('Calendar1')" value="<?=$tanggal?>" size="10" maxlength="10"/>
        <a href="JavaScript:showCal('Calendar1')"><img src="../img/calendar.jpg" border="0" onMouseOver="showhint('Buka kalender!', this, event, '100px')"/></a>        </td>
    </tr>
    <tr>
        <td class="news_content1">Pembayaran </td>
        <td colspan="3"> 
        <select name="idkategori" class="cmbfrm" id="idkategori" style="width:188px" onchange="change_kate()">
        <?php  
			$sql = "SELECT kode, kategori FROM $db_name_fina.kategoripenerimaan WHERE kode IN ('JTT') ORDER BY urutan";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idkategori == "")
                    $idkategori = $row['kode']  ?>
                <option value="<?=$row['kode'] ?>" <?=StringIsSelected($idkategori, $row['kode']) ?> > <?=$row['kategori'] ?></option>
        <?php } ?>
        </select>
        <select name="idpenerimaan" class="cmbfrm" id="idpenerimaan" style="width:260px" onchange="change_penerimaan()">
        <?php  $sql = "SELECT replid, nama FROM $db_name_fina.datapenerimaan WHERE aktif = 1 AND idkategori = '$idkategori' AND departemen = '$departemen' ORDER BY replid DESC";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idpenerimaan == 0) 
                    $idpenerimaan = $row['replid'];  ?>
                <option value="<?=$row['replid'] ?>" <?=IntIsSelected($row['replid'], $idpenerimaan) ?> > <?=$row['nama'] ?></option>
        <?php } ?>
        </select>        </td>
    </tr>
    </table>
    <td width="7%" rowspan="4" valign="middle">
        <a href="#" onclick="show_pembayaran()"><img src="../img/view.png" border="0" height="48" width="48" onmouseover="showhint('Klik untuk menampilkan data laporan pembayaran siswa yang menunggak!', this, event, '180px')"/></a>	</td>
    <td width="29%" align="right" valign="top">
   	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
    <font color="Gray" size="4" face="Verdana, Arial, Helvetica, sans-serif" class="news_title2">Laporan Pembayaran<br />
    Siswa Yang Menunggak</font>	</td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
</form>
<div id="contentarea">
<?php
if (isset($_REQUEST['showpembayaran']))
{
	if ($_REQUEST['kat'] == "jtt")
	{
		$sql = "SELECT replid FROM jbsfina.tahunbuku WHERE departemen='$departemen' AND aktif=1";
		$idtahunbuku = FetchSingle($sql);

		if ($idtingkat == -1) 
		{
			$sql = "SELECT idbesarjtt, datediff('$tgl', max(tanggal)) as x 
					  FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b, jbsakad.siswa s 
						WHERE p.idbesarjtt = b.replid AND b.lunas = 0 AND b.info2='$idtahunbuku' AND b.idpenerimaan = '$idpenerimaan' 
						  AND s.nis = b.nis AND s.idangkatan = '$idangkatan' 
					GROUP BY idbesarjtt HAVING x >= $telat";
		} 
		else 
		{
			if ($idkelas == -1) 
			{
				$sql = "SELECT idbesarjtt, datediff('$tgl', max(tanggal)) as x 
						  FROM jbsfina.penerimaanjtt p , jbsfina.besarjtt b, jbsakad.siswa s, jbsakad.kelas k 
							WHERE p.idbesarjtt = b.replid AND b.lunas = 0 AND b.info2 = '$idtahunbuku' AND b.idpenerimaan = '$idpenerimaan'
							  AND s.nis = b.nis AND s.idangkatan = '$idangkatan' AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' 
					   GROUP BY idbesarjtt 
						  HAVING x >= $telat";
			} 
			else 
			{
				$sql = "SELECT idbesarjtt, datediff('$tgl', max(tanggal)) as x 
						  FROM jbsfina.penerimaanjtt p , jbsfina.besarjtt b, jbsakad.siswa s 
							WHERE p.idbesarjtt = b.replid AND b.lunas = 0 AND b.info2='$idtahunbuku' AND b.idpenerimaan = '$idpenerimaan'
							  AND s.nis = b.nis AND s.idkelas = '$idkelas' AND s.idangkatan = '$idangkatan'  
					   GROUP BY idbesarjtt 
						  HAVING x >= $telat";
			}
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
		$sql = "SELECT nama FROM jbsfina.datapenerimaan WHERE replid=$idpenerimaan";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$namapenerimaan = $row[0];
		?>
		
		<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
		<!-- TABLE CENTER -->
		<tr>
			<td>
		<?php if (strlen($idstr) > 0) { 
			$sql = "SELECT MAX(jumlah) FROM (SELECT idbesarjtt, count(replid) AS jumlah FROM jbsfina.penerimaanjtt WHERE idbesarjtt IN ($idstr) GROUP BY idbesarjtt) AS X";
			//echo "$sql<br>";
			$result = QueryDb($sql);
			$row = mysqli_fetch_row($result);
			$max_n_cicilan = $row[0];
			$table_width = 810 + $max_n_cicilan * 90;
		
		?>
			<table width="100%" border="0" align="center">
			<tr>
				<td valign="bottom">
			<a href="#" onClick="document.location.reload()"><img src="../img/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;
			<a href="JavaScript:cetak('page')"><img src="../img/ico/print1.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
			<a href="JavaScript:excel()"><img src="../img/ico/excel.png" border="0" onMouseOver="showhint('Buka di Ms Excel!', this, event, '50px')"/>&nbsp;Excel</a>&nbsp;
				</td>
			</tr>
			</table>
			<br />
			<table class="tab" id="table" border="1" style="border-collapse:collapse" width="<?=$table_width ?>" align="left" bordercolor="#000000" cellpadding="5" cellspacing="0">
			<tr height="30" align="center" class="header">
				<td width="30">No</td>
				<td width="80" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;"></td>
				<td width="140" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;"></td>
				<td width="50" >Kelas</td>
				<?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
						$n = $i + 1; ?>
						<td class="header" width="120" align="center"><?="Bayaran-$n" ?></td>	
				<?php  } ?>
				<td width="80">Telat<br /><em>(hari)</em></td>
				<td width="125" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;"></td>
				<td width="125">Total Pembayaran</td>
				<td width="125">Total Tunggakan</td>
				<td width="200" align="center">Keterangan</td>
			</tr>
		<?php
		
		$sql_tot = "SELECT b.nis, s.nama, k.kelas, b.replid AS id, b.besar, b.keterangan, b.lunas FROM jbsakad.siswa s, jbsakad.kelas k, jbsfina.besarjtt b WHERE s.nis = b.nis AND s.idkelas = k.replid AND b.replid IN ($idstr) ORDER BY s.nama";
		
		$sql = "SELECT b.nis, s.nama, k.kelas, b.replid AS id, b.besar, b.keterangan, b.lunas, t.tingkat FROM jbsakad.siswa s, jbsakad.kelas k, jbsfina.besarjtt b, jbsakad.tingkat t WHERE s.nis = b.nis AND s.idkelas = k.replid AND k.idtingkat = t.replid AND b.replid IN ($idstr) ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
		
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
		
		
		$totalbayarallB = 0;
		$besarjttallA = 0;
		$x = 1;
		while ($rowA = @mysqli_fetch_array($result_tot)) {
			$besarjttA = 0;
			$idbesarjttA = $rowA['id'];
			$besarjttA = $rowA['besar'];
			$sqlB = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah FROM jbsfina.penerimaanjtt WHERE idbesarjtt = '$idbesarjttA' ORDER BY tanggal";
			$resultB = QueryDb($sqlB);
			$totalbayarB = 0;
			while ($rowB = @mysqli_fetch_row($resultB)) {
				$totalbayarB = $totalbayarB + $rowB[1]; 	
			}
			$totalbayarallB += $totalbayarB;
			$besarjttallA += $besarjttA;
		}
		
		while ($row = mysqli_fetch_array($result)) {
			$idbesarjtt = $row['id'];
			$besarjtt = $row['besar'];
			$ketjtt = $row['keterangan'];
			$lunasjtt = $row['lunas'];
			$infojtt = "<font color=red><strong>Belum Lunas</strong></font>";
			if ($lunasjtt == 1)
				$infojtt = "<font color=blue><strong>Lunas</strong></font>";
			$totalbiayaall += $besarjtt;
				
		?>
			<tr height="40">
				<td align="center"><?=++$cnt ?></td>
				<td align="center"><?=$row['nis'] ?></td>
				<td><?=$row['nama'] ?></td>
				<td align="center"><?php if ($idkelas == -1) echo $row['tingkat']." - "; ?><?=$row['kelas'] ?></td>
			<?php
			$sql = "SELECT count(*) FROM jbsfina.penerimaanjtt WHERE idbesarjtt = $idbesarjtt";
			$result2 = QueryDb($sql);
			$row2 = mysqli_fetch_row($result2);
			$nbayar = $row2[0];
			$nblank = $max_n_cicilan - $nbayar;
			$totalbayar = 0;
			if ($nbayar > 0) {
				$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah FROM jbsfina.penerimaanjtt WHERE idbesarjtt = '$idbesarjtt' ORDER BY tanggal";
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
		<?php $sql = "SELECT datediff('$tgl', max(tanggal)) FROM jbsfina.penerimaanjtt WHERE idbesarjtt = '".$idbesarjtt."'";
			//echo $sql;
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
			<?php if($page==$total-1){ ?>
			<tr height="40">
				<td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
				<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($besarjttallA) ?></strong></font></td>
				<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbayarallB) ?></strong></font></td>
				<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($besarjttallA - $totalbayarallB) ?></strong></font></td>
				<td bgcolor="#999900">&nbsp;</td>
			</tr>
			<?php } ?>
			</table>
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
					<font size = "2" color ="red"><b>Tidak ditemukan adanya siswa yang menunggak pembayaran.
					</font>
				</td>
			</tr>
			</table>  
		<?php } ?>
        
			</td>
		</tr>
		</table>
		<?php CloseDb() ?>

<?php } else {
	//DISINI BUAT SKR=====================================================================================================
		if ($idtingkat == -1) {
			$sql = "SELECT p.nis, datediff('$tgl', max(tanggal)) AS x FROM $db_name_fina.penerimaaniuran p, siswa s WHERE p.idpenerimaan = '$idpenerimaan' AND s.nis = p.nis AND s.idangkatan = '$idangkatan' GROUP BY p.nis HAVING x >= $telat ORDER BY tanggal DESC";
		} else {
			if ($idkelas == -1)
				$sql = "SELECT p.nis, datediff('$tgl', max(tanggal)) AS x FROM $db_name_fina.penerimaaniuran p, siswa s, kelas k WHERE p.idpenerimaan = '$idpenerimaan' AND s.nis = p.nis AND s.idangkatan = '$idangkatan' AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' GROUP BY p.nis HAVING x >= $telat ORDER BY tanggal DESC";
			else
				$sql = "SELECT p.nis, datediff('$tgl', max(tanggal)) AS x FROM $db_name_fina.penerimaaniuran p, siswa s WHERE p.idpenerimaan = '$idpenerimaan' AND s.nis = p.nis AND s.idangkatan = '$idangkatan' AND s.idkelas = '$idkelas' GROUP BY p.nis HAVING x >= $telat ORDER BY tanggal DESC";
		}
		
		$result = QueryDb($sql);
		$nisstr = "";
		while($row = mysqli_fetch_row($result)) {
			if (strlen($nisstr) > 0)
				$nisstr = $nisstr . ",";
			$nisstr = $nisstr . "'" . $row[0] . "'";
		}
		
		
		//Dapatkan namapenerimaan
		$sql = "SELECT nama FROM $db_name_fina.datapenerimaan WHERE replid=$idpenerimaan";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$namapenerimaan = $row[0];
		?>
		<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
		<!-- TABLE CENTER -->
		<tr>
			<td>
		
		<?php if (strlen($nisstr) > 0) { 
			$sql = "SELECT MAX(jumlah) FROM (SELECT nis, count(replid) AS jumlah FROM $db_name_fina.penerimaaniuran WHERE nis IN ($nisstr) GROUP BY nis) AS X";
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
			<tr height="30" class="header" align="center">
				<td width="30" >No</td>
				<td width="80" >N I S</td>
				<td width="140" >Nama</td>
				<td width="50">Kelas</td>
				<?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
						$n = $i + 1; ?>
						<td class="header" width="120" align="center"><?="Bayaran-$n" ?></td>	
				<?php  } ?>
				<td width="80">Telat<br /><em>(hari)</em></td>
				<td width="100">Total Pembayaran</td>
			</tr>
		<?php
		$sql_tot = "SELECT s.nis, s.nama, k.kelas, t.tingkat FROM siswa s, kelas k, tingkat t WHERE s.idkelas = k.replid AND k.idtingkat = t.replid AND s.nis IN ($nisstr) ORDER BY s.nama";
		
		$sql = "SELECT s.nis, s.nama, k.kelas, t.tingkat FROM siswa s, kelas k, tingkat t WHERE s.idkelas = k.replid AND k.idtingkat = t.replid AND s.nis IN ($nisstr) ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
		
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
			$nis = $row['nis']; ?>
		<tr height="40">
			<td align="center"><?=++$cnt ?></td>
			<td align="center"><?=$row['nis'] ?></td>
			<td><?=$row['nama'] ?></td>
			<td align="center"><?php if ($idkelas == -1) echo $row['tingkat']." - "; ?><?=$row['kelas'] ?></td>
		<?php $sql = "SELECT count(*) FROM $db_name_fina.penerimaaniuran WHERE nis = '$nis' AND idpenerimaan = $idpenerimaan";
			//echo "$sql<br>";
			$result2 = QueryDb($sql);
			$row2 = mysqli_fetch_row($result2);
			$nbayar = $row2[0];
			$nblank = $max_n_cicilan - $nbayar;
			$totalbayar = 0;
			
			if ($nbayar > 0) {
				$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah FROM $db_name_fina.penerimaaniuran WHERE nis = '$nis' AND idpenerimaan = '$idpenerimaan' ORDER BY tanggal";
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
		<?php $sql = "SELECT max(datediff('$tgl', tanggal)) FROM $db_name_fina.penerimaaniuran WHERE nis = '$nis' AND idpenerimaan = '".$idpenerimaan."'";
			echo $sql;
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
				<td align="center" valign="middle" height="250">
					<font size = "2" color ="red"><b>Tidak ditemukan adanya siswa yang menunggak pembayaran.
					</font>
				</td>
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