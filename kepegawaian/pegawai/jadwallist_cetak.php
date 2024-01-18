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

$namabulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];

$tgl = $_REQUEST['tgl'];
$bln = $_REQUEST['bln'];
$thn = $_REQUEST['thn'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<style>
.tanggal {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 32px;
	font-weight: bold;
}

.bulan {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 14px;
	font-weight: bold;
}
</style>

<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<style type="text/css">
<!--
.style1 {
	font-size: 18px;
	font-weight: bold;
}
.style2 {font-weight: bold}
-->
</style>
</head>
<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">
  <?php include("../include/headercetak.php") ?>
  <div align="center">
  <br />
  <span class="style2"><span class="style1">DAFTAR AGENDA</span><br />
  <br />
  </span></div>
  <strong>Periode : <?=$tgl?> <?=$namabulan[$bln-1]?> <?=$thn?></strong><br />
  <br />

<table width="100%" cellpadding="0" cellspacing="0" border="1" class="tab" id="table">
<tr height="30">
	<td width="5%" class="header" align="center">No</td>
    <td width="35%" class="header" align="center">Pegawai</td>
    <td width="35%" class="header" align="center">Keterangan</td>
</tr>
<?php
$tanggal = "$thn-$bln-$tgl";
OpenDb();
$sql = "SELECT DISTINCT jenis FROM jadwal WHERE tanggal = '".$tanggal."'";
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result)) { 
	$jenis = $row['jenis'];	
	if ($jenis == "golongan")
		$njenis = "Kenaikan Golongan";
	elseif ($jenis == "jabatan")
		$njenis = "Kenaikan Jabatan";
	elseif ($jenis == "gaji")
		$njenis = "Kenaikan Gaji";
	elseif ($jenis == "pensiun")
		$njenis = "Pensiun";
	?>
	<tr height="18">
		<td colspan="5" align="center" bgcolor="#014187">
	    <font color="yellow"><strong><?=$njenis?></strong></font>
	    </td>
	</tr>	
<?php
	$sql = "SELECT j.replid, j.nip, p.nama, j.keterangan FROM jadwal j, pegawai p WHERE j.nip = p.nip AND j.jenis='$jenis' AND j.tanggal='$tanggal'";
	$rs2 = QueryDb($sql);
	while ($row2 = mysqli_fetch_array($rs2)) { ?>
        <tr height="25">
            <td align="center" valign="top"><?=++$cnt?></td>
            <td align="left" valign="top"><?=$row2['nip'] . "<br>" . $row2['nama']?></td>
            <td align="left" valign="top"><?=$row2['keterangan']?></td>
        </tr>
<?php 
	}	
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