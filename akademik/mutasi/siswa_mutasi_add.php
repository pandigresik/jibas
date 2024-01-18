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
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

if (isset($_REQUEST['nis']))
	$nis=$_REQUEST['nis'];

$tanggal =date('d')."-".date('n')."-".date('Y');
if (isset($_REQUEST['tanggal']))
	$tanggal=$_REQUEST['tanggal'];
	
$mutasi ="";
if (isset($_REQUEST['mutasi']))
	$mutasi=CQ($_REQUEST['mutasi']);
	
$keterangan ="";
if (isset($_REQUEST['keterangan']))
	$keterangan=CQ($_REQUEST['keterangan']);
	
OpenDb();
$sql = "SELECT s.nama,a.angkatan,t.tingkat,k.kelas,t.departemen,k.idtingkat,s.idkelas FROM siswa s, angkatan a, tingkat t, kelas k WHERE s.nis = '$nis' AND t.replid = k.idtingkat AND k.replid = s.idkelas AND s.idangkatan = a.replid"; 

$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$nama = $row[0];
$angkatan = $row[1];
$tingkat = $row[2];
$kelas = $row[3];
$departemen = $row[4];
$idtingkat = $row[5];
$idkelas = $row[6];

if (isset($_REQUEST['Simpan'])) {
	$tanggal=$_REQUEST["tanggal"];
	$tglmutasi=TglDb($tanggal);
	
	OpenDb();
	BeginTrans();
	$success=0;
	$sql="UPDATE jbsakad.siswa SET aktif=0, statusmutasi='$mutasi', alumni=1 WHERE nis='$nis'";
	QueryDbTrans($sql, $success);
		
	if ($success){
		$sql="UPDATE jbsakad.riwayatkelassiswa SET aktif=0 WHERE nis='$nis' AND aktif=1";
		QueryDbTrans($sql,$success);
	}

	if ($success) {
		$sql="UPDATE jbsakad.riwayatdeptsiswa SET aktif=0 WHERE nis='$nis' AND aktif=1";
		QueryDbTrans($sql,$success);
	}
	
	if ($success){
		$sql="INSERT INTO jbsakad.alumni SET nis='$nis', tgllulus='$tglmutasi', tktakhir='$idtingkat', klsakhir='$idkelas', departemen = '".$departemen."'";
		QueryDbTrans($sql,$success);
	}
	
	if ($success){
		$sql="INSERT INTO jbsakad.mutasisiswa SET nis='$nis', jenismutasi='$mutasi', tglmutasi='$tglmutasi', keterangan='$keterangan', departemen = '".$departemen."'";
		QueryDbTrans($sql, $success);
		//echo $sql; 
		//RollBackTrans();
		//exit;
	};
	
	if ($success){
		CommitTrans();
	?>	
	<script type="text/javascript" language="javascript">
		parent.opener.refresh_daftar();
		window.close();
	</script>
	<?php
	} else {
		RollBackTrans();
		?>	
		<script type="text/javascript" language="javascript">
            alert ('Maaf, gagal memutasikan siswa!');
			parent.opener.refresh_daftar();
            window.close();
        </script>
        <?php
	}
	CloseDb();
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<SCRIPT type="text/javascript" language="text/javascript" src="../script/tables.js"></SCRIPT>
<SCRIPT type="text/javascript" language="javascript" src="../script/common.js"></script>
<SCRIPT type="text/javascript" language="javascript" src="../script/tools.js"></script>
<link rel="stylesheet" type="text/css" href="../style/calendar-system.css">
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/validasi.js"></script>
<script type="text/javascript" src="../script/calendar.js"></script>
<script type="text/javascript" src="../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../script/calendar-setup.js"></script>
<link href="../style/style.css" rel="stylesheet" type="text/css">
<title>JIBAS SIMAKA [Tambah Mutasi Siswa]</title>
<SCRIPT type="text/javascript" language="javascript">
function validate(){
	return	validateEmptyText('nis', 'NIS Siswa') &&
			validateEmptyText('tanggal', 'Tanggal Mutasi') &&	
			validateEmptyText('mutasi', 'Jenis Mutasi') &&	
			validateMaxText('keterangan', 255, 'Keterangan');
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
}</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('mutasi').focus()">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Mutasi Siswa :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<form name="simpan_mutasi" id="simpan_mutasi" method="post" onSubmit="return validate();">
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="25%"><strong>NIS</strong></td>
    <td colspan="2"><input name="nis1" type="text" class="disabled" id="nis1" value="<?=$nis?>" size="15" readonly>
        <input name="nis" type="hidden" id="nis" value="<?=$nis?>">
    </td>
</tr>
<tr>
   	<td><strong>Nama</strong></td>
   	<td colspan="2"><input name="nama" type="text" class="disabled" id="nama" value="<?=$nama?>" size="30" readonly></td>
</tr>
<tr>
   	<td><strong>Departemen</strong></td>
   	<td colspan="2"><input name="departemen" type="text" class="disabled" id="departemen" value="<?=$departemen?>" size="30" readonly>		
   </td>
</tr>
<tr>
   	<td><strong>Angkatan</strong></td>
   	<td colspan="2"><input name="angkatan" type="text" class="disabled" id="angkatan" value="<?=$angkatan?>" size="30" readonly>		
   </td>
</tr>
<tr>
   	<td><strong>Kelas</strong></td>
   	<td colspan="2"><input name="kelas" type="text" class="disabled" id="kelas" value="<?=$tingkat.' - '.$kelas?>" readonly size="15">
   	</td>
</tr>
<tr>
	<td><strong>Tgl Mutasi</strong></td>
	<td><input name="tanggal" type="text" class="disabled" id="tanggal" readonly="readonly" onClick="Calendar.setup()" onKeyPress="return focusNext('mutasi', event)" size="15" value="<?=$tanggal?>">
		<img src="../images/calendar.jpg" name="tabel" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/></td>
</tr>
<tr>
 	<td><strong>Jenis Mutasi </strong></td>
	<td colspan="2"><select name="mutasi" id="mutasi" onKeyPress="return focusNext('keterangan', event)">
	<?php  OpenDb();
		$sql = "SELECT * FROM jbsakad.jenismutasi ORDER BY replid";
       	$result = QueryDb($sql);
    	while($row = mysqli_fetch_array($result)) {
    		if ($mutasi == "")
   				 $mutasi = $row['replid'];
    ?>
		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $mutasi) ?>>
		<?=$row['jenismutasi']?>
		</option>
	<?php
	} //while
	CloseDb();
	?>
		</select>                         
	</td>
</tr>
<tr>
	<td valign="top">Keterangan</td>
	<td colspan="2"><textarea name="keterangan" cols="30" rows="5" id="keterangan" onKeyPress="return focusNext('Simpan', event)"></textarea></td>
</tr>
<tr>                   
	<td colspan="3"><div align="center">
  	<input name="Simpan" id="Simpan" type="Submit" class="but" value="Simpan">&nbsp;
  	<input name="Tutup" type="button" class="but" value="Tutup" onClick="window.close()">
 	</div></td>
</tr>
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
</body>
</html>
<script type="text/javascript">
Calendar.setup(
{
inputField  : "tanggal",         // ID of the input field
ifFormat    : "%d-%m-%Y",    // the date format
button      : "btntanggal"       // ID of the button
}
);
Calendar.setup(
{
inputField  : "tanggal",         // ID of the input field
ifFormat    : "%d-%m-%Y",    // the date format
button      : "tanggal"       // ID of the button
}
);
var spryselect11 = new Spry.Widget.ValidationSelect("mutasi");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
</script>