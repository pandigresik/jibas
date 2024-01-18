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
    
    echo "<select id='ptkastat_perpus' name='ptkastat_perpus' class='inputbox' onchange='ptkastat_perpus_change()'>\r\n";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>\r\n";
    }
    echo "<option value='-1'>(Semua Perpustakaan)</option>\r\n";
    echo "</select>\r\n";
}

function ShowCbBulan()
{
    global $G_START_YEAR;
    
    $sql = "SELECT MONTH(DATE_SUB(NOW(), INTERVAL 30 DAY)),
                   YEAR(DATE_SUB(NOW(), INTERVAL 30 DAY)),
                   MONTH(NOW()), YEAR(NOW())";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $bln1 = $row[0];
    $thn1 = $row[1];
    $bln2 = $row[2];
    $thn2 = $row[3];
    
    echo "<select id='ptkastat_bulan1' name='ptkastat_bulan1' class='inputbox' onchange='ptkastat_perpus_change()'>\r\n";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == $bln1 ? "selected" : "";
        echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>\r\n";
    }
    echo "</select>\r\n";
    
    echo "<select id='ptkastat_tahun1' name='ptkastat_tahun1' class='inputbox' onchange='ptkastat_perpus_change()'>\r\n";
    for($i = $G_START_YEAR; $i <= $thn2; $i++)
    {
        $sel = $i == $thn1 ? "selected" : "";
        echo "<option value='$i' $sel>" . $i . "</option>\r\n";
    }
    echo "</select>\r\n";
    
    echo " s/d ";
    
    echo "<select id='ptkastat_bulan2' name='ptkastat_bulan2' class='inputbox' onchange='ptkastat_perpus_change()'>\r\n";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == $bln2 ? "selected" : "";
        echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>\r\n";
    }
    echo "</select>\r\n";
    
    echo "<select id='ptkastat_tahun2' name='ptkastat_tahun2' class='inputbox' onchange='ptkastat_perpus_change()'>\r\n";
    for($i = $G_START_YEAR; $i <= $thn2; $i++)
    {
        $sel = $i == $thn2 ? "selected" : "";
        echo "<option value='$i' $sel>" . $i . "</option>\r\n";
    }
    echo "</select>\r\n";
}

function ShowCbJumlah()
{
    echo "<select id='ptkastat_jumlah' name='ptkastat_jumlah' class='inputbox' onchange='ptkastat_perpus_change()'>\r\n";
    echo "<option value='10'>10</option>\r\n";
    echo "<option value='25'>25</option>\r\n";
    echo "<option value='50'>50</option>\r\n";
    echo "<option value='100'>100</option>\r\n";
    echo "</select>\r\n";
}

function ShowStatistics($perpus, $bln1, $thn1, $bln2, $thn2, $jum)
{
    $filter = "";
	if ($perpus != -1)
		$filter = " AND d.perpustakaan = $perpus";
			
	$sql = "SELECT COUNT(p.replid) AS num, pu.replid, pu.judul, ps.nama AS penulis, pb.nama AS penerbit, CONCAT(kt.kode, ' ', kt.nama) AS katalog
			  FROM jbsperpus.pinjam p, jbsperpus.daftarpustaka d, jbsperpus.pustaka pu,
                   jbsperpus.penulis ps, jbsperpus.penerbit pb, jbsperpus.katalog kt
			 WHERE d.kodepustaka = p.kodepustaka
               AND pu.penulis = ps.replid
               AND pu.penerbit = pb.replid
               AND pu.katalog = kt.replid
               AND p.tglpinjam BETWEEN '$thn1-$bln1-1' AND '$thn2-$bln2-31'
			   AND pu.replid = d.pustaka
                   $filter
			 GROUP BY pu.judul
			 ORDER BY num DESC
			 LIMIT $jum";
    $res = QueryDb($sql);
    
    if (mysqli_num_rows($res) == 0)
    {
        echo "<br><br><center><i>Tidak ada data</i></center>";
        return;
    }
    
    $rnd = random_int(0, 1000);
    ?>
    <img src="<?= "pustaka/pustaka.stat.image.php?perpus=$perpus&bln1=$bln1&thn1=$thn1&bln2=$bln2&thn2=$thn2&jum=$jum&rnd=$rnd" ?>" />
    <table width="98%" border="1" cellspacing="0" cellpadding="5" class="tab">
	<tr height="25">
		<td width='6%' align="center" class="header">No</td>
        <td width="14%" class="header" align="center">&nbsp;</td>
		<td width='*' align="center" class="header">Judul</td>
		<td width='16%' align="center" class="header">Jumlah<br>Peminjaman</td>
	</tr>
<?php
    $cnt = 0;
    while ($row = @mysqli_fetch_array($res))
	{
        $idpustaka = $row['replid'];
        
		$cnt += 1; ?>
		<tr height="20">
			<td align="center"><?=$cnt?></td>
            <td align='center' valign='middle'>
                <img src="pustaka/pustaka.cover.php?replid=<?=$idpustaka?>" height="60" />
            </td>
            <td align='left'>
                <font style='font-size: 9px; font-style: italic; color: #004000;'><?=$row['katalog']?></font><br>
                <font style='font-weight: bold; font-size: 14px'><?=$row['judul']?></font><br>
                <font style='font-size: 9px; font-style: italic;'><?=$row['penulis'] . " - " . $row['penerbit']?></font>
                <br><br>
                <div id='ptkastat_divdetail_<?=$cnt?>'>
                <a style='color: #0000ff; font-size: 9px; text-decoration: underline' onclick="ptkastat_showdetail(<?=$cnt?>, <?=$idpustaka?>)">
                    detail
                </a>    
                </div>
            </td>
            <td align="center">
                <font style='font-weight: bold; font-size: 14px'>
                <?=$row['num']?>
                </font>
            </td>
		</tr>
<?php }
    echo "</table>";
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
    echo "<a style='color: #0000ff; font-size: 9px; text-decoration: underline' onclick='ptkastat_hidedetail($cnt, $idpustaka)'>";
    echo "sembunyikan";
    echo "</a>";
}
?>