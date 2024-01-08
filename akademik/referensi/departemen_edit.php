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
	
$cek = 0;
$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) 
{
	OpenDb();
	
	$sql = "SELECT * FROM departemen WHERE departemen = '".CQ($_REQUEST['departemen'])."' AND replid <> '$replid'";
	$result = QueryDb($sql);
	
	$sql1 = "SELECT * FROM departemen WHERE urutan = '".$_REQUEST['urutan']."' AND replid <> '$replid'";
	$result1 = QueryDb($sql1);
	
	if (mysqli_num_rows($result) > 0) 
	{
		CloseDb();
		$ERROR_MSG = "Departemen {$_REQUEST['departemen']} sudah digunakan!";
		$cek = 0;	
	} 
	else if (mysqli_num_rows($result1) > 0) 
	{
		CloseDb();
		$ERROR_MSG = "Urutan {$_REQUEST['urutan']} sudah digunakan!";
		$cek = 2;
	} 
	else 
	{
		$departemen = CQ($_REQUEST['departemen']);
		
		$sql = "UPDATE departemen 
				SET departemen='$departemen',nipkepsek='".$_REQUEST['nip']."',urutan='".$_REQUEST['urutan']."',
				    keterangan='".CQ($_REQUEST['keterangan'])."' WHERE replid='$replid'";
		$result = QueryDb($sql);
		CloseDb();
	
		if ($result) 
		{ ?>
			<script language="javascript">
				opener.refresh();
				window.close();
			</script> 
<?php 	}
		exit();
	}
};

switch ($cek) 
{
	case 0 : $input_awal = "onload=\"document.getElementById('departemen').focus()\"";
		break;
	case 1 : $input_awal = "onload=\"document.getElementById('nip').focus()\"";
		break;
	case 2 : $input_awal = "onload=\"document.getElementById('urutan').focus()\"";
		break;
}

OpenDb();

$sql = "SELECT d.departemen,d.nipkepsek,p.nama,d.urutan,d.keterangan 
		FROM departemen d, jbssdm.pegawai p 
		WHERE d.nipkepsek = p.nip AND d.replid = '".$replid."'"; 

$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$departemen = $row[0];
$nipkepsek = $row[1];
$namakepsek = $row[2];
$urutan = $row[3];
$keterangan = $row[4];

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
if (isset($_REQUEST['nipkepsek']))
	$nipkepsek = $_REQUEST['nipkepsek'];
if (isset($_REQUEST['namakepsek']))
	$namakepsek = $_REQUEST['namakepsek'];	
if (isset($_REQUEST['keterangan']))
	$keterangan = $_REQUEST['keterangan'];	
	
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Ubah Departemen]</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">
function caripegawai() {
	newWindow('../library/pegawai.php?flag=0', 'CariPegawai','600','618','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(nip, nama, flag) {
	document.getElementById('nipkepsek').value = nip;
	document.getElementById('nip').value = nip;
	document.getElementById('namakepsek').value = nama;
	document.getElementById('nama').value = nama;
	document.getElementById('urutan').focus();
}

function tutup() {
	document.getElementById('urutan').focus();
}

function validate() {
	return validateEmptyText('departemen', 'Nama Departemen') && 
		   validateMaxText('departemen', 15, 'Nama Departemen');
		   validateEmptyText('nip', 'NIP Kepala Sekolah') && 
		   validateEmptyText('urutan', 'Urutan Departemen') && 
		   validateNumber('urutan', 'Urutan Departemen') &&
		   validateMaxText('keterangan', 255, 'Keterangan');
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'nip')
			caripegawai();
		return false;
    } 
    return true;
}

function panggil(elem){
	var lain = new Array('departemen','urutan','keterangan');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"  style="background-color:#dcdfc4" <?=$input_awal?>>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
    <div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Departemen :.
    </div>
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<form name="main" onSubmit="return validate()">
<input type="hidden" name="replid" id="replid" value="<?=$replid ?>" />


<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="120"><strong>Departemen</strong></td>
	<td>
    	<input type="text" name="departemen" id="departemen" size="10" maxlength="15" value="<?=$departemen ?>" onFocus="showhint('Nama departemen tidak boleh lebih dari 50 karakter!', this, event, '120px');panggil('departemen')"  onKeyPress="return focusNext('nip', event)"/>
    </td>
</tr>
<tr>
    <td><strong>Kepala Sekolah</strong></td>
    <td>
        <input type="text" class="disabled" name="nip" id="nip" size="10" readonly value="<?=$nipkepsek ?>" onClick="caripegawai()" onKeyPress="caripegawai();return focusNext('urutan', event) " onFocus="panggil('nip')"/>
        <input type="hidden" name="nipkepsek" id="nipkepsek" size="10" value="<?=$nipkepsek ?>" />
        <input type="text"  class="disabled" name="nama" id="nama" size="25" readonly value="<?=$namakepsek ?>" onClick="caripegawai()"/> 
        <input type="hidden" name="namakepsek" id="namakepsek" value="<?=$namakepsek?>"/>
        &nbsp;
        <a href="JavaScript:caripegawai()"><img src="../images/ico/lihat.png" border="0" onMouseOver="showhint('Cari Pegawai!', this, event, '50px')"/></a>
    </td>
</tr>
<tr>
	<td><strong>Urutan</strong></td>
	<td>
    	<input type="text" name="urutan" id="urutan" size="3" maxlength="5" value="<?=$urutan ?>" onFocus="showhint('Urutan penampilan departemen', this, event, '120px');panggil('urutan')" onKeyPress="return focusNext('keterangan', event)"/>
    </td>
</tr>
<tr>
	<td valign="top">Keterangan</td>
	<td>
    	<textarea name="keterangan" id="keterangan" rows="3" cols="45" onKeyPress="return focusNext('Simpan', event)" onFocus="panggil('keterangan')"><?=$keterangan ?></textarea>
    </td>
</tr>
<tr>
	<td colspan="2" align="center">
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
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>

</body>
</html>