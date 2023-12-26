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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Semester</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

</script>
</head>

<body>

<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/b_departemen.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
	<td width="180">&nbsp;</td>
	<td align="left" valign="top">

	<table border="0"width="78%">
    <!-- TABLE TITLE -->
    <tr>
        <td align="left"><font size="5" color="#660000"><b>TAHUN AJARAN</b></font></td>
    </tr>
    <tr>
        <td align="left"><a href="../referensi.php" target="content">
        <font size="1" color="#000000"><b>Referensi</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Tahun Ajaran</b></font>
        </td>
    </tr>
	</table><br />
    
    <table border="0" cellpadding="0" cellspacing="0" width="80%" align="left">
    <!-- TABLE LINK -->
    <tr>
    <td align="left" width="40%">
    Departemen:&nbsp;
    <select name="departemen" id="departemen">
<?php $dep = getDepartemen(SI_USER_ACCESS());    
	foreach($dep as $value) {
		if ($departemen == "")
			$departemen = $value; ?>
		<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > <?=$value ?> </option>
<?php } ?>
	</select>
    </td>
    <td align="right" width="60%">
    <a href="#" onclick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" />&nbsp;Refresh</a>&nbsp;&nbsp;
    <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" />&nbsp;Cetak</a>&nbsp;&nbsp;
<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
	    <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" />&nbsp;Tambah Semester</a>
<?php } ?>    
    </td></tr>
    </table><br /><br />
    
    <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="80%" align="left">
    <!-- TABLE CONTENT -->
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="25%" class="header">Tahun Ajaran</td>
        <td width="40%" class="header">Tgl Mulai</td>
        <td width="40%" class="header">Tgl Kahir</td>
        <td width="15%" class="header" align="center">Status</td>
        <td width="15%" class="header">Keterangan</td>
        <td width="*" class="header">&nbsp;</td>
    </tr>
    <!-- 
    	=============================
        TAMBAHKAN BARIS KODE DISINI
        =============================
	//-->        
    
    <!-- END TABLE CONTENT -->
    </table>
    
<?php CloseDb() ?>    
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>


	</td></tr>
<!-- END TABLE CENTER -->    
</table>

</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    

</body>
</html>