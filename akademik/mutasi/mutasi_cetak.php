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
$mode=$_REQUEST['mode'];
$nis=$_REQUEST['nis'];
$kelas=$_REQUEST['kelas'];
$nama=$_REQUEST['nama'];
if ($mode=="text"){
if ($nis=="" && $nama<>"")
	$tambahan="AND s.nama LIKE '%$nama%'";
if ($nis<>"" && $nama=="")
	$tambahan="AND s.nis LIKE '%$nis%'";
if ($nis<>"" && $nama<>"")
	$tambahan="AND s.nis LIKE '%$nis%' OR s.nama LIKE '%$nama%'";
}
if ($mode=="kelas"){
if ($kelas<>"")
	$tambahan="AND s.idkelas='$kelas'";
}
OpenDb();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Daftar Siswa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT type="text/javascript" language="text/javascript" src="../script/tables.js"></SCRIPT>
	<SCRIPT type="text/javascript" language="javascript" src="../script/common.js"></script>
	<SCRIPT type="text/javascript" language="javascript" src="../script/tools.js"></script>
<link href="../style/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style4 {font-size: 12; font-weight: bold; }
-->
</style>
</head>


<body>
<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td valign="top">
    <br>
    <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#666666">
      <tr>
        <td colspan="2">
          <div align="center">
            <input name="action" type="hidden" id="action2" value="<?php if(!empty($_GET['action'])) echo $_GET['action'] ; else echo "tambahJenisMutasi" ;?>">
            <input name="state" type="hidden" id="state2" value="jenis">
             <strong>DAFTAR SISWA YANG BELUM DIMUTASI</strong><br>
            <br>
          </div></td>
        </tr>
      <tr>
        <td><table width="100%" border="1" align="center" cellpadding="0" cellspacing="2" bordercolor="#000000">
          <tr >
            <td width="46" height="30" bordercolor="#000000" bgcolor="#CCCCCC" class="header"><div align="center"><span class="style4">No</span></div></td>
            <td width="214" height="30" bordercolor="#000000" bgcolor="#CCCCCC" class="header"><div align="center"><span class="style4">NIS</span></div></td>
            <td width="214" height="30" bordercolor="#000000" bgcolor="#CCCCCC" class="header"><div align="center"><span class="style4">Nama</span></div></td>
            <td width="214" height="30" bordercolor="#000000" bgcolor="#CCCCCC" class="header"><div align="center"><span class="style4">Angkatan</span></div></td>
            <td width="214" height="30" bordercolor="#000000" bgcolor="#CCCCCC" class="header"><div align="center"><span class="style4">Kelas</span></div></td>
            <td width="40" height="30" bordercolor="#000000" bgcolor="#CCCCCC" class="header"><div align="center"><span class="style4">Status&nbsp;Mutasi</span></div></td>
          </tr>
		  <tr><td colspan="6">&nbsp;</td></tr>
		  <?php 
		
	$query_mutasi="SELECT s.nis,s.nama,a.angkatan,k.kelas,s.statusmutasi FROM jbsakad.siswa s, jbsakad.angkatan a, jbsakad.kelas k WHERE s.idangkatan=a.replid AND s.aktif=1 AND k.replid=s.idkelas AND a.departemen='$departemen' $tambahan ORDER BY s.nis";
	$result_mutasi=QueryDb($query_mutasi);
		  $a=0;
		  while($row_mutasi=mysqli_fetch_row($result_mutasi)){$a++;
		  ?>
          <tr>
            <td height="25" bordercolor="#000000"><?=$a; ?></td>
            <td height="25" bordercolor="#000000"><?=$row_mutasi[0]?></td>
            <td height="25" bordercolor="#000000"><?=$row_mutasi[1]?></td>
            <td height="25" bordercolor="#000000"><?=$row_mutasi[2]?></td>
            <td height="25" bordercolor="#000000"><?=$row_mutasi[3]?></td>
            <td height="25" bordercolor="#000000">
            <?php if ($row_mutasi[4]==0){ ?>
            Belum&nbsp;Dimutasi
			<?php } else { ?>
            Sudah&nbsp;Dimutasi
            <?php } ?></td>
          </tr>
		  <tr><td colspan="6">&nbsp;</td></tr>
		  <?php
		  }
		  if(mysqli_num_rows($result_mutasi)==0)
		  	{
		?>
		<tr>
			<td colspan="6" align="center" bordercolor="#000000"> "Data Belum Ada"</td>
		</tr>	
		<?php 
			}
		  ?>
        </table>  
		
		  </td>
      </tr>
    </table></td>
  </tr>
</table>
<script language="javascript">window.print();</script>
</body>
</html>
<?php
CloseDb();
?>