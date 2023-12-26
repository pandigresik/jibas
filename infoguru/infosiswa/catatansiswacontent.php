<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 2.6.0 (January 14, 2012)
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
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once("../include/sessionchecker.php");

$nis = "";
if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];
$tahunajaran = "";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
$idkategori = "";
if (isset($_REQUEST['idkategori']))
	$idkategori = $_REQUEST['idkategori'];	
OpenDb();
$res=QueryDb("SELECT kategori FROM jbsvcr.catatankategori WHERE replid='$idkategori'");
$row=@mysqli_fetch_array($res);
$namakat=$row['kategori'];
CloseDb();	
OpenDb();
$res=QueryDb("SELECT tahunajaran FROM jbsakad.tahunajaran WHERE replid='$tahunajaran'");
$row=@mysqli_fetch_array($res);
$namathnajrn=$row['tahunajaran'];
CloseDb();
$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];
if ($op=="kwe9823h98hd29h98hd9h"){
	OpenDb();
	$sql="DELETE FROM jbsvcr.catatansiswa WHERE replid='".$_REQUEST['replid']."'";
	QueryDb($sql);
	?>
	<script language="javascript" type="text/javascript">
		parent.catatansiswamenu.willshow('<?=$idkategori?>');
	</script>
	<?php
	CloseDb();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../script/tables.js"></script>
<script language="javascript" type="text/javascript">
function get_fresh(){
	var nis = document.getElementById('nis').value;
	var idkategori = document.getElementById('idkategori').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	document.location.href="catatansiswacontent.php?nis="+nis+"&idkategori="+idkategori+"&tahunajaran="+tahunajaran;
}
function ubah(replid){
	var nis = document.getElementById('nis').value;
	var idkategori = document.getElementById('idkategori').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	document.location.href="catatansiswaedit.php?replid="+replid+"&nis="+nis+"&idkategori="+idkategori+"&tahunajaran="+tahunajaran;
}
function hapus(replid){
	var nis = document.getElementById('nis').value;
	var idkategori = document.getElementById('idkategori').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	if (confirm('Anda yakin akan menghapus catatan siswa ini ?'))
	document.location.href="catatansiswacontent.php?op=kwe9823h98hd29h98hd9h&replid="+replid+"&nis="+nis+"&idkategori="+idkategori+"&tahunajaran="+tahunajaran;
}
</script>
</head>

<body leftmargin="5" topmargin="10">
<input type="hidden" id="nis" name="nis" value="<?=$nis?>">
<input type="hidden" id="tahunajaran" name="tahunajaran" value="<?=$tahunajaran?>">
<input type="hidden" id="idkategori" name="idkategori" value="<?=$idkategori?>">
<font style="font-size: 18px;"><?=$namakat?></font><BR>
Tahun Ajaran:<?=$namathnajrn?>
<BR><BR>
<table width="100%" border="1" cellspacing="0" class="tab" style="border-color: #ddd; border-width: 1px; border-collapse: collapse" >
  <tr>
    <td height="30" width="3%" class="header">No</td>
    <td height="30" width="15%" class="header">Tanggal/Guru</td>
    <td height="30" width="*" class="header">Catatan</td>
    <td height="30" width="5%" class="header">&nbsp;</td>
  </tr>
  <?php
  OpenDb();
  $sql="SELECT c.replid as replid,c.judul as judul, c.catatan as catatan, c.nip as nip, p.nama as nama, c.tanggal as tanggal ".
  	   "FROM jbsvcr.catatansiswa c, jbssdm.pegawai p, jbsakad.kelas k ".
	   "WHERE c.nis='$nis' AND c.idkelas=k.replid AND k.idtahunajaran='$tahunajaran' AND p.nip=c.nip AND c.idkategori='$idkategori'";
  //echo $sql;
  //exit;
  $result=QueryDb($sql);
  $num=@mysqli_num_rows($result);
  if ($num>0){
  	$cnt=1;
	while ($row=@mysqli_fetch_array($result)){
  ?>
  <tr>
    <td height="25" valign="top" align="center"><?=$cnt?></td>
    <td height="25" valign="top"><i><?=ShortDateFormat($row['tanggal'])?></i><br />
        <strong><?=$row['nama']?><br><?=$row['nip']?><strong>
    </td>
    <td height="25" valign="top"><strong><?=$row['judul']?></strong><br />
    <?=$row['catatan']?></td>
    <td height="25" valign="top">
	<?php
    if ($row['nip']==SI_USER_ID()){
	?>
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td><img onClick="ubah('<?=$row['replid']?>')" src="../images/ico/ubah.png" style="cursor:pointer" /></td>
        <td><img onClick="hapus('<?=$row['replid']?>')" src="../images/ico/hapus.png" style="cursor:pointer" /></td>
      </tr>
    </table>
    <?php } ?>
    </td>
  </tr>
  <?php $cnt++;
  	}
  } else { ?>
  <tr>
    <td height="25" colspan="4"><div align="center"><em>Tidak ada catatan Kejadian Siswa untuk NIS : 
      <?=$nis?>
    </em></div></td>
  </tr>
  <?php } ?>
</table>

</body>
</html>

<script language="javascript">
//var sprytextfield = new Spry.Widget.ValidationTextField("judul");
//var spryselect = new Spry.Widget.ValidationSelect("kategori");
</script>
<script language='JavaScript'>
	//Tables('table', 1, 0);
</script>