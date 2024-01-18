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

require_once('pilihandata.add.func.php');

ReadParams();

SimpanData();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JIBAS SIMAWAI [Tambah Pilihan Data]</title>
    <script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/validasi.js"></script>
    <script language="javascript">
        function validate() {
            return validateEmptyText('pilihan', 'Pilihan Data') &&
                   validateEmptyText('urutan', 'Urutan Kolom Data') &&
                   validateNumber('urutan', 'Urutan Kolom Data');
        }

        function focusNext(elemName, evt)
        {

        }

        function panggil(elem){

        }
    </script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"  style="background-color:#fff">
<!-- CONTENT GOES HERE //--->
<form name="main" onSubmit="return validate()">
    <table border="0" width="95%" cellpadding="2" cellspacing="5" align="center">
        <!-- TABLE CONTENT -->
        <tr style="height: 26px">
            <td colspan="2" align="center" class="header">
                Tambah Pilihan Data
            </td>
        </tr>
        <tr>
            <td width="90"><strong>Pilihan</strong></td>
            <td>
                <input type="hidden" name="idtambahan" id="idtambahan" value="<?=$idtambahan?>">
                <input type="text" name="pilihan" id="pilihan" size="30" maxlength="100" value="<?=$pilihan?>"
                       onFocus="showhint('Nama pilihan tidak boleh lebih dari 100 karakter!', this, event, '120px'); panggil('pilihan')"
                       onKeyPress="return focusNext('urutan', event)"/>

            </td>
        </tr>
        <tr>
            <td width="90"><strong>Urutan</strong></td>
            <td>
                <input type="text" name="urutan" id="urutan" size="4" maxlength="4" value="<?=$urutan?>"
                       onFocus="showhint('Urutan kolom tidak boleh lebih dari 4 karakter!', this, event, '120px'); panggil('urutan')"
                       onKeyPress="return focusNext('Simpan', event)"/>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br><br>
                <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but" onFocus="panggil('Simpan')"/>&nbsp;
                <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />
            </td>
        </tr>
        <!-- END OF TABLE CONTENT -->
    </table>
</form>
<!-- END OF CONTENT //--->

<!-- Tamplikan error jika ada -->
<?php if (strlen((string) $ERROR_MSG) > 0) { ?>
    <script language="javascript">
        alert('<?=$ERROR_MSG?>');
    </script>
<?php } ?>
</body>
</html>