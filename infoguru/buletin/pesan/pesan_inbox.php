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
	
$op="";
if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];
	
if ($op=="34983xihxf084bzux834hx8x7x93"){
	$numdel=(int)$_REQUEST["numdel"]-1;
	$msgall=$_REQUEST["listdel"];
	$x=0;
	$msg=explode("|",(string) $msgall);
	while ($x<=$numdel){
		if ($msg[$x]!=""){
		OpenDb();
		//Cek ada gak di pesan terkirim punya pengirim
		$sql1 = "SELECT replid FROM jbsvcr.pesanterkirim WHERE idpesan=(SELECT p.replid as replid FROM jbsvcr.pesan p, jbsvcr.tujuanpesan t WHERE t.idpesan=p.replid AND t.replid='".$msg[$x]."')";
		//echo $sql1;
		$res1 = QueryDb($sql1);
		$exist = @mysqli_num_rows($res1);
		if ($exist==0)
		{	//Kalo gak ada, hapus semua...........
			$sql3 = "SELECT p.replid as replid FROM jbsvcr.pesan p, jbsvcr.tujuanpesan t WHERE t.idpesan=p.replid AND t.replid='".$msg[$x]."'";
			$res3 = QueryDb($sql3);
			$row3 = @mysqli_fetch_array($res3);
			$idpesan = $row3['replid'];

			$sql4 = "DELETE FROM jbsvcr.tujuanpesan WHERE replid=".$msg[$x];
			QueryDb($sql4);

			$sql5 = "SELECT * FROM jbsvcr.tujuanpesan WHERE idpesan=(SELECT p.replid as replid FROM jbsvcr.pesan p, jbsvcr.tujuanpesan t WHERE t.idpesan=p.replid AND t.replid='".$msg[$x]."') AND replid<>'$msg[$x]'";
			$res5 = QueryDb($sql5);
			if (@mysqli_num_rows($res5)==0){
			$sql6 = "DELETE FROM jbsvcr.pesan WHERE replid='$idpesan'";
			QueryDb($sql6);
			}
		} else {
			$sql4 = "UPDATE jbsvcr.tujuanpesan SET aktif=0, baru=0 WHERE replid='".$msg[$x]."'";
			QueryDb($sql4);
		}
		CloseDb();
		}
		
	$x++;
	}
}

if ($op=="baca"){
	OpenDb();
	$sql="UPDATE jbsvcr.tujuanpesan SET baru=0 WHERE replid='".$_REQUEST['replid']."'";
	$result=QueryDb($sql);
	CloseDb();
	?>
	<script language="javascript">
		document.location.href="pesanbaca.php?replid=<?=$_REQUEST['replid']?>&page=<?=$page?>";
	</script>
	<?php
	
}

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
	document.location.href="pesan_inbox.php?bulan="+bulan+"&tahun="+tahun;
}
function chg_page(){
	var page=document.getElementById("page").value;
	document.location.href="pesan_inbox.php?page="+page;
}
function change_page(page) {
	document.location.href="pesan_inbox.php?page="+page;
}
function ubah(replid){
	document.location.href="pesanguru_ubah_main.php?replid="+replid;
}
function bacapesan(replid){
	var page=document.getElementById("page").value;
	document.location.href="pesan_inbox.php?op=baca&replid="+replid+"&page="+page;
}
function hapus(replid){
	var page=document.getElementById("page").value;
	var bulan=parent.pesanguru_header.document.getElementById("bulan").value;
	var tahun=parent.pesanguru_header.document.getElementById("tahun").value;
	if (confirm('Anda yakin akan menghapus pesan ini dan lampiran-lampirannya ?')){ 
		document.location.href="pesan_inbox.php?op=bzux834hx8x7x934983xihxf084&replid="+replid+"&bulan="+bulan+"&tahun="+tahun+"&page="+page;
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
			document.location.href="pesan_inbox.php?op=34983xihxf084bzux834hx8x7x93&listdel="+list+"&numdel="+num;
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
			document.location.href="pesan_inbox.php?op=f3fxxa7svys774l3067den747hhd783uu83&listdel="+list+"&numdel="+num;
		} else {
			document.getElementById("listdel").value="";
		}
	}
}
function load()
{
	document.location.href="pesan_inbox.php";
}
function loadawal()
{
	setInterval("load()",60000);
}
</script>
</head>
<body onLoad="loadawal()" >
<form name="inbox" id="inbox" action="pesan_inbox.php">
<div align="right">
<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Kotak Masuk</font><br />
    <a href="pesan.php" target="framecenter">
      <font size="1" color="#000000"><b>Pesan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Kotak Masuk</b></font>
</div><br />
<input type="hidden" name="bulan" id="bulan" value="<?=$bulan?>" />
<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>" />
<table width="100%" border="0" cellspacing="0">
  <tr>
  <?php OpenDb();
  $sql_tot="SELECT * FROM jbsvcr.tujuanpesan t, jbsvcr.pesan p WHERE t.idpenerima='".SI_USER_ID()."' AND t.idpesan=p.replid AND t.aktif=1";
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
	<select name="page" id="page" onChange="chg_page()">
	<?php for ($p=1;$p<=$total;$p++){ ?>
		<option value="<?=$p-1?>" <?=StringIsSelected($page,$p-1)?>><?=$p;?></option>
	<?php } ?>
	</select>   
    <input <?=$disnext?> type="button" class="but" name="next" title="Selanjutnya" value=">" onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">&nbsp;dari&nbsp;<?=$total?> 
	<?php } ?><br><br>
	
    <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table">
	 <tr>
		  <th width="3%" height="30" class="header" scope="row" align="center">No</th>
		  <td width="3%" height="20" class="header" align="center">
				<input type="checkbox" name="cek" id="cek" onclick="cek_all()" title="Pilih semua" onmouseover="showhint('Pilih semua', this, event, '120px')"/>
		  </td>
		  <td width="5%" height="30" class="header">&nbsp;</td>
		  <td width="12%" class="header"><div align="center">Tanggal</div></td>
		  <td width="20%" height="30" class="header"><div align="center">Pengirim</div></td>
	     <td width="*" class="header"><div align="center">Judul</div></td>
   </tr>
<?php
	 OpenDb();
	 $sql1 = "SELECT pg.idguru as idguru,pg.nis as nis,t.replid as replid,
						  pg.replid as replid2, pg.judul as judul, DATE_FORMAT(pg.tanggalpesan, '%e %b %Y') as tanggal,
						  TIME_FORMAT(pg.tanggalpesan, '%H:%i') as waktu, t.baru as baru
				   FROM jbsvcr.pesan pg, jbsvcr.tujuanpesan t
				  WHERE t.idpesan=pg.replid AND t.idpenerima='".SI_USER_ID()."' AND t.aktif=1
				  ORDER BY t.baru DESC, pg.tanggalpesan DESC, pg.replid
				   DESC LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	 $result1 = QueryDb($sql1);
	 if (@mysqli_num_rows($result1) > 0)
	 {
	   $clrcnt=1;
	   if ($page==0)
		{
		  $cnt=1;
	   }
		else
		{
		    $cnt=(int)$page*(int)$varbaris+1;
		}
		$numpesan=@mysqli_num_rows($result1);
		$count=1;
  
		while ($row1=@mysqli_fetch_array($result1))
		{
			 $depan="";
			 $belakang="";
		  	 if ($row1['baru']==1)
			 {
				$depan="<strong>";
				$belakang="</strong>";
				$color = "style=\"background-color:#fcf7c1;\"";
			 }
			 else
			 {
				$color = "style=\"background-color:#FFFFFF;\"";
			 }  ?>
			 <tr <?=$color?> height="25">
				  <td align="center"> <?=$cnt?> </td>
				  <td align="center">
					 <input type="checkbox" onClick="chg('<?=$count?>')" name="cekpesan<?=$count?>" id="cekpesan<?=$count?>"/>
					 <input type="hidden" name="delete<?=$count?>" id="delete<?=$count?>"/>
					 <input type="hidden" name="rep<?=$count?>" id="rep<?=$count?>" value="<?=$row1['replid']?>"/>
				  </td>
				  <td align="left">
				  <?php if ($row1['baru']==1) { ?>
					 <img src="../../images/ico/unread.png" width="16" height="13" title="Belum dibaca..." />
					 <img src="../../images/ico/new.png" width="10" height="14" />
				  <?php } else { ?>
					 <img src="../../images/ico/readen.png" width="15" height="14" title="Sudah dibaca..." />
				  <?php } ?>
				  </td>
				  <td>
					 <?=$depan?><div align="center"><?=$row1['tanggal']?><br><?=$row1['waktu']?></div><?=$belakang?>
				  </td>
				  <td>
					 <?=$depan?>
				  <?php if ($row1['nis']!="")
					  {
						  $r_sis=QueryDb("SELECT nis,nama FROM jbsakad.siswa WHERE nis='".$row1['nis']."'");
						  $row_sis=@mysqli_fetch_array($r_sis);
						  $id=$row_sis['nis'];
						  $nm=$row_sis['nama'];
						  $gol=" ['Siswa']";
					  }
					  if ($row1['idguru']!="")
					  {
						  $r_gr=QueryDb("SELECT nip,nama FROM jbssdm.pegawai WHERE nip='".$row1['idguru']."'");
						  $row_gr=@mysqli_fetch_array($r_gr);
						  $id=$row_gr['nip'];
						  $nm=$row_gr['nama'];
						  $gol=" ['Guru']";
					  }
					  echo $id."-".$nm."&nbsp;&nbsp;".$gol;
				  ?>
					 <?=$belakang?>
				  </td>
				  <td align="left">
					 <?=$depan?>
					 <a href="#" onClick="bacapesan('<?=$row1['replid']?>')">
					 <?=$row1['judul']?>
					 </a>
					 <?=$belakang?>
				  </td>
			 </tr>
<?php   		$cnt++;
	   } // while
  }
  else
  { ?>
	 <tr>
		<td scope="row" colspan="9">
		<div align="center"  class="divNotif"">Tidak ada pesan di kotak Masuk Anda</div>
		</td>
	 </tr>
<?php
  }
?>
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
<script language='JavaScript'>
   Tables('table', 1, 0);
</script>