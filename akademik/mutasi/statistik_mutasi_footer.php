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

<body background="../images/bk_scroll_1000.jpg">
<script language="javascript" src="../script/tables.js"></script>
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
</script>
<table width="50%"  border="2" align="center" cellpadding="0" cellspacing="0" >
  <tr>
    <td><table width="100%"  border="0" align="center" cellpadding="3" cellspacing="0" class="tab" id="table" bordercolor="#000000">
	 <tr class="header">
        <td align="center">Tahun</td>
        <td>Keterangan </td>
        <td>Jumlah siswa </td>
      </tr>
 <?php

for($a=$tahunawal;$a<=$tahunakhir;$a++){
	 	$query="SELECT COUNT(*) As Jum,j.jenismutasi As jenismutasi, j.replid as replidmutasi FROM jbsakad.mutasisiswa m,jbsakad.jenismutasi j,jbsakad.angkatan a,jbsakad.siswa s WHERE	a.departemen='$departemen' AND s.idangkatan=a.replid  AND m.nis=s.nis AND s.statusmutasi=m.jenismutasi AND m.jenismutasi=j.replid AND YEAR(m.tglmutasi) = '$a' GROUP BY jenismutasi";
		$result = QueryDb($query);

 	$start=0;
	
	while($fetch=mysqli_fetch_array($result)){ $start++;	
		if($start==1){	
	?>	  
      <tr valign="middle">
        <td width="100" rowspan="<?=mysqli_num_rows($result);?>" align="center" bgcolor="#FFFFFF"><?=$a;?></td>
        <?php
				     }
	?>
        <td><?=$fetch[1]; ?></td>
        <td><a href="#" onClick="newWindow('detail_statistik_mutasi_siswa.php?replidmutasi=<?=$fetch[3];?>&jenismutasi=<?=$fetch[1];?>&departemen=<?=$departemen?>&tahun=<?=$a?>','A','686','368','scrollbars=1')"><?=$fetch[0]; ?> orang</a></td>
	    </tr>
	  
      <?php
}}
		
?>
    </table></td>
  </tr>
</table>
<center>
  <input name="button" type="button" class="but" value="Cetak" onClick="newWindow('cetak_statistik_mutasi.php?departemen=<?=$departemen?>&tahunawal=<?=$tahunawal?>&tahunakhir=<?=$tahunakhir?>','s',750,650,'scrollbars=1')">
</center>
<br>
<script language="javascript">
 	 Tables('table', 1, 0);
</script>
</body>
</html>