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
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../cek.php');

OpenDb();
$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op == "xm8r389xemx23xb2378e23") {
	$sql = "DELETE FROM jbsuser.hakakses WHERE login = '".$_REQUEST['login']."' AND modul='SIMAKA'";
	QueryDb($sql);
	$sql = "SELECT * FROM jbsuser.hakakses WHERE login = '".$_REQUEST['login']."' AND modul<>'SIMAKA'";
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result)==0) {
		$sql = "DELETE FROM jbsuser.login WHERE login='".$_REQUEST['login']."'";
		QueryDb($sql);
	}
	CloseDb();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function tambah() {
	newWindow('useradd.php', 'TambahPengguna','500','360','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() {
	document.location.href = "user.php";
}

function edit(replid) {
	newWindow('user_edit.php?replid='+replid, 'UbahPengguna','500','325','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(login) {
	if (confirm("Apakah anda yakin akan menghapus pengguna ini?"))
		document.location.href = "user.php?op=xm8r389xemx23xb2378e23&login="+login+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function cetak() {
	var total=document.getElementById("total").value;
	newWindow('user_cetak.php?urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakPengguna','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function change_urut(urut,urutan) {			
	var varbaris=document.getElementById("varbaris").value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "user.php?urutan="+urutan+"&urut="+urut+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;

}

function change_page(page) {
	var varbaris=document.getElementById("varbaris").value;
		
	document.location.href = "user.php?page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}

function change_hal() {
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="user.php?page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="user.php?urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>
</head>

<body>

<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/ico/user_group.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
  <td align="left" valign="top">
	<table border="0" width="95%" align="center">
    <tr>
        <td align="right">
         <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Daftar Pengguna</font>
         </td>
    </tr>
    <tr>
        <td align="right"><a href="../usermenu.php"><font size="1" color="#000000"><b>Pengaturan</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Daftar Pengguna</b></font>
        </td>
    </tr>
	<tr>
      <td align="left">&nbsp;</td>
      </tr>
	</table>
	<br /><br />
<?php
	OpenDb();
	$sql_tot = "SELECT h.login, h.replid,  h.tingkat, h.departemen, h.keterangan, p.nama, p.aktif,  DATE_FORMAT(h.lastlogin,'%Y-%m-%d') AS tanggal, TIME(h.lastlogin) as jam FROM jbsuser.hakakses h, jbssdm.pegawai p, jbsuser.login l WHERE h.modul='SIMAKA' AND h.login = l.login AND l.login = p.nip ";
	
	$result_tot = QueryDb($sql_tot);
	$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
					
	$sql="SELECT h.login, h.replid,  h.tingkat, h.departemen, h.keterangan, p.nama, p.aktif,  DATE_FORMAT(h.lastlogin,'%Y-%m-%d') AS tanggal, TIME(h.lastlogin) as jam FROM jbsuser.hakakses h, jbssdm.pegawai p, jbsuser.login l WHERE h.modul='SIMAKA' AND h.login = l.login AND l.login = p.nip ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	
	$result = QueryDB($sql);
	$akhir = ceil($jumlah/5)*5;
	
	if (mysqli_num_rows($result) > 0) {
?>
    <input type="hidden" name="total" id="total" value="<?=$total?>"/>
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <!-- TABLE CONTENT -->
    <tr>
      	<td align="right">   
    	<a href="#" onClick="refresh()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    	<a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')" />&nbsp;Cetak</a>&nbsp;&nbsp;
<?php if (SI_USER_LEVEL() != $SI_USER_STAFF && SI_USER_LEVEL() != $SI_USER_MANAGER) { ?>
	    <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')" />&nbsp;Tambah Pengguna</a>
<?php } ?>    
    </td>
    </tr>
    </table><br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
    <tr height="30" class="header" align="center">
    	<td width="4%">No</td>
        <td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('login','<?=$urutan?>')" style="cursor:pointer;">Login <?=change_urut('login',$urut,$urutan)?></td>
        <td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('nama','<?=$urutan?>')" style="cursor:pointer;">Nama <?=change_urut('nama',$urut,$urutan)?></td>
        <td width="12%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('departemen','<?=$urutan?>')" style="cursor:pointer;">Departemen <?=change_urut('departemen',$urut,$urutan)?></td>
        <td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('tingkat','<?=$urutan?>')" style="cursor:pointer;">Tingkat <?=change_urut('tingkat',$urut,$urutan)?></td>
      
        <td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('aktif','<?=$urutan?>')" style="cursor:pointer;">Status <?=change_urut('aktif',$urut,$urutan)?></td>
        <td width="*">Keterangan</td>  
        <td width="15%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" onClick="change_urut('tanggal','<?=$urutan?>')" style="cursor:pointer;">Login Terakhir <?=change_urut('tanggal',$urut,$urutan)?></td>
         <?php if (SI_USER_LEVEL() != $SI_USER_STAFF && SI_USER_LEVEL() != $SI_USER_MANAGER) { ?>
        <td width="8%">&nbsp;</td>
        <?php } ?>
    </tr>
<?php 	
	if ($page==0)
		$cnt = 0;
	else 
		$cnt = (int)$page*(int)$varbaris;
		
	while ($row = mysqli_fetch_array($result)) { ?>
    <tr height="25">
    	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['login'] ?></td>
        <td><?=$row['nama']; ?></td>
        <td align="center"><?php if ($row['tingkat']==1){
					echo "Semua Departemen";
				} else {
					echo $row['departemen'];
				}
			?>
       	</td>
        <td align="center">
			<?php switch ($row['tingkat']){
					case 0: echo "Landlord";
						break;
					case 1: echo "Manajer Akademik";
						break;
					case 2: echo "Staf Akademik";
						break;
				}
        	?>
        </td>
        
        
        <td align="center"><?php if ($row['aktif'] == 1) echo 'Aktif'; else echo 'Tidak Aktif'; ?></td>
        <td><?=$row['keterangan'] ?></td>
        <td align="center"><?=format_tgl($row['tanggal'])?> <?=$row['jam']?></td>
 <?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF && SI_USER_LEVEL() != $SI_USER_MANAGER) {  ?> 
        <td align="center">
            <a href="JavaScript:edit('<?=$row['replid'] ?>')"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Pengguna!', this, event, '75px')" /></a>&nbsp;
            <a href="JavaScript:hapus('<?=$row['login'] ?>')"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Pengguna!', this, event, '75px')"/></a>
        </td>
<?php 	} ?>
    </tr>
<?php } CloseDb(); ?>	
    
    <!-- END TABLE CONTENT -->
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>	
     <?php if ($page==0){ 
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page<$total && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page==$total-1 && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:hidden;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:hidden;'";
		}
	?>
     </td>
</tr> 
<tr>
    <td>
    <table border="0"width="95%" align="center" cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left">Halaman
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
	  	dari <?=$total?> halaman
		
		<?php 
     // Navigasi halaman berikutnya dan sebelumnya
        ?>
        </td>
    	<!--td align="center">
    <input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
		<?php
		/*for($a=0;$a<$total;$a++){
			if ($page==$a){
				echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
			}
				 
	    }*/
		?>
	     <input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
 		</td-->
        <td width="30%" align="right">Jumlah baris per halaman
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>
    </td></tr>
<!-- END TABLE CENTER -->    
</table>
<?php } else { ?>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">
       	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF && SI_USER_LEVEL() != $SI_USER_MANAGER) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        <?php } ?>
	</td>
</tr>
</table>  
<?php } ?>

</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    

</body>
</html>