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

$namabulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];

$agenda = $_REQUEST['agenda'];
$bln = $_REQUEST['bln'];
$thn = $_REQUEST['thn'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<link rel="stylesheet" href="../script/mktree.css" />
<script language="javascript" src="../script/mktree.js"></script>
<style type="text/css">
<!--
.style1 {
	font-size: 18px;
	font-weight: bold;
}
.style2 {font-weight: bold}
-->
</style>
</head>
<body>

<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">
<?php include("../include/headercetak.php") ?>
<div align="center">
<br />
<?php
OpenDb();
?>
<span class="style2"><span class="style1">STRUKTUR ORGANISASI
<br /><br />
</span>
</div>

<?php
$sql = "SELECT replid, singkatan, jabatan FROM jabatan WHERE rootid=0";
$result = QueryDb($sql);
if (mysqli_num_rows($result) == 0) 
{
	echo "Belum ada data";
} 
else 
{
	$ntree = 0;
	while($row = mysqli_fetch_row($result))
	{
		$ntree++;
		$idjab = $row[0];
		$sing  = $row[1];
		$jab   = $row[2]; 
		$nsubdir = getNSubDir($idjab);
		
		$anchorflag = "<a name='item$idjab'></a>";
		
		echo "<ul class='mktree' id='tree$ntree'>\r\n";
		if ($nsubdir == 0)
		{
			echo "  <li class='liBullet' style='margin-left:20px'>$anchorflag&nbsp;$sing&nbsp;<br><br>";
			echo "	</li>\r\n";
		}
		else
		{
			echo "  <li class='liClosed' style='margin-left:20px'>$anchorflag&nbsp;$sing&nbsp;<br><br>";
			echo "  <ul>\r\n";
			traverse($idjab, 2);
			echo "  </ul></li>\r\n";
		}
		echo "</ul>\r\n";
	}
}

CloseDb();
?>

</td></tr>
</table>
</body>
<script language="javascript">
	setTimeout("DoExpand()", 100);
	
	function DoExpand()
    {
		expandTree('tree1');
        window.print();
	}
</script>
</html>

<?php
function getIdDel($idroot) {
	global $iddel;
	
	if (strlen((string) $iddel) > 0) 
		$iddel = $iddel . ",";
	$iddel = $iddel . $idroot;	
	
	$sql = "SELECT replid FROM jabatan WHERE rootid = $idroot";
	$result = QueryDb($sql);
	while ($row = mysqli_fetch_row($result)) 
		getIdDel($row[0]);
}

function getNSubDir($idroot) 
{
	$sql = "SELECT count(*) FROM jabatan WHERE rootid = $idroot";
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

function getEmployee($idjab)
{
    $employee = "";
    
    $sql = "SELECT TRIM(CONCAT(IFNULL(p.gelarawal,''), ' ' , p.nama, ' ', IFNULL(p.gelarakhir,'')))
              FROM pegawai p, pegjab pj
             WHERE p.nip = pj.nip
               AND pj.terakhir = 1 AND pj.idjabatan = '$idjab'
             ORDER BY p.nama";
    $result = QueryDb($sql);
    while ($row = mysqli_fetch_row($result))
	{
        if ($employee != "")
            $employee .= ", ";
        $employee .= $row[0];    
    }
    
    if ($employee == "")
        return "-";
    return $employee;
}

function getNEmployee($idjab)
{
    $sql = "SELECT COUNT(replid)
              FROM pegjab
             WHERE terakhir = 1
               AND idjabatan = '".$idjab."'";
    $result = QueryDb($sql);
    $row = mysqli_fetch_row($result);
	
    return $row[0];
}

function traverse($idroot, $count) 
{
	global $SI_USER_STAFF;
	
	$sql = "SELECT replid, singkatan, jabatan FROM jabatan WHERE rootid=$idroot";
	$result = QueryDb($sql);
	$space = spacing($count);
	
	while ($row = mysqli_fetch_row($result))
	{
		$idjab = $row[0];
		$sing  = $row[1];
		$jab   = $row[2];
		$nsubdir = getNSubDir($idjab);
				
		$anchorflag = "<a name='item$idjab'></a>";
		if ($nsubdir == 0)
		{
			echo "$space";
            echo "<li class='liBullet' style='margin-left:20px'>";
            
            echo "<span style='border:1px solid black; background-color:black; color:white; font-size:11px; font-weight:bold;'>&nbsp;&nbsp;";
            echo strtoupper((string) $jab) . " [" . getNEmployee($idjab) . "]";
            echo "&nbsp;&nbsp;</span><br>";
            echo "<table border='0' cellpadding='0' cellspacing='0' width='450px'>";
            echo "<tr><td width='20'>&nbsp;</td>";
            echo "<td style='border-width:1px; border-style:solid; border-color:black;'>";
            echo getEmployee($idjab);
            echo "</td>";
            echo "</tr></table>";
            echo "<br>";
            
			echo "</li>\r\n";
		}
		else
		{
			echo "$space<li class='liClosed' style='margin-left:20px'>";
            
            echo "<span style='border:1px solid black; background-color:black; color:white; font-size:11px; font-weight:bold;'>&nbsp;&nbsp;";
            echo strtoupper((string) $jab) . " [" . getNEmployee($idjab) . "]";
            echo "&nbsp;&nbsp;</span><br>";
            echo "<table border='0' cellpadding='0' cellspacing='0' width='450px'>";
            echo "<tr><td width='20'>&nbsp;</td>";
            echo "<td style='border-width:1px; border-style:solid; border-color:black;'>";
            echo getEmployee($idjab);
            echo "</td>";
            echo "</tr></table>";
            echo "<br>";
                        
			echo "$space<ul>\r\n";
			traverse($idjab, ++$count);
			echo "$space</ul></li>\r\n";
		}
	}
}
?>