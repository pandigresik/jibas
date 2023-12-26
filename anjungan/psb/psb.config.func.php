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
function IsPasswordValid($password)
{
    $sql = "SELECT COUNT(replid)
              FROM jbsuser.landlord
             WHERE password = md5('$password')";
    $ndata = (int)FetchSingle($sql);
    
    return $ndata != 0;
}

function ShowConfig()
{
    require_once("psb.config.php");
    
    $checked = (PSB_ENABLE_INPUT == 1) ? "checked" : "";
    ?>
    <br><br><br><br><br><br>
    <center>
    <fieldset style="border-color: #557d1d; border-width: 1px; width: 420px;" >
    <legend>
        <font style='color: #557d1d; font-size: 12px; font-weight: bold;'>KONFIGURASI HALAMAN PSB</font>
    </legend>
    <br>
    <table border='0' cellpadding='4' cellspacing='2' align='center'>
    <tr>
        <td align='left' style='line-height: 20px;'>
            <input type='checkbox' id='psb_CheckEnableInput' name='psb_CheckEnableInput' <?=$checked?>>
            Aktifkan pendataan calon siswa baru.<br>
            <font style='color: #555'>
            Dengan mengaktifkan fitur ini, calon siswa/orangtua dapat mengisi sendiri form pendaftaran calon siswa melalui <strong>JIBAS Anjungan Informasi</strong>. Pastikan anda telah mengatur data-data referensi PSB di <strong>JIBAS Akademik</strong>.
            </font>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <input type="button" onclick="psb_SaveConfig()" value="Simpan" style='width: 100px; height: 30px;' class="but">
            <input type="button" onclick="psb_CancelConfig()" value="Batal" style='width: 100px; height: 30px;' class="but">
        </td>
    </tr>
    </table>
    </fieldset>
    </center>
    <br>
    
<?php    
}

function DoLogout()
{
    require_once("psb.config.session.php");

    unset($_SESSION['isadminlogin']);

}
?>