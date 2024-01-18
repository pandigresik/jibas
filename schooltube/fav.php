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
require_once ("fav.func.php");
require_once ("search.video.func.php");
require_once ("common.func.php");

OpenDb();
?>
<span style="font-size: 24px">Favourite Video</span><br>
Sort by:
<select id="sortFavBy" style="height: 24px; width: 120px;" onchange="fav_changeFavSort()">
    <option value="1">Newest</option>
    <option value="2">Oldest</option>
    <option value="3">Title A-Z</option>
    <option value="4">Title Z-A</option>
</select>&nbsp;&nbsp;
<?php
$nData = 0;
$idMediaList = GetVideoLikedIdList(1, $nData);
?>
<input type="hidden" id="fav_idMediaList" value="<?=$idMediaList?>">
<span style="font-style: italic; color: #333">found <?=$nData?> video</span><br><br>
<div id="divFav">
    <table id="tableFav" border="0" cellpadding="5" cellspacing="0">
    <thead>
    <tr>
        <td width="250">&nbsp;</td>
        <td width="650">&nbsp;</td>
    </tr>
    </thead>
    <tbody id="tableFavBody">
    <?php
    DisplayVideoSearchList($idMediaList, 0);
    ?>
    </tbody>
    </table>
</div>
<br>
<?php
if (strlen((string) $idMediaList) > 0)
    echo "<a style='cursor: pointer; font-weight: normal; color: blue' onclick='fav_nextVideoResult()'>next .. </a>";

CloseDb();
?>