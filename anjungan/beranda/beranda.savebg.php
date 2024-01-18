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
require_once("beranda.session.php");
require_once("beranda.security.php");

$bgList = [];
$delay = $_REQUEST['delay'];

$nkeep = $_REQUEST['nkeep'];
for($i = 0; $i < $nkeep; $i++)
{
    $parm = "keeplist$i";
    $bgList[] = $_REQUEST[$parm];
}

$ndelete = $_REQUEST['ndelete'];
for($i = 0; $i < $ndelete; $i++)
{
    $parm = "dellist$i";
    $gambar = $_REQUEST[$parm];
    
    $gambar = "../images/background/$gambar";
    if (file_exists($gambar))
    {
        echo "DEL $gambar<br>";
        unlink($gambar);
    }    
}

foreach($_FILES as $file)
{
    $name = $file['name'];
    $dest = "../images/background/$name";
    
    echo "move $name to $dest<br>";
    move_uploaded_file($file['tmp_name'], $dest);
    
    $bgList[] = $name;
}

echo "BGLIST<br>";
$list = "";
$jslist = "";
for($i = 0; $i < count($bgList); $i++)
{
    echo $bgList[$i] . "<br>";
    
    if ($list != "")
        $list .= ", ";
    
    if ($jslist != "")
        $jslist .= ", ";    
    
    $list .= "'" . $bgList[$i] . "'";
    $jslist .= "'images/background/" . $bgList[$i] . "'";    
}

$content  = "<?\r\n";
$content .= '$bgDelay = ' . $delay . ';';
$content .= "\r\n";
$content .= '$bgList = array(' . $list . ');';
$content .= "\r\n";
$content .= "?>\r\n";
file_put_contents('beranda.listbg.php', $content);

$msdelay = $delay * 1000;
$content  = "var b_bgDelay = $msdelay;\r\n";
$content .= "var b_bgList = [$jslist];\r\n";
file_put_contents('beranda.listbg.js', $content);
?>