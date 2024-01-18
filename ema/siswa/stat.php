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

$krit = ['', 'Agama', 'Asal Sekolah', 'Golongan Darah', 'Jenis Kelamin', 'Kewarganegaraan', 'Kode Pos Siswa', 'Kondisi Siswa', 'Pekerjaan Ayah', 'Pekerjaan Ibu', 'Pendidikan Ayah', 'Pendidikan Ibu', 'Penghasilan Orang Tua', 'Status Aktif', 'Status Siswa', 'Suku', 'Tahun Kelahiran', 'Usia'];
$departemen = '-1';
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$angkatan = '';
if (isset($_REQUEST['angkatan']))
	$angkatan = $_REQUEST['angkatan'];
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
function change_dep(){
	var dep = document.getElementById('departemen').value;
	var krit = document.getElementById('kriteria').value;
	document.location.href='stat.php?departemen='+dep+'&kriteria='+krit;
	
}
function chg(){
	var dep = document.getElementById('departemen').value;
	var a = document.getElementById('angkatan').value;
	var krit = document.getElementById('kriteria').value;
	document.location.href='stat.php?departemen='+dep+'&angkatan='+a+'&kriteria='+krit;
}
function viewdetail(kriteria,departemen,angkatan,kondisi){
	show_wait('statdetail');
	sendRequestText('get_stat_detail.php',showDetail,'departemen='+departemen+'&angkatan='+angkatan+'&kriteria='+kriteria+'&kondisi='+kondisi);
}
function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}

function showDetail(x){
	document.getElementById('statdetail').innerHTML = x;
}
function lihat_siswa(replid) {
	//var replid = document.getElementById('replid').value;
	newWindow('../lib/detail_siswa.php?replid='+replid, 'CetakDetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')
	//newWindow('cetak_detail_siswa.php?replid='+replid, 'CetakDetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak(){
	var addr = "stat_cetak.php?kriteria=<?=$kriteria?>&departemen=<?=$departemen?>&angkatan=<?=$angkatan?>";
	newWindow(addr, 'CetakStatistik','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>
<body>
<div id="waitBox" style="position:absolute; visibility:hidden;">
	<img src="../img/loading2.gif" border="0" />&nbsp;<span class="tab2">Please&nbsp;wait...</span>
</div>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td align="right" class="tab2">Dep</td>
    <td><select name="departemen" class="cmbfrm" id="departemen" style="width:240px;" onchange="change_dep()" >
       		  <option value="-1" >(Semua Departemen)</option>    
			<?php
				$sql = "SELECT * FROM departemen WHERE aktif=1 ORDER BY urutan";
				OpenDb();
				$result = QueryDb($sql);
				CloseDb();
			
				//$departemen = "";	
				while($row = mysqli_fetch_array($result)) {
					//if ($departemen == "")
						//$departemen = "-1";
						//$departemen = $row['departemen'];
			?>
            	<option value="<?=urlencode((string) $row['departemen'])?>" <?=StringIsSelected($row['departemen'], $departemen) ?>><?=$row['departemen']?></option>
<?php
				} //while
			?>
  		  </select></td>
        <td align="right" class="tab2">Angkatan</td>
        <td><select name="angkatan" class="cmbfrm" id="angkatan" style="width:240px;" onchange="chg()" <?=$disable?> >
        	<option value="" >(Semua Angkatan yang Aktif)</option>
        	<?php if ($departemen!='-1'){ ?> 
			<?php 	OpenDb();
				$sql = "SELECT replid,angkatan FROM angkatan where aktif = 1 AND departemen = '$departemen' ORDER BY replid DESC";
				$result = QueryDb($sql);
				while ($row = mysqli_fetch_array($result)) {
					//if ($angkatan=="")
						//$angkatan = $row['replid'];
			?>
        	<option value="<?=urlencode((string) $row['replid'])?>" <?=StringIsSelected($row['replid'], $angkatan) ?>><?=$row['angkatan']?></option>
<?php
  				} //while
				CloseDb();
			}
			?>
   		  </select></td>
        <td align="right" class="tab2">Kriteria</td>
        <td><select name="kriteria" class="cmbfrm" id="kriteria" style="width:240px;" onchange="chg()" >
        <?php for ($i=1;$i<=17;$i++) { 
			if ($kriteria=="")
				$kriteria = $i;
		?>
        <option value ="<?=$i?>" <?=IntIsSelected($i, $kriteria) ?>><?=$krit[$i] ?></option>
        <?php  } ?>
        </select></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td colspan="2" align="center"><a href="javascript:cetak()"><img src="../img/print.png" width="16" height="16" border="0" />&nbsp;Cetak</a></td>
    </tr>
  <tr>
    <td><div align="center">
        <img src="<?="statimage.php?type=bar&dep=$departemen&angkatan=$angkatan&krit=$kriteria" ?>" />
        </div></td>
    <td><div align="center">
        <img src="<?="statimage.php?type=pie&dep=$departemen&angkatan=$angkatan&krit=$kriteria" ?>" />
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
		$filter = "";
		if ($departemen == "-1")
			$filter="AND a.replid=s.idangkatan ";
		if ($departemen != "-1" && $angkatan == "")
			$filter="AND a.departemen='$departemen' AND a.replid=s.idangkatan ";
		if ($departemen != "-1" && $angkatan != "")
			$filter="AND s.idangkatan=$angkatan AND a.replid=s.idangkatan AND a.departemen='$departemen' ";
		
		if ($kriteria == 1) 
		{
			$xtitle = "Agama";
			$ytitle = "Jumlah";
		
			$sql = "SELECT s.agama, count(s.replid), s.agama AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.agama";	
		}
		
		elseif ($kriteria == 2) 
		{
			$xtitle = "Asal Sekolah";
			$ytitle = "Jumlah";
		
			$sql = "SELECT s.asalsekolah, count(s.replid), s.asalsekolah AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.asalsekolah";	
					//echo $sql;
		}
		
		elseif ($kriteria == 3) 
		{
			$xtitle = "Golongan Darah";
			$ytitle = "Jumlah";
		
			$sql = "SELECT s.darah, count(s.replid), s.darah AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.darah";	
		}
		elseif ($kriteria == 4)
		{
			$xtitle = "Jenis Kelamin";
			$ytitle = "Jumlah";
			$sql	=  "SELECT IF(s.kelamin='l','Laki - laki','Perempuan') as X, COUNT(s.nis), s.kelamin AS XX FROM siswa s, angkatan a WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X";
		}
		elseif ($kriteria == 5)
		{
			$xtitle = "Warga Negara";
			$ytitle = "Jumlah";
			$sql = "SELECT s.warga, count(s.replid), s.warga AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.warga ORDER BY s.warga DESC";
		}
		elseif ($kriteria == 6)
		{
			$xtitle = "Kodepos";
			$ytitle = "Jumlah";
			$sql = "SELECT s.kodepossiswa, count(s.replid), s.kodepossiswa AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.kodepossiswa ";
		}
		elseif ($kriteria == 7)
		{
			$xtitle = "Kondisi";
			$ytitle = "Jumlah";
			$sql = "SELECT s.kondisi, count(s.replid), s.kondisi AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.kondisi ";
		}
		elseif ($kriteria == 8)
		{
			$xtitle = "Pekerjaan Ayah";
			$ytitle = "Jumlah";
			$sql = "SELECT s.pekerjaanayah, count(s.replid), s.pekerjaanayah AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pekerjaanayah ";
		}
		elseif ($kriteria == 9)
		{
			$xtitle = "Pekerjaan Ibu";
			$ytitle = "Jumlah";
			$sql = "SELECT s.pekerjaanibu, count(s.replid), s.pekerjaanibu AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pekerjaanibu ";
		}
		elseif ($kriteria == 10)
		{
			$xtitle = "Pendidikan Ayah";
			$ytitle = "Jumlah";
			$sql = "SELECT s.pendidikanayah, count(s.replid), s.pendidikanayah AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pendidikanayah ";
		}
		elseif ($kriteria == 11)
		{
			$xtitle = "Pendidikan Ibu";
			$ytitle = "Jumlah";
			$sql = "SELECT s.pendidikanibu, count(s.replid), s.pendidikanibu AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pendidikanibu ";
		}
		elseif ($kriteria == 12)
		{
			$xtitle = "Penghasilan (rupiah)";
			$ytitle = "Jumlah";
			$sql = "SELECT G, COUNT(nis), G FROM (
					  SELECT nis, IF(s.penghasilanibu + s.penghasilanayah < 1000000, '< 1 juta',
								  IF(s.penghasilanibu + s.penghasilanayah >= 1000001 AND s.penghasilanibu + s.penghasilanayah <= 2500000, '1 juta - 2,5 juta',
								  IF(s.penghasilanibu + s.penghasilanayah >= 2500001 AND s.penghasilanibu + s.penghasilanayah <= 5000000, '2,5 juta - 5 juta',
								  IF(s.penghasilanibu + s.penghasilanayah >= 5000001 , '> 5 juta', 'Tidak Ada Data')))) AS G
					    FROM siswa s, angkatan a
					   WHERE a.aktif = 1
					     AND s.aktif = 1
						     $filter
					) AS X 		 
					GROUP BY G";
		}
		elseif ($kriteria == 13)
		{
			$xtitle = "Status Aktif";
			$ytitle = "Jumlah";
			$sql	=  "SELECT IF(s.aktif=1,'Aktif','Tidak Aktif') as X, COUNT(s.nis), s.aktif AS XX FROM siswa s, angkatan a WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X";
		}
		elseif ($kriteria == 14)
		{
			$xtitle = "Status Siswa";
			$ytitle = "Jumlah";
			$sql = "SELECT s.status as X, count(s.replid), s.status AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X ";
		}
		elseif ($kriteria == 15)
		{
			$xtitle = "Suku";
			$ytitle = "Jumlah";
			$sql = "SELECT s.suku as X, count(s.replid), s.suku AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X ";
		}
		elseif ($kriteria == 16)
		{
			$xtitle = "Tahun Lahir";
			$ytitle = "Jumlah";
			$sql = "SELECT YEAR(s.tgllahir) as X, count(s.replid), YEAR(s.tgllahir) AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X ORDER BY X ";
		}
		elseif ($kriteria == 17)
		{
			$xtitle = "Usia (tahun)";
			$ytitle = "Jumlah";
			$sql = "SELECT G, COUNT(nis), G FROM (
					  SELECT nis, IF(YEAR(NOW()) - YEAR(s.tgllahir) < 6, '<6',
								  IF(YEAR(NOW()) - YEAR(s.tgllahir) >= 6 AND YEAR(NOW()) - YEAR(s.tgllahir) <= 12, '6-12',
								  IF(YEAR(NOW()) - YEAR(s.tgllahir) >= 13 AND YEAR(NOW()) - YEAR(s.tgllahir) <= 15, '13-15',
								  IF(YEAR(NOW()) - YEAR(s.tgllahir) >= 16 AND YEAR(NOW()) - YEAR(s.tgllahir) <= 18, '16-18', '>18')))) AS G
						FROM siswa s, angkatan a
					   WHERE a.aktif = 1
					     AND s.aktif = 1
						     $filter
					) AS X 		 
					GROUP BY G";
		}
		
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
            <td height="20" align="center"><?=$row[1]?> siswa</td>
            <td height="20" align="center"><a href="javascript:viewdetail('<?=$kriteria?>','<?=$departemen?>','<?=$angkatan?>','<?=$row[2]?>')"><img src="../img/lihat.png" border="0" /></a></td>
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