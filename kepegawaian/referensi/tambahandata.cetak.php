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
require_once('../include/getheader.php');

require_once('tambahandata.func.php');

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JIBAS SIMAKA [Cetak Tambahan Data]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
    <tr><td align="left" valign="top">

            <?=getHeader("yayasan")?>

            <center><font size="4"><strong> TAMBAHAN DATA</strong></font><br /> </center><br /><br />

            <br />

            <strong>Departemen: <?=$departemen?></strong><br>
            <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
                <tr height="30">
                    <td width="4%" class="header" align="center">No</td>
                    <td width="25%" class="header" align="center">Kolom</td>
                    <td width="10%" class="header" align="center">Urutan</td>
                    <td width="15%" class="header" align="center">Jenis</td>
                    <td width="25%" class="header" align="center">Data Pilihan</td>
                    <td width="10%" class="header" align="center">Aktif</td>
                </tr>
                <?php
                $sql = "SELECT kolom, jenis, keterangan, aktif, urutan, replid  
                          FROM tambahandata";
                $result = QueryDB($sql);
                $cnt = 0;
                while ($row = mysqli_fetch_array($result)) { ?>
                    <tr height="25">
                        <td align="center"><?=++$cnt ?></td>
                        <td><?=$row['kolom'] ?></td>
                        <td align="center"><?=$row['urutan'] ?></td>
                        <td align="center">
                        <?php  if ((int)$row[1] == 1)
                                echo "Teks";
                            else if ((int)$row[1] == 2)
                                echo "File";
                            else
                                echo "Pilihan" ?>
                        </td>
                        <td align="center">
                            <?php if ((int) $row[1] == 3) {
                                ShowDataPilihan($row[5]);
                            } else { echo "-"; } ?>
                        </td>
                        <td align="center"><?=$row['aktif'] == 1 ? "Aktif" : "Non Aktif" ?></td>
                    </tr>
                <?php }
                ?>
                <!-- END TABLE CONTENT -->
            </table>

        </td></tr></table>
</body>
<script language="javascript">
    window.print();
</script>
</html>
<?php
CloseDb();
?>