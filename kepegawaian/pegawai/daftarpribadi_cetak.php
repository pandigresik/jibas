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

OpenDb();

$nip = $_REQUEST['nip'];

$sql = "SELECT * FROM pegawai WHERE nip='$nip'";

$result = QueryDb($sql);
$row = mysqli_fetch_array($result);

$namapeg = $row['nama'];
$gelarawal = $row['gelarawal'];
$gelarakhir = $row['gelarakhir'];
$pegawai = $namapeg;
$nip = $row['nip'];
$nuptk = $row['nuptk'];
$nrp = $row['nrp'];
$tmplahir = $row['tmplahir'];
$tgllahir = GetDatePart($row['tgllahir'], "d");
$blnlahir = GetDatePart($row['tgllahir'], "m");
$thnlahir = GetDatePart($row['tgllahir'], "y");
$agama = $row['agama'];
$suku = $row['suku'];
$nikah = $row['nikah'];
$jk = $row['kelamin'];
$alamat = $row['alamat'];
$hp = $row['handphone'];
$telpon = $row['telpon'];
$email = $row['email'];
$facebook = $row['facebook'];
$twitter = $row['twitter'];
$website = $row['website'];
$foto = $row['foto'];
$status = $row['status'];
$bagian = $row['bagian'];
$tglmulai = GetDatePart($row['mulaikerja'], "d");
$blnmulai = GetDatePart($row['mulaikerja'], "m");
$thnmulai = GetDatePart($row['mulaikerja'], "y");
$keterangan = $row['keterangan'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style<?=GetThemeDir2()?>.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top"><?php include("../include/headercetak.php") ?>
  <center><font size="4"><strong>DATA PRIBADI</strong></font><br /> </center><br /><br />
<br />

<table border="0" cellpadding="5" cellspacing="0" width="95%">
<tr>
	<td align="right" valign="top"><strong>Status :</strong></td>
    <td width="*" colspan="2" align="left" valign="top"><?=$status?></td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Bagian :</strong></td>
    <td width="*" colspan="2" align="left" valign="top"><?=$bagian?></td>
</tr>
<tr>
	<td width="140" align="right" valign="top"><strong>Nama </strong>:</td>
    <td width="0" align="left" valign="top"><?=$gelarawal . " " . $namapeg . " " . $gelarakhir?></td>
    <td width="113" rowspan="5" align="center" valign="top"><img src="../include/gambar.php?nip=<?=$nip?>&table=pegawai&field=foto" height="120" alt="Foto" /></td>
</tr>
<tr>
	<td align="right" valign="top"><strong>NIP </strong>:</td>
    <td width="0" align="left" valign="top"><?=$nip?></td>
</tr>
<tr>
	<td align="right" valign="top">NUPTK :</td>
    <td width="0" align="left" valign="top"><?=$nuptk?></td>
</tr>
<tr>
	<td align="right" valign="top">NRP :</td>
    <td width="0" align="left" valign="top"><?=$nrp?></td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Tempat, Tgl Lahir </strong>:</td>
    <td width="0" align="left" valign="top">
    <?=$tmplahir?>, <?=$tgllahir?> <?= NamaBulan($blnlahir)?> <?=$thnlahir?>    </td>
    </tr>
<tr>
	<td align="right" valign="top"><strong>Agama :</strong></td>
    <td width="0" align="left" valign="top"><?=$agama?></td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Suku :</strong></td>
    <td width="0" align="left" valign="top"><?=$suku?></td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Pernikahan :</strong></td>
    <td width="0" align="left" valign="top"><?=$nikah?></td>
    </tr>
<tr>
	<td align="right" valign="top"><strong>Jenis Kelamin :</strong></td>
    <td width="*" colspan="2" align="left" valign="top"><?php if ($jk == "l") echo "Laki-Laki"; else echo "Perempuan"; ?></td>
</tr>
<tr>
	<td align="right" valign="top">Alamat :</td>
    <td width="*" colspan="2" align="left" valign="top"><?=$alamat?></td>
</tr>
<tr>
	<td align="right" valign="top">HP :</td>
    <td width="*" colspan="2" align="left" valign="top"><?=$hp?></td>
</tr>
<tr>
	<td align="right" valign="top">Telpon :</td>
    <td width="*" colspan="2" align="left" valign="top"><?=$telpon?></td>
</tr>
<tr>
	<td align="right" valign="top">Email :</td>
    <td width="*" colspan="2" align="left" valign="top"><?=$email?></td>
</tr>
<tr>
	<td align="right" valign="top">Facebook :</td>
    <td width="*" colspan="2" align="left" valign="top"><?=$facebook?></td>
</tr>
<tr>
	<td align="right" valign="top">Twitter :</td>
    <td width="*" colspan="2" align="left" valign="top"><?=$twitter?></td>
</tr>
<tr>
	<td align="right" valign="top">Website :</td>
    <td width="*" colspan="2" align="left" valign="top"><?=$website?></td>
</tr>
<tr>
	<td align="right" valign="top">Keterangan :</td>
    <td width="*" colspan="2" align="left" valign="top">
	<?=$keterangan?>    </td>
</tr>
</table>
	

</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>