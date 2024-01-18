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
$UserLetterIdList = [];
$UserGroupList = "";

function GetUserGroup()
{
    global $nip, $UserGroupList;
    
    $sql = "SELECT DISTINCT idkelompok
              FROM jbsletter.anggota
             WHERE nip = '$nip'
               AND aktif = 1";
    
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        if ($UserGroupList != "")
            $UserGroupList .= ",";
        $UserGroupList .= $row[0];           
    }
}

function GetFromInDst()
{
    global $UserGroupList, $UserLetterIdList;
    global $nip, $departemen, $jenis, $kategori, $bulan1, $tahun1, $bulan2, $tahun2;
    
    $tableList = [["jbsletter.suratindstuser", "iduser", "'" . $nip. "'"], ["jbsletter.suratindstgroup", "idkelompok", $UserGroupList], ["jbsletter.suratindstcc", "iduser", "'" . $nip. "'"]];
    
    for($i = 0; $i < count($tableList); $i++)
    {
        $tabName = $tableList[$i][0];
        $colName = $tableList[$i][1];
        $colValue = $tableList[$i][2];
        
        if (0 == strlen((string) $colValue))
            continue;
        
        $sql = "SELECT DISTINCT s.replid
                  FROM jbsletter.surat s, jbsletter.kategori k, $tabName dst
                 WHERE s.idkategori = k.replid
                   AND s.replid = dst.idsurat
                   AND dst.$colName IN ($colValue)
                   AND s.departemen = '$departemen'
                   AND ((MONTH(s.tanggal) >= $bulan1 AND YEAR(s.tanggal) >= $tahun1) AND 
                        (MONTH(s.tanggal) <= $bulan2 AND YEAR(s.tanggal) <= $tahun2))";
        
        if ($jenis != "ALL")
            $sql .= " AND s.jenis = '".$jenis."'";
        
        if ($kategori != 0)
            $sql .= " AND s.idkategori = '".$kategori."'";
        
        //echo "$sql<br>";
        
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $found = false;
            for($j = 0; !$found && $j < count($UserLetterIdList); $j++)
            {
                $found = $row[0] == $UserLetterIdList[$j];
            }
            
            if ($found)
                continue;
            
            $UserLetterIdList[] = $row[0];
        }   
    }
}

function GetFromSuratOutDst()
{
    global $UserGroupList, $UserLetterIdList;
    global $nip, $departemen, $jenis, $kategori, $bulan1, $tahun1, $bulan2, $tahun2;
    
    $tableList = [["jbsletter.suratoutdst", "iduser", "'" . $nip. "'"], ["jbsletter.suratoutdst", "idkelompok", $UserGroupList]];
    
    for($i = 0; $i < count($tableList); $i++)
    {
        $tabName = $tableList[$i][0];
        $colName = $tableList[$i][1];
        $colValue = $tableList[$i][2];
        
        if (0 == strlen((string) $colValue))
            continue;
        
        $sql = "SELECT DISTINCT s.replid
                  FROM jbsletter.surat s, jbsletter.kategori k, $tabName dst
                 WHERE s.idkategori = k.replid
                   AND s.replid = dst.idsurat
                   AND dst.$colName IN ($colValue)
                   AND s.departemen = '$departemen'
                   AND ((MONTH(s.tanggal) >= $bulan1 AND YEAR(s.tanggal) >= $tahun1) AND 
                        (MONTH(s.tanggal) <= $bulan2 AND YEAR(s.tanggal) <= $tahun2))";
        if ($jenis != "ALL")
            $sql .= " AND s.jenis = '".$jenis."'";
        
        if ($kategori != 0)
            $sql .= " AND s.idkategori = '".$kategori."'";
            
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $found = false;
            for($j = 0; !$found && $j < count($UserLetterIdList); $j++)
            {
                $found = $row[0] == $UserLetterIdList[$j];
            }
            
            if ($found)
                continue;
            
            $UserLetterIdList[] = $row[0];
        }
    }
}

function GetFromSuratOutSrc()
{
    global $UserGroupList, $UserLetterIdList;
    global $nip, $departemen, $jenis, $kategori, $bulan1, $tahun1, $bulan2, $tahun2;
    
    $tableList = [["jbsletter.suratoutsrcuser", "iduser", "'" . $nip. "'"], ["jbsletter.suratoutsrcgroup", "idkelompok", $UserGroupList], ["jbsletter.suratoutsrccc", "iduser", "'" . $nip. "'"]];
    
    for($i = 0; $i < count($tableList); $i++)
    {
        $tabName = $tableList[$i][0];
        $colName = $tableList[$i][1];
        $colValue = $tableList[$i][2];
        
        if (0 == strlen((string) $colValue))
            continue;
        
        $sql = "SELECT DISTINCT s.replid
                  FROM jbsletter.surat s, jbsletter.kategori k, $tabName src
                 WHERE s.idkategori = k.replid
                   AND s.replid = src.idsurat
                   AND src.$colName IN ($colValue)
                   AND s.departemen = '$departemen'
                   AND ((MONTH(s.tanggal) >= $bulan1 AND YEAR(s.tanggal) >= $tahun1) AND 
                        (MONTH(s.tanggal) <= $bulan2 AND YEAR(s.tanggal) <= $tahun2))";
                        
        if ($jenis != "ALL")
            $sql .= " AND s.jenis = '".$jenis."'";
        
        if ($kategori != 0)
            $sql .= " AND s.idkategori = '".$kategori."'";
            
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $found = false;
            for($j = 0; !$found && $j < count($UserLetterIdList); $j++)
            {
                $found = $row[0] == $UserLetterIdList[$j];
            }
            
            if ($found)
                continue;
            
            $UserLetterIdList[] = $row[0];
        }
    }
}

function GetSumberSurat($idsurat, $jenissurat)
{
    $sumber = "";
    
    if ($jenissurat == "IN")
    {
        $sql = "SELECT sumber
                  FROM jbsletter.suratinsrc src, jbsletter.sumberin si
                 WHERE src.idsumber = si.replid
                   AND src.idsurat = $idsurat";
        $res = QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $sumber = $row[0];    
    }
    else
    {
        $sql = "SELECT nama FROM
                (
                    SELECT p.nama
                    FROM jbsletter.suratoutsrcuser src, jbssdm.pegawai p
                    WHERE src.iduser = p.nip
                    AND idsurat = $idsurat
                UNION
                    SELECT s.nama
                    FROM jbsletter.suratoutsrcuser src, jbsakad.siswa s
                    WHERE src.idsiswa = s.nis
                    AND idsurat = $idsurat
                ) AS X
                ORDER BY nama";
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            if ($sumber != "")
                $sumber .= ", ";
            $sumber .= $row[0];    
        }
        
        $sql = "SELECT k.kelompok
                  FROM jbsletter.suratoutsrcgroup src, jbsletter.kelompok k
                 WHERE src.idkelompok = k.replid
                   AND idsurat = $idsurat
                 ORDER BY k.kelompok";
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            if ($sumber != "")
                $sumber .= ", ";
            $sumber .= $row[0];    
        }
    }
    
    return $sumber;
}

function GetTujuanSurat($idsurat, $jenissurat)
{
    $tujuan = "";
    
    if ($jenissurat == "IN")
    {
        $sql = "SELECT nama FROM
                (
                    SELECT p.nama
                    FROM jbsletter.suratindstuser dst, jbssdm.pegawai p
                    WHERE dst.iduser = p.nip
                    AND dst.idsurat = $idsurat
                UNION
                    SELECT s.nama
                    FROM jbsletter.suratindstuser dst, jbsakad.siswa s
                    WHERE dst.idsiswa = s.nis
                    AND dst.idsurat = $idsurat
                ) AS X
                ORDER BY nama";
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            if ($tujuan != "")
                $tujuan .= ", ";
            $tujuan .= $row[0];    
        }
        
        $sql = "SELECT k.kelompok
                  FROM jbsletter.suratindstgroup dst, jbsletter.kelompok k
                 WHERE dst.idkelompok = k.replid
                   AND dst.idsurat = $idsurat
                 ORDER BY k.kelompok";
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            if ($tujuan != "")
                $tujuan .= ", ";
            $tujuan .= $row[0];    
        }         
    }
    else
    {
        $sql = "SELECT nama FROM
                (
                    SELECT tout.tujuan AS nama
                    FROM jbsletter.suratoutdst dst, jbsletter.tujuanout tout
                    WHERE dst.idtujuan = tout.replid
                    AND dst.idsurat = $idsurat
                UNION
                    SELECT p.nama
                    FROM jbsletter.suratoutdst dst, jbssdm.pegawai p
                    WHERE dst.iduser = p.nip
                    AND dst.idsurat = $idsurat
                UNION
                    SELECT s.nama
                    FROM jbsletter.suratoutdst dst, jbsakad.siswa s
                    WHERE dst.idsiswa = s.nis
                    AND dst.idsurat = $idsurat
                UNION
                    SELECT k.kelompok AS nama
                    FROM jbsletter.suratoutdst dst, jbsletter.kelompok k
                    WHERE dst.idkelompok = k.replid
                    AND dst.idsurat = $idsurat
                ) 
                AS X
                ORDER BY nama";
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            if ($tujuan != "")
                $tujuan .= ", ";
            $tujuan .= $row[0];    
        }          
    }
    
    return $tujuan;
}

function GetTembusanSurat($idsurat, $jenissurat)
{
    if ($jenissurat == "IN")
    {
        $sql = "SELECT p.nama
                  FROM jbsletter.suratindstcc dst, jbssdm.pegawai p
                 WHERE dst.iduser = p.nip
                   AND dst.idsurat = $idsurat
                 ORDER BY p.nama";
    }
    else
    {
        $sql = "SELECT p.nama
                  FROM jbsletter.suratoutsrccc src, jbssdm.pegawai p
                 WHERE src.iduser = p.nip
                   AND src.idsurat = $idsurat
                 ORDER BY p.nama";
    }
    
    $tembusan = "";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        if ($tembusan != "")
            $tembusan .= ", ";
        $tembusan .= $row[0];    
    }
    
    return $tembusan;
}

function GetNBerkas($idsurat)
{
    $sql = "SELECT COUNT(replid)
              FROM jbsletter.berkassurat
             WHERE idsurat = $idsurat";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    
    return $row[0];
}

function GetNComment($idsurat)
{
    $sql = "SELECT COUNT(replid)
              FROM jbsletter.comment
             WHERE idsurat = $idsurat";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    
    return $row[0];
}

function GetLastActive($idsurat)
{
    $sql = "SELECT TIME_TO_SEC(TIMEDIFF(NOW(), lastactive)) AS secdiff_active,
                   DAYOFWEEK(lastactive) AS hariaktif, DATE_FORMAT(lastactive, '%d-%M-%Y') AS tglaktif
              FROM jbsletter.surat
             WHERE replid = $idsurat";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    
    return SecToAgeDate($row[0], $row[2]);
}

function SecToAgeDate($secdiff, $date)
{
    if ($secdiff >= 86400)
    {
        $nday = ceil($secdiff / 86400);
        return $nday <= 14 ? "$nday hari yang lalu" : $date;
    }
    
    if ($secdiff >= 3600)
        return ceil($secdiff / 3600) . " jam yang lalu";
    
    if ($secdiff >= 60)
        return ceil($secdiff / 60) . " menit yang lalu";
    
    return $secdiff . " detik yang lalu";
}

function ShowListSurat()
{
    global $UserGroupList, $UserLetterIdList, $searchby, $keyword;
    
    GetUserGroup();
    GetFromInDst();
    GetFromSuratOutDst();
    GetFromSuratOutSrc();
    
    if (0 == count($UserLetterIdList))
    {
        echo "<tr height='45' style='background-color: #FFF'>";
        echo "<td align='center' colspan='9' style='border-color: #bbb; border-width: 1px;' valign='middle'><em>Belum ada data surat</em></td>";
        echo "</tr>";
        
        return;
    }
    
    $idSuratList = "";
    for($i = 0; $i < count($UserLetterIdList); $i++)
    {
        if ($idSuratList != "")
            $idSuratList .= ",";
        $idSuratList .= $UserLetterIdList[$i];    
    }
    
    if ($searchby == 0)
    {
        $sql = "SELECT *, DATE_FORMAT(s.tanggal, '%d %M %Y') AS tanggalsurat, k.kategori, s.jenis AS jenissurat, s.replid AS idsurat
                  FROM jbsletter.surat s, jbsletter.kategori k
                 WHERE s.idkategori = k.replid
                   AND s.replid IN ($idSuratList)
                 ORDER BY s.tanggal DESC, s.replid DESC";
    }
    else
    {
        if ($searchby == 1)
            $colname = "perihal";
        elseif ($searchby == 2)
            $colname = "nomor";
        elseif ($searchby == 3)
            $colname = "deskripsi";
        elseif ($searchby == 4)
            $colname = "keterangan";
            
        $sql = "SELECT *, DATE_FORMAT(s.tanggal, '%d %M %Y') AS tanggalsurat, k.kategori, s.jenis AS jenissurat, s.replid AS idsurat
                  FROM jbsletter.surat s, jbsletter.kategori k
                 WHERE s.idkategori = k.replid
                   AND s.replid IN ($idSuratList)
                   AND s.$colname LIKE '%$keyword%'
                 ORDER BY s.tanggal DESC, s.replid DESC";    
    }
    $res = QueryDb($sql);
    
    if (0 == mysqli_num_rows($res))
    {
        echo "<tr height='45' style='background-color: #FFF'>";
        echo "<td align='center' colspan='9' style='border-color: #bbb; border-width: 1px;' valign='middle'><em>Tidak ditemukan data surat</em></td>";
        echo "</tr>";
        
        return;
    }
    
    $no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        
        $idsurat = $row['idsurat'];
        
        $jenissurat = $row['jenissurat'];
        $bgcolor = $jenissurat == "IN" ? "#e6f2ff" : "#ffecd9";
        $jenis = $jenissurat == "IN" ? "Surat Masuk" : "Surat Keluar";
        
        $sifat = $row['sifat'];
        if ($sifat == 1)
            $sifat = "<font style='color: red'>SANGAT PENTING</font>";
        elseif ($sifat == 2)
            $sifat = "<font style='color: blue'>PENTING</font>";
        else
            $sifat = "<font style='color: black'>Biasa</font>";
            
        $sumber = GetSumberSurat($idsurat, $jenissurat);
        $tujuan = GetTujuanSurat($idsurat, $jenissurat);
        $tembusan = GetTembusanSurat($idsurat, $jenissurat);
        $nberkas = GetNBerkas($idsurat);
        $ncomment = GetNComment($idsurat);
        
        
        if ($ncomment == 0)
        {
            $comment = "<font style='font-size: 16px;'>0</font>";    
        }
        else
        {
            $comment  = "<font style='font-size: 16px;'>$ncomment</font><br>";
            $comment .= "<font style='font-size: 9px; color: green;'>";
            $comment .= GetLastActive($idsurat);
            $comment .= "</font>";
        }
        
        echo "<tr style='background-color : $bgcolor' >";
        echo "<td align='center' style='border-color: #bbb; border-width: 1px;' valign='top'>$no</td>";
        echo "<td align='left' style='border-color: #bbb; border-width: 1px;' valign='top'><font style='color: maroon;'><em>".$row['tanggalsurat']."</em></font><br>".$row['nomor']."<br>$jenis</td>";
        echo "<td align='left' style='border-color: #bbb; border-width: 1px;' valign='top'><strong>".$row['perihal']."</strong><br>Kategori: ".$row['kategori']."<br>Sifat: $sifat</td>";
        echo "<td align='center' style='font-size: 16px; border-color: #bbb; border-width: 1px;' valign='top'>$nberkas</td>";
        echo "<td align='left' style='border-color: #bbb; border-width: 1px;' valign='top'>$sumber</td>";
        echo "<td align='left' style='border-color: #bbb; border-width: 1px;' valign='top'>$tujuan</td>";
        echo "<td align='left' style='border-color: #bbb; border-width: 1px;' valign='top'>$tembusan</td>";
        echo "<td align='center' style='border-color: #bbb; border-width: 1px;' valign='top'>$comment</td>";
        echo "<td align='center' style='border-color: #bbb; border-width: 1px;' valign='top'><a href='#' onclick='viewLetter($idsurat)' title='baca surat'><img src='../../images/ico/lihat.png' border='0'></a></td>";
        echo "</tr>";
    }
}
?>