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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");
require_once("../library/datearith.php");
require_once("daftarpresensi.func.php");

OpenDb();
ReadRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="daftarpresensi.js"></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#ffffff">
<br />
<input type="hidden" name="nip" id="nip" value="<?=$nip?>">    

<p align="center">
<font class="subtitle"><?=$nama?> - <?=$nip?></font><br />
<a href="JavaScript:refresh()"><img src="../images/ico/refresh.png" border="0" />&nbsp;refresh</a>&nbsp;
<a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" />&nbsp;cetak</a>&nbsp;
<br />
</p>

<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Riwayat Presensi <?=$nama?> - <?=$nip?></font><br />
    </td>
</tr>
<tr>
    <td align="left">
    
    <br>
    <?php ShowSelectDate(); ?>
    
    </td>
</tr>
<tr>
    <td align="left">
    
    <br>
    <?php ShowStatistics(); ?>
    
    </td>
</tr>
<tr>
    <td align="left">
    
    <br>
    <?php ShowDetail(); ?>
    
    </td>
</tr>
</table>

<?php
CloseDb();
?>    
<script language='JavaScript'>
	//Tables('table', 1, 0);
</script>
</body>
</html>



