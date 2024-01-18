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
require_once('../inc/sessioninfo.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../lib/GetHeaderCetak.php');
OpenDb();
$departemen='yayasan';
$tglAwal = $_REQUEST['tglAwal'];
$tglAkhir = $_REQUEST['tglAkhir'];
$title = "<tr><td width='20'>Periode</td><td>&nbsp;:&nbsp;$tglAwal s.d. $tglAkhir</td></tr>";	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../sty/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SimTaka [Cetak Daftar Aktivitas Perpustakaan]</title>
</head>

<body>
<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=GetHeader('alls')?>

<center><font size="4"><strong>DATA AKTIVITAS PERPUSTAKAAN</strong></font><br /> </center><br /><br />

<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:verdana; font-size:12px">
<?=$title?>
</table><br>
		<?php
		$sql = "SELECT * FROM aktivitas WHERE tanggal BETWEEN '".MysqlDateFormat($tglAwal)." 00:00:00' AND '".MysqlDateFormat($tglAkhir)." 23:59:59' ORDER BY tanggal DESC";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		?>
		<link href="../sty/style.css" rel="stylesheet" type="text/css">
        <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table">
          <tr>
            <td width="50" height="30" align="center" class="header">No</td>
            <td width="80" height="30" align="left" class="header">Tanggal</td>
            <td width="*" height="30" align="left" class="header">Aktivitas</td>
            <?php if (IsAdmin()) { ?><?php } ?>
		  </tr>
          <?php
		  if ($num>0){
		  	  $cnt=0;	
			  while ($row=@mysqli_fetch_array($result)){
			  ?>
			  <tr>
				<td width="50" height="25" align="center"><?=++$cnt?></td>
				<td width="150" height="25" align="left">&nbsp;<?=substr((string) $row['tanggal'],8,2)."-".substr((string) $row['tanggal'],5,2)."-".substr((string) $row['tanggal'],0,4)." ".substr((string) $row['tanggal'],11,8)?></td>
				<td height="25" align="left">
			    <div align="justify"><?=chg_p_to_div(stripslashes((string) $row['aktivitas']))?></div>                </td>
				<?php if (IsAdmin()) { ?>
				<?php } ?>
			  </tr>
			  <?php
			  }
		  } else {
		  ?>
          <tr>
            <td height="25" colspan="4" align="center" class="nodata">Tidak ada data</td>
          </tr>
		  <?php
		  }
		  ?>	
        </table>
</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>