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
require_once('../../include/common.php');
require_once('../../include/config.php');
require_once('../../include/getheader.php');
require_once('../../include/db_functions.php');

OpenDb();

$kalender = $_REQUEST['kalender'];
$sql_kalender = "SELECT MONTH(t.tglmulai), YEAR(t.tglmulai), MONTH(t.tglakhir), YEAR(t.tglakhir), k.kalender,
						t.tglmulai, t.tglakhir, k.departemen, k.aktif, k.terlihat
				   FROM jbsakad.kalenderakademik k, jbsakad.tahunajaran t
				  WHERE k.replid='$kalender' AND k.idtahunajaran = t.replid";
$result = QueryDb($sql_kalender);
$row = mysqli_fetch_row($result);
$departemen = $row[7];
$akademik = $row[4];
$periode = TglTextLong($row[5]).' s/d '.TglTextLong($row[6]);

if ($row[8] == 1) 
	$aktif = 'Aktif';
else
	$aktif = 'Tidak Aktif';
			
if ($row[9] == 1) 
	$terlihat = 'Terlihat';
else
	$aktif = 'Tidak Terlihat';
	
$bulan1 = $row[0];
$tahun1 = $row[1];
$bulan2 = $row[2];
$tahun2 = $row[3];
		
$bln = $bulan1;
//if (isset($_REQUEST['bln']))
//	$bln = $_REQUEST['bln'];

$thn = $tahun1;
//if (isset($_REQUEST['thn']))
//	$thn = $_REQUEST['thn'];
$prevbln = $bulan1;
//if (isset($_REQUEST['prevbln']))
//	$prevbln = $_REQUEST['prevbln'];
$prevthn = $tahun1;
//if (isset($_REQUEST['prevthn']))
//	$prevthn = $_REQUEST['prevthn'];
$next = 0;
if (isset($_REQUEST['next']))
	$next = $_REQUEST['next'];	
		
$last = 0;
$tahun = $thn;
$bul = $bln+5;
if ($bln > 6) {
	$bul = ($bln+5)-12;
	$tahun = $thn+1;
}

//echo "Masuk bln ".$bln." bul ".$bul." bulan2 ".$bulan2." tahun ".$tahun." tahun2 ".$tahun2;

if ($bul >= $bulan2 && $tahun >= $tahun2) {	
	$last = 1;
	//echo "Masuk bul >= bulan2 & tahun >= tahun2";
}

if ($bln == $bulan1 && $thn == $tahun1) {
	$next = 0;
	//Masuk bln == bulan1 & thn == tahun1
}
		

$color = ["#afafaf", "#000000"];

function loadKalender1($kalender)
{
	$sql = "SELECT replid, kegiatan, tanggalawal, tanggalakhir, MONTH(tanggalawal), MONTH(tanggalakhir), DAY(tanggalawal),
				   DAY(tanggalakhir), YEAR(tanggalawal), YEAR(tanggalakhir)
			  FROM aktivitaskalender
			 WHERE idkalender = '$kalender' ORDER BY tanggalawal"; 
	
	$result = QueryDb($sql);
	$i = 0;	
	while($row = mysqli_fetch_row($result))
	{		
		$tgl1 = explode('-',(string) $row[2]);
		$tgl2 = explode('-',(string) $row[3]);
		$awal = $tgl1[2].'/'.$tgl1[1].'/'.substr($tgl1[0],2,2).' - '.$tgl2[2].'/'.$tgl2[1].'/'.substr($tgl2[0],2,2);
		
		$GLOBALS['keg']['row'][$i]['id'] = $row[0];				
		$GLOBALS['keg']['row'][$i]['judul'] = $row[1];				
		$GLOBALS['keg']['row'][$i]['tanggal'] = $awal;
		++$i;
	}
	return true;
}	

function loadKalender2($kalender, $bulan1, $tahun1, $bulan2, $tahun2)
{
	global $keg;
	$batastgl1 = $tahun1."-".$bulan1."-1";
	$batastgl2 = $tahun2."-".$bulan2."-31";
	
	$sql = "SELECT replid, kegiatan, tanggalawal, tanggalakhir, MONTH(tanggalawal), MONTH(tanggalakhir),
				   DAY(tanggalawal), DAY(tanggalakhir), YEAR(tanggalawal), YEAR(tanggalakhir)
			  FROM aktivitaskalender
			 WHERE idkalender = '$kalender'
			   AND (('$batastgl1' BETWEEN tanggalawal AND tanggalakhir) OR ('$batastgl2' BETWEEN tanggalawal AND tanggalakhir) OR (tanggalawal BETWEEN '$batastgl1' AND '$batastgl2') OR (tanggalakhir BETWEEN '$batastgl1' AND '$batastgl2')) ORDER BY tanggalawal";  
	$result = QueryDb($sql);
	
	while($row = mysqli_fetch_row($result)) {
				
		if ($row[6]<= 7)
			$awal = 1;				
		if (7 < $row[6] && $row[6]<= 14) 
			$awal = 2;
		if (14 < $row[6] && $row[6]<= 21) 
			$awal = 3;
		if (21 < $row[6]) 
			$awal = 4;
		
		if ($row[7] <= 7)
			$akhir = 1;				
		if (7 < $row[7] && $row[7] <= 14) 
			$akhir = 2;
		if (14 < $row[7] && $row[7] <= 21) 
			$akhir = 3;
		if (21 < $row[7]) 
			$akhir = 4;	
		
		//echo "<br>akhir ".$akhir;
		$blnawal = $row[4];			
		if ($blnawal < $bulan1) {
			if ($row[9] == $tahun2 && $row[8] <> $tahun2) {
				$blnawal = $bulan1;						
				$awal = 1;
				//echo "<br>masuk sini1 ".$blnawal." ".$awal;
			} 
			if ($row[9] == $tahun1 && $row[8] == $tahun1) {
				$blnawal = $bulan1;						
				$awal = 1;
				//echo "<br>masuk sini2 ".$blnawal." ".$awal;
			}
			if ($row[9] == $tahun2 && $row[8] == $tahun2 && $tahun1 <> $tahun2) {
				$blnawal = $row[4] + 12;
				//echo "<br>masuk sini3 ".$blnawal." ".$awal;
			}
		} 
		
		$thnawal = $row[8];
		$thnakhir = $row[9];
		//echo "<br>".$row[9]." tahun1 ".$tahun1." tahun2 ".$tahun2;
		if ($row[9] == $tahun1 || $row[9] == $tahun2) {			
			$blnakhir = $row[5];
			if ($row[5] < $bulan1) {				
				$blnakhir = $row[5] + 12;
			}
			
			if ($row[8] <> $tahun1 && $row[8] <> $tahun2) {
				$blnakhir = $row[5];
				$blnawal = $bulan1;
				$awal = 1;
				//echo '<br> beda tahun awal '.$awal.' bulan1 '.$blnawal.' akhir '. $blnakhir;	
			}
			//echo '<br> tahun sama bulan '.$row[5].' bulan1 '.$bulan1.' akhir '. $blnakhir.' row '.$row[4];
		} else {
			$blnakhir = $bulan1 + 5;
			if ($row[8] == $tahun1 && $row[9] <> $tahun2)
				$akhir = 4;
				
			//echo '<br> tahun beda bulan '.$blnakhir. ' bulan1 '.$bulan1.' akhir '.$blnakhir.' bulanawal '.$blnawal.' row '.$row[4];
		}
				
		$kolom = ($blnawal-$bulan1)*4+$awal;
		$selisih = (($blnakhir-$bulan1)*4+$akhir-$kolom)+1;
		//echo "<br>((bulan akhir-bulan awal) x 4 + akhir - kolom) + 1";
		//echo '<br> bulan awal '.$bulan1.'<br> bulan akhir '.$blnakhir.'<br> kolom '.$kolom.'<br> selisih '.$selisih;
		//echo "<br><br> kolom: (blnawal-bulan1) x 4 + awal";
		//echo '<br> blnawal '.$blnawal. ' bulan1 '.$bulan1.' awal '.$awal.' kolom '.$kolom.' selisih '.$selisih;
		//echo '<br>'.(($blnakhir-$blnawal)*4+$akhir).' - '.$kolom;
		$tanggal = $row[6].'/'.$row[4].'/'.substr((string) $row[8],2,2).' - '.$row[7].'/'.$row[5].'/'.substr((string) $row[9],2,2);
		//$tanggal = $row[6].'/'.$row[4].' - '.$row[7].'/'.$row[5];
		if ($selisih == 0) {
			$selisih = 1;			
			$tanggal = $row[6].'/'.$row[4];
		} 
		
		if ($selisih > 1 && $selisih < 5 && $row[8] == $row[9]) {
			$tanggal = $row[6].'/'.$row[4].' - '.$row[7].'/'.$row[5];
			//$tanggal = $row[6].'/'.$row[4];
		}
		
		for ($j=0;$j< count($keg['row']); $j++) {
			if ($keg['row'][$j]['id'] == $row[0]) 				
				$baris = $j;
		}	
		
		$GLOBALS['jadwal']['row'][$row[0]][$baris][$kolom]['njam'] = $selisih;
		$GLOBALS['jadwal']['row'][$row[0]][$baris][$kolom]['awal'] = $tanggal;
	}
	return true;
}

function getCell1($r, $c, $id, $m) {
	global $mask, $jadwal, $color;	
	if($mask[$c] == 0) {
		if(isset($jadwal['row'][$id][$r][$c])) {	
			
			$mask[$c+1] = $jadwal['row'][$id][$r][$c]['njam'] - 1;
			
			$dt=explode("-",(string) $jadwal['row'][$id][$r][$c]['awal']);
			$dt1=explode("/",$dt[0]);
			$dt2=explode("/",$dt[1]);
								
			$s = "<td align='center' valign='middle' style='background-color: {$color[$m][1]}' colspan='{$jadwal['row'][$id][$r][$c]['njam']}'>";
			$s.= "<font class='thismonth'>".$dt1[0] - $dt2[0]."</font>";
			$s.= "</td>";
			
			return $s;
		} else {			
			$mask[$c+1] = 0;			
			$s = "<td align='center' valign='middle' style='background-color: #FFF'>";
			$s.= "</td>";
			return $s;
		}
	} else {
		$mask[$c+1] = $mask[$c]-1;	
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS InfoSiswa [Cetak Kalender Akademik]</title>
<script language="javascript" src="../../script/tools.js"></script>
<script type="text/javascript" language="javascript" src="../../javascript/tables.js"></script>
<script type="text/javascript" language="javascript" src="../../javascript/common.js"></script>

</head>

<body>
<input type="hidden" name="kalender" id="kalender" value="<?=$kalender ?>">
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top" >
 
<?=getHeader($departemen)?>

<center>
  <font size="4"><strong>Kalender Akademik</strong></font><br />
 </center><br /><br />
 	<table width="100%">    
	<tr>
		<td width="18%"><strong>Departemen</strong> </td> 
		<td width="*"><strong>:&nbsp;<?=$departemen?></strong></td>
	</tr>
    <tr>
		<td><strong>Kalender Akademik</strong></td>
		<td><strong>:&nbsp;<?=$akademik?></strong></td>        		
    </tr>
    <tr>
		<td><strong>Periode</strong></td>
		<td><strong>:&nbsp;<?=$periode?></strong></td>        		
    </tr>
    <tr>
		<td><strong>Status</strong></td>
		<td><strong>:&nbsp;<?=$aktif?> dan <?=$terlihat?></strong></td>
        <td align="right" width="320">
    	<?php if ($last == 0 && $next == 0) { ?>
        	&nbsp;
        <?php } else {
				if ($next == 1) {?>
    			<!--<input type="button" class="but" onClick="GoToPrevMonth()" value=" < Bulan sebelumnya " style="width:150px">-->
   				<?php 	} if ($next == 0 || $last == 0) {?>   
    			<!--<input type="button" class="but" onClick="GoToNextMonth()" value=" Bulan berikutnya > " style="width:150px">--> 
                <?php } ?>        
    	<?php }?></td>
    </td>        		
    </tr>
	</table>
	<br />

	<input type="hidden" name="kalender" id="kalender" value="<?=$kalender ?>">
<table border="0" width="100%" align="center" >
<!-- TABLE CENTER -->
<tr>
	<td>
    <?php 	$sql = "SELECT * FROM aktivitaskalender WHERE idkalender = '".$kalender."'";
		$result = QueryDb($sql);
		
		if (@mysqli_num_rows($result)>0){
	
    
	?>
    <table border="0" width="100%" align="center">
    <tr>
        <td width="*" align="right">
        </td>
        <td align="right" width="320">
        	<?php 	if ($next == 1) {?>
            		<!--<input type="button" class="but" onClick="GoToPrevMonth()" value=" < Bulan sebelumnya " style="width:150px">-->
        		<?php } 
				
				if ($next == 0 || $last == 0) {
						$batasthn = $thn;
						//echo "1_".$batasthn;
						for ($i=$bln;$i<=$bln+5;$i++) { 	
							$n = $i;
							if ($i > 12) {
								$n = $i-12;							
								$batasthn = $thn+1;
							}
						}
						
						$batas = $batasthn."-".$n."-31";
					
					
						//$sql = "SELECT MONTH(tanggalakhir) AS bulan, YEAR(tanggalakhir) AS tahun FROM aktivitaskalender WHERE idkalender = '$kalender' AND MONTH(tanggalakhir) < '$n' AND YEAR(tanggalakhir) = '".$batasthn."'"; 
						$sql = "SELECT * FROM aktivitaskalender 
								WHERE idkalender = '$kalender' AND tanggalakhir > '$batas'";
						
						$result = QueryDb($sql);
						if (mysqli_num_rows($result) > 0) {
			?>   
            		<!--<input type="button" class="but" onClick="GoToNextMonth()" value=" Bulan berikutnya > " style="width:150px">-->  
        			<?php } ?>
				<?php } ?>	
        </td>
    </tr>
    <!--<tr>
        <td align="center" valign="top" colspan="2">    -->
    </table>
    <br>
    <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-color:#000000; border-collapse:collapse; border:solid" align="center">
    <tr height="30">
        <td width="22%" align="center" style="border-color:#000000;color:#000000" rowspan="2" colspan="2">
        <b>Kegiatan</b></td>
        <?php 	$batasthn = $thn;			
            for ($i=$bln;$i<=$bln+5;$i++) { 	
                $n = $i;
                if ($i > 12) {
                    $n = $i-12;
                    $batasthn = $thn+1;
                } 
        ?>
        <td width="*" align="center" style="border-color:#000000;color:#000000" colspan="4">
            <b><?=$bulan[$n]."'".substr((string) $batasthn,2,2)?></b>
        <!--&nbsp;<a href="JavaScript:lihat()"><img src="../images/ico/lihat.png" border="0" /></a>-->
        </td>
        <?php 	} 
			
			if ($n == 12) {
				$nextbln = 1;
				$nextthn = $thn+1;
			} else {
				$nextbln = $n+1;
				$nextthn = $thn;
			}
		?>
    </tr>
    <tr>
        <?php 	
            for ($j=$bln;$j<=$bln+5;$j++) {								
                for ($p = 1; $p <=4; $p++) {
        ?>
        <td width="25" align="center" style="background-color:#afafaf; color:#000000"><?=$p?></td>
           
        <?php 	}
            } ?>
    </tr>
    <?php
    
    $mask = NULL;
    $s = 0;
    $mask[1] = 0;
        
    loadKalender1($kalender);
    ?> <input type="hidden" name="kegiatan" id="kegiatan" value="<?=$keg['row']?>"> <?php
   // echo $batasthn."_".$n;
	//loadKalender2($kalender,$nextbln,$nextthn,$n,$batasthn);
	loadKalender2($kalender,$bln,$thn,$n,$batasthn);
    if (isset($keg['row'])) {
        for ($i = 0; $i < count($keg['row']); $i++ ){
            $id = $keg['row'][$i]['id'];
            $m = $i;
            if ($i > count($color)-1) 
                $m = $i - ((count($color)-1)*(int)substr($i,0,1)+1);		
    
    ?>
    <tr height="25">
        <td style="background-color:#d8d8d8 ; color:#000000" align="center" width="5%"><b><?=($i+1).'. '?></b></td>
        <td style="background-color:#d8d8d8 ; color:#000000">
		<div style="margin-left:5px">
        <b><?=$keg['row'][$i]['judul'];
            //if (!isset($jadwal['row'][$id]))	
              //  echo '<br>'.$keg['row'][$i]['tanggal'];
            
            ?>
        </b>
        </div>
        </td>
            <?php 	
            for($j = 1; $j <=24 ; $j++) {	
                echo getCell1($i, $j, $id); 
            }
            ?>
        
    </tr>
<?php 	} ?>
<?php }?> 
	 </table>
	 <br>
<?php 	
		$bln = $nextbln;
		$thn = $nextthn;

		$tahun = $thn;
		$bul = $bln+5;
		if ($bln > 6) {
			$bul = ($bln+5)-12;
			$tahun = $thn+1;
		}
		
		//echo "Masuk bln ".$bln." bul ".$bul." bulan2 ".$bulan2." tahun ".$tahun." tahun2 ".$tahun2;
		
		/*if ($bul >= $bulan2 && $tahun >= $tahun2) {	
			$last = 1;
			//echo "Masuk bul >= bulan2 & tahun >= tahun2";
		}
		
		if ($nextbln == $bulan1 && $nextthn == $tahun1) {
			$next = 0;
			//Masuk bln == bulan1 & thn == tahun1
		}*/
		
	
	
	?>
	 <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-color:#000000; border-collapse:collapse; border:solid" align="center">
	 <tr height="30" >
        <td width="22%" align="center"  rowspan="2" colspan="2" style="border-color:#000000;color:#000000">
        <b>Kegiatan</b></td>
        <?php 	$batasthn = $thn;			
            for ($i=$bln;$i<=$bln+5;$i++) { 	
                $n = $i;
                if ($i > 12) {
                    $n = $i-12;
                    $batasthn = $thn+1;
                } 
        ?>
        <td width="*" align="center"  colspan="4" style="border-color:#000000;color:#000000">
            <b><?=$bulan[$n]."'".substr((string) $batasthn,2,2)?></b>
        <!--&nbsp;<a href="JavaScript:lihat()"><img src="../images/ico/lihat.png" border="0" /></a>-->
        </td>
        <?php 	} 
			
			/*if ($n == 12) {
				$nextbln = 1;
				$nextthn = $thn+1;
			} else {
				$nextbln = $n+1;
				$nextthn = $thn;
			}*/
		?>
    </tr>
    <tr>
        <?php 	
            for ($j=$bln;$j<=$bln+5;$j++) {								
                for ($p = 1; $p <=4; $p++) {
        ?>
        <td width="25" align="center" bgcolor="#AFAFAF"><?=$p?></td>
           
        <?php 	}
            } ?>
    </tr>
    <?php
    
    $mask = NULL;
    $s = 0;
    $mask[1] = 0;
        
    loadKalender1($kalender);
    ?> <input type="hidden" name="kegiatan" id="kegiatan" value="<?=$keg['row']?>"> <?php
    loadKalender2($kalender,$bln,$thn,$n,$batasthn);
	//loadKalender2($kalender,$nextbln,$nextthn,$n+6,$batasthn);
   
	if (isset($keg['row'])) {
        for ($i = 0; $i < count($keg['row']); $i++ ){
            $id = $keg['row'][$i]['id'];
            $m = $i;
            if ($i > count($color)-1) 
                $m = $i - ((count($color)-1)*(int)substr($i,0,1)+1);		
    
    ?>
    <tr height="25">
        <td style="background-color:#d8d8d8 ; color:#000000" align="center" width="5%"><b><?=($i+1).'. '?></b></td>
        <td style="background-color:#d8d8d8 ; color:#000000">
        <div style="margin-left:5px">
        <b><?=$keg['row'][$i]['judul']; 
			
            //if (!isset($jadwal['row'][$id]))	
              //  echo '<br>'.$keg['row'][$i]['tanggal'];
            
            ?>
        </b>
        </div>
        </td>
            <?php 	
            for($j = 1; $j <=24 ; $j++) {	
                echo getCell1($i, $j, $id); 
            }
            ?>
        
    </tr>
<?php 	} ?>
<?php } ?> 
	 </table>
<?php
	} else {
	?> 
    <table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
        
        </b></font>
        </td>
	</tr>
	</table> 
    <?php } ?>
	
	</td>
<tr>
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