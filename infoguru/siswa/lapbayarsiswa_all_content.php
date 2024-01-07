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
//require_once('../include/errorhandler.php');
//require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
//require_once('../library/departemen.php');

//$idtahunbuku = $_REQUEST['idtahunbuku'];
$nis = $_REQUEST['nis'];
$clientid=$_REQUEST['clientid'];
$region=$_REQUEST['region'];
$location=$_REQUEST['location'];
OpenDb();
$res_dep=QueryDb("SELECT d.departemen AS departemen FROM jbsakad.departemen d, jbsakad.kelas k, jbsakad.siswa s, jbsakad.tingkat ti, jbsakad.tahunajaran ta WHERE ".
"d.clientid='$clientid' AND d.region='$region' AND d.location='$location' AND ".
"k.clientid='$clientid' AND k.region='$region' AND k.location='$location' AND ".
"s.clientid='$clientid' AND s.region='$region' AND s.location='$location' AND ".
"ta.clientid='$clientid' AND ta.region='$region' AND ta.location='$location' AND ".
"ti.clientid='$clientid' AND ti.region='$region' AND ti.location='$location' AND ".
"s.nis='$nis' AND s.idkelas=k.replid AND k.idtahunajaran=ta.replid AND ".
"k.idtingkat=ti.replid AND ti.departemen=d.departemen AND ta.departemen=d.departemen");
$row_dep=@mysqli_fetch_array($res_dep);
$departemen=$row_dep['departemen'];
CloseDb();

/*
$conn = @mysqli_connect($db_host2, $db_user2, $db_pass2) or trigger_error("Can not connect to database server", E_USER_ERROR);
$sel = @mysqli_select_db($db_name2, $conn) or trigger_error("Can not open the database", E_USER_ERROR);
$res2 = mysqli_query($mysqlconnection, "SELECT id, tahunbuku FROM tahunbuku WHERE ".
//"clientid='$G_CL_ID' AND region='$G_CL_REG' AND location='$G_CL_LOC' AND ".
"aktif = 1 AND departemen = '$departemen'") or trigger_error("Failed to execute sql query: $sql", E_USER_ERROR);
$row_tb=@mysqli_fetch_array($res2);
$idtahunbuku=$row_tb['id'];
@mysqli_close($conn);
*/

/*OpenDb();
$res2=QueryDb("SELECT id, tahunbuku FROM jbsfina.tahunbuku WHERE ".
"clientid='$clientid' AND region='$region' AND location='$location' AND ".
"aktif = 1 AND departemen = '$departemen'");
$row_tb=@mysqli_fetch_array($res2);
$idtahunbuku=$row_tb['id'];
CloseDb();
*/
if ($departemen=="")
	exit;

OpenDb();

$sql =	"SELECT count(*) FROM jbsfina.besarjtt b, jbsfina.penerimaanjtt p WHERE p.idbesarjtt = b.replid AND b.nis='$nis' AND ".
		"p.clientid='$clientid' AND p.region='$region' AND p.location='$location' AND ".
		"b.clientid='$clientid' AND b.region='$region' AND b.location='$location' ";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$nwajib = $row[0];

$sql = "SELECT count(*) FROM jbsfina.penerimaaniuran WHERE nis='$nis' AND clientid='$clientid' AND region='$region' AND location='$location'"; 
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$niuran = $row[0];


$sql =	"SELECT s.nama, k.kelas FROM jbsakad.siswa s, jbsakad.kelas k WHERE s.nis = '$nis' AND s.idkelas = k.replid AND ".
		"s.clientid='$clientid' AND s.region='$region' AND s.location='$location' AND ".
		"k.clientid='$clientid' AND k.region='$region' AND k.location='$location' ";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namasiswa = $row[0];
$kelas = $row[1];
?>
<div align="left" style="margin-left:1px">
<div align="left" class="nm">
	<span style="background-color:#FF9900">&nbsp;&nbsp;</span>&nbsp;<span class="nm">Data Pembayaran</span>
</div><br />
<?php if (($nwajib + $niuran) >  0) {
	//CloseDb();
	//echo "<br><br><br><br><br><center><i>Tidak ada data pembayaran siswa tersebut di rentang tanggal terpilih</i></center>";
	//exit();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="center">
   
<?php
$sql =	"SELECT DISTINCT b.replid, b.besar, b.lunas, b.keterangan, d.nama FROM jbsfina.besarjtt b, jbsfina.penerimaanjtt p, jbsfina.datapenerimaan d WHERE p.idbesarjtt = b.replid AND b.idpenerimaan = d.replid AND b.nis='$nis' AND ".
		"b.clientid='$clientid' AND b.region='$region' AND b.location='$location' AND ".
		"p.clientid='$clientid' AND p.region='$region' AND p.location='$location' AND ".
		"d.clientid='$clientid' AND d.region='$region' AND d.location='$location' ".
		"ORDER BY nama";

$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) {
	$idbesarjtt = $row['replid'];
	$namapenerimaan = $row['nama']; 
	$besar = $row['besar'];
	$lunas = $row['lunas'];
	$keterangan = $row['keterangan'];
	
	$sql = "SELECT SUM(jumlah) FROM jbsfina.penerimaanjtt WHERE idbesarjtt = $idbesarjtt AND clientid='$clientid' AND region='$region' AND location='$location'";
	$result2 = QueryDb($sql);
	$pembayaran = 0;
	if (mysqli_num_rows($result2)) {
		$row2 = mysqli_fetch_row($result2);
		$pembayaran = $row2[0];
	};
	$sisa = $besar - $pembayaran;
	
	$sql = "SELECT jumlah, DATE_FORMAT(tanggal, '%d-%b-%Y') AS ftanggal FROM jbsfina.penerimaanjtt WHERE idbesarjtt=$idbesarjtt AND clientid='$clientid' AND region='$region' AND location='$location' ORDER BY tanggal DESC LIMIT 1";
	
	$result2 = QueryDb($sql);
	$byrakhir = 0;
	$tglakhir = "";
	if (mysqli_num_rows($result2)) {
		$row2 = mysqli_fetch_row($result2);
		$byrakhir = $row2[0];
		$tglakhir = $row2[1];
	};	?>
   
    <tr height="35">
        <td colspan="4" bgcolor="#99CC00"><font size="2"><strong><em><?=$namapenerimaan?></em></strong></font></td>
    </tr>    
    <tr height="25">
        <td width="20%" bgcolor="#CCFF66"><strong>Total Bayaran</strong> </td>
        <td width="15%" bgcolor="#FFFFFF" align="right"><?=FormatRupiah($besar) ?></td>
        <td width="22%" bgcolor="#CCFF66" align="center"><strong>Pembayaran Terakhir</strong></td>
        <td width="43%" bgcolor="#CCFF66" align="center"><strong>Keterangan</strong></td>
    </tr>
    <tr height="25">
        <td bgcolor="#CCFF66"><strong>Jumlah Pembayaran</strong> </td>
        <td bgcolor="#FFFFFF" align="right"><?=FormatRupiah($pembayaran) ?></td>
        <td bgcolor="#FFFFFF" align="center" valign="top" rowspan="2"><?=FormatRupiah($byrakhir) . "<br><i>" . $tglakhir . "</i>" ?> </td>
        <td bgcolor="#FFFFFF" align="left" valign="top" rowspan="2"><?=$keterangan ?></td>
    </tr>
    <tr height="25">
        <td bgcolor="#CCFF66"><strong>Sisa Bayaran</strong> </td>
        <td bgcolor="#FFFFFF" align="right"><?=FormatRupiah($sisa) ?></td>
    </tr>
    <tr height="3">
        <td colspan="4" bgcolor="#E8E8E8">&nbsp;</td>
    </tr>
<?php 
} //while iuran wajib

$sql =	"SELECT DISTINCT p.idpenerimaan, d.nama FROM jbsfina.penerimaaniuran p, jbsfina.datapenerimaan d WHERE p.idpenerimaan = d.replid AND p.nis='$nis' AND ".
		"p.clientid='$clientid' AND p.region='$region' AND p.location='$location' AND ".
		"d.clientid='$clientid' AND d.region='$region' AND d.location='$location' ".
		"ORDER BY nama";
$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) {
	$idpenerimaan = $row['idpenerimaan'];
	$namapenerimaan = $row['nama'];
	
	$sql = "SELECT SUM(jumlah) FROM jbsfina.penerimaaniuran WHERE idpenerimaan=$idpenerimaan AND nis='$nis' AND clientid='$clientid' AND region='$region' AND location='$location'";
	$result2 = QueryDb($sql);
	$pembayaran = 0;
	if (mysqli_num_rows($result2)) {
		$row2 = mysqli_fetch_row($result2);
		$pembayaran = $row2[0];
	};

	$sql = "SELECT jumlah, DATE_FORMAT(tanggal, '%d-%b-%Y') AS ftanggal FROM jbsfina.penerimaaniuran WHERE idpenerimaan=$idpenerimaan AND nis='$nis' AND clientid='$clientid' AND region='$region' AND location='$location' ORDER BY tanggal DESC LIMIT 1";
	$result2 = QueryDb($sql);
	$byrakhir = 0;
	$tglakhir = "";
	if (mysqli_num_rows($result2)) {
		$row2 = mysqli_fetch_row($result2);
		$byrakhir = $row2[0];
		$tglakhir = $row2[1];
	};	
?>
 	<tr height="35">
        <td colspan="4" bgcolor="#99CC00"><font size="2"><strong><em><?=$namapenerimaan?></em></strong></font></td>
    </tr>  
   	<tr height="25">
        <td width="22%" bgcolor="#CCFF66" align="center"><strong>Total Pembayaran</strong> </td>
        <td width="22%" bgcolor="#CCFF66" align="center"><strong>Pembayaran Terakhir</strong></td>
        <td width="50%" colspan="2" bgcolor="#CCFF66" align="center"><strong>Keterangan</strong></td>
    </tr>
    <tr height="25">
        <td bgcolor="#FFFFFF" align="center"><?=FormatRupiah($pembayaran) ?></td>
        <td bgcolor="#FFFFFF" align="center"><?=FormatRupiah($byrakhir) . "<br><i>" . $tglakhir . "</i>" ?></td>
        <td colspan="2" bgcolor="#FFFFFF" align="left">&nbsp;</td>
    </tr>
    <tr height="3">
        <td colspan="4" bgcolor="#E8E8E8">&nbsp;</td>
    </tr>
<?php
} //while iuran sukarela
?>
	</table>
<?php } else { ?>
        <td></td>
    </tr>
    </table>
    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="250">    
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data.         
            </font>
        </td>
    </tr>
    </table>  
<?php } ?>    
	</tr>
</td>
</table>
</div>
<?php

CloseDb();
?>