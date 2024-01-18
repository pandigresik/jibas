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
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
if (isset($_REQUEST['simpan'])) {
	OpenDb();
	$tingkat = $_REQUEST['status_user'];
	$pass=md5((string) $_REQUEST['password']);
	
	
  	//cek apakah sudah ada account yang sama di SIMAKA
	$query_c = "SELECT * FROM $db_name_user.hakakses WHERE login = '".$_REQUEST['nip']."' AND tingkat = 1 AND modul = 'EMA'";
	$result_c = QueryDb($query_c);
    $num_c = @mysqli_num_rows($result_c);
	
	$query_cek = "SELECT * FROM $db_name_user.login WHERE login = '".$_REQUEST['nip']."'";
	$result_cek = QueryDb($query_cek);
    $num_cek = @mysqli_num_rows($result_cek);
	
	
		
	BeginTrans();
	$success=1;	
	
	if ($num_c == 0) {
		
			if ($num_cek == 0) {
				$sql_login="INSERT INTO $db_name_user.login SET login='".$_REQUEST['nip']."', password='$pass', aktif=1";
				QueryDbTrans($sql_login, $success);		
			}		
				
			$sql_hakakses="INSERT INTO $db_name_user.hakakses SET login='".$_REQUEST['nip']."', tingkat=1, modul='EMA', keterangan='".$_REQUEST['keterangan']."'";
		
		if ($success)	
			QueryDbTrans($sql_hakakses, $success);
	}
	
	
	
	if ($success){	
		CommitTrans();
		?>
		<script language="javascript">
			//alert ('Udah');
			parent.opener.fresh();
			window.close();
		</script>
		<?php
	} else {
		RollbackTrans();
		?>
		<script language="javascript">
			//alert ('Lom');
			alert ('Gagal menambah pengguna');
			parent.opener.fresh();
			window.close();
		</script>
		<?php
		CloseDb();
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Add User]</title>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">
function caripegawai() {
	//newWindow('../library/caripegawai.php?flag=0', 'CariPegawai','600','565','resizable=1,scrollbars=1,status=0,toolbar=0');
	newWindow('../lib/pegawai.php?flag=0','CariPegawai','600','618','resizable=1,scrollbars=1,status=0,toolbar=0');
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF">
<div id="waitBox" style="position:absolute; visibility:hidden;">
	<img src="../img/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>
<form action="tambahpengguna.php" method="post" name="tambah_user" onSubmit="return cek_form()">
<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr height="25">
	<td height="25" colspan="3" class="header" align="center">Tambah Pengguna</td>
</tr>
<tr>
    <td width="80" class="news_content1"><strong>Login</strong></td>
    <td width="1025"><input type="text" size="10" name="nip1" id="nip1" readonly value="<?=$_REQUEST['nip'] ?>" class="inputtxt" onClick="caripegawai()">&nbsp;<input type="text" size="30" name="nama1" id="nama1" readonly value="<?=$_REQUEST['nama']?>" class="inputtxt" onClick="caripegawai()">
    	<input type="hidden" name="nip" id="nip" value="<?=$_REQUEST['nip']?>">
        <input type="hidden" name="nama" id="nama" value="<?=$_REQUEST['nama']?>"><a href="#" onClick="caripegawai()"><img src="../img/cari.png" border="0" onMouseOver="showhint('Cari pegawai',this, event, '100px')"></a>    </td>
</tr>
<tr>
	<td colspan="2">
		<div id="passInfo">
        	<input type="hidden" id="haspwd" value="1" />
        </div>	</td>
</tr>
<tr>
    <td width="80" valign="top" class="news_content1">Keterangan</td>
    <td><textarea name="keterangan" cols="47" rows="3" wrap="soft" class="areatxt2" id="keterangan" onFocus="panggil('keterangan')" onKeyPress="return focusNext('simpan', event)"><?=$keterangan?></textarea></td>
</tr>
<tr>
    <td colspan="2" align="center">
   		<input type="submit" value="Simpan" name="simpan" id="simpan" class="cmbfrm2" onFocus="panggil('simpan')">&nbsp;
        <input type="button" value="Tutup" name="batal" class="cmbfrm2" onClick="window.close();">    </td>
</tr>
</table>
</form>
</body>
</html>