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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../include/sessioninfo.php');
OpenDb();
$dbnameperpus = "jbsperpus";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS INFOGURU [Daftar Siswa]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<link rel="stylesheet" type="text/css" href="../script/tooltips.css" />
<script src="../script/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tooltips.js"></script>
<link rel="stylesheet" type="text/css" href="../style/calendar-win2k-1.css">
<script language="javascript" src="../script/cal2.js"></script>
<script language="javascript" src="../script/cal_conf2.js"></script>
<script type="text/javascript">
function SelectCatalog(IdKatalog){
	ShowWaits('MainBookList');
	sendRequestText('GetMainBookList.php',ShowMainBook,'IdKatalog='+IdKatalog);
}
function ShowWaits(x){
	var img = "<img src='../images/ico/loader.gif'>&nbsp;Please wait...";
	document.getElementById(x).innerHTML = img;
}
function ShowMainBook(x){
	document.getElementById('MainBookList').innerHTML = x;
	Tables('table', 1, 0);
}
function ViewData(IdKatalog){
	var keyword = document.getElementById('search').value;
	if (keyword!='' && keyword!='Cari'){
		ShowWaits('BookList');
		sendRequestText('GetBookList.php',ShowBook,'IdKatalog='+IdKatalog+'&keyword='+keyword);
	}
}
function ShowBook(x){
	document.getElementById('BookList').innerHTML = x;
	Tables('table', 1, 0);
}
function FocusText(State){
	var x = document.getElementById('search').value;
	if (State=='1'){
		if (x=='Cari'){
			document.getElementById('search').value = '';
			document.getElementById('search').style.color = '#000000';
		} else {
			document.getElementById('search').style.color = '#000000';
		}
	} else {
		if (x=='Cari'){
			document.getElementById('search').style.color = '#999999';
		} else {
			if (x==''){
				document.getElementById('search').value = 'Cari';
				document.getElementById('search').style.color = '#999999';
			} else {
				document.getElementById('search').style.color = '#000000';
			}
		}
	}
}
function ViewDetail(replid){
	newWindow('pustaka.view.detail.php?replid='+replid+'&sender=opac', 'DetailPustaka','509','456','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
<style type="text/css">
<!--
.style1 {font-size: 16px}
-->
</style>
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF">
<?php
$sql 	= "SELECT * FROM $dbnameperpus.katalog";
$result = QueryDb($sql);
$num	= @mysqli_num_rows($result);
if ($num>0){
	?>
	<table width="99%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td width="27%" style="padding-left:3px; padding-right:5px;" valign="top">
		<div class="brown" style="padding-top:5px; padding-bottom:5px; font-size: 14px; font-weight: bold;">Katalog</div>
		<table width="100%" border="1" cellspacing="0" cellpadding="0" id="Table1" class="tab">
		<?php
		while ($row = @mysqli_fetch_array($result)){
			?>
			<tr>
				<td width="27" class="td"><?=$row['kode']?></td>
				<td width="100%" class="td"><?=$row['nama']?></td>
				<td width="20" class="td"><img onclick="SelectCatalog('<?=$row['replid']?>')" style="cursor:pointer" src="../images/ico/panahkanan.png" width="16" height="16" /></td>
			</tr>
			<?php 
		}
		?>
		</table>
		<script language="javascript">
			Tables('Table1',0);
		</script>
		</td>
		<td width="70%" valign="top">
		<div class="brown" style="padding-top:5px; padding-bottom:5px; font-size: 14px; font-weight: bold; padding-left:7px">Daftar Buku</div>
		<div id="MainBookList">
			<div align="center" style="padding-top:50px;color: #FF9900;font-weight: bold;font-size: 16px;">
			Silakan Pilih Katalog
			</div>
		</div>
		</td>
	  </tr>
	</table>
	<?php
} else {
	?>
	<center><span style="color:#FF0000; font-size:12px">Tidak ada data</span></center>  
	<?php
}
?>
</body>
</html>