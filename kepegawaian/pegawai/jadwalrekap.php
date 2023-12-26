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

$agenda = $_REQUEST['agenda'];
$bln = $_REQUEST['bln'];
$thn = $_REQUEST['thn'];

$op = $_REQUEST['op'];
if ($op == "0c967123b863486x873n01789b123") {
	OpenDb();
	$tanggal = "$thn-$bln-$tgl";
	$sql = "DELETE FROM jadwal WHERE tanggal='$tanggal'";
	QueryDb($sql);
	CloseDb(); ?>
    <script language="javascript">
		parent.jadwalcal.document.location.href = "jadwalcal.php?bulan=<?=$bln?>&tahun=<?=$thn?>";	
    </script> <?php 
} elseif ($op == "987x1n31237bx136786xb9123") {
	$id = $_REQUEST['id'];
	OpenDb();
	$sql = "DELETE FROM jadwal WHERE replid=$id";
	QueryDb($sql);
	CloseDb(); ?>
    <script language="javascript">
		parent.jadwalcal.document.location.href = "jadwalcal.php?bulan=<?=$bln?>&tahun=<?=$thn?>";	
    </script> <?php 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<style>
.tanggal {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 32px;
	font-weight: bold;
}

.bulan {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 14px;
	font-weight: bold;
}
</style>

<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function RefreshAgenda()
{
	document.location.href = "jadwalrekap.php?bln=<?=$bln?>&thn=<?=$thn?>&agenda=<?=$agenda?>";
}

function DetailPegawai(nip)
{
	var addr = "detailpegawai.php?nip="+nip;
    newWindow(addr, 'DetailPegawai','500','550','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Cetak()
{
	newWindow('jadwalrekap_cetak.php?agenda=<?=$agenda?>&bln=<?=$bln?>&thn=<?=$thn?>',
			  'CetakAgendaDetail','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>

</head>

<body>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td width="70%" align="left" valign="top">
    	<font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="pagetitle">Rekap Agenda Kepegawaian</font><br />
        <a href="pegawai.php" target="_parent">Kepegawaian</a> &gt; Rekap Agenda Kepegawaian
        <br /><br />
        <a href="JavaScript:Cetak()"><img src="../images/ico/print.png" border="0" /> Cetak</a>&nbsp;
        <a href="JavaScript:RefreshAgenda()"><img src="../images/ico/refresh.png" border="0" /> Refresh</a><br />
        <br />
    </td>
    <td width="30%" align="left" valign="top">
    
        <table border="0" cellpadding="0" cellspacing="2" width="120" align="center">
        <tr height="120">
            <td width="120" background="../images/Calender2.jpg" align="center">
                <br />
                <font class="bulan"><?=NamaBulan($bln)?></font><br />
                <font class="bulan"><?=$thn?></font>
            </td>
        </tr>
        </table>
    
    </td>
</tr>
</table>
<br />
<table width="100%" cellpadding="0" cellspacing="0" class="tab" id="table">
<tr height="30">
	<td width="7%" class="header" align="center">No</td>
	<td width="17%" class="header" align="center">Tgl</td>
    <td width="35%" class="header" align="center">Pegawai</td>
    <td width="*" class="header" align="center">Keterangan</td>
</tr>
<?php
$tanggal = "$thn-$bln-$tgl";

OpenDb();
$sql = "SELECT nama FROM jenisagenda WHERE agenda='$agenda'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$njenis = $row[0];

$sql = "SELECT DISTINCT jenis FROM jadwal WHERE jenis='$agenda'";
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result))
{
	$jenis = $row['jenis'];
	?>
	<tr height="18">
		<td colspan="4" align="center" bgcolor="#014187">
	    <font color="yellow"><strong><?=$njenis?></strong></font>
	    </td>
	</tr>	
<?php
	$sql = "SELECT j.replid, DATE_FORMAT(j.tanggal, '%e-%b-%y') AS tgl, j.nip, p.nama, j.keterangan, j.exec 
			  FROM jadwal j, pegawai p
			 WHERE j.nip = p.nip AND j.jenis='$jenis'
			   AND (MONTH(j.tanggal)=$bln AND YEAR(j.tanggal)=$thn)
			 ORDER BY tanggal";
	$rs2 = QueryDb($sql);
	while ($row2 = mysqli_fetch_array($rs2))
	{ ?>
        <tr height="25">
            <td align="center" valign="top"><?=++$cnt?></td>
			<td align="center" valign="top"><?=$row2['tgl']?></td>
            <td align="left" valign="top">
            <a href="JavaScript:DetailPegawai('<?=$row2['nip']?>')" title="lihat detail pegawai ini">
			<?=$row2['nip'] . "<br>" . $row2['nama']?>
            </a>
            </td>
            <td align="left" valign="top"><?=$row2['keterangan']?></td>
        </tr>
<?php 
	}	
}
?>
</table>
<script language='JavaScript'>
	Tables('table', 1, 0);
</script>
</body>
</html>