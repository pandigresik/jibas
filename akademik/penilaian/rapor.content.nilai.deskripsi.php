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
<fieldset>
    <legend><strong>Deskripsi Nilai Pelajaran</strong></legend>
<?php $sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
              FROM infonap i, nap n, aturannhb a, dasarpenilaian d
             WHERE i.replid = n.idinfo AND n.nis = '$nis' 
               AND i.idsemester = '$semester' 
               AND i.idkelas = '$kelas'
               AND n.idaturan = a.replid 	   
               AND a.dasarpenilaian = d.dasarpenilaian
               AND d.aktif = 1";
    $res = QueryDb($sql);
    $i = 0;
    while($row = mysqli_fetch_row($res))
    {
        $aspekarr[$i++] = [$row[0], $row[1]];
    }
    $naspek = count($aspekarr);
    $colwidth = $naspek == 0 ? "*" : round(55 / count($aspekarr)) . "%"; ?>
    <table width="100%" border="1" class="tab" id="table" bordercolor="#000000" style="border-collapse: collapse; border-width: 1px;">
    <tr>
        <td width="5%" class="headerlong"><div align="center">No</div></td>
        <td width="25%" class="headerlong"><div align="center">Pelajaran</div></td>
        <td width="15%" class="headerlong"><div align="center">Aspek</div></td>
        <td width="*" class="headerlong"><div align="center">Deskripsi</div></td>
    </tr>
<?php $sql = "SELECT pel.replid, pel.nama, pel.idkelompok, kpel.kelompok
              FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel, kelompokpelajaran kpel 
             WHERE uji.replid = niluji.idujian 
               AND niluji.nis = sis.nis 
               AND uji.idpelajaran = pel.replid 
               AND pel.idkelompok = kpel.replid
               AND uji.idsemester = $semester
               AND uji.idkelas = $kelas
               AND sis.nis = '$nis' 
             GROUP BY kpel.urutan, pel.nama";
    $respel = QueryDb($sql);
    $previdkpel = 0;
    $no = 0;
    while($rowpel = mysqli_fetch_row($respel))
    {
        $no += 1;
        $idpel = $rowpel[0];
        $nmpel = $rowpel[1];
        $idkpel = $rowpel[2];
        $nmkpel = $rowpel[3];

        if ($idkpel != $previdkpel)
        {
            $previdkpel = $idkpel;
            echo "<tr style='height: 30px'>
                  <td colspan='4' align='left' style='font-size:12px; font-weight: bold; background-color: #ddd'>$nmkpel</td>
                  </tr>";
        }

        echo "<tr height='40'>";
        echo "<td align='center' rowspan='$naspek' valign='middle' style='background-color: #f5f5f5;'>$no</td>";
        echo "<td align='left' rowspan='$naspek' valign='middle'>$nmpel</td>";

        $set_tr = false;
        for($i = 0; $i < count($aspekarr); $i++)
        {
            $asp = $aspekarr[$i][0];
            $nmasp = $aspekarr[$i][1];

            $komentar = "";

            $sql = "SELECT nilaiangka, nilaihuruf, komentar
                      FROM infonap i, nap n, aturannhb a 
                     WHERE i.replid = n.idinfo 
                       AND n.nis = '$nis' 
                       AND i.idpelajaran = '$idpel' 
                       AND i.idsemester = '$semester' 
                       AND i.idkelas = '$kelas'
                       AND n.idaturan = a.replid 	   
                       AND a.dasarpenilaian = '".$asp."'";
            $res = QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
            {
                $row = mysqli_fetch_row($res);
                $komentar = $row[2];
            }

            if ($set_tr)
                echo "<tr height='40'>";

            echo "<td align='left' valign='middle' style='font-size: 12px'>$nmasp</td>
                  <td align='left' valign='middle'>$komentar</td>";
            echo "</tr>";

            $set_tr = true;
        }
    }
?>
    </table>
</fieldset>
<br>
