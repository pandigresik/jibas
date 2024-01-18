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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../library/jurnal.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');
require_once('../include/sessioninfo.php');
require_once('tabungan.trans.edit.func.php');

OpenDb();

$idpembayaran = $_REQUEST['idpembayaran'];
   
GetDataTransaksi();

if (1 == (int)$_REQUEST['issubmit'])
    SimpanTransaksi();
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Ubah Transaksi Tabungan]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/calendar-green.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script src="../script/tooltips.js" language="javascript"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/rupiah2.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script type="text/javascript" src="../script/calendar.js"></script>
<script type="text/javascript" src="../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../script/calendar-setup.js"></script>
<script type="text/javascript" src="tabungan.trans.edit.js"></script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="" style='background-color:#dfdec9' onLoad="document.getElementById('jcicilan').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../images/default/bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../images/default/bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Transaksi Tabungan :.
    </div>
	</td>
    <td width="28" background="../images/default/bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../images/default/bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
   
    <form name="main" method="post">
    <input type="hidden" name="issubmit" id="issubmit" value="0" />
    <input type="hidden" name="action" id="action" value="<?=$action?>">
    <input type="hidden" name="debet" id="debet" value="<?=$debet?>">
    <input type="hidden" name="kredit" id="kredit" value="<?=$kredit?>">
    <input type="hidden" name="idpembayaran" id="idpembayaran" value="<?=$idpembayaran ?>" />
   	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
    <tr>
        <td width="50%"><strong>Tabungan</strong></td>
        <td colspan="2"><input type="text"  size="30" value="<?=$namatabungan?>" readonly style="background-color:#CCCC99"/></td>
    </tr>
    <tr>
        <td><strong>Nama</strong></td>
        <td colspan="2"><input type="text"  size="30" value="<?=$nis . " - " . $namasiswa ?>" readonly style="background-color:#CCCC99"/></td>
    </tr>
	<tr>
        <td>
        <strong>
<?php      echo ($action == "setor") ? "Setoran" : "Penarikan"; ?>
        </strong>
        </td>
        <td colspan="2">
            <input type="text" name="jbayar" id="jbayar" value="<?=FormatRupiah($jbayar) ?>"
                   onblur="formatRupiah('jbayar')" onfocus="unformatRupiah('jbayar')"
                   onKeyPress="return focusNext('tbayar', event)" onkeyup="salinangka()"/>
            <input type="hidden" name="angkabayar" id="angkabayar" value="<?=$jbayar?>" />
        </td>
    </tr>
    <tr>
        <td><strong>Rek. Kas</strong></td>
        <td colspan="2">
            <select name="rekkas" id="rekkas" style="width: 220px">
    <?php          $sql = "SELECT kode, nama
                          FROM jbsfina.rekakun
                         WHERE kategori = 'HARTA'
                         ORDER BY nama";        
                $res = QueryDb($sql);
                while($row = mysqli_fetch_row($res))
                {
                    $sel = $row[0] == $rekkastrans ? "selected" : "";
                    echo "<option value='".$row[0]."' $sel>{$row[0]} {$row[1]}</option>";
                } ?>                
            </select>
        </td>
    </tr>
    <tr>
        <td><strong>Tanggal</strong></td>
        <td colspan="2">
			<input type="text" name="tbayar" id="tbayar" readonly size="15" value="<?=$tanggal ?>"
                   onKeyPress="return focusNext('alasan', event)" style="background-color:#CCCC99">
		</td>
        
    </tr>
    <tr>
        <td valign="top"><strong>Alasan Perubahan</strong></td>
        <td colspan="2">
            <textarea id="alasan" name="alasan" rows="2" cols="30"
                      onKeyPress="return focusNext('keterangan', event)"><?=$alasan ?></textarea>
        </td>
    </tr>
    <tr>
        <td valign="top">Keterangan</td>
        <td colspan="2">
            <textarea id="keterangan" name="keterangan" rows="2" cols="30"
                      onKeyPress="return focusNext('Simpan', event)"><?=$keterangan ?></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center">
            <input type="button" name="Simpan" id="Simpan" class="but" value="Simpan" onclick="this.disabled = true; ValidateSubmit();" />
            <input type="button" name="tutup" id="tutup" class="but" value="Tutup" onclick="window.close()" />
        </td>
    </tr>
    </table>
    </form>
    </td>
    <td width="28" background="../images/default/bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../images/default/bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../images/default/bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../images/default/bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
<?php if (strlen((string) $errmsg) > 0) { ?>
<script language="javascript">alert('<?=$errmsg?>');</script>
<?php } ?>
</body>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("tcicilan");
var sprytextfield1 = new Spry.Widget.ValidationTextField("jcicilan");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("kcicilan");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("alasan");
</script>
<?php
CloseDb();    
?>