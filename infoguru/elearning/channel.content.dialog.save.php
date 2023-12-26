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
require_once("common.func.php");

$userId = SI_USER_ID();

$idchannel = $_REQUEST['idchannel'];
$dept = $_REQUEST["dept"];
$idpel = $_REQUEST["idpel"];

$judul = SafeInputText($_REQUEST["judul"]);
$urutan = trim($_REQUEST["urutan"]);
$deskripsi = SafeInputText($_REQUEST["deskripsi"]);

if ($idchannel == 0)
{
    $sql = "INSERT INTO jbsel.channel
               SET nip = '$userId', idpelajaran = $idpel, judul = '$judul', tanggal = NOW(), 
                   urutan = $urutan, aktif = 1, deskripsi = '$deskripsi'";
}
else
{
    $sql = "UPDATE jbsel.channel
               SET judul = '$judul', urutan = $urutan, deskripsi = '$deskripsi'
             WHERE id = $idchannel";
}

OpenDb();
QueryDb($sql);
CloseDb();
?>
<script language="JavaScript">
    opener.ch_refresh();
    window.close();
</script>
<?php
exit();
?>
