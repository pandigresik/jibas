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
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/getheader.php'); 

$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];

$bln = 0;
if (isset($_REQUEST['bln']))
	$bln = (int)$_REQUEST['bln'];

$thn = 0;
if (isset($_REQUEST['thn']))
	$thn = (int)$_REQUEST['thn'];
	
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Arus Kas]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>
<center><font size="4"><strong>LAPORAN ARUS KAS</strong></font><br /> </center><br /><br />

<table border="0">
<tr>
	<td width="90"><strong>Departemen </strong></td>
    <td><strong>: <?=$departemen ?></strong></td>
</tr>
<tr>
	<td><strong>Per Tanggal </strong></td>
    <td><strong>: <?=LongDateFormat($tanggal2) ?></strong></td>
</tr>
</table>
<br />

<?php
OpenDb();

//$sql = "SELECT tanggalmulai FROM tahunbuku WHERE id = $idtahunbuku";
//$result = QueryDb($sql);
//$row = mysqli_fetch_row($result);
//$tanggal1 = $row[0];

$firstdate = "$thn-$bln-1";
$sql = "SELECT date_sub('$firstdate', INTERVAL 1 DAY)";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$lastdate = $row[0];
?>
<br />
<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td valign="middle">
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="100%">
    <tr height="30">
    	<td colspan="4" align="left"><font size="2"><strong>Arus Kas dari Kegiatan Operasional</strong></font></td>
    </tr>
    <?php
	$totalpendapatan = 0;
	
    // Jumlah Setiap Pendapatan dari Iuran Wajib Siswa
    $sql = "SELECT kode, nama FROM rekakun WHERE kategori = 'PENDAPATAN' ORDER BY kode";
    $result = QueryDb($sql);
    while ($row = mysqli_fetch_row($result)) 
	{
        $koderek = $row[0];
        $namarek = $row[1];
        $sql = "SELECT sum(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra 
                WHERE jd.koderek = ra.kode AND ra.kategori = 'HARTA' AND 
                jd.idjurnal IN (
                    SELECT j.replid FROM jurnal j, penerimaanjtt p, besarjtt b, datapenerimaan dp 
                    WHERE j.replid = p.idjurnal AND p.idbesarjtt = b.replid AND b.idpenerimaan = dp.replid 
                    AND dp.rekpendapatan = '$koderek' 
                    AND j.tanggal BETWEEN '$firstdate' AND '$tanggal2' AND j.idtahunbuku = '$idtahunbuku')";          
        $result2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($result2);
        $jpendapatan = (float)$row2[0]; 
        if ($jpendapatan > 0) 
		{
            $totalpendapatan += $jpendapatan; ?>
            <tr height="25">
                <td width="20">&nbsp;</td>
                <td width="420">Kas diterima dari <?=$namarek ?></td>
                <td width="120" align="right"><?=FormatRupiah($jpendapatan) ?></td>
                <td width="120" align="right">&nbsp;</td>
            </tr>
    <?php  } //end if
    } //end while ?>
    
    <?php
    // Jumlah Setiap Pendapatan dari Iuran Sukarela Siswa
    $sql = "SELECT kode, nama FROM rekakun WHERE kategori = 'PENDAPATAN' ORDER BY kode";
    $result = QueryDb($sql);
    while ($row = mysqli_fetch_row($result)) 
	{
        $koderek = $row[0];
        $namarek = $row[1];
        $sql = "SELECT sum(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra 
                WHERE jd.koderek = ra.kode AND ra.kategori = 'HARTA' AND 
                jd.idjurnal IN (
                    SELECT j.replid FROM jurnal j, penerimaaniuran p, datapenerimaan dp 
                    WHERE j.replid = p.idjurnal AND p.idpenerimaan = dp.replid AND dp.rekpendapatan = '$koderek' 
                    AND j.tanggal BETWEEN '$firstdate' AND '$tanggal2' AND j.idtahunbuku = '$idtahunbuku')";

        $result2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($result2);
        $jpendapatan = (float)$row2[0]; 
        if ($jpendapatan > 0) 
		{
            $totalpendapatan += $jpendapatan; ?>
            <tr height="25">
                <td width="20">&nbsp;</td>
                <td width="420">Kas diterima dari <?=$namarek ?></td>
                <td width="120" align="right"><?=FormatRupiah($jpendapatan) ?></td>
                <td width="120" align="right">&nbsp;</td>
            </tr>
    <?php  } //end if
    } //end while ?>
    
    <?php
    // Jumlah Setiap Pendapatan dari Iuran Wajib Calon Siswa
    $sql = "SELECT kode, nama FROM rekakun WHERE kategori = 'PENDAPATAN' ORDER BY kode";
    $result = QueryDb($sql);
    while ($row = mysqli_fetch_row($result)) 
	{
        $koderek = $row[0];
        $namarek = $row[1];
        $sql = "SELECT sum(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra 
                WHERE jd.koderek = ra.kode AND ra.kategori = 'HARTA' AND 
                jd.idjurnal IN (
                    SELECT j.replid FROM jurnal j, penerimaanjttcalon p, besarjttcalon b, datapenerimaan dp 
                    WHERE j.replid = p.idjurnal AND p.idbesarjttcalon = b.replid AND b.idpenerimaan = dp.replid 
                    AND dp.rekpendapatan = '$koderek' 
                    AND j.tanggal BETWEEN '$firstdate' AND '$tanggal2' AND j.idtahunbuku = '$idtahunbuku')";
	
        $result2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($result2);
        $jpendapatan = (float)$row2[0]; 
        if ($jpendapatan > 0) 
		{
            $totalpendapatan += $jpendapatan; ?>
            <tr height="25">
                <td width="20">&nbsp;</td>
                <td width="420">Kas diterima dari <?=$namarek ?></td>
                <td width="120" align="right"><?=FormatRupiah($jpendapatan) ?></td>
                <td width="120" align="right">&nbsp;</td>
            </tr>
    <?php  } //end if
    } //end while ?>
    
    <?php
    // Jumlah Setiap Pendapatan dari Iuran Sukarela Siswa
    $sql = "SELECT kode, nama FROM rekakun WHERE kategori = 'PENDAPATAN' ORDER BY kode";
    $result = QueryDb($sql);
    while ($row = mysqli_fetch_row($result)) 
	{
        $koderek = $row[0];
        $namarek = $row[1];
        $sql = "SELECT sum(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra 
                WHERE jd.koderek = ra.kode AND ra.kategori = 'HARTA' AND 
                jd.idjurnal IN (
                    SELECT j.replid FROM jurnal j, penerimaaniurancalon p, datapenerimaan dp 
                    WHERE j.replid = p.idjurnal AND p.idpenerimaan = dp.replid AND dp.rekpendapatan = '$koderek' 
                    AND j.tanggal BETWEEN '$firstdate' AND '$tanggal2' AND j.idtahunbuku = '$idtahunbuku')";
		
        $result2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($result2);
        $jpendapatan = (float)$row2[0]; 
        if ($jpendapatan > 0) 
		{
            $totalpendapatan += $jpendapatan; ?>
            <tr height="25">
                <td width="20">&nbsp;</td>
                <td width="420">Kas diterima dari <?=$namarek ?></td>
                <td width="120" align="right"><?=FormatRupiah($jpendapatan) ?></td>
                <td width="120" align="right">&nbsp;</td>
            </tr>
    <?php  } //end if
    } //end while ?> 
    
    <?php
    // Jumlah Setiap Pendapatan dari Peneriman Lain
    $sql = "SELECT kode, nama FROM rekakun WHERE kategori = 'PENDAPATAN' ORDER BY kode";
    $result = QueryDb($sql);
    while ($row = mysqli_fetch_row($result)) 
	{
        $koderek = $row[0];
        $namarek = $row[1];
        $sql = "SELECT sum(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra 
                WHERE jd.koderek = ra.kode AND ra.kategori = 'HARTA' AND 
                jd.idjurnal IN (
                    SELECT j.replid FROM jurnal j, penerimaanlain p, datapenerimaan dp 
                    WHERE j.replid = p.idjurnal AND p.idpenerimaan = dp.replid AND dp.rekpendapatan = '$koderek' 
                    AND j.tanggal BETWEEN '$firstdate' AND '$tanggal2' AND j.idtahunbuku = '$idtahunbuku')";
                    
        $result2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($result2);
        $jpendapatan = (float)$row2[0]; 
        if ($jpendapatan > 0) 
		{
            $totalpendapatan += $jpendapatan; ?>
            <tr height="25">
                <td width="20">&nbsp;</td>
                <td width="420">Kas diterima dari <?=$namarek ?></td>
                <td width="120" align="right"><?=FormatRupiah($jpendapatan) ?></td>
                <td width="120" align="right">&nbsp;</td>
            </tr>
    <?php  } //end if
    } //end while ?>
    
    <?php
    // Jumlah Pembayaran Beban
    $sql = "SELECT SUM(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra 
            WHERE jd.koderek = ra.kode AND ra.kategori = 'HARTA' AND 
            jd.idjurnal IN (
                SELECT jd.idjurnal FROM jurnaldetail jd, jurnal j, rekakun ra 
                WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.tanggal BETWEEN '$firstdate' 
                AND '$tanggal2' AND j.idtahunbuku = '$idtahunbuku' AND ra.kategori = 'BIAYA')";
    //echo  $sql;		
    $result = QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $totalbiaya = (float)$row[0];
    ?>
    <tr height="25">
        <td width="20">&nbsp;</td>
        <td width="420">Pembayaran Beban</td>
        <td width="120" align="right"><?=FormatRupiah($totalbiaya) ?></td>
        <td width="120" align="right">&nbsp;</td>
    </tr>
        
    <tr height="30">
        <td width="20">&nbsp;</td>
        <td width="420"><font size="2"><strong><em>Arus Kas Bersih Kegiatan Operasional</em></strong></font></td>
        <td width="120" align="right">&nbsp;</td>
        <td width="120" align="right"><font size="2"><strong>
        <?php $totaloperasional = ($totalpendapatan + $totalbiaya);
            echo  FormatRupiah($totaloperasional) ?></strong></font></td>
    </tr>
    
    
    <tr height="5">
    <td colspan="4" align="left">&nbsp;</td>
    </tr>
    
    <tr height="30">
    <td colspan="4" align="left"><font size="2"><strong>Arus Kas dari Kegiatan Keuangan</strong></font></td>
    </tr>
    
    <?php
    // Penambahan Piutang
    $sql = "SELECT SUM(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra 
            WHERE jd.koderek = ra.kode AND ra.kategori = 'HARTA' AND jd.kredit > 0 
            AND jd.idjurnal IN (
                SELECT jd.idjurnal FROM jurnaldetail jd, jurnal j, rekakun ra 
                WHERE j.sumber = 'jurnalumum' AND jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.tanggal 
                BETWEEN '$firstdate' AND '$tanggal2' AND j.idtahunbuku = '$idtahunbuku'
                AND ra.kategori = 'PIUTANG' AND jd.debet > 0)
            GROUP BY ra.nama";
    //echo  $sql;
    $result = QueryDb($sql);
    $totalpiutangtambah = 0;
    while($row = mysqli_fetch_row($result)) {
        $piutang = (float)$row[0];
        $totalpiutangtambah += $piutang;
    ?>
    <tr height="25">
        <td width="20">&nbsp;</td>
        <td width="420">Penambahan Piutang Usaha</td>
        <td width="120" align="right"><?=FormatRupiah($piutang) ?></td>
        <td width="120" align="right">&nbsp;</td>
    </tr>
    <?php } ?>
    
    <?php
    // Pengurangan Piutang
    $sql = "SELECT SUM(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra 
            WHERE jd.koderek = ra.kode AND ra.kategori = 'HARTA' AND jd.debet > 0 
            AND jd.idjurnal IN (
                SELECT jd.idjurnal FROM jurnaldetail jd, jurnal j, rekakun ra 
                WHERE j.sumber = 'jurnalumum' AND jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.tanggal 
                BETWEEN '$firstdate' AND '$tanggal2' 
                AND j.idtahunbuku = '$idtahunbuku' AND ra.kategori = 'PIUTANG' AND jd.kredit > 0)
            GROUP BY ra.nama";
    //echo  $sql;
    $result = QueryDb($sql);
    $totalpiutangkurang = 0;
    while($row = mysqli_fetch_row($result)) {
        $piutang = (float)$row[0];
        $totalpiutangkurang += $piutang;
    ?>
    <tr height="25">
        <td width="20">&nbsp;</td>
        <td width="420">Pengurangan Piutang Usaha</td>
        <td width="120" align="right"><?=FormatRupiah($piutang) ?></td>
        <td width="120" align="right">&nbsp;</td>
    </tr>
    <?php } ?>
    
    
    <?php
    // Jumlah Penurunan Hutang
    $sql = "SELECT SUM(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra 
            WHERE jd.koderek = ra.kode AND ra.kategori = 'HARTA' AND jd.kredit > 0 
            AND jd.idjurnal IN (
                SELECT jd.idjurnal FROM jurnaldetail jd, jurnal j, rekakun ra 
                WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.tanggal BETWEEN '$firstdate' 
                AND '$tanggal2' AND j.idtahunbuku = '$idtahunbuku' AND ra.kategori = 'UTANG' AND jd.debet > 0)";
    $result = QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $totalutangturun = (float)$row[0];
    ?>
    <tr height="25">
        <td width="20">&nbsp;</td>
        <td width="420">Penurunan Utang</td>
        <td width="120" align="right"><?=FormatRupiah($totalutangturun) ?></td>
        <td width="120" align="right">&nbsp;</td>
    </tr>
    
    <?php
    // Jumlah Kenaikan Hutang
    $sql = "SELECT SUM(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra 
            WHERE jd.koderek = ra.kode AND ra.kategori = 'HARTA' AND jd.debet > 0 
            AND jd.idjurnal IN (
                SELECT jd.idjurnal FROM jurnaldetail jd, jurnal j, rekakun ra 
                WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.tanggal BETWEEN '$firstdate' 
                AND '$tanggal2' AND j.idtahunbuku = '$idtahunbuku' AND ra.kategori = 'UTANG' AND jd.kredit > 0)";
    $result = QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $totalutangnaik = (float)$row[0];
    ?>
    <tr height="25">
        <td width="20">&nbsp;</td>
        <td width="420">Kenaikan Utang</td>
        <td width="120" align="right"><?=FormatRupiah($totalutangnaik) ?></td>
        <td width="120" align="right">&nbsp;</td>
    </tr>
    
    <tr height="30">
        <td width="20">&nbsp;</td>
        <td width="420"><font size="2"><strong><em>Arus Kas Bersih Kegiatan Keuangan</em></strong></font></td>
        <td width="120" align="right">&nbsp;</td>
        <td width="120" align="right"><font size="2"><strong>
    <?php $totalkeuangan = $totalpiutangtambah + $totalpiutangkurang + $totalutangturun + $totalutangnaik;
        echo  FormatRupiah($totalkeuangan) ?></strong></font></td>
    </tr>
    
    <tr height="5">
    <td colspan="4" align="left">&nbsp;</td>
    </tr>
    
    <tr height="30">
    <td colspan="4" align="left"><font size="2"><strong>Arus Kas dari Kegiatan Investasi</strong></font></td>
    </tr>
    
    <?php
    //Penambahan kas dari setoran modal
    $sql = "SELECT x.nama, SUM(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra,
             (SELECT jd.idjurnal, ra.nama FROM jurnaldetail jd, jurnal j, rekakun ra 
              WHERE j.sumber = 'jurnalumum' AND jd.idjurnal = j.replid AND jd.koderek = ra.kode
              AND j.tanggal BETWEEN '$firstdate' AND '$tanggal2' 
              AND j.idtahunbuku = '$idtahunbuku' AND ra.kategori = 'MODAL' AND jd.kredit > 0) AS x
            WHERE x.idjurnal = jd.idjurnal AND jd.koderek = ra.kode AND jd.debet > 0 AND ra.kategori = 'HARTA' 
            GROUP BY x.nama";
    $result = QueryDb($sql);
    $totalmodalterima = 0;
    while($row = mysqli_fetch_row($result)) {
        $totalmodalterima += (float)$row[1];
    ?>
    <tr height="25">
        <td width="20">&nbsp;</td>
        <td width="420">Kas diterima dari penambahan <?=$row[0] ?></td>
        <td width="120" align="right"><?=FormatRupiah($row[1]) ?></td>
        <td width="120" align="right">&nbsp;</td>
    </tr>
    <?php } ?>
    
    <?php
    // Pengembilan kas dari modal
    $sql = "SELECT x.nama, SUM(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra,
             (SELECT jd.idjurnal, ra.nama FROM jurnaldetail jd, jurnal j, rekakun ra 
              WHERE j.sumber = 'jurnalumum' AND jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.tanggal 
              BETWEEN '$firstdate' AND '$tanggal2' 
              AND j.idtahunbuku = '$idtahunbuku' AND ra.kategori = 'MODAL' AND jd.debet > 0) AS x
            WHERE x.idjurnal = jd.idjurnal AND jd.koderek = ra.kode AND jd.kredit > 0 AND ra.kategori = 'HARTA' 
            GROUP BY x.nama";
    $result = QueryDb($sql);
    $totalmodalambil = 0;
    while($row = mysqli_fetch_row($result)) {
        $totalmodalambil += (float)$row[1];
    ?>
    <tr height="25">
        <td width="20">&nbsp;</td>
        <td width="420">Pengurangan kas dari pengambilan<?=$row[0] ?></td>
        <td width="120" align="right"><?=FormatRupiah($row[1]) ?></td>
        <td width="120" align="right">&nbsp;</td>
    </tr>
    <?php } ?>
    
    <?php
    //INVESTASi
    $sql = "SELECT x.nama, SUM(jd.debet - jd.kredit) FROM jurnaldetail jd, rekakun ra,
             (SELECT jd.idjurnal, ra.nama FROM jurnaldetail jd, jurnal j, rekakun ra 
              WHERE j.sumber = 'jurnalumum' AND jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.tanggal 
              BETWEEN '$firstdate' AND '$tanggal2' 
              AND j.idtahunbuku = '$idtahunbuku' AND ra.kategori = 'INVENTARIS') AS x
            WHERE x.idjurnal = jd.idjurnal AND jd.koderek = ra.kode AND ra.kategori = 'HARTA' GROUP BY x.nama";
    // echo  $sql;		
    $result = QueryDb($sql);
    $totalinvest = 0;
	$subinvest = 0;
    while($row = mysqli_fetch_row($result)) {
        $invest = (float)$row[1];
		$subinvest += $invest;
    ?>
    <tr height="25">
        <td width="20">&nbsp;</td>
        <td width="420"><?=$row[0] ?></td>
        <td width="120" align="right"><?=FormatRupiah($invest) ?></td>
        <td width="120" align="right">&nbsp;</td>
    </tr>
    <?php } ?>
    
    <tr height="30">
        <td width="20">&nbsp;</td>
        <td width="420"><font size="2"><strong><em>Arus Kas Bersih Kegiatan Investasi</em></strong></font></td>
        <td width="120" align="right">&nbsp;</td>
        <td width="120" align="right"><font size="2"><strong>
    <?php 
	$totalinvest = $totalmodalterima + $totalmodalambil + $subinvest;
	echo  FormatRupiah($totalinvest) ?></strong></font></td>
    </tr>
    
    <tr height="5">
    <td colspan="4" align="left">&nbsp;</td>
    </tr>
    
    <tr height="30">
        <td colspan="3"><font size="2"><strong><em>Perubahan Kas</em></strong></font></td>
        <td width="150" align="right"><font size="2"><strong>
    <?php $totalperubahan = $totaloperasional + $totalkeuangan + $totalinvest;
        echo  FormatRupiah($totalperubahan) ?></strong></font></td>
    </tr>
    
    <tr height="30">
        <td colspan="3"><font size="2"><strong><em>Saldo Kas <?=LongDateFormat($firstdate) ?></em></strong></font></td>
        <td width="120" align="right"><font size="2"><strong>
    <?php $sql = "SELECT SUM(jd.debet - jd.kredit) FROM jurnaldetail jd, jurnal j, rekakun ra WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.tanggal BETWEEN '$tanggal1' AND '$lastdate' AND j.idtahunbuku = '$idtahunbuku' AND ra.kategori = 'HARTA'";
        $result = QueryDb($sql);
        $row = mysqli_fetch_row($result);
        $saldoawal = (float)$row[0]; 
        echo  FormatRupiah($saldoawal); ?></strong></font></td>
    </tr>
    
    <tr height="30">
        <td colspan="3"><font size="2"><strong><em>Saldo Kas <?=LongDateFormat($tanggal2) ?></em></strong></font></td>
        <td width="150" align="right"><font size="2"><strong>
    <?=FormatRupiah($saldoawal + $totalperubahan); ?></strong></font></td>
    </tr>
    
    <?php CloseDb() ?>
    
    </table>
</td></tr>
</table>

</td></tr></table>

<script language="javascript">window.print();</script>

</body>
</html>