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
function getHeader($dep){
	global $full_url;
	OpenDb();
	$sql = "SELECT * FROM jbsumum.identitas WHERE departemen='$dep'";
	//return $sql;exit;
	$result = QueryDb($sql); 
	$num = @mysqli_num_rows($result);
	$row = @mysqli_fetch_array($result);
	$replid = $row['replid'];
	$nama = $row[nama];
	$alamat1 = $row[alamat1];
	$alamat2 = $row[alamat2];
	$te1p1 = $row[telp1];
	$telp2 = $row[telp2];
	$te1p3 = $row[telp3];
	$telp4 = $row[telp4];
	$fax1 = $row[fax1];
	$fax2 = $row[fax2];
	$situs = $row[situs];
	$email = $row[email];
	$head =	"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
	$head .="	<tr>";
	$head .="		<td width=\"20%\" align=\"center\">";
	$head .="		<img src=\"".$full_url."library/header.php?replid=".$replid."&table=jbsumum.identitas\" />";
	$head .="		</td>";
	$head .="		<td valign=\"top\">";
						if ($num >  0) {	
	$head .="				<font size=\"5\"><strong>".$nama."</strong></font><br />".
			"				<strong>";
						if ($alamat2 <> "" && $alamat1 <> "")
	$head .="				Lokasi 1: ";
						if ($alamat1 != "") 
	$head .=				$alamat1;
									
						if ($telp1 != "" || $telp2 != "") 
	$head .="				<br>Telp. ";	
						if ($telp1 != "" ) 
	$head .=				$telp1;	
						if ($telp1 != "" && $telp2 != "") 
	$head .="				, ";
						if ($telp2 != "" ) 
	$head .=				$telp2;			
						if ($fax1 != "" ) 
	$head .="				&nbsp;&nbsp;Fax. ".$fax1."&nbsp;&nbsp;";
						if ($alamat2 <> "" && $alamat1 <> "") {
	$head .="				<br>";
	$head .="				Lokasi 2: ";
	$head .=				$alamat2;
											
						if ($telp3 != "" || $telp4 != "") 
	$head .="				<br>Telp. ";	
						if ($telp3 != "" ) 					
	$head .=				$telp3;
						if ($telp3 != "" && $telp4 != "") 
	$head .="				, ";
						if ($telp4 != "" ) 
	$head .=				$telp4;				
						if ($fax2 != "" ) 
	$head .="				&nbsp;&nbsp;Fax. ".$fax2;	
						}
						if ($situs != "" || $email != "")
	$head .="				<br>";
						if ($situs != "" ) 
	$head .="				Website: ".$situs."&nbsp;&nbsp;";
						if ($email != "" ) 
	$head .="				Email: ".$email;
						
	$head .="			</strong>";
						}  
	$head .="			</td>";
	$head .="		</tr>";
	$head .="		<tr>";
	$head .="			<td colspan=\"2\"><hr width=\"100%\" /></td>";
	$head .="		</tr>";
	$head .="		</table>";
	$head .="	<br />";

	echo $head;
}
?>