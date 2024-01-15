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
function ShowPegawai($bagian)
{
    $sql = "SELECT nip, nama
              FROM jbssdm.pegawai";

    if ($bagian != "ALL")
        $sql .= " WHERE bagian = '".$bagian."'";

    $sql .= " ORDER BY nama";

    $no = 0;
    $res = QueryDb($sql);
    $num = mysqli_num_rows($res);

    if ($num == 0)
    {
        echo "<table id='table' class='tab' border='1' style='border-width: 1px; border-collapse: collapse;' width='97%' cellpadding='2' cellspacing='0'>";
        echo "<tr style='height: 30px'>";
        echo "<td class='header' width='10%'>No</td>";
        echo "<td class='header' width='*'>Nama</td>";
        echo "</tr>";
        echo "<tr><td align='center' colspan='2'>(belum ada data pegawai</td></tr></table>";
    }
    else
    {
        echo "<table id='table' class='tab' border='1' style='border-width: 1px; border-collapse: collapse;' width='97%' cellpadding='2' cellspacing='0'>";
        echo "<tr style='height: 30px'>";
        echo "<td class='header' width='10%'>No</td>";
        echo "<td class='header' width='*'>Nama</td>";
        echo "</tr>";
        while($row = mysqli_fetch_row($res))
        {
            $no += 1;

            echo "<tr style='height: 25px;' onclick='pilih(\"$row[0]\")'>";
            echo "<td align='center'>$no</td>";
            echo "<td align='left'>".$row[0]."<br><strong>".$row[1]."</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}
?>