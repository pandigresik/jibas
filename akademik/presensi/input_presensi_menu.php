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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');

$thn = date("Y");
$th = $thn;
if (isset($_REQUEST['th']))
	$th = $_REQUEST['th'];
$bln = date("n");
if (isset($_REQUEST['bln']))
	$bln = $_REQUEST['bln'];
if (isset($_REQUEST['tgl1']))
	$tgl1 = $_REQUEST['tgl1'];
if (isset($_REQUEST['tgl2']))
	$tgl2 = $_REQUEST['tgl2'];
			
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
$aktif = 1;
if (isset($_REQUEST['aktif']))
	$aktif = $_REQUEST['aktif'];

OpenDb();
$sql = "SELECT t.tahunajaran, t.tglmulai, t.tglakhir FROM tahunajaran t, kelas k WHERE k.idtahunajaran = t.replid AND k.replid = '".$kelas."'"; 
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);

$jgk1 = explode('-',(string) $row['tglmulai']);
$jgk2 = explode('-',(string) $row['tglakhir']); 

$tahunajaran = $row['tahunajaran'];
$awal = $row['tglmulai'];
$akhir = $row['tglakhir'];

$tanggal1 = $jgk1[2];
$bulan1 = $jgk1[1];
$tahun1 = $jgk1[0];
$tanggal2 = $jgk2[2];
$bulan2 = $jgk2[1];
$tahun2 = $jgk2[0];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Input Presensi Harian</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>

<script language="javascript">

function refresh() {	
	document.location.reload();
}

function baru() {
	var kelas = document.getElementById('kelas').value;
	var semester = document.getElementById('semester').value;
	var bln = parseInt(document.getElementById('bln').value); 
	var th = parseInt(document.getElementById('th').value);
	var tgl1 = document.getElementById('tgl1').value; 
	var tgl2 = document.getElementById('tgl2').value;
	var aktif = document.getElementById('aktif').value;
	
	if (aktif == 0) {
		alert ('Pastikan waktu presensi berada dalam periode tahun ajaran!');
		document.getElementById('bln').focus();
		return false;
	}
	
	parent.isi.location.href = "input_presensi_content.php?kelas="+kelas+"&semester="+semester+"&bln="+bln+"&th="+th+"&tgl1="+tgl1+"&tgl2="+tgl2;
}

function panggil() {
	parent.isi.location.href = "blank_presensi_harian.php?tipe='harian'";
}

function tampil(replid) {	
	var kelas = document.getElementById('kelas').value;
	var semester = document.getElementById('semester').value;	
	parent.isi.location.href = "input_presensi_content.php?id="+replid+"&kelas="+kelas+"&semester="+semester;
}

function change() {
	var kelas = document.getElementById('kelas').value;
	var semester = document.getElementById('semester').value;
	var bln = parseInt(document.getElementById('bln').value); 
	var th = parseInt(document.getElementById('th').value);
	
	document.location.href = "input_presensi_menu.php?kelas="+kelas+"&semester="+semester+"&bln="+bln+"&th="+th;
	
}

</script>
</head>

<body style="background-color:#EFEFEF" onload="document.getElementById('bln').focus()">
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>">
<input type="hidden" name="semester" id="semester" value="<?=$semester ?>">
<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top" >
	<table border="0" width="100%" align="left">
	<!-- TABLE LINK -->
    <tr>
        <td align="center" bgcolor="#CCCCCC">             
           	<input type="button" name="tampil" value="Input Presensi Baru" class="but" style="width:150px" onclick="baru()" />
    	</td>
    </tr>
    <tr>	    
    	<td align="left" width="100%">
	        <br /><strong>Data Presensi</strong>
      	</td>
    </tr>
    <tr>
    	<td>
    	<strong>Bulan</strong>&nbsp;
            <select name="bln" id="bln" onChange="change()" onFocus="panggil()" onKeyPress="focusNext('th',event)">
        <?php 	for ($i=1;$i<=12;$i++) { ?>
          	<option value="<?=$i?>" <?=IntIsSelected($bln, $i)?>><?=$bulan[$i]?></option>	
       	<?php }	?>	
        	</select> 
            <select name="th" id="th" onchange="change()" onfocus="panggil()" >
        <?php  for ($i = $tahun1; $i <= $tahun2; $i++) {
			///for($i=$thn-10;$i<=$thn;$i++){ ?>
        <?php  //for($i=$thn;$i>=$thn-10;$i--){ ?>
          	<option value="<?=$i?>" <?=IntIsSelected($th, $i)?>><?=$i?></option>	   
<?php } ?>	
        	</select>            
 		</td>
 	</tr>
	</table>
	</td>
</tr>
<tr>
	<td>
<?php


if ((int)$bln == (int)$bulan2 && (int)$th == (int)$tahun2) {		
	$batas = $th."-".$bln."-1";
	$tgl1 = $tanggal2;
	$tgl2 = $tanggal2;
} elseif ((int)$bln == (int)$bulan1 && (int)$th == (int)$tahun1) {
	$batas = $th."-".$bln."-31";
	$tgl1 = $tanggal1;
	$tgl2 = $tanggal1; 
	
} else {
	$batas = $th."-".$bln."-1";	
}


OpenDb();
$sql = "SELECT t.tglmulai, t.tglakhir FROM tahunajaran t, kelas k WHERE k.idtahunajaran = t.replid AND k.replid = '$kelas' AND '$batas' BETWEEN t.tglmulai AND t.tglakhir ";
$result = QueryDb($sql);

CloseDb();
if (mysqli_num_rows($result)) {
	OpenDb();
	$sql = "SELECT replid, DAY(tanggal1) AS tgl1, MONTH(tanggal1) AS bln1, YEAR(tanggal1) AS th1, DAY(tanggal2) AS tgl2, MONTH(tanggal2) AS bln2, YEAR(tanggal2) AS th2 FROM presensiharian WHERE idkelas = '$kelas' AND idsemester = '$semester' AND ((MONTH(tanggal1) = '$bln' AND YEAR(tanggal1) = '$th') OR (MONTH(tanggal2) = '$bln') AND YEAR(tanggal2) = '$th') ORDER BY tanggal1";    
	
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0) {
?>


    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
    <!-- TABLE CONTENT -->
    
    <tr height="30">    	
    	<td width="4%" class="header" align="center">No</td>
        <td width="*" class="header" align="center">Tanggal</td>
    </tr>
   	<?php
		$cnt = 0;
		while ($row = @mysqli_fetch_array($result)) {
	?>
    <tr height="25">   	
       	<td align="center"><?=++$cnt ?></td>        
        <td align="center"  onclick="tampil(<?=$row['replid']?>)" style="cursor:pointer">
          
        <!--<a href="input_presensi_content.php?id=<?=$row['replid']?>&kelas=<?=$kelas?>&semester=<?=$semester?>" target = "isi" >--><u><b><?=$row['tgl1']." ".$bulan[$row['bln1']]." ".$row['th1']?> - <?=$row['tgl2']." ".$bulan[$row['bln2']]." ".$row['th2']?></b></u>
        <!--</a>-->
        
        </td>
    </tr>
<?php } 
	CloseDb(); 
?>	
	<!-- END TABLE CONTENT -->
	</table>    
	<script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>

<?php } else { ?>	
    <table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">
   		<font size = "2" color ="red"><b>Tidak ditemukan adanya data presensi pada bulan <?=NamaBulan($bln).' '.$th?> .  </b></font>
       	
		</td>
	</tr>
	</table>  
	
<?php }
} else {
	$aktif = 0;
	?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">
   		<font size = "2" color ="red"><b>
        Waktu data presensi tidak boleh melebihi batas <br /><?=format_tgl($awal)?> s/d <?=format_tgl($akhir)?> pada tahun ajaran <?=$tahunajaran?>.</b></font>
       	
		</td>
	</tr>
	</table>  
    
<?php } ?>
	<input type="hidden" name="aktif" id="aktif" value="<?=$aktif?>">
	<input type="hidden" name="tgl1" id="tgl1" value="<?=$tgl1 ?>">
    <input type="hidden" name="tgl2" id="tgl2" value="<?=$tgl2 ?>">    
    </td>
</tr>
<!-- END TABLE CENTER -->    
</table>    
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("bln");
	var spryselect2 = new Spry.Widget.ValidationSelect("th");
	
</script>