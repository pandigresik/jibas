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
function ShowCbPustaka()
{
    $sql = "SELECT replid, nama
              FROM jbsperpus.perpustakaan
             ORDER BY nama";
    $res = QueryDb($sql);
    
    echo "<select id='ptkadaftar_perpus' name='ptkadaftar_perpus' class='inputbox' onchange='ptkadaftar_perpus_change()'>\r\n";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>\r\n";
    }
    echo "<option value='-1'>(Semua Perpustakaan)</option>\r\n";
    echo "</select>\r\n";
}

function ShowCbPilih()
{
    echo "<select id='ptkadaftar_pilih' name='ptkadaftar_pilih' class='inputbox' onchange='ptkadaftar_pilih_change()'>\r\n";
    echo "<option value='KAT'>Katalog</option>\r\n";
    echo "<option value='PNLS'>Penulis</option>\r\n";
    echo "<option value='PNBT'>Penerbit</option>\r\n";
    echo "</select>\r\n";
}

function ShowCbKriteria($choice)
{
    if ($choice == "KAT")
    {
        $sql = "SELECT replid, CONCAT('[', kode, '] ', nama)
                  FROM jbsperpus.katalog
                 ORDER BY nama";    
    }
    elseif($choice == "PNLS")
    {
        $sql = "SELECT replid, nama
                  FROM jbsperpus.penulis
                 ORDER BY nama";
    }
    elseif($choice == "PNBT")
    {
        $sql = "SELECT replid, nama
                  FROM jbsperpus.penerbit
                 ORDER BY nama";
    }
    $res = QueryDb($sql);
    
    echo "<select id='ptkadaftar_kriteria' name='ptkadaftar_kriteria' class='inputbox' style='width: 300px;' onchange='ptkadaftar_kriteria_change()'>\r\n";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>\r\n";
    }
    echo "</select>\r\n";
}

function ShowNavigation($perpus, $pilih, $kriteria, $halaman, $npage, $ndata)
{
    $onclickprev = "";
    $onclicknext = "";
    if ($halaman - 1 >= 1)
    {
        $goto = $halaman - 1;
        $onclickprev = "onclick='ptkadaftar_goto($perpus, \"$pilih\", $kriteria, $goto)'";
        
    }
    if ($halaman + 1 <= $npage)
    {
        $goto = $halaman + 1;
        $onclicknext = "onclick='ptkadaftar_goto($perpus, \"$pilih\", $kriteria, $goto)'";
        
    }
    
    echo "<input type='button' style='height: 27px' class='but' value='    <    ' $onclickprev>";
    echo "<input type='button' style='height: 27px' class='but' value='    >    ' $onclicknext>";
    echo "  Halaman:";
    echo "<select id='ptkadaftar_pageno' name='ptkadaftar_pageno' onchange='ptkadaftar_pageno_change($perpus, \"$pilih\", $kriteria)' class='inputbox'>";
    for($i = 1; $i <= $npage; $i++)
    {
        $sel = $halaman == $i ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    } 
    echo "</select>dari $npage, Jumlah Pustaka: $ndata";
}

function ShowList($perpus, $pilih, $kriteria, $halaman)
{
    global $Pustaka_NItemPerView;
    
    $limit = ($halaman - 1) * $Pustaka_NItemPerView;
    
    $where = "";
    if ($pilih == "KAT")
        $where = "AND p.katalog = '".$kriteria."'";
    elseif ($pilih == "PNLS")   
        $where = "AND p.penulis = '".$kriteria."'";
    elseif ($pilih == "PNBT")   
        $where = "AND p.penerbit = '".$kriteria."'";    
    
    if ($perpus == -1)
    {
        $sql = "SELECT DISTINCT p.replid, p.judul, p.keteranganfisik, ps.nama AS penulis, pb.nama AS penerbit, CONCAT(kt.kode, ' ', kt.nama) AS katalog
                  FROM jbsperpus.pustaka p, jbsperpus.penulis ps, jbsperpus.penerbit pb, jbsperpus.katalog kt
                 WHERE p.penulis = ps.replid
                   AND p.penerbit = pb.replid
                   AND p.katalog = kt.replid
                   $where
                 ORDER BY judul
                 LIMIT $limit, $Pustaka_NItemPerView";
        
        $nsql = "SELECT COUNT(DISTINCT p.replid)
                   FROM jbsperpus.pustaka p, jbsperpus.penulis ps, jbsperpus.penerbit pb, jbsperpus.katalog kt
                  WHERE p.penulis = ps.replid
                    AND p.penerbit = pb.replid
                    AND p.katalog = kt.replid
                    $where";         
    }
    else
    {
        $sql = "SELECT DISTINCT p.replid, p.judul, p.keteranganfisik, ps.nama AS penulis, pb.nama AS penerbit, CONCAT(kt.kode, ' ', kt.nama) AS katalog
                  FROM jbsperpus.pustaka p, jbsperpus.penulis ps, jbsperpus.penerbit pb, jbsperpus.daftarpustaka dp, jbsperpus.katalog kt
                 WHERE p.penulis = ps.replid
                   AND p.penerbit = pb.replid
                   AND p.katalog = kt.replid
                   AND p.replid = dp.pustaka
                   $where
                   AND dp.perpustakaan = '$perpus'
                 ORDER BY judul
                 LIMIT $limit, $Pustaka_NItemPerView";
        
        $nsql = "SELECT COUNT(DISTINCT p.replid)
                   FROM jbsperpus.pustaka p, jbsperpus.penulis ps, jbsperpus.penerbit pb, jbsperpus.daftarpustaka dp, jbsperpus.katalog kt
                  WHERE p.penulis = ps.replid
                    AND p.penerbit = pb.replid
                    AND p.katalog = kt.replid
                    AND p.replid = dp.pustaka
                    $where
                    AND dp.perpustakaan = '".$perpus."'";         
    }
    
    echo "<div style='overflow: auto; height: 350px'>";
    
    $ndata = FetchSingle($nsql);
    $npage = floor($ndata / $Pustaka_NItemPerView);
    if ($ndata % $Pustaka_NItemPerView != 0)
        $npage += 1;
    
    if ($ndata > 0)
        ShowNavigation($perpus, $pilih, $kriteria, $halaman, $npage, $ndata);
    ?>    
    
    <table border="1" width="99%" id="table" class="tab"
           align="left" cellpadding="2" style="border-collapse:collapse"
           cellspacing="2" bordercolor="#000000">
    <tr height="30">		
        <td width="5%" class="header" align="center">No</td>
        <td width="14%" class="header" align="center">&nbsp;</td>
        <td width="*" class="header" align="center">Pustaka</td>
        <td width="10%" class="header" align="center">Jumlah</td>
    </tr>
<?php
    if ($ndata == 0)
    {
        ?>
        <tr height='30'>
            <td colspan='5' align='center'><i>Tidak ada data</i></td>
        </tr>        
        <?php
        return;
    }
    
    $res = QueryDb($sql);        
    $cnt = ($halaman - 1) * $Pustaka_NItemPerView;
    while($row = mysqli_fetch_array($res))
    {
        $idpustaka = $row['replid'];
        if ($perpus == -1)
        {
            $sql = "SELECT COUNT(replid)
                      FROM jbsperpus.daftarpustaka
                     WHERE pustaka = '".$idpustaka."'";    
        }
        else
        {
            $sql = "SELECT COUNT(replid)
                      FROM jbsperpus.daftarpustaka
                     WHERE pustaka = '$idpustaka'
                       AND perpustakaan = '".$perpus."'"; 
        }
        $ndata = FetchSingle($sql);
        
        $cnt += 1; ?>
        <tr>
            <td align='center' valign='middle'><?=$cnt?></td>
            <td align='center' valign='middle'>
                <img src="pustaka/pustaka.cover.php?replid=<?=$idpustaka?>" height="60" />
            </td>
            <td align='left' valign='middle'>
                <font style='font-size: 9px; font-style: italic; color: #004000;'><?=$row['katalog']?></font><br>
                <font style='font-weight: bold; font-size: 14px'><?=$row['judul']?></font><br>
                <font style='font-size: 9px; font-style: italic;'><?=$row['penulis'] . " - " . $row['penerbit']?></font>
                <br><br>
                <div id='ptkadaftar_divdetail_<?=$cnt?>'>
                <a style='color: #0000ff; font-size: 9px; text-decoration: underline' onclick="ptkadaftar_showdetail(<?=$cnt?>, <?=$idpustaka?>)">
                    detail
                </a>    
                </div>
                <br>
            </td>
            <td align='center' valign='middle'>
                <font style='font-weight: bold; font-size: 12px;'><?=$ndata?></font>
            </td>
        </tr>
        <?php
    }

    echo "</table><br>";

    if ($ndata > 0)
        ShowNavigation($perpus, $pilih, $kriteria, $halaman, $npage, $ndata);
        
    echo "</div>";    
}

function ShowDetailPustaka($cnt, $idpustaka)
{
    $sql = "SELECT IF(LENGTH(TRIM(abstraksi)) = 0, '-', abstraksi) AS abstraksi,
                   IF(LENGTH(TRIM(keteranganfisik)) = 0, '-', keteranganfisik) AS keteranganfisik,
                   IF(LENGTH(TRIM(keyword)) = 0, '-', keyword) AS keyword
              FROM jbsperpus.pustaka
             WHERE replid = '".$idpustaka."'";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        echo "-";
        return;
    }
    
    $row = mysqli_fetch_array($res);
    echo "<font style='color: #444;'><strong>Kata Kunci:</strong></font><br>";
    echo $row['keyword'];
    echo "<br><br>";
    echo "<font style='color: #444;'><strong>Abstraksi:</strong></font><br>";
    echo $row['abstraksi'];
    echo "<br><br>";
    echo "<font style='color: #444;'><strong>Keterangan Fisik:</strong></font><br>";
    echo $row['keteranganfisik'];
    echo "<br><br>";
    echo "<a style='color: #0000ff; font-size: 9px; text-decoration: underline' onclick='ptkadaftar_hidedetail($cnt, $idpustaka)'>";
    echo "sembunyikan";
    echo "</a>";
}
?>