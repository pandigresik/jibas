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
require_once('../inc/sessionchecker.php');

$nis = $_REQUEST['nis'];
$nama = $_REQUEST['nama'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Penilaian Pelajaran</title>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script src="../script/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="javascript">

function carisiswa() {
	//parent.footer.location.href = "rataus.blank.php";
	//parent.isi.location.href = "blank_lap_pelajaran.php";
	newWindow('../lib/siswa.php?flag=0', 'CariSiswa','600','500','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(nis, nama, flag) {
	document.getElementById('nis').value = nis;
//	document.getElementById('nis1').value = nis;
	document.getElementById('nama').value = nama;
	//parent.footer.location.href = "../siswa/rataus.footer.main.php?nis="+nis+"&nama="+nama;
	//parent.isi.location.href = "../penilaian/blank_lap_pelajaran.php";
	sendRequestText("rataus.left.php",ShowLeft,"oldnis="+nis);	
}
function ChgTkt(Val){
    var nis = document.getElementById('nis').value;
	var x = Val.explode(",");
	sendRequestText("rataus.left.php",ShowLeft,"oldnis="+nis+"&kls="+x[0]+"&tkt="+x[1]+"&nis="+x[2]);
	//document.location.href="rataus.left.php?kls="+x[0]+"&tkt="+x[1]+"&oldnis=<?=$oldnis?>&nis="+x[2];
}
function ShowLeft(x){
	document.getElementById('rataus.left').innerHTML = x;
	Tables('table', 1, 0);
}
function SelectPel(idpel,nis,kls,tkt){
	sendRequestText("rataus.right.php",ShowRight,"pel="+idpel+"&nis="+nis+"&kls="+kls+"&tkt="+tkt);
}
function ShowRight(x){
	document.getElementById('rataus.right').innerHTML = x;
	var TabbedPanelsA0 = new Spry.Widget.TabbedPanels("TabbedPanelsA0");
	var TabbedPanelsA1 = new Spry.Widget.TabbedPanels("TabbedPanelsA1");
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
	var i = document.getElementById('i').value;
	var cnt2 = document.getElementById('cnt2').value;
	for (y=0;y<=i;y++){
		for (z=1;z<=cnt2;z++){
			Tables('table_'+y+'_'+z, 1, 0);
		}	
	}
}
function validate() {
	return validateEmptyText('nip', 'NIP Guru');
}
function CetakRataUjianSiswa(pel,kls,sem,nis,tkt,dp){
	newWindow('rataus.cetak.php?pel='+pel+'&kls='+kls+'&sem='+sem+'&nis='+nis+'&tkt='+tkt+'&dp='+dp, 'CetakRataRataUjianSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-weight: bold;
}
-->
</style>
</head>
	
<body leftmargin="0">
<div style="padding-left:5px">
<form name="main" enctype="multipart/form-data" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="70%">
        <table border="0" >
        <tr>
            <td><strong>Nama</strong></td>
            <td><strong>
              <input type="text" name="nis" id="nis" size="15"  readonly class="disabled" value="<?=$nis ?>" onclick="carisiswa()" />
            </strong></td>
            <td><strong>
              <input type="text" name="nama" id="nama" size="25"  readonly value="<?=$nama ?>" class="disabled"  onclick="carisiswa()"/>
            </strong></td>
            <td><a href="JavaScript:carisiswa()" onmouseover="showhint('Cari Siswa!', this, event, '50px')"><img src="../img/ico/lihat.png" border="0"/></a></td>
        </tr>
        </table>  
    </td>
    <td width="30%">
        <div align="right">
        <font style="background-color: rgb(255, 204, 102);" face="Verdana, Arial, Helvetica, sans-serif" size="4">&nbsp;</font>&nbsp;<font color="Gray" face="Verdana, Arial, Helvetica, sans-serif" size="4">Rata-rata Nilai Setiap Siswa</font><br>
        </div>
    </td>
  </tr>
</table>
</form>
</div>
<div style="padding-left:5px">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top">
    <div id="rataus.left"></div>
    </td>
    <td valign="top" style="padding-left:10px">
    <div id="rataus.right"></div>
    </td>
  </tr>
</table>

</div>
</body>
</html>