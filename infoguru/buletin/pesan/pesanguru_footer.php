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
require_once('../../include/db_functions.php');
require_once('../../include/sessionchecker.php');

$op="";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];
	
if ($op=="34983xihxf084bzux834hx8x7x93")
{
	$numdel=(int)$_REQUEST["numdel"]-1;
	$msgall=$_REQUEST["listdel"];
	$x=0;
	$msg=explode("|",(string) $msgall);

	OpenDb();	
	while ($x<=$numdel)
	{
		if ($msg[$x] != "")
		{
		   $sql1 = "SELECT replid FROM jbsvcr.pesanterkirim WHERE idpesan=(SELECT p.replid as replid FROM jbsvcr.pesan p, jbsvcr.tujuanpesan t WHERE t.idpesan=p.replid AND t.replid='".$msg[$x]."')";
		   $res1 = QueryDb($sql1);
		   $exist = @mysqli_num_rows($res1);
		   if ($exist==0)
		   {
			   $sql3 = "SELECT p.replid as replid FROM jbsvcr.pesan p, jbsvcr.tujuanpesan t WHERE t.idpesan=p.replid AND t.replid='".$msg[$x]."'";
			   $res3 = QueryDb($sql3);
			   $row3 = @mysqli_fetch_array($res3);
			   $idpesan = $row3['replid'];

			   $sql4 = "DELETE FROM jbsvcr.tujuanpesan WHERE replid='".$msg[$x]."'";
			   QueryDb($sql4);

			   $sql5 = "SELECT * FROM jbsvcr.tujuanpesan WHERE idpesan=(SELECT p.replid as replid FROM jbsvcr.pesan p, jbsvcr.tujuanpesan t WHERE t.idpesan=p.replid AND t.replid='".$msg[$x]."') AND replid<>'$msg[$x]'";
			   $res5 = QueryDb($sql5);
			   if (@mysqli_num_rows($res5)==0)
			   {
				  $sql6 = "DELETE FROM jbsvcr.pesan WHERE replid='$idpesan'";
				  QueryDb($sql6);
			   }
		   }
		   else
		   {
			   $sql4 = "UPDATE jbsvcr.tujuanpesan SET aktif=0, baru=0 WHERE replid='".$msg[$x]."'";
			   QueryDb($sql4);
		   }
		}
		$x++;
	}
	CloseDb();
}

if ($op=="baca")
{
	OpenDb();
	$sql="UPDATE jbsvcr.tujuanpesan SET baru=0 WHERE replid='".$_REQUEST['replid']."'";
	$result=QueryDb($sql);
	CloseDb();
	?>
	<script language="javascript">
		document.location.href="pesanbaca.php?replid=<?=$_REQUEST['replid']?>";
	</script>
	<?php
}

$bulan="";
if (isset($_REQUEST['bulan']))
	$bulan=$_REQUEST['bulan'];
$tahun="";
if (isset($_REQUEST['tahun']))
	$tahun=$_REQUEST['tahun'];
$idguru=SI_USER_ID();
$varbaris=10;
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../../script/tables.js"></script>
<script language="javascript" src="../../script/tools.js"></script>
<script language="javascript">
function fill_month_and_year(){
	var bulan=parent.pesanguru_header.document.getElementById("bulan").value;
	var tahun=parent.pesanguru_header.document.getElementById("tahun").value;
	document.location.href="pesanguru_footer.php?bulan="+bulan+"&tahun="+tahun;
}
function chg_page(){
	var page=document.getElementById("page").value;
	document.location.href="pesanguru_footer.php?page="+page;
}
function change_page(page) {
	document.location.href="pesanguru_footer.php?page="+page;
}
function ubah(replid){
	document.location.href="pesanguru_ubah_main.php?replid="+replid;
}
function bacapesan(replid){
	var page=document.getElementById("page").value;
	document.location.href="pesanguru_footer.php?op=baca&replid="+replid+"&page="+page;
}
function hapus(replid){
	var page=document.getElementById("page").value;
	var bulan=parent.pesanguru_header.document.getElementById("bulan").value;
	var tahun=parent.pesanguru_header.document.getElementById("tahun").value;
	if (confirm('Anda yakin akan menghapus pesan ini dan lampiran-lampirannya ?')){ 
		document.location.href="pesanguru_footer.php?op=bzux834hx8x7x934983xihxf084&replid="+replid+"&bulan="+bulan+"&tahun="+tahun+"&page="+page;
	}
}
function chg(i) {
	document.getElementById("listdel").value="";
	document.inbox.cek.checked=false;
}
function cek_all() {
	var x;
	var jum = document.inbox.numpesan.value;
	var ceked = document.inbox.cek.checked;
	for (x=1;x<=jum;x++){
		if (ceked==true){
			document.getElementById("cekpesan"+x).checked=true;
		} else {
			document.getElementById("cekpesan"+x).checked=false;
		}
	}
}
function delpesan(){
	var x;
	var y=0;
	var jum = document.inbox.numpesan.value;
	for (x=1;x<=jum;x++){
		var ceked = document.getElementById("cekpesan"+x).checked;
		var rep = document.getElementById("rep"+x).value;
		var listdel=document.getElementById('listdel').value;
		if (ceked==true){
			if (y==0)
				y=y+1;
			document.getElementById('listdel').value=listdel+rep+"|";
			document.getElementById('numdel').value=y++;
		}
	}
	var num = document.inbox.numdel.value;
	var list = document.inbox.listdel.value;
	if (list.length==0){
		alert ('Minimal ada satu pesan yang akan dihapus');
		return false;
	} else {
		if (confirm('Anda yakin akan menghapus pesan ini?')){
			document.location.href="pesanguru_footer.php?op=34983xihxf084bzux834hx8x7x93&listdel="+list+"&numdel="+num;
		} else {
			document.getElementById("listdel").value="";
		}
	}
}
function savepesan(){
	var x;
	var y=0;
	var jum = document.inbox.numpesan.value;
	for (x=1;x<=jum;x++){
		var ceked = document.getElementById("cekpesan"+x).checked;
		var rep = document.getElementById("rep"+x).value;
		var listdel=document.getElementById('listdel').value;
		if (ceked==true){
			if (y==0)
				y=y+1;
			document.getElementById('listdel').value=listdel+rep+"|";
			document.getElementById('numdel').value=y++;
		}
	}
	var num = document.inbox.numdel.value;
	var list = document.inbox.listdel.value;
	if (list.length==0){
		alert ('Minimal ada satu pesan yang akan dipindahkan ke draft');
		return false;
	} else {
		if (confirm('Anda yakin akan memindahkan pesan ini ke draft?')){
			document.location.href="pesanguru_footer.php?op=f3fxxa7svys774l3067den747hhd783uu83&listdel="+list+"&numdel="+num;
		} else {
			document.getElementById("listdel").value="";
		}
	}
}
</script>
</head>
<body >
<form name="inbox" id="inbox"  action="pesanguru_footer.php">
<div align="right">
<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Kotak Masuk</font><br />
    <a href="pesanguru.php" target="framecenter">
      <font size="1" color="#000000"><b>Pesan Guru</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Kotak Masuk</b></font>
</div><br />
<input type="hidden" name="bulan" id="bulan" value="<?=$bulan?>" />
<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>" />
<table width="100%" border="0" cellspacing="0">
  <tr>
  <?php OpenDb();
  $sql_tot="SELECT * FROM jbsvcr.tujuanpesan t, jbsvcr.pesan p WHERE t.idpenerima='".SI_USER_ID()."' AND t.idpesan=p.replid AND p.idguru<>'' AND t.aktif=1";
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
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tab" id="table">
  <tr>
    <th width="31" height="30" class="header" scope="row"><div align="center">No</div></th>
    <td width="28" height="30" class="header"><div align="center"><input type="checkbox" name="cek" id="cek" onClick="cek_all()" title="Pilih semua" onMouseOver="showhint('Pilih semua', this, event, '120px')"/></div></td>
	<td width="117" class="header"><div align="center">Tanggal</div></td>
    <td width="492" height="30" class="header"><div align="center">Pengirim</div></td>
    <td width="22" height="30" class="header" title="Lampiran"><div align="center"></div></td>
    <td width="240" class="header"><div align="center">Judul</div></td>
   	
   </tr>
  <?php
  OpenDb();
  
	$sql1="SELECT p.nama as nama,p.nip as nip,t.replid as replid, pg.replid as replid2, pg.judul as judul, DATE_FORMAT(pg.tanggalpesan, '%e %b %Y') as tanggal, TIME_FORMAT(pg.tanggalpesan, '%H:%i') as waktu, t.baru as baru FROM jbsvcr.pesan pg, jbsvcr.tujuanpesan t, jbssdm.pegawai p WHERE t.idpesan=pg.replid AND t.idpenerima='".SI_USER_ID()."' AND pg.idguru=p.nip AND pg.idguru<>'' AND t.aktif=1 ORDER BY pg.tanggalpesan DESC, pg.replid DESC LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
  $result1=QueryDb($sql1);
  if (@mysqli_num_rows($result1)>0){
  if ($page==0){
  $cnt=1;
  } else {
  $cnt=(int)$page*(int)$varbaris+1;
  }
  $numpesan=@mysqli_num_rows($result1);
  $count=1;
  while ($row1=@mysqli_fetch_array($result1)){
  $depan="";
  $belakang="";
  $tr="";
  if ($row1['baru']==1){
  	 	$depan="<strong>";
  		$belakang="</strong>";
		$tr="style=\"background-color:#fcf7c1;\"";
  } else {
	  $tr="style=\"background-color:#FFFFFF;\"";
  }
  ?>
  <tr <?=$tr?> >
    <td height="25" scope="row"><div align="center">
      <?=$cnt?>
    </div></td>
    <td height="25" align="center">
	<input type="checkbox" onclick="chg('<?=$count?>')" name="cekpesan<?=$count?>" id="cekpesan<?=$count?>"/>
	<input type="hidden" name="delete<?=$count?>" id="delete<?=$count?>"/>
	<input type="hidden" name="rep<?=$count?>" id="rep<?=$count?>" value="<?=$row1['replid']?>"/>	</td>
	<td height="25"><?=$depan?><div align="center"><?=$row1['tanggal']?><br><?=$row1['waktu']?></div><?=$belakang?></td>
    <td height="25"><?=$depan?><?=$row1['nip']?>-<?=$row1['nama']?><?=$belakang?></td>
    <?php 
	$sql2="SELECT direktori,namafile FROM jbsvcr.lampiranpesan WHERE idpesan='".$row1[\REPLID2]."'";
	$result2=QueryDb($sql2); 
	?>
    <td width="22" height="25"><?=$depan?><?php if (@mysqli_num_rows($result2)>0){ ?><img title="Disertai Lampiran" src="../../images/ico/attachment1.png"/><?php } ?><?=$belakang?></td>
    <td height="25"><?=$depan?><?php if ($row1['baru']==1) { ?><img title="Belum dibaca..." src="../../images/ico/unread.png" /><?php } else { ?><img src="../../images/ico/readen.png" title="Sudah dibaca..." /><?php } ?><a href="#" onClick="bacapesan('<?=$row1['replid']?>')">
	<?php 
	$judul=substr((string) $row1['judul'],0,20);
	if (strlen((string) $row1['judul'])>20){
	echo $judul." ...";
	} else {
	echo $judul;
	}
	?></a><?=$belakang?></td>
    <!--<td><?=$depan?>
    <?php
	//while ($row2=@mysqli_fetch_array($result2)){
		//echo "<a title='Buka lampiran ini!' href=\"#\" onclick=newWindow('".$row2['direktori'].$row2['namafile']."','View',640,480,'resizable=1'); ><img border='0' src='../../images/ico/titik.png' width='5' heiht='5'/> ".$row2['namafile']."</a><br>";
	//}
	?><?=$belakang?></td>-->
    
  </tr>
  <?php 
  $cnt++;
	$count++;
  } 
  } else {?>
   <tr>
    <td scope="row" colspan="8"><div align="center">Tidak ada pesan guru di kotak Masuk Anda</div></th>   </tr>
  <?php } ?>
</table>
	</td>
  </tr>
</table>
<input type="hidden" name="numpesan" id="numpesan" value="<?=$numpesan?>">
<input type="hidden" name="listdel" id="listdel">
<input type="hidden" name="numdel" id="numdel">
<?php if ($numpesan>0){ ?>
<input type="button" class="but" name="del_pesan" id="del_pesan" value="Hapus Pesan Terpilih" onClick="delpesan()">
<?php } ?>
</form>
</body>
</html>