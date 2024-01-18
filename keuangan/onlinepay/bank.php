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

$dept = $_REQUEST["dept"] ?? "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Rekening Bank</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="bank.js?r=<?=filemtime('bank.js')?>"></script>
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
                    </font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Rekening Bank</font>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <a href="onlinepay.php">
                        <font size="1" color="#000000"><b>OnlinePay</b></font>
                    </a>&nbsp>&nbsp
                    <font size="1" color="#000000"><b>Rekening Bank</b></font>
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
                <div>
                <span style="font-weight: bold; font-size: 14px">Departemen:</span>&nbsp;
                <select id="dept" name="dept" class="inputbox" style="width: 250px" onchange="changeDept()">
<?php
                $sql = "SELECT departemen FROM jbsakad.departemen WHERE aktif = 1 ORDER BY urutan";
                $res = QueryDb($sql);
                while($row = mysqli_fetch_row($res))
                {
                    if ($dept == "") $dept = $row[0];
                    $sel = ($dept == $row[0]) ? "selected" : "";

                    echo "<option value='".$row[0]."' $sel>".$row[0]."</option>";
                }
?>
                </select>&nbsp;&nbsp;
<?php           if (getLevel() != 2) { ?>
                    <a href="#" onclick="tambahBank()"><img src="../images/ico/tambah.png" border="0">&nbsp;tambah</a>&nbsp;&nbsp;
<?php           } ?>
                    <a href="#" onclick="location.reload();"
                       style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a>
                    <br>
                </div>
                <br><br>
                <table id="table" border="1" cellpadding="5" style="border-width: 1px; border-collapse: collapse; border-color: #dddddd">
                <tr style="height: 30px">
                    <td class="header" width="30" align="center">No</td>
                    <td class="header" width="150" align="center">Bank</td>
                    <td class="header" width="175" align="center">Lokasi</td>
                    <td class="header" width="175" align="center">Nomor Rekening</td>
                    <td class="header" width="175" align="center">Nama Rekening</td>
                    <td class="header" width="100" align="center">Status</td>
                    <td class="header" width="250" align="center">Keterangan</td>
                    <td class="header" width="80">&nbsp</td>
                </tr>
<?php
                $no = 0;
                $sql = "SELECT * FROM jbsfina.bank WHERE departemen = '".$dept."'";
                $res = QueryDb($sql);
                while ($row = mysqli_fetch_array($res))
                {
                    $bankReplid = $row["replid"];

                    if (getLevel() != 2)
                    {
                        $aktif = "<a href='#' onclick='setBankAktif($bankReplid, 1)'><img src='../images/ico/nonaktif.png' border='0' title='set aktif'></a>";
                        if ($row["aktif"] == 1)
                            $aktif = "<a href='#' onclick='setBankAktif($bankReplid, 0)'><img src='../images/ico/aktif.png' border='0' title='set non aktif'></a>";
                    }
                    else
                    {
                        $aktif = "<img src='../images/ico/nonaktif.png' border='0' title='set aktif'>";
                        if ($row["aktif"] == 1)
                            $aktif = "<img src='../images/ico/aktif.png' border='0' title='set non aktif'>";
                    }

                    $no += 1;
                    ?>

                    <tr>
                        <td align="center" valign="top"><?=$no?></td>
                        <td align="left" valign="top"><?=$row["bank"]?></td>
                        <td align="left" valign="top"><?=$row["bankloc"]?></td>
                        <td align="left" valign="top"><?=$row["bankno"]?></td>
                        <td align="left" valign="top"><?=$row["bankname"]?></td>
                        <td align="center" valign="top">
                            <span id="spAktif<?=$bankReplid?>">
                                <?=$aktif?>
                            </span>
                        </td>
                        <td align="left" valign="top"><?=$row["keterangan"]?></td>
                        <td align="center" valign="top">
<?php                       if (getLevel() != 2) { ?>
                                <a href="#" onclick="editBank(<?= $bankReplid ?>)" ><img src="../images/ico/ubah.png" border="0" alt=""/></a>&nbsp;&nbsp;
                                <a href="#" onclick="hapusBank('<?= $bankReplid ?>')"><img src="../images/ico/hapus.png" border="0" alt=""/></a>
<?php                       } ?>
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