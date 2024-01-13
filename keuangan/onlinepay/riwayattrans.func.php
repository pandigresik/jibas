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
function ShowSelectDepartemen()
{
    global $departemen;

    echo "<select id='departemen' name='departemen' class='inputbox' style='width: 250px' onchange='changeDept(); clearContent();'>";
    $dep = getDepartemen(getAccess());
    foreach($dep as $value)
    {
        if ($departemen == "") $departemen = $value;
        $sel = $departemen == $value ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function ShowSelectIuranWajib()
{
    $departemen = $_REQUEST["departemen"];

    $sql = "SELECT replid, nama 
              FROM jbsfina.datapenerimaan
             WHERE departemen = '$departemen'
               AND idkategori = 'JTT'
               AND aktif = 1
             ORDER BY nama";
    $res = QueryDb($sql);

    echo "<select id='idpembayaran' class='inputbox' style='width: 250px' onchange='clearContent()'>";
    echo "<option value='0' selected>Semua Iuran Wajib</option>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
}

function ShowSelectIuranSukarela()
{
    $departemen = $_REQUEST["departemen"];

    $sql = "SELECT replid, nama 
              FROM jbsfina.datapenerimaan
             WHERE departemen = '$departemen'
               AND idkategori = 'SKR'
               AND aktif = 1
             ORDER BY nama";
    $res = QueryDb($sql);

    echo "<select id='idpembayaran' class='inputbox' style='width: 250px' onchange='clearContent()'>";
    echo "<option value='0' selected>Semua Iuran Sukarela</option>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
}

function ShowSelectTabunganSiswa()
{
    $departemen = $_REQUEST["departemen"];

    $sql = "SELECT replid, nama 
              FROM jbsfina.datatabungan
             WHERE departemen = '$departemen'
               AND aktif = 1
             ORDER BY nama";
    $res = QueryDb($sql);

    echo "<select id='idpembayaran' class='inputbox' style='width: 250px' onchange='clearContent()'>";
    echo "<option value='0' selected>Semua Tabungan Siswa</option>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
}

function ShowSelectBank()
{
    global $departemen;

    $sql = "SELECT bankno, bank 
              FROM jbsfina.bank
             WHERE aktif = 1
               AND departemen = '$departemen'
             ORDER BY bank";
    $res = QueryDb($sql);

    echo "<select id='bankno' class='inputbox' style='width: 250px' onchange='clearContent()'>";
    echo "<option value='ALL' selected>Semua Bank</option>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]. $row[0]."</option>";
    }
    echo "</select>";
}

function ShowSelectPetugas()
{
    $sql = "SELECT h.login, p.nama
              FROM jbsuser.hakakses h, jbssdm.pegawai p
             WHERE h.login = p.nip
               AND h.modul = 'KEUANGAN'
               AND h.aktif = 1
               AND p.aktif = 1
             ORDER BY p.nama";
    $res = QueryDb($sql);

    echo "<select id='idpetugas' class='inputbox' style='width: 250px' onchange='clearContent()'>";
    echo "<option value='ALL' selected>Semua Petugas</option>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]. $row[0]."</option>";
    }
    echo "</select>";
}
?>