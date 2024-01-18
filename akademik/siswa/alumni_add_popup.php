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
//$bag=$_REQUEST["bagian"];
OpenDb();

$flag=0;
if(isset($_REQUEST["simpan"])){
	$tanggal=$_REQUEST["tanggal"];
	$info = explode("-", (string) $tanggal);
	$tgl = $info[2] . "-" . $info[1] . "-" . $info[0];
	OpenDb();
	BeginTrans();
	$success=0;
	$sql="SELECT s.nis as nis, s.idkelas as idkelas, t.departemen as departemen, t.replid as tingkat FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t WHERE s.nis='".$_REQUEST['nis']."' AND k.replid=s.idkelas AND k.idtingkat=t.replid";
	
	$result=QueryDbTrans($sql, $success);
	$row=@mysqli_fetch_array($result);
	if ($success){
		$sql="INSERT INTO jbsakad.alumni SET nis='".$row['nis']."',klsakhir='".$row['idkelas']."',tktakhir='".$row['tingkat']."',tgllulus='$tgl',keterangan='".CQ($_REQUEST['keterangan'])."'";
		//echo $sql;
		//exit;
		QueryDbTrans($sql, $success);
		}
	if ($success){
		$sql="UPDATE jbsakad.riwayatkelassiswa SET aktif=0 WHERE nis='".$_REQUEST['nis']."' AND aktif=1";
		QueryDbTrans($sql, $success);
		//echo $sql;
		//exit;
		}
	if ($success){
		$sql="UPDATE jbsakad.riwayatdeptsiswa SET aktif=0 WHERE nis='".$_REQUEST['nis']."' AND aktif=1";
		QueryDbTrans($sql, $success);
		}
	if ($success){
		$sql="UPDATE jbsakad.siswa SET aktif=0, alumni=1 WHERE nis='".$_REQUEST['nis']."'";
		QueryDbTrans($sql, $success);
		}
	if ($success){
		CommitTrans(); 
		?>
		<script language="javascript">
			alert ('Berhasil mengisi data!');
			parent.opener.refresh();
			window.close();
	 	</script>
        <?php 
	} else {
		RollbackTrans();
	}
	CloseDb();	

}
?>

<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<link rel="stylesheet" type="text/css" href="../style/calendar-win2k-1.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script type="text/javascript" src="../script/calendar.js"></script>
<script type="text/javascript" src="../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../script/calendar-setup.js"></script>
<script language = "javascript" type = "text/javascript">
    function cek_form() {
        var nip,nama,tanggal,keterangan;

        nis = document.tambah_alumni.nis.value;
        nama = document.tambah_alumni.nama.value;
        tanggal = document.tambah_alumni.tanggal.value;
		keterangan = document.tambah_alumni.keterangan.value;
        
        if(nis.length == 0) {
            alert("NIS dan Nama alumni tidak boleh kosong");
            document.tambah_alumni.nis.value = "";
            document.tambah_alumni.nis.focus();
            return false;
        }

		if(tanggal.length == 0) {
            alert("Tanggal kelulusan tidak boleh kosong");
            document.tambah_alumni.tanggal.value = "";
            document.tambah_alumni.btntanggal.focus();
            return false;
        }
       
       
        if(keterangan.length > 255) {
            alert("Keterangan tidak boleh lebih dari 255 karakter");
            document.tambah_alumni.keterangan.value = "";
            document.tambah_alumni.keterangan.focus();
            return false;
        }
		
		if (confirm('Anda yakin akan merubah ststus siswa ini menjadi alumni?')){
		return true;
        }
    }
	function carisiswa() {
		newWindow('../library/alumnimain.php?flag=0&alumni=1', 'CariSiswa','600','565','resizable=1,scrollbars=1,status=0,toolbar=0');
	}

	function acceptSiswa(nis, nama) {
		document.tambah_alumni.nis.value=nis;
		document.tambah_alumni.nama.value=nama;
		
		//eval("document.tambah_user.nip.value='" + nip + "'");
		//eval("document.tambah_user.nama.value='" + nama + "'");
		//document.location.href = "../user/user_add.php?nip="+nip+"&departemen="+dep+"&nama="+nama;	
	}
    //function change_page() {

      //  var dep = document.tambah_user.departemen.value;
      //  document.location.href="lihat_user.php?departemen="+dep;
    //}
    function clear_nis() {
        document.tambah_alumni.nis.value = "";
		document.tambah_alumni.nama.value = "";
    }
    
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4">
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

    <form action="alumni_add.php" method="post" name="tambah_alumni" onSubmit="return cek_form()">
    <table border="0">
        <tr>
            <td colspan="2" class="header"><div align="center">Input Alumni Baru</div></td>
        </tr>
        <tr>
            <td>NIS</td><td>
            <input type="text" size="40" name="nis" readonly="readonly"  value="<?=$_GET['nis'] ?>" class="disabled" onClick="carisiswa()">&nbsp
            <a href="#null" onClick="carisiswa()"><img src="../images/ico/cari.png" border="0" onMouseOver="showhint('Cari siswa',
            this, event, '100px')"></a>
            <img src="../images/ico/hapus.png" border="0" onClick="clear_nis();" onMouseOver="showhint('Kosongkan NIS dan Nama',
            this, event, '100px')" style="cursor:pointer">
            </td>
        </tr>
        <tr>
            <td>Nama</td><td>
            <input type="text" size="50" name="nama" readonly="readonly" value="<?=$_GET['nama']?>" class="disabled" onClick="carisiswa()">
            </td>
        </tr>
        <tr>
            <td>Tanggal Lulus</td>
            <td><input type="text" size="25" name="tanggal" id="tanggal" readonly="readonly"  class="disabled" onMouseOver="showhint('Buka kalendar!', this, event, '120px')">
              <img src="../images/ico/calendar_1.png" alt="Tampilkan Tabel" name="btntanggal" width="22" height="22" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '120px')"/></td>
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
<?php
CloseDb();
?>
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
</script>