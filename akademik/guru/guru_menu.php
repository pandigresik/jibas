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

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Status Guru</title>
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

function departemen() {		
	var departemen = document.getElementById('departemen').value;
	parent.isi.location.href = "blank_guru.php";
//	parent.isi.location.href = "guru_content.php?departemen="+departemen;
	document.location.href = "guru_menu.php?departemen="+departemen;
}

function tampil(replid) {	
	var departemen = document.getElementById('departemen').value;
	var aktif = 1;	
	parent.isi.location.href = "guru_content.php?id="+replid+"&departemen="+departemen+"&aktif="+aktif;
}


function semua() {
	var departemen = document.getElementById('departemen').value;
	var aktif = 0;
	parent.isi.location.href = "guru_content.php?departemen="+departemen+"&aktif="+aktif;
}

</script>
</head>

<body onload="document.getElementById('departemen').focus()" style="background-color: #f5f5f5;">
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="center"><p><strong>Departemen &nbsp;</strong>
    	<select name="departemen" id="departemen" onchange="departemen()" style="width:50%;">
       <?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
			if ($departemen == "")
				$departemen = $value; ?>
              	<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> ><?=$value ?> </option>
                <?php } ?>
            </select>  <p>
           	<input type="button" name="tampil" value="Tampilkan Semua Guru" class="but" onclick="semua()" style="width:180px;"/>
	</td>
</tr>
</table>
<hr />

<?php 
	OpenDb();
	$sql = "SELECT replid,nama,aktif,kode FROM pelajaran WHERE departemen = '$departemen' ORDER BY nama ASC, aktif DESC";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0) {
?>
<table border="0" width="100%" align="center">
<tr>
	<td><strong>Berdasarkan:</strong></td>
</tr>
<tr>
	<td>
    
	<table class="tab" id="table" border="1" style="border-collapse:collapse; border-width: 1px; border-color: #f5f5f5;" width="100%" align="left" bordercolor="#000000">
    <!-- TABLE CONTENT -->
    
    <tr height="30">    	
    	<td width="15%" class="header" align="center">Kode</td>
        <td width="*" class="header" align="center">Pelajaran</td>
        <td width="12%" class="header" align="center">Status</td>
    </tr>
    
     <?php
		
		$cnt = 0;
		//$aktif = 1;
		while ($row = @mysqli_fetch_array($result)) {
	?>
    <tr height="25">   	
       	<td align="left"><?=$row['kode'] ?></td>
        <td align="left" onclick="tampil(<?=$row['replid']?>)" style="cursor:pointer">
        <u><b><?=$row['nama']?></b></u>
        <!--<a href="guru_content.php?id=<?=$row['replid']?>&departemen=<?=$departemen?>&aktif=<?=$aktif?>" target = "isi" ><?=$row['nama']?></a>--></td>
        <td align="center"><?php if ($row['aktif'] == 1) 
					echo 'Aktif';
				else
					echo 'Tidak Aktif';
			?></td>        
    </tr>
<?php } 
	CloseDb(); 
?>	
	<!-- END TABLE CONTENT -->
	</table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);		
    </script>
	</td>
</tr>
</table>
<?php } else {?>

   
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">
        <?php if ($departemen <> "") {	?>
    		<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br /><br />Tambah data pelajaran pada departemen <?=$departemen?> di menu Pendataan Pelajaran pada bagian Guru & Pelajaran. </b></font>
       	 <?php } else { ?> 
              <font size = "2" color ="red"><b>Belum ada data Departemen.
              <br />Silahkan isi terlebih dahulu di menu Departemen pada bagian Referensi.
              </b></font>
		<?php } ?>
		</td>
	</tr>
	</table>  
	
<?php } ?>


</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
</script>