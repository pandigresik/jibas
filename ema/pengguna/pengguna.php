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
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/sessioninfo.php');
OpenDb();
if (isset($_REQUEST['ac'])){
	if ($_REQUEST['ac']=="nd7bw6g25gdf"){
		$sql = "DELETE FROM $db_name_user.hakakses WHERE replid='". $_REQUEST['id']."'";
		QueryDb($sql);
	}
	if ($_REQUEST['ac']=="g25gdfnd7bw6"){
		$sql = "UPDATE $db_name_user.login SET aktif='". $_REQUEST['newaktif']."' WHERE login='".$_REQUEST['login']."'";
		//echo $sql; exit;
		QueryDb($sql);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function tambah(){
	var addr = "tambahpengguna.php";
	newWindow(addr, 'TambahPengguna','366','222','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function cetak(){
	var addr = "penggunacetak.php";
	newWindow(addr, 'CetakPengguna','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function fresh(){
	document.location.href = "pengguna.php";
}
function hapus(id){
	if (confirm('Anda yakin akan menghapus pengguna ini?'))
		document.location.href = "pengguna.php?ac=nd7bw6g25gdf&id="+id;
}
function setaktif(login,newaktif){
	if (confirm('Anda yakin akan mengubah status aktif pengguna ini?'))
		document.location.href = "pengguna.php?ac=g25gdfnd7bw6&login="+login+"&newaktif="+newaktif;
}
</script>
</head>

<body>
<div align="right">
<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
    <span class="news_title2">Daftar Pengguna </span>
</div><br />
<div align="right">
<a href="javascript:cetak()"><img src="../img/print.png" border="0" />&nbsp;Cetak</a>&nbsp;&nbsp;
<?php if (is_admin()){ ?><a href="javascript:tambah()"><img src="../img/tambah.png" border="0" />&nbsp;Tambah</a><?php } ?></div>
<br />
<table width="100%" border="1" class="tab">
  <tr>
    <td height="25" align="center" class="header">No.</td>
    <td height="25" align="center" class="header">NIP</td>
    <td height="25" align="center" class="header">Nama</td>
    <td align="center" class="header">Last&nbsp;Login</td>
    <!--td height="25" align="center" class="header">Status</td-->
    <td height="25" align="center" class="header">&nbsp;</td>
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
    <!--td align="center">
    	<?php if (is_admin()){ ?>
			<?php if ($row[2]==1){ ?>
            <a href="javascript:setaktif('<?=$row[0]?>','0')"><img src="../img/aktif.png" width="16" height="16" border="0" /></a>
            <?php } else { ?>
            <a href="javascript:setaktif('<?=$row[0]?>','1')"><img src="../img/nonaktif.png" alt="" width="16" height="16" border="0" /></a>
            <?php } ?>
        <?php } else { ?>
        	<?php if ($row[2]==1){ ?>
            <img src="../img/aktif.png" width="16" height="16" border="0" />
            <?php } else { ?>
            <img src="../img/nonaktif.png" alt="" width="16" height="16" border="0" />
            <?php } ?>
        <?php } ?>        
    </td-->
    <td align="center"><?php if (is_admin()){ ?><a href="javascript:hapus('<?=$row[3]?>')"><img src="../img/hapus.png" width="16" height="16" border="0" /></a><?php } ?></td>
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
</table>

</body>
</html>