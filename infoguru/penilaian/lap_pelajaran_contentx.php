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

$departemen = $_REQUEST['departemen'];
$pelajaran = $_REQUEST['pelajaran'];
$kelas = $_REQUEST['kelas'];
$nis = $_REQUEST['nis'];


OpenDb();
$sql = "SELECT s.replid, s.semester, p.nama FROM semester s, pelajaran p WHERE s.departemen = '$departemen' AND p.replid = '$pelajaran' AND p.departemen = '".$departemen."'"; 
$result = QueryDb($sql);

$i = 0;
while ($row = @mysqli_fetch_row($result)) {
	$sem[$i]= [$row[0], $row[1]];
	$pel = $row[2];
	$i++;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Penilaian Pelajaran </title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function refresh() {	
	document.location.reload();
}

function cetak() {
	var id = document.getElementById('id').value;	
	var nip = document.getElementById('nip').value;
	newWindow('aturannilai_cetak.php?id='+id+'&nip='+nip, 'CetakAturanPenentuanGradingNilai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
</head>
<body topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
	<table width="100%" border="0" height="100%">
  	<tr>
    	<td valign="top" colspan="2"><font size="4" color="#660000"><b>Pelajaran <?=$pel?></b></font></td>      	
  	</tr>
  	<tr>
    	<td valign="right"></td>
  	</tr>
  	<tr>
   	 	<td><b>Semester <?=$sem[0][1]?></b></td>
        <td valign="top" align="right">     
  		
        <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp; 
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
			<td class="header" align="center" height="30">No</td>
			<td class="header" align="center" height="30">Tanggal</td>
            <td class="header" align="center" height="30">Nilai</td>
			<td class="header" align="center" height="30">Keterangan</td>
		</tr>
		<?php 	OpenDb();		
			$sql1 = "SELECT u.tanggal, n.nilaiujian, n.keterangan FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas' AND u.idpelajaran = '$pelajaran' AND u.idsemester = '".$sem[0][0]."' AND u.idjenis = '".$row['replid']."' AND u.replid = n.idujian AND n.nis = '$nis' ORDER BY u.tanggal";
			
			$result1 = QueryDb($sql1);
			$cnt = 0;
			while($row1 = @mysqli_fetch_array($result1)){			
        ?>
        <tr>        			
			<td align="center" height="25"><?=++$cnt?></td>
			<td height="25"><?=$row1[0]?></td>
            <td height="25"><?=$row1[1]?></td>
            <td height="25"><?=$row1[2]?></td>            
		</tr>	
        <?php } ?>
		</table>	
		</td>	
	</tr>
    <?php } ?> 
    
    <!-- END TABLE CONTENT -->
    </table>
    </td>
</tr>
</table>

</body>
</html>