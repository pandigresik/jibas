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
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$ndepartemen = $departemen;
	
$idtahunbuku = "";
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];
$ntahunbuku = getname2('tahunbuku',$db_name_fina.'.tahunbuku','replid',$idtahunbuku);	

if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
$nperiode = LongDateFormat($tanggal1)." s.d. ".LongDateFormat($tanggal2);

$id = $_REQUEST['id'];
if ($id=="penerimaanlain"){
	$tit1 = "Cetak Perubahan Data Lain-lain ";
	$tit2 = "Perubahan Data Penerimaan Lain";
} elseif ($id=="pengeluaran"){
	$tit1 = "Cetak Perubahan Data Pengeluaran ";
	$tit2 = "Perubahan Data Pengeluaran";
} elseif ($id=="penerimaaniuran"){
	$tit1 = "Cetak Perubahan Data Iuran Siswa ";
	$tit2 = "Perubahan Data Iuran Siswa";
} elseif ($id=="penerimaaniurancalon"){
	$tit1 = "Cetak Perubahan Data Iuran Calon Siswa";
	$tit2 = "Perubahan Data Iuran Calon Siswa";
} elseif ($id=="penerimaanjtt"){
	$tit1 = "Cetak Perubahan Data Iuran Wajib Siswa";
	$tit2 = "Perubahan Data Iuran Wajib Siswa";
} elseif ($id=="penerimaanjttcalon"){
	$tit1 = "Cetak Perubahan Data Iuran Wajib Calon Siswa";
	$tit2 = "Perubahan Data Iuran Wajib Calon Siswa";
} elseif ($id=="jurnalumum"){
	$tit1 = "Cetak Perubahan Data Jurnal Umum";
	$tit2 = "Perubahan Data Jurnal Umum";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [<?=$tit1?>]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong><?=$tit2?></strong></font><br />
 </center><br /><br />
<table width="100%">
<tr>
	<td width="7%" class="news_content1"><strong>Departemen</strong></td>
    <td width="93%" class="news_content1">: 
      <?=$departemen ?></td>
    </tr>
<tr>
  <td class="news_content1"><strong>Tahun Buku</strong></td>
  <td class="news_content1">: 
      <?=$ntahunbuku ?></td>
  </tr>
<tr>
  <td class="news_content1"><strong>Periode</strong></td>
  <td class="news_content1">:
    <?=$nperiode ?></td>
  </tr>
</table>
<br />
<?php
if ($id=="penerimaanlain"){
?>
	<table class="tab" id="table" border="1" width="100%" align="left" cellpadding="5" cellspacing="0">
        <tr height="30" align="center">
            <td class="header" width="4%">No</td>
            <td class="header" width="17%">Status Data</td>
            <td class="header" width="10%">Tanggal</td>
            <td class="header" width="15%">Jumlah</td>
            <td class="header" width="*">Keterangan</td>
            <td class="header" width="15%">Petugas</td>
        </tr>
    <?php
    OpenDb();
    $sql = "SELECT DISTINCT ai.petugas as petugasubah, j.transaksi, date_format(ai.tanggal, '%d-%b-%Y %H:%i:%s') as tanggalubah, ap.replid AS id, ap.idaudit, ap.statusdata, j.nokas, date_format(ap.tanggal, '%d-%b-%Y') AS tanggal, ap.petugas, ap.keterangan, ap.jumlah, ap.petugas, ai.alasan FROM $db_name_fina.auditpenerimaanlain ap, $db_name_fina.auditinfo ai, $db_name_fina.jurnal j WHERE j.replid = ap.idjurnal AND ap.idaudit = ai.replid AND ai.departemen = '$departemen' AND ai.sumber='penerimaanlain' AND ai.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY ap.idaudit DESC, ai.tanggal DESC, ap.statusdata ASC";
    
    $result = QueryDb($sql);
    $cnt = 0;
    $no = 0;
    while ($row = mysqli_fetch_array($result)) {
        $statusdata = "Data Lama";
		$bgcolor = "#FFFFFF";
        if ($row['statusdata'] == 1) {
            $statusdata = "Data Perubahan";
			$bgcolor = "#FFFFB7";
        }    
        if ($cnt % 2 == 0) { ?>
        <tr>
            <td rowspan="4" align="center" bgcolor="#CCCC66"><strong><?=++$no ?></strong></td>
            <td colspan="6" align="left" bgcolor="#CCCC66"><font size="2"><em><strong>Perubahan dilakukan oleh <?=$row['petugasubah'] . " tanggal " . $row['tanggalubah'] ?></strong></em></font></td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#E5E5E5" ><strong>No. Jurnal :</strong> <?=$row['nokas'] ?>   
            &nbsp;&nbsp;<strong>Alasan : </strong><?=$row['alasan'];?>
            <br /><strong>Transaksi :</strong> <?=$row['transaksi'] ?></td>
        </tr>
    <?php  } ?>
        <tr>
            <td><?=$statusdata ?></td>
            <td align="center"><?=$row['tanggal'] ?></td>
            <td align="right"><?=FormatRupiah($row['jumlah']) ?></td>
            <td><?=$row['keterangan'] ?></td>
            <td align="center"><?=$row['petugas']; ?></td>
        </tr>
    <?php
        $cnt++;
    }
    CloseDb();
    ?>
    </table>
<?php 
} elseif ($id=="pengeluaran"){
?>
	<table class="tab" id="table" border="1" width="100%" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000">
    <tr height="30" align="center">
        <td class="header" width="4%">No</td>
        <td class="header" width="17%">Status Data</td>
        <td class="header" width="10%">Tanggal</td>
        <td class="header" width="15%">Jumlah</td>
        <td class="header" width="*">Keterangan</td>
        <td class="header" width="15%">Petugas</td>
    </tr>
    <?php
    OpenDb();
    $sql = "SELECT DISTINCT ai.petugas as petugasubah, j.transaksi, date_format(ai.tanggal, '%d-%b-%Y %H:%i:%s') as tanggalubah, ap.replid AS id, ap.idaudit, ap.statusdata, j.nokas, date_format(ap.tanggal, '%d-%b-%Y') AS tanggal, ap.petugas, ap.keterangan, ap.jumlah, ap.petugas, ai.alasan FROM $db_name_fina.auditpengeluaran ap, $db_name_fina.auditinfo ai, $db_name_fina.jurnal j WHERE j.replid = ap.idjurnal AND ap.idaudit = ai.replid AND ai.departemen = '$departemen' AND ai.sumber='pengeluaran' AND ai.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY ap.idaudit DESC, ai.tanggal DESC, ap.statusdata ASC";
    $result = QueryDb($sql);
    $cnt = 0;
    $no = 0;
    while ($row = mysqli_fetch_array($result)) {
        $statusdata = "Data Lama";
		$bgcolor = "#FFFFFF";
        if ($row['statusdata'] == 1) {
            $statusdata = "Data Perubahan";
			$bgcolor = "#FFFFB7";
        }    
            
        if ($cnt % 2 == 0) { ?>
        <tr>
            <td rowspan="4" align="center" bgcolor="#CCCC66"><strong><?=++$no ?></strong></td>
            <td colspan="6" align="left" bgcolor="#CCCC66"><font size="2"><em><strong>Perubahan dilakukan oleh <?=$row['petugasubah'] . " tanggal " . $row['tanggalubah'] ?></strong></em></font></td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#E5E5E5" ><strong>No. Jurnal :</strong> <?=$row['nokas'] ?>   
            &nbsp;&nbsp;<strong>Alasan : </strong><?=$row['alasan'];?>
            <br /><strong>Transaksi :</strong> <?=$row['transaksi'] ?></td>
        </tr>
    <?php  } ?>
        <tr>
            <td><?=$statusdata ?></td>
            <td align="center"><?=$row['tanggal'] ?></td>
            <td align="right"><?=FormatRupiah($row['jumlah']) ?></td>
            <td><?=$row['keterangan'] ?></td>
            <td align="center"><?=$row['petugas']; ?></td>
        </tr>
    <?php
        $cnt++;
    }
    CloseDb();
    ?>
    </table>
<?php 
} elseif ($id=="penerimaaniuran"){
?>
<table class="tab" id="table" border="1" width="100%" align="left" cellpadding="5" cellspacing="0">
    <tr height="30" align="center">
        <td class="header" width="4%">No</td>
        <td class="header" width="17%">Status Data</td>
        <td class="header" width="10%">Tanggal</td>
        <td class="header" width="15%">Jumlah</td>
        <td class="header" width="*">Keterangan</td>
        <td class="header" width="15%">Petugas</td>
    </tr>
    <?php
    OpenDb();
    $sql = "SELECT DISTINCT ai.petugas as petugasubah, j.transaksi, date_format(ai.tanggal, '%d-%b-%Y %H:%i:%s') as tanggalubah, ap.replid AS id, ap.idaudit, ap.statusdata, j.nokas, date_format(ap.tanggal, '%d-%b-%Y') AS tanggal, ap.petugas, ap.keterangan, ap.jumlah, ap.petugas, ai.alasan FROM $db_name_fina.auditpenerimaaniuran ap, $db_name_fina.auditinfo ai, $db_name_fina.jurnal j WHERE j.replid = ap.idjurnal AND ap.idaudit = ai.replid AND ai.departemen = '$departemen' AND ai.sumber='penerimaaniuran$calon' AND ai.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY ap.idaudit DESC, ai.tanggal DESC, ap.statusdata ASC";
	
    $result = QueryDb($sql);
	
    $cnt = 0;
    $no = 0;
    while ($row = mysqli_fetch_array($result)) {
        $statusdata = "Data Lama";
		$bgcolor = "#FFFFFF";
        if ($row['statusdata'] == 1) {
            $statusdata = "Data Perubahan";
			$bgcolor = "#FFFFB7";
        }
		 
        if ($cnt % 2 == 0) { ?>
        <tr>
            <td rowspan="4" align="center" bgcolor="#CCCC66"><strong><?=++$no ?></strong></td>
            <td colspan="6" align="left" bgcolor="#CCCC66"><font size="2"><em><strong>Perubahan dilakukan oleh <?=$row['petugasubah'] . " tanggal " . $row['tanggalubah'] ?></strong></em></font></td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#E5E5E5" ><strong>No. Jurnal :</strong> <?=$row['nokas'] ?>   
            &nbsp;&nbsp;<strong>Alasan : </strong><?=$row['alasan'];?>
            <br /><strong>Transaksi :</strong> <?=$row['transaksi'] ?></td>
        </tr>
    <?php  } ?>
        <tr bgcolor="<?=$bgcolor?>">
            <td><?=$statusdata ?></td>
            <td align="center"><?=$row['tanggal'] ?></td>
            <td align="right"><?=FormatRupiah($row['jumlah']) ?></td>
            <td><?=$row['keterangan'] ?></td>
            <td align="center"><?=$row['petugas']; ?></td>
        </tr>
    <?php
        $cnt++;
    }
    CloseDb();
    ?>
    </table>
<?php 
} elseif ($id=="penerimaaniurancalon"){
?>
<table class="tab" id="table" border="1" width="100%" align="left" cellpadding="5" cellspacing="0">
    <tr height="30" align="center">
        <td class="header" width="4%">No</td>
        <td class="header" width="17%">Status Data</td>
        <td class="header" width="10%">Tanggal</td>
        <td class="header" width="15%">Jumlah</td>
        <td class="header" width="*">Keterangan</td>
        <td class="header" width="15%">Petugas</td>
    </tr>
    <?php
    OpenDb();
    $sql = "SELECT DISTINCT ai.petugas as petugasubah, j.transaksi, date_format(ai.tanggal, '%d-%b-%Y %H:%i:%s') as tanggalubah, ap.replid AS id, ap.idaudit, ap.statusdata, j.nokas, date_format(ap.tanggal, '%d-%b-%Y') AS tanggal, ap.petugas, ap.keterangan, ap.jumlah, ap.petugas, ai.alasan FROM $db_name_fina.auditpenerimaaniurancalon ap, $db_name_fina.auditinfo ai, $db_name_fina.jurnal j WHERE j.replid = ap.idjurnal AND ap.idaudit = ai.replid AND ai.departemen = '$departemen' AND ai.sumber='penerimaaniuran$calon' AND ai.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY ap.idaudit DESC, ai.tanggal DESC, ap.statusdata ASC";
	
    $result = QueryDb($sql);
	
    $cnt = 0;
    $no = 0;
    while ($row = mysqli_fetch_array($result)) {
        $statusdata = "Data Lama";
		$bgcolor = "#FFFFFF";
        if ($row['statusdata'] == 1) {
            $statusdata = "Data Perubahan";
			$bgcolor = "#FFFFB7";
        }
		 
        if ($cnt % 2 == 0) { ?>
        <tr>
            <td rowspan="4" align="center" bgcolor="#CCCC66"><strong><?=++$no ?></strong></td>
            <td colspan="6" align="left" bgcolor="#CCCC66"><font size="2"><em><strong>Perubahan dilakukan oleh <?=$row['petugasubah'] . " tanggal " . $row['tanggalubah'] ?></strong></em></font></td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#E5E5E5" ><strong>No. Jurnal :</strong> <?=$row['nokas'] ?>   
            &nbsp;&nbsp;<strong>Alasan : </strong><?=$row['alasan'];?>
            <br /><strong>Transaksi :</strong> <?=$row['transaksi'] ?></td>
        </tr>
    <?php  } ?>
        <tr bgcolor="<?=$bgcolor?>">
            <td><?=$statusdata ?></td>
            <td align="center"><?=$row['tanggal'] ?></td>
            <td align="right"><?=FormatRupiah($row['jumlah']) ?></td>
            <td><?=$row['keterangan'] ?></td>
            <td align="center"><?=$row['petugas']; ?></td>
        </tr>
    <?php
        $cnt++;
    }
    CloseDb();
    ?>
    </table>
<?php 
} elseif ($id=="penerimaanjtt"){
?>
<table class="tab" id="table" border="1" width="100%" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000">
    <tr height="30" align="center">
        <td class="header" width="4%">No</td>
        <td class="header" width="17%">Status Data</td>
        <td class="header" width="10%">Tanggal</td>
        <td class="header" width="15%">Jumlah</td>
        <td class="header" width="*">Keterangan</td>
        <td class="header" width="15%">Petugas</td>
    </tr>
    <?php
    OpenDb();
    $sql = "SELECT DISTINCT ai.petugas as petugasubah, j.transaksi, date_format(ai.tanggal, '%d-%b-%Y %H:%i:%s') as tanggalubah, ap.replid AS id, ap.idaudit, ap.statusdata, j.nokas, date_format(ap.tanggal, '%d-%b-%Y') AS tanggal, ap.petugas, ap.keterangan, ap.jumlah, ap.petugas, ai.alasan FROM $db_name_fina.auditpenerimaanjtt ap, $db_name_fina.auditinfo ai, $db_name_fina.jurnal j WHERE j.replid = ap.idjurnal AND ap.idaudit = ai.replid AND ai.departemen = '$departemen' AND ai.sumber='penerimaanjtt$calon' AND ai.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY ap.idaudit DESC, ai.tanggal DESC, ap.statusdata ASC";
   	//echo $sql;
    $result = QueryDb($sql);
    $cnt = 0;
    $no = 0;
    while ($row = mysqli_fetch_array($result)) {
        $statusdata = "Data Lama";
        $bgcolor = "#FFFFFF";
	    if ($row['statusdata'] == 1) {
            $statusdata = "Data Perubahan";
			$bgcolor = "#FFFFB7";
        }
		    
        if ($cnt % 2 == 0) { ?>
        <tr>
            <td rowspan="4" align="center" bgcolor="#CCCC66"><strong><?=++$no ?></strong></td>
            <td colspan="6" align="left" bgcolor="#CCCC66"><font size="2"><em><strong>Perubahan dilakukan oleh <?=$row['petugasubah'] . " tanggal " . $row['tanggalubah'] ?></strong></em></font></td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#E5E5E5" ><strong>No. Jurnal :</strong> <?=$row['nokas'] ?>   
            &nbsp;&nbsp;<strong>Alasan : </strong><?=$row['alasan'];?>
            <br /><strong>Transaksi :</strong> <?=$row['transaksi'] ?></td>
        </tr>
    <?php  } ?>
    
        <tr>
            <td><?=$statusdata ?></td>
            <td align="center"><?=$row['tanggal'] ?></td>
            <td align="right"><?=FormatRupiah($row['jumlah']) ?></td>
            <td><?=$row['keterangan'] ?></td>
            <td align="center"><?=$row['petugas']; ?></td>
        </tr>
    <?php
        $cnt++;
    }
    CloseDb();
    ?>
    </table>
<?php 
} elseif ($id=="penerimaanjttcalon"){
?>
<table class="tab" id="table" border="1" width="100%" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000">
    <tr height="30" align="center">
        <td class="header" width="4%">No</td>
        <td class="header" width="17%">Status Data</td>
        <td class="header" width="10%">Tanggal</td>
        <td class="header" width="15%">Jumlah</td>
        <td class="header" width="*">Keterangan</td>
        <td class="header" width="15%">Petugas</td>
    </tr>
    <?php
    OpenDb();
    $sql = "SELECT DISTINCT ai.petugas as petugasubah, j.transaksi, date_format(ai.tanggal, '%d-%b-%Y %H:%i:%s') as tanggalubah, ap.replid AS id, ap.idaudit, ap.statusdata, j.nokas, date_format(ap.tanggal, '%d-%b-%Y') AS tanggal, ap.petugas, ap.keterangan, ap.jumlah, ap.petugas, ai.alasan FROM $db_name_fina.auditpenerimaanjttcalon ap, $db_name_fina.auditinfo ai, $db_name_fina.jurnal j WHERE j.replid = ap.idjurnal AND ap.idaudit = ai.replid AND ai.departemen = '$departemen' AND ai.sumber='penerimaanjtt$calon' AND ai.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY ap.idaudit DESC, ai.tanggal DESC, ap.statusdata ASC";
   	//echo $sql;
    $result = QueryDb($sql);
    $cnt = 0;
    $no = 0;
    while ($row = mysqli_fetch_array($result)) {
        $statusdata = "Data Lama";
        $bgcolor = "#FFFFFF";
	    if ($row['statusdata'] == 1) {
            $statusdata = "Data Perubahan";
			$bgcolor = "#FFFFB7";
        }
		    
        if ($cnt % 2 == 0) { ?>
        <tr>
            <td rowspan="4" align="center" bgcolor="#CCCC66"><strong><?=++$no ?></strong></td>
            <td colspan="6" align="left" bgcolor="#CCCC66"><font size="2"><em><strong>Perubahan dilakukan oleh <?=$row['petugasubah'] . " tanggal " . $row['tanggalubah'] ?></strong></em></font></td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#E5E5E5" ><strong>No. Jurnal :</strong> <?=$row['nokas'] ?>   
            &nbsp;&nbsp;<strong>Alasan : </strong><?=$row['alasan'];?>
            <br /><strong>Transaksi :</strong> <?=$row['transaksi'] ?></td>
        </tr>
    <?php  } ?>
    
        <tr>
            <td><?=$statusdata ?></td>
            <td align="center"><?=$row['tanggal'] ?></td>
            <td align="right"><?=FormatRupiah($row['jumlah']) ?></td>
            <td><?=$row['keterangan'] ?></td>
            <td align="center"><?=$row['petugas']; ?></td>
        </tr>
    <?php
        $cnt++;
    }
    CloseDb();
    ?>
    </table>
<?php 
} elseif ($id=="jurnalumum"){
?>
<table class="tab" id="table" border="1" width="100%" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000">
    <tr height="30" align="center">
        <td class="header" width="4%">No</td>
        <td class="header" width="10%">Status Data</td>
        <td class="header" width="10%">Tanggal</td>
        <td class="header" width="15%">Keterangan</td>
        <td class="header" width="*">Detail Jurnal</td>
        <td class="header" width="15%">Petugas</td>
    </tr>
    <?php
    OpenDb();
  	$sql = "SELECT DISTINCT ai.petugas AS petugasubah, j.transaksi, date_format(ai.tanggal, '%d-%b-%Y %H:%i:%s') as tanggalubah, aj.replid AS id, aj.idaudit, aj.status, aj.nokas, date_format(aj.tanggal, '%d-%b-%Y') AS tanggal,  aj.petugas, aj.keterangan, aj.petugas, ai.alasan FROM $db_name_fina.auditjurnal aj, $db_name_fina.auditinfo ai, $db_name_fina.jurnal j WHERE aj.idaudit = ai.replid AND ai.idsumber = j.replid AND ai.departemen = '$departemen' AND ai.sumber='jurnalumum' AND ai.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY aj.idaudit DESC, ai.tanggal DESC, aj.status ASC";
    $result = QueryDb($sql);
	
    $cnt = 0;
    $no = 0;
    while ($row = mysqli_fetch_array($result)) {
			
        $status = $row['status'];
        $idaudit = $row['idaudit'];
        $statusdata = "Data Lama";
		$bgcolor = "#FFFFFF";
        if ($row['status'] == 1) {
            $statusdata = "Data Perubahan";
			$bgcolor = "#FFFFB7";
		}             
		
        if ($cnt % 2 == 0) { ?>
        
    <tr>
        <td rowspan="4" align="center" bgcolor="#CCCC66"><strong><?=++$no ?></strong></td>
        <td colspan="6" align="left" bgcolor="#CCCC66"><font size="2"><em><strong>Perubahan dilakukan oleh <?=$row['petugasubah'] . " tanggal " . $row['tanggalubah'] ?></strong></em></font></td>
    </tr>
    <tr>
        <td colspan="6" bgcolor="#E5E5E5">
            <table cellpadding="0" cellspacing="0" style="border-collapse:collapse" width="100%" >
            <tr>
                <td width="30%"><strong>No. Jurnal : </strong><?=$row['nokas'] ?>
                <td valign="top" width="10%"><strong>Alasan : </td>
                <td rowspan="2" valign="top"><strong><?=$row['alasan']?></strong></td>
            </tr>
            <tr>
                <td><strong>Transaksi : </strong><?=$row['transaksi'] ?></td>
            </tr>
            </table>
        </td>     
    </tr>
    <?php  } ?>
        
    <tr bgcolor="<?=$bgcolor?>">
        <td><?=$statusdata ?></td>
        <td align="center" ><?=$row['tanggal'] ?></td>
        <td align="left"><?=$row['keterangan'] ?></td>
        <td bgcolor="#E8FFE8">
            <table cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse" width="100%" bgcolor="#FFFFFF">
    <?php 	$nokas = $row['nokas'];
            $sql = "SELECT ajd.koderek, ra.nama, ajd.debet, ajd.kredit FROM $db_name_fina.auditjurnaldetail ajd, $db_name_fina.jurnal j, $db_name_fina.rekakun ra WHERE ajd.idjurnal = j.replid AND ajd.koderek = ra.kode AND j.nokas = '$nokas' AND ajd.status = '$status' AND idaudit='$idaudit' ORDER BY ajd.replid";
            $result2 = QueryDb($sql);            
            while ($row2 = mysqli_fetch_row($result2)) {  ?>   
            <tr>
                <td width="*"><?=$row2[0] . " " . $row2[1] ?></td>
                <td width="30%" align="right"><?=FormatRupiah($row2[2]) ?></td>
                <td width="30%" align="right"><?=FormatRupiah($row2[3]) ?></td>
            </tr>
    <?php 	} ?>            
            </table>
			
        </td>
        <td align="center"><?=$row['petugas']; ?></td>
    </tr>
    <?php
        $cnt++;
    }
    CloseDb();
    ?>
    </table> 
<?php 
}
?>

  </td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>

</html>