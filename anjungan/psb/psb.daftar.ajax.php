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
require_once("../include/compatibility.php");
require_once("psb.daftar.func.php");

$op = $_REQUEST['op'];
if ($op == "getDaftarPsb")
{
    $idkelompok = $_REQUEST['idkelompok'];
    $page = $_REQUEST['page'] ?? 1;
        
    OpenDb();    
    ShowDaftarPsb($idkelompok, $page);
    CloseDb();
}
elseif ($op == "doChangeData")
{
    $nocalon = urldecode((string) $_REQUEST['nocalon']);
    $namacalon = urldecode((string) $_REQUEST['namacalon']);
    $idkelompok = $_REQUEST['idkelompok'];
    $page = $_REQUEST['page'];
    $npage = $_REQUEST['npage'];
    
    ShowFormUbahData($nocalon, $namacalon, $idkelompok, $page, $npage);
}
elseif ($op == "setProsesPsb")
{
    $dept = $_REQUEST['dept'];
    
    OpenDb();
    ShowPenerimaanCombo($dept);
    CloseDb();
}
elseif ($op == "setKelompokPsb")
{
    $proses = $_REQUEST['proses'];
    
    OpenDb();
    ShowKelompokCombo($proses);
    CloseDb();
}
elseif ($op == "doCheckPin")
{
    $nocalon = urldecode((string) $_REQUEST['nocalon']);
    $namacalon = urldecode((string) $_REQUEST['namacalon']);
    $idkelompok = $_REQUEST['idkelompok'];
    $page = $_REQUEST['page'];
    $npage = $_REQUEST['npage'];
    $pincalon = $_REQUEST['pincalon'];
    
    OpenDb(); 
    if (PinIsValid($nocalon, $pincalon))
    {
        CloseDb();
        
        http_response_code(200);
        include("psb.edit.php");
    }
    else
    {
        CloseDb();
        http_response_code(500);
        echo "PIN Calon Siswa tidak sesuai!";    
    }
}
?>