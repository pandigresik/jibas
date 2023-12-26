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
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../library/departemen.php');
require_once('laporan.siswa.header.func.php');

OpenDb();

InitTanggal();

ReadRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<title>Untitled Document</title>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="laporan.siswa.header.js"></script>
</head>

<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form method="post" name="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
	<td rowspan="3" width="60%">
    <table border="0" width = "100%">
    <tr>
    	<td width="15%"><strong>Departemen </strong></td>
        <td colspan="4">
        <select name="departemen" id="departemen" style="width:115px" onchange="change_dep()" onKeyPress="return focusNext('tgl1',event)" onfocus="panggil('departemen')">
<?php      $dep = getDepartemen(getAccess());
        foreach($dep as $value) {
            if ($departemen == "")
                $departemen = $value; ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?>><?=$value ?></option>
        <?php } ?>    
        </select>&nbsp;
<?php 		$sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_row($res);
		$idtahunbuku = $row[0];
		//$idtahunbuku = FetchSingle($sql); ?>
        <input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku?>" />
        </td>
 	</tr>
    <tr>
    	<td><strong>Tanggal </strong></td>
       	<td width="10">
        	<div id="InfoTgl1">      
            <select name="tgl1" id = "tgl1" onchange="change_tgl1()" onfocus = "panggil('tgl1')" onKeyPress="return focusNext('bln1',event)">
            <option value="">[Tgl]</option>
            <?php for($i = 1; $i <= $n1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl1) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
         	</div>
     	</td>
        <td width="160">
            <select name="bln1" id="bln1" onchange="change_tgl1()" onfocus = "panggil('bln1')" onKeyPress="return focusNext('thn1',event)">
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln1) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn1" id="thn1" onchange="change_tgl1()" onfocus = "panggil('thn1')" onKeyPress="return focusNext('tgl2',event)">
            <?php for($i = $G_START_YEAR; $i <= $thn1+1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn1) ?> > <?=$i ?></option>
            <?php } ?>
            </select> s/d
       	</td>
        <td width="10">
         	<div id="InfoTgl2">
        	<select name="tgl2" id="tgl2" onchange="change_tgl2()" onfocus = "panggil('tgl2')" onKeyPress="return focusNext('bln2',event)" >
            <option value="">[Tgl]</option>
			<?php for($i = 1; $i <= $n2; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
            </div>
        </td>
        <td>
            <select name="bln2" id="bln2" onchange="change_tgl2()" onfocus = "panggil('bln2')" onKeyPress="return focusNext('thn2',event)">
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln2) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn2" id="thn2" onchange="change_tgl2()" onfocus = "panggil('thn2')" onKeyPress="return focusNext('tabel',event)">
            <?php for($i = $G_START_YEAR; $i <= $thn2+2; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
    	</td>
   	</tr>
    </table>
    </td>
	<td width="*" rowspan="2" valign="middle">
		<a href="#" onclick="show_pembayaran()"><img src="../images/view.png" border="0" height="48"  width="48" id="tabel" onmouseover="showhint('Klik untuk menampilkan data laporan pembayaran per siswa!', this, event, '200px')"/></a>
     </td>
	<td width="40%" colspan="3" align="right" valign="top">
	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Tabungan Per Siswa</font><br />
    <a href="tabungan.php" target="_parent">
      <font size="1" color="#000000"><b>Tabungan Siswa</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Laporan Tabungan Per Siswa</b></font>
	</td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
</form>
</body>
</html>
<?php
CloseDb();
?>