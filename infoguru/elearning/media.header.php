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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once("../include/sessionchecker.php");
require_once("media.header.func.php");

$departemen = "";
if (isset($_REQUEST['departemen']))
    $departemen = $_REQUEST['departemen'];

OpenDb();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Jadwal Guru</title>
    <script language="javascript" src="../script/jquery-1.9.1.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="media.header.js"></script>
</head>

<body topmargin="0" leftmargin="0">
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td width="50%">

        <table border="0" cellspacing="5">
        <tr>
            <td width="100">Departemen:</td>
            <td width="300">
<?php           $selDept = "";
                ShowCbDepartemen()  ?>
            </td>
            <td rowspan="2" width="100">
                <a href="#" onClick="ch_tampil()">
                    <img src="../images/ico/view.png" height="48" width="48" border="0"/>
                </a>
            </td>
        </tr>
        <tr>
            <td width="100">Pelajaran:</td>
            <td width="300">
                <div id="divPelajaran">
<?php           $selPel = "";
                ShowCbPelajaran($selDept) ?>
                </div>
            </td>
        </tr>
        <tr>
            <td width="100">Channel:</td>
            <td width="300">
                <div id="divChannel">
<?php           ShowCbChannel($selPel) ?>
                </div>
            </td>
        </tr>
        </table>

    </td>
    <td valign="top" align="right" width="50%">
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Upload Video</font><br />
        <font size="1" color="#000000"><b>School Tube</b></font>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Upload Video</b></font>
    </td>
</tr>
</table>

</body>
</html>

<?php
CloseDb();
?>