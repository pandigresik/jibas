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
require_once('../../include/errorhandler.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/common.php');
require_once('../../include/db_functions.php');
require_once("../../include/sessionchecker.php");

OpenDb();
$disable = "";
//$kalender = $_REQUEST['kalender'];
$replid = $_REQUEST['replid'];

//$sql = "SELECT a.replid, a.kegiatan, DAY(a.tanggalawal) AS tanggal1, DAY(a.tanggalakhir) AS tanggal2, MONTH(a.tanggalawal) AS bulan1, YEAR(a.tanggalawal) AS tahun1, MONTH(a.tanggalakhir) AS bulan2, YEAR(a.tanggalakhir) AS tahun2, a.idkalender, a.tanggalawal, a.tanggalakhir, a.keterangan, t.tglmulai, t.tglakhir, MONTH(t.tglmulai) AS bulmulai, YEAR(t.tglmulai) AS thnmulai, MONTH(t.tglakhir) AS bulakhir, YEAR(t.tglakhir) AS thnakhir, k.departemen, k.kalender FROM aktivitaskalender a, kalenderakademik k, tahunajaran t WHERE a.idkalender = k.replid AND a.replid = $replid AND k.idtahunajaran = a.replid";
$sql = "SELECT a.replid, a.kegiatan, a.idkalender, a.tanggalawal, a.tanggalakhir, a.keterangan, t.tglmulai, t.tglakhir, k.departemen, k.kalender FROM aktivitaskalender a, kalenderakademik k, tahunajaran t WHERE a.idkalender = k.replid AND a.replid = '$replid' AND k.idtahunajaran = t.replid";

//echo $sql;
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$departemen =  $row['departemen'];
$kalender = $row['kalender'];
$periode =  LongDateFormat($row['tglmulai']).' s/d '. LongDateFormat($row['tglakhir']);
/*$bulan1 = $row['bulan1'];
$tahun1 = $row['tahun1'];
$bulan2 = $row['bulan2'];
$tahun2 = $row['tahun2'];
$tanggal1 = $row['tanggal1'];
$tanggal2 = $row['tanggal2'];
*/
$idkalender = $row['idkalender'];
$kegiatan = $row['kegiatan'];
$keterangan = $row['keterangan'];
/*$blnawal = $row['bulmulai'];
$thnawal = $row['thnmulai'];
$blnakhir = $row['bulakhir'];
$thnakhir = $row['thnakhir'];*/


$jgk1 = explode('-',(string) $row['tanggalawal']);
$jgk2 = explode('-',(string) $row['tanggalakhir']);

$jgk3 = explode('-',(string) $row['tglmulai']);
$jgk4 = explode('-',(string) $row['tglakhir']);

$jangka_waktu = '('.$jgk1[2].'/'.$jgk1[1].'/'.substr($jgk1[0],2,2).' - '.$jgk2[2].'/'.$jgk2[1].'/'.substr($jgk2[0],2,2).')';

$tanggal1 = $jgk1[2];
$bulan1 = $jgk1[1];
$tahun1 = $jgk1[0];
$tanggal2 = $jgk2[2];
$bulan2 = $jgk2[1];
$tahun2 = $jgk2[0];

$blnawal = $jgk3[1];
$thnawal = $jgk3[0];
$blnakhir = $jgk4[1];
$thnakhir = $jgk4[0];


$bulan = $bulan1;
if (isset($_REQUEST['bulan']))
	$bulan = $_REQUEST['bulan'];

$tahun = $tahun1;
if (isset($_REQUEST['tahun']))
	$tahun = $_REQUEST['tahun'];
if (isset($_REQUEST['aktif']))
	$aktif = $_REQUEST['aktif'];

if ($tahun2 == $tahun && $bulan2 == $bulan) 
	$aktif = 1;
if ($tahun1 == $tahun && $bulan1 == $bulan)
	$aktif = 0;

$next = 1;
if (isset($_REQUEST['next']))
	$next = $_REQUEST['next'];

$last = 0;
if (isset($_REQUEST['last']))
	$last = $_REQUEST['last'];

if ($bulan == $blnakhir && $tahun == $thnakhir) {
	$next = 0;
	$disable = 'disabled="disabled"';
}

if ($bulan == $blnawal && $tahun == $thnawal)
	$last = 1;

$tmp = $tahun."-".$bulan."-1";
$sql = "SELECT DAYOFWEEK('$tmp')";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$first_weekday_this_month = $row[0];

if ($bulan == 12) {
	$next_month = 1;
	$next_year = $tahun + 1;
} else {
	$next_month = $bulan + 1;
	$next_year = $tahun;
}


if ($bulan == 1) {
	$last_month = 12;
	$last_year = $tahun - 1;
	
	$tmp = ($tahun - 1) . "-12-1";
} else {
	$last_month = $bulan - 1;
	$last_year = $tahun;
	$tmp = $tahun . "-" . ($bulan - 1) . "-1";
}	


$sql = "SELECT DAY(LAST_DAY('$tmp'))";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$last_day_last_month = $row[0];

$now = $tahun . "-" . $bulan . "-1";
$sql = "SELECT DAY(LAST_DAY('$now'))";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$last_day_this_month = $row[0];

$nweek = 0;
$nday = 0;
for ($i = 0; $i < ($first_weekday_this_month - 1); $i++) {
	$cal[$nweek][$nday][0] = $last_day_last_month - ($first_weekday_this_month - 1) + ($i + 1);
	$cal[$nweek][$nday][1] = $last_month;
	$cal[$nweek][$nday][2] = $last_year;
	
	$nday++;
	//echo $cal[$nweek][$nday] . "<br>";
}

for ($i = 1; $i <= $last_day_this_month; $i++) {
	$cal[$nweek][$nday][0] = $i;
	$cal[$nweek][$nday][1] = $bulan;
	$cal[$nweek][$nday][2] = $tahun;
	
	if ($nday == 6) {
		$nday = 0;
		$nweek++;
	} else {
		$nday++;
	}
}

if (($nday > 0) && ($nday < 7)) {
	$start = 1;
	for ($i = $nday; $i < 7; $i++) {
		$cal[$nweek][$i][0] = $start++;
		$cal[$nweek][$i][1] = $next_month;
		$cal[$nweek][$i][2] = $next_year;
	}
}

function loadKalender1($kalender, $id) {
	OpenDb();
	$sql = "SELECT replid, kegiatan, tanggalawal, tanggalakhir, MONTH(tanggalawal), MONTH(tanggalakhir), DAY(tanggalawal), DAY(tanggalakhir), YEAR(tanggalawal), YEAR(tanggalakhir) FROM aktivitaskalender WHERE idkalender = '$kalender' ORDER BY tanggalawal";
	$result = QueryDb($sql);
	$i = 0;	
	while($row = mysqli_fetch_row($result)) {		
		$tgl1 = explode('-',(string) $row[2]);
		$tgl2 = explode('-',(string) $row[3]);
		$jangka = '('.$tgl1[2].'/'.$tgl1[1].'/'.substr($tgl1[0],2,2).' - '.$tgl2[2].'/'.$tgl2[1].'/'.substr($tgl2[0],2,2).')';
		//$jangka = '('.$row[6].'/'.$row[4].'/'.substr($row[8],2,2).' - '.$row[7].'/'.$row[5].'/'.substr($row[9],2,2).')';
		
		$GLOBALS['keg']['row'][$i]['id'] = $row[0];				
		$GLOBALS['keg']['row'][$i]['judul'] = $row[1];				
		$GLOBALS['keg']['row'][$i][\TANGGAL1] = $tgl1[2];
		$GLOBALS['keg']['row'][$i][\BULAN1] = $tgl1[1];				
		$GLOBALS['keg']['row'][$i][\TAHUN1] = $tgl1[0];
		$GLOBALS['keg']['row'][$i][\TANGGAL2] = $tgl2[2];
		$GLOBALS['keg']['row'][$i][\BULAN2] = $tgl2[1];
		$GLOBALS['keg']['row'][$i][\TAHUN2] = $tgl2[0];
		$GLOBALS['keg']['row'][$i]['jangka'] = $jangka;
		
		if ($id == $row[0]) {
			$GLOBALS['urutan'] = $i;
		}
			
		++$i;
	}
	return true;
}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../style/style.css" />
<link rel="stylesheet" href="../../style/tooltips.css">
<title>Kalender</title>

<style>
.thismonth {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 20px;
	font-weight: bold;
}

.othermonth {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 14px;
	font-weight: bold;
	color:#999999;
}
</style>

<script language="javascript" src="../script/ajax.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language="javascript">
function GoToLastMonth() {
	var replid = document.getElementById('replid').value;
	var aktif = document.getElementById('lastaktif').value;
	
	document.location.href = "kalender_detail.php?bulan=<?=$last_month?>&tahun=<?=$last_year?>&replid="+replid+"&aktif="+aktif;
	//document.location.href = "kalender_detail.php?bulan=<?=$last_month?>&tahun=<?=$last_year?>&replid="+replid;
}

function GoToNextMonth() {
	var replid = document.getElementById('replid').value;
	var aktif = document.getElementById('nextaktif').value;
	
	document.location.href = "kalender_detail.php?bulan=<?=$next_month?>&tahun=<?=$next_year?>&replid="+replid+"&aktif="+aktif;
	//document.location.href = "kalender_detail.php?bulan=<?=$next_month?>&tahun=<?=$next_year?>&replid="+replid;
}

function tampil(replid) {
	//var aktif = document.getElementById('nextaktif').value;
	
	document.location.href = "kalender_detail.php?replid="+replid;
	//document.location.href = "kalender_detail.php?bulan=<?=$next_month?>&tahun=<?=$next_year?>&replid="+replid;
}
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF">
<input type="hidden" name="replid" id="replid" value="<?=$replid?>" />
<input type="hidden" name="last" id="last" value="<?=$last?>" />
<input type="hidden" name="next" id="next" value="<?=$next?>" />
<input type="hidden" name="lastaktif" id="lastaktif" value="<?=$aktif ?>">	
<table border="0" width="95%" align="center">
<!-- TABLE CONTENT -->
<tr height="25">
	<td height="25" colspan="3" class="header"><div align="center">Kalender Akademik</div></td>
</tr>
<tr height="488">
	<td valign="top">
    <table width="100%" border="0">
	<tr height="20">
        <td width="30%"><strong>Departemen</strong></td>
        <td width="*"><?=$departemen?></td>
        <!--<td rowspan="6" valign="top" height="600">-->
   	<tr height="20">
        <td><strong>Kalender Akademik</strong></td>
        <td><?=$kalender?></td>
    </tr> 
    <tr height="20">
        <td><strong>Periode</strong></td>
        <td><?=$periode?></td>
    </tr>
    <tr> 
        <td><strong>Bulan Kegiatan </strong></td> 
        <td>
        <?php if ($last == 0) { ?>
        <input type="button" class="but" onClick="GoToLastMonth()" value="  <  ">
        <?php }?>
        <select name="bulan" id="bulan" onChange="ChangeCal()" <?=$disable?>>
            <?php 	for ($i=1;$i<=12;$i++) { ?>
            <option value="<?=$i?>" <?=IntIsSelected($bulan, $i)?>><?=NamaBulan($i)?></option>	
            <?php } ?>
        </select>
        <select id="tahun" name="tahun" onChange="ChangeCal()" <?=$disable?>>
        <?php $YNOW = date('Y');
           //for ($i = $tahun1; $i <= $tahun2; $i++) { 
			for ($i = $tahun1; $i <= $thnakhir; $i++) {   
		?>
            <option value="<?=$i?>" <?=IntIsSelected($i, $tahun)?>><?=$i?></option>
        <?php } ?>
        </select> 
               
        <?php if ($next == 1) {		
        ?> 
        <input type="button" class="but" onClick="GoToNextMonth()" value="  >  ">
        <?php } ?>
        </td>    
    </tr>
    <tr height="250">
		<td colspan="2" valign="top" >
    	<table border="0" cellpadding="5" cellspacing="1" style="border-color:#999999" >        
        <tr height="30" bgcolor="#DFFFDF">
            <td width="55" align="center" style="background-color:#990000; color:#FFFFFF"><b>Minggu</b></td>
            <td width="55" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Senin</b></td>
            <td width="55" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Selasa</b></td>
            <td width="55" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Rabu</b></td>
            <td width="55" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Kamis</b></td>
            <td width="55" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Jumat</b></td>
            <td width="55" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Sabtu</b></td>
        </tr>
        <?php
		
		
        for ($i = 0; $i < count($cal); $i++) { 
            echo "<tr height='55'>";
            for ($j = 0; $j < count($cal[$i]); $j++) {
                $tgl = $cal[$i][$j][0];
                $bln = $cal[$i][$j][1];
                $thn = $cal[$i][$j][2];
                
                $tanggal = "$thn-$bln-$tgl";
								                
                if (($bln == $bulan) && ($thn == $tahun))
                    $style = "thismonth";
                else
                    $style = "othermonth";
                
                if ($j == 0)
                    $color = "#FFCCCC";
				else
                    $color = "#DFEFFF";
                //elseif ($j == 5) 
                //    $color = "#DFFFDF";
             
				
				$tglnow = $thn.'-'.$bln.'-'.$tgl;
				$sql1 = "SELECT * FROM aktivitaskalender WHERE '$tglnow' BETWEEN tanggalawal AND tanggalakhir ".
						"AND replid = '$replid' ";
				$result1 = QueryDb($sql1);
				$jum1 = mysqli_num_rows($result1);	
				if ($jum1 > 0)
					echo "<td align='center' valign='middle' style='background-color: #FFFFAA'>";
				else 
					echo "<td align='center' valign='middle' style='background-color: $color'>";
				
			   	for ($m=0;$m<6;$m++) {					
					$id = $warna[$m];
					if (isset($id)) {
					//if ($warna[$m] == 1) {
						//$style_in[$m] = "style='background-color: {$color_in[$m]}'";
						if (($bln == $bulan) && ($thn == $tahun)) {
                    		if (($keg['row'][$id][\TANGGAL1]==$tgl && $bln==$keg['row'][$id][\BULAN1] && $thn==$keg['row'][$id][\TAHUN1])){
                        	    $style_in[$m] = "";    
								//$style_in[$m] = "style='background-color: {$color_in[$m]}'";
                    		}
                    		if (($keg['row'][$id][\TANGGAL2]<$tgl && $bln==$keg['row'][$id][\BULAN2] && $thn==$keg['row'][$id][\TAHUN2])){
                        		$style_in[$m] = "";
							}
                		}		
					} else {
					  	$style_in[$m] = "";
					}
				} 
                
				echo "<font class='$style'>$tgl</font>";
                
               /* echo "<table border='0' width='55' height ='55' cellpadding='1' cellspacing='2'>";
                echo "<tr height='20' align='center'><td colspan='3'>";
                echo "<font class='$style'>$tgl</font><br></td>";
				echo "</td></tr>";
                echo "<tr height='10' align='center'>";
				echo "<td {$style_in[3]} width='10'></td>";
				echo "<td {$style_in[4]} width='10'></td>";
				echo "<td {$style_in[5]} width='10'></td></tr>";
				echo "<tr height='10' align='center'>";
				echo "<td {$style_in[0]}></td>";
				echo "<td {$style_in[1]}></td>";
				echo "<td {$style_in[2]}></td>";
				echo "</tr>";
                echo "</table>";
                */
                //echo "<font size='2' style='background-color: green; color: yellow; '> ada";
               
                echo "</td>";
            }
            echo "</tr>";
        }
        CloseDb();
        ?>
        <input type="hidden" name="nextaktif" id="nextaktif" value="<?=$aktif ?>">	
    	</table>
        </td>
    </tr>
    </table>
    </td>
	<td valign="top" height="150">
    <fieldset><legend><b>Kegiatan <?php ///NamaBulan($bulan).' '.$tahun?>  </b></legend>
    <!--<div style="overflow: auto">--->
    <div style="overflow:auto; height:450px">
    <table border="0" cellpadding="3" cellspacing="2" width="175" >
   	<tr>
    	<td style="background-color: #FFFFAA" >
        	<b><font size="4"><?=$kegiatan?></font><br><?=$jangka_waktu?></b></td> 
    </tr>
   
    <?php //foreach($acara as $k => $v) {
        /*$GLOBALS['keg']['row'][$i]['id'] = $row[0];				
        $GLOBALS['keg']['row'][$i]['judul'] = $row[1];				
        $GLOBALS['keg']['row'][$i][tanggal1] = $tgl1[2];
        $GLOBALS['keg']['row'][$i][bulan1] = $tgl1[1];
        $GLOBALS['keg']['row'][$i][tahun1] = $tgl1[0];
        $GLOBALS['keg']['row'][$i][tanggal2] = $tgl2[2];
        $GLOBALS['keg']['row'][$i][bulan2] = $tgl2[1];
        $GLOBALS['keg']['row'][$i][tahun2] = $tgl2[0];
        */
		//$color_in = array("#FFCC33","#C7C6EA","#FF8e8e","#9DD7CB","#f2ade4","#C1E6AC");
		$color_in = ["#C1E6AC", "#f2ade4", "#9DD7CB", "#FF8e8e", "#C7C6EA", "#FFCC33"];
		loadKalender1($idkalender);
		$cnt = 0;
		$clr = 0;
		
		
        for ($i=0;$i<sizeof($keg['row']);$i++) { 
			if ($keg['row'][$i]['id'] <> $replid) {
					
		$style = "";
			
			if (($keg['row'][$i][\TAHUN1] == $tahun) && ($keg['row'][$i][\BULAN1] <= $bulan)) {
				//$style =  'style="background-color: '.$color_in[$clr].'"';
				$style = 'style = "color:#FF0000;font-weight: bold;"';			
				$warna[$clr] = $i;
				++$clr;			
			}
			
			if ($keg['row'][$i][\TAHUN1] < $tahun) { 
				if ($keg['row'][$i][\TAHUN2] == $tahun && $keg['row'][$i][\BULAN2] >= $bulan){
				//$style =  'style="background-color: '.$color_in[$clr].'"';
				$style = 'style = "color:#FF0000;font-weight: bold;"';
				$warna[$clr] = $i;
				++$clr;			
			}
		}
		?>
    <tr height="20">
    	<td <?=$style?> onclick="tampil(<?=$keg['row'][$i]['id']?>)" style="cursor:pointer">
		<?=$keg['row'][$i]['judul'].'<br>'.$keg['row'][$i]['jangka']?>
		<?php //echo '<br>'.$keg['row'][$i][tahun1].' = '.$tahun.' dan '.$keg['row'][$i][bulan1].' = '.$bulan?>     
   		</td>
    </tr>   
<?php   
		}     
    }
	?>
	</table>
    </div>
    </fieldset>
    </td>
</tr>
<tr>
	<td colspan="2">
     <fieldset><legend><b>Keterangan</b></legend>
    	<table width="100%">
        	<tr height="60"><td valign="top"><?=$keterangan?></td></tr>
        </table>
    </fieldset>
</td>
</tr>
</table>

</body>
</html>