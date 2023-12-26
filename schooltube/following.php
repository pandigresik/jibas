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
require_once ("following.func.php");
require_once ("common.func.php");

$type = $_REQUEST["type"];
$title = $type ==  1 ? "Modul" : "Channel";

OpenDb();
?>
<input type="hidden" id="followType" value="<?=$type?>">
<table border="0" cellspacing="0" cellpadding="2" width="1000">
<tr>
    <td width="100%" align="left" valign="top" style="line-height: 18px">
        <span style="font-size: 24px">Following <?=$title?></span><br>
    </td>
</tr>
<tr>
    <td width="100%" align="left" valign="top" style="line-height: 18px">
        Sort by:
        <select id="sortFollowBy" style="height: 24px; width: 120px;" onchange="changeFollowSort()">
            <option value="1">Newest</option>
            <option value="2">Oldest</option>
            <option value="3">Title A-Z</option>
            <option value="4">Title Z-A</option>
        </select>
    </td>
</tr>
<tr>
    <td valign="top" align="left">
        <br><br>
        <div id="divFollowing">
<?php
        if ($type == 1)
            ShowFollowModul(1);
        else
            ShowFollowChannel(1);
?>
        </div>
    </td>
</tr>
</table>
<?php
CloseDb();
?>