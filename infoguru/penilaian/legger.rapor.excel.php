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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../sessionchecker.php');

$departemen = $_REQUEST['departemen'];
$tahunajaran = $_REQUEST['tahunajaran'];
$tingkat = $_REQUEST['tingkat'];
$kelas = $_REQUEST['kelas'];
$semester = $_REQUEST['semester'];
$pelajaran = $_REQUEST['pelajaran'];

header('Content-Type: application/vnd.ms-excel');
header('Content-Type: application/x-msexcel');
header('Content-Disposition: attachment; filename=LeggerRapor.xls');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

OpenDb();

$sql = "SELECT tahunajaran FROM tahunajaran WHERE replid = '".$tahunajaran."'";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$ta  = $row[0];

$sql = "SELECT kelas FROM kelas WHERE replid = '".$kelas."'";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$kls = $row[0];

$sql = "SELECT semester FROM semester WHERE replid = '".$semester."'";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$sem = $row[0];

$sql = "SELECT nama FROM pelajaran WHERE replid = '".$pelajaran."'";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$pel = $row[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Legger Nilai Rapor</title>
</head>
<body>
<table border="0">
<tr>
    <td colspan="2" align="left"><h3>Legger Nilai Rapor</h3></td>
</tr>
<tr>
    <td align="left">Departemen:</td>
    <td align="left"><?=$departemen?></td>
</tr>
<tr>
    <td align="left">Tahun Ajaran:</td>
    <td align="left"><?=$ta?></td>
</tr>
<tr>
    <td align="left">Kelas:</td>
    <td align="left"><?=$kls?></td>
</tr>
<tr>
    <td align="left">Semester:</td>
    <td align="left"><?=$sem?></td>
</tr>
<tr>
    <td align="left">Pelajaran:</td>
    <td align="left"><?=$pel?></td>
</tr>
</table>
<br>

<?php
$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
          FROM infonap i, nap n, aturannhb a, dasarpenilaian d
         WHERE i.replid = n.idinfo
           AND n.idaturan = a.replid 	   
           AND a.dasarpenilaian = d.dasarpenilaian
           AND i.idpelajaran = '$pelajaran'  
           AND i.idsemester = '$semester' 
           AND i.idkelas = '".$kelas."'";
$res = QueryDb($sql);
$aspekarr = [];
while($row = mysqli_fetch_row($res))
{
    $aspekarr[] = [$row[0], $row[1]];
}
$naspek = count($aspekarr);
$colwidth = $naspek == 0 ? "*" : round(600 / $naspek);

$sql = "SELECT aktif
          FROM tahunajaran
         WHERE replid = '".$tahunajaran."'";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$ta_aktif = (int)$row[0];

if ($ta_aktif == 0)
    $sql = "SELECT r.nis, s.nama
              FROM riwayatkelassiswa r, siswa s
             WHERE r.nis = s.nis
               AND r.idkelas = '$kelas'
             ORDER BY nama";
else
    $sql = "SELECT nis, nama
              FROM siswa
             WHERE idkelas = '$kelas'
               AND aktif = 1
             ORDER BY nama";

$res = QueryDb($sql);
$siswa = [];
while($row = mysqli_fetch_row($res))
{
    $siswa[] = [$row[0], $row[1]];
}
$nsiswa = count($siswa);
?>
<table border="1" id="table" cellpadding="2" cellspacing="0" width="<?=$allwidth?>" style="border-width: 1px; border-collapse:collapse;">
    <tr>
        <td width="30" class="header" rowspan="2">No</td>
        <td width="100" class="header" rowspan="2">NIS</td>
        <td width="240" class="header" rowspan="2">Nama</td>
        <?php  for($i = 0; $i < $naspek; $i++)
        { ?>
            <td width="<?=$colwidth?>" align="center" colspan="2" class="header"><?=$aspekarr[$i][1]?></td>
        <?php  } ?>
        <td width="100" class="header" align="center"  rowspan="2">Rata-Rata<br>Siswa</td>
    </tr>
    <tr>
        <?php  $colwidth2 = $colwidth / 2;
        for($i = 0; $i < $naspek; $i++)
        { ?>
            <td width="<?=$colwidth2?>" align="center" class="header">Nilai Angka</td>
            <td width="<?=$colwidth2?>" align="center" class="header">Nilai Huruf</td>
        <?php  } ?>
    </tr>
    <?php
    $ratapel = [];
    for($j = 0; $j < $naspek; $j++)
    {
        $ratapel[] = [0, 0]; // totna, divna
    }

    $totratasis = 0;
    $ntotratasis = 0;

    for($s = 0; $s < $nsiswa; $s++)
    {
        $no = $s + 1;
        $nis = $siswa[$s][0];
        $nama = $siswa[$s][1];

        echo "<tr height='25'>";
        echo "<td align='center'>$no</td>";
        echo "<td align='left'>$nis</td>";
        echo "<td align='left'>$nama</td>";

        $ratasis = 0;
        $nratasis = 0;
        for($j = 0; $j < $naspek; $j++)
        {
            $asp = $aspekarr[$j][0];

            $na = "";
            $nh = "";
            $komentar = "";

            $sql = "SELECT nilaiangka, nilaihuruf, komentar
                      FROM infonap i, nap n, aturannhb a 
                     WHERE i.replid = n.idinfo 
                       AND n.nis = '$nis' 
                       AND i.idpelajaran = '$pelajaran' 
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

                $ratasis += $na;
                $nratasis += 1;

                $ratapel[$j][0] += $na;
                $ratapel[$j][1] += 1;
            }
            echo "<td align='center'><strong>$na</strong></td>";
            echo "<td align='center'><strong>$nh</strong></td>";
        }
        $rata = ($nratasis == 0) ? "" : round($ratasis / $nratasis, 2);
        echo "<td align='center'><strong>$rata</strong></td>";
        echo "</tr>";

        if ($nratasis != 0)
        {
            $totratasis += $rata;
            $ntotratasis += 1;
        }
    }

    $valtotratasis = $ntotratasis == 0 ? "" : round($totratasis / $ntotratasis, 2);

    // RATA-RATA PER PELAJARAN
    echo "<tr height='25'>";
    echo "<td colspan='3' style='background-color: #fcefa1' align='right'><i><strong>Rata-Rata</strong></i></td>";
    for($j = 0; $j < $naspek; $j++)
    {
        $totratapel = $ratapel[$j][0];
        $nratapel = $ratapel[$j][1];
        $valratapel = $nratapel == 0 ? "" : round($totratapel / $nratapel, 2);
        echo "<td style='background-color: #fcefa1' align='center'><strong>$valratapel</strong></td>";
        echo "<td style='background-color: #fcefa1' align='center'><strong>&nbsp;</strong></td>";
    }
    echo "<td style='background-color: #fcefa1' align='center'><strong>$valtotratasis</strong></td>";
    echo "</tr>";

    echo "<tr height='15'>";
    echo "<td colspan='$npelspan' style='background-color: #fff'>&nbsp;</td>";
    echo "</tr>";
    ?>
</table>

<?php
CloseDb();
?>
</body>
</html>