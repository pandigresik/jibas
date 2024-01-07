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

$replid = $_REQUEST['replid'];

OpenDb();
/*$sql_get_namatahunajaran = "SELECT tahunajaran FROM jbsakad.tahunajaran WHERE replid = '".$tahunajaran."'";
$result_get_namatahunajaran = QueryDb($sql_get_namatahunajaran);
$row_get_namatahunajaran =@mysqli_fetch_array($result_get_namatahunajaran);

$sql_get_namatingkat = "SELECT tingkat, departemen FROM jbsakad.tingkat WHERE replid = '".$tingkat."'";
$result_get_namatingkat = QueryDb($sql_get_namatingkat);
$row_get_namatingkat =@mysqli_fetch_array($result_get_namatingkat);
*/
$sql_get_kelas = "SELECT k.kelas, k.kapasitas, k.keterangan, a.tahunajaran, a.departemen, t.tingkat, k.nipwali, k.idtahunajaran, k.idtingkat FROM jbsakad.kelas k, tahunajaran a, tingkat t WHERE k.replid = '$replid' AND k.idtingkat = t.replid AND k.idtahunajaran = a.replid";
$result_get_kelas = QueryDb($sql_get_kelas);
$row_get_kelas =@mysqli_fetch_array($result_get_kelas);
$departemen = $row_get_kelas['departemen'];
$idtahunajaran = $row_get_kelas['idtahunajaran'];
$idtingkat = $row_get_kelas['idtingkat'];
$tahunajaran = $row_get_kelas['tahunajaran'];
$tingkat = $row_get_kelas['tingkat'];
$kelas = $row_get_kelas['kelas'];
$kapasitas = $row_get_kelas['kapasitas'];
$keterangan = $row_get_kelas['keterangan'];

if (isset($_REQUEST['kelas']))
	$kelas = CQ($_REQUEST['kelas']);
if (isset($_REQUEST['kapasitas']))
	$kapasitas = $_REQUEST['kapasitas'];
if (isset($_REQUEST['keterangan']))
	$keterangan = CQ($_REQUEST['keterangan']);

$sql_get_pegawai = "SELECT * FROM jbssdm.pegawai WHERE nip = '".$row_get_kelas['nipwali']."'";
$result_get_pegawai = QueryDb($sql_get_pegawai);
$row_get_pegawai =@mysqli_fetch_array($result_get_pegawai);
$nipwali = $row_get_pegawai['nip'];
$namawali = $row_get_pegawai['nama'];

if (isset($_REQUEST['nipwali']))
	$nipwali = $_REQUEST['nipwali'];
if (isset($_REQUEST['namawali']))
	$namawali = $_REQUEST['namawali'];

$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) {
	$sql_cek = "SELECT * FROM kelas WHERE kelas = '$kelas' AND idtahunajaran = '$idtahunajaran' AND idtingkat = '$idtingkat' AND replid <>  '$replid'";
	$result_cek = QueryDb($sql_cek);
	if (@mysqli_num_rows($result_cek) > 0) {
		CloseDb();
		$ERROR_MSG = "Kelas ".$kelas." sudah digunakan!";
	} else {
		$sql = "UPDATE kelas SET kelas='$kelas',kapasitas='".$_REQUEST['kapasitas']."', nipwali='".trim($_REQUEST['nipwali'])."', keterangan='$keterangan' WHERE replid= '$replid'";
		
		$result = QueryDb($sql);
		if ($result) { ?>
			<script language="javascript">
			opener.refresh();
			window.close();
			</script> 
<?php 	}
	}
} 
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Ubah Kelas]</title>
<script language="JavaScript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">
function caripegawai() {
	newWindow('../library/pegawai.php?flag=0&bagian=Akademik', 'CariPegawai','600','618','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(nip, nama, flag) {
	document.getElementById('nipwali').value = nip;
	document.getElementById('nip').value = nip;
	document.getElementById('namawali').value = nama;
	document.getElementById('nama').value = nama;
	document.getElementById('kapasitas').focus();
}

function tutup() {
	document.getElementById('kapasitas').focus();
}

function validate() {
	return validateEmptyText('kelas', 'Nama Kelas') &&  
		   validateEmptyText('nipwali', 'NIP dan Nama Wali Kelas') &&
		   validateEmptyText('kapasitas', 'Kapasitas') &&
		   validateNumber('kapasitas', 'Kapasitas Kelas') &&
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
	var lain = new Array('kelas','kapasitas','keterangan');
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('kelas').focus()">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Kelas :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="300">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<form name="main" onSubmit="return validate()" action="kelas_edit.php">
<input type="hidden" name="replid" id="replid" value="<?=$replid?>"/>
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="120"><strong>Departemen</strong></td>
    <td><input type="text" size="10" readonly value="<?=$departemen?>" class="disabled"/>
    </td>
</tr>
<tr>
	<td><strong>Tingkat</strong></td>
    <td><input type="text" size="10" readonly value="<?=$tingkat?>" class="disabled"/>
    </td>
</tr>
<tr>
	<td><strong>Tahun Ajaran</strong></td>
    <td><input type="text" size="30" readonly value="<?=$tahunajaran?>" class="disabled"/></td>
</tr>
<tr>
	<td ><strong>Kelas</strong></td>
    <td><input type="text" name="kelas" id="kelas" size="10" onFocus="showhint('Kelas tidak boleh kosong !', this, event, '120px');panggil('kelas')" value="<?=$kelas?>" onKeyPress="return focusNext('nip', event)"/></td>
</tr>
<tr>
    <td><strong>Wali Kelas</strong></td>
    <td>
    <input type="text" class="disabled" size="10" name="nip" id="nip" readonly value="<?=$nipwali?>" onClick="caripegawai()"/><input type="hidden" name="nipwali" id="nipwali" value="<?=$nipwali?>"/>
    <input type="text" class="disabled" name="nama" id="nama" size="25" readonly value="<?=$namawali?>" onClick="caripegawai()"/><input type="hidden" name="namawali" id="namawali" value="<?=$namawali?>"/>&nbsp;
    <a href="JavaScript:caripegawai()"><img src="../images/ico/lihat.png" border="0" onMouseOver="showhint('Cari Pegawai!', this, event, '50px')"/></a></td>
</tr>

<tr>
	<td><strong>Kapasitas</strong></td>
	<td>
    	<input type="text" name="kapasitas" id="kapasitas" size="5" maxlength="3" onFocus="showhint('Kapasitas tidak boleh lebih dari 255 !', this, event, '120px');panggil('kapasitas')" value="<?=$kapasitas?>"  onKeyPress="return focusNext('keterangan', event)"/></td>
</tr>
<tr>
	<td valign="top">Keterangan</td>
	<td>
    	<textarea name="keterangan" id="keterangan" rows="3" cols="45"  onKeyPress="return focusNext('Simpan', event)" onFocus="panggil('keterangan')"><?=$keterangan?></textarea>    </td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but"  onFocus="panggil('Simpan')"/>&nbsp;
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />    </td>
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