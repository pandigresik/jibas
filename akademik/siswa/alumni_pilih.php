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

$jenis="xxxx";
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
	$ket=$_REQUEST['ket'];
if (isset($_REQUEST['depasal']))
	$depasal=$_REQUEST['depasal'];
if (isset($_REQUEST['depawal']))
	$depawal=$_REQUEST['depawal'];
if (isset($_REQUEST['tahunajaranawal']))
	$tahunajaranawal=$_REQUEST['tahunajaranawal'];
if (isset($_REQUEST['tingkatawal']))
	$tingkatawal=$_REQUEST['tingkatawal'];
if (isset($_REQUEST['angkatan']))
	$angkatan=$_REQUEST['angkatan'];
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

$tahun1 = date("Y");
$th = date("Y");
if (isset($_REQUEST['th']))
	$th = $_REQUEST['th'];
$tgl = date("j");
if (isset($_REQUEST['tgl']))
	$tgl = $_REQUEST['tgl'];
$bln = date("n");
if (isset($_REQUEST['bln']))
	$bln = $_REQUEST['bln'];

$string = "";
if ($pilihan == 1)
{			
	if ($namadicari == "")			
		$string = " s.nis LIKE '%$nisdicari%' AND ";
	if ($nisdicari == "")
		$string = " s.nama LIKE '%$namadicari%' AND ";
	if ($nisdicari <> "" && $namadicari <> "")
		$string = " s.nis LIKE '%$nisdicari%' OR s.nama LIKE '%$namadicari%' AND ";
}
elseif ($pilihan == 2)
{			
	$string = " s.idkelas = $kelas AND ";
} 

$n = JmlHari($bln, $th);	
	
OpenDb();	
$sql = "SELECT YEAR(tgllulus) AS tahun
	  	  FROM alumni
		 WHERE departemen='$departemen'
		 GROUP BY tahun
		 ORDER BY tahun DESC";
$result = QueryDb($sql);
if (isset($_REQUEST['alumnikan']))
{
	$success = true;
	BeginTrans();
	
	$thn = $_REQUEST['th'];
	$bln = $_REQUEST['bln'];
	$tgl = $_REQUEST['tgl'];
	$tgllulus = $thn."-".$bln."-".$tgl;

	$jumalumni = (int)$_REQUEST['total'];
	for ($ialumni = 1; $success && $ialumni <= $jumalumni; $ialumni++)
	{
		$nis = $_REQUEST["nis".$ialumni];
		$cek = $_REQUEST["ceknis".$ialumni];
		
		if ($nis && $cek)
		{
			$sql1 = "SELECT k.replid, k.idtingkat
					   FROM jbsakad.siswa s, jbsakad.kelas k
					  WHERE s.nis = '$nis'
					    AND s.idkelas = k.replid";
			$result1 = QueryDb($sql1);
			$row1 = @mysqli_fetch_array($result1);
			$idtingkat = $row1['idtingkat'];
			$idkelas = $row1['replid'];
			
			$sql_siswa = "UPDATE jbsakad.siswa SET aktif=0, alumni=1 WHERE nis='$nis'";
			QueryDbTrans($sql_siswa, $success);

			if ($success)
			{	
				$sql_kelas = "UPDATE jbsakad.riwayatkelassiswa
								 SET aktif=0
							   WHERE nis='$nis'
							     AND aktif=1";
				QueryDbTrans($sql_kelas, $success);
			}
			
			if ($success)
			{
				$sql_dept = "UPDATE jbsakad.riwayatdeptsiswa
				                SET aktif = 0
						  	  WHERE nis='$nis'
							    AND aktif=1";
				QueryDbTrans($sql_dept,$success);
			}
			
			if ($success)
			{
				$sql_alumni = "INSERT INTO jbsakad.alumni
								  SET nis='$nis', tgllulus='$tgllulus', tktakhir='$idtingkat',
									  klsakhir='$idkelas', departemen = '".$departemen."'";
				QueryDbTrans($sql_alumni, $success);
			}
		} // if
	} // for
	
	if ($success)
	{
		CommitTrans(); ?>
		<script language="javascript">
			parent.alumni_content.location.href="alumni_content.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&tahun=<?=$th?>";
		</script>	
<?php 	}
	else
	{
		RollbackTrans();
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kelulusan Siswa[Pilih]</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function change_urutan(urut,urutan){
	var jenis=document.getElementById("jenis").value;
	var nisdicari = document.getElementById("nisdicari").value;
	var namadicari = document.getElementById("namadicari").value;
	var departemen = document.getElementById("departemen").value;
	var tingkat = document.getElementById("tingkat").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var kelas = document.getElementById("kelas").value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href="alumni_pilih.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&nisdicari="+nisdicari+"&namadicari="+namadicari+"&departemen="+departemen+"&kelas="+kelas+"&urut="+urut+"&urutan="+urutan+"&jenis="+jenis+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
	
}

function cek_all() {
	var x;
	var jum = document.pilih.total.value;
	var ceked = document.pilih.cek.checked;
	for (x=1;x<=jum;x++){
		if (ceked==true){
			document.getElementById("ceknis"+x).checked=true;
		} else {
			document.getElementById("ceknis"+x).checked=false;
		}
	}
}

function alumnikeun() {
	var jumalumni=0;
	var jum = document.pilih.total.value;
	var tgl = document.getElementById('tgl').value;
	for (x=1;x<=jum;x++){
		var nis=document.getElementById("ceknis"+x).checked;
		if (nis == 1){
			jumalumni++;	
		}
	}
	
	if (tgl.length == 0) {	
		alert ('Tanggal tidak boleh kosong!');	
		document.getElementById('tgl').focus();
		return false;	
	}
		
	if (jumalumni==0) {
		alert ("Anda harus memberi centang setidaknya satu siswa untuk menjadikannya alumnus!");
		return false;
	} else if (jumalumni > 0) {
		if (confirm("Apakah Anda yakin akan mengalumnikan siswa yang telah diberi tanda centang?"))
			return true;
		else
			return false;	
	}
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
	
	document.location.href = "alumni_pilih.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&nisdicari="+nisdicari+"&namadicari="+namadicari+"&departemen="+departemen+"&kelas="+kelas+"&jenis="+jenis+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
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
	
	document.location.href="alumni_pilih.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&nisdicari="+nisdicari+"&namadicari="+namadicari+"&departemen="+departemen+"&kelas="+kelas+"&jenis="+jenis+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
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
	
	document.location.href= "alumni_pilih.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&nisdicari="+nisdicari+"&namadicari="+namadicari+"&departemen="+departemen+"&kelas="+kelas+"&jenis="+jenis+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_tgl() {
	var th = parseInt(document.getElementById('th').value);
	var bln = parseInt(document.getElementById('bln').value);
	var tgl = parseInt(document.pilih.tgl.value);
	var namatgl = "tgl";
	var namabln = "bln";
	
	sendRequestText("../library/gettanggal.php", show, "tahun="+th+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);	
}

function show(x) {
	document.getElementById("InfoTgl").innerHTML = x;
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

function panggil(elem){
	var lain = new Array('tgl','bln','th');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

function refresh_pilih() {
	var pilihan=document.getElementById("pilihan").value;
	var departemen=document.getElementById("departemen").value;
	var tingkat=document.getElementById("tingkat").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var kelas=document.getElementById("kelas").value;
	var nisdicari=document.getElementById("nisdicari").value;
	var namadicari=document.getElementById("namadicari").value;
	var jenis=document.getElementById("jenis").value;
		
	document.location.href="siswa_kenaikan_pilih.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&nisdicari="+nisdicari+"&namadicari="+namadicari+"&departemen="+departemen+"&kelas="+kelas+"&urut=<?=$urut?>&urutan=<?=$urutan?>&jenis="+jenis+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

</script>
</head>

<body topmargin="0" leftmargin="0">
<form name="pilih" id="pilih" onSubmit="return alumnikeun()">
<input type="hidden" name="pilihan" id="pilihan" value="<?=$pilihan?>">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" />
<input type="hidden" name="nisdicari" id="nisdicari" value="<?=$nisdicari?>" />
<input type="hidden" name="namadicari" id="namadicari" value="<?=$namadicari?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>" />
<input type="hidden" name="jenis" id="jenis" value="<?=$jenis?>" />

<?php
if ($jenis <> "")
{ 
	OpenDb();
	$sql_tot = "SELECT s.nis,s.nama,s.idkelas,k.kelas,s.replid,t.tingkat
				  FROM jbsakad.siswa s, kelas k, tingkat t
				 WHERE $string s.idkelas = k.replid
				   AND k.idtahunajaran = '$tahunajaran'
				   AND s.aktif=1
				   AND k.idtingkat = t.replid
				   AND t.replid = '".$tingkat."'"; 
	$result_tot = QueryDb($sql_tot);
	$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;	
	
	$sql_siswa = "SELECT s.nis,s.nama,s.idkelas,k.kelas,s.replid,t.tingkat
				    FROM jbsakad.siswa s, kelas k, tingkat t
				   WHERE $string s.idkelas = k.replid
				     AND k.idtahunajaran = '$tahunajaran'
					 AND s.aktif=1 AND k.idtingkat = t.replid
					 AND t.replid = '$tingkat'
				   ORDER BY $urut $urutan
				   LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	
	$result_siswa = QueryDb($sql_siswa);
	if (@mysqli_num_rows($result_siswa)>0)
	{
?>	
<input type="hidden" name="total" id="total" value="<?=$jumlah?>">
<table width="100%" border="0" align="center">
<tr>
	<td> 
    	<table border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td><strong>Tanggal Lulus</strong>&nbsp;</td>
           	<td>
            <div id = "InfoTgl" >
            <select name="tgl" id = "tgl" onChange="change_tgl()" onfocus = "panggil('tgl')" onKeyPress="focusNext('bln',event)">
            <option value="">[Tgl]</option>
        <?php 	for($i=1;$i<=$n;$i++){   ?>      
            <option value="<?=$i?>" <?=IntIsSelected($tgl, $i)?>><?=$i?></option>
        <?php } ?>
            </select>
            </div>
            </td>
            <td>
            <select name="bln" id ="bln" onChange="change_tgl()" onfocus = "panggil('bln')" onKeyPress="focusNext('th',event)">
        <?php 	for ($i=1;$i<=12;$i++) { ?>
            <option value="<?=$i?>" <?=IntIsSelected($bln, $i)?>><?=$bulan[$i]?></option>	
        <?php }	?>	
            </select>
            <select name="th" id = "th" onChange="change_tgl()" onfocus = "panggil('th')" style="width:60px">
        <?php  for ($i = $tahun1-5; $i <= $tahun1; $i++) { ?>
            <option value="<?=$i?>" <?=IntIsSelected($th, $i)?>><?=$i?></option>	   
        <?php } ?>	
            </select> 
            </td>
       	</tr>
        </table>
    </td>
</tr>
<tr>
	<td align="left" valign="top">
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
	<tr align="center">
		<td width="6%" height="30" rowspan="2" class="header">No</td>
		<td width="15%" height="30" rowspan="2" class="header" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urutan('s.nis','<?=$urutan?>')">N I S <?=change_urut('s.nis',$urut,$urutan)?></td>
		<td width="*" height="30" rowspan="2" class="header" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urutan('s.nama','<?=$urutan?>')" >Nama <?=change_urut('s.nama',$urut,$urutan)?></td>
		<td height="30" width="20%"rowspan="2" class="header" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urutan('k.kelas','<?=$urutan?>')">Kelas <?=change_urut('k.kelas',$urut,$urutan)?></td>
		<td height="15" colspan="2" class="header">Alumni</td>
	</tr>
	<tr>
  		<td width="11%" class="header" colspan="2">
  		<input type="checkbox" name="cek" id="cek" onClick="cek_all()" onMouseOver="showhint('Pilih semua!', this, event, '80px')"/>
		</td>
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
        <td align="center">
            <input type="checkbox" name="ceknis<?=$cnt?>" id="ceknis<?=$cnt?>" value="1"/>
            <input type="hidden" name="nis<?=$cnt?>" id="nis<?=$cnt?>" value="<?=$row_siswa[0]?>" />
        </td>
        <?php if ($cnt==1){ ?>
        <td rowspan="<?=$jumlah?>" align="center">
            <input name="alumnikan" id="alumnikan" type="submit" class="but" value=" > " onMouseOver="showhint('Alumnikan siswa ini', this, event, '120px')"/>
        </td>
        <?php } ?>
	</tr>
<?php 	$cnt++;
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
       	<td width="45%" align="left">Hal
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
	  	dari <?=$total?> hal
        </td>
    	<td align="center">
		&nbsp;
 		</td>
        <td width="45%" align="right">Jml baris per hal
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
      menampilkan daftar siswa yang akan menjadi alumnus &nbsp;</b></font>
 	</td>
	</tr>
	</table>
<?php 	} ?>
	</td>
</tr>
</table>
</form>
</body>
</html>