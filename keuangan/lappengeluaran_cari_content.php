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
require_once('include/sessioninfo.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];
	
$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
	
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$kriteria = 0;
if (isset($_REQUEST['kriteria']))
	$kriteria = (int)$_REQUEST['kriteria'];

$keyword = "";
if (isset($_REQUEST['keyword']))
	$keyword = $_REQUEST['keyword'];
	
$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "p.tanggal";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
	
$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">

function cetakbukti(id) {
	newWindow('buktipengeluaran.php?idtransaksi='+id, 'BuktiPengeluaran','360','600','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function edit(id) {
	newWindow('pengeluaran_edit.php?idtransaksi='+id, 'EditPengeluaran','500','550','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() {	
	document.location.href = "lappengeluaran_cari_content.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&kriteria=<?=$kriteria?>&keyword=<?=urlencode((string) $keyword)?>";
	//document.location.reload();
}

function cetak() {
	var total = document.getElementById("total").value;
	
	var addr = "lappengeluaran_cari_cetak.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&kriteria=<?=$kriteria?>&keyword=<?=urlencode((string) $keyword)?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'CetakCariDetailLapPengeluaran','780','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() {
	var total = document.getElementById("total").value;
	
	var addr = "lappengeluaran_cari_excel.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&kriteria=<?=$kriteria?>&keyword=<?=urlencode((string) $keyword)?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'ExcelCariDetailLapPengeluaran','780','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	var varbaris=document.getElementById("varbaris").value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "lappengeluaran_cari_content.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris+"&kriteria=<?=$kriteria?>&keyword=<?=urlencode((string) $keyword)?>";
}

function change_page(page) {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lappengeluaran_cari_content.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lappengeluaran_cari_content.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&page="+hal+"&hal="+hal;
}

function change_baris() {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lappengeluaran_cari_content.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>
</head>

<body topmargin="0" marginheight="0" >
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
			      FROM pengeluaran p, jurnal j, datapengeluaran d 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND p.idpengeluaran = d.replid AND d.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' $sqlwhere ORDER BY p.tanggal";         
	
    $sql = "SELECT p.replid AS id, d.nama AS namapengeluaran, p.keperluan, p.keterangan, p.jenispemohon, 
	               p.nip, p.nis, p.pemohonlain, p.penerima, date_format(p.tanggal, '%d-%b-%Y') as tanggal, date_format(p.tanggalkeluar, '%d-%b-%Y') as tanggalkeluar, 
				   p.petugas, p.jumlah 
		     FROM pengeluaran p, jurnal j, datapengeluaran d 
			WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
			  AND p.idpengeluaran = d.replid AND d.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' 
			      $sqlwhere 
		 ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
   	
	$result_tot = QueryDb($sql_tot);
	$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
    
    $result = QueryDb($sql);
	
	$totalbiayaB = 0;
    while ($rowB = mysqli_fetch_array($result_tot)) {
        $totalbiayaB += $rowB['jumlah'];
	}

	if (mysqli_num_rows($result) > 0) {
	?>
    <input type="hidden" name="total" id="total" value="<?=$total?>"/>
    <table border="0" width="100%" align="center">
    <tr>
        <td align="right">
        <a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0"  onMouseOver="showhint('Refresh!', this, event, '50px')" />&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
        <a href="JavaScript:excel()"><img src="images/ico/excel.png" border="0" onMouseOver="showhint('Buka di Ms Excel!', this, event, '50px')"/>&nbsp;Excel</a>&nbsp;
        </td>
    </tr>
    </table>
    <br />
   <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
    <tr align="center" class="header" height="30">
        <td width="4%">No</td>
        <td width="10%" height="30" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('p.tanggal','<?=$urutan?>')">Tanggal <?=change_urut('p.tanggal',$urut,$urutan)?></td>
        <td width="15%" height="30" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('namapengeluaran','<?=$urutan?>')">Pengeluaran <?=change_urut('namapengeluaran',$urut,$urutan)?></td>
        <td width="15%">Pemohon</td>
        <td width="10%" height="30" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('penerima','<?=$urutan?>')">Penerima <?=change_urut('penerima',$urut,$urutan)?></td>
        <td width="10%" height="30" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('jumlah','<?=$urutan?>')">Jumlah <?=change_urut('jumlah',$urut,$urutan)?></td>
        <td width="*" >Keperluan</td>
        <td width="7%" height="30" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('petugas','<?=$urutan?>')">Petugas <?=change_urut('petugas',$urut,$urutan)?></td>
        <td width="7%">&nbsp;</td>
    </tr>
    <?php
    
  	if ($page==0)
		$cnt = 0;
	else 
		$cnt = (int)$page*(int)$varbaris;
    $totalbiaya = 0;
    while ($row = mysqli_fetch_array($result)) {
        
        if ($row['jenispemohon'] == 1) {
            $idpemohon = $row['nip'];
            $sql = "SELECT nama FROM jbssdm.pegawai WHERE nip = '".$idpemohon."'";
            $jenisinfo = "pegawai";
        } else if ($row['jenispemohon'] == 2) {
            $idpemohon = $row['nis'];
            $sql = "SELECT nama FROM jbsakad.siswa WHERE nis = '".$idpemohon."'";
            $jenisinfo = "siswa";
        } else {
            $idpemohon = "";
            $sql = "SELECT nama FROM pemohonlain WHERE replid = '".$row['pemohonlain']."'";
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
        <em>(<?=$jenisinfo ?>)</em>
        </td>
        <td valign="top"><?=$row['penerima'] ?></td>
        <td align="right" valign="top"><?=FormatRupiah($row['jumlah']) ?></td>
        <td valign="top">
        <strong>Keperluan: </strong><?=$row['keperluan'] ?><br />
        <strong>Keterangan: </strong><?=$row['keterangan'] ?>
        </td>
        <td valign="top" align="center"><?=$row['petugas'] ?></td>
        <td valign="top" align="center">
        <a href="JavaScript:cetakbukti(<?=$row['id'] ?>)"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak Bukti Pengeluaran Kas!', this, event, '150px')"/></a>&nbsp;
    <?php  if (getLevel() != 2) { ?>        
        <a href="JavaScript:edit(<?=$row['id'] ?>)"><img src="images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Pembayaran Pengeluaran!', this, event, '100px')"/></a>
   
    <?php  } ?>    
        </td>
    </tr>
    <?php
    }
    CloseDb();
    ?>
    <?php if ($page==$total-1){ ?>
	<tr height="30">
        <td colspan="5" align="center" bgcolor="#999900">
        <font color="#FFFFFF"><strong>T O T A L</strong></font>
        </td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbiayaB) ?></strong></font></td>
        <td colspan="3" bgcolor="#999900">&nbsp;</td>
    </tr>
	<?php } ?>
    </table>
    <script language='JavaScript'>
        Tables('table', 1, 0);
    </script>
    <?php CloseDb() ?>
     <?php if ($page==0){ 
		$disback="style='display:none;'";
		$disnext="style=''";
		}
		if ($page<$total && $page>0){
		$disback="style=''";
		$disnext="style=''";
		}
		if ($page==$total-1 && $page>0){
		$disback="style=''";
		$disnext="style='display:none;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='display:none;'";
		$disnext="style='display:none;'";
		}
	?>
    </td>
</tr> 
<tr>
    <td>
    <table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left" colspan="2">Halaman
		<input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
		<input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
	  	dari <?=$total?> halaman
		
 		</td>
        <td width="30%" align="right">Jumlah baris per halaman
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>
<?php } else { ?>
    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="250">
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data.<br />Silahkan ulangi pencarian kembali. </font>
            
        </td>
    </tr>
    </table>  
<?php } ?>
    </td>
</tr>
</table>
</body>
</html>