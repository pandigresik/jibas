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
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once("../include/sessionchecker.php");
require_once('../library/dpupdate.php');

OpenDb();

$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op == "xm8r389xemx23xb2378e23") 
{
	$sql = "DELETE FROM jbsakad.dasarpenilaian WHERE replid = '".$_REQUEST['replid']."'";
	$result = QueryDb($sql);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function refresh() {
	document.location.href = "aspeknilai.php";
}

function cetak() {
	newWindow('aspeknilai_cetak.php', 'CetakAspekNilai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
</head>

<body>

<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/ico/b_jenisujian.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
  <td align="left" valign="top">

	<table border="0"width="95%" align="center">
    <tr>
        <td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Aspek Penilaian</font></td>
    </tr>
    <tr>
        <td align="right">
        	<a href="../pelajaran.php">
          <font size="1" face="Verdana" color="#000000"><b>Pelajaran</b></font></a>&nbsp>&nbsp <font size="1" face="Verdana" color="#000000"><b>Aspek Penilaian</b></font>
          
        </td>
    </tr>
     <tr>
      <td align="left">&nbsp;</td>
      </tr>
	</table>
	<br /><br />
    <?php
	$sql = "SELECT replid, dasarpenilaian, keterangan 
			  FROM dasarpenilaian WHERE aktif = 1";
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0)
	{
	?>
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <!-- TABLE CONTENT -->
    <tr><td align="right">
    
    <a href="#" onClick="refresh()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')" />&nbsp;Cetak</a>&nbsp;&nbsp;    
    </td></tr>
    </table><br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="15%" class="header" align="center">Kode</td>
        <td width="*" class="header" align="center">Aspek Penilaian</td>
    </tr>
<?php 	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) { ?>
    <tr height="25">
    	<td align="center"><?=++$cnt ?></td>
      <td align="center"><?=$row[1] ?></td>
      <td><?=$row[2] ?></td>
    </tr>
<?php } ?>
    <!-- END TABLE CONTENT -->
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>

	</td></tr>
<!-- END TABLE CENTER -->    
</table>
<?php } else { ?>

<table width="100%" border="0" align="center">

<tr>
	<td align="center" valign="middle" height="250" colspan="2">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.</b></font>
	</td>
</tr>
</table>  
<?php } ?> 
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    

</body>
</html>
<?php CloseDb();?>