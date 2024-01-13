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
require_once("../include/sessionchecker.php");

$idMedia = $_REQUEST['idMedia'];

OpenDb();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Video Like</title>
    <script language="javascript" src="../script/jquery-1.9.1.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            Tables('tabLiker');
        });
    </script>
</head>

<body topmargin="10" leftmargin="10">
<span style="font-size: 18px">Media Like</span><br><br>

<table id="tabLiker" border="1" style="border-width: 1px; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr style="height: 30px;">
        <td align="center" width="30" class="header">No</td>
        <td align="center" width="120" class="header">Id</td>
        <td align="center" width="250" class="header">Nama</td>
        <td align="center" width="120" class="header">Kelompok</td>
        <td align="center" width="150" class="header">Tanggal</td>
    </tr>
<?php
$sql = "SELECT id, nama, kelompok, tanggal 
          FROM (
        SELECT mf.nis AS id, s.nama, 'Siswa' AS kelompok, DATE_FORMAT(mf.timestamp, '%d-%m-%Y %H:%i') AS tanggal
          FROM jbsel.medialike mf, jbsakad.siswa s
         WHERE mf.nis = s.nis
           AND mf.idmedia = $idMedia
         UNION 
        SELECT mf.nip AS id, p.nama, 'Pegawai' AS kelompok, DATE_FORMAT(mf.timestamp, '%d-%m-%Y %H:%i') AS tanggal
          FROM jbsel.medialike mf, jbssdm.pegawai p
         WHERE mf.nip = p.nip
           AND mf.idmedia = $idMedia
               ) AS X
         ORDER BY tanggal DESC";

    $no = 0;
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $no += 1;

        echo "<tr style='height: 22px'>";
        echo "<td align='center'>$no</td>";
        echo "<td align='left'>".$row[0]."</td>";
        echo "<td align='left'>".$row[1]."</td>";
        echo "<td align='left'>".$row[2]."</td>";
        echo "<td align='left'>".$row[3]."</td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
<?php
CloseDb();
?>
