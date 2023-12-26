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
require_once("../inc/session.checker.php");
require_once("../inc/config.php");
require_once("../inc/db_functions.php");
require_once("../inc/common.php");
$op	= $_REQUEST['op'];
OpenDb();
if ($op=='Edit'){
	$perpustakaan=$_REQUEST['perpustakaan'];
	$sql = "SELECT replid FROM ".$db_name_umum.".identitas WHERE status='1' AND perpustakaan='$perpustakaan'";
	$result = QueryDb($sql);
	$row  =@mysqli_fetch_array($result);
	?>
    <table width="300" border="0" cellspacing="2">
      <tr>
        <td valign="middle" align="center">
        	<img src='../lib/gambar.php?replid=<?=$row['replid']?>&table=<?=$db_name_umum?>.identitas&field=foto'><br />Logo Lama
        </td>
        <td valign="middle"><img src='../img/Arrow-Left.png' /></td>
        <td valign="middle" align="center"><img src='temp.jpg' /><br />Logo Baru</td>
      </tr>
    </table>
	<?php
} else {
	echo "<img src='temp.jpg' /><br />Logo Baru";
}
?>