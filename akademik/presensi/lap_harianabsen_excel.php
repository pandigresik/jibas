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

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=LaporanHarianDataSiswaTidakHadir.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$departemen = $_REQUEST['departemen'];
$tahunajaran = $_REQUEST['tahunajaran'];
$semester = $_REQUEST['semester'];
$kelas = $_REQUEST['kelas'];
$tingkat = $_REQUEST['tingkat'];
$tglawal = $_REQUEST['tglawal'];
$tglakhir = $_REQUEST['tglakhir'];
$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

$filter1 = "AND t.departemen = '".$departemen."'";
if ($tingkat <> -1) 
	$filter1 = "AND k.idtingkat = '".$tingkat."'";

$filter2 = "";
if ($kelas <> -1) 
	$filter2 = "AND k.replid = '".$kelas."'";
	
OpenDb();
$sql = "SELECT t.departemen, a.tahunajaran, s.semester, k.kelas, t.tingkat 
          FROM tahunajaran a, kelas k, tingkat t, semester s, presensiharian p 
         WHERE p.idkelas = k.replid 
           AND k.idtingkat = t.replid 
           AND k.idtahunajaran = a.replid 
           AND p.idsemester = s.replid 
           AND a.replid = '$tahunajaran'
           AND s.replid = '$semester' $filter1 $filter2";

$result = QueryDB($sql);	
$row = mysqli_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Laporan Harian Data Siswa yang Tidak Hadir]</title>
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-family: Verdana;
}
.style4 {font-family: Verdana; font-weight: bold; font-size: 12px; }
.style5 {font-family: Verdana}
.style6 {font-size: 12px}
.style7 {font-family: Verdana; font-size: 12px; }
-->
</style>
</head>

<body>

<table width="100%" border="0" cellspacing="0">
  <tr>
    <th scope="row" colspan="10"><span class="style1">Laporan Harian Data Siswa yang Tidak Hadir</span></th>
  </tr>
</table>
<br />
<table width="27%">
<tr>
	<td width="43%"><span class="style4">Departemen</span></td>
    <td width="57%" colspan="9"><span class="style4">: 
      <?=$row['departemen']?>
    </span></td>
</tr>
<tr>
	<td><span class="style4">Tahun Ajaran</span></td>
    <td colspan="9"><span class="style4">: 
      <?=$row['tahunajaran']?>
    </span></td>
</tr>
<tr>
	<td><span class="style4">Semester</span></td>
    <td colspan="9"><span class="style4">: 
      <?=$row['semester']?>
    </span></td>
</tr>
<tr>
	<td><span class="style4">Tingkat</span></td>
    <td colspan="9"><span class="style4">: 
      <?php if ($tingkat == -1) echo "Semua Tingkat"; else echo $row['tingkat']; ?>
    </span></td>
</tr>
<tr>
	<td><span class="style4">Kelas</span></td>
    <td colspan="9"><span class="style4">: 
      <?php if ($kelas == -1) echo "Semua Kelas"; else echo $row['kelas']; ?>
    </span></td>
</tr>
<tr>
	<td><span class="style4">Periode Presensi</span></td>
    <td colspan="9"><span class="style4">: <?=format_tgl($tglawal).' s/d '. format_tgl($tglakhir) ?></span></td>
</tr>
</table>
<br />
<?php 	OpenDb();
	$sql = "SELECT s.nis, s.nama, SUM(ph.hadir), SUM(ph.ijin) AS ijin, SUM(ph.sakit) AS sakit, SUM(ph.alpa) AS alpa, SUM(ph.cuti) AS cuti, k.kelas, s.hportu, s.emailayah, s.alamatortu, s.telponortu, s.hpsiswa, s.emailsiswa, s.aktif, s.emailibu FROM siswa s LEFT JOIN (phsiswa ph INNER JOIN presensiharian p ON p.replid = ph.idpresensi) ON ph.nis = s.nis, kelas k, tingkat t WHERE k.replid = s.idkelas AND k.idtingkat = t.replid $filter1 $filter2 AND p.idsemester = '$semester' AND (((p.tanggal1 BETWEEN '$tglawal' AND '$tglakhir') OR (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) OR (('$tglawal' BETWEEN p.tanggal1 AND p.tanggal2) OR ('$tglakhir' BETWEEN p.tanggal1 AND p.tanggal2))) GROUP BY s.nis HAVING ijin>0 OR sakit>0 OR cuti>0 OR alpa>0 ORDER BY $urut $urutan";
	
	$result = QueryDb($sql);
	$jum = mysqli_num_rows($result);
	if ($jum > 0) { 
?>
	<table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left">
   	<tr height="30" align="center">
    	<td width="5%" bgcolor="#CCCCCC" class="style6 style5 header"><strong>No</strong></td>
		<td width="10%" bgcolor="#CCCCCC" class="style6 style5 header"><strong>N I S</strong></td>
        <td width="10%" bgcolor="#CCCCCC" class="style6 style5 header"><strong>Nama</strong></td>
   		<td width="5%" bgcolor="#CCCCCC" class="style6 style5 header"><strong>Kelas</strong></td>
        <td width="*" bgcolor="#CCCCCC" class="style6 style5 header"><strong>Ortu</strong></td>
   		<td width="5%" bgcolor="#CCCCCC" class="style6 style5 header"><strong>Hadir</strong></td>
		<td width="5%" bgcolor="#CCCCCC" class="style6 style5 header"><strong>Ijin</strong></td>            
		<td width="5%" bgcolor="#CCCCCC" class="style6 style5 header"><strong>Sakit</strong></td>
        <td width="5%" bgcolor="#CCCCCC" class="style6 style5 header"><strong>Alpa</strong></td>
        <td width="5%" bgcolor="#CCCCCC" class="style6 style5 header"><strong>Cuti</strong></td>
        
    </tr>
<?php 	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) { ?>
    <tr height="25" valign="middle">    	
    	<td align="center" valign="middle"><span class="style7">
   	    <?=++$cnt?>
    	</span></td>
		<td align="center" valign="middle"><span class="style7">
	    <?=$row[0]?>
		</span></td>
        <td valign="middle"><span class="style7">
        <?=$row[1]?>
        </span></td>
        <td align="center" valign="middle"><span class="style7">
        <?=$row[7]?>
        </span></td>
        <!--<td valign="middle"><span class="style7">HP: 
        <?=$row[8]?>
        <br />
       	  Email: 
       	  <?=$row[9]?>
       	  <br />
          Alamat: 
          <?=$row[10]?>
          <br />
		  Telp: 
		  <?=$row[11]?>
		  <br />
          HP Siswa: 
          <?=$row[12]?>
          <br />
          Email Siswa: 
          <?=$row[13]?>      	
          </span></td>
  		-->
        <td valign="middle"><span class="style7">
         <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="27%" >Handphone</td>
                <td>:&nbsp;</td>
                <td width="90%" ><?=$row[8]?> </td>  
            </tr>                
            <tr>
                <td>Email</td>
                <td>:&nbsp;</td>
              	<td>
				<?php 	if ($row[9] <> "" && $row[15] <> "")
						echo $row[9].", ".$row[15];
				 	elseif ($row[15] == "")
						echo $row[9];
					else 
						echo $row[15];
				?>
                </td>
            </tr>
            <tr>
                <td valign="top">Alamat</strong></td>
                <td valign="top">:&nbsp; </td>
              	<td><?=$row[10]?></td>
            </tr>
            <tr>
                <td>Telepon</strong></td>
              	<td>:&nbsp; </td>  
                <td><?=$row[11]?></td>
            </tr>
            <tr>
                <td>HP Siswa</strong></td>
              	<td>:&nbsp; </td>   
                <td><?=$row[12]?></td>
            </tr>
            <tr>
                <td>Email Siswa</strong></td>
              	<td>:&nbsp; </td>  
                <td><?=$row[13]?></td>
            </tr>
            </table> 
        </span></td> 
        <td align="center" valign="middle"><span class="style7"><font size="4"><b>
	    <?=$row[2]?>
	    </br>
  		</span></td>
        <td align="center" valign="middle"><span class="style7"><font size="4"><b>
        <?=$row[3]?>
        </br>
        </span></td>    
        <td align="center" valign="middle"><span class="style7"><font size="4"><b>
        <?=$row[4]?>
        </br>
        </span></td>
        <td align="center" valign="middle"><span class="style7"><font size="4"><b>
        <?=$row[5]?>
        </br>
        </span></td>    
        <td align="center" valign="middle"><span class="style7"><font size="4"><b>
        <?=$row[6]?>
        </br>
        </span></td>
    </tr>
<?php } 
	CloseDb() ?>	
    <!-- END TABLE CONTENT -->
</table>	
<?php 	} ?>

</body>
<script language="javascript">
window.print();
</script>

</html>