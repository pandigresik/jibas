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
require_once ("common.func.php");
require_once ("home.func.php");
require_once ("setting.php");

OpenDb();
?>
<table border="0" cellspacing="0" cellpadding="2" width="1000">
<tr>
    <td width="100%" align="right" valign="top" style="line-height: 18px">
        Departemen:
<?php   $selDept = "ALLDEPT";
        ShowCbHomeDepartemen($selDept) ?>
    </td>
</tr>
<tr>
    <td width="100%" align="left" valign="top" style="line-height: 18px">
        <br>
<?php      IsAllowPlaying() ?>
        <span style="color: #e08b0e; font-size: 24px; font-family: Calibri;">Random Video</span>&nbsp;&nbsp;
        <span style="font-weight: normal; color: blue; font-size: 11px; cursor: pointer" onclick="hm_nextRandomVideo()">next ..</span>
        <br>
        <div id="divRandomVideo" style="height: 340px; background-color: white;">
<?php   ShowRandomVideo($selDept) ?>
        </div>
        <br><br>

        <span style="color: #5d9beb; font-size: 24px; font-family: Calibri;">Most Liked</span>&nbsp;&nbsp;
        <span style="font-weight: normal; color: blue; font-size: 11px; cursor: pointer" onclick="hm_nextMostLiked()">next ..</span>
        <div id="divMostLiked" style="height: 340px; background-color: white; overflow: auto;">
            <table id="tabMostLiked" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr><td>
<?php           $idList = GetMostLikedList($selDept);?>
                <input type="hidden" id="idListMostLiked" value="<?=$idList?>">
                </td></tr>
            </thead>
            <tbody id="tabMostLikedBody">
<?php           ShowMediaList($idList, 1 ) ?>
            </tbody>
            </table>
        </div>
        <br><br>

        <span style="color: #8565eb; font-size: 24px; font-family: Calibri;">Most Viewed</span>&nbsp;&nbsp;
        <span style="font-weight: normal; color: blue; font-size: 11px; cursor: pointer" onclick="hm_nextMostViewed()">next ..</span>
        <div id="divMostViewed" style="height: 340px; background-color: white; overflow: auto;">
            <table id="tabMostViewed" border="0" cellspacing="0" cellpadding="0">
                <thead>
                <tr><td>
<?php               $idList = GetMostViewedList($selDept);?>
                    <input type="hidden" id="idListMostViewed" value="<?=$idList?>">
                </td></tr>
                </thead>
                <tbody id="tabMostViewedBody">
<?php           ShowMediaList($idList, 1 ) ?>
                </tbody>
            </table>
        </div>
        <br>

</td></tr>
</table>
<br><br>
<?php
CloseDb();
?>