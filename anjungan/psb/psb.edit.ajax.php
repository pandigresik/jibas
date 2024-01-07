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
require_once("../include/rupiah.php");
require_once("../include/compatibility.php");
require_once("psb.edit.func.php");

$op = $_REQUEST['op'];
if ($op == "setProsesPsb")
{
    $dept = $_REQUEST['dept'];
    
    OpenDb();
    ShowPenerimaanCombo2($dept);
    CloseDb();
}
elseif ($op == "setKelompokPsb")
{
    $proses = $_REQUEST['proses'];
    
    OpenDb();
    ShowKelompokCombo2($proses);
    CloseDb();
}
elseif ($op == "setSumbanganPsb")
{
    $proses = $_REQUEST['proses'];
    $no = $_REQUEST['no'];
    
    OpenDb();
    
    $sql = "SELECT sum1, sum2
              FROM jbsakad.calonsiswa
             WHERE nopendaftaran = '".$no."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_array($res);
    
    ShowSumbangan($proses, $row);
    CloseDb();
}
elseif ($op == "setUjianPsb")
{
    $proses = $_REQUEST['proses'];
    $no = $_REQUEST['no'];
    
    OpenDb();
    
    $sql = "SELECT ujian1, ujian2, ujian3, ujian4, ujian5,
                   ujian6, ujian7, ujian8, ujian9, ujian10
              FROM jbsakad.calonsiswa
             WHERE nopendaftaran = '".$no."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_array($res);
    
    ShowNilaiUjian($proses, $row);
    CloseDb();
}
?>