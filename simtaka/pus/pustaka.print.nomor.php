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
include_once '../../vendor/autoload.php';
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/db_functions.php');

define('IN_CB', true);

OpenDb();
$replid=$_REQUEST['replid'];

$perpustakaan=$_REQUEST['perpustakaan'];
if ($perpustakaan!="")
	$filter = " AND perpustakaan='$perpustakaan'";	
if ($perpustakaan=="-1")
	$filter = "";	
$sql = "SELECT judul FROM pustaka WHERE replid='$replid'";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
$judul = $row['judul'];

if ($filter!=""){
	$sql = "SELECT nama FROM perpustakaan WHERE replid='$perpustakaan'";
	$result = QueryDb($sql);
	$row = @mysqli_fetch_array($result);
	$nama = $row['nama'];
} else {
	$nama = "<i>(Semua)</i>";
}
$sql = "SELECT kodepustaka, info1 FROM daftarpustaka WHERE pustaka='$replid' $filter";
//echo $sql;
$result = QueryDb($sql);
$jum = @mysqli_num_rows($result);
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../sty/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cetak Label Pustaka</title>
</head>

<body leftmargin="0" topmargin="0">
<table border="0" cellspacing="5" cellpadding="5" width="780" align="left">
    <tr>
        <td align="left" valign="top">
        	<div align="center">
				
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
                <td width="4%" align="right" valign="top"><strong class="news_title2">Pepustakaan&nbsp;:</strong></td>
                <td width="96%" class="nav_title" align="left"><?=$nama?></td>
            </tr>
            <tr>
                <td width="4%" align="right" valign="top"><strong class="news_title2">Judul&nbsp;:</strong></td>
                <td width="96%" class="nav_title" align="left"><?=$judul?></td>
            </tr>
            </table>

             
            </div><br />
<?php 			$i = 1;
			$cellcnt = 1;
			while($row = @mysqli_fetch_row($result))
			{
				if ($cellcnt == 1 || $cellcnt % 9 == 1)
					echo "<table border='0' width='99%' cellspacing='0' cellpadding='5'>";
					
                if ($i == 1 || $i % 3 == 1)
                    echo "<tr>";
						
				$kode = explode('/',(string) $row[0]);
				$barcode = $row[1];	?>
                
				<td width="33%" align="center" >
						
					<table border='1' cellpadding='2' style='border-width: 1px; border-style: dashed; border-collapse: collapse'>
					<tr style='border-width: 1px; border-style: dashed; border-collapse: collapse'>
						<td align='center'>
							<font style='font-size: 12px;'><?= $row[0] ?></font><br><br>
							<table width="200" border="1" cellspacing="0" cellpadding="0" class="tab2">
								<tr height="30"><td align="center" style='font-size: 32px'><?=$kode[0]?></td></tr>
								<tr height="30"><td align="center" style='font-size: 32px'><?=$kode[1]?></td></tr>
								<tr height="30"><td align="center" style='font-size: 32px'><?=$kode[2]?></td></tr>
								<tr height="30"><td align="center" style='font-size: 32px'><?=$kode[3]?></td></tr>
							</table>
							<br><br>
						</td>
					</tr>	
					<tr style='border-width: 1px; border-style: dashed; border-collapse: collapse'>
						<td align='center'>barcode
							<font style='font-size: 12px;'><?= $row[0] ?></font><br><br>							
							<img width='160' src="data:image/png;base64,<?php echo base64_encode($generator->getBarcode($barcode, $generator::TYPE_CODE_39)) ?>">
							<br><br>								
						</td>
					</tr>		
					</table>
                    
                </td>

<?php              if ($i % 3 == 0)
                    echo "</tr>";
				
				if ($cellcnt % 9 == 0)
                    echo "</table><br><br><br><br><br><br><br>";
					
				$i++;
				
				$cellcnt += 1;
				
			}	?>
            
        </td>
    </tr>
</table>
</body>
<script language="javascript">
window.print();
</script>
</html>