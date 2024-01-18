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

$idMutasi = $_REQUEST["idmutasi"];

$sql = "SELECT berkas, jenis, nomormutasi, adaberkas 
          FROM jbsfina.bankmutasi
         WHERE replid = $idMutasi";
$res = QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    echo "Data tidak ditemukan";
    return;
}

$row = mysqli_fetch_row($res);
$berkas64 = $row[0];
$jenis = $row[1] == 1 ? "Simpan" : "Ambil";
$nomorMutasi = $row[2];
$adaBerkas = $row[3];
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
            if ($("#tabRincianMutasi").length)
                Tables('tabRincianMutasi', 1, 0);
        })
    </script>
</head>

<body>
<div style="text-align: center; font-size: 20px">RINCIAN MUTASI</div><br><br>
<table border="0" cellpadding="10"  width="100%" align="left">
<tr>
    <td align="left" valign="top" width="100%">
        <span style="font-size: 14px; font-weight: bold">Mutasi <?=$jenis?></span><br>

        <table id="tabRincianMutasi" border='1' cellspacing='0' cellpadding='5' style='border: 1px solid #efefef;'>
        <tr style='height: 30px'>
            <td class='header' width='30' align='center'>No</td>
            <td class='header' width='200' align='left'>Transaksi</td>
            <td class='header' width='150' align='right'>Jumlah</td>
            <td class='header' width='300' align='left'>Keterangan</td>
        </tr>
<?php
        $sql = "SELECT kategori, idpenerimaan, idtabungan, idtabunganp, iddeposit, jumlah, nokas, keterangan
                  FROM jbsfina.bankmutasidata
                 WHERE idmutasi = $idMutasi";
        $res = QueryDb($sql);
        $no = 0;
        $total = 0;
        while($row = mysqli_fetch_array($res))
        {
            $no += 1;
            $kategori = $row["kategori"];

            $idPenerimaan = 0;
            if ($kategori == "JTT" || $kategori == "SKR")
                $idPenerimaan = $row["idpenerimaan"];
            else if ($kategori == "SISTAB")
                $idPenerimaan = $row["idtabungan"];
            else if ($kategori == "PEGTAB")
                $idPenerimaan = $row["idtabunganp"];
            else if ($kategori == "DPST")
                $idPenerimaan = $row["iddeposit"];

            $namaPenerimaan = NamaPenerimaan($kategori, $idPenerimaan);
            $namaKategori = NamaKategori($kategori);

            $jumlah = $row["jumlah"];
            $rp = FormatRupiah($jumlah);
            $total += $jumlah;

            $noKas = trim((string) $row["nokas"]);
            $keterangan = trim((string) $row["keterangan"]);

            $info = "";
            if (strlen($noKas) > 0)
                $info .= "No Jurnal: $noKas";

            if (strlen($keterangan) > 0)
            {
                if ($info != "") $info .= "<br>";
                $info .= "Keterangan: $keterangan";
            }

            echo "<tr>";
            echo "<td align='center' style='background-color: #efefef'>$no</td>";
            echo "<td align='left'><b>$namaPenerimaan</b><br><i>$namaKategori</i></td>";
            echo "<td align='right'>$rp</td>";
            echo "<td align='left'>$info</td>";
            echo "</tr>";
        }

        $rp = FormatRupiah($total);
        echo "<tr style='height: 30px;'>";
        echo "<td align='right' colspan='2' style='background-color: #ffc038'><b>TOTAL</b></td>";
        echo "<td align='right' style='background-color: #ffc038'><b>$rp</b></td>";
        echo "<td align='left' style='background-color: #ffc038'>&nbsp;</td>";
        echo "</tr>";
?>
        </table>
    </td>
</tr>
<tr>
    <td align="left" width="100%">
        <span style="font-size: 14px; font-weight: bold">Bukti:</span><br>
<?php
        echo "<span style='font-size: 14px;'>Nomor: $nomorMutasi</span><br><br>";
        if ($adaBerkas == 1)
            echo "<img src='data:image/jpeg;base64,$berkas64' width='300'>"; ?>
    </td>
</tr>
</table>

</body>
</html>
<?php
CloseDb();
?>