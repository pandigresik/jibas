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
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/departemen.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function show_content(id) {
	var dep = document.getElementById('departemen').value;
	parent.content.location.href = "pengeluaran_content.php?idpengeluaran="+id+"&departemen="+dep;
}

function change_dep() {
	var dep = document.getElementById('departemen').value;
	document.location.href = "pengeluaran_pilih.php?departemen="+dep;
	parent.content.location.href = "pengeluaran_blank.php";
}
</script>
</head>

<body onload="document.getElementById('departemen').focus()">
<table border="0" width="100%" align="center">
<tr>
    <td align="left" valign="top">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" align="left">
	<!-- TABLE LINK -->
	<tr>
    <td align="left" width="100%">
    <p><strong>Departemen&nbsp;</strong> 
    <select name="departemen" id="departemen" onchange="change_dep()" style="width:150px;">
<?php $dep = getDepartemen(getAccess());
    foreach($dep as $value) {
        if ($departemen == "")
            $departemen = $value; ?>
        <option value="<?=$value ?>" <?=StringIsSelected($departemen, $value) ?>  > <?=$value ?></option>
<?php 	} ?>            
    </select>
    </td>
</tr>
</table>
<br /><br />
<?php $sql = "SELECT replid AS id, nama FROM datapengeluaran WHERE aktif = 1 AND departemen='$departemen' ORDER BY nama";
	$request = QueryDb($sql);
	if (mysqli_num_rows($request) > 0) {
?>	
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
    <!-- TABLE CONTENT -->
    <tr height="30">
        <td class="header" width="4%" align="center">No</td>
        <td class="header" width="*" align="center">Pengeluaran</td>
    </tr>
<?php
	$cnt = 0;
	while ($row = mysqli_fetch_array($request)) { ?>
    <tr height="25" onClick="show_content(<?=$row['id'] ?>)" style="cursor:pointer;">
    	<td align="center"><?=++$cnt?></td>
        <td><a href="JavaScript:show_content(<?=$row['id'] ?>)"><?=$row['nama'] ?></a></td>
    </tr>
<?php } //end while ?>
	</table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
<?php } else { ?>
	&nbsp;
   
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="300">
    		<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br /><br />Tambah data pengeluaran pada departemen <?=$departemen?> di menu Jenis Pengeluaran pada bagian Pengeluaran. </b></font>        
		</td>
	</tr>
	</table>  
	
<?php } ?> 
	</td>
</tr>
<!-- END TABLE CENTER -->    
</table>    

<?php
CloseDb();
?>
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
</script>