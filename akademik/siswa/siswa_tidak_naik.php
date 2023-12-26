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
if (isset($_REQUEST['departemen'])){
	$departemen=$_REQUEST['departemen'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tidak Naik Kelas</title>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">
function change_departemen() {
	var departemen = document.getElementById("departemen").value;
	document.location.href = "siswa_kenaikan_header.php?departemen="+departemen;
	parent.siswa_kenaikan_menu.location.href = "siswa_kenaikan_menu.php?departemen="+departemen;
	parent.siswa_kenaikan_pilih.location.href = "../blank_white.php";
	//var tahunajaran = parent.siswa_kenaikan_menu.document.menu.tahunajaran.value;
	//var tingkat = parent.siswa_kenaikan_menu.document.menu.tingkat.value;
	parent.siswa_kenaikan_tujuan.location.href = "../blank4.php";
}
function blank(){
alert ('Blank');
//parent.siswa_kenaikan_tujuan.location.href = "../blank4.php";
}
</script>
</head>
	
<body background="../images/bkmainlong.jpg">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />&nbsp;please wait...
</div>
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td width="32%" rowspan="3"><strong>Departemen :</strong>      <select name="departemen" id="departemen" onchange="change_departemen()">
          
			<?php
				$sql = "SELECT * FROM jbsakad.departemen where aktif=1 ORDER BY urutan ASC";
				OpenDb();
				$result = QueryDb($sql);
				CloseDb();
			
				while($row = mysqli_fetch_array($result)) {
					if ($departemen == "")
						$departemen = $row['departemen'];
			?>
            		<option value="<?=urlencode((string) $row['departemen'])?>" <?=StringIsSelected($row['departemen'], $departemen) ?> ><?=$row['departemen']?></option>
            <?php
				} //while
			?>
     	</select>    </td>
    <td width="40%" rowspan="3">&nbsp;</td>
    <td width="28%" class="headerlink"><div align="right"><font size="5" color="#660000"><b>TIDAK NAIK KELAS</b></font></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right" class="headerlink"><a href="../siswa.php">Kesiswaan</a> &gt; Siswa Tidak Naik Kelas Kelas</div></td>
  </tr>
</table>
</body>
</html>