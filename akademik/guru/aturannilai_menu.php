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
require_once('../cek.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aturan Perhitungan Grading Nilai-Menu</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function refresh() {	
	document.location.reload();
}

function tampil(id,nip) {	
	parent.aturan_nilai_content.location.href="aturannilai_content.php?id="+id+"&nip="+nip;
}

</script>
</head>
<body topmargin="5" leftmargin="5">
	<?php
	OpenDb();
	$sql = "SELECT d.departemen,pg.nama FROM guru g, pelajaran p, departemen d,jbssdm.pegawai pg WHERE g.idpelajaran = p.replid AND d.departemen = p.departemen AND g.nip ='".$_REQUEST['nip']."' AND pg.nip=g.nip GROUP BY d.departemen ORDER BY d.urutan";	
	
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result)>0){
		echo "<div align='left'><strong>Pelajaran yang diajar oleh guru ".$_REQUEST['nama']."</strong><br></div>";
		$count = 0;
		while ($row = @mysqli_fetch_row($result)) {				
		$count++;
	?>
   
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
  	<tr><td valign="top">
      	
	<table class="tab" id="table<?=$count?>" border="1" style="border-collapse:collapse" width="100%" align="left">
    	<!-- TABLE CONTENT -->
    
    <tr height="30">    	
    	<td width="100%" class="header" align="center"><?=$row[0];?></td>
    </tr>
    <?php 	
		$sql1 = "SELECT p.nama,p.replid FROM guru g, pelajaran p WHERE g.idpelajaran = p.replid AND g.nip='".$_REQUEST['nip']."' AND p.departemen = '".$row[0]."' GROUP BY p.nama";	
		$result1 = QueryDb($sql1); 				
		while ($row1 = @mysqli_fetch_array($result1)) {
	?>
    <tr>   	
       	<td align="left" height="25" onclick="tampil('<?=$row1[1]?>','<?=$_REQUEST['nip']?>')" style="cursor:pointer">
        <u><b><?=$row1[0]?></b></u>
		</td>
    </tr>
    <!-- END TABLE CONTENT -->
    <?php 		} ?>
	</table>
		 <script language='JavaScript'>
	    Tables('table<?=$count?>', 1, 0);
    </script>
    </td></tr>
<!-- END TABLE CENTER -->    
</table> 
<?php 
		}
	CloseDb(); 
	} else { 
?>
<table width="100%" border="0" align="center">          
<tr>
    <td align="center" valign="middle" height="200">
    <font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br /><br />Tambah data pelajaran yang akan diajar oleh guru <?=$_REQUEST['nama']?> di menu Pendataan Guru pada bagian Guru & Pelajaran. </b></font>
    </td>
</tr>
</table> 
<?php } ?> 


   

</body>
</html>