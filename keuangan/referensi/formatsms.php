<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 23.0 (November 12, 2020)
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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

OpenDb();
$seldept = $_REQUEST['departemen'] ?? "";

?>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="formatsms.css">
<script language="javascript" src="../script/validator.js"></script>
<script language="javascript" src="../script/jquery-1.9.0.js"></script>
<script language="javascript" src="formatsms.js"></script>
<table border="0"width="95%" align="center">
<tr>
    <td align="right">
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Format Notifikasi Informasi Pembayaran</font>
    </td>
</tr>
<tr>
    <td align="right"><a href="../referensi.php">
    <font size="1" color="#000000"><b>Referensi</b></font></a>&nbsp>&nbsp
    <font size="1" color="#000000"><b>Format Notifikasi Informasi Pembayaran</b></font></td>
</tr>
<tr>
    <td align="left">&nbsp;</td>
</tr>
</table>
<br />

<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td align='left' valign='top'>
        <form name='main' method='post' action='formatsms.save.php' onsubmit='return ValidateInput()'>
		Departemen:
		<select id='departemen' name='departemen' onchange='ChangeDept()'>
<?php 	$sql = "SELECT departemen
				  FROM jbsakad.departemen
				 WHERE aktif = 1
				 ORDER BY urutan";
		$res = QueryDb($sql);
		while($row = mysqli_fetch_row($res))
		{
			if ($seldept == "")
				$seldept = $row[0];
			$selected = $seldept == $row[0] ? "selected" : "";
			
			echo "<option value='".$row[0]."' $selected>".$row[0]."</option>";
		} ?>
		</select><br><br>
<?php
		$sql = "SELECT COUNT(replid)
				  FROM jbsfina.formatsms
				 WHERE departemen = '$seldept'
				   AND jenis = 'SISPAY'";
		$ndata = FetchSingle($sql);
		if ($ndata == 0)
		{
			$format = "Terima kasih, kami telah menerima pembayaran dari {NAMA} tanggal {TANGGAL} sebesar {BESAR} untuk {PEMBAYARAN} - Bag. Keuangan";
			$sql = "INSERT INTO jbsfina.formatsms
					   SET jenis = 'SISPAY', departemen = '$seldept', format = '".$format."'";		
			QueryDb($sql);		
		}
		
		$sql = "SELECT format
				  FROM jbsfina.formatsms
				 WHERE departemen = '$seldept'
				   AND jenis = 'SISPAY'";
		$sisformatsms = FetchSingle($sql);
		
		$sql = "SELECT COUNT(replid)
				  FROM jbsfina.formatsms
				 WHERE departemen = '$seldept'
				   AND jenis = 'CSISPAY'";
		$ndata = FetchSingle($sql);
		if ($ndata == 0)
		{
			$format = "Terima kasih, kami telah menerima pembayaran dari {NAMA} tanggal {TANGGAL} sebesar {BESAR} untuk {PEMBAYARAN} - Bag. Keuangan";
			$sql = "INSERT INTO jbsfina.formatsms
					   SET jenis = 'CSISPAY', departemen = '$seldept', format = '".$format."'";		
			QueryDb($sql);		
		}
		
		$sql = "SELECT format
				  FROM jbsfina.formatsms
				 WHERE departemen = '$seldept'
				   AND jenis = 'CSISPAY'";
        $csisformatsms = FetchSingle($sql);

        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.formatsms
                 WHERE departemen = '$seldept'
                   AND jenis = 'SISTUNG'";
        $ndata = FetchSingle($sql);
        if ($ndata == 0)
        {
            $format = "Kami informasikan {NAMA} masih memiliki tunggakan sebesar {TUNGGAKAN} untuk {PEMBAYARAN} - Bag. Keuangan";
            $sql = "INSERT INTO jbsfina.formatsms
                       SET jenis = 'SISTUNG', departemen = '$seldept', format = '".$format."'";
            QueryDb($sql);
        }

        $sql = "SELECT format
                  FROM jbsfina.formatsms
                 WHERE departemen = '$seldept'
                   AND jenis = 'SISTUNG'";
		$tunggakformatsms = FetchSingle($sql);

        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.formatsms
                 WHERE departemen = '$seldept'
                   AND jenis = 'SISTAB'";
        $ndata = FetchSingle($sql);
        if ($ndata == 0)
        {
            $format = "Kami informasikan transaksi tabungan dari {NAMA} tanggal {TANGGAL} sebesar {BESAR} untuk {PEMBAYARAN} saldo {SALDO} keterangan {KETERANGAN} - Bag. Keuangan";
            $sql = "INSERT INTO jbsfina.formatsms
                       SET jenis = 'SISTAB', departemen = '$seldept', format = '".$format."'";
            QueryDb($sql);
        }

        $sql = "SELECT format
                  FROM jbsfina.formatsms
                 WHERE departemen = '$seldept'
                   AND jenis = 'SISTAB'";
        $tabunganformatsms = FetchSingle($sql);


        // SchoolPay Cashless Payment
        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.formatsms
                 WHERE departemen = '$seldept'
                   AND jenis = 'SCHOOLPAY'";
        $ndata = FetchSingle($sql);
        if ($ndata == 0)
        {
            $format = "Kami informasikan transaksi pembayaran non tunai dari {NAMA} tanggal {TANGGAL} sebesar {BESAR}, saldo tersisa {SALDO}, nomor {TRANSID}";
            $sql = "INSERT INTO jbsfina.formatsms
                               SET jenis = 'SCHOOLPAY', departemen = '$seldept', format = '".$format."'";
            QueryDb($sql);
        }

        $sql = "SELECT format
                  FROM jbsfina.formatsms
                 WHERE departemen = '$seldept'
                   AND jenis = 'SCHOOLPAY'";
        $paymentformatsms = FetchSingle($sql);
		?>

        Format Notifikasi untuk informasi transaksi <strong>Pembayaran Siswa</strong>:<br>
        <textarea id='sisformatsms' name='sisformatsms' class='inputbox' rows='4' cols='50'><?=$sisformatsms?></textarea><br><br>

		Format Notifikasi untuk informasi transaksi <strong>Pembayaran Calon Siswa</strong>:<br>
        <textarea id='csisformatsms' name='csisformatsms' class='inputbox' rows='4' cols='50'><?=$csisformatsms?></textarea><br><br>

        Format Notifikasi untuk informasi transaksi <strong>Tabungan Siswa</strong>:<br>
        <textarea id='tabunganformatsms' name='tabunganformatsms' class='inputbox' rows='4' cols='50'><?=$tabunganformatsms?></textarea><br><br>

        Format Notifikasi untuk informasi <strong>Tunggakan Siswa &amp; Calon Siswa</strong>:<br>
        <textarea id='tungformatsms' name='tungformatsms' class='inputbox' rows='4' cols='50'><?=$tunggakformatsms?></textarea><br><br>

        Format Notifikasi untuk informasi <strong>Transaksi SchoolPay Cashless Payment</strong>:<br>
        <textarea id='paymentformatsms' name='paymentformatsms' class='inputbox' rows='4' cols='50'><?=$paymentformatsms?></textarea><br>

        <br>
        <input type='submit' class='but' value='Simpan' style="width: 60px; height: 40px;">&nbsp;
        <input type='button' class='but' value='Refresh' style="width: 60px; height: 40px;" onclick='window.location.reload()'>
        </form>
    </td>
    <td valign='top' align='left'>
        <br><br><br>
        <fieldset>
            <legend><strong>Auto Format</strong></legend>
            <table border='0' cellpadding='5' cellspacing='0' width='320' style='background-color: #ffffe8;'>
            <tr>
                <td width='70'>{NIS}</td>
                <td width='*'>Nomor induk siswa / calon siswa</td>
            </tr>
            <tr>
                <td>{NAMA}</td>
                <td width='*'>Nama lengkap siswa / calon siswa</td>
            </tr>
            <tr>
                <td>{TANGGAL}</td>
                <td width='*'>Tanggal pembayaran</td>
            </tr>
            <tr>
                <td>{BESAR}</td>
                <td width='*'>Besar pembayaran yang dibayarkan (sudah dipotong diskon)</td>
            </tr>
            <tr>
                <td>{PEMBAYARAN}</td>
                <td width='*'>Jenis pembayaran yang dibayarkan</td>
            </tr>
            <tr>
                <td>{TUNGGAKAN}</td>
                <td width='*'>Sisa tunggakan pembayaran siswa</td>
            </tr>
            <tr>
                <td>{SALDO}</td>
                <td width='*'>Saldo tabungan siswa</td>
            </tr>
            <tr>
                <td>{KETERANGAN}</td>
                <td width='*'>Keterangan Transaksi</td>
            </tr>
            </table>
        </fieldset>
    </td>
</tr>
</table>
<?php
CloseDb();
?>