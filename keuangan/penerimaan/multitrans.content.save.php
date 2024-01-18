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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/compatibility.php');
require_once('../library/jurnal.php');
require_once('../library/smsmanager.func.php');
require_once('multitrans.content.save.func.php');

if (1 != (int)$_SESSION["multipaystep"])
{
    echo "Maaf, halaman ini tidak bisa dimuat ulang!";
    exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Multiple Transactions</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css" />
	<link rel="stylesheet" href="../script/themes/ui-lightness/jquery.ui.all.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
	<script language="javascript" src="../script/rupiah2.js"></script>
	<script language="javascript" src="../script/validator.js"></script>
    <script language="javascript" src="multitrans.content.save.js"></script>
</head>
<body topmargin="0" leftmargin="0">
<?php
$departemen = $_REQUEST['departemen'];
$kelompok = $_REQUEST['kelompok'];
$idtahunbuku = $_REQUEST['idtahunbuku'];
$studentid = $_REQUEST['noid'];
$studentname = $_REQUEST['nama'];
$ktransaksi = $_REQUEST['ktransaksi'];
$ktransaksi = str_replace("'", "`", (string) $ktransaksi);
$ktransaksi = str_replace('"', '`', $ktransaksi);
$smsinfo = isset($_REQUEST['smsinfo']) ? 1 : 0;

OpenDb();

$success = true;
BeginTrans();

$transactions = [];

$nrow = $_REQUEST['nflagrow'];
for($i = 0; $i < $nrow; $i++)
{
    $tmp = "flagrow$i";
    if ($_REQUEST[$tmp] == 0)
        continue;
    
    $tmp = "i_kate_$i";
    $kate = $_REQUEST[$tmp];
    
    if ($kate == "JTT")
        $success = SaveIuranWajibSiswa($i);
    elseif ($kate == "SKR")
        $success = SaveIuranSukarelaSiswa($i);
    elseif ($kate == "CSWJB")
        $success = SaveIuranWajibCalonSiswa($i);    
    elseif ($kate == "CSSKR")
        $success = SaveIuranSukarelaCalonSiswa($i);    
    
    if (!$success)
        break;
}

// 2020-09-12: Simpan $transaction ke table multitransinfo utk digunakan di SchoolPay
if ($success)
    SaveMultiTransInfo();

if ($success && $smsinfo == 1)
	$success = CreateSMSReport();

if ($success)
{
    CommitTrans(); ?>
    <br><br>
    <font style="font-size: 18px; color: blue">Transaksi telah berhasil disimpan</font><br>
    Cetak Tanda Bukti Pembayaran:&nbsp;&nbsp;<input type="button" class="but" value="Sederhana" onclick="PrintCompact()" style='height: 30px'>&nbsp;<input type="button" class="but" value="Detail" onclick="PrintDetail()" style='height: 30px'> <br>   
    <input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>">
    <input type="hidden" name="kelompok" id="kelompok" value="<?=$kelompok?>">
    <input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku?>">
    <input type="hidden" name="studentid" id="studentid" value="<?=$studentid?>">
    <input type="hidden" name="ktransaksi" id="ktransaksi" value="<?=$ktransaksi?>">
    <?php
    CountTotalPayment();
    ?>
    <div id="divReportCompact" style="visibility: hidden">
    <?php
    CreateDivPrintReportCompact();
    ?>
    </div>
    <div id="divReportDetail" style="visibility: hidden">
    <?php
    CreateDivPrintReportDetail();
    ?>
    </div>
<?php  $_SESSION["multipaystep"] = 2;
}
else
{
    RollbackTrans(); ?>
    <br><br>
    <font style="font-size: 18px; color: red">Gagal menyimpan data. Tidak ada data transaksi yang tersimpan.</font><br>
<?php  
}
CloseDb();
?>
</body>
</html>