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
//require_once('../include/config.php');
/*
function cari_gambar(){
 	if(file_exists("../library/gambar.php")) {
	   $addr = "../library/gambar.php";
	} else {
		if(file_exists("../../library/gambar.php")) {
	          $addr = "../../library/gambar.php";
		} else	{
			   $addr = "../../../library/gambar.php";
		}
	}
		return $addr ; 
}
*/
OpenDb();
$sql_identitas = "SELECT * FROM jbsumum.identitas";
$result_identitas = QueryDb($sql_identitas); 
$row_iden = mysqli_fetch_array($result_identitas);
$replid_logo = $row_iden['replid'];

?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td width="20%" align="center">
    <img src="<?=$G_WEB_ADDR?>library/gambar.php?replid=<?=$replid_logo?>&table=jbsumum.identitas" />
	<!--<img src="../images/logokecil.gif" border="0" />-->
	</td>
    <td valign="top">
  
       	<?php 
			$result_identitas = QueryDb($sql_identitas); 
			if (mysqli_num_rows($result_identitas) >  0) {	
			$row_identitas = mysqli_fetch_array($result_identitas);?>
            <font size="5"><strong><?=$row_identitas['nama']?></strong></font><br />
            <strong>
		<?php 	if ($row_identitas['alamat2'] <> "" && $row_identitas['alamat1'] <> "")
            	echo "Lokasi 1: ";
		  	if ($row_identitas['alamat1'] != "") 
				echo $row_identitas['alamat1'];
			echo "<br>";
			
			if ($row_identitas['telp1'] != "" || $row_identitas['telp2'] != "") 
				echo "Telp. ";	
			if ($row_identitas['telp1'] != "" ) 
				echo $row_identitas['telp1'];	
			if ($row_identitas['telp1'] != "" && $row_identitas['telp2'] != "") 
					echo ", ";
			if ($row_identitas['telp2'] != "" ) 
				echo $row_identitas['telp2'];			
			if ($row_identitas['fax1'] != "" ) 
				echo "&nbsp;&nbsp;Fax. ".$row_identitas['fax1']."&nbsp;&nbsp;";
			if ($row_identitas['alamat2'] <> "" && $row_identitas['alamat1'] <> "") {
				echo "<br>";
            	echo "Lokasi 2: ";
				echo $row_identitas['alamat2'];
				echo "<br>";
				
				if ($row_identitas['telp3'] != "" || $row_identitas['telp4'] != "") 
					echo "Telp. ";	
				if ($row_identitas['telp3'] != "" ) 
					echo $row_identitas['telp3'];
				if ($row_identitas['telp3'] != "" && $row_identitas['telp4'] != "") 
					echo ", ";
				if ($row_identitas['telp4'] != "" ) 
					echo $row_identitas['telp4'];				
				if ($row_identitas['fax2'] != "" ) 
					echo "&nbsp;&nbsp;Fax. ".$row_identitas['fax2'];	
			}
			echo "<br>";
			if ($row_identitas['situs'] != "" ) 
				echo "Website: ".$row_identitas['situs']."&nbsp;&nbsp;";
			if ($row_identitas['email'] != "" ) 
				echo "Email: ".$row_identitas['email'];
			
		?>
            </strong>
   		<?php }  ?>
    </td>
</tr>
<tr>
	<td colspan="2"><hr width="100%" /></td>
</tr>
</table>
<br />