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
require_once('../lib/datearith.php');
require_once('presensikeg.guru.func.php');

$bulan = $_REQUEST['bulan'] ?? date('n');
$tahun = $_REQUEST['tahun'] ?? date('Y');
$idkegiatan = $_REQUEST['idkegiatan'] ?? -1;

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Laporan Presensi Kegiatan Pegawai]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/jquery-1.9.1.js"></script>
<script language="javascript" src="presensikeg.guru.header.js"></script>
</head>

<body>

<table border="0" width="100%">
<tr>
    <td width="52" align="right"><strong>Kegiatan</strong></td>
    <td align="left" width='520'>
        <select id='cbAktif' style='width: 80px' onchange='changeCbAktif()'>
            <option value='1' selected=>Aktif</option>
            <option value='0'>Non AktiF</option>
        </select>
        <span id='divCbKegiatan'>
<?php          GetCbActivity(1); ?>
        </span>
    </td>
    <td rowspan='2' align='left'>
        <a href="#" onclick="show()">
            <img src="../img/view.png" height="48" border="0"/>
        </a>
    </td>
    <td rowspan='2' align='right' valign='top'>
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Presensi Kegiatan</font><br />
        <a href="../kepegawaian.php?page=pk" target="content">
        <font size="1" color="#000000"><b>Kepegawaian</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Laporan Presensi Kegiatan</b></font>
    </td>
</tr>
<tr>
    <td width="52" align="right"><strong>Bulan</strong></td>
    <td align="left">
        <select id='cbBulan' onchange='clearContent()'>
<?php      for($i = 1; $i <= 12; $i++)
        {
            $sel = $i == date('n') ? "selected" : "";
            echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
        } ?>            
        </select>
        <select id='cbTahun' onchange='clearContent()'>
<?php      for($i = $G_START_YEAR; $i <= date('Y'); $i++)
        {
            $sel = $i == date('Y') ? "selected" : "";
            echo "<option value='$i' $sel>" . $i . "</option>";
        } ?>            
        </select>
    </td>
</tr>
</table>

</body>
</html>
<?php
CloseDb();
?>