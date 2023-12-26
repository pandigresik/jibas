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
require_once('../include/errorhandler.php');
require_once('../include/sessionchecker.php');
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once("../library/datearith.php");

/*
foreach($_REQUEST as $key => $value)
{
    echo $key . " = " . $value . "<br>";
}
*/

OpenDb();
try
{
    $success = true;
    BeginTrans();
    
    $nflagrow = $_REQUEST['nflagrow'];
    for($i = 0; $success && $i < $nflagrow; $i++)
    {
        $flagrow = "flagrow$i";
        if ($_REQUEST[$flagrow] == 0)
            continue;
        
        $key = "i_nip$i";
        $nip = $_REQUEST[$key];
        $key = "i_tahun$i";
        $tahun = $_REQUEST[$key];
        $key = "i_bulan$i";
        $bulan = $_REQUEST[$key];
        $key = "i_tanggal$i";
        $tanggal = $_REQUEST[$key];
        $key = "i_jammasuk$i";
        $jammasuk = DateArith::FormatDigit($_REQUEST[$key]);
        $key = "i_menitmasuk$i";
        $menitmasuk = DateArith::FormatDigit($_REQUEST[$key]);
        $key = "i_jampulang$i";
        $jampulang = DateArith::FormatDigit($_REQUEST[$key]);
        $key = "i_menitpulang$i";
        $menitpulang = DateArith::FormatDigit($_REQUEST[$key]);
        $key = "i_keterangan$i";
        $keterangan = $_REQUEST[$key];
    
        $tglpresensi = "$tahun-$bulan-$tanggal";
        $jmasuk = "$jammasuk:$menitmasuk:00";
        $jpulang = "$jampulang:$menitpulang:00";
        
        $jkerja = 0;
        $mkerja = 0;
        $dkerja = 0;
        DateArith::TimeDiff($jmasuk, $jpulang, $jkerja, $mkerja, $dkerja);
        
        $sql = "INSERT INTO jbssdm.presensi
                   SET nip = '$nip', tanggal = '$tglpresensi',
                       jammasuk = '$jmasuk', jampulang = '$jpulang',
                       jamwaktukerja = '$jkerja', menitwaktukerja = '$mkerja',
                       status = '1', keterangan = '$keterangan', source='LEMBUR'";
        //echo "$sql<br>";
        QueryDbTrans($sql, $success);
    }
    
    if ($success)
    {
        CommitTrans();
        CloseDb();
        
        echo "<script>\r\n";
        echo "opener.refreshPage();\r\n";
        echo "window.close();\r\n";
        echo "</script>\r\n";
        exit();
    }
    else
    {
        RollbackTrans();
        CloseDb();
        
        echo "<script>\r\n";
        echo "alert('Gagal menyimpan data!');\r\n";
        echo "</script>\r\n";
        exit();
    }
}
catch(DbException $dbe)
{
    CloseDb();
    
    //http_response_code(500);
    trigger_error($dbe->getMessage(), E_USER_ERROR);
}
catch(Exception $e)
{
    CloseDb();
    
    //http_response_code(500);
    trigger_error($e->getMessage(), E_USER_ERROR);
}
?>