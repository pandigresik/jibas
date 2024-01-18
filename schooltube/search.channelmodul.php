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

if ($searchBy == 1)
{
    if ($searchDept == "ALLDEPT")
    {
        $sql = "SELECT id
                  FROM jbsel.modul
                 WHERE aktif = 1
                   AND judul LIKE '%$searchKey%'";
    }
    else
    {
        $sql = "SELECT m.id
                  FROM jbsel.modul m, jbsel.channel c, jbsakad.pelajaran p
                 WHERE m.idchannel = c.id
                   AND c.idpelajaran = p.replid
                   AND p.departemen = '$searchDept'
                   AND m.aktif = 1
                   AND m.judul LIKE '%$searchKey%'";
    }

}
else
{
    if ($searchDept == "ALLDEPT")
    {
        $sql = "SELECT id
                  FROM jbsel.channel
                 WHERE aktif = 1
                   AND judul LIKE '%$searchKey%'";
    }
    else
    {
        $sql = "SELECT c.id
                  FROM jbsel.channel c, jbsakad.pelajaran p
                 WHERE c.idpelajaran = p.replid
                   AND p.departemen = '$searchDept'
                   AND c.aktif = 1
                   AND c.judul LIKE '%$searchKey%'";
    }
}

$res = QueryDb($sql);
$nData = mysqli_num_rows($res);
if ($nData == 0)
{
    echo "Tidak menemukan hasil!";
    exit();
}

$idList = "";
while($row = mysqli_fetch_row($res))
{
    if ($idList != "") $idList .= ",";
    $idList .= $row[0];
}
?>
<input type="hidden" id="sr_searchBy" value="<?=$searchBy?>">
<input type="hidden" id="sr_idList" value="<?=$idList?>">
<span style="font-style: italic; color: #333">found <?=$nData?> data</span><br><br>

<div id="divChannelModulSearch">
<table id="tableChannelModulSearch" border="0" width="900" cellpadding="2" cellspacing="0">
    <thead>
    <tr>
        <td width="900">&nbsp;</td>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($searchBy == 1)
        DisplayModulSearchList($idList, $page);
    else
        DisplayChannelSearchList($idList, $page);
    ?>
    </tbody>
</table>
</div>

<br>
<a style="cursor: pointer; font-weight: normal; color: blue" onclick="sr_nextChannelModulSearchResult()">next .. </a>
