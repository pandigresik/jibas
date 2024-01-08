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

if (isset($_REQUEST['departemen']))
	$departemen=$_REQUEST['departemen'];
if (isset($_REQUEST['pilihan']))
	$pilihan=(int)$_REQUEST['pilihan'];
if (isset($_REQUEST['nisdipindah']))
	$nisdipindah=$_REQUEST['nisdipindah'];
if (isset($_REQUEST['namadicari']))
	$namadicari=$_REQUEST['namadicari'];
if (isset($_REQUEST['nisdicari']))
	$nisdicari=$_REQUEST['nisdicari'];
if (isset($_REQUEST['kelas']))
	$kelas=$_REQUEST['kelas'];
if (isset($_REQUEST['tingkat']))
	$tingkat=$_REQUEST['tingkat'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran=$_REQUEST['tahunajaran'];
if (isset($_REQUEST['kelastujuan']))
	$kelastujuan=$_REQUEST['kelastujuan'];
if (isset($_REQUEST['tingkattujuan']))
	$tingkattujuan=$_REQUEST['tingkattujuan'];
if (isset($_REQUEST['tahunajarantujuan']))
	$tahunajarantujuan=$_REQUEST['tahunajarantujuan'];
if (isset($_REQUEST['ket']))
	$ket=CQ($_REQUEST['ket']);
if (isset($_REQUEST['tahunajaranawal']))
	$tahunajaranawal=$_REQUEST['tahunajaranawal'];
if (isset($_REQUEST['tingkatawal']))
	$tingkatawal=$_REQUEST['tingkatawal'];
if (isset($_REQUEST['jenis']))
	$jenis=$_REQUEST['jenis'];
if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];
	
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

if ($jenis <> ""){
	$string = "";
	if ($jenis == "text") {			
		if ($namadicari == "")			
			$string = "s.nis LIKE '%$nisdicari%' AND";
		if ($nisdicari == "")
			$string = "s.nama LIKE '%$namadicari%' AND";
		if ($nisdicari <> "" && $namadicari <> "")
			$string = "s.nis LIKE '%$nisdicari%' AND s.nama LIKE '%$namadicari%' AND";
	} else if ($jenis == "combo") {			
		$string = "s.idkelas = '$kelas' AND";
	} 
} 

/*$ERROR_MSG = "";
if ($op=="x2378e23dkofh73n25ki9234"){
	//cek kapasitas kelas tujuan
	OpenDb();
	$sql_kap_kelas_tujuan="SELECT kapasitas FROM jbsakad.kelas WHERE replid=$kelastujuan";
	$result_kap_kelas_tujuan=QueryDb($sql_kap_kelas_tujuan);
	$row_kap_kelas_tujuan=mysqli_fetch_array($result_kap_kelas_tujuan);
	$kap_kelas_tujuan=$row_kap_kelas_tujuan['kapasitas'];
	
	$sql_jum_kelas_tujuan="SELECT COUNT(nis) FROM jbsakad.siswa WHERE idkelas=$kelastujuan AND aktif = 1";
	$result_jum_kelas_tujuan=QueryDb($sql_jum_kelas_tujuan);
	$row_jum_kelas_tujuan=mysqli_fetch_row($result_jum_kelas_tujuan);
	
	if ((int)$kap_kelas_tujuan<=(int)$row_jum_kelas_tujuan[0]){
		$ERROR_MSG = "Kapasitas kelas tujuan sudah penuh.  Silahkan pilih kelas tujuan lain!";
	} else { // Jika jumlah murid kelas tujuan < dari kapasitasnya 
		$tahunsekarang=date('Y');
		$bulansekarang=date('m');
		$tanggalsekarang=date('j');
		$sekarang=$tahunsekarang."-".$bulansekarang."-".$tanggalsekarang;
		OpenDb();
		BeginTrans();
		$success=0;
		$sql_naik="UPDATE jbsakad.siswa SET idkelas=$kelastujuan WHERE nis='$nisdipindah'";
		QueryDbTrans($sql_naik, $success);
		if ($success){
			$sql_naik_kelas_update="UPDATE jbsakad.riwayatkelassiswa SET aktif=0 WHERE nis='$nisdipindah' AND idkelas='$kelas'";
			QueryDbTrans($sql_naik_kelas_update, $success);
		}
		
		if ($success){
			$sql_naik_kelas_insert="INSERT INTO jbsakad.riwayatkelassiswa SET idkelas='$kelastujuan', aktif=1, nis='$nisdipindah' ,mulai='$sekarang',status=2,keterangan='$ket'";
			QueryDbTrans($sql_naik_kelas_insert, $success);
		}
		
		if ($success){
			CommitTrans(); 
			?>
			<script language="javascript">
			parent.siswa_tidak_naik_tujuan.location.href = "siswa_tidak_naik_tujuan.php?kelas=<?=$kelastujuan?>&departemen=<?=$departemen?>&tingkatawal=<?=$tingkatawal?>&tahunajaranawal=<?=$tahunajaranawal?>&pilihan=<?=$pilihan?>";
			document.location.href = "siswa_tidak_naik_pilih.php?kelas=<?=$kelas?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&tahunajaran=<?=$tahunajaran?>&namadicari=<?=$namadicari?>&nisdicari=<?=$nisdicari?>&pilihan=<?=$pilihan?>";
			</script>
			<?php 
		} else {
			RollbackTrans();
		}
		CloseDb();	
	
		}
	}     
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kenaikan Kelas[Pilih]</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function pindah_siswa(nis,idkelas, i) {
	var ket = document.getElementById("ket_"+i).value;
	
	var tingkattujuan = parent.siswa_tidak_naik_tujuan.document.kanan.tingkatawal.value;
	var tahunajarantujuan = parent.siswa_tidak_naik_tujuan.document.kanan.tahunajaran.value;
	//var tingkatawal = parent.siswa_tidak_naik_tujuan.document.kanan.tingkatawal.value;
	//var tahunajaranawal = parent.siswa_tidak_naik_tujuan.document.kanan.tahunajaranawal.value;
	var kelastujuan = parent.siswa_tidak_naik_tujuan.document.kanan.kelas.value;
	var tingkat = document.getElementById('tingkat').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var departemen = document.getElementById('departemen').value;
	var pilihan = document.getElementById("pilihan").value;
	var nisdicari = document.getElementById("nisdicari").value;
	var namadicari = document.getElementById("namadicari").value;
	var jenis = document.getElementById("jenis").value;
	
	if (kelas == kelastujuan){
		alert ('Anda tidak dapat memindahkan siswa ke kelas yang sama !');
		return false;
	}	
	if (tahunajarantujuan.length==0){
		alert ('Tidak ada tahunajaran tujuan!');
		return false;
	}	
	if (tingkattujuan.length==0){
		alert ('Tidak ada tingkat tujuan!');
		return false;
	}		
	if (kelastujuan.length==0){
		alert ('Tidak ada kelas tujuan atau kelas tujuan yang aktif!');
		return false;
	}
	
	if (confirm("Apakah anda yakin akan menempatkan siswa ini tetap tinggal kelas?")){
		parent.siswa_tidak_naik_tujuan.location.href = "siswa_tidak_naik_tujuan.php?op=x2378e23dkofh73n25ki9234&departemen=<?=$departemen?>&tahunajaran="+tahunajarantujuan+"&tahunajaranawal="+tahunajaran+"&tingkatawal="+tingkat+"&kelas="+kelastujuan+"&nis="+nis+"&ket="+ket+"&kelasawal="+kelas;
		refresh_pilih(i);
	}
}
function change_urutan(urut,urutan){
	var pilihan=document.getElementById("pilihan").value;
	var departemen=document.getElementById("departemen").value;
	var tingkat=document.getElementById("tingkat").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var kelas=document.getElementById("kelas").value;
	var nisdicari=document.getElementById("nisdicari").value;
	var namadicari=document.getElementById("namadicari").value;
	var jenis=document.getElementById("jenis").value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href="siswa_tidak_naik_pilih.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&nisdicari="+nisdicari+"&namadicari="+namadicari+"&departemen="+departemen+"&kelas="+kelas+"&urut="+urut+"&urutan="+urutan+"&jenis="+jenis+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function change_page(page) {
	var departemen=document.getElementById("departemen").value;
	var tingkat=document.getElementById("tingkat").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var kelas=document.getElementById("kelas").value;
	var nisdicari=document.getElementById("nisdicari").value;
	var namadicari=document.getElementById("namadicari").value;
	var jenis=document.getElementById("jenis").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "siswa_tidak_naik_pilih.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&nisdicari="+nisdicari+"&namadicari="+namadicari+"&departemen="+departemen+"&kelas="+kelas+"&jenis="+jenis+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}

function change_hal() {
	var departemen=document.getElementById("departemen").value;
	var tingkat=document.getElementById("tingkat").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var kelas=document.getElementById("kelas").value;
	var nisdicari=document.getElementById("nisdicari").value;
	var namadicari=document.getElementById("namadicari").value;
	var jenis=document.getElementById("jenis").value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="siswa_tidak_naik_pilih.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&nisdicari="+nisdicari+"&namadicari="+namadicari+"&departemen="+departemen+"&kelas="+kelas+"&jenis="+jenis+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var departemen=document.getElementById("departemen").value;
	var tingkat=document.getElementById("tingkat").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var kelas=document.getElementById("kelas").value;
	var nisdicari=document.getElementById("nisdicari").value;
	var namadicari=document.getElementById("namadicari").value;
	var jenis=document.getElementById("jenis").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href= "siswa_tidak_naik_pilih.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&nisdicari="+nisdicari+"&namadicari="+namadicari+"&departemen="+departemen+"&kelas="+kelas+"&jenis="+jenis+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
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

function refresh_pilih(i) {
	var pilihan=document.getElementById("pilihan").value;
	var departemen=document.getElementById("departemen").value;
	var tingkat=document.getElementById("tingkat").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var kelas=document.getElementById("kelas").value;
	var nisdicari=document.getElementById("nisdicari").value;
	var namadicari=document.getElementById("namadicari").value;
	var jenis=document.getElementById("jenis").value;
	var ket = document.getElementById("ket_"+i).value;
		
	document.location.href="siswa_tidak_naik_pilih.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&nisdicari="+nisdicari+"&namadicari="+namadicari+"&departemen="+departemen+"&kelas="+kelas+"&urut=<?=$urut?>&urutan=<?=$urutan?>&jenis="+jenis+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&count="+i+"&ket="+ket;
}
</script>
</head>
<body>
<form name="pilih" id="pilih">
<input type="hidden" name="pilihan" id="pilihan" value="<?=$pilihan?>">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" />
<input type="hidden" name="nisdicari" id="nisdicari" value="<?=$nisdicari?>" />
<input type="hidden" name="namadicari" id="namadicari" value="<?=$namadicari?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>" />
<input type="hidden" name="jenis" id="jenis" value="<?=$jenis?>" />

<?php 	if ($jenis <> ""){ 
		OpenDb();
		$sql_tot = "SELECT s.nis,s.nama,s.idkelas,k.kelas,s.replid,t.tingkat FROM jbsakad.siswa s, kelas k, tingkat t WHERE $string s.idkelas = k.replid AND k.idtahunajaran = $tahunajaran AND s.aktif=1 AND k.idtingkat = t.replid AND t.replid = $tingkat"; 
		
		$result_tot = QueryDb($sql_tot);
		$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
		$jumlah = mysqli_num_rows($result_tot);
		$akhir = ceil($jumlah/5)*5;	
		
		$sql_siswa = "SELECT s.nis,s.nama,s.idkelas,k.kelas,s.replid,t.tingkat FROM jbsakad.siswa s, kelas k, tingkat t WHERE $string s.idkelas = k.replid AND k.idtahunajaran = $tahunajaran AND s.aktif=1 AND k.idtingkat = t.replid AND t.replid = $tingkat ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		
		$result_siswa = QueryDb($sql_siswa);
		if (@mysqli_num_rows($result_siswa)>0) {
?>	

<input type="hidden" name="total" id="total" value="<?=$jumlah?>">
<table width="100%" border="0" class="tab" id="table">
<table width="100%" border="0" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top">
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
	<tr align="center">
    	<td width="6%" rowspan="2" class="header" height="30">No</td>
		<td width="15%" height="30" rowspan="2" class="header" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urutan('s.nis','<?=$urutan?>')" >N I S <?=change_urut('s.nis',$urut,$urutan)?></td>      
		<td width="*" height="30" rowspan="2" class="header" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urutan('s.nama','<?=$urutan?>')" >Nama <?=change_urut('s.nama',$urut,$urutan)?></td>
		<td width="14%" height="30" rowspan="2" class="header" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urutan('k.kelas','<?=$urutan?>')"><div align="center">Kelas <?=change_urut('k.kelas',$urut,$urutan)?></td>
		<td height="15" colspan="2" class="header">Tidak Naik</td>
    </tr>
    <tr>
    	<td width="26%" class="header">Keterangan</td>
        <td width="8%" class="header">&nbsp;</td>
    </tr>

<?php 	if ($page==0)
			$cnt = 1;
		else 
			$cnt = (int)$page*(int)$varbaris+1;
		while ($row_siswa=@mysqli_fetch_row($result_siswa)){
            $sql_kelas="SELECT replid,kelas FROM jbsakad.kelas WHERE replid='".$row_siswa[2]."'";
            $result_kelas=QueryDb($sql_kelas);
            $row_kelas=@mysqli_fetch_row($result_kelas);
?>

    <tr height="25">
    	<td align="center"><?=$cnt?></td>
    	<td align="center"><?=$row_siswa[0]?></td>
    	<td><a href="#" onClick="newWindow('../library/detail_siswa.php?replid=<?=$row_siswa[4]?>', 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')"><?=$row_siswa[1]?></a></td>
    	<td align="center"><?=$row_siswa[5]." - ".$row_siswa[3]?></td>
    	<td align="center"><input type="text" size="15" maxlength="255" id="ket_<?=$cnt?>" name="ket_<?=$row_siswa[0]?>" onKeyPress="return focusNext('ket_<?=$cnt+1?>', event)" value="<?php if ($_REQUEST['count'] == $cnt) echo $ket ?>"/></td>
    	<td align="center"><input type="button" class="but" value=" > " onClick="pindah_siswa('<?=$row_siswa[0]?>', '<?=$row_siswa[2]?>', <?=$cnt?>)" onMouseOver="showhint('Klik untuk tidak kelas!', this, event, '80px')"/></td>
    </tr>
<?php 		$cnt++;
		}
		
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
       	<td width="30%" align="left">Hal
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
    	<td align="center">
    	<!--<input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
		<?php
		for($a=0;$a<$total;$a++){
			if ($page==$a){
				echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
			}
				 
	    }
		?>
	  <input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">-->
 		</td>
        <td width="30%" align="right">Jml baris per hal
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
		<td align="center" valign="middle" height="200">

    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
        <br />Tambah data siswa pada departemen <?=$departemen?> di menu Kesiswaan pada bagian Pendataan Siswa.
       	</b></font>
        
		</td>
	</tr>
	</table>
<?php 	}
	} else {
?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">    	  	
    	<font size="2" color="#757575"><b>Klik pada tombol &quot;Tampil&quot; atau &quot;Cari&quot; untuk
      menampilkan daftar siswa yang akan tidak naik kelas &nbsp;</b></font>
 	</td>
	</tr>
	</table>
<?php 	} ?>
	</td>
</tr>
</table>
</form>
<!-- Tamplikan error jika ada -->

</body>
</html>
<script language="javascript">
	var page = document.getElementById('hal').value;
	var varbaris = document.getElementById('varbaris').value;
	var total = document.getElementById('total').value;
	var i, x;
	if (page == 0)
		x = 1;
	else 
		x = parseInt(page)*parseInt(varbaris)+1;
		
	for (i=x;i<=total;i++)	{
		var sprytextfield1 = new Spry.Widget.ValidationTextField("ket_"+i);
	}
</script>