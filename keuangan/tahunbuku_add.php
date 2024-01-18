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
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/theme.php');
require_once('include/sessioninfo.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$tanggalmulai = date('d-m-Y');
if (isset($_REQUEST['tanggalmulai']))
	$tanggalmulai = $_REQUEST['tanggalmulai'];
	
$cek = 0;	
if (isset($_REQUEST['simpan'])) {
	$tanggalmulai = MySqlDateFormat($_REQUEST['tcicilan']);
	OpenDb();
	
	$sql = "SELECT * FROM tahunbuku WHERE tahunbuku = '".CQ($_REQUEST['tahunbuku'])."' AND departemen = '".$_REQUEST['departemen']."'";
	$result = QueryDb($sql);
	
	$sql1 = "SELECT * FROM tahunbuku WHERE awalan = '".CQ($_REQUEST['awalan'])."' AND departemen = '".$_REQUEST['departemen']."'";
	$result1 = QueryDb($sql1);
	
	if (mysqli_num_rows($result) > 0) {
		CloseDb();
		$mysqli_ERROR_MSG = "Nama {$_REQUEST['tahunbuku']} sudah digunakan";
		$cek = 0;
	} else if (mysqli_num_rows($result1) > 0) {
		CloseDb();
		$mysqli_ERROR_MSG = "Awalan {$_REQUEST['awalan']} sudah digunakan";
		$cek = 1;	
	} else {
		$sql = "INSERT INTO tahunbuku SET tahunbuku='".CQ($_REQUEST['tahunbuku'])."', tanggalmulai='$tanggalmulai', awalan='".CQ($_REQUEST['awalan'])."',aktif=1,keterangan='".CQ($_REQUEST['keterangan'])."', departemen='".$_REQUEST['departemen']."'";
		
		$result = QueryDb($sql);
		CloseDb();
	
		if ($result) { ?>
			<script language="javascript">
				opener.refresh();
				window.close();
			</script> 
<?php 	}
	}
}

switch ($cek) {
	case 0 : $input_awal = "onload=\"document.getElementById('tahunbuku').focus()\"";
		break;
	case 1 : $input_awal = "onload=\"document.getElementById('awalan').focus()\"";
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/calendar-green.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>JIBAS KEU [Tambah Tahun Buku]</title>
<script language="javascript" src="script/tooltips.js"></script>
<script type="text/javascript" src="script/calendar.js"></script>
<script type="text/javascript" src="script/lang/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar-setup.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript">
function validasi() {
	return validateEmptyText('tahunbuku', 'Tahun Buku') 
		&& validateEmptyText('awalan', 'Awalan Kuitansi')
		&& validateMaxText('keterangan', 255, 'Keterangan Tahun Buku');
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function panggil(elem){
	var lain = new Array('tahunbuku','awalan','keterangan');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#FFFF99';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}
</script>
</head>

<body style='background-color:#dfdec9' topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="" <?=$input_awal?>>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Tambah Tahun Buku :.
    </div>
	</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    
    <form name="main" method="post" onSubmit="return validasi();">    
   	<table border="0" cellpadding="2" cellspacing="2" align="center" background="">
	<!-- TABLE CONTENT -->
    <tr>
        <td width="40%"><strong>Departemen</strong></td>
        <td colspan="2"><input type="text" name="departemen" id="departemen" value="<?=$_REQUEST['departemen']?>" maxlength="25" size="25" readonly="readonly" style="background-color:#CCCC99"></td>
    </tr>
    <tr>
        <td><strong>Tahun Buku</strong></td>
        <td colspan="2"><input type="text" name="tahunbuku" id="tahunbuku" value="<?=CQ($_REQUEST['tahunbuku'])?>" maxlength="100" size="25" onKeyPress="return focusNext('tcicilan', event)" onFocus="panggil('tahunbuku')"></td>
    </tr>
    <tr>
		<td align="left"><strong>Tanggal Mulai</strong></td>
	    <td>
	    <input type="text" name="tcicilan" id="tcicilan" readonly size="15" value="<?=$tanggalmulai?>" onKeyPress="return focusNext('awalan', event);" onFocus="panggil('tcicilan')" onClick="Calendar.setup()" style="background-color:#CCCC99"></td>
        <td width="60%">
        <img src="images/calendar.jpg" name="tabel" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/>
	    </td>
	</tr>
    <tr>
        <td><strong>Awalan Kuitansi</strong></td>
        <td colspan="2"><input type="text" name="awalan" id="awalan" value="<?=CQ($_REQUEST['awalan'])?>" maxlength="5" size="7" onKeyPress="return focusNext('keterangan', event)" onFocus="panggil('awalan')"></td>
    </tr>
    <tr>
        <td valign="top">Keterangan</td>
        <td colspan="2"><textarea name="keterangan" id="keterangan" rows="3" cols="40" onKeyPress="return focusNext('simpan', event)" onFocus="panggil('keterangan')"><?=$_REQUEST['keterangan']?></textarea></td>
    </tr>
    <tr>
        <td align="center" colspan="3">
        	<input class="but" type="submit" value="Simpan" name="simpan" id="simpan" onFocus="panggi('simpan')">
            <input class="but" type="button" value="Tutup" onClick="window.close();">
        </td>
    </tr>
    </table>
    </form>
	<!-- END OF CONTENT //--->
    </td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
<?php if (strlen((string) $mysqli_ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$mysqli_ERROR_MSG?>');		
</script>
<?php } ?>

</body>
</html>
<script language="javascript">
  Calendar.setup(
    {
      //inputField  : "tanggalshow","tanggal"
	  inputField  : "tcicilan",         // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format
      button      : "btntanggal"       // ID of the button
    }
   );
 	Calendar.setup(
    {
      inputField  : "tcicilan",        // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format	  
	  button      : "tcicilan"       // ID of the button
    }
  );
</script>