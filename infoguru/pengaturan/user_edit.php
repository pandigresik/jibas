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
require_once("../include/sessionchecker.php");
require_once("../include/theme.php");
require_once('../include/errorhandler.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');

OpenDb();
//$bag=$_REQUEST["bagian"];
if(isset($_REQUEST["replid"])){
$sql_get_nip="SELECT * FROM jbsuser.hakakses WHERE replid='".$_REQUEST['replid']."'";
//echo $sql_get_nip;
$result_get_nip=QueryDb($sql_get_nip);
$row_get_nip=@mysqli_fetch_array($result_get_nip);
//echo $row_get_nip['login'];
$sql_get_nama="SELECT nama FROM jbssdm.pegawai WHERE nip='".$row_get_nip['login']."'";
$result_get_nama=QueryDb($sql_get_nama);
$row_get_nama=@mysqli_fetch_array($result_get_nama);
}
$flag=0;

if(isset($_POST["departemen"])){
	$departemen = $_POST["departemen"];
}elseif(isset($_GET["departemen"])){
	$departemen = $_GET["departemen"];
}

if(isset($_POST["status_user"])){
	$status_user = $_POST["status_user"];
}elseif(isset($_GET["status_user"])){
	$status_user = $_GET["status_user"];
}

?>

<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
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
        dep = document.tambah_user.departemen.value;
        stat = document.tambah_user.status_user.value;
        pass = document.tambah_user.password.value;
        kon = document.tambah_user.konfirmasi.value;
        
        if(nip.length == 0) {
            alert("User tidak boleh kosong");
            document.tambah_user.nip.value = "";
            document.tambah_user.nip.focus();
            return false;
        }
        if(stat.length == 0) {
            alert("Tingkat tidak boleh kosong");
            document.tambah_user.status_user.value = "";
            document.tambah_user.status_user.focus();
            return false;
        }
        if(dis.disabled == false) {
            if(pass.length == 0) {
                alert("Password tidak boleh kosong");
                return false;
            }else if(kon.length == 0) {
                alert("Konfirmasi tidak boleh kosong");
                return false;
            }
            if(pass != kon) {
                alert("Password dan konfirmasi harus sama");
                return false;
            }
        }
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

	function acceptPegawai(nip, nama, flag) {
		var dep = document.tambah_user.departemen.value;
		document.tambah_user.nip.value=nip;
		document.tambah_user.nama.value=nama;
		
		//eval("document.tambah_user.nip.value='" + nip + "'");
		//eval("document.tambah_user.nama.value='" + nama + "'");
		document.location.href = "../user/user_add.php?nip="+nip+"&departemen="+dep+"&nama="+nama;	
	}
    //function change_page() {

      //  var dep = document.tambah_user.departemen.value;
      //  document.location.href="lihat_user.php?departemen="+dep;
    //}
    function clear_nip() {
        document.tambah_user.nip.value = "";
		document.tambah_user.nama.value = "";
    }
    function change_tingkat() {
        var tin = document.tambah_user.status_user.value;
        if(tin == 1) {
            document.tambah_user.departemen.value = "";
            tt.disabled = true;
        }else {
            tt.disabled = false;
        }
    }
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF" onLoad="document.tambah_user.status_user.focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<table border="0" width="100%" height="100%" valign="middle">
<tr>
    <td align="center" valign="center">

<?php

if (!isset($_POST['simpan'])) {
?>
    <form action="user_edit.php" method="post" name="tambah_user" onSubmit="return cek_form()">
    <input type="hidden" name="replid" readonly value="<?=$_REQUEST['replid']?>" class="disabled">
    <table border="0">
        <tr>
            <td colspan="2" class="header"><div align="center">Ubah User </div></td>
        </tr>
        <tr>
            <td align="left">Login</td>
            <td align="left">
            <input type="text" size="30" name="nip" readonly value="<?=$row_get_nip['login']?>" class="disabled"></td>
        </tr>
        <tr>
            <td align="left">Nama</td>
            <td align="left">
            <input type="text" size="30" name="nama" readonly value="<?=$row_get_nama['nama']?>" class="disabled">
            </td>
        </tr>
        <?php
        $query_cek = "SELECT * FROM jbsuser.login WHERE login = '".$row_get_nip['login']."'";
        $result_cek = QueryDb($query_cek);
        $num_cek = @mysqli_num_rows($result_cek);
		$row_cek = @mysqli_fetch_array($result_cek);
		$query_cek2 = "SELECT * FROM jbsuser.hakakses WHERE login = '".$row_get_nip['login']."' AND modul='SIMAKA'";
        $result_cek2 = QueryDb($query_cek2);
        $num_cek2 = @mysqli_num_rows($result_cek2);
		$row_cek2 = @mysqli_fetch_array($result_cek2);
        if($num_cek == 0) {
            $dis = "";
        }else {
			$status_user=$row_cek2['tingkat'];
            $dis = "disabled='disabled' class='disabled' value='********'";
        }
        ?>
        <tr>
            <td align="left">Password</td>
            <td align="left"><input type="text" size="41" name="password" <?=$dis ?> id="dis"></td>
        </tr>
        <tr>
            <td align="left">Konfirmasi</td>
            <td align="left"><input type="text" size="41" name="konfirmasi" <?=$dis ?> id="dis"></td>
        </tr>
        <tr><td align="left">Tingkat</td>
            <td align="left"><select name="status_user" id="status_user" style="width:150px" onChange="change_tingkat();">
                    <option value="1"
					<?php
						if ($status_user==1)
						echo "selected";
						?>
					>Manajer Akademik</option>
					<option value="2"
					<?php
						if ($status_user==2)
						echo "selected";
						?>
					>Staff Akademik</option>

            
              
        
            <?php
			if($status_user == 1 || $status_user == "") {
                $dd = "disabled";
            }else {
                $dd = "";
				$departemen=$row_cek2['departemen'];
            }
            ?>
            </select></td>
        </tr>
        <tr>
            <td align="left">Departemen</td>
            <td align="left" width="*"><select name="departemen" style="width:150px;" id="tt" <?=$dd ?>>
    		<?php
            $query_pro = "SELECT departemen FROM jbsakad.departemen WHERE aktif=1 ORDER BY urutan ASC";
            $result_pro = QueryDb($query_pro);

    		$i = 0;
    		while($row_pro = @mysqli_fetch_array($result_pro)) {
                if($departemen == "") {
                    $departemen = $row_pro['departemen'];
                    $sel[$i] = "selected";
                }elseif($departemen == $row_pro['departemen']) {
    				$sel[$i] = "selected";
    			}else {
                    $sel[$i] = "";
                }
    			echo "
    				<option value='".$row_pro['departemen']."' $sel[$i]>".$row_pro['departemen'];
    			$i++;
    		}
    		?>
            </option></select></td>
        </tr>
        <tr>
            <td align="left">Keterangan</td>
            <td align="left"><textarea wrap="soft" id="keterangan" name="keterangan" cols="40" rows="3"
            onFocus="showhint('Keterangan tidak boleh lebih dari 255 karakter',
            this, event, '100px')"><?=$row_cek['keterangan']?></textarea></td>
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
} else {
    $tingkat=(int)$_REQUEST['status_user'];
	if ($tingkat==1){
	$sql_simpan="UPDATE jbsuser.hakakses SET tingkat='1',departemen=NULL WHERE replid='".$_REQUEST['replid']."'";
	} else {
	$departemen=$_REQUEST['departemen'];
	$sql_simpan="UPDATE jbsuser.hakakses SET tingkat='2',departemen='$departemen' WHERE replid='".$_REQUEST['replid']."'";
	}
	//echo $departemen." ".$sql_simpan;
	$result_simpan=QueryDb($sql_simpan);
	if ($result_simpan){
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
CloseDb();
?>
</td></tr>
</table>
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
var sprytextSelect = new Spry.Widget.ValidationSelect("status_user");
var sprytextSelect2 = new Spry.Widget.ValidationSelect("tt");
var sprytextarea3 = new Spry.Widget.ValidationTextarea("keterangan");
</script>