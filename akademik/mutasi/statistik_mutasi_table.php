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
$departemen=$_REQUEST['departemen'];
$tahunakhir=$_REQUEST['tahunakhir'];
$tahunawal=$_REQUEST['tahunawal'];
openDb();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/style.css" rel="stylesheet" type="text/css">
</head>

<body topmargin="0" leftmargin="0">
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">
function newWindow(mypage,myname,w,h,features) {
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
}
function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}
function detail1(departemen,tahunawal,tahunakhir,jenismutasi) {
	//var departemen2=document.getElementById("departemen2").value;
	show_wait("detail_tabel");	
	//show_wait("tabel_pilih");
	sendRequestText("mutasi_get_detail.php", show_tabel, "show=1&departemen="+departemen+"&tahunawal="+tahunawal+"&tahunakhir="+tahunakhir+"&jenismutasi="+jenismutasi);
}
function detail2(departemen,tahun,jenismutasi) {
    show_wait("detail_tabel");	
	//var departemen2=document.getElementById("departemen2").value;	
	sendRequestText("mutasi_get_detail.php", show_tabel, "show=2&departemen="+departemen+"&tahun="+tahun+"&jenismutasi="+jenismutasi);
}

function show_tabel(x) {
	document.getElementById("detail_tabel").innerHTML = x;
	//var kelas=document.getElementById("kelas").value;
	//sendRequestText("mutasi_get_siswa_pilih.php", show_tabelpilih, "kelas="+kelas);
}
function tampil(replid) {
	newWindow('../library/detail_siswa.php?replid='+replid, 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
<body>
<div id="waitBox" style="position:absolute; visibility:hidden;">
&nbsp;&nbsp;&nbsp;<img src="../images/movewait.gif" border="0" />&nbsp;please wait...
</div>
<table width="100%" border="0">
  <tr>
    <td>
    <table width="95%"  border="1" align="center" cellpadding="3" cellspacing="0" class="tab" id="table" bordercolor="#000000">
	 <tr class="header">
        <td height="30" align="center">No</td>
		<td height="30" align="center">Jenis Mutasi</td>
        <td height="30">Jumlah </td>
        <td height="30"></td>
      </tr>
 <?php
$sql1="SELECT * FROM jbsakad.jenismutasi ORDER BY replid";
$result1=QueryDb($sql1);
$cnt=1;
	while ($row1=@mysqli_fetch_array($result1)){
	$sql2="SELECT COUNT(*) FROM jbsakad.mutasisiswa m,jbsakad.siswa s,jbsakad.kelas k,jbsakad.tahunajaran ta,jbsakad.tingkat ti WHERE s.idkelas=k.replid AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ti.departemen='$departemen' AND ta.departemen='$departemen' AND s.statusmutasi='".$row1['replid']."' AND m.jenismutasi='".$row1['replid']."' AND s.statusmutasi=m.jenismutasi AND m.nis=s.nis";
	$result2=QueryDb($sql2);
	$row2=@mysqli_fetch_row($result2);

?>
<tr><td><?=$cnt?></td><td><?=$row1['jenismutasi']?></td><td><?=$row2[0]?>&nbsp;siswa</td><td><img onClick="detail1('<?=$departemen?>','<?=$tahunawal?>','<?=$tahunakhir?>','<?=$row1['replid']?>')" src="../images/ico/lihat.png"></td></tr>
<?php
$sql3="SELECT COUNT(*),YEAR(m.tglmutasi) FROM mutasisiswa m,siswa s,kelas k,tingkat ti,tahunajaran ta WHERE m.jenismutasi='".$row1['replid']."' AND YEAR(m.tglmutasi)<='$tahunakhir' AND YEAR(m.tglmutasi)>='$tahunawal' AND m.nis=s.nis AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND s.idkelas=k.replid AND ta.departemen='$departemen' AND ti.departemen='$departemen' GROUP BY YEAR(m.tglmutasi)";
$result3=QueryDb($sql3);
while ($row3=@mysqli_fetch_row($result3)){
?>
<tr><td>&nbsp;</td><td>-&nbsp;<?=$row3[1]?></td><td><?=$row3[0]?>&nbsp;siswa</td><td><img  onClick="detail2('<?=$departemen?>','<?=$row3[1]?>','<?=$row1['replid']?>')" src="../images/ico/lihat.png"></td></tr>
<?php
}
$cnt++;
}
	
?>
  
</table>
<script language="javascript">
 	 Tables('table', 1, 0);
</script>
    </td>
  </tr>
  <tr>
    <td><div id="detail_tabel">
    
    </div></td>
  </tr>
</table>
<center>
 
</center>

</body>
</html>