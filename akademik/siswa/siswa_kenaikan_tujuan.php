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
$tahunajaranawal=$_REQUEST['tahunajaranawal'];
$tingkatawal=$_REQUEST['tingkatawal'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran=$_REQUEST['tahunajaran'];
if (isset($_REQUEST['tingkat']))
	$tingkat=$_REQUEST['tingkat'];
if (isset($_REQUEST['kelas']))
	$kelas=$_REQUEST['kelas'];
if (isset($_REQUEST['pilihan']))
	$pilihan=(int)$_REQUEST['pilihan'];

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

OpenDb();
$sql_ajaran = "SELECT replid,tglmulai FROM jbsakad.tahunajaran WHERE replid='$tahunajaranawal'";
$result_ajaran = QueryDb($sql_ajaran);
$row_ajaran = mysqli_fetch_array($result_ajaran);
$tglmulai = $row_ajaran['tglmulai'];

$sql_tingkat = "SELECT urutan FROM jbsakad.tingkat WHERE replid='$tingkatawal'";
$result_tingkat = QueryDb($sql_tingkat);
$row_tingkat = mysqli_fetch_array($result_tingkat);
$urutan_tingkat = $row_tingkat['urutan'];

if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];

if ($op=="hgiu82kjs98uqjq89wuj89sga")
{
	$nis = $_REQUEST['nis'];
	
	OpenDb();
	BeginTrans();
	$success=0;
	
	$sql_rwyt_del="DELETE FROM jbsakad.riwayatkelassiswa WHERE nis='$nis' AND idkelas='$kelas'";
	QueryDbTrans($sql_rwyt_del, $success);
	
	if ($success)
	{
		$sql_rwyt_get = "SELECT r.idkelas, k.idtingkat, k.idtahunajaran
						   FROM jbsakad.riwayatkelassiswa r, jbsakad.kelas k
						  WHERE r.nis='$nis' AND r.idkelas = k.replid
						  ORDER BY mulai DESC LIMIT 1";
		$result_rwyt_get = QueryDbTrans($sql_rwyt_get, $success);
		$row_rwyt_get = mysqli_fetch_row($result_rwyt_get);
	}
	
	if ($success)
	{
		$sql_rwyt_upd = "UPDATE jbsakad.riwayatkelassiswa
							SET aktif=1
						  WHERE nis='$nis' AND idkelas='".$row_rwyt_get[0]."'";
		QueryDbTrans($sql_rwyt_upd, $success);
	}
	
	if ($success)
	{
		$sql_siswa_upd = "UPDATE jbsakad.siswa SET idkelas='".$row_rwyt_get[0]."' WHERE nis='$nis'";
		QueryDbTrans($sql_siswa_upd, $success);
	}
	
	if ($success)
	{
		CommitTrans(); ?>
		<script language="javascript">
			parent.siswa_kenaikan_pilih.location.reload();
		</script>
<?php }
	else
	{
		RollBackTrans();
	}
	CloseDb();
}

$ERROR_MSG = "";
if ($op=="x2378e23dkofh73n25ki9234")
{
	$nis=$_REQUEST['nis'];
	$ket=CQ($_REQUEST['ket']);
	$kelasawal=$_REQUEST['kelasawal'];
	
	OpenDb();
	$sql_kap_kelas_tujuan="SELECT kapasitas FROM jbsakad.kelas WHERE replid='$kelas'";
	$result_kap_kelas_tujuan=QueryDb($sql_kap_kelas_tujuan);
	$row_kap_kelas_tujuan=mysqli_fetch_array($result_kap_kelas_tujuan);
	$kap_kelas_tujuan=$row_kap_kelas_tujuan['kapasitas'];
	
	$sql_jum_kelas_tujuan="SELECT COUNT(nis) FROM jbsakad.siswa WHERE idkelas='$kelas' AND aktif = 1";
	$result_jum_kelas_tujuan=QueryDb($sql_jum_kelas_tujuan);
	$row_jum_kelas_tujuan=mysqli_fetch_row($result_jum_kelas_tujuan);
	
	if ((int)$kap_kelas_tujuan <= (int)$row_jum_kelas_tujuan[0])
	{
		$ERROR_MSG = "Kapasitas kelas tujuan sudah penuh. Silahkan pilih kelas tujuan lain!";
	}
	else
	{
		// Jika jumlah murid kelas tujuan < dari kapasitasnya 
		$tahunsekarang=date('Y');
		$bulansekarang=date('m');
		$tanggalsekarang=date('j');
		$sekarang=$tahunsekarang."-".$bulansekarang."-".$tanggalsekarang;

		OpenDb();
		BeginTrans();
		$success=0;
		
		$sql_naik = "UPDATE jbsakad.siswa
						SET idkelas='$kelas'
					  WHERE nis='$nis'";
		"$sql_naik<br>";
		QueryDbTrans($sql_naik, $success);
			
		$sql_naik_kelas_update = "UPDATE jbsakad.riwayatkelassiswa
									 SET aktif=0
								   WHERE nis='$nis' AND idkelas='$kelasawal'";
		if ($success)
			QueryDbTrans($sql_naik_kelas_update, $success);
				
		$sql_naik_kelas_insert = "INSERT INTO jbsakad.riwayatkelassiswa
									 SET idkelas='$kelas', aktif=1, nis='$nis', mulai='$sekarang', status=1, keterangan='$ket'";
		if ($success)
			QueryDbTrans($sql_naik_kelas_insert, $success);	
				
		if ($success)
		{
			CommitTrans();	?>
			<script language="javascript">
				parent.siswa_kenaikan_pilih.location.reload();
			</script>
<?php 	}
		else
		{
			RollbackTrans();
		}
		CloseDb();	
	}
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
<script language="javascript">
function change_tahunajaran(){
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	
	document.location.href="siswa_kenaikan_tujuan.php?pilihan=1&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat;
}

function change_tingkat(){
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	
	document.location.href="siswa_kenaikan_tujuan.php?pilihan=1&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat;
}

function change_kelas(){
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	
	document.location.href="siswa_kenaikan_tujuan.php?pilihan=1&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas;
}

function change_urut(urut,urutan){
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	
	if (urutan =="ASC")
		urutan="DESC";
	else
		urutan="ASC";
		
	document.location.href="siswa_kenaikan_tujuan.php?pilihan=1&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function change_page(page) {
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "siswa_kenaikan_tujuan.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&kelas="+kelas+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}

function change_hal() {
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="siswa_kenaikan_tujuan.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&kelas="+kelas+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href= "siswa_kenaikan_tujuan.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&kelas="+kelas+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function batal_naik(nis){
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	var pilihan=document.getElementById("pilihan").value;
	
	if (confirm("Apakah anda yakin akan mengembalikan siswa ini ke tingkat & kelas sebelumnya?")) {
		document.location.href="siswa_kenaikan_tujuan.php?op=hgiu82kjs98uqjq89wuj89sga&nis="+nis+"&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&kelas="+kelas+"&tingkat="+tingkat+"&pilihan="+pilihan+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
			
	}	
}

function ubah_ket(nis,kelas) {
	newWindow('ubah_keterangan.php?nis='+nis+'&idkelas='+kelas, 'UbahKeterangan','417','315','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refresh() {
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	
	document.location.href="siswa_kenaikan_tujuan.php?pilihan=1&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
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
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" />
<input type="hidden" name="tingkatawal" id="tingkatawal" value="<?=$tingkatawal?>" />
<input type="hidden" name="tahunajaranawal" id="tahunajaranawal" value="<?=$tahunajaranawal?>" />
<input type="hidden" name="pilihan" id="pilihan" value="<?=$pilihan?>" />

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
  	<td height="50" align="center">
    <fieldset>
    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" >
    <!-- TABLE TITLE -->
    <tr>            
    	<td align="left" width="24%"><strong>Tahun&nbsp;Ajaran</strong></td>
       	<td align="left">
        	<select name="tahunajaran" id="tahunajaran" onChange="change_tahunajaran()" style="width:228px;" onKeyPress="return focusNext('tingkat', event)">
         <?php OpenDb();
			//$sql_tahunajaran="SELECT replid,tahunajaran,aktif FROM jbsakad.tahunajaran WHERE departemen='$departemen'";
			$sql_tahunajaran="SELECT replid,tahunajaran,aktif FROM jbsakad.tahunajaran WHERE departemen='$departemen' AND tglmulai > '$tglmulai' ORDER BY aktif DESC, tglmulai DESC";			$result_tahunajaran=QueryDb($sql_tahunajaran);
			while ($row_tahunajaran=@mysqli_fetch_array($result_tahunajaran)){
				if ($tahunajaran=="")
					$tahunajaran=$row_tahunajaran['replid'];
				if ($row_tahunajaran['aktif']) 
					$ada = '(Aktif)';
				else 
					$ada = '';
		?>
        	<option value="<?=$row_tahunajaran['replid']?>" <?=IntIsSelected($row_tahunajaran['replid'], $tahunajaran)?>><?=$row_tahunajaran['tahunajaran'].' '.$ada?></option>
      	<?php
			}
			CloseDb();
		?>
            </select></td>
  	</tr>
    <tr>
    	<td align="left"><strong>Tingkat </strong></td>
        <td align="left">
        	<select name="tingkat" id="tingkat" onChange="change_tingkat()"  style="width:228px;" onKeyPress="return focusNext('kelas', event)">
     	<?php OpenDb();
			$sql_tingkat="SELECT replid,tingkat FROM jbsakad.tingkat WHERE departemen='$departemen' AND urutan > '$urutan_tingkat' AND aktif = 1";
			$result_tingkat=QueryDb($sql_tingkat);
			while ($row_tingkat=@mysqli_fetch_array($result_tingkat)){
				if ($tingkat=="")
					$tingkat=$row_tingkat['replid'];
		?>
				<option value="<?=$row_tingkat['replid']?>" <?=IntIsSelected($row_tingkat['replid'], $tingkat)?>>
				<?=$row_tingkat['tingkat']?>
				</option>
		<?php
			 }
			 CloseDb();
		?>
        	</select>
       	</td>
  	</tr>
    <tr>
    	<td align="left"><strong>Kelas Tujuan</strong></td>
        <td align="left">
        	<select name="kelas" id="kelas" onChange="change_kelas()" style="width:228px;">
        <?php 	OpenDb();
			$sql_kelas="SELECT replid,kelas,kapasitas FROM jbsakad.kelas WHERE idtahunajaran='$tahunajaran' AND idtingkat='$tingkat' AND aktif=1";
			$result_kelas=QueryDb($sql_kelas);
			while ($row_kelas=@mysqli_fetch_array($result_kelas)){
				if ($kelas=="") 
					$kelas=$row_kelas['replid'];
				
				$sql_terisi="SELECT COUNT(*) FROM jbsakad.siswa WHERE idkelas='".$row_kelas['replid']."' AND aktif=1";
				$result_terisi=QueryDb($sql_terisi);
				$row_terisi=@mysqli_fetch_row($result_terisi);
		?>
          		<option value="<?=$row_kelas['replid']?>" <?=IntIsSelected($row_kelas['replid'], $kelas)?>>
                <?=$row_kelas['kelas'].", kapasitas: ".$row_kelas['kapasitas'].", terisi: ".$row_terisi[0]?>
              	</option>
              <?php
		 }
		 CloseDb();
		 ?>
           	</select>
     	</td>
 	</tr>
    </table>     
    </fieldset>
   	</td>
</tr>
<tr>
	<td>
<?php 
	if ($tahunajaran <> "" && $tingkat <> "" && $kelas <> "" ) {
		OpenDb();    
	
		$sql_tot = "SELECT s.replid,s.nis,s.nama FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t WHERE s.idkelas = '$kelas' AND k.idtahunajaran = '$tahunajaran' AND k.idtingkat = '$tingkat' AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.aktif=1";
		$result_tot = QueryDb($sql_tot);
		$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
		$jumlah = mysqli_num_rows($result_tot);
		$akhir = ceil($jumlah/5)*5;
		
		$sql_siswa = "SELECT s.replid,s.nis,s.nama FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t WHERE s.idkelas = '$kelas' AND k.idtahunajaran = '$tahunajaran' AND k.idtingkat = '$tingkat' AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.aktif=1 ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		
		$result_siswa = QueryDb($sql_siswa);
		$jum = @mysqli_num_rows($result_siswa);
		
		$sql5 = "SELECT kelas FROM jbsakad.kelas WHERE replid = '$kelas' ";
		$result5 = QueryDb($sql5);
		$row5 = @mysqli_fetch_array($result5);
		$nama_kelas = $row5['kelas'];
		
		if ($jum > 0) { ?> 
	
   	<table width="100%" border="1" cellspacing="0" class="tab" id="table" bordercolor="#000000">
  	<tr align="center" height="30" class="header">
    	<td width="8%"><div align="center">No</div></td>
    	<td width="25%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nis','<?=$urutan?>')" >N I S <?=change_urut('s.nis',$urut,$urutan)?></td>
     	<td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nama','<?=$urutan?>')">Nama <?=change_urut('s.nama',$urut,$urutan)?></td>
    	<td width="*">Keterangan</td>
    	<td width="6%">&nbsp;</td>
  	</tr>
   	<?php 
	if ($page==0)
		$cnt = 1;
	else 
		$cnt = (int)$page*(int)$varbaris+1;
		
	while ($row_siswa=@mysqli_fetch_array($result_siswa)){
		$sql_riwayat_kelas="SELECT keterangan,status FROM jbsakad.riwayatkelassiswa WHERE nis='".$row_siswa['nis']."' AND idkelas='$kelas'";
        $result_riwayat_kelas=QueryDb($sql_riwayat_kelas);
        $row_riwayat = mysqli_fetch_array($result_riwayat_kelas);
		
	?>
	<tr height="25"> 
        <td align="center"><?=$cnt?></td>
        <td align="center"><?=$row_siswa['nis']?></td>
        <td><a href="#" onClick="newWindow('../library/detail_siswa.php?replid=<?=$row_siswa['replid']?>', 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')"><?=$row_siswa['nama']?></a></td>
        <?php if ($row_riwayat['keterangan'] <> "") { ?>
        <td><?=$row_riwayat['keterangan']?>&nbsp;&nbsp;
        	<?php if ($row_riwayat['status'] == 1) {?>
        	<a href="#" onClick="JavaScript:ubah_ket('<?=$row_siswa['nis']?>',<?=$kelas?>)"><img src="../images/ico/ubah.png" width="16" height="16" border="0" onMouseOver="showhint('Ubah Keterangan Siswa!', this, event, '100px')"/></a>    
        	<?php } ?>
        </td>
        <?php } else { ?>
        <td align="center">
       		<?php if ($row_riwayat['status'] == 1) {?>
        	<a href="#" onClick="JavaScript:ubah_ket('<?=$row_siswa['nis']?>',<?=$kelas?>)"><img src="../images/ico/ubah.png" width="16" height="16" border="0" onMouseOver="showhint('Ubah Keterangan Siswa!', this, event, '100px')"/></a>    
        	<?php } ?>
        </td>
        <?php } ?>
        <td align="center">
			<?php if ($row_riwayat['status']==1) {?>
        	<a href="#" onClick="javascript:batal_naik('<?=$row_siswa['nis']?>')"><img src="../images/ico/hapus.png" width="16" height="16" border="0" onMouseOver="showhint('Batalkan kenaikan kelas!', this, event, '100px')"/></a>
        	<?php } ?>
     	</td>
  	</tr>
  	<?php $cnt++;
  	}
  	CloseDb();?>
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
    <input <?=$disback?> type="button" class="but" name="back" value="<<" onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')"-->
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
<?php } else {  ?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="250">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
        <br />Belum ada siswa yang terdaftar pada kelas <?=$nama_kelas?>.
        </b></font>
	</td>
	</tr>
	</table> 
<?php  } 
} else {
?>
 	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="250">
    	<?php if ($tahunajaran == "") {	?>
            <font size = "2" color ="red"><b>Tidak ada tahun ajaran yang lebih tinggi pada departemen <?=$departemen?>.
            <br />Tambah data tahun ajaran pada departemen <?=$departemen?> di menu Tahun Ajaran pada bagian Referensi. 
            </b></font>
		<?php } else if ($tingkat == "") { ?>
            <font size = "2" color ="red"><b>Tidak ada tingkat yang lebih tinggi pada departemen <?=$departemen?>.
            <br />Pindahkan siswa di menu Kelulusan pada bagian Kenaikan & Kelulusan.
            </b></font>
		<?php } else if ($kelas == "") { ?>    
        	<font size = "2" color ="red"><b>Belum ada kelas yang dituju.
            <br />Tambah data kelas pada departemen <?=$departemen?> di menu Kelas pada bagian Referensi. 
            </b></font>
        <?php } ?>
	</td>
	</tr>
	</table> 	
<?php } ?>
</td>
</tr>
<!-- END TABLE CENTER -->    
</table>
</form>
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>
</body>
</html>
<script language="javascript">
	var spryselect = new Spry.Widget.ValidationSelect("tahunajaran");
	var spryselect1 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect2 = new Spry.Widget.ValidationSelect("kelas");
</script>