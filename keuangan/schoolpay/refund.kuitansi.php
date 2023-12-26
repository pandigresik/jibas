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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/rupiah.php');
require_once('../include/sessioninfo.php');
require_once('../include/db_functions.php');
require_once('../include/getheader.php');

$idRefund = $_REQUEST["idrefund"];

OpenDb();

$sql = "SELECT DATE_FORMAT(r.waktu, '%d-%b-%Y %H:%i') AS waktu, IFNULL(pg.nama, 'Administrator JIBAS') AS petugas, 
               u.nama AS penerima, r.jumlah, r.keterangan, 
               IFNULL(r.idjurnalsiswa, 0) AS idjurnalsiswa, IFNULL(r.idjurnalpegawai, 0) AS idjurnalpegawai, tb.departemen 
          FROM jbsfina.refund r
          LEFT JOIN jbssdm.pegawai pg ON r.nip = pg.nip
          LEFT JOIN jbsfina.userpos u ON r.idpenerima = u.userid
          LEFT JOIN jbsfina.tahunbuku tb ON r.idtahunbuku = tb.replid
         WHERE r.replid = $idRefund";
$res = QueryDb($sql);
$row = mysqli_fetch_array($res);
$tanggal = $row["waktu"];
$jumlah = $row["jumlah"];
$petugas = $row["petugas"];
$penerima = $row["penerima"];
$keterangan = $row["keterangan"];
$idJurnalSiswa = $row["idjurnalsiswa"];
$idJurnalPegawai = $row["idjurnalpegawai"];
$departemen = $row["departemen"];

$noKas = "";
if ($idJurnalSiswa <> 0)
{
    $sql = "SELECT nokas FROM jbsfina.jurnal WHERE replid = $idJurnalSiswa";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        $noKas = $row[0];
}

if ($idJurnalPegawai <> 0)
{
    $sql = "SELECT nokas FROM jbsfina.jurnal WHERE replid = $idJurnalPegawai";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        if ($noKas <> "") $noKas .= ", ";
        $noKas .= $row[0];
    }
}

$sql = "SELECT DATE_FORMAT(tanggal, '%d-%b-%Y') AS tanggal
          FROM jbsfina.refunddate
         WHERE idrefund = $idRefund
         ORDER BY tanggal";
$res2 = QueryDb($sql);
$stTanggal = "";
while($row2 = mysqli_fetch_row($res2))
{
    if ($stTanggal <> "") $stTanggal .= ", ";
    $stTanggal .= $row2[0];
}
$transaksi = "Pembayaran penerimaan Vendor dari transaksi SchoolPay tanggal $stTanggal";

$sql = "SELECT replid, nama, alamat1 FROM jbsumum.identitas WHERE departemen='$departemen'";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
$idHeader = $row["replid"];
$namaHeader = $row["nama"];
$alamatHeader = $row["alamat1"];

CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JIBAS KEU [Kuitansi Pembayaran Tabungan]</title>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">

<table border="0" cellpadding="0" cellspacing="0" width="340" align="center">
<?php
for($i = 0; $i < 2; $i++)
{
    ?>
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="0" cellspacing="3" width="330" align="center">
<?php       if ($i == 0)
            { ?>
                <tr>
                    <td align="center" width='15%'>
                        <img src='<?= $full_url."library/gambar.php?replid=$idHeader&table=jbsumum.identitas" ?>' height='30' />
                    </td>
                    <td align="left">
                        <font style='font-size:14px'><strong><?=$namaHeader?></strong></font><br>
                        <font style='font-size:10px'><?=$alamatHeader?></font>
                    </td>
                </tr>
<?php       } else { ?>
                <tr height="1">
                    <td align="center" width='15%'>&nbsp;</td>
                    <td align="left">&nbsp;</td>
                </tr>
<?php       } ?>
                <tr>
                    <td align="right" colspan='2'>
                        <font size="1"><strong>No. <?=$noKas ?></strong></font>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan='2'>
                        <font size="1"><strong>TANDA BUKTI PEMBAYARAN</strong></font>
                    </td>
                </tr>
                <tr>
                    <td align="left" colspan='2'>
                        Telah dibayarkan kepada:
                        <table border="0" cellpadding="2" cellspacing="0" width="100%">
                            <tr>
                                <td>&nbsp;</td>
                                <td>Nama</td>
                                <td>:&nbsp;<strong><?=$penerima?></strong></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>Tanggal</td>
                                <td>:&nbsp;<strong><?= $tanggal ?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" valign="top">uang sejumlah
                                    <font style="font-size:11px; font-weight:bold; font-style:italic;">
                                        <?= FormatRupiah($jumlah) ?> (<?= KalimatUang($jumlah) ?>)
                                    </font>
                                    untuk <?=$transaksi ?>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td width="65%">

                                    <table border="1" cellpadding="2" cellspacing="0" style="border-width:1px" width="100%">
                                        <tr>
                                            <td valign="top">
                                                <strong>Keterangan:</strong><br>
                                                &#149;&nbsp;<em>Tgl cetak: <?= date('d/m/Y H:i:s') ?></em><br>
                                                &#149;&nbsp;<em>Petugas: <?= $petugas ?></em><br>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                                <td align="center">
                                    <?php if ($i == 0) { ?>
                                        Yang menerima<br /><br /><br /><br /><br />
                                        ( <?=getUserName() ?> )
                                    <?php } else { ?>
                                        Yang menyerahkan<br /><br /><br /><br /><br />
                                        ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </td></tr>
            </table>
        </td></tr>
    <tr>
        <td align='right'>
            <?php if ($i == 0) { ?>
                <hr width="350" style="border-style:dashed; line-height:1px; color:#666;" />
            <?php } ?>
        </td>
    </tr>
<?php } //for ?>
</table>

</body>
<script language="javascript">
    window.print();
</script>
</html>