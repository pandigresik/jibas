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
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

$replid = $_REQUEST['replid'];

OpenDb();
$sql = "SELECT * FROM tahunajaran WHERE replid = '".$replid."'"; 
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$departemen = $row['departemen'];
$replid = $row['replid'];
$tahunajaran = $row['tahunajaran'];
$keterangan = $row['keterangan'];
$tglmulai = TglText($row['tglmulai']);
$tglakhir = TglText($row['tglakhir']);
CloseDb();
/* 
$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) {
	OpenDb();

	$sql = "SELECT * FROM tahunajaran WHERE departemen = '".$_REQUEST['departemen']."' AND tahunajaran='".$_REQUEST['tahunajaran']."' AND replid <> $replid ";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {
		CloseDb();
			$ERROR_MSG = "Tahun Ajaran {$_REQUEST['tahunajaran']} sudah digunakan!";
	} else {
		
		$sql = "UPDATE tahunajaran SET tahunajaran = '".$_REQUEST['tahunajaran']."', tglmulai = '$tglmulai', tglakhir = '$tglakhir', keterangan = '".$_REQUEST['keterangan']."' WHERE replid = $replid";
			//$result = QueryDb($sql);
		echo "sql ".$sql;
	}
}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<link rel="stylesheet" type="text/css" href="../style/calendar-system.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Ubah Tahun Ajaran]</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script type="text/javascript" src="../script/calendar.js"></script>
<script type="text/javascript" src="../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../script/calendar-setup.js"></script>
<script language="javascript">

function validate(){	
	var replid = document.getElementById("replid").value;
	var ajaran = document.main.tahunajaran.value;
	var tglmulai = document.main.tglmulai.value;
	var tglakhir = document.main.tglakhir.value;
	var keterangan = document.main.keterangan.value;
	var departemen = document.main.departemen.value;
	var tgl = "";	
	var bln = "";
	var th = "";
	var tgl1 = "";
	var bln1 = "";
	var th1 = "";
	
	if (ajaran.length == 0) {
		alert("Anda harus mengisikan data untuk tahun ajaran");
		document.getElementById("tahunajaran").focus();
		return false;
	}
	
	if (tglmulai.length == 0) {	
		alert("Anda harus mengisikan data untuk tanggal mulai");
		return false;
	}
	
	if (tglakhir.length == 0) {	
		alert("Anda harus mengisikan data untuk tanggal akhir");
		return false;
	}
	
	if (tglmulai.length > 0 && tglakhir.length > 0) {
		for (i = 0; i<tglmulai.length;i++){
			if (i < 2) {
				if (i == 0 && tglmulai.charAt(0) == '0' ) 
					tgl = "";	
				else 
					tgl = tgl + tglmulai.charAt(i);
							
				if (i == 0 && tglakhir.charAt(0) == '0' ) 
					tgl1 = "";					
			 	else 
					tgl1 = tgl1 + tglakhir.charAt(i);
			} else if (i < 5 && i > 2) {
				if (i == 3 && tglmulai.charAt(3) == '0' ) 
					bln = "";	
				else 
					bln = bln + tglmulai.charAt(i);
							
				if (i == 3 && tglakhir.charAt(3) == '0' ) 
					bln11 = "";					
			 	else 
					bln1 = bln1 + tglakhir.charAt(i);
				
			} else if (i < tglmulai.length && i > 5 ) {				
				th = th + tglmulai.charAt(i);
				th1 = th1 + tglakhir.charAt(i);				
			}	 
		}
	
		tgl = parseInt(tgl);
		tgl1 = parseInt(tgl1);
		bln = parseInt(bln);
		bln1 = parseInt(bln1);
		th = parseInt(th);
		th1 = parseInt(th1);
		
		if (th > th1) {
			alert ('Pastikan batas tahun akhir tidak kurang dari batas tahun awal');
			return false;
		} 
	
		if (th == th1 && bln > bln1 ) {
			alert ('Pastikan batas bulan akhir tidak kurang dari batas bulan awal');
			return false; 
		}	
	
		if (th == th1 && bln == bln1 && tgl > tgl1 ) { 
			alert ('Pastikan batas tanggal akhir tidak kurang dari batas tanggal awal');			
			return false;
		} 
	}
	
	action = "update";
	opener.location.href = "tahunajaran.php?departemen="+departemen+"&tahunajaran="+ajaran+"&tglmulai="+tglmulai+"&tglakhir="+tglakhir+"&keterangan="+keterangan+"&replid="+replid+"&action="+action;
	window.close();
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
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"  style="background-color:#dcdfc4" onload="document.getElementById('tahunajaran').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Tahun Ajaran :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<form name="main" onsubmit="return validate()">
<input type="hidden" name="replid" id="replid" value="<?=$replid ?>" />
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="30%"><strong>Departemen</strong></td>
	<td colspan="2">
    	<input type="text" class="disabled" name="departemen" id="departemen" size="10" maxlength="50" value="<?=$departemen ?>" readonly="readonly" />    </td>
</tr>
<tr>
    <td><strong>Tahun Ajaran</strong></td>
    <td colspan="2">
    	<input type="text" name="tahunajaran" id="tahunajaran" size="30" maxlength="50" value="<?=$tahunajaran ?>" onFocus="showhint('Nama Tahun Ajaran tidak boleh lebih dari 50 karakter!', this, event, '120px')"   onKeyPress="return focusNext('keterangan', event)"/>
    </td>
</tr>
<tr>
	<td><strong>Tgl Mulai</strong></td>
	<td>
    	<input type="text" class="disabled" id="tglmulai" name="tglmulai" size="25" value="<?=$tglmulai ?>"readonly onclick="Calendar.setup()" onKeyPress="return focusNext('keterangan', event)"/></td>
    <td width="35%"><img src="../images/calendar.jpg" name="tabel" border="0" id="btntglmulai" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/></td>
</tr>
<tr>
	<td><strong>Tgl Akhir</strong></td>
	<td>
    	<input type="text" class="disabled" id="tglakhir" name="tglakhir" size="25" value="<?=$tglakhir ?>" readonly onclick="Calendar.setup()" onKeyPress="return focusNext('keterangan', event)"/></td>
    <td><img src="../images/calendar.jpg" name="tabel" border="0" id="btntglakhir" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/></td>
</tr>
<tr>
	<td valign="top">Keterangan</td>
	<td colspan="2">
    	<textarea name="keterangan" id="keterangan" rows="3" cols="40"  onKeyPress="return focusNext('Simpan', event)"><?=$keterangan ?></textarea>    </td>
</tr>
<tr>
	<td colspan="3" align="center">
    <input type="Submit" name="Simpan" id="Simpan" value="Simpan" class="but" />&nbsp;
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />    </td>
</tr>
<!-- END OF TABLE CONTENT -->
</table>
</form>
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
<script type="text/javascript">
  Calendar.setup(
    {
      inputField  : "tglmulai",         // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format
      button      : "btntglmulai"       // ID of the button
    }
  );
   Calendar.setup(
    {
      inputField  : "tglakhir",         // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format
      button      : "btntglakhir"       // ID of the button
    }
  );
</script>
</body>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("tahunajaran");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
</script>