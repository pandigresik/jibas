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
require_once('../include/sessioninfo.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../library/departemen.php');
require_once('../cek.php');
require_once('pengantar.func.php');

$departemen = $_REQUEST['departemen'] ?? "";
$status = isset($_REQUEST['status']) ? (int)$_REQUEST['status'] : 2;
$op = $_REQUEST['op'];

OpenDb();
if ($op == "cqiqywpxwq")
{
	$id = $_REQUEST['id'];
	$sql = "DELETE FROM jbsumum.pengantarsurat
			 WHERE replid = $id";
	QueryDb($sql);		 
}
elseif ($op == "mxd238mhde2")
{
	$id = $_REQUEST['id'];
	$newstatus = $_REQUEST['newstatus'];
	
	$sql = "UPDATE jbsumum.pengantarsurat
			   SET aktif = $newstatus
			 WHERE replid = $id";
	QueryDb($sql);		 
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
<link rel="stylesheet" type="text/css" href="penyusunan.css">	
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/jquery-1.9.1.js"></script>
<script language="javascript" src="pengantar.js"></script>
</head>

<body>

<table border="0" width="100%" height="100%">
<tr><td align="center" valign="top" background="../images/ico/b_daftarmutasi.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<tr height="300">
    <td align="left" valign="top">

	<table border="0"width="95%" align="center">
    <tr>
        <td align="right">
            <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pengantar Surat</font>
        </td>
    </tr>
    <tr>
        <td align="right"><a href="../pelaporanmenu.php" target="content">
            <font size="1" face="Verdana" color="#000000"><b>Pelaporan</b></font></a>&nbsp>&nbsp <font size="1" face="Verdana" color="#000000"><b>Pengantar Surat</b></font>
        </td>
    </tr>
    <tr>
        <td align="left">&nbsp;</td>
    </tr>
	</table>
    
    <br /><br />
    
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <tr>
		<td align="left" width='12%'>
			&nbsp;
		</td>	
		<td align="left" width='30%'>
			Departemen:&nbsp;
			<select class='inputbox' name="departemen" id="departemen" style="width:130px;"
					onChange="changeSelect()">
<?php 		$dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value)
			{
				if ($departemen == "")
					$departemen = $value;
				$sel = $departemen == $value ? "selected" : "";	?>
				<option value="<?=$value?>" <?=$sel?> ><?=$value ?></option>
<?php 		} ?>
			</select>
			&nbsp;&nbsp;
			Status:
			<select class='inputbox' name="status" id="status" onChange="changeSelect()">
				<option value='2' <?= IntIsSelected($status, 2)?>>(Semua)</option>
				<option value='1' <?= IntIsSelected($status, 1)?>>Aktif</option>
				<option value='0' <?= IntIsSelected($status, 0)?>>Non Aktif</option>
			</select>
		</td>
		<td align="right" width='50%'>
    
			<a href="#" onClick="refresh();"><img src="../images/ico/refresh.png" border="0"/>&nbsp;Refresh</a>&nbsp;&nbsp;
<?php 		if (SI_USER_LEVEL() != $SI_USER_STAFF) 
			{ ?>
				<a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0"/>&nbsp;Tambah</a>
<?php 		} ?>

		</td>
	</tr>
	<tr>
		<td align="left" width='12%'>
			&nbsp;
		</td>
		<td align="left" width='*' colspan="2">
			
			<br>
			<table border='1' style='border-width: 1px; border-collapse: collapse;' cellpadding='5' cellspacing='0'>
			<tr height='30'>
				<td class='header' align='center' width='25'>No</td>
				<td class='header' width='140'>Tanggal/Petugas</td>
				<td class='header' width='750'>Judul/Pengantar Surat</td>
				<td class='header' align='center' width='50'>Aktif</td>
				<td class='header' align='center' width='50'>&nbsp;</td>		
			</tr>
		<?php ShowList() ?>	
			</table>	
			
		</td>	
	</tr>
    </table>
    
    </td>
</tr>
</table>

</td></tr>
</table>    

</body>
</html>
<?php CloseDb();?>