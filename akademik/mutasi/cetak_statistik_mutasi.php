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
require_once('../library/departemen.php');
require_once('../include/getheader.php');
$departemen=$_REQUEST['departemen'];
$tahunawal=$_REQUEST['tahunawal'];
$tahunakhir=$_REQUEST['tahunakhir'];

OpenDb();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {
	font-size: 24px;
	font-weight: bold;
}
-->
</style><script language="javascript" src="../script/tables.js"></script></head>

<body>
<table width="100%" border="0">
  <tr>
    <td><?=getHeader($departemen)?></td>
  </tr>
  <tr>
    <td>
    <p align="center"><strong>STATISTIK MUTASI SISWA</strong><br>
  <strong>TAHUN : 
  <?=$tahunawal?> 
  s/d 
  <?=$tahunakhir?>  
  </strong><br>
  <br>
<img src="gambar_statistik.php?departemen=<?=$departemen?>&tahunakhir=<?=$tahunakhir?>&tahunawal=<?=$tahunawal?>" ></p>


	<table width="100%"  border="1" align="center" cellpadding="3" cellspacing="0" class="tab" bordercolor="#000000">
	 <tr class="header">
        <td width="5" height="30" align="center">No</td>
		<td width="54%" height="30" align="center">Jenis Mutasi</td>
        <td width="31%" height="30">Jumlah </td>
        </tr>
 <?php
$sql1="SELECT * FROM jbsakad.jenismutasi ORDER BY replid";
$result1=QueryDb($sql1);
$cnt=1;
	while ($row1=@mysqli_fetch_array($result1)){
	$sql2="SELECT COUNT(*) FROM jbsakad.mutasisiswa m,jbsakad.siswa s,jbsakad.kelas k,jbsakad.tahunajaran ta,jbsakad.tingkat ti WHERE s.idkelas=k.replid AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ti.departemen='$departemen' AND ta.departemen='$departemen' AND s.statusmutasi='".$row1['replid']."' AND m.jenismutasi='".$row1['replid']."' AND s.statusmutasi=m.jenismutasi AND m.nis=s.nis";
	$result2=QueryDb($sql2);
	$row2=@mysqli_fetch_row($result2);

?>
<tr><td><?=$cnt?></td><td><?=$row1['jenismutasi']?></td><td><?=$row2[0]?>&nbsp;siswa</td></tr>
<?php
$sql3="SELECT COUNT(*),YEAR(m.tglmutasi) FROM mutasisiswa m,siswa s,kelas k,tingkat ti,tahunajaran ta WHERE m.jenismutasi='".$row1['replid']."' AND YEAR(m.tglmutasi)<='$tahunakhir' AND YEAR(m.tglmutasi)>='$tahunawal' AND m.nis=s.nis AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND s.idkelas=k.replid AND ta.departemen='$departemen' AND ti.departemen='$departemen' GROUP BY YEAR(m.tglmutasi)";
$result3=QueryDb($sql3);
while ($row3=@mysqli_fetch_row($result3)){
?>
<tr><td>&nbsp;</td><td>-&nbsp;<?=$row3[1]?></td><td><?=$row3[0]?>&nbsp;siswa</td></tr>
<?php
}
$cnt++;
}
	
?>
  
</table>
    </td>
  </tr>
</table>

<script language="javascript">
window.print();
</script>
</body>
</html>