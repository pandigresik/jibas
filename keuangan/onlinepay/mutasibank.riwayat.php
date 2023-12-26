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
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/errorhandler.php');
require_once('onlinepay.util.func.php');
require_once('mutasibank.riwayat.func.php');

$departemen = $_REQUEST["departemen"];
$bank = $_REQUEST["bank"];
$bankNo = $_REQUEST["bankno"];

OpenDb();
?>
<span style="font-size: 20px; color: #999;">Riwayat Mutasi</span><br><br>

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="bank" value="<?=$bank?>">
<input type="hidden" id="bankno" value="<?=$bankNo?>">
<table border="0" cellspacing="0" cellpadding="5" style="border: 1px solid #efefef; border-collapse: collapse;">
<tr>
    <td width="120"><strong>Tanggal Mutasi:</strong></td>
    <td width="600">
<?php   $tanggal1 = date('Y-m-d', strtotime("-1 months")) ?>
        <input type="hidden" id="dttanggal1" value="<?= $tanggal1 ?>">
        <input type="text" id="tanggal1" class="inputbox" value="<?= formatInaMySqlDate($tanggal1) ?>" style="width: 140px" onclick="showDatePicker1()">
        s/d
<?php   $tanggal2 = date('Y-m-d'); ?>
        <input type="hidden" id="dttanggal2" value="<?= $tanggal2 ?>">
        <input type="text" id="tanggal2" class="inputbox" value="<?= formatInaMySqlDate($tanggal2) ?>" style="width: 140px" onclick="showDatePicker2()">
        <input type="button" class="but" value="lihat" onclick="showLaporanMutasi()">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="#" onclick="cetakMutasi()" title="cetak"><img src="../images/ico/print.png" border="0">&nbsp;cetak</a>&nbsp;&nbsp;
        <a href="#" onclick="excelMutasi()" title="excel"><img src="../images/ico/excel.png" border="0">&nbsp;excel</a>
    </td>
</tr>
</table>

<br><br>
<div id="dvRiwayatMutasi">
<?php
ShowLaporanMutasi();
?>

</div>

<?php
CloseDb();
?>
