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
require_once('../../include/common.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');

$bagian="-1";
if (isset($_REQUEST['bagian']))
	$bagian=$_REQUEST['bagian'];

if ($bagian!="-1"){
$bag="WHERE bagian='$bagian' AND nip<>'".SI_USER_ID()."'";
} else {
$bag="WHERE nip<>'".SI_USER_ID()."'";
}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<title>Untitled Document</title>
<script language="javascript" type="text/javascript" src="../../script/tables.js"></script>
<script language="javascript" type="text/javascript">
function ambil(){
	var jumkirim=0;
	var jum = document.tujuan.numpegawai.value;
	for (x=1;x<=jum;x++){
		var nip=document.getElementById("ceknip"+x).checked;
		if (nip==true){
			document.getElementById("kirimin"+x).value="1";
			jumkirim++;	
		} else {
			document.getElementById("kirimin"+x).value="0";
		}
	}
	if (jumkirim>0 && jumkirim==1){
		document.getElementById("numpegawaikirim").value=jumkirim;
		if (confirm('Kirimkan pesan kepada pegawai ini ?')){
			parent.pesanguru_add.validate();
		}
	} else if (jumkirim>1){
		document.getElementById("numpegawaikirim").value=jumkirim;
		if (confirm('Kirimkan pesan kepada pegawai-pegawai ini ?')){
			parent.pesanguru_add.validate();
		}
	} else if (jumkirim==0) {
		alert ('Setidaknya harus ada 1 penerima untuk melanjutkan !');
		return false;
	}
}
function cek_all() {
	var x;
	var jum = document.tujuan.numpegawai.value;
	var ceked = document.tujuan.cek.checked;
	for (x=1;x<=jum;x++){
		if (ceked==true){
			document.getElementById("ceknip"+x).checked=true;
		} else {
			document.getElementById("ceknip"+x).checked=false;
		}
	}
}
</script>
</head>
<body>
<form name="tujuan" id="tujuan" action="pesansimpan.php">
<table width="100%" border="0" cellspacing="0" class="tab" id="table">
  <tr>
    <th width="18%" height="30" class="header" scope="row">No</th>
    <td width="3%" height="30" class="header"><input type="checkbox" name="cek" id="cek" onClick="cek_all()" title="Pilih semua" onMouseOver="showhint('Pilih semua', this, event, '120px')"/></td>
    <td width="*" height="30" class="header">Nama</td>
  </tr>
  <?php 
			OpenDb();
			$sql="SELECT * FROM jbssdm.pegawai $bag ORDER BY nama";
			$result=QueryDb($sql);
			$cnt=1;
			while ($row=@mysqli_fetch_array($result)){
  ?>
   <tr>
    <th height="25" scope="row"><?=$cnt?></th>
    <td height="25"><input type="checkbox" name="ceknip<?=$cnt?>" id="ceknip<?=$cnt?>"/></td>
    <td height="25">
		<?=$row['nip']?><br>
		<strong><?=$row['nama']?></strong>
		<input type="hidden" name="nip<?=$cnt?>" id="nip<?=$cnt?>" value="<?=$row['nip']?>"/>
		<input type="hidden" name="kirimin<?=$cnt?>" id="kirimin<?=$cnt?>"/>
	</td>
  </tr>
  <?php 
  $cnt++;
  } 
  ?>
</table>

<input type="hidden" name="numpegawai" id="numpegawai" value="<?=$cnt-1?>" />
<input type="hidden" name="numpegawaikirim" id="numpegawaikirim"/>
</form>
<script language='JavaScript'>
			Tables('table', 1, 0);
</script>
</body>
</html>