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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/compatibility.php");
require_once("../library/datearith.php");

OpenDb();
try
{
    $replid = (int)$_REQUEST['replid'];
    $nip = $_REQUEST['nip'];
    $jammasuk = DateArith::FormatDigit($_REQUEST['jammasuk']);
    $menitmasuk = DateArith::FormatDigit($_REQUEST['menitmasuk']);
    $jampulang = DateArith::FormatDigit($_REQUEST['jampulang']);
    $menitpulang = DateArith::FormatDigit($_REQUEST['menitpulang']);
    $keterangan = $_REQUEST['keterangan'];
	$keterangan = str_replace("'", "`", (string) $keterangan);
	$tglpresensi = $_REQUEST['tglpresensi'];
    
	$jmasuk = "";
	$jpulang = "";
	$jkerja = 0;
	$mkerja = 0;
	$dkerja = 0;
	$jmasuk = "$jammasuk:$menitmasuk:00";
	$jpulang = "$jampulang:$menitpulang:00";
	DateArith::TimeDiff($jmasuk, $jpulang, $jkerja, $mkerja, $dkerja);	
    
	$sql = "UPDATE jbssdm.presensi
			   SET nip = '$nip', tanggal = '$tglpresensi',
				   jammasuk = '$jmasuk', jampulang = '$jpulang',
				   jamwaktukerja = '$jkerja', menitwaktukerja = '$mkerja',
				   keterangan = '$keterangan'
			 WHERE replid = '".$replid."'";
	QueryDbEx($sql);		 
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