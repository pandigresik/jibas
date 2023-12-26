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
require_once('../include/getheader.php');
require_once('../include/db_functions.php');
require_once('../include/rupiah.php');
require_once('../library/departemen.php');
OpenDb();
$replid=$_REQUEST['replid'];
$query="SELECT * FROM jbsakad.aktivitaskalender WHERE idkalender='".$_REQUEST['replid']."'";
$result=QueryDb($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tables.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?php include("../library/headercetak.php") ?>

<center>
  <font size="3"><strong>Kalender Akademik</strong></font><br />
 </center><br /><br />
<table width="100%" border="1" class="tab" id="table">
  <tr>
    <td width="3%" height="30" class="header"><div align="center">No</div></td>
    <td width="19%" height="30" class="header"><div align="center">Tanggal Mulai</div></td>
    <td width="22%" height="30" class="header"><div align="center">Tanggal Selesai</div></td>
    <td width="56%" height="30" class="header"><div align="center">Kegiatan</div></td>
  </tr>
  <?php
  $cnt=1;
  while ($row=@mysqli_fetch_array($result)){
  ?>
  <tr>
    <td height="25"><?=$cnt?></td>
    <td height="25"><?=format_tgl($row['tanggalawal'])?></td>
    <td height="25"><?=format_tgl($row['tanggalakhir'])?></td>
    <td height="25"><?=$row['kegiatan']?></td>
  </tr>
  <?php
  $cnt++;
  }
  CloseDb();
  ?>
</table>
<script language='JavaScript'>
            Tables('table', 1, 0);
        </script>
         </table>

</td></tr></table>
<script language="javascript">
window.print();
</script>
</body>
</html>