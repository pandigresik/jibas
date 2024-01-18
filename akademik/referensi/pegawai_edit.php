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
require_once('../include/imageresizer.php');
require_once('../cek.php');

OpenDb();

$replid = $_REQUEST["replid"];
$sql_pegawai = "SELECT * FROM jbssdm.pegawai WHERE replid = '".$replid."'";
$result_pegawai = QueryDb($sql_pegawai);
$row_pegawai = @mysqli_fetch_array($result_pegawai);

$bagian = $row_pegawai['bagian'];
$nip = $row_pegawai['nip'];
$nama = $row_pegawai['nama'];
$gelarawal = $row_pegawai['gelarawal'];
$gelarakhir = $row_pegawai['gelarakhir'];
$panggilan = $row_pegawai['panggilan'];
$kelamin = $row_pegawai['kelamin'];
$tempatlahir = $row_pegawai['tmplahir'];
$lahir = explode("-",(string) $row_pegawai['tgllahir']);
$tgllahir = $lahir[2];
$blnlahir = $lahir[1];
$thnlahir = $lahir[0];
$agama = $row_pegawai['agama'];
$suku = $row_pegawai['suku'];
$menikah = $row_pegawai['nikah'];
$identitas = $row_pegawai['noid'];
$alamat = $row_pegawai['alamat'];
$telpon = $row_pegawai['telpon'];
$handphone = $row_pegawai['handphone'];
$email = $row_pegawai['email'];
$keterangan = $row_pegawai['keterangan'];

if (isset($_REQUEST['bagian']))
	$bagian = $_REQUEST['bagian'];
if (isset($_REQUEST['nip']))
	$nip = CQ($_REQUEST['nip']);
if (isset($_REQUEST['nama']))
	$nama = CQ($_REQUEST['nama']);
if (isset($_REQUEST['gelarawal']))
	$gelarawal = CQ($_REQUEST['gelarawal']);
if (isset($_REQUEST['gelarakhir']))
	$gelarakhir = CQ($_REQUEST['gelarakhir']);    
if (isset($_REQUEST['panggilan']))
	$panggilan = CQ($_REQUEST['panggilan']);
if (isset($_REQUEST['kelamin']))
	$kelamin = $_REQUEST['kelamin'];
if (isset($_REQUEST['tempatlahir']))
	$tempatlahir = CQ($_REQUEST['tempatlahir']);
if (isset($_REQUEST['tgllahir']))
	$tgllahir = $_REQUEST['tgllahir'];
if (isset($_REQUEST['blnlahir']))
	$blnlahir = $_REQUEST['blnlahir'];
if (isset($_REQUEST['thnlahir']))
	$thnlahir = $_REQUEST['thnlahir'];
if (isset($_REQUEST['agama']))
	$agama = $_REQUEST['agama'];
if (isset($_REQUEST['suku']))
	$suku = $_REQUEST['suku'];
if (isset($_REQUEST['menikah']))
	$menikah = $_REQUEST['menikah'];
if (isset($_REQUEST['identitas']))
	$identitas = CQ($_REQUEST['identitas']);
if (isset($_REQUEST['alamat']))
	$alamat = CQ($_REQUEST['alamat']);
if (isset($_REQUEST['telpon']))
	$telpon = CQ($_REQUEST['telpon']);
if (isset($_REQUEST['handphone']))
	$handphone = CQ($_REQUEST['handphone']);
if (isset($_REQUEST['email']))
	$email = CQ($_REQUEST['email']);
if (isset($_REQUEST['keterangan']))
	$keterangan = CQ($_REQUEST['keterangan']);

$ERROR_MSG = "";
if (isset($_REQUEST['simpan'])) 
{
	$foto = $_FILES["foto"];
	$uploadedfile = $foto['tmp_name'];
	$uploadedtypefile = $foto['type'];
	$uploadedsizefile = $foto['size'];
		
	if (strlen((string) $uploadedfile) != 0)
	{
		$tmp_path = realpath(".") . "/../../temp";
		$tmp_exists = file_exists($tmp_path) && is_dir($tmp_path);
		if (!$tmp_exists)
			mkdir($tmp_path, 0755);
		
		$filename = "$tmp_path/ed-peg-tmp.jpg";
		ResizeImage($foto, 159, 120, 80, $filename);
		
		$fh = fopen($filename, "r");
		$foto_data = addslashes(fread($fh, filesize($filename)));
		fclose($fh);
		
		$gantifoto = ", foto = '$foto_data'";
	} 
	else 
	{
		$gantifoto = "";
	}
	
	$lahir = $thnlahir."-".$blnlahir."-".$tgllahir;
	
	$query_cek = "SELECT * FROM jbssdm.pegawai WHERE nip = '$nip' AND replid <> '$replid'";
	$result_cek = QueryDb($query_cek);
	$num_cek = @mysqli_num_rows($result_cek);
	if($num_cek > 0) 
	{
		$ERROR_MSG = "NIP ".$nip." sudah digunakan!";
	} 
	else 
	{
		$nama = str_replace("'", "`", (string) $nama);
		$query = "UPDATE jbssdm.pegawai 
					 SET nip='$nip', nama='$nama', gelarawal='$gelarawal', gelarakhir='$gelarakhir', panggilan='$panggilan', tmplahir='$tempatlahir', 
					 	 tgllahir='$lahir', agama='$agama', suku='$suku',nikah='$menikah', noid='$identitas',alamat='$alamat',
						 telpon='$telpon',handphone='$handphone',email='$email', bagian='$bagian', keterangan='$keterangan', 
						 kelamin='$kelamin' $gantifoto WHERE replid = '$replid' ";
    	$result = QueryDb($query);

        if ($gantifoto != "")
        {
            $sql = "INSERT INTO jbsakad.riwayatfoto SET nip = '$nip', foto = '$foto_data', tanggal = NOW()";
            QueryDbTrans($sql, $success);
        }

		if($result) 
		{  ?>
        <script language = "javascript" type = "text/javascript">
			parent.opener.location.href="pegawai.php?bagian=<?=$bagian ?>";
    		window.close();
       	</script>
<?php 		} 
   		else 
		{          ?>
            <script language = "javascript" type = "text/javascript">
            	alert("Gagal menyimpan data!");
           </script>
<?php  	}
	}
}

$n = JmlHari($blnlahir, $thnlahir);	

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Ubah Pegawai]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/ajax.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/validasi.js"></script>
<script language="javascript">
var win = null;

function newWindow(mypage,myname,w,h,features) {
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
}
//tunggu!
function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}
function tambah_suku(){
	newWindow('../library/suku.php', 'tambahSuku','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function tambah_agama(){
	newWindow('../library/agama.php', 'tambahAgama','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function kirim_agama(agama_kiriman){	
	agama=agama_kiriman
	setTimeout("refresh_agama(agama)",1);
}

function refresh_agama(kode){
	wait_agama();
	if (kode==0){
		sendRequestText("../library/getagama.php", show_agama, "agama=<?=$agama?>");
	} else {
		sendRequestText("../library/getagama.php", show_agama, "agama="+kode);
	}
}

function wait_agama() {
	show_wait("agama_info"); 
}

function show_agama(x) {
	document.getElementById("agama_info").innerHTML = x;
}

function ref_del_agama(){
	setTimeout("refresh_agama(0)",1);
}

function suku_kiriman(suku_kiriman) {	
	suku = suku_kiriman
	setTimeout("refresh_suku(suku)",1);
}

function refresh_suku(kode){
	wait_suku();
	if (kode==0){
		sendRequestText("../library/getsuku.php", show_suku, "suku=<?=$suku?>");
	} else {
		sendRequestText("../library/getsuku.php", show_suku, "suku="+kode);
	}
}

function wait_suku() {
	show_wait("suku_info"); 
}
function show_suku(x) {
	document.getElementById("suku_info").innerHTML = x;
}

function refresh_delete(){
	setTimeout("refresh_suku(0)",1);
}

function change_tgl() {	
	var thn = document.getElementById('thnlahir').value;
	var bln = parseInt(document.getElementById('blnlahir').value);	
	var tgl = parseInt(document.form1.tgllahir.value);
	
	if(thn.length == 0) {
       	alert("Anda harus mengisikan data untuk Tahun Lahir!");
		document.form1.blnlahir.value = "";
		document.form1.thnlahir.focus();
        return false;
	} else {	
		if(isNaN(thn)) {
    		alert("Tahun lahir harus berupa angka!"); 
			document.form1.thnlahir.focus();
        	return false;
		} else {	
			if (thn.length > 4 || thn.length < 4) {
            	alert("Tahun lahir tidak boleh lebih atau kurang dari 4 karakter!"); 
				document.form1.thnlahir.focus();
            	return false;
			}
		}
    }
	
	var namatgl = "tgllahir";
	var namabln = "blnlahir";
	
	sendRequestText("../library/gettanggal.php", show1, "tahun="+thn+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);	
}

function show1(x) {
	document.getElementById("tgllahir").innerHTML = x;
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
	var lain = new Array('bagian','nip','nama','panggilan','gelar','tempatlahir','tgllahir','blnlahir','thnlahir','agama','suku','identitas','alamat','telpon','handphone','email','keterangan');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

function cek_form() {
	var nip = document.form1.nip.value;
	var nama = document.form1.nama.value;
	var	tempatlahir = document.form1.tempatlahir.value;
	var tanggal=document.getElementById("tgllahir").value;
	var bulan=document.getElementById("blnlahir").value;
	var	tahun = document.getElementById("thnlahir").value;
	var agama = document.form1.agama.value;
	var suku = document.form1.suku.value;
	var email = document.form1.email.value;
		
	if (nip.length == 0) {
      	alert("Anda harus mengisikan data untuk NIP!");
		document.form1.nip.focus();
        return false;
   	}
    
	if (nip.length > 20) {
        alert("NIP tidak boleh lebih dari 20 karakter"); 
		document.form1.nip.focus();
        return false;
   	}
    
	if (nama.length == 0) {
       	alert("Anda harus mengisikan data untuk Nama pegawai!");
		document.form1.nama.focus();
        return false;
   	}
    
	if (nama.length > 100) {
    	alert("Nama tidak boleh lebih dari 100 karakter"); 
		document.form1.nama.focus();
        return false;
   	}
	
	if (tempatlahir.length == 0) {
       	alert("Anda harus mengisikan data untuk Tempat Lahir!");
		document.form1.tempatlahir.focus();
        return false;
   	}
	
	if(tanggal.length == 0) {
       	alert("Anda harus mengisikan data untuk Tanggal Lahir!");
		document.form1.tgllahir.focus();
        return false;
   	}
	
	if(bulan.length == 0) {
       	alert("Anda harus mengisikan data untuk Bulan Lahir!");
		document.form1.blnlahir.focus();
        return false;
   	}
	
	if(tahun.length == 0) {
       	alert("Anda harus mengisikan data untuk Tahun Lahir!");
		document.form1.thnlahir.focus();
        return false;
   	} 
	
	if (tahun.length > 0){	
		if(isNaN(tahun)) {
    		alert ('Data tahun lahir harus berupa bilangan!');
			document.form1.thnlahir.value="";
			document.form1.thnlahir.focus();
        	return false;
		}
		if (tahun.length > 4 || tahun.length < 4) {
        	alert("Tahun lahir tidak boleh lebih atau kurang dari 4 karakter!"); 
			document.form1.thnlahir.focus();
        	return false;
    	}
    }
	
	if(agama.length == 0) {
       	alert("Anda harus mengisikan data untuk Agama pegawai!");
		document.form1.agama.focus();
        return false;
   	}
	
	if(suku.length == 0) {
       	alert("Anda harus mengisikan data untuk Suku pegawai!");
		document.form1.suku.focus();
        return false;
   	}
	
	if (email.length > 0) {
		if (!validateEmail("email") ) { 
			alert( "Email yang Anda masukkan bukan merupakan alamat email!" );
			document.form1.email.focus();
			return false;	
		}	
	}
	
	var namatgl = "tgllahir";
	var namabln = "blnlahir";
	
	if (tahun.length != 0 && bulan.length != 0 && tanggal.length != 0){
		if (tahun % 4 == 0){
			 if (bulan == 2){
				  if (tanggal>29){
					   alert ('Maaf, silakan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+tahun+"&bulan="+bulan+"&tgl="+tanggal+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
				  }
			 }
			 if (bulan == 4 || bulan == 6 || bulan == 9 || bulan == 11){
				  if (tanggal>30){
					   alert ('Maaf, silakan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+tahun+"&bulan="+bulan+"&tgl="+tanggal+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
				  }
			 }
		}
		if (tahun % 4 != 0){
			 if (bulan == 2){
				 if (tanggal>28){
					   alert ('Maaf, silakan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+bulan+"&tgl="+tanggal+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
					   
				  }
			 }
			 if (bulan == 4 || bulan == 6 || bulan == 9 || bulan == 11){
				  if (tanggal>30){
					   alert ('Maaf, silakan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+bulan+"&tgl="+tanggal+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
					   
				  }
			 }
		}				  
			
		//document.getElementById(elemName).focus();
	}
	
	if (file.length>0){
		var x = file.explode('.');
		ext = x[(x.length-1)];
		if (ext!='JPG' && ext!='jpg' && ext!='Jpg' && ext!='JPg' && ext!='JPEG' && ext!='jpeg'){
			alert ('Format Gambar harus ber-extensi jpg atau JPG !');
			document.getElementById("foto").value='';
			document.form1.foto.focus();
    		document.form1.foto.select();
			return false;
		} 
	} 
	return true;
}

function change_bln() {	
	var thn = document.getElementById('thnlahir').value;
	var bln = parseInt(document.getElementById('blnlahir').value);	
	var tgl = parseInt(document.form1.tgllahir.value);
	var namatgl = "tgllahir";
	var namatgl = "blnlahir";
	if(thn.length != 0) {
    	if(isNaN(thn)) {
    		alert("Tahun lahir harus berupa angka!"); 
			document.form1.thnlahir.focus();
        	return false;
		} else {	
			if (thn.length > 4 || thn.length < 4) {
            	alert("Tahun lahir tidak boleh lebih atau kurang dari 4 karakter!"); 
				document.form1.thnlahir.focus();
            	return false;
			}
		}
    
	sendRequestText("../library/gettanggal.php", show1, "tahun="+thn+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);
	}
}

</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('nip').focus();">

<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
    <div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Pegawai :.
    </div>
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
    <form id="form1" name="form1" enctype="multipart/form-data" method="post" action="pegawai_edit.php" onSubmit="return cek_form()">
  	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
    <!-- TABLE CONTENT -->
    <tr>
      	<td width="25%"><strong>Bagian</strong></td>
      	<td>
        <select name="bagian" id="bagian" onKeyPress="return focusNext('nip', event)" style="width:135px" onFocus="panggil('bagian')">
      	<?php
      
        $sql_bagian="SELECT bagian FROM jbssdm.bagianpegawai ORDER BY urutan ASC";
        $result_bagian=QueryDb($sql_bagian);
        while ($row_bagian=@mysqli_fetch_array($result_bagian)){
      	?>
        <option value="<?=$row_bagian['bagian']?>" <?=StringIsSelected($row_bagian['bagian'],$bagian)?>>            
        <?=$row_bagian['bagian']?></option>
      	<?php } ?>
      	</select></td>
        <td width="30%" rowspan="8" bgcolor="#FFFFFF" valign="middle">
        <table width="100%" border="0">
        <tr>
        	<td align="center" valign="middle">
            <img src="../library/gambar.php?replid=<?=$replid?>&table=jbssdm.pegawai"  width="100" border="0"/>
            </td>
       	</tr>
        </table>
        </td>
    </tr>
    <tr>
      	<td><strong>NIP</strong></td>
      	<td>
        <input type="text" name="nip" id="nip" value="<?=$nip?>"  onKeyPress="return focusNext('nama', event)" onFocus="showhint('NIP tidak boleh kosong!', this, event, '100px');panggil('nip')" maxlength="20"/>
        <input type="hidden" name="replid" id="replid" value="<?=$replid?>"/>
        </td>
    </tr>
    <tr>
      	<td><strong>Nama</strong></td>
      	<td><input name="nama" type="text" id="nama" size="30" value="<?=$nama?>"  onKeyPress="return focusNext('gelarawal', event)" onFocus="showhint('Nama tidak boleh kosong!', this, event, '100px');panggil('nama')"/>            </td>
	</tr>
    <tr>
      	<td>Gelar Awal</td>
      	<td><input type="text" name="gelarawal" id="gelarawal" size="30" value="<?=$gelarawal?>"  onKeyPress="return focusNext('gelarakhir', event)" onFocus="panggil('gelar')"/></td>
    </tr>
    <tr>
      	<td>Gelar Akhir</td>
      	<td><input type="text" name="gelarakhir" id="gelarakhir" size="30" value="<?=$gelarakhir?>"  onKeyPress="return focusNext('panggilan', event)" onFocus="panggil('gelar')"/></td>
    </tr>
    <tr>
      	<td>Panggilan</td>
      	<td><input type="text" name="panggilan" id="panggilan" size="30" value="<?=$panggilan?>"  onKeyPress="return focusNext('kelamin', event)" onFocus="panggil('panggilan')"/></td>
  	</tr>
    <tr>
       	<td><strong>Jenis Kelamin</strong></td>
        <td><input type="radio" name="kelamin"  id="kelamin" value="l" 
    	<?php 	if ($kelamin=="l") 
    			echo "checked='checked'";
    	?> onKeyPress="return focusNext('gelar', event)"/>&nbsp;Laki-laki&nbsp;&nbsp;
        	<input type="radio" name="kelamin" value="p"
    	<?php 	if ($kelamin=="p") 
    			echo "checked='checked'";
    	?> onKeyPress="return focusNext('tempatlahir', event)"/>&nbsp;Perempuan</td>
   	</tr>
    <tr>
    	<td><strong>Tempat Lahir</strong></td>
        <td><input type="text" name="tempatlahir" id="tempatlahir" size="30" value="<?=$tempatlahir?>" onKeyPress="return focusNext('tgllahir', event)" onFocus="showhint('Tempat Lahir tidak boleh kosong!', this, event, '100px');panggil('tempatlahir')"/></td>
	</tr>
    <tr>
      	<td><strong>Tanggal Lahir</strong></td>
      	<td colspan="2">
        <select name="tgllahir" id="tgllahir"  onKeyPress="return focusNext('blnlahir', event)" onFocus="panggil('tgllahir')"> 
    <?php 	for($i=1;$i<=$n;$i++){   ?>      
        <option value="<?=$i?>" <?=IntIsSelected($tgllahir, $i)?>><?=$i?></option>
    <?php } ?>
        </select>
        <select name="blnlahir" id="blnlahir" onKeyPress="return focusNext('thnlahir', event)"  onChange="change_bln()"  onFocus="panggil('blnlahir')">
    <?php 	for ($i=1;$i<=12;$i++) { ?>
        <option value="<?=$i?>" <?=IntIsSelected($blnlahir, $i)?>><?=$bulan_pjg[$i]?></option>	
    <?php }	?>
        </select>
        <input type="text" name="thnlahir" id="thnlahir" size="5" maxlength="4" onFocus="showhint('Tahun Lahir tidak boleh kosong!', this, event, '100px');panggil('thnlahir')"  value="<?=$thnlahir?>"  onKeyPress="return focusNext('agama', event)"/></td>
    </tr>
    <tr>
        <td><strong>Agama</strong></td>
        <td colspan="2">
        <div id="agama_info">
        <select name="agama" id="agama" class="ukuran"  onKeyPress="return focusNext('suku', event)"onFocus="panggil('agama')">
        <option value="">[Pilih Agama]</option>
        <?php
        $query_a="select agama from jbsumum.agama order by urutan asc " ;
        $result_a=QueryDb($query_a) or (mysqli_error($mysqlconnection)) ;
        while($row_a=mysqli_fetch_array($result_a)) 	{
        ?>
        <option value="<?=$row_a['agama']?>"<?=StringIsSelected($agama,$row_a['agama'])?> ><?=$row_a['agama']?>
        </option>
        <?php } ?>
        </select>
        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <img src="../images/ico/tambah.png" border="0" onClick="tambah_agama();" onMouseOver="showhint('Tambah Agama!', this, event, '50px')">   
        <?php } ?>	 </div>     </td>
    </tr>
    <tr>
        <td><strong>Suku</strong></td>
        <td colspan="2"><div id="suku_info">
        <select name="suku" id="suku" class="ukuran"  onKeyPress="return focusNext('menikah', event)" onFocus="panggil('suku')">
        <option value="">[Pilih Suku]</option>
        <?php
        $query_s="select suku from jbsumum.suku order by urutan asc " ;
        $result_s=QueryDb($query_s) or (mysqli_error($mysqlconnection)) ;
        while($row_s=mysqli_fetch_array($result_s)) {
        ?>
        <option value=<?=$row_s['suku']?> <?=StringIsSelected($suku,$row_s['suku'])?>><?=$row_s['suku']?></option>
        <?php } ?>
        </select>
        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <img src="../images/ico/tambah.png" border="0" onClick="tambah_suku();" onMouseOver="showhint('Tambah Suku!', this, event, '50px')">
        <?php } ?>
        </div>     </td>
    </tr>
    <tr>
        <td>Menikah</td>
        <td colspan="2">
        <input type="radio" name="menikah" id="menikah" value="menikah" <?php if ($menikah== 'menikah') echo 'checked';?>  onKeyPress="return focusNext('identitas', event)"/>&nbsp;Sudah&nbsp;
        <input type="radio" name="menikah"  id="menikah" value="belum" <?php if ($menikah == 'belum') echo 'checked';?>  onKeyPress="return focusNext('identitas', event)"/>&nbsp;Belum&nbsp;
        <input name="menikah" type="radio" id="menikah" value="tak_ada" 
        <?php if ($menikah == 'tak_ada' || $menikah =="") echo 'checked';?> onKeyPress="return focusNext('identitas', event)"/>&nbsp;(Tidak ada data) 
        </td>
    </tr>
    <tr>
        <td>No. Identitas</td>
        <td colspan="2"><input type="text" name="identitas" id="identitas" size="30" value="<?=$identitas?>" onKeyPress="return focusNext('alamat', event)" onFocus="panggil('identitas')"/></td>
    </tr>
    <tr>
        <td valign="top">Alamat</td>
        <td colspan="2"><textarea name="alamat" cols="40" rows="2" id="alamat" onKeyPress="return focusNext('telpon', event)" onFocus="panggil('alamat')"><?=$alamat?>
        </textarea></td>
    </tr>
    <tr>
        <td>Telepon</td>
        <td colspan="2"><input type="text" name="telpon" id="telpon" size="30" value="<?=$telpon?>" onKeyPress="return focusNext('handphone', event)"  onFocus="panggil('telpon')"/></td>
    </tr>
    <tr>
        <td>Handphone</td>
        <td colspan="2"><input type="text" name="handphone" id="handphone" size="30" value="<?=$handphone?>" onKeyPress="return focusNext('email', event)"  onFocus="panggil('handphone')"/>
        </td>
    </tr>
    <tr>
        <td>Email</td>
        <td colspan="2"><input type="text" name="email" id="email" size="30" value="<?=$email?>" onKeyPress="return focusNext('keterangan', event)" onFocus="panggil('email')"/></td>
    </tr>
    <tr>
        <td>Foto</td>
        <td colspan="2"><input type="file" name="foto" id="foto" style="width:260px" size="38"/></td>
    </tr>
    <tr>
        <td valign="top">Keterangan</td>
        <td colspan="2" valign="top">
            <textarea name="keterangan" id="keterangan" cols="40" rows="2" onKeyPress="return focusNext('simpan', event)" onFocus="panggil('keterangan')"><?=$keterangan?></textarea>
        </td>
    </tr>
    <tr>
 		<td align="center" colspan="3">
    	<input type="submit" name="simpan" id="simpan" value="Simpan" class="but" onFocus="panggil('simpan')"/>
        <input type="button" name="tutup" id="tutup" value="Tutup" class="but" onClick="window.close();" />
    	</td>
	</tr>
  	</table>
       <script language='JavaScript'>
	    //Tables('table', 1, 0);
    </script>
	
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
<!-- Tamplikan error jika ada -->
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>
</body>
</html>
<?php
CloseDb();
?>