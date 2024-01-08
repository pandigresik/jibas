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
require_once('../library/departemen.php');
require_once('../include/exceldata.php');
require_once('../cek.php');

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['tahunajaran'])) 
	$tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
//$nis = $_REQUEST['nis'];
$varbaris=20;
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
	
OpenDb();

$op = $_REQUEST['op'];
if ($op == "dw8dxn8w9ms8zs22") 
{
	$sql = "UPDATE siswa SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	QueryDb($sql);
} 
else if ($op == "xm8r389xemx23xb2378e23") 
{
    $success = true;
    BeginTrans();

    $sql = "SELECT nis FROM siswa WHERE replid = '".$_REQUEST['replid']."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $nis = $row[0];

    $sql = "DELETE FROM tambahandatasiswa WHERE nis = '".$nis."'";
    QueryDbTrans($sql, $success);

    if ($success)
    {
        $sql = "DELETE FROM siswa WHERE replid = '".$_REQUEST['replid']."'";
        QueryDbTrans($sql, $success);
    }

	if ($success) 
	{
		$sql = "SELECT * FROM calonsiswa WHERE replidsiswa = '".$_REQUEST['replid']."'";
		$result = QueryDb($sql);
		if (mysqli_num_rows($result) > 0) 
		{
			$sql = "UPDATE calonsiswa SET replidsiswa = NULL WHERE replidsiswa = '".$_REQUEST['replid']."'";
            QueryDbTrans($sql, $success);
		}
	}

	if ($success)
	    CommitTrans();
    else
        RollbackTrans();
	
	if ($success) 
	{	?>
		<script>refresh();</script> 
<?php } 
}	
//OpenDb();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pendataan Siswa</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function refresh() {	
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	
	document.location.href = "siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen;
}

function tambah() {
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	newWindow('siswa_add.php?departemen='+departemen+'&kelas='+kelas+'&tahunajaran='+tahunajaran+'&tingkat='+tingkat, 'TambahSiswa','905','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function edit(replid, nis) {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	newWindow('siswa_edit.php?replid='+replid+'&departemen='+departemen+'&tahunajaran='+tahunajaran+'&kelas='+kelas+'&tingkat='+tingkat, 'UbahSiswa','905','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid, nis) {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	if (confirm('Apakah anda yakin akan menghapus siswa ini?'))
		document.location.href = "siswa_content.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&nis="+nis+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function change_urut(urut,urutan) {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	//if (confirm("Apakah anda yakin akan menghapus angkatan ini?"))
	document.location.href = "siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function cetak() {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	var total=document.getElementById("total").value;
	
	newWindow('siswa_cetak.php?departemen='+departemen+'&tahunajaran='+tahunajaran+'&tingkat='+tingkat+'&kelas='+kelas+'&urut='+urut+'&urutan='+urutan+'&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function exel(){
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	
	newWindow('siswa_cetak_excel.php?departemen='+departemen+'&tahunajaran='+tahunajaran+'&tingkat='+tingkat+'&kelas='+kelas+'&urut='+urut+'&urutan='+urutan, 'CetakSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function tampil(replid) {
	newWindow('../library/detail_siswa.php?replid='+replid, 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh_after_add(){
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	
	document.location.href = "siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function setaktif(replid, aktif) {
	var msg;
	var newaktif;
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	var kapasitas = document.getElementById('kapasitas').value;
	var isi = document.getElementById('isi').value;
		
	if (aktif == 1) {
		msg = "Apakah anda yakin akan mengubah  siswa ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		msg = "Apakah anda yakin akan mengubah siswa ini menjadi AKTIF?";
		newaktif = 1;
		//if (kapasitas > isi) {
//			msg = "Apakah anda yakin akan mengubah siswa ini menjadi AKTIF?";
//			newaktif = 1;
//		} else {
//			msg = "Apakah anda yakin akan mengubah siswa ini menjadi AKTIF?";
//			//msg = "Status siswa tidak dapat diaktifkan karena kapasitas kelas tidak mencukupi";
//			newaktif = 0;
//		}
	}
	
	if (confirm(msg)) {
		document.location.href = "siswa_content.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
		parent.header.location.href = "siswa_header.php?tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&departemen="+departemen+"&kelas="+kelas;
	}
}

function change_page(page) {
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}

function change_hal() {
	var departemen = document.getElementById("departemen").value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var departemen = document.getElementById("departemen").value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href= "siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

</script>
</head>
<body topmargin="0" leftmargin="0">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran ?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat ?>" />
<input type="hidden" name="urut" id="urut" value="<?=$urut ?>" />
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan ?>" />

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="right">
    <?php
	$sql_tot = "SELECT nis,nama,asalsekolah,tmplahir,tgllahir,s.aktif,DAY(tgllahir),MONTH(tgllahir),YEAR(tgllahir),s.replid,s.nisn FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t WHERE s.idkelas = '$kelas' AND k.idtahunajaran = '$tahunajaran' AND k.idtingkat = '$tingkat' AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.alumni=0 ORDER BY replid ";
	$result_tot = QueryDb($sql_tot);
	$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	
	$sql = "SELECT nis,nama,asalsekolah,tmplahir,tgllahir,s.aktif,DAY(tgllahir),MONTH(tgllahir),YEAR(tgllahir),s.replid,s.statusmutasi,s.alumni,s.nisn FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t WHERE s.idkelas = '$kelas' AND k.idtahunajaran = '$tahunajaran' AND k.idtingkat = '$tingkat' AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.alumni=0 ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	$result = QueryDb($sql);
	
	if (@mysqli_num_rows($result)>0){ 
		$sql_kapasitas = "SELECT kapasitas FROM kelas WHERE replid = '".$kelas."'";
		$result_kapasitas = QueryDb($sql_kapasitas);
		$row_kapasitas = mysqli_fetch_row($result_kapasitas);
		$kapasitas = $row_kapasitas[0];
		
		$sql_siswa = "SELECT COUNT(*) FROM siswa WHERE idkelas = '$kelas' AND aktif = 1";
		$result_siswa = QueryDb($sql_siswa);
		$row_siswa = mysqli_fetch_row($result_siswa);
		$isi = $row_siswa[0];
	
?>
    <input type="hidden" name="total" id="total" value="<?=$total?>"/>
    <input type="hidden" name="kapasitas" id="kapasitas" value="<?=$kapasitas?>"/>
    <input type="hidden" name="isi" id="isi" value="<?=$isi?>"/>
    <table width="100%" border="0" align="center">
  	<tr>
    	<td align="right">
    	<a href="#" onClick="refresh()">
        <img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    	<a href="#" onClick="JavaScript:exel()"><img src="../images/ico/excel.png" border="0" onMouseOver="showhint('Cetak dalam format Excel!', this, event, '80px')"/>&nbsp;Cetak Excel</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp; 
	    <a href="#" onClick="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')" />&nbsp;Tambah Data Siswa</a>
		</td>
	</tr>    
    </table>
	<br />       
	<table border="1" width="100%" id="table" class="tab" align="center" style="border-collapse:collapse" bordercolor="#000000" />
	<tr class="header" height="30" align="center">		
		<td width="4%">No</td>
		<td width="10%"onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nis','<?=$urutan?>')">N I S <?=change_urut('nis',$urut,$urutan)?></td>
		<td width="10%"onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nisn','<?=$urutan?>')">N I S N <?=change_urut('nisn',$urut,$urutan)?></td>
		<td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?></td>
      	<td width="15%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('asalsekolah','<?=$urutan?>')">Asal Sekolah <?=change_urut('asalsekolah',$urut,$urutan)?></td>
		<td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer; " onClick="change_urut('tgllahir','<?=$urutan?>')">Tempat Tanggal Lahir <?=change_urut('tgllahir',$urut,$urutan)?></td>
        <td width="8%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('aktif','<?=$urutan?>')">Status <?=change_urut('aktif',$urut,$urutan)?></td>
		<td width="15%">&nbsp;</td>
	</tr>
		<?php 
		
		CloseDb();
		if ($page==0){
			$cnt = 1;
		}else{ 
			$cnt = (int)$page*(int)$varbaris+1;
		}
		while ($row = @mysqli_fetch_row($result)) {
		
		?>	
	<tr>        			
		<td height="25" align="center"><?=$cnt?></td>
		<td height="25" align="center"><?=$row[0]?></td>
		<td height="25" align="left"><?=$row[12]?></td>
  		<td height="25" align="left"><?=$row[1]?></td>
        <td height="25" align="left"><?=$row[2]?></td>
        <!--<td height="25"><?=$row[3].', '.$row[6].'&nbsp;'.$namabulan.'&nbsp;'.$row[8]?></td>-->
        <td height="25" align="left"><?=$row[3].', '.$row[6].'&nbsp;'.NamaBulan($row[7]).'&nbsp;'.$row[8]?></td>
        <td height="25" align="center">
<?php 	if ($row[10] == 0) { ?>
	<?php 	if (SI_USER_LEVEL() == $SI_USER_STAFF) {  			
				if ($row[5] == 1) { 
					?> 
					<img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/>
	<?php 		} else { ?>                
					<img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/>
	<?php 		}
			} else {	
				if ($row[5] == 1) {	?>
					<a href="JavaScript:setaktif(<?=$row[9] ?>, <?=$row[5] ?>)"><img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/></a>
	<?php 		} else { 
					if ($kapasitas > $isi) {
					?>
					<a href="JavaScript:setaktif(<?=$row[9] ?>, <?=$row[5] ?>)"><img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/></a>
	<?php 			} else { ?>
					<img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status siswa tidak dapat diaktifkan karena kapasitas kelas tidak mencukupi!', this, event, '165px')"/>
	<?php 			
					}
				} //end if
			} //end if 
		} else {
			if ($row[5] == 1) { 
					?> 
					<img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/>
	<?php 		} else { ?>                
					<img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Sudah di mutasi!', this, event, '80px')"/>
	<?php 		}
		}	
			?>        	
</td>
        <td height="25" align="center">
        	<a href="JavaScript:tampil(<?=$row[9]?>)" ><img src="../images/ico/lihat.png" border="0" onMouseOver="showhint('Detail Data Siswa!', this, event, '80px')" /></a>&nbsp;        
        	<a href="#" onClick="newWindow('siswa_cetak_detail.php?replid=<?=$row[9]?>', 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak Detail Data Siswa!', this, event, '80px')"/></a>&nbsp;
			<a href="JavaScript:edit(<?=$row[9]?>)" /><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Data Siswa!', this, event, '80px')"/></a>&nbsp;
  		<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {	?>             	
        	<a href="JavaScript:hapus(<?=$row[9] ?>,'<?=$row[0] ?>')"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Data Siswa!', this, event, '80px')"/></a>
		<?php } ?>
        </td>
	</tr>
<?php 	$cnt++; 
		} 
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
    <table border="0"width="100%" align="center" cellpadding="0" cellspacing="0">	
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
	</td>
</tr>
</table>  
<?php } else { ?>

<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="300">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
       	<?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        <?php //} ?>        
        </b></font>
	</td>
</tr>
</table>  
<?php } ?> 
</td></tr>

<!-- END TABLE BACKGROUND IMAGE -->
</table> 
<?php
CloseDb();
?>
</body>
</html>