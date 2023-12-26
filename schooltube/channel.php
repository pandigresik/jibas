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
require_once ("include/config.php");
require_once ("include/db_functions.php");
require_once ("include/session.php");
require_once ("channel.func.php");
require_once ("common.func.php");

$idChannel = $_REQUEST["idChannel"];

OpenDb();

$sql = "SELECT c.judul as channel, g.nama as guru, pl.nama as pelajaran, c.deskripsi
          FROM jbsel.channel c, jbssdm.pegawai g, jbsakad.pelajaran pl
         WHERE c.idpelajaran = pl.replid
           AND c.nip = g.nip
           AND c.id = $idChannel";
$res = QueryDb($sql);
if ($row = mysqli_fetch_row($res))
{
    $channel = $row[0];
    $guru = $row[1];
    $pelajaran = $row[2];
    $deskripsi = $row[3];
}
?>
<input type="hidden" id="idChannel" value="<?=$idChannel?>">
<table border="0" cellspacing="0" cellpadding="2" width="1000">
<tr>
    <td width="80%" align="left" valign="top" style="line-height: 22px">
        <span style="font-weight: bold; color: #c1354c">CHANNEL</span><br>
        <span style="font-size: 24px"><?=$channel?></span><br>
        <span style="color: blue"><?= "$pelajaran | $guru" ?></span><br><br>
        <span style="color: #333; font-style: italic"><?=$deskripsi?></span>
    </td>
    <td width="*" align="left" valign="top">

        <table border="0" cellpadding="5" cellspacing="0">
        <tr>
            <td>
                <span id="spFollow">
<?php           ShowFollowButton($idChannel) ?>
                </span>
            </td>
            <td valign="middle">
                Follower:<br>
                <span id="spFollowCount" style="font-size: 14px">
<?php           echo GetFollowerCount($idChannel); ?>
                </span>
            </td>
        </tr>
            <tr>
                <td colspan="2" align="right">
                    <span style="color: blue; cursor: pointer" onclick="cn_reload(<?=$idChannel?>)">
                        <img src="images/refresh.png" border="0">&nbsp;muat ulang
                    </span>
                </td>
            </tr>
        </table>

    </td>
</tr>
<tr style="height: 270px; background-color: white;">
    <td colspan="2" valign="top" align="left">
        <br>
        <span style="font-weight: bold; color: #999; font-size: 14px;">Modul (<?= GetModulCount($idChannel) ?>)</span><br>
        <div id="divChannelModul" style="overflow: auto; background-color: #f0f0f0; width: 98%; height: 220px; margin-left: 10px; margin-top: 10px; margin-right: 10px;">
<?php   ShowModulChannel($idChannel) ?>
        </div>
    </td>
</tr>
<tr style="height: 270px; background-color: white;">
    <td colspan="2" valign="top" align="left">
        <br>
        <span style="font-weight: bold; color: #999; font-size: 14px;">Video (<?= GetVideoCount($idChannel) ?>)</span><br><br>
        &nbsp;&nbsp;&nbsp;Sort by:&nbsp;
        <select id="cbUrutanChannelVideo" onchange="cn_changeVideoOrder()">
            <option value="1">Relevance</option>
            <option value="2">Most Liked</option>
            <option value="3">Most Viewed</option>
        </select>
        <br>
        <div id="divChannelVideo" style="background-color: #fff; width: 98%; margin-left: 10px; margin-top: 10px; margin-right: 10px;">
<?php   $idList = GetVideoList($idChannel, 1) ?>
        <input type="hidden" id="idVideoChannelList" value="<?=$idList?>">
        <table id="tabVideoChannel" border="0" cellpadding="5" cellspacing="0">
            <thead>
            <tr>
                <td width="250">&nbsp;</td>
                <td width="650">&nbsp;</td>
                <td width="100">&nbsp;</td>
            </tr>
            </thead>
            <tbody>
<?php       ShowMedia($idList, 1);  ?>
            </tbody>
        </table>
        </div>
        <br>
        <a style="cursor: pointer; font-weight: normal; color: blue" onclick="cn_nextVideoChannelResult()">next .. </a>
    </td>
</tr>
</table>

<?php
CloseDb();
?>
