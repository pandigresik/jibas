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
//include('../cek.php');


require_once("../include/theme.php");
require_once('../include/errorhandler.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/getheader.php');
//$bag=$_REQUEST["bagian"];
OpenDb();

$flag=0;

?>

<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/cal2.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/cal_conf2.js"></script>
<script language = "javascript" type = "text/javascript">
    function cek_form() {
        var nip,dep,ket,stat,pass,kon;

        nip = document.tambah_user.nip.value;
        pass = document.tambah_user.password.value;
        kon = document.tambah_user.konfirmasi.value;
        
        if(nip.length == 0) {
            alert("User tidak boleh kosong");
            document.tambah_user.nip.value = "";
            document.tambah_user.nip.focus();
            return false;
        }
        //if(pass.disabled == false) {
            if(pass.length == 0) {
                alert("Password tidak boleh kosong");
				document.tambah_user.password.focus();
                return false;
            }else if(kon.length == 0) {
                alert("Konfirmasi tidak boleh kosong");
				document.tambah_user.konfirmasi.focus();
                return false;
            }
            if(pass != kon) {
                alert("Password dan konfirmasi harus sama");
				document.tambah_user.konfirmasi.focus();
                return false;
            }
        //}
        if(ket.length > 255) {
            alert("Keterangan tidak boleh lebih dari 255 karakter");
            document.tambah_user.keterangan.value = "";
            document.tambah_user.keterangan.focus();
            return false;
        }
        return true;
    }
	function caripegawai() {
		newWindow('../library/caripegawai.php?flag=0', 'CariPegawai','600','565','resizable=1,scrollbars=1,status=0,toolbar=0');
	}

	function acceptPegawai(nip, nama) {
		document.tambah_user.nip.value=nip;
		document.tambah_user.nama.value=nama;
		
		document.location.href = "../pengaturan/user_add.php?nip="+nip+"&nama="+nama;	
	}
    function clear_nip() {
        document.tambah_user.nip.value = "";
		document.tambah_user.nama.value = "";
    }
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF" onLoad="document.tambah_user.password.focus();">
<table border="0" width="100%" height="100%" valign="middle">
<tr>
    <td align="center" valign="center">

<?php

if (!isset($_POST['simpan'])) {
?>
    <form action="user_add.php" method="post" name="tambah_user" onSubmit="return cek_form()">
    <table border="0">
        <tr>
            <td colspan="2" class="header"><div align="center">Input User Baru</div></td>
        </tr>
        <tr>
            <td>Login</td><td>
            <input type="text" size="40" name="nip" readonly value="<?=$_GET['nip'] ?>" class="disabled" onClick="caripegawai()">&nbsp
            <a href="#null" onClick="caripegawai()"><img src="../images/ico/cari.png" border="0" onMouseOver="showhint('Cari pegawai',
            this, event, '100px')"></a>
            <img src="../images/ico/hapus.png" border="0" onClick="clear_nip();" onMouseOver="showhint('Kosongkan NIP dan Nama',
            this, event, '100px')" style="cursor:pointer">
            </td>
        </tr>
        <tr>
            <td>Nama</td><td>
            <input type="text" size="50" name="nama" readonly value="<?=$_GET['nama']?>" class="disabled" onClick="caripegawai()">
            </td>
        </tr>
        <?php
        //Ini tuk ngecek user sudah punya login apa belum di SISTO
        $sql_cek = "SELECT * FROM jbsuser.login WHERE login = '".$_GET['nip']."'";
        $res_cek = QueryDb($sql_cek);
        $jum_cek = @mysqli_num_rows($res_cek);
		$row_cek = @mysqli_fetch_array($res_cek);
		$query_cek2 = "SELECT * FROM jbsuser.hakakses WHERE login = '".$_GET['nip']."' AND modul='INFOGURU'";
        $result_cek2 = QueryDb($query_cek2);
        $num_cek2 = @mysqli_num_rows($result_cek2);
		$row_cek2 = @mysqli_fetch_array($result_cek2);
        if($jum_cek == 0) {
            $dis = "";
        }else {
			$status_user=$row_cek2['tingkat'];
            $dis = "disabled='disabled' class='disabled' value='********'";
        }
        ?>
        <tr>
            <td>Password</td>
            <td><input type="password" size="50" name="password" <?=$dis ?> id="password"></td>
        </tr>
        <tr>
            <td>Konfirmasi</td>
            <td><input type="password" size="50" name="konfirmasi" <?=$dis ?> id="konfirmasi"></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td><textarea wrap="soft" name="keterangan" cols="47" rows="3"
            onFocus="showhint('Keterangan tidak boleh lebih dari 255 karakter',
            this, event, '100px')"></textarea></td>
        </tr>
        <tr>
            <td colspan="2"><div align="center">
              <input type="submit" value="Simpan" name="simpan" class="but">&nbsp;
                <input type="button" value="Tutup" name="batal" class="but" onClick="window.close();">
            </div></td>
          </tr>
    </table>
    </form>

<?php
}
else {
    //Ini tuk ngecek user sudah punya login apa belum di SISTO
    $query_cek = "SELECT * FROM jbsuser.login WHERE login  = '".$_POST['nip']."'";
    $result_cek = QueryDb($query_cek);
    $num_cek = @mysqli_num_rows($result_cek);
    //echo $query_cek;
	//exit;
    $query_c = "SELECT * FROM jbsuser.hakakses WHERE login = '".$_POST['nip']."' AND modul = 'INFOGURU'";
    $result_c = QueryDb($query_c);
    $num_c = @mysqli_num_rows($result_c);
	
		
	$pass=md5((string) $_POST['password']);
	$tingkat=1;
	if ($tingkat==1){
	//Kalo manajer
	if ($num_cek>0){
	$sql_login="UPDATE jbsuser.login SET keterangan='".$_POST['keterangan']."' WHERE login='".$_POST['nip']."'";
	//$result_login=QueryDb($sql_login);
	} elseif ($num_cek==0){
	$sql_login="INSERT INTO jbsuser.login SET login='".$_POST['nip']."',password='$pass',keterangan='".$_POST['keterangan']."',aktif=1";
	//$result_login=QueryDb($sql_login);
	}
	if ($num_c>0){
	$sql_hakakses="UPDATE jbsuser.hakakses SET departemen='',tingkat=1 WHERE modul='INFOGURU' AND login='".$_POST['nip']."'";
	//$result_hakakses=QueryDb($sql_hakakses);
	} elseif ($num_c==0){
	$sql_hakakses="INSERT INTO jbsuser.hakakses SET login='".$_POST['nip']."',departemen='',tingkat=1,modul='INFOGURU'";
	//$result_hakakses=QueryDb($sql_hakakses);
	}
	} elseif ($tingkat==2){
	//Kalo staf
	if ($num_cek>0){
	$sql_login="UPDATE jbsuser.login SET keterangan='".$_POST['keterangan']."' WHERE login='".$_POST['nip']."'";
	//$result_login=QueryDb($sql_login);
	} elseif ($num_cek==0){
	$sql_login="INSERT INTO jbsuser.login SET login='".$_POST['nip']."', password='$pass', keterangan='".$_POST['keterangan']."', aktif=1";
	
	}
	if ($num_c>0){
	$sql_hakakses="UPDATE jbsuser.hakakses SET departemen='".$_POST['departemen']."',tingkat=2 WHERE modul='INFOGURU' AND login='".$_POST['nip']."'";
	//$result_hakakses=QueryDb($sql_hakakses);
	} elseif ($num_c==0){
	$sql_hakakses="INSERT INTO jbsuser.hakakses SET login='".$_POST['nip']."',departemen='".$_POST['departemen']."',tingkat=2,modul='INFOGURU'";
	
	}
	}
	//echo $sql_hakakses."<br>".$sql_login;
	//exit;
	$result_login=QueryDb($sql_login);
	$result_hakakses=QueryDb($sql_hakakses);
	
	if ($result_hakakses && $result_login){
	?>
	<script language="javascript">
		//alert ('Berhasil');
		parent.opener.refresh();
		window.close();
	</script>
	<?php
	} else {
	?>
	<script language="javascript">
		alert ('Gagal menyimpan data');

	</script>
	<?php
	}


}
?>
</td></tr>
</table>
</body>
</html>
<?php
CloseDb();
?>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("password");
var sprytextfield2 = new Spry.Widget.ValidationTextField("konfirmasi");
var sprytextfield3 = new Spry.Widget.ValidationTextField("dis");
var sprytextSelect = new Spry.Widget.ValidationSelect("status_user");
var sprytextSelect2 = new Spry.Widget.ValidationSelect("tt");
var sprytextarea3 = new Spry.Widget.ValidationTextarea("keterangan");
</script>