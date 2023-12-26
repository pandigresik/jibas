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
require_once('multitrans.content.paymentinfo.func.php');

OpenDb();
try
{
    $jenis = $_REQUEST['jenis'];
    $departemen = $_REQUEST['departemen'];
    $noid = $_REQUEST['noid'];
    $idtahunbuku = $_REQUEST['idtahunbuku'];
    $kelompok = $_REQUEST['kelompok'];
    $idpayment = $_REQUEST['idpayment'];
    
    if ($jenis == "JTT")
        InfoWjbSiswa();
    elseif ($jenis == "SKR")
        InfoSkrSiswa();
    elseif ($jenis == "CSWJB")
        InfoWjbCalonSiswa();
    elseif ($jenis == "CSSKR")
        InfoSkrCalonSiswa();
    
    CloseDb();
    http_response_code(200);
}
catch(DbException $dbe)
{
    CloseDb();
    
    http_response_code(500);
    echo $dbe->getMessage();
}
catch(Exception $e)
{
    CloseDb();
    
    http_response_code(500);
    echo $e->getMessage();
}
?>