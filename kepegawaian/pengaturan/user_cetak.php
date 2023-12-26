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
require_once('../include/sessionchecker.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/getheader.php');

$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];
$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian [Cetak Pengguna]</title>
</head>
<body>

<table border="0"  cellpadding="5" width="100%">
<tr><td align="left" valign="top">

<?=getHeader('yayasan')?>

<center><font size="4"><strong>DAFTAR PENGGUNA<br />SISTEM MANAJEMEN AKADEMIK</strong></font><br /> </center><br />
<br />
	
	 <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="0" width="100%" align="center" bordercolor="#000000">
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="10%" class="header" align="center">Login</td>
        <td width="20%" class="header" align="center">Nama</td>
        <td width="15%" class="header" align="center">Tingkat</td>
        <td width="10%" class="header" align="center">Status</td>
        <td width="*" class="header" align="center">Keterangan</td>
        <td width="16%" class="header" align="center">Login Terakhir</td>
    </tr>
<?php 	OpenDb();
	$sql = "SELECT h.login, h.replid,  h.tingkat, h.departemen, h.keterangan, p.nama, p.aktif,
				   DATE_FORMAT(h.lastlogin,'%Y-%m-%d') AS tanggal, TIME(h.lastlogin) as jam
			  FROM jbsuser.hakakses h, jbssdm.pegawai p, jbsuser.login l
			 WHERE h.modul='SIMPEG' AND h.login = l.login AND l.login = p.nip
			 ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	
	$result = QueryDB($sql);
	
	//if ($page==0)
		$cnt = 0;
	//else
		//$cnt = (int)$page*(int)$varbaris;
	
	while ($row = mysqli_fetch_array($result)) { ?>
    <tr height="25">
    	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['login'] ?></td>
        <td><?=$row['nama']; ?></td>
        <td align="center">
			<?php switch ($row['tingkat']){
					case 0: echo "Landlord";
						break;
					case 1: echo "Manajer Akademik";
						break;
					case 2: echo "Staf Akademik";
						break;
				}
        	?>
        </td>
        
        <td align="center"><?php if ($row['aktif'] == 1) echo 'Aktif'; else echo 'Tidak Aktif'; ?></td>
        <td><?=$row['keterangan'] ?></td>
        <td align="center"><?=format_tgl($row['tanggal'])?> <?=$row['jam']?></td>
    </tr>
<?php } CloseDb() ?>	
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