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

$idkategori = "";
if (isset($_REQUEST['idkategori']))
	$idkategori = $_REQUEST['idkategori'];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

OpenDb();
$sql = "SELECT day(now()), month(now()), year(now()), day(date_sub(now(), INTERVAL 30 DAY)), month(date_sub(now(), INTERVAL 30 DAY)), year(date_sub(now(), INTERVAL 30 DAY))";		
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$tgl2 = $row[0];
$bln2 = $row[1];
$thn2 = $row[2];
$tgl1 = $row[3];
$bln1 = $row[4];
$thn1 = $row[5];

if (isset($_REQUEST['tgl1']))
	$tgl1 = (int)$_REQUEST['tgl1'];

if (isset($_REQUEST['bln1']))
	$bln1 = (int)$_REQUEST['bln1'];

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
<script src="../script/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">
function change_dep() {
	var departemen = document.getElementById('departemen').value;
	var tgl1 = document.getElementById('tgl1').value;
	var bln1 = document.getElementById('bln1').value;
	var thn1 = document.getElementById('thn1').value;
	var tgl2 = document.getElementById('tgl2').value;
	var bln2 = document.getElementById('bln2').value;
	var thn2 = document.getElementById('thn2').value;
	
	document.location.href = "lapbayarcalon_all.php?tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+departemen;
	//parent.contentblank.location.href = "lapbayarcalon_all_blank.php";
}

function change_sel() {
	//parent.contentblank.location.href = "lapbayarcalon_all_blank.php";
}

function show_pembayaran() {
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var tahunbuku = document.getElementById('tahunbuku').value;
	var departemen = document.getElementById('departemen').value;
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
	}  else if (tgl1.length == 0) {	
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
		document.location.href = "lapbayarcalon_all.php?tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&idtahunbuku="+idtahunbuku+"&tahunbuku="+tahunbuku+"&departemen="+departemen+"&showpembayaran=true";

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

function focusNext(elemName, evt) {
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :
		((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel')
			show_pembayaran();
		return false;
	}
	return true;
}

function panggil(elem){
	parent.contentblank.location.href ="lapbayarcalon_all_blank.php";
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
</head>

<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form method="post" name="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
	<td rowspan="3" width="58%">
    <table border="0" width = "100%">
    <tr>
    	<td width="15%" class="news_content1">Departemen </td>
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
        <?php
		if ($departemen != "") {
			$sql = "SELECT replid AS id, tahunbuku FROM $db_name_fina.tahunbuku WHERE aktif = 1 AND departemen = '".$departemen."'";
			//echo $sql;
			$result = QueryDb($sql);
			$row = mysqli_fetch_array($result);
			$idtahunbuku = $row['id'];
			if (isset($_REQUEST['idtahunbuku']))
				$idtahunbuku = $_REQUEST['idtahunbuku'];
			$tahunbuku = $row['tahunbuku'];
			if (isset($_REQUEST['tahunbuku']))
				$tahunbuku = $_REQUEST['tahunbuku'];			
		}
		?>
        <input type="text" name="tahunbuku" id="tahunbuku" size="27" readonly style="background-color:#CCCC99" value="<?=$tahunbuku ?>">
        <input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku ?>" />        </td>
 	</tr>
    <tr>
    	<td class="news_content1">Tanggal </td>
        <td width="10">
        	<div id="InfoTgl1">           
            <select name="tgl1" class="cmbfrm" id = "tgl1" onfocus = "panggil('tgl1')" onchange="change_tgl1()" onKeyPress="return focusNext('bln1',event)">
            	<option value="">[Tgl]</option>
            <?php for($i = 1; $i <= $n1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl1) ?> > <?=$i ?></option>
            <?php } ?>
            </select>   
            </div>       	</td>
        <td width="160">
            <select name="bln1" class="cmbfrm" id="bln1" onfocus = "panggil('bln1')" onchange="change_tgl1()" onKeyPress="return focusNext('thn1',event)">
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln1) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn1" class="cmbfrm" id="thn1" onfocus = "panggil('thn1')" onchange="change_tgl1()" onKeyPress="return focusNext('tgl2',event)">
            <?php for($i = $G_START_YEAR; $i <= $thn1+1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn1) ?> > <?=$i ?></option>
            <?php } ?>
            </select> 
            <span class="news_content1">s/d   		</span></td>
        <td width="10">	
        	<div id="InfoTgl2">
            <select name="tgl2" class="cmbfrm" id="tgl2" onfocus = "panggil('tgl2')" onchange="change_tgl2()" onKeyPress="return focusNext('bln2',event)" >
            	<option value="">[Tgl]</option>
            <?php for($i = 1; $i <= $n2; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
            </div>       	</td>
        <td>
            <select name="bln2" class="cmbfrm" id="bln2" onfocus = "panggil('bln2')" onchange="change_tgl2()" onKeyPress="return focusNext('thn2',event)">
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln2) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn2" class="cmbfrm" id="thn2" onfocus = "panggil('thn2')" onchange="change_tgl2()" onkeypress="return focusNext('tabel',event)">
              <?php for($i = $G_START_YEAR; $i <= $thn2+1; $i++) { ?>
              <option value="<?=$i ?>" <?=IntIsSelected($i, $thn2) ?> >
              <?=$i ?>
                </option>
              <?php } ?>
            </select></td>
   	</tr>
    </table>
    </td>
	<td width="*" rowspan="2" valign="middle">
		<a href="#" onclick="show_pembayaran()"><img src="../img/view.png" border="0" height="48" width="48" id="tabel" onmouseover="showhint('Klik untuk menampilkan data laporan pembayaran per calon siswa!', this, event, '200px')"/></a>    </td>
	<td width="40%" align="right" valign="top">
	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font color="Gray" size="4" face="Verdana, Arial, Helvetica, sans-serif" class="news_title2">Laporan Pembayaran Per Calon Siswa</font>	</td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
</form>
<div id="contentarea">
<?php
if (isset($_REQUEST['showpembayaran'])){
?>
<table width="100%" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td valign="top" width="33%">
        <div id="TabbedPanels1" class="TabbedPanels">
          <ul class="TabbedPanelsTabGroup">
            <li class="TabbedPanelsTab" tabindex="0">Pilih Calon Siswa</li>
            <li class="TabbedPanelsTab" tabindex="0">Cari Calon Siswa</li>
          </ul>
          <div class="TabbedPanelsContentGroup">
            <div class="TabbedPanelsContent" id="pilihcalon"></div>
            <div class="TabbedPanelsContent" id="caricalon"></div>
          </div>
        </div>
        <script type="text/javascript">
        <!--
        var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
        //function getTabContent(){
            //show_wait('pilihsiswa');
        sendRequestText('pilihcalon.php',showTabPilih,'departemen=<?=$departemen?>');
            //show_wait('carisiswa');
        sendRequestText('caricalon.php',showTabCari,'departemen=<?=$departemen?>');
        //}
        function showTabPilih(x){
            document.getElementById('pilihcalon').innerHTML = x;
        }
        function showTabCari(x){
            document.getElementById('caricalon').innerHTML = x;
        }
        function chg_klp(){
            var klp = document.getElementById('kelompok').value;
            if (klp!="")
                sendRequestText('get_cal.php',showCal,'kelompok='+klp);
            else	
                sendRequestText('get_blank.php',showCal,'');
        }
        function showCal(x){
            document.getElementById('calInfo').innerHTML = x;
            sendRequestText('get_blank.php',showLap,'');
        }
        function pilihcalon(nopendaftaran){
            var tgl1 = parseInt(document.getElementById('tgl1').value);
            var bln1 = parseInt(document.getElementById('bln1').value);
            var thn1 = parseInt(document.getElementById('thn1').value);
            var tgl2 = parseInt(document.getElementById('tgl2').value);
            var bln2 = parseInt(document.getElementById('bln2').value);
            var thn2 = parseInt(document.getElementById('thn2').value);
            var tanggal1 = escape(thn1 + "-" + bln1 + "-" + tgl1);
            var tanggal2 = escape(thn2 + "-" + bln2 + "-" + tgl2);
            var idtahunbuku = document.getElementById('idtahunbuku').value;
            //alert (nopendaftaran+'_'+tanggal1+'_'+tanggal2+'_'+idtahunbuku);
            sendRequestText('get_lapbayarcalon_all.php',showLap,'nopendaftaran='+nopendaftaran+'&tanggal1='+tanggal1+'&tanggal2='+tanggal2+'&idtahunbuku='+idtahunbuku);
        }
		function cetak(replid){
            var tgl1 = parseInt(document.getElementById('tgl1').value);
            var bln1 = parseInt(document.getElementById('bln1').value);
            var thn1 = parseInt(document.getElementById('thn1').value);
            var tgl2 = parseInt(document.getElementById('tgl2').value);
            var bln2 = parseInt(document.getElementById('bln2').value);
            var thn2 = parseInt(document.getElementById('thn2').value);
            var tanggal1 = escape(thn1 + "-" + bln1 + "-" + tgl1);
            var tanggal2 = escape(thn2 + "-" + bln2 + "-" + tgl2);
            var idtahunbuku = document.getElementById('idtahunbuku').value;
            //alert (nopendaftaran+'_'+tanggal1+'_'+tanggal2+'_'+idtahunbuku);
            var addr = 'lapbayarcalon_all_cetak.php?replid='+replid+'&tanggal1='+tanggal1+'&tanggal2='+tanggal2+'&idtahunbuku='+idtahunbuku;
			newWindow(addr, 'CetakNeraca','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
        }
        function showLap(x){
            document.getElementById('LapInfo').innerHTML = x;
        }
        function CariCalon(){
            var nopendaftaran = document.getElementById('nopendaftaran').value;
            var nama = document.getElementById('nama').value;
            if (nopendaftaran.length<3){
                if (nama.length<3){
                    alert ('No. Pendaftaran atau Nama harus diisi dan tidak boleh kurang dari 3 karakter!');
                    document.frmCari.nopendaftaran.focus();
                } else {
                    //show_wait('sisInfoCari');
                    sendRequestText('getcalcari.php',showcalInfoCari,'nama='+nama);
                }
            } else {
                if (nama.length<3){
                    //show_wait('sisInfoCari');
                    sendRequestText('getcalcari.php',showcalInfoCari,'nopendaftaran='+nopendaftaran);
                } else {
                    //show_wait('sisInfoCari');
                    sendRequestText('getcalcari.php',showcalInfoCari,'nopendaftaran='+nopendaftaran+'&nama='+nama);
                }
            }
        }
        function showcalInfoCari(x){
            document.getElementById('calInfoCari').innerHTML = x;
        }
        //-->
        </script>
    </td>
    <td valign="top"><div id="LapInfo">&nbsp;</div></td>
  </tr>
</table>
<?php
}
?>
</div>
</body>
</html>