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
require_once("../include/sessioninfo.php");

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pilih Jabatan</title>
<link rel="stylesheet" href="../style/style.css" />
<link rel="stylesheet" href="../script/mktree.css" />
<script language="javascript" src="../script/mktree.js"></script>
<script language="javascript">
function Pilih(id, jabatan) {
	opener.TerimaJabatan(id, jabatan);
	window.close();
}
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#ffffff">

<?php
function getNSubDir($idroot) 
{
	$sql = "SELECT count(*) FROM jbssdm.jabatan WHERE rootid = $idroot";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	return $row[0];
}

function spacing($count) 
{
	$str = "";
	for ($i = 0; $i < $count * 2; $i++) 
		$str = $str . " ";
	return $str;
}

function traverse($idroot, $count) 
{
	$sql = "SELECT replid, singkatan, jabatan FROM jbssdm.jabatan WHERE rootid=$idroot";
	$result = QueryDb($sql);
	$space = spacing($count);
	
	while ($row = mysqli_fetch_row($result)) {
		$idjab = $row[0];
		$sing  = $row[1];
		$jab   = $row[2];
		$nsubdir = getNSubDir($idjab);
		
		$sing = "<a href=\"#\" onclick=\"JavaScript:Pilih($idjab, '$jab')\">$sing</a>";
				
	
		if ($nsubdir == 0) {
			echo "$space<li class='liBullet'>&nbsp;$sing&nbsp;</li>\r\n";
		} else {
			echo "$space<li class='liClosed'>&nbsp;$sing&nbsp;\r\n";
			echo "$space<ul>\r\n";
			traverse($idjab, ++$count);
			echo "$space</ul></li>\r\n";
		}
	}
}

?>
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="right" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:18px">&nbsp;&nbsp;</font>
        <font class="subtitle">Pilih Jabatan</font><br />
        <a onclick="document.location.reload()" href="#">refresh</a>
    </td>
</tr>
<tr><td>
<a href="#" onclick="expandTree('tree1'); return false;">Expand All</a>&nbsp;|&nbsp;
<a href="#" onclick="collapseTree('tree1'); return false;">Collapse All</a><br /><br />
<?php
$sql = "SELECT replid, singkatan, jabatan FROM jbssdm.jabatan WHERE rootid=0";
$result = QueryDb($sql);
if (mysqli_num_rows($result) == 0) 
{
	echo "Belum ada data";
} 
else 
{
	$row = mysqli_fetch_row($result);
	$idjab = $row[0];
	$sing  = $row[1];
	$jab   = $row[2]; 
	$nsubdir = getNSubDir($idjab);
	
	$sing = "<a href=\"#\" onclick=\"JavaScript:Pilih($idjab, '$jab')\">$sing</a>";
	
	echo "<ul class='mktree' id='tree1'>\r\n";
	if ($nsubdir == 0) {
		echo "  <li class='liBullet'>&nbsp;$sing&nbsp;</li>\r\n";
	} else {
		echo "  <li class='liClosed'>&nbsp;$sing&nbsp;\r\n";
		echo "  <ul>\r\n";
		traverse($idjab, 2);
		echo "  </ul></li>\r\n";
	}
	echo "</ul>\r\n";
}
?>

</td></tr>
</table>
<?php
CloseDb();
?>
<script language="javascript">
	setTimeout("DoExpand()", 100);
	function DoExpand() {
		expandTree('tree1'); 
	}
</script>
</body>
</html>
