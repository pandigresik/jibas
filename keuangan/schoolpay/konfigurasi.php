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

OpenDb();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Konfigurasi SchoolPay</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="konfigurasi.js?r=<?=filemtime('konfigurasi.js')?>"></script>
</head>

<body >
<table border="0" width="100%" height="100%">
<tr>
    <td align="center" valign="top" background="../images/bulu1.png" style="background-repeat:no-repeat">

        <table border="0" width="100%" align="center">
        <tr>
            <td align="left" valign="top">

                <table border="0"width="95%" align="center">
                <tr>
                    <td align="right">
                        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;
                        </font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Konfigurasi Cashless Payment</font>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <a href="schoolpay.php">
                            <font size="1" color="#000000"><b>SchoolPay</b></font>
                        </a>&nbsp>&nbsp
                        <font size="1" color="#000000"><b>Konfigurasi Cashless Payment</b></font>
                    </td>
                </tr>
                <tr>
                    <td align="left">&nbsp;</td>
                </tr>
                </table>
                <br />

            </td>
        </tr>
        </table>
        <br>

        <table border="0" width="100%" align="left">
        <tr>
            <td align="left" valign="top" width="10%">
                &nbsp;
            </td>
            <td align="left" valign="top" width="*">

                <a href="#" onclick="location.reload();"><img src="../images/ico/refresh.png" border="0">&nbsp;refresh</a><br><br>
                <span style="font-size: 14pt">Pembayaran Siswa</span><br>

                <table id="tablesis" border="1" cellpadding="5" style="border-width: 1px; border-collapse: collapse; border-color: #dddddd">
                <tr>
                    <td class="header" width="30" rowspan="2" align="center">No</td>
                    <td class="header" width="180" rowspan="2" align="center">Departemen</td>
                    <td class="header" width="220" rowspan="2" align="center">AutoDebet Tabungan<br>untuk Pembayaran</td>
                    <td class="header" width="180" rowspan="2" align="center">Maksimum Transaksi<br> per Hari</td>
                    <td class="header" width="320" colspan="2" align="center">Vendor</td>
                    <td class="header" width="80" rowspan="2">&nbsp</td>
                </tr>
                <tr>
                    <td class="header" width="160" align="center">Rek Kas Vendor</td>
                    <td class="header" width="160" align="center">Rek Utang Vendor</td>
                </tr>
<?php
                $no = 0;
                $sql = "SELECT departemen FROM jbsakad.departemen WHERE aktif = 1 ORDER BY urutan";
                $res = QueryDb($sql);
                while($row = mysqli_fetch_row($res))
                {
                    $no += 1;
                    $dept = $row[0];

                    $idPt = 0;
                    $namaTab = "";
                    $rekKas = "";
                    $rekUtang = "";
                    $maxTrans = "";

                    $sql = "SELECT pt.replid, dt.nama, pt.rekkasvendor, pt.rekutangvendor, pt.maxtransvendor, rk1.nama AS namakasvendor, rk2.nama AS namautangvendor
                              FROM jbsfina.paymenttabungan pt
                             INNER JOIN jbsfina.datatabungan dt ON pt.idtabungan = dt.replid
                             INNER JOIN jbsfina.rekakun rk1 ON pt.rekkasvendor = rk1.kode
                             INNER JOIN jbsfina.rekakun rk2 ON pt.rekutangvendor = rk2.kode
                             WHERE pt.departemen = '$dept'
                               AND pt.jenis = 2";

                    $res2 = QueryDb($sql);
                    if ($row2 = mysqli_fetch_array($res2))
                    {
                        $idPt = $row2["replid"];
                        $namaTab = $row2["nama"];
                        $rekKas = $row2["rekkasvendor"] . " - " . $row2["namakasvendor"];
                        $rekUtang = $row2["rekutangvendor"] . " - " . $row2["namautangvendor"];
                        $maxTrans = $row2["maxtransvendor"];
                    }

                    $sql = "SELECT p.replid 
                              FROM jbsfina.paymenttrans p, jbsakad.siswa s, jbsakad.angkatan a
                             WHERE p.nis = s.nis
                               AND s.idangkatan = a.replid
                               AND a.departemen = '$dept'
                               AND p.jenis = 2
                             LIMIT 1  ";
                    $res2 = QueryDb($sql);
                    $isReadOnly = mysqli_num_rows($res2) > 0;
                    ?>
                    <tr>
                        <td align="center"><?= $no ?></td>
                        <td align="left"><?= $dept ?></td>
                        <td align="left"><?= $namaTab ?></td>
                        <td align="right"><?= FormatRupiah($maxTrans) ?></td>
                        <td align="left"><?= $rekKas ?></td>
                        <td align="left"><?= $rekUtang ?></td>
                        <td align="center">

                        <?php
                        if (getLevel() != 2)
                        {   ?>

                            <a href="#" onclick="atur(<?= $idPt ?>, '<?= $dept ?>')"><img src="../images/ico/ubah.png"
                                                                                          border="0" alt=""/></a>
                            <?php if (!$isReadOnly) { ?>
                                <a href="#" onclick="hapus(<?= $idPt ?>)"><img src="../images/ico/hapus.png" border="0"
                                                                               alt=""/></a>
                            <?php }
                        }

                        ?>
                            &nbsp;
                        </td>
                    </tr>
<?php
                }

?>
                </table>

                <br><br>
                <span style="font-size: 14pt">Pembayaran Pegawai</span><br>
                <table id="tablepeg" border="1" cellpadding="5" style="border-width: 1px; border-collapse: collapse; border-color: #dddddd">
                <tr>
                    <td class="header" width="30" rowspan="2">No</td>
                    <td class="header" width="180" rowspan="2" align="center">Departemen</td>
                    <td class="header" width="220" rowspan="2" align="center">AutoDebet Tabungan<br>untuk Pembayaran</td>
                    <td class="header" width="180" rowspan="2" align="center">Maksimum Transaksi<br>per Hari</td>
                    <td class="header" width="320" colspan="2" align="center">Vendor</td>
                    <td class="header" width="80" rowspan="2">&nbsp</td>
                </tr>
                <tr>
                    <td class="header" width="160" align="center">Rek Kas Vendor</td>
                    <td class="header" width="160" align="center">Rek Utang Vendor</td>
                </tr>
<?php
                $sql = "SELECT COUNT(replid) FROM jbsfina.paymenttabungan WHERE jenis = 1";
                $nData = FetchSingle($sql);
                if ($nData == 0)
                {
                    ?>
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="center">
                        <?php
                        if (getLevel() != 2) { ?>
                            <a href="#" onclick="aturPeg(0)"><img src="../images/ico/ubah.png" border="0" alt=""/></a>
                            <a href="#" onclick="hapusPeg(0)"><img src="../images/ico/hapus.png" border="0" alt=""/></a>
                        <?php
                        }
                        ?>
                        </td>
                    </tr>
<?php
                }
                else
                {
                    $sql = "SELECT pt.replid, pt.departemen, dt.nama, pt.rekkasvendor, pt.rekutangvendor, pt.maxtransvendor, rk1.nama AS namakasvendor, rk2.nama AS namautangvendor
                              FROM jbsfina.paymenttabungan pt
                             INNER JOIN jbsfina.datatabunganp dt ON pt.idtabunganp = dt.replid
                             INNER JOIN jbsfina.rekakun rk1 ON pt.rekkasvendor = rk1.kode
                             INNER JOIN jbsfina.rekakun rk2 ON pt.rekutangvendor = rk2.kode
                             WHERE pt.jenis = 1";

                    $res2 = QueryDb($sql);
                    if ($row2 = mysqli_fetch_array($res2))
                    {
                        $idPt = $row2["replid"];
                        $dept = $row2["departemen"];
                        $namaTab = $row2["nama"];
                        $rekKas = $row2["rekkasvendor"] . " - " . $row2["namakasvendor"];
                        $rekUtang = $row2["rekutangvendor"] . " - " . $row2["namautangvendor"];
                        $maxTrans = $row2["maxtransvendor"];
                    }

                    $sql = "SELECT replid
                              FROM jbsfina.paymenttrans
                             WHERE jenis = 1
                             LIMIT 1";
                    $res2 = QueryDb($sql);
                    $isReadOnly = mysqli_num_rows($res2) > 0;
                    ?>
                    <tr>
                        <td align="center">1</td>
                        <td align="left"><?= $dept ?></td>
                        <td align="left"><?= $namaTab ?></td>
                        <td align="right"><?= FormatRupiah($maxTrans) ?></td>
                        <td align="left"><?= $rekKas ?></td>
                        <td align="left"><?= $rekUtang ?></td>
                        <td align="center">

<?php                   if (getLevel() != 2)
                        { ?>
                            <a href="#" onclick="aturPeg(<?= $idPt ?>, '<?= $dept ?>')">
                                <img src="../images/ico/ubah.png" border="0" alt=""/>
                            </a>
<?php                       if (!$isReadOnly)
                            { ?>
                                <a href="#" onclick="hapusPeg(<?= $idPt ?>)">
                                    <img src="../images/ico/hapus.png" border="0" alt=""/>
                                </a>
<?php                       }
                        } ?>
                        </td>
                    </tr>
                    <?php
                }
?>
                </table>

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