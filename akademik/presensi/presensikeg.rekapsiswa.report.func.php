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
$sql = "SELECT DISTINCT pk.idkegiatan
          FROM jbssat.frpresensikegiatan pk, jbssat.frkegiatan k
         WHERE pk.idkegiatan = k.replid
           AND MONTH(pk.date_in) = $bulan
           AND YEAR(pk.date_in) = $tahun
           AND pk.nis = '".$nis."'";
$res = QueryDb($sql);

if (mysqli_num_rows($res) == 0)
{
    CloseDb();
    
    echo "<center>Tidak ada data presensi</center>";
    exit();
}

?>

<?php
if ($showbutton)
{
?>
<table width="99%" align="center" border="0">
<tr>
    <td align="right">
        <a href="#" onClick="document.location.reload()">
            <img src="../images/ico/refresh.png" border="0"/>&nbsp;Refresh
        </a>&nbsp;&nbsp;
        <a href="#" onclick="excel()">
            <img src="../images/ico/excel.png" border="0" />&nbsp;Excel
        </a>&nbsp;&nbsp;
        <a href="#" onclick="cetak()">
            <img src="../images/ico/print.png" border="0" />&nbsp;Cetak
        </a>
    </td>
</tr>    
</table>
<?php
}
?>

<table class="tab" id="table" border="1" align="left" style="border-collapse:collapse" width="99%" align="center" bordercolor="#000000">
<tr height="30" align="center" class="header">		
    <td width="5%">No</td>
    <td width="*">Kegiatan</td>
    <td width="10%">Departemen</td>
    <td width="20%">Peserta</td>
    <td width="8%">Jumlah Peserta</td>
    <td width="8%">Jumlah Hari<br>(A)</td>
    <td width="8%">Jumlah Kehadiran (B)</td>
    <td width="8%">Persen Kehadiran (B/A)</td>
<?php  if ($showbutton) { ?>    
    <td width="8%">&nbsp;</td>
<?php  } ?>    
</tr>
<?php
$cnt = 0;
while($row = mysqli_fetch_row($res))
{
    $idkegiatan = $row[0];
    
    $sql = "SELECT kegiatan, departemen, jenispeserta, idkelompok, iddepartemen,
                   idtingkat, idkelas, kelompokpegawai, jeniswaktu, aktif
              FROM jbssat.frkegiatan
             WHERE replid = $idkegiatan";
    $res2 = QueryDb($sql);
    
    if (mysqli_num_rows($res2) == 0)
        continue;
    
    $row2 = mysqli_fetch_array($res2);
    
    $jenispeserta = $row2['jenispeserta'];
    $iddepartemen = $row2['iddepartemen'];
        
    $sql = "SELECT COUNT(DISTINCT pk.date_in)
              FROM jbssat.frpresensikegiatan pk
             WHERE MONTH(pk.date_in) = $bulan
               AND YEAR(pk.date_in) = $tahun
               AND pk.idkegiatan = $idkegiatan";
    $res3 = QueryDb($sql);
    $row3 = mysqli_fetch_row($res3);
    $nhari = $row3[0];
    
    $sql = "SELECT COUNT(pk.replid)
              FROM jbssat.frpresensikegiatan pk
             WHERE MONTH(pk.date_in) = $bulan
               AND YEAR(pk.date_in) = $tahun
               AND pk.nis = '$nis'
               AND pk.idkegiatan = $idkegiatan";
    $res3 = QueryDb($sql);
    $row3 = mysqli_fetch_row($res3);
    $nhadir = $row3[0];
    
    $persen = $nhari == 0 ? 0 : 100 * round($nhadir / $nhari, 2);
    
    $cnt += 1;
    
    echo "<tr height='25'>\r\n";
    echo "<td align='center'>$cnt</td>\r\n";
    echo "<td align='left'>" . $row2['kegiatan'] . "</td>\r\n";
    echo "<td align='left'>" . $row2['departemen'] . "</td>\r\n";
    echo "<td align='left'>" . GetPeserta($idkegiatan, $jenispeserta, $iddepartemen) . "</td>\r\n";
    echo "<td align='center'>" . GetNPeserta($idkegiatan, $jenispeserta, $iddepartemen) . "</td>\r\n";
    echo "<td align='center'>$nhari</td>\r\n";
    echo "<td align='center'>$nhadir</td>\r\n";
    echo "<td align='center'>$persen %</td>\r\n";
    if ($showbutton)
        echo "<td align='center'><input type='button' class='but' value='detail' onclick=\"detail($idkegiatan)\" </td>\r\n";
    echo "</tr>\r\n";
}
echo "</table>\r\n";

function GetPeserta($idkegiatan, $jenispeserta, $iddepartemen)
{
    $peserta = "";
    
    if ($jenispeserta == 0)
    {
        return "Semua Siswa dan Pegawai";
    }
    else if ($jenispeserta == 1)
    {
        return "Semua Siswa";
    }
    else if ($jenispeserta == 2)
    {
        return "Semua Pegawai";
    }
    else if ($jenispeserta == 3)
    {
        return "Siswa " . $iddepartemen;
    }
    else if ($jenispeserta == 4)
    {
        $sql = "SELECT t.tingkat, t.departemen
                  FROM jbssat.frkegiatan k, jbsakad.tingkat t
                 WHERE k.idtingkat = t.replid
                   AND k.replid = $idkegiatan";
        $res = QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            
            $tingkat = $row[0];
            $dept = $row[1];
            
            return "Siswa $dept tingkat $tingkat";
        }
        
        return "";
    }
    else if ($jenispeserta == 5)
    {
        $sql = "SELECT kl.kelas, t.tingkat, t.departemen
                  FROM jbssat.frkegiatan k, jbsakad.kelas kl, jbsakad.tingkat t
                 WHERE k.idkelas = kl.replid
                   AND kl.idtingkat = t.replid
                   AND k.replid = $idkegiatan";
        
        $res = QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            
            $kelas = $row[0];
            $tingkat = $row[1];
            $dept = $row[2];
            
            return "Siswa $dept tingkat $tingkat kelas $kelas";
        }
        
        return "";           
    }
    else if ($jenispeserta == 6)
    {
        return "Pegawai bagian Akademik";
    }
    else if ($jenispeserta == 7)
    {
        return "Pegawai bagian Non Akademik";
    }
    else if ($jenispeserta == 8)
    {
        $sql = "SELECT kl.kelompok
                  FROM jbssat.frkegiatan k, jbssat.frkelompok kl
                 WHERE k.idkelompok = kl.replid
                   AND k.replid = $idkegiatan";
                   
        $res = QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            
            $kelompok = $row[0];
            
            return "Kelompok $kelompok";
        }
        
        return "";                      
    }
    
    return "Lainnya";
}

function GetNPeserta($idkegiatan, $jenispeserta, $iddepartemen)
{
    $npeserta = 0;
    
    if ($jenispeserta == 0)
    {
        // peserta = "Semua Siswa dan Pegawai";
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran ta
                 WHERE f.nis = s.nis
                   AND s.idkelas = k.replid
                   AND k.idtahunajaran = ta.replid 
                   AND ta.aktif = 1 
                   AND f.active = 1 
                   AND f.verify = 1
                   AND s.aktif = 1";
        $nsiswa = FetchSingle($sql);
        
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbssdm.pegawai p
                 WHERE f.nip = p.nip
                   AND f.active = 1 
                   AND f.verify = 1
                   AND p.aktif = 1";
        $npegawai = FetchSingle($sql);
        
        $npeserta = $npegawai + $nsiswa;
    }
    else if ($jenispeserta == 1)
    {
        // peserta = "Semua Siswa";
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran ta
                 WHERE f.nis = s.nis
                   AND s.idkelas = k.replid
                   AND k.idtahunajaran = ta.replid 
                   AND ta.aktif = 1 
                   AND f.active = 1 
                   AND f.verify = 1
                   AND s.aktif = 1";
        $npeserta = FetchSingle($sql);           
    }
    else if ($jenispeserta == 2)
    {
        // peserta = "Semua Pegawai";
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbssdm.pegawai p
                 WHERE f.nip = p.nip
                   AND f.active = 1 
                   AND f.verify = 1
                   AND p.aktif = 1";
        $npeserta = FetchSingle($sql);
    }
    else if ($jenispeserta == 3)
    {
        // peserta = "Siswa " + iddepartemen;
        $sql = @"SELECT COUNT(f.replid)
                   FROM jbssat.frdata f, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran ta
                  WHERE f.nis = s.nis
                    AND s.idkelas = k.replid
                    AND k.idtahunajaran = ta.replid 
                    AND ta.aktif = 1 
                    AND f.active = 1 
                    AND f.verify = 1
                    AND s.aktif = 1
                    AND ta.departemen = '".$iddepartemen."'";
        $npeserta = FetchSingle($sql);        
    }
    else if ($jenispeserta == 4)
    {
        $sql = "SELECT k.idtingkat
                  FROM jbssat.frkegiatan k
                 WHERE k.replid = $idkegiatan";
        $idtingkat = FetchSingle($sql);

        $sql =  "SELECT COUNT(f.replid)
                   FROM jbssat.frdata f, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran ta
                  WHERE f.nis = s.nis
                    AND s.idkelas = k.replid
                    AND k.idtahunajaran = ta.replid 
                    AND ta.aktif = 1 
                    AND f.active = 1 
                    AND f.verify = 1
                    AND s.aktif = 1
                    AND k.idtingkat = '".$idtingkat."'";
        $npeserta = FetchSingle($sql);
    }
    else if ($jenispeserta == 5)
    {
        $sql = "SELECT k.idkelas
                  FROM jbssat.frkegiatan k
                 WHERE k.replid = $idkegiatan";
        $idkelas = FetchSingle($sql);

        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran ta
                 WHERE f.nis = s.nis
                   AND s.idkelas = k.replid
                   AND k.idtahunajaran = ta.replid 
                   AND ta.aktif = 1 
                   AND f.active = 1 
                   AND f.verify = 1
                   AND s.aktif = 1
                   AND k.replid = $idkelas";
        $npeserta = FetchSingle($sql);
    }
    else if ($jenispeserta == 6)
    {
        // peserta = "Pegawai bagian Akademik";
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbssdm.pegawai p
                 WHERE f.nip = p.nip
                   AND f.active = 1 
                   AND f.verify = 1
                   AND p.aktif = 1
                   AND p.bagian = 'Akademik'";
        $npeserta = FetchSingle($sql);
    }
    else if ($jenispeserta == 7)
    {
        // peserta = "Pegawai bagian Non Akademik";
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbssdm.pegawai p
                 WHERE f.nip = p.nip
                   AND f.active = 1 
                   AND f.verify = 1
                   AND p.aktif = 1
                   AND p.bagian = 'Non Akademik'";
        $npeserta = FetchSingle($sql);
    }
    else if ($jenispeserta == 8)
    {
        $sql = "SELECT k.idkelompok
                  FROM jbssat.frkegiatan k
                 WHERE k.replid = $idkegiatan";
        $idkelompok = FetchSingle($sql);

        $sql = "SELECT COUNT(a.replid)
                  FROM jbssat.franggota a
                 WHERE a.idkelompok = $idkelompok";
        $npeserta = FetchSingle($sql);        
    }
    else if ($jenispeserta == 9)
    {
        $sql = "SELECT COUNT(ps.replid)
                  FROM jbssat.frpeserta ps
                 WHERE ps.idkegiatan = $idkegiatan";
        $npeserta = FetchSingle($sql);        
    }
    
    return $npeserta;
}
?>