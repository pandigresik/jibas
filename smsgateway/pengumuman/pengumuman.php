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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('pengumuman.class.php');

$P = new Pengumuman();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SMS</title>
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<script language="javascript" src="../script/ShowError.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/jquery-1.4.2.js"></script>
<script language="javascript" src="pengumuman.js"></script>
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
</head>
<body onload="ResizeTabHeight()" onresize="ResizeTabHeight()">

<table id="MainTable" border="0" cellpadding="2" cellspacing="2" style="height:500px">
  <tr>
    <td height="50"  valign="top">
    <div id="SubTitle" align="left">
    <span style="color:#F90; background-color:#F90; font-size:20px">&nbsp;</span>&nbsp;<span style="color:#060; font-size:16px; font-weight:bold">Kirim Pengumuman</span><br />
    <a href="pengumuman.main.php">Pengumuman</a> > Kirim Pengumuman
    </div>
    </td>
    <td width="100%" rowspan="2" valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <?php
			$P->SelectReceiver();
			?>
            </td>
          </tr>
          <tr>
            <td style="padding-top:10px">
            <?php
			$P->ReceiverList();
			?>
            </td>
          </tr>
        </table>    </td>
  </tr>
  <tr>
    <td  valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <?php
			$P->MainMessage();
			?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<div style="width:100%">
	<?php
	$P->SendingTime();
	?>
</div>
<?php
$P->OnFinish();
?>
</body>
</html>
<script language="javascript">
	
</script>