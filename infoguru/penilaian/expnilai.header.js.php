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
function change_sel()
{
    var departemen = document.getElementById("departemen").value;
    document.location.href = "expnilai.header.php?departemen="+departemen;
    parent.content.location.href = "expnilai.blank.php";
}

function change_sel2()
{
    var departemen = document.getElementById("departemen").value;
    var tingkat = document.getElementById("tingkat").value;
    var tahunajaran = document.getElementById("tahunajaran").value;

    document.location.href="expnilai.header.php?tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&departemen="+departemen;
    parent.content.location.href = "expnilai.blank.php";
}

function change()
{
    parent.content.location.href = "expnilai.blank.php";
}

function show()
{
    var departemen = document.getElementById('departemen').value;
    var tingkat = document.getElementById('tingkat').value;
    var tahun = document.getElementById('tahunajaran').value;
    var semester = document.getElementById('semester').value;
    var kelas = document.getElementById('kelas').value;

    if(departemen.length == 0)
    {
        alert("Departemen tidak boleh kosong!");
        document.getElementById('departemen').focus();
        return false;
    }
    else if(tingkat.length == 0)
    {
        alert("Tingkat tidak boleh kosong!");
        document.getElementById('tingkat').focus();
        return false;
    }
    else if(tahun.length == 0)
    {
        alert("Tahun Ajaran tidak boleh kosong!");
        document.getElementById('tahun').focus();
        return false;
    }
    else if(semester.length == 0)
    {
        alert("Semester tidak boleh kosong!");
        document.getElementById('semester').focus();
        return false;
    }
    else if(kelas.length == 0)
    {
        alert("Kelas tidak boleh kosong!");
        document.getElementById('kelas').focus();
        return false;
    }
    else
    {
        parent.content.location.href="expnilai.content.php?departemen="+departemen+"&tingkat="+tingkat+"&semester="+semester+"&kelas="+kelas+"&tahunajaran="+tahun;
    }
}