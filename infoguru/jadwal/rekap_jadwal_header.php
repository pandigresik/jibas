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
	$departemen=$_REQUEST['departemen'];
$tahunajaran = "";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran=$_REQUEST['tahunajaran'];
if (isset($_REQUEST['info_jadwal']))
	$info_jadwal=$_REQUEST['info_jadwal'];

OpenDb();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rekap Jadwal Guru</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function change_info_jadwal(){	
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var info_jadwal = document.getElementById('info_jadwal').value;
	document.location.href="rekap_jadwal_header.php?info_jadwal="+info_jadwal+"&tahunajaran="+tahunajaran+"&departemen="+departemen;
	parent.footer.location.href="blank_rekapjadwal.php";
}

function change_departemen() {
	var departemen=document.getElementById("departemen").value;
	document.location.href = "rekap_jadwal_header.php?departemen="+departemen;
	parent.footer.location.href="blank_rekapjadwal.php";
}

function change_tahunajaran() {
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	document.location.href = "rekap_jadwal_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran;
	parent.footer.location.href="blank_rekapjadwal.php";
}

function tampil(){
	var info_jadwal = document.getElementById('info_jadwal').value;	
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	
	if (tahunajaran==""){
		alert ('Tahun Ajaran tidak boleh kosong !');
		document.getElementById('tahunajaran').focus();
		return false;
	} else if (info_jadwal==""){
		alert ('Info Jadwal tidak boleh kosong !');	
		document.getElementById('info_jadwal').focus();
		return false;
	} else {	
		parent.footer.location.href="rekap_jadwal_footer.php?info="+info_jadwal;
	}
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
</script>
</head>

<body topmargin="0" leftmargin="0">
<form action="rekap_jadwal_footer.php" method="post" id="inputForm" name="inputForm" >
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
    <td width="40%">
	<table width = "90%" border = "0">
    <tr>
    	<td><strong>Departemen</strong></td>
        <td><select name="departemen" id="departemen" onChange="change_departemen()" style="width:200px;" onkeypress="return focusNext('tahunajaran', event)">
          <?php $dep = getDepartemen(SI_USER_ACCESS());    
		foreach($dep as $value) {
		if ($departemen == "")
			$departemen = $value; ?>
          <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
          <?=$value ?>
          </option>
          <?php } ?>
        </select>
        </td>
    </tr>
    <tr>
    	<td><strong>Tahun Ajaran </strong></td>
    	<td>       
        <select name="tahunajaran" id="tahunajaran" onChange="change_tahunajaran()" style="width:200px;" onkeypress="return focusNext('info_jadwal', event)">
   		 	<?php
			OpenDb();
			$sql = "SELECT replid,tahunajaran,aktif FROM jbsakad.tahunajaran where departemen='$departemen' ORDER BY aktif DESC, replid DESC";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
				if ($tahunajaran == "") 
					$tahunajaran = $row['replid'];
				if ($row['aktif']) 
					$ada = '(A)';
				else 
					$ada = '';			 
			?>
            
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tahunajaran)?> ><?=$row['tahunajaran'].' '.$ada?></option>
    		<?php
			}
    		?>
    	</select>        </td>
    </tr>
   	<tr>
        <td><strong>Info Jadwal</strong></td>
        <td colspan="3"><select name="info_jadwal" id="info_jadwal" onChange="change_info_jadwal()" style="width:200px">
          <?php 	OpenDb();
            $sql_info_jadwal="SELECT i.replid, i.deskripsi, i.aktif FROM jbsakad.infojadwal i, tahunajaran a WHERE i.idtahunajaran = a.replid AND a.departemen = '$departemen' AND i.idtahunajaran = '$tahunajaran' ORDER BY i.aktif DESC";						            
			$result_info_jadwal=QueryDb($sql_info_jadwal);
            while ($row_info_jadwal=@mysqli_fetch_array($result_info_jadwal)){
                if ($info_jadwal=="")
                    $info_jadwal=$row_info_jadwal['replid'];
                if ($row_info_jadwal['aktif']) 
                    $ada = '(A)';
                else 
                    $ada = '';			 
        ?>
          <option value="<?=$row_info_jadwal['replid']?>" <?=StringIsSelected($row_info_jadwal['replid'],$info_jadwal)?>>
          <?=$row_info_jadwal['deskripsi'].' '.$ada?>
          </option>
          <?php  } ?>
        </select>
        </td>
   	</tr>
    </table>
    </td>
	<td valign="middle" rowspan="2" width="*" >
       	<a href="#" onClick="tampil()"><img src="../images/ico/view.png" height="48" width="48" border="0" name="tabel" id="tabel"  onmouseover="showhint('Klik untuk menampilkan rekap jadwal guru !', this, event, '120px')"/></a>   	</td>
    <td valign="top" align="right" width="50%">
		<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Rekap Jadwal Guru</font><br />
   		<a href="../jadwal.php" target="framecenter">
        <font size="1" color="#000000"><b>Jadwal</b></font></a>&nbsp>&nbsp 
		<font size="1" color="#000000"><b>Rekap Jadwal Guru</b></font><a> 	
	</td>
</tr>


</table>
</form>
</body>
</html>
<script language="javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
var spryselect2 = new Spry.Widget.ValidationSelect("tahunajaran");
var spryselect3 = new Spry.Widget.ValidationSelect("info_jadwal");
</script>