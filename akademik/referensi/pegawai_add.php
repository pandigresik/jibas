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

$bagian = $_REQUEST["bagian"];
$nip = "";
if (isset($_REQUEST['nip']))
	$nip = CQ($_REQUEST['nip']);
$nama = "";
if (isset($_REQUEST['nama']))
	$nama = CQ($_REQUEST['nama']);
$gelarawal = "";
if (isset($_REQUEST['gelarawal']))
	$gelarawal = CQ($_REQUEST['gelarawal']);
$gelarakhir = "";
if (isset($_REQUEST['gelarakhir']))
	$gelarakhir = CQ($_REQUEST['gelarakhir']);
$panggilan = "";
if (isset($_REQUEST['panggilan']))
	$panggilan = CQ($_REQUEST['panggilan']);
$kelamin = "l";
if (isset($_REQUEST['kelamin']))
	$kelamin = $_REQUEST['kelamin'];
$tempatlahir = "";
if (isset($_REQUEST['tempatlahir']))
	$tempatlahir = CQ($_REQUEST['tempatlahir']);
if (isset($_REQUEST['tgllahir']))
	$tgllahir = (int)$_REQUEST['tgllahir'];
if (isset($_REQUEST['blnlahir']))
	$blnlahir = $_REQUEST['blnlahir'];
if (isset($_REQUEST['thnlahir']))
	$thnlahir = $_REQUEST['thnlahir'];
$agama = "";
if (isset($_REQUEST['agama']))
	$agama = $_REQUEST['agama'];
$suku = "";
if (isset($_REQUEST['suku']))
	$suku = $_REQUEST['suku'];
$menikah = "";	
if (isset($_REQUEST['menikah']))
	$menikah = $_REQUEST['menikah'];
$identitas = "";
if (isset($_REQUEST['identitas']))
	$identitas = CQ($_REQUEST['identitas']);
$alamat = "";
if (isset($_REQUEST['alamat']))
	$alamat = CQ($_REQUEST['alamat']);
$telpon = "";
if (isset($_REQUEST['telpon']))
	$telpon = CQ($_REQUEST['telpon']);
$handphone = "";
if (isset($_REQUEST['handphone']))
	$handphone = $_REQUEST['handphone'];
	$handphone=trim((string) $_REQUEST['handphone']);
    $handphone=str_replace(' ','',(string) CQ($handphone));
$email = "";
if (isset($_REQUEST['email']))
	$email = CQ($_REQUEST['email']);

$keterangan = "";
if (isset($_REQUEST['keterangan']))
	$keterangan = CQ($_REQUEST['keterangan']);

$n = JmlHari($blnlahir, $thnlahir);	

$ERROR_MSG = "";
if (isset($_REQUEST['simpan'])) 
{
	$pin = random(5);	
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
		
		$filename = "$tmp_path/ad-peg-tmp.jpg";
		ResizeImage($foto, 159, 120, 80, $filename);
		
		$fh = fopen($filename, "r");
		$foto_data = addslashes(fread($fh, filesize($filename)));
		fclose($fh);
		
		$gantifoto = ", foto='$foto_data'";
	} 
	else 
	{
		$gantifoto="";
	}
	
	$lahir = $thnlahir."-".$blnlahir."-".$tgllahir;
	
	$query_cek = "SELECT * FROM jbssdm.pegawai WHERE nip = '".$nip."'";
	$result_cek = QueryDb($query_cek);
	$num_cek = @mysqli_num_rows($result_cek);
	if($num_cek > 0) 
	{
		//CloseDb();
		$ERROR_MSG = "NIP ".$nip." sudah digunakan!";
	} 
	else 
	{
		$nama = str_replace("'", "`", (string) $nama);
		$query = "INSERT INTO jbssdm.pegawai SET nip='$nip', nama='$nama', gelarawal='$gelarawal', gelarakhir='$gelarakhir', panggilan='$panggilan', 
				  tmplahir='$tempatlahir', tgllahir='$lahir', agama='$agama', suku='$suku',nikah='$menikah', noid='$identitas',
				  alamat='$alamat',telpon='$telpon',handphone='$handphone',email='$email', bagian='$bagian', keterangan='$keterangan', 
				  aktif='1', kelamin='$kelamin', pinpegawai='$pin' $gantifoto";
    	//echo $query; exit;
		$result = QueryDb($query) ;

		if ($gantifoto != "")
        {
            $sql = "INSERT INTO jbsakad.riwayatfoto SET nip = '$nip', foto = '$foto_data', tanggal = NOW()";
            QueryDbTrans($sql, $success);
        }

		if($result) 
		{       ?>
        <script language = "javascript" type = "text/javascript">
			parent.opener.location.href="pegawai.php?bagian=<?=$bagian?>";
    		window.close();
       	</script>
   <?php 	} 
   		else 
		{          ?>
            <script language = "javascript" type = "text/javascript">
            	alert("Gagal menambah data");
           </script>
	<?php  }
	}
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Tambah Pegawai]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/ajax.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

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
	agama=agama_kiriman;
	setTimeout("refresh_agama(agama)",1);
}

function refresh_agama(kode){
	wait_agama();
	
	if (kode==0){
		sendRequestText("../library/getagama.php", show_agama, "agama=");
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
	suku = suku_kiriman;
	setTimeout("refresh_suku(suku)",1);
}

function refresh_suku(kode){
	wait_suku();
	if (kode==0){
		sendRequestText("../library/getsuku.php", show_suku, "suku=");
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
	var namatgl = "blnlahir";
	
	sendRequestText("../library/gettanggal.php", show1, "tahun="+thn+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);	
}

function change_bln() {	
	var thn = document.getElementById('thnlahir').value;
	var bln = parseInt(document.getElementById('blnlahir').value);	
	var tgl = parseInt(document.form1.tgllahir.value);
	var namatgl = "tgllahir";
	var namabln = "blnlahir";

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

function show1(x) {
	document.getElementById("tgl_info").innerHTML = x;
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
	var lain = new Array('bagian','nip','nama','panggilan','gelar','tempatlahir','tgllahir','blnlahir','thnlahir','agama','suku','identitas','alamat','telpon','handphone','email','keterangan','foto');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

function cek() {
	var nip = document.form1.nip.value;
	var nama = document.form1.nama.value;
	var	tempatlahir = document.form1.tempatlahir.value;
	var	tanggal = document.form1.tgllahir.value;
	var	bulan = document.form1.blnlahir.value;
	var	tahun = document.getElementById("thnlahir").value;
	var agama = document.form1.agama.value;
	var suku = document.form1.suku.value;
	var handphone =document.form1.handphone.value;
	var email = document.form1.email.value;
	var file=document.getElementById("foto").value;
	
	
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
					   alert ('Maaf, silahkan masukan ulang tanggal lahir!');
					   
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+tahun+"&bulan="+bulan+"&tgl="+tanggal+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
				  }
			 }
			 if (bulan == 4 || bulan == 6 || bulan == 9 || bulan == 11){
				  if (tanggal>30){
					   alert ('Maaf, silahkan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+tahun+"&bulan="+bulan+"&tgl="+tanggal+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
				  }
			 }
		}
		
		if (tahun % 4 != 0){
			 if (bulan == 2){
				 if (tanggal>28){
					   alert ('Maaf, silahkan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+bulan+"&tgl="+tanggal+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
					   
				  }
			 }
			 if (bulan == 4 || bulan == 6 || bulan == 9 || bulan == 11){
				  if (tanggal>30){
					   alert ('Maaf, silahkan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+bulan+"&tgl="+tanggal+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
					   
				  }
			 }
		}
	}
	
	if (handphone.length > 0){
		if(isNaN(handphone)) {
			alert ('Data nomor HP harus berupa bilangan!\nTidak boleh menggunakan spasi.');
			//document.getElementById('hpsiswa').value="";
			document.getElementById('handphone').focus();
			return false;
		}
	}
	
//	alert (file);
	if (file.length>0){
		var ext = "";
		var i = 0;
		var string4split='.';

		z = file.explode(string4split);
		ext = z[z.length-1];
		
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
</script>
</head>
<!-- bgcolor="#d6d8cb" -->
<body style="background-color:#dcdfc4" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="document.getElementById('nip').focus()">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>
<form id="form1" name="form1" enctype="multipart/form-data" method="post" action="pegawai_add.php" onSubmit="return cek();">
<table  border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg"style="padding:0px">
    <div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Tambah Pegawai :.
    </div>
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg" style="background-repeat:repeat-y">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF; padding:0px">
    <!-- CONTENT GOES HERE //---> 
	<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
    <!-- TABLE CONTENT -->
    <tr>
    	<td width="35%"><strong>Bagian</strong></td>
        <td colspan="2" width="60%"><select name="bagian" id="bagian" style="width:135px" onKeyPress="return focusNext('nip',event)" onFocus="panggil('bagian')">
        <?php
			$sql_bagian="SELECT bagian FROM jbssdm.bagianpegawai ORDER BY urutan ASC";
		  	$result_bagian=QueryDb($sql_bagian);
		  	while ($row_bagian=@mysqli_fetch_array($result_bagian)){
		?>
          	<option value="<?=$row_bagian['bagian']?>" <?=StringIsSelected($row_bagian['bagian'],$bagian)?>>
          <?=$row_bagian['bagian']?>
            </option>
       	<?php }  ?>
        </select></td>
  	</tr>
    <tr>
    	<td><strong>NIP</strong></td>
        <td colspan="2"><input type="text" name="nip" id="nip" value="<?=$nip?>"  onKeyPress="return focusNext('nama',event)" onFocus="showhint('NIP tidak boleh kosong!', this, event, '100px');panggil('nip')" maxlength="20"/></td>
    </tr>
    <tr>
    	<td><strong>Nama</strong></td>
        <td colspan="2"><input name="nama" type="text" id="nama" size="30" value="<?=$nama?>"  onKeyPress="return focusNext('gelarawal',event)" onFocus="showhint('Nama tidak boleh kosong!', this, event, '100px');panggil('nama')"/></td>
    </tr>
	<tr>
    	<td>Gelar Awal</td>
       	<td colspan="2"><input type="text" name="gelarawal" id="gelarawal" size="30" value="<?=$gelarawal?>"  onKeyPress="return focusNext('gelarakhir', event)" onFocus="panggil('gelarawal')"/></td>
   	</tr>
	<tr>
    	<td>Gelar Akhir</td>
       	<td colspan="2"><input type="text" name="gelarakhir" id="gelarakhir" size="30" value="<?=$gelarakhir?>"  onKeyPress="return focusNext('panggilan', event)" onFocus="panggil('gelarakhir')"/></td>
   	</tr>
    <tr>
    	<td>Panggilan</td>
        <td colspan="2"><input type="text" name="panggilan" id="panggilan" size="30" value="<?=$panggilan?>"  onKeyPress="return focusNext('kelamin',event)" onFocus="panggil('panggilan')"/></td>
   	</tr>
    <tr>
       	<td><strong>Jenis Kelamin</strong></td>
        <td colspan="2"><input type="radio" name="kelamin" id="kelamin" value="l" 
    	<?php 	if ($kelamin=="l") 
    			echo "checked='checked'";
    	?> onKeyPress="return focusNext('gelar', event)" />&nbsp;Laki-laki&nbsp;&nbsp;
        	<input type="radio" name="kelamin" id="kelamin" value="p"
    	<?php 	if ($kelamin=="p") 
    			echo "checked='checked'";
    	?> onKeyPress="return focusNext('tempatlahir', event)"/>&nbsp;Perempuan</td>
   	</tr>
    <tr>
    	<td><strong>Tempat Lahir</strong></td>
        <td colspan="2"><input type="text" name="tempatlahir" size="30" id="tempatlahir" value="<?=$tempatlahir?>"  onKeyPress="return focusNext('tgllahir', event)" onFocus="showhint('Tempat Lahir tidak boleh kosong!', this, event, '100px');panggil('tempatlahir')"/></td>
    </tr>
    <tr>
        <td><strong>Tanggal Lahir</strong></td>
        <td width="5%">
        	<!--<select name="tgllahir" id="tgllahir"  onKeyPress="return focusNext('blnlahir', event)" onFocus="panggil('tgllahir')">
        	<option value="">[Tgl]</option>  
       	<?php 	for($i=1;$i<=$n;$i++){ ?>      
		    <option value="<?=$i?>" <?=IntIsSelected($tgl, $i)?>><?=$i?></option>
		<?php } ?>           
          	</select>-->
            
            <div id="tgl_info">
            <select name="tgllahir" id="tgllahir"  onKeyPress="return focusNext('blnlahir', event)" onFocus="panggil('tgllahir')">
        	<option value="">[Tgl]</option>  
       	<?php 	for($i=1;$i<=$n;$i++){ ?>      
		    <option value="<?=$i?>" <?=IntIsSelected($tgllahir, $i)?>><?=$i?></option>
		<?php } ?>           
          	</select>
            </div>       	</td>
       	<td>
          	<select name="blnlahir" id="blnlahir" onKeyPress="return focusNext('thnlahir', event)" onChange="change_bln()" onFocus="panggil('blnlahir')">
          	<option value="">[Bulan]</option>
        <?php 	for ($i=1;$i<=12;$i++) { ?>
          	<option value="<?=$i?>" <?=IntIsSelected($blnlahir, $i)?>><?=$bulan_pjg[$i]?></option>	
       	<?php }	?>
          </select>
         	<input type="text" name="thnlahir" id="thnlahir" size="5" maxlength="4" onFocus="showhint('Tahun Lahir tidak boleh kosong!', this, event, '75px');panggil('thnlahir')" value="<?=$thnlahir?>" onKeyPress="return focusNext('agama', event)"/></td>
    </tr>
    <tr>
      	<td><strong>Agama</strong></td>
      	<td colspan="2">
          	<div id="agama_info">
          	<select class="ukuran" name="agama" id="agama" onKeyPress="return focusNext('suku', event)" onFocus="panggil('agama')">
            <option value="">[Pilih Agama]</option>
        <?php 	 
		  	$query_a="select agama from jbsumum.agama order by urutan asc " ;
		  	$result_a=QueryDb($query_a) or (mysqli_error($mysqlconnection)) ;
		  	while($row_a=mysqli_fetch_array($result_a)) 	{
		?>		<option value="<?=$row_a['agama']?>"<?=StringIsSelected($row_a['agama'],$agama)?>><?=$row_a['agama']?></option>
		<?php  }   ?>
          	</select>
       	<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
          	<img src="../images/ico/tambah.png" border="0" onClick="tambah_agama();" onMouseOver="showhint('Tambah Agama!', this, event, '50px')">
          <?php } ?>								
          	</div>     </td>
    </tr>
    <tr>
    	<td><strong>Suku</strong></td>
        <td colspan="2"><div id="suku_info">
           	<select name="suku" id="suku" class="ukuran"  onKeyPress="return focusNext('menikah', event)" onFocus="panggil('suku')">
             <option value="">[Pilih Suku]</option>
        	<?php // Olah untuk combo suku
			$sql_suku="SELECT suku,urutan,replid FROM jbsumum.suku ORDER BY urutan";
			$result_suku=QueryDB($sql_suku);
			while ($row_suku = mysqli_fetch_array($result_suku)) {
				//if($suku == "")
				//	$suku = $row_suku['suku'] ;  
			?>
              	<option value="<?=$row_suku['suku']?>"<?=StringIsSelected($row_suku['suku'],$suku)?>><?=$row_suku['suku']?></option>
<?php
    		} 
			// Akhir Olah Data suku
			?>
           	</select>
           	<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            <img src="../images/ico/tambah.png" onClick="tambah_suku();" onMouseOver="showhint('Tambah Suku!', this, event, '50px')" />
            <?php } ?>
            </div></td>
   </tr>
   <tr>
       	<td>Menikah</td>
       	<td colspan="2">
            <input type="radio" id="menikah"  name="menikah" value="menikah" <?php if ($menikah == 'menikah') echo 'checked';?>  onKeyPress="return focusNext('identitas', event)" />&nbsp;Sudah&nbsp;
            <input type="radio" id="menikah"  name="menikah" value="belum" <?php if ($menikah == 'belum') echo 'checked';?>  onKeyPress="return focusNext('identitas', event)"/>&nbsp;Belum&nbsp;
	        <input name="menikah" type="radio" id="menikah" value="tak_ada" <?php if ($menikah == 'tak_ada' || $menikah =="") echo 'checked';?>  onKeyPress="return focusNext('identitas', event)"/>&nbsp;(Tidak ada data)            </td>
   </tr>
   <tr>
      	<td>No. Identitas</td>
      	<td colspan="2"><input type="text" name="identitas" id="identitas" value="<?=$identitas?>" onKeyPress="return focusNext('alamat', event)" onFocus="panggil('identitas')"/></td>
   </tr>
   <tr>
       	<td valign="top">Alamat</td>
       	<td colspan="2">
        <textarea name="alamat" id="alamat" cols="40" rows="2" onKeyPress="return focusNext('telpon', event)" onFocus="panggil('alamat')"><?=$alamat?></textarea></td>
   </tr>
   <tr>
       	<td>Telepon</td>
       	<td colspan="2"><input type="text" name="telpon" id="telpon" value="<?=$telpon?>" onKeyPress="return focusNext('handphone', event)" onFocus="panggil('telpon')"/></td>
   </tr>
   <tr>
        <td>Handphone</td>
        <td colspan="2"><input type="text" name="handphone" id="handphone" value="<?=$handphone?>" onKeyPress="return focusNext('email', event)" onFocus="panggil('handphone')"/></td>
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
        <td valign="top" colspan="2"><textarea name="keterangan" id="keterangan" cols="40" rows="2" onKeyPress="return focusNext('simpan', event)" onFocus="panggil('keterangan')"><?=$keterangan?></textarea>        </td>
    </tr>
	<tr>
    	<td align="center" colspan="3">
        <input type="submit" name="simpan" id="simpan" value="Simpan" class="but"  onFocus="panggil('simpan')"/>
        <input type="button" name="tutup" id="tutup" value="Tutup" class="but" onClick="window.close();" />          		</td>
	</tr>
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
</form>
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