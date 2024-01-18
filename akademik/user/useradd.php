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
require_once("../include/theme.php");
require_once('../include/errorhandler.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../cek.php');
if (isset($_REQUEST['simpan'])) {
	OpenDb();
	$tingkat = $_REQUEST['status_user'];
	$pass=md5((string) $_REQUEST['password']);
	
	$sql_dep = "AND departemen = '".$_REQUEST['departemen']."'";
	if ($_REQUEST['status_user'] == "" || $_REQUEST['status_user'] == 1) {
		$tingkat = 1;
		$sql_dep = "";	
	}	
	
  	//cek apakah sudah ada account yang sama di SIMAKA
	$query_c = "SELECT * FROM jbsuser.hakakses WHERE login = '".$_REQUEST['nip']."' AND tingkat = $tingkat AND modul = 'SIMAKA' $sql_dep";
	$result_c = QueryDb($query_c);
    $num_c = @mysqli_num_rows($result_c);
	
	$query_cek = "SELECT * FROM jbsuser.login WHERE login = '".$_REQUEST['nip']."'";
	$result_cek = QueryDb($query_cek);
    $num_cek = @mysqli_num_rows($result_cek);
	
	
		
	BeginTrans();
	$success=1;	
	
	if ($num_c == 0) {
		if ($tingkat==1){
			//Kalo manajer
			if ($num_cek == 0) {
				$sql_login="INSERT INTO jbsuser.login SET login='".$_REQUEST['nip']."', password='$pass', aktif=1";
				QueryDbTrans($sql_login, $success);		
			}		
				
			$sql_hakakses="INSERT INTO jbsuser.hakakses SET login='".$_REQUEST['nip']."', tingkat=1, modul='SIMAKA', keterangan='".CQ($_REQUEST['keterangan'])."'";
		} elseif ($tingkat==2){
			//Kalo staf
			if ($num_cek == 0) {
				$sql_login="INSERT INTO jbsuser.login SET login='".$_REQUEST['nip']."', password='$pass', aktif=1";
				QueryDbTrans($sql_login, $success);		
			}			
			
			$sql_hakakses="INSERT INTO jbsuser.hakakses SET login='".$_REQUEST['nip']."', departemen='".$_REQUEST['departemen']."', tingkat=2, modul='SIMAKA', keterangan='".CQ($_REQUEST['keterangan'])."'";
		}
		if ($success)	
			QueryDbTrans($sql_hakakses, $success);
	}
	
	
	
	if ($success){	
		CommitTrans();
		?>
		<script language="javascript">
			parent.opener.refresh();
			window.close();
		</script>
		<?php
	} else {
		RollbackTrans();
		CloseDb();
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS [SIMAKA] Add User</title>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">
function caripegawai() {
	//newWindow('../library/caripegawai.php?flag=0', 'CariPegawai','600','565','resizable=1,scrollbars=1,status=0,toolbar=0');
	newWindow('../library/pegawai.php?flag=0','CariPegawai','600','618','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(nip,nama) {
	//var dep = document.tambah_user.departemen.value;
	//document.location.href = "../user/user_add.php?nip="+nip+"&departemen="+dep+"&nama="+nama;	
	document.getElementById('nip').value=nip;
	document.getElementById('nip1').value=nip;
	document.getElementById('nama').value=nama;
	document.getElementById('nama1').value=nama;
	pwd_check(nip);
}

function pwd_check(nip){
	//alert (nip);
	show_wait('passInfo');
	sendRequestText("getpwd.php", ShowPwd, "nip="+nip);
	//alert ('sss');
}
function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}
function ShowPwd(x){
	//alert ('sss');
	document.getElementById("passInfo").innerHTML = x;
}
function change_tingkat() {
	var tkt = document.getElementById('status_user').value;
	show_wait('tktInfo');
	sendRequestText("getdep.php", ShowTkt, "tingkat="+tkt);
}
function ShowTkt(x){
	//alert ('sss');
	document.getElementById("tktInfo").innerHTML = x;
}
function cek_form()
{
	/*alert ('Masuk');
	*/
	var haspwd = document.getElementById('haspwd').value;
	if (haspwd==0){
		var password = document.getElementById('password').value;
		var konfirmasi = document.getElementById('konfirmasi').value;
	}
	var nip = document.getElementById('nip').value;
	var status_user = document.getElementById('status_user').value;
	var departemen = document.getElementById('departemen').value;
	var keterangan = document.getElementById('keterangan').value;
	
	if (nip.length==0){
		alert ('NIP tidak boleh kosong!');
		return false;
	}
	if (haspwd==0){
		if (password.length==0){
			alert ('Password tidak boleh kosong!');
			document.getElementById('password').focus();
			return false;
		}
		if (konfirmasi.length==0){
			alert ('Konfirmasi Password tidak boleh kosong!');
			document.getElementById('konfirmasi').focus();
			return false;
		}
		if (konfirmasi!=password){
			alert ('Password dan Konfirmasi Password harus sesuai!');
			document.getElementById('konfirmasi').value="";
			document.getElementById('konfirmasi').focus();
			return false;
		}
	}
	return true;
	
}
</script>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4">
<div id="waitBox" style="position:absolute; visibility:hidden;">
	<img src="../images/movewait_2.gif" border="0" />Silahkan&nbsp;tunggu...
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Tambah Pengguna :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<form action="useradd.php" method="post" name="tambah_user" onSubmit="return cek_form()">
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
    <td width="80"><strong>Login</strong></td>
    <td width="1025"><input type="text" size="10" name="nip1" id="nip1" readonly value="<?=$_REQUEST['nip'] ?>" class="disabled" onClick="caripegawai()">&nbsp;<input type="text" size="30" name="nama1" id="nama1" readonly value="<?=$_REQUEST['nama']?>" class="disabled" onClick="caripegawai()">
    	<input type="hidden" name="nip" id="nip" value="<?=$_REQUEST['nip']?>">
        <input type="hidden" name="nama" id="nama" value="<?=$_REQUEST['nama']?>"><a href="#" onClick="caripegawai()"><img src="../images/ico/cari.png" border="0" onMouseOver="showhint('Cari pegawai',this, event, '100px')"></a>
    </td>
</tr>
<tr>
	<td colspan="2">
		<div id="passInfo">
        	<input type="hidden" id="haspwd" value="1" />
        </div>
	</td>
</tr>
<tr>
	<td width="80"><strong>Tingkat</strong></td>
    <td><select name="status_user" id="status_user" style="width:165px" onChange="change_tingkat();" onFocus="panggil('status_user')" <?=$fokus.' '.$dd1?>>
            <option value="1">Manajer Akademik</option>
            <option value="2">Staf Akademik</option>
    	</select>
	</td>
</tr>
<tr>
    <td width="80"><strong>Departemen</strong></td>
    <td>
    	<div id="tktInfo">
    	<select name="departemen" id="departemen" style="width:165px;" OnKeyPress="return focusNext('keterangan', event)" onFocus="panggil('tt')">
    		<option value='' selected='selected'>Semua</option>
    	</select>
    	</div>
    </td>
</tr>
<tr>
    <td width="80" valign="top">Keterangan</td>
    <td><textarea wrap="soft" name="keterangan" id="keterangan" cols="47" rows="3" onFocus="panggil('keterangan')" onKeyPress="return focusNext('simpan', event)"><?=$keterangan?></textarea></td>
</tr>
<tr>
    <td colspan="2" align="center">
   		<input type="submit" value="Simpan" name="simpan" id="simpan" class="but" onFocus="panggil('simpan')">&nbsp;
        <input type="button" value="Tutup" name="batal" class="but" onClick="window.close();">
    </td>
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
</body>
</html>