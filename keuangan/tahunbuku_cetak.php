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
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/getheader.php'); 
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];
$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Tahun Buku]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>


<center><font size="4"><strong>TAHUN BUKU</strong></font><br /> </center><br /><br />

<table border="0">
<tr>
	<td><strong>Departemen : <?=$departemen ?></strong></td>
</tr>
</table>
<br />

    <table id="table" class="tab" bordercolor="#000000" border="1" style="border-collapse:collapse" width="100%">
	<tr height="30" align="center">
        <td class="header" width="5%">No</td>
        <td class="header" width="12%">Tahun Buku</td>
        <td class="header" width="18%">Tanggal Mulai</td>
        <td class="header" width="15%">Awalan Kuitansi</td>
        <td class="header" width="*">Keterangan</td>
	</tr>
    <?php
	OpenDb();
	$sql = "SELECT * FROM tahunbuku WHERE departemen='$departemen' ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	$result = QueryDb($sql);
	
	//if ($page==0)
		$cnt = 0;
	//else 
		//$cnt = (int)$page*(int)$varbaris;
		
	while($row = mysqli_fetch_array($result)) {
	?>
    <tr height="25">
    	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['tahunbuku'] ?></td>
        <td align="center"><?=LongDateFormat($row['tanggalmulai']) ?></td>
        <td align="center"><?=$row['awalan'] ?></td>
        <td><?=$row['keterangan'] ?></td>
    </tr>
<?php  }
	CloseDb(); ?>
     <!-- END TABLE CONTENT -->
    </table>
	</td>
</tr>
    </table>
</td></tr></table>
</body>
</html>
<script language="javascript">window.print();</script>