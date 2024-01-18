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
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');
require_once('../include/sessioninfo.php');
require_once('jenistabungan.add.func.php');

$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['simpan'])) SimpanData();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Tambah Jenis Tabungan]</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/rupiah.js"></script>
<script language="javascript" src="jenistabungan.add.js"></script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="" style='background-color:#dfdec9' onLoad="document.getElementById('nama').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../images/default/bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../images/default/bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Tambah Jenis Tabungan :.
    </div>
	</td>
    <td width="28" background="../images/default/bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../images/default/bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">

    <form name="main" method="post" onSubmit="return validasi();">   
    <input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" /> 
    <table border="0" cellpadding="2" cellspacing="2" align="center" background="">
	<!-- TABLE CONTENT -->
    <tr>
        <td align="left"><strong>Departemen</strong></td>
        <td align="left"><input type="text" name="departemen" id="departemen" maxlength="100" size="30" readonly style="background-color:#CCCC99" value="<?=$departemen ?>">
        </td>
    </tr>
    <tr>
        <td align="left"><strong>Nama</strong></td>
        <td align="left"><input type="text" name="nama" id="nama" value="<?=$_REQUEST['nama']?>" maxlength="100" size="30" onKeyPress="return focusNext('rekkas', event);" onFocus="panggil('nama')"></td>
    </tr>
    <tr>
        <td align="left"><strong>Rek. Kas</strong></td>
        <td align="left">
            <input onClick="cari_rek(1,'HARTA')" type="text" name="rekkas" id="rekkas" value="<?=$_REQUEST['rekkas']?>" readonly
                   style="background-color:#CCCC99" maxlength="100" size="30"
                   onKeyPress="cari_rek(1,'HARTA'); return focusNext('rekutang',event);" onFocus="panggil('rekkas')">&nbsp;
                   <a href="#" onClick="JavaScript:cari_rek(1,'HARTA')"><img src="../images/ico/lihat.png" border="0" /></a>
            <input type="hidden" name="norekkas" id="norekkas"  value="<?=$_REQUEST['norekkas']?>" />
        </td>
    </tr>
    <tr>
        <td align="left"><strong>Rek. Utang</strong></td>
        <td align="left">
            <input onClick="cari_rek(2,'UTANG')" type="text" name="rekutang" id="rekutang" value="<?=$_REQUEST['rekutang']?>" readonly
                   style="background-color:#CCCC99" maxlength="100" size="30" onKeyPress="cari_rek(2,'UTANG'); return focusNext('keterangan', event)" onFocus="panggil('rekutang')">&nbsp;
                   <a href="#" onClick="JavaScript:cari_rek(2,'UTANG')"><img src="../images/ico/lihat.png" border="0" /></a>
        <input type="hidden" name="norekutang" id="norekutang" value="<?=$_REQUEST['norekutang']?>"/>
        </td>
    </tr>
    <tr>
        <td align="left" valign="top">Keterangan</td>
        <td align="left"><textarea name="keterangan" id="keterangan" rows="2" cols="40" onKeyPress="return focusNext('simpan', event)" onFocus="panggil('keterangan')"><?=$_REQUEST['keterangan']?></textarea></td>
    </tr>
	<tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left">
			<input type='checkbox' id='smsinfo' name='smsinfo'>&nbsp;Notifikasi SMS | Telegram | Jendela Sekolah
		</td>
    </tr>
    <tr>
        <td colspan="2" align="center">
        	<input class="but" type="submit" value="Simpan" name="simpan" id="simpan" onFocus="panggil('simpan')">
            <input class="but" type="button" value="Tutup" onClick="window.close();">
        </td>
    </tr>
    </table>
    </form>
	<!-- END OF CONTENT //--->
    </td>
    <td width="28" background="../images/default/bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../images/default/bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../images/default/bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../images/default/bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
<?php if (strlen((string) $mysqli_ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$mysqli_ERROR_MSG?>');		
</script>
<?php } ?>
</body>
</html>
<!--<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("nama");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
</script>-->