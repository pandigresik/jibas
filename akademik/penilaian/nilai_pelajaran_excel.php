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
require_once('HitungRata.php');

/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Nilai_Pelajaran.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

OpenDb();
if(isset($_REQUEST["semester"]))
	$semester = $_REQUEST["semester"];
if(isset($_REQUEST["kelas"]))
	$kelas = $_REQUEST["kelas"];
if(isset($_REQUEST["idaturan"]))
	$idaturan = $_REQUEST["idaturan"];

$sql = "SELECT p.nama, p.replid AS pelajaran, a.dasarpenilaian, j.jenisujian, j.replid AS jenis, dp.keterangan
		  FROM jbsakad.aturannhb a, jbsakad.pelajaran p, jenisujian j, dasarpenilaian dp 
		 WHERE a.dasarpenilaian = dp.dasarpenilaian AND a.replid='$idaturan' AND p.replid = a.idpelajaran AND a.idjenisujian = j.replid";
$result = QueryDb($sql);

$row = @mysqli_fetch_array($result);
$namapel = $row['nama'];
$pelajaran = $row['pelajaran'];
$aspek = $row['dasarpenilaian'];
$aspekket = $row['keterangan'];
$namajenis = $row['jenisujian'];
$jenis = $row['replid'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aturan Perhitungan Nilai Rapor['Menu']</title>

<style type="text/css">
<!--
.style1 {
	color: #000099;
	font-weight: bold;
}
.style2 {color: #000099; font-weight: bold; font-size: 12px; }
-->
</style>
</head>
<body>
<form name="tampil_nilai_pelajaran" action="nilai_pelajaran_content.php" method="post" onSubmit="return validate();">
<input type="hidden" name="semester" value="<?=$semester?>" />
<input type="hidden" name="kelas" value="<?=$kelas?>" />
<input type="hidden" name="idaturan" value="<?=$idaturan?>" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <table width="100%" border="0" height="100%">
   	<tr>
    	<td>
        <table width="100%" border="0">
        <tr>
            <td width="17%"><strong>Pelajaran</strong></td>
            <td><strong>: <?=$namapel ?> </strong></td>
            <td rowspan="2"></td>
        </tr>
        <tr>
            <td><strong>Aspek Penilaian</strong></td>
            <td><strong>: <?=$aspekket?></strong></td>            
        </tr>
    	<tr>
            <td><strong>Jenis Pengujian</strong></td>
            <td><strong>: <?=$namajenis?></strong></td>  
<?php 	$sql_cek_ujian = "SELECT u.replid, u.tanggal, u.deskripsi, u.idrpp 
						FROM jbsakad.ujian u 
					   WHERE u.idaturan='$idaturan' AND u.idkelas='$kelas' AND u.idsemester='$semester' ORDER by u.tanggal ASC";
    $result_cek_ujian = QueryDb($sql_cek_ujian);		
	$jumlahujian = @mysqli_num_rows($result_cek_ujian); ?>            
  		</tr>
        </table>
        <br />
  		<table border="1" width="100%" id="table" class="tab">
       	<tr>
            <td height="30" bgcolor="#666666" width="5" align='center'><font color="white"><strong>No.</strong></font></td>
    		<td height="30" bgcolor="#666666" align='center'><font color="white"><strong>N I S</strong></font></td>
    		<td height="30" bgcolor="#666666" align='center'><font color="white"><strong>Nama</strong></font></td>
    <?php
       
        $i=1;
        while ($row_cek_ujian=@mysqli_fetch_array($result_cek_ujian)){
			$sql_get_rpp_name = "SELECT rpp FROM rpp WHERE replid='".$row_cek_ujian['idrpp']."'";
			if (!empty($row_cek_ujian['idrpp'])) {
				$res_get_rpp_name = QueryDb($sql_get_rpp_name);
				$rpp = @mysqli_fetch_array($res_get_rpp_name);
				$namarpp = $rpp['rpp'];
			} else {
				$namarpp = "Tanpa RPP";
			}
			$nilaiujian[$i] = 0;
			$idujian[$i] = $row_cek_ujian['replid'];			
            $tgl = explode("-",(string) $row_cek_ujian['tanggal']);
			
        ?>
    <td height="30" bgcolor="#666666" align="center" ><font color="white"><strong><?=$namajenis."-".$i?>&nbsp;<br />
     
	<?=$tgl[2]."/".$tgl[1]."/".substr((string) $tgl[0],2)?></strong></font></td>
    <?php
	$i++;
	}
	?>
    <td height="30" align="center" bgcolor="#666666" width="10px"><font color="white"><strong>Rata- rata Siswa</strong></font></td>
    <td height="30" align="center" bgcolor="#666666"><font color="white"><strong>Nilai Akhir <?=$namajenis?></strong>&nbsp;</font>
	</td>
  </tr>
  <?php
  $sql_siswa="SELECT * FROM jbsakad.siswa WHERE idkelas='$kelas' AND aktif=1 ORDER BY nama ASC";
  $result_siswa=QueryDb($sql_siswa);
  $cnt=1;
  $jumsiswa = mysqli_num_rows($result_siswa);
  while ($row_siswa=@mysqli_fetch_array($result_siswa)){
  		$nilai = 0;	
  ?>
  <tr>
    <td height="25" align="center" width="5"><?=$cnt?></td>
    <td height="25" align="center"><?=$row_siswa['nis']?></td>
    <td height="25" align="left"><?=$row_siswa['nama']?></td>
    <?php 	for ($j=1;$j<=count($idujian);$j++) { ?>
            <td align="center">							
			<?php $sql_cek_nilai_ujian="SELECT * FROM jbsakad.nilaiujian WHERE idujian='".$idujian[$j]."' AND nis='".$row_siswa['nis']."'";
                $result_cek_nilai_ujian=QueryDb($sql_cek_nilai_ujian);
               	
                    $row_cek_nilai_ujian=@mysqli_fetch_array($result_cek_nilai_ujian);
                	$nilaiujian[$j] = $nilaiujian[$j]+$row_cek_nilai_ujian['nilaiujian'];					
                	$nilai = $nilai+$row_cek_nilai_ujian['nilaiujian'];
                
                 
                    echo $row_cek_nilai_ujian['nilaiujian'];
					if ($row_cek_nilai_ujian['keterangan']<>"")
                        echo "<strong><font color='blue'>)*</font></strong>";               
				
			?>
            </td>
		<?php  } ?>
            <td align="center">
               <?php GetRataSiswa2($pelajaran, $idjenisujian, $kelas, $semester, $idaturan, $row_siswa['nis']); ?>
            </td>
    		<td align="center">
	<?php 				
			$sql_get_nau_per_nis="SELECT nilaiAU,replid,keterangan,info1 FROM jbsakad.nau WHERE nis='".$row_siswa['nis']."' AND idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan'";
		
			//echo $sql_get_nau_per_nis;			
			$result_get_nau_per_nis=QueryDb($sql_get_nau_per_nis);
			if (mysqli_num_rows($result_get_nau_per_nis) > 0) {
				$row_get_nau_per_nis=@mysqli_fetch_array($result_get_nau_per_nis);
				
				echo $row_get_nau_per_nis['nilaiAU'];
				if ($row_get_nau_per_nis['keterangan']<>"")
					echo "<font color='#067900'><strong>)*</strong></font>";
				if ($row_get_nau_per_nis['info1']<>"")
					echo "&nbsp;<font color='blue'><strong>)*</strong></font>";
			} ?>
            </td>
    	</tr>
  <?php
  		$cnt++;
  		} ?>
        
		<tr>
        	<td height='25'  bgcolor='#666666' align='center' colspan='3'><font color="white"><strong>Rata-rata Kelas</strong></font></td>
	<?php 	$rata = 0;
        for ($j=1;$j<=count($idujian);$j++) { 
        	$rata = $rata+($nilaiujian[$j]/$jumsiswa);
    ?>
			<td align="center" bgcolor="#FFFFFF"><?=GetRataKelas($_REQUEST['kelas'],$_REQUEST['semester'],$idujian[$j])?></td>
	<?php 	} ?>
            <td align="center" bgcolor="#FFFFFF"><?=round($rata/count($idujian),2)?></td>
            <td align="center" bgcolor="#FFFFFF">
   	<?php 				
		$sql_get_nau_per_nis="SELECT SUM(nilaiAU)/$jumsiswa FROM jbsakad.nau WHERE idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan'";
		
		//echo $sql_get_nau_per_nis;			
		$result_get_nau_per_nis=QueryDb($sql_get_nau_per_nis);
		if (mysqli_num_rows($result_get_nau_per_nis) > 0) {
			$row = mysqli_fetch_row($result_get_nau_per_nis);     
			echo round($row[0],2);
     	} ?>        
            </td>
        </tr>
		</table>
</td>
    </tr>
    <tr>
    	<td><strong><font color="blue">)*</font> ada perubahan nilai akhir individual &nbsp;&nbsp; 
		<font color="#067900">)*</font> Nilai Akhir Siswa dihitung manual </strong>
        </td>
   	</tr>
  	<tr>
  		<td>
        <br />
        
        <table id="table" class="tab" width="350" border="1">
			<tr height="30" class="header" align="center">
				<td width="85%" bgcolor="#666666" height="30" colspan="2" align="center"><strong><font color="white">Bobot Nilai Ujian</font></strong></td>
			</tr>
            <tr>
					<td width="85%" bgcolor="#666666" height="30"><strong><font color="white"><?=$namajenis?></font></strong></td>
					<td width="15%" bgcolor="#666666" align="center" height="30"><strong><font color="white">Bobot</font></strong></td>
				</tr>
     	<?php
			$sql_cek_ujian="SELECT * FROM jbsakad.ujian WHERE idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan' ORDER by tanggal ASC";			
			$result_cek_ujian=QueryDb($sql_cek_ujian);
			$jumujian = mysqli_num_rows($result_cek_ujian);
			$ibobot=1;
			
			while ($row_cek_ujian=@mysqli_fetch_array($result_cek_ujian)){
				$sql_get_bobotnya="SELECT b.replid, b.bobot FROM jbsakad.bobotnau b WHERE b.idujian='".$row_cek_ujian['replid']."'";								
				$result_get_bobotnya=QueryDb($sql_get_bobotnya);
				$nilai_bobotnya=@mysqli_fetch_array($result_get_bobotnya);
		?>
    		<?php 	if (mysqli_num_rows($result_get_bobotnya) > 0) { ?>
            <tr>
				<td width="85%" height="25">
            
				<!--<input <?=$dis?> type="checkbox" name="<?='jenisujian'.$ibobot ?>" id="<?='jenisujian'.$ibobot ?>" value="1" checked  onKeyPress="focusNext('bobot<?=$ibobot?>',event)">				            	
			<?php  //} else { ?>
				<input <?=$dis?> type="checkbox" name="<?='jenisujian'.$ibobot ?>" id="<?='jenisujian'.$ibobot ?>" value="1"  onKeyPress="focusNext('bobot<?=$ibobot?>',event)">--> 
            <?php //} ?>
                <?=$namajenis."-".$ibobot." (".format_tgl($row_cek_ujian['tanggal']).")"; ?>               
                </td>
                <td align="center">
                <?=$nilai_bobotnya['bobot']?>
                </td>
            </tr>
<?php
				}
				$ibobot++;
			}
			?>
            	<input type="hidden" name="jumujian" id="jumujian" value="<?=$jumujian?>" />
            </table>
   		</td>
  	</tr>
	</table>
    </td>
</tr>
</table>

</form>
</body>
</html>