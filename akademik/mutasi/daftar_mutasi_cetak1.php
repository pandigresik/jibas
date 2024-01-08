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
if (isset($_REQUEST['tahun'])){
$tahun=$_REQUEST['tahun'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Daftar Mutasi Siswa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT type="text/javascript" language="text/javascript" src="../script/tables.js"></SCRIPT>
	<SCRIPT type="text/javascript" language="javascript" src="../script/common.js"></script>
	<SCRIPT type="text/javascript" language="javascript" src="../script/tools.js"></script>
	<SCRIPT type="text/javascript" language="javascript" src="../script/tooltips.js"></script>
	<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<link href="../style/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style5 {
	font-size: 14px;
	font-weight: bold;
}
.style7 {font-size: 12; font-weight: bold; }
-->
</style>
</head>

<body>
<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td valign="top"><?php include('../library/headercetak.php'); ?></td>
  </tr>
  <tr>
    <td valign="top">
    <br>
    <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#666666">
      <tr>
        <td colspan="2">
          
          
          <div align="center" class="style5">DAFTAR SISWA YANG SUDAH DIMUTASI          </div>
          <div align="left"><br>
              <strong>Departemen :
              <?=$departemen?>
              <br>
              <?php if (isset($_REQUEST['tahun'])){ ?>
              Tahun Mutasi : 
              <?=$tahun?><?php } ?>
                </strong></div>
          <br>          </td>
        </tr>
      <tr>
        <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="2" id="table" class="tab">
          <tr bgcolor="#CCCCCC">
            <td width="32" height="30" class="header"><div align="center"><span class="style7">No</span></div></td>
            <td width="178" height="30" class="header"><div align="center"><span class="style7">NIS</span></div></td>
            <td width="276" height="30" class="header"><div align="center"><span class="style7">Nama</span></div></td>
            <td width="146" height="30" class="header"><div align="center"><span class="style7">Tanggal Mutasi</span></div></td>
            <td width="123" height="30" class="header"><div align="center"><span class="style7">Jenis Mutasi </span></div></td>
            <td width="198" height="30" class="header"><div align="center"><span class="style7">Keterangan Mutasi</span></div></td>
          </tr>
		  <tr><td colspan="6">&nbsp;</td></tr>
		  <?php 
	OpenDb();
	if (isset($_REQUEST['tahun'])){	
	$query_mutasi="SELECT s.nis,s.nama,s.statusmutasi,j.jenismutasi,m.tglmutasi,m.keterangan FROM jbsakad.siswa s, jbsakad.jenismutasi j, jbsakad.angkatan a, jbsakad.departemen d, jbsakad.mutasisiswa m WHERE d.departemen='$departemen' AND d.departemen=a.departemen AND a.replid=s.idangkatan AND j.replid=s.statusmutasi AND j.replid=m.jenismutasi AND YEAR(m.tglmutasi)='$tahun' AND s.nis=m.nis ORDER BY j.replid";
	} else {
	$query_mutasi="SELECT s.nis,s.nama,s.statusmutasi,j.jenismutasi,m.tglmutasi,m.keterangan FROM jbsakad.siswa s, jbsakad.jenismutasi j, jbsakad.angkatan a, jbsakad.departemen d, jbsakad.mutasisiswa m WHERE d.departemen='$departemen' AND d.departemen=a.departemen AND a.replid=s.idangkatan AND j.replid=s.statusmutasi AND j.replid=m.jenismutasi AND s.nis=m.nis ORDER BY j.replid";
	}
	$result_mutasi=QueryDb($query_mutasi);
		  $a=0;
		  while($row_mutasi=mysqli_fetch_row($result_mutasi)){$a++;
		  ?>
          <tr>
            <td height="25"><?=$a; ?></td>
            <td height="25"><?=$row_mutasi[0]?></td>
            <td height="25"><?=$row_mutasi[1]?></td>
            <td height="25"><?=TglTextLong($row_mutasi[4])?></td>
            <td height="25"><?=$row_mutasi[3]?></td>
            <td height="25"><?=$row_mutasi[5]?></td>
          </tr>
          <tr><td colspan="8" align="center">&nbsp;</td></tr>
		  <?php
		  }
		  if(mysqli_num_rows($result_mutasi)==0)
		  	{
		?>
		<tr>
			<td height="25" colspan="8" align="center"> "Data Belum Ada"</td>
		</tr>	
		<?php 
			}
		CloseDb();
		  ?>
        </table>		</td>
      </tr>
    </table></td>
  </tr>
</table>
<script language="javascript">window.print();</script>
</body>
</html>