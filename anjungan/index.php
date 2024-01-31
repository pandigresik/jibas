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
require_once('../include/mainconfig.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS - ANJUNGAN INFORMASI</title>
<link rel="shortcut icon" href="images/jibas2015.ico" />
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="../script/bgstretcher.css" />
<link rel="stylesheet" type="text/css" href="index.css" />
<link rel="stylesheet" type="text/css" href="berita/berita.css" />
<link rel="stylesheet" type="text/css" href="style/jquery-jvert-tabs-1.1.4.css" />
<link rel="stylesheet" type="text/css" href="script/SpryAssets/SpryTabbedPanels.css" />
<link rel="stylesheet" type="text/css" href="script/mktree.css" />
<link rel="stylesheet" type="text/css" href="script/lytebox.css" />
<link rel="stylesheet" type="text/css" href="script/themes/south-street/jquery.ui.all.css"  />    
<script type="text/javascript" language="javascript" src="script/mktree.js"></script>
<script type="text/javascript" language="javascript" src="script/SpryAssets/SpryTabbedPanels.js"></script>
<script type="text/javascript" language="javascript" src='script/ajax.js'></script>
<script type="text/javascript" language="javascript" src='script/tools.js'></script>
<script type="text/javascript" language="javascript" src='script/string.js'></script>
<script type="text/javascript" language="javascript" src='script/jquery-latest.js'></script>
<script type="text/javascript" language="javascript" src="script/jquery-jvert-tabs-1.1.4.js"></script>
<script type="text/javascript" language="javascript" src="script/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" language="javascript" src="script/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" language="javascript" src='script/rupiah2.js'></script>
<script type="text/javascript" language="javascript" src='script/validator.js'></script>
<script type="text/javascript" language="javascript" src='script/lytebox.js'></script>
<script type="text/javascript" language="javascript" src="../script/bgstretcher.js"></script>
<script type="text/javascript" language="javascript" src="../script/footer.js"></script>
<script type="text/javascript" language="javascript" src="beranda/beranda.js"></script>
<script type="text/javascript" language="javascript" src="beranda/beranda.listbg.js?r="<?php filemtime("beranda/beranda.listbg.js") ?>></script>
<script type="text/javascript" language="javascript" src="berita/berita.js"></script>
<script type="text/javascript" language="javascript" src="infosiswa/infosiswa.js"></script>
<script type="text/javascript" language="javascript" src="infosiswa/infosiswa.cbe.js"></script>
<script type="text/javascript" language="javascript" src="jadwalguru/jadwalguru.js"></script>
<script type="text/javascript" language="javascript" src="jadkal/jadkal.js"></script>
<script type="text/javascript" language="javascript" src="pegawai/struktur.js"></script>
<script type="text/javascript" language="javascript" src="psb/psb.js"></script>
<script type="text/javascript" language="javascript" src="mading/mading.js"></script>
<script type="text/javascript" language="javascript" src="infosekolah/infosekolah.js"></script>
<script type="text/javascript" language="javascript" src="pustaka/pustaka.js"></script>
<script type="text/javascript" language="javascript" src="index.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="padding:0px; margin:0px;">
	
<div style="position:relative; z-index:2;">
<!--
<input type="text" id="debug1">
<input type="text" id="debug2">
<input type="text" id="debug3">
//-->
<table border="0" cellpadding="0" cellspacing="0" align="center" width="980" >
<tr>
	<td align="center" valign="middle">
	<table border="0" cellpadding="5">
	<tr>
		<td>
			<img src="../images/<?= $G_LOGO_DEPAN_KIRI ?>">
		</td>
		<td width="*" align="center">
			<font style="font-family:Arial; font-size:14px; color:#fff; font-weight: bold; ">
			ANJUNGAN INFORMASI
			</font><br>
			<font style="font-family:Tahoma; font-size:16px; color:#fff; ">
			<?= $G_JUDUL_DEPAN_1 ?>
			</font><br>
			<font style="font-family:Tahoma; font-size:11px; color:#fff; ">
			<?= $G_JUDUL_DEPAN_2 ?>
			</font><br>
		</td>
		<td>
			<img src="../images/<?= $G_LOGO_DEPAN_KANAN ?>">
		</td>
	</tr>
	</table>		
	<br>
    </td>
</tr>
<tr>
	<td align="center">
<?php	include('index.tab.php'); ?>		
	</td>
</tr>
</table>

<div id="Partner">
<?php
$_REQUEST = array();
$_REQUEST['relpath'] = "..";
include('../partner.php');
?>
</div>

<div id="Footer">
<?php
include('../footer.php');
?>
</div>

</div>
</body>
</html>