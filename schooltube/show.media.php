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
require_once ("show.media.func.php");
require_once ("setting.php");

IsAllowPlaying();

OpenDb();

$idMedia = $_REQUEST["idMedia"];

$sessionId = session_id();

SaveViewCount($sessionId, $idMedia);

$sql = "SELECT m.id, m.judul, m.deskripsi, m.objektif, m.pertanyaan, IF(m.idkategori IS NULL, 0, m.idkategori) AS idkategori, 
               c.judul as channel, pg.nama AS guru, pl.nama as pelajaran, m.videoloc, m.videoname
          FROM jbsel.media m, jbsel.channel c, jbssdm.pegawai pg, jbsakad.pelajaran pl
         WHERE m.idchannel = c.id
           AND c.nip = pg.nip
           AND c.idpelajaran = pl.replid
           AND m.id = $idMedia";
$res = QueryDb($sql);
if ($row = mysqli_fetch_array($res))
{
    $judul = $row['judul'];
    $deskripsi = $row['deskripsi'];
    $objektif = $row['objektif'];
    $pertanyaan = $row['pertanyaan'];
    $idKategori = $row['idkategori'];
    $channel = $row['channel'];
    $guru = $row['guru'];
    $pelajaran = $row['pelajaran'];
    $videoLoc = $row['videoloc'];
    $videoName = $row['videoname'];

    $videoUrl = UrlCombine($FILESHARE_ADDR, $videoLoc);
    $videoUrl = UrlCombine($videoUrl, $videoName);
}

?>
<!DOCTYPE HTML PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <title>JIBAS School Tube: <?= $judul ?></title>

    <link href="images/jibas2015.ico" rel="shortcut icon" />
    <link href="script/jquery-ui/jquery-ui.min.css" type="text/css" media="screen" rel="stylesheet" />
    <link href="style/mainstyle.css" rel="stylesheet" />
    <link href="index.css" rel="stylesheet" />

    <script type="text/javascript" src="script/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="script/jquery-ui/jquery-ui.min.js"></script>

    <script type="text/javascript" src="show.media.js?r=<?=filemtime('show.media.js')?>"></script>

</head>

<body style="padding: 0; margin: 0">
<table border="0" cellspacing="0" cellpadding="0" width="100%" style="background-color: #ececec;">
<tr><td align="center" valign="top">

<table border="0" cellspacing="0" cellpadding="10" width="1100"  style="background-color: #fff;">
<tr><td align="left" valign="top" width="1100">

<span style="font-family: Arial; font-size: 24px"><?= $judul ?></span><br>
<span style="color: blue"><?= " $pelajaran | $channel | $guru?" ?></span>
<br><br>

<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
    <td colspan="4" align="center">
        <video id="video" onerror="failed(event)" controls="controls"
               src="<?=$videoUrl?>"
               style="height: 480px; width: 100%; background-color: #222"></video>
    </td>
</tr>
<tr style="height: 30px">
    <td width="5%" align="center" valign="middle" style="font-size: 14px">
        <span id="spLikeButton">
<?php   ShowLikeButton($idMedia) ?>
        </span>
    </td>
    <td width="10%" align="center" valign="middle" style="font-size: 14px">
        Like:
        <span id="spLikeCount">
<?php   ShowLikeCount($idMedia)?>
        </span>
    </td>
    <td width="10%" align="center" valign="middle" style="font-size: 14px">
        View:
        <span id="spViewCount">
<?php   ShowViewCount($idMedia) ?>
        </span>
    </td>
    <td width="*" align="right">
        &nbsp;
    </td>
</tr>
</table>
<br><br>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1" style="color: blue"><strong>Informasi</strong></a></li>
        <li><a href="#tabs-2" style="color: blue"><strong>File Lampiran (<?=ShowCountFiles($idMedia)?>)</strong></a></li>
        <li><a href="#tabs-3" style="color: blue"><strong>Catatan Personal<span id="spCountNotes"> (<?=ShowCountNotes($idMedia)?>)</strong></span></a></li>
    </ul>
    <div id="tabs-1" style="height: 300px; overflow: auto">
<?php ShowMediaInfo() ?>
    </div>
    <div id="tabs-2" style="height: 300px; overflow: auto">
    <span style="font-size: 12px; font-weight: bold">File Lampiran:</span><br><br>
<?php ShowMediaFiles($idMedia) ?>
    </div>
    <div id="tabs-3" style="height: 300px;">
<?php ShowMediaNotes($idMedia) ?>
    </div>
</div>

</td></tr>
</table>

<br><br><br><br>

</td></tr>
</table>


</body>
</html>