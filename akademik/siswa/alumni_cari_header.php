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

$tipe = [["nis", "NIS"], ["idangkatan", "Angkatan"], ["tgllulus", "Tahun Lulus"], ["nama", "Nama"], ["panggilan", "Nama Panggilan"], ["agama", "Agama"], ["suku", "Suku"], ["status", "Status"], ["kondisi", "Kondisi Siswa"], ["darah", "Golongan Darah"], ["alamatsiswa", "Alamat Siswa"], ["asalsekolah", "Asal Sekolah"], ["namaayah", "Nama Ayah"], ["namaibu", "Nama Ibu"], ["alamatortu", "Alamat Orang Tua"], ["keterangan", "Keterangan"]];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
/*$angkatan = "";
if (isset($_REQUEST['angkatan']))
	$angkatan = $_REQUEST['angkatan'];	*/
if (isset($_REQUEST['jenis']))
	$jenis = $_REQUEST['jenis'];
if (isset($_REQUEST['cari']))
	$cari = $_REQUEST['cari'];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pencarian Siswa</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">
function blank() {
	var departemen = document.getElementById('departemen').value;
	//var angkatan = document.getElementById('angkatan').value;
	var jenis = document.getElementById('jenis').value;
	var cari = document.getElementById("cari").value;
	
	document.location.href = "alumni_cari_header.php?departemen="+departemen+"&jenis="+jenis+"&cari="+cari;
	parent.cari_alumni_footer.location.href = "blank_alumni_cari.php";
}

function cari_alumni() {
	var jenis = document.getElementById("jenis").value;
	var cari = document.getElementById("cari").value;
	var departemen = document.getElementById("departemen").value;	
	//var angkatan = document.getElementById("angkatan").value;	
	
	if (cari == "") {
		alert ('Keyword tidak boleh kosong');
		document.getElementById("cari").focus();
		return false;
	}
	
	if (jenis != 'kondisi' && jenis != 'status' && jenis != 'agama' && jenis != 'suku' && jenis != 'darah' && jenis != 'idangkatan'){
		if (cari.length<3){
		 	alert ('Keyword tidak boleh kurang dari 3 karakter');
	 		return false;
		}
	}
	
	parent.cari_alumni_footer.location.href = "alumni_cari_footer.php?departemen="+departemen+"&jenis="+jenis+"&cari="+cari;
}

function focusNext( evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		cari_alumni();
        return false;
    }
    return true;
}
</script>
</head>
<body onload="document.getElementById('cari').focus();" leftmargin="0" topmargin="0">

<table border="0" width="100%" cellpadding="0" cellspacing="0"  >
<!-- TABLE TITLE -->
<tr>
	<td rowspan="2" width="52%">
	<table width = "98%" border="0" >
    <tr>
  		<td width = "16%"><strong>Departemen</strong>
   		<td width = "30%">
        <select name="departemen" id="departemen" onChange="blank()" style="width:140px;" onKeyPress="return focusNext('jenis', event)">
        <?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
				if ($departemen == "")
					$departemen = $value; ?>
    		<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> ><?=$value ?></option>
       	<?php } ?>
        </select></td>
        <!--<td width = "18%"><strong>Angkatan</strong></td>
        <td width = "36%"><select name="angkatan" id="angkatan" onchange="blank()" style="width:140px;">
          <?php $sql_angk="SELECT * FROM jbsakad.angkatan WHERE departemen='$departemen'";
			$res_angk=QueryDb($sql_angk);
			while ($row_angk=@mysqli_fetch_array($res_angk)){	
				if ($angkatan == "")
					$angkatan = $row_angk['replid']; ?>
          <option value="<?=$row_angk['replid'] ?>" <?=StringIsSelected($row_angk['replid'], $angkatan) ?> >
          <?=$row_angk['angkatan'] ?>
          </option>
          <?php } ?>
        </select></td>-->
    </tr>
	<tr> 
    	<td><strong>Pencarian</strong>
      	<td><select name="jenis" id="jenis" onchange="blank()" style="width:140px;" onKeyPress="return focusNext('cari', event)">			
       	<?php foreach($tipe as $value) { ?>
				<option value="<?=$value[0]?>" <?=StringIsSelected($value[0], $jenis)?> ><?=$value[1]?></option>
        <?php 	} ?>
    		</select>         		</td>
	    <td><strong>Keyword</strong></td>
	    <td>
        <?php
	
	if ($jenis == 'darah') {
		$row = ['A', 'O', 'B', 'AB'];
		$jum = 4;
?>				
			<select name="cari" id="cari" style="width:140px;">
<?php 			for ($i=0;$i<$jum;$i++) { 	 ?>
        		<option value="<?=$row[$i]?>" <?=StringIsSelected($row[$i], $cari)?> ><?=$row[$i]?></option>
              	
<?php 			} ?>   
        	</select>
<?php } elseif ($jenis == 'idangkatan') {?>
		    <select name="cari" id="cari" style="width:140px;" onKeyPress="return focusNext(event)">
<?php 		$query = "SELECT replid, angkatan FROM jbsakad.angkatan WHERE departemen = '$departemen' ORDER BY angkatan DESC ";
			$result = QueryDb($query);
			while ($row = mysqli_fetch_row($result)) {	?>
   					<option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $cari)?> ><?=$row[1]?></option>
<?php 			} ?>    
         	</select>
        
<?php 		
	} elseif ($jenis == 'kondisi' || $jenis == 'status' || $jenis == 'agama' || $jenis == 'suku' || $jenis == 'tgllulus') {	
		if ($jenis == 'kondisi') {								
			$query = "SELECT kondisi FROM jbsakad.kondisisiswa ORDER BY kondisi ";			
			$result = QueryDb($query);
		} elseif ($jenis == 'status') {	
			$query = "SELECT status FROM jbsakad.statussiswa ORDER BY status ";
			$result = QueryDb($query);
		} elseif ($jenis == 'tgllulus') {
			$query = "SELECT YEAR(tgllulus) AS tahun FROM jbsakad.alumni WHERE departemen = '$departemen' GROUP BY tahun ORDER BY tahun DESC ";
			$result = QueryDb($query);
		} elseif ($jenis == 'agama') {		
			$query = "SELECT $jenis FROM jbsumum.$jenis ORDER BY urutan";
			$result = QueryDb($query);
		} elseif ($jenis == 'suku') {		
			$query = "SELECT $jenis FROM jbsumum.$jenis ORDER BY $jenis";
			$result = QueryDb($query);
		} 
		
?>		<select name="cari" id="cari" style="width:140px;" onKeyPress="return focusNext(event)">
<?php 	while ($row = mysqli_fetch_row($result)) {	?>
   			<option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $cari)?> ><?=$row[0]?></option>
<?php 		} ?>    
         </select>

<?php }	else { 	 ?>
    	<input type="text" name="cari" id="cari" style="width:140px;" onKeyPress="return focusNext(event)"/>
        
<?php 	} 
	
CloseDb();

?></td>
	</tr>
    </table>	</td>    
    <td width="5%" valign="middle"><a href="#" onclick="cari_alumni()" ><img src="../images/view.png" height="48" width="48" border="0" name="tabel" id="tabel" onmouseover="showhint('Klik untuk melihat hasil pencarian !', this, event, '120px')" /></a></td>
   	<td width = "*" align="right" valign="top">
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pencarian Alumni</font><br />
    <a href="../kelulusan.php" target="content">
        <font size="1" color="#000000"><b>Kenaikan & Kelulusan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Pencarian alumni</b></font></td>     
</tr>
</table>
<!-- Pilih inputan pertama -->
    	
</body>
</html>
<script language="javascript">
	var spryselect = new Spry.Widget.ValidationSelect("departemen");
	var spryselect1 = new Spry.Widget.ValidationSelect("jenis");
	var jenis = document.getElementById("jenis").value;
	if (jenis == 'kondisi' || jenis == 'status' || jenis == 'agama' || jenis == 'suku' || jenis == 'idangkatan' || jenis == 'tgllulus'){ 
		var spryselect1 = new Spry.Widget.ValidationSelect("cari");
	} else {
		var sprytextfield = new Spry.Widget.ValidationTextField("cari");
	}
	//var sprytextfield = new Spry.Widget.ValidationTextField("cari");
	 
</script>