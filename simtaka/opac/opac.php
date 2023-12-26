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
require_once('../inc/config.php');
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
require_once('../inc/sessioninfo.php');
require_once('../inc/db_functions.php');
OpenDb();
$kriteria = 'all';
if (isset($_REQUEST['kriteria']))
	$kriteria = $_REQUEST['kriteria'];
$keyword = $_REQUEST['keyword'];
$op = '';
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];
	
$onload = "";
if ($kriteria!='all' && $kriteria!='tersedia')
	$onload = "onload=\"document.getElementById('keyword').focus()\"";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Katalog Umum</title>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scr/tables.js"></script>
<script type="text/javascript" src="../scr/tools.js"></script>
<script type="text/javascript" src="../scr/rupiah.js"></script>
<script type="text/javascript">
function chgKrit(){
	var krit = document.getElementById('kriteria').value;
	document.location.href="opac.php?kriteria="+krit;
}
function ViewData(){
	var krit = document.getElementById('kriteria').value;
	var addr;
	if (krit!='all' && krit!='tersedia'){
		var key = document.getElementById('keyword').value;
		addr = "opac.php?kriteria="+krit+"&keyword="+key;
	} else {
		addr = "opac.php?kriteria="+krit;
	}
	document.location.href = addr+"&op=view";
}
function ViewDetail(replid){
	newWindow('../pus/pustaka.view.detail.php?replid='+replid+'&sender=opac', 'DetailPustaka','509','456','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
</head>

<body leftmargin="0" topmargin="0" style="padding-left:25px; padding-bottom:25px; padding-right:25px; padding-top:25px; background-image:url(../img/Fiesta.jpg); background-attachment:fixed; margin-left:0px; margin-top:0px" <?=$onload?>>
<div id="title" align="right">
    <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
  <font style="font-size:18px; color:#999999">Katalog Umum</font><br />
  <a href="../login.php" class="welc">Halaman Depan</a><span class="welc"> > Pencarian Katalog Buku</span><br /><br />
</div>
<div id="content">
<div id="filter">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="52%" valign="top">
        <table width="58%" border="0" cellspacing="1" cellpadding="1">
          <tr>
            <td width="23%"><strong>Tampilkan&nbsp;katalog&nbsp;buku&nbsp;berdasarkan</strong></td>
            <td width="77%">
            <select name="kriteria" class="cmbfrm" id="kriteria" onchange="chgKrit()">
                <option value="all" <?=StringIsSelected('all',$kriteria)?>>Semua Buku</option>
                <option value="judul" <?=StringIsSelected('judul',$kriteria)?>>Judul</option>
                <option value="keyword" <?=StringIsSelected('keyword',$kriteria)?>>Keyword</option>
                <option value="penulis" <?=StringIsSelected('penulis',$kriteria)?>>Penulis</option>
                <option value="penerbit" <?=StringIsSelected('penerbit',$kriteria)?>>Penerbit</option>
                <option value="tahun" <?=StringIsSelected('tahun',$kriteria)?>>Tahun Terbit</option>
                <option value="kota" <?=StringIsSelected('kota',$kriteria)?>>Kota Penerbit</option>
                <option value="tersedia" <?=StringIsSelected('tersedia',$kriteria)?>>Semua Buku Yang Tersedia</option>
            </select>
            </td>
          </tr>
          <?php if ($kriteria!='all' && $kriteria!='tersedia'){ ?>
          <?php 
		  		if ($kriteria=='judul') 
		  			$title='Judul';
				if ($kriteria=='keyword')
					$title='Keyword';
				if ($kriteria=='penulis')
					$title='Penulis';
				if ($kriteria=='penerbit')
					$title='Penerbit';
				if ($kriteria=='tahun')
					$title='Tahun Terbit';
				if ($kriteria=='kota')
					$title='Kota Penerbit';	
		  ?>
          <tr>
            <td align="right"><strong><?=$title?></strong></td>
            <td><input type="text" name="keyword" id="keyword" class="inptxt-small-text" value="<?=$keyword?>" style="width:250px" /></td>
          </tr>
          <?php } ?>
        </table>
    </td>
    <td width="48%" valign="top"><img src="../img/view.png" onclick="ViewData()" style="cursor:pointer" /></td>
  </tr>
</table>
</div>
<div id="content">
<?php if ($op!='view') { ?>
<table width="100" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="welc">Tentukan&nbsp;Kriteria&nbsp;lalu&nbsp;klik&nbsp;ikon&nbsp;</td>
    <td class="welc" height="300"><img src="../img/view.png" width="30" height="23" /></td>
    <td class="welc">&nbsp;di&nbsp;atas&nbsp;untuk&nbsp;menampilkan&nbsp;data</td>
  </tr>
</table>
<?php } else { ?>
<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table">
  <tr>
    <td height="25" align="center" class="header">No</td>
    <td height="25" align="center" class="header">Judul</td>
    <td height="25" align="center" class="header">Penulis</td>
    <td height="25" align="center" class="header">Penerbit</td>
    <td height="25" align="center" class="header">Jumlah Seluruhnya</td>
    <td height="25" align="center" class="header">Tersedia</td>
    <td align="center" class="header">&nbsp;</td>
  </tr>
<?php  
if ($kriteria=='all')
	$sql = "SELECT * FROM pustaka ORDER BY judul";
if ($kriteria=='judul')
	$sql = "SELECT * FROM pustaka WHERE judul LIKE '%$keyword%' ORDER BY judul";
if ($kriteria=='keyword')
	$sql = "SELECT * FROM pustaka WHERE keyword LIKE '%$keyword%' ORDER BY judul";	
if ($kriteria=='penerbit')
	$sql = "SELECT * FROM pustaka WHERE penerbit IN (SELECT replid FROM penerbit WHERE nama LIKE '%$keyword%') ORDER BY judul";
if ($kriteria=='penulis')
	$sql = "SELECT * FROM pustaka WHERE penulis IN (SELECT replid FROM penulis WHERE nama LIKE '%$keyword%') ORDER BY judul";	
if ($kriteria=='tahun')
	$sql = "SELECT * FROM pustaka WHERE tahun LIKE '%$keyword%' ORDER BY judul";
if ($kriteria=='kota')
	$sql = "SELECT * FROM pustaka WHERE penerbit IN (SELECT replid FROM penerbit WHERE alamat LIKE '%$keyword%') ORDER BY judul";
if ($kriteria=='tersedia')
	$sql = "SELECT p.replid as replid,p.judul as judul, p.penerbit as penerbit, p.penulis as penulis FROM pustaka p, daftarpustaka d WHERE p.replid=d.pustaka AND d.status=1 GROUP BY d.pustaka ORDER BY p.judul";	
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
if ($num > 0){
$cnt=1;
while ($row = @mysqli_fetch_array($result)){
$sqlPenulis = "SELECT kode,nama FROM penulis WHERE replid = '".$row['penulis']."'";
$resultPenulis = QueryDb($sqlPenulis);
$rowPenulis = @mysqli_fetch_row($resultPenulis);
$penulis = $rowPenulis[0]."&nbsp;-&nbsp;".$rowPenulis[1];

$sqlPenerbit = "SELECT kode,nama FROM penerbit WHERE replid = '".$row['penerbit']."'";
$resultPenerbit = QueryDb($sqlPenerbit);
$rowPenerbit = @mysqli_fetch_row($resultPenerbit);
$penerbit = $rowPenerbit[0]."&nbsp;-&nbsp;".$rowPenerbit[1];

$rtotal = @mysqli_num_rows(QueryDb("SELECT * FROM daftarpustaka d WHERE d.pustaka=$row[0]"));
$rtersedia = @mysqli_num_rows(QueryDb("SELECT * FROM daftarpustaka d WHERE d.pustaka=$row[0] AND d.status=1"));
?>
  <tr>
    <td height="20" align="center"><div class="tab_content"><?=$cnt?></div></td>
    <td height="20"><div class="tab_content"><?=$row['judul']?></div></td>
    <td height="20"><div class="tab_content"><?=$penulis?></div></td>
    <td height="20"><div class="tab_content"><?=$penerbit?></div></td>
    <td height="20" align="center"><div class="tab_content"><?=$rtotal?></div></td>
    <td height="20" align="center" ><div class="tab_content"><?=$rtersedia?></div></td>
    <td align="center" ><div class="tab_content"><a href="javascript:ViewDetail('<?=$row['replid']?>')"><img src="../img/ico/lihat.png" width="16" height="16" border="0" /></a></div></td>
  </tr>
<?php
$cnt++;
}
} else {
?>
  <tr>
    <td height="20" colspan="7" align="center" class="nodata">Tidak ada data</td>
  </tr>
<?php } ?>
</table>
<script language='JavaScript'>
	Tables('table', 1, 0);
</script>
<?php } ?>
</div>
</div>

</body>
</html>
<?php CloseDb(); ?>