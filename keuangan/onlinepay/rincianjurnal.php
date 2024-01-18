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
require_once('onlinepay.util.func.php');
require_once('riwayattrans.func.php');

if (!isset($_REQUEST["stnojurnal"]))
    exit();

OpenDb();

$stNoJurnal = urldecode((string) $_REQUEST["stnojurnal"]);
//echo "$stNoJurnal<br>";
$lsJurnal = explode(",", $stNoJurnal);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Rincian Jurnal</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="../script/themes/ui-lightness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/ui/jquery-ui.custom.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/dateutil.js"></script>
    <script language="javascript" src="../script/stringutil.js"></script>
    <script type="application/javascript">
        $(document).ready(function () {
            if ($("#tabRincianJurnal").length)
                Tables('tabRincianJurnal', 0, 0);
        });
    </script>
</head>

<body style="margin-right: 20px; margin-top: 20px">
<span style="font-size: 18px;">Rincian Jurnal</span>
<br><br>

<table id="tabRincianJurnal" border="1" cellpadding="5" cellspacing="0" style="border: 1px solid #666666; border-collapse: collapse;">
<tr style="height: 25px">
    <td width="30" class="header" align="center">No</td>
    <td width="150" class="header" align="center">Jurnal</td>
    <td width="150" class="header" align="center">Petugas</td>
    <td width="350" class="header" align="center">Transaksi</td>
</tr>

<?php
$no = 0;
for($i = 0; $i < count($lsJurnal); $i++)
{
    $no += 1;
    $noJurnal = $lsJurnal[$i];

    $sql = "SELECT replid, transaksi, DATE_FORMAT(tanggal, '%d %b %Y') AS ftanggal, idpetugas, petugas
              FROM jbsfina.jurnal
             WHERE nokas = '".$noJurnal."'";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        continue;

    $row = mysqli_fetch_array($res);
    $idJurnal = $row["replid"];
    echo "<tr>";
    echo "<td style='background-color: #efefef' align='center' valign='top' rowspan='2'>$no</td>";
    echo "<td valign='top' align='left'><b>".$noJurnal."</b><br>".$row['ftanggal']."</td>";
    echo "<td valign='top' align='left'><b>".$row['petugas']."</b><br>".$row['idpetugas']."</td>";
    echo "<td valign='top' align='left'>".$row['transaksi']."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan='3' style='background-color: #fff'>";

    echo "<table border='1' cellpadding='2' cellspacing='0' style='border: 1px solid #666666; border-collapse: collapse;'>";
    $sql = "SELECT jd.koderek, rk.nama, jd.debet, jd.kredit
              FROM jbsfina.jurnaldetail jd, jbsfina.rekakun rk
             WHERE jd.koderek = rk.kode
               AND idjurnal = $idJurnal";
    $res2 = QueryDb($sql);
    while($row2 = mysqli_fetch_array($res2))
    {
        echo "<tr>";
        echo "<td width='50' align='center'>".$row2['koderek']."</td>";
        echo "<td width='250'>".$row2['nama']."</td>";

        $rp = FormatRupiah($row2["debet"]);
        echo "<td width='120' align='right'>$rp</td>";

        $rp = FormatRupiah($row2["kredit"]);
        echo "<td width='120' align='right'>$rp</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "</td>";
    echo "</tr>";
}
?>
</table>
</body>
</html>
<?php
CloseDb();
?>
