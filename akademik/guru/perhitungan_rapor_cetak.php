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
require_once('../include/getheader.php'); 
require_once('../library/dpupdate.php');

if(isset($_REQUEST["nip_guru"]))
	$nip = $_REQUEST["nip_guru"];

if(isset($_REQUEST["id_pelajaran"]))
	$id_pelajaran = $_REQUEST["id_pelajaran"];

OpenDb();

$sql = "SELECT j.departemen, j.nama, p.nip, p.nama 
		  FROM guru g, jbssdm.pegawai p, pelajaran j 
		 WHERE g.nip=p.nip AND g.idpelajaran = j.replid AND j.replid = '$id_pelajaran' AND g.nip = '".$nip."'"; 
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$departemen = $row[0];
$pelajaran = $row[1];
$guru = $row[2].' - '.$row[3];
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<title>JIBAS SIMAKA [Cetak Perhitungan Nilai Rapor]</title>
</head>
<body>
<center>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<!-- TABLE CENTER -->

<tr>
    <td align="left" valign="top">
	 <?=getHeader($departemen)?>
<center>
  <font size="4"><strong>ATURAN PERHITUNGAN NILAI RAPOR</strong></font><br />
 </center><br /><br />
<br />

<table>
    <tr>
		<td><strong>Departemen</strong>	</td>
    	<td><strong>: <?=$departemen ?></strong></td>
    </tr>
    <tr>
        <td><strong>Pelajaran</strong></td>
    	<td><strong>: <?=$pelajaran ?></strong></td>
   	</tr>
    <tr>
        <td><strong>Guru</strong></td>  	
        <td><strong>: <?=$guru ?></strong></td>       
	</tr>
    </table>
<?php $sql = "SELECT tingkat,replid FROM tingkat WHERE departemen = '$departemen' ORDER BY urutan";
	$result = QueryDb($sql);
    while ($row_tkt = @mysqli_fetch_array($result)) 
	{
		$query_at = "SELECT a.dasarpenilaian, dp.keterangan 
		               FROM aturannhb a, tingkat t, dasarpenilaian dp 
			 	      WHERE a.idtingkat='".$row_tkt['replid']."' AND a.idpelajaran = '$id_pelajaran' AND t.departemen='$departemen' 
					  	AND a.dasarpenilaian = dp.dasarpenilaian AND dp.aktif = 1 
				 		AND t.replid = a.idtingkat AND a.nipguru = '$nip' GROUP BY a.dasarpenilaian";
		
		$result_at = QueryDb($query_at);
        if (@mysqli_num_rows($result_at)>0)
		{ ?>
  <br>
  <b>Tingkat <?=$row_tkt['tingkat'] ?></b><br /><br />
  <table border="1" width="100%" id="table" class="tab">
  	<tr>
		<td height="30" align="center" class="header">No</td>
		<td height="30" align="center" class="header">Aspek Penilaian</td>
		<td height="30" align="center" class="header">Bobot Perhitungan Nilai Rapor </td>
		
	</tr>
<?php $i=1;
	while($row_at = mysqli_fetch_row($result_at))
	{ ?>
	<tr>
		<td height="25" align="center"><?=$i ?></td>
		<td height="25"><?=$row_at[1] ?></td>
		<td height="25">
<?php 	$query_ju = "SELECT j.jenisujian, a.bobot, a.aktif, a.replid FROM aturannhb a, tingkat t, jenisujian j ".
				 	"WHERE a.idtingkat = '".$row_tkt['replid']."' AND a.idpelajaran = '$id_pelajaran' AND j.replid = a.idjenisujian ".
					"AND t.departemen = '$departemen' AND a.dasarpenilaian = '".$row_at[0]."' AND t.replid = a.idtingkat ".
					"AND a.nipguru = '".$nip."'";
		$result_ju = QueryDb($query_ju);
		while($row_ju = mysqli_fetch_row($result_ju))
		{
					echo $row_ju[0]." = ".$row_ju[1]."<br>";
		}  ?>		
        </td>
    </tr>
<?php 	$i++;
	}
	?>
	
  </table>
<?php }
}
?>

</td>
</tr>
</table>
<script language="javascript">
window.print();
</script>
</center>
</body>
</html>
<?php
CloseDb();
?>