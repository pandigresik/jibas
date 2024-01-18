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
function ShowAdminLogin()
{
    ?>
    <br><br><br><br>
    <fieldset style="width: 190px;">
        <legend>
            <span style="font-size: 12px">Administrator JIBAS</span><br><br>
        </legend>

        <table border='0' cellpadding="10">
        <tr>
            <td>
                <span style="font-size: 12px">Login:</span><br>
                <input type="text" id="txLogin" readonly style="font-size: 14px; width: 180px; background-color: #ccc" value="jibas">
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-size: 12px">Password:</span><br>
                <input type="password" id="txPassword" style="font-size: 14px; width: 180px;" value="jibas">
            </td>
        </tr>
        <tr>
            <td>
                <input type="button" class="BtnPrimary" value="Login" onclick="ad_login()"
            </td>
        </tr>
        </table>

    </fieldset>

<?php
}

function AdminLogin($password)
{
    $password = str_replace("'", "`", (string) $password);

    $sql = "SELECT COUNT(*)
              FROM jbsuser.landlord
             WHERE password = md5('$password')";
    $nData = FetchSingle($sql);

    if ($nData == 0)
        return GenericReturn::createJson(-1, "Password salah", "");

    $_SESSION["AdminLogin"] = true;

    return GenericReturn::createJson(1, "OK", "");
}

function AdminLogout()
{
    unset($_SESSION["AdminLogin"]);
}

function ShowAdminMenu()
{
    global $G_VIEW_MEDIA_ALLOW, $G_VIEW_MEDIA_INFO;

    $checked = $G_VIEW_MEDIA_ALLOW ? "checked" : "";
    ?>
    <br><br><br><br>
    <fieldset style="width: 450px;">
        <legend>
            <span style="font-size: 12px">Administrator JIBAS</span><br><br>
        </legend>

        <table border='0' cellpadding="10">
        <tr>
            <td>
                <input type="checkbox" id="chAllow" <?=$checked?>>&nbsp;
                Pengakses JIBAS SchoolTube boleh memainkan media video<br><br>
                <i>Administrator JIBAS bisa menonaktifkan pengaturan ini misalnya ketika sedang ujian sekolah guna mencegah siswa mencari jawaban dan menghemat bandwidth jaringan</i>
            </td>
        </tr>
        <tr>
            <td>
                Keterangan untuk pengakses:<br>
                <textarea id="txKeterangan" rows="2" cols="60"><?=$G_VIEW_MEDIA_INFO?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <input type="button" class="BtnPrimary" value="Simpan" onclick="ad_saveSetting()">&nbsp;
                <input type="button" class="BtnDefault" value="Logout" onclick="ad_logout()">
            </td>
        </tr>
        </table>

    </fieldset>

    <?php
}

function SaveSetting($allow, $info)
{
    $info = SafeInputText($info);
    $info = str_replace("\"", "\\\"", (string) $info);

    $content  = "<?php\r\n";
    $content .= '$G_VIEW_MEDIA_ALLOW = ' . $allow . ';';
    $content .= "\r\n";
    $content .= '$G_VIEW_MEDIA_INFO = "' . $info . '";';
    $content .= "\r\n";
    $content .= "?>\r\n";
    file_put_contents('setting.php', $content);

    $content  = 'var G_VIEW_MEDIA_ALLOW = ' . $allow . ';';
    $content .= "\r\n";
    $content .= 'var G_VIEW_MEDIA_INFO = "' . $info . '";';
    $content .= "\r\n";
    file_put_contents('setting.js', $content);
}
?>
