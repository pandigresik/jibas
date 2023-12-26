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
require_once('../inc/errorhandler.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/rupiah.php');
require_once('infosiswa.keuangan.class.php');

OpenDb();

$K = new CK();
?>
<form name="panelkeu">
<input type="hidden" name="nis_awal" id="nis_awal" value="<?= $_SESSION['infosiswa.nis'] ?> " />
<table border="0" cellpadding="2">
<tr>
    <td align='left'>
<?php  $K->ShowDepartemenComboBox(); ?>
    &nbsp;&nbsp;
<?php  $K->ShowTahunBukuComboBox(); ?>
    </td>
    <td align='left'></td>
</tr>
<tr>
    <td align='left' valign='top'>
<?php  $K->ShowFinanceReport(); ?>        
    </td>
</tr>
</table>
</form>
<?php
CloseDb();
?>