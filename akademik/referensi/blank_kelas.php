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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
?>
<html>
<head>
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css"  href="../style/style.css">
</head>
<body>
<table width="100%" border="0" height="100%">
<tr>
    <!--<td align="center" valign="middle" background="../images/ico/b_kelas.png"
    style="background-repeat:no-repeat;" width="20%"><br><br>
    </td>-->
    <td align="center">
    <?php 	OpenDb();		
		$sql = "SELECT * FROM departemen";    
		$result = QueryDb($sql);
		if (@mysqli_num_rows($result) > 0){
	?>	
        <font size="2" color="#757575"><b>Klik pada icon <img src="../images/ico/view_x.png" border="0"> di atas untuk melihat kelas sesuai dengan Departemen, Tingkat dan Tahun Ajaran terpilih</b></font>    
   	<?php } else { ?>
    	<font size = "2" color ="red"><b>Belum ada data Departemen.
        <br />Silahkan isi terlebih dahulu di menu Departemen pada bagian Referensi.
        </b></font>  
    <?php } ?>     
   	</td>
</tr>
</table>
</body>
</html>