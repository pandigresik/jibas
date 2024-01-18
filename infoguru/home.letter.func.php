<?php
function GetLetterListIn(&$letterList, $iduser, $limit2, $filterTs)
{
    // Tujuan surat masuk AS USER
    $sql = "SELECT s.replid, CONCAT(UNIX_TIMESTAMP(s.lastactive), LPAD(s.replid, 7, '0')) AS ts
              FROM jbsletter.surat s, jbsletter.suratindstuser dst
             WHERE s.replid = dst.idsurat
               AND dst.iduser = '$iduser'
                   $filterTs
             ORDER BY s.lastactive DESC
             LIMIT $limit2";
    //echo "$sql<br>";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $found = false;
        for($i = 0; !$found && $i < count($letterList); $i++)
        {
            $found = $letterList[$i][0] == $row[0];
        }
        
        if ($found)
            continue;
        
        $letterList[] = [$row[0], $row[1]];
    }
    
    // Tujuan surat masuk AS CC
    $sql = "SELECT s.replid, CONCAT(UNIX_TIMESTAMP(s.lastactive), LPAD(s.replid, 7, '0')) AS ts
              FROM jbsletter.surat s, jbsletter.suratindstcc dst
             WHERE s.replid = dst.idsurat
               AND dst.iduser = '$iduser'
                   $filterTs
             ORDER BY s.lastactive DESC
             LIMIT $limit2";
    //echo "$sql<br>";         
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $found = false;
        for($i = 0; !$found && $i < count($letterList); $i++)
        {
            $found = $letterList[$i][0] == $row[0];
        }
        
        if ($found)
            continue;
        
        $letterList[] = [$row[0], $row[1]];
    }
    
    // Tujuan surat masuk AS GROUP
    $sql = "SELECT s.replid, CONCAT(UNIX_TIMESTAMP(s.lastactive), LPAD(s.replid, 7, '0')) AS ts
              FROM jbsletter.surat s, jbsletter.suratindstgroup dst, jbsletter.anggota a
             WHERE s.replid = dst.idsurat
               AND dst.idkelompok = a.idkelompok
               AND a.nip = '$iduser'
                   $filterTs
             ORDER BY s.lastactive DESC
             LIMIT $limit2";
    //echo "$sql<br>";         
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $found = false;
        for($i = 0; !$found && $i < count($letterList); $i++)
        {
            $found = $letterList[$i][0] == $row[0];
        }
        
        if ($found)
            continue;
        
        $letterList[] = [$row[0], $row[1]];
    }
}

function GetLetterListOut(&$letterList, $iduser, $limit2, $filterTs)
{
    // Tujuan surat kelas AS USER
    $sql = "SELECT s.replid, CONCAT(UNIX_TIMESTAMP(s.lastactive), LPAD(s.replid, 7, '0')) AS ts
              FROM jbsletter.surat s, jbsletter.suratoutdst dst
             WHERE s.replid = dst.idsurat
               AND dst.iduser = '$iduser'
                   $filterTs
             ORDER BY s.lastactive DESC
             LIMIT $limit2";
    //echo "$sql<br>";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $found = false;
        for($i = 0; !$found && $i < count($letterList); $i++)
        {
            $found = $letterList[$i][0] == $row[0];
        }
        
        if ($found)
            continue;
        
        $letterList[] = [$row[0], $row[1]];
    }
    
    // Sumber surat keluar AS USER
    $sql = "SELECT s.replid, CONCAT(UNIX_TIMESTAMP(s.lastactive), LPAD(s.replid, 7, '0')) AS ts
              FROM jbsletter.surat s, jbsletter.suratoutsrcuser src
             WHERE s.replid = src.idsurat
               AND src.iduser = '$iduser'
                   $filterTs
             ORDER BY s.lastactive DESC
             LIMIT $limit2";
    //echo "$sql<br>";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $found = false;
        for($i = 0; !$found && $i < count($letterList); $i++)
        {
            $found = $letterList[$i][0] == $row[0];
        }
        
        if ($found)
            continue;
        
        $letterList[] = [$row[0], $row[1]];
    }
    
    // Tujuan surat masuk AS CC
    $sql = "SELECT s.replid, CONCAT(UNIX_TIMESTAMP(s.lastactive), LPAD(s.replid, 7, '0')) AS ts
              FROM jbsletter.surat s, jbsletter.suratoutsrccc src
             WHERE s.replid = src.idsurat
               AND src.iduser = '$iduser'
                   $filterTs
             ORDER BY s.lastactive DESC
             LIMIT $limit2";
    //echo "$sql<br>";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $found = false;
        for($i = 0; !$found && $i < count($letterList); $i++)
        {
            $found = $letterList[$i][0] == $row[0];
        }
        
        if ($found)
            continue;
        
        $letterList[] = [$row[0], $row[1]];
    }
    
    // Sumber surat keluar AS GROUP
    $sql = "SELECT s.replid, CONCAT(UNIX_TIMESTAMP(s.lastactive), LPAD(s.replid, 7, '0')) AS ts
              FROM jbsletter.surat s, jbsletter.suratoutsrcgroup src, jbsletter.anggota a
             WHERE s.replid = src.idsurat
               AND src.idkelompok = a.idkelompok
               AND a.nip = '$iduser'
                   $filterTs
             ORDER BY s.lastactive DESC
             LIMIT $limit2";
    //echo "$sql<br>";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $found = false;
        for($i = 0; !$found && $i < count($letterList); $i++)
        {
            $found = $letterList[$i][0] == $row[0];
        }
        
        if ($found)
            continue;
        
        $letterList[] = [$row[0], $row[1]];
    }
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
                   DAYOFWEEK(lastactive) AS hariaktif, DATE_FORMAT(lastactive, '%d-%m-%Y') AS tglaktif
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


function ShowLastLetter($jenis, $minListSuratTs)
{
    global $HOME_NLIST_SURAT;
    
    $iduser = SI_USER_ID();
    $limit2 = $HOME_NLIST_SURAT * 2;
    
    $filterTs = $minListSuratTs == 0 ?
                "" :
                "AND CONCAT(UNIX_TIMESTAMP(s.lastactive), LPAD(s.replid, 7, '0')) < '$minListSuratTs'";
    
    $letterList = [];
    
    if ($jenis == "ALL")
    {
        GetLetterListIn($letterList, $iduser, $limit2, $filterTs);
        GetLetterListOut($letterList, $iduser, $limit2, $filterTs);
    }
    elseif ($jenis == "IN")
    {
        GetLetterListIn($letterList, $iduser, $limit2, $filterTs);    
    }
    elseif ($jenis == "OUT")
    {
        GetLetterListOut($letterList, $iduser, $limit2, $filterTs);    
    }
    
    //echo "<pre>";
    //print_r($letterList);
    //echo "</pre>";
    $html = '';
    if (count($letterList) == 0)
    {
        $html .= "<tr height='30'>";
        $html .= "<td align='center' valign='middle' colspan='7'>";
        $html .= "<em>belum ada data surat</em>";
        $html .= "</td>";
        $html .= "</tr>";
        
        echo '{"minListSuratTs" : "NA", "html" : "' . $html . '"}';
        return;
    }
    
    // SWAP
    for($i = 0; $i < count($letterList) - 1; $i++)
    {
        for($j = $i + 1; $j < count($letterList); $j++)
        {
            if ($letterList[$j][1] > $letterList[$i][1])
            {
                $temp = $letterList[$j];
                $letterList[$j] = $letterList[$i];
                $letterList[$i] = $temp;
            }
        }   
    }
    
    //GET IDLIST
    $idlist = "";
    $n = 0;
    for($i = 0; $i < count($letterList); $i++)
    {
        $n++;
        
        if ($n > 1)
            $idlist .= ",";
        $idlist .= $letterList[$i][0];
        $minListSuratTs = $letterList[$i][1];
        
        if ($n == $HOME_NLIST_SURAT)
            break;
    }
    
    ShowDetailSurat($idlist, $minListSuratTs);
    
    //echo $idlist;
    
    //echo "<pre>";
    //print_r($letterList);
    //echo "</pre>";
}

function ShowLinkNextListSurat($jenis, $minListSuratTs)
{
    global $HOME_NLIST_SURAT;
    
    $iduser = SI_USER_ID();
    $limit2 = $HOME_NLIST_SURAT * 2;
    
    $filterTs = $minListSuratTs == 0 ?
                "" :
                "AND CONCAT(UNIX_TIMESTAMP(s.lastactive), LPAD(s.replid, 7, '0')) < '$minListSuratTs'";
    
    $letterList = [];
    
    if ($jenis == "ALL")
    {
        GetLetterListIn($letterList, $iduser, $limit2, $filterTs);
        GetLetterListOut($letterList, $iduser, $limit2, $filterTs);
    }
    elseif ($jenis == "IN")
    {
        GetLetterListIn($letterList, $iduser, $limit2, $filterTs);    
    }
    elseif ($jenis == "OUT")
    {
        GetLetterListOut($letterList, $iduser, $limit2, $filterTs);    
    }
    
    if (count($letterList) == 0)
	{
        // TIDAK ADA DATA!
        
		echo "";
		return;
	}
    
	
	$html  = "<tr style='background-color: #ddd'>";
	$html .= "<td align='center' valign='middle' colspan='7'>";
	$html .= "<a href='#' onclick='showLastSurat()'>";
	$html .= "<font style='color: green; font-weight: normal;'><em>selanjutnya</em>&nbsp;&nbsp;</font>";
	$html .= "</a>";
	$html .= "</td>";
	$html .= "</tr>";
	
	echo $html;		
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

function ShowDetailSurat($idlist, $minListSuratTs)
{
    $sql = "SELECT *, DATE_FORMAT(s.tanggal, '%d %M %Y') AS tanggalsurat, k.kategori, s.jenis AS jenissurat, s.replid AS idsurat
              FROM jbsletter.surat s, jbsletter.kategori k
             WHERE s.idkategori = k.replid
               AND s.replid IN ($idlist)
             ORDER BY s.lastactive DESC";
    $res = QueryDb($sql);
    
    $html = "";
    while($row = mysqli_fetch_array($res))
    {
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
        
        $html .= "<tr style='background-color : $bgcolor'; >";
        $html .= "<td align='left' style='border-color: #bbb; border-width: 1px;' valign='top'><font style='color: maroon;'><em>".$row['tanggalsurat']."</em></font><br>".$row['nomor']."<br>$jenis</td>";
        $html .= "<td align='left' style='border-color: #bbb; border-width: 1px;' valign='top'><strong>".$row['perihal']."</strong><br>Kategori: ".$row['kategori']."<br>Sifat: $sifat</td>";
        $html .= "<td align='center' style='font-size: 16px; border-color: #bbb; border-width: 1px;' valign='top'>$nberkas</td>";
        $html .= "<td align='left' style='border-color: #bbb; border-width: 1px;' valign='top'>$sumber</td>";
        $html .= "<td align='left' style='border-color: #bbb; border-width: 1px;' valign='top'>$tujuan</td>";
        $html .= "<td align='center' style='border-color: #bbb; border-width: 1px;' valign='top'>$comment</td>";
        $html .= "<td align='center' style='border-color: #bbb; border-width: 1px;' valign='top'><a href='#' onclick='viewLetter($idsurat)' title='baca surat'><img src='images/ico/lihat.png' border='0'></a></td>";
        $html .= "</tr>";
    }
    
    echo '{"minListSuratTs" : "' . $minListSuratTs . '", "html" : "' . $html . '"}';
}
?>