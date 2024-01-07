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

function ShowGalleryList($dept, $start, $rowperpage)
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
              WHERE kategori = 'mading'
                AND departemen = '$dept' 
              ORDER BY lastactive DESC
              LIMIT $start, $rowperpage";
              
    $res = QueryDb($sql);
    $colcnt = 0;
    
    $lastactive = "1970-01-01 12:00:00";
    $lastid = -1;
    while($row = mysqli_fetch_array($res))
    {
        $lastactive = $row['lastactive'];
        $lastid = $row['replid'];
        
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
                    <span class="GaleriLink" onclick="gallst_ViewGallery(<?=$galleryid?>)">
                    <font class='GaleriTitle'><?= $row['fjudul'] ?></font><br>
                    <font class='GaleriOwner'><?= $ownername . " - " . $age ?></font>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan='3' align='left' valign='top'>
                    <table border='0' cellpadding='0' cellspacing='2' align='left'>
                    <tr><td>
                        <span class="GaleriLink" onclick="gallst_ViewGallery(<?=$galleryid?>)">
                        <img src='<?= "$FILESHARE_ADDR/$coverloc/$coverfile" ?>' <?=$imgdim?>>
                        </span>
                    </td></tr>    
                    </table>
                    
                    <span class="GaleriLink" onclick="gallst_ViewGallery(<?=$galleryid?>)">
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
    
    if ($lastid == -1)
        return;
    
    $sql = "SELECT replid
              FROM jbsvcr.gallery
             WHERE lastactive <= '$lastactive'
               AND replid <> $lastid
               AND kategori = 'mading'
             LIMIT 1";
    $res = QueryDb($sql);
    $nnext = mysqli_num_rows($res);
    
    if ($nnext > 0)
    {  ?>
        ~~#$@**
        <tr style='background-color: #e0e0e0;' height='25'>
            <td colspan='2' align='center'>
                <span id='gallst_LinkNextComment' style='cursor: pointer; color: #808080;'
                      onmouseover="document.getElementById('gallst_LinkNextComment').style.textDecoration = 'underline'"
                      onmouseout="document.getElementById('gallst_LinkNextComment').style.textDecoration = 'none'"
                      onclick="gallst_GetGalleryList()">
                <strong>Klik untuk melihat galeri selanjutnya</strong>    
                </span>      
            </td>
        </tr>
<?php  }
}
?>