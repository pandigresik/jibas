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
require_once ("modul.func.php");
require_once ("common.func.php");

$idModul = $_REQUEST["idModul"];

OpenDb();

$sql = "SELECT m.judul AS modul, c.judul as channel, m.deskripsi, pl.nama as pelajaran, g.nama as guru
          FROM jbsel.modul m, jbsel.channel c, jbsakad.pelajaran pl, jbssdm.pegawai g
         WHERE m.idchannel = c.id
           AND c.idpelajaran = pl.replid
           AND c.nip = g.nip 
           AND m.id = $idModul";
$res = QueryDb($sql);
if ($row = mysqli_fetch_row($res))
{
    $modul = $row[0];
    $channel = $row[1];
    $deskripsi = $row[2];
    $pelajaran = $row[3];
    $guru = $row[4];
}
?>
<input type="hidden" id="idModul" value="<?=$idModul?>">
<table border="0" cellspacing="0" cellpadding="2" width="1000">
<tr>
    <td width="80%" align="left" valign="top" style="line-height: 22px">
        <span style="font-weight: bold; color: #c1354c">MODUL</span><br>
        <span style="font-size: 24px"><?=$modul?></span><br>
        <span style="color: blue"><?="$pelajaran | $channel | $guru"?></span><br>
        <span style="color: #333; font-style: italic"><?=$deskripsi?></span>
    </td>
    <td width="*" align="left" valign="top">

        <table border="0" cellpadding="5" cellspacing="0">
            <tr>
                <td>
                <span id="spFollow">
<?php           ShowFollowButton($idModul) ?>
                </span>
                </td>
                <td valign="middle">
                    Follower:<br>
                    <span id="spFollowCount" style="font-size: 14px">
<?php               echo GetFollowerCount($idModul); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <span style="color: blue; cursor: pointer" onclick="md_reload(<?=$idModul?>)">
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
        <span style="font-weight: bold; color: #999; font-size: 14px;">Video (<?= GetVideoCount($idModul) ?>)</span><br><br>
        &nbsp;&nbsp;&nbsp;Sort by:&nbsp;
        <select id="cbUrutanModulVideo" onchange="md_changeVideoOrder()">
            <option value="1">Relevance</option>
            <option value="2">Most Liked</option>
            <option value="3">Most Viewed</option>
        </select>
        <br>
        <div id="divModulVideo" style="background-color: #fff; width: 98%; margin-left: 10px; margin-top: 10px; margin-right: 10px;">
<?php       $idList = GetVideoList($idModul, 1) ?>
            <input type="hidden" id="idVideoModulList" value="<?=$idList?>">
            <table id="tabVideoModul" border="0" cellpadding="5" cellspacing="0">
                <thead>
                <tr>
                    <td width="250">&nbsp;</td>
                    <td width="650">&nbsp;</td>
                    <td width="100">&nbsp;</td>
                </tr>
                </thead>
                <tbody>
<?php               ShowMedia($idList, 1);  ?>
                </tbody>
            </table>
        </div>
        <br>
        <a style="cursor: pointer; font-weight: normal; color: blue" onclick="md_nextVideoResult()">next .. </a>
    </td>
</tr>
</table>

<?php
CloseDb();
?>
