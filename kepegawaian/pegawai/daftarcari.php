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
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/string.js"></script>
<script language="javascript">
function CariPegawai()
{
	var nama = document.getElementById('txNama').value;
	var nip  = document.getElementById('txNIP').value;
	
	nama = trim(nama);
	nip  = trim(nip);		
	if (nama.length > 0 || nip.length > 0)
		parent.daftarhasil.location.href = "daftarhasil.php?nama=" + nama + "&nip=" + nip;
}

function SemuaPegawai()
{
	parent.daftarhasil.location.href = "daftarhasil.php?show=all";
}
</script>
</head>

<body style="background-color:#F0F0F0">
<form name="main">
<fieldset><legend><strong>Cari Pegawai</strong></legend>
<table border="0" cellpadding="0" cellspacing="2" width="100%">
<tr>
	<td width="50" align="left">Nama: </td>
    <td width="*" align="left"><input type="text" name="txNama" id="txNama" size="25" /></td>
</tr>
<tr>
	<td align="left">NIP: </td>
    <td width="*" align="left"><input type="text" name="txNIP" id="txNIP" size="25" /></td>
</tr>
<tr>
	<td align="left">&nbsp;</td>
    <td width="*" align="left">
		<input type="button" value="Cari" onclick="JavaScript:CariPegawai()" class="but" />
		<input type="button" value="Semua" onclick="JavaScript:SemuaPegawai()" class="but" />	
	</td>
</tr>
</table>
</fieldset>
</form>

</body>
</html>