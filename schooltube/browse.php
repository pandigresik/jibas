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
require_once ("include/config.php");
require_once ("include/db_functions.php");
require_once ("include/session.php");
require_once ("browse.func.php");
require_once ("common.func.php");

OpenDb();
?>
<table border="0" cellspacing="0" cellpadding="2" width="1100">
<tr><td width="100%" align="left" valign="top" style="line-height: 18px">
    <span style="font-size: 24px">Browse Channel</span><br><br>

    <table border="0" cellpadding="2">
    <tr>
        <td align="left">Departement:</td>
        <td align="left">
<?php       $selDept = "";
            ShowCbDepartemen() ?>
        </td>
        <td rowspan="2">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" style="height: 40px; width: 100px" value="Lihat" class="BtnPrimary"
                   onclick="bw_showChannel()">
        </td>
    </tr>
    <tr>
        <td align="left">Lesson:</td>
        <td align="left">
            <span id="spCbPelajaran">
<?php       ShowCbPelajaran($selDept) ?>
            </span>
        </td>
    </tr>
    </table>
</td></tr>
</table>
<br><br>
<div id="divBrowse">

</div>
<?php
CloseDb();
?>