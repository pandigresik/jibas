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
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/rupiah.php');
require_once('../inc/db_functions.php');
require_once('../lib/GetHeaderCetak.php');

$perpustakaan	= $_REQUEST['perpustakaan'];
$from			= $_REQUEST['from'];
$to				= $_REQUEST['to'];
$limit			= $_REQUEST['limit'];

OpenDb();
if ($perpustakaan!='-1')
{
	$sql 	= "SELECT nama FROM perpustakaan WHERE replid='$perpustakaan'";
	$result = QueryDb($sql);
	$row 	= @mysqli_fetch_row($result);
	$nama	= $row[0];
}
else
{
	$nama = "<i>Semua</i>";
}

$from	= explode('-',(string) $from);
$to		= explode('-',(string) $to);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../sty/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cetak Statistik Pustaka Yang Paling Sering Dipinjam</title>
</head>

<body>
<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr><td align="left" valign="top">

<?php GetHeader($perpustakaan) ?>

<center>
<strong><font size="4">STATISTIK PUSTAKA YANG PALING SERING DIPINJAM</font></strong><br /> 
</center>
<br /><br /><br />

<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
    <td>
    	<table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td width="14%"><strong>Perpustakaan</strong></td>
            <td width="86%">&nbsp;<?=$nama?></td>
        </tr>
        <tr>
            <td><strong>Periode</strong></td>
            <td>&nbsp;<?=NamaBulan($from[1])?> <?=$from[0]?> s.d. <?=NamaBulan($to[1])?> <?=$to[0]?></td>
        </tr>
        <tr>
            <td><strong>Jumlah&nbsp;data yang&nbsp;ditampilkan</strong></td>
            <td valign="top">&nbsp;<?=$limit?></td>
        </tr>
        </table>
    </td>
</tr>
<tr>
    <td align="center" valign="top">
        <img src="<?="statimage.php?type=bar&key={$_REQUEST['from']},{$_REQUEST['to']}&Limit=$limit&krit=2&perpustakaan=$perpustakaan" ?>" />
    </td>
</tr>
<tr>
    <td align="center" valign="top">
    	<img src="<?="statimage.php?type=pie&key={$_REQUEST['from']},{$_REQUEST['to']}&Limit=$limit&krit=2&perpustakaan=$perpustakaan" ?>" />
    </td>
</tr>
<tr>
    <td>
    	<table width="95%" border="1" cellspacing="0" cellpadding="0" class="tab">
        <tr>
            <td height="25" align="center" class="header">No</td>
            <td height="25" align="center" class="header">Judul</td>
            <td height="25" align="center" class="header">Jumlah</td>
        </tr>
<?php 	$filter="";
		if ($perpustakaan!='-1')
			$filter=" AND d.perpustakaan=".$perpustakaan;
			
		$sql = "SELECT count(*) as num, judul, pu.replid
				  FROM pinjam p, daftarpustaka d, pustaka pu
			     WHERE p.tglpinjam BETWEEN '".$_REQUEST['from']."' AND '".$_REQUEST['to']."'
				   AND d.kodepustaka=p.kodepustaka
				   AND pu.replid=d.pustaka $filter
			     GROUP BY judul
			     ORDER BY num DESC
			     LIMIT $limit";		
		$result = QueryDb($sql);
		
		if (@mysqli_num_rows($result)>0)
		{
			$cnt=1;
			
			while ($row = @mysqli_fetch_row($result))
			{ 
				$judul = $row[1];  ?>
				<tr>
					<td height="20" align="center"><?=$cnt?></td>
					<td height="20"><div style="padding-left:5px; padding-right:5px;"><?=$judul?></div></td>
					<td height="20" align="center"><?=$row[0]?></td>
				</tr>
<?php 				$cnt++;
			} // while
		}
		else
		{ ?>
          <tr>
            <td height="20" align="center" colspan="3" class="nodata">Tidak ada data</td>
          </tr>	
<?php 		} // if ?>
		</table>
    </td>
</tr>
</table>

</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>
<?php
CloseDb();
?>