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


if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];	
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];	
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];	
if (isset($_REQUEST['bln']))
	$bln = $_REQUEST['bln'];	
if (isset($_REQUEST['thn']))
	$thn = $_REQUEST['thn'];		
if ($pelajaran == -1) 	
	$pel = "";
else  
	$pel = "AND pp.idpelajaran = $pelajaran" 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Lihat Data Presensi Pelajaran]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript">

</script>
</head>

<body>
<table border="0" cellpadding="5" cellspacing="5" width="100%" align="center">
<!-- TABLE UTAMA -->
<tr>
<td align="left">
    <?php 	
	if ($kelas <> "") { 
		OpenDb();
		$sql = "SELECT DAY(pp.tanggal), MONTH(pp.tanggal), pp.jam, p.nama, g.nama, pp.materi, pp.replid FROM presensipelajaran pp, pelajaran p, jbssdm.pegawai g WHERE pp.idkelas = '$kelas' AND pp.idsemester = '$semester' '$pel' AND MONTH(pp.tanggal) = '$bln' AND YEAR(pp.tanggal) = '$thn' AND pp.idpelajaran = p.replid AND pp.gurupelajaran = g.nip ORDER BY pp.tanggal, pp.jam ";
		
		$result = QueryDb($sql);			 
		$jum = mysqli_num_rows($result);
		if ($jum > 0) {
	?>
   		
        <table border="0" width="100%" id="table" class="tab">
		
        <tr>		
			<td class="header" align="center" width="4%">No</td>
			<td class="header" align="center" width="4%">Tgl</td>
			<td class="header" align="center" width="9%">Jam</td>
            <td class="header" align="center" width="20%">Pelajaran</td>
            <td class="header" align="center" width="20%">Pengajar</td>
            <td class="header" align="center" width="32%">Materi</td>
            <td class="header" align="center" width="8%"></td>
		</tr>
		<?php 
		$cnt = 1;
		while ($row = @mysqli_fetch_row($result)) {					
		?>	
        <tr>        			
			<td align="center"><?=$cnt?></td>
			<td align="center"><?=$row[0].'/'.$row[1]?></td>
  			<td><?=$row[2]?></td>
           	<td><?=$row[3]?></td>
            <td><?=$row[4]?></td>
            <td><textarea name="materi_lanjut" id="materi_lanjut" rows="2" cols="30%"><?=$row[5] ?></textarea></td>            
            <td align="center"><input type="button" name="pilih" class="but" id="pilih" value="Pilih" onClick="parent.pilih('<?=$row[6]?>')" /></td>
    	</tr>
 	<?php 	$cnt++;
		} 
		CloseDb();	?>
    		
		</table>
		<script language='JavaScript'>
   			Tables('table', 1, 0);
		</script>

	<?php 	} else { 
			echo "<strong><font color='red'>Tidak ditemukan adanya data</strong>";
		}
	} else { 
			echo "<strong><font color='red'>Tidak ditemukan adanya data</strong>";
	}	
	?>
</td></tr>
<tr height="30">
	<td align="center">
    <input type="button" class="but" name="tutup" id="tutup" value="Tutup" onClick="parent.tutup()" /></td>
</tr>	
<!-- END OF TABLE UTAMA -->
</table>

</body>
</html>