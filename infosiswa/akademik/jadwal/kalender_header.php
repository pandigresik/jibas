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
require_once('../../include/errorhandler.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/common.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
//require_once('../../library/departemen.php');
require_once("../../include/sessionchecker.php");

if (isset($_REQUEST['departemen']))
	$departemen=$_REQUEST['departemen'];
if (isset($_REQUEST['kalender']))
	$kalender=$_REQUEST['kalender'];
if (isset($_REQUEST['replid']))
	$replid=$_REQUEST['replid'];

$periode = "";
OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kalender Akademik</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function change(cal, dept){	
	var kalender = document.getElementById("kalender").value;
	var departemen=document.getElementById("departemen").value;
	//alert ('kal '+cal+' '+dept);
	if (cal == 0) {		
		document.location.href="kalender_header.php?kalender="+kalender+"&departemen="+departemen;			
	} else { 
		document.location.href="kalender_header.php?kalender="+cal+"&departemen="+dept;	
	}
	parent.footer.location.href="blank_kalender.php";
}

function refresh_change(a,b) {
	var c = a;
	var d = b;
	
	if (a == 0) { 	
		setTimeout("change(0,0)",1);
	} else { 	
		setTimeout("change(c,d)",1);
	}
}

function change_departemen() {
	var departemen=document.getElementById("departemen").value;
	document.location.href = "kalender_header.php?departemen="+departemen;
	parent.footer.location.href="blank_kalender.php";
}

function tampil(){
	var kalender = document.getElementById('kalender').value;
	var departemen = document.getElementById('departemen').value;
	
	if (kalender==""){
		alert ('Kalender Akademik tidak boleh kosong !');	
		document.getElementById('kalender').focus();
		return false;
	} else {			
		parent.footer.location.href="kalender_footer.php?kalender="+kalender+"&departemen="+departemen;
		
	}
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

</script>
</head>
	
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
    <td width="55%">
	<table width="100%" height="100%" border="0">
  	<tr>
        <td width="27%"><strong>Departemen </strong></td>
    	<td width="73%"><select name="departemen" id="departemen" onChange="change_departemen()" style="width:250px"  onkeypress="return focusNext('kalender', event)">
            <?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
			if ($departemen == "")
				$departemen = $value; ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
            <?=$value ?> 
            </option>
            <?php } ?>
            </select>		</td>
    </tr>
    <tr>
    	<td><strong>Kalender&nbsp;Akademik</strong></td>
    	<td><select name="kalender" id="kalender" onchange="change(0)" style="width:250px;" onkeypress="return focusNext('tabel', event)">
          <?php OpenDb();
				$sql_kalender = "SELECT * FROM jbsakad.kalenderakademik where departemen='$departemen' ORDER BY aktif DESC, replid ASC";
				$result_kalender = QueryDb($sql_kalender);
				while($row_kalender = @mysqli_fetch_array($result_kalender)) {
					if ($kalender == "")
						$kalender = $row_kalender['replid'];
					if ($row_kalender['aktif']) 
						$ada = '(Aktif)';
					else 
						$ada = '';
			?>
          <option value="<?=urlencode((string) $row_kalender['replid'])?>" <?=IntIsSelected($row_kalender['replid'], $kalender) ?> >
            <?=$row_kalender['kalender'].' '.$ada?>
          </option>
          <?php } //while	?>
        </select>
    	     	</td>
   </tr>
   <tr>
      	<td><strong>Periode</strong></td>
        <td>
        <?php 	
		if ($kalender <> "" ) {
			OpenDb();
			$sql = "SELECT * FROM jbsakad.tahunajaran t, jbsakad.kalenderakademik k WHERE t.replid=k.idtahunajaran AND k.replid = '".$kalender."'";
			$result = QueryDb($sql);
			$row = mysqli_fetch_array($result);
			$periode = format_tgl($row['tglmulai']).' s/d '.format_tgl($row['tglakhir']);
		} 
		?> 
        <input type="text" name="periode" size="39" value="<?=$periode ?>" readonly class="disabled"/>
        </td>
  	</tr>          
   	</table>    </td>
    <td valign="middle" rowspan="2" width="10%" align="left" ><a href="#" onClick="tampil()"><img src="../images/ico/view.png" height="48" width="48" border="0" name="tabel" id="tabel"  onmouseover="showhint('Klik untuk menampilkan kalender akademik !', this, event, '120px')"/></a></td>
	<td valign="top" align="right" width="35%">
		<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Kalender Akademik</font>   	
	</td>
</tr>
</table>

</body>
</html>

<script language="javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
var spryselect2 = new Spry.Widget.ValidationSelect("kalender");
</script>