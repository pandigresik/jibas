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
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../cek.php');

$departemen=$_REQUEST['departemen'];
if (isset($_REQUEST['tingkat']))
	$tingkat=$_REQUEST['tingkat'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran=$_REQUEST['tahunajaran'];
if (isset($_REQUEST['kelas']))
	$kelas=$_REQUEST['kelas'];

OpenDb();
$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];
$urut = "s.nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	
$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql="SELECT m.nis, s.idkelas, m.tglmutasi, k.idtahunajaran, k.idtingkat FROM mutasisiswa m, siswa s, kelas k WHERE m.replid='".$_REQUEST['replid']."' AND s.nis = m.nis AND s.idkelas = k.replid";
	
	$result=QueryDb($sql);
	$row = mysqli_fetch_array($result);
	$nis = $row['nis'];
	$tglmutasi = $row['tglmutasi'];
	$idkelas = $row['idkelas'];
	$idtingkat = $row['idtingkat'];
	$idtahunajaran = $row['idtahunajaran'];
	
	BeginTrans();
	$success=0;
	
	$sql1="UPDATE jbsakad.riwayatkelassiswa SET aktif=1 WHERE nis='$nis' AND idkelas = '$idkelas' ORDER BY mulai DESC LIMIT 1";
	$result1=QueryDbTrans($sql1, $success);
	
	if ($success){
		$sql1="UPDATE jbsakad.riwayatdeptsiswa SET aktif=1 WHERE nis='$nis' AND departemen='$departemen' ORDER BY mulai DESC LIMIT 1";
		$result1=QueryDbTrans($sql1, $success);
	}
	
	if ($success){
		$sql1="UPDATE jbsakad.siswa SET aktif=1, statusmutasi=NULL, alumni = 0 WHERE nis='$nis'";
		$result=QueryDbTrans($sql1, $success);
	}
	
	if ($success){
		$sql1="DELETE FROM jbsakad.alumni WHERE nis='$nis' AND departemen = '$departemen' AND klsakhir='$idkelas' AND tgllulus = '".$tglmutasi."'";
		$result1=QueryDbTrans($sql1, $success);
	}
	
	if ($success){
		$sql1="DELETE FROM jbsakad.mutasisiswa WHERE replid='".$_REQUEST['replid']."'";
		$result1=QueryDbTrans($sql1, $success);
	}
	
	if ($success){
		CommitTrans();
		
		?>
		<script language="javascript">
			var tingkat = parent.mutasi_siswa_menu.document.menu.tingkat.value;
			var kelas = parent.mutasi_siswa_menu.document.menu.kelas.value; 
			var tahunajaran = parent.mutasi_siswa_menu.document.menu.tahunajaran.value;
						
			if (tingkat == <?=$idtingkat?> && tahunajaran == <?=$idtahunajaran?>) {
				parent.mutasi_siswa_menu.location.href="mutasi_siswa_menu.php?kelas=<?=$idkelas?>&departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&tingkat=<?=$tingkat?>&pilihan=2&jenis=combo";
				parent.mutasi_siswa_pilih.location.href="mutasi_siswa_daftar.php?idkelas=<?=$idkelas?>&pilihan=2&jenis=combo&departemen=<?=$departemen?>&idtahunajaran=<?=$tahunajaran?>&idtingkat=<?=$tingkat?>";
				
			} else {
				parent.mutasi_siswa_menu.location.href="mutasi_siswa_menu.php?kelas="+kelas+"&departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&tingkat="+tingkat;
				parent.mutasi_siswa_pilih.location.href="mutasi_siswa_daftar.php?idkelas="+kelas+"&pilihan=2&jenis=combo&departemen=<?=$departemen?>&idtahunajaran=<?=$tahunajaran?>&idtingkat="+tingkat;
			}
	 	</script>
        <?php
		
	} else {
		RollbackTrans();
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
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function hapus(nis, replid) {
	var tahunajaran = document.getElementById('tahunajaran').value;
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
		
	if (confirm("Apakah anda yakin akan mengembalikan siswa ini ke Departemen, Tingkat dan Kelas sebelumnya?"))
		document.location.href = "mutasi_siswa_content.php?op=xm8r389xemx23xb2378e23&nis="+nis+"&replid="+replid+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&tingkat="+tingkat+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>"
}

function change_urut(urut,urutan){
	var tahunajaran = document.getElementById('tahunajaran').value;
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
		
	if (urutan =="ASC")
		urutan="DESC";
	else
		urutan="ASC";
		
	document.location.href="mutasi_siswa_content.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function change_page(page) {
	var tahunajaran = document.getElementById('tahunajaran').value;
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "mutasi_siswa_content.php?tahunajaran="+tahunajaran+"&departemen="+departemen+"&tingkat="+tingkat+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}

function change_hal() {
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="mutasi_siswa_content.php?tahunajaran="+tahunajaran+"&departemen="+departemen+"&tingkat="+tingkat+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var tahun=document.getElementById("tahun").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href= "mutasi_siswa_content.php?tahunajaran="+tahunajaran+"&departemen="+departemen+"&tingkat="+tingkat+"&tahun="+tahun+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function refresh_isi() {
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
		
	document.location.href="mutasi_siswa_content.php?tahunajaran="+tahunajaran+"&departemen="+departemen+"&tingkat="+tingkat+"&page=<?=$page?>&hal=<?=$hal?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>";
}
</script>
</head>
<body leftmargin="0" topmargin="0">
<form name="pilih" id="pilih">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>" />
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
  	<td>
<?php
	OpenDb();    
	$sql_tot = "SELECT s.replid, s.nis, s.nama, k.kelas, m.tglmutasi, j.jenismutasi, m.keterangan, m.replid, t.tingkat FROM mutasisiswa m, kelas k, tingkat t, siswa s, jenismutasi j WHERE m.departemen='$departemen' AND k.idtingkat=t.replid AND k.replid=s.idkelas AND k.idtahunajaran = '$tahunajaran' AND j.replid = m.jenismutasi AND s.nis = m.nis ";
	
	$result_tot = QueryDb($sql_tot);
	$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	
	$sql_siswa = "SELECT s.replid AS replidsiswa, s.nis, s.nama, k.kelas, m.tglmutasi, j.jenismutasi, m.keterangan, m.replid, t.tingkat FROM mutasisiswa m, kelas k, tingkat t, siswa s, jenismutasi j WHERE m.departemen='$departemen' AND k.idtingkat=t.replid AND k.replid=s.idkelas AND k.idtahunajaran = '$tahunajaran' AND j.replid = m.jenismutasi AND s.nis = m.nis ORDER BY $urut $urutan, kelas ASC LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		
	$result_siswa = QueryDb($sql_siswa);
	$jum = @mysqli_num_rows($result_siswa);
		
	if ($jum > 0) { ?> 
    <table width="100%" border="1" cellspacing="0" class="tab" id="table" bordercolor="#000000">
  	<tr align="center" height="30" class="header">
    	<td width="4%">No</td>
        <td width="13%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nis','<?=$urutan?>')">N I S <?=change_urut('s.nis',$urut,$urutan)?></td>
        <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nama','<?=$urutan?>')">Nama <?=change_urut('s.nama',$urut,$urutan)?></td>
        <td width="18%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('t.tingkat','<?=$urutan?>')">Kls Terakhir <?=change_urut('t.tingkat',$urut,$urutan)?></td>
		<td width="22%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('m.tglmutasi','<?=$urutan?>')">Tgl Mutasi <?=change_urut('m.tglmutasi',$urut,$urutan)?></td>
        <td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('j.jenismutasi','<?=$urutan?>')">Jenis Mutasi <?=change_urut('j.jenismutasi',$urut,$urutan)?></td>	
        <!--<td width="*" class="header" align="center">Keterangan</td>-->
        <td width="5%">&nbsp;</td>
    </tr>
<?php 	
	if ($page==0)
		$cnt = 1;
	else 
		$cnt = (int)$page*(int)$varbaris+1;
		
	while ($row_siswa=@mysqli_fetch_array($result_siswa)){
?>
    <tr height="25">
    	<td align="center"><?=$cnt ?></td>
        <td align="center"><?=$row_siswa['nis'] ?></td>
        <td><a href="#" onClick="newWindow('../library/detail_siswa.php?replid=<?=$row_siswa['replidsiswa']?>', 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')" ><?=$row_siswa['nama']?></a></td>
        <td align="center"><?=$row_siswa['tingkat']." - ".$row_siswa['kelas']?></td>
        <td align="center"><?=LongDateFormat($row_siswa['tglmutasi'])?></td>
        <td><?=LongDateFormat($row_siswa['jenismutasi'])?></td>
        <td align="center"><a href="JavaScript:hapus('<?=$row_siswa['nis'] ?>', <?=$row_siswa['replid']?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Batalkan mutasi!', this, event, '100px')"/></a>
		</td>
   	</tr>
	<?php $cnt++; 
	} 
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
    <table border="0"width="100%" align="center" cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="50%" align="left">Hal
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
	  	dari <?=$total?> hal
		
		<?php 
     // Navigasi halaman berikutnya dan sebelumnya
        ?>
        </td>
    	<!--td align="center">
    <input <?=$disback?> type="button" class="but" name="back" value="<<" onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
		<?php
		/*for($a=0;$a<$total;$a++){
			if ($page==$a){
				echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
			}
				 
	    }*/
		?>
	    <input <?=$disnext?> type="button" class="but" name="next" value=">>" onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
 		</td-->
        <td width="50%" align="right">Jml baris per hal
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>	
<?php 						
		} else {
	?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="325" background="../images/ico/b_mutasi.png"
    style="background-repeat:no-repeat;">
		<br /><br />
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
        <br />Belum ada siswa yang dimutasi pada departemen <?=$departemen?>.
       	</b></font>
		</td>
	</tr>
	</table>
<?php 
} ?>
	</td>
</tr>
</table>
</form>
</body>
</html>