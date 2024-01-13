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
$sql = "SELECT day(now()), month(now()), year(now()), 
               day(date_sub(now(), INTERVAL 30 DAY)), month(date_sub(now(), INTERVAL 30 DAY)), year(date_sub(now(), INTERVAL 30 DAY))";		
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

$n1 = JmlHari($bln1, $thn1);
$n2 = JmlHari($bln2, $thn2);

$idkategori = $_REQUEST['idkategori'];
$laporan = $_REQUEST['laporan'];
$dept = $_REQUEST['dept'];
	
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
function change_sel() 
{
	parent.contentblank.location.href ="laprekap_blank.php";
}

function show_pembayaran() 
{
	var idkategori = document.getElementById('idkategori').value;
	var tgl1 = parseInt(document.getElementById('tgl1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var thn1 = parseInt(document.getElementById('thn1').value);
	var tgl2 = parseInt(document.getElementById('tgl2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var thn2 = parseInt(document.getElementById('thn2').value);
	var tanggal1 = escape(thn1 + "-" + bln1 + "-" + tgl1);
	var tanggal2 = escape(thn2 + "-" + bln2 + "-" + tgl2);
	var laporan = document.getElementById('laporan').value;
	var dept = document.getElementById('departemen').value;
	var petugas = document.getElementById('petugas').value;
	
	if (laporan == 1)
		parent.contentblank.location.href = "laprekap_content.php?dept="+dept+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&idkategori="+idkategori+"&petugas="+petugas;
	else
		parent.contentblank.location.href = "laprekapharian_content.php?dept="+dept+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&idkategori="+idkategori+"&petugas="+petugas;
}

function change_date()
{
	var idkategori = document.getElementById('idkategori').value;
	var tgl1 = parseInt(document.getElementById('tgl1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var thn1 = parseInt(document.getElementById('thn1').value);
	var tgl2 = parseInt(document.getElementById('tgl2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var thn2 = parseInt(document.getElementById('thn2').value);
	var laporan = document.getElementById('laporan').value;
	var dept = document.getElementById('departemen').value;
	
	document.location.href = "laprekap_header.php?idkategori="+idkategori+"&tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&laporan="+laporan+"&dept="+dept;
	parent.contentblank.location.href ="laprekap_blank.php";
}
</script>
</head>

<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form method="post" name="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
	<td rowspan="3" width="60%">
    <table border="0" style="border-collapse: collapse" width = "100%">
    <tr>
        <td width="15%"><strong>Departemen </strong></td>
        <td colspan="4">
    	<select id="departemen" name="departemen" style="width:188px" onchange="change_sel()">
   <?php   if (getAccess() == "ALL")
   		    echo  "<option value='ALL' selected>(Semua)</option>";
			
        $dep = getDepartemen(getAccess());
        foreach($dep as $value) 
		{
			$sel = $dept == $value ? "selected" : ""; ?>
            <option value="<?=$value ?>" <?=$sel?>><?=$value ?></option>
        <?php } ?>  
    	</select>&nbsp;<strong>Jenis</strong>&nbsp;
        <select name="idkategori" id="idkategori" style="width:188px;" onchange="change_sel()" >
        <?php  $sql = "SELECT kode, kategori FROM kategoripenerimaan ORDER BY urutan";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idkategori == "")
                    $idkategori = $row['kode']  ?>
                <option value="<?=$row['kode'] ?>" <?=StringIsSelected($idkategori, $row['kode']) ?> > <?=$row['kategori'] ?></option>
        <?php } ?>
        </select> 
        </td>
 	</tr>
    <tr>
    	<td><strong>Tanggal </strong></td>
       	<td width="10">
        	<div id="InfoTgl1">      
            <select name="tgl1" id = "tgl1" onchange="change_date()" >
            <option value="">[Tgl]</option>
            <?php for($i = 1; $i <= $n1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl1) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
         	</div>
     	</td>
        <td width="160">
            <select name="bln1" id="bln1" onchange="change_date()" >
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln1) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn1" id="thn1" onchange="change_date()" >
            <?php for($i = $G_START_YEAR; $i <= $thn1+1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn1) ?> > <?=$i ?></option>
            <?php } ?>
            </select> s/d
       	</td>
        <td width="10">
         	<div id="InfoTgl2">
        	<select name="tgl2" id="tgl2" onchange="change_date()" >
            <option value="">[Tgl]</option>
			<?php for($i = 1; $i <= $n2; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
            </div>
        </td>
        <td>
            <select name="bln2" id="bln2" onchange="change_date()" >
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln2) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn2" id="thn2" onchange="change_date()" >
            <?php for($i = $G_START_YEAR; $i <= $thn2+2; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
    	</td>
   	</tr>
    <tr>
    	<td width="15%"><strong>Laporan </strong></td>
        <td colspan="4">
        <select name="laporan" id="laporan" style="width:148px;" onchange="change_sel()" >
        	<option value="1" <?=IntIsSelected(1, $laporan)?>>Rekapitulasi Total</option>
            <option value="2" <?=IntIsSelected(2, $laporan)?>>Rekapitulasi Harian</option>
        </select>
		&nbsp;&nbsp;
		Petugas:
		<select name="petugas" id="petugas" style="width:148px;" onchange="change_sel()" >
			<option value="ALL">(Semua Petugas)</option>
			<option value="landlord">Administrator JIBAS</option>
<?php 		$sql = "SELECT p.nip, p.nama
					  FROM jbsuser.hakakses h, jbssdm.pegawai p, jbsuser.login l
					 WHERE h.modul = 'KEUANGAN'
					   AND h.login = l.login
					   AND l.login = p.nip
					 ORDER BY p.nama";
			$res = QueryDb($sql);
			while($row = mysqli_fetch_row($res))
			{
				echo "<option value='".$row[0]."'>".$row[1]."</option>";
			} ?>			
		</select>
        </td>
 	</tr>
    </table>
    </td>
	<td width="*" rowspan="3" valign="middle">
		<a href="#" onclick="show_pembayaran()">
			<img src="images/view.png" border="0" height="48"  width="48" id="tabel"
				 onmouseover="showhint('Klik untuk menampilkan data laporan pembayaran per siswa!', this, event, '200px')"/>
		</a>
     </td>
	<td width="40%" colspan="3" align="right" valign="top">
	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Rekapitulasi Penerimaan</font><br />
    <a href="penerimaan.php" target="_parent">
      <font size="1" color="#000000"><b>Penerimaan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Laporan Rekapitulasi Penerimaan</b></font>
	</td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
</form>
</body>
<?php
CloseDb();
?>
</html>