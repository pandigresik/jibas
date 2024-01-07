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
require_once('../../include/common.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/getheader.php');
require_once('../../include/db_functions.php');

$bulan="";
if (isset($_REQUEST['bulan']))
	$bulan=$_REQUEST['bulan'];
$tahun="";
if (isset($_REQUEST['tahun']))
	$tahun=$_REQUEST['tahun'];
$idpengirim=SI_USER_ID();
$varbaris=10;
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op == "dw8dxn8w9ms8zs22") {
    OpenDb();
	$sql = "UPDATE jbsvcr.beritasekolah SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	QueryDb($sql);
	CloseDb();
} else if ($op == "xm8r389xemx23xb2378e23") {
	$sql = "DELETE FROM jbsvcr.beritasekolah WHERE replid = '".$_REQUEST['replid']."'";
	$result = QueryDb($sql);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<link href="../../style/tooltips.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../../script/tables.js"></script>
<script language="javascript" src="../../script/tools.js"></script>
<script language="javascript" src="../../script/tooltips.js"></script> 
<script language="javascript">
function bacaberita(replid){
	//parent.frametop.buletin();
	newWindow('bacaberitasekolah.php?replid='+replid,'BacaBeritanya',738,525,'resizable=1,scrollbars=1,status=0,toolbar=0');
	
}
function fill_month_and_year(){
	var bulan=parent.beritasekolah_header.document.getElementById("bulan").value;
	var tahun=parent.beritasekolah_header.document.getElementById("tahun").value;
	//alert ('Resend');
	document.location.href="beritasekolah_footer.php?bulan="+bulan+"&tahun="+tahun;
}
function chg_page(){
	var page=document.getElementById("page").value;
	var bulan=parent.beritasekolah_header.document.getElementById("bulan").value;
	var tahun=parent.beritasekolah_header.document.getElementById("tahun").value;
	//var tahun=parent.beritaguru_header.document.getElementById("tahun").value;<link rel="stylesheet" type="text/css" href="../../style/style.css">
	//alert ('Resend');
	document.location.href="beritasekolah_footer.php?bulan="+bulan+"&tahun="+tahun+"&page="+page;
}
function ubah(replid,page){
	var bulan=parent.beritasekolah_header.document.getElementById("bulan").value;
	var tahun=parent.beritasekolah_header.document.getElementById("tahun").value;
	//alert (bulan+tahun+page+replid);
	document.location.href="beritasekolah_edit.php?replid="+replid+"&bulan="+bulan+"&tahun="+tahun+"&page="+page;
}
function hapus(replid){
	var page=document.getElementById("page").value;
	var bulan=parent.beritasekolah_header.document.getElementById("bulan").value;
	var tahun=parent.beritasekolah_header.document.getElementById("tahun").value;
	if (confirm('Anda yakin akan menghapus berita ini ?')){ 
		document.location.href="beritasekolah_footer.php?op=bzux834hx8x7x934983xihxf084&replid="+replid+"&bulan="+bulan+"&tahun="+tahun+"&page="+page;
	}
}
function change_page(page) {
	var bulan=parent.beritasekolah_header.document.getElementById("bulan").value;
	var tahun=parent.beritasekolah_header.document.getElementById("tahun").value;
	//var tahun=parent.beritaguru_header.document.getElementById("tahun").value;
	//alert ('Resend');
	document.location.href="beritasekolah_footer.php?bulan="+bulan+"&tahun="+tahun+"&page="+page;
}

function setaktif(replid, aktif) {
	var page=document.getElementById("page").value;
	var bulan=parent.beritasekolah_header.document.getElementById("bulan").value;
	var tahun=parent.beritasekolah_header.document.getElementById("tahun").value;
	var msg;
	var newaktif;
	
	if (aktif == 1) {
		msg = "Apakah anda yakin mengubah status buletin ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		msg = "Apakah anda yakin mengubah status buletin ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg)) 
		document.location.href = "beritasekolah_footer.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&bulan="+bulan+"&tahun="+tahun+"&page="+page;
}
</script>
</head>
<body <?php //if($bulan=="" && $tahun=="") { ?> <?php //} ?>><!--onload="fill_month_and_year();"-->
<input type="hidden" name="bulan" id="bulan" value="<?=$bulan?>" />
<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>" />
<table width="100%" border="0" cellspacing="0">
  <tr>
  <?php OpenDb();
  $sql_tot="SELECT b.replid as replid, b.judul as judul, DATE_FORMAT(b.tanggal, '%e %b %Y') as tanggal, TIME_FORMAT(b.tanggal, '%H:%i') as waktu, ".
  		"b.abstrak as abstrak, b.isi as isi FROM jbsvcr.beritasekolah b ".
		"WHERE MONTH(b.tanggal)='$bulan' AND YEAR(b.tanggal)='$tahun' ORDER BY replid DESC";
  //echo $sql1;
  $result_tot=QueryDb($sql_tot);
  $total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
  CloseDb();
	?>
	<td scope="row" align="left">
	<?php
	if ($total!=0){
		if ($page==0){ 
		$disback="style='visibility:hidden;position:absolute;'";
		$disnext="style='visibility:visible;position:inherit;'";
		}
		if ($page<$total && $page>0){
		$disback="style='visibility:visible;position:inherit;'";
		$disnext="style='visibility:visible;position:inherit;'";
		}
		if ($page==$total-1 && $page>0){
		$disback="style='visibility:visible;position:inherit;'";
		$disnext="style='visibility:hidden;position:absolute;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='visibility:hidden;position:absolute;'";
		$disnext="style='visibility:hidden;position:absolute;'";
		}
	
	?>
    Halaman : 
	<input <?=$disback?> type="button" class="but" title="Sebelumnya" name="back" value="<" onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
	<select name="page" id="page" onchange="chg_page()">
	<?php for ($p=1;$p<=$total;$p++){ ?>
		<option value="<?=$p-1?>" <?=StringIsSelected($page,$p-1)?>><?=$p;?></option>
	<?php } ?>
	</select>   
    <input <?=$disnext?> type="button" class="but" name="next" title="Selanjutnya" value=">" onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">&nbsp;dari&nbsp;<?=$total?> 
	<?php } ?><br><br>
	<table width="100%" border="1" cellspacing="0" class="tab" id="table">
  <tr>
    <th width="21" height="30" class="header" scope="row"><div align="center">No</div></th>
    <td width="68" height="30" class="header"><div align="center">Tanggal</div></td>
    <td width="405" height="30" class="header"><div align="center">Berita</div></td>
    <td width="178" height="30" class="header"><div align="center">Penulis</div></td>
    <td width="106" height="30" class="header"><div align="center">Jenis Berita</div></td>
    <td width="62" class="header"><div align="center">Status</div></td>
    <td width="108" height="30" class="header">&nbsp;</td>
  </tr>
  <?php
  OpenDb();
  

  $sql1="SELECT b.replid as replid, b.judul as judul, DATE_FORMAT(b.tanggal, '%e %b %Y') as tanggal, TIME_FORMAT(b.tanggal, '%H:%i') as waktu, ".
  		"IF(b.jenisberita=1,'Darurat',IF(b.jenisberita=2,'Umum','Sekolah')) as berita, b.abstrak as abstrak, b.isi as isi, b.aktif, b.idpengirim as idpengirim FROM jbsvcr.beritasekolah b ".
		"WHERE MONTH(b.tanggal)='$bulan' AND YEAR(b.tanggal)='$tahun' ORDER BY replid DESC LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
  //echo $sql1;
  $result1=QueryDb($sql1);
  if (@mysqli_num_rows($result1)>0){
  if ($page==0){
  $cnt=1;
  } else {
  $cnt=(int)$page*(int)$varbaris+1;
  }
  while ($row1=@mysqli_fetch_array($result1)){
  ?>
  <tr>
    <td scope="row"><div align="center"><?=$cnt;?></div></th>
    <td><div align="center"><?=$row1['tanggal']?><br><?=$row1['waktu']?></div></td>
    <td><strong><a href="#" onclick="bacaberita('<?=$row1['replid']?>')" ><?=$row1['judul']?></a></strong><br />
	<?php  $is=$row1['isi'];
	    echo removetag($is);
	?></td>
    <td><?php
		
	if ($row1['idpengirim']=="adminsiswa"){
		echo "Administrator Siswa";
	} elseif ($row1['idpengirim']=="landlord") {
			echo "Administrator JIBAS InfoSiswa";
	} else {
	$rs=QueryDb("SELECT nama FROM jbssdm.pegawai WHERE nip='".$row1['idpengirim']."'");
	if (@mysqli_num_rows($rs)>0){
	$rp=@mysqli_fetch_array($rs);
	$nm=$rp['nama'];
	} else {
	$rsi=QueryDb("SELECT nama FROM jbsakad.siswa WHERE nis='".$row1['idpengirim']."'");
	$rsis=@mysqli_fetch_array($rsi);
	$nm=$rsis['nama'];
	}
	echo $row1['idpengirim']."-".$nm;
	}
		?></td>
    <td align="center"><?=$row1['berita']?></td>
    <td align="center">
	<?php
	  if($row1['aktif']==1){ ?>
	    <a href="JavaScript:setaktif(<?=$row1['replid'] ?>, <?=$row1['aktif'] ?>)">
        <img src="../../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '50px')"/></a>
	<?php  } else{ ?>
        <a href="JavaScript:setaktif(<?=$row1['replid'] ?>, <?=$row1['aktif'] ?>)">
        <img src="../../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '50px')"/></a>
    <?php } ?>   
    </td>
    <td><div align="center">
    <?php if ($row1['nip']==$idguru){ ?>
    <img src="../../images/ico/ubah.png" border="0" onclick="ubah('<?=$row1['replid']?>','<?=$page?>')" style="cursor:pointer;" title="Ubah Berita ini !" />&nbsp;<img src="../../images/ico/hapus.png" border="0" onclick="hapus('<?=$row1['replid']?>')" style="cursor:pointer;" title="Hapus Berita ini !" />
    <?php } ?>
	</div></td>
  </tr>
  <?php 
  $cnt++;
  } 
  } else {?>
   <tr>
    <td scope="row" colspan="7"><div align="center">Tidak ada berita</div></th>   </tr>
  <?php } ?>
</table>

	  <script language='JavaScript'>
			//Tables('table', 1, 0);
		</script>

	</td>
  </tr>
</table>

</body>
</html>