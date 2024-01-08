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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');
require_once('../cek.php');

$replid = "";
if (isset($_REQUEST['replid']))
	$replid = $_REQUEST['replid'];
	
if ($_REQUEST['ganti'] == 1)	{
?>
<script language="javascript">
	document.location.href = "logo.php?gambar=<?=$_REQUEST['gambar']?>&replid=<?=$_REQUEST['replid']?>&ganti=0";
	///document.location.href = "logo.php?ganti=0";
	
</script>
<?php } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Input Logo Sekolah]</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" type="text/javascript">

function validate(send){
	var logo=document.getElementById("logo").value;
	var gambar=document.getElementById("gambar").value;
	
	var x = logo.explode('.');
	ext = x[(x.length-1)];
	
	
	if (logo.length > 0 || gambar.length > 0) {
		if (send != 'submit') {
			if (ext!='JPG' && ext!='jpg' && ext!='Jpg' && ext!='JPg' && ext!='JPEG' && ext!='jpeg'){
				alert ('Format Gambar harus ber-extensi jpg atau JPG!');
				document.getElementById("gambar").value='';
				return false;
			} 
		}
	} else {
		alert ('Field foto harus diisi!');
		return false;
	}
}

function ganti() {	
	if (validate() != false) 
		document.getElementById("main").submit();
}

function showFoto(x) {
	document.getElementById("upload").innerHTML = x;
}

</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('gambar').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Logo Sekolah :.
    </div>	
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="215">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF" valign="top">
    <!-- CONTENT GOES HERE //--->    
    <form name="main" id="main" method="post" enctype="multipart/form-data" action="getfoto.php" onSubmit="return validate('submit')">
    <input type="hidden" name="replid" id="replid" value="<?=$replid?>"/>
	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
	<!-- TABLE CONTENT -->
	<tr height="100" align="center">
    	<td>
        	<img src="../library/gambar.php?replid=<?=$replid?>&table=jbsumum.identitas" border="0"/>
        	
				<?php if (isset($_REQUEST['gambar'])) { 
                        if ($replid != 0) {
                ?>
                        &nbsp;<img src="../images/panah.png" border="0" width="100" height="100"/>&nbsp;&nbsp;
                <?php 	} ?>
                    <img src="../library/gambar1.php" border="0"/>
                    <!--<img src="../images/logokecil.jpg" border="0" />-->
                <?php } ?>
			<?php 	if ($replid == 0 && !isset($_REQUEST['gambar']) ) { ?>
            	<font size = "2"  color="#757575"><b>Klik <i>Browse...</i> untuk memilih gambar.</b></font>
            <?php } ?>
        </td>
    </tr>
    <tr height="50" id="tr">
        <td align="center"><p>      
        	 <?php if (isset($_REQUEST['gambar'])) { ?>
            		<?=$_REQUEST['gambar']?>		   			
                    <!--<input size="20px" type="text" name="logo1" id="logo1" value="<?=$_REQUEST['gambar']?>"/>-->
            <?php } ?></p>
            <input size="75px" type="file" name="gambar" id="gambar" style="width:470px" onChange="ganti()"  /> 
           	<input type="hidden" name="logo" id="logo"  value="<?=$_REQUEST['gambar']?>"/>
        </td>
    </tr>
    <tr>
        <td align="center" colspan="2">
            <input class="but" type="submit" value="Simpan" id="Simpan" name="Simpan">
            <input class="but" type="button" value="Tutup" onClick="window.close();">
        </td>
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
</body>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("gambar");
</script>