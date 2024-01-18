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
require_once('../include/config.php');
require_once('../include/common.php');
require_once('../include/db_functions.php');
require_once('infosiswa.session.php');
require_once('infosiswa.security.php');

$nis_awal = $_SESSION["infosiswa.nis"];

$semester = "";
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
	
$departemen = 0;
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

OpenDb();

// Dapatkan nis, jenjang dan replid sekarang dan terdahuku
$check_nis = $nis_awal;
do
{
	$sql = "SELECT replid, departemen, IF(nislama IS NULL, '', nislama) AS nislama
		 	  FROM riwayatdeptsiswa
			 WHERE nis = '$check_nis'";
	
	$result = QueryDb($sql);
	$nrow = mysqli_num_rows($result);
	if ($nrow > 0)
	{
		$row = mysqli_fetch_array($result);
		$dep[] = [$row['departemen'], $check_nis];
		
		if (strlen((string) $row['nislama']) > 0)
			$check_nis = $row['nislama'];
		else
			$nrow = 0;
	}
}
while($nrow > 0);

$nis = $dep[$departemen][1];
$sql_ajaran = "SELECT DISTINCT(t.replid), t.tahunajaran
				 FROM riwayatkelassiswa r, kelas k, tahunajaran t
				WHERE r.nis = '$nis' AND r.idkelas = k.replid AND k.idtahunajaran = t.replid
				ORDER BY t.replid DESC";
$result_ajaran = QueryDb($sql_ajaran);
$k = 0;
while ($row_ajaran = @mysqli_fetch_array($result_ajaran))
{
	$ajaran[$k] = [$row_ajaran['replid'], $row_ajaran['tahunajaran']];
	$k++;
}

$tahunajaran = $ajaran[0][0];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
	
$sql_kls = "SELECT DISTINCT(r.idkelas), k.kelas, t.tingkat, k.idtahunajaran
			  FROM riwayatkelassiswa r, kelas k, tingkat t
			 WHERE r.nis = '$nis' AND r.idkelas = k.replid AND k.idtingkat = t.replid ";
$result_kls = QueryDb($sql_kls);
$j = 0;
while ($row_kls = @mysqli_fetch_array($result_kls))
{
	$kls[$j] = [$row_kls['idkelas'], $row_kls['kelas'], $row_kls['tingkat'], $row_kls['idtahunajaran']];
	if ($row_kls['idtahunajaran']==$tahunajaran)
		$kelas = $row_kls['idkelas'];	
	$j++;
}

if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
	
$pelajaran = "";
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];
?>
<br />
<form name="panelnilai" id="panelnilai" method="post">
<input type="hidden" name="nis" id="nis" value="<?=$nis?>">
<input type="hidden" name="nis_awal" id="nis_awal" value="<?=$nis_awal?>">
<input type="hidden" name="current_nis" id="current_nis" value="<?=$nis?>">
<input type="hidden" name="active_tab" id="active_tab" value="1">

<table width="100%" cellpadding="0" cellspacing="0">    
<tr>
<td width="0"><!-- 1 -->
	<table border="0" cellpadding="2"cellspacing="2" width="100%" style="color:#000000">
	<tr>
	<td width="18%" class="gry">
		<strong class="news_content1">Departemen</strong>
	</td>
	<td width="*"> 
		<select name="departemen" class="cmbfrm" id="departemen" style="width:150px" onChange="ChangeNilaiOption2('departemen')">
<?php 		for ($i=0; $i<sizeof($dep); $i++) { ?>        	
			<option value="<?=$i ?>" <?=IntIsSelected($i, $departemen) ?> > <?=$dep[$i][0] ?> </option>
<?php 		} ?>
		</select>
	</td>
	<td class="gry"><strong class="news_content1">
		Riwayat&nbsp;Kelas</strong>
	</td>
	<td>
		<select name="kelas" class="cmbfrm" id="kelas" style="width:200px" onChange="ChangeNilaiOption2('kelas')">
<?php 			for ($j=0; $j<sizeof($kls); $j++) {
			if ($kls[$j][3] == $tahunajaran) {	?>
				<option value="<?=$kls[$j][0] ?>" <?=IntIsSelected($kls[$j][0], $kelas) ?> > <?=$kls[$j][2]." - ".$kls[$j][1] ?> </option>
<?php 				}
		} ?>
		</select>    
	</td>
	</tr>
	<tr>
	<td class="gry">
		<strong class="news_content1">Tahun&nbsp;Ajaran</strong>
	</td>
	<td>
		<select name="tahunajaran" class="cmbfrm" id="tahunajaran" style="width:150px" onChange="ChangeNilaiOption2('tahunajaran')">
<?php 			for($k=0; $k<sizeof($ajaran); $k++) { ?>
			<option value="<?=$ajaran[$k][0] ?>" <?=IntIsSelected($ajaran[$k][0], $tahunajaran) ?> ><?=$ajaran[$k][1]?> </option>
<?php 			} ?>
		</select>    
	</td>
	<td class="gry">
		<strong class="news_content1">Pelajaran </strong>
	</td>
	<td>
		<select name="pelajaran" class="cmbfrm" id="pelajaran" style="width:200px" onChange="ChangeNilaiOption2('pelajaran')">
<?php 			$sql = "SELECT DISTINCT p.replid, p.nama
			  		  FROM ujian u, pelajaran p, nilaiujian n
					 WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas'
					   AND u.replid = n.idujian AND n.nis = '$nis'
					 ORDER BY p.nama";
			$result = QueryDb($sql); 				
			while ($row = @mysqli_fetch_array($result)) {
				if ($pelajaran == "") 
					$pelajaran = $row['replid'];  ?>
				<option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $pelajaran) ?> > 
				<?=$row['nama']?> </option>
<?php 				} ?>
		</select>    
	</td>
	</tr>
	</table>
</td><!-- 1 -->
</tr>
<tr>
<td><!-- 2 -->
<?php 	if ($pelajaran <> "" && $kelas <> "") { ?>
	<br />
	<div id="TabbedPanels3" class="TabbedPanels">
		<ul class="TabbedPanelsTabGroup">
<?php 			$depart = $dep[$departemen][0];
			$sql_sem = "SELECT * FROM semester WHERE departemen = '$depart' ORDER BY replid";
			$result_sem = QueryDb($sql_sem);
			$numsem = @mysqli_num_rows($result_sem);
			
			$id = 0;
			$arrsem;
			while ($row_sem = @mysqli_fetch_array($result_sem))
			{
				$arrsem[] = $row_sem['replid'];
				$namasem[] = $row_sem['semester'];
				if ($semester == "") 
					$semester = $row_sem['replid'];
				if ($nmsem == "") 
					$nmsem = $row_sem['semester'];	?>
				<li class="TabbedPanelsTab" tabindex="0" id="<?=$id?>" onclick="ChangeTabNilai('<?=$id++?>')"><?=$row_sem['semester']?></li>    
<?php 			} ?>
		</ul>
		<div class="TabbedPanelsContentGroup">
<?php 			for($i=1; $i<=$numsem; $i++)
			{
				$semester = $arrsem[$i - 1];
				$nmsem = $namasem[$i - 1];
				?>
			<div class="TabbedPanelsContent" id="sem<?=$i?>">
			    <input type="hidden" name="idsem<?=$semester?>" id="idsem<?=$semester?>" value="<?=$semester?>" />
				<table width="100%" border="0" height="100%" >
				<tr>
					<td width="72%" valign="top">
<?php 					$sql = "SELECT * FROM pelajaran WHERE replid = $pelajaran ";
						$result = QueryDb($sql);
						$row = mysqli_fetch_array($result); ?>	
						<font color="#000000" size="3" class="news_content1">Pelajaran <?=$row['nama']?><br />Semester <?=$nmsem?> </font>
					</td> 
					<td width="28%" align="right" valign="top"> 
						&nbsp;
					</td>
				</tr>
<?php 			$sql = "SELECT j.replid, j.jenisujian
						  FROM jenisujian j, ujian u
						 WHERE j.idpelajaran = '$pelajaran' AND u.idjenis = j.replid
						 GROUP BY j.jenisujian";
				$result = QueryDb($sql);
				if (mysqli_num_rows($result) > 0)
				{ //2
					while($row = @mysqli_fetch_array($result))
					{	//1		?>
						<tr>
							<td colspan="2"> 
							<br>
							
							<fieldset>
							<legend><span class="news_title2"><?=$row['jenisujian']?></span></legend><br/>
<?php  						$sql1 = "SELECT u.tanggal, n.nilaiujian, n.keterangan
									   FROM ujian u, pelajaran p, nilaiujian n
									  WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas'
									    AND u.idpelajaran = '$pelajaran' AND u.idsemester = '".$semester."'
										AND u.idjenis = '".$row['replid']."' AND u.replid = n.idujian AND n.nis = '$nis'
									  ORDER BY u.tanggal";
							$result1 = QueryDb($sql1);
							
							if (@mysqli_num_rows($result1) > 0)
							{ // if nilai ?>
								<table border="1" width="100%" id="table19" class="tab" >
								<tr class="header" align="center" height="30">		
									<td width="5%">No</td>
									<td width="20%">Tanggal</td>
									<td width="10%">Nilai</td>
									<td width="*">Keterangan</td>
								</tr>
<?php 							$sql2 = "SELECT AVG(n.nilaiujian) as rata FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas' AND u.idpelajaran = '$pelajaran' AND u.idsemester = '".$semester."' AND u.idjenis = '".$row['replid']."' AND u.replid = n.idujian AND n.nis = '$nis' ";
								$result2 = QueryDb($sql2);	
								$row2 = @mysqli_fetch_array($result2);
								$rata = $row2['rata'];
								$cnt = 1;
								while($row1 = @mysqli_fetch_array($result1))
								{ ?>
									<tr>        			
										<td width="5" height="25" align="center"><?=$cnt?></td>
										<td width="250" height="25" align="center"><?=LongDateFormat($row1[0])?></td>
										<td width="10" height="25" align="center"><?=$row1[1]?></td>
										<td height="25"><?=$row1[2]?></td>            
									</tr>	
<?php 								$cnt++;
								}	?>
								<tr>        			
									<td colspan="2" height="25" align="center"><strong>Nilai rata rata</strong></td>
									<td width="10" height="25" align="center"><?=round($rata,2)?></td>
									<td height="25">&nbsp;</td>            
								</tr>
								</table>
<?php 							} 
							else
							{ ?>
								<table width="100%" border="0" align="center" id="table1">          
								<tr>
									<td align="center" valign="middle" height="50">
										<font color ="red" size = "2" class="err"><b>Tidak ditemukan adanya data.</b></font>
									</td>
								</tr>
								</table>
<?php 							} // if nilai ?>
							</fieldset>
							
							</td>	
						</tr>
<?php 				} // while 1 ?>
<?php 				}
				else
				{ //2 ?>
					<tr>
						<td align="center" valign="middle" height="50">
						<table border="0" width="100%" id="table1" cellpadding="0" cellspacing="0">
						<tr align="center" valign="middle" >
							<td>
							<font size = "2" color ="red"><b><span class="err">Tidak ditemukan adanya data.</span><br /></font>
							</td>
						</tr>
						</table>
						</td>
					</tr>
<?php 				} //2?>
				</table>
		</div> <!-- class="TabbedPanelsContent" -->
<?php 	} //for next jumlah div TabbedPanelsContent?>
	</div> <!-- class="TabbedPanelsContentGroup" -->
</div> <!-- id="TabbedPanels3" -->
<?php
	}
	else
	{ ?>
		<table border="0" width="100%" id="table1" cellpadding="0" cellspacing="0">
		<tr align="center" valign="middle" >
			<td>
				<font size = "2" color ="red"><b><span class="err">Tidak ditemukan adanya data.</span><br /></font>
			</td>
		</tr>
		</table>   
<?php 	} ?>
	</td><!-- 2 -->
</tr>
</table>   
</form>