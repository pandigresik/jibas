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
 function ReadRequest()
 {
    global $idtabungan, $idangkatan, $idtingkat, $idkelas, $departemen;
    global $varbaris, $page, $hal, $urut, $urutan;
    
    if (isset($_REQUEST['idtabungan']))
        $idtabungan = (int)$_REQUEST['idtabungan'];
        
    if (isset($_REQUEST['idangkatan']))
        $idangkatan = (int)$_REQUEST['idangkatan'];
    
    if (isset($_REQUEST['idtingkat']))
        $idtingkat = (int)$_REQUEST['idtingkat'];
        
    if (isset($_REQUEST['idkelas']))
        $idkelas = (int)$_REQUEST['idkelas'];
    
    if (isset($_REQUEST['departemen']))
        $departemen = $_REQUEST['departemen'];
    
    $varbaris=10;
    if (isset($_REQUEST['varbaris']))
        $varbaris = $_REQUEST['varbaris'];
    
    $page=0;
    if (isset($_REQUEST['page']))
        $page = $_REQUEST['page'];
        
    $hal=0;
    if (isset($_REQUEST['hal']))
        $hal = $_REQUEST['hal'];
    
    $urut = "nama";	
    if (isset($_REQUEST['urut']))
        $urut = $_REQUEST['urut'];	
    
    $urutan = "ASC";	
    if (isset($_REQUEST['urutan']))
        $urutan = $_REQUEST['urutan'];
 }
 
 function CheckTahunBuku()
 {
    global $departemen, $idtahunbuku;
    
    $sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        CloseDb();
        
        echo "<script>";
        echo "alert ('Belum ada Tahun buku yang Aktif di departemen ".$departemen.". Silakan isi/aktifkan Tahun Buku di menu Referensi!');";
        echo "</script>";
        
        exit();
    }
    $row = mysqli_fetch_row($res);
    $idtahunbuku = $row[0];
 }
 
 function GetNames()
 {
    global $idtabungan, $idkelas, $idtingkat, $idangkatan;
    global $namatabungan, $namakelas, $namatingkat, $namaangkatan;
    
    // -- Dapatkan namapenerimaan --------------------------------------
    $sql = "SELECT d.nama FROM datatabungan d WHERE d.replid=$idtabungan";
    $row = FetchSingleRow($sql);
    $namatabungan = $row[0];
        
    if ($idkelas <> -1) 
    {
        $sql = "SELECT kelas FROM jbsakad.kelas WHERE replid=$idkelas";
        $namakelas = FetchSingle($sql);
    }
    else
    {
        $namakelas = "(SEMUA KELAS)";
    }
    
    if ($idtingkat <> -1)
    {
        $sql = "SELECT tingkat FROM jbsakad.tingkat WHERE replid=$idtingkat";
        $namatingkat = FetchSingle($sql);
    }
    else
    {
        $namatingkat = "(SEMUA TINGKAT)";
    }
    
    $sql = "SELECT angkatan FROM jbsakad.angkatan WHERE replid=$idangkatan";
    $namaangkatan = FetchSingle($sql);
 }
 ?>