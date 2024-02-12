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
require_once('../inc/db_functions.php');
require_once('../inc/common.php');

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
OpenDb();
$sql = "SELECT keterangan FROM dasarpenilaian WHERE dasarpenilaian = '".$dasarpenilaian."'";
$res2 = QueryDb($sql);
$row2 = mysqli_fetch_row($res2);
$aspekket = $row2[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Rapor]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>STATISTIK PEROLEHAN NILAI RAPOR</strong></font><br />
 </center><br /><br />
<table width="84%">
<tr>
	<td width="9%" class="news_content1"><strong>Departemen</strong></td>
    <td width="42%" class="news_content1">: 
        <?=$departemen?>    </td>
    <td width="7%" class="news_content1"><strong>Semester</strong></td>
    <td width="42%" class="news_content1">: 
        <?=getname('semester','semester',$semester)?>    </td>
</tr>
<tr>
	<td class="news_content1"><strong>Tingkat</strong></td>
    <td class="news_content1">: 
        <?=getname('tingkat','tingkat',$tingkat)?>    </td>
    <td class="news_content1"><strong>Pelajaran</strong></td>
    <td class="news_content1">: 
        <?=getname('nama','pelajaran',$pelajaran)?>    </td>
</tr>
<tr>
	<td class="news_content1"><strong>Tahun&nbsp;Ajaran</strong></td>
    <td class="news_content1">: 
        <?=getname('tahunajaran','tahunajaran',$tahunajaran) ?>    </td>
    <td class="news_content1"><strong>Nilai</strong></td>
    <td class="news_content1">: 
        <?=$aspekket?>    </td>
</tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="center"><img src="<?="statimagenilai.php?departemen=$departemen&tahunajaran=$tahunajaran&tingkat=$tingkat&semester=$semester&pelajaran=$pelajaran&dasarpenilaian=$dasarpenilaian" ?>" /></td>
      </tr>
      <tr>
        <td align="center">
        <?php
		$sql = "SELECT MIN(nilaiangka) as nmin, MAX(nilaiangka) AS nmax
				  FROM nap n, infonap i, aturannhb a, kelas k
				 WHERE n.idinfo = i.replid
				   AND n.idaturan = a.replid
				   AND i.idkelas = k.replid
				   AND a.dasarpenilaian = '$dasarpenilaian'
				   AND i.idpelajaran = '$pelajaran'
				   AND i.idsemester = '$semester'
				   AND k.idtahunajaran = '$tahunajaran'
				   AND k.idtingkat = '".$tingkat."'";
				   
        $result=Querydb($sql);
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
					  </tr>
                      <?php
                      for ($i=0;$i<count($lab);$i++){
                      ?>
                      <tr>
                        <td height="20" align="center"><?=$i+1?></td>
                        <td height="20">&nbsp;&nbsp;<?=$lab[$i]?></td>
                        <td height="20">
                        <?php if ($data[$i]>0){ ?>
                        <strong>&nbsp;&nbsp;<?=$data[$i]?> siswa</strong>
                        <?php } else { ?>
                        &nbsp;&nbsp;<?=$data[$i]?> siswa
                        <?php } ?>            </td>
                        <?php //} ?>
                      </tr>
<?php
                      }
                      ?>
                    </table>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>

	</td>
</tr>    
</table>
<?php
CloseDb();
?>
</body>
<script language="javascript">
window.print();
</script>

</html>