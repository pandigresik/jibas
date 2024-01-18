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
require_once('../lib/dpupdate.php');
require_once('../inc/numbertotext.class.php');
require_once("infosiswa.rapor.func.php");

$nis_awal = $_SESSION["infosiswa.nis"];

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
			 WHERE nis='$check_nis'";
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

		
$depart = $dep[$departemen][0];
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

$sql_kls = "SELECT DISTINCT(r.idkelas), k.kelas, t.tingkat, k.idtahunajaran 
			  FROM riwayatkelassiswa r, kelas k, tingkat t 
			 WHERE r.nis = '$nis' AND r.idkelas = k.replid AND k.idtingkat = t.replid ";
$result_kls = QueryDb($sql_kls);
$j = 0;
while ($row_kls = @mysqli_fetch_array($result_kls)) 
{
	$kls[$j] = [$row_kls['idkelas'], $row_kls['kelas'], $row_kls['tingkat'], $row_kls['idtahunajaran']];
	$j++;
}

$tahunajaran = "";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
	
$kelas = "";
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
	
$semester = "";
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
	
?>
<div align="left" style="margin-left:1px">
<br />
<form name="panelrapor" id="panelrapor" method="post">
<input type="hidden" name="nis" id="nis" value="<?=$nis?>">
<input type="hidden" name="nis_awal" id="nis_awal" value="<?=$nis_awal?>">
<input type="hidden" name="current_nis" id="current_nis" value="<?=$nis?>">

<table width="100%" cellspacing="0" cellpadding="0">    
<tr>
	<td width="0">
    <!-- CONTENT GOES HERE //--->	
    <table border="0" cellpadding="2"cellspacing="2" width="100%" style="color:#000000">
     <tr>
     	  <td width="18%" class="gry"><strong class="news_content1">Departemen</strong></td>
	     <td width="*"> 
    	  <select name="departemen" class="cmbfrm" id="departemen" style="width:150px" onChange="ChangeRaporOption2('departemen')">
			<?php for ($i = 0; $i < sizeof($dep); $i++) 
				{ ?>        	
            <option value="<?=$i ?>" <?=IntIsSelected($i, $departemen) ?> > <?=$dep[$i][0] ?> </option>
			<?php } ?>
		  </select>
    	  </td>
        <td class="gry"><strong class="news_content1">Tahun Ajaran</strong></td>
        <td>
         <select name="tahunajaran" class="cmbfrm" id="tahunajaran" style="width:150px" onChange="ChangeRaporOption2('tahunajaran')">
   		<?php for($k = 0; $k<sizeof($ajaran); $k++) 
		   {
				if ($tahunajaran == "")
					$tahunajaran = $ajaran[$k][0]; ?>
				<option value="<?=$ajaran[$k][0] ?>" <?=IntIsSelected($ajaran[$k][0], $tahunajaran) ?> ><?=$ajaran[$k][1]?> </option>
		<?php } ?>
         </select>    
		  </td>
  	</tr>
    <tr>
	    <td width="19%" class="gry"><strong class="news_content1">Riwayat Kelas</strong></td>
       <td>
       <select name="kelas" class="cmbfrm" id="kelas" style="width:200px" onChange="ChangeRaporOption2('kelas')">
   	 <?php for ($j=0; $j<sizeof($kls); $j++) 
		 	 {
				if ($kls[$j][3] == $tahunajaran) 
				{  
					if ($kelas == "")
						$kelas = $kls[$j][0];	?>
				<option value="<?=$kls[$j][0] ?>" <?=IntIsSelected($kls[$j][0], $kelas) ?> > <?=$kls[$j][2]." - ".$kls[$j][1] ?> </option>
		<?php 	}
			} ?>
    	</select>    
		</td>
        <td class="gry"><strong class="news_content1">Semester </strong></td>
        <td>
        <select name="semester" class="cmbfrm" id="semester" style="width:200px" onChange="ChangeRaporOption2('semester')">
        <?php 	$sql = "SELECT * FROM semester WHERE departemen = '$depart' ORDER BY replid";			
			$result = QueryDb($sql); 				
			while ($row = @mysqli_fetch_array($result)) {
			if ($semester == "") 
				$semester = $row['replid'];		
		?>
			<option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $semester) ?> > 
			<?=$row['semester']?> </option>
		<?php 	} ?>
    	</select>    
		</td>
    </tr>
    <tr>
      
    </tr>
<?php if ($kelas <> "" && $semester <> "") 
	{ 		
		$sql = "SELECT pel.replid as replid,pel.nama as nama 
				  FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel 
				  WHERE uji.replid=niluji.idujian AND niluji.nis=sis.nis AND uji.idpelajaran=pel.replid 
				  AND uji.idsemester='$semester' AND uji.idkelas='$kelas' AND sis.nis='$nis' 
				  GROUP BY pel.nama";
		$result_get_pelajaran_laporan = QueryDb($sql);
    	$num = mysqli_num_rows($result_get_pelajaran_laporan);
		echo "<input type='hidden' name='num' id='num' value=$num>";
		if ($num > 0) 
		{
			?>
        <tr>
            <td colspan="4">
                <div align="right">
                    <a href="javascript:ExcelRapor3()"><img src="../img/ico/excel.png" border="0" />&nbsp;Excel </a>&nbsp;&nbsp;
                    <a href="javascript:CetakRapor3()"><img src="../img/print.png" border="0" />&nbsp;Cetak </a>
                </div>
                <?php 		ShowKomentar($semester, $kelas, $nis) ?>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <fieldset><legend><strong>Nilai Pelajaran</strong></legend>
                    <?php 		ShowRapor($semester, $kelas, $nis) ?>
                </fieldset>
                <br>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <fieldset><legend><strong>Deskripsi Nilai Pelajaran</strong></legend>
                    <?php 		    ShowRaporDeskripsi($semester, $kelas, $nis) ?>
                </fieldset>
                <br>
            </td>
        </tr>
    </table>
      </td>
  	</tr>
<?php } else { ?>                 
	<tr>
		<td align="center" valign="middle" height="120" colspan="4">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.<br /></font>
		<table id="table2"></table><table id="table3"></table>
		</td>
	</tr>
  	<?php } ?>
<?php } else { ?>                 
	<tr>
		<td align="center" valign="middle" height="120" colspan="4">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.<br /></font>
		<table id="table2"></table><table id="table3"></table>
		</td>
	</tr>
<?php } ?>

    </table>
     <!-- END OF CONTENT //--->
	</td>
</tr>
</table> 
</form>
</div>
<?php
CloseDb();
?>