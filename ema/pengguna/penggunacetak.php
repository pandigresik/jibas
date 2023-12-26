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
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
$departemen = "yayasan";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Daftar Pengguna]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
  <td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>DAFTAR PENGGUNA</strong></font> <br />
 </center><br /><br />

<table width="100%" border="1" class="tab">
  <tr>
    <td height="25" align="center" class="header">No.</td>
    <td height="25" align="center" class="header">NIP</td>
    <td height="25" align="center" class="header">Nama</td>
    <td align="center" class="header">Last&nbsp;Login</td>
    <td height="25" align="center" class="header">Status</td>
  </tr>
  <?php
  $sql = "SELECT p.nip,p.nama,l.aktif,h.replid,h.lastlogin FROM $db_name_user.hakakses h, $db_name_sdm.pegawai p, $db_name_user.login l WHERE h.modul='EMA' AND p.nip=h.login AND h.login=l.login ORDER BY h.lastlogin";
  $result = QueryDb($sql);
  $num = @mysqli_num_rows($result);
  if ($num>0){
  $cnt=1;
  while ($row = @mysqli_fetch_row($result)){
    ?>
  <tr>
    <td align="center"><?=$cnt?></td>
    <td align="center"><?=$row[0]?></td>
    <td><?=$row[1]?></td>
    <td align="center"><?=$row[4]?></td>
    <td align="center">
    	<?php if ($row[2]==1){ ?>
    	Aktif
        <?php } else { ?>
        Tidak Aktif
    	<?php } ?>    
    </td>
  </tr>
  <?php
  $cnt++;
  }
  } else { 
  ?>
  <tr>
    <td colspan="6" align="center" class="nodata">Tidak ada data</td>
  </tr>
  <?php
  }
  ?>
</table></td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>

</html>