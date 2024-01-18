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
require_once("include/config.php");
require_once("../include/cbe.config.php");
require_once("include/session.php");
require_once("include/db_functions.php");
require_once("library/genericreturn.php");
require_once("library/httprequest.php");
require_once("library/httpmanager.php");
require_once("library/cbe.state.php");
require_once("library/cbe.system.php");
require_once("library/cbe.session.php");
require_once("library/cbe.protocol.php");
require_once("library/debugger.php");
require_once("common.func.php");

function checkSize($path)
{
    $totSize = 0;

    $files = glob($path . '*', GLOB_MARK);
    foreach($files as $file)
    {
        if (is_dir($file))
            continue;

        $totSize += filesize($file);
    }

    return formatBytes($totSize, 2);
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = ['', 'KB', 'MB', 'GB', 'TB'];

    return round(1024 ** ($base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

function getResDir()
{
    return __DIR__ . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR;
}

function delResDir($path)
{
    if (!str_contains((string) $path, (string) getResDir()))
        return "NOOP";

    $files = glob($path . '*', GLOB_MARK);
    foreach($files as $file)
    {
        if (is_dir($file))
            continue;

        unlink($file);
    }
    rmdir($path);

    return "OK";
}

function showResDir()
{
    $result = "<ul style='line-height: 22px;'>";

    $no = 0;
    $resDir = getResDir();
    $currDir = date('Ym');
    $files = glob($resDir . '*', GLOB_MARK);
    foreach($files as $file)
    {
        if (!is_dir($file))
            continue;

        $result .= "<li>";
        $result .= "<input type='hidden' id='dir-$no' value='$file'>";
        $result .= $file;
        $result .= "&nbsp;&nbsp;&nbsp;&nbsp;<span id='spSize-$no' style='cursor: pointer; color: blue; text-decoration: underline' onclick='ru_checkSize($no)'>check size</span>";
        if (!str_contains($file, $currDir))
            $result .= "&nbsp;&nbsp;<span id='spDel-$no' style='cursor: pointer; color: red; text-decoration: underline' onclick='ru_delResDir($no)'>hapus</span>";
        else
            $result .= "&nbsp;&nbsp;aktif";
        $resDir .= "</li>";

        $no += 1;
    }
    $result .= "</ul>";

    return $result;
}
?>
