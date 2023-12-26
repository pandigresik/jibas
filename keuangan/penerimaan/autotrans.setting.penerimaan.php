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
require_once('../library/departemen.php');
require_once('../include/errorhandler.php');
require_once('autotrans.setting.penerimaan.func.php');

if (getLevel() == 2)
{ ?>
    <script language="javascript">
        alert('Maaf, anda tidak berhak mengakses halaman ini!');
        window.close();
    </script>
    <?php 	exit();
} // end if

$kelompok = $_REQUEST["kelompok"];
$departemen = $_REQUEST["departemen"];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tambah Jenis Penerimaan</title>
    <script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
    <link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah.js"></script>
    <script language="javascript" src="autotrans.setting.penerimaan.js"></script>
</head>

<body>

<table border="0" cellpadding="10" width="100%">
<tr><td>

<span style="font-size: 16px">Pilih Jenis Penerimaan</span><br><br>
<input type="hidden" id="departemen" value="<?=$departemen?>">
<table border="0" cellpadding="3" width="100%" height="100%">
<tr>
    <td width="150"><strong>Kategori:</strong></td>
    <td>
<?php
        $idKategori = "";
        ShowKategoriPenerimaan();
?>
    </td>
</tr>
<tr>
    <td><strong>Penerimaan:</strong></td>
    <td>
        <span id="spPenerimaan">
<?php
        ShowPenerimaan($departemen, $idKategori);
?>
        </span>
    </td>
</tr>
<tr>
    <td><strong>Cicilan/Tagihan:</strong></td>
    <td>
        <input type="text" style="font-size: 14px; width: 200px; background-color: #fdffc7" id="besar" onblur="formatRupiah('besar')" onfocus="unformatRupiah('besar')">
    </td>
</tr>
<tr>
    <td><strong>Urutan:</strong></td>
    <td>
        <input type="text" style="font-size: 14px; width: 30px; background-color: #fdffc7" maxlength="2" id="urutan">
    </td>
</tr>
<tr>
    <td valign="top"><strong>Keterangan:</strong></td>
    <td>
        <textarea id="keterangan" rows="2" cols="30"></textarea>
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>
        <input type="button" value="Simpan" class="but" style="height: 30px; width: 80px;" onclick="simpanPenerimaan()">&nbsp;
        <input type="button" value="Tutup" class="but" style="height: 30px; width: 80px;" onclick="window.close()">
    </td>
</tr>
</table>

</td></tr>
</table>

</body>

</html>
<?php
CloseDb();
?>
