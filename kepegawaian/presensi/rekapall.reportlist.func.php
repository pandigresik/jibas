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
function ShowReportList($status)
{
    global $tahun30, $bulan30, $tanggal30, $tahun, $bulan, $tanggal;
?>
    <table border="1" cellpadding="2" cellspacing="0" width="1000" style="border-width: 1px; border-collapse: collapse" class="tab" id="table">
    <tr height="25">
        <td width="30" align="center" class="header">No</td>
        <td width="110" align="center" class="header">NIP</td>
        <td width="140" align="center" class="header">Nama</td>
        <td width="60" align="center" class="header">Hari</td>
        <td width="110" align="center" class="header">Tanggal</td>
        <td width="70" align="center" class="header">Status</td>
        <td width="75" align="center" class="header">Jam Masuk</td>
        <td width="75" align="center" class="header">Jam Pulang</td>
        <td width="120" align="center" class="header">Waktu Kerja</td>
        <td width="100" align="center" class="header">Keterangan</td>
        <td width="110" align="center" class="header">Sumber</td>
    </tr>  
<?php  $sql = "SELECT p.tanggal, DATE_FORMAT(p.tanggal, '%d %M %Y') AS tanggalview, p.jammasuk, p.jampulang,
                   p.jamwaktukerja, p.menitwaktukerja, p.status, p.keterangan, p.source, p.nip, pg.nama, WEEKDAY(p.tanggal) AS hari
              FROM jbssdm.presensi p, jbssdm.pegawai pg
             WHERE p.nip = pg.nip
               AND p.tanggal BETWEEN '$tahun30-$bulan30-$tanggal30' AND '$tahun-$bulan-$tanggal'
               AND p.status = '$status'
             ORDER BY pg.nama, p.tanggal DESC";       
    $res = QueryDb($sql);
    $no = 0;
    $totjkerja = 0;
    $totmkerja = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        $status = $row["status"];
        
        if ($status == 1)
        {
            $bgcolor = "#b3de81";
            $statusname = "Hadir";
        }
        elseif ($status == 2)
        {
            $bgcolor = "#eccbfb";
            $statusname = "Izin";
        }
        elseif ($status == 3)
        {
            $bgcolor = "#eccbfb";
            $statusname = "Sakit";
        }
        elseif ($status == 4)
        {
            $bgcolor = "#eccbfb";
            $statusname = "Cuti";
        }
        elseif ($status == 5)
        {
            $bgcolor = "#fbcbcb";
            $statusname = "Alpa";    
        }
        elseif ($status == 6)
        {
            $bgcolor = "#979797";
            $statusname = "Bebas";    
        }
        
        $totjkerja += $row["jamwaktukerja"];
        $totmkerja += $row["menitwaktukerja"];
    ?>
    <tr height="22">
        <td align="center"><?=$no?></td>
        <td align="left"><?=$row["nip"]?></td>
        <td align="left"><?=$row["nama"]?></td>
        <td align="left"><?=NamaHari($row["hari"])?></td>
        <td align="center"><?=$row["tanggalview"]?></td>
        <td align="center" bgcolor="<?=$bgcolor?>"><strong><?=$statusname?></strong></td>
        <td align="center"><?=$row["jammasuk"]?></td>
        <td align="center"><?=$row["jampulang"]?></td>
        <td align="left">
    <?php  if ($status == 1)
            echo $row["jamwaktukerja"] . " jam " . $row["menitwaktukerja"] . " menit";
        else
            echo ""; ?>
        </td>
        <td align="left"><?=$row["keterangan"]?></td>
        <td align="left"><?=$row["source"]?></td>
    </tr>
    <?php
    } //end while
    ?>
    </table>

<?php
} //end function
?>