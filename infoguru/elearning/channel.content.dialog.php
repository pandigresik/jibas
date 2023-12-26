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

$userId = SI_USER_ID();

$idchannel = $_REQUEST['idchannel'];
$dept = $_REQUEST["dept"];
$idpel = $_REQUEST["idpel"];

OpenDb();

$title = "Channel E-Learning Baru";

$judul = "";
$deskripsi = "";
$urutan = "";
$aktif = 1;

if ($idchannel != 0)
{
    $title = "Ubah Data Channel E-Learning";

    $sql = "SELECT judul, deskripsi, urutan, aktif
              FROM jbsel.channel
             WHERE id = $idchannel";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $judul = $row[0];
        $deskripsi = $row[1];
        $urutan = $row[2];
        $aktif = (int) $row[3];
    }
}

$aktif_checked = $aktif == 1 ? "checked" : "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$title?></title>
    <script language="javascript" src="../script/jquery-1.9.1.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/validatorx.js"></script>
    <script language="javascript" src="channel.content.dialog.js"></script>
</head>

<body topmargin="10" leftmargin="10">
<br>
<span style="font-size: 14px; font-weight: bold; font-family: Verdana"><?=$title?></span>
<br><br>

<form action="channel.content.dialog.save.php" method="post" onsubmit="return ch_Validate()">
<input type="hidden" id="idchannel" name="idchannel" value="<?=$idchannel?>">
<input type="hidden" id="dept" name="dept" value="<?=$dept?>">
<input type="hidden" id="idpel" name="idpel" value="<?=$idpel?>">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
    <td width="100" align="right">Judul:</td>
    <td width="500" align="left">
        <input type="text" id="judul" name="judul" value="<?=$judul?>" style="font-size: 12px; height: 25px; width: 400px;" maxlength="255">
    </td>
</tr>
<tr>
    <td width="100" align="right">Urutan:</td>
    <td width="500" align="left">
        <input type="text" id="urutan" name="urutan" value="<?=$urutan?>" style="font-size: 12px; height: 25px; width: 50px;" maxlength="3">
    </td>
</tr>
<tr>
    <td width="100" align="right" valign="top">Deskripsi:</td>
    <td width="500" align="left">
        <textarea id="deskripsi" name="deskripsi" rows="5" cols="60" style="font-size: 12px;"><?=$deskripsi?></textarea>
    </td>
</tr>
<tr>
    <td width="100" align="right" valign="top">&nbsp;</td>
    <td width="500" align="left">
        <input type="submit" id="Simpan" name="Simpan" value="Simpan" class="but" style="height: 25px;">
        <input type="button" value="Tutup" class="but" style="height: 25px;">
    </td>
</tr>
</table>
</form>

</body>
</html>

<?php
CloseDb();
?>
