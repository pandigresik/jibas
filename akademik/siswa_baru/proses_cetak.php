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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/getheader.php');
$departemen = $_REQUEST['departemen'];
$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Proses Penerimaan Siswa Baru]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center>
  <font size="4"><strong>DATA PROSES PENERIMAAN SISWA BARU</strong></font><br />
 </center><br /><br />

<br />
	<strong>Departemen : <?=$departemen?></strong>
<br /><br />
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="18%" class="header" align="center">Proses</td>
        <td width="15%" class="header" align="center">Kode Awalan</td>
        <td width="8%" class="header" align="center">Jumlah</td>
        <td width="*" class="header" align="center">Keterangan</td>
        <td width="10%" class="header" align="center">Status</td>
    </tr>
<?php 	OpenDb();
	$sql = "SELECT p.replid, p.proses, p.keterangan, p.aktif, p.kodeawalan FROM prosespenerimaansiswa p WHERE p.departemen='$departemen' ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	$result = QueryDB($sql);
	//if ($page==0)
		$cnt = 0;
	//else
		//$cnt = (int)$page*(int)$varbaris;
		
	while ($row = mysqli_fetch_array($result)) { ?>
    <tr height="25">    	
    	<td align="center"><?=++$cnt ?></td>
        <td><?=$row['proses'] ?></td>        
        <td><?=$row['kodeawalan'] ?></td>
        <td align="center"><?=$row['jumlah'] ?></td>
        <td><?=$row['keterangan'] ?></td>
    	<td align="center">
			<?php if ($row['aktif'] == 1) 
					echo 'Aktif';
				else
					echo 'Tidak Aktif';
			?>        </td> 
   	</tr>
<?php } 
	CloseDb() ?>	
    <!-- END TABLE CONTENT -->
    </table>
	</td>
</tr>
</table>
</body>
<script language="javascript">
window.print();
</script>
</html>