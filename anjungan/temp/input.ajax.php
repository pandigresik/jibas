<?php
require_once("../include/config.php");
require_once("../include/common.php");
require_once("../include/db_functions.php");
require_once("../library/datearith.php");
require_once("input.func.php");

$op = $_REQUEST['op'];
if ($op == "setTglLahirSiswa")
{
    $y = $_REQUEST['y'];
    $m = $_REQUEST['m'] ?? date('j');
    $d = $y == date('Y') && $m == date('n') ? date('j') : 1;
    
    ShowDateCombo('tgllahir', 'changeTanggalLahirSiswa()', $y, $m, $d); 
}
elseif ($op == "setTglLahirAyah")
{
    $y = $_REQUEST['y'];
    $m = $_REQUEST['m'] ?? date('j');
    $d = $y == date('Y') && $m == date('n') ? date('j') : 1;
    
    ShowDateCombo('tgllahirayah', 'changeTanggalLahirAyah()', $y, $m, $d); 
}
elseif ($op == "setTglLahirIbu")
{
    $y = $_REQUEST['y'];
    $m = $_REQUEST['m'] ?? date('j');
    $d = $y == date('Y') && $m == date('n') ? date('j') : 1;
    
    ShowDateCombo('tgllahiribu', 'changeTanggalLahirIbu()', $y, $m, $d); 
}
elseif ($op == "setAsalSekolah")
{
    $jenjang = $_REQUEST['jenjang'];
    
    OpenDb();
    ShowAsalSekolahCombo($jenjang);
    CloseDb();
} 
?>