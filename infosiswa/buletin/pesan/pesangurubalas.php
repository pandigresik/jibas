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
require_once('../../include/common.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/getheader.php');
require_once('../../include/db_functions.php');
require_once('../../include/sessionchecker.php');

$idpesan="";
if (isset($_REQUEST['idpesan']))
	$idpesan=$_REQUEST['idpesan'];
$senderstate="";
if (isset($_REQUEST['senderstate']))
	$senderstate=$_REQUEST['senderstate'];	
$idguru=SI_USER_ID();
OpenDb();
if ($senderstate=="guru")
	$sql = "SELECT p.judul,p.pesan,p.idguru,peg.nama FROM jbsvcr.pesan p, jbssdm.pegawai peg WHERE p.replid='$idpesan' AND p.idguru=peg.nip";
elseif ($senderstate=="siswa")
	$sql = "SELECT p.judul,p.pesan,p.nis,sis.nama FROM jbsvcr.pesan p, jbsakad.siswa sis WHERE p.replid='$idpesan' AND p.nis=sis.nis";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$judul = $row[0];
$pesan = $row[1];
$receiver = $row[2];
$nama = $row[3];

CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../../style/calendar-win2k-1.css">
<script type="text/javascript" src="../../script/calendar.js"></script>
<script type="text/javascript" src="../../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../../script/calendar-setup.js"></script>
<script language="javascript" src="../../script/tables.js"></script>
<script src="../../script/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../../script/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="../../script/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "silver",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",		
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "forecolor,backcolor,fullscreen,print,|,cut,copy,paste,pastetext,|,search,replace,|,bullist,numlist,|,hr,removeformat,|,sub,sup,|,charmap,image",
		theme_advanced_buttons3 : "tablecontrols",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,
		content_css : "../../style/content.css"
	});
	
	
	function OpenUploader() {
	    var addr = "UploaderMain.aspx";
	    newWindow(addr, 'Uploader','720','630','resizable=1,scrollbars=1,status=0,toolbar=0');
    }
function validate(){
	var judul=document.getElementById('judul').value;
	var pesan=tinyMCE.get('pesan').getContent();
	if (judul.length==0){
		alert ('Anda harus mengisikan data untuk Judul Pesan');
		document.getElementById('judul').focus();
		return false;
	}
	if (pesan.length==0){
		alert ('Anda harus mengisikan data untuk Pesan');
		document.getElementById('pesan').focus();
		return false;
	}
	simpan();
}
function hapusfile(field){
	document.getElementById(field).value="";
}
</script>
</head>
<body onload="document.getElementById('judul').focus();">
<form name="pesanguru" id="pesanguru" action="pesansimpan.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="kesiapa" id="kesiapa" value="<?=$senderstate?>" />
<input type="hidden" name="idpesan" id="idpesan" value="<?=$idpesan?>" />
<input type="hidden" name="receiver" id="receiver" value="<?=$receiver?>"/>
<input type="hidden" name="balas" id="balas" value="1"/>
<input type="hidden" name="jum" id="jum" value="1"/>
<input type="hidden" name="idguru" id="idguru" value="<?=$idguru?>" />
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td scope="row" align="left"><strong><font size="2" color="#999999">Balas Pesan :</font></strong><br /><br /></td>
  </tr>
  <tr>
    <td scope="row" align="left">
    <table width="100%" border="0" cellspacing="2" cellpadding="2"  >
  <tr >
    <th width="134" scope="row"><div align="left">Kepada</div></th>
    <td colspan="2"><input type="text" maxlength="254" name="receiver2" id="receiver2" size="50" value="[<?=$receiver?>] <?=$nama?>" disabled="disabled"/></td>
  </tr>
  <tr >
    <th width="134" scope="row"><div align="left">Judul</div></th>
    <td colspan="2"><input type="text" maxlength="254" name="judul" id="judul" size="50" value="Balasan : <?=$judul?>" /></td>
  </tr>
  <tr>
    <th colspan="3" valign="top" align="left" scope="row"  ><div align="left"><fieldset><legend>Pesan</legend>
          <textarea name="pesan" rows="20" id="pesan" style="width:100%">
          <br /><br /><br />
          <div style='padding-left:10px'>
		  <i style='color:#006633; font-size:10px' >--- Pesan asli dari <?=$nama?> (<?=$tgl?> <?=$waktu?>) --- </i><br />
		  <?=$pesan?>
		  </div>
          </textarea>
    </fieldset></div></th>
    </tr>
  <tr>
    <th colspan="3" scope="row">
	<button name="kirim" value="Kirim" type="submit" class="but style1" id="kirim" style="width:100px;" />Kirim
              </button>	
	</th>
    </tr>
</table>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("judul");
var sprytextfield2 = new Spry.Widget.ValidationTextField("receiver2");
</script>