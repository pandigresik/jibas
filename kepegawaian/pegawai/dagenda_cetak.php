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

$bln1 = date('n');
if (isset($_REQUEST['bln1']))
	$bln1 = $_REQUEST['bln1'];

$thn1 = date('Y');
if (isset($_REQUEST['thn1']))
	$thn1 = $_REQUEST['thn1'];	

$bln2 = date('n');
if (isset($_REQUEST['bln2']))
	$bln2 = $_REQUEST['bln2'];

$thn2 = date('Y');
if (isset($_REQUEST['thn2']))
	$thn2 = $_REQUEST['thn2'];		

$tgl1 = "$thn1-$bln1-1";
$tgl2 = "$thn2-$bln2-31";




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
    <font size="4"><strong>DAFTAR AGENDA KEPEGAWAIAN</strong></font><br />
   </center><br /><br /><br />
   
	<p align="left">
    Periode: <?=NamaBulan($bln1)?> <?=$thn1?> - <?=NamaBulan($bln2)?> <?=$thn2?> 
    </p>
   <table class="tab" id="table" cellpadding="0" cellspacing="0" width="100%">
    <tr height="30">
        <td width="3%" class="header" align="center" valign="top">No</td>
        <td width="12%" class="header" align="center" valign="top">Tanggal</td>
        <td width="20%" class="header" align="center" valign="top">Agenda</td>
        <td width="20%" class="header" align="center" valign="top">NIP</td>
        <td width="25%" class="header" align="center" valign="top">Nama</td>
        <td width="*" class="header" align="center" valign="top">Keterangan</td>
    </tr>
    <?php
    $sql = "SELECT j.nip, ja.nama AS jenisagenda, p.nama, DATE_FORMAT(j.tanggal, '%d-%m-%Y') AS tanggal, j.jenis, j.keterangan 
            FROM jadwal j, pegawai p, jenisagenda ja
            WHERE j.nip = p.nip AND j.aktif = 1 AND j.exec = 0 AND j.jenis = ja.agenda AND j.tanggal BETWEEN '$tgl1' AND '$tgl2'
            ORDER BY j.tanggal ASC, p.nama";
    OpenDb();
    $result = QueryDb($sql);
    $cnt = 0;
    while ($row = mysqli_fetch_array($result)) {
    ?>
    <tr height="25">
        <td align="center" valign="top"><?=++$cnt?></td>
        <td align="center" valign="top"><?=$row['tanggal']?></td>
        <td align="center" valign="top"><?=$row['jenisagenda']?></td>
        <td align="center" valign="top"><?=$row['nip']?></td>
        <td align="left" valign="top"><?=$row['nama']?></td>
        <td align="left" valign="top"><?=$row['keterangan']?></td>
    </tr>
    <?php
    }
    CloseDb();
    ?>
    </table>

</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>