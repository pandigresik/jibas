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

OpenDb();

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$idtahunbuku = "";
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];	

$tgl1 = 0;
if (isset($_REQUEST['tgl1']))
	$tgl1 = (int)$_REQUEST['tgl1'];

$bln1 = 0;
if (isset($_REQUEST['bln1']))
	$bln1 = (int)$_REQUEST['bln1'];

$thn1 = 0;
if (isset($_REQUEST['thn1']))
	$thn1 = (int)$_REQUEST['thn1'];

$tgl2 = date("j");
if (isset($_REQUEST['tgl2']))
	$tgl2 = (int)$_REQUEST['tgl2'];

$bln2 = date("n");
if (isset($_REQUEST['bln2']))
	$bln2 = (int)$_REQUEST['bln2'];

$thn2 = date("Y");
if (isset($_REQUEST['thn2']))
	$thn2 = (int)$_REQUEST['thn2'];	

$n1 = JmlHari($bln1,$thn1);
$n2 = JmlHari($bln2,$thn2);

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];
	
$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">
function change_sel() {
	//parent.contentblank.location.href = "lappengeluaran_jenis_blank.php";
}

function change_dep() {
	var departemen = document.getElementById('departemen').value;
	var tgl1 = document.getElementById('tgl1').value;
	var bln1 = document.getElementById('bln1').value;
	var thn1 = document.getElementById('thn1').value;
	var tgl2 = document.getElementById('tgl2').value;
	var bln2 = document.getElementById('bln2').value;
	var thn2 = document.getElementById('thn2').value;
		
	document.location.href = "lappengeluaran.php?tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+departemen;
	//parent.contentblank.location.href = "lappengeluaran_jenis_blank.php";
	//parent.pilih.location.href = "lappengeluaran_jenis_blank.php";
	//parent.contentblank.location.href = "lappengeluaran_jenis_blank.php";
}

function change_tahunbuku()
{
	var departemen = document.getElementById("departemen").value;
	var idtahunbuku = document.getElementById("idtahunbuku").value;
	
	document.location.href = "lappengeluaran.php?departemen="+departemen+"&idtahunbuku="+idtahunbuku;
}

function show_laporan() {
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var dep = document.getElementById('departemen').value;
	var tgl1 = parseInt(document.getElementById('tgl1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var thn1 = parseInt(document.getElementById('thn1').value);
	var tgl2 = parseInt(document.getElementById('tgl2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var thn2 = parseInt(document.getElementById('thn2').value);
	var tanggal1 = escape(thn1 + "-" + bln1 + "-" + tgl1);
	var tanggal2 = escape(thn2 + "-" + bln2 + "-" + tgl2);
	
	if (idtahunbuku.length == 0) {	
		alert ('Tahun Buku tidak boleh kosong!');
		document.getElementById('departemen').focus();
		return false;
	} else if (tgl1.length == 0) {	
		alert ('Tanggal awal tidak boleh kosong!');	
		document.main.tgl1.focus();
		return false;	
	} else if (tgl2.length == 0) {	
		alert ('Tanggal akhir tidak boleh kosong!');	
		document.main.tgl2.focus();
		return false;	
	}
	
	var validasi = validateTgl(tgl1,bln1,thn1,tgl2,bln2,thn2);
	if (validasi)		
		document.location.href = "lappengeluaran.php?tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&idtahunbuku="+idtahunbuku+"&tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+dep+"&showpembayaran=true";
	/*parent.pilih.location.href="lappengeluaran_jenis_pilih.php?departemen="+dep+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2;
	parent.content.location.href = "lappengeluaran_jenis_blank.php";*/
}

function change_tgl1() {
	var th1 = parseInt(document.getElementById('thn2').value);
	var bln1 = parseInt(document.getElementById('bln2').value);
	var tgl1 = parseInt(document.main.tgl2.value);
	var th = parseInt(document.getElementById('thn1').value);
	var bln = parseInt(document.getElementById('bln1').value);
	var tgl = parseInt(document.main.tgl1.value);
	
	validateTgl(tgl,bln,th,tgl1,bln1,th1);
	
	var namatgl = "tgl1";
	var namabln = "bln1";	
	sendRequestText("../lib/gettanggal.php", show1, "tahun="+th+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);			
}

function change_tgl2() {
	var th1 = parseInt(document.getElementById('thn1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);
	
	var th = parseInt(document.getElementById('thn2').value);
	var bln = parseInt(document.getElementById('bln2').value);
	var tgl = parseInt(document.main.tgl2.value);
	
	validateTgl(tgl1,bln1,th1,tgl,bln,th);
	
	var namatgl = "tgl2";
	var namabln = "bln2";				
	sendRequestText("../lib/gettanggal.php", show2, "tahun="+th+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);			
}

function show1(x) {
	document.getElementById("InfoTgl1").innerHTML = x;
}

function show2(x) {
	document.getElementById("InfoTgl2").innerHTML = x;
}
function show_detail(id) {
	//parent.content.location.href = "lappengeluaran_jenis_content.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1 ?>&tanggal2=<?=$tanggal2 ?>&idpengeluaran="+id;
	sendRequestText('get_lappengeluaran.php',showLap,'departemen=<?=$departemen?>&tanggal1=<?=$tanggal1 ?>&tanggal2=<?=$tanggal2 ?>&idpengeluaran='+id);
}
function cetak(id){
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var addr = "lappengeluaran_cetak.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1 ?>&tanggal2=<?=$tanggal2 ?>&idpengeluaran="+id+"&idtahunbuku="+idtahunbuku;
	newWindow(addr, 'DaftarPengeluaran','750','850','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function cetakbukti(id) {
	newWindow('buktipengeluaran.php?idtransaksi='+id, 'BuktiPengeluaran','750','850','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetaklist(){
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var dep = document.getElementById('departemen').value;
	var tgl1 = parseInt(document.getElementById('tgl1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var thn1 = parseInt(document.getElementById('thn1').value);
	var tgl2 = parseInt(document.getElementById('tgl2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var thn2 = parseInt(document.getElementById('thn2').value);
	var tanggal1 = escape(thn1 + "-" + bln1 + "-" + tgl1);
	var tanggal2 = escape(thn2 + "-" + bln2 + "-" + tgl2);
	var addr = "lappengeluaran_list_cetak.php?tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&idtahunbuku="+idtahunbuku+"&tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+dep;
	newWindow(addr, 'DaftarPengeluaran','750','850','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function showLap(x){
	document.getElementById('lapInfo').innerHTML = x;
}
function focusNext(elemName, evt) {
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :
		((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel')
			show_laporan();
		return false;
	}
	return true;
}

function panggil(elem){
	parent.contentblank.location.href ="lappengeluaran_jenis_blank.php";
	var lain = new Array('tgl1','bln1','thn1','tgl2','bln2','thn2','departemen');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#FFFF99';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}
</script>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form method="post" name="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
<td rowspan="3" width="60%">
    <table border="0" width = "100%">
    <tr>
        <td width="15%"><span class="news_content1">Departemen</span> </font></td>
        <td colspan="4">
        <select name="departemen" class="cmbfrm" id="departemen" style="width:188px" onchange="change_dep()">
    	        <?php 	$sql = "SELECT departemen FROM departemen WHERE aktif = 1 ORDER BY urutan";
            $result = QueryDb($sql);
            while($row = mysqli_fetch_row($result)) {
                if ($departemen == "")
                    $departemen = $row[0]; ?>
    	      <option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $departemen)?> > 
    	        <?=$row[0]?>
    	        </option>
   	              <?php } ?>
  	        </select>
        &nbsp;
        <span class="news_content1">Tahun Buku </span>
        <select name="idtahunbuku" id="idtahunbuku" onchange="change_tahunbuku()" style="width:160px">        
        <?php 	if ($departemen != "") 
			{ 
				 $sql = "SELECT replid, tahunbuku, DAY(tanggalmulai), MONTH(tanggalmulai), YEAR(tanggalmulai), aktif 
							FROM jbsfina.tahunbuku WHERE departemen='$departemen' ORDER BY replid DESC";
				 $result = QueryDb($sql);
				 while ($row = mysqli_fetch_row($result))
				 {
					  if ($idtahunbuku == 0)
							$idtahunbuku = $row[0];
					  
					  $sel = "";
					  if ($idtahunbuku == $row[0])
					  {
							$sel = "selected";
							
							if ($tgl1 == 0)	$tgl1 = $row[2];
							if ($bln1 == 0) $bln1 = $row[3];
							if ($thn1 == 0) $thn1 = $row[4];
					  }
					  
					  $A = "";
					  if ($row[5] == 1)
							$A = "(A)";
					  
					  echo "<option value='".$row[0]."' $sel>$row[1] $A</option>";
				 }
			} ?>
            </select>        </td>
    </tr>
    <tr>
<?php 	if ($tgl1 == 0) $tgl1 = $tgl2;
		if ($bln1 == 0) $bln1 = $bln2;
		if ($thn1 == 0) $thn1 = $thn2;
					
		$n1 = JmlHari($bln1, $thn1);
		$n2 = JmlHari($bln2, $thn2);	?>      
        <td class="news_content1">Tanggal </td>
        <td width="10">
            <div id="InfoTgl1">    
            <select name="tgl1" class="cmbfrm" id = "tgl1" onchange="change_tgl1()">
            <option value="">[Tgl]</option>        
            <?php for($i = 1; $i <= $n1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl1) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
            </div>       	</td>
        <td width="160">
            <select name="bln1" class="cmbfrm" id="bln1" onchange="change_tgl1()">
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln1) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn1" class="cmbfrm" id="thn1" onchange="change_tgl1()">
            <?php for($i = $G_START_YEAR; $i <= $thn1+1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn1) ?> > <?=$i ?></option>
            <?php } ?>
            </select> 
            <span class="news_content1">s/d</span> </td>
        <td width="10">
         	<div id="InfoTgl2">
            <select name="tgl2" class="cmbfrm" id="tgl2" onchange="change_tgl2()">
            <option value="">[Tgl]</option>
            <?php for($i = 1; $i <= $n2; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
         </div>        </td>
        <td>
            <select name="bln2" class="cmbfrm" id="bln2" onchange="change_tgl2()">
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln2) ?> > <?=$bulan[$i]?></option>
            <?php } ?>
            </select>
            <select name="thn2" class="cmbfrm" id="thn2" onchange="change_tgl2()">
            <?php for($i = $G_START_YEAR; $i <= $thn2+1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>        </td>
    </tr>
    </table>

	</td>
 	<td rowspan="2" width="*" valign="middle">
        <a href="#" onclick="show_laporan()"><img src="../img/view.png" name="tabel" width="48" height="48" border="0" id="tabel" onmouseover="showhint('Klik untuk menampilkan data laporan pengeluaran!', this, event, '200px')"/></a>    </td>
	<td width="40%" colspan="3" align="right" valign="top">
	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font color="Gray" size="4" face="Verdana, Arial, Helvetica, sans-serif" class="news_title2">Laporan Pengeluaran</font>	</td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
</form>
<div>
<?php
if (isset($_REQUEST['showpembayaran']))
{ ?>
<table width="100%" border="0" cellspacing="8" cellpadding="0">
  <tr>
    <td width="33%" valign="top">
        <table border="0" width="100%" align="center">
        <!-- TABLE CENTER -->
        <tr>
            <td align="left">
        <?php  OpenDb();   
		
            $sql = "SELECT d.replid AS id, d.nama, SUM(p.jumlah) AS jumlah 
			  FROM jbsfina.pengeluaran p, jbsfina.datapengeluaran d, jbsfina.jurnal j
			 WHERE p.idpengeluaran = d.replid AND d.departemen = '$departemen' 
			   AND p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
			   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' GROUP BY d.replid, d.nama ORDER BY d.nama";
            
            $result = QueryDb($sql);    
            if (mysqli_num_rows($result) > 0) 
			{   ?>    
            <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
            <!-- TABLE TITLE -->
            <tr>
                <td align="right">
                <!--<a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;-->
                <a href="JavaScript:cetaklist()"><img src="../img/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;                </td>
            </tr>
            </table>
            <br />
            <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
            <tr height="30" align="center">
                <td width="10%" class="header">No</td>
                <td width="50%" class="header">Pengeluaran</td>
                <td width="*" class="header">Jumlah</td>
                <td width="*" class="header">&nbsp;</td>
            </tr>
            <?php
            
            $cnt = 0;
            $total = 0;
            while ($row = mysqli_fetch_array($result)) {
                $total += $row['jumlah'];
            ?>
            <tr height="25" onclicks="show_detail(<?=$row['id'] ?>)" styles="cursor:pointer">
                <td align="center"><?=++$cnt ?></td>
                <td align="left"><strong><?=$row['nama'] ?></strong></td>
                <td align="right"><?=FormatRupiah($row['jumlah']) ?></td>
                <td align="center"><input name="button" type="submit" class="cmbfrm2" id="button" value=">" onclick="show_detail(<?=$row['id'] ?>)" /></td>
            </tr>
            <?php
            }
            CloseDb();
            ?>
            <tr height="30">
                <td bgcolor="#999900" colspan="2" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
                <td bgcolor="#999900" align="right">
                    <font color="#FFFFFF"><strong><?=FormatRupiah($total) ?></strong></font>		</td>
                <td bgcolor="#999900" align="right">&nbsp;</td>
            </tr>
            </table>
            <script language='JavaScript'>
                Tables('table', 1, 0);
            </script>
           
        
        <?php } else { ?>	
        
            <table width="100%" border="0" align="center">          
            <tr>
                <td align="center" valign="middle" height="300">    
                    <span class="err">Tidak ditemukan adanya data.</span>
                                  </td>
            </tr>
            </table>  
        <?php } ?>
        </td></tr>
        <!-- END TABLE BACKGROUND IMAGE -->
        </table> 
    </td>
    <td valign="top"><div id="lapInfo">&nbsp;</div></td>
  </tr>
</table>
<?php
}
?>
</div>
</body>
</html>