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
require_once('../inc/errorhandler.php');
require_once('../inc/sessioninfo.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../lib/departemen.php');
require_once('presensikeg.rekapguru.func.php');

$bulan = $_REQUEST['bulan'];
$tahun = $_REQUEST['tahun'];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Presensi Kegiatan Pegawai</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../script/themes/ui-lightness/jquery-ui.css">
<script src="../script/jquery-1.9.1.js"></script>
<script src="../script/jquery-ui-1.10.3.custom.min.js"></script>
<script src="presensikeg.rekapguru.pegawai.js"></script>
<script src="../script/tools.js"></script>
<script src="../script/tables.js"></script>
    
<body topmargin="0" leftmargin="0">
<input type='hidden' id='bulan' value='<?=$bulan?>'>
<input type='hidden' id='tahun' value='<?=$tahun?>'>

<div id='tabs'>
    <ul>
        <li><a href="#tabs-1">Pilih Pegawai</a></li>
        <li><a href="#tabs-2">Cari Pegawai</a></li>
    </ul>
    <div id='tabs-1'>
        <table border='0' width='100%'>
        <tr>
            <td width='100' align='right'>
                <strong>Bagian :</strong>
            </td>
            <td align='left'>
                <select id='cbBagian' onchange='showDataPegawai()'>
                    <option value='Akademik' selected>Akademik</option>
                    <option value='Non Akademik'>Non Akademik</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan='2' align='left'>
            <span id='divPegawai'>
        <?php      echo GetPegawai($bulan, $tahun, "Akademik"); ?>        
            </span>    
            </td>
        </tr>
        </table>
    </div>
    <div id='tabs-2'>
        <table border='0' width='100%'>
        <tr>
            <td align='right'>
                <strong>Cari
                <select id='cbFilter' onchange="cbFilterChange()">
                    <option value='nama'>Nama</option>
                    <option value='nip'>NIP</option>
                </select>&nbsp;:
                </strong>
            </td>
            <td align='left'>
                <input type='text' id='txKeyword' style='width: 100px' onkeyup='txKeywordKeyUp(event)'>
                <input type='button' id='btCari' class='but'
                       style='width: 40px' value='Cari' onclick='btCariClick()'>
            </td>
        </tr>
        <tr>
            <td colspan='2' align='center'>
                <span id='lbError' style='color: red'></span>                
            </td>
        </tr>
        <tr>
            <td colspan='2' align='left'>
            <span id='divPegawai2'>
                
            </span>    
            </td>
        </tr>
        </table>
    </div>
</div>

</body>
</html>
<?php
CloseDb();
?>
<script language='JavaScript'>
    Tables('table', 1, 0);
</script>