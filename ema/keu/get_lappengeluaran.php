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
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');

$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];
$departemen = $_REQUEST['departemen'];
$idpengeluaran = $_REQUEST['idpengeluaran'];

OpenDb();
$sql = "SELECT nama FROM $db_name_fina.datapengeluaran WHERE replid = $idpengeluaran";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$nama = $row[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function cetakbukti(id) {
	newWindow('buktipengeluaran.php?idtransaksi='+id, 'BuktiPengeluaran','750','850','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function edit(id) {
	newWindow('pengeluaran_edit.php?idtransaksi='+id, 'EditPengeluaran','500','550','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() {
	document.location.reload();
}

function cetak() {
	var addr = "lappengeluaran_jenis_detailcetak.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&idpengeluaran=<?=$idpengeluaran?>";
	newWindow(addr, 'CetakDetailLapPengeluaran','780','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body topmargin="0" marginheight="0" >

<table width="100%" border="0" align="center">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
   	<tr>
    	<td><span class="news_title1"><?=$nama ?></span></td>
    	<td align="right">
    	<!--<a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')" />&nbsp;Refresh</a>&nbsp;&nbsp;-->
    	<a href="JavaScript:cetak('<?=$idpengeluaran?>')"><img src="../img/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;    	</td>
	</tr>
	</table>
    <br />
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
    <tr height="30" align="center" >
        <td class="header" width="4%" >No</td>
        <td class="header" width="10%">Tanggal</td>
        <td class="header" width="20%">Pemohon</td>
        <td class="header" width="10%">Penerima</td>
        <td class="header" width="11%">Jumlah</td>
        <td class="header" width="*">Keperluan</td>
        <td class="header" width="7%">Petugas</td>
    </tr>
<?php
	$sql = "SELECT p.replid AS id, p.keperluan, p.keterangan, p.jenispemohon, p.nip, p.nis, p.pemohonlain, p.penerima, date_format(p.tanggal, '%d-%b-%Y') as tanggal, date_format(p.tanggalkeluar, '%d-%b-%Y') as tanggalkeluar, p.petugas, p.jumlah FROM $db_name_fina.pengeluaran p, $db_name_fina.datapengeluaran d WHERE p.idpengeluaran = d.replid AND d.replid = $idpengeluaran AND d.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY p.tanggal";
	//echo $sql;
	OpenDb();
	$result = QueryDb($sql);
	$cnt = 0;
	$total = 0;
	while ($row = mysqli_fetch_array($result)) {
		
		if ($row['jenispemohon'] == 1) {
			$idpemohon = $row['nip'];
			$sql = "SELECT nama FROM $db_name_sdm.pegawai WHERE nip = '".$idpemohon."'";
			$jenisinfo = "pegawai";
		} else if ($row['jenispemohon'] == 2) {
			$idpemohon = $row['nis'];
			$sql = "SELECT nama FROM siswa WHERE nis = '".$idpemohon."'";
			$jenisinfo = "siswa";
		} else {
			$idpemohon = "";
			$sql = "SELECT nama FROM $db_name_fina.pemohonlain WHERE replid ='".$row['pemohonlain']."' " ;
			$jenisinfo = "pemohon lain";
		}
		$result2 = QueryDb($sql);
		$row2 = mysqli_fetch_row($result2);
		$namapemohon = $row2[0];
		
		$total += $row['jumlah'];
?>
    <tr height="30">
        <td align="center" valign="top"><?=++$cnt ?></td>
        <td align="center" valign="top"><?=$row['tanggal'] ?></td>
        <td valign="top"><?=$idpemohon?> <?=$namapemohon ?><br />
        <em>(<?=$jenisinfo ?>)</em>
        </td>
        <td valign="top"><?=$row['penerima'] ?></td>
        <td align="right" valign="top"><?=FormatRupiah($row['jumlah']) ?></td>
        <td valign="top">
        <strong>Keperluan: </strong><?=$row['keperluan'] ?><br />
        <strong>Keterangan: </strong><?=$row['keterangan'] ?>
        </td>
        <td valign="top" align="center"><?=$row['petugas'] ?></td>
    </tr>
<?php } ?>
    <tr height="30">
        <td colspan="3" align="center" bgcolor="#999900">
        <font color="#FFFFFF"><strong>T O T A L</strong></font>
        </td>
        <td align="right" bgcolor="#999900" colspan="2"><font color="#FFFFFF"><strong><?=FormatRupiah($total) ?></strong></font></td>
        <td colspan="3" bgcolor="#999900">&nbsp;</td>
    </tr>
    </table>
    <script language='JavaScript'>
        //Tables('table', 1, 0);
    </script>
    </td>
</tr>
</table>
</body>
</html>