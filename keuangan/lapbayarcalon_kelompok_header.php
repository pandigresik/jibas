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
require_once('library/departemen.php');
require_once('include/sessioninfo.php');

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

$lunas = -1;
if (isset($_REQUEST['lunas']))
	$lunas = (int)$_REQUEST['lunas'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="script/tooltips.js"></script>
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript">
function change_kate() {
	var idkategori = document.getElementById('idkategori').value;
	var dep = document.getElementById('departemen').value;
	var kelompok = document.getElementById('kelompok').value;
	var lunas = document.getElementById('lunas').value;

	document.location.href = "lapbayarcalon_kelompok_header.php?idkategori="+idkategori+"&kelompok="+kelompok+"&lunas="+lunas+"&departemen="+dep;
	parent.content.location.href = "lapbayarsiswa_kelas_blank.php";
}

function change_dep() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var dep = document.getElementById('departemen').value;
	var lunas = document.getElementById('lunas').value;
	
	document.location.href = "lapbayarcalon_kelompok_header.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&departemen="+dep+"&lunas="+lunas;
	
	parent.content.location.href = "lapbayarsiswa_kelas_blank.php";
}

function change_penerimaan() {
	parent.content.location.href = "lapbayarsiswa_kelas_blank.php";
}

function change() {
	parent.content.location.href = "lapbayarsiswa_kelas_blank.php";
}

function change_status() {
	parent.content.location.href = "lapbayarsiswa_kelas_blank.php";
}

function show_pembayaran() {
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var kelompok = document.getElementById('kelompok').value;
	var lunas = document.getElementById('lunas').value;
	var departemen = document.getElementById('departemen').value;
	
	if (kelompok.length == 0) {	
		alert ('Pastikan kelompok sudah ada!');	
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
	if (idtahunbuku.length == 0) {	
		alert ('Belum ada Tahun buku yang Aktif di departemen ybs.\nSilakan isi/aktifkan Tahun Buku di menu Referensi!');
		return false;
	}	
	if (idkategori == 'CSWJB')
		parent.content.location.href = "lapbayarcalon_kelompok_jtt.php?departemen="+departemen+"&idtahunbuku="+idtahunbuku+"&idpenerimaan="+idpenerimaan+"&kelompok="+kelompok+"&lunas="+lunas;
	else 
		parent.content.location.href = "lapbayarcalon_kelompok_skr.php?departemen="+departemen+"&idtahunbuku="+idtahunbuku+"&idpenerimaan="+idpenerimaan+"&kelompok="+kelompok;
}

function focusNext(elemName, evt) {
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :
		((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) {
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
        <td width="18%"><strong>Departemen </strong></td>
        <td>
    	<select id="departemen" name="departemen" style="width:188px" onchange="change_dep()" onKeyPress="return focusNext('kelompok', event)">
   <?php   OpenDb();
        $dep = getDepartemen(getAccess());
        foreach($dep as $value) {
            if ($departemen == "")
                $departemen = $value; ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?>><?=$value ?></option>
        <?php } ?>  
    	</select>        
   <?php $sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_row($res);
		$idtahunbuku = $row[0];
		//$idtahunbuku = FetchSingle($sql);
		?>
		<input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku?>" />
        </td>
    </tr>
     <tr>
    	<td><strong>Kelompok </strong></td>
        <td>
        <select name="kelompok" id="kelompok" onChange="change()" style="width:188px;" onkeypress="return focusNext('lunas', event)" >
        <option value="-1">(Semua Kelompok)</option>
        <?php
           $sql = "SELECT k.replid,kelompok FROM jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p  WHERE k.idproses = p.replid AND p.aktif = 1 AND p.departemen = '$departemen' ORDER BY kelompok";
			
            $result=QueryDb($sql);
			
            while ($row=@mysqli_fetch_array($result)){
        ?> 
            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $kelompok)?>><?=$row['kelompok']?></option>
        <?php 	} ?> 
        </select>
        <strong>Status </strong>
        <select id="lunas" name="lunas" style="width:130px" onchange="change_status()" onkeypress="return focusNext('idkategori', event)">
            <option value="-1" <?=IntIsSelected(-1, $lunas) ?> >(Semua)</option>
            <option value="0" <?=IntIsSelected(0, $lunas) ?> >Belum Lunas</option>
            <option value="1" <?=IntIsSelected(1, $lunas) ?> >Lunas</option>
            <option value="2" <?=IntIsSelected(2, $lunas) ?> >Gratis</option>
        </select>
        
    	</td>
    </tr>
    <tr>
        <td><strong>Pembayaran </strong></td>
        <td> 
        <select name="idkategori" id="idkategori" style="width:188px;" onchange="change_kate()" onkeypress="return focusNext('idpenerimaan', event)">
        <?php  $sql = "SELECT kode, kategori FROM kategoripenerimaan WHERE kode IN ('CSWJB','CSSKR') ORDER BY urutan";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idkategori == "")
                    $idkategori = $row['kode']  ?>
                <option value="<?=$row['kode'] ?>" <?=StringIsSelected($idkategori, $row['kode']) ?> > <?=$row['kategori'] ?></option>
        <?php } ?>
        </select>       
        <select name="idpenerimaan" id="idpenerimaan" style="width:175px;" onchange="change_penerimaan()" onkeypress="return focusNext('tampil', event)">
        <?php  $sql = "SELECT replid, nama FROM datapenerimaan WHERE aktif = 1 AND idkategori = '$idkategori' AND departemen = '$departemen' ORDER BY replid DESC";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idpenerimaan == 0) 
                    $idpenerimaan = $row['replid'];  ?>
                <option value="<?=$row['replid'] ?>" <?=IntIsSelected($row['id'], $idpenerimaan) ?> > <?=$row['nama'] ?></option>
        <?php } ?>
        </select>&nbsp;        
        
        </td>
    </tr>
   
    </table>
	</td>
 	<td width="*" rowspan="2" valign="middle">
    	<a href="#" onclick="show_pembayaran()"><img src="images/view.png" border="0" height="48" width="48" onmouseover="showhint('Klik untuk menampilkan data laporan pembayaran per kelas!', this, event, '180px')"/></a>
    </td>
	<td width="45%" colspan="3" align="right" valign="top">
<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Pembayaran Per Kelompok Calon Siswa</font><br />
    <a href="penerimaan.php" target="_parent">
      <font size="1" color="#000000"><b>Penerimaan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Laporan Pembayaran Per Kelompok Calon Siswa</b></font>
	</td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
<?php CloseDb() ?>
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect1 = new Spry.Widget.ValidationSelect("kelompok");	
	var spryselect3 = new Spry.Widget.ValidationSelect("lunas");
	var spryselect4 = new Spry.Widget.ValidationSelect("idpenerimaan");
	var spryselect4 = new Spry.Widget.ValidationSelect("idkategori");
</script>