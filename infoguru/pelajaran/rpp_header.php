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
require_once("../include/sessionchecker.php");

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$semester = "";
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
$pelajaran = "";
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];
$tingkat = "";
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
OpenDb();	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Rencana Program Pembelajaran]</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../menu.js"></script>
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

function change_dep() {
	var departemen = document.getElementById("departemen").value;
	var semester = document.getElementById("semester").value;
	var pelajaran = document.getElementById("pelajaran").value;
	parent.header.location.href = "rpp_header.php?departemen="+departemen+"&semester="+semester+"&pelajaran="+pelajaran;
	parent.footer.location.href = "blank_rpp.php";
}

function change() {
	var departemen = document.getElementById("departemen").value;	
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;
		
	parent.header.location.href = "rpp_header.php?departemen="+departemen+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran;
	parent.footer.location.href = "blank_rpp.php";
	
}
function show() {
	//alert ('gimana?');	
	var departemen = document.getElementById("departemen").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;	
	var pelajaran = document.getElementById("pelajaran").value;
	
	if (departemen.length == 0) {
		alert ('Departemen tidak boleh kosong !');
		return false;
	}
	if (semester.length == 0) {
		alert ('Semester tidak boleh kosong !');
		return false;
	}
	if (tingkat.length == 0) {
		alert ('Tingkat tidak boleh kosong !');
		return false;
	}
	if (pelajaran.length == 0) {
		alert ('Pelajaran tidak boleh kosong !');
		return false;
	}	
	//alert ('udah masuk nih'+semester+' tingkat '+tingkat+ ' pelajaran '+pelajaran);
	parent.footer.location.href = "rpp_footer.php?departemen="+departemen+"&tingkat="+tingkat+"&semester="+semester+"&pelajaran="+pelajaran;
}

</script>
</head>
	
<body topmargin="0" leftmargin="0">

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
    <td rowspan="3" width="55%">
	<table width = "98%" border = "0">
    <tr>
      	<td width = "18%"><strong>Departemen</strong>
      	<td width="*"><select name="departemen" id="departemen" onchange="change_dep()" style="width:100px">
          <?php 	$dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
				if ($departemen == "")
					$departemen = $value; ?>
          <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
          <?=$value ?>
          </option>
          <?php } ?>
        </select></td>
        <td width="15%"><strong>Semester </strong></td>
	    <td width="*">
        <select name="semester" id="semester" onchange="change()" style="width:200px">
   		 	<?php
			OpenDb();
			$sql = "SELECT replid,semester,aktif FROM semester where departemen='$departemen' ORDER BY aktif DESC";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
			if ($semester == "") 
				$semester = $row['replid'];
			$ada = "";
			if ($row['aktif'])
				$ada = "(Aktif)";
						 
			?>            
    			<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $semester)?> ><?=$row['semester']." ".$ada?></option>                 
    		<?php }	?>
    	</select>        </td> 
  	</tr>
	<tr>
    	<td align="left"><strong>Tingkat</strong>
       	<td>
        <select name="tingkat" id="tingkat" onchange="change()" style="width:100px">
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
        </select>        </td>
        <td align="left"><strong>Pelajaran</strong></td>
      	<td>
        <!--edited -->
        	<select name="pelajaran" id="pelajaran" onchange="change()" style="width:200px">
   		<?php
			OpenDb();
			$sql = "SELECT pel.replid as replid,pel.nama as nama FROM pelajaran pel,guru g WHERE pel.departemen = '$departemen' AND pel.aktif=1 AND g.nip='".SI_USER_ID()."' AND g.idpelajaran=pel.replid ORDER BY pel.nama";
					$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
			if ($pelajaran == "") 				
				$pelajaran = $row['replid'];			
			?>
            
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $pelajaran)?> ><?=$row['nama']?></option>
                  
    		<?php
			}
    		?>
    		</select>
            
              <!--edited -->
            </td>   
    </tr>
    </table>	</td>
	<td width="*" rowspan="3" valign="middle" align="left"><a href="#" onclick="show()"><img src="../images/ico/view.png" name="tabel" width="48" height="48" border="0" id="tabel" onmouseover="showhint('Klik untuk menampilkan data rencana program pembelajaran!', this, event, '150px')"/></a></td>
    <td width="40%" align="right" valign="top"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Rencana Program Pembelajaran</font><br />
    	<a href="../pelajaran.php" target="framecenter">
        <font size="1" color="#000000"><b>Pelajaran</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Rencana Program Pembelajaran</b></font>   	</td>
</tr>
</table>
</body>
</html>