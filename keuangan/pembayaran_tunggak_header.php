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
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/departemen.php');

$idkategori = "";
if (isset($_REQUEST['idkategori']))
	$idkategori = $_REQUEST['idkategori'];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="script/tooltips.js"></script>
<script language="javascript">

function change_kate() 
{
	var idkategori = document.getElementById('idkategori').value;
	var departemen = document.getElementById('departemen').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	document.location.href = "pembayaran_tunggak_header.php?idkategori="+idkategori+"&departemen="+departemen+"&idtahunbuku="+idtahunbuku;
	parent.contentblank.location.href = "pembayaran_blank.php";
}

function change_dep() 
{
	change_kate();
}

function change_penerimaan() 
{
	parent.contentblank.location.href = "pembayaran_blank.php";
}

function change_tahunbuku() 
{
	parent.contentblank.location.href = "pembayaran_blank.php";
}

function show_pembayaran() 
{
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var departemen = document.getElementById('departemen').value;
				
	if (idtahunbuku.length == 0) 
	{
		alert ('Tahun buku tidak boleh kosong !');
		document.getElementById('idtahunbuku').focus();
		return false;			
	} 
	else if (idkategori.length == 0) 
	{
		alert ('Pastikan kategori pembayaran sudah ada!');
		document.getElementById('idkategori').focus();
		return false;	
	} 
	else if (idpenerimaan.length == 0) 
	{
		alert ('Pastikan penerimaan pembayaran sudah ada!');
		document.getElementById('idpenerimaan').focus();
		return false;	
	}
	
	parent.contentblank.location.href = "pembayaran_tunggak_main2.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&idtahunbuku="+idtahunbuku+"&departemen="+departemen;
}

function focusNext(elemName, evt) 
{
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) 
		{
			if (elemName == "tampil") 
				show_pembayaran();
			else 
				document.getElementById(elemName).focus();
			return false;
    }
    return true;
}
</script>
</head>

<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
    <td rowspan="3" width="55%">
    <table width = "100%" border = "0">
    <tr>
    	<td align="left" width = "15%"><strong>Departemen&nbsp;</strong>
      	<td width="*">
        <select name="departemen" id="departemen" style="width:100px" onchange="change_dep()" onKeyPress="return focusNext('idtahunbuku', event)">
		    <?php
        OpenDb();
        $dep = getDepartemen(getAccess());
        foreach($dep as $value) {
            if ($departemen == "")
                $departemen = $value; ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?>><?=$value ?></option>
        <?php } ?>    
        </select>&nbsp;
        <strong>Tahun Buku&nbsp;</strong>
        <?php
        $sql = "SELECT replid AS id, tahunbuku FROM tahunbuku WHERE aktif <> 1 AND departemen = '$departemen' ORDER BY tanggalmulai DESC";
        $result = QueryDb($sql);
   		  ?>
        <select name="idtahunbuku" id="idtahunbuku" style="width:100px" onchange="change_tahunbuku()" onKeyPress="return focusNext('idkategori', event)">
        <?php
				while ($row = mysqli_fetch_row($result))
				{	
					if ($idtahunbuku == 0)
						$idtahunbuku = $row[0];	?>
        		<option value="<?=$row[0]?>" <?=IntIsSelected($idtahunbuku, $row[0])?>><?=$row[1]?></option>
        <?php } ?>
        </select>&nbsp;
    	</td>
	</tr>
    <tr>
    	<td><strong>Pembayaran&nbsp;</strong></td>
      	<td>    
        <select name="idkategori" id="idkategori" style="width:188px;" onchange="change_kate()" onKeyPress="return focusNext('idpenerimaan', event)">
        <?php
        $sql = "SELECT kode, kategori FROM kategoripenerimaan WHERE kode IN ('JTT','CSWJB') ORDER BY urutan";
        $result = QueryDb($sql);
        while ($row = mysqli_fetch_array($result)) {
            if ($idkategori == "")
                $idkategori = $row['kode']
        ?>
            <option value="<?=$row['kode'] ?>" <?=StringIsSelected($idkategori, $row['kode']) ?> > <?=$row['kategori'] ?></option>
        <?php
        }
        ?>
        </select>
    	<select name="idpenerimaan" id="idpenerimaan" style="width:195px;" onchange="change_penerimaan()" onKeyPress="return focusNext('tampil', event)">
			  <?php
        $sql = "SELECT replid, nama FROM datapenerimaan WHERE aktif = 1 AND idkategori = '$idkategori' AND departemen = '$departemen' ORDER BY replid DESC ";
        $result = QueryDb($sql);
        while ($row = mysqli_fetch_array($result)) {
        ?>
            <option value="<?=$row['replid'] ?>"><?=$row['nama'] ?></option>
        <?php
        }
        ?>
        </select>
    	</td>
	</tr>
	</table>
    </td>		
    <td width="*" rowspan="2" valign="middle"><a href="#" onclick="show_pembayaran()">
    <img src="images/view.png" border="0" height="48" width="48" onmouseover="showhint('Klik untuk menampilkan data penerimaan pembayaran!', this, event, '180px')"/></a>    
    </td>
    <td width="30%" colspan = "2" align="right" valign="top">
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pembayaran Sisa Tunggakan</font><br />
    <a href="penerimaan.php" target="_parent">
    <font size="1" color="#000000"><b>Penerimaan</b></font></a>&nbsp>&nbsp
    <font size="1" color="#000000"><b>Pembayaran Sisa Tunggakan</b></font>
</td>  
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
</body>
</html>
<script language="javascript">	
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect1 = new Spry.Widget.ValidationSelect("idkategori");
	var spryselect1 = new Spry.Widget.ValidationSelect("idpenerimaan");
</script>