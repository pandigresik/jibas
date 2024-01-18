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
if(isset($_POST["kelas"])){
	$kelas = $_POST["kelas"];
}elseif(isset($_GET["kelas"])){
	$kelas = $_GET["kelas"];
}
if(isset($_POST["pelajaran"])){
	$pelajaran = $_POST["pelajaran"];
}elseif(isset($_GET["pelajaran"])){
	$pelajaran = $_GET["pelajaran"];
}
if(isset($_REQUEST["jenis_penilaian"]))
	$jenis_penilaian = $_REQUEST["jenis_penilaian"];
if(isset($_REQUEST["dasar_penilaian"]))
	$dasar_penilaian = $_REQUEST["dasar_penilaian"];
if(isset($_POST["perubahan"])){
	$perubahan = $_POST["perubahan"];
}elseif(isset($_GET["perubahan"])){
	$perubahan = $_GET["perubahan"];
}
$keter=0;
?>

<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/cal2.js"></script>
<script language="javascript" src="../script/cal_conf2.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript">
function change_sel(){
    var departemen = document.tampil_nilai_pelajaran.departemen.value;
	var tingkat = document.tampil_nilai_pelajaran.tingkat.value;
    var semester = document.tampil_nilai_pelajaran.semester.value;
	var kelas = document.tampil_nilai_pelajaran.kelas.value;
	var pelajaran = document.tampil_nilai_pelajaran.pelajaran.value;
	var tahun = document.tampil_nilai_pelajaran.tahun.value;
	var jenis_penilaian = document.tampil_nilai_pelajaran.jenis_penilaian.value;
	var dasar_penilaian = document.tampil_nilai_pelajaran.dasar_penilaian.value;
    document.location.href="tampil_nilai_pelajaran.php?departemen="+departemen+"&tingkat="+tingkat+"&semester="+semester+"&pelajaran="+pelajaran+"&tahun="+tahun+"&jenis_penilaian="+jenis_penilaian+"&kelas="+kelas+"&dasar_penilaian="+dasar_penilaian;    
}
function sel(no) {
	//alert(no);
	document.tampil_nilai_pelajaran.pilih.value = no;
}
function clist(cno) {
	//alert(cno);
	document.tampil_nilai_pelajaran.check.value = cno;
}
function validate(){
var pilih;
	pilih = document.tampil_nilai_pelajaran.pilih.value;
	cek = document.tampil_nilai_pelajaran.check.value;
//	t_max = document.tampil_nilai_pelajaran.t_max.value;

	if(pilih.length == 0){
		alert("Anda harus menentukan jenis perhitungan untuk menghitung nilai akhir");
		return false;
	}
	else if(pilih == 1){
		if(cek.length == 0){
			alert("Anda harus menentukan jenis penilaian untuk menghitung rata-rata nilai");
		return false;
		}
		eval("bobot = document.tampil_nilai_pelajaran.bobot" + cek + ".value;");
		if(bobot.length == 0){
			alert("Anda harus mengisi bobot jenis penilaian untuk menghitung rata-rata nilai");
		return false;
		}
	}
	return true;
}
function hapus() {
    return window.confirm("Anda yakin akan menghapus data ini?");
}
</script>
</head>
<body bgcolor="#FFFFFF"><!--
Departemen=<?=$departemen?><br>
Tingkat=<?=$tingkat?><br>
TA=<?=$tahun?><br>
Kls=<?=$kelas?><br>
Pelajaran=<?=$pelajaran?><br>
Semester=<?=$semester?><br>
-->
<table border="0" width="100%" height="100%">
    <tr>
	    <td align="center" valign="top">
<?php
OpenDb();
?>
<form name="tampil_nilai_pelajaran" action="tampil_nilai_pelajaran.php" method="post" onSubmit="return validate();">
<input type="hidden" name="departemen" value="<?=$departemen ?>">
<input type="hidden" name="pelajaran" value="<?=$pelajaran ?>">
<input type="hidden" name="kelas" value="<?=$kelas ?>">
<input type="hidden" name="tingkat" value="<?=$tingkat ?>">
<input type="hidden" name="tahun" value="<?=$tahun ?>">
<input type="hidden" name="semester" value="<?=$semester ?>">
<input type="hidden" name="dasar_penilaian" value="<?=$dasar_penilaian ?>">
    <fieldset><legend><b>Jenis Penilaian
	<?php
	$sql_jenisujian="SELECT * FROM jbsakad.jenisujian WHERE replid='$jenis_penilaian'";
	$result_jenisujian=QueryDb($sql_jenisujian);
	$row_jenisujian=@mysqli_fetch_array($result_jenisujian);
	echo $row_jenisujian['jenisujian'];
	?><br>
	Dasar Penilaian : <?=$dasar_penilaian?><br>
	Pelajaran : <?php
	$sql_pelajaran="SELECT nama FROM jbsakad.pelajaran WHERE replid=$pelajaran";
	$result_pelajaran=QueryDb($sql_pelajaran);
	$row_pelajaran=@mysqli_fetch_array($result_pelajaran);
	echo $row_pelajaran['nama'];
	?><input type="hidden" name="jenis_penilaian" id="jenis_penilaian" value="<?=$jenis_penilaian?>">
	</b>
	<table width="100%">
		<tr>
			<td align="right">
			<a href="#" onClick="newWindow('tambah_nilai_pelajaran2.php?departemen=<?=$departemen; ?>&tingkat=<?=$tingkat ?>&pelajaran=<?=$pelajaran ?>&semester=<?=$semester ?>&kelas=<?=$kelas ?>&tahun=<?=$tahun ?>&jenis_penilaian=<?=$jenis_penilaian ?>','Tambah Nilai Pelajaran',584,532,'resizable=1,scrollbars=1,status=0,toolbar=0')">
            <img src="../images/ico/tambah.png" border="0">Tambah Penilaian Pelajaran</a>
			<a href="#null" onClick="newWindow('cetak_nilai_pelajaran.php?departemen=<?=$departemen; ?>&tingkat=<?=$tingkat ?>&pelajaran=<?=$pelajaran ?>&semester=<?=$semester ?>&kelas=<?=$kelas ?>&tahun=<?=$tahun ?>&jenis_penilaian=<?=$jenis_penilaian ?>',
            'Penilaian Pelajaran','800','600','resizable=1,scrollbars=1,status=0,toolbar=0')">
            <img src="../images/ico/print.png" border="0">Cetak</a>
			</td>
		</tr>
	</table>
	<?php
	$query_uj = "SELECT nilaiujian.replid, nilaiujian.idujian, nilaiujian.nis, siswa.nama,  length(nilaiujian.keterangan) as lenket, nilaiujian.nilaiujian ".
				"FROM jbsakad.ujian, jbsakad.nilaiujian, jbsakad.siswa ".
				"WHERE ujian.idjenis = '$jenis_penilaian' ".
				"AND ujian.idpelajaran = '$pelajaran' ".
				"AND ujian.idkelas = '$kelas' ".
				"AND ujian.idsemester = '$semester' ".
				"AND ujian.replid = nilaiujian.idujian ".
				//"AND siswa.idtingkat = '$tingkat' ".
				"AND siswa.idkelas = '$kelas' ".
				"AND siswa.nis = nilaiujian.nis ".
                "AND siswa.aktif = '1' ORDER BY siswa.nama, ujian.tanggal, nilaiujian.idujian";
	$result_uj = QueryDb($query_uj) or die (mysqli_error($mysqlconnection));
	
	//echo $query_uj;
	$num_uj = @mysqli_num_rows($result_uj);
	
	while($row_uj = @mysqli_fetch_array($result_uj)){
		$my_data[$row_uj['nis']]['n'][$row_uj['idujian']]['nilai'] = $row_uj['nilaiujian'];
		$my_data[$row_uj['nis']]['n'][$row_uj['idujian']]['id'] = $row_uj['replid'];
		$my_data[$row_uj['nis']]['n'][$row_uj['idujian']]['idujian'] = $row_uj['idujian'];
		$my_data[$row_uj['nis']]['n'][$row_uj['idujian']]['status'] = $row_uj['statuspenilaian'];
		$my_data[$row_uj['nis']]['n'][$row_uj['idujian']]['lenket'] = $row_uj['lenket'];
		//$my_data[$row_uj['nis']]['replid'] = $row_uj['replid'];
		$my_data[$row_uj['nis']]['nama'] = $row_uj['nama'];
	}
	?>
	<table width="100%" id="table" class="tab" border="1">
		<tr >
			<td class="headerlong" align="center" height="30" valign="middle">No</td>
			<td class="headerlong" height="30" valign="middle">NIS</td>
			<td class="headerlong" height="30" valign="middle">Nama</td>
			<?php 		
				$query_qz = "SELECT ujian.replid, ujian.tanggal, jenisujian.jenisujian ".
							"FROM jbsakad.ujian, jbsakad.jenisujian ".
							"WHERE ujian.idjenis = '$jenis_penilaian' ".
							"AND ujian.idpelajaran = '$pelajaran' ".
							"AND ujian.idkelas = '$kelas' ".
							"AND ujian.idsemester = '$semester' ".
							"AND ujian.idjenis = jenisujian.replid ORDER BY ujian.tanggal, ujian.replid";						
				$result_qz = QueryDb($query_qz);
				//echo "<br>$query_qz";									
			$z = 0;
			$nujian = 0;
			while($row_qz = @mysqli_fetch_array($result_qz)){
				$z++;
				$sql = "SELECT count(*) as cnt FROM jbsakad.nilaiujian WHERE idujian = '".$row_qz['replid']."' GROUP BY nis, nilaiujian HAVING cnt > 1";
				$rs = QueryDb($sql);
				$ndup = mysqli_num_rows($rs);
			?>
			<td class="headerlong" align="center"  height="30">				
			<?php 
				$tgl = format_tgl($row_qz['tanggal']);
				echo  "{$row_qz['jenisujian']}-$z"; ?>
				<a href="#" onClick="newWindow('ubah_nilai_pelajaran.php?id=<?=$row_qz['replid']; ?>&departemen=<?=$departemen; ?>&tingkat=<?=$tingkat ?>&pelajaran=<?=$pelajaran ?>&semester=<?=$semester ?>&kelas=<?=$kelas ?>&tahun=<?=$tahun ?>&jenis_penilaian=<?=$jenis_penilaian ?>','Ubah Nilai Pelajaran',555,366,'resizable=1,scrollbars=0,status=0,toolbar=0')">
				<img src="../images/ico/ubah.png" border="0"></a>
				<a href="hapus_ujian.php?id=<?=$row_qz['replid']; ?>&departemen=<?=$departemen ?>&tahun=<?=$tahun ?>&tingkat=<?=$tingkat ?>&pelajaran=<?=$pelajaran ?>&kelas=<?=$kelas ?>&semester=<?=$semester ?>&jenis_penilaian=<?=$jenis_penilaian ?>"
				onClick="return hapus();"><img src="../images/ico/hapus.png" border="0"></a>
				
			<?="<br>($tgl)"; 
				if ($ndup > 0) {
        echo "<br><font color='cyan'><strong>*DUPLIKASI*</strong></font>";
    }
			?>
			</td>								
					<?php
					$kol_idujian[$nujian] = $row_qz['replid'];
					$nujian++;
					$kolom[$row_qz['replid']] = $row_qz['replid'];			
					}
					?>
					<input type="hidden" name="jum_jns" value="<?=$z ?>">
			<td class="headerlong" align="center" height="30">Rata-Rata Siswa</td>
					<?php
					 $query_ju = "SELECT * FROM jbsakad.jenisujian ".
								 "WHERE jenisujian.replid = '$jenis_penilaian'";
					 $result_ju = QueryDb($query_ju);
					 $row_ju = @mysqli_fetch_array($result_ju);
					?>
			<td class="headerlong" align="center" height="30">Nilai Akhir <?=$row_ju['jenisujian'] ?>
			<a href="hapus_na.php?id=<?=$row_ju['replid']; ?>&departemen=<?=$departemen ?>&tahun=<?=$tahun ?>&tingkat=<?=$tingkat ?>&pelajaran=<?=$pelajaran ?>&kelas=<?=$kelas ?>&semester=<?=$semester ?>&jenis_penilaian=<?=$jenis_penilaian ?>"
       	    onClick="return hapus();"><img src="../images/ico/hapus.png" border="0"></a>
			</td>
		</tr>			
			<?php
			$totCol[] = 0;
		if($my_data == 0){
			?>
			<tr>
				<td colspan="7" align="center">Data Tidak ada</td>
			</tr>
			<?php 				
        }elseif($my_data != "") {
            $i = 0;
            foreach($my_data as $ns => $d) {
                $i++;
                echo "
                    <tr>
						<td align='center' height='25'>$i</td>
						<td height='25'>$ns</td>
						<td height='25'>".$d['nama']."</td>";
						$t = 0;
						$idx = 0;
                	if($kolom != "") {
                		$nkolpinted = 0;
                		$ujcntstart = 0;
                  	foreach($d['n'] as $nuj => $v) {
                    		$ujcnt = $ujcntstart;
								$ujfound = false;
                    		while ($ujcnt < $nujian && !$ujfound) {                  			
                    			//echo "$v['idujian'] vs $kol_idujian[$ujcnt]";
                    			if ($v['idujian'] == $kol_idujian[$ujcnt]) { 
                    				$ujfound = true;
                    				$ujcntstart = $ujcnt + 1;
									
                    			} else {
                    				
                    				$nkolpinted++;
                    				?>
									<td align='center' height='25'><a href="#null" onClick="newWindow('tambah_nilai_ujian.php?id=<?=$v['id'] ?>&idujian=<?=$kol_idujian[$ujcnt] ?>&jenis_penilaian=<?=$jenis_penilaian ?>&pelajaran=<?=$pelajaran ?>&kelas=<?=$kelas ?>&semester=<?=$semester ?>&departemen=<?=$departemen ?>&tingkat=<?=$tingkat ?>&tahun=<?=$tahun ?>',
					            	'Data Nilai Ujian','500','250','resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="../images/ico/tambah.png" width="16" height="16" border="0"  onMouseOver="showhint('Tambah Nilai Siswa!', this, event, '120px')"/></a>
									<?php if ($v['lenket'] > 0){
										$keter=1;
										echo " <font color='Blue'><b>)*</b></font>";
									} else {
										$keter=1;
									}
									?>
									</td>
									<?php
									$ujcnt++;
                    			} 
                    		}
                    		/*$sp = "";
                    		if ($v['status'] > 0) {
                    			if ($v['status'] == 1) 
                    				$sp = "(TH)";
                    			elseif ($v['status'] == 2) 
                    				$sp = "(TM)";
                    			elseif ($v['status'] == 3) 
                    				$sp = "(C)";
                    			elseif ($v['status'] == 4) 
                    				$sp = "(L)";
                    		} */
								$t += $v['nilai'];
								$totCol[$idx] += $v['nilai'];
								$idx++;
								$nkolpinted++;								
                    		?>          
	                        <td align='center' height='25'>
									<a href="#null" onClick="newWindow('ubah_nilai_ujian.php?id=<?=$v['id'] ?>&jenis_penilaian=<?=$jenis_penilaian ?>&pelajaran=<?=$pelajaran ?>&kelas=<?=$kelas ?>&semester=<?=$semester ?>&departemen=<?=$departemen ?>&tingkat=<?=$tingkat ?>&tahun=<?=$tahun ?>',
					            	'Data Nilai Ujian','487','275','resizable=1,scrollbars=1,status=0,toolbar=0')">
										<?= $v['nilai'] ?></a>
										<?php if ($v['lenket'] > 0)
										echo " <font color='Blue'><b>)*</b></font>";
										?>
										<input type="hidden" name="nilai<?=$i ?>" value="<?=$v['nilai'] ?>">
									</td>
								<?php
                  	}
                  	//echo "*** $nkolpinted < $nujian<br>";
                  	while ($nkolpinted < $nujian) {
                  		
                  		?>
						<td align='center' height='25'><a href="#null" onClick="newWindow('tambah_nilai_ujian.php?id=<?=$v['id'] ?>&idujian=<?=$kol_idujian[$nkolpinted] ?>&jenis_penilaian=<?=$jenis_penilaian ?>&pelajaran=<?=$pelajaran ?>&kelas=<?=$kelas ?>&semester=<?=$semester ?>&departemen=<?=$departemen ?>&tingkat=<?=$tingkat ?>&tahun=<?=$tahun ?>',
					            	'Data Nilai Ujian','500','250','resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="../images/ico/tambah.png" width="16" height="16" border="0"  onMouseOver="showhint('Tambah Nilai Ujian!', this, event, '120px')"/></a></td>
				<?php
					$nkolpinted++;
                  	}                  	
            }
				$rata = $t/$idx;
				$rata = round($rata, 2);
                echo "
                    <td align='center' height='25'>$rata</td>
                    <td align='center' height='25'>";
					$query_nau = "SELECT replid,nau.nilaiAU ".
								 "FROM jbsakad.nau ".
								 "WHERE nau.idjenis = '$jenis_penilaian' ".
								 "AND nau.idpelajaran = '$pelajaran' ".
								 "AND nau.idkelas = '$kelas' ".
								 "AND nau.idsemester = '$semester' ".
								 "AND nau.nis = '".$ns."'";
					$result_nau = QueryDb($query_nau) or die (mysqli_error($mysqlconnection));
					$row_nau = mysqli_fetch_array($result_nau);
					?>
					<a href="#null" onClick="newWindow('ubah_nilai_au.php?id=<?=$row_nau['replid'] ?>&jenis_penilaian=<?=$jenis_penilaian ?>&pelajaran=<?=$pelajaran ?>&kelas=<?=$kelas ?>&semester=<?=$semester ?>&departemen=<?=$departemen ?>&tingkat=<?=$tingkat ?>&tahun=<?=$tahun ?>',
					            	'Data Nilai Ujian Akhir','500','250','resizable=1,scrollbars=1,status=0,toolbar=0')"><?=$row_nau['nilaiau'] ?></a>
									
				    </td>
				</tr>
				<?php
            }
			?>
			<tr>
				<td colspan="3" align="center" height='25' class='header'><b>Rata-rata Kelas</b></td>
				<?php
				foreach($totCol as $key => $val){
				?>
					<td align="center" height='25' bgcolor='#FFFFFF'  onMouseOver="showhint('Rata-rata Kelas!', this, event, '120px')"><b><?=round(($val * 1.0)/$i, 2); ?></b></td>
				<?php
				}
				?>
                <td height='25' bgcolor='#FFFFFF'></td><td height='25' bgcolor='#FFFFFF'></td>
            </tr>
		<?php
        }
		?>		
	</table>
	<script language='JavaScript'>
            Tables('table', 1, 0);
    </script>
	<br>

	<div align="center"> <font color="Blue"><b>)* : Ada keterangan nilai untuk siswa ybs.</b></font></div>
	<input type="hidden" name="jum_data" value="<?=$i ?>">
	
	<!--
	<?php 
	if(!mysqli_num_rows($result_qz) == 0){
		?>
	<p align="left">
	<input type="button" value="Tambah Siswa" class="but" onClick="newWindow('tambah_siswa_pp.php?departemen=<?=$departemen; ?>&tingkat=<?=$tingkat ?>&pelajaran=<?=$pelajaran ?>&semester=<?=$semester ?>&kelas=<?=$kelas ?>&tahun=<?=$tahun ?>&jenis_penilaian=<?=$jenis_penilaian ?>',
            'Penilaian Pelajaran','550','400','resizable=1,scrollbars=0,status=0,toolbar=0')">
	</p>
	<?php }
			?>
	
    -->
    <br>
	<?php
	if($my_data != 0){
	?>	
	<fieldset><legend><b>Hitung Nilai Akhir <?=$row_ju['jenisujian'] ?> Berdasarkan </b></legend>
	<input type="hidden" name="pilih" value="1">
	<input type="hidden" name="rtn" value="1">
	<table width="100%">
		<tr>
			<td colspan="3">
				<font size="2" color="Navy"><b>A. Perhitungan Otomatis</b></font>
			</td>
		</tr>
		<tr>
			<td width="10">&nbsp;</td>
			<td width="10" valign="top">
			<?php
			/*
			<input type="radio" name="rtn" value="1" onClick="sel(1);">
			*/
			?>
			</td>
			<td>Rata-rata Nilai
			<?php if($perubahan==1)
			 echo "<font color='#d0a41e'><b>)* : Ada perubahan nilai, silakan hitung ulang nilai akhir</b></font>";
			?>
			<br><br>
				<table id="table" class="tab" width="50%" border="1">
				<tr>
					<td width="85%" class="header" height="30"><?=$row_ju['jenisujian'] ?></td>
					<td width="15%" class="header" align="center" height="30">Bobot</td>
				</tr>
				<?php
				$query_qz = "SELECT ujian.replid, ujian.tanggal, jenisujian.jenisujian ".
							"FROM jbsakad.ujian, jbsakad.jenisujian ".
							"WHERE ujian.idjenis = '$jenis_penilaian' ".
							"AND ujian.idpelajaran = '$pelajaran' ".
							"AND ujian.idkelas = '$kelas' ".
							"AND ujian.idsemester = '$semester' ".
							"AND ujian.idjenis = jenisujian.replid ORDER BY ujian.tanggal";						
				$result_qz = QueryDb($query_qz);
				
				$num_qz = @mysqli_num_rows($result_qz);
				?>
				<input type="hidden" name="num_qz" value="<?=$num_qz ?>">
				<input type="hidden" name="check">			
				<?php 			
				$i=0;
				while($row_qz = @mysqli_fetch_array($result_qz)){
				$i++;
				?>
				
				<tr>
					<td height="25">
					<input type="checkbox" name="rplidju<?=$i ?>" value="<?=$row_qz['replid'] ?>" onClick="clist(<?=$i ?>);">
					<?php 
					
					$tgl = format_tgl($row_qz['tanggal']);
					echo  "{$row_qz['jenisujian']}-$i ($tgl) "; 
					
					$query_nuj = "SELECT nilaiujian FROM jbsakad.nilaiujian WHERE idujian = '".$row_qz['replid']."'";
					$result_nuj = QueryDb($query_nuj);
					
					//echo $query_nuj;
					$row_nuj = @mysqli_fetch_array($result_nuj);
					
					//echo "tes$row_nuj['NilaiUjian']";
					?>
					<input type="hidden" name="nilai_ujian<?=$i ?>" value="<?=$row_nuj['nilaiujian'] ?>">
					</td>
					<td align="center" height="25"><input type="text" name="bobot<?=$i ?>" size="1" maxlength="1"></td>
				</tr>
<?php
				}
				?>
			</table>
			<script language='JavaScript'>
            	Tables('table', 1, 0);
      		</script>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">
			<input type="submit" name="hitung" value="Hitung dan Simpan Nilai Akhir <?=$row_ju['jenisujian'] ?>" class="but"></td>
		</tr>
		<tr>
			<td colspan="3">
				<font size="2" color="Navy"><b>B. Input Manual</b></font>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2"><a href="hitung_nilai_akhir.php?departemen=<?=$departemen ?>&tingkat=<?=$tingkat ?>&tahun=<?=$tahun ?>&jenis_penilaian=<?=$jenis_penilaian ?>&pelajaran=<?=$pelajaran ?>&kelas=<?=$kelas ?>&semester=<?=$semester ?>"><b>Input Nilai Akhir <?=$row_ju['jenisujian'] ?></b></a></td>
		</tr>		
	</table>
	</fieldset>
	</legend> 
	</form>
	</td>
  </tr>
</table>

<?php
}
if(isset($_POST['hitung'])){
	$perubahan=0;
	//echo "Hitung...";
	if($_POST['rtn'] == "1"){		//jika checklist pertama yg dipilih (rata-rata nilai)

		$i = 1;
		$r = 0;
		while($i <= $_POST['num_qz']){
			$ruj = "rplidju$i";
			$b = "bobot$i";
			$nuj = "nilai$i";

			if(isset($_POST[$ruj])){
				$r = $r + 1; 		// r ini variabel buat ngitung jumlah data yg ter-checklist			
				$info .= "$_POST[$ruj]:$_POST[$b];";	//pengkodean buat infobobot ujian
				
				//query buat cari nilai ujian masing-masing siswa berdasarkan idujiannya		
				$query_iduj = "SELECT * FROM jbsakad.nilaiujian WHERE nilaiujian.idujian   = '".$_POST[$ruj]."'";
			
				$result_iduj = QueryDb($query_iduj);
				
				$ttl_bbt += $_POST[$b];
				
				while($row_iduj = mysqli_fetch_array($result_iduj)){
					
					$nakhr = $_POST[$b]*$row_iduj['nilaiujian'];	//perkalian antara bobot dengan nilaiujian
					$data_uj[$row_iduj['nis']] += $nakhr; 	//array siswa nis pemjumlahan hasil perkalian
					
				}							
			}			
		$i++;		
		}		
		
        //echo "test ".count($data_uj);
		foreach($data_uj as $ns => $v) {
			//query buat cek data udah ada belon untuk data jenis penilaian. Klo belum insert, klo udah di update
			 $query_cek = "SELECT nau.replid, nau.idjenis FROM jbsakad.nau WHERE nau.idjenis = '$jenis_penilaian' AND idsemester = '$semester' AND idkelas = '$kelas' AND nis = '$ns' AND idpelajaran = '".$pelajaran."'";
			$result_cek = QueryDb($query_cek);
			$num_cek = mysqli_num_rows($result_cek);
			$row_cek = mysqli_fetch_array($result_cek); 
		
			$rata=$v/$ttl_bbt;
			$query_id = "SELECT nau.replid FROM jbsakad.nau WHERE nau.idjenis = '$jenis_penilaian' AND idsemester = '$semester' AND idkelas = '$kelas' AND nis  = '".$_POST[$ns]."' AND idpelajaran = '".$pelajaran."'";
			$result_id = QueryDb($query_id);
			$row_id = @mysqli_fetch_array($result_id);
			if($num_cek == 0){
					$query_nau = "INSERT INTO jbsakad.nau (idpelajaran, nis, idkelas, idsemester, idjenis, nilaiAU) ".
								 "VALUES ('$pelajaran','$ns','$kelas','$semester','$jenis_penilaian','$rata')";
					$result_nau = QueryDb($query_nau) or die(mysqli_error($mysqlconnection));		
			}elseif($num_cek > 0){
					$query_nau = "UPDATE jbsakad.nau SET ".
								 "nilaiAU = '$rata' WHERE nau.nis = '$ns' AND nau.idjenis = '$jenis_penilaian' ".
								 "AND idpelajaran = '$pelajaran' AND idkelas = '$kelas' ".
								 "AND idsemester = '".$semester."'";
					$result_nau = QueryDb($query_nau) or die(mysqli_error($mysqlconnection));		
			}
			//echo $query_nau;
		}
		
		$inf = substr((string) $info, 0, -1);		// buat ngilangin tanda ; diakhir string
		//echo "num_cek".$num_cek;
		if($num_cek == 0){
				echo $query_inf = "INSERT INTO jbsakad.infobobotujian ".
								 "(idpelajaran, idkelas, idsemester, idjenisujian, pilihan, info, keterangan) ".
								 "VALUES ('".$pelajaran."', '$kelas','$semester','$jenis_penilaian', '".$_POST['rtn']."', '$inf','$keterangan')";
				$result_inf = QueryDb($query_inf) or die (mysqli_error($mysqlconnection));
		}elseif($num_cek > 0){
				$query_inf = "UPDATE jbsakad.infobobotujian SET info = '$inf' WHERE idjenisujian = '$jenis_penilaian'";
				$result_inf = QueryDb($query_inf) or die (mysqli_error($mysqlconnection));
		}
				
		if($result_inf && $result_nau){
          	?>
        	<script language="javascript">
				//alert ("brasil");
        		change_sel();
        	</script>
        	<?php
	   }else{
        	?>
        	<script language="javascript">
        		alert("Data gagal");
        		change_sel();
        	</script>
        	<?php
	   }
	   
}

elseif($_POST['rtn'] == "2"){			//jika checklist kedua yg dipilih

	if($_POST['jum_jns'] < $_POST['t_max']){
		?>
		<script language="javascript">
			alert("Perhitungan tidak dapat dilakukan. Jenis penilaian hanya ada " + <?=$_POST['jum_jns'] ?> + "!"); 
		</script>
		<?php
		}elseif(($_POST['jum_data'] == $_POST['t_max']) || ($_POST['jum_data'] > $_POST['t_max'] )){

		$query_cek = "SELECT nau.replid, nau.idjenis FROM jbsakad.nau WHERE nau.idjenis = '$jenis_penilaian' ";
		$result_cek = QueryDb($query_cek);
		$num_cek = mysqli_num_rows($result_cek);
		$row_cek = mysqli_fetch_array($result_cek); 

		//query buat cari ulang
		$query_qz1 = "SELECT ujian.replid ".
					 "FROM jbsakad.ujian, jbsakad.jenisujian ".
					 "WHERE ujian.idjenis = '$jenis_penilaian' ".
					 "AND ujian.idpelajaran = '$pelajaran' ".
					 "AND ujian.idkelas = '$kelas' ".
					 "AND ujian.idsemester = '$semester' ".
					 "AND ujian.idjenis = jenisujian.replid ";						
		$result_qz1 = QueryDb($query_qz1);
		while($row_qz1 = @mysqli_fetch_array($result_qz1)){

		//query buat nyari siswa berdasarkan idujian		
			$query_ns = "SELECT nilaiujian.nis, nilaiujian.idujian, nilaiujian.nilaiujian ".
						"FROM jbsakad.nilaiujian ".
						"WHERE nilaiujian.idujian = '".$row_qz1['replid']."' ".
						"ORDER BY nilaiujian.nis, nilaiujian.nilaiujian";
			$result_ns = QueryDb($query_ns);
							
			while($row_ns = @mysqli_fetch_array($result_ns)){				
				echo $data_iduj[$row_ns['nis']]= $row_ns['nilaiujian']; 
			}
								
				foreach($data_iduj as $ns => $v) {
					$sum = 0;
					for($j = 0; $j < $_POST['t_max']; $j++) {
						 $sum += $v[$j];
					}
									
					$avg[$ns] = $sum / $_POST['t_max'];
				}
				
				foreach($avg as $nis => $rata) {
					//echo "[$nis : $rata]";
					if($num_cek == 0){
							$query_nau = "INSERT INTO jbsakad.nau (idpelajaran, nis, idkelas, idsemester, idjenis, nilaiAU) ".
										 "VALUES ('$pelajaran','$nis','$kelas','$semester','$jenis_penilaian','$rata')";
							$result_nau = QueryDb($query_nau) or die(mysqli_error($mysqlconnection));		
					}elseif($num_cek > 0){
							$query_nau = "UPDATE jbsakad.nau SET ".
										 "nilaiAU = '$rata' WHERE nau.nis = '$nis' AND nau.idjenis = '$jenis_penilaian'";
							$result_nau = QueryDb($query_nau) or die(mysqli_error($mysqlconnection));		
					}
				}
				//echo "$_POST['t_max']+$_POST['jum_jns']";
				
				if($result_nau){
				?>
				<script language="javascript">
					change_sel();
				</script>
				<?php
				}else{
				?>
				<script language="javascript">
					alert("Data gagal");
					change_sel();
				</script>
				<?php
				}
			}		
		}
  	}  
}
?>
</body>
</html>