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
OpenDb();
$idbarang = $_REQUEST['idbarang'];
$sql = "SELECT * FROM jbsfina.barang WHERE replid='$idbarang'";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Detail Barang</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<fieldset style="border:#336699 1px solid; background-color:#eaf4ff; height:100%" >
<legend style="background-color:#336699; color:#FFFFFF; font-size:10px; font-weight:bold; padding:5px">&nbsp;<?=$row['kode']?>&nbsp;</legend>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="29%" align="right"><strong>Kode Barang</strong></td>
    <td width="2" align="right"><strong>:</strong></td>
    <td width="33%"><?=stripslashes((string) $row['kode'])?></td>
    <td width="38%" rowspan="4" align="center" valign="middle"><img src="gambar.php?table=jbsfina.barang&replid=<?=$row['replid']?>" align="left" style="padding:2px" /></td>
  </tr>
  <tr>
    <td align="right"><strong>Nama Barang</strong></td>
    <td width="2" align="right"><strong>:</strong></td>
    <td><?=stripslashes((string) $row['nama'])?></td>
  </tr>
  <tr>
    <td align="right"><strong>Jumlah</strong></td>
    <td width="2" align="right"><strong>:</strong></td>
    <td><?=$row['jumlah']?>&nbsp;<?=stripslashes((string) $row['satuan'])?></td>
  </tr>
  <tr>
    <td align="right"><strong>Tanggal Perolehan</strong></td>
    <td width="2" align="right"><strong>:</strong></td>
    <td><?=substr((string) $row['tglperolehan'],8,2)."-".substr((string) $row['tglperolehan'],5,2)."-".substr((string) $row['tglperolehan'],0,4)?></td>
  </tr>  
  <tr>
    <td align="right"><strong>Kondisi</strong></td>
    <td width="2" align="right"><strong>:</strong></td>
    <td colspan="2"><?=stripslashes((string) $row['kondisi'])?></td>
  </tr>
  <tr>
    <td align="right"><strong>Keterangan</strong></td>
    <td width="2" align="right"><strong>:</strong></td>
    <td colspan="2"><?=stripslashes((string) $row['keterangan'])?></td>
  </tr>
  <tr>
    <td colspan="4" align="center"><input type="button" value="Tutup" onclick="window.close()" class="but" /></td>
  </tr>
</table>

</fieldset>
</body>
<?php
CloseDb();
?>
</html>