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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/rupiah.php');
require_once('../library/departemen.php');
require_once('../cek.php');

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['proses'])) 
	$proses = $_REQUEST['proses'];
	
if (isset($_REQUEST['kelompok']))
	$kelompok = $_REQUEST['kelompok'];

$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$varbaris=20;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];
	
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];

$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

OpenDb();
	
$op = $_REQUEST['op'];
if ($op == "dw8dxn8w9ms8zs22") 
{
	$sql = "UPDATE calonsiswa SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	QueryDb($sql);
} 
else if ($op == "xm8r389xemx23xb2378e23") 
{
    $sql = "SELECT nopendaftaran FROM calonsiswa WHERE replid = '".$_REQUEST['replid']."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $no = $row[0];

    $sql = "DELETE FROM tambahandatacalon WHERE nopendaftaran = '".$no."'";
    QueryDb($sql);

	$sql = "DELETE FROM calonsiswa WHERE replid = '".$_REQUEST['replid']."'"; 	
	QueryDb($sql);

	$page=0;
	$hal=0;	?>
    <script>refresh_add();</script> 
<?php
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pendataan Calon Siswa</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function refresh() {		
	var departemen = document.getElementById('departemen').value;	
	var proses = document.getElementById('proses').value;	
	var kelompok = document.getElementById('kelompok').value;
	
	document.location.href = "calon_content.php?proses="+proses+"&kelompok="+kelompok+"&departemen="+departemen;
}

function refresh_add() {	
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	var departemen = document.getElementById('departemen').value;	
	var proses = document.getElementById('proses').value;	
	var kelompok = document.getElementById('kelompok').value;
	
	document.location.href = "calon_content.php?urut="+urut+"&urutan="+urutan+"&proses="+proses+"&kelompok="+kelompok+"&departemen="+departemen+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function tambah() {
	var departemen = document.getElementById('departemen').value;
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	newWindow('calon_add.php?departemen='+departemen+'&proses='+proses+'&kelompok='+kelompok, 'TambahCalonSiswa','825','650','resizable=1,scrollbars=1,status=0,toolbar=0')
	//document.location.href = 'calon_add.php?departemen='+departemen+'&proses='+proses+'&kelompok='+kelompok;
}

function edit(replid){
	newWindow('calon_edit.php?replid='+replid,'UbahPendataanCalonSiswa','825','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function setaktif(replid, aktif) {
	var msg;
	var newaktif;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	var departemen = document.getElementById('departemen').value;	
	var proses = document.getElementById('proses').value;	
	var kelompok = document.getElementById('kelompok').value;
	
	if (aktif == 1) {
		msg = "Apakah anda yakin akan mengubah calon siswa ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		
		msg = "Apakah anda yakin akan mengubah calonsiswa ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg))  {
		document.location.href = "calon_content.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
		parent.header.location.href = "calon_header.php?departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok;
	}
		
}

function hapus(replid) {
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	var departemen = document.getElementById('departemen').value;
	var proses = document.getElementById('proses').value;	
	var kelompok = document.getElementById('kelompok').value;
	
	if (confirm("Apakah anda yakin akan menghapus calon siswa ini?"))
		document.location.href = "calon_content.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function cetak(urut, urutan) {
	var departemen = document.getElementById('departemen').value;
	var proses = document.getElementById('proses').value;	
	var kelompok = document.getElementById('kelompok').value;
	var total=document.getElementById("total").value;
	
	newWindow('calon_cetak.php?departemen='+departemen+'&proses='+proses+'&kelompok='+kelompok+'&urut='+urut+'&urutan='+urutan+'&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')	
}

function cetak_excel(urut, urutan) {	
	var departemen = document.getElementById('departemen').value;	
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	
	newWindow('calon_cetak_excel.php?departemen='+departemen+'&proses='+proses+'&kelompok='+kelompok+'&urut='+urut+'&urutan='+urutan, 'CetakCalonSiswaFormatExcel','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function tampil(replid) {
	newWindow('../library/detail_calon.php?replid='+replid, 'DetailCalonSiswa'+replid,'790','610','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cetak_detail(replid) {
	newWindow('calon_cetak_detail.php?replid='+replid, 'CetakDetailCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function change_urut(urut,urutan) {			
	var kelompok = document.getElementById("kelompok").value;
	var proses = document.getElementById("proses").value;
	var departemen = document.getElementById("departemen").value;	
	var varbaris=document.getElementById("varbaris").value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "calon_content.php?urutan="+urutan+"&urut="+urut+"&proses="+proses+"&kelompok="+kelompok+"&departemen="+departemen+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;

}

function refresh_simpan(dep,pro,kel) {	
	parent.header.location.href = "calon_header.php?departemen="+dep+"&proses="+pro+"&kelompok="+kel;
	document.location.href = "calon_content.php?departemen="+dep+"&proses="+pro+"&kelompok="+kel;
	
}

function change_page(page) {
	var kelompok = document.getElementById("kelompok").value;
	var proses = document.getElementById("proses").value;
	var departemen = document.getElementById("departemen").value;
	var varbaris=document.getElementById("varbaris").value;
		
	document.location.href = "calon_content.php?departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}

function change_hal() {
	var departemen = document.getElementById("departemen").value;
	var kelompok = document.getElementById("kelompok").value;
	var proses = document.getElementById("proses").value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="calon_content.php?departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var departemen = document.getElementById("departemen").value;
	var kelompok = document.getElementById("kelompok").value;
	var proses = document.getElementById("proses").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="calon_content.php?departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>

</head>

<body topmargin="0" leftmargin="0">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="urut" id="urut" value="<?=$urut ?>" />
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan ?>" />
<input type="hidden" name="proses" id="proses" value="<?=$proses ?>" />
<input type="hidden" name="kelompok" id="kelompok" value="<?=$kelompok ?>" />

<table border="0" width="100%" align="center" background="../images/b_calon.png" style="background-repeat:no-repeat;">
<!-- TABLE CENTER -->
<tr>
	<td align="right" >
<?php $sql_tot = "SELECT nopendaftaran,nama,asalsekolah,tmplahir,DAY(tgllahir),MONTH(tgllahir),YEAR(tgllahir),". 
		   "c.aktif,c.replid FROM calonsiswa c, kelompokcalonsiswa k, prosespenerimaansiswa p ".
		   "WHERE c.idproses = '$proses' AND c.idkelompok = '$kelompok' AND k.idproses = p.replid ".
		   "AND c.idproses = p.replid AND c.idkelompok = k.replid ORDER BY $urut $urutan ";
	
	$result_tot = QueryDb($sql_tot);
	$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	
	$sql = "SELECT nopendaftaran,nama,c.sum1,c.sum2,c.ujian1,c.ujian2,c.ujian3,c.ujian4,c.ujian5,c.ujian6,c.ujian7,c.ujian8,c.ujian9,c.ujian10,". 
		   "c.aktif,c.replid, c.replidsiswa,c.nisn,c.info3,c.pinsiswa FROM calonsiswa c, kelompokcalonsiswa k, prosespenerimaansiswa p ".
		   "WHERE c.idproses = '$proses' AND c.idkelompok = '$kelompok' AND k.idproses = p.replid ".
		   "AND c.idproses = p.replid AND c.idkelompok = k.replid ORDER BY $urut $urutan ".
		   "LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	
	$result = QueryDb($sql);
	
	if (@mysqli_num_rows($result)>0)
	{ 
		$sql1 = "SELECT COUNT(c.replid) AS isi FROM calonsiswa c WHERE c.idkelompok = '$kelompok' AND c.idproses = '$proses' AND c.aktif = 1";	
		$sql2 = "SELECT kapasitas, keterangan FROM kelompokcalonsiswa k WHERE k.idproses = '$proses' AND k.replid = '".$kelompok."'";
		$result1 = QueryDb($sql1);
		$result2 = QueryDb($sql2);
		$row1 = @mysqli_fetch_array($result1);
		$row2 = @mysqli_fetch_array($result2);
		$kapasitas = $row2['kapasitas'];
		$isi = $row1['isi'];
		
	?>
    <input type="hidden" name="total" id="total" value="<?=$total?>"/>
    <table width="100%" border="0" align="center">
  	<tr>
  		<td width="45%" valign="top" align="right">
  		<strong>Keterangan</strong>
        </td>
    	<td width="*" align="right">
    	<textarea name="keterangan" id="keterangan" rows="2" cols="80" readonly class="disabled"><?=$row2['keterangan'] ?></textarea>
        <!--<input type="text" id="keterangan" name="keterangan" disabled="disabled" class="disabled" value=<?=$row1['keterangan']?>  size="100"/>-->
        </td>
   	</tr>
	<tr>
    	<td colspan="2" align="left" valign="bottom">
        <br />
		
		<table border="0" width="88%">
		<tr><td align="right">
		<a href="#" onClick="refresh()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp; 		
        <a href="#" onClick="cetak_excel('<?=$urut?>','<?=$urutan?>')"><img src="../images/ico/excel.png" border="0" onMouseOver="showhint('Cetak dalam format Excel!', this, event, '80px')"/>&nbsp;Cetak Excel</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak('<?=$urut?>','<?=$urutan?>')"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
<?php  	if ($kapasitas > $isi) { ?>
		<a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah Calon Siswa!', this, event, '50px')"/>&nbsp;Tambah Calon Siswa</a>
<?php 	} ?>	
		</td></tr>	
		</table>
        
    	</td>
  	</tr>
	</table>   	
	<br />
	<table border="1" width="1470" id="table" class="tab" align="center" style="border-collapse:collapse" bordercolor="#000000">
	<tr class="header" height="30" align="center">		
		<td width="20" rowspan="2" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif">No</td>
		<td width="100" rowspan="2" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif">&nbsp;</td>
		<td width="120" rowspan="2" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('nopendaftaran','<?=$urutan?>')" style="cursor:pointer;">No Daftar <?=change_urut('nopendaftaran', $urut, $urutan)?></td>
		<td width="60" rowspan="2" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif">PIN</td>
		<td width="120" rowspan="2" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('nisn','<?=$urutan?>')" style="cursor:pointer;">NISN <?=change_urut('nisn', $urut, $urutan)?></td>
		<td width="180" rowspan="2" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('nama','<?=$urutan?>')" style="cursor:pointer;">Nama <?=change_urut('nama',$urut,$urutan)?></td>
        <td width="100" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('sum1','<?=$urutan?>')" style="cursor:pointer;">Sumb#1 <?=change_urut('sum1',$urut,$urutan)?></td>
        <td width="100" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('sum2','<?=$urutan?>')" style="cursor:pointer;">Sumb#2 <?=change_urut('sum2',$urut,$urutan)?></td>
        <td width="60" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian1','<?=$urutan?>')" style="cursor:pointer;">Uji#1 <?=change_urut('ujian1',$urut,$urutan)?></td>
        <td width="60" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian2','<?=$urutan?>')" style="cursor:pointer;">Uji#2 <?=change_urut('ujian2',$urut,$urutan)?></td>
        <td width="60" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian3','<?=$urutan?>')" style="cursor:pointer;">Uji#3 <?=change_urut('ujian3',$urut,$urutan)?></td>
        <td width="60" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian4','<?=$urutan?>')" style="cursor:pointer;">Uji#4 <?=change_urut('ujian4',$urut,$urutan)?></td>
        <td width="60" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian5','<?=$urutan?>')" style="cursor:pointer;">Uji#5 <?=change_urut('ujian5',$urut,$urutan)?></td>
		<td width="60" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian6','<?=$urutan?>')" style="cursor:pointer;">Uji#6 <?=change_urut('ujian6',$urut,$urutan)?></td>
		<td width="60" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian7','<?=$urutan?>')" style="cursor:pointer;">Uji#7 <?=change_urut('ujian7',$urut,$urutan)?></td>
		<td width="60" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian8','<?=$urutan?>')" style="cursor:pointer;">Uji#8 <?=change_urut('ujian8',$urut,$urutan)?></td>
		<td width="60" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian9','<?=$urutan?>')" style="cursor:pointer;">Uji#9 <?=change_urut('ujian9',$urut,$urutan)?></td>
		<td width="60" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian10','<?=$urutan?>')" style="cursor:pointer;">Uji#10 <?=change_urut('ujian10',$urut,$urutan)?></td>
		<td width="50" rowspan="2" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif"  onClick="change_urut('aktif','<?=$urutan?>')" style="cursor:pointer;">Status <?=change_urut('aktif',$urut,$urutan)?></td>
	    <td width="50" rowspan="2" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif">&nbsp;</td>
	</tr>
<?php 	$sqlset = "SELECT COUNT(replid) FROM settingpsb WHERE idproses = $proses";
		$resset = QueryDb($sqlset);
		$rowset = mysqli_fetch_row($resset);
		$ndata = $rowset[0];
		
		if ($ndata > 0)
		{
			$sqlset = "SELECT * FROM settingpsb WHERE idproses = $proses";
			$resset = QueryDb($sqlset);
			$rowset = mysqli_fetch_array($resset);
			
			$kdsum1 = $rowset['kdsum1']; //$nmsum1 = $rowset['nmsum1'];
			$kdsum2 = $rowset['kdsum2']; //$nmsum2 = $rowset['nmsum2'];
			$kdujian1 = $rowset['kdujian1']; //$nmujian1 = $rowset['nmujian1'];
			$kdujian2 = $rowset['kdujian2']; //$nmujian2 = $rowset['nmujian2'];
			$kdujian3 = $rowset['kdujian3']; //$nmujian3 = $rowset['nmujian3'];
			$kdujian4 = $rowset['kdujian4']; //$nmujian4 = $rowset['nmujian4'];
			$kdujian5 = $rowset['kdujian5']; //$nmujian5 = $rowset['nmujian5'];
			$kdujian6 = $rowset['kdujian6'];
			$kdujian7 = $rowset['kdujian7'];
			$kdujian8 = $rowset['kdujian8'];
			$kdujian9 = $rowset['kdujian9'];
			$kdujian10 = $rowset['kdujian10'];
		} ?>
    <tr class="header" height="30" align="center">		
        <td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('sum1','<?=$urutan?>')" style="cursor:pointer;"><?=$kdsum1?> <?=change_urut('sum1',$urut,$urutan)?></td>
        <td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('sum2','<?=$urutan?>')" style="cursor:pointer;"><?=$kdsum2?> <?=change_urut('sum2',$urut,$urutan)?></td>
        <td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian1','<?=$urutan?>')" style="cursor:pointer;"><?=$kdujian1?> <?=change_urut('ujian1',$urut,$urutan)?></td>
        <td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian2','<?=$urutan?>')" style="cursor:pointer;"><?=$kdujian2?> <?=change_urut('ujian2',$urut,$urutan)?></td>
        <td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian3','<?=$urutan?>')" style="cursor:pointer;"><?=$kdujian3?> <?=change_urut('ujian3',$urut,$urutan)?></td>
        <td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian4','<?=$urutan?>')" style="cursor:pointer;"><?=$kdujian4?> <?=change_urut('ujian4',$urut,$urutan)?></td>
        <td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian5','<?=$urutan?>')" style="cursor:pointer;"><?=$kdujian5?> <?=change_urut('ujian5',$urut,$urutan)?></td>
		<td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian6','<?=$urutan?>')" style="cursor:pointer;"><?=$kdujian6?> <?=change_urut('ujian6',$urut,$urutan)?></td>
		<td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian7','<?=$urutan?>')" style="cursor:pointer;"><?=$kdujian7?> <?=change_urut('ujian7',$urut,$urutan)?></td>
		<td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian8','<?=$urutan?>')" style="cursor:pointer;"><?=$kdujian8?> <?=change_urut('ujian8',$urut,$urutan)?></td>
		<td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian9','<?=$urutan?>')" style="cursor:pointer;"><?=$kdujian9?> <?=change_urut('ujian9',$urut,$urutan)?></td>
		<td onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('ujian10','<?=$urutan?>')" style="cursor:pointer;"><?=$kdujian10?> <?=change_urut('ujian10',$urut,$urutan)?></td>
	</tr>
        
<?php 		if ($page==0)
			$cnt = 1;
		else 
			$cnt = (int)$page*(int)$varbaris+1;
		
		while ($row = @mysqli_fetch_array($result)) 
		{
			$siswa = "";
			$pjg = "80px";
			if ($row["replidsiswa"] <> 0) 
			{
				$sql3 = "SELECT nis FROM jbsakad.siswa WHERE replid = '".$row['replidsiswa']."'";
				$result3 = QueryDb($sql3);
				$row3 = @mysqli_fetch_array($result3);
				$siswa = "<br>NIS Siswa: <b>".$row3['nis']."</b>";
				$pjg = "125px";
			}
		?>	
		<tr>        			
			<td height="25" align="center"><?=$cnt?></td>
			<td height="25" align="center">
	            <a href="JavaScript:tampil(<?=$row["replid"] ?>)"><img src="../images/ico/lihat.png" border="0" onMouseOver="showhint('Detail Data Calon Siswa!', this, event, '80px')"/></a>&nbsp;
	            <a href="JavaScript:cetak_detail(<?=$row["replid"] ?>)"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak Detail Data Calon Siswa!', this, event, '110px')"/></a>&nbsp; 
	            <a href="JavaScript:edit(<?=$row["replid"] ?>)" ><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Calon Siswa!', this, event, '80px')"/></a>&nbsp;
			</td>
			<td height="25" align="left"><?=$row["nopendaftaran"]?></td>
			<td height="25" align="left"><?=$row["pinsiswa"]?></td>
			<td height="25" align="left"><?=$row["nisn"]?></td>
  			<td height="25" align="left"><?=$row["nama"]?></td>
            <td height="25" align="right"><?=FormatRupiah($row["sum1"])?></td>
            <td height="25" align="right"><?=FormatRupiah($row["sum2"])?></td>
            <td height="25" align="center"><?=$row["ujian1"]?></td>
            <td height="25" align="center"><?=$row["ujian2"]?></td>
            <td height="25" align="center"><?=$row["ujian3"]?></td>
            <td height="25" align="center"><?=$row["ujian4"]?></td>
            <td height="25" align="center"><?=$row["ujian5"]?></td>
			<td height="25" align="center"><?=$row["ujian6"]?></td>
			<td height="25" align="center"><?=$row["ujian7"]?></td>
			<td height="25" align="center"><?=$row["ujian8"]?></td>
			<td height="25" align="center"><?=$row["ujian9"]?></td>
			<td height="25" align="center"><?=$row["ujian10"]?></td>
            <td height="25" align="center">
<?php 	if (SI_USER_LEVEL() == $SI_USER_STAFF) {  
			if ($row["aktif"] == 1) { ?> 
            	<img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif<?=$siswa?>', this, event, '<?=$pjg?>')"/>
<?php 		} else { ?>                
				<img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif<?=$siswa?>', this, event, '<?=$pjg?>')"/>
<?php 		}
		} else { 
			if ($row["aktif"] == 1) { ?>
				<a href="JavaScript:setaktif(<?=$row["replid"] ?>, <?=$row["aktif"] ?>)"><img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif<?=$siswa?>', this, event, '<?=$pjg?>')" /></a>
<?php 		} else { 
				if ($kapasitas > $isi) {?>
				<a href="JavaScript:setaktif(<?=$row["replid"] ?>, <?=$row["aktif"] ?>)"><img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif<?=$siswa?>', this, event, '<?=$pjg?>')"/></a>
           	<?php  } else { ?>
                <img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Calon siswa tidak dapat diaktifkan karena kapasitas kelompok tidak mencukupi<?=$siswa?>', this, event, '165px')"/>
<?php 			}
			} //end if
		} //end if ?>        	
        	</td>
            <td height="25" align="center">
     <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>            
            <a href="JavaScript:hapus(<?=$row["replid"] ?>)" ><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Data Calon Siswa!', this, event, '80px')"/></a>
<?php 	} ?>
			</td>
        </tr>
<?php 	$cnt++; } ?>	
		 
    <!-- END TABLE CONTENT -->
    </table>
   
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
    
    <?php if ($page==0){ 
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page<$total && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page==$total-1 && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:hidden;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:hidden;'";
		}
	?>
   	</td>
</tr> 
<tr>
    <td>
    <table border="0"width="100%" align="center" cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left">Halaman
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
	  	dari <?=$total?> halaman
        </td>
        <td width="30%" align="right">Jumlah baris per halaman
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=10; $m <= 100; $m=$m+10) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>      
      	</select></td>
    </tr>
    </table>	
    </td></tr>
<!-- END TABLE CENTER -->    
</table>
<?php } else { ?>

<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="300">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
       	 <?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        <?php //} ?>        
        </b></font>
	</td>
</tr>
</table>  
<?php } ?> 
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    
</body>
</html>
<?php
CloseDb();
?>