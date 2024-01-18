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

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if (isset($_REQUEST['replid']))
	$replid = $_REQUEST['replid'];

if ($op=="xm8r389xemx23xb2378e23"){	
	OpenDb();
	$sql_hapus_jam="DELETE FROM jbsakad.jam WHERE replid='$replid'";	
	QueryDb($sql_hapus_jam);
	CloseDb();
}

OpenDb();	
?>
<html>
<head>
<title>Definisi Jam Belajar</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script type="text/javascript" language="text/javascript" src="../script/tables.js"></script>
<script type="text/javascript" language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function change_departemen(){
	var departemen = document.getElementById('departemen').value;
	document.location.href="definisi_jam.php?departemen="+departemen;
}

function cetak() {
	var departemen = document.getElementById('departemen').value;
	newWindow('jam_cetak.php?departemen='+departemen, 'CetakJam','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function tambah() {
	var departemen = document.getElementById('departemen').value;
	newWindow('tambah_jam.php?departemen='+departemen, 'TambahJam','350','300','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh(){
	var departemen = document.getElementById('departemen').value;
	document.location.href="definisi_jam.php?departemen="+departemen;
}

function hapusjam(replid){
	var departemen = document.getElementById('departemen').value;
	if (confirm('Apakah Anda yakin akan menghapus jam ini?')){
		if (confirm('Menghapus jam akan membuat urutan jam menjadi tidak beraturan..\nAnda yakin akan menghapus jam ini?')){
			document.location.href="definisi_jam.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen;
		}
	}
}
</script>
</head>

<body>
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/ico/b_jam.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
<td align="left" valign="top">
	<table border="0"width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
        <td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Definisi Jam Belajar</font></td>
    </tr>
    <tr>
        <td align="right"><a href="../jadwal.php" target="content">
          <font size="1" face="Verdana" color="#000000"><b>Jadwal</b></font></a>&nbsp>&nbsp <font size="1" face="Verdana" color="#000000"><b>Definisi Jam Belajar</b></font>
        </td>
    </tr>
    <tr>
      	<td align="left">&nbsp;</td>
    </tr>
	</table>
	<br /><br />
      
    <table border="0" width="95%" align="center">
    <!-- TABLE LINK -->
    <tr>
    	<td align="right" width="35%">
		<strong>Departemen</strong>    
        <select name="departemen" id="departemen" onChange="change_departemen()" >
        <?php $dep = getDepartemen(SI_USER_ACCESS());    
            foreach($dep as $value) {
            if ($departemen == "")
                $departemen = $value; ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
            <?=$value ?> 
            </option>
        <?php } CloseDb(); ?>
        </select>
		</td>
		<?php
        OpenDb();
        $sql_jam="SELECT replid, jamke, HOUR(jam1) As jammulai, MINUTE(jam1) As menitmulai, HOUR(jam2) As jamakhir, MINUTE(jam2) As menitakhir FROM jbsakad.jam WHERE departemen='$departemen' ORDER BY jamke ASC";
        $result_jam=QueryDb($sql_jam);
            if (mysqli_num_rows($result_jam) > 0){
        ?>
        <td align="right">
		<a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
		<a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')" />&nbsp;Cetak</a>&nbsp;&nbsp;
        <?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
	    <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')"/>&nbsp;Tambah Jam Belajar</a>
		<?php //} ?>    
		</td></tr>	
	</table><br>
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000" />
    <!-- TABLE CONTENT -->
    <tr height="30" class="header" align="center">	
		<td width="20%">Jam ke</td>
	  	<td width="*">Waktu</td>
	  	<?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <td width="*"></td>
        <?php // }?>
	</tr>
	<?php 
		while ($row_jam=@mysqli_fetch_row($result_jam)){
			if ((int)$row_jam[2]<10) 
				$jammulai="0".$row_jam[2]; 
			else  
				$jammulai=$row_jam[2]; 
					
			if ((int)$row_jam[3]<10) 
				$menitmulai="0".$row_jam[3]; 
			else  
				$menitmulai=$row_jam[3]; 
				
			if ((int)$row_jam[4]<10) 
				$jamakhir="0".$row_jam[4]; 
			else  
				$jamakhir=$row_jam[4]; 
					
			if ((int)$row_jam[5]<10) 
				$menitakhir="0".$row_jam[5]; 
			else  
				$menitakhir=$row_jam[5]; 
				
                    
	?> 
	<tr height="25">
		<td align="center"><?=$row_jam[1] ?> </td>
		<td><?=$jammulai.":".$menitmulai ?> - <?=$jamakhir.":".$menitakhir ?></td>
        <?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>
        <td align="center" > 
			<a href="#" onClick="newWindow('ubah_jam.php?replid=<?=$row_jam[0] ?>','UbahJamBelajar','350','300','resizable=1,scrollbars=1,status=0,toolbar=0')" onMouseOver="showhint('Ubah Jam Belajar!', this, event, '75px')">        
			<img src="../images/ico/ubah.png" border="0" ></a>&nbsp;
			<a href="JavaScript:hapusjam(<?=$row_jam[0]?>)" onMouseOver="showhint('Hapus Jam Belajar!', this, event, '75px')">
			<img src="../images/ico/hapus.png" border="0"></a>					
        
       	</td>
		<?php //} ?>     
	</tr>
<?php }	CloseDb();	?> 
	</table>    
	 <script language="javascript">
		Tables('table', 1, 0);
	</script>
	<!-- ============================END MAIN VIEW============================ -->
    </td></tr></table>
   
<?php } else { ?> 
<td width = "65%"></td>
</tr>
</table>
<table width="95%" border="0" align="center">          
<tr>
	<td width="18%"></td>
	<td><hr style="border-style:dotted" color="#000000" /></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">
     <?php if (isset($departemen)) {	?>	
        <font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
        <?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        <?php //} ?>
        </b></font>
<?php } else { ?> 
          <font size = "2" color ="red"><b>Belum ada data Departemen.
          <br />Silahkan isi terlebih dahulu di menu Departemen pada bagian Referensi.
          </b></font>
	<?php } ?>
	</td>
</tr>
</table>  
<?php } ?> 
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table> 
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
</script>