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
require_once("channel.content.func.php");

$userId = SI_USER_ID();

$dept = $_REQUEST["dept"];
$idpel = $_REQUEST["idpel"];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Jadwal Guru</title>
    <script language="javascript" src="../script/jquery-1.9.1.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/jsonutil.js"></script>
    <script language="javascript" src="channel.content.js?m=<?=filemtime("channel.content.js")?>"></script>
</head>

<body topmargin="10" leftmargin="10">
<br>

<input type="hidden" id="departemen" value="<?=$dept?>">
<input type="hidden" id="idpelajaran" value="<?=$idpel?>">

<table border="0" cellspacing="0" cellpadding="2" width="1030">
<tr>
    <td align="right">
        <a onclick="ch_tambahChannel()" style="cursor: pointer;"><img src="../images/ico/tambah.png" border="0">&nbsp;Channel Baru</a>&nbsp;&nbsp;
        <a onclick="ch_refresh()" style="cursor: pointer;"><img src="../images/ico/refresh.png" border="0">Refresh</a>
    </td>
</tr>
</table>

<table id="tableChannel" border="1" cellspacing="0" cellpadding="10" style="border-width: 1px; border-collapse: collapse; border-color: #666;" width="1030">
<tr style="height: 25px;">
    <td class="header" width="40" align="center">No</td>
    <td class="header" width="600">Channel</td>
    <td class="header" width="100" align="center">#Video</td>
    <td class="header" width="100" align="center">#Modul</td>
    <td class="header" width="100" align="center">#Follower</td>
    <td class="header" width="100">&nbsp;</td>
</tr>
<?php
$sql = "SELECT judul, deskripsi, DATE_FORMAT(tanggal, '%d-%b-%Y %H:%i') AS tanggal, aktif, urutan, id, nfollower
          FROM jbsel.channel
         WHERE nip = '$userId'
           AND idpelajaran = $idpel
         ORDER BY urutan";
$res = QueryDb($sql);
$no = 0;
while($row = mysqli_fetch_row($res))
{
    $no += 1;
    $aktif = 1 == (int) $row[3]? "Aktif" : "Tidak Aktif";
    $idChannel = $row[5];

    $sql = "SELECT COUNT(id)
              FROM jbsel.media
             WHERE idchannel = $idChannel";
    $nMedia = FetchSingle($sql);

    $sql = "SELECT COUNT(id)
              FROM jbsel.modul
             WHERE idchannel = $idChannel";
    $nModul = FetchSingle($sql);

    $info = "<strong>".$row[0]."</strong><br>Tanggal buat: $row[2]<br>Urutan: $row[4]<br>Deskripsi: $row[1]";
?>
    <tr>
        <td align="center" valign="top"><?=$no?></td>
        <td><?=$info?></td>
        <td align="center" valign="top">
            <span style="font-size: 18px;"><?=$nMedia?></span>
            <input type="hidden" id="nmedia-<?=$idChannel?>" value="<?=$nMedia?>">
        </td>
        <td align="center" valign="top">
            <span style="font-size: 18px;"><?=$nModul?></span>
            <input type="hidden" id="nmodul-<?=$idChannel?>" value="<?=$nModul?>">
        </td>
        <td align="center" valign="top">
            <span style="font-size: 18px;"><?=$row[6]?></span><br>
            <a style="cursor: pointer; color: blue; font-weight: normal;" onclick="ch_viewFollower(<?=$idChannel?>)">
                <img src="../images/ico/lihat.png" border="0">&nbsp;lihat
            </a>
        </td>
        <td align="center" valign="top">
            <span id="spStatusAktif<?=$no?>">
<?php       ShowChannelAktif($no, $idChannel) ?>
            </span>&nbsp;
            <a onclick="ch_editChannel(<?=$idChannel?>)" style="cursor: pointer"><img src="../images/ico/ubah.png" border="0" alt="ubah"/></a>&nbsp;
            <a onclick="ch_hapusChannel(<?=$idChannel?>)" style="cursor: pointer"><img src="../images/ico/hapus.png" border="0" alt="ubah"/></a>
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