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


$nis=$_REQUEST['nis'];
$idkelas=$_REQUEST['idkelas'];

if (isset($_REQUEST['Simpan'])){
	OpenDb();
	$sql_simpan="UPDATE jbsakad.riwayatkelassiswa SET keterangan='".CQ($_REQUEST['ket'])."' WHERE nis='$nis' AND idkelas='$idkelas'";
	$result_simpan=QueryDb($sql_simpan);
	CloseDb();
	if ($result_simpan) { 	
	?>
   		<script language="javascript">
			opener.refresh();
			window.close();
		</script> 
    <?php
	}		
}	

	
OpenDb();
$sql_ket="SELECT r.keterangan,k.kelas,s.nama FROM jbsakad.riwayatkelassiswa r, jbsakad.kelas k,  jbsakad.siswa s WHERE r.nis=s.nis AND r.idkelas=k.replid AND k.replid = '$idkelas' AND s.nis='$nis'";
$result_ket=QueryDb($sql_ket);
$row_ket=@mysqli_fetch_array($result_ket);
$nama = $row_ket['nama'];
$kelas = $row_ket['kelas'];
$ket = $row_ket['keterangan'];

CloseDb();	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<title>JIBAS SIMAKA [Ubah Keterangan Riwayat kelas]</title>
<script src="../script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function validate() {
	return validateEmptyText('idnis', 'NIS') && 
		   validateEmptyText('idkelas', 'idkelas') && 
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
}

</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('ket').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Keterangan Riwayat Kelas :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->

<form action="#" method="post" onSubmit="return validate()" > 
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT 
<tr height="25">
	<td class="header" colspan="2"><div align="center">Ubah Keterangan Riwayat Kelas</div></td>
</tr>-->
<tr>	
	<td width="120"><strong>NIS</strong></td>
    <td><input type="text" name="nis" id="nis" readonly="readonly" value="<?=$nis?>" class="disabled" size="20" readonly="readonly"/><input type="hidden" name="idnis" id="idnis" value="<?=$nis?>" />
    </td>
</tr>
<tr>
    <td><strong>Nama</strong></td>
    <td><input type="text" name="nama" id="nama" readonly="readonly" value="<?=$nama?>" size="30" class="disabled"/></td>
  </tr>
<tr>
    <td><strong>Kelas</strong></td>
    <td><input type="text" name="kelas" id="kelas" readonly="readonly" value="<?=$kelas?>" size="30" class="disabled"/><input type="hidden" name="idkelas" id="idkelas" value="<?=$idkelas?>" /></td>
</tr>
<tr>
    <td valign="top"><strong>Keterangan</strong></td>
    <td><textarea name="ket" id="ket" rows="3" cols="35" onKeyPress="return focusNext('simpan', event)"><?=$row_ket[0]?></textarea></td>
</tr>
<tr>
    <td colspan="2" align="center">
    <input type="submit" name="Simpan" id="simpan" value="Simpan" class="but" />&nbsp;&nbsp;
    <input type="button" name="Tutup" value="Tutup" class="but" onClick="window.close()" />
    </td>
</tr>
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
</body>
</html>
<script language="javascript">
	var sprytextarea1 = new Spry.Widget.ValidationTextarea("ket");
</script>