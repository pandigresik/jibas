<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 2.6.0 (January 14, 2012)
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
require_once("../include/sessionchecker.php");

$nis = "";
if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];

if (isset($_REQUEST['simpan'])){
	$nis = $_REQUEST['nis'];
	$tgl=explode("-",(string) $_REQUEST['tanggal']);
	$tanggal=$tgl[2]."-".$tgl[1]."-".$tgl[0];
	$judul = $_REQUEST['judul'];
	$catatan = $_REQUEST['catatan'];
	//echo " NIS = ".$nis;
	//echo " tanggal = ".$tanggal;
	//echo " judul = ".$judul;
	//echo " catatan = ".$catatan;
	OpenDb();
	$sql_get_idkelas = "SELECT idkelas FROM jbsakad.siswa WHERE nis='$nis'";
	$res_get_idkelas = QueryDb($sql_get_idkelas);
	$row_id = @mysqli_fetch_array($res_get_idkelas);
	$idkelas = $row_id['idkelas'];
	CloseDb();
	//echo " idkelas = ".$idkelas;
	$nip = SI_USER_ID();
	$idkategori = $_REQUEST['kategori'];
	//echo " nip = ".$nip;
	//echo " idkategori = ".$idkategori;
	OpenDb();
	$sql="INSERT INTO jbsvcr.catatansiswa SET idkategori='$idkategori',nis='$nis',idkelas='$idkelas',tanggal='$tanggal',judul='$judul',catatan='$catatan',nip='$nip'";
	//echo $sql; exit;
	$result = QueryDb($sql);
	if ($result){
	?>
	<script language="javascript" type="text/javascript">
		parent.catatansiswamenu.show('<?=$idkategori?>');
		parent.catatansiswamenu.willshow('<?=$idkategori?>');
	
		//document.location.href="../blank.php";
	</script>
	<?php
	}
	CloseDb();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/calendar-win2k-1.css">
<script type="text/javascript" src="../script/calendar.js"></script>
<script type="text/javascript" src="../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../script/calendar-setup.js"></script>
<script language="javascript" type="text/javascript" src="../script/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script src="../script/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
tinyMCE.init({
		mode : "textareas",
		theme : "simple"

	});

function validate(){
	var judul=document.getElementById('judul').value;
	var kategori=document.getElementById('kategori').value;
	var catatan=tinyMCE.get('catatan').getContent();
	if (judul.length==0){
		alert ('Anda harus mengisikan data untuk Judul Catatan');
		document.getElementById('judul').focus();
		return false;
	}
	if (kategori.length==0){
		alert ('Anda harus mengisikan data untuk Kategori Catatan');
		document.getElementById('kategori').focus();
		return false;
	}
	if (catatan.length==0){
		alert ('Anda harus mengisikan data untuk Catatan');
		document.getElementById('catatan').focus();
		return false;
	}
	return true;
}
</script>
</head>

<body leftmargin="2" topmargin="10" onLoad="document.getElementById('judul').focus();">
<form action="catatansiswaadd.php" onSubmit="return validate()">
<input name="nis" type="hidden" value="<?=$nis?>" id="nis" />
<table width="100%" border="0" cellspacing="5">
  <tr>
    <td colspan="2"><strong><font size="2" color="#999999">Catatan Siswa Baru</font></strong><br /><br /></td>
  </tr>
  <tr>
    <td width="68"><strong>Kategori </strong></td>
    <td width="1147">
    <select name="kategori" id="kategori" style="font-size: 14px; height: 24px;" >
    <?php
	OpenDb();
	$sql = "SELECT * FROM jbsvcr.catatankategori WHERE aktif=1 ORDER BY replid";
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0){
	$cnt=1;
	while ($row=@mysqli_fetch_array($result)){
		echo "<option value='".$row['replid']."'>".$row['kategori']."</option>";
	}
	} else {
		echo "<option value=''>Tidak ada kategori</option>";
	}
    CloseDb();
	?>
	</select>
    </td>
  </tr>
  <tr>
    <td><strong>Tanggal</strong></td>
    <td>
        <input title="Klik untuk membuka kalender !" type="text" name="tanggal" id="tanggal" size="10" readonly="readonly"
               class="disabled" value="<?=date('d')."-".date('m')."-".date('Y'); ?>"
               style="font-size: 14px; height: 24px; background-color: #eee" />&nbsp;
        <img title="Klik untuk membuka kalender !" src="../images/ico/calendar_1.png" name="btntanggal" width="16" height="16" border="0" id="btntanggal"/>
    </td>
  </tr>
  <tr>
    <td><strong>Judul </strong></td>
    <td>
        <input name="judul" type="text" id="judul" size="35" style="font-size: 14px; height: 24px; width: 550px" maxlength="255" />
    </td>
  </tr>
  <tr>
    <td colspan="2" align="left"><strong>Catatan </strong>
      <div align="center"><br />
          <textarea name="catatan" rows="25" id="catatan" style="width:100%"></textarea>
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <input name="simpan" type="submit" class="but" id="simpan" value="Simpan" />
    </div></td>
  </tr>
</table>
</form>
</body>
</html>
<script type="text/javascript">
  Calendar.setup(
    {
	  inputField  : "tanggal",         
      ifFormat    : "%d-%m-%Y",  
      button      : "btntanggal"    
    }
   );
   Calendar.setup(
    {
	  inputField  : "tanggal",      
      ifFormat    : "%d-%m-%Y",   
      button      : "tanggal"     
    }
   );
  
</script>
<script language="javascript">
var sprytextfield = new Spry.Widget.ValidationTextField("judul");
var spryselect = new Spry.Widget.ValidationSelect("kategori");
</script>