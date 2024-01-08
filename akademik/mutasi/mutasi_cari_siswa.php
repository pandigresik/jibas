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
//include('../cek.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');


	$departemen = $_POST["departemen"];

OpenDb();
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<link rel="stylesheet" type="text/css" href="../script/ajaxtabs.css" />
<script type="text/javascript" src="../script/ajaxtabs.js"></script>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" onLoad="document.getElementById('nis').focus();">
<table width="100%" border="0">
  <tr>
    <td width="9%">Departemen</td>
    <td width="11%"><select name="departemen" id="departemen" onChange="departemen()">
            <?php $dep = getDepartemen(SI_USER_ACCESS());    
				foreach($dep as $value) {
					if ($departemen == "")
						$departemen = $value; ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
            <?=$value ?>
            </option>
            <?php } ?>
          </select></td>
    <td width="80%" rowspan="3"><img src="../images/view.png" width="48" height="48" onClick="cari()" style="cursor:pointer"></td>
  </tr>
  <tr>
    <td>NIS</td>
    <td><input type="text" name="nis" id="nis" value="<?=$_REQUEST['nis'] ?>" size="17" /></td>
    </tr>
  <tr>
    <td>Nama</td>
    <td><input type="text" name="nama" id="nama" value="<?=$_REQUEST['nama'] ?>" size="17" /></td>
    </tr>
	<tr>
    <td colspan="3">
	<div id="tabel_cari">&nbsp;</div>
	</td>
    </tr>
</table>



</body>
</html>