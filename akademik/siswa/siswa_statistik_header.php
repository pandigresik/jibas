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

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$angkatan = -1;
if (isset($_REQUEST['angkatan']))
	$angkatan = $_REQUEST['angkatan'];
$iddasar = 1;
if (isset($_REQUEST['iddasar']))
	$iddasar = $_REQUEST['iddasar'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pindah Kelas</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/ajax.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript">
function change_departemen() {

	var departemen = document.getElementById("departemen").value;
	var angkatan = document.getElementById("angkatan").value;
	var iddasar = document.getElementById("iddasar").value;		
	document.location.href = "siswa_statistik_header.php?departemen="+departemen+"&angkatan="+angkatan+"&iddasar="+iddasar;	
	parent.siswa_statistik_footer.location.href = "blank_statistik.php";
}

function tampil_statistik() {
	var departemen = document.getElementById("departemen").value;
	var idangkatan = document.getElementById("angkatan").value;
	var iddasar = document.getElementById("iddasar").value;	
	parent.siswa_statistik_footer.location.href = "siswa_statistik_footer.php?departemen="+departemen+"&idangkatan="+idangkatan+"&iddasar="+iddasar;
}

function blank() {
	var departemen = document.getElementById("departemen").value;
	var angkatan = document.getElementById("angkatan").value;
	var iddasar = document.getElementById("iddasar").value;		
	document.location.href = "siswa_statistik_header.php?departemen="+departemen+"&angkatan="+angkatan+"&iddasar="+iddasar;	
	parent.siswa_statistik_footer.location.href="blank_statistik.php";
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel')
			tampil_statistik();
		return false;
    } 
    return true;
}
</script>
</head>
	
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<table border="0" width="100%" cellpadding="0" cellspacing="0"  >
<!-- TABLE TITLE -->
<tr>
	<td rowspan="2" width="45%">
    <table width = "98%" border = "0" cellpadding="0" cellspacing="0">
    <tr>
    	<td width="25%"><strong>Departemen</strong>
      	<td width="*">
			<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) {	?>
		   	<select name="departemen" id="departemen" onchange="change_departemen()" style="width:240px;" onKeyPress="return focusNext('iddasar', event)">
        		<option value="-1" >(Semua Departemen)</option>    
			<?php
				$sql = "SELECT * FROM jbsakad.departemen where aktif=1 ORDER BY urutan";
				OpenDb();
				$result = QueryDb($sql);
				CloseDb();
			
				//$departemen = "";	
				while($row = mysqli_fetch_array($result)) {
					if ($departemen == "")
						$departemen = "-1";
						//$departemen = $row['departemen'];
			?>
            	<option value="<?=urlencode((string) $row['departemen'])?>" <?=StringIsSelected($row['departemen'], $departemen) ?>><?=$row['departemen']?></option>
<?php
				} //while
			?>
   	  		</select>
<?php } else {	?>
			<select name="departemen" id="departemen" onchange="change_departemen()" style="width:240px;" onKeyPress="return focusNext('angkatan', event)">
        	<?php
				$dep = getDepartemen(SI_USER_ACCESS());    
				foreach($dep as $value) {
				if ($departemen == "")
					$departemen = $value; ?>
                <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
                  <?=$value ?> 
                  </option>
            <?php } ?>
            </select>
			<?php }	
			
			if ($departemen == -1)  {
				$disable = 'disabled="disabled"';
				$angkatan = -1;
				$dep = "";
			} else	{
				$disable = "";
				$dep = "AND departemen = '".$departemen."'";
			}
			?>	  	</td>
    </tr>
    <tr>
       	<td width = "13%" align="left"><strong>Angkatan</strong></td>      
      	<td><div id="angkatanInfo">        	
           	<select name="angkatan" id="angkatan" onchange="blank()" <?=$disable?> style="width:240px;" onKeyPress="return focusNext('iddasar', event)">
        	<option value="-1" >(Semua Angkatan yang Aktif)</option>
        	<?php 	OpenDb();
				$sql_angkatan = "SELECT replid,angkatan FROM jbsakad.angkatan where aktif = 1 AND departemen = '$departemen' ORDER BY replid DESC";
				$result_angkatan = QueryDb($sql_angkatan);
				while ($row_angkatan = mysqli_fetch_array($result_angkatan)) {
			?>
        	<option value="<?=urlencode((string) $row_angkatan['replid'])?>" <?=IntIsSelected($row_angkatan['replid'], $angkatan) ?>><?=$row_angkatan['angkatan']?></option>
        	<?php
  				} //while
				CloseDb();
			?>
      		</select></div></td>
 	</tr>
  	<tr>
   		<td align="left" width = "13%"><strong>Berdasarkan</strong>
      	<td>
        <!--<div id="dasarInfo">-->
        <select name="iddasar" id="iddasar" onchange="blank()" style="width:240px;" onKeyPress="return focusNext('tabel', event)">
        <?php for ($i=1;$i<=17;$i++) { ?>
        <option value ="<?=$i?>" <?=IntIsSelected($i, $iddasar) ?>><?=$kriteria[$i] ?></option>
        <?php  } ?>
        </select>		</td> 
	</tr>
	</table>    
    </td>
    <td width="5%" valign="middle">
    <a href="#" onclick="tampil_statistik()" ><img src="../images/view.png" height="48" border="0" name="tabel" id="tabel" onmouseover="showhint('Klik untuk menampilkan statistik !', this, event, '135px')"/></a></div></td>
    <td width="50%" align="right" valign="top">
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Statistik Kesiswaan</font><br />
    <a href="../siswa.php" target="content">
      <font size="1" color="#000000"><b>Kesiswaan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Statistik Kesiswaan</b></font>        </td>     
</tr>
</table>
</td>
</tr>
</table>
 
</body>
</html>
<script language="javascript">
var spryselect = new Spry.Widget.ValidationSelect("departemen");
var spryselect = new Spry.Widget.ValidationSelect("angkatan");
var spryselect = new Spry.Widget.ValidationSelect("iddasar");
</script>