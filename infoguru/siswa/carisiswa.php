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
require_once('../include/config.php');
require_once('../include/getheader.php');
require_once('../include/common.php');
require_once('../include/db_functions.php');
require_once('siswa.class.php');

OpenDb();
$SP = new CSiswa();
?>
<link href="../style/style.css" rel="stylesheet" type="text/css" />

<form name="frmCari">
<table width="100%" border="0" cellspacing="3" cellpadding="1">
  <tr>
    <td width="5%" class="tab2">NIS</td>
    <td width="95%">
    <div id="nisInfo">
    <input name="nis" type="text" class="inputtxt" id="nis"/>	
    </div>    </td>
  </tr>
  <tr>
    <td class="tab2">NISN</td>
    <td>
    <div id="namaInfo">
    <input name="nisn" type="text" class="inputtxt" id="nisn"/>
    </div>    </td>
  </tr>
  <tr>
    <td class="tab2">Nama</td>
    <td>
    <div id="namaInfo">
    <input name="nama" type="text" class="inputtxt" id="nama"/>
    </div>    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="tab2"><input class="cmbfrm2" type="button" name="cari" id="cari" value="Cari Siswa" onclick="CariSiswa()" /></td>
  </tr>
  
  <tr>
    <td colspan="2">
    <div id="sisInfoCari">
    </div></td>
  </tr>
</table>
</form>