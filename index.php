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
require_once('include/mainconfig.php');
require_once('include/db_functions.php');

// --- Patch Management Framework ---
require_once('include/global.patch.manager.php');
ApplyGlobalPatch(".");

// --- LiveUpdate Status ----
session_name("jbsmain");
session_start();

$lid = -1; // current liveupdate id
$dbconnect = @mysqli_connect($db_host, $db_user, $db_pass);
if ($dbconnect)
{
	$dbselect = @mysqli_select_db($dbconnect, "jbsclient");
	
	if ($dbselect)
	{
		$sql = "SELECT nilai FROM jbsclient.liveupdateconfig WHERE tipe='MIN_UPDATE_ID'";
		$result = @mysqli_query($dbconnect, $sql);
		$row = @mysqli_fetch_row($result);
		$minid = $row[0];
		
		$sql = "SELECT MAX(liveupdateid) FROM jbsclient.liveupdate";
		$result = @mysqli_query($dbconnect, $sql);
		$row = @mysqli_fetch_row($result);
		$maxinstalled  = is_null($row[0]) ? 0 : $row[0];
		
		$lid = $minid >= $maxinstalled ? $minid : $maxinstalled;
	}
	
	@mysqli_close($dbconnect);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS <?=$G_VERSION?></title>
<link href="script/vtip.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="images/jibas2015.ico" />
<script language="javascript" src="script/jquery.min.js"></script>
<script language="javascript" src="script/ajax.js"></script>
<script language="javascript" src="script/vtip.js"></script>
<script language="javascript" src="index.js?r=<?=filemtime('index.js')?>""></script>
<link rel="stylesheet" href="script/bgstretcher.css" />
<script language="javascript" src="script/bgstretcher.js"></script>
</head>

<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0">
<div style="position:relative; z-index:2">

<div id="dvMain" style='position:absolute; width:1240px; height:620px; '>
	
<table border="0" cellpadding="0" cellspacing="0" align="center" >
<tr>
	<td align="center" height="70">
	<br><br><br>
	<table border="0" cellpadding="5">
	<tr>
		<td>
			<img src="images/<?= $G_LOGO_DEPAN_KIRI ?>">
		</td>
		<td width="*" align="center">
			<font style="font-family:Tahoma; font-size:20px; color:#fff; ">
			<?= $G_JUDUL_DEPAN_1 ?>
			</font><br>
			<font style="font-family:Tahoma; font-size:12px; color:#fff; font-weight:bold; ">
			<?= $G_JUDUL_DEPAN_2 ?>
			</font><br>
			<font style="font-family:Tahoma; font-size:10px; color:#fff; ">
			<?= $G_JUDUL_DEPAN_3 ?>
			</font>
		</td>
		<td>
			<img src="images/<?= $G_LOGO_DEPAN_KANAN ?>">
		</td>
	</tr>
	</table>		
    <br><br>
    </td>
</tr>
<tr>
	<td align="center">
		
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
        <td align="center" width="140">
            <a href="akademik/index.php">
                <img id="btAkademik" src="images/btnmenu_green_p_03.png"  style="width: 120px"
                     onMouseOver="changeImage('btAkademik','images/btnmenu_green_a_03.png')"
                     onMouseOut="changeImage('btAkademik','images/btnmenu_green_p_03.png')" border="0">
            </a>
        </td>
        <td align="center" width="140">
            <a href="http://www.jibas.net/content/jendelasekolah/jendelasekolah.php" target="_blank">
                <img id="btJs" src="images/btnmenu_green_p_30.png" onMouseOver="changeImage('btJs','images/btnmenu_green_a_30.png')"
                     onMouseOut="changeImage('btJs','images/btnmenu_green_p_30.png')" border="0"
                     title="Jendela Informasi Sekolah bagi Siswa dan Orangtua">
            </a	>
        </td>
        <td align="center" width="140">
            <a href="ema/index.php" target="_blank">
                <img id="btJsEma" src="images/btnmenu_green_p_32.png" onMouseOver="changeImage('btJsEma','images/btnmenu_green_a_32.png')"
                     onMouseOut="changeImage('btJsEma','images/btnmenu_green_p_32.png')" border="0"
                     title="Jendela Informasi Sekolah e-Management bagi Eksekutif dan Staf Sekolah">
            </a	>
        </td>
        <td align="center" width="140">
            <a href="cbe/login.php" target="_blank">
                <img id="btCbe" src="images/btnmenu_green_p_24a.png" onMouseOver="changeImage('btCbe','images/btnmenu_green_a_24a.png')"
                     onMouseOut="changeImage('btCbe','images/btnmenu_green_p_24a.png')" border="0"
                     title="Aplikasi pengujian berbasis komputer untuk siswa, calon siswa dan pegawai">
            </a	>
        </td>
        <td align="center" width="150">
            <a href="http://www.jibas.net/content/sptfgr/sptfgr.php" target="_blank">
                <img id="btSptFgr" src="images/btnmenu_green_p_22.png" onMouseOver="changeImage('btSptFgr','images/btnmenu_green_a_22.png')" onMouseOut="changeImage('btSptFgr','images/btnmenu_green_p_22.png')" border="0"
                     class="vtip" title="Sistem Presensi Terpadu untuk pendataan presensi siswa dan pegawai menggunakan sidik jari">
            </a>
        </td>






	</tr>
	</table>
	
	</td>
</tr>
<tr>
	<td align="center">
		
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
        <td align="center" width="140">
            <a href="keuangan/index.php">
                <img id="btKeuangan" src="images/btnmenu_green_p_04.png" style="width: 130px"
                     onMouseOver="changeImage('btKeuangan','images/btnmenu_green_a_04.png')"
                     onMouseOut="changeImage('btKeuangan','images/btnmenu_green_p_04.png')" border="0">
            </a>
        </td>
        <td align="center" width="140">
            <a href="http://www.jibas.net/content/paygate/paygate.php" target="_blank">
                <img id="btPg" src="images/btnmenu_green_p_31.png" onMouseOver="changeImage('btPg','images/btnmenu_green_a_31.png')"
                     onMouseOut="changeImage('btPg','images/btnmenu_green_p_31.png')" border="0"
                     title="Payment Gateway">
            </a	>
        </td>
        <td align="center" width="140">
            <a href="http://www.jibas.net/content/schoolpay/schoolpay.php" target="_blank">
                <img id="btSchoolPay" src="images/btnmenu_green_p_28.png" onMouseOver="changeImage('btSchoolPay','images/btnmenu_green_a_28.png')"
                     onMouseOut="changeImage('btSchoolPay','images/btnmenu_green_p_28.png')" border="0"
                     title="Aplikasi Pembayaran Non Tunai">
            </a	>
        </td>
        <td align="center" width="140">
            <a href="kepegawaian/index.php">
                <img id="btKepegawaian" src="images/btnmenu_green_p_19.png"
                     onMouseOver="changeImage('btKepegawaian','images/btnmenu_green_a_19.png')"
                     onMouseOut="changeImage('btKepegawaian','images/btnmenu_green_p_19.png')" border="0">
            </a>
        </td>
        <td align="center" width="150">
            <a href="http://www.jibas.net/content/letterstore/letterstore.php" target="_blank">
                <img id="btLetterStore" src="images/btnmenu_green_p_23.png" style="width: 100px"
                     onMouseOver="changeImage('btLetterStore','images/btnmenu_green_a_23.png')" onMouseOut="changeImage('btLetterStore','images/btnmenu_green_p_23.png')" border="0"
                     class="vtip" title="Aplikasi pengarsipan dan digitalisasi surat masuk/keluar">
            </a>
        </td>
	</tr>
	</table>
	
	</td>
</tr>
<tr>
    <td align="center">

        <table border="0" cellpadding="0" cellspacing="0">
        <tr>

            <td align="center" width="140">
                <a href="infoguru/index.php">
                    <img id="btInfoGuru" src="images/btnmenu_green_p_08.png" style="width: 100px"
                         onMouseOver="changeImage('btInfoGuru','images/btnmenu_green_a_08.png')"
                         onMouseOut="changeImage('btInfoGuru','images/btnmenu_green_p_08.png')" border="0">
                </a>
            </td>
            <td align="center" width="140">
                <a href="simtaka/index.php">
                    <img id="btPerpustakaan" src="images/btnmenu_green_p_05.png"  style="width: 120px"
                         onMouseOver="changeImage('btPerpustakaan','images/btnmenu_green_a_05.png')"
                         onMouseOut="changeImage('btPerpustakaan','images/btnmenu_green_p_05.png')" border="0">
                </a>
            </td>
            <td align="center" width="140">
                <a href="anjungan/index.php">
                    <img id="btAnjungan" src="images/btnmenu_green_p_21.png"
                         onMouseOver="changeImage('btAnjungan','images/btnmenu_green_a_21.png')"
                         onMouseOut="changeImage('btAnjungan','images/btnmenu_green_p_21.png')" border="0">
                </a>
            </td>
            <td align="center" width="140">
                <a href="schooltube/index.php" target="_blank">
                    <img id="btSchoolTube" src="images/btnmenu_green_p_26.png"  style="width: 100px"
                         onMouseOver="changeImage('btSchoolTube','images/btnmenu_green_a_26.png')"
                         onMouseOut="changeImage('btSchoolTube','images/btnmenu_green_p_26.png')" border="0"
                         title="Aplikasi e-Learning">
                </a	>
            </td>
            <td align="center" width="150">
                <a href="http://www.jibas.net/content/cardmaker/cardmaker.php" target="_blank">
                    <img id="btCardMaker" src="images/btnmenu_green_p_25.png" style="width: 95px"
                         onMouseOver="changeImage('btCardMaker','images/btnmenu_green_a_25.png')"
                         onMouseOut="changeImage('btCardMaker','images/btnmenu_green_p_25.png')" border="0"
                         class="vtip" title="Aplikasi pembuatan berbagai macam kartu untuk siswa, calon siswa dan pegawai">
                </a>
            </td>
            <td align="center" width="150">
                <a href="http://www.jibas.net/content/phototake/phototake.php" target="_blank">
                    <img id="btPhotoTake" src="images/btnmenu_green_p_20.png" style="width: 95px"
                         onMouseOver="changeImage('btPhotoTake','images/btnmenu_green_a_20.png')"
                         onMouseOut="changeImage('btPhotoTake','images/btnmenu_green_p_20.png')" border="0"
                         class="vtip" title="Membuat foto siswa & guru dari kamera/webcam">
                </a>
            </td>
        </tr>
        </table>

    </td>
</tr>
<tr>
    <td align="center">

        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="140">
                <a href="http://www.jibas.net/content/tgram/tgram.php" target="_blank">
                    <img id="btTgram" src="images/btnmenu_green_p_27.png" style="width: 100px"
                         onMouseOver="changeImage('btTgram','images/btnmenu_green_a_27.png')"
                         onMouseOut="changeImage('btTgram','images/btnmenu_green_p_27.png')" border="0"
                         title="Aplikasi pengiriman pesan dan laporan melalui Telegram">
                </a	>
            </td>
            <td align="center" width="140">
                <a href="http://www.jibas.net/content/egw/egw.php" target="_blank">
                    <img id="btEgw" src="images/btnmenu_green_p_29.png" style="width: 100px"
                         onMouseOver="changeImage('btEgw','images/btnmenu_green_a_29.png')"
                         onMouseOut="changeImage('btEgw','images/btnmenu_green_p_29.png')" border="0"
                         title="Aplikasi pengiriman berita dan laporan melalui Email">
                </a	>
            </td>
            <td align="center" width="150">
                <a href="smsgateway/index.php">
                    <img id="btSMSGateway" src="images/btnmenu_green_p_10.png" style="width: 115px"
                         onMouseOver="changeImage('btSMSGateway','images/btnmenu_green_a_10.png')"
                         onMouseOut="changeImage('btSMSGateway','images/btnmenu_green_p_10.png')" border="0">
                </a>
            </td>
            <td align="center" width="140">
                <a href="http://www.jibas.net/content/br/br.php" target="_blank">
                    <img id="btBackup" src="images/btnmenu_green_p_15.png" onMouseOver="changeImage('btBackup','images/btnmenu_green_a_15.png')" onMouseOut="changeImage('btBackup','images/btnmenu_green_p_15.png')" border="0"
                         class="vtip" title="Membuat data cadangan untuk keamanan dan ketersediaan data">
                </a>
            </td>
            <td align="center" width="140">
                <a href="http://www.jibas.net/content/lu/lu.php" target="_blank">
                    <img id="btLiveUpdate" src="images/btnmenu_green_p_16.png" onMouseOver="changeImage('btLiveUpdate','images/btnmenu_green_a_16.png')" onMouseOut="changeImage('btLiveUpdate','images/btnmenu_green_p_16.png')" border="0"
                         class="vtip" title="Memutakhirkan aplikasi dengan mudah dan cepat">
                </a>
            </td>

        </tr>
        </table>

    </td>
</tr>
</table>

</div>

<div id="dvCopy" style="color:#fff; width:300px; font-size:11px; font-family:Tahoma; position:absolute; background-image:url(images/bgdiv_black.png);">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
<td align="right" valign="middle">
	versi <?=$G_VERSION." - ".$G_BUILDDATE?><br />
	<a href="http://www.jibas.net" target="_blank" style="color:#fff; text-decoration:none;">
	&nbsp;&nbsp;<strong>JIBAS</strong>: Jaringan Informasi Bersama Antar Sekolah</a><br />
</td>
<td>
	<a href="http://www.jibas.net" target="_blank">
	<img src="images/jibas.png" border="0" title="JIBAS">
	</a>	
</td>	
</tr>	
</table>
</div>

<div id="dvPartner" style="color:#fff; width:120px; font-size:11px; font-family:Tahoma; position:absolute; background-image:url(images/bgdiv_black.png);">
<?php
include('info.php');
?>
</div>

<div id="lumessage" style="width:250px; height:20px; position:absolute; font-family:Tahoma; font-size:11px; color:#ddd; text-align:center; background-image:url(images/bgdiv_black.png);">
<?	
if ($_SESSION['lugetstatus'] && $_SESSION['lugetlid'] == $lid)
	echo $_SESSION['lugetmessage'];
else	
	echo "Memeriksa Update ..."; 
?>
</div>

</div> 
</body>
</html>

<?php if (!$_SESSION['lugetstatus'] ?? false || $_SESSION['lugetlid'] != $lid) { ?>
<script type="text/javascript" language="javascript">
getLuStatus(<?=$lid?>);
</script>
<?php } ?>
