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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/compatibility.php');
require_once('pustaka.config.php');
require_once('pustaka.stat.func.php');

$op = $_REQUEST['op'];
if ($op == "showstat")
{
    OpenDb();
    try
    {
        $perpus = $_REQUEST['perpus'];
        $bln1 = $_REQUEST['bln1'];
        $thn1 = $_REQUEST['thn1'];
        $bln2 = $_REQUEST['bln2'];
        $thn2 = $_REQUEST['thn2'];
        $jum = $_REQUEST['jum'];

        ShowStatistics($perpus, $bln1, $thn1, $bln2, $thn2, $jum);
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
}
elseif($op == "getdetail")
{
    OpenDb();
    try
    {
        $cnt = $_REQUEST['cnt'];
        $idpustaka = $_REQUEST['idpustaka'];
        
        ShowDetailPustaka($cnt, $idpustaka);
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
}
elseif ($op == "hidedetail")
{
    $cnt = $_REQUEST['cnt'];
    $idpustaka = $_REQUEST['idpustaka'];
        
    echo "<a style='color: #0000ff; font-size: 9px; text-decoration: underline' onclick='ptkastat_showdetail($cnt, $idpustaka)'>";
    echo "detail";
    echo "</a>";
    
    http_response_code(200);
}
?>