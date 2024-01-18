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
function ShowComboBulan()
{   
    echo "<select id='notidx_CbBulan' class='inputbox' onchange='notidx_ComboChange()'>\r\n";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == date('n') ? "selected" : "";
        echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
    }
    echo "</select>\r\n";
}

function ShowComboTahun()
{
    global $G_START_YEAR;
    
    echo "<select id='notidx_CbTahun' class='inputbox' onchange='notidx_ComboChange()'>\r\n";
    for($i = $G_START_YEAR; $i <= date('Y'); $i++)
    {
        $sel = $i == date('Y') ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>\r\n";
}

function GetOwnerName($ownerid, $ownertype)
{
    $sql = $ownertype == "S" ?
           "SELECT nama FROM jbsakad.siswa WHERE nis = '$ownerid'" :
           "SELECT nama FROM jbssdm.pegawai WHERE nip = '".$ownerid."'";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        return $row[0];
    }
    else
    {
        return "-";    
    }
}


function ShowNotesIndex($dept, $bulan, $tahun)
{
    $sql = "SELECT *, DAYOFWEEK(tanggal) AS haribuat, DATE_FORMAT(tanggal, '%d-%m-%Y %H:%i') AS tglbuat, 
                   DAYOFWEEK(lastactive) AS hariaktif, DATE_FORMAT(lastactive, '%d-%m-%Y %H:%i') AS tglaktif,
                   DAYOFWEEK(lastread) AS haribaca, DATE_FORMAT(lastread, '%d-%m-%Y %H:%i') AS tglbaca,
                   IF(nip IS NULL, 'S', 'P') AS ownertype,
                   TIME_TO_SEC(TIMEDIFF(NOW(), tanggal)) AS secdiff,
                   TIME_TO_SEC(TIMEDIFF(NOW(), lastactive)) AS secdiff_active,
                   TIME_TO_SEC(TIMEDIFF(NOW(), lastread)) AS secdiff_read
              FROM jbsvcr.notes
             WHERE kategori = 'ifse'
               AND departemen = '$dept'
               AND MONTH(tanggal) = '$bulan'
               AND YEAR(tanggal) = '$tahun'
             ORDER BY tanggal DESC";
    $res = QueryDb($sql);
    $ndata = mysqli_num_rows($res);
    if ($ndata == 0)
    {
        echo "<tr style='background-color: #fff;'>\r\n";
        echo "<td colspan='5' align='center' valign='center'><em>Belum ada data</em></td>\r\n";
        echo "</tr>\r\n";
        
        return;
    }
    
    $lastactive = "1970-01-01 12:00:00";
    $lastid = -1;
    while($row = mysqli_fetch_array($res))
    {
        $notesid = $row['replid'];
        $lastactive = $row['lastactive'];
        $lastid = $row['replid'];
    
        $ownertype = $row['ownertype'];
        $ownerid = ($ownertype == 'S') ? $row['nis'] : $row['nip'];
        $ownername = GetOwnerName($ownerid, $ownertype);
    
        $sql = "SELECT COUNT(replid)
                  FROM jbsvcr.notesfile
                 WHERE filecate = 'pict'
                   AND notesid = '".$notesid."'";
        $npict = (int)FetchSingle($sql);
        
        $sql = "SELECT COUNT(replid)
                  FROM jbsvcr.notesfile
                 WHERE filecate = 'doc'
                   AND notesid = '".$notesid."'";
        $ndoc = (int)FetchSingle($sql);
        
        $sql = "SELECT COUNT(replid)
                  FROM jbsvcr.notescomment
                 WHERE notesid = '".$notesid."'";
        $ncomment = (int)FetchSingle($sql);
        
        $nread = (int)$row['nread']; ?>
        
        <tr id='not_list_row_<?=$notesid?>' style='background-color: #fff;'>
            <td align='center' valign='top'>
                <img src='notes.list.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>&ownertype=<?=$ownertype?>' height='40'><br>
                <strong><?=$ownername?></strong><br>
                <font class='NotesAge'>
                <?= SecToAge($row['secdiff']) ?>
                </font><br>
                <font class='NotesTanggal'>
                <?= DayName($row['haribuat']) ?>, <?=$row['tglbuat']?>
                </font>
            </td>
            <td align='center' valign='middle'><?= $row['kepada'] ?></td>
            <td align='left' valign='top'>
                <span style='cursor: pointer;'
                      onclick='notidx_ViewNotes(<?=$notesid?>)'>
                <font class='NotesJudul'><?= $row['judul'] ?></font><br>
                <?= $row['fprevpesan'] ?>
                </span>
                <br><br>
                <font class='NotesJumlahFile'>
                Gambar: <?= $npict ?>&nbsp;&nbsp;&nbsp;Dokumen: <?= $ndoc ?>
                </font>
            </td>
            <td align='center' valign='middle'>
                <font class='NotesJumlah'><?= $ncomment ?></font><br>
                <font class='NotesAge'>
                    <?= SecToAge($row['secdiff_active']) ?>
                </font><br>
                <font class='NotesTanggal'>
                    <?= DayName($row['hariaktif']) ?>, <?=$row['tglaktif']?>
                </font>
            </td>
            <td align='center' valign='middle'>
                <font class='NotesJumlah'><?= $nread ?></font><br>
                <font class='NotesAge'>
                    <?= SecToAge($row['secdiff_read']) ?>
                </font><br>
                <font class='NotesTanggal'>
                    <?= DayName($row['haribaca']) ?>, <?=$row['tglbaca']?>
                </font>
            </td>
        </tr>
        <tr style='background-color: #fff;'>
            <td colspan='5' align='center'>
                <hr width='96%' style='height: 1px; border: 0; border-top: 1px solid #557d1d; '>
            </td>
        </tr>
<?php  }
}
?>