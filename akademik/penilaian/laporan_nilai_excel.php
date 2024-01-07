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
//require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

OpenDb();
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['pelajaran'])) 
	$pelajaran = $_REQUEST['pelajaran'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> New Document </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style4 {font-size: 12}
.style5 {color: #FFFFFF; font-weight: bold; font-size: 12; }
.style6 {
	font-size: 12px;
	font-weight: bold;
}
-->
</style>
<script language="javascript" src="../script/tables.js"></script>
</HEAD>
<BODY>
<table width="100%" border="0">
  <tr>
    <td><table width="100%" border="0" bordercolor="#666666">
  <tr>
    <td width="6%" height="50" bgcolor="#666666"><span class="style5">NIS </span><span class="style4"><br>
        <span class="style1">Nama </span></span></td>
    <td width="93%" height="50" bgcolor="#666666"><span class="style5">
      : <?=$nis?>
    </span><span class="style4"><br>
	  <span class="style1">
	  : <?php
	$sql_get_nama="SELECT nama FROM jbsakad.siswa WHERE nis='$nis'";
	$result_get_nama=QueryDb($sql_get_nama);
	$row_get_nama=@mysqli_fetch_array($result_get_nama);
	echo $row_get_nama['nama'];
    ?>
	   </span></span></td>
  </tr>
  
</table>
</td>
  </tr>
  <tr>
    <td><fieldset><legend><strong>Laporan Hasil Belajar</strong></legend>
	<!-- Content Laporan disini -->
	<table width="100%" border="1" class="tab" id="table">
  <tr>
    <td width="27%" rowspan="2" class="headerlong"><div align="center">Pelajaran</div></td>
    <td width="14%" rowspan="2" class="headerlong"><div align="center">Standar Kelulusan</div></td>
    <td width="18%" colspan="2" class="headerlong"><div align="center">Nilai Pemahaman Konsep</div></td>
    <td width="19%" colspan="2" class="headerlong"><div align="center">Nilai Praktik</div></td>
    <td width="23%" rowspan="2" class="headerlong"><div align="center">Predikat</div></td>
  </tr>
  <tr>
    <td class="header"><div align="center">Angka</div></td>
    <td class="header"><div align="center">Huruf</div></td>
    <td class="header"><div align="center">Angka</div></td>
    <td class="header"><div align="center">Huruf</div></td>
    </tr>
	<?php
	$sql_get_pelajaran_laporan="SELECT * FROM jbsakad.pelajaran WHERE departemen='$departemen' ORDER BY nama ASC";
	$result_get_pelajaran_laporan=QueryDb($sql_get_pelajaran_laporan);
	$cntpel_laporan=1;
	while ($row_get_pelajaran_laporan=@mysqli_fetch_array($result_get_pelajaran_laporan)){
		$sql_get_pred="SELECT k.predikat as predikat FROM jbsakad.komennap k, jbsakad.infonap i WHERE k.nis='$nis' AND i.idpelajaran='".$row_get_pelajaran_laporan['replid']."' AND i.replid=k.idinfo";
		$result_get_pred=QueryDb($sql_get_pred);
		$row_get_pred=@mysqli_fetch_array($result_get_pred);
		switch ($row_get_pred['predikat']){
			case "":$predikat="";
			break;
			case "0":$predikat="Buruk";
			break;
			case "1":$predikat="Kurang";
			break;
			case "2":$predikat="Cukup";
			break;
			case "3":$predikat="Baik";
			break;
			case "4":$predikat="Istimewa";
			break;
		}
		//Get infonap id and min value
		$sql_get_infonap="SELECT replid,nilaimin FROM jbsakad.infonap WHERE idpelajaran='".$row_get_pelajaran_laporan['replid']."' AND idsemester='$semester' AND idkelas='$kelas'";
		$result_get_infonap=QueryDb($sql_get_infonap);
		$row_get_infonap=@mysqli_fetch_array($result_get_infonap);
		//get value from nap pemahaman konsep
		$sql_get_nap_PK="SELECT n.nilaiangka as nilaiangka,n.nilaihuruf as nilaihuruf FROM jbsakad.nap n, jbsakad.aturannhb a WHERE n.idinfo='".$row_get_infonap['replid']."' AND n.idaturan=a.replid AND n.nis='$nis' AND a.dasarpenilaian='Pemahaman Konsep'";
		$result_get_nap_PK=QueryDb($sql_get_nap_PK);
		$row_get_nap_PK=@mysqli_fetch_array($result_get_nap_PK);
		//get value from nap praktik
		$sql_get_nap_P="SELECT n.nilaiangka as nilaiangka,n.nilaihuruf as nilaihuruf FROM jbsakad.nap n, jbsakad.aturannhb a WHERE n.idinfo='".$row_get_infonap['replid']."' AND n.idaturan=a.replid AND n.nis='$nis' AND a.dasarpenilaian='Praktik'";
		$result_get_nap_P=QueryDb($sql_get_nap_P);
		$row_get_nap_P=@mysqli_fetch_array($result_get_nap_P);
	?>
  <tr>
    <td height="25"><?=$row_get_pelajaran_laporan['nama']?>
					<input type="hidden" name="replid_pel_laporan" id="replid_pel_laporan" value="<?=$row_get_pelajaran_laporan['replid']?>">
	</td>
    <td height="25" align="center"><?=$row_get_infonap['nilaimin']?></td>
    <!-- Biar ada merahnya -->
	<?php
		if ($row_get_nap_PK['nilaiangka']<$row_get_infonap['nilaimin']){
		?>
	<td height="25" align="center"><font color="red"><?=$row_get_nap_PK['nilaiangka']?></font><br></td>
    <?php
	} else {
			?>
	<td height="25" align="center"><?=$row_get_nap_PK['nilaiangka']?><br></td>
	<?php
				}
			?>
	<td height="25" align="center"><?=$row_get_nap_PK['nilaihuruf']?></td>
     <!-- Biar ada merahnya -->
	<?php
		if ($row_get_nap_P['nilaiangka']<$row_get_infonap['nilaimin']){
		?>
	<td height="25" align="center"><font color="red"><?=$row_get_nap_P['nilaiangka']?></font><br></td>
    <?php
	} else {
			?>
	<td height="25" align="center"><?=$row_get_nap_P['nilaiangka']?><br></td>
	<?php
				}
			?>
    <td height="25" align="center"><?=$row_get_nap_P['nilaihuruf']?></td>
    <td height="25" align="center"><?=$predikat?></td>
  </tr>
	<?php
		$cntpel_laporan++;
		}
	?>
</table>
	<script language='JavaScript'>
	   	Tables('table', 1, 0);
	</script>
	</fieldset></td>
  </tr>
  <tr>
    <td><fieldset><legend><strong>Komentar Hasil Belajar</strong></legend>
	<!-- Content Komentar disini -->
	<table border="1" id="table" class="tab" width="100%">
	<tr>
	<td width="27%" height="30" align="center" class="header">Pelajaran</td>
	<td width="73%" height="30" align="center" class="header">Komentar</td>
	</tr>
	<!-- Ambil pelajaran per departemen-->
	<?php
	$sql_get_pelajaran_komentar="SELECT * FROM jbsakad.pelajaran WHERE departemen='$departemen' ORDER BY nama ASC";
	$result_get_pelajaran_komentar=QueryDb($sql_get_pelajaran_komentar);
	$cntpel_komentar=1;
	while ($row_get_pelajaran_komentar=@mysqli_fetch_array($result_get_pelajaran_komentar)){
	$sql_get_komentar="SELECT k.komentar FROM jbsakad.komennap k, jbsakad.infonap i WHERE k.nis='$nis' AND i.idpelajaran='".$row_get_pelajaran_komentar['replid']."' AND i.replid=k.idinfo";
	$result_get_komentar=QueryDb($sql_get_komentar);
	$row_get_komentar=@mysqli_fetch_row($result_get_komentar);
	?>
	<tr>
	<td height="25"><?=$row_get_pelajaran_komentar['nama']?></td>
	<td height="25"><?=$row_get_komentar[0]?></td>
	</tr>
	<?php
	$cntpel_komentar++;
	}
	?>
	</table>
	<script language='JavaScript'>
	   	Tables('table', 1, 0);
	</script>
	</fieldset></td>
  </tr>
  <tr>
    <td><fieldset><legend><strong>Presensi</strong></legend>
	<!-- Content Presensi disini -->
	<table width="100%" border="1" class="tab" id="table">
  <tr>
    <td width="27%" rowspan="2" class="headerlong"><div align="center">Pelajaran</div></td>
    <td height="25" colspan="2" class="headerlong"><div align="center">Hadir</div></td>
    <td height="25" colspan="2" class="headerlong"><div align="center">Sakit</div></td>
    <td height="25" colspan="2" class="headerlong"><div align="center">Ijin</div></td>
    <td height="25" colspan="2" class="headerlong"><div align="center">Alpa</div></td>
    </tr>
  <tr>
    <td width="6" class="headerlong"><div align="center">Jumlah</div></td>
    <td width="6" class="headerlong"><div align="center">%</div></td>
    <td width="6" class="headerlong"><div align="center">Jumlah</div></td>
    <td width="6" class="headerlong"><div align="center">%</div></td>
    <td width="6" class="headerlong"><div align="center">Jumlah</div></td>
    <td width="6" class="headerlong"><div align="center">%</div></td>
    <td width="6" class="headerlong"><div align="center">Jumlah</div></td>
    <td width="6" class="headerlong"><div align="center">%</div></td>
  </tr>
  <!-- Ambil pelajaran per departemen-->
	<?php
	$sql_get_pelajaran_presensi="SELECT * FROM jbsakad.pelajaran WHERE departemen='$departemen' ORDER BY nama ASC";
	$result_get_pelajaran_presensi=QueryDb($sql_get_pelajaran_presensi);
	$cntpel_presensi=1;
	
	while ($row_get_pelajaran_presensi=@mysqli_fetch_array($result_get_pelajaran_presensi)){
	//ambil semua jumlah presensi per pelajaran 
	$sql_get_all_presensi="select count(*) as jumlah FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='$nis'";
	$result_get_all_presensi=QueryDb($sql_get_all_presensi);
	$row_get_all_presensi=@mysqli_fetch_array($result_get_all_presensi);
	//dapet nih jumlahnya
	$jumlah_presensi=$row_get_all_presensi['jumlah'];

	//ambil yang hadir
	$sql_get_hadir="select count(*) as hadir FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=0";
	$result_get_hadir=QueryDb($sql_get_hadir);
	$row_get_hadir=@mysqli_fetch_array($result_get_hadir);
	$hadir=$row_get_hadir['hadir'];
	$hh[$cntpel_presensi]=$hadir;
	//ambil yang sakit
	$sql_get_sakit="select count(*) as sakit FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=1";
	$result_get_sakit=QueryDb($sql_get_sakit);
	$row_get_sakit=@mysqli_fetch_array($result_get_sakit);
	$sakit=$row_get_sakit['sakit'];
	$ss[$cntpel_presensi]=$sakit;
		
	//ambil yang ijin
	$sql_get_ijin="select count(*) as ijin FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=2";
	$result_get_ijin=QueryDb($sql_get_ijin);
	$row_get_ijin=@mysqli_fetch_array($result_get_ijin);
	$ijin=$row_get_ijin['ijin'];
	$ii[$cntpel_presensi]=$ijin;
	//ambil yang alpa
	$sql_get_alpa="select count(*) as alpa FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=3";
	$result_get_alpa=QueryDb($sql_get_alpa);
	$row_get_alpa=@mysqli_fetch_array($result_get_alpa);
	$alpa=$row_get_alpa['alpa'];
	$aa[$cntpel_presensi]=$alpa;
	//hitung prosentase kalo jumlahnya gak 0
	if ($jumlah_presensi<>0){
		$p_hadir=round(($hadir/$jumlah_presensi)*100);
		$p_sakit=round(($sakit/$jumlah_presensi)*100);
		$p_ijin=round(($ijin/$jumlah_presensi)*100);
		$p_alpa=round(($alpa/$jumlah_presensi)*100);
	} else {
		$p_hadir=0;
		$p_sakit=0;
		$p_ijin=0;
		$p_alpa=0;
	}
	?>
	<tr>
    <td height="25"><?=$row_get_pelajaran_presensi['nama']?></td>
    <td height="25"><div align="center">
      <?=$hadir?>
    </div></td>
    <td height="25"><div align="center">
      <?=$p_hadir?>
      &nbsp;%</div></td>
    <td height="25"><div align="center">
      <?=$sakit?>
    </div></td>
    <td height="25"><div align="center">
      <?=$p_sakit?>
      &nbsp;%</div></td>
    <td height="25"><div align="center">
      <?=$ijin?>
    </div></td>
    <td height="25"><div align="center">
      <?=$p_ijin?>
      &nbsp;%</div></td>
    <td height="25"><div align="center">
      <?=$alpa?>
    </div></td>
    <td height="25"><div align="center">
      <?=$p_alpa?>
      &nbsp;%</div></td>
	 </tr>
	<?php
	$cntpel_presensi++;
	}
	$hdr = 0;
	for ($i=1;$i<=count($hh);$i++)
		$hdr += $hh[$i];
	$skt = 0;
	for ($i=1;$i<=count($ss);$i++)
		$skt += $ss[$i];
	$ijn = 0;
	for ($i=1;$i<=count($ii);$i++)
		$ijn += $ii[$i];
	$alp = 0;
	for ($i=1;$i<=count($aa);$i++)
		$alp += $aa[$i];
	/*
	//sekarang hitung jumlah hadir semua pelajaran
	$sql_all_hadir="select count(*) as allhadir FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='22' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=0";
    $result_all_hadir=QueryDb($sql_all_hadir);
	$row_all_hadir=@mysqli_fetch_array($result_all_hadir);
	$all_hadir=$row_all_hadir['allhadir'];
	
	//sekarang hitung jumlah sakit semua pelajaran
	$sql_all_sakit="select count(*) as allsakit FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='22' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=1";
    $result_all_sakit=QueryDb($sql_all_sakit);
	$row_all_sakit=@mysqli_fetch_array($result_all_sakit);
	$all_sakit=$row_all_sakit['allsakit'];

	//sekarang hitung jumlah ijin semua pelajaran
	$sql_all_ijin="select count(*) as allijin FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='22' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=2";
    $result_all_ijin=QueryDb($sql_all_ijin);
	$row_all_ijin=@mysqli_fetch_array($result_all_ijin);
	$all_ijin=$row_all_ijin['allijin'];

	//sekarang hitung jumlah alpa semua pelajaran
	$sql_all_alpa="select count(*) as allalpa FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='22' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=3";
    $result_all_alpa=QueryDb($sql_all_alpa);
	$row_all_alpa=@mysqli_fetch_array($result_all_alpa);
	$all_alpa=$row_all_alpa['allalpa'];
	*/
	?>
  <tr>
    <td height="25" bgcolor="#FFFFFF"><div align="center" class="style6">Total</div></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><?=$hdr?></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"></div></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><?=$skt?></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"></div></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><?=$ijn?></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"></div></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><?=$alp?></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"></div></td>
  </tr></table>
<script language='JavaScript'>
	   	Tables('table', 1, 0);
</script>
    </fieldset></td>
  </tr>
</table>

</BODY>
</HTML>
<?php
CloseDb();
?>