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

$varkolom=4;

$idkelompok = "";
if (isset($_REQUEST['idkelompok']))
	$idkelompok = $_REQUEST['idkelompok'];
	
$departemen = "yayasan";

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Jenis Penerimaan]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center><font size="4"><strong>INVENTORI</strong></font><br /> </center><br /><br />
<table border="0">
<tr>
	<td width="90"><strong>Kelompok</strong></td>
    <td><strong>:
<?php $sql = "SELECT kelompok FROM jbsfina.kelompokbarang WHERE replid='$idkelompok'";
    $result = QueryDb($sql);
    $row = @mysqli_fetch_row($result);
    $namakelompok = $row[0];
	echo  $namakelompok; ?>
    </strong>
    </td>
</tr>
</table>
<br />

<?php
$sql = "SELECT * FROM jbsfina.barang WHERE idkelompok='$idkelompok'";
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
$total = ceil(mysqli_num_rows($result)/(int)$varkolom);
if ($num > 0)
{   ?>
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
<?php
    $cnt=1;
    while ($row = @mysqli_fetch_array($result))
    {
    	if ($cnt==1 || $cnt%(int)$varkolom==1)
            echo "<tr>";
	
        $jumlah = (int)$row['jumlah'];
        $satuan = $row['satuan'];
        $harga = (int)$row['info1'];
        $total = $jumlah * $harga;	
    ?>
    <td valign="top" align="center">
        <div id="div<?=$row['replid']?>" style="padding:5px; width:200px; margin:5px; border:2px solid #eaf4ff; cursor:default">
        <div align="left">
            <span style="font-family:Arial; font-size:14px; font-weight:bold; color:#990000"><?=$row['kode']?></span><br />
            <span style="font-family:Arial; font-size:12px; font-weight:bold; color:#006600; cursor:pointer"><?=$row['nama']?></span><br />
        </div>
        <img src="gambar.php?table=jbsfina.barang&replid=<?=$row['replid']?>"  style="padding:2px" />
        <div align="left">
            Jumlah: <?=$jumlah?>&nbsp;<?=$satuan?>&nbsp;@<?=FormatRupiah($harga)?><br />
            Total: <?=FormatRupiah($total)?><br>
            Tanggal: <?=substr((string) $row['tglperolehan'],8,2)."-".substr((string) $row['tglperolehan'],5,2)."-".substr((string) $row['tglperolehan'],0,4)?><br />
        </div>
        </div>
    </td>
<?php
    if ($num < $varkolom)
    {
    	if ($num==1)
    		echo  "<td width='157'>&nbsp;</td><td width='157'>&nbsp;</td><td width='157'>&nbsp;</td><td width='157'>&nbsp;</td>";
    	elseif ($num==2)
    		echo  "<td width='157'>&nbsp;</td><td width='157'>&nbsp;</td><td width='157'>&nbsp;</td>";	
    	elseif ($num==3)
    		echo  "<td width='157'>&nbsp;</td><td width='157'>&nbsp;</td>";
    	elseif ($num==4)
    		echo  "<td width='157'>&nbsp;</td>";
    }
    
    if ($cnt%(int)$varkolom==0)
        echo "</tr>";
    
    $cnt++;
}
?>
</table>
<?php
}
else
{
    ?>
    <div align="center"><span style="font-family:verdana; font-size:12px; font-style:italic; color:#666666">Tidak ada Data Barang Untuk Kelompok <?=stripslashes((string) $namakelompok)?></span></div>
<?php
}
?>

</td></tr>
</table>
<?php
CloseDb();
?>
</body>
</html>
<script language="javascript">window.print();</script>