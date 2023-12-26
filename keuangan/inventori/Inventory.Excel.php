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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/getheader.php'); 

$idkelompok = $_REQUEST['idkelompok'];

OpenDb();

$sql = "SELECT kelompok FROM jbsfina.kelompokbarang WHERE replid='$idkelompok'";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$namakelompok = $row[0];

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Inventory'.$idkelompok.'.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Inventory]</title>
</head>

<body>
<center><font size="4" face="Verdana"><strong>INVENTORI</strong></font><br /> 
</center>
<br /><br />
<table border="0">
<tr>
	<td width="90"><font size="2" face="Arial"><strong>Kelompok </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=$namakelompok ?>
    </strong></font></td>
</tr>
</table>
<br />

<table border="1" style="border-collapse:collapse" cellpadding="5" width="100%" class="tab" bordercolor="#000000">
<tr height="30">
	<td width="25" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No</font></strong></td>
    <td width="120" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Kode</font></strong></td>
    <td width="250" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Barang</font></strong></td>
    <td width="250" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Tgl Perolehan</font></strong></td>
    <td width="250" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Kondisi</font></strong></td>
    <td width="250" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Jumlah</font></strong></td>
    <td width="250" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Harga</font></strong></td>
    <td width="250" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Total</font></strong></td>
</tr>
<?php
$sql = "SELECT * FROM jbsfina.barang WHERE idkelompok='$idkelompok'";
$result = QueryDb($sql);
$no = 0;
while($row = mysqli_fetch_array($result))
{
    $no += 1;
    
    $jumlah = (int)$row['jumlah'];
    $harga = (int)$row['info1'];
    $total = $jumlah * $harga;
?>
<tr>
    <td><?=$no?></td>
    <td><?=$row['kode']?></td>
    <td><?=$row['nama']?></td>
    <td><?=$row['tglperolehan']?></td>
    <td><?=$row['kondisi']?></td>
    <td><?=$jumlah . " " . $row['satuan']?></td>
    <td><?=$harga?></td>
    <td><?=$total?></td>
</tr>
<?php
}
CloseDb();
?>
</table>

</body>
</html>
