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
require_once('../include/errorhandler.php');
require_once('../include/sessionchecker.php');
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once("../library/datearith.php");

$tahun1 = $_REQUEST['tahun1'];
$bulan1 = $_REQUEST['bulan1'];
$tanggal1 = $_REQUEST['tanggal1'];
$tahun2 = $_REQUEST['tahun2'];
$bulan2 = $_REQUEST['bulan2'];
$tanggal2 = $_REQUEST['tanggal2'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style.css" />
<link rel="stylesheet" href="../script/themes/ui-lightness/jquery.ui.all.css">
<script type="application/x-javascript" src="../script/jquery-1.9.0.js"></script>
<script type="application/x-javascript" src="../script/validator.js"></script>
<script type="application/x-javascript" src="../script/logger.js"></script>
<script type="application/x-javascript" src="../script/tools.js"></script>
<script type="application/x-javascript" src="lembur.input.js"></script>
</head>

<body>
<font style="font-size: 16px; color: #666; font-weight: bold;">Input Lembur Pegawai</font>
<br><br>
<fieldset style="width: 95%; background-color: #f4f5d5">
<legend><strong>Data Lembur Pegawai</strong></legend>
<table id="daftarTab" border="1" style="border-width: 1px; border-collapse: collapse; width: 790px">
<tr height="15">
    <td width="210" align="center" class="header">Tanggal</td>
    <td width="165" align="center" class="header">NIP</td>
    <td width="200" align="center" class="header">Nama</td>
    <td width="100" align="center" class="header">Jam Masuk</td>
    <td width="100" align="center" class="header">Jam Pulang</td>
</tr>    
<tr style="background-color: #fff">
    <td align="center">
        <span id="spdate0">
<?php      $selno = 0;
        include("lembur.input.getdate.php"); ?>            
        </span> 
    </td>
    <td align="left">
        <input type="hidden" name="flagnip0" id="flagnip0" value="-1">
        <input type="text" name="nip0" id="nip0" size="19" onblur="cariPegawai()">
        <input type="button" value=".." style="background-color: #eee" onclick="pilihPegawai()">    
    </td>
    <td align="left">
        <span name="nama0" id="nama0"></span>
    </td>
    <td align="center">
        <input type='text' maxlength='2' size='2' name='jammasuk0' id='jammasuk0' value=''>:
        <input type='text' maxlength='2' size='2' name='menitmasuk0' id='menitmasuk0' value=''>
    </td>
    <td align="center">
        <input type='text' maxlength='2' size='2' name='jampulang0' id='jampulang0' value=''>:
        <input type='text' maxlength='2' size='2' name='menitpulang0' id='menitpulang0' value=''>
    </td>
    <td align="left"></td>
</tr>
<tr style="background-color: #fff">
    <td align="right" class="header">
        Keterangan: 
    </td>
    <td align="left" colspan="5">
        <input type="text" name="keterangan0" id="keterangan0" size="80" maxlength="255">
    </td>
</tr>
<tr height="25" style="background-color: #fff">
    <td align="center" colspan="6">
        <input type="button" style="background-color: #eee" value="Tambah ke Daftar Lembur Pegawai" onclick="checkData()">
    </td>
</tr>
</table>

</fieldset>

<br>
   

<form name="mainForm" id="mainForm" method="post" action="lembur.input.save.php" onsubmit="return validateSave();">
<input type="hidden" name="tahun1" id="tahun1" value="<?= $tahun1 ?>">
<input type="hidden" name="bulan1" id="bulan1" value="<?= $bulan1 ?>">
<input type="hidden" name="tanggal1" id="tanggal1" value="<?= $tanggal1 ?>">
<input type="hidden" name="tglpresensi1" id="tglpresensi1" value="<?= "$tahun1-$bulan1-$tanggal1" ?>">
<input type="hidden" name="tahun2" id="tahun2" value="<?= $tahun2 ?>">
<input type="hidden" name="bulan2" id="bulan2" value="<?= $bulan2 ?>">
<input type="hidden" name="tanggal2" id="tanggal2" value="<?= $tanggal2 ?>">
<input type="hidden" name="tglpresensi2" id="tglpresensi2" value="<?= "$tahun2-$bulan2-$tanggal2" ?>">   
<input type="hidden" value="0" id="nflagrow" name="nflagrow">    

<fieldset style="width: 95%; height: 350px;">
<legend><strong>Daftar Lembur Pegawai</strong></legend>
<div style="overflow: auto; height: 330px;">
<table border="1" id="tabLembur" style="border-width: 1px; border-collapse: collapse; width: 760px;">
<tr height="25">
    <td width="90" align="center" class="header">Tanggal</td>
    <td width="100" align="center" class="header">NIP</td>
    <td width="200" align="center" class="header">Nama</td>
    <td width="80" align="center" class="header">Jam Masuk</td>
    <td width="80" align="center" class="header">Jam Pulang</td>
    <td width="160" align="center" class="header">Keterangan</td>
    <td width="50" align="center" class="header">&nbsp;</td>
</tr>
</table>
</div>
</fieldset>

<br>
<fieldset style="width: 95%; background-color: #f4f5d5">
<center>
<input type="submit" value="Simpan" style="background-color: #eee">
<input type="button" value="Tutup" style="background-color: #eee" onclick="window.close()">
</center>
</fieldset>

</form>

</body>
</html>