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
$arrjenis = ['SPI', 'SOS'];
$arrnmjenis = ['Spiritual', 'Sosial'];
for($i = 0; $i < count($arrjenis); $i++)
{
    $jenis = $arrjenis[$i];
    $nmjenis = $arrnmjenis[$i];

    $sql = "SELECT komentar, k.predikat
              FROM jbsakad.komenrapor k 
             WHERE k.nis = '$nis' 
               AND k.idsemester = '$semester' 
               AND k.idkelas = '$kelas'
               AND k.jenis = '".$jenis."'";
    $res2 = QueryDb($sql);
    $komentar = "";
    $predikat = "";
    $nilaiExist = false;
    if ($row2 = mysqli_fetch_row($res2))
    {
        $nilaiExist = true;
        $komentar = $row2[0];
        $predikat = PredikatNama($row2[1]);
    }


    echo "<fieldset><legend><strong>Sikap $nmjenis</strong></legend>";
    echo "<table border='1' width='100%' cellpadding='2' cellspacing='0' style='border-width: 1px; border-collapse: collapse'>";
    echo "<tr style='height: 80px'>";
    echo "<td width='20%' align='left' valign='top'>Predikat: $predikat</td>";
    echo "<td width='*' align='left' valign='top'>$komentar</td>";
    echo "</tr>";
    echo "</table>";
    echo "</fieldset><br>";

}
?>