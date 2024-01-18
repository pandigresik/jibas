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
require_once('include/sessionchecker.php');
require_once('include/sessioninfo.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/theme.php');

OpenDb();
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$keterangan = "";
if (isset($_REQUEST['keterangan']))
	$keterangan = CQ($_REQUEST['keterangan']);

//Ini tuk ngecek user sudah punya login apa belum
$sql_cek = "SELECT * FROM jbsuser.login WHERE login = '".$_REQUEST['nip']."'";
$res_cek = QueryDb($sql_cek);
$jum_cek = @mysqli_num_rows($res_cek);

$query_cek2 = "SELECT * FROM jbsuser.hakakses WHERE login = '".$_REQUEST['nip']."' AND modul='KEUANGAN'";
$result_cek2 = QueryDb($query_cek2);
$num_cek2 = @mysqli_num_rows($result_cek2);
$row_cek2 = @mysqli_fetch_array($result_cek2);
$dd1 = "";
if($jum_cek == 0) {
	$dis = "";
	
} else {
	$status_user=$row_cek2['tingkat'];
	$keterangan=$row_cek2['keterangan'];
	$dis = "disabled='disabled' class='disabled' value='*******'";
	
	if ($status_user == 1) 
		$dd1 = "disabled";
}

/*if (isset($_REQUEST['simpan'])) {
	OpenDb();
	$sql="INSERT INTO jbsuser.login SET login = '".$_REQUEST['nip']."', password = '".md5($_REQUEST['pass1'])."', aktif = 1";
	$result=QueryDb($sql);
	$tingkat=(int)$_REQUEST['tingkat'];
	if ($tingkat == 2){
		$departemen=$_REQUEST['departemen'];
		$sql2="INSERT INTO jbsuser.hakakses SET login = '".$_REQUEST['nip']."', tingkat = '2', departemen = '$departemen', modul = 'KEUANGAN'";
		//$result2=QueryDb();
	} else {
		$sql2="INSERT INTO jbsuser.hakakses SET login = '".$_REQUEST['nip']."', tingkat = '$tingkat', modul = 'KEUANGAN', departemen = NULL";
		//$result2=QueryDb();
	}
	$result2=QueryDb($sql2);
	if ($result && $result2){
	?>
	<script language="javascript">
		opener.refresh();
		window.close();
	</script>
	<?php
	}
	}
*/	
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
	$query_c = "SELECT * FROM jbsuser.hakakses WHERE login = '".$_REQUEST['nip']."' AND tingkat = '$tingkat' AND modul = 'KEUANGAN' $sql_dep";
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
				
			$sql_hakakses="INSERT INTO jbsuser.hakakses SET login='".$_REQUEST['nip']."', tingkat=1, modul='KEUANGAN', keterangan='$keterangan'";
		} elseif ($tingkat==2){
			//Kalo staf
			if ($num_cek == 0) {
				$sql_login="INSERT INTO jbsuser.login SET login='".$_REQUEST['nip']."', password='$pass', aktif=1";
				QueryDbTrans($sql_login, $success);		
			}			
			
			$sql_hakakses="INSERT INTO jbsuser.hakakses SET login='".$_REQUEST['nip']."', departemen='$departemen', tingkat=2, modul='KEUANGAN', keterangan='$keterangan'";
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

$input_awal = "onload=\"document.getElementById('password').focus()\"";
if (isset($_REQUEST['status_user']) || $jum_cek > 0) {
	$input_awal = "onload=\"document.getElementById('status_user').focus()\"";
	$status_user = $_REQUEST['status_user'];
} 

if($status_user == 1 || $status_user == "") {
	$dd = "disabled";
	$fokus = "onKeyPress=\"return focusNext('keterangan', event)\"";
} else {
	$dd = "";
	$departemen=$row_cek2['departemen'];
	$fokus = "onKeyPress=\"return focusNext('departemen', event)\"";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Tambah Data Pengguna]</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function cek_form() {
	var nip = document.getElementById('nip').value;
	var dep = document.getElementById('departemen').value;
	var stat = document.getElementById('status_user').value;
	var pass = document.getElementById('password').value;
	var kon = document.getElementById('konfirmasi').value;
	var ket = document.getElementById('keterangan').value;
	
	if (nip.length == 0) {
		alert("User tidak boleh kosong");
		return false;
	}

	if (pass.length == 0) {
		alert("Password tidak boleh kosong!");
		document.getElementById('password').focus();
		return false;
	} else if (kon.length == 0) {
		alert("Konfirmasi tidak boleh kosong!");
		document.getElementById('konfirmasi').focus();
		return false;
	}
	
	if (pass != kon) {
		alert("Password dan konfirmasi harus sama!");
		document.getElementById('konfirmasi').focus();
		return false;
	}

	if (stat.length == 0) {
		alert("Tingkat tidak boleh kosong!");
		document.getElementById('status_user').focus();
		return false;
	}
	
	if (stat != 1) {
		if (dep.length==0) {
		alert("Departemen tidak boleh kosong!");
		document.getElementById('departemen').focus();
		return false;
		}
	}
	
	if (ket.length > 255) {
		alert("Keterangan tidak boleh lebih dari 255 karakter!");
		document.getElementById('keterangan').focus();
		return false;
	}
}


function caripegawai() {
	//page = 'caripegawai.php?flag=0';	
	page = 'library/pegawai.php?flag=0';	
	newWindow(page, 'CariPegawai','600','618','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(nip, nama, flag) {
	var dep = document.getElementById('departemen').value;
	document.location.href = "../user_add.php?nip="+nip+"&departemen="+dep+"&nama="+nama;	
}

/*function acceptCari(id, nama, flag) {
	document.getElementById('nama').value = nama;
	document.getElementById('nip').value = id;
}*/

function change_tingkat() {
	var tin = document.getElementById('status_user').value;
	var nip = document.getElementById('nip').value;
	var nama = document.getElementById('nama').value;
	var dep = document.getElementById('departemen').value;
	var pass = document.getElementById('password').value;
	var kon = document.getElementById('konfirmasi').value;
	var keterangan = document.getElementById('keterangan').value;
	
	if(tin == 1) {
		document.getElementById('departemen').disabled = true;
	} else {
		document.getElementById('departemen').disabled = false;
	}
	
	document.location.href ="user_add.php?nip="+nip+"&nama="+nama+"&departemen="+dep+"&status_user="+tin+"&password="+pass+"&konfirmasi="+kon+"&keterangan="+keterangan;
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
	var lain = new Array('password','konfirmasi','departemen','status_user','keterangan');
	var dis = document.main.password.disabled;
	
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#FFFF99';
		} else {
			if (dis) {
				document.getElementById(lain[0]).style.background='#c0c0c0';
				document.getElementById(lain[1]).style.background='#c0c0c0';
			}
			document.getElementById(lain[i]).style.background='#FFFFFF';
			
		}
	}
}

</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF" <?=$input_awal?>>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_02a.jpg">&nbsp;</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<form name="main" method="post" onSubmit="return cek_form();">    
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<tr height="25">
    <td colspan="3" class="header" align="center">Tambah Pengguna</td>
</tr>
<tr>
    <td width="20%"><strong>Login</strong></td>
    <td><input type="text" name="ni1" id="nip1" size="10" readonly="readonly" value="<?=$_REQUEST['nip'] ?>" onClick="caripegawai()" style="background-color:#CCCC99">&nbsp;
    <input type="text" name="nama1" id="nama1" size="30" readonly="readonly" value="<?=$_REQUEST['nama'] ?>" style="background-color:#CCCC99" onClick="caripegawai()">
	<input type="hidden" name="nip" id="nip" value="<?=$_REQUEST['nip']?>">
    <input type="hidden" name="nama" id="nama" value="<?=$_REQUEST['nama']?>">
    <a href="JavaScript:caripegawai()"><img src="images/ico/cari.png" border="0" onMouseOver="showhint('Cari pegawai',this, event, '100px')"/></a>
    </td>
</tr>
<tr>
    <td><strong>Password</strong></td>
    <td><input type="password" size="25" maxlength="100" name="password" <?=$dis ?> id="password" onKeyPress="return focusNext('konfirmasi', event)" onFocus="panggil('password')" value="<?=$_REQUEST['password']?>"></td>
    <!--<td><input type="password" name="pass1" id="pass1" size="20" /></td>-->
</tr>
<tr>
    <td><strong>Konfirmasi</strong></td>
    <td><input type="password" size="25" maxlength="100" name="konfirmasi" <?=$dis ?> id="konfirmasi" onKeyPress="return focusNext('status_user', event)" onFocus="panggil('konfirmasi')" value="<?=$_REQUEST['konfirmasi']?>" ></td>
    <!--<td><input type="password" name="pass2" id="pass2" size="20" /></td>-->
</tr>
<tr>
    <td><strong>Tingkat</strong></td>
    <td>
    <select name="status_user" id="status_user" style="width:165px" onChange="change_tingkat();" onFocus="panggil('status_user')" <?=$fokus.' '.$dd1?>>
    <!--<select name="tingkat" id="tingkat" onChange="change_tingkat()">-->
        <option value="1" <?=IntIsSelected($status_user, 1) ?> >Manajer Keuangan</option>
        <option value="2" <?=IntIsSelected($status_user, 2) ?> >Staf Keuangan</option>
    </select>
    </td>
</tr>
<tr>
    <td><strong>Departemen</strong></td>
    <td>
    <select name="departemen" style="width:165px;" id="departemen" <?=$dd ?> onKeyPress="return focusNext('keterangan', event)" onFocus="panggil('departemen')">
    <!--<select name="departemen" id="departemen" disabled="disabled" >-->
    <?php  if ($status_user == 1 || $status_user == ""){	
    		echo  "<option value='' selected='selected'>Semua</option>";
    	}
		OpenDb();
		$query_pro = "SELECT departemen FROM jbsakad.departemen WHERE aktif=1 ORDER BY urutan ASC";
		$result_pro = QueryDb($query_pro);
	
		$i = 0;
		while($row_pro = @mysqli_fetch_array($result_pro)) {
			if($departemen == "") {
				$departemen = $row_pro['departemen'];
				if ($status_user == 1 || $status_user == "")
					$sel[$i] = "";
				else
					$sel[$i] = "selected";
			} elseif ($departemen == $row_pro['departemen']) {
				if ($status_user == 1 || $status_user == "")
					$sel[$i] = "";
				else
					$sel[$i] = "selected";
			} else {
				$sel[$i] = "";
			}
			echo  "<option value='".$row_pro['departemen']."' $sel[$i]>".$row_pro['departemen'];
			$i++;
		}
	?>
    	</option></select></td>
</tr>
<tr>
    <td valign="top">Keterangan</td>
    <td><textarea name="keterangan" id="keterangan" rows="3" cols="47" onFocus="panggil('keterangan')" onKeyPress="return focusNext('simpan', event)"><?=$keterangan?></textarea></td>
</tr>
<tr>
    <td colspan="2" align="center">
        <input class="but" type="submit" value="Simpan" name="simpan" id="simpan" onFocus="panggil('simpan')">
        &nbsp;<input class="but" type="button" value="Tutup" onClick="window.close();">
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