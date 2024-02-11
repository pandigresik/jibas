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
require_once("../inc/session.checker.php");
require_once("../inc/config.php");
require_once("../inc/db_functions.php");
require_once("../inc/common.php");
$err = '';
$perpustakaan=$_REQUEST['perpustakaan'];
OpenDb();
$sql	= "SELECT * FROM ".$db_name_umum.".identitas WHERE status=1 AND perpustakaan='$perpustakaan'";
$result = QueryDb($sql);
$row	= @mysqli_fetch_array($result);
$nama 	= $row['nama'];
$alamat	= $row['ALAMAT1'];
$telp1 	= $row['TELP1'];
$telp2 	= $row['TELP2'];
$fax 	= $row['FAX1'];
$website= $row['situs'];
$email 	= $row['email']; 
if (isset($_REQUEST['Simpan'])){
	$nama 	= CQ($_REQUEST['nama']);
	$alamat	= CQ($_REQUEST['alamat']);
	$telp1 	= CQ($_REQUEST['telp1']);
	$telp2 	= CQ($_REQUEST['telp2']);
	$fax 	= CQ($_REQUEST['fax']);
	$website= CQ($_REQUEST['website']);
	$email 	= CQ($_REQUEST['email']); 
	$sql 	= "SELECT * FROM ".$db_name_umum.".identitas WHERE status=1 AND nama='$nama' AND perpustakaan<>'$perpustakaan'";
	$result = QueryDb($sql);
	$num	= @mysqli_num_rows($result);
	if ($num>0){
		$err = "Nama perpustakaan $nama sudah digunakan!";
	} else {
		$sql2 	= "UPDATE ".$db_name_umum.".identitas SET nama='$nama',alamat1='$alamat',telp1='$telp1',telp2='$telp2',fax1='$fax',situs='$website',email='$email' WHERE status=1 AND perpustakaan='$perpustakaan'";
		QueryDb($sql2);
		?>
        <script language="javascript">
			parent.opener.Fresh();
			window.close();
        </script>
        <?php
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pengaturan Informasi Header</title>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="title" align="right">
	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
	<font style="font-size:18px; color:#999999">Informasi Header</font><br />
</div>
<form action="AddInfo.php" method="post">
<input type="hidden" name="Action" value="<?=$_REQUEST['op']?>" />
<input type="hidden" name="perpustakaan" value="<?=$perpustakaan?>" /> 
<table width="100%" border="0" cellspacing="3">
  <tr>
    <td width="34%" align="right">Nama Perpustakaan</td>
    <td width="66%"><input type="text" name="nama" id="nama" class="inputtxt" style="width:175px" value="<?=$row['nama']?>" /></td>
  </tr>
  <tr>
    <td align="right">Alamat</td>
    <td><textarea name="alamat" class="areatxt" id="alamat" cols="28" rows="3"><?=$row['ALAMAT1']?></textarea></td>
  </tr>
  <tr>
    <td align="right">Telepon 1</td>
    <td><input type="text" name="telp1" id="telp1" class="inputtxt" style="width:175px" value="<?=$row['TELP1']?>" /></td>
  </tr>
  <tr>
    <td align="right">Telepon 2</td>
    <td><input type="text" name="telp2" id="telp2" class="inputtxt" style="width:175px" value="<?=$row['TELP2']?>" /></td>
  </tr>
  <tr>
    <td align="right">Fax</td>
    <td><input type="text" name="fax" id="fax" class="inputtxt" style="width:175px" value="<?=$row['FAX1']?>" /></td>
  </tr>
  <tr>
    <td align="right">Website</td>
    <td><input type="text" name="website" id="website" class="inputtxt" style="width:175px" value="<?=$row['situs']?>" /></td>
  </tr>
  <tr>
    <td align="right">Email</td>
    <td><input type="text" name="email" id="email" class="inputtxt" style="width:175px" value="<?=$row['email']?>" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input name="Simpan" type="submit" value="Simpan" class="btnfrm2" />&nbsp;&nbsp;<input type="button" value="Batal" onClick="window.close()" class="btnfrm2" /></td>
  </tr>
</table>

</body>
</html>