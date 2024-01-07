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
function GetTitle()
{
    global $idpustaka;
    
    $sql = "SELECT judul
              FROM pustaka
             WHERE replid = '".$idpustaka."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    
    return $row[0];
}

function GetActive($iddp, $status)
{
    $html = "";
    if ($status == 1)
    {
        $html  = "<a title='Set Non Aktif' onclick='SetAktif($iddp, 0)'>\r\n";
        $html .= "<img src='../img/ico/aktif.png' border='0'>\r\n";
        $html .= "</a>\r\n";
    }
    else
    {
        $html  = "<a title='Set Aktif' onclick='SetAktif($iddp, 1)'>\r\n";
        $html .= "<img src='../img/ico/nonaktif.png' border='0'>\r\n";
        $html .= "</a>\r\n";
    }
    
    return $html;
}

function ShowDelLink($iddp, $kodepustaka, $rowno)
{
    $sql = "SELECT COUNT(replid)
              FROM jbsperpus.pinjam
             WHERE kodepustaka = '".$kodepustaka."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $npinjam = $row[0];
    
    if ($npinjam == 0)
        return "<a title='Hapus pustaka ini' href='#' onclick='DelPustaka($iddp, $rowno)'><img src='../img/ico/hapus.png' border='0'></a>";
    
    return "<img title='Tidak bisa menghapus pustaka ini karena telah dipinjam sebelumnya' src='../img/ico/nonhapus.png'>";   
}

function ShowList()
{
    global $idpustaka;
    
    $sql = "SELECT dp.replid, dp.kodepustaka, dp.status, dp.info1, dp.info2, p.nama, dp.aktif
              FROM daftarpustaka dp, perpustakaan p
             WHERE dp.perpustakaan = p.replid
               AND pustaka = '$idpustaka'  
             ORDER BY dp.kodepustaka";
    $res = QueryDb($sql);
    $cnt = 0;
    while($row = mysqli_fetch_array($res))
    {
        $iddp = $row['replid'];
        $aktif = $row['aktif'];
        
        $keterangan = "";
        
        $cnt += 1;
        $status = (0 == (int)$row['status'] ? "Dipinjam" : "Tersedia");
        $color = (0 == (int)$row['status'] ? "green" : "blue");
        if (0 == (int)$row['status'])
        {
            $kodepustaka = $row['kodepustaka'];
            
            $sql = "SELECT DATE_FORMAT(tglpinjam, '%d-%b-%Y') AS tglpinjam, DATE_FORMAT(tglkembali, '%d-%b-%Y') AS tglkembali,
                           idanggota, keterangan, info1, nis, nip, idmember
                      FROM pinjam
                     WHERE kodepustaka = '".$kodepustaka."'";
            $res2 = QueryDb($sql);
            if (mysqli_num_rows($res2) > 0)
            {
                $row2 = mysqli_fetch_array($res2);
                $jenisanggota = $row2['info1'];
                if ($jenisanggota == "siswa")
                {
                    $jenisanggota = "Siswa";
                    $idanggota = $row2['nis'];
                    $sql = "SELECT nama
                              FROM jbsakad.siswa
                             WHERE nis = '".$idanggota."'";
                }
                elseif ($jenisanggota == "pegawai")
                {
                    $jenisanggota = "Pegawai";
                    $idanggota = $row2['nip'];
                    $sql = "SELECT nama
                              FROM jbssdm.pegawai
                             WHERE nip = '".$idanggota."'";
                }
                else
                {
                    $jenisanggota = "Anggota Lain";
                    $idanggota = $row2['idmember'];
                    $sql = "SELECT nama
                              FROM jbsperpus.anggota
                             WHERE noregistrasi = '".$idanggota."'";
                }
                $res3 = QueryDb($sql);
                $row3 = mysqli_fetch_row($res3);
                $namaanggota = $row3[0];
                
                $keterangan = "($jenisanggota) $idanggota - $namaanggota<br><i>Tgl Pinjam: " . $row2['tglpinjam'] . ", Tgl Kembali: " . $row2['tglkembali'] . "</i>";
            }
        }
        
        echo "<tr id='row$cnt'>\r\n";
        echo "<input type='hidden' id='iddp$cnt' value='$iddp'>";
        echo "<td align='center'>$cnt</td>\r\n";
        echo "<td align='left'><font style='font-weight: bold; font-size: 12px'>". $row['kodepustaka'] . "</font><br><i>Barcode: " . $row['info1'] . "</i></td>\r\n";
        echo "<td align='left'>". $row['nama'] . "</td>\r\n";
        echo "<td align='center'><font color='$color'><strong>". $status . "</strong></font></td>\r\n";
        echo "<td align='left'>$keterangan</td>\r\n";
        echo "<td align='center'><div id='divStatus$iddp'>" . GetActive($iddp, $aktif) . "</div></td>\r\n";
        echo "<td align='center'><input type='checkbox' id='ck$cnt'></td>\r\n";
        echo "<td align='center'>" . ShowDelLink($iddp, $row['kodepustaka'], $cnt) . "</td>\r\n";
        echo "</tr>\r\n";
    }
    echo "<input type='hidden' id='nlist' value='$cnt'>\r\n";
}
?>