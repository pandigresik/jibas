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
require_once('lebihtrans.proses.func.php');

OpenDb();

$idPgTransLebih = $_REQUEST["idpgtranslebih"];

$sql = "SELECT nomor, jlebihsisa, jlebihtrans, bankno 
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
$jLebihSisa = $row[1];
$jLebihTrans = $row[2];
$bankNo = $row[3];

$jumlah = $jLebihSisa + $jLebihTrans;
$rp = FormatRupiah($jumlah);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Proses Kelebihan Pembayaran</title>
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
    <script language="javascript" src="lebihtrans.proses.js?r=<?=filemtime('lebihtrans.proses.js')?>"></script>
</head>

<body>
<div style="text-align: center; font-size: 20px">PROSES KELEBIHAN PEMBAYARAN</div><br><br>
<table border="0" cellpadding="10"  width="100%" align="left">
<tr>
    <td align="left" valign="top" width="100%">

<?php
        $sql = "SELECT p.replid, p.nis, IFNULL(s.nama, '') AS namasiswa, p.bankno, b.bank, b.departemen, p.nomor, p.jenis,
                       DATE_FORMAT(p.waktu, '%d %b %Y %H:%i') AS fwaktu, DATE_FORMAT(p.tanggal, '%d %b %Y') AS ftanggal,
                       p.idpetugas, p.petugas, p.ketver
                  FROM jbsfina.pgtrans p
                 INNER JOIN jbsfina.bank b ON p.bankno = b.bankno
                  LEFT JOIN jbsakad.siswa s ON p.nis = s.nis
                 WHERE p.nomor = '".$nomor."'";
        $res = QueryDb($sql);
        $row = mysqli_fetch_array($res);
        $idPgTrans = $row["replid"];
        $departemen = $row["departemen"];
        ?>
        <table border="0" cellpadding="5" cellspacing="0">
        <tr>
            <td align="right"><b>Siswa</b></td>
            <td align="center"><b>:</b></td>
            <td align="left"><?=$row["namasiswa"] . " (" . $row["nis"] . ")"?></td>
        </tr>
        <tr>
            <td align="right"><b>Tanggal</b></td>
            <td align="center"><b>:</b></td>
            <td align="left"><?=$row["fwaktu"]?></td>
        </tr>
        <tr>
            <td align="right"><b>Metode</b></td>
            <td align="center"><b>:</b></td>
            <td align="left"><?= $row["jenis"] ? "Pembayaran Tagihan" : "Pembayaran Keranjang" ?></td>
        </tr>
        <tr>
            <td align="right"><b>Nomor</b></td>
            <td align="center"><b>:</b></td>
            <td align="left"><?=$row["nomor"]?></td>
        </tr>
        <tr>
            <td align="right"><b>Bank</b></td>
            <td align="center"><b>:</b></td>
            <td align="left"><?= $row["bank"] . " " . $row["bankno"] ?></td>
        </tr>
        <tr>
            <td align="right"><b>Keterangan</b></td>
            <td align="center"><b>:</b></td>
            <td align="left"><?= $row["ketver"] ?></td>
        </tr>
        </table>
        <br><br>
        <table border="0" cellpadding="5" cellspacing="0">
        <tr>
            <td align="right" width="80"><b>Kelebihan</b></td>
            <td align="center" width="10"><b>:</b></td>
            <td align="left" width="300">
                <span style="font-size: 16px;"><?=$rp?></span>
                <input type="hidden" id="idpgtranslebih" value="<?=$idPgTransLebih?>">
            </td>
        </tr>
        <tr>
            <td align="right"><b>Metode</b></td>
            <td align="center"><b>:</b></td>
            <td align="left">
                <select id="proses" class="inputbox" style="width: 250px; font-size: 14px" onchange="changeSelProses()">
                    <option value="1" selected>Simpan Ke Tabungan</option>
                    <option value="2">Transfer Kembali</option>
                </select>
            </td>
        </tr>
        </table>

        <div id="dvSimpanTabungan">
            <table border="0" cellpadding="5" cellspacing="0">
            <tr>
                <td align="right"><b>Tabungan</b></td>
                <td align="center"><b>:</b></td>
                <td align="left">
<?php           ShowSelectTabungan()        ?>
                </td>
                <td rowspan="6" valign="top">
                    <div id="buktitfpreview1" style="width: 370px; height: 290px; overflow: auto;">
                        <img id="imagePreview1" style="width: 365px">
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right" width="80"><b>Tanggal Transfer</b></td>
                <td align="center" width="10"><b>:</b></td>
                <td align="left" width="300">
                    <input type="hidden" id="dttanggaltf1" value="<?= date('Y-m-d') ?>">
                    <input type="text" id="tanggaltf1" class="inputbox"
                           value="<?= formatInaMySqlDate(date('Y-m-d')) ?>"
                           style="width: 140px"
                           onclick="showDatePickerTf(1)">
                </td>
            </tr>
            <tr>
                <td align="right"><b>Nomor Bukti Transfer</b></td>
                <td align="center"><b>:</b></td>
                <td align="left">
                    <input type="text" id="nomortf1" class="inputbox" style="width: 250px">
                </td>
            </tr>
            <tr>
                <td align="right">
                    Gambar Bukti Transfer&nbsp;
                    <img src="../images/ico/hapus.png" style="cursor: pointer" title="hapus"
                         onclick="removeBuktiTf(1)">
                </td>
                <td align="center"><b>:</b></td>
                <td align="left">
                    <input type="file" id="buktitf1" class="inputbox" style="width: 250px" onchange="validateBuktiTf(1)">
                    <input type="hidden" id="buktitfvalid1" value="0"><br>
                    <span id="spFileInfo1"></span>
                </td>
            </tr>
            <tr>
                <td align="right">Keterangan</td>
                <td align="center"><b>:</b></td>
                <td align="left">
                    <textarea id="keterangan1" rows="2" cols="31" class="inputbox"></textarea>
                </td>
            </tr>
            <tr>
                <td align="right">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="left">
                    <input type="button" id="btSimpanTabungan" class="but" value="Simpan" style="height: 35px; width: 80px" onclick="simpanTabungan()">&nbsp;&nbsp;
                    <input type="button" id="btTutupTabungan" class="but" value="Tutup" style="height: 35px; width: 80px" onclick="window.close()">&nbsp;&nbsp;
                    <span id="spSimpanTabungan"></span>
                </td>
            </tr>
            </table>
        </div>

        <div id="dvTransferBalik" style="display: none;">
            <table border="0" cellpadding="5" cellspacing="0">
            <tr>
                <td align="right" width="80"><b>Tanggal Transfer</b></td>
                <td align="center" width="10"><b>:</b></td>
                <td align="left" width="300">
                    <input type="hidden" id="dttanggaltf2" value="<?= date('Y-m-d') ?>">
                    <input type="text" id="tanggaltf2" class="inputbox"
                           value="<?= formatInaMySqlDate(date('Y-m-d')) ?>"
                           style="width: 140px"
                           onclick="showDatePickerTf(2)">
                </td>
                <td rowspan="5" valign="top">
                    <div id="buktitfpreview2" style="width: 370px; height: 290px; overflow: auto;">
                        <img id="imagePreview2" style="width: 365px">
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right"><b>Nomor Bukti Transfer</b></td>
                <td align="center"><b>:</b></td>
                <td align="left">
                    <input type="text" id="nomortf2" class="inputbox" style="width: 250px">
                </td>
            </tr>
            <tr>
                <td align="right">
                    Gambar Bukti Transfer&nbsp;
                    <img src="../images/ico/hapus.png" style="cursor: pointer" title="hapus"
                         onclick="removeBuktiTf(2)">
                </td>
                <td align="center"><b>:</b></td>
                <td align="left">
                    <input type="file" id="buktitf2" class="inputbox" style="width: 250px" onchange="validateBuktiTf(2)">
                    <input type="hidden" id="buktitfvalid2" value="0"><br>
                    <span id="spFileInfo2"></span>
                </td>
            </tr>
            <tr>
                <td align="right">Keterangan</td>
                <td align="center"><b>:</b></td>
                <td align="left">
                    <textarea id="keterangan2" rows="2" cols="31" class="inputbox"></textarea>
                </td>
            </tr>
            <tr>
                <td align="right">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="left">
                    <input type="button" id="btSimpanTransfer" class="but" value="Simpan" style="height: 35px; width: 80px" onclick="simpanTransfer()">&nbsp;&nbsp;
                    <input type="button" id="btTutupTransfer" class="but" value="Tutup" style="height: 35px; width: 80px" onclick="window.close()">&nbsp;&nbsp;
                    <span id="spSimpanTransfer"></span>
                </td>
            </tr>
            </table>
        </div>

    </td>
</tr>
<tr>
    <td align="left" width="100%">


    </td>
</tr>
</table>

</body>
</html>
<?php
CloseDb();
?>