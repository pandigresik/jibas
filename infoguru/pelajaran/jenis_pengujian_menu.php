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
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function refresh() {	
	document.location.reload();
}

function tampil() {		
	var departemen = document.getElementById('departemen').value;
	document.location.href = "jenis_pengujian_menu.php?departemen="+departemen;
	parent.jenis_pengujian_content.location.href = "blank_pengujian.php";
}
function pilih(id,nama_dep,nama_pel) {		
	parent.jenis_pengujian_content.location.href = "jenis_pengujian_content.php?id="+id+"&nama_dep="+nama_dep+"&nama_pel="+nama_pel;
}
</script>
</head>

<body>

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" align="left">
	<!-- TABLE LINK -->
	<tr>
    <td align="left" width="100%">
      <p><strong>Departemen&nbsp;</strong>
            <select name="departemen" id="departemen" onchange="tampil()" style="width:50%;">
         <?php $dep = getDepartemen(SI_USER_ACCESS());    
	foreach($dep as $value) {
		if ($departemen == "")
			$departemen = $value; ?>
              <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
                <?=$value ?> 
                </option>
                <?php } ?>
            </select></td>
    <td>
        <!--<input type="button" name="tampil" value="Tampilkan Semua Guru" class="but"/>   -->
          
    </td>    
    </tr>
	</table>  <br />
        <br />	
<?php OpenDb();
	$sql = "SELECT pel.replid as replid,pel.nama as nama,pel.departemen as departemen FROM pelajaran pel,guru g WHERE pel.departemen = '$departemen' AND pel.aktif=1 AND g.nip='".SI_USER_ID()."' AND g.idpelajaran=pel.replid ORDER BY pel.nama";
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result)>0){
?>
	<table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left" bordercolor="#000000">
    <!-- TABLE CONTENT -->
    
    <tr height="30">    	
    	<td width="4%" class="header" align="center">No</td>
        <td width="96%" class="header" align="center">Pelajaran</td>
    </tr>
    
     <?php
		
		$cnt = 0;
		while ($row = @mysqli_fetch_array($result)) {
	?>
    <tr height="25" onClick="pilih('<?=$row[0]?>','<?=$row[2]?>','<?=$row[1]?>')" style="cursor:pointer;">   	
       	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row[1]?></td>
            
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
	&nbsp;
   
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">
        <?php if (isset($departemen)) {	?>
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
	</td>
</tr>
<!-- END TABLE CENTER -->    
</table>    

    
<?php CloseDb() ?>    
  

</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
</script>