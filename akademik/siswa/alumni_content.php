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
	
$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$urut = "s.nama";
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	
$tahun = "";
if (isset($_REQUEST['tahun']))
	$tahun = $_REQUEST['tahun'];

if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql="SELECT a.tktakhir, a.klsakhir, a.nis, k.idtahunajaran FROM alumni a, kelas k WHERE a.replid='".$_REQUEST['replid']."' AND a.klsakhir = k.replid AND k.idtingkat=a.tktakhir";
	//echo $sql;
	$result=QueryDb($sql);
	$row = mysqli_fetch_array($result);
	$nis = $row['nis'];
	$idtingkat = $row['tktakhir'];
	$idkelas = $row['klsakhir'];
	$idtahunajaran = $row['idtahunajaran'];
	
	BeginTrans();
	$success=0;
	
	$sql1="UPDATE jbsakad.riwayatkelassiswa SET aktif=1 WHERE nis='$nis' AND idkelas = '$idkelas' ORDER BY mulai DESC LIMIT 1";
	//echo $sql1."<br>";
	$result1=QueryDbTrans($sql1, $success);
	
	if ($success){
		$sql1="UPDATE jbsakad.riwayatdeptsiswa SET aktif=1 WHERE nis='$nis' AND departemen='$departemen' ORDER BY mulai DESC LIMIT 1";
		//echo $sql1."<br>";
		$result1=QueryDbTrans($sql1, $success);
	}
	
	if ($success){
		$sql1="UPDATE jbsakad.siswa SET aktif=1,alumni=0 WHERE nis='$nis'";
		//echo $sql1."<br>";
		$result=QueryDbTrans($sql1, $success);
	}
	
	if ($success){
		$sql1="DELETE FROM jbsakad.alumni WHERE replid='".$_REQUEST['replid']."'";
		//echo $sql1."<br>";
		$result1=QueryDbTrans($sql1, $success);
	}
	
	if ($success){
		CommitTrans();
		?>
		<script language="javascript">
			var tingkat = parent.alumni_menu.document.menu.tingkat.value;
			var kelas = parent.alumni_menu.document.menu.kelas.value; 
			var tahunajaran = parent.alumni_menu.document.menu.tahunajaran.value;
			
			if (tingkat == <?=$idtingkat?> && tahunajaran == <?=$idtahunajaran?>) {
				parent.alumni_menu.location.href="alumni_menu.php?kelas=<?=$idkelas?>&departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&tingkat=<?=$idtingkat?>&pilihan=2&jenis=combo";
				parent.alumni_pilih.location.href="alumni_pilih.php?kelas=<?=$idkelas?>&pilihan=2&jenis=combo&departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&tingkat=<?=$idtingkat?>";
			} else {
				parent.alumni_menu.location.href="alumni_menu.php?kelas="+kelas+"&departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&tingkat="+tingkat;
				parent.alumni_pilih.location.href="alumni_pilih.php?kelas="+kelas+"&pilihan=2&jenis=combo&departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&tingkat="+tingkat;
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
	
function change_tahun() {
	var tahun = document.getElementById('tahun').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
		
	document.location.href = "alumni_content.php?departemen="+departemen+"&tahun="+tahun+"&tingkat="+tingkat+"&tahunajaran="+tahunajaran;
}

function hapus(nis, replid) {
	var tahunajaran = document.getElementById('tahunajaran').value;
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
	var tahun = document.getElementById('tahun').value;
	
	if (confirm("Apakah anda yakin akan mengembalikan siswa ini ke Departemen, Tingkat dan Kelas sebelumnya?"))
		document.location.href = "alumni_content.php?op=xm8r389xemx23xb2378e23&nis="+nis+"&replid="+replid+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&tingkat="+tingkat+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>"
}

function change_urut(urut,urutan){
	var tahunajaran = document.getElementById('tahunajaran').value;
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
	var tahun = document.getElementById('tahun').value;
	
	if (urutan =="ASC")
		urutan="DESC";
	else
		urutan="ASC";
		
	document.location.href="alumni_content.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&tahun="+tahun+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function change_page(page) {
	var tahunajaran = document.getElementById('tahunajaran').value;
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
	var tahun = document.getElementById('tahun').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "alumni_content.php?tahunajaran="+tahunajaran+"&departemen="+departemen+"&tingkat="+tingkat+"&tahun="+tahun+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}

function change_hal() {
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var tahun=document.getElementById("tahun").value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="alumni_content.php?tahunajaran="+tahunajaran+"&departemen="+departemen+"&tingkat="+tingkat+"&tahun="+tahun+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var tahun=document.getElementById("tahun").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href= "alumni_content.php?tahunajaran="+tahunajaran+"&departemen="+departemen+"&tingkat="+tingkat+"&tahun="+tahun+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
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
    <strong>Tahun Lulus</strong>&nbsp;
    <select name="tahun" id="tahun" onChange="change_tahun()" style="width:60px">
	<?php  
	OpenDb();
    $sql="SELECT YEAR(tgllulus) AS tahun FROM alumni WHERE departemen='$departemen' GROUP BY tahun ORDER BY tahun DESC";
    //$sql="SELECT YEAR(tglmulai) AS tahunmulai, YEAR(tglakhir) AS tahunakhir FROM tahunajaran WHERE replid=$tahunajaran";
	$result=QueryDb($sql);
    while ($row=@mysqli_fetch_array($result)){
		if ($tahun=="")
			$tahun = $row['tahun'];	
	?>
        <option value="<?=$row['tahun']?>" <?=IntIsSelected($row['tahun'], $tahun) ?>><?=$row['tahun']?>
        </option>
        <!--<option value="<?//=$row['tahunakhir']?>" <?//= IntIsSelected($row['tahunakhir'], $tahun) ?>><?//=$row['tahunakhir']?>
        </option>-->
	<?php
	}  
    CloseDb();
    ?>
    </select>
	</td>
</tr>
<tr>
    <td>
<?php
if ($tahun <> "" ) {	
	OpenDb();    
	//$sql_tot = "SELECT s.replid,s.nis,s.nama FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t WHERE s.idkelas = $kelas AND k.idtahunajaran = $tahunajaran AND k.idtingkat = $tingkat AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.aktif=1";
	
	$sql_tot = "SELECT s.replid, s.nis, s.nama, k.kelas, al.tgllulus, al.klsakhir, al.tktakhir, al.replid, t.tingkat FROM alumni al, kelas k, tingkat t, siswa s WHERE al.departemen='$departemen' AND k.idtingkat=t.replid AND t.replid=al.tktakhir AND k.replid=al.klsakhir AND YEAR(al.tgllulus) = '$tahun' AND s.nis = al.nis AND s.alumni = 1";
	//echo $sql_tot;
	$result_tot = QueryDb($sql_tot);
	$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	
	$sql_siswa = "SELECT s.replid AS replidsiswa, s.nis, s.nama, k.kelas, al.tgllulus, al.klsakhir, al.tktakhir, al.replid, t.tingkat FROM alumni al, kelas k, tingkat t, siswa s WHERE al.departemen='$departemen' AND k.idtingkat=t.replid AND t.replid=al.tktakhir AND k.replid=al.klsakhir AND YEAR(al.tgllulus) = '$tahun' AND s.nis = al.nis AND s.alumni = 1 ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		
	$result_siswa = QueryDb($sql_siswa);
	$jum = @mysqli_num_rows($result_siswa);
		
	if ($jum > 0) { ?> 
    <table width="100%" border="1" cellspacing="0" class="tab" id="table" bordercolor="#000000">
  	<tr align="center" height="30" class="header">
    	<td width="4%">No</td>
        <td width="13%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nis','<?=$urutan?>')">N I S <?=change_urut('s.nis',$urut,$urutan)?></td>
        <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nama','<?=$urutan?>')">Nama <?=change_urut('s.nama',$urut,$urutan)?></td>
        <td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('t.tingkat, k.kelas','<?=$urutan?>')">Kls Terakhir <?=change_urut('t.tingkat,k.kelas',$urut,$urutan)?></td>
        <!--<td width="9%"> Tingkat Terakhir</td>
        <td width="11%">Departemen Terakhir</td>-->
		<td width="22%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('al.tgllulus','<?=$urutan?>')">Tanggal Lulus <?=change_urut('al.tgllulus',$urut,$urutan)?></td>
        <!--<td width="10%" class="header" align="center">Keterangan</td>--->
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
        <td align="center"><?=LongDateFormat($row_siswa['tgllulus'])?></td>
        <td align="center"><a href="JavaScript:hapus('<?=$row_siswa['nis'] ?>', <?=$row_siswa['replid']?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Batalkan sebagai alumnus!', this, event, '100px')"/></a>
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
		<td align="center" valign="middle" height="250">

    	<font size = "2" color ="red"><b>Belum ada data Alumni pada departemen <?=$departemen?>
       	</b></font>
		</td>
	</tr>
	</table>
<?php } 
} else { ?>
<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="250">

    	<font size = "2" color ="red"><b>Belum ada data Alumni pada departemen <?=$departemen?>
       	</b></font>
		</td>
	</tr>
	</table>
<?php
}
?>
	</td>
</tr>
</table>
</form>
</body>
</html>
<script language="javascript">
	var spryselect12 = new Spry.Widget.ValidationSelect("tahun");
</script>