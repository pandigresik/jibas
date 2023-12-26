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
require_once("notes.content.func.php");

$userId = SI_USER_ID();

$departemen = $_REQUEST["departemen"];
$idPelajaran = $_REQUEST["idPelajaran"];
$idChannel = $_REQUEST["idChannel"];
$date1 = $_REQUEST["date1"] . " 00:00:00";
$date2 = $_REQUEST["date2"] . " 23:59:59";

OpenDb();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Catatan</title>
    <script language="javascript" src="../script/jquery-1.9.1.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="notes.content.js?m=<?=filemtime("notes.content.js")?>"></script>
</head>

<body topmargin="10" leftmargin="10">
<br>

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idPelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="idChannel" value="<?=$idChannel?>">
<input type="hidden" id="date1" value="<?=$date1?>">
<input type="hidden" id="date2" value="<?=$date2?>">

<?php
$selIdMedia = 0;
ShowCbMedia();
?>
<br><br>
<table id="tableNotes" border="1" cellspacing="0" cellpadding="10" style="border-width: 1px; border-collapse: collapse; border-color: #666;" width="930">
<thead>
<tr style="height: 25px;">
    <td class="header" width="40" align="center">No</td>
    <td class="header" width="200">Identitas</td>
    <td class="header" width="600">Catatan</td>
</tr>
</thead>
<tbody id="divNotes">
<?php
ShowDaftarCatatan($selIdMedia, $date1, $date2);
?>
</tbody>
</table>

</body>
</html>
<?php
CloseDb();
?>
