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
require_once("../include/sessionchecker.php");
require_once("media.content.func.php");
require_once("modul.media.func.php");
require_once("common.func.php");

$userId = SI_USER_ID();

$departemen = $_REQUEST["departemen"];
$idPelajaran = $_REQUEST["idPelajaran"];
$idChannel = $_REQUEST["idChannel"];
$idModul = $_REQUEST["idModul"];

OpenDb();

$sql = "SELECT judul, deskripsi 
          FROM jbsel.modul
         WHERE id = $idModul";
$res = QueryDb($sql);
if ($row = mysqli_fetch_row($res))
{
    $judul = $row[0];
    $deskripsi = $row[1];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Penyusunan Media Modul</title>
    <script language="javascript" src="../script/jquery-1.9.1.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="modul.media.js"></script>
</head>

<body topmargin="10" leftmargin="10">
<br>
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idPelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="idChannel" value="<?=$idChannel?>">
<input type="hidden" id="idModul" value="<?=$idModul?>">

<span style="font-size: 16px; font-weight: bold">Media <?=$judul?></span><br>
<span style="font-size: 11px; font-style: italic"><?=$deskripsi?></span><br>
<input type="button" class="but" style="height: 24px; width: 90px" value="Kembali" onclick="backToModul()">


<table border="0" cellspacing="0" cellpadding="2" width="1200">
<tr>
    <td align="right">
        <a onclick="tambahMedia()" style="cursor: pointer;"><img src="../images/ico/tambah.png" border="0">&nbsp;Tambah Video</a>&nbsp;&nbsp;
        <a onclick="refreshMedia()" style="cursor: pointer;"><img src="../images/ico/refresh.png" border="0">&nbsp;Refresh</a>
    </td>
</tr>
</table>

<table id="tableChannel" border="1" cellspacing="0" cellpadding="10" style="border-width: 1px; border-collapse: collapse; border-color: #666;" width="1200">
<tr style="height: 25px;">
    <td class="header" width="40" align="center">No</td>
    <td class="header" width="300" align="center">Video</td>
    <td class="header" width="450" align="center">Informasi</td>
    <td class="header" width="400" align="center">Keterangan</td>
    <td class="header" width="100">&nbsp;</td>
</tr>
<?php

$sql = "SELECT id, idmedia, urutan, keterangan
          FROM jbsel.mediamodul
         WHERE idmodul = $idModul
         ORDER BY urutan";
$res = QueryDb($sql);

$cnt = 0;
while ($row = mysqli_fetch_array($res))
{
    $cnt += 1;

    $idMediaModul = $row["id"];
    $idMedia = $row["idmedia"];
    $urutan = $row["urutan"];
    $keterangan = $row["keterangan"];
    ?>
    <tr>
        <td align="center" valign="top"><?= $cnt ?></td>
        <td align="left" valign="top">
            <div id="listVideo<?= $cnt ?>">
<?php           ShowMediaVideo($idMedia) ?>
            </div>
        </td>
        <td align="left" valign="top">
            <div id="listInfo<?= $cnt ?>"
                 style="height: 180px; background-color: white; overflow: auto; line-height: 20px; ">
<?php           ShowMediaInfo($idMedia) ?>
            </div>
        </td>
        <td align="left" valign="top">
            <div id="listKeterangan<?= $cnt ?>"
                 style="height: 180px; background-color: white; overflow: auto; line-height: 20px; ">
<?php           ShowKeterangan($idMediaModul) ?>
            </div>
        </td>
        <td align="center" valign="top">
            <span id="spStatusAktif<?= $cnt ?>">
<?php           ShowMediaModulAktif($cnt, $idMediaModul) ?>
            </span>&nbsp;
            <a onclick="editMediaModul(<?=$cnt?>, <?=$idMediaModul?>)" style="cursor: pointer"><img src="../images/ico/ubah.png" border="0" alt="ubah"/></a>&nbsp;
            <a onclick="hapusMediaModul(<?=$cnt?>, <?=$idMediaModul?>)" style="cursor: pointer"><img src="../images/ico/hapus.png" border="0" alt="hapus"/></a>
        </td>
    </tr>
    <?php
}

?>
</table>

</body>
</html>
<?php
CloseDb();
?>