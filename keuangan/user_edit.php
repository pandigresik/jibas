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

$replid = $_REQUEST["replid"];
OpenDb();
$sql="SELECT h.login, h.tingkat, h.departemen, h.keterangan, p.nama FROM jbsuser.hakakses h, jbssdm.pegawai p, jbsuser.login l WHERE h.modul='KEUANGAN' AND h.login = l.login AND l.login = p.nip AND h.replid=$replid ";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$nip = $row['login'];
$nama = $row['nama'];
$departemen = $row['departemen'];
$status_user = $row['tingkat'];
$keterangan = $row['keterangan'];

//if ($status_user == 1) 
//	$dd1 = "disabled";

if(isset($_REQUEST["nip"]))
	$nip = $_REQUEST["nip"];
if(isset($_REQUEST["nama"]))
	$nama = $_REQUEST["nama"];
if(isset($_REQUEST["departemen"]))
	$departemen = $_REQUEST["departemen"];
if(isset($_REQUEST["status_user"]))
	$status_user = $_REQUEST["status_user"];
if(isset($_REQUEST["keterangan"]))
	$keterangan = $_REQUEST["keterangan"];

$mysqli_ERROR_MSG = "";
if (isset($_REQUEST['simpan'])) {
	OpenDb();
	$tingkat = (int)$_REQUEST['status_user'];
	
	$sql_dep = "AND departemen = '".$_REQUEST['departemen']."'";
	if ($_REQUEST['status_user'] == "" || $_REQUEST['status_user'] == 1) {
		$tingkat = 1;
		$sql_dep = "";	
	}	
		
	$sql = "SELECT * FROM jbsuser.hakakses WHERE login = '".$_REQUEST['nip']."' AND tingkat = '$tingkat' AND modul = 'KEUANGAN' $sql_dep AND replid <> '$replid'";
	
	$result=QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {
		CloseDb();
		$mysqli_ERROR_MSG = "Pengguna ".$_REQUEST['nip']." sudah mempunyai account untuk tingkat dan departemen ini!";
	} else {
		if ($tingkat == 1) {
			$sql_hakakses="UPDATE jbsuser.hakakses SET tingkat=1, keterangan ='".CQ($_REQUEST['keterangan'])."' WHERE replid = '".$replid."'";
		} else {
			$sql_hakakses="UPDATE jbsuser.hakakses SET departemen='$departemen', tingkat=2, keterangan ='".CQ($_REQUEST['keterangan'])."' WHERE replid = '".$replid."'";
		}
		
		$result = QueryDb($sql_hakakses);
		CloseDb();
	
		if ($result) { ?>
			<script language="javascript">
				parent.opener.refresh();
				window.close();
			</script> 
<?php 	}
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
	$fokus = "onKeyPress=\"return focusNext('departemen', event)\"";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Ubah Data Pengguna]</title>
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
/*function validasi() {
	return validateEmptyText('nip', 'Pengguna'); 
}*/

function cek_form() {
	var nip = document.main.nip.value;
	var dep = document.main.departemen.value;
	var stat = document.main.status_user.value;
	var ket = document.main.keterangan.value;
		
	if (nip.length == 0) {
		alert("User tidak boleh kosong");
		return false;
	}

	if (stat.length == 0) {
		alert("Tingkat tidak boleh kosong!");
		document.main.status_user.focus();
		return false;
	}
	
	if (stat != 1) {
		if (dep.length==0) {
		alert("Departemen tidak boleh kosong!");
		document.main.departemen.focus();
		return false;
		}
	}
	
	if (ket.length > 255) {
		alert("Keterangan tidak boleh lebih dari 255 karakter!");
		document.main.keterangan.focus();
		return false;
	}
}

/*function acceptCari(id, nama, flag) {
	document.getElementById('nama').value = nama;
	document.getElementById('nip').value = id;
}

function change_tingkat() {
	var tingkat = document.getElementById('tingkat').value;
	if (tingkat == 1) 
		document.getElementById('departemen').disabled = true;
	else
		document.getElementById('departemen').disabled = false;
}

function change_tingkat() {
	var tin = document.main.status_user.value;
	var nip = document.main.nip.value;
	var nama = document.main.nama.value;
	var dep = document.main.departemen.value;
	var keterangan = document.main.keterangan.value;
	var replid = document.main.replid.value;
	
	if(tin == 1) {
		document.main.tt.disabled = true;
	} else {
		document.main.tt.disabled = false;
	}
	
	document.location.href ="user_edit.php?nip="+nip+"&nama="+nama+"&status_user="+tin+"&keterangan="+keterangan+"&replid="+replid;
}*/

function change_tingkat() {
	var tin = document.getElementById('status_user').value;
	var nip = document.getElementById('nip').value;
	var nama = document.getElementById('nama').value;
	var dep = document.getElementById('departemen').value;
	var keterangan = document.getElementById('keterangan').value;
	var replid = document.main.replid.value;
	
	if(tin == 1) {
		document.getElementById('departemen').disabled = true;
	} else {
		document.getElementById('departemen').disabled = false;
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
	var lain = new Array('departemen','status_user','keterangan');
	
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

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dfdec9" <?=$input_awal?>>
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
    <form name="main" method="post" onSubmit="return cek_form();">    
    <input type="hidden" name="replid" id="replid" value="<?=$replid ?>" />
   	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
    <!-- TABLE CONTENT -->
    <tr>
        <td width="20%"><strong>Login</strong></td>
        <td>
        <input type="text" name="nip1" id="nip1" size="12" readonly="readonly" value="<?=$nip ?>" style="background-color:#CCCC99"/>
        &nbsp;<input type="text" size="32" name="nama1" readonly value="<?=$nama?>" style="background-color:#CCCC99">
        <input type="hidden" name="nip" id="nip" value="<?=$nip?>">
        <input type="hidden" name="nama" id="nama" value="<?=$nama?>">
<!--<a href="JavaScript:cari()"><img src="images/ico/lihat.png" border="0" /></a>-->
        </td>
    </tr>
    
    
    <tr>
        <td><strong>Tingkat</strong></td>
        <td><select name="status_user" id="status_user" style="width:165px" onChange="change_tingkat();" onFocus="panggil('status_user')" <?=$fokus?>>
        <!--<select name="tingkat" id="tingkat" onChange="change_tingkat()">-->
        	<option value="1" <?=IntIsSelected($status_user, 1) ?> >Manajer Keuangan</option>
            <option value="2" <?=IntIsSelected($status_user, 2) ?> >Staf Keuangan</option>
        </select>
        </td>
    </tr>
    <tr>
        <td><strong>Departemen</strong></td>
        <td><select name="departemen" style="width:165px;" id="departemen" <?=$dd ?> onKeyPress="return focusNext('keterangan', event)" onFocus="panggil('departemen')">
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
        <!--<td>-->
        <?php //$disabled = "";
		   //if ($tingkat == 1)
		   //		$disabled = "disabled"; ?> 
        <!--<select name="departemen" id="departemen" <?=$disabled ?> >-->
        <?php //OpenDb();
			//$dep = getDepartemen("ALL");
            //foreach ($dep as $value) { ?>
                <option value="<?=$value?>" <?=StringIsSelected($departemen, $value) ?> > <?=$value ?></option>
        <?php  //} 
			//CloseDb();	?>     
        <!--</select>
        </td>-->
    </tr>
    <tr>
        <td valign="top">Keterangan</td>
        <td><textarea name="keterangan" id="keterangan" rows="3" cols="40" onFocus="panggil('keterangan')" onKeyPress="return focusNext('simpan', event)"><?=$keterangan?></textarea></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
        	<input class="but" type="submit" value="Simpan" name="simpan" onFocus="panggil('simpan')">&nbsp;
            <input class="but" type="button" value="Tutup" onClick="window.close();">
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
<?php if (strlen($mysqli_ERROR_MSG) > 0) { ?>
<script language="javascript">
    alert('<?=$mysqli_ERROR_MSG ?>');
</script>
<?php } ?>
</body>
</html>