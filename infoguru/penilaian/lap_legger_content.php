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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once("../include/theme.php");

$departemen = $_REQUEST['departemen'];
$tahunajaran = $_REQUEST['tahunajaran'];
$tingkat = $_REQUEST['tingkat'];
$kelas = $_REQUEST['kelas'];
$semester = $_REQUEST['semester'];
$pelajaran = $_REQUEST['pelajaran'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<link rel="stylesheet" type="text/css" href="../script/tooltips.css" />
<link href="../script/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="lap_legger.js"></script>
<script src="../script/SpryTabbedPanels.js" type="text/javascript"></script>
</head>

<body leftmargin="15" topmargin="15">
<?php
OpenDb();

$info = [];
$sql = "SELECT DISTINCT u.idjenis, j.jenisujian
          FROM ujian u, jenisujian j
         WHERE u.idjenis = j.replid
           AND u.idpelajaran = '$pelajaran'
           AND u.idkelas = '$kelas'
           AND u.idsemester = '$semester'
         ORDER BY j.jenisujian";
$res = QueryDb($sql);
$njenis = 0;
while($row = mysqli_fetch_row($res))
{
    $idjenis = $row[0];
    $namajenis = $row[1];
    
    $sql = "SELECT replid, DAY(tanggal), MONTH(tanggal)
              FROM ujian
             WHERE idpelajaran = '$pelajaran'
               AND idkelas = '$kelas'
               AND idsemester = '$semester'
               AND idjenis = '$idjenis'
             ORDER BY tanggal";
    $res2 = QueryDb($sql);
    $nujian = mysqli_num_rows($res2);
    $idujian = "";
    $tglujian = "";
    while($row2 = mysqli_fetch_row($res2))
    {
        if ($idujian != "")
            $idujian .= "#";
        $idujian .= $row2[0];
        
        if ($tglujian != "")
            $tglujian .= "#";
        $tglujian .= $row2[1] . "/" . $row2[2];    
    }
    
    $info[$njenis][0] = $idjenis;
    $info[$njenis][1] = $namajenis;
    $info[$njenis][2] = $nujian;
    $info[$njenis][3] = $idujian;
    $info[$njenis][4] = $tglujian;
    
    $njenis += 1;
}

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
$nsiswa = 0;
while($row = mysqli_fetch_row($res))
{
    $siswa[$nsiswa][0] = $row[0];
    $siswa[$nsiswa][1] = $row[1];
    $nsiswa += 1;
}

$allwidth = 30 + 100 + 240;
for($i = 0; $i < $njenis; $i++)
{
    $nujian = $info[$i][2];
    $allwidth += $nujian * 60;
}
?>
<a href="#" onclick="cetak_excel()">
    <img src="../images/ico/excel.png" border="0" onMouseOver="showhint('Cetak dalam format Excel!', this, event, '80px')"/>&nbsp;Cetak Excel
</a><br><br>
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="tahunajaran" value="<?=$tahunajaran?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="kelas" value="<?=$kelas?>">
<input type="hidden" id="semester" value="<?=$semester?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<table border="1" id="table" cellpadding="2" cellspacing="0" width="<?=$allwidth?>" style="border-width: 1px; border-collapse:collapse;">
<tr>
    <td width="30" class="header" rowspan="2">No</td>
    <td width="100" class="header" rowspan="2">NIS</td>
    <td width="240" class="header" rowspan="2">Nama</td>
<?php  for($i = 0; $i < $njenis; $i++)
    {
        $namajenis = $info[$i][1];
        $nujian = $info[$i][2];
        $width = 60 * $nujian; ?>
        <td width="<?=$width?>" colspan="<?=$nujian?>" align="center" class="header"><?=$namajenis?></td>
<?php  } ?>    
</tr>
<tr>
<?php  for($i = 0; $i < $njenis; $i++)
    {
        $nujian = $info[$i][2];
        $tglujian = $info[$i][4];
        $arrtgl = explode("#", $tglujian);
        for($j = 0; $j < count($arrtgl); $j++)
        {  ?>
        <td width="60" align="center" class="header"><?=$arrtgl[$j]?></td>
<?php      }
    } ?>        
</tr>

<?php
for($s = 0; $s < $nsiswa; $s++)
{
    $no = $s + 1;
    $nis = $siswa[$s][0];
    $nama = $siswa[$s][1];
    
    echo "<tr height='25'>";
    echo "<td align='left'>$no</td>";
    echo "<td align='left'>$nis</td>";
    echo "<td align='left'>$nama</td>";
    
    for($j = 0; $j < $njenis; $j++)
    {
        $idujian = $info[$j][3];
        $arrujian = explode("#", $idujian);
        $nujian = count($arrujian);
        for($u = 0; $u < $nujian; $u++)
        {
            $id = $arrujian[$u];
            $sql = "SELECT nilaiujian
                      FROM nilaiujian
                     WHERE nis = '$nis'
                       AND idujian = '".$id."'";
            $res = QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
            {
                $row = mysqli_fetch_row($res);
                echo "<td align='center'>".$row[0]."</td>";
            }
            else
            {
                echo "<td align='center'>-</td>";
            }
        }
    }
    echo "</tr>";
}
?>
</table>
<script language='JavaScript'>
    Tables('table', 1, 0);
</script>
<?php
CloseDb();
?>
</body>
</html>