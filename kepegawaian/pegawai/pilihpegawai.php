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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

$nip = $_REQUEST['nip'];
$nama = $_REQUEST['nama'];
$all = $_REQUEST['all'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pilih Pegawai</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/string.js"></script>
<script language="javascript">
function ShowAll()
{
	document.location.href = "pilihpegawai.php?all=1";
}

function PilihPegawai(nip, nama) {
	opener.AcceptPegawai(nip, nama);
	window.close();
}

function Cari()
{
	var nama = trim(document.main.txNama.value);
	var nip  = trim(document.main.txNIP.value);
	
	if (nama.length > 0 || nip.length > 0)
		document.location.href = "pilihpegawai.php?nip="+nip+"&nama="+nama;
}
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr height="60">
	<td bgcolor="#EAEAEA" align="left">
    
    <form name="main">
    <fieldset>
    <legend><strong>Cari Pegawai</strong></legend>
    Nama : <input type="text" name="txNama" id="txNama" size="15" maxlength="20" value="<?=$nama?>" />&nbsp;
    NIP: <input type="text" name="txNIP" id="txNIP" size="15" maxlength="20" value="<?=$nip?>" />&nbsp;
    <input type="button" name="btSubmit" class="but" value="Cari" onclick="JavaScript:Cari()" />&nbsp;
    <input type="button" name="btAll" class="but" value="Lihat Semua" onclick="JavaScript:ShowAll()" /><br />
    </fieldset>
    </form>
    
    </td>
</tr>
<?php
if (strlen((string) $nip) > 0 || strlen((string) $nama) > 0 || $all == 1)
{ 
?>
<tr>
	<td align="center"><br />
    
<table border="0" cellpadding="2" cellspacing="0" width="98%" align="center" class="tab" id="table">
<tr height="30">
	<td class="header" width="5%" align="center">No</td>
    <td class="header" width="20%" align="center">NIP</td>
    <td class="header" width="55%" align="left">Nama</td>
    <td class="header" width="10%" align="center">&nbsp;</td>
</tr>
<?php
	if ($all == 1) 
		$sql = "SELECT nip, TRIM(CONCAT(IFNULL(gelarawal, ''), ' ' , nama, ' ', IFNULL(gelarakhir,''))) AS fnama
				  FROM pegawai
				 WHERE aktif = 1 
				 ORDER BY nama";
	else
		if (strlen((string) $nama) > 0)
			$sql = "SELECT nip, TRIM(CONCAT(IFNULL(gelarawal, ''), ' ' , nama, ' ', IFNULL(gelarakhir,''))) AS fnama
					  FROM pegawai
					 WHERE nama LIKE '%$nama%'
					   AND aktif = 1
					 ORDER BY nama";
		elseif (strlen((string) $nip) > 0)
			$sql = "SELECT nip, TRIM(CONCAT(IFNULL(gelarawal, ''), ' ' , nama, ' ', IFNULL(gelarakhir,''))) AS fnama
					  FROM pegawai
					 WHERE nip LIKE '%$nip%'
					   AND aktif = 1
					 ORDER BY nama";
	OpenDb();
	$result = QueryDb($sql);
	$cnt = 0;
	while ($row = mysqli_fetch_array($result)) 
	{ ?>

<tr height="25">
	<td align="center"><?=++$cnt?></td>
    <td align="left"><?=$row['nip']?></td>
    <td align="left"><?=$row['fnama']?></td>
    <td align="center">
    <input type="button" class="but" onclick="JavaScript:PilihPegawai('<?=$row['nip']?>', '<?=$row['fnama']?>')" value="pilih" />
    </td>
</tr>    

<?php }
	CloseDb();
?>

</table>

<script language='JavaScript'>
	Tables('table', 1, 0);
</script>


</td>
</tr>

<?php
}
?>
</table>
</body>
</html>