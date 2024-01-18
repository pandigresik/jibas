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
	$op=$_REQUEST['op'];
$varbaris=10;
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];

if ($op=="bzux834hx8x7x934983xihxf084"){
	OpenDb();
	//cek di tujuan yang belum dihapus sama penerima (aktif=1)
	$sql_cek_tujuan="SELECT * FROM jbsvcr.tujuanpesan WHERE idpesan=(SELECT idpesan FROM jbsvcr.pesanterkirim WHERE replid='".$_REQUEST['replid']."') AND aktif=1";
	//echo "sql_cek_tujuan = ".$sql_cek_tujuan."<br>";
	$res_cek_tujuan=QueryDb($sql_cek_tujuan);
	$tujuanexist=@mysqli_num_rows($res_cek_tujuan);
	if ($tujuanexist==0){ //Kalo gak ada, lsg hapus aja semuanya...
		$sql_get_idpesan="SELECT idpesan FROM jbsvcr.pesanterkirim WHERE replid='".$_REQUEST['replid']."'";
		$res_get_idpesan=QueryDb($sql_get_idpesan);
		$row_get_idpesan=@mysqli_fetch_array($res_get_idpesan);
		$idpesan=$row_get_idpesan['idpesan'];
		
		$sql_del_tujuan="DELETE FROM jbsvcr.tujuanpesan WHERE idpesan='$idpesan'";
		QueryDb($sql_del_tujuan);
		
		$sql_del_terkirim="DELETE FROM jbsvcr.pesanterkirim WHERE replid='".$_REQUEST['replid']."'";
		QueryDb($sql_del_terkirim);
		
		$sql_del_terkirim="DELETE FROM jbsvcr.pesan WHERE replid='$idpesan'";
		QueryDb($sql_del_terkirim);

	} else { //Kalo ada pesan yang belum dibaca
		$sql_del_terkirim="DELETE FROM jbsvcr.pesanterkirim WHERE replid='".$_REQUEST['replid']."'";
		QueryDb($sql_del_terkirim);
	}
	CloseDb();
}
if ($op=="baca"){
	OpenDb();
	$sql="UPDATE jbsvcr.tujuanpesan SET baru=0 WHERE replid='".$_REQUEST['replid']."'";
	//echo $sql;
	//exit;
	$result=QueryDb($sql);
	CloseDb();
	?>
	<script language="javascript">
		document.location.href="pesangurubaca.php?replid=<?=$_REQUEST['replid']?>";
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
	document.location.href="pesan_terkirim.php?page="+page;
}
function change_page(page) {
	
	//var tahun=parent.beritaguru_header.document.getElementById("tahun").value;
	//alert ('Resend');
	document.location.href="pesan_terkirim.php?page="+page;
}
function ubah(replid){
	document.location.href="pesan_ubah_main.php?replid="+replid;
}
function bacapesan(replid){
	var page=document.getElementById("page").value;
	document.location.href="pesanbaca_terkirim.php?op=baca&replid="+replid+"&page="+page;
}
function hapus(replid){
	var page=document.getElementById("page").value;
	if (confirm('Anda yakin akan menghapus pesan ini dan lampiran-lampirannya ?')){ 
		document.location.href="pesan_terkirim.php?op=bzux834hx8x7x934983xihxf084&replid="+replid+"&page="+page;
	}
}
function cek_all() {
	//alert ('Masuk');
	var x;
	var jum = document.inbox.numpesan.value;
	var ceked = document.inbox.cek.checked;
	//alert (''+ceked);
	for (x=1;x<=jum;x++){
		if (ceked==true){
			document.getElementById("cekpesan"+x).checked=true;
		} else {
			document.getElementById("cekpesan"+x).checked=false;
		}
	}
}
function delpesan(){
	//alert ('Masuk Hapus');
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
	if (listdel.length==0){
		alert ('Minimal ada satu pesan yang akan dihapus');
		return false;
	} else {
		if (confirm('Anda yakin akan menghapus pesan ini?')){
			document.location.href="pesan_terkirim.php?op=34983xihxf084bzux834hx8x7x93&listdel="+list+"&numdel="+num;
		}
	}
}
</script>
</head>
<body >
<input type="hidden" name="bulan" id="bulan" value="<?=$bulan?>" />
<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>" />
<div align="right">
<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pesan Terkirim</font><br />
    <a href="pesan.php" target="framecenter">
      <font size="1" color="#000000"><b>Pesan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Pesan Terkirim</b></font>
</div><br />
<table width="100%" border="0" cellspacing="0">
  <tr>
  <?php OpenDb();
  /*$sql_tot="SELECT pg.replid as replid, pg.judul as judul, DATE_FORMAT(pg.tanggalpesan, '%e %b %Y') as tanggal, TIME_FORMAT(pg.tanggalpesan, '%H:%i') as waktu, ".
  		"pg.pesan as pesan, p.nama as nama, p.nip as nip FROM jbsvcr.pesanguru pg, jbssdm.pegawai p, jbsvcr.tujuanpesanguru t ".
		"WHERE pg.replid=t.idpesan AND p.nip=pg.idguru ORDER BY pg.tanggalpesan DESC ";
  */
  $sql_tot="SELECT * FROM jbsvcr.pesanterkirim pt, jbsvcr.pesan p WHERE p.idguru='".SI_USER_ID()."' AND pt.idpesan=p.replid";
  //echo $sql_tot;
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
    <table width="100%" border="1" cellspacing="0" class="tab" id="table">
  <tr>
    <th width="22" height="30" class="header" scope="row"><div align="center">No</div></th>
    <td width="69" height="30" class="header"><div align="center">Tanggal</div></td>
    <td width="184" height="30" class="header"><div align="center">Penerima</div></td>
    <td width="428" height="30" class="header"><div align="center">Judul</div></td>
    <td width="70" height="30" class="header">&nbsp;</td>
  </tr>
  <?php
  OpenDb();
  
  $sql1="SELECT pt.replid as replid_tkrm, pg.replid as replid, pg.judul as judul, DATE_FORMAT(pg.tanggalpesan, '%e %b %Y') as tanggal, TIME_FORMAT(pg.tanggalpesan, '%H:%i') as waktu FROM jbsvcr.pesan pg, jbsvcr.pesanterkirim pt WHERE pg.idguru='".SI_USER_ID()."' AND pt.idpesan=pg.replid ORDER BY pg.tanggalpesan DESC, pg.replid DESC LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
  /*$sql1="SELECT pg.replid as replid, pg.judul as judul, DATE_FORMAT(pg.tanggalpesan, '%e %b %Y') as tanggal, TIME_FORMAT(pg.tanggalpesan, '%H:%i') as waktu, t.baru as baru, t.replid as replid2, ".
  		"pg.pesan as pesan, p.nama as nama, p.nip as nip FROM jbsvcr.pesanguru pg, jbssdm.pegawai p, jbsvcr.tujuanpesanguru t ".
		"WHERE pg.replid=t.idpesan AND p.nip=pg.idguru ORDER BY replid LIMIT ".(int)$page*(int)$varbaris.",$varbaris";*/
  $result1=QueryDb($sql1);
  $numpesan=@mysqli_num_rows($result1);
  if (@mysqli_num_rows($result1)>0){
  if ($page==0){
  $cnt=1;
  } else {
  $cnt=(int)$page*(int)$varbaris+1;
  }
  while ($row1=@mysqli_fetch_array($result1)){
  //$trstyle="style='background-color:#FFFFFF'";
  if ($cnt%2==0)
	//  $trstyle="style='background-color:#FFFFCC'";
  ?>
  <tr <?=$trstyle?>>
    <td scope="row"><div align="center"><?=$cnt;?></div></th>
    <td><div align="center"><?=$row1['tanggal']?></div></td>
    <td>
	<?php
	  $sql3="SELECT t.baru as baru, t.idpenerima as penerima, p.nama as nama FROM jbsvcr.tujuanpesan t, jbssdm.pegawai p WHERE idpesan='".$row1['replid']."' AND t.idpenerima=p.nip ORDER BY p.nama";
	  $result3=QueryDb($sql3);
	  $num3=@mysqli_num_rows($result3);
	  if ($num3>0){
	  while ($row3=@mysqli_fetch_array($result3)){
	  $img="<img src='../../images/ico/unread.png' />";
	  if ($row3['baru']==1)
		  $img="<img src='../../images/ico/unread.png' title='Belum dibaca oleh ".$row3['nama']."'/>";
	  if ($row3['baru']==0)
		  $img="<img src='../../images/ico/readen.png' title='Sudah dibaca oleh ".$row3['nama']."' />";
	  echo $img.$row3['penerima']."-".$row3['nama']."<br>";
	  }
	} else {
	$sql4="SELECT t.baru as baru, t.idpenerima as penerima, p.nama as nama FROM jbsvcr.tujuanpesan t, jbsakad.siswa p WHERE idpesan='".$row1['replid']."' AND t.idpenerima=p.nis ORDER BY p.nama";
	$result4=QueryDb($sql4);
		while ($row4=@mysqli_fetch_array($result4)){
	  $img="<img src='../../images/ico/unread.png' />";
	  if ($row4['baru']==1)
		  $img="<img src='../../images/ico/unread.png' title='Belum dibaca oleh ".$row4['nama']."'/>";
	  if ($row4['baru']==0)
		  $img="<img src='../../images/ico/readen.png' title='Sudah dibaca oleh ".$row4['nama']."' />";
	  echo $img.$row4['penerima']."-".$row4['nama']."<br>";
	  }
		}
	 
	?>
	</td>
    <td><?php if ($row1['baru']==1) { ?><img src="../../images/ico/unread.gif" /><?php } ?><a href="#" onClick="bacapesan('<?=$row1['replid']?>')">
	<?php 
	echo $row1['judul'];
	?></a>
    </td>
    <td><div align="center">
    <img src="../../images/ico/hapus.png" border="0" onClick="hapus('<?=$row1[\REPLID_TKRM]?>')" style="cursor:pointer;" title="Hapus Pesan ini !" />
   </div></td>
  </tr>
  <?php 
  $cnt++;
  } 
  } else {?>
   <tr>
    <td scope="row" colspan="6"><div align="center"  class="divNotif">Tidak ada pesan</div></th>
   </tr>
  <?php } ?>
</table>
<input type="hidden" name="numpesan" id="numpesan" value="<?=$numpesan?>">
	</td>
  </tr>
</table>

</body>
</html>
<script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>