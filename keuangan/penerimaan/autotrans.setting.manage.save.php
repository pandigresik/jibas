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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/compatibility.php');

function SafeText($text)
{
    $text = str_replace("'", "`", (string) $text);
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace(">", "&gt;", $text);

    return $text;
}

$idAutoTrans = $_REQUEST["idautotrans"];
$departemen = $_REQUEST["departemen"];
$judul = SafeText($_REQUEST["judul"]);
$kelompok = $_REQUEST["kelompok"];
$urutan = $_REQUEST["urutan"];
$keterangan = SafeText($_REQUEST["keterangan"]);
$smsinfo = isset($_REQUEST["smsinfo"]) ? 1 : 0;

$temp = str_replace("`", "\"", (string) $_REQUEST["lsPenerimaan"]);
$lsPenerimaan = json_decode($temp, null, 512, JSON_THROW_ON_ERROR);

OpenDb();

$success = true;
BeginTrans();

if ($idAutoTrans != 0)
{
    $sql = "UPDATE jbsfina.autotrans 
               SET judul = '$judul', aktif = 1, kelompok = $kelompok,
                   urutan = $urutan, keterangan = '$keterangan', smsinfo = $smsinfo 
             WHERE replid = $idAutoTrans";
    QueryDbTrans($sql, $success);
}
else
{
    $sql = "INSERT INTO jbsfina.autotrans 
               SET judul = '$judul', aktif = 1, kelompok = $kelompok, smsinfo = $smsinfo,  
                   urutan = $urutan, keterangan = '$keterangan', departemen = '".$departemen."'";
    QueryDbTrans($sql, $success);

    if ($success)
    {
        $sql = "SELECT LAST_INSERT_ID()";
        $res = QueryDbTrans($sql, $success);
        $row = mysqli_fetch_row($res);
        $idAutoTrans = $row[0];
    }
}

for($i = 0; $success && $i < count($lsPenerimaan); $i++)
{
    $info = $lsPenerimaan[$i];
    $keterangan = SafeText($info->Keterangan);

    if ($info->Hapus == 1 && $info->IdData != 0)
    {
        $sql = "DELETE FROM jbsfina.autotransdata 
                 WHERE replid = $info->IdData";
        QueryDbTrans($sql, $success);
    }
    else if ($info->Hapus == 0)
    {
        if ($info->IdData == 0)
        {
            $sql = "INSERT INTO jbsfina.autotransdata 
                       SET idautotrans = $idAutoTrans, idpenerimaan = $info->IdPenerimaan, 
                           besar = $info->Besar, aktif = $info->Aktif, urutan = $info->Urutan, 
                           keterangan = '".$keterangan."'";
            QueryDbTrans($sql, $success);
        }
        else
        {
            $sql = "UPDATE jbsfina.autotransdata
                       SET besar = $info->Besar, aktif = $info->Aktif, 
                           urutan = $info->Urutan, keterangan = '$keterangan'
                     WHERE replid = $info->IdData";
            QueryDbTrans($sql, $success);
        }
    }

}

if ($success)
{
    CommitTrans();
    echo "<script>";
    echo "window.opener.refreshPage();";
    echo "window.close();";
    echo "</script>";
}
else
{
    echo "Gagal menyimpan data: ROLLBACK";
    RollbackTrans();
}

CloseDb();
?>