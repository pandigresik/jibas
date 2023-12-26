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
require_once('../include/errorhandler.php');
require_once('mutasibank.func.php');

$departemen = $_REQUEST["departemen"];
$bank = $_REQUEST["bank"];
$bankNo = $_REQUEST["bankno"];

OpenDb();
?>
<table border="0" cellpadding="5" cellspacing="0">
<tr>
    <td width="400">
        <span style="font-size: 20px"><?=$bank?></span><br>
        <span style="font-size: 16px"><?=$bankNo?></span><br><br>
    </td>
    <td>
        <input type="button" class="but" style="width: 135px; height: 40px;" value="Riwayat Mutasi" onclick="showRiwayatMutasi()">&nbsp;&nbsp;
        <input type="button" class="but" style="width: 135px; height: 40px; color: darkred;" value="Mutasi Ambil (-)" onclick="showMutasiAmbil()">
        <input type="button" class="but" style="width: 135px; height: 40px; color: darkblue;" value="Mutasi Simpan (+)" onclick="showMutasiSimpan()">&nbsp;&nbsp;
        <input type="hidden" id="bank" value="<?=$bank?>">
        <input type="hidden" id="bankno" value="<?=$bankNo?>">
    </td>
</tr>
</table>
<br><br>
<div id="dvSubContent">

</div>

<?php
CloseDb();
?>
