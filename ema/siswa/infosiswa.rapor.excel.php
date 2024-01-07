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
require_once('../inc/sessionchecker.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../lib/as-diagrams.php');
require_once('../lib/dpupdate.php');
require_once('../inc/numbertotext.class.php');
require_once('infosiswa.rapor.func.php');

$NTT = new NumberToText();
$kelas = $_REQUEST['kelas'];
$nis = $_REQUEST['nis'];
$semester = $_REQUEST['semester'];

header('Content-Type: application/vnd.ms-excel');
header('Content-Type: application/x-msexcel');
header('Content-Disposition: attachment; filename=ExcelRapor.xls');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

OpenDb();

$sql = "SELECT t.departemen, a.tahunajaran, k.kelas, t.tingkat, s.nama, a.tglmulai, a.tglakhir 
          FROM tahunajaran a, kelas k, tingkat t, siswa s 
         WHERE k.idtingkat = t.replid 
           AND k.idtahunajaran = a.replid 
           AND k.replid = '$kelas' 
           AND s.nis = '".$nis."'";

$result = QueryDB($sql);
$row = mysqli_fetch_array($result);
$tglmulai = $row['tglmulai'];
$tglakhir = $row['tglakhir'];
$nama = $row['nama'];
$departemen = $row['departemen'];
$tahunajaran = $row['tahunajaran'];
$kls = $row['kelas'];

$sql_get_pelajaran_laporan=	"SELECT pel.replid as replid,pel.nama as nama 
                               FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel 
                              WHERE uji.replid=niluji.idujian 
                                AND niluji.nis=sis.nis 
                                AND uji.idpelajaran=pel.replid 
                                AND uji.idsemester='$semester' 
                                AND uji.idkelas='$kelas' 
                                AND sis.nis='$nis' 
                              GROUP BY pel.nama";
//echo $sql_get_pelajaran_laporan;
$result_get_pelajaran_laporan=QueryDb($sql_get_pelajaran_laporan);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JIBAS EMA [Rapor]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
    <td align="left" valign="top" colspan="2">

        <center>
            <font size="4"><strong>LAPORAN HASIL BELAJAR</strong></font><br />
        </center><br /><br />
        <table>
        <tr>
            <td width="25%" class="news_content1"><strong>Siswa</strong></td>
            <td class="news_content1">:
                <?=$nis.' - '.$nama?></td>
        </tr>
        <tr>
            <td width="25%" class="news_content1"><strong>Departemen</strong></td>
            <td class="news_content1">:
                <?=$departemen?></td>
        </tr>
        <tr>
            <td class="news_content1"><strong>Tahun Ajaran</strong></td>
            <td class="news_content1">:
                <?=$tahunajaran?></td>
        </tr>
        <tr>
            <td class="news_content1"><strong>Kelas</strong></td>
            <td class="news_content1">:
                <?=$kls ?></td>
        </tr>
        </table>
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td>
<?php 		ShowKomentar($semester, $kelas, $nis) ?>
            </td>
        </tr>
        <tr>
            <td>
                <fieldset>
                    <legend><strong>Nilai Pelajaran</strong></legend>
<?php 		        ShowRapor($semester, $kelas, $nis) ?>
                </fieldset>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                <fieldset><legend><strong>Deskripsi Nilai Pelajaran</strong></legend>
<?php 		    ShowRaporDeskripsi($semester, $kelas, $nis) ?>
                </fieldset>
                <br>
            </td>
        </tr>

        </table>

    </td>
</tr>
</table>
</body>
</html>
<?php
CloseDb();
?>