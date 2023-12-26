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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");
require_once("daftarkeluarga.class.php");

OpenDb();
$DK = new DaftarKeluarga();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="daftarkeluarga.js"></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#ffffff">
<br />
<input type="hidden" name="nip" id="nip" value="<?=$DK->nip?>">
<p align="center">
<font class="subtitle"><?=$DK->nama?> - <?=$DK->nip?></font><br />
<a href="JavaScript:Refresh()"><img src="../images/ico/refresh.png" border="0" />&nbsp;refresh</a>&nbsp;
<a href="JavaScript:Cetak()"><img src="../images/ico/print.png" border="0" />&nbsp;cetak</a>&nbsp;
<br />
</p>

<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Daftar Keluarga</font><br />
    </td>
</tr>
<tr><td>

<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td align="right">
<a href="JavaScript:TambahS()"><img src="../images/ico/tambah.png" border="0" />&nbsp;tambah</a>
</td></tr>
</table>
<table border="1" id="table" style="border-collapse:collapse" cellpadding="0" cellspacing="0" width="100%" class="tab">
<tr height="30">
	<td width="5%" align="center" class="header">No</td>
    <td width="20%" align="center" class="header">Nama</td>
    <td width="12%" align="center" class="header">Hubungan</td>
    <td width="12%" align="center" class="header">Tgl Lahir</td>
    <td width="12%" align="center" class="header">HP</td>
    <td width="15%" align="center" class="header">Email</td>
    <td width="*" align="center" class="header">Keterangan</td>
    <td width="8%" align="center" class="header">&nbsp;</td>
</tr>
<?php
$sql = "SELECT ps.replid, ps.nama, ps.alm, ps.hubungan, ps.tgllahir, ps.hp, ps.email, ps.keterangan
          FROM jbssdm.pegkeluarga ps 
         WHERE ps.nip = '$DK->nip'
         ORDER BY ps.nama";
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result))
{
?>
<tr height="25">
	<td align="center"><?=++$cnt?></td>
    <td align="left">
	<?=$row['nama']?>
	<?php if ((int)$row['alm'] == 1) echo " (alm)"; ?>
	</td>
    <td align="left"><?=$row['hubungan']?></td>
    <td align="left"><?=$row['tgllahir']?></td>
    <td align="left"><?=$row['hp']?></td>
	<td align="left"><?=$row['email']?></td>
    <td align="left"><?=$row['keterangan']?></td>
    <td align="center">
	    <a title="edit" href="JavaScript:Ubah(<?=$row['replid']?>)"><img src="../images/ico/ubah.png" border="0" /></a>&nbsp;
   	    <a title="hapus" href="JavaScript:Hapus(<?=$row['replid']?>)"><img src="../images/ico/hapus.png" border="0" /></a>
    </td>
</tr>
<?php
}
?>
</table>
</td></tr>
</table>
<?php
CloseDb();
?>    

<script language='JavaScript'>
	Tables('table', 1, 0);
</script>


</body>
</html>