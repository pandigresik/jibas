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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");
require_once("../include/sessionchecker.php");

OpenDb();

$nip = $_REQUEST['nip'];

$sql = "SELECT CONCAT(gelarawal, ' ' , nama, ' ', gelarakhir) AS nama FROM jbssdm.pegawai WHERE nip='$nip'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$nama = $row[0];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style<?=GetThemeDir2()?>.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top"><?php include("../include/headercetak.php") ?>
  <center>
    <font size="4"><strong>DATA DIKLAT</strong></font><br />
   </center><br /><br />
<br />

<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Riwayat Diklat <?=$nama?> - <?=$nip?></font><br />
    </td>
</tr>
<tr><td>

<table border="1" id="table" style="border-collapse:collapse" cellpadding="0" cellspacing="0" width="100%" class="tab">
<tr height="30">
	<td width="5%" align="center" class="header">No</td>
    <td width="25%" align="center" class="header">Diklat</td>
    <td width="5%" align="center" class="header">Tahun</td>
    <td width="7%" align="center" class="header">&nbsp;</td>
    <td width="20%" align="center" class="header">SK</td>
    <td width="28%" align="center" class="header">Keterangan</td>
</tr>
<?php
$sql = "SELECT p.replid, p.iddiklat, d.diklat, p.tahun, p.sk, p.keterangan, p.terakhir FROM jbssdm.pegdiklat p, jbssdm.diklat d WHERE p.nip = '$nip' AND p.iddiklat = d.replid ORDER BY p.tahun DESC, p.replid DESC";
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result)) {
?>
<tr height="25">
	<td align="center"><?=++$cnt?></td>
    <td align="left"><?=$row['diklat']?></td>
    <td align="center"><?=$row['tahun']?></td>
    <td align="center">
	<?php if ($row['terakhir'] == 1) { ?>
    	<img src="../images/ico/aktif.png" border="0" title="diklat terakhir" />
    <?php } else { ?>
    	<img src="../images/ico/nonaktif.png" border="0" />
    <?php } ?>
    </td>
    <td align="left"><?=$row['sk']?></td>
    <td align="left"><?=$row['keterangan']?></td>
</tr>
<?php
}
?>
</table>
</td></tr>
</table>

</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>
