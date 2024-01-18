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
//require_once('include/errorhandler.php');
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');


$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];
	
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
OpenDb();
$sql = "SELECT day(now()), month(now()), year(now()), 
               day(date_sub(now(), INTERVAL 30 DAY)), month(date_sub(now(), INTERVAL 30 DAY)), year(date_sub(now(), INTERVAL 30 DAY))";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$tgl2 = $row[0];
$bln2 = $row[1];
$thn2 = $row[2];

$tgl1 = 0;
if (isset($_REQUEST['tgl1']))
	$tgl1 = (int)$_REQUEST['tgl1'];

$bln1 = 0;
if (isset($_REQUEST['bln1']))
	$bln1 = (int)$_REQUEST['bln1'];

$thn1 = 0;
if (isset($_REQUEST['thn1']))
	$thn1 = (int)$_REQUEST['thn1'];
	
if (isset($_REQUEST['tgl2']))
	$tgl2 = (int)$_REQUEST['tgl2'];

if (isset($_REQUEST['bln2']))
	$bln2 = (int)$_REQUEST['bln2'];

if (isset($_REQUEST['thn2']))
	$thn2 = (int)$_REQUEST['thn2'];	

$n1 = JmlHari($bln1,$thn1);
$n2 = JmlHari($bln2,$thn2);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="../script/tools.js" language="javascript" ></script>
<script src="../script/ajax.js" language="javascript" ></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">
function change_sel() 
{
	//parent.content.location.href = "laptransaksi_blank.php";
}

function change_dep() 
{
	var departemen = document.getElementById('departemen').value;
	var tgl1 = document.getElementById('tgl1').value;
	var bln1 = document.getElementById('bln1').value;
	var thn1 = document.getElementById('thn1').value;
	var tgl2 = document.getElementById('tgl2').value;
	var bln2 = document.getElementById('bln2').value;
	var thn2 = document.getElementById('thn2').value;	
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	
	document.location.href = "laptransaksi.php?idtahunbuku="+idtahunbuku+"&tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+departemen+"&idtahunbuku="+idtahunbuku;
}

function show_laporan() 
{
	var dep = document.getElementById('departemen').value;
	var tgl1 = parseInt(document.getElementById('tgl1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var thn1 = parseInt(document.getElementById('thn1').value);
	var tgl2 = parseInt(document.getElementById('tgl2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var thn2 = parseInt(document.getElementById('thn2').value);
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var tanggal1 = escape(thn1 + "-" + bln1 + "-" + tgl1);
	var tanggal2 = escape(thn2 + "-" + bln2 + "-" + tgl2);
	
	if (idtahunbuku.length == 0) 
	{
		alert ('Tahun Buku tidak boleh kosong!');
		document.getElementById('departemen').focus();
		return false;
	} 
	else if (tgl1.length == 0) 
	{	
		alert ('Tanggal awal tidak boleh kosong!');	
		document.main.tgl1.focus();
		return false;	
	} 
	else if (tgl2.length == 0) 
	{	
		alert ('Tanggal akhir tidak boleh kosong!');	
		document.main.tgl2.focus();
		return false;	
	}
	
	var validasi = validateTgl(tgl1,bln1,thn1,tgl2,bln2,thn2);
	if (validasi)	
		document.location.href="laptransaksi.php?tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+dep+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&idtahunbuku="+idtahunbuku+"&showpembayaran=true";
}

function cetak()
{
	var dep = document.getElementById('departemen').value;
	var tgl1 = parseInt(document.getElementById('tgl1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var thn1 = parseInt(document.getElementById('thn1').value);
	var tgl2 = parseInt(document.getElementById('tgl2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var thn2 = parseInt(document.getElementById('thn2').value);
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var tanggal1 = escape(thn1 + "-" + bln1 + "-" + tgl1);
	var tanggal2 = escape(thn2 + "-" + bln2 + "-" + tgl2);
	var addr = "laptransaksi_cetak.php?departemen="+dep+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&idtahunbuku="+idtahunbuku;
	newWindow(addr, 'LapTransaksiKeuangan','750','850','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_page(page) 
{
	var dep = document.getElementById('departemen').value;
	var tgl1 = parseInt(document.getElementById('tgl1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var thn1 = parseInt(document.getElementById('thn1').value);
	var tgl2 = parseInt(document.getElementById('tgl2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var thn2 = parseInt(document.getElementById('thn2').value);
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var tanggal1 = escape(thn1 + "-" + bln1 + "-" + tgl1);
	var tanggal2 = escape(thn2 + "-" + bln2 + "-" + tgl2);
	if (page=="XX")
		page = document.getElementById('page').value;
	
	document.location.href="laptransaksi.php?tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+dep+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&idtahunbuku="+idtahunbuku+"&showpembayaran=true&page="+page;
}

function change_tgl1()
{
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

function change_tgl2() 
{
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

function show1(x) 
{
	document.getElementById("InfoTgl1").innerHTML = x;
}

function show2(x) 
{
	document.getElementById("InfoTgl2").innerHTML = x;
}

function focusNext(elemName, evt) 
{
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :
		((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) {
		if (elemName == 'tabel') 
			show_laporan();
		else 
			document.getElementById(elemName).focus();
		return false;
	}
	return true;
}

function panggil(elem)
{
	parent.content.location.href = "laptransaksi_blank.php";
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
<style type="text/css">
<!--
.style1 {font-size: 4px}
-->
</style>
</head>
<body topmargin="0" leftmargin="0" onLoad="document.getElementById('departemen').focus();">
<form name="main">
<table border="0" cellpadding="2" cellspacing="0" width="100%" align="center">
<tr>
	<td rowspan="3" width="60%">
    <table width = "100%" border="0">
    <tr>
        <td width="15%" class="news_content1">Departemen </font></td>
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
        <span class="news_content1">Tahun Buku </span>
        <select name="idtahunbuku" id="idtahunbuku" onchange="change_dep()" style="width:160px">        
<?php 			if ($departemen != "") 
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
        </select>	
        </td>
	</tr>
	<tr>
<?php 	if ($tgl1 == 0)	$tgl1 = $tgl2;
			if ($bln1 == 0) $bln1 = $bln2;
			if ($thn1 == 0) $thn1 = $thn2;
					
			$n1 = JmlHari($bln1, $thn1);
			$n2 = JmlHari($bln2, $thn2);	?>       
        <td class="news_content1">Tanggal </td>
        <td width="10">
        	<div id="InfoTgl1"> 
               <select name="tgl1" class="cmbfrm" id="tgl1" onchange="change_tgl1()">  
           <option value="">[Tgl]</option>  
           <?php for($i = 1; $i <= $n1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($tgl1,$i) ?> > <?=$i ?></option>
            <?php } ?>
            </select>               
            </div>        </td>
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
            <span class="news_content1">            s/d        </span></td>
       	<td width="10">
         	<div id="InfoTgl2">
            <select name="tgl2" class="cmbfrm" id="tgl2" onchange="change_tgl2()">
            <option value="">[Tgl]</option>  
            <?php for($i = 1; $i <= $n2; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($tgl2,$i) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
            </div>       	</td>
        <td>
            <select name="bln2" class="cmbfrm" id="bln2" onchange="change_tgl2()">
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln2) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn2" class="cmbfrm" id="thn2" style="width:60px" onchange="change_tgl2()">
              <?php for($i = $G_START_YEAR; $i <= $thn2+1; $i++) { ?>
              <option value="<?=$i ?>" <?=IntIsSelected($i, $thn2) ?> >
              <?=$i ?>
              </option>
              <?php } ?>
            </select></td>
    </tr>
    </table>
	</td>
	<td rowspan="3" width="*" valign="middle">
	    <a href="#" onclick="show_laporan()"><img src="../img/view.png" border="0" height="48" width="48" id="tabel" onmouseover="showhint('Klik untuk menampilkan data laporan transaksi keuangan!', this, event, '180px')" /></a>    </td>
	<td width="40%" align="right" valign="top">
		<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<span class="news_title2">Laporan Transaksi Keuangan</span>	</td>
</tr>
</table>
</form>
<div>
<?php
if (isset($_REQUEST['showpembayaran']))
{
	$varbaris = 10;
	if (isset($_REQUEST['varbaris']))
		$varbaris = $_REQUEST['varbaris'];
	
	$page=0;
	if (isset($_REQUEST['page']))
		$page = $_REQUEST['page'];
	
	$urut = "tanggal ASC, nokas";	
	if (isset($_REQUEST['urut']))
		$urut = $_REQUEST['urut'];	
	
	$urutan = "ASC";	
	if (isset($_REQUEST['urutan']))
		$urutan = $_REQUEST['urutan'];
	
	$tanggal1 = "";
	if (isset($_REQUEST['tanggal1']))
		$tanggal1 = $_REQUEST['tanggal1'];
		
	$tanggal2 = "";
	if (isset($_REQUEST['tanggal2']))
		$tanggal2 = $_REQUEST['tanggal2'];
	
	$idtahunbuku = 0;
	if (isset($_REQUEST['idtahunbuku']))
		$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
	?>
    <table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <!-- TABLE CENTER -->
    <tr>
        <td>
    <?php     
        OpenDb();
        $sql_tot = "SELECT COUNT(nokas), SUM(debet) AS totdebet, SUM(kredit) AS totkredit FROM $db_name_fina.transaksilog WHERE departemen='$departemen' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND idtahunbuku = '".$idtahunbuku."'";
    
        $result_tot = QueryDb($sql_tot);
        $row_tot = mysqli_fetch_row($result_tot);
        $jumlah = $row_tot[0];
        $total=ceil($jumlah/(int)$varbaris);
        $akhir = ceil($jumlah/5)*5;
        
        $totaldebet = $row_tot[1];
        $totalkredit = $row_tot[2];
        
        
        $sql = "SELECT nokas, date_format(tanggal, '%d-%b-%Y') AS tanggal, petugas, transaksi, keterangan, debet, kredit FROM $db_name_fina.transaksilog WHERE departemen='$departemen' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND idtahunbuku = '$idtahunbuku' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
            
        $result = QueryDb($sql);	
        if (mysqli_num_rows($result) > 0) {
    ?>    
        <input type="hidden" name="total" id="total" value="<?=$total?>"/>
        <table border="0" width="100%" align="center">
        <tr>
            <td align="right">
            <!--<a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;-->
            <a href="JavaScript:cetak()"><img src="../img/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;            </td>
        </tr>
        </table>
        <br />
        <table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="100%" align="left" bordercolor="#000000">
        <tr height="30" align="center">
            <td width="4%" class="header" >No</td>
            <td width="18%" class="header">No. Jurnal/Tanggal</td>
            <td width="10%" class="header">Petugas</td>
            <td width="*" class="header" >Transaksi</td>
            <td width="15%" class="header">Debet</td>
            <td width="15%" class="header">Kredit</td>
        </tr>
    <?php 	if ($page==0)
                $cnt = 0;
            else 
                $cnt = (int)$page*(int)$varbaris;
            
            while($row = mysqli_fetch_array($result)) {
                
    ?>
        <tr height="25">
            <td align="center" valign="top"><?=++$cnt ?></td>
            <td align="center" valign="top"><strong><?=$row['nokas'] ?></strong><br /><?=$row['tanggal'] ?></td>
            <td valign="top" align="center"><?=$row['petugas'] ?></td>
            <td align="left" valign="top"><?=$row['transaksi'] ?>
            <?php if ($row['keterangan'] <> "") { ?>
            <br /><strong>Keterangan: </strong><?=$row['keterangan'] ?>
            <?php } ?>
            </td>
            <td align="right" valign="top"><?=FormatRupiah($row['debet']) ?></td>
            <td align="right" valign="top"><?=FormatRupiah($row['kredit']) ?></td>
        </tr>
    <?php
            }
            CloseDb();
        //echo 'total '.$total.' dan '.$page;	
        if ($total-1 == $page) {
            
            
    ?>
       
        <tr height="30">
            <td colspan="4" align="center" bgcolor="#999900">
            <font color="#FFFFFF"><strong>T O T A L</strong></font>
            </td>
            <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totaldebet) ?></strong></font></td>
            <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalkredit) ?></strong></font></td>
        </tr>
    <?php 	} ?>
        </table>
        <script language='JavaScript'>
            Tables('table', 1, 0);
        </script>
         <?php CloseDb() ?>
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
        <table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
        <tr>
            <td width="30%" align="left" class="news_content1">Halaman
            <select name="page" class="cmbfrm" id="page" onChange="change_page('XX')">
            <?php for ($m=0; $m<$total; $m++) {?>
                 <option value="<?=$m ?>" <?=IntIsSelected($page,$m) ?>><?=$m+1 ?></option>
            <?php } ?>
            </select>
            dari <?=$total?> halaman
            
            <?php 
         // Navigasi halaman berikutnya dan sebelumnya
            ?>            </td>
            <td align="center">
        <!--input <?=$disback?> type="button" class="cmbfrm2" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
            <?php
            for($a=0;$a<$total;$a++){
                if ($page==$a){
                    echo "<font face='verdana' color='red' size='4'><strong>".($a+1)."</strong></font> "; 
                } else { 
                    echo "<a href='#' onClick=\"change_page('".$a."')\"><font face='verdana' color='green'>".($a+1)."</font></a> "; 
                }
                     
            }
            ?>
             <input <?=$disnext?> type="button" class="cmbfrm2" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')"-->
            </td>
            <td width="30%" align="right"><!--Jumlah baris per halaman
            <select name="varbaris" id="varbaris" onChange="change_baris()">
            <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
                <option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
            <?php 	} ?>
           
            </select>--></td>
        </tr>
        </table>
<?php } else { ?>
        <table width="100%" border="0" align="center">          
        <tr>
            <td align="center" valign="middle" height="300">
                <font size = "2" color ="red"><b>Tidak ditemukan adanya data.<br />Tambah data transaksi keuangan pada departemen <?=$departemen?> antara tanggal <?=LongDateFormat($tanggal1)?> s/d <?=LongDateFormat($tanggal2)?><br />.</font>
                
            </td>
        </tr>
        </table>  
    <?php } ?>
        </td>
    </tr>
    </table>
    <?php 
	

}
?>
</div>
</body>
</html>