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
function ShowCbSearchDepartemen()
{
    $sql = "SELECT departemen
              FROM jbsakad.departemen
             WHERE aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);
    echo "<select id='searchDept' style='font-size: 12px; height: 27px; width: 160px;'>";
    echo "<option value='ALLDEPT'>(all departement)</option>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[0]."</option>";
    }
    echo "</select>";
}

function ShowDefaultMenu()
{
    ?>
    <br><br><br>
    <table border="0" cellpadding="5" width="180px">
    <tr>
        <td align="left">
            <fieldset style="border-color: white; border-width: 1px; border-color: #00cef4;">
                <legend style="color: white; font-size: 12px">SIGN IN</legend>

                <br>
                <span style="color: white">User Id:</span>
                <input id="ix_login" type="text" style="height: 25px; width: 140px" value="" maxlength="30">

                <br><br>
                <span style="color: white">Password:</span>
                <input id="ix_password" type="password" style="height: 25px; width: 140px" value="" maxlength="50">

                <br><br>
                <input type="button" class="BtnPrimary" style="width: 80px; height: 25px;" value="Login" onclick="ix_doLogin()">
                <br>
                <br>
            </fieldset>
        </td>
    </tr>
    <tr>
        <td align="left"  style="line-height: 18px;">
            <br>
            <span style="cursor: pointer; font-family: Verdana; font-weight: bold; color: #d8f0ff; font-size: 12px;" onclick="ix_showHome()">Home</span>
            <br><br>
            <span style="cursor: pointer; font-family: Verdana; font-weight: bold; color: #d8f0ff; font-size: 12px;" onclick="ix_browseChannel()">Browse Channel</span><br>
        </td>
    </tr>
    </table>
<?php
}

function CheckLogin($login, $password)
{
    $login = str_replace("'", "\'", (string) $login);
    $login = str_replace("--", " ", $login);
    $loginValue = "'$login'";

    $sql = "SELECT p.aktif, l.password, p.nama
              FROM jbsuser.login l, jbssdm.pegawai p 
             WHERE l.login = p.nip 
               AND l.login = $loginValue";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        // PEGAWAI
        $aktif = $row[0];
        $passmd5 = $row[1];
        $nama = $row[2];

        if ($aktif == 0)
            return GenericReturn::createJson(-1, "Status pengguna tidak aktif", "");

        if (md5((string) $password) != $passmd5)
        {
            $info = md5((string) $password) . " vs " . $passmd5;
            return GenericReturn::createJson(-1, $info, "");
        }

        $_SESSION["IsLogin"] = true;
        $_SESSION["UserId"] = $login;
        $_SESSION["UserName"] = $nama;
        $_SESSION["UserCol"] = "nip";
        $_SESSION["UserType"] = 2; // 1 Admin, 2 Pegawai, 3 Siswa

        return GenericReturn::createJson(1, "Login OK");
    }
    else
    {
        $sql = "SELECT aktif, pinsiswa, nama
                  FROM jbsakad.siswa
                 WHERE nis = $loginValue";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $aktif = $row[0];
            $pinsiswa = $row[1];
            $nama = $row[2];

            if ($aktif == 0)
                return GenericReturn::createJson(-1, "Status pengguna tidak aktif", "");

            if ($password != $pinsiswa)
                return GenericReturn::createJson(-1, "Password salah", "");

            $_SESSION["IsLogin"] = true;
            $_SESSION["UserId"] = $login;
            $_SESSION["UserName"] = $nama;
            $_SESSION["UserCol"] = "nis";
            $_SESSION["UserType"] = 3; // 1 Admin, 2 Pegawai, 3 Siswa

            return GenericReturn::createJson(1, "Login OK");
        }
        else
        {
            return GenericReturn::createJson(-1, "Tidak ditemukan pengguna", "");
        }

    }
}

function ShowUserPict()
{
    $userId = $_SESSION["UserId"];
    $userType = $_SESSION["UserType"];

    if ($userType == 2)
    {
        $sql = "SELECT IF(foto IS NULL, 0, 1), foto
                  FROM jbssdm.pegawai
                 WHERE nip = '".$userId."'";
    }
    else
    {
        $sql = "SELECT IF(foto IS NULL, 0, 1), foto
                  FROM jbsakad.siswa
                 WHERE nis = '".$userId."'";
    }

    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        echo "<img src='images/nofoto.png' title='tidak ada gambar foto'>";
    }
    else
    {
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
        {
            echo "<img src='images/nofoto.png' title='tidak ada gambar foto'>";
        }
        else
        {
            $imgSrc = "data:image/png;base64," . base64_encode((string) $row[1]);
            echo "<img src='$imgSrc'>";
        }
    }
}

function ShowLoginMenu()
{
    ?>
    <br><br><br>
    <table border="0" cellpadding="10" width="190px;">
    <tr>
        <td align="center">
<?php       ShowUserPict() ?>
        </td>
    </tr>
    <tr>
        <td align="center">
            <span style="font-size: 14px; color: white"><?=$_SESSION["UserName"]?></span>
            <br>
            <span style="font-size: 12px; color: white"><?=$_SESSION["UserId"]?></span>
        </td>
    </tr>
    <tr>
        <td align="left" style="line-height: 18px;">
            <span style="cursor: pointer; font-family: Verdana; font-weight: bold; color: #d8f0ff; font-size: 12px;" onclick="ix_showHome()">Home</span><br><br>
            <span style="cursor: pointer; font-family: Verdana; font-weight: bold; color: #d8f0ff; font-size: 12px;" onclick="ix_browseChannel()">Browse Channel</span><br><br>
            <span style="cursor: pointer; font-family: Verdana; font-weight: bold; color: #d8f0ff; font-size: 12px;">Following</span><br>
            &bull;&nbsp;<span style="cursor: pointer; font-family: Verdana; font-weight: bold; color: #d8f0ff; font-size: 11px;"  onclick="ix_showFollow(2)">Channel</span><br>
            &bull;&nbsp;<span style="cursor: pointer; font-family: Verdana; font-weight: bold; color: #d8f0ff; font-size: 11px;" onclick="ix_showFollow(1)" >Modul</span><br><br>
            <span style="cursor: pointer; font-family: Verdana; font-weight: bold; color: #d8f0ff; font-size: 12px;" onclick="ix_showFavVideo();" >Favourite Video</span><br><br>
            <span style="cursor: pointer; font-family: Verdana; font-weight: bold; color: #ff9e11; font-size: 12px;" onclick="ix_doLogout()">Logout</span><br>
        </td>
    </tr>
    </table>
    <?php
}

function CountTotalVideo()
{
    $sql = "SELECT COUNT(id)
              FROM jbsel.media
             WHERE aktif = 1";
    return FetchSingle($sql);
}

function Logout()
{
    foreach($_SESSION as $k => $v)
    {
        unset($_SESSION[$k]);
    }
}

function GetCurrentSession()
{
    if (!isset($_SESSION["IsLogin"]))
        return "---";

    return $_SESSION["UserId"];
}
?>