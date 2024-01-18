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
$G_ROW_PER_PAGE = 5;

function SafeInputText($text)
{
    $text = trim((string) $text);
    $text = str_replace("'", "`", $text);
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace(">", "&gt;", $text);

    return $text;
}

function SafeFileName($name)
{
    $name = trim((string) $name);

    $name = str_replace("'", "`", $name);
    $name = str_replace("<", "", $name);
    $name = str_replace(">", "", $name);
    $name = str_replace("*", "", $name);
    $name = str_replace("?", "", $name);
    $name = str_replace(":", "", $name);
    $name = str_replace("\"", "", $name);
    $name = str_replace("\\", "", $name);
    $name = str_replace("/", "", $name);
    $name = str_replace("|", "", $name);
    $name = str_replace(" ", "_", $name);

    return $name;
}

function ProperPath($path)
{
    return str_replace("/", DIRECTORY_SEPARATOR, (string) $path);
}

function PathCombine($dir1, $dir2)
{
    return $dir1 . DIRECTORY_SEPARATOR . $dir2;
}

function UrlCombine($path1, $path2)
{
    return $path1 . "/" . $path2;
}

function UrlToPath($url)
{
    return str_replace("/", DIRECTORY_SEPARATOR, (string) $url);
}


?>