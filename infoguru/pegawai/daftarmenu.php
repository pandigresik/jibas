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
require_once('../include/sessionchecker.php');
require_once("../include/sessioninfo.php");
require_once('../include/config.php');
require_once('../include/db_functions.php');

$nip = SI_USER_ID();

OpenDb();
$sql = "SELECT replid FROM jbssdm.pegawai WHERE nip='$nip'";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$replid = $row[0];
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style.css" />
<script language="javascript" src="../script/string.js"></script>
<script language="javascript">
function ChangePage(menu)
{
	var nip = document.main.nip.value;
	var replid = document.main.replid.value;
	
	nip = trim(nip);
	
	if (nip.length > 0)
	{
		if (menu == 1)
			parent.daftarisi.location.href = "daftarpribadi.php?nip=" + nip+"&replid="+replid;
		else if (menu == 2)
			parent.daftarisi.location.href = "daftargolongan.php?nip=" + nip;		
		else if (menu == 3)
			parent.daftarisi.location.href = "daftarjabatan.php?nip=" + nip;	
		else if (menu == 4)
			parent.daftarisi.location.href = "daftarpensiun.php?nip=" + nip;	
		else if (menu == 5)
			parent.daftarisi.location.href = "daftardiklat.php?nip=" + nip;	
		else if (menu == 6)
			parent.daftarisi.location.href = "daftarsekolah.php?nip=" + nip;	
		else if (menu == 7)
			parent.daftarisi.location.href = "daftarserti.php?nip=" + nip;
		else if (menu == 8)
			parent.daftarisi.location.href = "daftarkerja.php?nip=" + nip;
		else if (menu == 9)
			parent.daftarisi.location.href = "daftarkeluarga.php?nip=" + nip;
		else if (menu == 10)
			parent.daftarisi.location.href = "daftarpresensi.php?nip=" + nip;
		else if (menu == 11)
			parent.daftarisi.location.href = "daftarsemua.php?nip=" + nip;			
	}
}

function ChangePage2(mn)
{
	document.main.menu[mn - 1].checked = true;
	ChangePage(mn);
}
</script>
</head>

<body>
<form name="main">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
    <td width="100%" align="right">
	    <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Data Pegawai</font><br />
        <br /><br />
    </td>
</tr>
<tr height="30">
	<td width="100%" align="left" bgcolor="#F0F0F0">
        <input type="hidden" name="nip" value="<?=$nip?>"/>
        <input type="hidden" name="replid" value="<?=$replid?>"/>
		<table border="0" cellpadding="0" cellspacing="0">
		<tr height="30">
			<td width="70" align="left" valign="middle" style="background-color: #E7BB1C">
				<input type="radio" name="menu" id="menu" value="1" checked onclick="JavaScript:ChangePage(1)"/><a style='font-weight:normal' href="JavaScript:ChangePage2(1)">Pribadi</a>	
			</td>
			<td width="80" align="left" valign="middle" style="background-color: #E7BB1C">
				<input type="radio" name="menu" id="menu" value="2" onclick="JavaScript:ChangePage(2)"/><a style='font-weight:normal' href="JavaScript:ChangePage2(2)">Golongan</a>
			</td>
			<td width="70" align="left" valign="middle" style="background-color: #E7BB1C">
				<input type="radio" name="menu" id="menu" value="3" onclick="JavaScript:ChangePage(3)"/><a style='font-weight:normal' href="JavaScript:ChangePage2(3)">Jabatan</a>
			</td>
			<td width="70" align="left" valign="middle" style="background-color: #E7BB1C">
				<input type="radio" name="menu" id="menu" value="4" onclick="JavaScript:ChangePage(4)"/><a style='font-weight:normal' href="JavaScript:ChangePage2(4)">Pensiun</a>
			</td>
			<td width="60" align="left" valign="middle" style="background-color: #E7BB1C">
				<input type="radio" name="menu" id="menu" value="6" onclick="JavaScript:ChangePage(5)"/><a style='font-weight:normal' href="JavaScript:ChangePage2(5)">Diklat</a>
			</td>
			<td width="70" align="left" valign="middle" style="background-color: #E7BB1C">
				<input type="radio" name="menu" id="menu" value="7" onclick="JavaScript:ChangePage(6)"/><a style='font-weight:normal' href="JavaScript:ChangePage2(6)">Sekolah</a>
			</td>
			<td width="85" align="left" valign="middle" style="background-color: #E7BB1C">
				<input type="radio" name="menu" id="menu" value="8" onclick="JavaScript:ChangePage(7)"/><a style='font-weight:normal' href="JavaScript:ChangePage2(7)">Sertifikasi</a>
			</td>
			<td width="60" align="left" valign="middle" style="background-color: #E7BB1C">
				<input type="radio" name="menu" id="menu" value="9" onclick="JavaScript:ChangePage(8)"/><a style='font-weight:normal' href="JavaScript:ChangePage2(8)">Kerja</a>
			</td>
			<td width="80" align="left" valign="middle" style="background-color: #E7BB1C">
				<input type="radio" name="menu" id="menu" value="9" onclick="JavaScript:ChangePage(9)"/><a style='font-weight:normal' href="JavaScript:ChangePage2(9)">Keluarga</a>
			</td>
			<td width="80" align="left" valign="middle" style="background-color: #E7BB1C">
				<input type="radio" name="menu" id="menu" value="10" onclick="JavaScript:ChangePage(10)"/><a style='font-weight:normal' href="JavaScript:ChangePage2(10)">Presensi</a>
			</td>
			<td width="100" align="left" valign="middle" style="background-color: #000">
				&nbsp;<input type="radio" name="menu" id="menu" value="11" onclick="JavaScript:ChangePage(11)"/><a style="color:#FFF" href="JavaScript:ChangePage2(11)">[Semua]</a>
			</td>
		</tr>
		</table>
    </td>
</tr>
</table>
</form>
<script language="javascript">
	ChangePage(1);
</script>
</body>
</html>
