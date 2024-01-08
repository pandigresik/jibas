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
/*$tgl2 = date("j");
if (isset($_REQUEST['tgl2']))
	$tgl2 = $_REQUEST['tgl2'];*/
$bln2 = date("n");
if (isset($_REQUEST['bln2']))
	$bln2 = $_REQUEST['bln2'];
//echo 'tgl '.$tgl1.' '.$tgl2;

if ($bln1 == 4 || $bln1 == 6|| $bln1 == 9 || $bln1 == 11 || $bln2 == 4 || $bln2 == 6|| $bln2 == 9 || $bln2 == 11 ) 
	$n = 30;
else if (($bln1 == 2 && $th1 % 4 <> 0) || ($bln2 == 2 && $th2 % 4 <> 0))
	$n = 28;
else if (($bln1 == 2 && $th1 % 4 == 0) || ($bln2 == 2 && $th2 % 4 == 0)) 
	$n = 29;
else 
	$n = 31;	

$tgl2 =$n;
if (isset($_REQUEST['tgl2']))
	$tgl2 = $_REQUEST['tgl2'];

OpenDb();
$tahun2 = date("Y");
$tahun1 = $tahun2-10;

if (isset($_REQUEST['nis']))  { 
	$sql = "SELECT t.tahunajaran, YEAR(t.tglmulai) AS tahun1, YEAR(t.tglakhir) AS tahun2 FROM tahunajaran t, kelas k, siswa s WHERE k.idtahunajaran = t.replid AND k.replid = s.idkelas AND s.nis='".$_REQUEST['nis']."'"; 
	$result = QueryDb($sql);
	
	$row = mysqli_fetch_row($result);
	$tahun1 = $row[1];
	$tahun2 = $row[2];
} 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Presensi Harian Siswa</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function tampil() {
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = document.main.tgl2.value;
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = document.main.tgl1.value;
	var nis = document.getElementById('nis1').value;
	var nama = document.getElementById('nama1').value;
	
	if (nis.length == 0){
		alert ('NIS siswa tidak boleh kosong !');
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
	
	parent.footer.location.href = "lap_hariansiswa_footer.php?nis="+nis+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2;
} 

function panggil() {
	parent.footer.location.href = "blank_presensi_siswa.php?tipe='harian'";
}

function carisiswa() {
	panggil();	
	newWindow('../library/siswa.php?flag=0', 'CariSiswa','600','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptSiswa(nis, nama) {
	//document.getElementById('nis').value = nis;
	//document.getElementById('nis1').value = nis;
	//document.getElementById('nama').value = nama;
	//document.getElementById('nama1').value = nama;
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = document.main.tgl2.value;
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = document.main.tgl1.value;
		
	
	parent.header.location.href = "../presensi/lap_hariansiswa_header.php?nis="+nis+"&nama="+nama+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2;
}

function change_tgl1() {
	var th1 = parseInt(document.getElementById('th2').value);
	var bln1 = parseInt(document.getElementById('bln2').value);
	var tgl1 = parseInt(document.main.tgl2.value);
	var th = parseInt(document.getElementById('th1').value);
	var bln = parseInt(document.getElementById('bln1').value);
	var tgl = parseInt(document.main.tgl1.value);
	
	if (th > th1) {
		alert ('Pastikan batas tahun akhir tidak kurang dari batas tahun awal');
		return false;
	} 
	
	if (th == th1 && bln > bln1 ) {
		alert ('Pastikan batas bulan akhir tidak kurang dari batas bulan awal');
		return false; 
	}	
	
	if (th == th1 && bln == bln1 && tgl > tgl1 ) { 
		alert ('Pastikan batas tanggal akhir tidak kurang dari batas tanggal awal');
		return false;
	}		
	sendRequestText("../library/gettanggal.php", show1, "tahun="+th+"&bulan="+bln+"&tgl="+tgl);	
}

function change_tgl2() {
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);
	
	var th = parseInt(document.getElementById('th2').value);
	var bln = parseInt(document.getElementById('bln2').value);
	var tgl = parseInt(document.main.tgl2.value);
	
	if (th1 > th) {
		alert ('Pastikan batas tahun akhir tidak kurang dari batas tahun awal');
		return false;
	} 
	
	if (th1 == th && bln1 > bln ) {
		alert ('Pastikan batas bulan akhir tidak kurang dari batas bulan awal');
		return false; 
	}	
	
	if (th1 == th && bln1 == bln && tgl1 > tgl ) { 
		alert ('Pastikan batas tanggal akhir tidak kurang dari batas tanggal awal');
		return false;
	}		
	sendRequestText("../library/gettanggal.php", show2, "tahun="+th+"&bulan="+bln+"&tgl="+tgl);	
}

function show1(x) {
	document.getElementById("tgl1Info").innerHTML = x;
}

function show2(x) {
	document.getElementById("tgl2Info").innerHTML = x;
}


function focusNext(elemName, evt) {
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :
		((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) {
		document.getElementById(elemName).focus();
		return false;
	}
	return true;
}
</script>
</head>
	
<body topmargin="0" leftmargin="0">
<form action="lap_hariansiswa_header.php" method="post" name="main" id="main">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE CENTER -->
<tr>	
	<td rowspan="3" width="55%">
	<table width = "100%" border = "0" >
    <tr>
    	<td width="10%"><strong>Siswa</strong></td>
        <td width="*">
        	<input name="nis" type="text" class="disabled" id="nis" value="<?=$_REQUEST['nis']?>" size="12" readonly onclick="carisiswa()"/>
            <input type="hidden" name="nis1" id="nis1" value="<?=$_REQUEST['nis']?>">
        	<input name="nama" type="text" class="disabled" id="nama" value="<?=$_REQUEST['nama']?>" size="45" readonly onclick="carisiswa()"/>
        	<input type="hidden" name="nama1" id="nama1" value="<?=$_REQUEST['nama']?>"> 
                   	
           	<a href="JavaScript:carisiswa()"><img src="../images/ico/cari.png" border="0" /></a>        
      	</td>
    </tr>
   	<tr>
    	<td><strong>Tanggal</strong></td>
        <td>
        	<select name="tgl1" id = "tgl1Info" onchange="change_tgl1()" onfocus = "panggil()" onKeyPress="focusNext('bln1',event)">
			<option value="">['Tgl']</option>
		<?php 	for($i=1;$i<=$n;$i++){   ?>      
		    <option value="<?=$i?>" <?=IntIsSelected($tgl1, $i)?>><?=$i?></option>
		<?php } ?>
		    </select>    	
          	<select name="bln1" id ="bln1" onchange="change_tgl1()" onfocus = "panggil()" onKeyPress="focusNext('th1',event)">
        <?php 	for ($i=1;$i<=12;$i++) { ?>
          	<option value="<?=$i?>" <?=IntIsSelected($bln1, $i)?>><?=$bulan[$i]?></option>	
       	<?php }	?>	
        	</select>
       		<select name="th1" id = "th1" onchange="change_tgl1()" onfocus = "panggil()" onKeyPress="focusNext('tgl2Info',event)" >
        <?php  //for($i=$th1-10;$i<=$th1;$i++){ ?>
        <?php  for ($i = $tahun1; $i <= $tahun2; $i++) { ?>
          	<option value="<?=$i?>" <?=IntIsSelected($th1, $i)?>><?=$i?></option>	   
       	<?php } ?>	
        	</select> s/d 
    		<select name="tgl2" id = "tgl2Info" onchange="change_tgl2()" onfocus = "panggil()" onKeyPress="focusNext('bln2',event)">
			<option value="">['Tgl']</option>
		<?php 	for($i=1;$i<=$n;$i++){   ?>      
		    <option value="<?=$i?>" <?=IntIsSelected($tgl2, $i)?>><?=$i?></option>
		      <?php } ?>
			</select>
        	<select name="bln2" id ="bln2" onchange="change_tgl2()" onfocus = "panggil()" onKeyPress="focusNext('th2',event)">
        <?php 	for ($i=1;$i<=12;$i++) { ?>
        	<option value="<?=$i?>" <?=IntIsSelected($bln2, $i)?>><?=$bulan[$i]?></option>	
        <?php }	?>	
        	</select>
       	 	<select name="th2" id = "th2" onchange="change_tgl2()" onfocus = "panggil()" >
       	<?php  //for($i=$th2-10;$i<=$th2;$i++){ ?>
        <?php  for ($i = $tahun1; $i <= $tahun2; $i++) { ?>
        	<option value="<?=$i?>" <?=IntIsSelected($th2, $i)?>><?=$i?></option>	   
    	<?php } ?>	
        	</select>        
      	</td> 
	</tr> 
    </table>
    </td> 
    <td width="*" rowspan="2" align="left" valign="middle"><a href="#" onclick="tampil()">
    	<img src="../images/ico/view.png" height="48" width="48" border="0" name="tabel" id="tabel2" onmouseover="showhint('Klik untuk menampilkan laporan presensi harian siswa!', this, event, '180px')"/></a></td>
  	<td width="43%" rowspan="2" align="right" valign="top">
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Presensi Harian Siswa</font>	
        <br />
    	<a href="../presensi.php" target="framecenter">
      	<font size="1" color="#000000"><b>Presensi</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Laporan Presensi Harian Siswa</b></font></td>     
    </tr>
	</table>
    </td>
</tr>
</table>
</form>
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("tgl1Info");
	var spryselect2 = new Spry.Widget.ValidationSelect("bln1");
	var spryselect3 = new Spry.Widget.ValidationSelect("th1");
	var spryselect4 = new Spry.Widget.ValidationSelect("tgl2Info");
	var spryselect5 = new Spry.Widget.ValidationSelect("bln2");
	var spryselect6 = new Spry.Widget.ValidationSelect("th2");
</script>