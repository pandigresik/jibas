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
    <legend><strong>Nilai Pelajaran</strong></legend>
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
    $colwidth = $naspek == 0 ? "*" : round(50 / $naspek) . "%";
?>
    <table width="100%" border="1" class="tab" id="table" bordercolor="#000000" style="border-collapse: collapse; border-width: 1px;">
    <tr>
        <td width="5%" rowspan="2" class="headerlong"><div align="center">No</div></td>
        <td width="35%" rowspan="2" class="headerlong"><div align="center">Pelajaran</div></td>
        <td width="10%" rowspan="2" class="headerlong"><div align="center">KKM</div></td>
<?php 	for($i = 0; $i < count($aspekarr); $i++)
            echo "<td class='headerlong' colspan='2' align='center' width='$colwidth'>" . $aspekarr[$i][1] . "</td>"; ?>
    </tr>
    <tr>
<?php      $colwidth = $naspek == 0 ? "*" : round(50 / (2 * $naspek)) . "%";
        for($i = 0; $i < count($aspekarr); $i++)
            echo "<td class='headerlong' align='center' width='$colwidth'>Nilai</td>
        <td class='headerlong' align='center' width='$colwidth'>Predikat</td>"; ?>
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
            $colspan = $naspek * 2 + 3;
            echo "<tr style='height: 30px'>
                  <td colspan='$colspan' align='left' style='font-size:12px; font-weight: bold; background-color: #ddd'>$nmkpel</td>
                  </tr>";
        }

        $sql = "SELECT nilaimin 
                  FROM infonap
                 WHERE idpelajaran = $idpel
                   AND idsemester = $semester
                   AND idkelas = $kelas";
        $res = QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $nilaimin = $row[0];

        echo "<tr height='30'>";
        echo "<td align='center' valign='middle' style='background-color: #f5f5f5'>$no</td>";
        echo "<td align='left' valign='middle'>$nmpel</td>";
        echo "<td align='center' valign='middle' style='font-size: 12px'>$nilaimin</td>";

        for($i = 0; $i < count($aspekarr); $i++)
        {
            $asp = $aspekarr[$i][0];

            $na = "";
            $nh = "";
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
                $na = $row[0];
                $nh = $row[1];
                $komentar = $row[2];
            }
            echo "<td align='center' valign='middle' style='font-size: 12px'><strong>$na</strong></td>
                  <td align='center' valign='middle' style='font-size: 12px'><strong>$nh</strong></td>";
        }

        echo "</tr>";
    }
    ?>
    </table>
</fieldset>
<br>
