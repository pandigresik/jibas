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
require_once("../include/config.php");
require_once("../include/common.php");
require_once("../include/db_functions.php");
require_once("../library/datearith.php");
require_once("infosiswa.profile.func.php");

$op = $_REQUEST['op'];
if ($op == "setTglLahirSiswa")
{
    $y = $_REQUEST['y'];
    $m = $_REQUEST['m'] ?? date('j');
    $d = $y == date('Y') && $m == date('n') ? date('j') : 1;
    
    ShowDateCombo('is_tgllahir', 'is_changeTanggalLahirSiswa()', $y, $m, $d); 
}
elseif ($op == "setTglLahirAyah")
{
    $y = $_REQUEST['y'];
    $m = $_REQUEST['m'] ?? date('j');
    $d = $y == date('Y') && $m == date('n') ? date('j') : 1;
    
    ShowDateCombo('is_tgllahirayah', 'is_changeTanggalLahirAyah()', $y, $m, $d); 
}
elseif ($op == "setTglLahirIbu")
{
    $y = $_REQUEST['y'];
    $m = $_REQUEST['m'] ?? date('j');
    $d = $y == date('Y') && $m == date('n') ? date('j') : 1;
    
    ShowDateCombo('is_tgllahiribu', 'is_changeTanggalLahirIbu()', $y, $m, $d); 
}
elseif ($op == "setAsalSekolah")
{
    $jenjang = $_REQUEST['jenjang'];
    
    OpenDb();
    ShowAsalSekolahCombo($jenjang);
    CloseDb();
}
?>