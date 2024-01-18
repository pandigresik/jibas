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

//$departemen = -1;
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$proses = -1;
if (isset($_REQUEST['proses']))
	$proses = $_REQUEST['proses'];
$dasar = 1;
if (isset($_REQUEST['dasar']))
	$dasar = $_REQUEST['dasar'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Statistik Penerimaan Siswa Baru</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript">

function tampil_statistik() {
	var departemen = document.getElementById("departemen").value;
	var idproses = document.getElementById("proses").value;
	var iddasar = document.getElementById("dasar").value;	
	parent.header.location.href = "statistik_header.php?departemen="+departemen+"&proses="+idproses+"&dasar="+iddasar;			
	parent.footer.location.href = "statistik_footer.php?departemen="+departemen+"&idproses="+idproses+"&iddasar="+iddasar;
}

function change() {
	var departemen = document.getElementById("departemen").value;	
	var proses = document.getElementById("proses").value;
	var dasar = document.getElementById("dasar").value;
	
	parent.header.location.href = "statistik_header.php?departemen="+departemen+"&proses="+proses+"&dasar="+dasar;			
	parent.footer.location.href="blank_statistik.php";
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

function panggil(elem){
	var lain = new Array('departemen','proses','dasar');
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
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
	<td rowspan="3" width="45%">
    <table width = "98%" border = "0" cellpadding="0" cellspacing="0">
 	<tr>
    	<td align="left" width = "35%"><strong>Departemen</strong>
      	<td width = "*">
        <?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {	?>
        <select name="departemen" id="departemen" onchange="change()" style="width:240px;" onKeyPress="return focusNext('dasar', event)" onfocus="panggil('departemen')">
        	<option value="-1" >(Semua Departemen)</option>    
		<?php
			$sql = "SELECT * FROM jbsakad.departemen where aktif=1 ORDER BY urutan";
			OpenDb();
			$result = QueryDb($sql);
			CloseDb();
			while($row = mysqli_fetch_array($result)) {
			if ($departemen == "")
				$departemen = -1;	
		?>
        	<option value="<?=urlencode((string) $row['departemen'])?>" <?=StringIsSelected($row['departemen'], $departemen) ?>><?=$row['departemen']?></option>
        <?php 	} //while 	?>
     	</select>
<?php 	} else { ?>
        <select name="departemen" id="departemen" onchange="change()" style="width:240px;" onKeyPress="return focusNext('proses', event)" onfocus="panggil('departemen')">
        <?php
			$dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
			if ($departemen == "")
				$departemen = $value; ?>
                <option value="<?=$dep ?>" <?=StringIsSelected($value, $departemen) ?> > 
                  <?=$value ?> 
                  </option>
              <?php } ?>
              </select>
        <?php  }
		
		if ($departemen == -1)  {
			$disable = 'disabled="disabled"';
			$proses = -1;
			$dep = "";
		} else	{
			$disable = "";
			$dep = "AND departemen = '".$departemen."'";
		}
		
		?>
        </td>
    </tr>
    <tr>
    	<td align="left"><strong>Proses Penerimaan</strong></td>    
	 	<td>
      	<select name="proses" id="proses" onchange="change()" <?=$disable?> style="width:240px;" onKeyPress="return focusNext('dasar', event)" onfocus="panggil('proses')">      	
	 	<option value="-1" >(Semua Penerimaan yang Aktif)</option>	
		<?php
      		OpenDb();
			$sql = "SELECT replid,proses FROM prosespenerimaansiswa WHERE aktif = 1 $dep ";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = mysqli_fetch_array($result)) { 
		?>            	
    	<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $proses) ?>><?=$row['proses']?></option>
  		<?php } //while  	?>
		</select>
     	</td>
    </tr>
    <tr>
    	<td align="left" width = "13%"><strong>Berdasarkan</strong>
      	<td>
        <!--<div id="dasarInfo">-->
        <select name="dasar" id="dasar" onchange="change()" style="width:240px;" onKeyPress="return focusNext('tabel', event)" onfocus="panggil('dasar')">
        <?php for ($i=1;$i<=17;$i++) { ?>
        <option value ="<?=$i?>" <?=IntIsSelected($i, $dasar) ?>><?=$kriteria[$i] ?></option>
        <?php  } ?>
        </select>      
		</td> 
	</tr>
	</table>
    </td>
   	<td width="5%" rowspan="2" valign="middle"><a href="#" onclick="tampil_statistik()" ><img src="../images/view.png" height="48" border="0" name="tabel" id="tabel" onmouseover="showhint('Klik untuk menampilkan statistik!', this, event, '130px')"/></a></td>
    <td width="50%" align="right" valign="top"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Statistik Penerimaan Siswa Baru</font><br />
    <a href="../siswa_baru.php" target="content">
      	<font size="1" color="#000000"><b>Penerimaan Siswa Baru</b></font></a>&nbsp>&nbsp 
        <font size="1" color="#000000"><b>Statistik Penerimaan Siswa Baru</b></font>
    </td>    
</tr>
</table>
</td>
</tr>

</table>
</body>
</html>
<script language="javascript">
//var spryselect = new Spry.Widget.ValidationSelect("departemen");
//var spryselect = new Spry.Widget.ValidationSelect("proses");
//var spryselect = new Spry.Widget.ValidationSelect("dasar");
</script>