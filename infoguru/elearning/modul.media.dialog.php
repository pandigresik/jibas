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

$userId = SI_USER_ID();

$no = $_REQUEST["no"];
$idMediaModul = $_REQUEST["idMediaModul"];

OpenDb();

$sql = "SELECT urutan, keterangan FROM jbsel.mediamodul WHERE id = $idMediaModul";
$res = QueryDb($sql);
if ($row = mysqli_fetch_array($res))
{
    $urutan = $row["urutan"];
    $keterangan = $row["keterangan"];
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Edit Keterangan Media Modul</title>
    <script language="javascript" src="../script/jquery-1.9.1.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/validatorx.js"></script>
    <script type="text/javascript">
        validateInput = function ()
        {
            var valx = new ValidatorX();

            return valx.EmptyText("urutan", "Urutan") &&
                   valx.IsInteger("urutan", "Urutan");
        };

        simpanMediaModulInfo = function ()
        {
            if (!validateInput())
                return;

            var no = $("#no").val();
            var idMediaModul = $("#idMediaModul").val();
            var urutan = $("#urutan").val();
            var keterangan = $.trim($("#keterangan").val());

            opener.acceptMediaModulInfo(idMediaModul, no, urutan, keterangan);
            window.close();
        }
    </script>
</head>

<body topmargin="10" leftmargin="10">
<br>
<span style="font-size: 14px; font-weight: bold; font-family: Verdana">Edit Keterangan</span>
<br><br>
<input type="hidden" id="no" name="no" value="<?=$no?>">
<input type="hidden" id="idMediaModul" name="idMediaModul" value="<?=$idMediaModul?>">
<table border="0" cellpadding="2" cellspacing="0">
    <tr>
        <td width="100" align="right">Urutan:</td>
        <td width="500" align="left">
            <input type="text" id="urutan" name="urutan" style="font-size: 12px; height: 25px; width: 50px;" maxlength="3" value="<?=$urutan?>">
        </td>
    </tr>
    <tr>
        <td width="100" align="right" valign="top">Keterangan:</td>
        <td width="500" align="left">
            <textarea id="keterangan" name="keterangan" rows="5" cols="60" style="font-size: 12px;"><?=$keterangan?></textarea>
        </td>
    </tr>
    <tr>
        <td width="100" align="right" valign="top">&nbsp;</td>
        <td width="500" align="left">
            <input type="button" id="Simpan" name="Simpan" value="Simpan" class="but" style="height: 25px;" onclick="simpanMediaModulInfo()">
            <input type="button" value="Tutup" class="but" style="height: 25px;" onclick="window.close()" >
        </td>
    </tr>
</table>

</body>
</html>

<?php
CloseDb();
?>
