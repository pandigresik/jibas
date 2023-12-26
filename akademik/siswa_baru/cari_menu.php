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
require_once('../cek.php');

$departemen = $_REQUEST['departemen'];
$jenis = $_REQUEST['jenis'];
$cari = $_REQUEST['cari'];

$urut = "nopendaftaran";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
	
$varbaris=20;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];
	
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];

$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pencarian Calon Siswa</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function refresh() {
	var departemen = document.getElementById('departemen').value;
	var jenis= document.getElementById('jenis').value;
	var cari= document.getElementById('cari').value;
	
	document.location.href = "cari_menu.php?departemen="+departemen+"&jenis="+jenis+"&cari="+cari;	
}

function tampil(replid) {
	newWindow('../library/detail_calon.php?replid='+replid, 'DetailCalonSiswa'+replid,'790','610','resizable=1,scrollbars=1,status=0,toolbar=0');		//newWindow('calon_tampil.php?replid='+replid,'TampilCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	var departemen = document.getElementById('departemen').value;
	var jenis= document.getElementById('jenis').value;
	var cari= document.getElementById('cari').value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "cari_menu.php?departemen="+departemen+"&jenis="+jenis+"&urut="+urut+"&urutan="+urutan+"&cari="+cari+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
	
}

function cetak() {
	var departemen = document.getElementById('departemen').value;
	var jenis= document.getElementById('jenis').value;
	var cari= document.getElementById('cari').value;
	var total=document.getElementById("total").value;
	
	newWindow('cari_cetak.php?departemen='+departemen+'&jenis='+jenis+'&cari='+cari+'&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakCariCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')	
}

function cetak_excel() {	
	var departemen = document.getElementById('departemen').value;	
	var jenis= document.getElementById('jenis').value;
	var cari= document.getElementById('cari').value;
	
	newWindow('cari_cetak_excel.php?departemen='+departemen+'&jenis='+jenis+'&cari='+cari+'&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakCariCalonSiswaFormatExcel','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function change_page(page) {
	var departemen = document.getElementById('departemen').value;
	var jenis = document.getElementById('jenis').value;
	var cari = document.getElementById('cari').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="cari_menu.php?departemen="+departemen+"&jenis="+jenis+"&cari="+cari+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var departemen = document.getElementById('departemen').value;
	var jenis = document.getElementById('jenis').value;
	var cari = document.getElementById('cari').value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="cari_menu.php?departemen="+departemen+"&jenis="+jenis+"&cari="+cari+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var departemen = document.getElementById('departemen').value;
	var jenis = document.getElementById('jenis').value;
	var cari = document.getElementById('cari').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="cari_menu.php?departemen="+departemen+"&jenis="+jenis+"&cari="+cari+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function edit(replid){
	newWindow('calon_edit.php?replid='+replid,'UbahPendataanCalonSiswa','825','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>
<body topmargin="0" leftmargin="0">
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td>
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="jenis" id="jenis" value="<?=$jenis ?>" />
<input type="hidden" name="cari" id="cari" value="<?=$cari ?>" />
	<?php 
	OpenDb();
	if ($jenis!="kondisi" && $jenis!="status" && $jenis!="agama" && $jenis!="suku" && $jenis!="darah") {
		$sql_tot = "SELECT c.replid,c.nopendaftaran,c.nama,p.departemen,k.kelompok,c.aktif,c.nisn FROM calonsiswa c,kelompokcalonsiswa k, prosespenerimaansiswa p WHERE c.$jenis like '%$cari%' AND p.departemen='$departemen' AND c.idkelompok = k.replid AND c.idproses = p.replid AND p.replid = k.idproses";
		
		$sql = "SELECT c.replid,c.nopendaftaran,c.nama,p.departemen,k.kelompok,c.aktif,c.nisn FROM calonsiswa c,kelompokcalonsiswa k, prosespenerimaansiswa p WHERE c.$jenis like '%$cari%' AND p.departemen='$departemen' AND c.idkelompok = k.replid AND c.idproses = p.replid AND p.replid = k.idproses ORDER BY $urut $urutan, nama ASC LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	} else { 
		$sql_tot = "SELECT c.replid,c.nopendaftaran,c.nama,p.departemen,k.kelompok,c.aktif,c.nisn FROM calonsiswa c,kelompokcalonsiswa k, prosespenerimaansiswa p WHERE c.$jenis = '$cari' AND p.departemen='$departemen' AND c.idkelompok = k.replid AND c.idproses = p.replid AND p.replid = k.idproses";
		
		$sql = "SELECT c.replid,c.nopendaftaran,c.nama,p.departemen,k.kelompok,c.aktif,c.nisn FROM calonsiswa c,kelompokcalonsiswa k, prosespenerimaansiswa p WHERE c.$jenis = '$cari' AND p.departemen='$departemen' AND c.idkelompok = k.replid AND c.idproses = p.replid AND p.replid = k.idproses ORDER BY $urut $urutan, nama ASC LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	}
	
 	$result_tot = QueryDb($sql_tot);
	$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	
	$result = QueryDb($sql);
		
	if (mysqli_num_rows($result) > 0) { 
	?>
    <input type="hidden" name="total" id="total" value="<?=$total?>"/>
	<table width="100%" border="0">
    <tr>
    	<td align="right">
        <a href="#" onClick="refresh()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp; 		
        <a href="#" onClick="cetak_excel()"><img src="../images/ico/excel.png" border="0" onMouseOver="showhint('Cetak dalam format Excel!', this
        , event, '80px')"/>&nbsp;Cetak Excel</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>
		</td>
  	</tr>
	</table>   	
	<br />

    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
    <!-- TABLE CONTENT -->
    <tr height="30" align="center" class="header">    	
    	<td width="4%">No</td>
        <td width="18%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nopendaftaran','<?=$urutan?>')">No. Pendaftaran <?=change_urut('nopendaftaran',$urut,$urutan)?></td>
		<td width="18%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nisn','<?=$urutan?>')">NISN <?=change_urut('nisn',$urut,$urutan)?></td>
        <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama Calon Siswa <?=change_urut('nama',$urut,$urutan)?></td>
        <td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('kelompok','<?=$urutan?>')">Kelompok <?=change_urut('kelompok',$urut,$urutan)?></td>
        <td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('aktif','<?=$urutan?>')" >Status <?=change_urut('aktif',$urut,$urutan)?></td>
        <td width="8%">Detail</td>
    </tr>   
    <?php
		if ($page==0)
			$cnt = 0;
		else 
			$cnt = (int)$page*(int)$varbaris;
	
		while ($row = @mysqli_fetch_array($result)) {
	?>
    <tr height="25">
       	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['nopendaftaran'] ?></td>
		<td align="center"><?=$row['nisn'] ?></td>
        <td><?=$row['nama']?></td>
        <td><?=$row['kelompok'] ?></td>
        <td align="center"><?php if ($row['aktif']==1){
					echo "Aktif";
				} elseif ($row['aktif']==0){
					echo "Tidak Aktif ";
				}
			?>	
        </td>
        <td align="center">
			<a href="JavaScript:edit(<?=$row["replid"] ?>)" ><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Calon Siswa!', this, event, '80px')"/></a>&nbsp;
			<a href="JavaScript:tampil(<?=$row['replid'] ?>)"><img src="../images/ico/lihat.png" border="0" onmouseover="showhint('Detail Data Calon Siswa!', this, event, '80px')" /></a>&nbsp;
		</td>        
    </tr>
<?php } 
	CloseDb(); 
?>	
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
    <table border="0"width="100%" align="center">	
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
        <?php 	for ($m=10; $m <= 100; $m=$m+10) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>
<?php } else { ?>

<table width="100%" border="0" align="center" height="300">          
<tr>
	<td align="center" valign="middle">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
        <br />Silahkan ulangi pencarian kembali.
       	</b></font>
	</td>
</tr>
</table>  
<?php } ?> 	
</td>
</tr>
<!-- END TABLE CENTER -->    
</table>    


</body>
</html>