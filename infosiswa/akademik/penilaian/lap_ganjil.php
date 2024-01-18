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
require_once('../../include/errorhandler.php');
//require_once('../../include/sessioninfo.php');
require_once('../../include/common.php');
require_once('../../include/config.php');
require_once('../../include/getheader.php');
require_once('../../include/db_functions.php');

$departemen = $_REQUEST['departemen'];
$pelajaran = $_REQUEST['pelajaran'];
$kelas = $_REQUEST['kelas'];
$nis = $_REQUEST['nis'];


OpenDb();
$sql = "SELECT s.replid, s.semester, p.nama FROM semester s, pelajaran p WHERE s.departemen = '$departemen' AND p.replid = '$pelajaran' AND p.departemen = '$departemen' AND s.semester='Ganjil'"; 
$result = QueryDb($sql);

$i = 0;
while ($row = @mysqli_fetch_row($result)) {
	$sem[$i]= [$row[0], $row[1]];
	$pel = $row[2];
	$i++;
}

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
	<table width="100%" border="0" height="100%">
  	<tr>
    	<td valign="top" colspan="2"><font size="2" color="#000000"><b>Pelajaran <?=$pel?></b></font></td>      	
  	</tr>
  	<tr>
    	<td valign="right"></td>
  	</tr>
  	<tr>
   	 	<td valign="top" align="right">     
  		
        <a href="JavaScript:cetak('<?=$sem[0][0]?>')"><img src="../../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp; 
      	</td>
	</tr>
    
	<?php $sql = "SELECT j.replid, j.jenisujian FROM jenisujian j, ujian u WHERE j.idpelajaran = '$pelajaran' AND u.idjenis = j.replid GROUP BY j.jenisujian";
		$result = QueryDb($sql);
		while($row = @mysqli_fetch_array($result)){			
	?>
   	<tr>
        <td colspan="2">
        <br /><strong> <?=$row['jenisujian']?> </strong>
        <br /><br />		
		<table border="1" width="100%" id="table" class="tab">
		<tr>		
			<td width="5" height="30" align="center" class="header">No</td>
			<td width="250" class="header" align="center" height="30">Tanggal</td>
            <td width="10" height="30" align="center" class="header">Nilai</td>
			<td width="400" class="header" align="center" height="30">Keterangan</td>
		</tr>
		<?php 	OpenDb();		
			$sql1 = "SELECT u.tanggal, n.nilaiujian, n.keterangan FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas' AND u.idpelajaran = '$pelajaran' AND u.idsemester = '".$sem[0][0]."' AND u.idjenis = '".$row['replid']."' AND u.replid = n.idujian AND n.nis = '$nis' ORDER BY u.tanggal";
			$result1 = QueryDb($sql1);
			$sql2 = "SELECT AVG(n.nilaiujian) as rata FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas' AND u.idpelajaran = '$pelajaran' AND u.idsemester = '".$sem[0][0]."' AND u.idjenis = '".$row['replid']."' AND u.replid = n.idujian AND n.nis = '$nis' ";
			$result2 = QueryDb($sql2);
			$row2 = @mysqli_fetch_array($result2);
			$rata = $row2['rata'];

			/*
			$sql3 = "SELECT nau.nilaiAU as nilaiAU,nau.keterangan as keterangan  FROM ujian u, pelajaran p, nilaiujian n, nau nau WHERE u.idpelajaran = p.replid AND u.idkelas = $kelas AND u.idpelajaran = $pelajaran AND u.idsemester = ".$sem[0][0]." AND u.idjenis = ".$row['replid']." AND u.replid = n.idujian AND n.nis = '$nis' AND nau.idjenis=$row['replid'] AND nau.idpelajaran = $pelajaran AND nau.idsemester = ".$sem[0][0];
			$result3 = QueryDb($sql3);
			$row3 = @mysqli_fetch_array($result3);
			$nilaiAU = $row3['nilaiAU'];			
			*/
			$cnt = 1;
			if (@mysqli_num_rows($result1)>0){
			while($row1 = @mysqli_fetch_array($result1)){			
        ?>
        <tr>        			
			<td width="5" height="25" align="center"><?=$cnt?></td>
			<td width="250" height="25"><?=format_tgl($row1[0])?></td>
            <td width="10" height="25" align="center"><?=$row1[1]?></td>
            <td height="25"><?=$row1[2]?></td>            
		</tr>	
        <?php $cnt++;
			} ?>
		<tr>        			
			<td colspan="2" height="25" align="center"><strong>Nilai rata rata</strong></td>
			<td width="10" height="25" align="center"><?=round($rata,2)?></td>
            <td height="25">&nbsp;</td>            
		</tr><!--
		<tr>        			
			<td colspan="2" height="25" align="center"><strong>Nilai Akhir</strong></td>
			<td width="8" height="25" align="center"><?=$nilaiAU?></td>
            <td height="25"><?=$row3['keterangan']?></td>           
		</tr>-->
		<?php } else { ?>
		<tr>        			
			<td colspan="4" height="25" align="center">Tidak ada nilai</td>
		</tr>
		<?php }
			?>
		</table>
		<script language="javascript">
			Tables('table', 1, 0);
		</script>
		</td>	
	</tr>
    <?php } ?> 
    
    <!-- END TABLE CONTENT -->
    </table>
    </td>
</tr>
</table>