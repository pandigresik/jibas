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
function ShowKategoriPenerimaan()
{
    global $kelompok, $idKategori;

    echo "<select name='idKategori' id='idKategori' onChange='changeKategori();' style='width:200px'>";
    if ($kelompok == 1)
    {
        $idKategori = "JTT";
        echo "<option value='JTT'>Iuran Wajib Siswa</option>";
        echo "<option value='SKR'>Iuran Sukarela Siswa</option>";
    }
    else
    {
        $idKategori = "CSWJB";
        echo "<option value='CSWJB'>Iuran Wajib Calon Siswa</option>";
        echo "<option value='CSSKR'>Iuran Sukarela Calon Siswa</option>";
    }
    echo "</select>";
}

function ShowPenerimaan($departemen, $idKategori)
{
    $sql = "SELECT replid, nama
              FROM jbsfina.datapenerimaan
             WHERE idkategori = '$idKategori'
               AND departemen = '$departemen'
               AND aktif = 1
             ORDER BY nama";
    $res = QueryDb($sql);
    echo "<select name='idPenerimaan' id='idPenerimaan' style='width:200px'>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
}
?>