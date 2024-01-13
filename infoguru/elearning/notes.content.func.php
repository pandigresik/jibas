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
function ShowCbMedia()
{
    global $selIdMedia, $idPelajaran, $idChannel, $date1, $date2;

    $sql = "SELECT DISTINCT mn.idmedia, m.judul
              FROM jbsel.medianotes mn, jbsel.media m, jbsel.channel c
             WHERE mn.idmedia = m.id
               AND m.idchannel = c.id
               AND c.idpelajaran = $idPelajaran
               AND c.id = $idChannel
               AND mn.timestamp BETWEEN '$date1' AND '$date2'
             ORDER BY m.judul";
    $res = QueryDb($sql);

    if (mysqli_num_rows($res) == 0)
    {
        echo "<b>Video: </b>:&nbsp;&nbsp;";
        echo "<select id='media' style='width: 300px; height: 25px'>";
        echo "<option value='0'>(belum ada catatan di pilihan terpilih)</option>";
        echo "</select>";
        return;
    }

    echo "<b>Video: </b>:&nbsp;&nbsp;";
    echo "<select id='media' style='width: 300px; height: 25px' onchange='changeVideo()'>";
    while($row = mysqli_fetch_row($res))
    {
        $idMedia = $row[0];
        $judul = $row[1];

        if ($selIdMedia == 0) $selIdMedia = $idMedia;
        echo "<option value='$idMedia'>$judul</option>";

    }
    echo "</select>";
    return;
}

function ShowDaftarCatatan($idMedia, $date1, $date2)
{
    $sql = "SELECT id, nama, tanggal, notes, timestamp 
              FROM (
            SELECT mn.nis AS id, s.nama, DATE_FORMAT(mn.timestamp, '%d-%m-%Y %H:%i') As tanggal, mn.notes, mn.timestamp
              FROM jbsel.medianotes mn, jbsakad.siswa s 
             WHERE mn.nis = s.nis
               AND mn.idmedia = $idMedia
               AND mn.timestamp BETWEEN '$date1' AND '$date2'
             UNION    
            SELECT mn.nip AS id, p.nama, DATE_FORMAT(mn.timestamp, '%d-%m-%Y %H:%i') As tanggal, mn.notes, mn.timestamp
              FROM jbsel.medianotes mn, jbssdm.pegawai p 
             WHERE mn.nip = p.nip
               AND mn.idmedia = $idMedia
               AND mn.timestamp BETWEEN '$date1' AND '$date2'
                   ) AS X
             ORDER BY timestamp DESC";
    $res = QueryDb($sql);
    $no = 0;
    while($row = mysqli_fetch_row($res))
    {
        $no += 1;

        echo "<tr style='height: 100px; line-height: 17px;'>";
        echo "<td align='center' valign='top'>$no</td>";
        echo "<td align='left' valign='top'><b>".$row[1]."</b><br>".$row[0]."</td>";
        echo "<td align='left' valign='top'><i>".$row[2]."</i><br>".$row[3]."</td>";
        echo "</tr>";
    }
}
?>
