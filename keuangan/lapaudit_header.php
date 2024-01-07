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
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/departemen.php');

OpenDb();

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];

$tgl1 = 0;
if (isset($_REQUEST['tgl1']))
	$tgl1 = (int)$_REQUEST['tgl1'];

$bln1 = 0;
if (isset($_REQUEST['bln1']))
	$bln1 = (int)$_REQUEST['bln1'];

$thn1 = 0;
if (isset($_REQUEST['thn1']))
	$thn1 = (int)$_REQUEST['thn1'];

$tgl2 = date("j");
if (isset($_REQUEST['tgl2']))
	$tgl2 = (int)$_REQUEST['tgl2'];

$bln2 = date("n");
if (isset($_REQUEST['bln2']))
	$bln2 = (int)$_REQUEST['bln2'];

$thn2 = date("Y");
if (isset($_REQUEST['thn2']))
	$thn2 = (int)$_REQUEST['thn2'];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>Laporan Audit Perubahan Data</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/ajax.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript">
function change_dep() 
{
	var dep = document.getElementById('departemen').value;
	var tgl1 = document.getElementById('tgl1').value;
	var bln1 = document.getElementById('bln1').value;
	var thn1 = document.getElementById('thn1').value;
	var tgl2 = document.getElementById('tgl2').value;
	var bln2 = document.getElementById('bln2').value;
	var thn2 = document.getElementById('thn2').value;
	
	document.location.href = "lapaudit_header.php?tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+dep;
	parent.contentblank.location.href = "lapaudit_blank.php";
}

function change_tanggal()
{
	parent.contentblank.location.href = "lapaudit_blank.php";
}

function change_bulan()
{
	var departemen = document.getElementById('departemen').value;
	var bln1 = parseInt(document.getElementById('bln1').value);
	var thn1 = parseInt(document.getElementById('thn1').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var thn2 = parseInt(document.getElementById('thn2').value);
	var idtahunbuku = document.getElementById("idtahunbuku").value;
	
	document.location.href = "lapaudit_header.php?bln1="+bln1+"&thn1="+thn1+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+departemen+"&idtahunbuku="+idtahunbuku;
	parent.contentblank.location.href = "lapaudit_blank.php";
}

function show_laporan() 
{
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
		
	if (idtahunbuku.length == 0) 
	{
		alert ('Tahun buku tidak boleh kosong !');
		document.getElementById('departemen').focus();
		return false;
	} 
	else if (tgl1.length == 0) 
	{	
		alert ('Tanggal awal tidak boleh kosong!');	
		document.main.tgl1.focus();
		return false;	
	} 
	else if (tgl2.length == 0) 
	{	
		alert ('Tanggal akhir tidak boleh kosong!');	
		document.main.tgl2.focus();
		return false;	
	}
	
	var validasi = validateTgl(tgl1, bln1, thn1, tgl2, bln2, thn2);
	if (validasi) 	
		parent.contentblank.location.href = "lapaudit_main2.php?departemen="+dep+"&idtahunbuku="+idtahunbuku+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2;
}

function change_tahunbuku()
{
	var departemen = document.getElementById("departemen").value;
	var idtahunbuku = document.getElementById("idtahunbuku").value;
	
	document.location.href = "lapaudit_header.php?departemen="+departemen+"&idtahunbuku="+idtahunbuku;
	parent.contentblank.location.href = "lapaudit_blank.php";
}
</script>
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form name="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
	<td rowspan="3" width="60%">
    <table border="0" width = "100%">
    <tr>
        <td width="15%"><strong>Departemen </strong></td>
        <td colspan="4">
        <select name="departemen" id="departemen" onchange="change_dep()" style="width:115px">
        <?php $dep = getDepartemen(getAccess());
            foreach ($dep as $value) { 
                if ($departemen == "")
                    $departemen = $value ?>
                <option value="<?=$value ?>" <?=StringIsSelected($departemen, $value) ?> > <?=$value ?></option>
        <?php  } ?>     
        </select>
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
				{
					$sel = "selected";
					
					if ($tgl1 == 0)	$tgl1 = $row[2];
					if ($bln1 == 0) $bln1 = $row[3];
					if ($thn1 == 0) $thn1 = $row[4];
				}
				
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
<?php 	if ($tgl1 == 0)	$tgl1 = $tgl2;
		if ($bln1 == 0) $bln1 = $bln2;
		if ($thn1 == 0) $thn1 = $thn2;
					
		$n1 = JmlHari($bln1, $thn1);
		$n2 = JmlHari($bln2, $thn2);	?>     
        <td><strong>Tanggal </strong></td>
        <td width="10">
        	<div id="InfoTgl1"> 
            <select name="tgl1" id="tgl1" onchange="change_tanggal()">
            <?php for($i = 1; $i <= $n1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl1) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
            </div>
       	</td>
        <td width="160">
            <select name="bln1" id="bln1" onchange="change_bulan()">
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln1) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn1" id="thn1" onchange="change_bulan()">
            <?php for($i = $G_START_YEAR; $i <= $thn1+1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn1) ?> > <?=$i ?></option>
            <?php } ?>
            </select>s/d
       	</td>
        <td width="10">
        	<div id="InfoTgl2">
            <select name="tgl2" id="tgl2" onchange="change_tanggal()">
            <?php for($i = 1; $i <= $n2; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
             </div>
        </td>
        <td>
            <select name="bln2" id="bln2" onchange="change_bulan()">
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln2) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn2" id="thn2" onchange="change_bulan()">
            <?php for($i = $G_START_YEAR; $i <= $thn2+1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
        </td>
    </tr>
    </table>
	</td>
	<td rowspan="2" valign="middle">
    	<a href="#" onclick="show_laporan()"><img src="images/view.png" border="0" height="48" width="48" id="tabel" onmouseover="showhint('Klik untuk menampilkan data laporan audit perubahan data keuangan!', this, event, '220px')" /></a>
    </td>
    <td width="40%" align="right">
    	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Audit<br />Perubahan Data Keuangan</font><br />
    	<a target="_parent" href="lapkeuangan.php">
      	<font size="1" color="#000000"><b>Laporan Keuangan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Laporan Audit Perubahan Data</b></font>
	</td>
</tr>
</table>
</form>
<?php 
CloseDb() 
?>
</body>
</html>