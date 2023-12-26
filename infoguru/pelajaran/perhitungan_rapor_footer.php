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
require_once('../library/dpupdate.php');
require_once("../include/sessionchecker.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aturan Perhitungan Nilai Rapor[Menu]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function tampil(id,nip) {
	parent.perhitungan_rapor_content.location.href="perhitungan_rapor_content.php?id_pelajaran="+id+"&nip_guru="+nip;
}

</script>
</head>
<body>
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->

<?php
OpenDb();
$sql="SELECT DISTINCT pel.departemen FROM pelajaran pel, guru g, departemen  d WHERE g.nip='".SI_USER_ID()."' AND pel.replid=g.idpelajaran AND pel.departemen = d.departemen ORDER BY d.urutan";
$result = QueryDb($sql);
$cnt = 0;
if ((@mysqli_num_rows($result))>0){
?>
		
	<?php
	while ($row = @mysqli_fetch_array($result)) {
		$departemen=$row[0];
		
	?>
       <tr><td> 
        <table class="tab" id="table" border="1" style="border-collapse:collapse"  width="100%" align="left" bordercolor="#000000">

        <tr>
        	<td class="header" align="center" height="30"><?=$departemen?></td></tr>
   	<?php 
		$sql2="SELECT pel.nama,pel.departemen,pel.replid FROM pelajaran pel, guru g WHERE g.nip='".SI_USER_ID()."' AND pel.replid=g.idpelajaran AND pel.departemen='$departemen' GROUP BY pel.nama";
		$result2 = QueryDb($sql2);
		$cnt2 = 0;
		while ($row2 = @mysqli_fetch_array($result2)) {
			$nama_pelajaran=$row2[0];
		?>
		<tr>
        	<td align="center" height="25" onclick="tampil('<?=$row2[2]?>','<?=SI_USER_ID()?>','<?=$departemen?>')" style="cursor:pointer"><b><?=$nama_pelajaran?></b>           
            </td>
        </tr>
		<?php
			$cnt2++;
        }
		?></table>
		<script language='JavaScript'>
	    Tables('table', 1, 0);
		</script>
		<?php
 	}
	CloseDb();
} else { 
?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data <br /><br />Tambah data pelajaran yang diajar oleh guru <?=$_REQUEST['nama']?> di menu pendataan guru </b></font>
		</td>
	</tr>
	</table> 
<?php } ?> 

<!-- END TABLE CENTER -->    
</table> 
        
</body>
</html>