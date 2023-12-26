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
require_once('school.config.php');
require_once('version.config.php');
require_once('httprequest.php');

session_name("jbsmain");
session_start();

$lid = (int)$_REQUEST["lid"];
$_SESSION['lugetlid'] = $lid;

if ($lid == -1)
{
    $_SESSION['lugetstatus'] = false;
    $_SESSION['lugetmessage'] = "";
    
    echo "<span style='color:red; font-weight:normal;'>Tidak terhubung dengan database JIBAS</span>";
}
else
{
    $client = urlencode($G_JUDUL_DEPAN_1);
    $subtitle = urlencode($G_JUDUL_DEPAN_2);
    $version = urlencode($G_VERSION);

    $content = http_request("GET", "liveupdate.jibas.net", 80, "/getlustatus.php?lid=$lid&client=$client&subtitle=$subtitle&version=$version");       
    $pos1 = strpos($content, "[", 0);
    if ($pos1 !== FALSE)
    {
        $pos2 = strpos($content, "]", $pos1);
        if ($pos2 !== FALSE)
        {
            $response = substr($content, $pos1 + 1, $pos2 - $pos1 - 1);
            $header = substr($response, 0, 3);
            
            if ($header == "400")
            {
                $content = substr($response, 3);
                
                $_SESSION['lugetstatus'] = true;
                $_SESSION['lugetmessage'] = $content;
            }
            else
            {
                $content = "<span style='color:#CCCCCC; font-weight:normal;'>Tidak terhubung dengan JIBAS LiveUpdate</span>";
                
                $_SESSION['lugetstatus'] = false;
                $_SESSION['lugetmessage'] = "";
            }
        }
    }
    else
    {
        $content = "<span style='color:#CCCCCC; font-weight:normal;'>Tidak terhubung dengan JIBAS LiveUpdate</span>";
        
        $_SESSION['lugetstatus'] = false;
        $_SESSION['lugetmessage'] = "";
    }
    
    echo $content;   
}
?>