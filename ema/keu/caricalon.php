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
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');
OpenDb();
?>
<link href="../style/style.css" rel="stylesheet" type="text/css" />

<form name="frmCari">
<table width="100%" border="0" cellspacing="3" cellpadding="1">
  <tr>
    <td width="5%" class="tab2">No.&nbsp;Pendaftaran</td>
    <td width="95%">
    <div id="nisInfo">
    <input name="nopendaftaran" type="text" class="inputtxt" id="nopendaftaran"/>	
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
    <td colspan="2" align="center" class="tab2"><input class="cmbfrm2" type="button" name="cari" id="cari" value="Cari Calon" onclick="CariCalon()" /></td>
  </tr>
  
  <tr>
    <td colspan="2">
    <div id="calInfoCari">
    </div></td>
  </tr>
</table>
</form>