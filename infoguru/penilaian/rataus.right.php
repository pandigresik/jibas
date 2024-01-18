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
require_once('../include/sessioninfo.php');
require_once('../library/dpupdate.php');

OpenDb();

$tkt = $_REQUEST['tkt'];
$kls = $_REQUEST['kls'];
$nis = $_REQUEST['nis'];
$pel = $_REQUEST['pel'];
?>
<link href="../style/style.css" rel="stylesheet" type="text/css" />

<script src="../script/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript">
function CetakRataUjianSiswa(pel,kls,sem,nis,tkt,dp){
	newWindow('rataus.cetak.php?pel='+pel+'&kls='+kls+'&sem='+sem+'&nis='+nis+'&tkt='+tkt+'&dp='+dp, 'CetakRataRataUjianSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
<div style="padding-bottom:10px; padding-top:0px">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="padding-right:5px"><strong>Pelajaran</strong></td>
    <td style="color:#666666; font-weight:bold">
    <?php
    $sql = "SELECT nama,departemen FROM pelajaran WHERE replid='$pel'";
    $res = QueryDb($sql);
    $row = @mysqli_fetch_row($res);
    echo $row[0];
	
	$dep = $row[1];
	?>
    </td>
  </tr>
  <tr>
    <td><strong>Guru</strong></td>
    <td style="color:#666666; font-weight:bold">
    <?php
    $sql = "SELECT p.nama FROM jbssdm.pegawai p, guru g WHERE g.idpelajaran='$pel' AND g.nip=p.nip";
    $res = QueryDb($sql);
    $namaguru = "";
    while ($row = @mysqli_fetch_row($res)){
         if ($namaguru=="")
            $namaguru = $row[0];
         else
            $namaguru .= ", ".$row[0];	
    }
    echo $namaguru;
	?>
    </td>
  </tr>
</table>
</div>
<div>
	<?php
	$sql = "SELECT s.replid,s.semester FROM semester s, ujian u, nilaiujian n WHERE s.departemen='$dep' AND u.idpelajaran='$pel' AND  n.idujian=u.replid AND n.nis='$nis' AND u.idsemester=s.replid GROUP BY s.replid";
	$res = QueryDb($sql);
	while ($row = @mysqli_fetch_row($res)){
		$semester[] = [$row[0], $row[1]];
	}
	?>
	<div id="TabbedPanels1" class="TabbedPanels">
	  <ul class="TabbedPanelsTabGroup">
		<?php for ($i=0;$i<count($semester);$i++){ ?>
		<li class="TabbedPanelsTab" tabindex="0">Semester <?=$semester[$i][1]?></li>
		<?php } ?>
	  </ul>
	  <div class="TabbedPanelsContentGroup" style="z-index:0; border-left:none; border-right:none; border-bottom:none">
		<?php for ($i=0;$i<count($semester);$i++)
		   {
			   $idsemester = $semester[$i][0];
		   	?>
			<div class="TabbedPanelsContent">
				<div align="right" style="font-family:Calibri; font-size:14px; color:#333; font-weight:bold">Semester <?=$semester[$i][1]?></div>
				<div id="TabbedPanelsA<?=$i?>" class="TabbedPanels" style="z-index:1">
				  <ul class="TabbedPanelsTabGroup">
<?php 					$sql5 = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
							   FROM ujian u, nilaiujian n, aturannhb a, dasarpenilaian d
							  WHERE u.replid = n.idujian
								AND n.nis = '$nis'
								AND u.idpelajaran = '$pel'
								AND u.idsemester = '$idsemester'
								AND u.idaturan = a.replid
								AND a.dasarpenilaian = d.dasarpenilaian
						   ORDER BY d.keterangan;";	  	  
					$res5 = QueryDb($sql5);
					while ($row5 = @mysqli_fetch_array($res5))
					{ ?>
					<li class="TabbedPanelsTab" tabindex="0"><?=$row5['keterangan']?></li>
<?php 					} ?>
				  </ul> <!-- ul class="TabbedPanelsTabGroup" -->
				  <div class="TabbedPanelsContentGroup">
<?php 					$res5 = QueryDb($sql5);
					while ($row5 = @mysqli_fetch_array($res5))
					{ ?>
					<div class="TabbedPanelsContent">
						<div align="right"><span style="cursor:pointer" onclick="CetakRataUjianSiswa('<?=$pel?>','<?=$kls?>','<?=$semester[$i][0]?>','<?=$nis?>','<?=$tkt?>','<?=$row5['dasarpenilaian']?>')"><img src="../images/ico/print.png" width="16" height="16" border="0" />&nbsp;<b>Cetak</b></span></div>
						<?php
						$sql = "SELECT j.replid, j.jenisujian, a.replid FROM aturannhb a, jenisujian j WHERE  a.idpelajaran== '".$pel."' AND a.dasarpenilaian='".$row5['dasarpenilaian']."' AND a.idjenisujian=j.replid AND a.idtingkat='$tkt' ORDER BY j.jenisujian";

						//$sql = "SELECT replid, jenisujian FROM jenisujian WHERE idpelajaran = '$pel' ORDER BY jenisujian";
						$res = QueryDb($sql);
						$cnt2=1;

						while ($row = @mysqli_fetch_row($res))
						{
							$rata = 0;
							$numnilai = 0;
							$sql2 = "SELECT u.tanggal,u.deskripsi, n.nilaiujian,u.replid, n.keterangan 
								     FROM ujian u, nilaiujian n 
									 WHERE u.idkelas = '$kls' AND u.idsemester = '".$semester[$i][0]."' AND u.idjenis = $row[0] 
									 AND u.replid = n.idujian AND u.idaturan='".$row[2]."' AND n.nis = '$nis' ORDER BY u.tanggal";
							$res2 = QueryDb($sql2);
							$num2 = @mysqli_num_rows($res2);
							$cnt3 = 0;
							$content = [];
							while ($row2 = @mysqli_fetch_row($res2))
							{
								$sql3 = "SELECT nilaiRK FROM ratauk 
										 WHERE idkelas='$kls' AND idsemester='".$semester[$i][0]."' AND idujian='".$row2[3]."'";
								$res3 = QueryDb($sql3);
								$row3 = @mysqli_fetch_row($res3);
								$ratauk = $row[0];
								$prosen = round((($row2[2]  - $row3[0]) / $row3[0]) * 100, 2)."%";
								//if ($prosen < 0)
								//	$prosen = "-" . $prosen;	
								$numnilai += $row2[2];
								$content[] = [$row2[0], $row2[1], $row2[2], $row3[0], $prosen];	
								$cnt3++;
							}
							
							if ($num2 > 0)
								$rata = round($numnilai/$num2,2);
							
							//echo $num2."_";
							$sql2 = "SELECT nilaiAU FROM nau WHERE idkelas = '$kls' AND idsemester = '".$semester[$i][0]."' AND idjenis = '".$row[0]."' AND nis = '$nis' AND idpelajaran = '$pel' AND idaturan='".$row[2]."'";
							$res2 = QueryDb($sql2);
							$row2 = @mysqli_fetch_row($res2);
							$nilaiakhir = $row2[0];	
							//echo $sql2;
							?>
							<div style="padding-bottom:10px">
							  <fieldset>
							  <legend><?=$row[1]?></legend>
									<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table_<?=$i?>_<?=$cnt2?>">
									  <tr>
										<td width="5%" align="center" class="header">No</td>
										<td width="*" align="center" class="header">Tanggal/Materi</td>
										<td width="12%" align="center" class="header">Nilai</td>
										<td width="12%" align="center" class="header">Rata-rata Kelas</td>
										<td width="12%" align="center" class="header">%</td>
										<td width="12%" align="center" class="header">Rata-rata Nilai</td>
										<td width="12%" align="center" class="header">Nilai Akhir</td>
									  </tr>
									<?php
									if ($num2>0){
										$cnt = 1;
										for ($x=0;$x<count($content);$x++){
										//echo "<pre>";
										//print_r($content);
										//echo "</pre><br>";
										?>
										<tr>
											<td align="center"><?=$cnt?></td>
											<td class="td"><?=LongDateFormat($content[$x][0])?><br /><?=$content[$x][1]?></td>
											<td align="center" class="td"><?=$content[$x][2]?></td>
											<td align="center" class="td"><?=$content[$x][3]?></td>
											<td align="center" class="td"><?=$content[$x][4]?></td>
											<?php if ($x==0){ ?>
											<td align="center" class="td" rowspan="<?=count($content)?>" style="background-color:#FFFFFF"><?=$rata?></td>
											<td align="center" class="td" rowspan="<?=count($content)?>" style="background-color:#FFFFFF"><?=$nilaiakhir?></td>
											<?php } ?>
										</tr>
										<?php
										$cnt++;
										}
									} else {
										?>
										<tr>
											<td height="25" colspan="7" align="center" class="miring">Tidak ada data</td>
										</tr>
										<?php
									}	
									?>
							  </table>
							  <script language='JavaScript'>
								  Tables('table_<?=$i?>_<?=$cnt2?>', 1, 0);
							  </script>
							  </fieldset>
							</div>
						<?php
						$cnt2++;
						} 
						?>	
					</div><!-- div class="TabbedPanelsContent" -->
					<?php } ?>
				  </div> <!-- div class="TabbedPanelsContentGroup" -->
				</div> <!-- div id="TabbedPanels2" class="TabbedPanels" -->
			</div>
		<?php } ?>
	  </div>
	</div>
</div>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
<?php for ($i=0;$i<count($semester);$i++){ ?>
var TabbedPanelsA<?=$i?> = new Spry.Widget.TabbedPanels("TabbedPanelsA<?=$i?>");
<?php } ?>
//-->
</script>