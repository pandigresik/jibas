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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');

require_once('expnilai.content.func.php');

OpenDb();

ReadParam();

$sql = "SELECT k.kelas, t.tingkat
          FROM jbsakad.kelas k, jbsakad.tingkat t
         WHERE k.idtingkat = t.replid
           AND k.replid = '".$kelas."'";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$nmkelas = $row[0];
$nmtingkat = $row[1];
?>
<html>
<head>
    <title>Ekspor Nilai Pelajaran</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
    <script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
    <script language = "javascript" type = "text/javascript">
    function cetakExcel()
    {
        var filename = document.getElementById("filename").value + "";
        if (filename.trim().length == 0)
        {
            alert("Nama file Excel belum ditentukan!")
            document.getElementById("filename").focus();
            return;
        }

        filename = encodeURI(filename);
        var addr = "expnilai.content.excel.php?departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&tingkat=<?=$tingkat?>&kelas=<?=$kelas?>&semester=<?=$semester?>&filename="+filename;

        document.location.href = addr;
    }
    </script>
</head>
<body>
<table border="1" cellpadding="5" cellspacing="0" style="border-width: 1px; border-collapse: collapse;">
<tr style="background-color: #e5f6ff; height: 24px;">
    <td width="30">&nbsp;</td>
    <td width="30">A</td>
    <td width="125">B</td>
    <td width="200">C</td>
    <td width="100">D</td>
    <td width="200">E</td>
    <td width="100">F</td>
    <td width="120">G</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">1</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;"><font style="font-size: 12px; font-weight: bold;">FORM NILAI</font></td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">2</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">3</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">DEPARTEMEN (*):</td>
    <td><?=$departemen?></td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">4</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">KELAS:</td>
    <td><?=$nmkelas?></td>
    <td style="border-style: none;">TINGKAT:</td>
    <td><?=$nmtingkat?></td>
    <td style="border-style: none;">ID KELAS (*):</td>
    <td><?=$kelas?></td>
</tr>
<tr>
    <td style="background-color: #e5f6ff; height: 24px;" align="center">5</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">NIP GURU (*):</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">NAMA GURU:</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">6</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">PELAJARAN:</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">ASPEK:</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">JENIS UJIAN:</td>
    <td>&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">7</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">KODE UJIAN (*):</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">8</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">TANGGAL (*):</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">BULAN (*):</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">TAHUN (*):</td>
    <td>&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">9</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">RPP:</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">10</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">MATERI (*):</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">11</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">KETERANGAN:</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">12</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">13</td>
    <td class="header">No</td>
    <td class="header">NIS (*)</td>
    <td class="header">Nama</td>
    <td class="header">Nilai (*)</td>
    <td class="header">Keterangan</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<?php
$sql = "SELECT nis, nama 
          FROM siswa 
         WHERE idkelas = '$kelas' 
           AND aktif = 1 
           AND alumni = 0 
         ORDER BY nama ASC";
$res = QueryDb($sql);
$no = 0;
$rownum = 13;
while($row = mysqli_fetch_array($res))
{
    ?>
    <tr style="height: 24px;">
        <td style="background-color: #e5f6ff;" align="center"><?= ++$rownum ?></td>
        <td><?= ++$no ?></td>
        <td><?= $row['nis'] ?></td>
        <td><?= $row['nama'] ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="border-style: none;">&nbsp;</td>
        <td style="border-style: none;">&nbsp;</td>
    </tr>
<?php
}
echo "</table>";

$sql = "SELECT k.kelas, t.tingkat, ta.tahunajaran
          FROM jbsakad.kelas k, jbsakad.tingkat t, jbsakad.tahunajaran ta
         WHERE k.idtingkat = t.replid
           AND k.idtahunajaran = ta.replid
           AND k.replid = '".$kelas."'";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$nmkelas = $row[0];
$nmtingkat = $row[1];
$nmtahunajaran = $row[2];

$nmfile = "FORM_NILAI_";
$nmfile .= "K-" . SafeName($nmkelas) . "_";
$nmfile .= "T-" . SafeName($nmtingkat) . "_";
$nmfile .= "TA-" . SafeName($nmtahunajaran) . ".xlsx";
?>
<br>
<table border="0" cellpadding="2">
<tr>
    <td>
        <strong>Nama File (*.xlsx):</strong>
        <input type="text" id="filename" name="filename" style='background-color: #f9ffc9;' size="50" value="<?=$nmfile?>">
    </td>
    <td>
        <input onclick="cetakExcel()" type="button" class="but" style="width: 200px; height: 40px;" value="Simpan Form Nilai Excel">
    </td>
</tr>
</table>

</body>
</html>
<?php CloseDb(); ?>