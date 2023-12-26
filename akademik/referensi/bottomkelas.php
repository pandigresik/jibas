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

$departemen=$_REQUEST['departemen'];
$tingkat=$_REQUEST['tingkat'];
$tahunajaran=$_REQUEST['tahunajaran'];

$urut = "kelas";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

OpenDb();
$sql_get_tingkat="SELECT tingkat FROM tingkat WHERE replid='$tingkat'";
$result_get_tingkat = QueryDB($sql_get_tingkat);
$row_get_tingkat = @mysqli_fetch_row($result_get_tingkat);  
$nama_tingkat=$row_get_tingkat[0];
	
$sql_get_tahunajaran = "SELECT tahunajaran FROM tahunajaran WHERE replid='$tahunajaran'";  
$result_get_tahunajaran = QueryDB($sql_get_tahunajaran);
$row_get_tahunajaran = @mysqli_fetch_row($result_get_tahunajaran); 
$nama_tahunajaran=$row_get_tahunajaran[0];
CloseDb();

$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

	
if ($op == "dw8dxn8w9ms8zs22") {
	$replid = "";
	if (isset($_REQUEST['replid']))
		$replid = $_REQUEST['replid'];
		
	OpenDb();
	$sql = "UPDATE kelas SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	$result=QueryDb($sql);
	if ($result)
		CloseDb();
			
} else if ($op == "xm8r389xemx23xb2378e23") {
		$replid = "";
		if (isset($_REQUEST['replid']))
		$replid = $_REQUEST['replid'];
	OpenDb();
	$sql = "DELETE FROM kelas WHERE replid = '".$_REQUEST['replid']."'";
	QueryDb($sql);
	$result=QueryDb($sql);
	if ($result) { 
	CloseDb();
	?>
    <script language="javascript">
    document.location.href="bottomkelas.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>";
    </script>
	<?php 		 }
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Kelas]</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function carisiswa(replid) {	
	newWindow('../library/lihatsiswa.php?replid='+replid, 'LihatSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah() {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	
	newWindow('kelas_add.php?departemen='+departemen+'&tingkat='+tingkat+'&tahunajaran='+tahunajaran, 'TambahKelas','500','395','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	
	document.location.href = "bottomkelas.php?tingkat="+tingkat+"&departemen="+departemen+"&tahunajaran="+tahunajaran;
}

function setaktif(replid, aktif) {
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var departemen = document.getElementById('departemen').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	
	var msg;
	var newaktif;
	
	if (aktif == 1) {
		msg = "Apakah anda yakin akan mengubah kelas ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		msg = "Apakah anda yakin akan mengubah kelas ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg)) 
		document.location.href = "bottomkelas.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+'&departemen='+departemen+'&tingkat='+tingkat+'&tahunajaran='+tahunajaran+'&urut='+urut+'&urutan='+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function edit(replid) {
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var departemen = document.getElementById('departemen').value;	
	
	newWindow('kelas_edit.php?replid='+replid+'&departemen='+departemen+'&tingkat='+tingkat+'&tahunajaran='+tahunajaran, 'UbahKelas','500','395','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid) {
	var tingkat = document.getElementById('tingkat').value;
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
		
	if (confirm("Apakah anda yakin akan menghapus kelas ini?"))
		document.location.href = "bottomkelas.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function cetak(urut,urutan) {
	var namatahunajaran = document.getElementById('namatahunajaran').value;
	var namatingkat = document.getElementById('namatingkat').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var departemen = document.getElementById('departemen').value;
	var total=document.getElementById("total").value;
	
	newWindow('kelas_cetak.php?departemen='+departemen+'&tingkat='+tingkat+'&tahunajaran='+tahunajaran+'&namatahunajaran='+namatahunajaran+'&namatingkat='+namatingkat+'&urut='+urut+'&urutan='+urutan+'&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakKelas','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
	
}

function change_urut(urut,urutan) {		
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "bottomkelas.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function change_page(page) {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="bottomkelas.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="bottomkelas.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="bottomkelas.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>
</head>
<body leftmargin="0" topmargin="0">

<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>"/>
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>"/>
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>"/>
<input type="hidden" name="namatahunajaran" id="namatahunajaran" value="<?=$nama_tahunajaran?>"/>
<input type="hidden" name="namatingkat" id="namatingkat" value="<?=$nama_tingkat?>"/>
<input type="hidden" name="urut" id="urut" value="<?=$urut?>"/>
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan?>"/>

<table width="100%" border="0" width=""="100%">
<tr><td>
	<!--<td background="../images/ico/b_kelas.png" style="background-repeat:no-repeat; background-attachment:fixed; margin-left:10">-->
<?php 
	OpenDb();
	$sql_tot = "SELECT k.replid, k.kelas, k.idtahunajaran, k.kapasitas, k.nipwali, k.aktif, k.keterangan, t.replid, t.tahunajaran, t.departemen, p.nama FROM kelas k, tahunajaran t, jbssdm.pegawai p WHERE t.replid='$tahunajaran' AND k.idtahunajaran=t.replid AND k.nipwali=p.nip AND t.departemen='$departemen' AND k.idtingkat='$tingkat' GROUP BY k.replid";
	$result_tot = QueryDb($sql_tot);
	$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	
	$sql_kelas = "SELECT k.replid, k.kelas, k.idtahunajaran, k.kapasitas, k.nipwali, k.aktif, k.keterangan, t.replid, t.tahunajaran, t.departemen, p.nama FROM kelas k, tahunajaran t, jbssdm.pegawai p WHERE t.replid='$tahunajaran' AND k.idtahunajaran=t.replid AND k.nipwali=p.nip AND t.departemen='$departemen' AND k.idtingkat='$tingkat' GROUP BY k.replid ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	$result_kelas = QueryDb($sql_kelas);
	
	if (@mysqli_num_rows($result_kelas) > 0){ 
?>
<input type="hidden" name="total" id="total" value="<?=$total?>"/>
<table width="100%" border="0" align="center">          
<tr>
	<td align="right">            
    	<a href="JavaScript:refresh()" ><img src="../images/ico/refresh.png" border="0" name="refresh" id="refresh" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak('<?=$urut?>','<?=$urutan?>')" ><img src="../images/ico/print.png" border="0" name="cetak" id="cetak" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
   	<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <a href="JavaScript:tambah()" ><img src="../images/ico/tambah.png" border="0" name="tambah" id="tambah" onMouseOver="showhint('Tambah!', this, event, '50px')"/>&nbsp;Tambah Kelas</a>
 	<?php } ?>
     	</td>
	</tr>
</table>
<br />

<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
<!-- TABLE CONTENT -->
<tr height="30" class="header" align="center">
	<td width="4%">No</td>        
    <td width="8%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('kelas','<?=$urutan?>')">Kelas <?=change_urut('kelas',$urut,$urutan)?></td>    
	<td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('p.nama','<?=$urutan?>')">Wali Kelas <?=change_urut('p.nama',$urut,$urutan)?></td>
    <td width="12%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('kapasitas','<?=$urutan?>')">Kapasitas <?=change_urut('kapasitas',$urut,$urutan)?></td>
	<td width="8%">Terisi</td>
	<td width="*">Keterangan</td>
    <td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('k.aktif','<?=$urutan?>')">Status <?=change_urut('k.aktif',$urut,$urutan)?></td>
    <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
    <td width="*" class="header">&nbsp;</td>
    <?php } ?>
</tr>
 <?php 
	if ($page==0)
		$cnt = 0;
	else 
		$cnt = (int)$page*(int)$varbaris;

	while ($row_kelas = @mysqli_fetch_row($result_kelas)) {
		$kelas=$row_kelas[0];
		$sql_get_jumsiswa = "SELECT COUNT(*) FROM jbsakad.siswa s WHERE s.idkelas='$kelas' AND s.aktif=1";
		$result_get_jumsiswa = QueryDB($sql_get_jumsiswa);
		if ($row_get_jumsiswa = mysqli_fetch_row($result_get_jumsiswa)){
			$terisi = $row_get_jumsiswa[0];
		} else {
			$terisi = 0;
		}
?>
<tr height="25">   	
	<td align="center"><?=++$cnt ?></td>
    <td><?=$row_kelas[1]?><input type="hidden" name="kelas" id="kelas" value="<?=$row_kelas[1]?>"/></td>
    <td><?=$row_kelas[4] . " " . $row_kelas[10] ?></td>
	<td align="center"><?=$row_kelas[3] ?></td>
	<td align="center"><?=$terisi ?>
    	<?php if ($terisi > 0) { ?>
   	&nbsp;<a href="JavaScript:carisiswa(<?=$row_kelas[0]?>)"><img src="../images/ico/lihat.png" border="0" onMouseOver="showhint('Lihat Siswa!', this, event, '65px')"/></a>
    	<?php } ?>
    </td>
	<td><?=$row_kelas[6] ?></td>
    <td align="center">  
<?php 	if (SI_USER_LEVEL() == $SI_USER_STAFF) {  
			if ($row_kelas[5] == 1) { ?> 
     	<img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/>
<?php 		} else { ?>                
        <img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/>
<?php 		}
		} else { 
			if ($row_kelas[5] == 1) { ?>
        <a href="JavaScript:setaktif(<?=$row_kelas[0] ?>, <?=$row_kelas[5] ?>)"><img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/></a>
<?php 		} else { ?>
        <a href="JavaScript:setaktif(<?=$row_kelas[0] ?>, <?=$row_kelas[5] ?>)"><img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/></a>
<?php 		} //end if
		} //end if ?>        
	</td>
<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>     
	<td align="center">
    	<a href="JavaScript:edit(<?=$row_kelas[0] ?>)"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Kelas!', this, event, '80px')"/></a>&nbsp;
        <a href="JavaScript:hapus(<?=$row_kelas[0] ?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Kelas!', this, event, '80px')"/></a>
      
	</td>
<?php 	} ?> 
</tr>
<?php } ?>
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script></div>
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
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>

<?php } else { ?>

<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
       <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru.
        <?php } ?>
        </p></b></font>
	</td>
</tr>
</table>  
<?php } ?> 
</td>
</tr>
</table>
</body>
</html>