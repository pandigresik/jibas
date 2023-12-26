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
require_once('include/sessionchecker.php');
require_once('include/sessioninfo.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
	
$idpenerimaan = 0;
if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "tanggal";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];	
	
OpenDb();	
$sql = "SELECT nama FROM datapenerimaan WHERE replid=$idpenerimaan";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>Laporan Penerimaan Lainnya</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">

function refresh() {
	var tanggal1 = document.getElementById('tanggal1').value;
	var tanggal2 = document.getElementById('tanggal2').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var departemen = document.getElementById('departemen').value;
	
	var addr = "lappenerimaanlain_content.php?departemen="+departemen+"&idpenerimaan="+idpenerimaan+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2;
	document.location.href = addr;
}

function cetak() {
	var total = document.getElementById("tes").value;
	var addr = "lappenerimaanlain_cetak.php?departemen=<?=$departemen?>&idpenerimaan=<?=$idpenerimaan?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'CetakLapPenerimaanLain','780','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	
	var varbaris=document.getElementById("varbaris").value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	
	document.location.href = "lappenerimaanlain_content.php?departemen=<?=$departemen?>&idpenerimaan=<?=$idpenerimaan?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}


/*function change_page(page) {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lappenerimaanlain_content.php?departemen="+departemen+"&idpenerimaan="+idpenerimaan+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lappenerimaanlain_content.php?departemen="+departemen+"&idpenerimaan="+idpenerimaan+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&page="+hal+"&hal="+hal;
}

function change_baris() {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lappenerimaanlain_content.php?departemen="+departemen+"&idpenerimaan="+idpenerimaan+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}*/
</script>
</head>

<body leftmargin="0" topmargin="0">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="idpenerimaan" id="idpenerimaan" value="<?=$idpenerimaan ?>" />
<input type="hidden" name="tanggal1" id="tanggal1" value="<?=$tanggal1 ?>" />
<input type="hidden" name="tanggal2" id="tanggal2" value="<?=$tanggal2 ?>" />
<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
<?php 
    OpenDb();
	$sql_tot = "SELECT p.replid AS id, j.nokas, p.sumber, date_format(p.tanggal, '%d-%b-%Y') AS tanggal, p.keterangan, p.jumlah, p.petugas FROM penerimaanlain p, jurnal j, datapenerimaan dp WHERE j.replid = p.idjurnal AND p.idpenerimaan = dp.replid AND p.idpenerimaan = '$idpenerimaan' AND dp.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY p.tanggal, p.replid";
	
	$sql = "SELECT p.replid AS id, j.nokas, p.sumber, date_format(p.tanggal, '%d-%b-%Y') AS tanggal, p.keterangan, p.jumlah, p.petugas FROM penerimaanlain p, jurnal j, datapenerimaan dp WHERE j.replid = p.idjurnal AND p.idpenerimaan = dp.replid AND p.idpenerimaan = '$idpenerimaan' AND dp.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	
	
	$result_tot = QueryDb($sql_tot);
	$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0) {
?>
	<table width="100%" border="0" align="center">
    <tr>
    	<td valign="bottom">
    <a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;
    <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
    	</td>
	</tr>
	</table>
    <br />
	<table class="tab" id="table" border="0" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
	<tr height="30" align="center" class="header">
        <td width="5%">No</td>
        <td width="15%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('tanggal','<?=$urutan?>')">Tanggal <?=change_urut('tanggal',$urut,$urutan)?></td>
        <td width="15%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('sumber','<?=$urutan?>')">Sumber <?=change_urut('sumber',$urut,$urutan)?></td>
        <td width="15%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('jumlah','<?=$urutan?>')">Jumlah <?=change_urut('jumlah',$urut,$urutan)?></td>
        <td width="25%">Keterangan</td>
        <td width="10%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('petugas','<?=$urutan?>')">Petugas <?=change_urut('petugas',$urut,$urutan)?></td>
    </tr>
<?php 

if ($page==0)
	$cnt = 0;
else 
	$cnt = (int)$page*(int)$varbaris;
$total = 0;
while ($row = mysqli_fetch_array($result)) {
	$total += $row['jumlah'];
?>
    <tr height="25">
        <td align="center"><?=++$cnt?></td>
        <td align="center"><?="<strong>" . $row['nokas'] . "</strong><br>" . $row['tanggal']?></td>
        <td align="left"><?=$row['sumber'] ?></td>
        <td align="right"><?=FormatRupiah($row['jumlah'])?></td>
        <td><?=$row['keterangan'] ?></td>
        <td><?=$row['petugas'] ?></td>
    </tr>
<?php
}
?>
	<input type="hidden" name="tes" id="tes" value="<?=$total?>"/>
    <tr height="35">
        <td bgcolor="#996600" colspan="3" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
        <td bgcolor="#996600" align="right"><font color="#FFFFFF"><strong><?=FormatRupiah($total) ?></strong></font></td>
        <td bgcolor="#996600" colspan="3">&nbsp;</td>
    </tr>
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
   <!--	</td>
</tr> 
<tr>
    <td>
    <table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
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
    	<td align="center">
    <input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
		<?php
		for($a=0;$a<$total;$a++){
			if ($page==$a){
				echo  "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo  "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
			}
				 
	    }
		?>
	     <input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
 		</td>
        <td width="30%" align="right">Jumlah baris per halaman
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>-->
<?php } else { ?>
    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="250">
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data.
        	<br />Tambah data pembayaran <?=$namapenerimaan?> di menu Penerimaan Pembayaran pada bagian Penerimaan.
        	</b></font>
        </td>
    </tr>
    </table>  
<?php } ?>
    </td>
</tr>
</table>
</body>
</html>