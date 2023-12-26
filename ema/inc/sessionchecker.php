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
session_name("jbsema");
session_start();

if (!isset($_SESSION['login']))
{ 
	   if (file_exists("login.php")) 
		  $addr = "login.php";
	   elseif (file_exists("../login.php")) 
		  $addr = "../login.php";
	   elseif(file_exists("../../login.php")) 
		  $addr = "../../login.php";
	   else	
		  $addr = "../../../login.php";
		  ?>		  
	   <script language="javascript">
	   if(self!=self.top)
	   {
			  top.window.location.href='<?=$addr?>';
	   }
	   else if(self.name!="")
	   {
			  opener.top.window.location.href='<?=$addr?>';
			  window.close();
	   }	
	   else
	   {
			  window.location.href='<?=$addr?>';	
	   }
	   </script>
<?php
	   exit();
}  ?>