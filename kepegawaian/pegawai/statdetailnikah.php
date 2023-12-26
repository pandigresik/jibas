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
require_once("../include/sessionchecker.php");
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

$sat = $_REQUEST['sat'];
$nikah  = $_REQUEST['nikah'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function DetailPegawai(nip) {
	var addr = "detailpegawai.php?nip="+nip;
	newWindow(addr, 'DetailPegawai','680','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function cetak() {
	var addr = "statdetail_cetak.php?stat=6&sat=<?=$sat?>&nikah=<?=$nikah?>";
	newWindow(addr, 'CetakDetail','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body style="background-color:#F5F5F5">
<table width="100%" border="0">
  <tr>
    <td><strong>Satuan Kerja : <?=$sat?></strong><br />
<strong>Status Pernikahan : <?=$nikah?></strong>
</td>
    <td width="100" align="right"><a href="#" onclick="cetak()"><img src="../images/ico/print.png" width="16" height="16" border="0" />&nbsp;Cetak</a>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">

<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%">
<tr height="35">
	<td class="header" align="center" width="7%">No</td>
	<td class="header" align="center" width="40%">NIP</td>
    <td class="header" align="center" width="40%">Nama</td>
	<td class="header" align="center" width="10%">&nbsp;</td>
</tr>
<?php
OpenDb();
$sql = "SELECT p.nip, CONCAT(p.gelarawal, ' ', p.nama, ' ', p.gelarakhir) AS fnama 
        FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
		WHERE p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND j.satker='$sat' AND p.nikah='$nikah' AND p.aktif=1 
		ORDER BY p.nama";	
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_row($result)) {
?>
<tr height="20">
	<td align="center" valign="top"><?=++$cnt?></td>
    <td align="center" valign="top"><?=$row[0]?></td>
    <td align="left" valign="top"><?=$row[1] ?></td>
    <td align="center" valign="top">
	    <a href="JavaScript:DetailPegawai('<?=$row[0]?>')" title="Lihat Detail Pegawai"><img src="../images/ico/lihat.png" border="0" /></a>
    </td>
</tr>
<?php
}
CloseDb();
?>
</table>
<script language='JavaScript'>
   Tables('table', 1, 0);
</script>
</td>
  </tr>
</table>

</body>
</html>