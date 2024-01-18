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
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/getheader.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../library/as-diagrams.php');
require_once('../library/dpupdate.php');
require_once('../include/numbertotext.class.php');

$NTT = new NumberToText();
$kelas = $_REQUEST['kelas'];
$nis = $_REQUEST['nis'];
$semester = $_REQUEST['semester'];

OpenDb();
$sql = "SELECT t.departemen, a.tahunajaran, k.kelas, t.tingkat, s.nama, a.tglmulai, a.tglakhir FROM tahunajaran a, kelas k, tingkat t, siswa s WHERE k.idtingkat = t.replid AND k.idtahunajaran = a.replid AND k.replid = '$kelas' AND s.nis = '".$nis."'";  

$result = QueryDB($sql);	
$row = mysqli_fetch_array($result);
$tglmulai = $row['tglmulai'];
$tglakhir = $row['tglakhir'];
$nama = $row['nama'];
$departemen = $row['departemen'];
$tahunajaran = $row['tahunajaran'];
$kls = $row['kelas'];

/*
$sql = "SELECT replid FROM semester WHERE departemen = '$departemen' AND aktif=1";  

$result = QueryDB($sql);	
$row = mysqli_fetch_array($result);
$semester = $row['replid'];
*/

$sql_get_pelajaran_laporan=	"SELECT pel.replid as replid,pel.nama as nama FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel WHERE uji.replid=niluji.idujian AND niluji.nis=sis.nis AND uji.idpelajaran=pel.replid AND uji.idsemester='$semester' AND uji.idkelas='$kelas' AND sis.nis='$nis' ".
								"GROUP BY pel.nama";
//echo $sql_get_pelajaran_laporan;
$result_get_pelajaran_laporan=QueryDb($sql_get_pelajaran_laporan);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Rapor]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>LAPORAN HASIL BELAJAR</strong></font><br />
 </center><br /><br />
<table>
<tr>
	<td width="25%" class="news_content1"><strong>Siswa</strong></td>
    <td class="news_content1">: 
      <?=$nis.' - '.$nama?></td>
</tr>
<tr>
	<td width="25%" class="news_content1"><strong>Departemen</strong></td>
    <td class="news_content1">: 
      <?=$departemen?></td>
</tr>
<tr>
	<td class="news_content1"><strong>Tahun Ajaran</strong></td>
    <td class="news_content1">: 
      <?=$tahunajaran?></td>
</tr>
<tr>
	<td class="news_content1"><strong>Kelas</strong></td>
    <td class="news_content1">: 
      <?=$kls ?></td>
</tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
    <td>
        <?php ShowKomentar() ?>
    </td>
</tr>
<tr>
    <td>
    <fieldset><legend class="news_title2"><strong>Nilai Pelajaran</strong></legend>
<?php 		ShowRapor() ?>		
    </fieldset>
    <br>
    </td>
</tr>
<tr>
    <td>
        <fieldset><legend class="news_title2"><strong>Deskripsi Nilai Pelajaran</strong></legend>
<?php 		ShowRaporDeskripsi() ?>
        </fieldset>
        <br>
    </td>
</tr>

</table>

	</td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>
</html>
<?php

function ShowKomentar()
{
	global $semester, $kelas, $nis;

    $arrjenis = ['SPI', 'SOS'];
    $arrnmjenis = ['Spiritual', 'Sosial'];
    for($i = 0; $i < count($arrjenis); $i++)
    {
        $jenis = $arrjenis[$i];
        $nmjenis = $arrnmjenis[$i];

        $sql = "SELECT komentar, k.predikat
                  FROM jbsakad.komenrapor k 
                 WHERE k.nis = '$nis' 
                   AND k.idsemester = '$semester' 
                   AND k.idkelas = '$kelas'
                   AND k.jenis = '".$jenis."'";
        $res2 = QueryDb($sql);
        $komentar = "";
        $predikat = "";
        $nilaiExist = false;
        if ($row2 = mysqli_fetch_row($res2))
        {
            $nilaiExist = true;
            $komentar = $row2[0];
            $predikat = PredikatNama($row2[1]);
        }


        echo "<fieldset><legend><strong>Sikap $nmjenis</strong></legend>";
        echo "<table border='1' width='100%' cellpadding='2' cellspacing='0' style='border-width: 1px; border-collapse: collapse'>";
        echo "<tr style='height: 120px'>";
        echo "<td width='20%' align='left' valign='top'>Predikat: $predikat</td>";
        echo "<td width='*' align='left' valign='top'>$komentar</td>";
        echo "</tr>";
        echo "</table>";
        echo "</fieldset><br>";

    }
}

function ShowRapor()
{
	global $semester, $kelas;
	
	$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
	  	      FROM infonap i, nap n, aturannhb a, dasarpenilaian d
			 WHERE i.replid = n.idinfo
			   AND i.idsemester = '$semester' 
			   AND i.idkelas = '$kelas'
			   AND n.idaturan = a.replid 	   
			   AND a.dasarpenilaian = d.dasarpenilaian
			   AND d.aktif = 1";
	$res = QueryDb($sql);
	$naspek = mysqli_num_rows($res); 
	

    ShowRaporColumn();
}

function PredikatNama($predikat)
{
    switch ($predikat)
    {
        case 4:
            return "Istimewa";
        case 3:
            return "Baik";
        case 2:
            return "Cukup";
        case 1:
            return "Kurang";
        case 0:
            return "Buruk";

    }
}

function ShowRaporDeskripsi()
{
    global 	$semester, $kelas, $nis;

    $sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
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
    echo "</table>";
}

function ShowRaporColumn()
{
    global 	$semester, $kelas, $nis;

    $sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
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
    $colwidth = $naspek == 0 ? "*" : round(50 / count($aspekarr)) . "%";
    ?>
    <table width="100%" border="1" class="tab" id="table" bordercolor="#000000" style="border-collapse: collapse; border-width: 1px;">
        <tr>
            <td width="5%" rowspan="2" class="header"><div align="center">No</div></td>
            <td width="35%" rowspan="2" class="header"><div align="center">Pelajaran</div></td>
            <td width="10%" rowspan="2" class="header"><div align="center">KKM</div></td>
            <?php 	for($i = 0; $i < count($aspekarr); $i++)
                echo "<td class='header' colspan='3' align='center' width='$colwidth'>" . $aspekarr[$i][1] . "</td>"; ?>
        </tr>
        <tr>
            <?php       $colwidth = $naspek == 0 ? "*" : round(50 / (2 * $naspek)) . "%";
            for($i = 0; $i < count($aspekarr); $i++)
                echo "<td class='header' align='center' width='7%'>Nilai</td>
                    <td class='header' align='center' width='7%'>Predikat</td>"; ?>
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
    <?php
}

function ShowRaporRow()
{ 
	global 	$semester, $kelas, $nis, $NTT; ?>
    <table width="100%" border="1" class="tab" bordercolor="#000000">
    <tr>
        <td width="4%" rowspan="2" class="header"><div align="center">No</div></td>
        <td width="12%" rowspan="2" class="header"><div align="center">Pelajaran</div></td>
        <td width="7%" rowspan="2" class="header"><div align="center">KKM</div></td>
        <td width="12%" rowspan="2" class="header"><div align="center">Aspek<br>Penilaian</div></td>
        <td width="35%" colspan="3" class="header"><div align="center">Nilai</div></td>
    </tr>
    <tr>
        <td width="5%" class="header"><div align="center">Angka</div></td>
        <td width="5%" class="header"><div align="center">Huruf</div></td>
        <td width="15%" class="header"><div align="center">Terbilang</div></td>
    </tr>
   
<?php 	$sql = "SELECT pel.replid, pel.nama
              FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel 
             WHERE uji.replid = niluji.idujian 
               AND niluji.nis = sis.nis 
               AND uji.idpelajaran = pel.replid 
               AND uji.idsemester = '$semester'
               AND uji.idkelas = '$kelas'
               AND sis.nis = '$nis' 
         GROUP BY pel.nama";    
    $res = QueryDb($sql);
    $i = 0;
    while($row = mysqli_fetch_row($res))
    {
        $pelarr[$i++] = [$row[0], $row[1]];
    }
    
    for($i = 0; $i < count($pelarr); $i++)
    {
        $idpel = $pelarr[$i][0];
        $nmpel = $pelarr[$i][1];
        
        $sql = "SELECT nilaimin 
                 FROM infonap
                WHERE idpelajaran = '$idpel'
                  AND idsemester = '$semester'
                  AND idkelas = '".$kelas."'";
        $res = QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $nilaimin = $row[0];
        
        $sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan 
                FROM infonap i, nap n, aturannhb a, dasarpenilaian d 
               WHERE i.replid = n.idinfo AND n.nis = '$nis' 
                 AND i.idpelajaran = '$idpel' 
                 AND i.idsemester = '$semester' 
                 AND i.idkelas = '$kelas' 
                 AND n.idaturan = a.replid  	   
                 AND a.dasarpenilaian = d.dasarpenilaian
                 AND d.aktif = 1";
        $res = QueryDb($sql);				 
        $aspekarr = [];				 
        $j = 0;
        while($row = mysqli_fetch_row($res))
        {
            $na = "";
            $nh = "";
            $asp = $row[0];
            
            $sql = "SELECT nilaiangka, nilaihuruf
                      FROM infonap i, nap n, aturannhb a 
                     WHERE i.replid = n.idinfo 
                       AND n.nis = '$nis' 
                       AND i.idpelajaran = '$idpel' 
                       AND i.idsemester = '$semester' 
                       AND i.idkelas = '$kelas'
                       AND n.idaturan = a.replid 	   
                       AND a.dasarpenilaian = '".$asp."'";
            $res2 = QueryDb($sql);
            if (mysqli_num_rows($res2) > 0)
            {
                $row2 = mysqli_fetch_row($res2);
                $na = $row2[0];
                $nh = $row2[1];
            }
            
            $aspekarr[$j++] = [$row[0], $row[1], $na, $nh];
        } 
        $naspek = count($aspekarr);
        
        if ($naspek > 0)
        { ?>
            <tr height="20">
                <td rowspan="<?=$naspek?>" align="center"><?=$i + 1?></td>
                <td rowspan="<?=$naspek?>" align="left"><?=$nmpel?></td>
                <td rowspan="<?=$naspek?>" align="center"><?=$nilaimin?></td>
                <td align="left"><?=$aspekarr[0][1]?></td>
                <td align="center"><?=$aspekarr[0][2]?></td>
                <td align="center"><?=$aspekarr[0][3]?></td>
                <td align="left"><?=$NTT->Convert($aspekarr[0][2])?></td>
            </tr>
<?php 		for($k = 1; $k < $naspek; $k++)
            { ?>
                <tr height="20">
                    <td align="left"><?=$aspekarr[$k][1]?></td>
                    <td align="center"><?=$aspekarr[$k][2]?></td>
                    <td align="center"><?=$aspekarr[$k][3]?></td>
                    <td align="left"><?=$NTT->Convert($aspekarr[$k][2])?></td>
                </tr>
<?php 		} // end for
        } 
        else
        { ?>
            <tr height="20">
                <td align="center"><?=$i + 1?></td>
                <td align="left"><?=$nmpel?></td>
                <td align="center"><?=$nilaimin?></td>
                <td align="left">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
            </tr>		
<?php 	}// end if
    } 
	 echo "</table>";
}
?>

<?php
CloseDb();
?>