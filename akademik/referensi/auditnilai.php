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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');

$bulan = date('n');
if (isset($_REQUEST['bulan']))
	$bulan = $_REQUEST['bulan'];
	
$tahun = date('Y');
if (isset($_REQUEST['tahun']))
	$tahun = $_REQUEST['tahun'];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Audit Perubahan Data Nilai]</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function change_date()
{
	var bulan = document.getElementById('bulan').value;
	var tahun = document.getElementById('tahun').value;
	document.location.href = "auditnilai.php?bulan="+bulan+"&tahun="+tahun;
}
</script>
</head>
<body onload="document.getElementById('bagian').focus()">
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/Draft.png" style="margin:0;padding:0;background-repeat:no-repeat;background-attachment:fixed;margin-left:10">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top">

	<table border="0"width="95%" align="center">
  <tr>
  	<td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Audit Perubahan Nilai</font></td>
  </tr>
  <tr>
  	<td align="right"><a href="../usermenu.php" target="content">
    <font size="1" color="#000000"><b>Pengaturan</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Audit Perubahan Nilai</b></font>
    </td>
  </tr>
  <tr>
  	<td align="left">&nbsp;</td>
  </tr>
	</table>
	<br /><br />
  
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Bulan:
    <select name="bulan" id="bulan" onchange="change_date()">
<?php  for ($i = 1; $i <= 12; $i++) { ?>
		 <option value="<?=$i?>" <?=IntIsSelected($i, $bulan)?> ><?=NamaBulan($i)?></option>
<?php  } ?>
    </select>
    <select name="tahun" id="tahun" onchange="change_date()">
<?php  for ($i = $G_START_YEAR; $i <= date('Y') + 1; $i++) { ?>
		 <option value="<?=$i?>" <?=IntIsSelected($i, $tahun)?> ><?=$i?></option>
<?php  } ?>
    </select> 
    &nbsp;<a href="#" onclick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" />&nbsp;refresh</a>
    <br />
    <table class="tab" id="table" cellpadding="4" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000" />
    <tr height="30" align="center" class="header">
    	<td width="30" align="center" background="../style/formbg2.gif">No</td>
      <td width="120" align="center" background="../style/formbg2.gif">Tanggal</td>
      <td width="260" align="center" background="../style/formbg2.gif">Informasi</td>
      <td width="60" align="center" background="../style/formbg2.gif">Sebelum</td>
      <td width="60" align="center" background="../style/formbg2.gif">Setelah</td>
      <td width="*" align="center" background="../style/formbg2.gif">Alasan</td>
      <td width="50" align="center" background="../style/formbg2.gif">Pengguna</td>
    </tr>
<?php  OpenDb();

	 $cnt = 0;
	 $sql = "SELECT jenisnilai, idnilai, nasli, nubah, DATE_FORMAT(tanggal, '%d-%m-%Y %H:%i') AS tanggal, alasan, pengguna, informasi 
	         FROM jbsakad.auditnilai WHERE MONTH(tanggal) = '$bulan' AND YEAR(tanggal) = '$tahun' ORDER BY tanggal DESC";
	 $res = QueryDb($sql);
	 echo mysqli_error($mysqlconnection);
	 while ($row = mysqli_fetch_array($res))
	 { 
	 	$cnt++;	?>
      <tr>
      	<td align="left" valign="top"><?=$cnt?></td>
         <td align="left" valign="top"><?=$row['tanggal']?></td>
         <td align="left" valign="top"><?=$row['informasi']?></td>
         <td align="center" valign="top"><?=$row['nasli']?></td>
         <td align="center" valign="top"><?=$row['nubah']?></td>
         <td align="left" valign="top"><?=$row['alasan']?></td>
         <td align="left" valign="top"><?php echo ($row['pengguna']=="landlord - landlord"?"Administrator":$row['pengguna']); ?></td>
      </tr>
<?php  }  
	 CloseDb(); ?>
    <!-- END TABLE CONTENT -->
    </table>
    
    </td>
</tr> 
<tr>
    <td>
    
</td></tr>
<!-- END TABLE CENTER -->    
</table>

</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>   
</body>
</html>
