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
//require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../include/getheader.php');
require_once('../include/numbertotext.class.php');
require_once('../library/dpupdate.php');

$NTT = new NumberToText();

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/w-msword'); // Other browsers  
header('Content-Disposition: attachment; filename=Nilai_Pelajaran.doc');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

OpenDb();

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['pelajaran'])) 
	$pelajaran = $_REQUEST['pelajaran'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['harian']))
	$harian = $_REQUEST['harian'];
if (isset($_REQUEST['prespel']))
	$prespel = $_REQUEST['prespel'];
	
$sql_ta="SELECT * FROM jbsakad.tahunajaran WHERE replid='$tahunajaran'";
$result_ta=QueryDb($sql_ta);
$row_ta=@mysqli_fetch_array($result_ta);
$tglawal=$row_ta['tglmulai'];
$tglakhir=$row_ta['tglakhir'];	

$sql_get_siswa="SELECT nis,nama FROM jbsakad.siswa WHERE idkelas='$kelas' AND aktif = 1 ORDER BY nama";
$result_get_siswa=QueryDb($sql_get_siswa);

$sql_get_siswa1="SELECT nis,nama FROM jbsakad.siswa WHERE idkelas='$kelas' AND aktif = 1 ORDER BY nama";
$result_get_siswa1=QueryDb($sql_get_siswa1);

$sql = "SELECT k.kelas AS namakelas, s.semester AS namasemester, a.tahunajaran, t.tingkat, l.nama, a.departemen FROM jbsakad.kelas k, jbsakad.semester s, jbsakad.tahunajaran a, jbsakad.tingkat t, jbsakad.pelajaran l WHERE k.replid = $kelas AND s.replid = $semester AND a.replid = $tahunajaran AND k.idtahunajaran = a.replid AND k.idtingkat = t.replid";// AND l.replid = $pelajaran";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
	
$sql_get_w_kls="SELECT p.nama as namawalikelas, p.nip as nipwalikelas FROM jbssdm.pegawai p, jbsakad.kelas k WHERE k.replid='$kelas' AND k.nipwali=p.nip";
$rslt_get_w_kls=QueryDb($sql_get_w_kls);
$row_get_w_kls=@mysqli_fetch_array($rslt_get_w_kls);
	
$sql_get_kepsek="SELECT d.nipkepsek as nipkepsek,p.nama as namakepsek FROM jbssdm.pegawai p, jbsakad.departemen d WHERE  p.nip=d.nipkepsek AND d.departemen='$departemen'";
//echo $sql_get_kepsek;
$rslt_get_kepsek=QueryDb($sql_get_kepsek);
$row_get_kepsek=@mysqli_fetch_array($rslt_get_kepsek);

$namakelas = $row[0];
$namasemester = $row[1];
$namatahunajaran = $row[2];
$namatingkat = $row[3];
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 11">
<meta name=Originator content="Microsoft Word 11">
<link rel=File-List href="tes_files/filelist.xml">
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>user</o:Author>
  <o:LastAuthor>user</o:LastAuthor>
  <o:Revision>1</o:Revision>
  <o:TotalTime>1</o:TotalTime>
  <o:Created>2008-06-19T02:21:00Z</o:Created>
  <o:LastSaved>2008-06-19T02:22:00Z</o:LastSaved>
  <o:Pages>1</o:Pages>
  <o:Characters>2</o:Characters>
  <o:Lines>1</o:Lines>
  <o:Paragraphs>1</o:Paragraphs>
  <o:CharactersWithSpaces>2</o:CharactersWithSpaces>
  <o:Version>11.5606</o:Version>
 </o:DocumentProperties>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:View>Print</w:View>
 <w:Zoom>100</w:Zoom>
  <w:GrammarState>Clean</w:GrammarState>
  <w:PunctuationKerning/>
  <w:ValidateAgainstSchemas/>
  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
  <w:Compatibility>
   <w:BreakWrappedTables/>
   <w:SnapToGridInCell/>
   <w:WrapTextWithPunct/>
   <w:UseAsianBreakRules/>
   <w:DontGrowAutofit/>
  </w:Compatibility>
  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel>
 </w:WordDocument>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:LatentStyles DefLockedState="false" LatentStyleCount="156">
 </w:LatentStyles>
</xml><![endif]-->
<style>
<!--
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-parent:"";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:12.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";}
	<?php
$cnt_siswa=1;
while ($row_get_siswa=@mysqli_fetch_array($result_get_siswa)){
	$nis = $row_get_siswa['nis'];
	$nama = $row_get_siswa['nama'];
?>
@page Section<?=$cnt_siswa?>
	{size:612.0pt 792.0pt;
	margin:72.0pt 90.0pt 72.0pt 90.0pt;
	mso-header-margin:36.0pt;
	mso-footer-margin:36.0pt;
	mso-paper-source:0;}
div.Section<?=$cnt_siswa?>
	{page:Section<?=$cnt_siswa?>;}
@page Section<?=$cnt_siswa+1?>
	{size:612.0pt 792.0pt;
	margin:72.0pt 90.0pt 72.0pt 90.0pt;
	mso-header-margin:36.0pt;
	mso-footer-margin:36.0pt;
	mso-paper-source:0;}
div.Section<?=$cnt_siswa+1?>
	{page:Section<?=$cnt_siswa+1?>;}
@page Section<?=$cnt_siswa+2?>
	{size:612.0pt 792.0pt;
	margin:72.0pt 90.0pt 72.0pt 90.0pt;
	mso-header-margin:36.0pt;
	mso-footer-margin:36.0pt;
	mso-paper-source:0;}
div.Section<?=$cnt_siswa+2?>
	{page:Section<?=$cnt_siswa+2?>;}
@page Section<?=$cnt_siswa+3?>
	{size:612.0pt 792.0pt;
	margin:72.0pt 90.0pt 72.0pt 90.0pt;
	mso-header-margin:36.0pt;
	mso-footer-margin:36.0pt;
	mso-paper-source:0;}
div.Section<?=$cnt_siswa+3?>
	{page:Section<?=$cnt_siswa+3?>;}
	<?php
	$cnt_siswa=$cnt_siswa+3;
	}
	?>
.style1 {font-weight: bold}
.style1 {
	color: #000000;
	font-weight: bold;
}
.style2 {
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 16px;
}
.style3 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style4 {font-size: 12px}
.style10 {font-size: 14px; font-family: Verdana, Arial, Helvetica, sans-serif; }
.style11 {font-size: 14px}
.style12 {font-size: 13px}
.style13 {font-size: 13px; font-family: Verdana, Arial, Helvetica, sans-serif; }
.style14 {font-size: 16px}
-->
</style>
<!--[if gte mso 10]>
<style>
 /* Style Definitions */
 table.MsoNormalTable
	{mso-style-name:"Table Normal";
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-noshow:yes;
	mso-style-parent:"";
	mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
	mso-para-margin:0cm;
	mso-para-margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Times New Roman";
	mso-ansi-language:#0400;
	mso-fareast-language:#0400;
	mso-bidi-language:#0400;}
</style>
<![endif]-->
</head>

<body lang=EN-US style='tab-interval:36.0pt'>

<?php
$cnt_siswa1=1;
while ($row_siswa1=@mysqli_fetch_array($result_get_siswa1)){
	$nis = $row_siswa1['nis'];
	$nama = $row_siswa1['nama'];
?>
<div class=Section<?=$cnt_siswa1?>>
<?=getHeader($departemen)?>
<table width="100%" border="0">
  <tr>
    <td>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#666666">
		<tr>
		<td height="16" colspan="2" bgcolor="#FFFFFF">
		<div align="center" class="style13 style3 style14"><strong>NILAI HASIL
		BELAJAR</strong></div></td>
		</tr>
		<tr>
		<td height="20">&nbsp;</td>
		<td height="20">&nbsp;</td>
		</tr>
		<tr height="20">
		<td width="200"><span class="style13">Departemen</span></td>
		<td width="*"><span class="style13">: 
		  <?=$departemen?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Tahun&nbsp;Ajaran</span></td>
		<td><span class="style13">: 
		  <?=$namatahunajaran?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Semester</span></td>
		<td><span class="style13">: 
		  <?=$namasemester?>
		</span></td>
		</tr>
		<tr height="20">
		<td width="10%"><span class="style13">Kelas</span></td>
		<td><span class="style13">: 
		  <?=$namatingkat.' - '. $namakelas;?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">NIS</span></td>
		<td><span class="style13">: 
		  <?=$nis;?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Siswa</span></td>
		<td><span class="style13">: 
		  <?=$nama;?>
		</span></td>
		</tr>
		</table>
</td>
  </tr>
  <tr>
    <td>
    
     <table width="100%" border="1" cellpadding="0" cellspacing="0" class="tab" style="font-family:Verdana, Geneva, sans-serif; font-size:12px;">
	<tr>
	    <td width="4%" rowspan="2"  bgcolor="#CCCCCC"><div align="center"><strong>No</strong></div></td>
		<td width="20%" rowspan="2" bgcolor="#CCCCCC"><div align="center"><strong>Pelajaran</strong></div></td>
     	<td width="7%" rowspan="2"  bgcolor="#CCCCCC"><div align="center"><strong>KKM</strong></div></td>
		<td width="20%" rowspan="2"  bgcolor="#CCCCCC"><div align="center"><strong>Aspek<br>Penilaian</strong></div></td>
        <td width="35%" colspan="3"  bgcolor="#CCCCCC"><div align="center"><strong>Nilai</strong></div></td>
  	</tr>
    <tr>
    	<td width="7%"  bgcolor="#CCCCCC"><div align="center"><strong>Angka</strong></div></td>
        <td width="7%"  bgcolor="#CCCCCC"><div align="center"><strong>Huruf</strong></div></td>
        <td width="25%"  bgcolor="#CCCCCC"><div align="center"><strong>Terbilang</strong></div></td>
    </tr>
    
<?php $sql = "SELECT pel.replid, pel.nama
			 FROM ujian uji, nilaiujian niluji, siswa sis, pelajaran pel 
			WHERE uji.replid = niluji.idujian 
			  AND niluji.nis = sis.nis 
			  AND uji.idpelajaran = pel.replid 
			  AND uji.idsemester = $semester
			  AND uji.idkelas = $kelas
			  AND sis.nis = '$nis' 
		GROUP BY pel.nama";    
	$res = QueryDb($sql);
	$i = 0;
	while($row = mysqli_fetch_row($res))
	{
		$pelarr[$i++] = [$row[0], $row[1]];
	}
	
	for($i = 0; $i < count($pelarr); $i++)
	{
		$idpel = $pelarr[$i][0];
		$nmpel = $pelarr[$i][1];
		
		$sql = "SELECT nilaimin 
				 FROM infonap
				WHERE idpelajaran = $idpel
				  AND idsemester = $semester
			      AND idkelas = $kelas";
		$res = QueryDb($sql);
		$row = mysqli_fetch_row($res);
		$nilaimin = $row[0];
		
		$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan 
		  	    FROM infonap i, nap n, aturannhb a, dasarpenilaian d 
			   WHERE i.replid = n.idinfo AND n.nis = '$nis' 
			     AND i.idpelajaran = $idpel 
			     AND i.idsemester = '$semester' 
			     AND i.idkelas = '$kelas' 
			     AND n.idaturan = a.replid  	   
			     AND a.dasarpenilaian = d.dasarpenilaian";	
		$res = QueryDb($sql);				 
		$aspekarr = [];				 
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
			
			$aspekarr[$j++] = [$row[0], $row[1], $na, $nh];
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
	} ?>
	</table>
    
    </td>
  </tr>
  <tr>
    <td>
     <table width="100%" border="0">
  <tr>
    <td rowspan="2" width="33%"><div align="center" class="style13">Orang Tua/Wali Siswa</div></td>
    <td width="33%"><div align="center" class="style13">Mengetahui,</div></td>
    <td rowspan="2" width="33%"><div align="center" class="style13">Wali Kelas</div></td>
  </tr>
  <tr>
    <td width="33%"><div align="center" class="style13">Kepala Sekolah 
      <?=$departemen?>
    </div></td>
  </tr>
  <tr>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
  </tr>
  <tr>
    <td width="33%" rowspan="2"><div align="center" class="style13">(.............................................)</div></td>
    <td width="33%"><div align="center" class="style13">
      <u><?=$row_get_kepsek['namakepsek']?></u>
    </div></td>
    <td width="33%"><div align="center" class="style13">
      <u><?=$row_get_w_kls['namawalikelas']?></u>
    </div></td>
  </tr>
  <tr>
    <td><div align="center" class="style13">
      NIP : <?=$row_get_kepsek['nipkepsek']?>
    </div></td>
    <td width="33%"><div align="center" class="style13">
      NIP : <?=$row_get_w_kls['nipwalikelas']?>
    </div></td>
  </tr>
</table>
    </td>
  </tr>
</table>
</div>

<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:
"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='page-break-before:always;
mso-break-type:section-break'>
</span>

<div class=Section<?=$cnt_siswa1+1?>>
<table width="100%" border="0">
  <tr>
    <td>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#666666">
		<tr>
		<td height="16" colspan="2" bgcolor="#FFFFFF">
		<div align="center" class="style13 style3 style14"><strong>KOMENTAR HASIL
		BELAJAR</strong></div></td>
		</tr>
		<tr>
		<td height="20">&nbsp;</td>
		<td height="20">&nbsp;</td>
		</tr>
		<tr height="20">
		<td width="200"><span class="style13">Departemen</span></td>
		<td width="*"><span class="style13">: 
		  <?=$departemen?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Tahun&nbsp;Ajaran</span></td>
		<td><span class="style13">: 
		  <?=$namatahunajaran?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Semester</span></td>
		<td><span class="style13">: 
		  <?=$namasemester?>
		</span></td>
		</tr>
		<tr height="20">
		<td width="10%"><span class="style13">Kelas</span></td>
		<td><span class="style13">: 
		  <?=$namatingkat.' - '. $namakelas;?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">NIS</span></td>
		<td><span class="style13">: 
		  <?=$nis;?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Siswa</span></td>
		<td><span class="style13">: 
		  <?=$nama;?>
		</span></td>
		</tr>
		</table>
	</td>
  </tr>
  <tr>
    <td>

	<table width="100%" border="1" bordercolor="#FFFFFF" class="tab" id="table" cellpadding="0" cellspacing="0">
	<tr>
	<td width="27%" height="30" align="center" bgcolor="#CCCCCC" ><font face="Verdana" size="2" color="#000000" ><strong>Pelajaran</strong></font></td>
	<td width="73%" height="30" align="center" bgcolor="#CCCCCC" ><font face="Verdana" size="2" color="#000000" ><strong>Komentar</strong></font></td>
	</tr>
	<!-- Ambil pelajaran per departemen-->
	<?php
	$sql_get_pelajaran_komentar="SELECT pel.replid as replid,pel.nama as nama FROM infonap info, komennap komen, siswa sis, pelajaran pel ".
								"WHERE info.replid=komen.idinfo ".
								"AND komen.nis=sis.nis ".
								"AND info.idpelajaran=pel.replid ".
								"AND info.idsemester='$semester' ".
								"AND info.idkelas='$kelas' ".
								"AND sis.nis='".$row_siswa1['nis']."' ".
								"GROUP BY pel.nama";                      
    
	//echo $sql_get_pelajaran_komentar;
	//exit;
	$result_get_pelajaran_komentar=QueryDb($sql_get_pelajaran_komentar);
	$cntpel_komentar=1;
	while ($row_get_pelajaran_komentar=@mysqli_fetch_array($result_get_pelajaran_komentar)){
	$sql_get_komentar = "SELECT k.komentar
                           FROM jbsakad.komennap k, jbsakad.infonap i
                          WHERE k.nis='".$row_siswa1['nis']."'
                            AND i.idpelajaran='".$row_get_pelajaran_komentar['replid']."'
                            AND i.idsemester = '$semester'
                            AND i.replid=k.idinfo
                            AND i.idkelas = '".$kelas."'";
	$result_get_komentar=QueryDb($sql_get_komentar);
	$row_get_komentar=@mysqli_fetch_row($result_get_komentar);
	?>
	<tr>
	<td height="25"><span class="style13"><?=$row_get_pelajaran_komentar['nama']?></span></td>
	<td height="25"><?=$row_get_komentar[0]?></td>
	</tr>
	<?php
	$cntpel_komentar++;
	}
	?>
	</table>

	</td>
  </tr>
  <tr>
    <td>
	
	 <table width="100%" border="0">
  <tr>
    <td rowspan="2" width="33%"><div align="center" class="style13">Orang Tua/Wali Siswa</div></td>
    <td width="33%"><div align="center" class="style13">Mengetahui,</div></td>
    <td rowspan="2" width="33%"><div align="center" class="style13">Wali Kelas</div></td>
  </tr>
  <tr>
    <td width="33%"><div align="center" class="style13">Kepala Sekolah 
      <?=$departemen?>
    </div></td>
  </tr>
  <tr>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
  </tr>
  <tr>
    <td width="33%" rowspan="2"><div align="center" class="style13">(.............................................)</div></td>
    <td width="33%"><div align="center" class="style13">
      <u><?=$row_get_kepsek['namakepsek']?></u>
    </div></td>
    <td width="33%"><div align="center" class="style13">
      <u><?=$row_get_w_kls['namawalikelas']?></u>
    </div></td>
  </tr>
  <tr>
    <td><div align="center" class="style13">
      NIP. <?=$row_get_kepsek['nipkepsek']?>
    </div></td>
    <td width="33%"><div align="center" class="style13">
      NIP. <?=$row_get_w_kls['nipwalikelas']?>
    </div></td>
  </tr>
</table>

	</td>
  </tr>
</table>
</div>

<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:
"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='page-break-before:always;
mso-break-type:section-break'>
</span>

<?php if ($prespel!="false") { ?>
<div class=Section<?=$cnt_siswa1+2?>>

<table width="100%" border="0">
  <tr>
    <td>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#666666">
		<tr>
		<td height="16" colspan="2" bgcolor="#FFFFFF">
		<div align="center" class="style13 style3 style14"><strong>PRESENSI PELAJARAN</strong></div></td>
		</tr>
		<tr>
		<td height="20">&nbsp;</td>
		<td height="20">&nbsp;</td>
		</tr>
		<tr>
		<td height="20">&nbsp;</td>
		<td height="20">&nbsp;</td>
		</tr>
		<tr height="20">
		<td width="200"><span class="style13">Departemen</span></td>
		<td width="*"><span class="style13">: 
		  <?=$departemen?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Tahun&nbsp;Ajaran</span></td>
		<td><span class="style13">: 
		  <?=$namatahunajaran?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Semester</span></td>
		<td><span class="style13">: 
		  <?=$namasemester?>
		</span></td>
		</tr>
		<tr height="20">
		<td width="10%"><span class="style13">Kelas</span></td>
		<td><span class="style13">: 
		  <?=$namatingkat.' - '. $namakelas;?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">NIS</span></td>
		<td><span class="style13">: 
		  <?=$nis;?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Siswa</span></td>
		<td><span class="style13">: 
		  <?=$nama;?>
		</span></td>
		</tr>
		</table>
	</td>
  </tr>
  <tr>
    <td>
	<table width="100%" border="1" bordercolor="#FFFFFF" class="tab" id="table" cellpadding="0" cellspacing="0">
  <tr>
    <td width="27%" rowspan="2" bgcolor="#CCCCCC" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Pelajaran</strong></font></td>
    <td height="25" colspan="2" bgcolor="#CCCCCC" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Hadir</strong></font></td>
    <td height="25" colspan="2" bgcolor="#CCCCCC" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Sakit</strong></font></td>
    <td height="25" colspan="2" bgcolor="#CCCCCC" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Ijin</strong></font></td>
    <td height="25" colspan="2" bgcolor="#CCCCCC" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Alpa</strong></font></td>
    </tr>
  <tr>
    <td width="6" bgcolor="#CCCCCC"  align="center"  ><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#CCCCCC"  align="center" ><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
    <td width="6" bgcolor="#CCCCCC"  align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#CCCCCC"  align="center" ><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
    <td width="6" bgcolor="#CCCCCC"  align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#CCCCCC"  align="center" ><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
    <td width="6" bgcolor="#CCCCCC"  align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#CCCCCC"  align="center" ><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
  </tr>
  <!-- Ambil pelajaran per departemen-->
	<?php
	$sql_get_pelajaran_presensi="SELECT pel.replid as replid,pel.nama as nama FROM presensipelajaran ppel, ppsiswa pp, siswa sis, pelajaran pel ".
								"WHERE pp.nis=sis.nis ".
								"AND ppel.replid=pp.idpp ".
								"AND ppel.idpelajaran=pel.replid ".
								"AND ppel.idsemester='$semester' ".
								"AND ppel.idkelas='$kelas' ".
								"AND sis.nis='".$row_siswa1['nis']."' ".
								"GROUP BY pel.nama";
	$result_get_pelajaran_presensi=QueryDb($sql_get_pelajaran_presensi);
	$cntpel_presensi=1;
	
	while ($row_get_pelajaran_presensi=@mysqli_fetch_array($result_get_pelajaran_presensi)){
	//ambil semua jumlah presensi per pelajaran 
	$sql_get_all_presensi="select count(*) as jumlah FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='".$row_siswa1['nis']."'";
	$result_get_all_presensi=QueryDb($sql_get_all_presensi);
	$row_get_all_presensi=@mysqli_fetch_array($result_get_all_presensi);
	//dapet nih jumlahnya
	$jumlah_presensi=$row_get_all_presensi['jumlah'];

	//ambil yang hadir
	$sql_get_hadir="select count(*) as hadir FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='".$row_siswa1['nis']."' AND pp.statushadir=0";
	$result_get_hadir=QueryDb($sql_get_hadir);
	$row_get_hadir=@mysqli_fetch_array($result_get_hadir);
	$hadir=$row_get_hadir['hadir'];

	//ambil yang sakit
	$sql_get_sakit="select count(*) as sakit FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='".$row_siswa1['nis']."' AND pp.statushadir=1";
	$result_get_sakit=QueryDb($sql_get_sakit);
	$row_get_sakit=@mysqli_fetch_array($result_get_sakit);
	$sakit=$row_get_sakit['sakit'];

	//ambil yang ijin
	$sql_get_ijin="select count(*) as ijin FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='".$row_siswa1['nis']."' AND pp.statushadir=2";
	$result_get_ijin=QueryDb($sql_get_ijin);
	$row_get_ijin=@mysqli_fetch_array($result_get_ijin);
	$ijin=$row_get_ijin['ijin'];

	//ambil yang alpa
	$sql_get_alpa="select count(*) as alpa FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='".$row_siswa1['nis']."' AND pp.statushadir=3";
	$result_get_alpa=QueryDb($sql_get_alpa);
	$row_get_alpa=@mysqli_fetch_array($result_get_alpa);
	$alpa=$row_get_alpa['alpa'];

	//hitung prosentase kalo jumlahnya gak 0
	if ($jumlah_presensi<>0){
		$p_hadir=round(($hadir/$jumlah_presensi)*100);
		$p_sakit=round(($sakit/$jumlah_presensi)*100);
		$p_ijin=round(($ijin/$jumlah_presensi)*100);
		$p_alpa=round(($alpa/$jumlah_presensi)*100);
	} else {
		$p_hadir=0;
		$p_sakit=0;
		$p_ijin=0;
		$p_alpa=0;
	}
	?>
	<tr>
    <td height="25"><span class="style13"><?=$row_get_pelajaran_presensi['nama']?></span></td>
    <td height="25"><div align="center">
      <span class="style13"><?=$hadir?></span>
    </div></td>
    <td height="25"><div align="center">
      <span class="style13"><?=$p_hadir?>
      &nbsp;%</span></div></td>
    <td height="25"><div align="center">
      <span class="style13"><?=$sakit?></span>
    </div></td>
    <td height="25"><div align="center">
      <span class="style13"><?=$p_sakit?>
      &nbsp;%</span></div></td>
    <td height="25"><div align="center">
      <span class="style13"><?=$ijin?></span>
    </div></td>
    <td height="25"><div align="center">
      <span class="style13"><?=$p_ijin?>
      &nbsp;%</span></div></td>
    <td height="25"><div align="center">
      <span class="style13"><?=$alpa?></span>
    </div></td>
    <td height="25"><div align="center">
      <span class="style13"><?=$p_alpa?>
      &nbsp;%</span></div></td>
	 </tr>
	<?php
	$cntpel_presensi++;
	}

	//sekarang hitung jumlah hadir semua pelajaran
	$sql_all_hadir="select count(*) as allhadir FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='$semester' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='".$row_siswa1['nis']."' AND pp.statushadir=0";
    $result_all_hadir=QueryDb($sql_all_hadir);
	$row_all_hadir=@mysqli_fetch_array($result_all_hadir);
	$all_hadir=$row_all_hadir['allhadir'];
	
	//sekarang hitung jumlah sakit semua pelajaran
	$sql_all_sakit="select count(*) as allsakit FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='$semester' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='".$row_siswa1['nis']."' AND pp.statushadir=1";
    $result_all_sakit=QueryDb($sql_all_sakit);
	$row_all_sakit=@mysqli_fetch_array($result_all_sakit);
	$all_sakit=$row_all_sakit['allsakit'];

	//sekarang hitung jumlah ijin semua pelajaran
	$sql_all_ijin="select count(*) as allijin FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='$semester' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='".$row_siswa1['nis']."' AND pp.statushadir=2";
    $result_all_ijin=QueryDb($sql_all_ijin);
	$row_all_ijin=@mysqli_fetch_array($result_all_ijin);
	$all_ijin=$row_all_ijin['allijin'];

	//sekarang hitung jumlah alpa semua pelajaran
	$sql_all_alpa="select count(*) as allalpa FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='$semester' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='".$row_siswa1['nis']."' AND pp.statushadir=3";
    $result_all_alpa=QueryDb($sql_all_alpa);
	$row_all_alpa=@mysqli_fetch_array($result_all_alpa);
	$all_alpa=$row_all_alpa['allalpa'];
	?>
  <tr>
    <td height="25" bgcolor="#CCCCCC"><font face="Verdana" size="2" color="#000000" ><strong>Total</strong></font></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><font face="Verdana" size="2" color="#000000" ><strong>
      <?=$all_hadir?>
    </strong></font></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"><span class="style22"></span></div></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><font face="Verdana" size="2" color="#000000" ><strong>
      <?=$all_sakit?>
    </strong></font></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"><span class="style22"></span></div></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><font face="Verdana" size="2" color="#000000" ><strong>
      <?=$all_ijin?>
    </strong></font></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"><span class="style22"></span></div></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><font face="Verdana" size="2" color="#000000" ><strong>
      <?=$all_alpa?>
    </strong></font></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"><span class="style22"></span></div></td>
  </tr>
</table>
	
	</td>
  </tr>
  <tr>
    <td>
	 <table width="100%" border="0">
  <tr>
    <td rowspan="2" width="33%"><div align="center" class="style10 style12">Orang Tua/Wali Siswa</div></td>
    <td width="33%"><div align="center" class="style13">Mengetahui,</div></td>
    <td rowspan="2" width="33%"><div align="center" class="style13">Wali Kelas</div></td>
  </tr>
  <tr>
    <td width="33%"><div align="center" class="style13">Kepala Sekolah 
      <?=$departemen?>
    </div></td>
  </tr>
  <tr>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
  </tr>
  <tr>
    <td width="33%" rowspan="2"><div align="center" class="style13">(.............................................)</div></td>
    <td width="33%"><div align="center" class="style13">
      <u><?=$row_get_kepsek['namakepsek']?></u>
    </div></td>
    <td width="33%"><div align="center" class="style13">
      <u><?=$row_get_w_kls['namawalikelas']?></u>
    </div></td>
  </tr>
  <tr>
    <td><div align="center" class="style13">
      NIP : <?=$row_get_kepsek['nipkepsek']?>
    </div></td>
    <td width="33%"><div align="center" class="style13">
      NIP : <?=$row_get_w_kls['nipwalikelas']?>
    </div></td>
  </tr>
</table>
	</td>
  </tr>
</table>

</div>
<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:
"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='page-break-before:always;
mso-break-type:section-break'>
</span>
<?php }
if ($harian!="false"){
?>
<div class=Section<?=$cnt_siswa1+3?>>

<table width="100%" border="0">
  <tr>
    <td>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#666666">
		<tr>
		<td height="16" colspan="2" bgcolor="#FFFFFF">
		<div align="center" class="style13 style3 style14"><strong>PRESENSI HARIAN</strong></div></td>
		</tr>
		<tr>
		<td height="20">&nbsp;</td>
		<td height="20">&nbsp;</td>
		</tr>
		<tr height="20">
		<td width="200"><span class="style13">Departemen</span></td>
		<td width="*"><span class="style13">: 
		  <?=$departemen?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Tahun&nbsp;Ajaran</span></td>
		<td><span class="style13">: 
		  <?=$namatahunajaran?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Semester</span></td>
		<td><span class="style13">: 
		  <?=$namasemester?>
		</span></td>
		</tr>
		<tr height="20">
		<td width="10%"><span class="style13">Kelas</span></td>
		<td><span class="style13">: 
		  <?=$namatingkat.' - '. $namakelas;?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">NIS</span></td>
		<td><span class="style13">: 
		  <?=$nis;?>
		</span></td>
		</tr>
		<tr height="20">
		<td><span class="style13">Siswa</span></td>
		<td><span class="style13">: 
		  <?=$nama;?>
		</span></td>
		</tr>
		</table>
	</td>
  </tr>
  <tr>
    <td>
	<!-- Tabel presensi harian disini -->
    <?php


	 $sql_harian = "SELECT SUM(ph.hadir) as hadir, SUM(ph.ijin) as ijin, SUM(ph.sakit) as sakit, SUM(ph.cuti) as cuti, SUM(ph.alpa) as alpa, SUM(ph.hadir+ph.sakit+ph.ijin+ph.alpa+ph.cuti) as tot ".
			"FROM presensiharian p, phsiswa ph, siswa s ".
			"WHERE ph.idpresensi = p.replid ".
			"AND ph.nis = s.nis ".
			"AND ph.nis = '$nis' ".
			"AND ((p.tanggal1 ".
			"BETWEEN '$tglawal' ".
			"AND '$tglakhir') ".
			"OR (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) ".
			"ORDER BY p.tanggal1"; ;
	  //echo $sql;
	  ?>
	<!-- Content Presensi disini -->
	<table width="100%" border="1" class="tab" id="table" bordercolor="#FFFFFF" cellpadding="0" cellspacing="0">
  <tr>
    <td height="25" colspan="2" bgcolor="#CCCCCC"><div align="center" class="style1">Hadir</div></td>
    <td height="25" colspan="2" bgcolor="#CCCCCC" class="headerlong"><div align="center" class="style1">Sakit</div></td>
    <td height="25" colspan="2" bgcolor="#CCCCCC"><div align="center" class="style1">Ijin</div></td>
    <td height="25" colspan="2" bgcolor="#CCCCCC" class="headerlong"><div align="center" class="style1">Alpa</div></td>
    <td height="25" colspan="2" bgcolor="#CCCCCC"><div align="center" class="style1">Cuti</div></td>
    </tr>
  <tr>
    <td width="6" bgcolor="#CCCCCC" align="center"><div class="style1">Jumlah</div></td>
    <td width="6" bgcolor="#CCCCCC"><div align="center" class="style1">%</div></td>
    <td width="6" bgcolor="#CCCCCC" class="headerlong"><div align="center" class="style1">Jumlah</div></td>
    <td width="6" bgcolor="#CCCCCC" class="headerlong"><div align="center" class="style1">%</div></td>
    <td width="6" bgcolor="#CCCCCC"><div align="center" class="style1">Jumlah</div></td>
    <td width="6" bgcolor="#CCCCCC"><div align="center" class="style1">%</div></td>
    <td width="6" bgcolor="#CCCCCC" class="headerlong"><div align="center" class="style1">Jumlah</div></td>
    <td width="6" bgcolor="#CCCCCC" class="headerlong"><div align="center" class="style1">%</div></td>
    <td width="6" bgcolor="#CCCCCC"><div align="center" class="style1">Jumlah</div></td>
    <td width="6" bgcolor="#CCCCCC"><div align="center" class="style1">%</div></td>
  </tr>
  <!-- Ambil pelajaran per departemen-->
	<?php
	$result_harian=QueryDb($sql_harian);
	$row_harian=@mysqli_fetch_array($result_harian);
	$hadir=$row_harian['hadir'];
	$sakit=$row_harian['sakit'];
	$ijin=$row_harian['ijin'];
	$alpa=$row_harian['alpa'];
	$cuti=$row_harian['cuti'];
	$all=$row_harian['tot'];
	if ($hadir!=0 && $all !=0)
	$p_hadir=$hadir/$all*100;
	
	if ($sakit!=0 && $all !=0)
	$p_sakit=$sakit/$all*100;
	
	if ($ijin!=0 && $all !=0)
	$p_ijin=$ijin/$all*100;
	
	if ($alpa!=0 && $all !=0)
	$p_alpa=$alpa/$all*100;
	
	if ($cuti!=0 && $all !=0)
	$p_cuti=$cuti/$all*100;
	?>
	<tr>
	  <td height="25" bgcolor="#FFFFFF"><div align="center">
	    <?=$hadir?>
	      </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
      <?=round($p_hadir,2)?>%</div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
      <?=$sakit?>
    </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
      <?=round($p_sakit,2)?>%</div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
      <?=$ijin?>
    </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
      <?=round($p_ijin,2)?>%</div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
      <?=$alpa?>
    </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
      <?=round($p_alpa,2)?>%</div></td>
      <td height="25" bgcolor="#FFFFFF"><div align="center">
      <?=$cuti?>
    </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
      <?=round($p_cuti,2)?>%</div></td>
	 </tr>
</table>
	
	</td>
  </tr>
  <tr>
    <td>
	 <table width="100%" border="0">
  <tr>
    <td rowspan="2" width="33%"><div align="center" class="style10 style12">Orang Tua/Wali Siswa</div></td>
    <td width="33%"><div align="center" class="style13">Mengetahui,</div></td>
    <td rowspan="2" width="33%"><div align="center" class="style13">Wali Kelas</div></td>
  </tr>
  <tr>
    <td width="33%"><div align="center" class="style13">Kepala Sekolah 
      <?=$departemen?>
    </div></td>
  </tr>
  <tr>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
    <td width="33%" height="50"><div align="center"><span class="style3"><span class="style4"><span class="style11"><span class="style12"></span></span></span></span></div></td>
  </tr>
  <tr>
    <td width="33%" rowspan="2"><div align="center" class="style13">(.............................................)</div></td>
    <td width="33%"><div align="center" class="style13">
      <u><?=$row_get_kepsek['namakepsek']?></u>
    </div></td>
    <td width="33%"><div align="center" class="style13">
      <u><?=$row_get_w_kls['namawalikelas']?></u>
    </div></td>
  </tr>
  <tr>
    <td><div align="center" class="style13">
      NIP : <?=$row_get_kepsek['nipkepsek']?>
    </div></td>
    <td width="33%"><div align="center" class="style13">
      NIP : <?=$row_get_w_kls['nipwalikelas']?>
    </div></td>
  </tr>
</table>
	</td>
  </tr>
</table>

</div>
<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:
"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='page-break-before:always;
mso-break-type:section-break'>
</span>
<?php
}
	$cnt_siswa1=$cnt_siswa1+3;
}
?>
</body>

</html>
<?php
CloseDb();
?>