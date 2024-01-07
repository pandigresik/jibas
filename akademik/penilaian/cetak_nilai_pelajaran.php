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

if(isset($_POST["tglawal"])){
	$tglawal = $_POST["tglawal"];
}elseif(isset($_GET["tglawal"])){
	$tglawal = $_GET["tglawal"];
}

if(isset($_POST["tglakhir"])){
	$tglakhir = $_POST["tglakhir"];
}elseif(isset($_GET["tglakhir"])){
	$tglakhir = $_GET["tglakhir"];
}

if(isset($_POST["pelajaran"])){
	$pelajaran = $_POST["pelajaran"];
}elseif(isset($_GET["pelajaran"])){
	$pelajaran = $_GET["pelajaran"];
}
$jenis_penilaian = $_GET['jenis_penilaian'];
?>

<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript">
function print_this() {
    window.print();
}
</script>
</head>
<?php
openDb();
?>
<body onLoad="print_this()">
<?php include('../library/headercetak.php');  ?>
<table width="100%" border="0">
	<tr>
		<td align="center" colspan="6"><font size="4"><b>PENILAIAN PELAJARAN</b></td>
	</tr>
	<tr>
		<td width="127">Departemen</td>
		<td width="5">:</td>
		<td width="556"><?=$departemen ?></td>
	</tr>
	<tr>
		<td>Tingkat</td>
		<td>:</td>		
		<td>
		<?php
		$query_tkt = "SELECT * FROM jbsakad.tingkat WHERE replid = '".$tingkat."'";
		$result_tkt = QueryDb($query_tkt);
		$row_tkt = mysqli_fetch_array($result_tkt);
		 echo $row_tkt['tingkat'] ?></td>
	</tr>
	<tr>
		<td>Kelas</td>
		<td>:</td>		
		<td>
		<?php 
		$query_kls = "SELECT * FROM jbsakad.kelas WHERE replid = '".$kelas."'";
		$result_kls = QueryDb($query_kls);
		$row_kls = mysqli_fetch_array($result_kls);
		echo $row_kls['kelas'] ?></td>
	</tr>
	<tr>
		<td>Pelajaran</td>
		<td>:</td>		
		<td>
		<?php if($pelajaran == "all"){
				$pel = "Semua Pelajaran";
			}elseif($pelajaran != "all"){
				$query_pel = "SELECT nama FROM jbsakad.pelajaran WHERE replid = '".$pelajaran."'";
				$result_pel = QueryDb($query_pel);
				$row_pel = mysqli_fetch_array($result_pel);
				$pel = $row_pel['nama'];
			}
		echo $pel ?></td>
	</tr>
	
	<tr>
		<td align="center" colspan="4">&nbsp;</td>
	</tr>
</table>
<br>
<input type="hidden" name="departemen" value="<?=$departemen ?>">
<input type="hidden" name="pelajaran" value="<?=$pelajaran ?>">
<input type="hidden" name="kelas" value="<?=$kelas ?>">
<input type="hidden" name="tingkat" value="<?=$tingkat ?>">
<input type="hidden" name="tahun" value="<?=$tahun ?>">
<input type="hidden" name="semester" value="<?=$semester ?>">
<input type="hidden" name="jenis" value="<?=$jenis_penilaian ?>">
    <fieldset><legend><b>Jenis Penilaian</b>
	
	<?php 
	$query_jp = "SELECT * FROM jbsakad.jenisujian ".
				"WHERE jenisujian.replid = '$jenis_penilaian'";
	$result_jp = QueryDb($query_jp);
	
	$row_jp = @mysqli_fetch_array($result_jp);
	echo "<b>".$row_jp['jenisujian']."</b>"; ?>
	
	
	<?php
	$query_uj = "SELECT nilaiujian.replid, nilaiujian.idujian, nilaiujian.nis, siswa.nama, length(nilaiujian.keterangan) as lenket, nilaiujian.nilaiujian ".
				"FROM jbsakad.ujian, jbsakad.nilaiujian, jbsakad.siswa ".
				"WHERE ujian.idjenis = '$jenis_penilaian' ".
				"AND ujian.idpelajaran = '$pelajaran' ".
				"AND ujian.idkelas = '$kelas' ".
				"AND ujian.idsemester = '$semester' ".
				"AND ujian.replid = nilaiujian.idujian ".
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
		<tr>
			<td class="headerlong" align="center" height="30">No</td>
			<td class="headerlong" height="30">NIS</td>
			<td class="headerlong" height="30">Nama</td>
			<?php
				$query_qz = "SELECT ujian.replid, ujian.tanggal, jenisujian.jenisujian ".
							"FROM jbsakad.ujian, jbsakad.jenisujian ".
							"WHERE ujian.idjenis = '$jenis_penilaian' ".
							"AND ujian.idpelajaran = '$pelajaran' ".
							"AND ujian.idkelas = '$kelas' ".
							"AND ujian.idsemester = '$semester' ".
							"AND ujian.idjenis = jenisujian.replid ORDER BY ujian.tanggal, ujian.replid";						
				$result_qz = QueryDb($query_qz);
				
			$i=0;
			$nujian = 0;
			while($row_qz = @mysqli_fetch_array($result_qz)){
			$i++;
			?>
				<td class="headerlong" align="center" height="30">				
				<?php 
				$tgl = format_tgl($row_qz['tanggal']);
				echo  $row_qz['jenisujian']-$i ?>
				<?="<br>($tgl)"; ?>
				</td>								
			<?php
			$kol_idujian[$nujian] = $row_qz['replid'];
			$nujian++;
			$kolom[$row_qz['replid']] = $row_qz['replid'];			
			}
			?>
			<td class="headerlong" align="center" height="30">Rata-Rata Siswa</td>
			<?php
			 $query_ju = "SELECT * FROM jbsakad.jenisujian ".
						 "WHERE jenisujian.replid = '$jenis_penilaian'";
			 $result_ju = QueryDb($query_ju);
			 $row_ju = @mysqli_fetch_array($result_ju);
			?>
			<td class="headerlong" align="center" height="30">Nilai Akhir <?=$row_ju['jenisujian'] ?></td>
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
									<td align='center' height='25'>(+)</td>
									<?php
									$ujcnt++;
                    			} 
                    		}
                    		
						$t += $v['nilai'];
						$totCol[$idx] += $v['nilai'];
						$idx++;
						$nkolpinted++;		
                    ?>          
                        <td align='center' height='25'>
							<b>
							<?=$v['nilai'] ?></b>
							<input type="hidden" name="nilai<?=$i ?>" value="<?=$v['nilai'] ?>">
						</td>
					<?php
                    }
              		  while ($nkolpinted < $nujian) {
                  		
                  		?>
						<td align='center' height='25'>(+)</td>
				<?php
					$nkolpinted++;
						}                  	
				}
				$rata = sprintf("%01.2f",$t/$idx);
                echo "
                    <td align='center' height='25'>$rata</td>
                    <td align='center' height='25'>";
					$query_nau = "SELECT nau.nilaiAU ".
								 "FROM jbsakad.nau ".
								 "WHERE nau.idjenis = '$jenis_penilaian' ".
								 "AND nau.idpelajaran = '$pelajaran' ".
								 "AND nau.idkelas = '$kelas' ".
								 "AND nau.idsemester = '$semester' ".
								 "AND nau.nis = '".$ns."'";
					$result_nau = QueryDb($query_nau) or die (mysqli_error($mysqlconnection));
					$row_nau = mysqli_fetch_array($result_nau);
					echo $row_nau['nilaiAU'];
					?>					
				</td>
				</tr>
				<?php
            }
			?>
			<tr>
				<td colspan="3" align="center" class="header" height="30"><b>Rata-rata Kelas</b></td>
				<?php
				foreach($totCol as $key => $val){
				?>
					<td align="center" bgcolor="#FFFFFF"><b><?=round(($val * 1.0)/$i,2); ?></b></td>
				<?php
				}
				?><td></td>
		</tr>
		
		<?php
        }
		?>
		
	</table>
	<script language='JavaScript'>
            Tables('table', 1, 0);
    </script>
	<input type="hidden" name="jum_data" value="<?=$i ?>">
	<br>
	
</body>
</html>