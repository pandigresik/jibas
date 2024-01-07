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
function ShowList()
{
    global $SI_USER_STAFF;
    global $departemen, $status;
    
    $sql = "SELECT *, DATE_FORMAT(tanggal, '%d %M %Y') AS xtanggal, IF(petugas IS NULL, 'landlord', petugas) AS xpetugas
              FROM jbsumum.pengantarsurat
             WHERE departemen = '$departemen' ";
    if ($status != 2)
        $sql .= "AND aktif = $status ";
    $sql .= "ORDER BY tanggal DESC, replid DESC";
    
    $no = 0;
    $res = QueryDb($sql);
    
    if (0 == mysqli_num_rows($res))
    {
        echo "<tr height='60'>";
        echo "<td align='center' valign='middle' colspan='5'>";
        echo "<em>Belum ada data</em>";
        echo "</td>";
        echo "</tr>";
        
        return;
    }
    
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        
        if ($row['xpetugas'] == "landlord")
        {
            $nama = "Administrator JIBAS";
        }
        else
        {
            $sql = "SELECT nama
                      FROM jbssdm.pegawai
                     WHERE nip = '" . $row['xpetugas'] . "'";
            $res2 = QueryDb($sql);
            $row2 = mysqli_fetch_row($res2);
            $nama = $row2[0];
        }
        
        if (1 == (int)$row['aktif'])
        {
            if (SI_USER_LEVEL() != $SI_USER_STAFF)
            {
                $asrc = "<a href='#' onclick='setStatus(0, ".$row['replid'].")' title='set non aktif'>
                         <img src='../images/ico/aktif.png' border='0'>
                         </a>";
            }
            else
            {
                $asrc = "<img src='../images/ico/aktif.png' border='0'>";
            }
        }
        else
        {
            if (SI_USER_LEVEL() != $SI_USER_STAFF)
            {
                $asrc = "<a href='#' onclick='setStatus(1, {$row['replid']})' title='set aktif'>
                         <img src='../images/ico/nonaktif.png' border='0'>
                         </a>";
            }
            else
            {
                $asrc = "<img src='../images/ico/nonaktif.png' border='0'>";
            }
        }
        
        echo "<tr>";
        echo "<td align='center' valign='top'>$no</td>";
        echo "<td align='left' valign='top'>".$row['xtanggal']."<br><strong>$nama</strong></td>";
        echo "<td align='left' valign='top'><strong>".$row['judul']."</strong><br>";
        echo "<div style='height: 200px; overflow: auto;'>";
        echo $row['pengantar'];
        echo "</div></td>";
        echo "<td align='center' valign='top'>$asrc</td>";
        echo "<td align='center' valign='top'>";
        
        if (SI_USER_LEVEL() != $SI_USER_STAFF)
        {
            echo "<a href='#' onclick='ubah({$row['replid']})'>";
            echo "<img src='../images/ico/ubah.png' title='edit' border='0'>";
            echo "</a>&nbsp;";
            echo "<a href='#' onclick='hapus({$row['replid']})'>";
            echo "<img src='../images/ico/hapus.png' title='hapus' border='0'>";
            echo "</a>";
        }
        else
        {
            echo "&nbsp;";    
        }    
        echo "</td>";
    }
}
?>