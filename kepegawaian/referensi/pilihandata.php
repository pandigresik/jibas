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
require_once("../include/sessionchecker.php");
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once("../include/sessioninfo.php");

require_once('pilihandata.func.php');

OpenDb();

ReadParams();

if ($op == "dw8dxn8w9ms8zs22")
    ChangeAktif();

if ($op == "xm8r389xemx23xb2378e23")
    HapusData();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pilihan Data</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

    function tutup()
    {
        var idtambahan = document.getElementById('idtambahan').value;
        opener.refreshPilihan(idtambahan);
        window.close();
    }

    function tambah()
    {
        var addr = "pilihandata.add.php?idtambahan=<?=$idtambahan?>";
        newWindow(addr, 'TambahPilihanData','500','280','resizable=1,scrollbars=1,status=0,toolbar=0')
    }

    function refresh() {
        document.location.reload();
    }

    function setaktif(idpilihan, aktif) {
        var msg;
        var newaktif;

        if (aktif == 1) {
            msg = "Apakah anda yakin akan mengubah pilihan data ini menjadi TIDAK AKTIF?";
            newaktif = 0;
        } else	{
            msg = "Apakah anda yakin akan mengubah pilihan  ini menjadi AKTIF?";
            newaktif = 1;
        }

        if (confirm(msg))
        {
            var idtambahan = document.getElementById('idtambahan').value;
            document.location.href = "pilihandata.php?op=dw8dxn8w9ms8zs22&idtambahan=" + idtambahan + "&idpilihan=" + idpilihan + "&newaktif=" + newaktif;
        }
    }

    function edit(idpilihan) {
        var idtambahan = document.getElementById('idtambahan').value;
        var addr = "pilihandata.edit.php?idtambahan=" + idtambahan + "&idpilihan=" + idpilihan;
        newWindow(addr, 'UbahPilihanData','500','280','resizable=1,scrollbars=1,status=0,toolbar=0')
    }

    function hapus(idpilihan)
    {
        if (confirm("Apakah anda yakin akan menghapus data pilihan ini?"))
        {
            var idtambahan = document.getElementById('idtambahan').value;
            document.location.href = "pilihandata.php?op=xm8r389xemx23xb2378e23&idtambahan=" + idtambahan + "&idpilihan=" + idpilihan;
        }
    }
</script>
</head>

<body onLoad="document.getElementById('departemen').focus()">

<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr>
<td align="left" valign="top">

    <table border="0"width="95%" align="center">
        <tr>
            <td align="right">
                <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
                <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pilihan Data</font>
            </td>
        </tr>
    </table>
    <br /><br />

    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
        <!-- TABLE CONTENT -->
        <tr>
            <td align="left" width="60%">
                Kolom: <strong><?=$kolom?></strong>
                <input type="hidden" name="idtambahan" id="idtambahan" value="<?=$idtambahan?>">
            </td>
            <td align="right" width="*">

                <a href="#" onClick="refresh()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
                <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                    <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')" />&nbsp;Tambah</a>
                <?php } ?>

            </td>
        </tr>
    </table><br />

    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
    <tr height="20">
        <td width="2%" class="header" align="center">No</td>
        <td width="15%" class="header" align="center">Pilihan</td>
        <td width="5%" class="header" align="center">Urutan</td>
        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            <td width="5%" class="header" align="center">Aktif</td>
            <td width="8%" class="header">&nbsp;</td>
        <?php } ?>
    </tr>
<?php
    $sql = "SELECT replid, pilihan, aktif, urutan
              FROM pilihandata
             WHERE idtambahan = '".$idtambahan."'";
    $res = QueryDb($sql);
    $cnt = 0;
    while($row = mysqli_fetch_row($res))
    {
        $idpilihan = $row[0];
        $pilihan = $row[1];
        $aktif = $row[2];
        $urutan = $row[3]; ?>

        <tr height="20">
            <td align="center"><?= ++$cnt ?></td>
            <td align="left"><?= $pilihan ?></td>
            <td align="center"><?= $urutan ?></td>
            <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>
                <td align="center">
                    <?php  if ($aktif == 1) { ?>
                        <a href="JavaScript:setaktif(<?= $idpilihan ?>, <?= $aktif ?>)">
                            <img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/>
                        </a>
                    <?php } else { ?>
                        <a href="JavaScript:setaktif(<?= $idpilihan ?>, <?= $aktif ?>)">
                            <img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/>
                        </a>
                    <?php } ?>
                </td>
                <td align="center">
                    <a href="JavaScript:edit(<?= $idpilihan ?>)"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah!', this, event, '80px')" /></a>&nbsp;
                    <a href="JavaScript:hapus(<?= $idpilihan ?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus!', this, event, '80px')"/></a>
                </td>
            <?php } ?>
        </tr>
<?php
    }
?>
    </table><br />
    <div align="center">
    <input type="button" value="Tutup" class="but" onclick="tutup()">
    </div>
    <script language='JavaScript'>
        Tables('table', 1, 0);
    </script>

</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>

</body>
</html>
<?php
CloseDb();
?>