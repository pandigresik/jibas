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
function ShowInfoSiswa()
{
    global $noid, $nama, $kelompok;
    
    if ($kelompok == "siswa")
    {
        $sql = "SELECT replid FROM jbsakad.siswa WHERE nis = '".$noid."'";
        $table = "jbsakad.siswa";
    }
    else
    {
        $sql = "SELECT replid FROM jbsakad.calonsiswa WHERE nopendaftaran = '".$noid."'";
        $table = "jbsakad.calonsiswa";
    }
    $replid = FetchSingle($sql);
    ?>
    
    <center>
    <img src='<?= "../library/gambar.php?replid=$replid&table=$table" ?>' height='80'><br>
    </center>
    
<?php    
}

function ShowSelectJenisPayment()
{
    global $jenisp;
    
    echo "Kategori: <select name='kate' id='kate' onchange='ChangeKate()' onkeyup='ChangeKate()' style='width: 180px'>\r\n";
    foreach($jenisp as $key => $value)
    {
        echo "<option value='$key'>$value</option>\r\n";
    }
    echo "</select>\r\n";
}

function ShowSelectPayment($jenis, $dept)
{
    if (!isset($jenis))
    {
        global $jenisp;
        foreach($jenisp as $key => $value)
        {
            $jenis = $key;
            break;
        }
    }
    
    if (!isset($dept))
    {
        global $departemen;
        $dept = $departemen;
    }
    
    $sql = "SELECT replid, nama
              FROM jbsfina.datapenerimaan
             WHERE idkategori = '$jenis'
               AND departemen = '$dept'
               AND aktif = 1
             ORDER BY nama";
    $res = QueryDb($sql);
    
    echo "Pembayaran: <select name='payment' id='payment' onchange='ChangePayment()' onkeyup='ChangePayment()'>\r\n";
    echo "<option value='0'>--Pilih Pembayaran--</option>\r\n";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>\r\n";
    }
    echo "</select>\r\n";
}
?>