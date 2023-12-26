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
require_once('../inc/errorhandler.php');
require_once('../inc/sessioninfo.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/compatibility.php');
require_once('../inc/db_functions.php');
require_once('../lib/departemen.php');
require_once('presensikeg.rekapguru.func.php');

try
{
    OpenDb();
    
    $op = $_REQUEST['op'];
    if ($op == "getpegawai")
    {
        $bulan = $_REQUEST['bulan'];
        $tahun = $_REQUEST['tahun'];
        $bagian = $_REQUEST['bagian'];
        
        echo GetPegawai($bulan, $tahun, $bagian);
    }
    else if ($op == "searchpegawai")
    {
        $bulan = $_REQUEST['bulan'];
        $tahun = $_REQUEST['tahun'];
        $filter = $_REQUEST['filter'];
        $keyword = $_REQUEST['keyword'];
        
        echo SearchPegawai($bulan, $tahun, $filter, $keyword);
    }
    
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