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

OpenDb();
$departemen=$_REQUEST['departemen'];
$tahunakhir = date('Y');
if (isset($_REQUEST['tahunakhir']))
	$tahunakhir=$_REQUEST['tahunakhir'];
$tahunawal = date('Y');
if (isset($_REQUEST['tahunawal']))
	$tahunawal=$_REQUEST['tahunawal'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Statistik Mutasi</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<link href="../style/style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function tampil(){
	var departemen = document.getElementById("departemen").value;
	var tahunakhir = document.getElementById("tahunakhir").value;
	var tahunawal = document.getElementById("tahunawal").value;
		
	if (tahunakhir == "" || tahunawal == ""){
		alert ('Belum ada siswa yang dimutasi pada departemen ini!');
		document.getElementById("departemen").focus();
		return false;
	}
	
	if (tahunakhir<tahunawal){
		alert ('Pastikan tahun akhir tidak kurang dari tahun awal');
		return false;
	}
		
	parent.statistik_mutasi_grafik.location.href="content_mutasi.php?departemen="+departemen+"&tahunawal="+tahunawal+"&tahunakhir="+tahunakhir;
}

function change(){
	var departemen = document.getElementById("departemen").value;
	var tahunakhir = document.getElementById("tahunakhir").value;
	var tahunawal = document.getElementById("tahunawal").value;
	
	//if (tahunakhir < tahunawal) 
		
	if (tahunakhir < tahunawal){
		alert ('Tahun akhir tidak boleh !');
		document.getElementById("tahunawal").focus();
		return false;
	}	
	
	document.location.href="statistik_mutasi_header.php?departemen="+departemen+"&tahunawal="+tahunawal+"&tahunakhir="+tahunakhir;
	parent.statistik_mutasi_footer.location.href="blank_statistik.php";
	parent.statistik_mutasi_content.location.href="blank_statistik.php";
}

function change_dep() {
	var departemen=document.getElementById("departemen").value;
	document.location.href = "statistik_mutasi_header.php?departemen="+departemen;
	parent.statistik_mutasi_footer.location.href="blank_statistik.php";
	parent.statistik_mutasi_content.location.href="blank_statistik.php";
}

function focusNext(elemName, evt) {
	var aktif = 1;
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
<body leftmargin="0" topmargin="0" onload="document.getElementById('departemen').focus()">
<table border="0" width="100%" cellpadding="0" cellspacing="0"  >
<!-- TABLE TITLE -->
<tr>
	<td rowspan="2" width="38%">
	<table width = "100%" border = "0">
    <tr>
        <td width="25%"><strong>Departemen</strong></td>
        <td width="*">
      	<select name="departemen" id="departemen" onChange="change_dep()"  style="width:100px" onKeyPress="return focusNext('tahunawal', event)">
        <?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
				if ($departemen == "")
					$departemen = $value; ?>
        	<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
            <?=$value ?> 
            </option>
       	<?php } ?>
        </select></td>
	</tr>
  	<tr>
    	<td><strong>Periode</strong></td>
    	<td>
      	<select name="tahunawal" id="tahunawal" onChange="change()"  style="width:100px" onKeyPress="return focusNext('tahunakhir', event)">
        <?php OpenDb();
			$sql_thn_awal="SELECT DISTINCT YEAR(tglmutasi) as tahunawal FROM jbsakad.mutasisiswa WHERE departemen='$departemen' ORDER BY tahunawal ASC";
			$result_thn_awal=QueryDb($sql_thn_awal);
			while($row_thn_awal=mysqli_fetch_array($result_thn_awal)){
				if ($tahunawal=="")
					$tahunawal=$row_thn_awal['tahunawal'];
		?>
        	<option value="<?=$row_thn_awal['tahunawal']?>" <?=IntIsSelected($row_thn_awal['tahunawal'],$tahunawal)?>>
        	<?=$row_thn_awal['tahunawal']?>
        	</option>
        <?php }
			CloseDb();
		?>
      	</select>   
    	s/d
      	<select name="tahunakhir" id="tahunakhir" onChange="change()"  style="width:100px" onKeyPress="return focusNext('tabel', event)">
        <?php OpenDb();
			$sql_thn_akhir="SELECT DISTINCT YEAR(tglmutasi) as tahunakhir FROM jbsakad.mutasisiswa WHERE departemen='$departemen' ORDER BY tahunakhir DESC";
			$result_thn_akhir=QueryDb($sql_thn_akhir);
			while($row_thn_akhir=mysqli_fetch_array($result_thn_akhir)){
				if ($tahunakhir=="")
					$tahunakhir=$row_thn_akhir['tahunakhir'];
		?>
        	<option value="<?=$row_thn_akhir['tahunakhir']?>" <?=IntIsSelected($row_thn_akhir['tahunakhir'],$tahunakhir)?>>
        	<?=$row_thn_akhir['tahunakhir']?>
        	</option>
        <?php }
			CloseDb();
		?>
      	</select>   
		</td>
    </tr>
    </table></td>
    <td valign="middle"><a href="#" onclick="tampil()"><img src="../images/view.png" width="48" height="48" border="0" name="tabel" id="tabel" onmouseover="showhint('Klik untuk menampilkan statistik siswa yang dimutasi!', this, event, '200px')"></a></td>
    <td colspan="2" align="right" valign="top"> 
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Statistik Mutasi Siswa</font><br />
      <a href="../mutasi.php" target="content"> 
      <font size="1" color="#000000"><b>Mutasi</b></font></a>&nbsp>&nbsp 
      <font size="1" color="#000000"><b>Statistik Mutasi Siswa</b></font></td>
</tr>
</table>
</body>
</html>
<script language="javascript">
	var spryselect11 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect12 = new Spry.Widget.ValidationSelect("tahunawal");
	var spryselect12 = new Spry.Widget.ValidationSelect("tahunakhir");
</script>