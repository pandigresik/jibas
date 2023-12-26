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

$bulan="";
if (isset($_REQUEST['bulan']))
	$bulan=$_REQUEST['bulan'];
$bagian="-1";
if (isset($_REQUEST['bagian']))
	$bagian=$_REQUEST['bagian'];

if ($bagian!="-1"){
$bag="WHERE bagian='$bagian' AND nip<>'".SI_USER_ID()."'";
} else {
$bag="WHERE nip<>'".SI_USER_ID()."'";
}	

$tahun="";
if (isset($_REQUEST['tahun']))
	$tahun=$_REQUEST['tahun'];

$xxx="";
if (isset($_REQUEST['xxx']))
	$xxx=$_REQUEST['xxx'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<title>Untitled Document</title>
<script language="javascript" type="text/javascript" src="../../script/tables.js"></script>
<script language="javascript" type="text/javascript">
/*
function batal(){
	var bulan=document.getElementById('bulan').value;
	var tahun=document.getElementById('tahun').value;
	parent.location.href="pesanguru_footer.php?bulan="+bulan+"&tahun="+tahun;
}
*/
function batal(){
	//var bulan=document.getElementById('bulan').value;
	//var tahun=document.getElementById('tahun').value;
	parent.location.href="pesan_inbox.php";
}
function chg_bag(){
	var bulan=document.getElementById('bulan').value;
	var tahun=document.getElementById('tahun').value;
	var bagian=document.getElementById('bagian').value;
	parent.tujuan_footer.location.href="pesanguru_tujuan_footer.php?bagian="+bagian;
	document.location.href="pesanguru_tujuan.php?bulan="+bulan+"&tahun="+tahun+"&bagian="+bagian;
}
function ambil(){
	var jumkirim=0;
	var jum = parent.tujuan_footer.document.getElementById("numpegawai").value;
	for (x=1;x<=jum;x++){
		var nis=parent.tujuan_footer.document.getElementById("ceknip"+x).checked;
		if (nis==true){
			parent.tujuan_footer.document.getElementById("kirimin"+x).value="1";
			jumkirim++;	
		} else {
			parent.tujuan_footer.document.getElementById("kirimin"+x).value="0";
		}
	}
	if (jumkirim>0 && jumkirim==1){
		parent.tujuan_footer.document.getElementById("numpegawaikirim").value=jumkirim;
		if (confirm('Kirimkan pesan kepada pegawai ini ?')){
			parent.pesanguru_add.validate();
		}
	} else if (jumkirim>1){
		parent.tujuan_footer.document.getElementById("numpegawaikirim").value=jumkirim;
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
<body style="margin-bottom:0px; margin-left:0px; margin-right:0px; margin-top:0px; background-color:#FFFFFF">
<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>" />
<input type="hidden" name="bulan" id="bulan" value="<?=$bulan?>"/>
<table width="100%" border="0" cellspacing="0">
   <tr bgcolor="#FFFFFF">
    <th scope="row" align="right" valign="bottom"><select name="bagian" id="bagian" onchange="chg_bag()">
        	<option value="-1" <?=StringIsSelected("-1",$bagian)?>>Semua Bagian</option>
        	<?php 
			OpenDb();
			$sql="SELECT * FROM jbssdm.bagianpegawai ORDER BY bagian";
			$result=QueryDb($sql);
			while ($row=@mysqli_fetch_array($result)){
			?>
            <option value="<?=$row['bagian']?>" <?=StringIsSelected($bagian,$row['bagian'])?>><?=$row['bagian']?></option>
            <?php
			}
			CloseDb();
			?>
        </select></th>
    <th rowspan="2" align="left" valign="bottom" scope="row"><button title="Kirim pesan !" type="button" class="but" onclick="ambil();" name="kirim" id="kirim" value="Kirim" /><strong><font size="+3">Kirim</font></strong></button></th>
  </tr>
   <tr align="right" bgcolor="#FFFFFF">
     <th scope="row" valign="bottom">&nbsp;&nbsp;
     <input title="Kembali ke Halaman Kotak Pesan !" type="button" class="but" onclick="batal();" name="cancel" id="cancel" value="Batal" /></th>
   </tr>
</table>

</body>
</html>