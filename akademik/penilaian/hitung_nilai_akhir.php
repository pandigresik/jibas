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
OpenDb();
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
if(isset($_GET['jenis_penilaian'])){
	$jenis_penilaian = $_GET['jenis_penilaian'];
}elseif(isset($_POST['jenis_penilaian'])){
	$jenis_penilaian = $_POST['jenis_penilaian'];
}
if(isset($_POST['simpan'])){
	$j=1;
	while($_POST['num_data'] >= $j){
		$nau = "nilai_akhir$j";
		if ($_POST[$nau]=="")
		$_POST[$nau]=0;
		$n = "nis$j";
		
		//query buat cek data udah ada belon untuk data jenis penilaian. Klo belum insert, klo udah di update
		$query_cek = "SELECT nau.replid FROM jbsakad.nau WHERE nau.idjenis = '$jenis_penilaian' AND idsemester = '$semester' AND idkelas = '$kelas' AND nis = '".$_POST[$n]."' AND idpelajaran = '".$pelajaran."'";
		//echo $query_cek;
		//exit();
		$result_cek = QueryDb($query_cek);
		$num_cek = @mysqli_num_rows($result_cek);
		$row_cek = @mysqli_fetch_array($result_cek); 
		
		if($num_cek == 0){
				$query_nau = "INSERT INTO jbsakad.nau (idpelajaran, nis, idkelas, idsemester, idjenis, nilaiAU, keterangan) ".
							 "VALUES ('$pelajaran','$_POST[$n]','$kelas','$semester','$jenis_penilaian','$_POST[$nau]','$ket')";
				$result_nau = QueryDb($query_nau) or die (mysqli_error($mysqlconnection));
		}elseif($num_cek  > 0){
				$query_nau = "UPDATE jbsakad.nau SET nau.nilaiAU   = '".$_POST[$nau]."', idsemester = '$semester' WHERE nau.nis  = '".$_POST[$n]."' AND idjenis = '$jenis_penilaian'";
				$result_nau = QueryDb($query_nau) or die (mysqli_error($mysqlconnection));
		}
		//echo "tes$query_nau<br>";
		$j++;
	}
	  if(mysqli_affected_rows($conn) >= 0) {
	  	?>
		<script language="javascript">
			document.location.href = "tampil_nilai_pelajaran.php?jenis_penilaian=<?=$jenis_penilaian ?>&departemen=<?=$departemen ?>&pelajaran=<?=$pelajaran ?>&tingkat=<?=$tingkat ?>&kelas=<?=$kelas ?>&semester=<?=$semester ?>&tahun=<?=$tahun ?>";
		</script>
	<?php
	}else{
		?>
		<script language="javascript">
			alert("Gagal memasukkan data");
			document.location.href = "tampil_nilai_pelajaran.php?jenis_penilaian=<?=$jenis_penilaian ?>&departemen=<?=$departemen ?>&pelajaran=<?=$pelajaran ?>&tingkat=<?=$tingkat ?>&kelas=<?=$kelas ?>&semester=<?=$semester ?>&tahun=<?=$tahun ?>";
		</script>
	<?php

	}	
}
?>

<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>

<script language = "javascript" type = "text/javascript">

var win = null;
function newWindow(mypage,myname,w,h,features) {
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
}

function validate(){
var jumlah = document.tampil_nilai_pelajaran.num_data.value;
//alert ('Masuk validate'+jumlah);
//return true;
while (i<=jumlah){
			alert ('SS');
			/*
			var nilai = document.getElementById("nilaiakhir"+i).value;
			if (nilai.length==0){
					alert ('Nilai harus diisi !');
					document.getElementById("nilaiakhir"+i).focus();
					return false;
					} else {
				if (isNaN(nilai)) {
					alert ('Nilai harus berupa bilangan !');
					document.getElementById("nilaiakhir"+i).focus();
					return false;
				}
				
			}
			*/
			i++;
	}
//return true;
return false;
var pilih;
	pilih = document.tampil_nilai_pelajaran.pilih.value;
	cek = document.tampil_nilai_pelajaran.check.value;
	t_max = document.tampil_nilai_pelajaran.t_max.value;
	
	alert ('Jumlah baris'+jumlah);
	/*
		*/
	if(pilih.length == 0){
		alert("Anda harus menentukan jenis untuk menghitung nilai akhir");
		return false;
	}
	if(pilih == 1){
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
	if(pilih == 2){
		if(t_max.length == 0){
			alert("Anda harus menentukan jumlah terbesar untuk menentukan rata-rata nilai");
		return false;
		}
	}
}
</script>
</head>
<body bgcolor="#FFFFFF">
<table border="0" width="100%" height="100%">
    <tr>
	    <td align="center" valign="top">
<form name="tampil_nilai_pelajaran" action="hitung_nilai_akhir.php" method="post" onSubmit="return validate();">

<input type="hidden" name="departemen" value="<?=$departemen ?>">
<input type="hidden" name="pelajaran" value="<?=$pelajaran ?>">
<input type="hidden" name="kelas" value="<?=$kelas ?>">
<input type="hidden" name="tingkat" value="<?=$tingkat ?>">
<input type="hidden" name="tahun" value="<?=$tahun ?>">
<input type="hidden" name="semester" value="<?=$semester ?>">
<input type="hidden" name="jenis_penilaian" value="<?=$jenis_penilaian ?>">

    <fieldset><legend><b>Input Nilai Akhir Manual <?=$row_jp['jenisujian'] ?></b>
		
	<?php
	$query_uj = "SELECT nilaiujian.replid, nilaiujian.idujian, nilaiujian.nis, siswa.nama, nilaiujian.nilaiujian ".
				"FROM jbsakad.ujian, jbsakad.nilaiujian, jbsakad.siswa ".
				"WHERE ujian.idjenis = '$jenis_penilaian' ".
				"AND ujian.idpelajaran = '$pelajaran' ".
				"AND ujian.idkelas = '$kelas' ".
				"AND ujian.idsemester = '$semester' ".
				"AND ujian.replid = nilaiujian.idujian ".
				"AND siswa.nis = nilaiujian.nis ORDER BY siswa.nama, ujian.tanggal, nilaiujian.idujian";
	$result_uj = QueryDb($query_uj) or die (mysqli_error($mysqlconnection));
	
	//echo $query_uj;

	while($row_uj = @mysqli_fetch_array($result_uj)){
		$my_data[$row_uj['nis']]['n'][$row_uj['idujian']]['nilai'] = $row_uj['nilaiujian'];
		$my_data[$row_uj['nis']]['n'][$row_uj['idujian']]['id'] = $row_uj['replid'];
		$my_data[$row_uj['nis']]['n'][$row_uj['idujian']]['idujian'] = $row_uj['idujian'];
		//$my_data[$row_uj['nis']]['replid'] = $row_uj['replid'];
		$my_data[$row_uj['nis']]['nama'] = $row_uj['nama'];
	}
	?>
	<table width="100%" id="table" class="tab" border="1">
		<tr>
			<td class="headerlong" height="30">No</td>
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
				//echo "<br>$query_qz";				
					
			$i=0;
			$nujian = 0;
			while($row_qz = @mysqli_fetch_array($result_qz)){
			$i++;
			?>
				<td class="headerlong" align="center"  height="30">				
				<?php 
				$tgl = format_tgl($row_qz['tanggal']);
				echo  $row_qz['jenisujian'] - $i;
				$kol_idujian[$nujian] = $row_qz['replid'];
				$nujian++; 
				?>
				
				<?="<br>($tgl)"; ?>
				</td>								
			<?php
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
        if($my_data != "") {
            $i = 0;
            foreach($my_data as $ns => $d) {
                $i++;
                echo "
                    <tr>
						<td align='center'>$i</td>
						<td>
						<input type='hidden' name='nis$i' value='$ns'>
						$ns
						</td>
						<td>".$d['nama']."</td>
                  ";
					$t = 0;
					$idx = 0;
               if($kolom != "") {
                	  $nkolprinted = 0;	
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
                    				$ujcnt++;
                    				$nkolprinted++;
                    				echo "<td align='center'>&nbsp;</td>";
                    			} 
                    		} 
                    		  
						  		$t += $v['nilai'];
						  		$totCol[$idx] += $v['nilai'];
						  		$idx++;
						  		$nkolprinted++;
                    		?>          
                        <td align='center' height="25">
									<?=$v['nilai']?>
								</td>
					 <?php
                    }
                    //echo " *** $nkolprinted vs $nujian <br>";
                    while ($nkolprinted < $nujian) {
                  		$nkolprinted++;
                  		echo "<td align='center'>&nbsp;</td>";
                    }
               }
				$rata = sprintf("%01.2f",$t/$idx);
                echo "
                    <td align='center'>$rata</td>";
					$query_nau = "SELECT replid,nau.nilaiAU ".
								 "FROM jbsakad.nau ".
								 "WHERE nau.idjenis = '$jenis_penilaian' ".
								 "AND nau.idpelajaran = '$pelajaran' ".
								 "AND nau.idkelas = '$kelas' ".
								 "AND nau.idsemester = '$semester' ".
								 "AND nau.nis = '".$ns."'";
					$result_nau = QueryDb($query_nau) or die (mysqli_error($mysqlconnection));
					$row_nau = mysqli_fetch_array($result_nau);
                   echo " <td align='center'><input type='text' id='nilai_akhir$i' name='nilai_akhir$i' maxlength='8' value='".$row_nau['nilaiAU']."'></td>
				</tr>";
            }
			?>
			<tr>
					<td colspan="3" align="center" class="header" height="25"><b>Rata-rata Kelas</b></td>
				<?php
				foreach($totCol as $key => $val){
				?>
					<td align="center" bgcolor="#FFFFFF"  onMouseOver="showhint('Rata-rata Kelas!', this, event, '120px')"><b><?=($val * 1.0)/$i; ?></b></td>
				<?php
				}
				?>
		</tr>
		<?php
        }
		
		?>		
	</table>

	<script language='JavaScript'>
            Tables('table', 1, 0);
    </script>
	<br>	
	<table width="100%">
		<tr>
			<td>
			  <div align="center">
			    <input type="hidden" name="num_data" id="num_data" value="<?=$i ?>">
			    <input type="button" name="batal" value="Batal" class="but" onClick="window.history.back();">&nbsp;
		        <input type="submit" name="simpan" value="Simpan" class="but">
	      </div></td>
		</tr>
	</table>
	</form>

</body>
</html>
<?php
CloseDb();
?>