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

OpenDb();
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['tahunajaran'])) 
	$tahunajaran = $_REQUEST['tahunajaran'];
	
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
	
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pendataan Siswa</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript">
function change_dep() {
	var departemen = document.getElementById("departemen").value;
	parent.header.location.href = "siswa_header.php?departemen="+departemen;
	parent.footer.location.href = "blank_siswa.php";
	
}

function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var tingkat = document.getElementById("tingkat").value;	
	
	parent.header.location.href = "siswa_header.php?tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&departemen="+departemen;
	parent.footer.location.href = "blank_siswa.php";
}

function change_kelas() {	
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var tingkat = document.getElementById("tingkat").value;	
	var kelas = document.getElementById("kelas").value;	
	parent.header.location.href = "siswa_header.php?tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&departemen="+departemen+"&kelas="+kelas;
	parent.footer.location.href = "blank_siswa.php";
}

function show_siswa() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var tingkat = document.getElementById("tingkat").value;	
	var kelas = document.getElementById("kelas").value;
	
	if (kelas==""){
		alert ('Kelas tidak boleh kosong');
		return false;
	}	
	parent.footer.location.href = "siswa_content.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas;
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel') {
			show_siswa();
		} 
		return false;
    } 
    return true;
}

</script>
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">

<table border="0" width="100%" cellpadding="0" cellspacing="0" >
<!-- TABLE TITLE -->
<tr>
	<td rowspan="2" width="42%">
	<table width = "100%" border = "0">
    <tr>
		<td width = "25%"><strong>Departemen</strong>
    	<td width="*">
      	<select name="departemen" id="departemen" onchange="change_dep()" style="width:260px;" onKeyPress="return focusNext('tingkat', event)">
        <?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
			if ($departemen == "")
				$departemen = $value; ?>
       		<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> ><?=$value ?> 
            </option>
       	<?php } ?>
        </select>		</td>
	</tr>
 	<tr>
    	<td><strong>Tahun Ajaran</strong>
   	  	<td>
		<?php  $sql = "SELECT replid,tahunajaran FROM tahunajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY replid DESC";
			$result = QueryDb($sql);
			$row = @mysqli_fetch_array($result);	
			$tahunajaran = $row['replid']; ?>
        	<input type="text" name="tahun" id="tahun" size="20" readonly class="disabled" value="<?=$row['tahunajaran']?>" style="width:250px;"/>
        	<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$row['replid']?>">       	</td>
 	</tr>
    <tr>
    	<td><strong>Kelas</strong>
        <td><select name="tingkat" id="tingkat" onchange="change_tingkat()" style="width:55px;" onKeyPress="return focusNext('kelas', event)">
 		 <?php $sql = "SELECT replid,tingkat FROM tingkat where departemen='$departemen' AND aktif = 1 ORDER BY urutan";
			$result = QueryDb($sql);
			while ($row = @mysqli_fetch_array($result)) {
			if ($tingkat == "") 
				$tingkat = $row['replid'];	?>
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tingkat)?> ><?=$row['tingkat']?></option>
    	<?php }	?>
    		</select> 
            <select name="kelas" id="kelas" onchange="change_kelas()" style="width:200px;" onKeyPress="return focusNext('tabel', event)">
        
		<?php $sql = "SELECT replid, kelas, kapasitas FROM kelas where idtingkat='$tingkat' AND idtahunajaran='$tahunajaran' AND aktif = 1 ORDER BY kelas";
			$result = QueryDb($sql);
			while ($row = @mysqli_fetch_array($result)) {
				if ($kelas == "")
					$kelas = $row['replid'];
				$sql1 = "SELECT COUNT(*) FROM siswa WHERE idkelas = '".$row[0]."' AND aktif = 1";				
				$result1 = QueryDb($sql1);
				$row1 = @mysqli_fetch_row($result1); 				
		?>
        	<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $kelas)?> >
            <?=$row['kelas'].', kapasitas: '.$row['kapasitas'].', terisi: '.$row1[0]?>
            </option>
       	<?php } ?>
            </select>     	</td>
	</tr>
    </table>    </td>    
    <td valign="middle"><a href="#" onclick="show_siswa()" ><img src="../images/view.png" height="48" width="48" border="0" name="tabel" id="tabel" onMouseOver="showhint('Klik untuk menampilkan daftar siswa!', this, event, '120px')"/></a></td>  
    <td width="50%" colspan="2" align="right" valign="top"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pendataan Siswa</font>    	
	<br />
    <a href="../siswa.php" target="content">
    <font size="1" color="#000000"><b>Kesiswaan</b></font></a>&nbsp>&nbsp
    <font size="1" color="#000000"><b>Pendataan Siswa</b></font></td>
</tr>
</table>
</body>
</html>
<script language="javascript">
	var spryselect = new Spry.Widget.ValidationSelect("departemen");
	var spryselect1 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect2 = new Spry.Widget.ValidationSelect("kelas");
</script>
<?php
CloseDb();
?>