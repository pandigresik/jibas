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
require_once('../include/common.php');
require_once('../include/sessioninfo.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
function delete($file) {
 if (file_exists($file)) {
   chmod($file,0777);
   if (is_dir($file)) {
     $handle = opendir($file); 
     while($filename = readdir($handle)) {
       if ($filename != "." && $filename != "..") {
         delete($file."/".$filename);
       }
     }
     closedir($handle);
     rmdir($file);
   } else {
     unlink($file);
   }
 }
}
OpenDb();
$varbaris=5;
if (SI_USER_ID()=="")
	exit;
OpenDb();
$res=QueryDb("SELECT * FROM jbsvcr.profil WHERE nip='".SI_USER_ID()."'");
$row=@mysqli_fetch_array($res);
CloseDb();

$op="";
if (isset($_REQUEST['op']))
	$op=trim((string) $_REQUEST['op']);
if (isset($_REQUEST['simpan'])){
	
	$foto=$_FILES["foto"];
	$uploadedfile = $foto['tmp_name'];
	$uploadedtypefile = $foto['type'];
	$uploadedsizefile = $foto['size'];
	if (strlen((string) $uploadedfile)!=0){
		//$gantifoto=", foto='$foto_data'";
  	if($uploadedtypefile=='image/jpeg')
    $src = imagecreatefromjpeg($uploadedfile);
	$filename = "tmpimage/x.jpg";
	[$width, $height]=getimagesize($uploadedfile);
	if ($width<$height){
	$newheight=320;
   	$newwidth=240;
	} else if ($width>$height){
	$newwidth=320;
   	$newheight=240;
	}
   	$tmp=imagecreatetruecolor($newwidth,$newheight);
   	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
  	imagejpeg($tmp,$filename,50);
	imagedestroy($src);
  	imagedestroy($tmp); // NOTE: menghapus file di temp
	$foto_data=addslashes(fread(fopen($filename,"r"),filesize($filename)));
	$gantifoto=", foto='$foto_data'";
	} else {
	$gantifoto=" ";
	}
	
	$foto2=$_FILES["latar"];
	$uploadedfile2 = $foto2['tmp_name'];
	$uploadedtypefile2 = $foto2['type'];
	$uploadedsizefile2 = $foto2['size'];
	if (strlen((string) $uploadedfile2)!=0){
		//$gantifoto=", foto='$foto_data'";
  	if($uploadedtypefile2=='image/jpeg')
    $src2 = imagecreatefromjpeg($uploadedfile2);
	$filename2 = "tmpimage/xxx.jpg";
	[$width2, $height2]=getimagesize($uploadedfile2);
	if ($width2<$height2){
	$newheight2=640;
   	//$newwidth=($width/$height)*480;
   	$newwidth2=480;
	} else if ($width2>$height2){
	$newwidth2=640;
   	//$newheight=($height/$width)*640;
   	$newheight2=480;
	}
	$tmp2=imagecreatetruecolor($newwidth2,$newheight2);
   	imagecopyresampled($tmp2,$src2,0,0,0,0,$newwidth2,$newheight2,$width2,$height2);
  	imagejpeg($tmp2,$filename2,50);
	imagedestroy($src2);
  	imagedestroy($tmp2); // NOTE: menghapus file di temp
	//$filename = "../images/ico/no_image.png";
    //$handle = fopen($filename, "r");
    //$foto_data = fread($handle, filesize($filename));
	$foto_data2=addslashes(fread(fopen($filename2,"r"),filesize($filename2)));
	//echo $foto_data;
	$gantifoto_bg=", bg='$foto_data2'";
	//echo $gantifoto;
	//exit;
	} else {
	$gantifoto_bg=" ";
	}

	
	

OpenDb();
$sql="SELECT * FROM jbsvcr.profil WHERE nip='".SI_USER_ID()."'";
$result=QueryDb($sql);
$ada=@mysqli_num_rows($result);
CloseDb();
if ($ada>0){
OpenDb();
$sql="UPDATE jbsvcr.profil SET nip='".SI_USER_ID()."',nama='".$_REQUEST['nama']."',alamat='".$_REQUEST['alamat']."',".
	 " telpon='".$_REQUEST['telpon']."', hp='".$_REQUEST['hp']."', email='".$_REQUEST['email']."',hobi='".$_REQUEST['hobi']."',".
	 " buku='".$_REQUEST['buku']."', riwayat='".$_REQUEST['riwayat']."', tentang='".$_REQUEST['tentang']."' ".$gantifoto.$gantifoto_bg."  WHERE replid= '".$_REQUEST['replid']."'";

$result=QueryDb($sql);
if ($result)
	reloadpage("profile.php");
} else {
OpenDb();
$sql_client="SELECT * FROM jbsclient.localinfo ORDER BY region,location,clientid";
	$result_client=QueryDb($sql_client);
	$row_client=@mysqli_fetch_array($result_client);
	
$sql="INSERT INTO jbsvcr.profil SET region='".$row_client['region']."',location='".$row_client['location']."',clientid='".$row_client['clientid']."',nip='".SI_USER_ID()."',nama='".$_REQUEST['nama']."',alamat='".$_REQUEST['alamat']."',".
	 " telpon='".$_REQUEST['telpon']."', hp='".$_REQUEST['hp']."', email='".$_REQUEST['email']."',hobi='".$_REQUEST['hobi']."',".
	 " buku='".$_REQUEST['buku']."', riwayat='".$_REQUEST['riwayat']."', tentang='".$_REQUEST['tentang']."' ".$gantifoto.$gantifoto_bg;
$result=QueryDb($sql);	 
CloseDb();
if ($result)
	reloadpage("profile.php");
//echo $sql; 
//exit;
}

}
function reloadpage($dest){
	?>
	<script language="javascript" type="text/javascript">
		document.location.href="<?=$dest?>";
	</script>
	<?php
}
?>
<html>
<head>
<title>Profil Guru</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style5 {
	font-size: 10px;
	color: #0000FF;
	font-style: italic;
}
-->
</style>
<script src="../script/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/resizing_background.js"></script>
<script language="javascript" type="text/javascript" src="../script/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
		mode : "textareas",
		theme : "simple",
		skin : "o2k7",
		skin_variant : "silver",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",		
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,forecolor,backcolor,fullscreen,print",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,image",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,
		content_css : "style/content.css"
	});
function chg_page(){
	var page=document.getElementById("page").value;
	document.location.href="profile.php?page="+page;
}
function change_page(page) {
	document.location.href="profile.php?page="+page;
}
</script>
</head>
<body style="background-attachment:fixed;"  background="../library/gambarlatar.php?replid=<?=$row['replid']?>&table=jbsvcr.profil"  onLoad="document.getElementById('nama').focus();rbInit();"  onResize="rbResize()" bgcolor="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<script>
// "true" means "keep the proportions of the original image."
// If you pass "false" the image fills the whole window,
// even if it must be distorted to do so. Experiment.
//rbOpen(true);
</script>
<form action="profile_edit.php" method="POST" enctype="multipart/form-data">
<input name="replid" id="replid" type="hidden" value="<?=$row['replid']?>" />
<!-- ImageReady Slices (back_login.psd) -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="20" colspan="3" >&nbsp;</td>
  </tr>  
  <tr>
    <td height="20" colspan="4" >&nbsp;&nbsp;<input name="ubah" type="button" class="header" style="width:100px;" value="Profil" onClick="document.location.href='profile.php'">&nbsp;<input name="tema" type="button" class="header" style="width:100px;" value="Tema Profil">&nbsp;<input name="ngobrol" type="button" class="header" style="width:100px;" value="Ajak Ngobrol">&nbsp;<input name="pesan" type="button" class="header" style="width:100px;" value="Kirim Pesan"></td>
  </tr>
  <tr>
    <td width="19" height="20" style="background-image:url(../images/bg_profil80_01.png); background-repeat:no-repeat; background-position:right;">&nbsp;</td>
    <td height="20" colspan="2" style="background-image:url(../images/bg_profil80_02.png); background-repeat:repeat-x;"></td>
    <td width="19" height="20" style="background-image:url(../images/bg_profil80_04.png); background-repeat:no-repeat;">&nbsp;</td>
  </tr>
  <tr>
    <td width="19" style="background-image:url(../images/bg_profil80_07.png); background-repeat:repeat-y; background-position:right;">&nbsp;</td>
    <td width="453" valign="top" style="background-image:url(../images/bg_profil80_08.png); background-repeat:repeat;">
 <table border="0" cellspacing="2" >
  <tr>
    <th width="57" height="25" scope="row"><div align="left">Nama </div></th>
    <th width="2" scope="row">:</th>
    <td width="294" height="25"><div align="left">
    <input name="nama" id="nama" type="text" value="<?=$row['nama']?>" />
    </div></td>
  </tr>
  <tr>
    <th height="25" scope="row"><div align="left">Alamat </div></th>
    <th width="2" height="25" scope="row">:</th>
    <td height="25"><div align="left">
    <input name="alamat" id="alamat" type="text" value="<?=$row['alamat']?>" />
    </div></td>
  </tr>
  <tr>
    <th height="25" scope="row"><div align="left">Telepon </div></th>
    <th width="2" height="25" scope="row">:</th>
    <td height="25"><div align="left">
    <input name="telpon" id="telpon" type="text" value="<?=$row['telpon']?>" /></div></td>
  </tr>
  <tr>
    <th height="25" scope="row"><div align="left">HP </div></th>
    <th width="2" height="25" scope="row">:</th>
    <td height="25"><div align="left">
    <input name="hp" id="hp" type="text" value="<?=$row['hp']?>" /></div></td>
  	</tr>
  <tr>
    <th height="25" scope="row"><div align="left">Email </div></th>
    <th width="2" height="25" scope="row">:</th>
    <td height="25"><div align="left">
  
    <input name="email" id="email" type="text" value="<?=$row['email']?>" /></div></td>
  </tr>
   <tr>
    <th width="57" valign="top" scope="row"><div align="left">Hobby </div>      
      <div align="left"></div></th>
    <th width="2" valign="top" scope="row">:</th>
    <th width="294" align="left" valign="top" scope="row"><div align="left">
      <textarea name="hobi" ><?=$row['hobi']?></textarea>
     
    </div></th>
  </tr>
  <tr>
    <th valign="top" scope="row"><div align="left">Foto</div></th>
    <th width="2" valign="top" scope="row">:</th>
    <th align="left" valign="top" scope="row"><img id="gambar" src="../library/gambar.php?replid=<?=$row['replid']?>&table=jbsvcr.profil" width="57" height="57" /><input name="foto" id="foto" type="file" size="20" title="Ganti Foto" /><br>
      <span class="style1 style5">(Isi untuk mengganti gambar profil..)      </span></th>
    </tr>
  <tr>
    <th valign="top" scope="row"><div align="left">Latar<br>
      Belakang</div></th>
    <th valign="top" scope="row">:</th>
    <th align="left" valign="top" scope="row"><img id="gambarlatar" src="../library/gambarlatar.php?replid=<?=$row['replid']?>&table=jbsvcr.profil" width="57" height="57" /><input name="latar" id="latar" type="file" size="20" title="Ganti Gambar Latar" /><br>
      <span class="style1 style5">(Isi untuk mengganti gambar latar belakang ..)      </span></th>
  </tr>
  <tr>
    <th colspan="3" scope="row"></th>
    </tr>
</table>    </td>
    <td width="465" valign="top" style="background-image:url(../images/bg_profil80_08.png); background-repeat:repeat;"><table border="0" cellspacing="2" >
 
  
  <tr>
    <th valign="top" scope="row"><div align="left">Buku&nbsp;Favorit</div></th>
    <th width="2" valign="top" scope="row">:</th>
    <th align="left" valign="top" scope="row"><div align="left">
      
      <textarea name="buku" ><?=$row['buku']?></textarea>
     
    </div></th>
  </tr>
  
  <tr>
    <th valign="top" scope="row"><div align="left">Riwayat&nbsp;Hidup</div></th>
    <th width="2" valign="top" scope="row">:</th>
    <th align="left" valign="top" scope="row"><div align="left">
      
      		<textarea name="riwayat" ><?=$row['riwayat']?></textarea>
      
    </div></th>
  </tr>
  <tr>
    <th valign="top" scope="row"><div align="left">Tentang&nbsp;Saya</div></th>
    <th width="2" valign="top" scope="row">:</th>
    <th align="left" valign="top" scope="row"><textarea name="tentang" ><?=$row['tentang']?></textarea></th>
  </tr>
  <tr>
    
  </tr>
  <tr>
    <th colspan="3" valign="top" scope="row">&nbsp;</th>
    </tr>
  <tr>
    <th colspan="3" scope="row"></th>
    </tr>
</table></td>
    <td width="19" style="background-image:url(../images/bg_profil80_09.png); background-repeat:repeat-y;">&nbsp;</td>
  </tr>
  <tr>
    <td style="background-image:url(../images/bg_profil80_07.png); background-repeat:repeat-y; background-position:right;">&nbsp;</td>
    <td colspan="2" valign="top" style="background-image:url(../images/bg_profil80_08.png); background-repeat:repeat;"><div align="center">
  <input name="simpan" type="submit" class="but" id="simpan" value="Simpan">
  &nbsp;&nbsp;
      <input name="batal" type="button" class="but" id="batal" value="Batal" onClick="window.self.history.back();">
    </div></td>
    <td style="background-image:url(../images/bg_profil80_09.png); background-repeat:repeat-y;">&nbsp;</td>
  </tr>
  <tr>
    <td width="19" height="23" style="background-image:url(../images/bg_profil80_10.png); background-repeat:no-repeat; background-position:right;">&nbsp;</td>
    <td height="23" colspan="2" style="background-image:url(../images/bg_profil80_11.png); background-repeat:repeat-x;">&nbsp;</td>
    <td width="19" height="23" style="background-image:url(../images/bg_profil80_12.png); background-repeat:no-repeat;">&nbsp;</td>
  </tr>
</table>
<!-- End ImageReady Slices -->
</form>
<script>
	//rbClose("../library/gambarlatar.php?replid=<?=$row['replid']?>&table=jbsvcr.profil");
</script>
</body>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("nama");
var sprytextfield2 = new Spry.Widget.ValidationTextField("alamat");
var sprytextfield3 = new Spry.Widget.ValidationTextField("telpon");
var sprytextfield4 = new Spry.Widget.ValidationTextField("hp");
var sprytextfield5 = new Spry.Widget.ValidationTextField("email");
</script>