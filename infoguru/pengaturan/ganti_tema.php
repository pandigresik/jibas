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
require_once("../include/sessionchecker.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> New Document </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<script language="javascript">
	var currenttheme=parseInt(parent.frametop.document.top.theme.value);
	var nexttheme=currenttheme+1;
	//alert ('Masuk, temanya='+currenttheme);
	//alert ('Lalu='+nexttheme);
	//parent.frametop.change_theme();
	if (currenttheme==5){
		//alert ('Ini 5');
		parent.frametop.location.href="../frametop.php?theme=1";
		parent.frameleft.location.href="../frameleft.php?theme=1";
		parent.framebottom.location.href="../framebottom.php?theme=1";
		parent.frameright.location.href="../frameright.php?theme=1";
	} else {
		//alert ('Bukan 5');
		parent.frametop.location.href="../frametop.php?theme="+nexttheme;
		parent.frameleft.location.href="../frameleft.php?theme="+nexttheme;
		parent.framebottom.location.href="../framebottom.php?theme="+nexttheme;
		parent.frameright.location.href="../frameright.php?theme="+nexttheme;
	}
	//document.location.href="../center.php";
</script>
</HEAD>
<BODY>
</BODY>
</HTML>