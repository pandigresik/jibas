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
require_once('../include/sessionchecker.php');
require_once("../include/theme.php");
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');

OpenDb();

if(isset($_REQUEST["replid"]))
	$replid = $_REQUEST["replid"];

$sql="SELECT h.login, h.tingkat, h.departemen, h.keterangan, p.nama
		FROM jbsuser.hakakses h, jbssdm.pegawai p, jbsuser.login l
	    WHERE h.modul='SIMPEG' AND h.login = l.login AND l.login = p.nip AND h.replid=$replid ";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$nip = $row['login'];
$nama = $row['nama'];
$departemen = $row['departemen'];
$status_user = $row['tingkat'];
$keterangan = $row['keterangan'];

//$dd1 = "";
/*if ($status_user == 1) 
	$dd1 = "disabled";*/

if(isset($_REQUEST["nip"]))
	$nip = $_REQUEST["nip"];
if(isset($_REQUEST["nama"]))
	$nama = $_REQUEST["nama"];
if(isset($_REQUEST["departemen"]))
	$departemen = $_REQUEST["departemen"];
if(isset($_REQUEST["status_user"]))
	$status_user = $_REQUEST["status_user"];
if(isset($_REQUEST["keterangan"]))
	$keterangan = CQ($_REQUEST["keterangan"]);
	
if (isset($_REQUEST['simpan']))
{
	OpenDb();
	$tingkat=$_REQUEST['status_user'];
		
	$sql = "SELECT *
			  FROM jbsuser.hakakses
			 WHERE login = '".$_REQUEST['nip']."' AND tingkat = $tingkat AND modul = 'SIMPEG' AND replid <> $replid";
	
	$result=QueryDb($sql);

	if (mysqli_num_rows($result) > 0)
	{
		CloseDb();
		$ERROR_MSG = "Pengguna ".$_REQUEST['nip']." sudah mempunyai account untuk tingkat dan departemen ini!";
	}
	else
	{
		if ($tingkat==1)
		{
			$sql_hakakses="UPDATE jbsuser.hakakses SET tingkat=1, keterangan ='$keterangan' WHERE replid = $replid";
		}
		elseif ($tingkat==2)
		{
			$sql_hakakses="UPDATE jbsuser.hakakses SET tingkat=2, keterangan ='$keterangan' WHERE replid = $replid";
		}
		
		$result = QueryDb($sql_hakakses);
		CloseDb();
		if ($result){	
		?>
		<script language="javascript">
			parent.opener.refresh();
			window.close();
		</script>
		<?php
		} 
	}
}

$input_awal = "onload=\"document.getElementById('status_user').focus()\"";
if (isset($_REQUEST['status_user'])) {
	$input_awal = "onload=\"document.getElementById('status_user').focus()\"";
	$status_user = $_REQUEST['status_user'];
} 
	
if($status_user == 1 || $status_user == "") {
	$dd = "disabled";
	$fokus = "onKeyPress=\"return focusNext('keterangan', event)\"";
} else {
	$dd = "";
	$fokus = "onKeyPress=\"return focusNext('tt', event)\"";
}

?>

<html>
<head>
<title>JIBAS Kepegawaian [Ubah Pengguna]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript">

function cek_form() {
	var nip = document.tambah_user.nip.value;
	var dep = document.tambah_user.departemen.value;
	var stat = document.tambah_user.status_user.value;
	//var pass = document.tambah_user.password.value;
	//var kon = document.tambah_user.konfirmasi.value;
	var ket = document.tambah_user.keterangan.value;
		
	if (nip.length == 0) {
		alert("User tidak boleh kosong");
		return false;
	}

	/*if (pass.length == 0) {
		alert("Password tidak boleh kosong!");
		document.tambah_user.password.focus();
		return false;
	} else if (kon.length == 0) {
		alert("Konfirmasi tidak boleh kosong!");
		document.tambah_user.konfirmasi.focus();
		return false;
	}
	
	if (pass != kon) {
		alert("Password dan konfirmasi harus sama!");
		document.tambah_user.konfirmasi.focus();
		return false;
	}*/

	if (stat.length == 0) {
		alert("Tingkat tidak boleh kosong!");
		document.tambah_user.status_user.focus();
		return false;
	}
	
	if (stat != 1) {
		if (dep.length==0) {
		alert("Departemen tidak boleh kosong!");
		document.tambah_user.departemen.focus();
		return false;
		}
	}
	
	if (ket.length > 255) {
		alert("Keterangan tidak boleh lebih dari 255 karakter!");
		document.tambah_user.keterangan.focus();
		return false;
	}
}

function change_tingkat() {
	var tin = document.tambah_user.status_user.value;
	var nip = document.tambah_user.nip.value;
	var nama = document.tambah_user.nama.value;
	var dep = document.tambah_user.departemen.value;
	/*var pass = document.tambah_user.password.value;
	var kon = document.tambah_user.konfirmasi.value;*/
	var keterangan = document.tambah_user.keterangan.value;
	var replid = document.tambah_user.replid.value;
	
	if(tin == 1) {
		document.tambah_user.tt.disabled = true;
	} else {
		document.tambah_user.tt.disabled = false;
	}
	
	document.location.href ="user_edit.php?nip="+nip+"&nama="+nama+"&status_user="+tin+"&keterangan="+keterangan+"&replid="+replid;
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
	var lain = new Array('tt','status_user','keterangan');
	
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" <?=$input_awal?>>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Pengguna :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<form action="user_edit.php" method="post" name="tambah_user" onSubmit="return cek_form()">
<input type="hidden" name="replid" id="replid" readonly value="<?=$replid?>">
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
    <td width="20%"><strong>Login</strong></td>
    <td><input type="text" size="10" name="nip1" readonly value="<?=$nip ?>" class="disabled">&nbsp;<input type="text" size="32" name="nama1" readonly value="<?=$nama?>" class="disabled">
    	<input type="hidden" name="nip" id="nip" value="<?=$nip?>">
        <input type="hidden" name="nama" id="nama" value="<?=$nama?>">
        <!--<a href="#" onClick="caripegawai()"><img src="../images/ico/cari.png" border="0" onMouseOver="showhint('Cari pegawai',this, event, '100px')"></a>-->
    </td>
</tr>
<!--<tr>
    <td><strong>Password</strong></td>
    <td><input type="text" size="25" name="password" readonly class='disabled' value='********' id="dis"></td>
</tr>
<tr>
    <td><strong>Konfirmasi</strong></td>
    <td><input type="text" size="25" name="konfirmasi" readonly class='disabled' value='********' id="dis" ></td>
</tr>--->
<tr>
	<td><strong>Tingkat</strong></td>
    <td><select name="status_user" id="status_user" style="width:165px" onChange="change_tingkat();" onFocus="panggil('status_user')" <?=$fokus?>>
            <option value="1"
            <?php
                if ($status_user==1)
                echo "selected";
                ?>
            >Manajer </option>
            <option value="2"
            <?php
                if ($status_user==2)
                echo "selected";
                ?>
            >Staff </option>
    </select></td>
</tr>
<tr>
    <td valign="top">Keterangan</td>
    <td><textarea wrap="soft" id="keterangan" name="keterangan" cols="40" rows="3" onFocus="panggil('keterangan')" onKeyPress="return focusNext('simpan', event)"><?=$keterangan?></textarea></td>
</tr>
<tr>
    <td colspan="2"><div align="center">
      	<input type="submit" value="Simpan" name="simpan" id="simpan" class="but" onFocus="panggil('simpan')">&nbsp;
        <input type="button" value="Tutup" name="batal" class="but" onClick="window.close();">
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
<?php if (strlen((string) $ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>
</body>
</html>