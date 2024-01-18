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
require_once ("../include/cbe.config.php");

function sendConnectError($message)
{
    global $CBE_SERVER;

    $info  = "<strong>GAGAL KONEKSI CBE SERVER</strong>:<br> <i>$message</i><br><br>";
    $info .= "Tidak dapat menghubungi JIBAS CBE Server di <strong>$CBE_SERVER</strong><br><br>";
    $info .= "Periksa apakah JIBAS CBE Server aktif dan berjalan, konfigurasinya telah benar dan tidak terhalang firewall. ";
    $info .= "Atur kembali konfigurasi CBE Server di file <strong>cbe.config.php</strong>.";

    $return = new GenericReturn(-99, $info, "");

    return $return->toJson();
}

function sendCbeServerError($message)
{
    $info  = "<strong>CBE SERVER ERROR</strong>:<br> <i>$message</i><br><br>";
    $info .= "Hubungi Administrator JIBAS untuk informasi lebih lanjut.";

    $return = new GenericReturn(-99, $info, "");

    return $return->toJson();
}

function sendCbeServerInfo($message)
{
    $info  = "<strong>CBE SERVER</strong>:<br> <i>$message</i><br><br>";
    $info .= "Hubungi Administrator JIBAS untuk informasi lebih lanjut.";

    $return = new GenericReturn(-99, $info, "");

    return $return->toJson();
}

function sendConnectDbError($message)
{
    global $db_host;

    $info  = "<strong>GAGAL KONEKSI DATABASE</strong>:<br> <i>$message</i><br><br>";
    $info .= "Tidak dapat menghubungi Database JIBAS di <strong>$db_host</strong><br><br>";
    $info .= "Periksa apakah Database JIBAS aktif dan berjalan, konfigurasinya telah benar dan tidak terhalang firewall. ";
    $info .= "Atur kembali konfigurasi Database JIBAS di file <strong>database.config.php</strong>.";

    $return = new GenericReturn(-99, $info, "");

    return $return->toJson();
}

function nlToBr($string)
{
    return str_replace("\n",  "<br>", (string) $string);
}
?>