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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Status Guru</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function refresh() {	
	document.location.reload();
}
</script>
</head>

<body background="../images/buatdibawah_500.jpg">
<table border="0" width="100%" align="center" >
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top"><strong>Pelajaran yang Diajarkan Guru</strong>
	</td>
</tr>
<tr>
	<td>
	<?php
	OpenDb();
	$sql = "SELECT d.departemen FROM guru g, pelajaran p, departemen d WHERE g.idpelajaran = p.replid AND d.departemen = p.departemen AND g.nip ='".$_REQUEST['nip']."' GROUP BY d.departemen ORDER BY d.urutan";	
	
	$result = QueryDb($sql);
	$jumlah = mysqli_num_rows($result);
	
	if ($jumlah > 0) {			
		while ($row = @mysqli_fetch_row($result)) {		
	?>
	</td>
    <tr><td>&nbsp;</td></tr>
	<table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left">
    	<!-- TABLE CONTENT -->
    
    <tr height="30">    	
    	<td width="100%" class="header" align="center"><?=$row[0];?></td>
    </tr>
    <?php 	
		$sql1 = "SELECT p.nama,p.replid FROM guru g, pelajaran p WHERE g.idpelajaran = p.replid AND g.nip ='".$_REQUEST['nip']."' AND p.departemen = '".$row[0]."' GROUP BY p.nama";
		$result1 = QueryDb($sql1); 				
		while ($row1 = @mysqli_fetch_array($result1)) {
	?>
    <tr>   	
       	<td align="center">
        <a href="aturannilai_content.php?id=<?=$row1[1]?>&nip=<?=$_REQUEST['nip']?>" target = "isi" ><?=$row1[0]?></a>
		</td>
    </tr>
    <!-- END TABLE CONTENT -->
    <?php 		} ?>
	</table>
		 <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
<?php 
	}
} 
	CloseDb(); 
?>	
	</td>	
</tr>
<!-- END TABLE CENTER -->    
</table> 
   

</body>
</html>