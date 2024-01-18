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
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');

$th1 = date("Y");
if (isset($_REQUEST['th1']))
	$th1 = $_REQUEST['th1'];
$tgl1 = date("j");
if (isset($_REQUEST['tgl1']))
	$tgl1 = $_REQUEST['tgl1'];
$bln1 = date("n");
if (isset($_REQUEST['bln1']))
	$bln1 = $_REQUEST['bln1'];
$th2 = date("Y");
if (isset($_REQUEST['th2']))
	$th2 = $_REQUEST['th2'];

$bln2 = date("n");
if (isset($_REQUEST['bln2']))
	$bln2 = $_REQUEST['bln2'];
//echo 'tgl '.$tgl1.' '.$tgl2;
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$tahunajaran = "";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];

$n1 = JmlHari($bln1,$th1);
$n2 = JmlHari($bln2,$th2);

$tgl2 = $n1;
if (isset($_REQUEST['tgl2']))
	$tgl2 = $_REQUEST['tgl2'];
//$tahun2 = date("Y");
//$tahun1 = $tahun2-10;

OpenDb();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Presensi Pengajar</title>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">
var win = null;
function newWindow(mypage,myname,w,h,features) {
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
}

function tampil() {
	var departemen = document.getElementById("departemen").value;
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);
	var tahunajaran = document.getElementById('tahunajaran').value;	
	
	if (tahunajaran.length == 0){
		alert ('Tahun ajaran tidak boleh kosong !');
		return false;
	} else if (tgl1.length == 0) {	
		alert ('Tanggal awal tidak boleh kosong !');	
		document.main.tgl1.focus();
		return false;	
	} else if (tgl2.length == 0) {	
		alert ('Tanggal akhir tidak boleh kosong !');	
		document.main.tgl2.focus();
		return false;	
	}
	
	var validasi = validateTgl(tgl1,bln1,th1,tgl2,bln2,th2);
	if (validasi)
		document.location.href = "lappengajar.php?tahunajaran="+tahunajaran+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&showpresensi=true&departemen="+departemen;
		//parent.footer.location.href = "lap_pengajar_footer.php?tahunajaran="+tahunajaran+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2;
} 
function pilihguru(nip){
	var departemen = document.getElementById("departemen").value;
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);
	var tahunajaran = document.getElementById('tahunajaran').value;	
	sendRequestText("get_lappengajar.php", showPres,"tahunajaran="+tahunajaran+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&departemen="+departemen+"&nip="+nip);
}
function showPres(x) {
	document.getElementById("presInfo").innerHTML = x;
}
function change_dep() {
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);	
	var departemen = document.getElementById("departemen").value;
					
	document.location.href = "lappengajar.php?tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&departemen="+departemen;	
	//parent.footer.location.href = "blank_presensi_pengajar.php";			
}

function change_tahunajaran() {
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);	
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	
	document.location.href = "lappengajar.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2;
	//parent.footer.location.href="blank_presensi_pengajar.php";
}

function change_tgl1() {
	var th1 = parseInt(document.getElementById('th2').value);
	var bln1 = parseInt(document.getElementById('bln2').value);
	var tgl1 = parseInt(document.main.tgl2.value);
	var th = parseInt(document.getElementById('th1').value);
	var bln = parseInt(document.getElementById('bln1').value);
	var tgl = parseInt(document.main.tgl1.value);
	
	validateTgl(tgl,bln,th,tgl1,bln1,th1);
	
	var namatgl = "tgl1";
	var namabln = "bln1";	
	sendRequestText("../lib/gettanggal.php", show1, "tahun="+th+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);	
}

function change_tgl2() {
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);
	
	var th = parseInt(document.getElementById('th2').value);
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
			tampil();
		return false;
	}
	return true;
}

function panggil(elem){
	parent.footer.location.href = "blank_presensi_pengajar.php";
	var lain = new Array('departemen','tahunajaran','tgl1','bln1','th1','tgl2','bln2','th2');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}
function cetak(nip){
	var departemen = document.getElementById("departemen").value;
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);
	var tahunajaran = document.getElementById('tahunajaran').value;	
	var addr = "lappengajar_cetak.php?tahunajaran="+tahunajaran+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&departemen="+departemen+"&nip="+nip;
	newWindow(addr, 'CetakLaporanPresensiPengajar','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>
	
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form action="lap_pengajar_header.php" method="post" name="main">
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>	
	<td rowspan="3" width="65%">
	<table width = "100%" border = "0">
	<tr></tr>
    <tr>
    	<td width="20%" class="news_content1">Tahun Ajaran </td>
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
    	  <select name="tahunajaran" class="cmbfrm" id="tahunajaran" style="width:255px;" onchange="change_tahunajaran()" onkeypress="return focusNext('tgl1', event)">
            <?php
			OpenDb();
			$sql = "SELECT replid,tahunajaran,aktif FROM jbsakad.tahunajaran where departemen='$departemen' ORDER BY aktif DESC, replid DESC";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
				if ($tahunajaran == "") 
					$tahunajaran = $row['replid'];
				if ($row['aktif']) 
					$ada = '(Aktif)';
				else 
					$ada = '';			 
			?>
            <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tahunajaran)?> >
              <?=$row['tahunajaran'].' '.$ada?>
              </option>
            <?php
			}
    		?>
          </select></td>
    </tr>
    <tr>
    	<td class="news_content1">Tanggal</td>
        <td width="10"> 
		<?php 	if ($tahunajaran <> "") {
			OpenDb();
			$sql = "SELECT t.tahunajaran, YEAR(t.tglmulai) AS tahun1, YEAR(t.tglakhir) AS tahun2 FROM tahunajaran t WHERE t.replid='$tahunajaran'";
			$result = QueryDb($sql);
			CloseDb();
			$row = mysqli_fetch_row($result);
			$tahun1 = $row[1];
			$tahun2 = $row[2]; 
			}
		 ?> 
        	<div id = "InfoTgl1">
        	  <select name="tgl1" class="cmbfrm" id = "tgl1" onfocus = "panggil('tgl1')" onchange="change_tgl1()" onkeypress="focusNext('bln1',event)">
                <option value="">[Tgl]</option>
                <?php 	for($i=1;$i<=$n1;$i++){   ?>
                <option value="<?=$i?>" <?=IntIsSelected($tgl1, $i)?>>
                  <?=$i?>
                  </option>
                <?php } ?>
              </select>
        	</div>       	</td>
        <td width="160">
          	<select name="bln1" class="cmbfrm" id ="bln1" onfocus = "panggil('bln1')" onchange="change_tgl1()" onKeyPress="focusNext('th1',event)">
        <?php 	for ($i=1;$i<=12;$i++) { ?>
          	<option value="<?=$i?>" <?=IntIsSelected($bln1, $i)?>><?=$bulan[$i]?></option>	
       	<?php }	?>	
        	</select>
       		<select name="th1" class="cmbfrm" id = "th1" style="width:60px" onfocus = "panggil('th1')" onchange="change_tgl1()" onKeyPress="focusNext('tgl2',event)">
        <?php  for ($i = $tahun1; $i <= $tahun2; $i++) { ?>
		<?php  //for($i=$th1-10;$i<=$th1;$i++){ ?>
          	<option value="<?=$i?>" <?=IntIsSelected($th1, $i)?>><?=$i?></option>	   
<?php } ?>	
        	</select> <span class="news_content1">s/d     	</span></td>
        <td width="10">
    		<select name="tgl2" class="cmbfrm" id = "tgl2" onfocus = "panggil('bln2')" onchange="change_tgl2()" onKeyPress="focusNext('bln2',event)">
			<option value="">[Tgl]</option>
		<?php 	for($i=1;$i<=$n2;$i++){   ?>      
		    <option value="<?=$i?>" <?=IntIsSelected($tgl2, $i)?>><?=$i?></option>
		      <?php } ?>
			</select>      	</td>
        <td>
        	<select name="bln2" class="cmbfrm" id ="bln2" onfocus = "panggil('bln2')" onchange="change_tgl2()" onKeyPress="focusNext('th2',event)">
        <?php 	for ($i=1;$i<=12;$i++) { ?>
        	<option value="<?=$i?>" <?=IntIsSelected($bln2, $i)?>><?=$bulan[$i]?></option>	
        <?php }	?>	
        	</select>
       	 	<select name="th2" class="cmbfrm" id = "th2" style="width:60px" onfocus = "panggil('th2')" onchange="change_tgl2()" onKeyPress="focusNext('tabel',event)">
       	<?php  for ($i = $tahun1; $i <= $tahun2; $i++) { ?>
		<?php  //for($i=$th2-10;$i<=$th2;$i++){ ?>
        	<option value="<?=$i?>" <?=IntIsSelected($th2, $i)?>><?=$i?></option>	   
<?php } ?>	
        	</select>        </td>        
    </tr>
	</table>
    </td>
    <td width="*" align="left" valign="middle"><a href="#" onclick="tampil()" ><img src="../img/view.png" onmouseover="showhint('Klik untuk menampilkan laporan presensi pengajar!', this, event, '180px')" height="48" width="48" border="0" name="tabel" id="tabel2"/></a></td>
    <td width="45%" align="right" valign="top">
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<span class="news_title2">Laporan Presensi Pengajar</span>
    </tr>
	</table>
    </td>
</tr>
</table>
</form>
<div>
<?php if (isset($_REQUEST['showpresensi'])){ ?>
<table width="100%" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td valign="top" width="300">
		<br />
		<?php
		OpenDb();
        $sql = "SELECT DISTINCT nip FROM guru g, pelajaran p WHERE g.idpelajaran=p.replid AND p.departemen='$departemen' AND g.aktif=1";
        $result = QueryDb($sql);
        $num = @mysqli_num_rows($result);
		?>
        <table width="100%" border="1" class="tab">
          <tr class="header">
            <td height="25" align="center">NIP</td>
            <td height="25" align="center">Nama</td>
            <td height="25">&nbsp;</td>
          </tr>
          <?php if ($num>0){ ?>
          <?php while ($row = @mysqli_fetch_array($result)){ ?>
          <?php
		  $s = "SELECT nama FROM $db_name_sdm.pegawai WHERE nip='".$row['nip']."'";
		  $re = QueryDb($s);
		  $r = @mysqli_fetch_array($re);
		  ?>
          <tr>
            <td>&nbsp;<?=$row['nip']?></td>
            <td>&nbsp;<?=$r['nama']?></td>
            <td align="center"><input name="" type="button" onclick="pilihguru('<?=$row['nip']?>')" value=">" class="cmbfrm2" /></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td colspan="3" align="center" class="nodata">Tidak ada data</td>
          </tr>
          <?php } ?>
        </table>
    </td>
    <td valign="top"><div id="presInfo">&nbsp;</div></td>
  </tr>
</table>
<?php } ?>
</div>
</body>
</html>