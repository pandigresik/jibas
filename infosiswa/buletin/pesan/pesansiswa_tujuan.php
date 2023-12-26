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
require_once('../../include/getheader.php');
require_once('../../include/db_functions.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');

$bulan="";
if (isset($_REQUEST['bulan']))
	$bulan=$_REQUEST['bulan'];

$tahun="";
if (isset($_REQUEST['tahun']))
	$tahun=$_REQUEST['tahun'];

$xxx="";
if (isset($_REQUEST['xxx']))
	$xxx=$_REQUEST['xxx'];

$departemen="";
if (isset($_REQUEST['departemen']))
	$departemen=$_REQUEST['departemen'];
$tahunajaran="";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran=$_REQUEST['tahunajaran'];
$tingkat="";
if (isset($_REQUEST['tingkat']))
	$tingkat=$_REQUEST['tingkat'];
$kelas="";
if (isset($_REQUEST['kelas']))
	$kelas=$_REQUEST['kelas'];			




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<link href="../../script/SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<title>Untitled Document</title>
<script language="javascript" type="text/javascript" src="../../script/tables.js"></script>
<script language="javascript" type="text/javascript" src="../../script/SpryAssets/SpryValidationSelect.js"></script>
<script language="javascript" type="text/javascript">
function batal(){
	//var bulan=document.getElementById('bulan').value;
	//var tahun=document.getElementById('tahun').value;
	parent.location.href="pesan_inbox.php";
}
function chg_dep(){
	var bulan=document.getElementById('bulan').value;
	var tahun=document.getElementById('tahun').value;
	var departemen=document.getElementById('departemen').value;
	document.location.href="pesansiswa_tujuan.php?bulan="+bulan+"&tahun="+tahun+"&departemen="+departemen;
}
function chg_kelas(){
	var bulan=document.getElementById('bulan').value;
	var tahun=document.getElementById('tahun').value;
	var departemen=document.getElementById('departemen').value;
	var tahunajaran=document.getElementById('tahunajaran').value;
	var tingkat=document.getElementById('tingkat').value;
	var kelas=document.getElementById('kelas').value;
	document.location.href="pesansiswa_tujuan.php?bulan="+bulan+"&tahun="+tahun+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas;
}
function chg_semting(){
	var bulan=document.getElementById('bulan').value;
	var tahun=document.getElementById('tahun').value;
	var departemen=document.getElementById('departemen').value;
	var tahunajaran=document.getElementById('tahunajaran').value;
	var tingkat=document.getElementById('tingkat').value;
	document.location.href="pesansiswa_tujuan.php?bulan="+bulan+"&tahun="+tahun+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat;
}
function kasih_kebawah(){
	var kelas=document.getElementById('kelas').value;
	var bulan=document.getElementById('bulan').value;
	var tahun=document.getElementById('tahun').value;
	var departemen=document.getElementById('departemen').value;
	var tahunajaran=document.getElementById('tahunajaran').value;
	var tingkat=document.getElementById('tingkat').value;
	parent.tujuan_footer.location.href="pesansiswa_tujuan_footer.php?bulan="+bulan+"&tahun="+tahun+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas;
}
function ambil(){
	//alert ('Masuk');
	var jumkirim=0;
	var jum = parent.tujuan_footer.document.getElementById('numsiswa').value;
	
	for (x=1;x<=jum;x++){
		var nis=parent.tujuan_footer.document.getElementById("ceknis"+x).checked;
		if (nis==true){
			parent.tujuan_footer.document.getElementById("kirimin"+x).value="1";
			jumkirim++;	
		} else {
			parent.tujuan_footer.document.getElementById("kirimin"+x).value="0";
		}
	}
	if (jumkirim>0 && jumkirim==1){
		parent.tujuan_footer.document.getElementById("numsiswakirim").value=jumkirim;
		if (confirm('Kirimkan pesan kepada siswa ini ?')){
			parent.pesansiswa_add.validate();
		}
	} else if (jumkirim>1){
		parent.tujuan_footer.document.getElementById("numsiswakirim").value=jumkirim;
		if (confirm('Kirimkan pesan kepada siswa-siswa ini ?')){
			parent.pesansiswa_add.validate();
		}
	} else if (jumkirim==0) {
		alert ('Setidaknya harus ada 1 penerima untuk melanjutkan !');
		return false;
	}
}
</script>
</head>
<body onload="kasih_kebawah();" style="margin-bottom:0px; margin-left:0px; margin-right:0px; margin-top:0px; ">
<form name="tujuan" id="tujuan" action="pesansimpan.php">
<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>" />
<input type="hidden" name="bulan" id="bulan" value="<?=$bulan?>"/>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th height="30" colspan="4" valign="top" scope="row" ><div align="left">&nbsp;&nbsp;
  <button title="Kirim pesan !" type="button" class="but" onclick="ambil();" name="kirim" id="kirim" value="Kirim" /><font size="+2"><strong>Kirim</strong></font></button>
  &nbsp;&nbsp;
      <input title="Kembali ke Halaman Kotak Pesan !" type="button" class="but" onclick="batal();" name="cancel" id="cancel" value="Batal" />
    </div></th>
    </tr>
  <tr>
    <td width="100" scope="row" ><div align="left">&nbsp;&nbsp;Departemen </div></td>
    <td width="853" scope="row" >
      <div align="left">&nbsp;&nbsp;
        <select name="departemen" id="departemen" style="width:125px" onchange="chg_dep()">
          <?php 
			OpenDb();
			$sql="SELECT * FROM jbsakad.departemen WHERE aktif=1 ORDER BY urutan";
			$result=QueryDb($sql);
			while ($row=@mysqli_fetch_array($result)){
			if ($departemen=="")
				$departemen=$row['departemen'];
			?>
          <option value="<?=$row['departemen']?>" <?=StringIsSelected($departemen,$row['departemen'])?>>
            <?=$row['departemen']?>
            </option>
          <?php
			}
			CloseDb();
			?>
        </select>
        </div></td>
    </tr>
  <tr>
    <td width="100" scope="row" ><div align="left">&nbsp;&nbsp;Tingkat</div></td>
    <td scope="row" >
      <div align="left">&nbsp;&nbsp;
        <select name="tingkat" id="tingkat" onchange="chg_semting()" style="width:125px">
          <?php 
			OpenDb();
			$sql="SELECT * FROM jbsakad.tingkat WHERE aktif=1 AND departemen='$departemen' ORDER BY tingkat";
			$result=QueryDb($sql);
			while ($row=@mysqli_fetch_array($result)){
			if ($tingkat=="")
				$tingkat=$row['replid'];
			?>
          <option value="<?=$row['replid']?>" <?=StringIsSelected($tingkat,$row['replid'])?>>
            <?=$row['tingkat']?>
            </option>
          <?php
			}
			CloseDb();
			?>
        </select>
        </div></td>
    </tr>
  <tr>
    <td width="100" scope="row" ><div align="left">&nbsp;&nbsp;Tahun&nbsp;Ajaran</div></td>
    <td scope="row" ><div align="left">&nbsp;&nbsp;
      <select name="tahunajaran" id="tahunajaran" onchange="chg_semting()" style="width:125px">
        <?php 
			OpenDb();
			$sql="SELECT * FROM jbsakad.tahunajaran WHERE aktif=1 AND departemen='$departemen' ORDER BY tahunajaran";
			$result=QueryDb($sql);
			while ($row=@mysqli_fetch_array($result)){
			if ($tahunajaran=="")
				$tahunajaran=$row['replid'];
			?>
        <option value="<?=$row['replid']?>" <?=StringIsSelected($tahunajaran,$row['replid'])?>>
          <?=$row['tahunajaran']?>
          </option>
        <?php
			}
			CloseDb();
			?>
      </select>
    </div></td>
    </tr>
  <tr>
    <td width="100" scope="row" ><div align="left">&nbsp;&nbsp;Kelas</div></td>
    <td scope="row" ><div align="left">&nbsp;&nbsp;
      <select name="kelas" id="kelas" onchange="chg_kelas()" style="width:125px">
        <?php 
			OpenDb();
			$sql="SELECT * FROM jbsakad.kelas WHERE aktif=1 AND idtahunajaran='$tahunajaran' AND idtingkat='$tingkat' ORDER BY kelas";
			$result=QueryDb($sql);
			while ($row=@mysqli_fetch_array($result)){
			if ($kelas=="")
				$kelas=$row['replid'];
			?>
        <option value="<?=$row['replid']?>" <?=StringIsSelected($kelas,$row['replid'])?>>
          <?=$row['kelas']?>
          </option>
        <?php
			}
			CloseDb();
			?>
      </select>
    </div></td>
    </tr>
 </table>
</form>
</body>
</html>
<script language="javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
var spryselect2 = new Spry.Widget.ValidationSelect("tingkat");
var spryselect3 = new Spry.Widget.ValidationSelect("tahunajaran");
var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
</script>