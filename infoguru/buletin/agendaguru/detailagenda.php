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
require_once('../../include/common.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
require_once('../../include/sessionchecker.php');

OpenDb();
$sql="SELECT * FROM jbsvcr.agenda WHERE replid='".$_REQUEST['replid']."'";
$result=QueryDb($sql);
$row=@mysqli_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {
	font-family: "Lucida Handwriting";
	color: #666666;
}
.style2 {
	font-family: "Verdana";
	color: #009999;
}
-->
</style>
</head>
<body >
<div align="right">
<font size="4" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" color="Gray">Agenda Guru</font><br />
<a href="../../home.php" style="color:#0000FF" target="framecenter">Home</a> > <strong>Agenda Guru</strong><br /><br /><br />
<br /><br />
</div>
<table width="100%" style="background-color:#FFFFFF;"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="100%"  align="center" valign="top" scope="row"><table width="350"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="40" height="69" style="background-image:url(../../images/agenda_01.jpg); background-repeat:no-repeat;">&nbsp;</td>
    <td width="236" height="69" valign="bottom" style="background-image:url(../../images/agenda_03.jpg); background-repeat:repeat-x;"><div align="right"><span class="style1"><?=ShortDateFormat($row['tanggal'])?></span><br>
        </div></td>
    <td width="42" height="69" style="background-image:url(../../images/agenda_04.jpg); background-repeat:no-repeat">&nbsp;</td>
    <td width="32" height="69" style="background-image:url(../../images/agenda_05.jpg); background-repeat:no-repeat">&nbsp;</td>
  </tr>
  <tr>
    <td width="40" height="13" style="background-image:url(../../images/agenda_06.jpg); background-repeat:repeat-y">&nbsp;</td>
    <td width="236" height="13" style="background-image:url(../../images/agenda_09.jpg);"><span class="style2"><?=$row['judul']?></span><br /><br />
	<?php
	$komentar = $row['komentar'];
	$komentar = str_replace("#sq;", "'", (string) $komentar);
    $komentar = str_replace("`", "\"", $komentar);
	echo $komentar;
	?>
	</td>
    <td width="42" height="13" style="background-image:url(../../images/agenda_09.jpg);">&nbsp;</td>
    <td width="32" height="13" style="background-image:url(../../images/agenda_10.jpg); background-repeat:repeat-y">&nbsp;</td>
  </tr>
  <tr>
    <td width="40" height="39" style="background-image:url(../../images/agenda_16.jpg); background-repeat:no-repeat">&nbsp;</td>
    <td width="236" height="39" style="background-image:url(../../images/agenda_18.jpg); background-repeat:repeat-x">&nbsp;</td>
    <td width="42" height="39" style="background-image:url(../../images/agenda_18.jpg); background-repeat:repeat-x">&nbsp;</td>
    <td width="32" height="39" style="background-image:url(../../images/agenda_20.jpg); background-repeat:no-repeat">&nbsp;</td>
  </tr>
</table></th>
  </tr>
</table>

</body>
</html>