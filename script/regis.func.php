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
function ToGenericReturn($value, $message, $data)
{
    $list = array($value, $message, $data);
    return json_encode($list);
}

function FromGenericReturn($json)
{
    return json_decode($json);
}

function XorDecrypt($base64, $key)
{
    $result = "";

    $text = base64_decode($base64);
    $textLen = strlen($text);

    $sKey = $key . "";
    $keyLen = strlen($sKey);

    for($i = 0; $i < $textLen; $i++)
    {
        $result .= $text[$i] ^ $sKey[$i % $keyLen];
    }

    return $result;
}

function XorEncrypt($text, $key)
{
    $result = "";

    $sKey = $key . "";
    $keyLen = strlen($sKey);

    $textLen = strlen($text);
    for($i = 0; $i < $textLen; $i++)
    {
        $result .= $text[$i] ^ $sKey[$i % $keyLen];
    }

    return base64_encode($result);
}

function TryParseJson($json, &$success)
{
    $data = json_decode($json);
    if (JSON_ERROR_NONE !== json_last_error())
    {
        $success = false;
        return null;
    }

    $success = true;
    return $data;
}
?>