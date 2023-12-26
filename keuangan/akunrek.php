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
require_once('include/errorhandler.php');
require_once('include/sessioninfo.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');

$from = $_REQUEST['from'];
$sourcefrom = $_REQUEST['sourcefrom'];

if (getLevel() == 2) { ?>
<script language="javascript">
	alert('Maaf, anda tidak berhak mengakses halaman ini!');
	document.location.href = "<?=$sourcefrom ?>";
</script>
<?php 	exit();
} // end if

$kategori = "";
if (isset($_GET['kategori']))
	$kategori = $_GET['kategori'];
	
$op = $_REQUEST['op'];
if ($op == "12134892y428442323x423") 
{
	$sql = "DELETE FROM rekakun WHERE kode = '".$_REQUEST['kode']."'";
	OpenDb();
	QueryDb($sql);
	CloseDb();	?>
    <script language="javascript">
		document.location.href = "akunrek.php?kategori=<?=$_REQUEST['kategori']?>&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>";
    </script>
    <?php
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kode Perkiraan</title>
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function change_kategori() {
	var kate = document.getElementById('kategori').value;
	document.location.href = "akunrek.php?kategori=" + kate + "&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>";
}

function refresh() {

	document.location.reload();
}

function del(kode) {
	
 	if (confirm("Apakah anda yakin akan menghapus data ini?")) {
		var kate = document.getElementById('kategori').value;
		document.location.href = "akunrek.php?op=12134892y428442323x423&kategori="+kate+"&kode="+kode+"&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>";
	}
}

function cetak() {
	var kategori = document.getElementById('kategori').value;
	var addr = "akunrek_cetak.php?kategori="+kategori;
	newWindow(addr, 'CetakRekAkun','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah() {

	var kategori = document.getElementById('kategori').value;
	newWindow('akunrek_add.php?kategori='+kategori,'','450','310','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body onLoad="document.getElementById('kategori').focus();">
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="images/building1.png" style="background-repeat:no-repeat">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
	<td align="left" valign="top">
	<table border="0"width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
        <td align="right">
		<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Kode Rekening Perkiraan</font>	
        </td>
  	</tr>
    <tr>
    	<td align="right"><a href="<?=$sourcefrom ?>">
      	<font size="1" color="#000000"><b><?=$from ?></b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Kode Rekening Perkiraan</b></font></td>
   	</tr>
    <tr>
      	<td align="left">&nbsp;</td>
    </tr>
	</table><br />
    
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <!-- TABLE LINK -->
    <tr>
    	<td align="right" width="40%">
        <strong>Kategori&nbsp;</strong>
        <select name="kategori" id="kategori" onChange="change_kategori()" style="width:150px">
        <?php
        $sql = "SELECT * FROM katerekakun ORDER BY urutan";
		OpenDb();
		$result = QueryDb($sql);
		while ($row = mysqli_fetch_array($result)) {
			if ($kategori == "")
				$kategori = $row['kategori'];
		?>
        	<option value="<?=$row['kategori']?>" <?=StringIsSelected($kategori, $row['kategori'])?> ><?=$row['kategori']?></option>
        <?php
		}
		?>
		</select>
        </td>
<?php 
	OpenDb();
	$sql = "SELECT * FROM rekakun WHERE kategori = '$kategori' ORDER BY kode";
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0){
?>
	
        
        <td align="right">
        <a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;        
        <a href="JavaScript:tambah()">
            <img src="images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')">&nbsp;Tambah Rekening Perkiraan</a>
        
        </td>
    </tr>
	</table><br />
    
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
	<tr height="30" align="center">
        <td class="header" width="50">No</td>
        <td class="header" width="10%">Kode</td>
        <td class="header" width="20%">Nama</td>
        <td class="header">Keterangan</td>      
        <td class="header" width="100">&nbsp;</td>        
	</tr>
    <?php 
	
	$no = 0;
	while ($row = mysqli_fetch_array($result)) {
	?>
    <tr height="25">
    	<td align="center"><?=++$no ?></td>
        <td align="center"><?=$row['kode'] ?></td>
        <td><?=$row['nama'] ?></td>
        <td><?=$row['keterangan'] ?></td>        
        <td align="center">
        <a href="#" onClick="newWindow('akunrek_edit.php?kode=<?=$row['kode']?>',
        'UbahRekening','450','310','resizable=1,scrollbars=0,status=0,toolbar=0')"><img src="images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Rekening!',this, event, '75px')"></a>&nbsp;<a href="#" onClick="del('<?=$row['kode']?>')"><img src="images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Rekening!', this, event, '75px')"></a>
        </td>        
    </tr>
    <?php 
	}
	?>
    <!-- END TABLE CONTENT -->
    </table>
     <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
   
</td></tr>
<!-- EOF CONTENT -->
</table>
<?php } else { ?>
	<td width = "60%"></td>
</tr>
</table>
<table width="95%" border="0" align="center">          
<tr>
	<td width="14%"></td>
	<td><hr style="border-style:dotted" /></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">    
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.         
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        
        </b></font>
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
	var spryselect1 = new Spry.Widget.ValidationSelect("kategori");
</script>