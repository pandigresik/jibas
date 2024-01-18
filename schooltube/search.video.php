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
<span style="font-size: 24px">Search Result: <?= $searchKey ?></span><br><br>
<?php
$searchKey - str_replace("'", "`", (string) $searchKey);

if ($searchDept == "ALLDEPT")
{
    $sql = "SELECT f.idmedia
              FROM jbsel.ftdatamedia f, jbsel.media m
             WHERE f.idmedia = m.id
               AND m.aktif = 1
               AND f.data LIKE '%$searchKey%'";
}
else
{
    $sql = "SELECT f.idmedia
              FROM jbsel.ftdatamedia f, jbsel.media m, jbsel.channel c, jbsakad.pelajaran p
             WHERE f.idmedia = m.id
               AND m.idchannel = c.id
               AND c.idpelajaran = p.replid
               AND m.aktif = 1
               AND p.departemen = '$searchDept'
               AND f.data LIKE '%$searchKey%'";
}
$res = QueryDb($sql);
$nData = mysqli_num_rows($res);
if ($nData == 0)
{
    echo "Tidak menemukan hasil!";
    exit();
}

$idMediaList = "";
while($row = mysqli_fetch_row($res))
{
    if ($idMediaList != "") $idMediaList .= ",";
    $idMediaList .= $row[0];
}
?>
<input type="hidden" id="sr_idMediaList" value="<?=$idMediaList?>">
<span style="font-style: italic; color: #333">found <?=$nData?> video</span><br><br>

<div id="divSearch">
<table id="tableSearch" border="0" cellpadding="5" cellspacing="0">
<thead>
<tr>
    <td width="250">&nbsp;</td>
    <td width="650">&nbsp;</td>
    <td width="250">&nbsp;</td>
</tr>
</thead>
<tbody>
<?php
DisplayVideoSearchList($idMediaList, $page);
?>
</tbody>
</table>
</div>

<br>
<a style="cursor: pointer; font-weight: normal; color: blue" onclick="sr_nextVideoSearchResult()">next .. </a>
