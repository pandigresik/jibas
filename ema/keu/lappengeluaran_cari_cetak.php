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
$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$idtahunbuku = "";
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];

$kriteria = "";
if (isset($_REQUEST['kriteria']))
	$kriteria = $_REQUEST['kriteria'];
	
$keyword = "";
if (isset($_REQUEST['keyword']))
	$keyword = $_REQUEST['keyword'];

switch ($kriteria){
	case 1 : $dasar = "Nama Pemohon";
		break;
	case 2 : $dasar = "Nama Penerima";
		break;
	case 3 : $dasar = "Nama Petugas";
		break;
	case 4 : $dasar = "Keperluan";
		break;
	case 5 : $dasar = "Keterangan";
		break;				
}
$ndepartemen = $departemen;
$ntahunbuku = getname2('tahunbuku',$db_name_fina.'.tahunbuku','replid',$idtahunbuku);
$nperiode = LongDateFormat($tanggal1)." s.d. ".LongDateFormat($tanggal2);

$urut = "nama";
$urutan = "ASC";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Jurnal Penerimaan]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>LAPORAN PENGELUARAN</strong></font><br />
 </center><br /><br />
<table width="100%">
<tr>
	<td width="8%" class="news_content1"><strong>Departemen</strong></td>
    <td width="92%" class="news_content1">: 
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
<tr>
  <td class="news_content1"><strong>Berdasarkan</strong></td>
  <td class="news_content1">:
    <?=$dasar ?> '<?=$keyword?>'</td>
  </tr>
</table>
<br />
<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
    <?php 
    if ($kriteria == 1)
        $sqlwhere = " AND p.namapemohon LIKE '%$keyword%'";
    else if ($kriteria == 2)
        $sqlwhere = " AND p.penerima LIKE '%$keyword%'";
    else if ($kriteria == 3)
        $sqlwhere = " AND p.petugas LIKE '%$keyword%'";
    else if ($kriteria == 4)
        $sqlwhere = " AND p.keperluan LIKE '%$keyword%'";
    else if ($kriteria == 5)
        $sqlwhere = " AND p.keterangan LIKE '%$keyword%'";
		
   	OpenDb();
	$sql_tot = "SELECT p.replid AS id, d.nama AS namapengeluaran, p.keperluan, p.keterangan, p.jenispemohon, 
	                   p.nip, p.nis, p.pemohonlain, p.penerima, date_format(p.tanggal, '%d-%b-%Y') as tanggal, date_format(p.tanggalkeluar, '%d-%b-%Y') as tanggalkeluar, 
					   p.petugas, p.jumlah 
			      FROM jbsfina.pengeluaran p, jbsfina.jurnal j, jbsfina.datapengeluaran d 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND p.idpengeluaran = d.replid AND d.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' $sqlwhere ORDER BY p.tanggal";         
	
    $sql = "SELECT p.replid AS id, d.nama AS namapengeluaran, p.keperluan, p.keterangan, p.jenispemohon, 
	               p.nip, p.nis, p.pemohonlain, p.penerima, date_format(p.tanggal, '%d-%b-%Y') as tanggal, date_format(p.tanggalkeluar, '%d-%b-%Y') as tanggalkeluar, 
				   p.petugas, p.jumlah 
		     FROM jbsfina.pengeluaran p, jbsfina.jurnal j, jbsfina.datapengeluaran d 
			WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
			  AND p.idpengeluaran = d.replid AND d.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' 
			      $sqlwhere 
		 ORDER BY $urut $urutan"; 
    
    $result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {
	?>
    <input type="hidden" name="total" id="total" value="<?=$total?>"/>
   <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
    <tr align="center" class="header" height="30">
        <td width="4%">No</td>
        <td width="10%" height="30">Tanggal</td>
        <td width="15%" height="30">Pengeluaran</td>
        <td width="15%">Pemohon</td>
        <td width="10%" height="30">Penerima</td>
        <td width="10%" height="30">Jumlah</td>
        <td width="*" >Keperluan</td>
        <td width="7%" height="30">Petugas</td>
        </tr>
    <?php
    
  	//if ($page==0)
		$cnt = 0;
	//else 
		//$cnt = (int)$page*(int)$varbaris;
    $totalbiaya = 0;
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
            $sql = "SELECT nama FROM $db_name_fina.pemohonlain WHERE replid = '".$row['pemohonlain']."'";
            $jenisinfo = "pemohon lain";
        }
        $result2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($result2);
        $namapemohon = $row2[0];
        
        $totalbiaya += $row['jumlah'];
    ?>
    <tr height="25">
        <td align="center" valign="top"><?=++$cnt ?></td>
        <td align="center" valign="top"><?=$row['tanggal'] ?></td>
        <td valign="top"><?=$row['namapengeluaran'] ?></td>
        <td valign="top"><?=$idpemohon?> <?=$namapemohon ?><br />
        <em>(<?=$jenisinfo ?>)</em>        </td>
        <td valign="top"><?=$row['penerima'] ?></td>
        <td align="right" valign="top"><?=FormatRupiah($row['jumlah']) ?></td>
        <td valign="top">
        <strong>Keperluan: </strong><?=$row['keperluan'] ?><br />
        <strong>Keterangan: </strong><?=$row['keterangan'] ?>        </td>
        <td valign="top" align="center"><?=$row['petugas'] ?></td>
        </tr>
    <?php
    }
    CloseDb();
    ?>
    <tr height="30">
        <td colspan="5" align="center" bgcolor="#999900">
        <font color="#FFFFFF"><strong>T O T A L</strong></font>        </td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbiaya) ?></strong></font></td>
        <td colspan="3" bgcolor="#999900">&nbsp;</td>
    </tr>
    </table>
    <?php } ?>
   </td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>

</html>