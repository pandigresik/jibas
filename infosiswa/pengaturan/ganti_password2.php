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
require_once("../include/sessioninfo.php");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function ganti() {
	var login=document.getElementById('login').value;
	if (login=="LANDLORD" || login=="landlord"){
		alert ('Maaf, Administrator tidak dapat mengganti password !');
		parent.framecenter.location.href="../center.php";
	} else {
		newWindow('ganti_password.php','GantiPasswordUser',419,151,'resizable=0,scrollbars=0,status=0,toolbar=0');
	}
}
</script>
<style type="text/css">
<!--
.style2 {
	font-weight: bold;
	font-size: 16px;
}
-->
</style>
</head>
<body leftmargin="0" topmargin="0" onload="ganti()">
<input type="hidden" id="login" value="<?=trim((string) SI_USER_NAME())?>">
</body>
</html>