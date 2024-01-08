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
require_once('../include/db_functions.php');
require_once('komentar.lihat.func.php');

OpenDb();

ReadParams();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/aTR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Komentar Nilai Rapor</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
    function ver()
    {
        if (confirm('Anda yakin akan menghapus seluruh komentar di Kelas ini?'))
            return true;
        else
            return false;
    }

    function change_urut(urut,urutan)
    {
        if (urutan == "asc")
            urutan = "desc";
        else
            urutan = "asc";
        document.location.href="komentar_lihat.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&urut="+urut+"&urutan="+urutan;
    }
</script>
</head>
<body topmargin="10" leftmargin="10">

<br>

<?php
$aspekarr = [];

$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
          FROM infonap i, nap n, aturannhb a, dasarpenilaian d
         WHERE i.replid = n.idinfo 
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
$ncolumn = $naspek + 2;
$colwidth = round(75 / $ncolumn) . "%";

$arrjenis = ['SPI', 'SOS'];
$arrnmjenis = ["Spiritual", "Sosial"];
?>
<table width="100%" border="1" cellspacing="0" cellpadding="2" class="tab" id="table" style="border-width: 1px; border-collapse: collapse;">
<tr style="height: 26px;">
    <td class="header" width="3%">No</td>
    <td class="header" width="12%">Siswa</td>
    <?php
    for($i = 0; $i < $naspek; $i++)
    {
        $nmaspek = $aspekarr[$i][1];
        echo "<td class='header' width='$colwidth' align='center'>$nmaspek</td>";
    }
$counter = count($arrnmjenis);

    for($i = 0; $i < $counter; $i++)
    {
        $nmjenis = $arrnmjenis[$i];
        echo "<td class='header' width='$colwidth' align='center'>$nmjenis</td>";
    }
    ?>
</tr>
<?php
$sql = "SELECT s.nis, s.nama
          FROM siswa s
         WHERE s.idkelas = $kelas 
           AND aktif = 1  
         ORDER BY s.nama";
$res = QueryDb($sql);
$no = 0;
while($row = mysqli_fetch_row($res))
{
    $no += 1;
    $nis = $row[0];
    $nama = $row[1];

    echo "<tr style='height: 70px'>";
    echo "<td align='center'>$no</td>";
    echo "<td><i>$nis</i><br><strong>$nama</strong></td>";

    for($i = 0; $i < $naspek; $i++)
    {
        $kdaspek = $aspekarr[$i][0];
        $nmaspek = $aspekarr[$i][1];

        $sql = "SELECT n.nilaiangka, n.nilaihuruf, n.replid, n.komentar
                  FROM infonap i, nap n, aturannhb a 
                 WHERE i.replid = n.idinfo 
                   AND n.idaturan = a.replid 
                   AND n.nis = '$nis' 
                   AND i.idpelajaran = '$pelajaran' 
                   AND i.idsemester = '$semester' 
                   AND i.idkelas = '$kelas'	   
                   AND a.dasarpenilaian = '".$kdaspek."'";
        $res2 = QueryDb($sql);
        $nilaiExist = false;
        $na = "";
        $nh = "";
        $idnap = 0;
        $komentar = "";
        if (mysqli_num_rows($res2) > 0)
        {
            $row2 = mysqli_fetch_row($res2);
            $na = $row2[0];
            $nh = $row2[1];
            $idnap = $row2[2];
            $komentar = $row2[3];
            $nilaiExist = true;
        }

        if ($nilaiExist) {
            echo "<td align='left' valign='top'>$komentar<br><strong>Nilai: $na, Predikat: $nh</strong></td>";
        } else {
            echo "<td align='center' valign='middle'><i>(belum ada data)</i></td>";
        }
    }
    $counter = count($arrjenis);

    for($i = 0; $i < $counter; $i++)
    {
        $jenis = $arrjenis[$i];

        $sql = "SELECT predikat, komentar
                  FROM jbsakad.komenrapor 
                 WHERE nis = '$nis' 
                   AND idsemester = '$semester' 
                   AND idkelas = '$kelas'
                   AND jenis = '".$jenis."'";
        $res2 = QueryDb($sql);
        $komentar = "";
        $predikat = "";
        $nilaiExist = false;
        if ($row2 = mysqli_fetch_row($res2))
        {
            $nilaiExist = true;
            $komentar = $row2[1];
            $predikat = PredikatNama($row2[0]);
        }

        if ($nilaiExist) {
            echo "<td align='left' valign='top'>$komentar<br><strong>Predikat: $predikat</strong></td>";
        } else {
            echo "<td align='center' valign='middle'><i>(belum ada data)</i></td>";
        }
    }
    echo "</tr>";
}
?>

</table>
<script language='JavaScript'>
    Tables('table', 1, 0);
</script>
</body>
</html>

<?php
CloseDb();
?>
