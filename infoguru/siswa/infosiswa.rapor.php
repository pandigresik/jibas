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
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/getheader.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../library/dpupdate.php');
require_once('../include/numbertotext.class.php');

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
		$dep[] = array($row['departemen'], $check_nis);
		
		if (strlen($row['nislama']) > 0)
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
	$ajaran[$k] = array($row_ajaran['replid'], $row_ajaran['tahunajaran']);
	$k++;
}

$sql_kls = "SELECT DISTINCT(r.idkelas), k.kelas, t.tingkat, k.idtahunajaran 
			  FROM riwayatkelassiswa r, kelas k, tingkat t 
			 WHERE r.nis = '$nis' AND r.idkelas = k.replid AND k.idtingkat = t.replid ";
$result_kls = QueryDb($sql_kls);
$j = 0;
while ($row_kls = @mysqli_fetch_array($result_kls)) 
{
	$kls[$j] = array($row_kls['idkelas'], $row_kls['kelas'], $row_kls['tingkat'], $row_kls['idtahunajaran']);
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
            <div align="right"><a href="javascript:CetakRapor3()"><img src="../images/ico/print.png" border="0" />&nbsp;Cetak</a></div>
<?php 		ShowKomentar($semester, $kelas, $nis) ?>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <fieldset><legend class="news_title2"><strong>Nilai</strong></legend>
                    <?php 		ShowRapor($semester, $kelas, $nis) ?>
                </fieldset>
                <br>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <fieldset><legend class="news_title2"><strong>Deskripsi Nilai</strong></legend>
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

function ShowKomentar($semester, $kelas, $nis)
{
    $arrjenis = array('SPI', 'SOS');
    $arrnmjenis = array('Spiritual', 'Sosial');
    for($i = 0; $i < count($arrjenis); $i++)
    {
        $jenis = $arrjenis[$i];
        $nmjenis = $arrnmjenis[$i];

        $sql = "SELECT komentar, k.predikat
                  FROM jbsakad.komenrapor k 
                 WHERE k.nis = '$nis' 
                   AND k.idsemester = '$semester' 
                   AND k.idkelas = '$kelas'
                   AND k.jenis = '".$jenis."'";
        $res2 = QueryDb($sql);
        $komentar = "";
        $predikat = "";
        $nilaiExist = false;
        if ($row2 = mysqli_fetch_row($res2))
        {
            $nilaiExist = true;
            $komentar = $row2[0];
            $predikat = PredikatNama($row2[1]);
        }


        echo "<fieldset><legend><strong>Sikap $nmjenis</strong></legend>";
        echo "<table border='1' width='100%' cellpadding='2' cellspacing='0' style='border-width: 1px; border-collapse: collapse'>";
        echo "<tr style='height: 120px'>";
        echo "<td width='20%' align='left' valign='top'>Predikat: $predikat</td>";
        echo "<td width='*' align='left' valign='top'>$komentar</td>";
        echo "</tr>";
        echo "</table>";
        echo "</fieldset><br>";

    }
}

function ShowRapor($semester, $kelas, $nis)
{
	$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
	  	      FROM infonap i, nap n, aturannhb a, dasarpenilaian d
			 WHERE i.replid = n.idinfo
			   AND i.idsemester = '$semester' 
			   AND i.idkelas = '$kelas'
			   AND n.idaturan = a.replid 	   
			   AND a.dasarpenilaian = d.dasarpenilaian
			   AND d.aktif = 1";
		   
	$res = QueryDb($sql);
	$naspek = mysqli_num_rows($res); 
	
    ShowRaporColumn($semester, $kelas, $nis);
}

function PredikatNama($predikat)
{
    switch ($predikat)
    {
        case 4:
            return "Istimewa";
        case 3:
            return "Baik";
        case 2:
            return "Cukup";
        case 1:
            return "Kurang";
        case 0:
            return "Buruk";

    }
}

function ShowRaporDeskripsi($semester, $kelas, $nis)
{
    $sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
              FROM infonap i, nap n, aturannhb a, dasarpenilaian d
             WHERE i.replid = n.idinfo AND n.nis = '$nis' 
               AND i.idsemester = '$semester' 
               AND i.idkelas = '$kelas'
               AND n.idaturan = a.replid 	   
               AND a.dasarpenilaian = d.dasarpenilaian
               AND d.aktif = 1";
    $res = QueryDb($sql);
    $i = 0;
    while($row = mysqli_fetch_row($res))
    {
        $aspekarr[$i++] = array($row[0], $row[1]);
    }
    $naspek = count($aspekarr);
    $colwidth = $naspek == 0 ? "*" : round(55 / count($aspekarr)) . "%"; ?>
    <table width="100%" border="1" class="tab" id="table" bordercolor="#000000" style="border-collapse: collapse; border-width: 1px;">
    <tr>
        <td width="5%" class="headerlong"><div align="center">No</div></td>
        <td width="25%" class="headerlong"><div align="center">Pelajaran</div></td>
        <td width="15%" class="headerlong"><div align="center">Aspek</div></td>
        <td width="*" class="headerlong"><div align="center">Deskripsi</div></td>
    </tr>
<?php $sql = "SELECT pel.replid, pel.nama, pel.idkelompok, kpel.kelompok
              FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel, kelompokpelajaran kpel 
             WHERE uji.replid = niluji.idujian 
               AND niluji.nis = sis.nis 
               AND uji.idpelajaran = pel.replid 
               AND pel.idkelompok = kpel.replid
               AND uji.idsemester = $semester
               AND uji.idkelas = $kelas
               AND sis.nis = '$nis' 
             GROUP BY kpel.urutan, pel.nama";
    $respel = QueryDb($sql);
    $previdkpel = 0;
    $no = 0;
    while($rowpel = mysqli_fetch_row($respel))
    {
        $no += 1;
        $idpel = $rowpel[0];
        $nmpel = $rowpel[1];
        $idkpel = $rowpel[2];
        $nmkpel = $rowpel[3];

        if ($idkpel != $previdkpel)
        {
            $previdkpel = $idkpel;
            echo "<tr style='height: 30px'>
              <td colspan='4' align='left' style='font-size:12px; font-weight: bold; background-color: #ddd'>$nmkpel</td>
              </tr>";
        }

        echo "<tr height='40'>";
        echo "<td align='center' rowspan='$naspek' valign='middle' style='background-color: #f5f5f5;'>$no</td>";
        echo "<td align='left' rowspan='$naspek' valign='middle'>$nmpel</td>";

        $set_tr = false;
        for($i = 0; $i < count($aspekarr); $i++)
        {
            $asp = $aspekarr[$i][0];
            $nmasp = $aspekarr[$i][1];

            $komentar = "";

            $sql = "SELECT nilaiangka, nilaihuruf, komentar
                  FROM infonap i, nap n, aturannhb a 
                 WHERE i.replid = n.idinfo 
                   AND n.nis = '$nis' 
                   AND i.idpelajaran = '$idpel' 
                   AND i.idsemester = '$semester' 
                   AND i.idkelas = '$kelas'
                   AND n.idaturan = a.replid 	   
                   AND a.dasarpenilaian = '".$asp."'";
            $res = QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
            {
                $row = mysqli_fetch_row($res);
                $komentar = $row[2];
            }

            if ($set_tr)
                echo "<tr height='40'>";

            echo "<td align='left' valign='middle' style='font-size: 12px'>$nmasp</td>
              <td align='left' valign='middle'>$komentar</td>";
            echo "</tr>";

            $set_tr = true;
        }
    }
    echo "</table>";
}

function ShowRaporColumn($semester, $kelas, $nis)
{
    $sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
              FROM infonap i, nap n, aturannhb a, dasarpenilaian d
             WHERE i.replid = n.idinfo AND n.nis = '$nis' 
               AND i.idsemester = '$semester' 
               AND i.idkelas = '$kelas'
               AND n.idaturan = a.replid 	   
               AND a.dasarpenilaian = d.dasarpenilaian
               AND d.aktif = 1";

    $res = QueryDb($sql);
    $i = 0;
    while($row = mysqli_fetch_row($res))
    {
        $aspekarr[$i++] = array($row[0], $row[1]);
    }
    $naspek = count($aspekarr);
    $colwidth = $naspek == 0 ? "*" : round(50 / count($aspekarr)) . "%";
    ?>
    <table width="100%" border="1" class="tab" id="table" bordercolor="#000000" style="border-collapse: collapse; border-width: 1px;">
        <tr>
            <td width="5%" rowspan="2" class="header"><div align="center">No</div></td>
            <td width="35%" rowspan="2" class="header"><div align="center">Pelajaran</div></td>
            <td width="10%" rowspan="2" class="header"><div align="center">KKM</div></td>
            <?php 	for($i = 0; $i < count($aspekarr); $i++)
                echo "<td class='header' colspan='3' align='center' width='$colwidth'>" . $aspekarr[$i][1] . "</td>"; ?>
        </tr>
        <tr>
<?php       $colwidth = $naspek == 0 ? "*" : round(50 / (2 * $naspek)) . "%";
          for($i = 0; $i < count($aspekarr); $i++)
              echo "<td class='header' align='center' width='7%'>Nilai</td>
                    <td class='header' align='center' width='7%'>Predikat</td>"; ?>
        </tr>

        <?php $sql = "SELECT pel.replid, pel.nama, pel.idkelompok, kpel.kelompok
                      FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel, kelompokpelajaran kpel 
                     WHERE uji.replid = niluji.idujian 
                       AND niluji.nis = sis.nis 
                       AND uji.idpelajaran = pel.replid 
                       AND pel.idkelompok = kpel.replid
                       AND uji.idsemester = $semester
                       AND uji.idkelas = $kelas
                       AND sis.nis = '$nis' 
                     GROUP BY kpel.urutan, pel.nama";
        $respel = QueryDb($sql);
        $previdkpel = 0;
        $no = 0;
        while($rowpel = mysqli_fetch_row($respel))
        {
            $no += 1;

            $idpel = $rowpel[0];
            $nmpel = $rowpel[1];
            $idkpel = $rowpel[2];
            $nmkpel = $rowpel[3];

            if ($idkpel != $previdkpel)
            {
                $previdkpel = $idkpel;
                $colspan = $naspek * 2 + 3;
                echo "<tr style='height: 30px'>
                  <td colspan='$colspan' align='left' style='font-size:12px; font-weight: bold; background-color: #ddd'>$nmkpel</td>
                  </tr>";
            }

            $sql = "SELECT nilaimin 
                      FROM infonap
                     WHERE idpelajaran = $idpel
                       AND idsemester = $semester
                       AND idkelas = $kelas";
            $res = QueryDb($sql);
            $row = mysqli_fetch_row($res);
            $nilaimin = $row[0];

            echo "<tr height='30'>";
            echo "<td align='center' valign='middle' style='background-color: #f5f5f5'>$no</td>";
            echo "<td align='left' valign='middle'>$nmpel</td>";
            echo "<td align='center' valign='middle' style='font-size: 12px'>$nilaimin</td>";

            for($i = 0; $i < count($aspekarr); $i++)
            {
                $asp = $aspekarr[$i][0];

                $na = "";
                $nh = "";
                $komentar = "";

                $sql = "SELECT nilaiangka, nilaihuruf, komentar
                  FROM infonap i, nap n, aturannhb a 
                 WHERE i.replid = n.idinfo 
                   AND n.nis = '$nis' 
                   AND i.idpelajaran = '$idpel' 
                   AND i.idsemester = '$semester' 
                   AND i.idkelas = '$kelas'
                   AND n.idaturan = a.replid 	   
                   AND a.dasarpenilaian = '".$asp."'";
                $res = QueryDb($sql);
                if (mysqli_num_rows($res) > 0)
                {
                    $row = mysqli_fetch_row($res);
                    $na = $row[0];
                    $nh = $row[1];
                    $komentar = $row[2];
                }
                echo "<td align='center' valign='middle' style='font-size: 12px'><strong>$na</strong></td>
                      <td align='center' valign='middle' style='font-size: 12px'><strong>$nh</strong></td>";
            }

            echo "</tr>";
        }
        ?>
    </table>
    <?php
}

function ShowRaporRow($semester, $kelas, $nis)
{
	$NTT = new NumberToText(); ?>
    <table width="100%" border="1" class="tab" bordercolor="#000000">
    <tr>
        <td width="4%" rowspan="2" class="header"><div align="center">No</div></td>
        <td width="12%" rowspan="2" class="header"><div align="center">Pelajaran</div></td>
        <td width="7%" rowspan="2" class="header"><div align="center">KKM</div></td>
        <td width="12%" rowspan="2" class="header"><div align="center">Aspek<br>Penilaian</div></td>
        <td width="35%" colspan="3" class="header"><div align="center">Nilai</div></td>
    </tr>
    <tr>
        <td width="5%" class="header"><div align="center">Angka</div></td>
        <td width="5%" class="header"><div align="center">Huruf</div></td>
        <td width="15%" class="header"><div align="center">Terbilang</div></td>
    </tr>
   
<?php 	$sql = "SELECT pel.replid, pel.nama
              FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel 
             WHERE uji.replid = niluji.idujian 
               AND niluji.nis = sis.nis 
               AND uji.idpelajaran = pel.replid 
               AND uji.idsemester = '$semester'
               AND uji.idkelas = '$kelas'
               AND sis.nis = '$nis' 
         GROUP BY pel.nama";    
    $res = QueryDb($sql);
    $i = 0;
    while($row = mysqli_fetch_row($res))
    {
        $pelarr[$i++] = array($row[0], $row[1]);
    }
    
    for($i = 0; $i < count($pelarr); $i++)
    {
        $idpel = $pelarr[$i][0];
        $nmpel = $pelarr[$i][1];
        
        $sql = "SELECT nilaimin 
                 FROM infonap
                WHERE idpelajaran = '$idpel'
                  AND idsemester = '$semester'
                  AND idkelas = '".$kelas."'";
        $res = QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $nilaimin = $row[0];
        
        $sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan 
                FROM infonap i, nap n, aturannhb a, dasarpenilaian d 
               WHERE i.replid = n.idinfo AND n.nis = '$nis' 
                 AND i.idpelajaran = '$idpel' 
                 AND i.idsemester = '$semester' 
                 AND i.idkelas = '$kelas' 
                 AND n.idaturan = a.replid  	   
                 AND a.dasarpenilaian = d.dasarpenilaian
                 AND d.aktif = 1";
        $res = QueryDb($sql);				 
        $aspekarr = array();				 
        $j = 0;
        while($row = mysqli_fetch_row($res))
        {
            $na = "";
            $nh = "";
            $asp = $row[0];
            
            $sql = "SELECT nilaiangka, nilaihuruf
                      FROM infonap i, nap n, aturannhb a 
                     WHERE i.replid = n.idinfo 
                       AND n.nis = '$nis' 
                       AND i.idpelajaran = '$idpel' 
                       AND i.idsemester = '$semester' 
                       AND i.idkelas = '$kelas'
                       AND n.idaturan = a.replid 	   
                       AND a.dasarpenilaian = '".$asp."'";
            $res2 = QueryDb($sql);
            if (mysqli_num_rows($res2) > 0)
            {
                $row2 = mysqli_fetch_row($res2);
                $na = $row2[0];
                $nh = $row2[1];
            }
            
            $aspekarr[$j++] = array($row[0], $row[1], $na, $nh);
        } 
        $naspek = count($aspekarr);
        
        if ($naspek > 0)
        { ?>
            <tr height="20">
                <td rowspan="<?=$naspek?>" align="center"><?=$i + 1?></td>
                <td rowspan="<?=$naspek?>" align="left"><?=$nmpel?></td>
                <td rowspan="<?=$naspek?>" align="center"><?=$nilaimin?></td>
                <td align="left"><?=$aspekarr[0][1]?></td>
                <td align="center"><?=$aspekarr[0][2]?></td>
                <td align="center"><?=$aspekarr[0][3]?></td>
                <td align="left"><?=$NTT->Convert($aspekarr[0][2])?></td>
            </tr>
<?php 		for($k = 1; $k < $naspek; $k++)
            { ?>
                <tr height="20">
                    <td align="left"><?=$aspekarr[$k][1]?></td>
                    <td align="center"><?=$aspekarr[$k][2]?></td>
                    <td align="center"><?=$aspekarr[$k][3]?></td>
                    <td align="left"><?=$NTT->Convert($aspekarr[$k][2])?></td>
                </tr>
<?php 		} // end for
        } 
        else
        { ?>
            <tr height="20">
                <td align="center"><?=$i + 1?></td>
                <td align="left"><?=$nmpel?></td>
                <td align="center"><?=$nilaimin?></td>
                <td align="left">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
            </tr>		
<?php 	}// end if
    } 
	 echo "</table>";
}
?>

<?php
CloseDb();
?>