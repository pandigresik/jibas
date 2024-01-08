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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once("tabungan.trans.pegawai.func.php");

$idtahunbuku = $_REQUEST['idtahunbuku'];
$idtabungan = $_REQUEST['idtabungan'];
$departemen = $_REQUEST['departemen'];

$status = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
    <script src="../script/ajax.js" type="text/javascript"></script>
    <script src="../script/jquery-1.9.0.js" type="text/javascript"></script>
    <script type="text/javascript" src="tabungan.trans.pegawai.js"></script>
    <script language="javascript">
    function pilih(nip)
    {
        parent.content.location.href = "tabungan.trans.input.php?nip="+nip+"&idtabungan=<?=$idtabungan?>&idtahunbuku=<?=$idtahunbuku?>&status=<?=$status?>";
    }
    </script>
</head>

<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF"  onclick="document.getElementById('txBarcode').value = ''">
<input type="hidden" id="idtahunbuku" value="<?=$idtahunbuku ?>" />
<input type="hidden" id="idtabungan" value="<?=$idtabungan ?>" />
<input type="hidden" id="status" value="<?=$status ?>" />
<input type="hidden" id="departemen" value="<?=$departemen ?>" />
<table border="0" width="100%" align="center" cellspacing="2" cellpadding="2">
<tr>
    <td align="left">
        <strong>Bagian:</strong>
        <select id="bagian" onchange="changeBagian()">
            <option value="Akademik">Akademik</option>
            <option value="Non Akademik">Non Akademik</option>
            <option value="ALL" selected>Semua Bagian</option>
        </select>

    </td>
</tr>
<tr>
    <td align="left">

    <div id="divPegawai">
<?php
        OpenDb();
        ShowPegawai("ALL");
        CloseDb();

?>
    </div>


    </td>
</tr>
</table>
</body>
</html>
