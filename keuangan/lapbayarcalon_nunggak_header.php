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

$telat = 30;
if (isset($_REQUEST['telat']))
	$telat = (int)$_REQUEST['telat'];

$tanggal = date('d')."-".date('m')."-".date('Y');
if (isset($_REQUEST['tanggal']))
	$tanggal = $_REQUEST['tanggal'];

OpenDb(); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>Untitled Document</title>
<script src="script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript" src="script/cal2.js"></script>
<script language="javascript" src="script/cal_conf2.js"></script>
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
	
	document.location.href = "lapbayarcalon_nunggak_header.php?idkategori="+idkategori+"&departemen="+dep+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok;
	parent.content.location.href = "lapbayarcalon_nunggak_blank.php";
}

function change_dep() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var dep = document.getElementById('departemen').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	document.location.href = "lapbayarcalon_nunggak_header.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&departemen="+dep+"&telat="+telat+"&tanggal="+tanggal;
	parent.content.location.href = "lapbayarcalon_nunggak_blank.php";
}

function change() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var dep = document.getElementById('departemen').value;
	var kelompok = document.getElementById('kelompok').value;
	var telat = document.getElementById('telat').value;
	var tanggal = document.getElementById('tcicilan').value;
	
	document.location.href = "lapbayarcalon_nunggak_header.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&departemen="+dep+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok;
	parent.content.location.href = "lapbayarcalon_nunggak_blank.php";
}

function change_penerimaan() {
	parent.content.location.href = "lapbayarcalon_nunggak_blank.php";
}

function show_pembayaran() {
    var departemen = document.getElementById('departemen').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
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
	if (idtahunbuku.length == 0) {	
		alert ('Belum ada Tahun buku yang Aktif di departemen ybs.\nSilakan isi/aktifkan Tahun Buku di menu Referensi!');
		return false;
	}
	if (idkategori == "CSWJB")
		parent.content.location.href = "lapbayarcalon_nunggak_jtt.php?departemen="+departemen+"&idtahunbuku="+idtahunbuku+"&idpenerimaan="+idpenerimaan+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok;
	else
		parent.content.location.href = "lapbayarcalon_nunggak_skr.php?departemen="+departemen+"&idtahunbuku="+idtahunbuku+"&idpenerimaan="+idpenerimaan+"&telat="+telat+"&tanggal="+tanggal+"&kelompok="+kelompok;
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
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form name="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
	<td width="67%" rowspan="3">
    <table border="0" width="100%" >
    <tr>
        <td width="15%"><strong>Departemen </strong></td>
        <td>
    	<select id="departemen" name="departemen" style="width:188px" onchange="change_dep()" onKeyPress="return focusNext('kelompok',event)">
   <?php   $dep = getDepartemen(getAccess());
        foreach($dep as $value) {
            if ($departemen == "")
                $departemen = $value; ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?>><?=$value ?></option>
        <?php } ?>  
	<?php  $sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_row($res);
		$idtahunbuku = $row[0];
		//$idtahunbuku = FetchSingle($sql);     
	?>
		  <input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku?>" />        
    	</select>
    </tr>
    <tr>
    	<td><strong>Kelompok </strong></td>
        <td>
        <select name="kelompok" id="kelompok" onChange="change()" style="width:188px;" onKeyPress="return focusNext('telat',event)">
        <option value="-1">(Semua Kelompok)</option>
        <?php
           
			 $sql = "SELECT k.replid,kelompok FROM jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p  WHERE k.idproses = p.replid AND p.aktif = 1 AND p.departemen = '$departemen' ORDER BY kelompok";
            $result=QueryDb($sql);
			
            while ($row=@mysqli_fetch_array($result)){
        ?> 
            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $kelompok)?>><?=$row['kelompok']?></option>
        <?php 	} ?> 
        </select>
       
        <strong>Telat bayar</strong>
    	<input type="text" name="telat" id="telat" size="2" value="<?=$telat ?>" maxlength="3" style="text-align:center" onKeyPress="return focusNext('tcicilan',event)"/><strong> hari, dari </strong><input type="text" name="tcicilan" id="tcicilan" style="text-align:center" size="10" maxlength="10" value="<?=$tanggal?>" onclick="showCal('Calendar1')" onKeyPress="return focusNext('idkategori',event)"/>
        <a href="JavaScript:showCal('Calendar1')"><img src="images/calendar.jpg" border="0" onMouseOver="showhint('Buka kalender!', this, event, '100px')"/></a>
        </td>
    </tr>
    <tr>
        <td><strong>Pembayaran </strong></td>
        <td> 
        <select name="idkategori" id="idkategori" style="width:188px" onchange="change_kate()" onKeyPress="return focusNext('idpenerimaan',event)">
        <?php  
			$sql = "SELECT kode, kategori FROM kategoripenerimaan WHERE kode IN ('CSWJB') ORDER BY urutan";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idkategori == "")
                    $idkategori = $row['kode']  ?>
                <option value="<?=$row['kode'] ?>" <?=StringIsSelected($idkategori, $row['kode']) ?> > <?=$row['kategori'] ?></option>
        <?php } ?>
        </select>
        <select name="idpenerimaan" id="idpenerimaan" style="width:255px" onchange="change_penerimaan()" onKeyPress="return focusNext('tabel',event)">
        <?php  $sql = "SELECT replid, nama FROM datapenerimaan WHERE aktif = 1 AND idkategori = '$idkategori' ORDER BY replid DESC";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_array($result)) {
                if ($idpenerimaan == 0) 
                    $idpenerimaan = $row['replid'];  ?>
                <option value="<?=$row['replid'] ?>" <?=IntIsSelected($row['id'], $idpenerimaan) ?> > <?=$row['nama'] ?></option>
        <?php } ?>
        </select>
        </td>
    </tr>
    </table>
    <td width="*" rowspan="4" valign="middle">
        <a href="#" onclick="show_pembayaran()"><img src="images/view.png" border="0" height="48" width="48" id="tabel" onmouseover="showhint('Klik untuk menampilkan data laporan pembayaran siswa yang menunggak!', this, event, '180px')"/></a>
       
	</td>
    <td width="50%" align="right" valign="top">
   	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Pembayaran<br />Calon Siswa Yang Menunggak</font><br />
    <a href="penerimaan.php" target="_parent">
      <font size="1" color="#000000"><b>Penerimaan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Laporan Pembayaran Calon Siswa Yang Menunggak</b></font>
	</td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
</form>
<?php CloseDb() ?>
</body>
</html>
<script language="javascript">
	var sprytextfield1 = new Spry.Widget.ValidationTextField("telat");
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect1 = new Spry.Widget.ValidationSelect("kelompok");	
	var spryselect3 = new Spry.Widget.ValidationSelect("idkategori");
	var spryselect4 = new Spry.Widget.ValidationSelect("idpenerimaan");
</script>