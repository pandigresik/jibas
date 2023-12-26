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
$stat = $_REQUEST['stat'];
if ($stat == 5) 
{
	header("location: statdiklat.php");
	exit();
} 
elseif ($stat == 6) 
{
	header("location: statjk.php");
	exit();
} 
elseif ($stat == 7) 
{
	header("location: statnikah.php");
	exit();
} 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style.css" />
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function Refresh() {
	parent.parent.location.href = "statcontent.php?stat=<?=$stat?>";
}

function Cetak() {
	newWindow('stat_cetak.php?stat=<?=$stat?>', 'CetakStatistik','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

</script>
</head>

<body>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td align="center">
    <a href="JavaScript:Cetak()"><img src="../images/ico/print.png" border="0" />&nbsp;Cetak</a>&nbsp;
    <a href="JavaScript:Refresh()"><img src="../images/ico/refresh.png" border="0" />&nbsp;Refresh</a>
</td></tr>
</table>
<br />
<table width="100%" border="0">
<tr><td>

	<div id="grafik" align="center">
	<table width="100%" border="0" align="center">
    <tr><td>
    	<div align="center">
        <img src="<?= "statimage.php?type=bar&stat=$stat" ?>" />
        </div>
    </td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>
    	<div align="center">
        <img src="<?= "statimage.php?type=pie&stat=$stat" ?>" />
        </div>
    </td></tr>
	</table>
	</div>
    
</td></tr>
</table>

</body>
</html>