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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

$replid = $_REQUEST['replid'];

if (isset($_REQUEST['kalender']))
	$kalender = $_REQUEST['kalender'];

OpenDb();
$sql = "SELECT k.kalender, t.tglmulai, t.tglakhir, k.departemen, a.kegiatan, a.idkalender, a.tanggalawal, a.tanggalakhir, a.keterangan FROM kalenderakademik k, aktivitaskalender a, tahunajaran t WHERE a.replid = '$replid' AND a.idkalender = k.replid AND k.idtahunajaran = t.replid";

$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$departemen = $row['departemen'];
$akademik = $row['kalender'];
$periode = LongDateFormat($row['tglmulai']).' s/d '.LongDateFormat($row['tglakhir']);
$periode1 = RegularDateFormat($row['tglmulai']);
$periode2 = RegularDateFormat($row['tglakhir']);
$kegiatan = $row['kegiatan'];
$tglmulai = TglText($row['tanggalawal']);
$tglakhir = TglText($row['tanggalakhir']);
$keterangan = CQ($row['keterangan']);
$kalender = $row['idkalender'];
CloseDb();
if (isset($_REQUEST['kegiatan']))
	$kegiatan = CQ($_REQUEST['kegiatan']);
if (isset($_REQUEST['keterangan']))
	$keterangan = CQ($_REQUEST['keterangan']);
if (isset($_REQUEST['tglmulai']))
	$tglmulai = $_REQUEST['tglmulai'];
if (isset($_REQUEST['tglakhir']))
	$tglakhir = $_REQUEST['tglakhir'];
		
if (isset($_REQUEST['Simpan'])) {
	OpenDb();
	$sql = "SELECT * FROM aktivitaskalender WHERE kegiatan = '$kegiatan' AND idkalender = '$kalender' AND replid <> '$replid'";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {
		CloseDb();
		?>
        <script language="javascript">
			alert ('Nama kegiatan <?=$kegiatan?> sudah digunakan!');
		</script>
        <?php 
		
	} else {
		$tanggalawal=TglDb($tglmulai);
		$tanggalakhir=TglDb($tglakhir);
				
		$sql_simpan="UPDATE jbsakad.aktivitaskalender SET idkalender='$kalender', tanggalawal='$tanggalawal', tanggalakhir='$tanggalakhir', kegiatan='$kegiatan', keterangan='$keterangan' WHERE replid = '".$replid."'";
		//echo 'simpan '.$sql_simpan;
		$result_simpan=QueryDb($sql_simpan);
		
		if ($result_simpan){
		?>
			<script language="javascript">
			opener.refresh();
			window.close();
			</script>
		<?php
		} 
	}
}
		



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>JIBAS SIMAKA [Ubah Kegiatan Akademik]</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style/calendar-system.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link href="../style/style.css" rel="stylesheet" type="text/css">
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script type="text/javascript" src="../script/tools.js"></script>
<script type="text/javascript" src="../script/calendar.js"></script>
<script type="text/javascript" src="../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../script/calendar-setup.js"></script>
<script src="../script/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script language="javascript">
//textarea
tinyMCE.init({
	mode : "textareas",
	theme : "simple",
	plugins : "safari,pagebreak,style,layer,table,save,advhr,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",		
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,forecolor,backcolor,fullscreen,print",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : false,
	content_css : "../style/word.css"
});

function validate(){	
	var kegiatan = document.main.kegiatan.value;
	var tglmulai = document.main.tglmulai.value;
	var tglakhir = document.main.tglakhir.value;	
	var periodemulai = document.main.periode1.value;
	var periodeakhir = document.main.periode2.value;

	var tgl = "";	
	var bln = "";
	var th = "";
	var tgl1 = "";
	var bln1 = "";
	var th1 = "";
	
	var tglperiode = "";	
	var blnperiode = "";
	var thperiode = "";
	var tglperiode1 = "";
	var blnperiode1 = "";
	var thperiode1 = "";
	
	if (kegiatan.length == 0) {
		alert("Anda harus mengisikan data untuk nama kegiatan");
		document.getElementById("kegiatan").focus();
		return false;
	}
	
	if (tglmulai.length == 0) {	
		alert("Anda harus mengisikan data untuk tanggal mulai");
		return false;
	}
	
	if (tglakhir.length == 0) {	
		alert("Anda harus mengisikan data untuk tanggal akhir");
		return false;
	}
			
	if (periodemulai.length > 0 && periodeakhir.length > 0) {
		for (i = 0; i<periodemulai.length;i++){
			if (i < 2) {
				if (i == 0 && periodemulai.charAt(0) == '0' ) 
					tglperiode = "";	
				else 
					tglperiode = tglperiode + periodemulai.charAt(i);
							
				if (i == 0 && periodeakhir.charAt(0) == '0' ) 
					tglperiode1 = "";					
			 	else 
					tglperiode1 = tglperiode1 + periodeakhir.charAt(i);
			} else if (i < 5 && i > 2) {
				blnperiode = blnperiode + periodemulai.charAt(i);
				blnperiode1 = blnperiode1 + periodeakhir.charAt(i);				
			} else if (i < periodemulai.length && i > 5 ) {				
				thperiode = thperiode + periodemulai.charAt(i);
				thperiode1 = thperiode1 + periodeakhir.charAt(i);				
			}	 
		}
	
		tglperiode = parseInt(tglperiode);
		tglperiode1 = parseInt(tglperiode1);
		blnperiode = parseInt(blnperiode);
		blnperiode1 = parseInt(blnperiode1);
		thperiode = parseInt(thperiode);
		thperiode1 = parseInt(thperiode1);	
	}
		
		
	if (tglmulai.length > 0 && tglakhir.length > 0) {
		for (i = 0; i<tglmulai.length;i++){
			if (i < 2) {
				if (i == 0 && tglmulai.charAt(0) == '0' ) 
					tgl = "";	
				else 
					tgl = tgl + tglmulai.charAt(i);
							
				if (i == 0 && tglakhir.charAt(0) == '0' ) 
					tgl1 = "";					
			 	else 
					tgl1 = tgl1 + tglakhir.charAt(i);
			} else if (i < 5 && i > 2) {
				if (i == 3 && tglmulai.charAt(3) == '0' ) 
					bln = "";	
				else 
					bln = bln + tglmulai.charAt(i);
							
				if (i == 3 && tglakhir.charAt(3) == '0' ) 
					bln11 = "";					
			 	else 
					bln1 = bln1 + tglakhir.charAt(i);				
			} else if (i < tglmulai.length && i > 5 ) {				
				th = th + tglmulai.charAt(i);
				th1 = th1 + tglakhir.charAt(i);				
			}	 
		}
	
		tgl = parseInt(tgl);
		tgl1 = parseInt(tgl1);
		bln = parseInt(bln);
		bln1 = parseInt(bln1);
		th = parseInt(th);
		th1 = parseInt(th1);		
		
		
	}
				
			
		if (thperiode > th) {
			alert ('Pastikan tahun awal kegiatan berada dalam periode kalender akademik');			
			return false;
		} 
		
		if (th == thperiode && blnperiode > bln) {
			alert ('Pastikan bulan awal kegiatan berada dalam periode kalender akademik');
			return false; 
		}	
	
		if (th == thperiode && bln == blnperiode && tglperiode > tgl ) { 
			alert ('Pastikan tanggal awal kegiatan berada dalam periode kalender akademik');
			return false;
		} 
		
		if (th1 > thperiode1) {
			alert ('Pastikan tahun akhir kegiatan berada dalam periode kalender akademik');			
			return false;
		} 
	
		if (th1 == thperiode1 && bln1 > blnperiode1) {
			alert ('Pastikan bulan akhir kegiatan berada dalam periode kalender akademik');
			return false; 
		}	
	
		if (th1 == thperiode1 && bln1 == blnperiode1 && tgl1 > tglperiode1 ) { 
			alert ('Pastikan tanggal akhir kegiatan berada dalam periode kalender akademik');
			return false;
		} 
		
		if (th > th1) {
			alert ('Pastikan batas tahun akhir tidak kurang dari batas tahun awal');
			return false;
		} 
	
		if (th == th1 && bln > bln1 ) {
			alert ('Pastikan batas bulan akhir tidak kurang dari batas bulan awal');
			return false; 
		}	
	
		if (th == th1 && bln == bln1 && tgl > tgl1 ) { 
			alert ('Pastikan batas tanggal akhir tidak kurang dari batas tanggal awal');			
			return false;
		} 
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
</script>

</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('kegiatan').focus()">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Kegiatan :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->

<form name="main" method="post" onSubmit="return validate()">
<input type="hidden" name="replid" id="replid" value ="<?=$replid ?>" />
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="130"><strong>Departemen</strong></td>
    <td><input type="text" class="disabled" name="departemen" size="10" value="<?=$departemen ?>" readonly/></td>
</tr>
<tr>
	<td><strong>Kalender Akademik</strong></td>
    <td colspan="2"><input type="text" class="disabled" name="akademik" readonly  size="30" value="<?=$akademik?>" />
	    <input type="hidden" name="kalender" id="kalender" value ="<?=$kalender ?>" />
    </td>
</tr>
<tr>
	<td><strong>Periode</strong></td>
    <td colspan="2"><input type="text" class="disabled" name="periode" readonly  size="40" value="<?=$periode?>" />
    <input type="hidden" name="periode1" id="periode1" value ="<?=$periode1 ?>" />
    <input type="hidden" name="periode2" id="periode2" value ="<?=$periode2 ?>" />
    </td>
</tr>
<tr>
	<td><strong>Nama Kegiatan</strong></td>
	<td colspan="2"><input type="text" name="kegiatan" id="kegiatan"  size="40" maxlength="50" value="<?=$kegiatan ?>" onFocus="showhint('Nama kegiatan tidak boleh lebih dari 50 karakter!', this, event, '120px')" onKeyPress="return focusNext('kegiatan', event)"/>
    </td>
</tr>
<tr>
	<td><strong>Tanggal Mulai</strong></td>
  	<td><input type="text" class="disabled" id="tglmulai" name="tglmulai" readonly  size="40" value="<?=$tglmulai?>"  onClick="Calendar.setup()"/>
    <td width="180"><img src="../images/calendar.jpg" name="tabel"border="0" id="btntglmulai" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/></td>
    
</tr>
<tr>
	<td><strong>Tanggal Akhir</strong></td>
    <td><input type="text" class="disabled" id="tglakhir" name="tglakhir" readonly  size="40" value="<?=$tglakhir?>"  onClick="Calendar.setup()"/>
    <td><img src="../images/calendar.jpg" name="tabel" border="0" id="btntglakhir" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/></td>   
</tr>

<tr>
	<td colspan = "3" height="200" valign="top">
	<fieldset><legend><b>Kegiatan</b></legend>
    <br />
    <textarea name="keterangan" id="keterangan" rows="20" style="width:100%"><?=$keterangan?></textarea>
    </fieldset>
</tr>
<tr>
  <td colspan="5"><div align="center">
    <input name="Simpan" id="Simpan" type="Submit" class="but" value="Simpan" >
    <input name="Submit2" type="button" class="but" value="Tutup" onClick="window.close();">
  </div></td>
  </tr>
</table>
		

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
<script type="text/javascript">
  Calendar.setup(
    {
      inputField  : "tglmulai",         // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format
      button      : "btntglmulai"       // ID of the button
    }
  );
   Calendar.setup(
    {
      inputField  : "tglmulai",        // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format	  
	  button      : "tglmulai"       // ID of the button
    }
  );
   Calendar.setup(
    {
      inputField  : "tglakhir",         // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format
      button      : "btntglakhir"       // ID of the button
    }
  );
  Calendar.setup(
    {
      inputField  : "tglakhir",         // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format
      button      : "tglakhir"       // ID of the button
    }
  );
  var sprytextfield1 = new Spry.Widget.ValidationTextField("kegiatan");
</script>
</body>
</html>