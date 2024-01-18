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
//require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/getheader.php');

OpenDb();
$idaturan = $_REQUEST['idaturan'];
$semester = $_REQUEST['semester'];
$kelas = $_REQUEST['kelas'];
$nip = $_REQUEST['nip'];

$sql = "SELECT k.kelas AS namakelas, s.semester AS namasemester, a.tahunajaran, 
			   a.departemen, l.nama, t.tingkat, j.jenisujian, p.nama AS guru, n.dasarpenilaian,s.departemen as dep 
		  FROM kelas k, semester s, tahunajaran a, pelajaran l, tingkat t, aturannhb n, jenisujian j, jbssdm.pegawai p 
		 WHERE k.replid = $kelas AND s.replid = $semester AND  k.idtahunajaran = a.replid AND l.replid = n.idpelajaran 
		   AND t.replid = k.idtingkat AND n.replid = $idaturan AND n.idjenisujian = j.replid AND p.nip = '".$nip."'";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Form Pengisian Nilai Akhir Siswa]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">
<?=getHeader($row['dep'])?>
<center>
  <font size="4"><strong>FORM PENGISIAN NILAI AKHIR SISWA</strong></font><br />
 </center><br /><br />

<br />
<table>
<tr>
    <td width="18%"><strong>Departemen</strong></td>
    <td width="40%"><strong>:&nbsp;<?=$row['departemen'] ?></strong></td>
    <td width="18%"><strong>Pelajaran</strong></td>
    <td><strong>:&nbsp;<?=$row['nama'] ?></strong></td>
</tr>
<tr>
    <td><strong>Tahun Ajaran</strong></td>
    <td><strong>:&nbsp;<?=$row['tahunajaran'] ?></strong></td>
    <td><strong>Dasar Penilaian</strong></td>
    <td><strong>:&nbsp;<?=$row['dasarpenilaian']?></strong></td>
</tr>
<tr>
    <td><strong>Semester</strong></td>
    <td><strong>:&nbsp;<?=$row['namasemester']?></strong></td>		
   	<td><strong>Jenis Pengujian</strong></td>
    <td><strong>:&nbsp;<?=$row['jenisujian']?></strong></td>
</tr>
<tr>
    <td><strong>Kelas</strong></td>
    <td><strong>:&nbsp;<?=$row['tingkat'].' - '.$row['namakelas']?></strong></td>		
</tr>
</table>
<br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left">
    <tr align="center">
        <td bordercolor="#000000" class="headerlong" align="center">No</td>
        <td class="headerlong">N I S</td>
        <td class="headerlong">Nama</td>
<?php  $sql_nhb_PK = "SELECT * FROM jbsakad.ujian 
				   WHERE idaturan=$idaturan AND idkelas=$kelas AND idsemester=$semester 
				   ORDER by tanggal ASC";
    $result_nhb_PK = QueryDb($sql_nhb_PK);
    $cntujian=1;
    while ($row_nhb_PK = @mysqli_fetch_array($result_nhb_PK))
	{
        $idujian[$cntujian] = $row_nhb_PK['replid'];
		$tgl = explode("-",(string) $row_nhb_PK['tanggal']); ?>
        <td class="headerlong">
          <?=$row['jenisujian']."&nbsp;".$cntujian."<br>".$tgl[2]."/".$tgl[1]."/".substr((string) $tgl[0],2)?>
        </td>
<?php      $cntujian++;
    }  ?>
        <td class="headerlong">Rata-rata Siswa</td>
        <td class="headerlong">Nilai Akhir <?=$row['jenisujian']?></td>
    </tr>
<?php $sql_get_nis = "SELECT nis,nama,aktif,idkelas
					  FROM jbsakad.siswa
					 WHERE idkelas = $kelas
					   AND aktif = 1
					   AND alumni = 0
					 ORDER BY nama";
    $result_get_nis=QueryDb($sql_get_nis);
    $cntsiswa=1;
    while ($row_get_nis=@mysqli_fetch_row($result_get_nis))
	{
        $nilai = 0;
        $tanda = "";
        if ($row_get_nis[2] == 0) 
            $tanda = "*";  ?>
    <tr height="25">
        <td  align="center"><?=$cntsiswa?></td>
        <td align="center"><?=$row_get_nis[0]?><?=$tanda?></td>
        <td><?=$row_get_nis[1]?></td>
    <?php
        for ($i=1;$i<=count($idujian);$i++) {				
            $sql_get_nilai="SELECT n.nilaiujian FROM jbsakad.nilaiujian n WHERE n.nis='".$row_get_nis[0]."' AND idujian = ".$idujian[$i];
            $result_get_nilai=QueryDb($sql_get_nilai);
            $row_get_nilai = mysqli_fetch_array($result_get_nilai);
            echo "<td align='center'>".$row_get_nilai['nilaiujian']."</td>";
            $nilai = $nilai+$row_get_nilai['nilaiujian'];
        }
    ?>
        <td align="center"><?=round($nilai/count($idujian),2)?>
        </td>
        <td align="center"></td>
        </tr>
    <?php
        $cntsiswa++;
    } 
    
      ?>
    </table>
	</td>
</tr>
<tr>
	<td><?="Ket: *Status siswa tidak aktif lagi"; ?></td>
</tr>
<tr>
	<td>
    <table width="100%" border="0">
        <tr>
            <td width="80%" align="left"></td>
            <td width="20%" align="center"><br><br>Guru</td>
        </tr>
        <tr>
            <td colspan="2" align="right">&nbsp;<br /><br /><br /><br /><br /></td>
        </tr>
        <tr>		
            <td></td>
            <td valign="bottom" align="center"><strong><?=$row['guru']?></strong>
            <br /><hr />
            <strong>NIP. <?=$nip?></strong>
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
CloseDb();
?>