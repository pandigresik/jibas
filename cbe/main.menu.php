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
<br>

<table border="0" cellspacing="0" cellpadding="10" width="100%">
<tr>
    <td align="center">
        <?= getUserPict(120); ?>
        <br>
        <div style="width: 180px; overflow: hidden; ">
            <span style="font-family: 'Segoe UI', msp, Arial; white-space: nowrap; font-size: 20px; color: #fff">
                <?= $_SESSION["UserName"] ?>
            </span>
        </div>
        <div style="width: 180px; overflow: hidden; ">
            <span style="font-family: 'Segoe UI', msp, Arial; white-space: nowrap; font-size: 14px; color: #fff">
                <?= $_SESSION["UserId"] ?>
            </span>
        </div>
    </td>
</tr>
<tr>
    <td align="left">

        <?php
        if ($_SESSION["UserType"] == "admin")
            include "main.menu.admin.php";
        else
            include "main.menu.user.php";
        ?>

    </td>
</tr>
</table>