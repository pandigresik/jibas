<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 16.2 (March 12, 2019)
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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/rupiah.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../library/departemen.php');
require_once('multitrans.config.php');
require_once('multitrans.content.func.php');

$_SESSION["multipaystep"] = 1;

$departemen = $_REQUEST['departemen'];
$idtahunbuku = $_REQUEST['idtahunbuku'];
$kelompok = $_REQUEST['kelompok'];
$noid = $_REQUEST['noid'];
$nama = $_REQUEST['nama'];
$kelas = $_REQUEST['kelas'];

if ($kelompok == "siswa")
    $jenisp = ["JTT" => "Iuran Wajib Siswa", "SKR" => "Iuran Sukarela Siswa"];
else
    $jenisp = ["CSWJB" => "Iuran Wajib Calon Siswa", "CSSKR" => "Iuran Sukarela Calon Siswa"];

OpenDb();    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Multiple Transactions</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css" />
	<link rel="stylesheet" href="../script/themes/ui-lightness/jquery.ui.all.css">
    <script language="javascript" src="multitrans.content2.js"></script>
	<script language="javascript" src="../script/tools.js"></script>
	<script language="javascript" src="../script/rupiah2.js"></script>
	<script language="javascript" src="../script/validator.js"></script>
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
</head>

<body topmargin="0" leftmargin="0">   
<table border="0" cellpadding="6" cellspacing="0" width="100%">
<tr>
	<td align="left" valign="top" width="35%">
		<table border="0" cellpadding="6" cellspacing="0" width="100%">
		<tr>
			<td align="center" valign="top" width='30%' rowspan='3' style='background-color: #eee'>
<?php          	ShowInfoSiswa() ?>					
			</td>
			<td align="left" valign="top">
				<strong><?= "$noid $nama ($kelas)" ?></strong>
			</td>
		</tr>
		<tr>	
			<td align="left" valign="top">
<?php          	ShowSelectJenisPayment() ?>
			</td>
		</tr>
		<tr>	
			<td align="left" valign="top">
			<div id="divSelectPayment">
<?php          	ShowSelectPayment() ?>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="left" valign="top">
			<div id="divPaymentInfo" style="overflow: auto; height: 300px;">
			
			</div>		
			</td>
		</tr>
		</table> 
	</td>
	<td align="left" valign="top" width="*">
	<fieldset>
	<legend><strong>Daftar Pembayaran</strong></legend>
	<div id="divPaymentBox" style="background-color: #eee; height: 340px; overflow: auto;">
		
	<form name="mainForm" id="mainForm" method="POST" action="multitrans.content.save.php" onsubmit="return ValidateSave()">
	<input type='hidden' name="nflagrow" id="nflagrow" value="0">
	<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>">
	<input type="hidden" name="kelompok" id="kelompok" value="<?=$kelompok?>">
	<input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku?>">
	<input type="hidden" name="noid" id="noid" value="<?=$noid?>">
	<input type="hidden" name="nama" id="nama" value="<?=$nama?>">
	<table border="1" id="tabPaymentList" cellpadding="2" cellspacing="0" style="border-width: 1px; border-collapse: collapse; border-color: #ddd" width="770">
	<thead>
		<tr height="20">
			<td class="header" width="100" align="center">Rek. Kas</td>
			<td class="header" width="310" align="center">Transaksi	</td>
			<td class="header" width="90" align="center">Jumlah</td>
			<td class="header" width="90" align="center">Diskon</td>
			<td class="header" width="120" align="center">Sub Total</td>
			<td class="header" width="60" align="center">&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		
	</tbody>
	<tfoot>
		<tr height="30" style="background-color: #ccc">
			<td colspan="4" align="right"><strong>T O T A L</strong></td>
			<td align="right">
				<span id="spanTotalInfo" style="font-weight: bold"></span>
			</td>
			<td>&nbsp;</td>
		</tr>
	</tfoot>
	</table>
	
	</div>
	<table border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td width="120" align="right" valign="bottom">
			<input type="submit" value="Simpan" class="but" style="height: 45px; width: 100px;">
		</td>
		<td align="left" valign="top">
			Keterangan:<br>
			<input type='text' id='ktransaksi' name='ktransaksi' size='100' style="border-style: solid; border-color: #ccc; border-width: 1px;"><br>
			<input type='checkbox' id='smsinfo' name='smsinfo' <?php if ($SendSmsPayment == 1) echo "checked"?> >&nbsp;Notifikasi SMS | Telegram | Jendela Sekolah
		</td>
	</tr>	
	</table>
	</form>
	</fieldset>
	
	</td>
</tr>
</table>    
        
</body>
</html>
<?php
CloseDb();
?>