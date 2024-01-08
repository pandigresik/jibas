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
$tahunajaran=$_REQUEST['tahunajaran'];
$departemen=$_REQUEST['departemen'];
$tingkat=$_REQUEST['tingkat'];

$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Kelas]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center>
  <font size="4"><strong>DATA KELAS</strong></font><br />
 </center><br /><br />
<br />
<table>
<tr>
	<td><strong>Departemen</strong> </td> 
	<td><strong>:&nbsp;<?=$departemen?></strong></td>
</tr>
<tr>
	<td><strong>Tahun Ajaran</strong></td>
	<td><strong>:&nbsp;<?=$_REQUEST['namatahunajaran']?></strong></td>
</tr>
<tr>
	<td><strong>Tingkat</strong></td>
	<td><strong>:&nbsp;<?=$_REQUEST['namatingkat']?></strong></td>
</tr>
</table>
<br />
</span>
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="10%" class="header" align="center">Kelas</td>
        <td width="20%" class="header" align="center">Wali Kelas</td>
		<td width="10%" class="header" align="center">Kapasitas</td>
		<td width="10%" class="header" align="center">Terisi</td>
		<td width="*" class="header" align="center">Keterangan</td>
        <td width="10%" class="header" align="center">Status</td>
    </tr>
<?php OpenDb();
	$sql = "SELECT k.replid,k.kelas,k.idtahunajaran,k.kapasitas,k.nipwali,k.aktif,k.keterangan,t.replid,t.tahunajaran,t.departemen,p.nama,tkt.replid,k.idtingkat FROM kelas k, tahunajaran t, tingkat tkt, jbssdm.pegawai p WHERE t.replid='$tahunajaran' AND tkt.replid='$tingkat' AND k.idtahunajaran=t.replid AND t.departemen='$departemen' AND tkt.replid=k.idtingkat AND p.nip=k.nipwali ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris";  

	$result = QueryDB($sql);
	//if ($page==0)
		$cnt = 1;
	//else
		//$cnt = (int)$page*(int)$varbaris+1;
		
	while ($row = mysqli_fetch_row($result)) { 
		?>
    <tr height="25">    	
    	<td align="center"><?=$cnt ?></td>
        <td><?=$row[1] ?></td>        
        <td><?php
		$sql3 = "SELECT p.nip,p.nama FROM jbssdm.pegawai p WHERE p.nip='".$row[4]."'";
		$result3 = QueryDB($sql3);
		while ($row3 = mysqli_fetch_row($result3)){
		echo $row3[0]." - ".$row3[1];
		}	
		?></td>
		<td align="center"><?=$row[3] ?></td>        
        <td align="center"><?php 
			$kelasterpilih=$row[0];
		$sql2 = "SELECT COUNT(*) FROM jbsakad.siswa s WHERE s.idkelas='$kelasterpilih' AND s.aktif=1";
		$result2 = QueryDB($sql2);
		if ($row2 = mysqli_fetch_row($result2)){
		$terisi = $row2[0];
		} else {
		$terisi = 0;
		}
		echo $terisi; ?></td>
		<td><?=$row[6] ?></td>  
        <td align="center">
			<?php if ($row[5] == 1) 
					echo 'Aktif';
				else
					echo 'Tidak Aktif';
			?>		
        </td>      
    </tr>
<?php $cnt++;
			} 
	CloseDb() ?>	
    </table>
<!-- END TABLE CENTER -->    
</table>
</body>
<script language="javascript">
window.print();
</script>
</html>