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
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../cek.php');

$departemen="";
if (isset($_REQUEST['departemen']))
	$departemen=$_REQUEST['departemen'];
	
$title = "Sekolah";
if ($departemen=='yayasan')
	$title = "";

$op = $_REQUEST['op'];
if ($op == "delheader") 
{
	OpenDb();
	$sql = "SELECT foto FROM jbsumum.identitas WHERE departemen='$departemen'";
	$result = QueryDb($sql);
	$row = @mysqli_fetch_row($result);
	if ($row[0] != '')
		$sql = "UPDATE jbsumum.identitas SET nama=NULL, situs=NULL, email=NULL, alamat1=NULL, 
					   alamat2=NULL, telp1=NULL, telp2=NULL, telp3=NULL, telp4=NULL, fax1=NULL, fax2=NULL 
				 WHERE departemen = '".$departemen."'";
	else
		$sql = "DELETE FROM jbsumum.identitas WHERE departemen = '".$departemen."'";
	QueryDb($sql);		
	CloseDb();		
}

if ($op == "dellogo") 
{
	OpenDb();
	$sql = "SELECT nama FROM jbsumum.identitas WHERE departemen='$departemen'";
	$result = QueryDb($sql);
	$row = @mysqli_fetch_row($result);
	if ($row[0] != '')
		$sql = "UPDATE jbsumum.identitas SET foto=NULL WHERE departemen = '".$departemen."'";
	else
		$sql = "DELETE FROM jbsumum.identitas WHERE departemen = '".$departemen."'";
	QueryDb($sql);		
	CloseDb();		
}

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function tambah_logo() {
	var departemen=document.getElementById('departemen').value;
	newWindow('logo2.php?departemen='+departemen, 'InputLogoSekolah','550','305','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function tambah() {
	var departemen=document.getElementById('departemen').value;
	newWindow('identitas_add.php?departemen='+departemen, 'InputIdentitasSekolah','675','430','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function getfresh() {
	var departemen=document.getElementById('departemen').value;
	document.location.href="identitas.php?departemen="+departemen;
}

function edit() {
	var departemen=document.getElementById('departemen').value;
	newWindow('identitas_edit.php?departemen='+departemen, 'UbahIdentitasSekolah','675','430','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(bagian) {
	var departemen=document.getElementById('departemen').value;
	if (bagian=='header'){
		if (confirm("Apakah anda yakin akan menghapus identitas sekolah ini?"))
			document.location.href = "identitas.php?op=delheader&departemen="+departemen;
	} else if (bagian=='logo'){
		if (confirm("Apakah anda yakin akan menghapus identitas sekolah ini?"))
			document.location.href = "identitas.php?op=dellogo&departemen="+departemen;
	}
}
function chg_dep(){
	var departemen=document.getElementById('departemen').value;
	document.location.href = "identitas.php?departemen="+departemen;
}
function cetak(){
	var departemen=document.getElementById('departemen').value;
	newWindow('kop_cetak.php?departemen='+departemen, 'CetakHeader','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
</head>

<body>

<table border="0" width="100%" height="100%" background="../images/ico/Hometrans.png" style="margin:0;padding:0;background-repeat:no-repeat;">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
  <td align="left" valign="top">

	<table border="0"width="95%" align="center">
    <tr>
        <td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Identitas Sekolah</font></td>
    </tr>
    <tr>
        <td align="right"><a href="../referensi.php" target="content">
          <font size="1" face="Verdana" color="#000000"><b>Referensi</b></font></a>&nbsp>&nbsp <font size="1" face="Verdana" color="#000000"><b>Identitas Sekolah</b></font>
        </td>
    </tr>
     <tr>
      <td align="left">&nbsp;</td>
      </tr>
	</table>
	<br /><br />
    <table border="0" cellspacing="0" cellpadding="0" width="95%" align="center">
		<tr>
			<td height="50" valign="top">
				<strong>Departemen : </strong>
				<select name="departemen" id="departemen" onchange="chg_dep()">
					<option value="yayasan" <?=StringIsSelected($departemen,'yayasan')?>>Umum</option>
					<?php
					$res = QueryDb("SELECT departemen FROM departemen WHERE aktif=1 ORDER BY urutan");
					while ($r = @mysqli_fetch_array($res)){
					if ($departemen=="")
						$departemen=$r['departemen'];	
					?>
					<option value="<?=$r['departemen']?>" <?=StringIsSelected($departemen,$r['departemen'])?>><?=$r['departemen']?></option>							
					<?php
					}
					?>
				</select>
			</td>
			<td align="right"><a href="javascript:cetak()"><img border="0" src="../images/ico/print.png" />&nbsp;Cetak KOP Surat</a></td>
		</tr>
	</table><br>
	<?php
	$replid = 0;
	$foto = 0;
	$nama = 0;
	$sql="SELECT * FROM jbsumum.identitas WHERE departemen='$departemen' ORDER BY replid DESC LIMIT 1";
	$result=QueryDb($sql);

	$row=@mysqli_fetch_array($result);
	if (mysqli_num_rows($result) > 0) {
		$replid = $row['replid'];
	}
	?>
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
    <tr height="30">
    	<td width="20%" class="header" align="center">Logo <?=$title?></td>        
        <td width="*" class="header" align="center">Header</td>               
    </tr>

    <tr height="100">
  	<?php 	
		if ($row['foto'] == "") {
			$foto = 0;
	?>	
    	<td align="center" rowspan="2"> 
		<font size = "2" color ="red"><b>Klik&nbsp;<a href="JavaScript:tambah_logo()" >		
    	<font size = "2" color ="green">di sini</font></a>&nbsp;untuk memasukkan logo <?=$title?>.</b></font>
 	    	  
        </td>
	<?php } else { 
			$foto = $row['replid'];
	?> 
    	<td align="center"><img src="../library/gambar.php?replid=<?=$replid?>&table=jbsumum.identitas" border="0"/></td>
    <?php } ?>
    <?php 	
		
	
		if ($row['nama'] != "") {	
	?>
        <td align="left" valign="top">
        	<font size="6"><strong><?=$row['nama']?></strong></font><br />
        	<font size="2"><strong>
    	<?php 	if ($row['alamat2'] <> "" && $row['alamat1'] <> "")
            	echo "Lokasi 1: ";
		  	if ($row['alamat1'] != "") 
				echo $row['alamat1'];
			if ($row['telp1'] != "" || $row['telp2'] != "") 
				echo "<br>Telp. ";	
			if ($row['telp1'] != "" ) 
				echo $row['telp1'];	
			if ($row['telp1'] != "" && $row['telp2'] != "") 
					echo ", ";
			if ($row['telp2'] != "" ) 
				echo $row['telp2'];			
			if ($row['fax1'] != "" ) 
				echo "&nbsp;&nbsp;Fax. ".$row['fax1']."&nbsp;&nbsp;";
			
			if ($row['alamat2'] <> "" && $row['alamat1'] <> "") {
				echo "<br>";
            	echo "Lokasi 2: ";
				echo $row['alamat2'];
				
				if ($row['telp3'] != "" || $row['telp4'] != "")
					echo "<br>Telp. ";	
				if ($row['telp3'] != "" ) 
					echo $row['telp3'];
				if ($row['telp3'] != "" && $row['telp4'] != "") 
					echo ", ";
				if ($row['telp4'] != "" ) 
					echo $row['telp4'];				
				if ($row['fax2'] != "" ) 
					echo "&nbsp;&nbsp;Fax. ".$row['fax2'];	
			}
			if ($row['situs'] != "" || $row['email'] != "")
				echo "<br>";
			if ($row['situs'] != "" ) 
				echo "Website: ".$row['situs']."&nbsp;&nbsp;";
			if ($row['email'] != "" ) 
				echo "Email: ".$row['email'];
			
		?>
            </strong></font>
        </td>  
    <?php } else { 
			?>    
    	<td align="center" rowspan="2"><font size = "2" color ="red"><b>Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk memasukkan data.</b></font>
        </td> 
    <?php } ?>   
    </tr>			 
<?php 
	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>         
    <tr height="25"> 
	<?php 	if ($row['foto'] !="") {   	?>
		<td align="center">
            <a href="JavaScript:tambah_logo()"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Logo Sekolah!', this, event, '75px')" /></a>&nbsp;
            <a href="JavaScript:hapus('logo')"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Logo Sekolah!', this, event, '75px')"/></a>
        </td>
     <?php } ?>
     <?php if (mysqli_num_rows($result) >  0  && $row['nama'] != "") {	 ?>
        <td align="center">
            <a href="JavaScript:edit()"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Header!', this, event, '85px')" /></a>&nbsp;
            <a href="JavaScript:hapus('header')"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Header!', this, event, '85px')"/></a>
        </td>
    <?php } ?>
    </tr>
<?php 	 } ?>
    
	</table>
    <br /><br />      
	
	</td></tr>
<!-- END TABLE CENTER -->    
</table>
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    

</body>
</html>
<?php CloseDb();?>