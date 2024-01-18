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
require_once('../include/theme.php');
require_once('../cek.php');

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
    <title>JIBAS SIMAKA [Tambah Pilihan Data]</title>
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"  style="background-color:#dcdfc4">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr height="58">
        <td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
        <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
            <div align="center" style="color:#FFFFFF; font-size:12px; font-weight:bold">
                .: Tambah Pilihan Data :.
            </div>
        </td>
        <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
    </tr>
    <tr height="150">
        <td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
        <td width="0" style="background-color:#FFFFFF">

            <!-- CONTENT GOES HERE //--->
            <form name="main" onSubmit="return validate()">
                <table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
                    <!-- TABLE CONTENT -->
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

        </td>
        <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
    </tr>
    <tr height="28">
        <td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
        <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
        <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
    </tr>
</table>

<!-- Tamplikan error jika ada -->
<?php if (strlen((string) $ERROR_MSG) > 0) { ?>
    <script language="javascript">
        alert('<?=$ERROR_MSG?>');
    </script>
<?php } ?>
</body>
</html>