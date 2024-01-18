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
require_once("../include/sessionchecker.php");

if (isset($_REQUEST['nip']))
	$nip = $_REQUEST['nip'];
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$tahunajaran = "";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
$semester = "";
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
$tingkat = "";
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
$kelas = "";
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
$pelajaran = "";
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];



OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cetak Form</title>

<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function change() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;
	var kelas = document.getElementById("kelas").value;
	var nip = document.getElementById("nip").value;
			
	document.location.href = "formpenilaian.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas+"&nip="+nip;
}

function change_dep() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var pelajaran = document.getElementById("pelajaran").value;
		
	document.location.href = "formpenilaian.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&pelajaran="+pelajaran;
}

function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;	
	
	document.location.href = "formpenilaian.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran;
}


function validate(jenis) {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;	
	var pelajaran = document.getElementById("pelajaran").value;
	var kelas = document.getElementById("kelas").value;
	var nip = document.getElementById("nip").value; 	

	if (tahunajaran.length == 0) {	
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
	} else if (pelajaran.length == 0) {	
		alert ('Pastikan pelajaran sudah ada!');
		document.getElementById('pelajaran').focus();
		return false;
	} else if (nip.length == 0) {	
		alert ('Pastikan ada guru yang mengajar!');
		document.getElementById('nip').focus();
		return false;
	}
	var addr, title, w, h;
	if (jenis==1){
		w='790';
		h='850';
		title='CetakFormPengisianNilaiSiswa';
		addr='form_nilai_cetak.php?departemen='+departemen+'&tahunajaran='+tahunajaran+'&semester='+semester+'&pelajaran='+pelajaran+'&kelas='+kelas+'&nip='+nip;
	} else if (jenis==2){
		w='372';
		h='162';
		title='CetakFormPengisianNilaiAkhirSiswa_Verifikasi';
		addr='form_akhir_cetak_verifikasi.php?semester='+semester+'&pelajaran='+pelajaran+'&kelas='+kelas+'&nip='+nip+'&tingkat='+tingkat;
	} else if (jenis==3){
		w='790';
		h='850';
		title='CetakFormNilaiRaporSiswa';
		addr='form_rapor_cetak.php?semester='+semester+'&pelajaran='+pelajaran+'&kelas='+kelas+'&nip='+nip;
	} else if (jenis==4){
		w='790';
		h='850';
		title='CetakFormKomentarNilaiRaporSiswa';
		addr='form_komentar_cetak.php?semester='+semester+'&pelajaran='+pelajaran+'&kelas='+kelas+'&nip='+nip;
	}
	newWindow(addr,title,w,h,'resizable=1,scrollbars=1,status=0,toolbar=0');
	
	//document.location.href = "formpenilaian.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas+"&nip="+nip+"&jenis="+jenis;	
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
/*
function a(){
	newWindow('form_nilai_cetak.php?departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&semester=<?=$semester?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&nip=<?=$nip?>','','790','850','resizable=1,scrollbars=1,status=0,toolbar=0');	
}
function b(){
	newWindow('form_akhir_cetak_verifikasi.php?semester=<?=$semester?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&nip=<?=$nip?>&tingkat=<?=$tingkat?>', '','360','240','resizable=1,scrollbars=1,status=1,toolbar=0');	
}
function c(){
	newWindow('form_rapor_cetak.php?semester=<?=$semester?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&nip=<?=$nip?>','','790','850','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function d(){
	newWindow('form_komentar_cetak.php?semester=<?=$semester?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&nip=<?=$nip?>','','790','850','resizable=1,scrollbars=1,status=0,toolbar=0');
}
*/
</script>
</head>

<body onLoad="document.getElementById('departemen').focus()">

<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/ico/b_cetak.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td width="180" height="122">&nbsp;</td>
	<td align="left" valign="top">

	<table border="0"width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
        <td align="right">
         <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Cetak Form Penilaian</font><br />
        </td>
   	</tr>
    <tr>
      	<td align="right">
        <a href="../penilaian.php" target="framecenter">
      	<font size="1" color="#000000"><b>Penilaian</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Cetak Form-form Penilaian</b></font>
        </td>
    </tr>
    <tr>
    	<td align="left">&nbsp;</td>
    </tr>
	</table>
    <br />
    <table border="0" cellpadding="2" cellspacing="2" width="95%" align="left">
    <!-- TABLE LINK -->
    <tr>
   		<td width="15%"><strong>Departemen</strong></td>
    	<td width="25%"> 
    		<select name="departemen" id="departemen" onChange="change_dep()" style="width:150px" onKeyPress="return focusNext('tingkat', event)">
		<?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
				if ($departemen == "")
					$departemen = $value; ?>
			<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > <?=$value ?> </option>
		<?php } ?>
			</select>    </td>
       	<td width="10%"><strong>Tingkat </strong></td>
    	<td><select name="tingkat" id="tingkat" onChange="change_tingkat()" style="width:225px" onkeypress="return focusNext('kelas', event)">
          <?php OpenDb();
			$sql = "SELECT replid,tingkat FROM tingkat WHERE aktif=1 AND departemen='$departemen' ORDER BY urutan";	
			$result = QueryDb($sql);
			CloseDb();
	
			while($row = mysqli_fetch_array($result)) {
			if ($tingkat == "")
				$tingkat = $row['replid'];				
			$nama_tingkat = $row['tingkat'];
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
    	<td><strong>Tahun Ajaran</strong></td>
       	<td>
        <?php  OpenDb();
			$sql = "SELECT replid,tahunajaran FROM tahunajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY replid DESC";
			$result = QueryDb($sql);
			CloseDb();
			$row = @mysqli_fetch_array($result);	
			$tahunajaran = $row['replid'];				
		?>
        	<input type="text" name="tahun" id="tahun" size="22" readonly value="<?=$row['tahunajaran']?>" class="disabled" />
        	<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$row['replid']?>">        </td>
        <td><strong>Kelas </strong></td>
    	<td>
   			<select name="kelas" id="kelas" onChange="change()" style="width:225px" onKeyPress="return focusNext('pelajaran', event)">
		<?php OpenDb();
			$sql = "SELECT replid,kelas FROM kelas WHERE aktif=1 AND idtahunajaran = '$tahunajaran' AND idtingkat = '$tingkat' ORDER BY kelas ";	
			$result = QueryDb($sql);
			CloseDb();
	
			while($row = mysqli_fetch_array($result)) {
			if ($kelas == "")
				$kelas = $row['replid'];
			$nama_kelas = $row['kelas'];			 
			?>
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $kelas) ?>><?=$row['kelas']?></option>
             
    		<?php
			} //while
			?>
    		</select>		</td>
    </tr>
    <tr>
    	<td><strong>Semester </strong></td>
    	<td>
		<?php
            OpenDb();
            $sql = "SELECT replid,semester FROM semester where departemen='$departemen' AND aktif = 1";
            $result = QueryDb($sql);
            CloseDb();
            $row = @mysqli_fetch_array($result);
            
       	?>   	
            <input type="text" name="sem" size="22" value="<?=$row['semester'] ?>" readonly class="disabled"/>
            <input type="hidden" name="semester" id="semester" value="<?=$row['replid']?>">            </td>
        <td align="left"><strong>Pelajaran</strong></td>      	
      	<td>
        	<select name="pelajaran" id="pelajaran" onChange="change()" style="width:225px" onKeyPress="return focusNext('nip', event)">
   		 	<?php
			OpenDb();
			$sql = "SELECT p.replid,p.nama FROM pelajaran p, guru g WHERE p.departemen = '$departemen' AND g.idpelajaran=p.replid AND g.nip='".SI_USER_ID()."' AND p.aktif=1 ORDER BY p.nama";
			
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
			if ($pelajaran == "") 				
				$pelajaran = $row['replid'];			
			$nama_pelajaran = $row['nama'];
			?>
         	<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $pelajaran)?> ><?=$row['nama']?></option>                  
    		<?php
			}
    		?>
    		</select>		
            <input type="hidden" name="nip" id="nip" value="<?=SI_USER_ID()?>" />
            </td> 
   	</tr>
    <!--
    <tr>
      <td><strong>Guru</strong></td>
      <td colspan="3">
      		<select name="nip" id="nip" onChange="change()" style="width:465px">
   		 	<?php
			//OpenDb();
			//$sql = "SELECT DISTINCT p.nip,p.nama FROM jbssdm.pegawai p, guru g, pelajaran l WHERE p.nip = g.nip AND g.idpelajaran = $pelajaran AND g.aktif = 1  ORDER BY p.nama  ";
			//$result = QueryDb($sql);
			//CloseDb();
			//while ($row = @mysqli_fetch_array($result)) {
			//if ($nip == "") 				
			//	$nip = $row['nip'];			
			?>
         	<option value="<?//=urlencode($row['nip'])?>" <?//=StringIsSelected($row['nip'], $nip)?> ><?//=$row['nip']?> - <?//=$row['nama']?></option>                  
    		<?php
			//}
    		?>
    		</select>      </td>
    </tr>-->
    <tr>
    	
		<td colspan="3"><br />
        	
        	<a href="#" onclick="validate(1)" ><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak Form Pengisian Nilai Siswa!', this, event, '50px')"/>&nbsp;Cetak Form Pengisian Nilai Siswa</a>&nbsp;&nbsp;
            <p><a href="#" onclick="validate(2)"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak Form Pengisian Nilai Akhir Siswa!', this, event, '50px')"/>&nbsp;Cetak Form Pengisian Nilai Akhir Siswa</a>&nbsp;&nbsp;
            <p><a href="#" onclick="validate(3)"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak Form Pengisian Nilai Rapor Siswa!', this, event, '50px')"/>&nbsp;Cetak Form Pengisian Nilai Rapor Siswa</a>&nbsp;&nbsp;
            <p><a href="#" onclick="validate(4)"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak Form Komentar Rapor Siswa!', this, event, '50px')"/>&nbsp;Cetak Form Komentar Rapor Siswa</a>&nbsp;&nbsp;    	            
            </td>
       	<td></td>
	</tr>
    </table>
  
    </td>
</tr>
<!-- END TABLE CENTER -->    
</table>
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>
<?php
$ERROR_MSG = "";
if (isset($_REQUEST['jenis'])) {
	OpenDb();
	$sql = "SELECT nis, nama FROM siswa WHERE idkelas = '$kelas' ORDER BY nama";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {	
		switch($_REQUEST['jenis']) {
			case 1 :
?>
        	<!--<script language="javascript" src="../script/tools.js"></script>-->
			<script language="javascript">				
					a();						
			</script>
<?php 				
					break;
			case 2 :
				$sql1="SELECT * FROM jbsakad.aturannhb WHERE idtingkat='$tingkat' AND idpelajaran='$pelajaran' AND aktif=1 AND nipguru='$nip'";
				$result1=QueryDb($sql1);
				if (mysqli_num_rows($result1) > 0) {
			?>
        	<!--<script language="javascript" src="../script/tools.js"></script>-->
			<script language="javascript">				
				b();											
			</script>
<?php 			} else {					
					$ERROR_MSG = "Belum ada Jenis Pengujian!";		
				}	
					break;
			case 3 :
?>
        	<!--<script language="javascript" src="../script/tools.js"></script>-->
			<script language="javascript">				
					c();					
			</script>
<?php 		
					break;
			case 4 :
?>
        	<!--<script language="javascript" src="../script/tools.js"></script>-->
			<script language="javascript">				
				d();								
			</script>
<?php 				
					break;
		}
	} else {
		CloseDb();
		$ERROR_MSG = "Belum ada data siswa yang terdaftar pada kelas ini!";		
		
	}
}
?>    
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");	
	var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
	var spryselect5 = new Spry.Widget.ValidationSelect("nip");
	var spryselect6 = new Spry.Widget.ValidationSelect("pelajaran");
</script>