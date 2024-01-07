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
require_once('include/sessioninfo.php');
require_once('library/departemen.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

OpenDb();
$sql = "SELECT day(now()), month(now()), year(now()), day(date_sub(now(), INTERVAL 30 DAY)), month(date_sub(now(), INTERVAL 30 DAY)), year(date_sub(now(), INTERVAL 30 DAY))";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$tgl2 = $row[0];
$bln2 = $row[1];
$thn2 = $row[2];
$tgl1 = $row[3];
$bln1 = $row[4];
$thn1 = $row[5];
    
if (isset($_REQUEST['tgl1']))
	$tgl1 = (int)$_REQUEST['tgl1'];

if (isset($_REQUEST['bln1']))
	$bln1 = (int)$_REQUEST['bln1'];

if (isset($_REQUEST['thn1']))
	$thn1 = (int)$_REQUEST['thn1'];

if (isset($_REQUEST['tgl2']))
	$tgl2 = (int)$_REQUEST['tgl2'];

if (isset($_REQUEST['bln2']))
	$bln2 = (int)$_REQUEST['bln2'];

if (isset($_REQUEST['thn2']))
	$thn2 = (int)$_REQUEST['thn2'];	

$n1 = JmlHari($bln1,$thn1);
$n2 = JmlHari($bln2,$thn2);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>Untitled Document</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/ajax.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript">
function change_sel() {
	parent.contentblank.location.href = "lappengeluaran_jenis_blank.php";
}

function change_dep() {
	var departemen = document.getElementById('departemen').value;
	var tgl1 = document.getElementById('tgl1').value;
	var bln1 = document.getElementById('bln1').value;
	var thn1 = document.getElementById('thn1').value;
	var tgl2 = document.getElementById('tgl2').value;
	var bln2 = document.getElementById('bln2').value;
	var thn2 = document.getElementById('thn2').value;
		
	document.location.href = "lappengeluaran_jenis_header.php?tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+departemen;
	parent.contentblank.location.href = "lappengeluaran_jenis_blank.php";
	//parent.pilih.location.href = "lappengeluaran_jenis_blank.php";
	//parent.contentblank.location.href = "lappengeluaran_jenis_blank.php";
}

function show_laporan() {
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var dep = document.getElementById('departemen').value;
	var tgl1 = parseInt(document.getElementById('tgl1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var thn1 = parseInt(document.getElementById('thn1').value);
	var tgl2 = parseInt(document.getElementById('tgl2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var thn2 = parseInt(document.getElementById('thn2').value);
	var tanggal1 = escape(thn1 + "-" + bln1 + "-" + tgl1);
	var tanggal2 = escape(thn2 + "-" + bln2 + "-" + tgl2);
	
	if (idtahunbuku.length == 0) {	
		alert ('Tahun Buku tidak boleh kosong!');
		document.getElementById('departemen').focus();
		return false;
	} else if (tgl1.length == 0) {	
		alert ('Tanggal awal tidak boleh kosong!');	
		document.main.tgl1.focus();
		return false;	
	} else if (tgl2.length == 0) {	
		alert ('Tanggal akhir tidak boleh kosong!');	
		document.main.tgl2.focus();
		return false;	
	}
	
	var validasi = validateTgl(tgl1,bln1,thn1,tgl2,bln2,thn2);
	if (validasi)		
		parent.contentblank.location.href = "lappengeluaran_jenis_main2.php?tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&idtahunbuku="+idtahunbuku+"&departemen="+dep
	/*parent.pilih.location.href="lappengeluaran_jenis_pilih.php?departemen="+dep+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2;
	parent.content.location.href = "lappengeluaran_jenis_blank.php";*/
}

function change_tgl1() {
	var th1 = parseInt(document.getElementById('thn2').value);
	var bln1 = parseInt(document.getElementById('bln2').value);
	var tgl1 = parseInt(document.main.tgl2.value);
	var th = parseInt(document.getElementById('thn1').value);
	var bln = parseInt(document.getElementById('bln1').value);
	var tgl = parseInt(document.main.tgl1.value);
	
	validateTgl(tgl,bln,th,tgl1,bln1,th1);
	
	var namatgl = "tgl1";
	var namabln = "bln1";	
	sendRequestText("library/gettanggal.php", show1, "tahun="+th+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);			
}

function change_tgl2() {
	var th1 = parseInt(document.getElementById('thn1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);
	
	var th = parseInt(document.getElementById('thn2').value);
	var bln = parseInt(document.getElementById('bln2').value);
	var tgl = parseInt(document.main.tgl2.value);
	
	validateTgl(tgl1,bln1,th1,tgl,bln,th);
	
	var namatgl = "tgl2";
	var namabln = "bln2";				
	sendRequestText("library/gettanggal.php", show2, "tahun="+th+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);			
}

function show1(x) {
	document.getElementById("InfoTgl1").innerHTML = x;
}

function show2(x) {
	document.getElementById("InfoTgl2").innerHTML = x;
}

function focusNext(elemName, evt) {
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :
		((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel')
			show_laporan();
		return false;
	}
	return true;
}

function panggil(elem){
	parent.contentblank.location.href ="lappengeluaran_jenis_blank.php";
	var lain = new Array('tgl1','bln1','thn1','tgl2','bln2','thn2','departemen');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#FFFF99';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

function change_tahunbuku()
{
	parent.contentblank.location.href = "lappengeluaran_jenis_blank.php";
}
</script>
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form method="post" name="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
<td rowspan="3" width="60%">
    <table border="0" width = "100%">
    <tr>
        <td width="15%"><strong>Departemen </strong></font></td>
        <td colspan="4">
        <select name="departemen" id="departemen" style="width:115px" onchange="change_dep()" onKeyPress="return focusNext('tgl1',event)" onfocus="panggil('departemen')">
        <?php $dep = getDepartemen(getAccess());
            foreach ($dep as $value) { 
				if ($departemen == "")
                	$departemen = $value;
		?>
                <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?>><?=$value ?></option>
        <?php  } ?>     
        </select>
        &nbsp;
        <strong>Tahun Buku </strong>
        <select name="idtahunbuku" id="idtahunbuku" onchange="change_tahunbuku()" style="width:160px">        
<?php 		if ($departemen != "") 
		{ 
			 $sql = "SELECT replid, tahunbuku, DAY(tanggalmulai), MONTH(tanggalmulai), YEAR(tanggalmulai), aktif 
						FROM tahunbuku WHERE departemen='$departemen' ORDER BY replid DESC";
			 $result = QueryDb($sql);
			 while ($row = mysqli_fetch_row($result))
			 {
				  if ($idtahunbuku == 0)
					$idtahunbuku = $row[0];
					  
				  $sel = "";
				  if ($idtahunbuku == $row[0])
					$sel = "selected";
					  
				  $A = "";
				  if ($row[5] == 1)
					$A = "(A)";
					  
				  echo  "<option value='".$row[0]."' $sel>$row[1] $A</option>";
			 }
		} ?>
		</select>
           </td>
    </tr>
    <tr>
        <td><strong>Tanggal </strong></td>
        <td width="10">
            <div id="InfoTgl1">    
            <select name="tgl1" id = "tgl1" onchange="change_tgl1()" onfocus = "panggil('tgl1')" onKeyPress="focusNext('bln1',event)">
            <option value="">[Tgl]</option>        
            <?php for($i = 1; $i <= $n1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl1) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
            </div>       	</td>
        <td width="160">
            <select name="bln1" id="bln1" onchange="change_tgl1()" onfocus ="panggil('bln1')" onKeyPress="focusNext('thn1',event)">
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln1) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn1" id="thn1" onchange="change_tgl1()" onfocus ="panggil('thn1')" onKeyPress="focusNext('tgl2',event)">
            <?php for($i = $G_START_YEAR; $i <= $thn1+1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn1) ?> > <?=$i ?></option>
            <?php } ?>
            </select> s/d        </td>
        <td width="10">
         	<div id="InfoTgl2">
            <select name="tgl2" id="tgl2" onchange="change_tgl2()" onfocus ="panggil('tgl2')" onKeyPress="focusNext('bln2',event)">
            <option value="">[Tgl]</option>
            <?php for($i = 1; $i <= $n2; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
         </div>        </td>
        <td>
            <select name="bln2" id="bln2" onchange="change_tgl2()" onfocus ="panggil('bln2')" onKeyPress="focusNext('thn2',event)">
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln2) ?> > <?=$bulan[$i]?></option>
            <?php } ?>
            </select>
            <select name="thn2" id="thn2" onchange="change_tgl2()" onfocus ="panggil('thn2')" onKeyPress="return focusNext('tabel',event)">
            <?php for($i = $G_START_YEAR; $i <= $thn2+1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>        </td>
    </tr>
    </table>

	</td>
 	<td rowspan="2" width="*" valign="middle">
        <a href="#" onclick="show_laporan()"><img src="images/view.png" border="0" height="48" width="48" id="tabel" onmouseover="showhint('Klik untuk menampilkan data laporan pengeluaran!', this, event, '200px')"/></a>
    </td>
	<td width="40%" colspan="3" align="right" valign="top">
	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Transaksi Pengeluaran</font><br />
    <a target="_parent" href="pengeluaran.php">
      <font size="1" color="#000000"><b>Pengeluaran</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Laporan Transaksi Pengeluaran</b></font>
	</td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
</form>
</body>
</html>