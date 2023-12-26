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
require_once("common.func.php");

$userId = SI_USER_ID();

$idChannel = $_REQUEST["idChannel"];

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
    <script language="javascript" src="media.content.js?m=<?=filemtime("media.content.js")?>"></script>
</head>

<body topmargin="10" leftmargin="10">
<br>

<input type="hidden" id="idChannel" value="<?=$idChannel?>">

<table border="0" cellspacing="0" cellpadding="2" width="1400">
<tr>
    <td align="right">
        <a onclick="tambahMedia()" style="cursor: pointer;"><img src="../images/ico/tambah.png" border="0">&nbsp;Upload Video</a>&nbsp;&nbsp;
        <a onclick="refreshPage()" style="cursor: pointer;"><img src="../images/ico/refresh.png" border="0">Refresh</a>
    </td>
</tr>
</table>

<table id="tableChannel" border="1" cellspacing="0" cellpadding="10" style="border-width: 1px; border-collapse: collapse; border-color: #666;" width="1400">
<tr style="height: 25px;">
    <td class="header" width="40" align="center">No</td>
    <td class="header" width="300" align="center">Video</td>
    <td class="header" width="450" align="center">Informasi</td>
    <td class="header" width="400" align="center">Berkas</td>
    <td class="header" width="100" align="center">#Like</td>
    <td class="header" width="100" align="center">#View</td>
    <td class="header" width="100">&nbsp;</td>
</tr>
<?php
    $sql = "SELECT id
              FROM jbsel.media 
             WHERE idchannel = $idChannel  
             ORDER BY urutan";
    $res = QueryDb($sql);

    $cnt = 0;
    while($row = mysqli_fetch_array($res))
    {
        $cnt += 1;

        $idMedia = $row["id"];
        ?>
        <tr>
            <td align="center" valign="top"><?=$cnt?></td>
            <td align="left" valign="top">
                <div id="listVideo<?=$cnt?>">
<?php               ShowMediaVideo($idMedia) ?>
                </div>
                <a style="cursor: pointer; font-weight: normal; color: blue;" onclick="changeVideo(<?=$cnt?>, <?=$idMedia?>)">edit video</a>
            </td>
            <td align="left" valign="top">
                <div id="listInfo<?=$cnt?>" style="height: 180px; background-color: white; overflow: auto; line-height: 17px; ">
<?php              ShowMediaInfo($idMedia) ?>
                </div>
                <a style="cursor: pointer; font-weight: normal; color: blue;" onclick="changeInfo(<?=$cnt?>, <?=$idMedia?>)">edit info</a>
            </td>
            <td align="left" valign="top">
                <div id="listFile<?=$cnt?>" style="height: 180px; background-color: white; overflow: auto; line-height: 20px; ">
<?php               ShowMediaFiles($cnt, $idMedia) ?>
                </div>
                <a style="cursor: pointer; font-weight: normal; color: blue;" onclick="changeFile(<?=$cnt?>, <?=$idMedia?>)">tambah file lampiran</a>
            </td>
            <td align="center" valign="top">
                <span style="font-size: 18px;"><?= CountLike($idMedia) ?></span><br>
                <a style="cursor: pointer; color: blue; font-weight: normal;" onclick="viewLiker(<?=$idMedia?>)">
                    <img src="../images/ico/lihat.png" border="0">&nbsp;lihat
                </a>
            </td>
            <td align="center" valign="top">
                <span style="font-size: 18px;"><?= CountView($idMedia) ?></span>
            </td>
            <td align="center" valign="top">
                <span id="spStatusAktif<?=$cnt?>">
<?php               ShowMediaAktif($cnt, $idMedia) ?>
                </span>&nbsp;&nbsp;
                <a style="cursor: pointer" onclick="removeMedia(<?=$idMedia?>)"><img src="../images/ico/hapus.png" border="0" title="hapus media"></a>
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