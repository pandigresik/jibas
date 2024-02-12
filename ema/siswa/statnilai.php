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
require_once('../lib/dpupdate.php');

OpenDb();

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$tahunajaran = 0;
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
	
$tingkat = 0;
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
	
$semester = 0;
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
	
$pelajaran = 0;
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];
	
$dasarpenilaian = "";
if (isset($_REQUEST['dasarpenilaian']))
	$dasarpenilaian = $_REQUEST['dasarpenilaian'];					
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function chg_dep(){
	var d = document.getElementById('departemen').value;
	document.location.href = 'statnilai.php?departemen='+d;
}
function chg(){
	var d = document.getElementById('departemen').value;
	var ta = document.getElementById('tahunajaran').value;
	var t = document.getElementById('tingkat').value;
	var s = document.getElementById('semester').value;
	var p = document.getElementById('pelajaran').value;
	var dp = document.getElementById('dasarpenilaian').value;
	document.location.href = 'statnilai.php?departemen='+d+'&tahunajaran='+ta+'&tingkat='+t+'&semester='+s+'&pelajaran='+p+'&dasarpenilaian='+dp;
}

function viewdetail(rentang,i)
{
	var d = document.getElementById('departemen').value;
	var ta = document.getElementById('tahunajaran').value;
	var t = document.getElementById('tingkat').value;
	var s = document.getElementById('semester').value;
	var p = document.getElementById('pelajaran').value;
	var dp = document.getElementById('dasarpenilaian').value;
	
	show_wait('statdetail');
	sendRequestText('get_stat_detail_nilai.php', showDetail, 'departemen='+d+'&tahunajaran='+ta+'&tingkat='+t+'&semester='+s+'&pelajaran='+p+'&dasarpenilaian='+dp+'&rentang='+rentang+'&i='+i);
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
	var d = document.getElementById('departemen').value;
	var ta = document.getElementById('tahunajaran').value;
	var t = document.getElementById('tingkat').value;
	var s = document.getElementById('semester').value;
	var p = document.getElementById('pelajaran').value;
	var dp = document.getElementById('dasarpenilaian').value;
	var addr = "statnilai_cetak.php?departemen="+d+"&tahunajaran="+ta+"&tingkat="+t+"&semester="+s+"&pelajaran="+p+"&dasarpenilaian="+dp;
	newWindow(addr, 'CetakStatistikNilai','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>
<body>
<div id="waitBox" style="position:absolute; visibility:hidden;">
	<img src="../img/loading2.gif" border="0" />&nbsp;<span class="tab2">Please&nbsp;wait...</span>
</div>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
<tr>
    <td width="6%" class="news_content1">Departemen</td>
    <td width="7%">
		<select name="departemen" class="cmbfrm" id="departemen" onchange="chg_dep()">
<?php 	$sql = "SELECT departemen
				  FROM departemen
				 WHERE aktif=1
				 ORDER BY urutan";
		$result = QueryDb($sql);
		while ($row = @mysqli_fetch_row($result))
		{
			if ($departemen == "")
				$departemen = $row[0];	?>
			<option value="<?=$row[0]?>" <?=StringIsSelected($departemen,$row[0])?> ><?=$row[0]?></option>
<?php 	}	?>
		</select>
	</td>
    <td width="6%">
		<span class="news_content1">Tahunajaran</span>
	</td>
    <td width="7%">
		<select name="tahunajaran" class="cmbfrm" id="tahunajaran" onchange="chg()">
<?php 	$sql = "SELECT replid, tahunajaran, aktif
				  FROM tahunajaran
				 WHERE departemen='$departemen'
				 ORDER BY replid DESC";
		$result = QueryDb($sql);
		while ($row = @mysqli_fetch_row($result))
		{
			if ($tahunajaran == "" && $row[2] == 1)
				$tahunajaran = $row[0];
			
			$aktif = $row[2] == 1 ? " (A)" : "";
			?>
			<option value="<?=$row[0]?>" <?=StringIsSelected($tahunajaran,$row[0])?> >
			<?= $row[1] . $aktif?>
			</option>
<?php 	} ?>
		</select>
	</td>
    <td width="4%" class="news_content1">Pelajaran</td>
    <td width="70%">
		<select name="pelajaran" class="cmbfrm" id="pelajaran" onchange="chg()">
<?php 	$sql = "SELECT replid, nama
				  FROM pelajaran
				 WHERE aktif=1
				   AND departemen='$departemen'
				 ORDER BY nama";
		$result = QueryDb($sql);
		while ($row = @mysqli_fetch_row($result))
		{
			if ($pelajaran == "")
				$pelajaran = $row[0]; ?>
			<option value="<?=$row[0]?>" <?=StringIsSelected($pelajaran,$row[0])?> >
			<?=$row[1]?>
			</option>
<?php 	} ?>
		</select>
	</td>
</tr>
<tr>
    <td class="news_content1">Tingkat</td>
    <td>
		<select name="tingkat" class="cmbfrm" id="tingkat" onchange="chg()">
<?php 	$sql = "SELECT replid, tingkat
				  FROM tingkat
				 WHERE aktif=1
				   AND departemen='$departemen'
				 ORDER BY tingkat";
		$result = QueryDb($sql);
		while ($row = @mysqli_fetch_row($result))
		{
			if ($tingkat == "")
				$tingkat = $row[0];	?>
			<option value="<?=$row[0]?>" <?=StringIsSelected($tingkat,$row[0])?> >
			<?=$row[1]?>
			</option>
<?php 	}	?>
		</select>
	</td>
    <td>
		<span class="news_content1">Semester</span>
	</td>
    <td>
		<select name="semester" class="cmbfrm" id="semester" onchange="chg()">
<?php 	$sql = "SELECT replid, semester, aktif
				  FROM semester
				 WHERE aktif=1
				   AND departemen='$departemen'
				 ORDER BY semester";
		$result = QueryDb($sql);
		while ($row = @mysqli_fetch_row($result))
		{
			if ($semester == "")
				$semester = $row[0];
			
			$aktif = $row[2] == 1 ? " (A)" : "";	
			?>
			<option value="<?=$row[0]?>" <?=StringIsSelected($semester,$row[0])?> >
			<?=$row[1] . $aktif?>
			</option>
<?php 	}	?>
		</select>
	</td>
    <td class="news_content1">Nilai</td>
    <td>
		<select name="dasarpenilaian" class="cmbfrm" id="dasarpenilaian" onchange="chg()">
<?php 	$sql = "SELECT dasarpenilaian, keterangan
				  FROM dasarpenilaian
			     WHERE aktif = 1 
				 ORDER BY keterangan";
		$result = QueryDb($sql);
		while ($row = @mysqli_fetch_row($result))
		{
			if ($dasarpenilaian == "")
				$dasarpenilaian = $row[0];	?>
			<option value="<?=$row[0]?>" <?=StringIsSelected($dasarpenilaian,$row[0])?> >
			<?=$row[1]?>
			</option>
<?php 	}	?>
		</select>
	</td>
</tr>
<tr>
    <td colspan="6" align="center">
		<a href="javascript:cetak()"><img src="../img/print.png" width="16" height="16" border="0" />&nbsp;Cetak</a>
	</td>
</tr>
<tr>
    <td colspan="6" align="center">
		<img src="<?="statimagenilai.php?departemen=$departemen&tahunajaran=$tahunajaran&tingkat=$tingkat&semester=$semester&pelajaran=$pelajaran&dasarpenilaian=$dasarpenilaian" ?>" />
	</td>
</tr>
<tr>
    <td colspan="6">
		
    	<table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td width="34%" valign="top">
<?php 		$sql = "SELECT MIN(nilaiangka) as nmin, MAX(nilaiangka) AS nmax
				  FROM nap n, infonap i, aturannhb a, kelas k
				 WHERE n.idinfo = i.replid
				   AND n.idaturan = a.replid
				   AND i.idkelas = k.replid
				   AND a.dasarpenilaian = '$dasarpenilaian'
				   AND i.idpelajaran = '$pelajaran'
				   AND i.idsemester = '$semester'
				   AND k.idtahunajaran = '$tahunajaran'
				   AND k.idtingkat = '".$tingkat."'";						
                    //echo $sql;
        $result = Querydb($sql);
        $row = @mysqli_fetch_array($result);
                    
		if ($row['nmin'] >= 0 && $row['nmax'] <= 10)
			$dasar = '1'; //satuan
		else
			$dasar = '10'; //satuan
			
		//echo "dasar = $dasar<br>";	
        $rentang = [9*$dasar, 8*$dasar, 7*$dasar, 6*$dasar, 5*$dasar, 4*$dasar, 3*$dasar, 2*$dasar, 1*$dasar, 0];
		
        $filter = [$rentang[0], $rentang[1].'_'.$rentang[0], $rentang[2].'_'.$rentang[1], $rentang[3].'_'.$rentang[2], $rentang[4].'_'.$rentang[3], $rentang[5].'_'.$rentang[4], $rentang[6].'_'.$rentang[5], $rentang[7].'_'.$rentang[6], $rentang[8].'_'.$rentang[7], $rentang[9].'_'.$rentang[8]];
					
		$sql = "SELECT SUM(IF(nilaiangka >= $rentang[0],1,0)) as j1,
					   SUM(IF(nilaiangka>=$rentang[1] AND nilaiangka<$rentang[0],1,0)) as j2,
					   SUM(IF(nilaiangka>=$rentang[2] AND nilaiangka<$rentang[1],1,0)) as j3,
					   SUM(IF(nilaiangka>=$rentang[3] AND nilaiangka<$rentang[2],1,0)) as j4,
					   SUM(IF(nilaiangka>=$rentang[4] AND nilaiangka<$rentang[3],1,0)) as j5,
					   SUM(IF(nilaiangka>=$rentang[5] AND nilaiangka<$rentang[4],1,0)) as j6,
					   SUM(IF(nilaiangka>=$rentang[6] AND nilaiangka<$rentang[5],1,0)) as j7,
					   SUM(IF(nilaiangka>=$rentang[7] AND nilaiangka<$rentang[6],1,0)) as j8,
					   SUM(IF(nilaiangka>=$rentang[8] AND nilaiangka<$rentang[7],1,0)) as j9,
					   SUM(IF(nilaiangka>=$rentang[9] AND nilaiangka<$rentang[8],1,0)) as j10
				  FROM nap n, aturannhb a, infonap i, kelas k
				 WHERE n.idaturan = a.replid
				   AND n.idinfo = i.replid
				   AND i.idkelas = k.replid
				   AND a.dasarpenilaian = '$dasarpenilaian'
				   AND i.idpelajaran = '$pelajaran'
				   AND i.idsemester = '$semester'
				   AND k.idtahunajaran = '$tahunajaran'
				   AND k.idtingkat = '".$tingkat."'";
				   
        //echo "$sql<br>";
        $result = QueryDb($sql);
		
		$lab = "";
        if(mysqli_num_rows($result) == 0)
		{
            $data[$a] = 0;	
        }
		else
		{
			$num = 9;
			for($i = 0; $i < 10; $i++)
			{
				$lab[$i] = ">= " . ($num * $dasar);
				$num -= 1;
			}
			
            //$lab = array(">=90",">=80",">=70",">=60",">=50",">=40",">=30",">=20",">=10");
            while($fetch = @mysqli_fetch_array($result))
			{			
                $data = [$fetch['J1'], $fetch['J2'], $fetch['J3'], $fetch['J4'], $fetch['J5'], $fetch['J6'], $fetch['J7'], $fetch['J8'], $fetch['J9'], $fetch['J10']];
            }
        }
		?>
		<table width="80%" border="1" class="tab" align="center">
        <tr height="25" >
            <td width='10%' align="center" class="header">No.</td>
			<td width='*' align="center" class="header">Rentang</td>
            <td width='35%' align="center" class="header">Jumlah Siswa</td>
            <td width='10%' align="center" class="header">&nbsp;</td>
        </tr>
<?php 	for ($i = 0; $i < count($lab); $i++)
		{
			?>
            <tr>
                <td align="center"><?=$i+1?></td>
                <td align="center"><?=$lab[$i]?></td>
                <td align="center">
<?php 				if ($data[$i] > 0)
				{ ?>
					<strong>&nbsp;&nbsp;<?=$data[$i]?> siswa</strong>
<?php 			}
				else
				{ ?>
                    &nbsp;&nbsp;<?=$data[$i]?> siswa
<?php 				} ?>
				</td>
                <td height="20" align="center">
<?php 				if ($data[$i]>0)
				{ ?>
                    <a href="javascript:viewdetail('<?=$filter[$i]?>','<?=$i?>')"><img src="../img/lihat.png" border="0" /></a></td>
<?php 				} ?>
            </tr>
<?php      } ?>
        </table>
			</td>
            <td width="66%" valign="top"><div id="statdetail"></div></td>
          </tr>
        </table>
	</td>
</tr>
</table>
<?php
CloseDb();
?>
</body>
</html>