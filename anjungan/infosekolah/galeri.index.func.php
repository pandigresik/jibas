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
    echo "<select id='galidx_CbBulan' class='inputbox' style='z-Index: -1;' onchange='galidx_ComboChange()'>\r\n";
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
    
    echo "<select id='galidx_CbTahun' class='inputbox' style='z-Index: -1;' onchange='galidx_ComboChange()'>\r\n";
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

function ShowGalleryIndex($dept, $bulan, $tahun)
{
    global $FILESHARE_ADDR;
    
    $sql = "SELECT *, DAYOFWEEK(tanggal) AS haribuat, DATE_FORMAT(tanggal, '%d-%m-%Y %H:%i') AS tglbuat, 
                    DAYOFWEEK(lastactive) AS hariaktif, DATE_FORMAT(lastactive, '%d-%m-%Y %H:%i') AS tglaktif,
                    DAYOFWEEK(lastread) AS haribaca, DATE_FORMAT(lastread, '%d-%m-%Y %H:%i') AS tglbaca,
                    IF(nip IS NULL, 'S', 'P') AS ownertype,
                    TIME_TO_SEC(TIMEDIFF(NOW(), tanggal)) AS secdiff,
                    TIME_TO_SEC(TIMEDIFF(NOW(), lastactive)) AS secdiff_active,
                    TIME_TO_SEC(TIMEDIFF(NOW(), lastread)) AS secdiff_read
               FROM jbsvcr.gallery
              WHERE kategori = 'ifse'
                AND departemen = '$dept'
                AND MONTH(tanggal) = '$bulan'
                AND YEAR(tanggal) = '$tahun'
              ORDER BY tanggal DESC";
              
    $res = QueryDb($sql);
    $colcnt = 0;
    
    while($row = mysqli_fetch_array($res))
    {
        $galleryid = $row['replid'];
        $nread = $row['nread'];
        
        $age = SecToAgeDate($row['secdiff'], $row['tglbuat']);
        $ownertype = $row['ownertype'];
        $ownerid = $row['ownertype'] == "S" ? $row["nis"] : $row["nip"];
        $ownername = GetOwnerName($ownerid, $ownertype);
        
        $sql = "SELECT filename, location, width, height
                  FROM jbsvcr.galleryfile
                 WHERE galleryid = '$galleryid'
                   AND iscover = 1";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_array($res2);
        $coverfile = $row2['filename'];
        $coverloc = $row2['location'];
        $coverw = $row2['width'];
        $coverh = $row2['height'];
        
        $imgdim = "";
        if ($coverw > 140)
        {
            $delta = 140 / $coverw;
            $coverh = $coverh > 120 ? $coverh * $delta : $coverh;
            
            if ($coverh > 120)
                $imgdim = "height = '120'";
            else
                $imgdim = "width = '140'";
        }
            
        $sql = "SELECT COUNT(replid)
                  FROM jbsvcr.galleryfile
                 WHERE galleryid = '$galleryid'
                   AND iscover = 0";
        $npict = (int)FetchSingle($sql);
        
        $sql = "SELECT COUNT(replid)
                  FROM jbsvcr.gallerycomment
                 WHERE galleryid = '".$galleryid."'";
        $ncomment = (int)FetchSingle($sql);  
        
        if ($colcnt == 0)
            echo "<tr height='180'>\r\n"; ?>
            
        <td align='left' valign='top' width='50%'>
        
            <fieldset class='GaleriBox'>
                
            <table border='0' cellpadding='0' cellspacing='0' width='100%' style='height: 200px;'>
            <tr>
                <td colspan='3' align='left'>
                    <span class="GaleriLink" onclick="galidx_ViewGallery(<?=$galleryid?>)">
                    <font class='GaleriTitle'><?= $row['fjudul'] ?></font><br>
                    <font class='GaleriOwner'><?= $ownername . " - " . $age ?></font>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan='3' align='left' valign='top'>
                    <table border='0' cellpadding='0' cellspacing='2' align='left'>
                    <tr><td>
                        <span class="GaleriLink" onclick="galidx_ViewGallery(<?=$galleryid?>)">
                        <img src='<?= "$FILESHARE_ADDR/$coverloc/$coverfile" ?>' <?=$imgdim?>>
                        </span>
                    </td></tr>    
                    </table>
                    
                    <span class="GaleriLink" onclick="galidx_ViewGallery(<?=$galleryid?>)">
                    <font class='GaleriInfo'><?= $row['fprevketerangan'] ?></font>
                    </span>
                </td>
            </tr>
            <tr height='30'>
                <td width='33%' align='left' valign='middle' style="background-image: url('../images/bk-galeri-jumphoto.jpg')">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <font style='color: #5c5c5c; font-size: 14px;'><?=$npict?> gambar</font>
                </td>
                <td width='33%' align='center' valign='top' style="background-image: url('../images/bk-galeri-jumview.jpg')">
                    &nbsp;&nbsp;&nbsp;
                    <font style='color: #5c5c5c; font-size: 14px;'><?=$nread?> dilihat</font><br>
                    &nbsp;&nbsp;&nbsp;
                    <font style='color: #5c5c5c; font-size: 8.5px;'><?= SecToAge($row['secdiff_read']) ?></font>
                </td>
                <td width='33%' align='center' valign='top' style="background-image: url('../images/bk-galeri-jumcmt.jpg')">
                    &nbsp;&nbsp;&nbsp;
                    <font style='color: #5c5c5c; font-size: 14px;'><?=$ncomment?> komentar</font><br>
                    &nbsp;&nbsp;&nbsp;
                    <font style='color: #5c5c5c; font-size: 8.5px;'><?= SecToAge($row['secdiff_active']) ?></font>
                </td>
            </tr>
            </table>
            
            </fieldset>
            
        </td>    
<?php
        $colcnt += 1;
        if ($colcnt == 2)
        {
            $colcnt = 0;
            echo "</tr>\r\n";
        }
        
    } // while
    
    if ($colcnt != 0)
    {
        $n = 2 - $colcnt;
        for($i = 0; $i < $n; $i++)
            echo "<td width='50%'>&nbsp;</td>\r\n";
        echo "</tr>\r\n";
    }
}
?>