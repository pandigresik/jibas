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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function ShowDetail(sat, jk) {
	parent.statdetail.location.href = "statdetailjk.php?sat="+sat+"&jk="+jk;
}

function CetakWord() {
	var addr = "cetakword.php?key=<?=$key?>&keyword=<?=$keyword?>";
	newWindow(addr, 'StatWord','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body style="background-color:#DFDFDF">
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%">
<tr height="25">
	<td class="header" align="center" width="5%">No</td>
    <td class="header" align="center" width="60%">Satuan Kerja</td>
    <td class="header" align="center" width="15%">L</td>
    <td class="header" align="center" width="15%">P</td>
</tr>
<?php
OpenDb();
$sql = "SELECT j.satker, SUM(IF(p.kelamin = 'L', 1, 0)) AS Pria, SUM(IF(p.kelamin = 'P', 1, 0)) AS Wanita
		  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
		  WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND NOT j.satker IS NULL
		  GROUP BY j.satker";	
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) {
?>
<tr height="20">
	<td align="center" valign="top"><?=++$cnt?></td>
    <td align="center" valign="top"><?=$row[0]?></td>
    <td align="center" valign="top">
    	<a href="JavaScript:ShowDetail('<?=$row[0]?>','L')">
		<?=$row[1]?>&nbsp;<img src="../images/ico/lihat.png" border="0" />
        </a>
    </td>
    <td align="center" valign="top">
    	<a href="JavaScript:ShowDetail('<?=$row[0]?>','P')">
		<?=$row[2]?>&nbsp;<img src="../images/ico/lihat.png" border="0" />
        </a>
    </td>
</tr>
<?php
}
CloseDb();
?>
</table>
<script language='JavaScript'>
   Tables('table', 1, 0);
</script>

</body>
</html>