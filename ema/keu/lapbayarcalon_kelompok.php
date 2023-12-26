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

$statuslunas = -1;
if (isset($_REQUEST['lunas']))
	$statuslunas = (int)$_REQUEST['lunas'];

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
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">
function change_kate() 
{
	var idkategori = document.getElementById('idkategori').value;
	var dep = document.getElementById('departemen').value;
	var kelompok = document.getElementById('kelompok').value;
	var lunas = document.getElementById('lunas').value;

	document.location.href = "lapbayarcalon_kelompok.php?idkategori="+idkategori+"&kelompok="+kelompok+"&lunas="+lunas+"&departemen="+dep;
}

function change_dep() 
{
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var dep = document.getElementById('departemen').value;
	var lunas = document.getElementById('lunas').value;
	
	document.location.href = "lapbayarcalon_kelompok.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&departemen="+dep+"&lunas="+lunas;
}

function change_penerimaan() { }

function change() { }

function change_status() { }

function show_pembayaran() 
{
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var kelompok = document.getElementById('kelompok').value;
	var lunas = document.getElementById('lunas').value;
	
	if (kelompok.length == 0) 
	{	
		alert ('Pastikan kelompok sudah ada!');	
		document.getElementById('kelompok').focus();
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
	
	if (idkategori == 'CSWJB')	
		sendRequestText("lapbayarcalon_kelompok_jtt.ajax.php", showContent, "idpenerimaan="+idpenerimaan+"&kelompok="+kelompok+"&lunas="+lunas+"&departemen="+dep+"&idkategori="+idkategori);
	else
		sendRequestText("lapbayarcalon_kelompok_skr.ajax.php", showContent, "idpenerimaan="+idpenerimaan+"&kelompok="+kelompok+"&lunas="+lunas+"&departemen="+dep+"&idkategori="+idkategori);
}

function showContent(html)
{
	document.getElementById('contentarea').innerHTML = html;
}

function cetak() 
{
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var kelompok = document.getElementById('kelompok').value;
	var lunas = document.getElementById('lunas').value;
	
	var kat;
	if (idkategori == 'CSWJB')	
		var addr = "lapbayarcalon_kelompok_jtt_cetak.php?idpenerimaan="+idpenerimaan+"&kelompok="+kelompok+"&statuslunas="+lunas+"&departemen="+dep+"&idkategori="+idkategori+"&showpembayaran=true&kat="+kat;
	else
		var addr = "lapbayarcalon_kelompok_skr_cetak.php?idpenerimaan="+idpenerimaan+"&kelompok="+kelompok+"&statuslunas="+lunas+"&departemen="+dep+"&idkategori="+idkategori+"&showpembayaran=true&kat="+kat;

	newWindow(addr, 'CetakNeraca','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_page(page) 
{
	var dep = document.getElementById('departemen').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var kelompok = document.getElementById('kelompok').value;
	var lunas = document.getElementById('lunas').value;
	
	var kat;
	if (idkategori == 'CSWJB')	
		kat="jtt";
	else
		kat="skr";
	if (page == 'XX')	
		page=document.getElementById('page').value;
	
	document.location.href = "lapbayarcalon_kelompok.php?idpenerimaan="+idpenerimaan+"&kelompok="+kelompok+"&lunas="+lunas+"&departemen="+dep+"&idkategori="+idkategori+"&showpembayaran=true&kat="+kat+"&page="+page;
}

function focusNext(elemName, evt) 
{
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :
		((evt.which) ? evt.which : evt.keyCode);
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

</script>
</head>

<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
    <td rowspan="3" width="55%">
    <table width = "100%" border = "0">
	<tr>
        <td width="11%" class="news_content1">Departemen </td>
        <td width="89%">
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
          </select>        </td>
    </tr>
     <tr>
    	<td class="news_content1">Kelompok </td>
        <td>
        <select name="kelompok" class="cmbfrm" id="kelompok" style="width:188px;" onChange="change()"  >
        <option value="-1">(Semua Kelompok)</option>
        <?php
           $sql = "SELECT k.replid,kelompok FROM kelompokcalonsiswa k, prosespenerimaansiswa p  WHERE k.idproses = p.replid AND p.aktif = 1 AND p.departemen = '$departemen' ORDER BY kelompok";
			
            $result=QueryDb($sql);
			
            while ($row=@mysqli_fetch_array($result)){
        ?> 
            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $kelompok)?>><?=$row['kelompok']?></option>
        <?php 	} ?> 
        </select>
        <span class="news_content1">Status </span>
        <select name="lunas" class="cmbfrm" id="lunas" style="width:130px" onchange="change_status()" >
            <option value="-1" <?=IntIsSelected(-1, $statuslunas) ?> >(Semua)</option>
            <option value="0" <?=IntIsSelected(0, $statuslunas) ?> >Belum Lunas</option>
            <option value="1" <?=IntIsSelected(1, $statuslunas) ?> >Lunas</option>
            <option value="2" <?=IntIsSelected(2, $statuslunas) ?> >Gratis</option>
        </select>    	</td>
    </tr>
    <tr>
        <td class="news_content1">Pembayaran </td>
        <td> 
        <select name="idkategori" class="cmbfrm" id="idkategori" style="width:188px;" onchange="change_kate()" >
        <?php  $sql = "SELECT kode, kategori FROM $db_name_fina.kategoripenerimaan WHERE kode IN ('CSWJB','CSSKR') ORDER BY urutan";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idkategori == "")
                    $idkategori = $row['kode']  ?>
                <option value="<?=$row['kode'] ?>" <?=StringIsSelected($idkategori, $row['kode']) ?> > <?=$row['kategori'] ?></option>
        <?php } ?>
        </select>       
        <select name="idpenerimaan" class="cmbfrm" id="idpenerimaan" style="width:175px;" onchange="change_penerimaan()" >
        <?php  $sql = "SELECT replid, nama FROM $db_name_fina.datapenerimaan WHERE aktif = 1 AND idkategori = '$idkategori' AND departemen = '$departemen' ORDER BY replid DESC";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idpenerimaan == 0) 
                    $idpenerimaan = $row['replid'];  ?>
                <option value="<?=$row['replid'] ?>" <?=IntIsSelected($row['replid'], $idpenerimaan) ?> > <?=$row['nama'] ?></option>
        <?php } ?>
        </select>&nbsp;        </td>
    </tr>
    </table>
	</td>
 	<td width="*" rowspan="2" valign="middle">
    	<a href="#" onclick="show_pembayaran()"><img src="../img/view.png" border="0" height="48" width="48" onmouseover="showhint('Klik untuk menampilkan data laporan pembayaran per kelas!', this, event, '180px')"/></a>    </td>
	<td width="45%" colspan="3" align="right" valign="top">
<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font color="Gray" size="4" face="Verdana, Arial, Helvetica, sans-serif" class="news_title2">Laporan Pembayaran Per Kelompok Calon Siswa</font>    </td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>

<div id="contentarea"></div>
<?php CloseDb() ?>
</body>
</html>