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

$statuslunas = -1;
if (isset($_REQUEST['lunas']))
	$statuslunas = (int)$_REQUEST['lunas'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$kat="";
if (isset($_REQUEST['kat']))
	$kat = $_REQUEST['kat'];

$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$dis = "";
if ($idtingkat == -1)
	$dis = "disabled";

$dis1 = "";	
if ($idkategori == "SKR")
	$dis1 = "disabled";


OpenDb();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">
function change_kate()
{
	var idkategori = document.getElementById('idkategori').value;
	var dep = document.getElementById('departemen').value;
	var idang = document.getElementById('idangkatan').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idkel = document.getElementById('idkelas').value;
	var lunas = document.getElementById('lunas').value;
	
	document.location.href = "lapbayarsiswa_kelas.php?idkategori="+idkategori+"&departemen="+dep+"&idangkatan="+idang+"&idkelas="+idkel+"&lunas="+lunas+"&idtingkat="+idtingkat;
}

function change_dep() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var dep = document.getElementById('departemen').value;
	var lunas = document.getElementById('lunas').value;
	
	document.location.href = "lapbayarsiswa_kelas.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&departemen="+dep+"&lunas="+lunas;
	
	//parent.content.location.href = "lapbayarsiswa_kelas_blank.php";
}

function change_ang() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var dep = document.getElementById('departemen').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idang = document.getElementById('idangkatan').value;
	var lunas = document.getElementById('lunas').value;
	
	
	document.location.href = "lapbayarsiswa_kelas.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&departemen="+dep+"&idangkatan="+idang+"&lunas="+lunas+"&idtingkat="+idtingkat;
	
	//parent.content.location.href = "lapbayarsiswa_kelas_blank.php";
}

function change_penerimaan() {
	//parent.content.location.href = "lapbayarsiswa_kelas_blank.php";
}

function change_kelas() {
	//parent.content.location.href = "lapbayarsiswa_kelas_blank.php";
}

function change_status() {
	//parent.content.location.href = "lapbayarsiswa_kelas_blank.php";
}

function show_pembayaran()
{
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var idangkatan = document.getElementById('idangkatan').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idkelas = document.getElementById('idkelas').value;
	var lunas = document.getElementById('lunas').value;
	
	if (idangkatan.length == 0)
    {	
		alert ('Pastikan angkatan sudah ada!');	
		document.getElementById('idangkatan').focus();
		return false;		
	}
    else if (idkategori.length == 0)
    {
		alert ('Pastikan kategori pembayaran sudah ada!');
		document.getElementById('idkategori').focus();
		return false;	
	}
    else if (idpenerimaan.length == 0)
    {
		alert ('Pastikan penerimaan pembayaran sudah ada!');
		document.getElementById('idpenerimaan').focus();
		return false;	
	}
		
	if (idkategori == 'JTT')
        sendRequestText("lapbayarsiswa_kelas_jtt.ajax.php", showContent, "idpenerimaan="+idpenerimaan+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&lunas="+lunas+"&idkategori="+idkategori+"&idtingkat="+idtingkat+"&showpembayaran=true&kat=jtt&departemen="+dep);
		//document.location.href = "lapbayarsiswa_kelas.php?idpenerimaan="+idpenerimaan+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&lunas="+lunas+"&idkategori="+idkategori+"&idtingkat="+idtingkat+"&showpembayaran=true&kat=jtt&departemen="+dep;
	else
        sendRequestText("lapbayarsiswa_kelas_skr.ajax.php", showContent, "idpenerimaan="+idpenerimaan+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&lunas="+lunas+"&idkategori="+idkategori+"&idtingkat="+idtingkat+"&showpembayaran=true&kat=skr&departemen="+dep);
		//document.location.href = "lapbayarsiswa_kelas.php?idpenerimaan="+idpenerimaan+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&lunas="+lunas+"&idkategori="+idkategori+"&idtingkat="+idtingkat+"&showpembayaran=true&kat=skr&departemen="+dep;
}

function showContent(html)
{
    document.getElementById('contentarea').innerHTML = html;
}

function change_page(page)
{
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var idangkatan = document.getElementById('idangkatan').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idkelas = document.getElementById('idkelas').value;
	var lunas = document.getElementById('lunas').value;
	if (page=="XX")
		page = document.getElementById("page").value;
	if (idkategori == 'JTT')
		var kat = "jtt";
	else  	
		var kat = "skr";
	document.location.href="lapbayarsiswa_kelas.php?idkelas="+idkelas+"&idangkatan="+idangkatan+"&idpenerimaan="+idpenerimaan+"&lunas="+lunas+"&idtingkat="+idtingkat+"&page="+page+"&showpembayaran=true&kat="+kat+"&departemen="+dep+"&idkategori="+idkategori;
}

function refresh()
{
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var idangkatan = document.getElementById('idangkatan').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idkelas = document.getElementById('idkelas').value;
	var lunas = document.getElementById('lunas').value;
	var page = document.getElementById("page").value;
	var kat;
    
	if (idkategori == 'JTT')
		kat = "jtt";
	else  	
		kat = "skr";
        
	document.location.href="lapbayarsiswa_kelas.php?idkelas="+idkelas+"&idangkatan="+idangkatan+"&idpenerimaan="+idpenerimaan+"&lunas="+lunas+"&idtingkat="+idtingkat+"&page="+page+"&showpembayaran=true&kat="+kat+"&departemen="+dep+"&idkategori="+idkategori;
}

function focusNext(elemName, evt)
{
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
    
	if (charCode == 13)
    {
		if (elemName == "tampil") 
			show_pembayaran();
		else 
			document.getElementById(elemName).focus();
            
		return false;
	}
    
	return true;
}

function cetak()
{
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var idangkatan = document.getElementById('idangkatan').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idkelas = document.getElementById('idkelas').value;
	var lunas = document.getElementById('lunas').value;

	if (idkategori == 'JTT')
		var addr = "lapbayarsiswa_kelas_jtt_cetak.php?idpenerimaan="+idpenerimaan+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&lunas="+lunas+"&idkategori="+idkategori+"&idtingkat="+idtingkat+"&departemen="+dep;
	else 
    	var addr = "lapbayarsiswa_kelas_skr_cetak.php?idpenerimaan="+idpenerimaan+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&lunas="+lunas+"&idkategori="+idkategori+"&idtingkat="+idtingkat+"&departemen="+dep;
	
	newWindow(addr, 'CetakLapBayarSiswaKelas','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');		
}
</script>
</head>

<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
    <td rowspan="3" width="52%">
    <table width = "100%" border = "0">
	<tr>
        <td width="18%" class="news_content1">Departemen </td>
        <td>
    	<select name="departemen" class="cmbfrm" id="departemen" style="width:188px" onchange="change_dep()" onKeyPress="return focusNext('idangkatan', event)">
   		<?php 	$sql = "SELECT departemen FROM departemen WHERE aktif = 1 ORDER BY urutan";
            $result = QueryDb($sql);
            while($row = mysqli_fetch_row($result)) {
                if ($departemen == "")
                    $departemen = $row[0]; ?>
                <option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $departemen)?> > <?=$row[0]?></option>
        <?php } ?>
		</select>
        <span class="news_content1">Angkatan </span> </td>
        <td>
        <select name="idangkatan" class="cmbfrm" id="idangkatan" style="width:100px" onchange="change_ang()" onKeyPress="return focusNext('idtingkat', event)">
        <?php 	$sql = "SELECT replid, angkatan FROM angkatan WHERE departemen = '$departemen' AND aktif = 1 ORDER BY angkatan";
            $result = QueryDb($sql);
            while($row = mysqli_fetch_row($result)) {
                if ($idangkatan == 0)
                    $idangkatan = $row[0]; ?>
              <option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $idangkatan)?> > <?=$row[1]?></option>
        <?php } ?>
        </select>
        </td>
    </tr>
     <tr>
    	<td class="news_content1">Kelas </td>
        <td>
        <select name="idtingkat" class="cmbfrm" id="idtingkat" style="width:80px;" onChange="change_ang()" onkeypress="return focusNext('lunas', event)" >
        <option value="-1" <?=IntIsSelected(-1, $idtingkat)?>>(Semua)</option>
        <?php
           
			$sql="SELECT * FROM tingkat WHERE departemen='$departemen' AND aktif = 1 ORDER BY urutan";
            $result=QueryDb($sql);
			
            while ($row=@mysqli_fetch_array($result)){
				
        ?> 
            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $idtingkat)?>><?=$row['tingkat']?></option>
        <?php 	} ?> 
        </select>
       
        <select name="idkelas" class="cmbfrm" id="idkelas" style="width:103px" onchange="change_kelas()" <?=$dis?> onkeypress="return focusNext('lunas', event)">
        <option value="-1">(Semua)</option>
		<?php  $sql = "SELECT DISTINCT k.replid, k.kelas FROM tahunajaran t, kelas k WHERE t.replid = k.idtahunajaran AND k.aktif = 1 AND k.idtingkat = '$idtingkat' AND t.aktif = 1 ORDER BY k.kelas";
            $result = QueryDb($sql);
            while($row = mysqli_fetch_row($result)) {
        ?>       
                <option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $idkelas)?> > <?=$row[1]?></option>
        <?php 	} ?>
        </select>
        <span class="news_content1">Status </span> </td>
        <td>
        <select name="lunas" class="cmbfrm" id="lunas" style="width:100px" onchange="change_status()" <?=$dis1?> onkeypress="return focusNext('idkategori', event)">
            <option value="-1" <?=IntIsSelected(-1, $statuslunas) ?> >(Semua)</option>
            <option value="0" <?=IntIsSelected(0, $statuslunas) ?> >Belum Lunas</option>
            <option value="1" <?=IntIsSelected(1, $statuslunas) ?> >Lunas</option>
            <option value="2" <?=IntIsSelected(2, $statuslunas) ?> >Gratis</option>
        </select>
        
    	</td>
    </tr>
    <tr>
        <td class="news_content1">Pembayaran </td>
        <td colspan="2"> 
        <select name="idkategori" class="cmbfrm" id="idkategori" style="width:188px;" onchange="change_kate()" onkeypress="return focusNext('idpenerimaan', event)">
        <?php  $sql = "SELECT kode, kategori FROM $db_name_fina.kategoripenerimaan WHERE kode IN ('JTT','SKR') ORDER BY urutan";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idkategori == "")
                    $idkategori = $row['kode']  ?>
              <option value="<?=$row['kode'] ?>" <?=StringIsSelected($idkategori, $row['kode']) ?> > <?=$row['kategori'] ?></option>
        <?php } ?>
        </select>       
        <select name="idpenerimaan" class="cmbfrm" id="idpenerimaan" style="width:175px;" onchange="change_penerimaan()" onkeypress="return focusNext('tampil', event)">
        <?php  $sql = "SELECT replid, nama FROM $db_name_fina.datapenerimaan WHERE aktif = 1 AND idkategori = '$idkategori' AND departemen = '$departemen' ORDER BY replid DESC";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idpenerimaan == 0) 
                    $idpenerimaan = $row['replid'];  ?>
              <option value="<?=$row['replid'] ?>" <?=IntIsSelected($row['replid'], $idpenerimaan) ?> > <?=$row['nama'] ?></option>
        <?php } ?>
        </select>
        &nbsp;        
        
        </td>
    </tr>
   
    </table>
	</td>
 	<td width="*" rowspan="2" valign="middle">
    	<a href="#" onclick="show_pembayaran()"><img src="../img/view.png" border="0" height="48" width="48" onmouseover="showhint('Klik untuk menampilkan data laporan pembayaran per kelas!', this, event, '180px')"/></a>    </td>
	<td width="45%" colspan="3" align="right" valign="top">
<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font color="Gray" size="4" face="Verdana, Arial, Helvetica, sans-serif" class="news_title2">Laporan Pembayaran Per Kelas</font>
	</td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>

<div id="contentarea"></div>
</body>
</html>