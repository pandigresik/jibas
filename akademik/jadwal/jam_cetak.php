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
require_once('../cek.php');
require_once('../include/getheader.php');
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
OpenDb();
$sql_jam="SELECT replid, jamke, HOUR(jam1) As jammulai, MINUTE(jam1) As menitmulai, HOUR(jam2) As jamakhir, MINUTE(jam2) As menitakhir FROM jbsakad.jam WHERE departemen='$departemen' ORDER BY jamke ASC";
$result_jam=QueryDb($sql_jam);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Definisi Jam <?=$departemen?>]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center><font size="4"><strong>DEFINISI JAM</strong></font><br /> </center><br /><br />

<br />
	<table id="table" border="0" width="95%" />
    <tr><td align="left">
    &nbsp;&nbsp;&nbsp;&nbsp;<strong>Departemen :</strong> <?=$departemen?>
    </td></tr>
    </table><br />
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000" />
    <!-- TABLE CONTENT -->
    <tr height="30" class="header" align="center">	
		<td width="20%">Jam ke</td>
	  	<td width="*">Waktu</td>
	</tr>
	<?php 
		while ($row_jam=@mysqli_fetch_row($result_jam)){
			if ((int)$row_jam[2]<10) 
				$jammulai="0".$row_jam[2]; 
			else  
				$jammulai=$row_jam[2]; 
					
			if ((int)$row_jam[3]<10) 
				$menitmulai="0".$row_jam[3]; 
			else  
				$menitmulai=$row_jam[3]; 
				
			if ((int)$row_jam[4]<10) 
				$jamakhir="0".$row_jam[4]; 
			else  
				$jamakhir=$row_jam[4]; 
					
			if ((int)$row_jam[5]<10) 
				$menitakhir="0".$row_jam[5]; 
			else  
				$menitakhir=$row_jam[5]; 
				
                    
	?> 
	<tr height="25">
		<td align="center"><?=$row_jam[1] ?> </td>
		<td><?=$jammulai.":".$menitmulai ?> - <?=$jamakhir.":".$menitakhir ?></td>
	</tr>
	<?php }	CloseDb();	?> 
	</table>  

</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>