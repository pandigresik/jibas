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
$idtingkat=$_REQUEST['idtingkat'];
$idtahunajaran=$_REQUEST['idtahunajaran'];

if (isset($_REQUEST['idkelas']))
	$idkelas=$_REQUEST['idkelas'];

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

if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];

$ERROR_MSG = "";
if ($op=="xm8r389xemx23xb2378e23"){
	$nis=$_REQUEST['nis'];
	
	OpenDb();
	BeginTrans();
	$success=0;
	$sql = "DELETE FROM jbsakad.riwayatkelassiswa WHERE nis='$nis' AND idkelas='$idkelas' AND status=3";
	QueryDbTrans($sql, $success);
	
	if ($success){
		$sql = "SELECT r.idkelas FROM jbsakad.riwayatkelassiswa r, jbsakad.kelas k WHERE r.nis = '$nis' AND r.idkelas = k.replid ORDER BY mulai DESC LIMIT 1";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$idkelasasal = $row[0];
					
		$sql_jumlah="SELECT COUNT(s.nis) FROM jbsakad.siswa s WHERE s.idkelas = '$idkelasasal' AND aktif = 1";	
		$result_jumlah=QueryDb($sql_jumlah);
		$row_jumlah=@mysqli_fetch_row($result_jumlah);
			
		$sql_kapasitas="SELECT kapasitas FROM jbsakad.kelas WHERE replid = '".$idkelasasal."'";
		$result_kapasitas=QueryDb($sql_kapasitas);
		$row_kapasitas=@mysqli_fetch_row($result_kapasitas);
			
		if ((int)$row_jumlah[0] < (int)$row_kapasitas[0])
			$success = 1;
		else
			$success = 0; 
	}		
	
	if ($success){
		$sql = "UPDATE jbsakad.riwayatkelassiswa SET aktif=1 WHERE nis='$nis' AND idkelas='$idkelasasal'";
		QueryDbTrans($sql, $success);
	}
	
	if ($success){
		$sql = "UPDATE jbsakad.siswa SET idkelas='$idkelasasal' WHERE nis='$nis'";
		QueryDbTrans($sql, $success);
	}
	
	if ($success){
		CommitTrans();
		?>
		<script language="javascript">
			parent.siswa_pindah_menu.location.href="siswa_pindah_menu.php?idkelas=<?=$idkelasasal?>&departemen=<?=$departemen?>&idtahunajaran=<?=$idtahunajaran?>&idtingkat=<?=$idtingkat?>&pilihan=2&jenis=combo";
			parent.siswa_pindah_daftar.location.href="siswa_pindah_daftar.php?idkelas=<?=$idkelasasal?>&pilihan=2&jenis=combo&departemen=<?=$departemen?>&idtahunajaran=<?=$idtahunajaran?>&idtingkat=<?=$idtingkat?>";
		</script>
		<?php
	} else {
		RollBackTrans();
		$ERROR_MSG = '"Kapasitas kelas awal sudah penuh, siswa gagal kembali ke kelas awal!\nPindahkan siswa ke kelas lain"';
	}
	CloseDb();
	$page=0;
	$hal=0;
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<title>Tampil Siswa</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function change_kelas() {
	var departemen = document.getElementById("departemen").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idkelas = document.getElementById("idkelas").value;
	
	document.location.href = "siswa_pindah_content.php?departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&idkelas="+idkelas;
}

/*function refresh_content() {
	var departemen = document.getElementById("departemen").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idkelas = document.getElementById("idkelas").value;
	document.location.href = "siswa_pindah_content.php?departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&idkelas="+idkelas;
}*/

function change_urutan(urut,urutan) {
	//wait();	
	var departemen = document.getElementById("departemen").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idkelas = document.getElementById("idkelas").value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	//var urutan = document.getElementById("urutan").value;
	document.location.href = "siswa_pindah_content.php?departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&idkelas="+idkelas+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function change_page(page) {
	var idkelas = document.getElementById("idkelas").value;
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "siswa_pindah_content.php?idkelas="+idkelas+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}

function change_hal() {
	var idkelas = document.getElementById("idkelas").value;
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="siswa_pindah_content.php?idkelas="+idkelas+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var idkelas = document.getElementById("idkelas").value;
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href= "siswa_pindah_content.php?idkelas="+idkelas+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function batal_pindah(nis){
	var departemen=document.getElementById("departemen").value;
	var idtahunajaran=document.getElementById("idtahunajaran").value;
	var idtingkat=document.getElementById("idtingkat").value;
	var idkelas=document.getElementById("idkelas").value;
	
	if (confirm("Data Siswa ini akan dihapus.\nApakah anda yakin akan mengembalikan siswa ini ke kelas sebelumnya?")) {
		document.location.href="siswa_pindah_content.php?op=xm8r389xemx23xb2378e23&nis="+nis+"&departemen="+departemen+"&idtahunajaran="+idtahunajaran+"&idkelas="+idkelas+"&idtingkat="+idtingkat+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
	}	
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		return false;
    } 
    return true;
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<form name="kanan" action="#" method="post">
<input type="hidden" name="idtingkat" id="idtingkat" value="<?=$idtingkat?>" />
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" />
<input type="hidden" name="idtahunajaran" id="idtahunajaran" value="<?=$idtahunajaran?>" />
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
  	<td>    
    <strong>Kelas Tujuan</strong>&nbsp;
    <select name="idkelas" id="idkelas" onChange="change_kelas()" >
<?php  OpenDb();
    $sql_kelas="SELECT replid,kelas,kapasitas FROM jbsakad.kelas WHERE idtahunajaran='$idtahunajaran' AND idtingkat='$idtingkat' ORDER BY kelas";
    $result_kelas=QueryDb($sql_kelas);
    $cnt_kelas=1;
    while ($row_kelas=@mysqli_fetch_array($result_kelas)){
        if ($idkelas == "")
            $idkelas = $row_kelas['replid'];
            
        $kelas = $row_kelas['kelas'];			
        $sql1 = "SELECT COUNT(*) FROM siswa WHERE idkelas = '".$row_kelas['replid']."' AND aktif = 1";				
        $result1 = QueryDb($sql1);
        $row1 = @mysqli_fetch_row($result1); 				
        
?>
        <option value="<?=$row_kelas['replid']?>" <?=StringIsSelected($row_kelas['replid'], $idkelas) ?>><?=$row_kelas['kelas'].", kapasitas: ".$row_kelas['kapasitas'].", terisi: ".$row1[0]?></option>

<?php  
        if ($cnt_kelas==1)
            $id=$row_kelas['replid'];
        $cnt_kelas++;
    }
    CloseDb();
    ?>
    </select>
	</td>
</tr>
<tr>
    <td>
    	<?php
    if ($idkelas <> "" ) {	  
		OpenDb();
		
		$sql_tot = "SELECT s.nis,s.nama,s.idkelas,s.replid from jbsakad.siswa s, jbsakad.kelas k WHERE k.idtahunajaran='$idtahunajaran' AND k.idtingkat='$idtingkat' AND k.replid=s.idkelas AND s.idkelas='$idkelas' AND s.aktif=1 ORDER BY $urut $urutan"; 

		$result_tot = QueryDb($sql_tot);
		$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
		$jumlah = mysqli_num_rows($result_tot);
		$akhir = ceil($jumlah/5)*5;
		
		$sql_siswa = "SELECT s.nis,s.nama,s.idkelas,s.replid from jbsakad.siswa s, jbsakad.kelas k WHERE k.idtahunajaran='$idtahunajaran' AND k.idtingkat='$idtingkat' AND k.replid=s.idkelas AND s.idkelas='$idkelas' AND s.aktif=1 ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		$result_siswa = QueryDb($sql_siswa);
		
		if (mysqli_num_rows($result_siswa) > 0) {
	?>
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
	<tr height="30" class="header" align="center">
    	<td width="4%" >No</td>
    	<td width="15%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urutan('nis','<?=$urutan?>')" >N I S <?=change_urut('nis',$urut,$urutan)?></td>
    	<td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urutan('nama','<?=$urutan?>')" >Nama <?=change_urut('nama',$urut,$urutan)?></td>
        <td width="6%">&nbsp;</td>
  	</tr>  
  	<?php
			if ($page==0)
				$cnt_siswa = 0;
			else 
				$cnt_siswa = (int)$page*(int)$varbaris;
			
			while ($row_siswa = @mysqli_fetch_array($result_siswa)) {
				$nis=$row_siswa['nis'];
				$nama=$row_siswa['nama'];
				$idkelas=$row_siswa['idkelas'];
				
				$sql_riwayat_kelas="SELECT keterangan,status FROM jbsakad.riwayatkelassiswa WHERE nis='$nis' AND idkelas='$idkelas'";
				$result_riwayat_kelas=QueryDb($sql_riwayat_kelas);
				$row_riwayat = mysqli_fetch_array($result_riwayat_kelas);
	?>
  	<tr height="25"> 
  		<td align="center"><?=++$cnt_siswa?></td>
    	<td align="center"><?=$nis?></td>
    	<td><a href="#" onClick="newWindow('../library/detail_siswa.php?replid=<?=$row_siswa['replid']?>', 'DetailSiswa','800','650','resizable=0,scrollbars=1,status=0,toolbar=0')"><?=$nama?></a></td>
        <td align="center">
			<?php if ($row_riwayat['status']==3) {?>
        	<a href="#" onClick="javascript:batal_pindah('<?=$nis?>')"><img src="../images/ico/hapus.png" width="16" height="16" border="0" onMouseOver="showhint('Batalkan kepindahan kelas!', this, event, '120px')"/></a>
        	<?php } ?>
     	</td>
  	</tr>
  	<?php
			} CloseDb();
	?>	
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
    	<!--<td align="center">
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
 		</td>-->
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
		<td align="center" valign="middle" height="300">

    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
        <br />Belum ada siswa yang terdaftar pada kelas <?=$kelas?>.
       	</b></font>
		</td>
	</tr>
	</table>
<?php } 
} else { ?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">

    	<font size = "2" color ="red"><b>Belum ada kelas yang dituju.
       	</b></font>
		</td>
	</tr>
	</table>
<?php } ?>
	</td>
</tr>
</table>
</form>
<!-- Tamplikan error jika ada -->
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert(<?=$ERROR_MSG?>);
</script>
<?php } ?>

</body>
</html>
<script language="javascript">
	var spryselect12 = new Spry.Widget.ValidationSelect("idkelas");
</script>