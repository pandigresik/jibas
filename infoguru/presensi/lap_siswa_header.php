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
require_once('../library/departemen.php');
require_once('../sessionchecker.php');

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
$tgl2 = date("j");
if (isset($_REQUEST['tgl2']))
	$tgl2 = $_REQUEST['tgl2'];
$bln2 = date("n");
if (isset($_REQUEST['bln2']))
	$bln2 = $_REQUEST['bln2'];

$n1 = JmlHari($bln1,$th1);
$n2 = JmlHari($bln2,$th2);

$tgl2 =$n1;
if (isset($_REQUEST['tgl2']))
	$tgl2 = $_REQUEST['tgl2'];

OpenDb();
//$tahun2 = date("Y");
//$tahun1 = $tahun2-10;

if (isset($_REQUEST['nis']))  { 
	$sql = "SELECT t.tahunajaran, YEAR(t.tglmulai) AS tahun1, YEAR(t.tglakhir) AS tahun2 FROM tahunajaran t, kelas k, siswa s WHERE k.idtahunajaran = t.replid AND k.replid = s.idkelas AND s.nis='".$_REQUEST['nis']."'"; 
	$result = QueryDb($sql);
	
	$row = mysqli_fetch_row($result);
	$tahun1 = $row[1];
	$tahun2 = $row[2];
} 

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Presensi Siswa</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tools.js"></script>
<script language="JavaScript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function tampil() {
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);
	var nis = document.getElementById('nis1').value;
	var nama = document.getElementById('nama1').value;
	
	if (nis.length == 0){
		alert ('Nis dan atau Nama tidak boleh kosong !');
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
		parent.footer.location.href = "lap_siswa_footer.php?nis="+nis+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2;
} 

/*function panggil() {
	parent.footer.location.href = "blank_presensi_siswa.php";
}*/

function carisiswa() {
	parent.footer.location.href = "blank_presensi_siswa.php";
	newWindow('../library/siswa.php?flag=0', 'CariSiswa','600','600','resizable=1,scrollbars=1,status=0,toolbar=0');
	
}

function acceptSiswa(nis, nama) {
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = document.main.tgl2.value;
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = document.main.tgl1.value;
		
	
	parent.header.location.href = "../presensi/lap_siswa_header.php?nis="+nis+"&nama="+nama+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2;
	
	/*document.getElementById('nis').value = nis;
	document.getElementById('nis1').value = nis;
	document.getElementById('nama').value = nama;
	document.getElementById('nama1').value = nama;
	
	parent.header.location.href = "../presensi/lap_hariansiswa_header.php?nis="+nis+"&nama="+nama+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2;*/
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
	sendRequestText("../library/gettanggal.php", show1, "tahun="+th+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);	
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
	sendRequestText("../library/gettanggal.php", show2, "tahun="+th+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);	
}

function show1(x) {
	document.getElementById("InfoTgl1").innerHTML = x;
}

function show2(x) {
	document.getElementById("InfoTgl2").innerHTML = x;
}

function validate() {
	var nis = document.getElementById('nis').focus();
	var tgl1 = document.main.tgl1.focus();
	var tgl2 = document.main.tgl2.focus();
	
	if (nis.length == 0) {	
		alert ('NIS siswa tidak boleh kosong !');	
		document.main.tgl1.focus();
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
	return true;	
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
	parent.footer.location.href = "blank_presensi_siswa.php";
	var lain = new Array('tgl1','bln1','th1','tgl2','bln2','th2');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}
</script>
</head>
	
<body topmargin="0" leftmargin="0" onload="document.getElementById('tgl1').focus()">
<form action="lap_siswa_header.php" method="post" name="main">
<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top" width="55%">

	<table border="0" width="100%">
    <!-- TABLE TITLE -->
    <tr>
    	<td width="52"><strong>Siswa</strong></td>
        <td><input type="text" name="nis" id="nis" size="12" readonly class="disabled" value="<?=$_REQUEST['nis']?>" onclick="carisiswa()"/>
        	<input type="hidden" name="nis1" id="nis1" value="<?=$_REQUEST['nis']?>" >
        	<input type="text" name="nama" id="nama" size="45" readonly class="disabled" value="<?=$_REQUEST['nama']?>"  onclick="carisiswa()"/>
            <input type="hidden" name="nama1" id="nama1" value="<?=$_REQUEST['nama']?>">         
        	</strong>        	
           	<a href="JavaScript:carisiswa()"><img src="../images/ico/cari.png" border="0" /></a></td>
    </tr>	
    <tr>
    	<td rowspan="2"><strong>Tanggal</strong></td>
        <td>
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div id="InfoTgl1">
                  <select name="tgl1" id = "tgl1" onchange="change_tgl1()" onfocus = "panggil('tgl1')" onkeypress="focusNext('bln1',event)">
                    <option value="">[Tgl]</option>
                    <?php 	for($i=1;$i<=$n1;$i++){   ?>
                    <option value="<?=$i?>" <?=IntIsSelected($tgl1, $i)?>>
                      <?=$i?>
                      </option>
                    <?php } ?>
                  </select>
                </div></td>
                <td><select name="bln1" id ="bln1" onchange="change_tgl1()" onfocus = "panggil('bln1')" onkeypress="focusNext('th1',event)">
                  <?php 	for ($i=1;$i<=12;$i++) { ?>
                  <option value="<?=$i?>" <?=IntIsSelected($bln1, $i)?>>
                    <?=$bulan[$i]?>
                    </option>
                  <?php }	?>
                </select></td>
                <td><select name="th1" id = "th1" onchange="change_tgl1()" onfocus = "panggil('th1')" onkeypress="focusNext('tgl2',event)" style="width:60px">
                  <?php  for ($i = $tahun1; $i <= $tahun2; $i++) { ?>
                  <?php  //for($i=$th1-10;$i<=$th1;$i++){ ?>
                  <option value="<?=$i?>" <?=IntIsSelected($th1, $i)?>>
                    <?=$i?>
                    </option>
                  <?php } ?>
                </select></td>
                <td width="20"> s/d </td>
                <td><div id="InfoTgl2">
                  <select name="tgl2" id = "tgl2" onchange="change_tgl2()" onfocus = "panggil('tgl2')" onkeypress="focusNext('bln2',event)">
                    <option value="">[Tgl]</option>
                    <?php 	for($i=1;$i<=$n2;$i++){   ?>
                    <option value="<?=$i?>" <?=IntIsSelected($tgl2, $i)?>>
                      <?=$i?>
                      </option>
                    <?php } ?>
                  </select>
                </div></td>
                <td><select name="bln2" id ="bln2" onchange="change_tgl2()" onfocus = "panggil('bln2')" onkeypress="focusNext('th2',event)" >
                  <?php 	for ($i=1;$i<=12;$i++) { ?>
                  <option value="<?=$i?>" <?=IntIsSelected($bln2, $i)?>>
                    <?=$bulan[$i]?>
                    </option>
                  <?php }	?>
                </select></td>
                <td><select name="th2" id = "th2" onchange="change_tgl2()" onfocus = "panggil('th2')" onkeypress="focusNext('tabel',event)" style="width:60px">
                  <?php  for ($i = $tahun1; $i <= $tahun2; $i++) { ?>
                  <?php  //for($i=$th2-10;$i<=$th2;$i++){ ?>
                  <option value="<?=$i?>" <?=IntIsSelected($th2, $i)?>>
                    <?=$i?>
                    </option>
                  <?php } ?>
                </select></td>
              </tr>
            </table>        </td>
        </tr>
    <tr>
      <td>        </td>
      </tr>
	</table>
    </td>
    <td rowspan="2" align="left" valign="middle" width="*"><a href="#" onclick="tampil()"><img src="../images/ico/view.png" onmouseover="showhint('Klik untuk menampilkan laporan presensi siswa!', this, event, '150px')" height="48" border="0" name="tabel" id="tabel2"/></a></td>
    <td width="40%" rowspan="2" align="right" valign="top">
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Presensi Siswa</font><br />
<a href="../presensi.php?page=pp" target="framecenter">
  <font size="1" color="#000000"><b>Presensi</b></font></a>&nbsp>&nbsp
    <font size="1" color="#000000"><b>Laporan Presensi Siswa</b></font></td>
    </td>
</tr>

</table>
</form>
</body>
</html>