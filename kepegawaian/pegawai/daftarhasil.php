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

OpenDb();

$nama = $_REQUEST['nama'];
$nip  = $_REQUEST['nip'];
$show = $_REQUEST['show'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />

<script language="javascript" src="../script/tables.js"></script>
<script language="javascript">
function ShowPegawai(nip,replid)
{
	var menu = 0;
	for(i = 0; i < parent.daftarmenu.document.main.menu.length; i++)
	{
		if (parent.daftarmenu.document.main.menu[i].checked)
		{
			menu = parent.daftarmenu.document.main.menu[i].value;
			break;
		}
	}
	//menu = parent.daftarmenu.document.main.mn.value;
	parent.daftarmenu.document.main.nip.value = nip;
	parent.daftarmenu.document.main.replid.value = replid;
	
	if (menu == 1)
		parent.daftarisi.location.href = "daftarpribadi.php?nip=" + nip +"&replid="+replid;
	else if (menu == 2)
		parent.daftarisi.location.href = "daftargolongan.php?nip=" + nip;		
	else if (menu == 3)
		parent.daftarisi.location.href = "daftarjabatan.php?nip=" + nip;	
	else if (menu == 4)
		parent.daftarisi.location.href = "daftarpensiun.php?nip=" + nip;	
	else if (menu == 5)
		parent.daftarisi.location.href = "daftargaji.php?nip=" + nip;	
	else if (menu == 6)
		parent.daftarisi.location.href = "daftardiklat.php?nip=" + nip;	
	else if (menu == 7)
		parent.daftarisi.location.href = "daftarsekolah.php?nip=" + nip;	
	else if (menu == 8)
		parent.daftarisi.location.href = "daftarserti.php?nip=" + nip;
	else if (menu == 9)
		parent.daftarisi.location.href = "daftarkerja.php?nip=" + nip;
	else if (menu == 10)
		parent.daftarisi.location.href = "daftarkeluarga.php?nip=" + nip;
	else if (menu == 11)
		parent.daftarisi.location.href = "daftarpresensi.php?nip=" + nip;				
	else if (menu == 12)
		parent.daftarisi.location.href = "daftarsemua.php?nip=" + nip;			
}

function Refresh() {
	//document.location.href = "daftarhasil.php?nip=<?=$nip?>&nama=<?=$nama?>&pns=<?=$pns?>&aktif=<?=$aktif?>";
	document.location.reload();
}
</script>
</head>

<body style="background-color:#F0F0F0">
<table border="0" cellpadding="2" cellspacing="2" width="100%" class="tab" id="table">
<tr height="30">
	<td class="header" width="15" align="center">No</td>
    <td class="header" width="70" align="center">NIP</td>
    <td class="header" width="130" align="center">Nama</td>
    <td class="header" width="15" align="center">&nbsp;</td>
</tr>
<?php
$sql = "";
if (strlen((string) $nama) > 0)
	$sql = "SELECT nip, nama, replid FROM pegawai WHERE nama LIKE '%$nama%' ORDER BY nama";
elseif (strlen((string) $nip) > 0)
	$sql = "SELECT nip, nama, replid FROM pegawai WHERE nip LIKE '%$nip%' ORDER BY nama"; 

if ($show == "all")
	$sql = "SELECT nip, nama, replid FROM pegawai ORDER BY nama"; 
	
if (strlen($sql) == 0) {
	CloseDb();
	echo "</table>";
	exit();
}
	
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_row($result)) {
?>
<tr>
	<td align="center"><?=++$cnt?></td>
    <td align="center"><?=$row[0]?></td>
    <td align="left"><?=$row[1]?></td>
    <td align="center">
    <input type="button" value=" > " class="but" onclick="JavaScript:ShowPegawai('<?=$row[0]?>',<?=$row[2]?>)" />
    </td>
</tr>
<?php
}
?>
</table>
<script language='JavaScript'>
	Tables('table', 1, 0);
</script>
</body>
</html>