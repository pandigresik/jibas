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
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];

$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];


$urut = "nokas";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "DESC";
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];
	
$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
	
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>Untitled Document</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function refresh() {	
	document.location.href = "laptransaksi_content.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&idtahunbuku=<?=$idtahunbuku?>";
}

function cetak() {
	var addr = "laptransaksi_cetak.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&idtahunbuku=<?=$idtahunbuku?>";
	newWindow(addr, 'CetakTransaksi','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() {
	var addr = "laptransaksi_excel.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&idtahunbuku=<?=$idtahunbuku?>";
	newWindow(addr, 'ExcelTransaksi','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	var varbaris=document.getElementById("varbaris").value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "laptransaksi_content.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris+"&idtahunbuku=<?=$idtahunbuku?>";
}

function change_page(page) {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="laptransaksi_content.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="laptransaksi_content.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&page="+hal+"&hal="+hal;
}

function change_baris() {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="laptransaksi_content.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
<?php     
	OpenDb();
	//$sql_tot = "SELECT nokas, date_format(tanggal, '%d-%b-%Y') AS tanggal, petugas, transaksi, keterangan, debet, kredit FROM transaksilog WHERE departemen='$departemen' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND idtahunbuku = $idtahunbuku with ROLLUP";
	$sql_tot = "SELECT COUNT(nokas), SUM(debet) AS totdebet, SUM(kredit) AS totkredit 
                  FROM transaksilog 
                 WHERE departemen='$departemen' 
                   AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' 
                   AND idtahunbuku = '".$idtahunbuku."'";

	$result_tot = QueryDb($sql_tot);
	$row_tot = mysqli_fetch_row($result_tot);
	$jumlah = $row_tot[0];
	$total=ceil($jumlah/(int)$varbaris);
	$akhir = ceil($jumlah/5)*5;
	
	$totaldebet = $row_tot[1];
	$totalkredit = $row_tot[2];
	
	
	$sql = "SELECT nokas, date_format(tanggal, '%d-%b-%Y') AS tanggal, petugas, transaksi, keterangan, debet, kredit 
              FROM transaksilog 
             WHERE departemen='$departemen' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' 
               AND idtahunbuku = '$idtahunbuku' 
             ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		
	$result = QueryDb($sql);	
	if (mysqli_num_rows($result) > 0) {
?>    
    <input type="hidden" name="total" id="total" value="<?=$total?>"/>
    <table border="0" width="100%" align="center">
	<tr>
    	<td align="right">
    	<a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    	<a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
        <a href="JavaScript:excel()"><img src="images/ico/excel.png" border="0" onMouseOver="showhint('Buka di Ms Excel!', this, event, '50px')"/>&nbsp;Excel</a>&nbsp;
    	</td>
	</tr>
	</table>
    <br />
	<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="100%" align="left" bordercolor="#000000">
    <tr height="30" align="center">
        <td width="4%" class="header" >No</td>
        <td width="18%" class="header" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nokas','<?=$urutan?>')">No. Jurnal/Tanggal <?=change_urut('nokas',$urut,$urutan)?></td>
        <td width="10%" class="header" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('petugas','<?=$urutan?>')">Petugas <?=change_urut('petugas',$urut,$urutan)?></td>
        <td width="*" class="header" >Transaksi</td>
        <td width="15%" class="header" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('debet','<?=$urutan?>')">Debet <?=change_urut('debet',$urut,$urutan)?></td>
        <td width="15%" class="header" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('kredit','<?=$urutan?>')">Kredit <?=change_urut('kredit',$urut,$urutan)?></td>
    </tr>
<?php 	if ($page==0)
            $cnt = 0;
        else 
            $cnt = (int)$page*(int)$varbaris;
        
		while($row = mysqli_fetch_array($result)) {
			
?>
    <tr height="25">
        <td align="center" valign="top"><?=++$cnt ?></td>
        <td align="center" valign="top"><strong><?=$row['nokas'] ?></strong><br /><?=$row['tanggal'] ?></td>
        <td valign="top" align="center"><?=$row['petugas'] ?></td>
        <td align="left" valign="top"><?=$row['transaksi'] ?>
        <?php if ($row['keterangan'] <> "") { ?>
        <br /><strong>Keterangan: </strong><?=$row['keterangan'] ?>
        <?php } ?>
        </td>
        <td align="right" valign="top"><?=FormatRupiah($row['debet']) ?></td>
        <td align="right" valign="top"><?=FormatRupiah($row['kredit']) ?></td>
    </tr>
<?php
		}
		CloseDb();
	//echo  'total '.$total.' dan '.$page;	
	if ($total-1 == $page) {
		
		
?>
   
    <tr height="30">
        <td colspan="4" align="center" bgcolor="#999900">
        <font color="#FFFFFF"><strong>T O T A L</strong></font>
        </td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totaldebet) ?></strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalkredit) ?></strong></font></td>
    </tr>
<?php 	} ?>
    </table>
    <script language='JavaScript'>
        Tables('table', 1, 0);
    </script>
     <?php CloseDb() ?>
     <?php if ($page==0){ 
		$disback="style='display:none;'";
		$disnext="style=''";
		}
		if ($page<$total && $page>0){
		$disback="style=''";
		$disnext="style=''";
		}
		if ($page==$total-1 && $page>0){
		$disback="style=''";
		$disnext="style='display:none;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='display:none;'";
		$disnext="style='display:none;'";
		}
	?>
    </td>
</tr> 
<tr>
    <td>
    <table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left" colspan="2">Halaman
		<input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
		<input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
	  	dari <?=$total?> halaman
		
		<?php 
     // Navigasi halaman berikutnya dan sebelumnya
        ?>
		<?php
		//for($a=0;$a<$total;$a++){
		//	if ($page==$a){
		//		echo  "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
		//	} else { 
		//		echo  "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
		//	}
		//		 
	    //}
		?>
	     
 		</td>
        <td width="30%" align="right">Jumlah baris per halaman
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>
<?php } else { ?>
    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="300">
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data.<br />Tambah data transaksi keuangan pada departemen <?=$departemen?> antara tanggal <?=LongDateFormat($tanggal1)?> s/d <?=LongDateFormat($tanggal2)?><br />.</font>
            
        </td>
    </tr>
    </table>  
<?php } ?>
    </td>
</tr>
</table>
</body>
</html>