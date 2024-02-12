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
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once("../include/sessionchecker.php");


if(isset($_POST["departemen"])){
	$departemen = $_POST["departemen"];
}elseif(isset($_GET["departemen"])){
	$departemen = $_GET["departemen"];
}
if(isset($_POST["tingkat"])){
	$tingkat = $_POST["tingkat"];
}elseif(isset($_GET["tingkat"])){
	$tingkat = $_GET["tingkat"];
}
if(isset($_POST["kelas"])){
	$kelas = $_POST["kelas"];
}elseif(isset($_GET["kelas"])){
	$kelas = $_GET["kelas"];
}
if(isset($_POST["tahun"])){
	$tahun = $_POST["tahun"];
}elseif(isset($_GET["tahun"])){
	$tahun = $_GET["tahun"];
}
if(isset($_POST["semester"])){
	$semester = $_POST["semester"];
}elseif(isset($_GET["semester"])){
	$semester = $_GET["semester"];
}
if(isset($_POST["pelajaran"])){
	$pelajaran = $_POST["pelajaran"];
}elseif(isset($_GET["pelajaran"])){
	$pelajaran = $_GET["pelajaran"];
}
if(isset($_POST["nis"])){
	$nois = $_POST["nis"];
}elseif(isset($_GET["pelajaran"])){
	$nois = $_GET["nis"];
}

OpenDb();

if (isset($_POST['simpan'])) {
	$sql = "UPDATE jbsakad.komennap SET predikat = '".$_POST['predikat']."' ".
    	   " WHERE nis = '$nois' AND idinfo  = '".$_POST['idinfo']."'";	
	$rs = QueryDb($sql);

	$nnilai = $_POST['nnilai'];
	for ($i = 0; $i < $nnilai; $i++) {						
		$idaturan = $_POST["idaturan" . $i];
		$nang = $_POST["nA" . $i];
		$nihu = $_POST["nH" . $i];
		$sql = "UPDATE jbsakad.nap SET nilaiangka = '$nang', nilaihuruf = '$nihu' " .
			   " WHERE nis = = '".$nois."' AND idinfo  = '".$_POST['idinfo']."' AND idaturan = '".$idaturan."'";
		$rs = QueryDb($sql);	   
	}
	
	if (mysqli_errno($mysqlconnection) == 0) {
		CloseDb();
?>
		<script language="javascript">
			opener.refreshpage();
			window.close();
		</script>
<?php 	
	} else {
		CloseDb();
?>
	<center>
		<font face="Arial, Helvetica, sans-serif" color="red" size="1">
		<?=mysqli_error($mysqlconnection)?>
		</font>
	</center>
<?php 
	}  
	
  	exit();
}

$query_infonap = "SELECT replid FROM sistoakademik.infonap " .
                 " WHERE infonap.IdKelas = '$kelas'  ".
        		 " AND infonap.IdPelajaran = '$pelajaran' ".
                 " AND infonap.IdSemester = '".$semester."'";
$result_infonap = QueryDb($query_infonap);
$row_infonap = @mysqli_fetch_array($result_infonap);
$num_infonap = @mysqli_num_rows($result_infonap);
$replid_infonap = 0;
if ($num_infonap > 0) {
	$replid_infonap = $row_infonap['replid'];
}
          
if ($replid_infonap == 0) {
	$query = "SELECT siswa.NIS, siswa.Nama, nau.NilaiAU, nau.IdJenis ".
   	         "FROM sistoakademik.siswa, sistoakademik.nau ".
             "WHERE siswa.NIS = nau.NIS ".
			 "AND siswa.NIS = '$nois' ".
             "AND nau.IdKelas = '$kelas'  ".
             "AND IdPelajaran = '$pelajaran' ".
             "AND IdSemester = '$semester' AND siswa.StatusSiswa = '1' ORDER BY Nama";
} else {
   $query = "SELECT siswa.NIS, siswa.Nama, nau.NilaiAU, nau.IdJenis ".
   	        "FROM sistoakademik.siswa, sistoakademik.nau ".
            "WHERE siswa.NIS = nau.NIS ".
			"AND siswa.NIS = '$nois' ".
            "AND nau.IdKelas = '$kelas'  ".
            "AND IdPelajaran = '$pelajaran' ".
            "AND IdSemester = '$semester'" .
            "AND siswa.IdKelas = '$kelas'" .
            "AND siswa.Tingkat = '$tingkat'" .
            "AND siswa.StatusSiswa = '1'".
            "AND siswa.NIS IN " .
            " ( SELECT nis FROM nap WHERE nap.idinfo = '$replid_infonap' ) ORDER BY Nama";
};     
$result = QueryDb($query) or die(mysqli_error($mysqlconnection));
$num = @mysqli_num_rows($result);

$my_data = "";
while($row = @mysqli_fetch_array($result)) {
    $my_data[$row['NIS']]['nama'] = $row['Nama'];
    $my_data[$row['NIS']][$row['IdJenis']] = $row['NilaiAU'];
}

$query_cek = "SELECT Replid, NilaiMin FROM sistoakademik.infonap ".
             "WHERE IdPelajaran = '$pelajaran' ".
             "AND IdSemester = '$semester' ".
             "AND IdKelas = '$kelas' ".
             "AND Tingkat = '".$tingkat."'";
$result_cek = QueryDb($query_cek);
$num_cek = @mysqli_num_rows($result_cek);
$row_cek = @mysqli_fetch_array($result_cek);
 
$query_ju = "SELECT Replid, JenisUjian FROM sistoakademik.jenisujian WHERE IdPelajaran = '".$pelajaran."'";
$result_ju = QueryDb($query_ju) or die(mysqli_error($mysqlconnection));
$num_ju = @mysqli_num_rows($result_ju);
while($row_ju = @mysqli_fetch_array($result_ju)) {
	$kolom[$row_ju['replid']] = $row_ju['replid'];
}
       
$query_nhb = "SELECT Replid, DasarPenilaian, BobotPenilaian ".
             "FROM sistoakademik.aturannhb WHERE IdPelajaran = '$pelajaran' ".
             "AND IdTingkat = '".$tingkat."'";
$result_nhb = QueryDb($query_nhb) or die(mysqli_error($mysqlconnection));
$num_nhb = @mysqli_num_rows($result_nhb);
while($row_nhb = @mysqli_fetch_array($result_nhb)) {
	$r_aturan[] = $row_nhb['replid'];
}

$disp_nis = "";
$disp_nama = "";
$nnilai = 0;
		
if($my_data != "") {
	$i = 0;
	foreach($my_data as $ns => $d) {
    	$i++;
		
		$disp_nis = $ns;
		$disp_nama = $d['NAMA'];
				
		if($r_aturan != 0){            
			$id_aturan = null;
			$nau_b = null;
			$ttl_bbt = null;
			$ttl_nau_b = null;
			$nilaiangka = null;
					
			$t = 0;
           	foreach($r_aturan as $id_aturan){		
			   	$t++;
				
				$query_nhb = "SELECT BobotPenilaian, DasarPenilaian ".
							 "FROM sistoakademik.aturannhb WHERE aturannhb.Replid = '$id_aturan'";
				$result_nhb = QueryDb($query_nhb) or die(mysqli_error($mysqlconnection));
		
				while($row_nhb = @mysqli_fetch_array($result_nhb)) {
					$plit = explode(";", (string) $row_nhb['BobotPenilaian']);
					if($plit != "") {
						foreach($plit as $pl) {
							$r++;
							[$ujian, $bobot] = explode(":", $pl);
							if($bobot != "") {
								$as[$r] = $bobot;
							}
								
							$query_nau = "SELECT nau.NilaiAU FROM sistoakademik.nau ".
										 "WHERE nis  = '$ns' ".
                                         "AND IdJenis = '$ujian' " .
                                         "AND IdKelas = '$kelas'  ". 
      							     	 "AND IdPelajaran = '$pelajaran' ". 
                                         "AND IdSemester = '".$semester."'";
							$result_nau = QueryDb($query_nau);
							$row_nau = mysqli_fetch_array($result_nau);

							$nau_b = $row_nau['NilaiAU'] * $bobot;
							$ttl_idat[$nnilai] = $id_aturan;
							$ttl_bbt[$nnilai] += $bobot;
							$ttl_nau_b[$nnilai] += $nau_b;
							$ttl_nama[$nnilai] = $row_nhb['DasarPenilaian'];
						} //foreach($plit as $pl) 
                     } //if($plit != "") 
                  } //while($row_nhb = @mysqli_fetch_array($result_nhb)) 
				  
				  $nnilai++;
            }// foreach($r_aturan as $id_aturan)
        } //if($r_aturan != 0) 
    } //foreach($my_data as $ns => $d) 
} //if($my_data != "") 
?>

<html>
<head>
<title>Perhitungan Ulang Nilai Rapor <?=$disp_nama?></title>
<link rel="stylesheet" type="text/css" href="../css/mystyle.css">
<link rel="stylesheet" type="text/css" href="../css/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../javascript/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../javascript/tables.js"></script>

<body>
<form name="main" method="post" onSubmit="return confirm('Apakah anda yakin akan mengubah nilai rapor siswa ini?');">
<fieldset>
<legend><b>Perhitungan Ulang Nilai Rapor</b></legend>
<table border="0">
<tr>
	<td width="70">NIS</td>
	<td width="10">:</td>
	<td><?=$disp_nis?></td>
</tr>
<tr>
	<td width="70">Nama</td>
	<td width="10">:</td>
	<td><?=$disp_nama?></td>
</tr>
</table>
<br>
<table width="95%" class="tab" border="1" id="table">
<tr>
	<td class="header" align="center">Aspek</td>
	<td class="header" width="120" align="center">Nilai Angka</td>
	<td class="header" width="120" align="center">Nilai Huruf</td>
</tr>
<?php
for($i = 0; $i < $nnilai; $i++) {
	$nilaiangka = $ttl_nau_b[$i] / $ttl_bbt[$i];
	$f = sprintf("%01.2f", $nilaiangka);
?>
<tr>
	<td><?=$ttl_nama[$i]?></td>
	<td align="center">
	<input type="hidden" name="idaturan<?=$i?>" value="<?=$ttl_idat[$i]?>">
	<input type="text" size="5" maxlength="6" name="nA<?=$i?>" value="<?=$f?>">
	</td>
	<td align="center"><input type="text" size="5" maxlength="5" name="nH<?=$i?>"></td>
</tr>
<?php
}
?>
<tr>
	<td><em><strong>Predikat</strong></em></td>
	<td colspan="2" align="center">
	<select name='predikat'>
		<option value='0' ></option>
        <option value='1' >Amat Baik</option>
        <option value='2' >Baik</option>
        <option value='3' >Cukup</option>
        <option value='4' >Kurang</option>
	</select>
	</td>
</tr>
</table>
<br>
<input type="hidden" name="nis" value="<?=$disp_nis?>">
<input type="hidden" name="nnilai" value="<?=$nnilai?>">
<input type="hidden" name="departemen" value="<?=$departemen?>">
<input type="hidden" name="tingkat" value="<?=$tingkat?>">
<input type="hidden" name="tahun" value="<?=$tahun?>">
<input type="hidden" name="kelas" value="<?=$kelas?>">
<input type="hidden" name="semester" value="<?=$semester?>">
<input type="hidden" name="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" name="idinfo" value="<?=$replid_infonap?>">
<input type="submit" name="simpan" value="Simpan" class="but">
<input type="button" name="Tutup" value="Tutup" class="but" onClick="window.close()">
</fieldset>
</form>
<script language='JavaScript'>
	Tables('table', 1, 0);
</script>
</body>
</html>