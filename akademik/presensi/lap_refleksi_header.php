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
//echo 'tgl '.$tgl1.' '.$tgl2;
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$tahunajaran = "";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
$pelajaran = -1;
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];
$semester = "";
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
$kelas = -1;
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
$tingkat = -1;
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];

$n1 = JmlHari($bln1,$th1);
$n2 = JmlHari($bln2,$th2);

$tgl2 = $n1;
if (isset($_REQUEST['tgl2']))
	$tgl2 = $_REQUEST['tgl2'];
//$tahun2 = date("Y");
//$tahun1 = $tahun2-10;


if ($tingkat == -1) 
	$fokus = "focusNext('pelajaran',event)";
else
	$fokus = "focusNext('kelas',event)";

OpenDb();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Refleksi Pengajar</title>
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
	var tahunajaran = document.getElementById('tahunajaran').value;	
	var kelas = document.getElementById('kelas').value;	
	var semester = document.getElementById('semester').value;	
	var nip = document.getElementById('nipguru').value;	
	var pelajaran = document.getElementById('pelajaran').value;
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;

	if (nip.length == 0){
		alert ('NIP guru tidak boleh kosong !');
		return false;
	} else if (tahunajaran.length == 0){
		alert ('Tahun ajaran tidak boleh kosong !');
		document.getElementById('tahunajaran').focus();
		return false;
	} else if (semester.length == 0){
		alert ('Semester tidak boleh kosong !');
		document.getElementById('semester').focus();
		return false;
	} else if (kelas.length == 0){
		alert ('Kelas tidak boleh kosong !');
		document.getElementById('kelas').focus();
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
		parent.footer.location.href = "lap_refleksi_footer.php?pelajaran="+pelajaran+"&nip="+nip+"&semester="+semester+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&kelas="+kelas+"&departemen="+departemen+"&tingkat="+tingkat;
	
} 

function panggil() {
	parent.footer.location.href = "blank_presensi_refleksi_mengajar.php";
}

function change_departemen() {
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);	
	var departemen = document.getElementById("departemen").value;
	var nip = document.getElementById("nipguru").value;
	var nama = document.getElementById("namaguru").value;
				
	parent.header.location.href = "lap_refleksi_header.php?tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&departemen="+departemen+"&nip="+nip+"&nama="+nama;	
	parent.footer.location.href = "blank_presensi_refleksi_mengajar.php";			
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
	var nip = document.getElementById("nipguru").value;
	var nama = document.getElementById("namaguru").value;
	
	parent.header.location.href = "lap_refleksi_header.php?tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&departemen="+departemen+"&semester="+semester+"&tahunajaran="+tahunajaran+"&nip="+nip+"&nama="+nama;	
	parent.footer.location.href = "blank_presensi_refleksi_mengajar.php";	
}

function change_tingkat() {
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);		
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var nip = document.getElementById("nipguru").value;
	var nama = document.getElementById("namaguru").value;
		
	parent.header.location.href = "lap_refleksi_header.php?tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&departemen="+departemen+"&semester="+semester+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&nip="+nip+"&nama="+nama;
		
	parent.footer.location.href = "blank_presensi_refleksi_mengajar.php";	
}

function change() {
	var th2 = parseInt(document.getElementById('th2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var th1 = parseInt(document.getElementById('th1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);	
	var nip = document.getElementById('nipguru').value;
	var nama = document.getElementById('namaguru').value; 
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var semester=document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var kelas = document.getElementById("kelas").value;
	var pelajaran = document.getElementById("pelajaran").value;
	
	document.location.href = "lap_refleksi_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&nip="+nip+"&nama="+nama+"&semester="+semester+"&tgl1="+tgl1+"&bln1="+bln1+"&th1="+th1+"&tgl2="+tgl2+"&bln2="+bln2+"&th2="+th2+"&tingkat="+tingkat+"&kelas="+kelas+"&pelajaran="+pelajaran;
	parent.footer.location.href="blank_presensi_refleksi_mengajar.php";
}

function pegawai() {
	parent.footer.location.href = "blank_presensi_refleksi_mengajar.php";
	newWindow('../library/guru.php?flag=0', 'Guru','600','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(nip, nama, flag, dep) {
	document.location.href = "../presensi/lap_refleksi_header.php?departemen="+dep+"&nip="+nip+"&nama="+nama;
	document.getElementById('nip').value = nip;
	document.getElementById('nipguru').value = nip;
	document.getElementById('nama').value = nama;
	document.getElementById('namaguru').value = nama;
	
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
	parent.footer.location.href = "blank_presensi_refleksi_mengajar.php";
	var lain = new Array('departemen','tahunajaran','tingkat','semester','kelas','pelajaran','tgl1','bln1','th1','tgl2','bln2','th2');
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
<form action="lap_refleksi_header.php" method="post" name="main">
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>	
	<td rowspan="3" width="68%">
	<table width = "100%" border = "0" height="100%">
	<tr>
    	<td width="16%"><strong>Guru</strong></td>
        <td colspan="3"><strong>
    		<input name="nip" type="text" class="disabled" id="nip" value="<?=$_REQUEST['nip'] ?>" size="12" readonly onclick="pegawai();"/>
    		<input type="hidden" name="nipguru" id="nipguru" value="<?=$_REQUEST['nip'] ?>"/>
    		<input name="nama" type="text" class="disabled" id="nama" value="<?=$_REQUEST['nama'] ?>" size="45" readonly onclick="pegawai();"/>
    		<input type="hidden" name="namaguru" id="namaguru" value="<?=$_REQUEST['nama'] ?>"/>
			</strong>
        	<a href="JavaScript:pegawai()"><img src="../images/ico/cari.png" border="0" /></a></td>
    </tr>
   <tr>
		<td><strong>Tahun Ajaran</strong></td>
    	<td width="35%"> 
    	<select name="departemen" id="departemen" onChange="change_departemen()" style="width:70px" onKeyPress="focusNext('tahunajaran',event)" onfocus="panggil('departemen')">
		<?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
			if ($departemen == "")
				$departemen = $value; ?>
		<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > <?=$value ?> </option>
	<?php } ?>
		</select>
    	<select name="tahunajaran" id="tahunajaran" onchange="change_ajaran()"  style="width:125px" onkeypress="focusNext('semester',event)" onfocus="panggil('tahunajaran')">
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
          <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tahunajaran)?> >
          <?=$row['tahunajaran'].' '.$ada?>
          </option>
          <?php
			}
    		?>
        </select></td>
        <td width="12%"><strong>Kelas</strong></td>
    	<td>
		<select name="tingkat" style="width:80px" onKeyPress="<?=$fokus?>" id="tingkat" onchange="change_tingkat()" onfocus="panggil('tingkat')">
        <option value="-1" >(Semua)</option>
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
        </select>
        <?php  if ($tingkat == -1)  {
			$disable = 'disabled="disabled"';
		} else	{
			$disable = "";
		} ?>
        <select name="kelas" id="kelas" onchange="change()" style="width:110px"  <?=$disable?> onKeyPress="focusNext('pelajaran',event)" onfocus="panggil('kelas')">
        <option value="-1" >(Semua Kelas)</option>
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
    	<td><strong>Semester </strong></td>
      	<td><select name="semester" id="semester" onchange="change()" style="width:200px" onkeypress="focusNext('tingkat',event)" onfocus="panggil('semester')">
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
        <td align="left" width="12%"><strong>Pelajaran</strong></td>
      	<td>
        	<select name="pelajaran" style="width:195px" onKeyPress="focusNext('tgl1',event)" id="pelajaran" onchange="change()" onfocus="panggil('pelajaran')">
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
    	<td><strong>Tanggal</strong></td>
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
                <?php  //for($i=$th1-10;$i<=$th1;$i++){ ?>
                    <option value="<?=$i?>" <?=IntIsSelected($th1, $i)?>><?=$i?></option>	   
<?php } ?>	
                </select> s/d 
                </td>
                <td width="10">
               	<div id="InfoTgl2">
    			<select name="tgl2" id = "tgl2" onchange="change_tgl2()" onfocus = "panggil('tgl2')" onKeyPress="focusNext('bln2',event)">
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
                    <select name="th2" id = "th2" onchange="change_tgl2()" onfocus = "panggil('th2')"  onKeyPress="focusNext('tabel',event)" style="width:60px">
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
    <td align="left" valign="middle"><a href="#" onclick="tampil()" ><img src="../images/view.png" onmouseover="showhint('Klik untuk menampilkan laporan refleksi pengajar!', this, event, '180px')" height="48" width="48" border="0" name="tabel" id="tabel2"/></a></td>
    <td width="*" align="right" valign="top">
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Refleksi Mengajar</font><br />
	<a href="../presensi.php?page=pp" target="content">
  	<font size="1" color="#000000"><b>Presensi</b></font></a>&nbsp>&nbsp
    <font size="1" color="#000000"><b>Laporan Refleksi Mengajar</b></font>    </tr>
	</table>
    </td>
</tr>
</table>
</form>
</body>
</html>