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

OpenDb();

$idPgTransLebih = $_REQUEST["idpgtranslebih"];

$sql = "SELECT nomor 
          FROM jbsfina.pgtranslebih
         WHERE id = $idPgTransLebih";
$res = QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    echo "Data tidak ditemukan";
    return;
}

$row = mysqli_fetch_row($res);
$nomor = $row[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Rincian Mutasi</title>
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
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script type="application/javascript">
        $(document).ready(function () {
            if ($("#tabRincianData").length)
                Tables('tabRincianData', 1, 0);
        })
    </script>
</head>

<body>
<div style="text-align: center; font-size: 20px">INFORMASI TRANSAKSI</div><br><br>
<table border="0" cellpadding="10"  width="100%" align="left">
<tr>
    <td align="left" valign="top" width="100%">

<?php
    $sql = "SELECT p.replid, p.nis, s.nama AS namasiswa, p.bankno, b.bank, p.nomor, p.jenis,
                   DATE_FORMAT(p.waktu, '%d %b %Y %H:%i') AS fwaktu, DATE_FORMAT(p.tanggal, '%d %b %Y') AS ftanggal,
                   p.idpetugas, p.petugas, p.ketver
              FROM jbsfina.pgtrans p
             INNER JOIN jbsfina.bank b ON p.bankno = b.bankno
              LEFT JOIN jbsakad.siswa s ON p.nis = s.nis
             WHERE p.nomor = '".$nomor."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_array($res);
    $idPgTrans = $row["replid"];
?>
    <table border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td align="right" width="80"><b>Siswa</b></td>
        <td align="center" width="10"><b>:</b></td>
        <td align="left" width="300"><?=$row["namasiswa"] . " (" . $row["nis"] . ")"?></td>
    </tr>
    <tr>
        <td align="right"><b>Tanggal</b></td>
        <td align="center"><b>:</b></td>
        <td align="left"><?=$row["fwaktu"]?></td>
    </tr>
    <tr>
        <td align="right"><b>Nomor</b></td>
        <td align="center"><b>:</b></td>
        <td align="left"><?=$row["nomor"]?></td>
    </tr>
    <tr>
        <td align="right"><b>Metode</b></td>
        <td align="center"><b>:</b></td>
        <td align="left"><?= $row["jenis"] ? "Pembayaran Tagihan" : "Pembayaran Keranjang" ?></td>
    </tr>
    <tr>
        <td align="right"><b>Bank</b></td>
        <td align="center"><b>:</b></td>
        <td align="left"><?= $row["bank"] . " " . $row["bankno"] ?></td>
    </tr>
    <tr>
        <td align="right"><b>Petugas</b></td>
        <td align="center"><b>:</b></td>
        <td align="left"><?= $row["petugas"] . " (" . $row["idpetugas"] .")" ?></td>
    </tr>
    <tr>
        <td align="right"><b>Keterangan</b></td>
        <td align="center"><b>:</b></td>
        <td align="left"><?= $row["ketver"] ?></td>
    </tr>
    </table>

    </td>
</tr>
<tr>
    <td align="left" width="100%">
        <table id='tabRincianData' border='1' cellpadding='5' cellspacing='0' style='border: 1px #efefef; border-collapse: collapse'>
        <tr style="height: 30px;">
            <td class="header" width="30" align="center">No</td>
            <td class="header" width="200" align="center">Transaksi</td>
            <td class="header" width="120" align="center">Jumlah</td>
            <td class="header" width="140" align="center">No Jurnal</td>
        </tr>
<?php
        $sql = "SELECT pd.kategori, pd.jumlah, pd.diskon, pd.nokas, dp.nama AS namapenerimaan, dt.nama AS namatabungan
                  FROM jbsfina.pgtransdata pd
                  LEFT JOIN jbsfina.datapenerimaan dp ON pd.idpenerimaan = dp.replid
                  LEFT JOIN jbsfina.datatabungan dt ON pd.idtabungan = dt.replid
                 WHERE idpgtrans = $idPgTrans
                   AND pd.kategori <> 'LB'
                 ORDER BY kelompok";

        $no = 0;
        $total = 0;
        $res2 = QueryDb($sql);
        while($row2 = mysqli_fetch_array($res2))
        {
            $no += 1;
            $kategori = $row2["kategori"];

            $nama = "";
            if ($kategori == "SISTAB")
                $nama = $row2["namatabungan"];
            else if ($kategori == "JTT")
                $nama = $row2["namapenerimaan"];
            else if ($kategori == "SKR")
                $nama = $row2["namapenerimaan"];
            else if ($kategori == "BL")
                $nama = "Biaya Layanan";

            $rp = FormatRupiah($row2["jumlah"]);
            $total += $row2["jumlah"];

            echo "<tr>";
            echo "<td align='center' style='background-color: #efefef;'>$no</td>";
            echo "<td align='left'>$nama</td>";
            echo "<td align='right'>$rp</td>";
            echo "<td align='center'>".$row2['nokas']."</td>";
            echo "</tr>";
        }

        $rp = FormatRupiah($total);
        echo "<tr style='height: 30px'>";
        echo "<td align='right' colspan='2' style='background-color: #ffc038;'><b>TOTAL</b></td>";
        echo "<td align='right' style='background-color: #ffc038;'>$rp</td>";
        echo "<td align='center' style='background-color: #ffc038;'>&nbsp;</td>";
        echo "</tr>";
        ?>
        </table>

    </td>
</tr>
</table>

</body>
</html>
<?php
CloseDb();
?>