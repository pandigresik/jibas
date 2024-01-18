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
require_once('../inc/sessionchecker.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');

$krit = ['', 'Bagian', 'Agama', 'Gelar', 'Jenis Kelamin', 'Status Aktif', 'Status Menikah', 'Suku', 'Tahun Kelahiran', 'Usia'];
$kriteria = '1';
if (isset($_REQUEST['kriteria']))
	$kriteria = $_REQUEST['kriteria'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language='javascript'>
function chg(){
	//var dep = document.getElementById('departemen').value;
	//var a = document.getElementById('angkatan').value;
	var krit = document.getElementById('kriteria').value;
	document.location.href='statpegawai.php?kriteria='+krit;
}
function viewdetail(kriteria,kondisi){
	show_wait('statdetail');
	sendRequestText('get_stat_detail.php',showDetail,'kriteria='+kriteria+'&kondisi='+kondisi);
}
function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}

function showDetail(x){
	document.getElementById('statdetail').innerHTML = x;
}
function lihat_pegawai(replid) {
	//var replid = document.getElementById('replid').value;
	newWindow('../lib/detail_pegawai.php?replid='+replid, 'CetakDetailSiswa','600','600','resizable=1,scrollbars=1,status=0,toolbar=0')
	//newWindow('cetak_detail_siswa.php?replid='+replid, 'CetakDetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak(){
	var addr = "statpegawai_cetak.php?kriteria=<?=$kriteria?>";
	newWindow(addr, 'CetakStatistikPegawai','800','800','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>
<body>
<div id="waitBox" style="position:absolute; visibility:hidden;">
	<img src="../img/loading2.gif" border="0" />&nbsp;<span class="tab2">Please&nbsp;wait...</span>
</div>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>
		<table width="100%" border="0" cellspacing="2" cellpadding="2">
		  <tr>
			<td width="4%" align="left" class="tab2">Kriteria</td>
			<td width="96%"><select name="kriteria" class="cmbfrm" id="kriteria" style="width:240px;" onchange="chg()" >
			<?php for ($i=1;$i<count($krit);$i++) { 
				if ($kriteria=="")
					$kriteria = $i;
			?>
			<option value ="<?=$i?>" <?=IntIsSelected($i, $kriteria) ?>><?=$krit[$i] ?></option>
			<?php  } ?>
			</select></td>
		  </tr>
		</table>
	</td>
  </tr>
  <tr>
    <td>
		<table width="100%" border="0" cellspacing="2" cellpadding="2">
		  <tr>
			<td colspan="2" align="center"><a href="javascript:cetak()"><img src="../img/print.png" width="16" height="16" border="0" />&nbsp;Cetak</a></td>
			</tr>
		  <tr>
			<td><div align="center">
				<img src="<?="statimage.php?type=bar&krit=$kriteria" ?>" />
				</div></td>
			<td><div align="center">
				<img src="<?="statimage.php?type=pie&krit=$kriteria" ?>" />
				</div></td>
		  </tr>
		</table>
    </td>
  </tr>
  <tr>
    <td>
		<table width="100%" border="0" cellspacing="7" cellpadding="7">
		  <tr>
			<td width="30%" align="center" valign="top">
			<?php
			if ($kriteria == 1) 
			{
				$bartitle = "Banyaknya Pegawai berdasarkan Bagian";
				$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Bagian";
				$xtitle = "Bagian";
				$ytitle = "Jumlah";
			
				$sql = "SELECT bagian, count(replid), bagian AS XX FROM 
						$db_name_sdm.pegawai
						WHERE aktif=1 GROUP BY bagian";	
			}
			if ($kriteria == 2) 
			{
				$bartitle = "Banyaknya Pegawai berdasarkan Agama";
				$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Agama";
				$xtitle = "Agama";
				$ytitle = "Jumlah";
			
				$sql = "SELECT agama, count(replid), agama AS XX FROM 
						$db_name_sdm.pegawai
						WHERE aktif=1 GROUP BY agama";	
			}
			if ($kriteria == 3) 
			{
				$bartitle = "Banyaknya Pegawai berdasarkan Gelar";
				$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Gelar";
				$xtitle = "Gelar";
				$ytitle = "Jumlah";
			
				$sql = "SELECT gelar, count(replid), gelar AS XX FROM 
						$db_name_sdm.pegawai
						WHERE aktif=1 GROUP BY gelar";	
			}
			
			if ($kriteria == 4)
			{
				$bartitle = "Banyaknya Pegawai berdasarkan Jenis Kelamin";
				$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Jenis Kelamin";
				$xtitle = "Jenis Kelamin";
				$ytitle = "Jumlah";
				$sql	=  "SELECT IF(kelamin='l','Laki - laki','Perempuan') as X, COUNT(nip), kelamin AS XX FROM $db_name_sdm.pegawai  WHERE aktif=1 GROUP BY X";
			}
			
			if ($kriteria == 5)
			{
				$bartitle = "Banyaknya Pegawai berdasarkan Status Aktif";
				$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Status Aktif";
				$xtitle = "Status Aktif";
				$ytitle = "Jumlah";
				$sql	=  "SELECT IF(aktif=1,'Aktif','Tidak Aktif') as X, COUNT(nip), aktif AS XX FROM $db_name_sdm.pegawai GROUP BY X";
			}
			
			if ($kriteria == 6)
			{
				$bartitle = "Banyaknya Pegawai berdasarkan Status Menikah";
				$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Status Menikah";
				$xtitle = "Menikah";
				$ytitle = "Jumlah";
				$sql	=  "SELECT IF(nikah='menikah','Menikah','Belum Menikah') as X, COUNT(nip), nikah AS XX FROM $db_name_sdm.pegawai  WHERE aktif=1 GROUP BY X";
			}
			if ($kriteria == 7) 
			{
				$bartitle = "Banyaknya Pegawai berdasarkan Suku";
				$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Suku";
				$xtitle = "Suku";
				$ytitle = "Jumlah";
			
				$sql = "SELECT suku, count(replid), suku AS XX FROM 
						$db_name_sdm.pegawai
						WHERE aktif=1 GROUP BY suku";	
			}
			if ($kriteria == 8)
			{
				$bartitle = "Banyaknya Pegawai berdasarkan Tahun Kelahiran";
				$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Tahun Kelahiran";
				$xtitle = "Tahun Lahir";
				$ytitle = "Jumlah";
				$sql = "SELECT YEAR(tgllahir) as X, count(replid), YEAR(tgllahir) AS XX FROM 
						$db_name_sdm.pegawai
						WHERE aktif=1 GROUP BY X ORDER BY X ";
			}
			if ($kriteria == 9)
			{
				$bartitle = "Banyaknya Pegawai berdasarkan Usia";
				$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Usia";
				$xtitle = "Usia (tahun)";
				$ytitle = "Jumlah";
				$sql = "SELECT G, COUNT(nip), XX FROM (
						  SELECT nip, IF(usia < 20, '<20',
									  IF(usia >= 20 AND usia <= 30, '20-30',
									  IF(usia >= 30 AND usia <= 40, '30-40',
									  IF(usia >= 40 AND usia <= 50, '40-50','>50')))) AS G,
									  IF(usia < 20, '1',
									  IF(usia >= 20 AND usia <= 30, '2',
									  IF(usia >= 30 AND usia <= 40, '3',
									  IF(usia >= 40 AND usia <= 50, '4','5')))) AS GG,
									  IF(usia < 20, 'YEAR(now())__YEAR(tgllahir)<20',
									  IF(usia >= 20 AND usia <= 30, 'YEAR(now())__YEAR(tgllahir)>=20 AND YEAR(now())__YEAR(tgllahir)<=30',
									  IF(usia >= 30 AND usia <= 40, 'YEAR(now())__YEAR(tgllahir)>=30 AND YEAR(now())__YEAR(tgllahir)<=40',
									  IF(usia >= 40 AND usia <= 50, 'YEAR(now())__YEAR(tgllahir)>=40 AND YEAR(now())__YEAR(tgllahir)<=50','YEAR(now())__YEAR(tgllahir)>=50')))) AS XX FROM
							(SELECT nip, YEAR(now())-YEAR(tgllahir) AS usia FROM $db_name_sdm.pegawai WHERE aktif=1) AS X) AS X GROUP BY G ORDER BY GG";
			}
			//echo $sql;
			?>
			<table width="100%" border="1" class="tab" align="center">
			  <tr>
				<td height="25" align="center" class="header">No.</td>
				<td height="25" align="center" class="header"><?=$xtitle?></td>
				<td height="25" align="center" class="header"><?=$ytitle?></td>
				<td height="25" align="center" class="header">&nbsp;</td>
			  </tr>
			  <?php
			  OpenDb();
			  $result = QueryDb($sql);
			  $cnt=1;
			  while ($row = @mysqli_fetch_row($result)){
			  ?>
			  <tr>
				<td width="15" height="20" align="center"><?=$cnt?></td>
				<td height="20">&nbsp;&nbsp;<?=$row[0]?></td>
				<td height="20" align="center"><?=$row[1]?> orang</td>
				<td height="20" align="center"><a href="javascript:viewdetail('<?=$kriteria?>','<?=$row[2]?>')"><img src="../img/lihat.png" border="0" /></a></td>
			  </tr>
			  <?php
			  $cnt++;
			  }
			  ?>
			</table>
			</td>
			<td align="left" valign="top"><div id="statdetail"></div></td>
		  </tr>
		</table>
	</td>
  </tr>
</table>

</body>
</html>