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
require_once('../cek.php');

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
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$tingkat = "";
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
$pelajaran = -1;
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];
$kelas = "";
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
$tahunajaran = "";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
$semester = "";
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];

$n1 = JmlHari($bln1,$th1);
$n2 = JmlHari($bln2,$th2);

$tgl2 = $n1;
if (isset($_REQUEST['tgl2']))
	$tgl2 = $_REQUEST['tgl2'];
OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Presensi Siswa Per Kelas</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
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
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);
	var pelajaran = document.getElementById('pelajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var semester = document.getElementById('semester').value;
	
	if (tgl1.length == 0) {	
		alert ('Tanggal awal tidak boleh kosong !');	
		document.main.tgl1.focus();
		return false;	
	} else if (tgl2.length == 0) {	
		alert ('Tanggal akhir tidak boleh kosong !');	
		document.main.tgl2.focus();
		return false;	
	} else if (tahunajaran.length == 0) {	
		alert ('Pastikan tahun ajaran sudah ada!');
		document.getElementById('tahunajaran').focus();
		return false;
	} else if (semester.length == 0) {	
		alert ('Pastikan semester sudah ada!');
		document.getElementById('semester').focus();
		return false;
	} else if (tingkat.length == 0) {	
		alert ('Pastikan tingkat sudah ada!');
		document.getElementById('tingkat').focus();
		return false;
	} else if (kelas.length == 0) {	
		alert ('Pastikan kelas sudah ada!');
		document.getElementById('kelas').focus();
		return false;
	}
		
	var validasi = validateTgl(tgl1,bln1,th1,tgl2,bln2,th2);
	if (validasi)		
		parent.footer.location.href = "lap_kelas_footer.php?tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&pelajaran="+pelajaran+"&semester="+semester+"&kelas="+kelas;
}

function change() {
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);	
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var pelajaran = document.getElementById("pelajaran").value;
	var kelas = document.getElementById("kelas").value;
	var tingkat = document.getElementById("tingkat").value;
			
	parent.header.location.href = "lap_kelas_header.php?tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&departemen="+departemen+"&pelajaran="+pelajaran+"&tahunajaran="+tahunajaran+"&kelas="+kelas+"&tingkat="+tingkat+"&semester="+semester;	
	parent.footer.location.href = "blank_presensi_siswa_kelas.php";	
}

/*function panggil() {
	parent.footer.location.href = "blank_presensi_siswa_kelas.php";	
}
*/
function change_dep() {
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);	
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var pelajaran = document.getElementById("pelajaran").value;
				
	parent.header.location.href = "lap_kelas_header.php?tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&departemen="+departemen+"&pelajaran="+pelajaran+"&tahunajaran="+tahunajaran;	
	parent.footer.location.href = "blank_presensi_siswa_kelas.php";	
}

function change_ajaran() {
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);	
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	
	parent.header.location.href = "lap_kelas_header.php?tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&departemen="+departemen+"&semester="+semester+"&tahunajaran="+tahunajaran;	
	parent.footer.location.href = "blank_presensi_siswa_kelas.php'";	
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
	parent.footer.location.href = "blank_presensi_siswa_kelas.php";
	var lain = new Array('departemen','tahunajaran','tingkat','kelas','semester','pelajaran','tgl1','bln1','th1','tgl2','bln2','th2');
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
	
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form action="lap_kelas_header.php" method="post" name="main">
<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
<!-- TABLE CENTER -->
<tr>	
	<td rowspan="3" width="63%">
	<table width = "100%" border = "0" height="100%">
    <tr>
		<td width="17%"><strong>Departemen </strong></td>
    	<td width="20%"> 
    	<select name="departemen" id="departemen" onChange="change_dep()" style="width:165px" onKeyPress="focusNext('tahunajaran',event)" onfocus="panggil('departemen')">
		<?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
			if ($departemen == "")
				$departemen = $value; ?>
		<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > <?=$value ?> </option>
	<?php } ?>
		</select></td>
        <td width="12%"><strong>Tingkat </strong></td>
    	<td>
		<select name="tingkat" style="width:200px" onKeyPress="focusNext('kelas',event)" id="tingkat" onchange="change()" onfocus="panggil('tingkat')">
          <?php OpenDb();
			$sql = "SELECT replid,tingkat FROM tingkat WHERE aktif=1 AND departemen='$departemen' ORDER BY urutan";	
			$result = QueryDb($sql);
			CloseDb();
	
			while($row = mysqli_fetch_array($result)) {
			if ($tingkat == "")
				$tingkat = $row['replid'];				
			?>
          <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tingkat) ?>>
            <?=$row['tingkat']?>
            </option>
          <?php
			} //while
			?>
        </select></td>
    </tr>
	<tr>
    	<td><strong>Tahun Ajaran </strong></td>
      	<td>
        	<select name="tahunajaran" id="tahunajaran" onchange="change_ajaran()"  style="width:165px" onKeyPress="focusNext('semester',event)" onfocus="panggil('tahunajaran')">
   		 	<?php
			OpenDb();
			$sql = "SELECT replid,tahunajaran,aktif FROM tahunajaran WHERE departemen='$departemen' ORDER BY aktif DESC, tahunajaran DESC";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
			if ($tahunajaran == "") 				
				$tahunajaran = $row['replid'];			
			$ada = "";
			if ($row['aktif'])
				$ada = "(Aktif)";					
			?>
            
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tahunajaran)?> ><?=$row['tahunajaran'].' '.$ada?></option>
                  
    		<?php
			}
    		?>
    		</select>            </td> 
        <td><strong>Kelas </strong></td>
    	<td>
        	<select name="kelas" id="kelas" onchange="change()" style="width:200px" onKeyPress="focusNext('pelajaran',event)" onfocus="panggil('kelas')">
			<?php OpenDb();
			$sql = "SELECT replid,kelas FROM kelas WHERE aktif=1 AND idtahunajaran = '$tahunajaran' AND idtingkat = '$tingkat' ORDER BY kelas";	
			$result = QueryDb($sql);
			CloseDb();
	
			while($row = mysqli_fetch_array($result)) {
			if ($kelas == "")
				$kelas = $row['replid'];				 
			?>
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $kelas) ?>><?=$row['kelas']?></option>
             
    		<?php
			} //while
			?>
    		</select>        </td>
          </tr>
    <tr>
    <tr>
    	<td><strong>Semester </strong></td>
      	<td><select name="semester" id="semester" onchange="change()" style="width:165px" onkeypress="focusNext('tingkat',event)" onfocus="panggil('semester')">
          <?php
			OpenDb();
			$sql = "SELECT replid,semester,aktif FROM semester where departemen='$departemen' ORDER BY aktif DESC, replid DESC";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
			if ($semester == "") 
				$semester = $row['replid'];
			$ada = "";
			if ($row['aktif'])
				$ada = "(Aktif)";
			?>
          <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $semester)?> >
            <?=$row['semester'].' '.$ada?>
            </option>
          <?php
			}
    		?>
        </select></td>
   		<td align="left" width="10%"><strong>Pelajaran</strong></td>
      	<td width="30%">
        	<select name="pelajaran" style="width:200px" onKeyPress="focusNext('tgl1',event)" id="pelajaran" onchange="change()" onfocus="panggil('pelajaran')">
            <option value="-1">(Semua pelajaran)</option> 
   		<?php
			OpenDb();
			$sql = "SELECT replid,nama FROM pelajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY nama";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
			if ($pelajaran == "") 				
				$pelajaran = $row['replid'];			
		?>
            <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $pelajaran)?> ><?=$row['nama']?></option>
                  
    	<?php  } ?>
    		</select></td>
    </tr>
    <tr>
    	<td><strong>Tanggal </strong></td>
        <td colspan="3">
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
        	<table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
            	<td width="10">
                <div id = "InfoTgl1" > 
                <select name="tgl1" id = "tgl1" onchange="change_tgl1()" onfocus = "panggil('tgl1')" onKeyPress="focusNext('bln1',event)">
                    <option value="">[Tgl]</option>
                <?php 	for($i=1;$i<=$n1;$i++){   ?>      
                    <option value="<?=$i?>" <?=IntIsSelected($tgl1, $i)?>><?=$i?></option>
                <?php } ?>
                    </select>
              	</div>
                </td>
                <td width="160">
                    <select name="bln1" id ="bln1" onchange="change_tgl1()" onfocus = "panggil('bln1')" onKeyPress="focusNext('th1',event)">
                <?php 	for ($i=1;$i<=12;$i++) { ?>
                    <option value="<?=$i?>" <?=IntIsSelected($bln1, $i)?>><?=$bulan[$i]?></option>	
                <?php }	?>	
                    </select>
                    <select name="th1" id = "th1" onchange="change_tgl1()" onfocus = "panggil('th1')" onKeyPress="focusNext('tgl2',event)" style="width:60px">
                <?php  for ($i = $tahun1; $i <= $tahun2; $i++) { ?>
                    <option value="<?=$i?>" <?=IntIsSelected($th1, $i)?>><?=$i?></option>	   
                <?php } ?>	
                    </select> 
                    s/d
               	</td>
                <td width="10">
               	<div id="InfoTgl2">
                    <select name="tgl2" id = "tgl2" onchange="change_tgl2()" onfocus = "panggil('tgl2')"  onKeyPress="focusNext('bln2',event)">
                    <option value="">[Tgl]</option>
                <?php 	for($i=1;$i<=$n2;$i++){   ?>      
                    <option value="<?=$i?>" <?=IntIsSelected($tgl2, $i)?>><?=$i?></option>
                      <?php } ?>
                    </select>
              	</div>
                </td>
                <td>
                    <select name="bln2" id ="bln2" onchange="change_tgl2()" onfocus = "panggil('bln2')" onKeyPress="focusNext('th2',event)">
                <?php 	for ($i=1;$i<=12;$i++) { ?>
                    <option value="<?=$i?>" <?=IntIsSelected($bln2, $i)?>><?=$bulan[$i]?></option>	
                <?php }	?>	
                    </select>
                    <select name="th2" id = "th2" onchange="change_tgl2()" onfocus = "panggil('th2')" onKeyPress="focusNext('tabel',event)"  style="width:60px">
                <?php  for ($i = $tahun1; $i <= $tahun2; $i++) { ?>
                <?php  //for($i=$th2-10;$i<=$th2;$i++){ ?>
                    <option value="<?=$i?>" <?=IntIsSelected($th2, $i)?>><?=$i?></option>	   
<?php } ?>	
                    </select>        
              	</td>
          	</tr>
            </table>
    	</td> 
    </tr>
	</table>
    </td>
	<td align="left" valign="middle" width="6%"><a href="#" onclick="tampil()" >
    <img src="../images/view.png" onmouseover="showhint('Klik untuk menampilkan laporan presensi per kelas!', this, event, '100px')" height="48" width="48" border="0" name="tabel" id="tabel2"/></a></td>    
    <td width="31%" align="right" valign="top">
     <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Presensi Pelajaran per Kelas</font><br />
    <a href="../presensi.php?page=pp" target="content">
      	<font size="1" color="#000000"><b>Presensi</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Laporan Presensi Pelajaran per kelas</b></font>
        </td>     
    </tr>
	</table>
    </td>
</tr>
</table>
</form>
</body>
</html>