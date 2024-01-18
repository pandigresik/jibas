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
require_once('../include/errorhandler.php');
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

if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];

if (isset($_REQUEST['prespel']))
	$prespel = $_REQUEST['prespel'];

if (isset($_REQUEST['harian']))
	$harian = $_REQUEST['harian'];

$sql_ta="SELECT * FROM jbsakad.tahunajaran WHERE replid='$tahunajaran'";
$result_ta=QueryDb($sql_ta);
$row_ta=@mysqli_fetch_array($result_ta);
$tglawal=$row_ta['tglmulai'];
$tglakhir=$row_ta['tglakhir'];

//echo "Dep=".$departemen.", Tkt=".$tingkat.", Kls=".$kelas.", Pelajaran=".$pelajaran.", Semester=".$semester.", Thn AJaran=".$tahun;
 	$sql_get_ta="SELECT tahunajaran FROM jbsakad.tahunajaran WHERE replid='$tahunajaran'";
	$result_get_ta=QueryDb($sql_get_ta);
	$row_get_ta=@mysqli_fetch_array($result_get_ta);
	
	$sql_get_nama="SELECT nama FROM jbsakad.siswa WHERE nis='$nis'";
	$result_get_nama=QueryDb($sql_get_nama);
	$row_get_nama=@mysqli_fetch_array($result_get_nama);
	
	 $sql_get_kls="SELECT kelas FROM jbsakad.kelas WHERE replid='$kelas'";
	$result_get_kls=QueryDb($sql_get_kls);
	$row_get_kls=@mysqli_fetch_array($result_get_kls);
	
	$sql_get_sem="SELECT semester FROM jbsakad.semester WHERE replid='$semester'";
	$result_get_sem=QueryDb($sql_get_sem);
	$row_get_sem=@mysqli_fetch_array($result_get_sem);
	
	$sql_get_w_kls="SELECT p.nama as namawalikelas, p.nip as nipwalikelas FROM jbssdm.pegawai p, jbsakad.kelas k WHERE k.replid='$kelas' AND k.nipwali=p.nip";
	//echo $sql_get_w_kls;
	$rslt_get_w_kls=QueryDb($sql_get_w_kls);
	$row_get_w_kls=@mysqli_fetch_array($rslt_get_w_kls);
	
	$sql_get_kepsek="SELECT d.nipkepsek as nipkepsek,p.nama as namakepsek FROM jbssdm.pegawai p, jbsakad.departemen d WHERE  p.nip=d.nipkepsek AND d.departemen='$departemen'";
	//echo $sql_get_kepsek;
	$rslt_get_kepsek=QueryDb($sql_get_kepsek);
	$row_get_kepsek=@mysqli_fetch_array($rslt_get_kepsek);
/**/
header('Content-Type: application/vnd.ms-word'); //IE and Opera  
header('Content-Type: application/w-msword'); // Other browsers  
header('Content-Disposition: attachment; filename=Nilai_Pelajaran.doc');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 11">
<meta name=Originator content="Microsoft Word 11">
<link rel=File-List href="Doc1_files/filelist.xml">
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>user</o:Author>
  <o:LastAuthor>user</o:LastAuthor>
  <o:Revision>1</o:Revision>
  <o:TotalTime>0</o:TotalTime>
  <o:Created>2008-06-16T08:31:00Z</o:Created>
  <o:LastSaved>2008-06-16T08:31:00Z</o:LastSaved>
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
@page Section1
	{size:612.0pt 792.0pt;
	margin:72.0pt 90.0pt 72.0pt 90.0pt;
	mso-header-margin:36.0pt;
	mso-footer-margin:36.0pt;
	mso-paper-source:0;}
div.Section1
	{page:Section1;}
@page Section2
	{size:612.0pt 792.0pt;
	margin:72.0pt 90.0pt 72.0pt 90.0pt;
	mso-header-margin:36.0pt;
	mso-footer-margin:36.0pt;
	mso-paper-source:0;}
div.Section2
	{page:Section2;}
@page Section3
	{size:612.0pt 792.0pt;
	margin:72.0pt 90.0pt 72.0pt 90.0pt;
	mso-header-margin:36.0pt;
	mso-footer-margin:36.0pt;
	mso-paper-source:0;}
div.Section3
	{page:Section3;}
@page Section4
	{size:612.0pt 792.0pt;
	margin:72.0pt 90.0pt 72.0pt 90.0pt;
	mso-header-margin:36.0pt;
	mso-footer-margin:36.0pt;
	mso-paper-source:0;}
div.Section4
	{page:Section4;}
.style1 {
	color: #000000;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
}
.style2 {
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000000;
}
.style5 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.style13 {color: #000000}
.style14 {color: #FFFFFF}
.style17 {color: #FFFFFF; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.style20 {font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
.style21 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.style22 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style24 {
	font-size: 12;
	color: #FFFFFF;
}
.style27 {color: #FFFFFF; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12; }
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

<div class=Section1>
<!--                      Hal 1             -->
<?=getHeader($departemen)?><!--<img src="http://192.168.1.234/jibassimaka2/images/ico/blank_statistik.png">-->
<table width="100%" border="0">
  <tr>
    <td>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#666666">
  <tr>
    <td height="16" colspan="2" bgcolor="#FFFFFF"><div align="center" class="style13 style2"><strong>NILAI HASIL
          BELAJAR</strong></div></td>
    </tr>
  <tr>
    <td height="20"><span class="style5">Departemen</span></td>
    <td height="20"><span class="style5">:&nbsp;
        <?=$departemen?>
    </span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Tahun Ajaran</span></td>
    <?php
   
	
	?><td height="20"><span class="style5">:&nbsp;
        <?=$row_get_ta['tahunajaran']?>
    </span></td>
  </tr>
  <tr>
    <td width="6%" height="20"><span class="style5">NIS 
    </span></td>
    <td width="93%" height="20"><span class="style5">:&nbsp;
        <?=$nis?>
    </span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Nama</span></td>
    <?php
	
	
	?>
	<td height="20"><span class="style5">:&nbsp;
	    <?=$row_get_nama['nama']?>
	</span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Kelas/Semester&nbsp;</span></td>
    
    <?php
   
	?>
    <td height="20"><span class="style5">:&nbsp;
        <?=$row_get_kls['kelas']."/".$row_get_sem['semester']?>
    </span></td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td>
    
    <table width="100%" border="1" cellpadding="0" cellspacing="0" class="tab" style="font-family:Verdana, Geneva, sans-serif; font-size:12px;">
	<tr>
	    <td width="4%" rowspan="2" bgcolor="#CCCCCC"><div align="center"><strong>No</strong></div></td>
		<td width="20%" rowspan="2" bgcolor="#CCCCCC"><div align="center"><strong>Pelajaran</strong></div></td>
     	<td width="7%" rowspan="2" bgcolor="#CCCCCC"><div align="center"><strong>KKM</strong></div></td>
		<td width="20%" rowspan="2" bgcolor="#CCCCCC"><div align="center"><strong>Aspek<br>Penilaian</strong></div></td>
        <td width="35%" colspan="3" bgcolor="#CCCCCC"><div align="center"><strong>Nilai</strong></div></td>
  	</tr>
    <tr>
    	<td width="7%" bgcolor="#CCCCCC"><div align="center"><strong>Angka</strong></div></td>
        <td width="7%" bgcolor="#CCCCCC"><div align="center"><strong>Huruf</strong></div></td>
        <td width="25%" bgcolor="#CCCCCC"><div align="center"><strong>Bilang</strong></div></td>
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
    <td rowspan="2" width="33%"><div align="center">Orang Tua/Wali Siswa</div></td>
    <td width="33%"><div align="center">Mengetahui,</div></td>
    <td rowspan="2" width="33%"><div align="center">Wali Kelas</div></td>
  </tr>
  <tr>
    <td width="33%"><div align="center">Kepala Sekolah 
      <?=$departemen?>
    </div></td>
  </tr>
  <tr>
    <td width="33%" height="50"><div align="center"></div></td>
    <td width="33%" height="50"><div align="center"></div></td>
    <td width="33%" height="50"><div align="center"></div></td>
  </tr>
  <tr>
    <td width="33%" rowspan="2"><div align="center">(.............................................)</div></td>
    <td width="33%"><div align="center">
      <u><?=$row_get_kepsek['namakepsek']?></u>
    </div></td>
    <td width="33%"><div align="center">
      <u><?=$row_get_w_kls['namawalikelas']?></u>
    </div></td>
  </tr>
  <tr>
    <td><div align="center">
      NIP : <?=$row_get_kepsek['nipkepsek']?>
    </div></td>
    <td width="33%"><div align="center">
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

<div class=Section2>
<table width="100%" border="0">
  <tr>
    <td>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#666666">
  <tr>
    <td height="16" colspan="2" bgcolor="#FFFFFF"><div align="center" class="style13 style2"><strong>KOMENTAR
        HASIL BELAJAR</strong></div></td>
    </tr>
  <tr>
    <td height="20"><span class="style5">Departemen</span></td>
    <td height="20"><span class="style5">:&nbsp;
        <?=$departemen?>
    </span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Tahun Ajaran</span></td>
    <?php
   
	
	?><td height="20"><span class="style5">:&nbsp;
        <?=$row_get_ta['tahunajaran']?>
    </span></td>
  </tr>
  <tr>
    <td width="6%" height="20"><span class="style5">NIS 
    </span></td>
    <td width="93%" height="20"><span class="style5">:&nbsp;
        <?=$nis?>
    </span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Nama</span></td>
    <?php
	
	
	?>
	<td height="20"><span class="style5">:&nbsp;
	    <?=$row_get_nama['nama']?>
	</span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Kelas/Semester&nbsp;</span></td>
    
    <?php
   
	?>
    <td height="20"><span class="style5">:&nbsp;
        <?=$row_get_kls['kelas']."/".$row_get_sem['semester']?>
    </span></td>
  </tr>
</table>
	</td>
  </tr>
  <tr>
    <td>
        
    <table border="1" id="table" bordercolor="#b8b8b8" class="tab" width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="27%" height="30" align="center" bgcolor="#b8b8b8" class="header"><strong>Pelajaran</strong></td>
	<td width="73%" height="30" align="center" bgcolor="#b8b8b8" class="header"><strong>Komentar</strong></td>
	</tr>
	<!-- Ambil pelajaran per departemen-->
	<?php
	$sql = "SELECT pel.replid as replid,pel.nama as nama 
	          FROM infonap info, komennap komen, siswa sis, pelajaran pel 
			 WHERE info.replid = komen.idinfo 
			   AND komen.nis = sis.nis 
			   AND info.idpelajaran = pel.replid 
			   AND info.idsemester = '$semester' 
			   AND info.idkelas = '$kelas' 
			   AND sis.nis = '$nis' 
		  GROUP BY pel.nama";
	$res = QueryDb($sql);
	$cntpel_komentar = 1;
	
	while ($row = @mysqli_fetch_array($res))
	{
		$sql = "SELECT k.komentar 
		          FROM jbsakad.komennap k, jbsakad.infonap i 
				 WHERE k.nis='$nis' AND i.idpelajaran='".$row['replid']."' AND i.replid=k.idinfo 
				   AND i.idsemester='$semester' AND i.idkelas='$kelas'";
      
		$res2 = QueryDb($sql);
		$row2 = @mysqli_fetch_row($res2); ?>
	<tr>
	<td height="25"><?=$row['nama']?></td>
	<td height="25"><?=$row2[0]?></td>
	</tr>
<?php 	$cntpel_komentar++;
	}
	?>
	</table>

	</td>
  </tr>
  <tr>
    <td>
	
	 <table width="100%" border="0">
  <tr>
    <td rowspan="2" width="33%"><div align="center">Orang Tua/Wali Siswa</div></td>
    <td width="33%"><div align="center">Mengetahui,</div></td>
    <td rowspan="2" width="33%"><div align="center">Wali Kelas</div></td>
  </tr>
  <tr>
    <td width="33%"><div align="center">Kepala Sekolah 
      <?=$departemen?>
    </div></td>
  </tr>
  <tr>
    <td width="33%" height="50"><div align="center"></div></td>
    <td width="33%" height="50"><div align="center"></div></td>
    <td width="33%" height="50"><div align="center"></div></td>
  </tr>
  <tr>
    <td width="33%" rowspan="2"><div align="center">(.............................................)</div></td>
    <td width="33%"><div align="center">
      <u><?=$row_get_kepsek['namakepsek']?></u>
    </div></td>
    <td width="33%"><div align="center">
      <u><?=$row_get_w_kls['namawalikelas']?></u>
    </div></td>
  </tr>
  <tr>
    <td><div align="center">
      NIP : <?=$row_get_kepsek['nipkepsek']?>
    </div></td>
    <td width="33%"><div align="center">
      NIP : <?=$row_get_w_kls['nipwalikelas']?>
    </div></td>
  </tr>
</table>

	</td>
  </tr>
</table>
</div>
<?php if ($prespel!="false"){ ?>
<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:
"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='page-break-before:always;
mso-break-type:section-break'>
</span>


<div class=Section3>
<!--                       Section 3                                   -->
<table width="100%" border="0">
  <tr>
    <td>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#666666">
  <tr>
    <td height="16" colspan="2" bgcolor="#FFFFFF"><div align="center" class="style1 style13">PRESENSI PELAJARAN</div></td>
    </tr>
  <tr>
    <td height="20"><span class="style5">Departemen</span></td>
    <td height="20"><span class="style5">:&nbsp;
        <?=$departemen?>
    </span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Tahun Ajaran</span></td>
    <?php
   
	
	?><td height="20"><span class="style5">:&nbsp;
        <?=$row_get_ta['tahunajaran']?>
    </span></td>
  </tr>
  <tr>
    <td width="6%" height="20"><span class="style5">NIS 
    </span></td>
    <td width="93%" height="20"><span class="style5">:&nbsp;
        <?=$nis?>
    </span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Nama</span></td>
    <?php
	
	
	?>
	<td height="20"><span class="style5">:&nbsp;
	    <?=$row_get_nama['nama']?>
	</span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Kelas/Semester&nbsp;</span></td>
    
    <?php
   
	?>
    <td height="20"><span class="style5">:&nbsp;
        <?=$row_get_kls['kelas']."/".$row_get_sem['semester']?>
    </span></td>
  </tr>
</table>
	</td>
  </tr>
  <tr>
    <td>

	<table width="100%" border="1" bordercolor="#b8b8b8" class="tab" id="table" cellspacing="0" cellpadding="0">
  <tr>
    <td width="27%" rowspan="2" bgcolor="#b8b8b8" ><div align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Pelajaran</strong></font></div></td>
    <td height="25" colspan="2" bgcolor="#b8b8b8" ><div align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Hadir</strong></font></div></td>
    <td height="25" colspan="2" bgcolor="#b8b8b8" ><div align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Sakit</strong></font></div></td>
    <td height="25" colspan="2" bgcolor="#b8b8b8" ><div align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Ijin</strong></font></div></td>
    <td height="25" colspan="2" bgcolor="#b8b8b8" ><div align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Alpa</strong></font></div></td>
    </tr>
  <tr>
    <td width="6" bgcolor="#b8b8b8" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center" ><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
  </tr>
  <!-- Ambil pelajaran per departemen-->
	<?php
	$sql_get_pelajaran_presensi="SELECT pel.replid as replid,pel.nama as nama FROM presensipelajaran ppel, ppsiswa pp, siswa sis, pelajaran pel ".
								"WHERE pp.nis=sis.nis ".
								"AND ppel.replid=pp.idpp ".
								"AND ppel.idpelajaran=pel.replid ".
								"AND ppel.idsemester='$semester' ".
								"AND ppel.idkelas='$kelas' ".
								"AND sis.nis='$nis' ".
								"GROUP BY pel.nama";
	$result_get_pelajaran_presensi=QueryDb($sql_get_pelajaran_presensi);
	$cntpel_presensi=1;
	
	while ($row_get_pelajaran_presensi=@mysqli_fetch_array($result_get_pelajaran_presensi)){
	//ambil semua jumlah presensi per pelajaran 
	$sql_get_all_presensi="select count(*) as jumlah FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='$nis'";
	$result_get_all_presensi=QueryDb($sql_get_all_presensi);
	$row_get_all_presensi=@mysqli_fetch_array($result_get_all_presensi);
	//dapet nih jumlahnya
	$jumlah_presensi=$row_get_all_presensi['jumlah'];

	//ambil yang hadir
	$sql_get_hadir="select count(*) as hadir FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=0";
	$result_get_hadir=QueryDb($sql_get_hadir);
	$row_get_hadir=@mysqli_fetch_array($result_get_hadir);
	$hadir=$row_get_hadir['hadir'];
	$hh[$cntpel_presensi]=$hadir;	;
	//ambil yang sakit
	$sql_get_sakit="select count(*) as sakit FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=1";
	$result_get_sakit=QueryDb($sql_get_sakit);
	$row_get_sakit=@mysqli_fetch_array($result_get_sakit);
	$sakit=$row_get_sakit['sakit'];
	$ss[$cntpel_presensi]=$sakit;	
	//ambil yang ijin
	$sql_get_ijin="select count(*) as ijin FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=2";
	$result_get_ijin=QueryDb($sql_get_ijin);
	$row_get_ijin=@mysqli_fetch_array($result_get_ijin);
	$ijin=$row_get_ijin['ijin'];
	$ii[$cntpel_presensi]=$ijin;	
	//ambil yang alpa
	$sql_get_alpa="select count(*) as alpa FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
						  "WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' AND pel.idsemester='$semester' AND pel.idkelas='$kelas' ".
		                  "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=3";
	$result_get_alpa=QueryDb($sql_get_alpa);
	$row_get_alpa=@mysqli_fetch_array($result_get_alpa);
	$alpa=$row_get_alpa['alpa'];
	$aa[$cntpel_presensi]=$alp;
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
    <td height="25"><span class="style5"><?=$row_get_pelajaran_presensi['nama']?></span></td>
    <td height="25"><div align="center">
      <span class="style5"><?=$hadir?></span>
    </div></td>
    <td height="25"><div align="center">
      <span class="style5"><?=$p_hadir?>
      &nbsp;%</span></div></td>
    <td height="25"><div align="center">
      <span class="style5"><?=$sakit?></span>
    </div></td>
    <td height="25"><div align="center">
      <span class="style5"><?=$p_sakit?>
      &nbsp;%</span></div></td>
    <td height="25"><div align="center">
      <span class="style5"><?=$ijin?></span>
    </div></td>
    <td height="25"><div align="center">
      <span class="style5"><?=$p_ijin?>
      &nbsp;%</span></div></td>
    <td height="25"><div align="center">
      <span class="style5"><?=$alpa?></span>
    </div></td>
    <td height="25"><div align="center">
      <span class="style5"><?=$p_alpa?>
      &nbsp;%</span></div></td>
	 </tr>
	<?php
	$cntpel_presensi++;
	}
	$hdr = 0;
	for ($i=1;$i<=count($hh);$i++)
		$hdr += $hh[$i];
	$skt = 0;
	for ($i=1;$i<=count($ss);$i++)
		$skt += $ss[$i];
	$ijn = 0;
	for ($i=1;$i<=count($ii);$i++)
		$ijn += $ii[$i];
	$alp = 0;
	for ($i=1;$i<=count($aa);$i++)
		$alp += $aa[$i];
	/*
	//sekarang hitung jumlah hadir semua pelajaran
	$sql_all_hadir="select count(*) as allhadir FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='22' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=0";
    $result_all_hadir=QueryDb($sql_all_hadir);
	$row_all_hadir=@mysqli_fetch_array($result_all_hadir);
	$all_hadir=$row_all_hadir['allhadir'];
	
	//sekarang hitung jumlah sakit semua pelajaran
	$sql_all_sakit="select count(*) as allsakit FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='22' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=1";
    $result_all_sakit=QueryDb($sql_all_sakit);
	$row_all_sakit=@mysqli_fetch_array($result_all_sakit);
	$all_sakit=$row_all_sakit['allsakit'];

	//sekarang hitung jumlah ijin semua pelajaran
	$sql_all_ijin="select count(*) as allijin FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='22' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=2";
    $result_all_ijin=QueryDb($sql_all_ijin);
	$row_all_ijin=@mysqli_fetch_array($result_all_ijin);
	$all_ijin=$row_all_ijin['allijin'];

	//sekarang hitung jumlah alpa semua pelajaran
	$sql_all_alpa="select count(*) as allalpa FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp ".
	               "WHERE pel.idsemester='22' AND pel.idkelas='$kelas' ". 
                   "AND pel.replid=pp.idpp AND pp.nis='$nis' AND pp.statushadir=3";
    $result_all_alpa=QueryDb($sql_all_alpa);
	$row_all_alpa=@mysqli_fetch_array($result_all_alpa);
	$all_alpa=$row_all_alpa['allalpa'];
	*/
	?>
  <tr>
    <td height="25" bgcolor="#b8b8b8" align="center"><div class="style20">Total</div></td>
    <td height="25" bgcolor="#FFFFFF" align="center"><div class="style21">
      <span class="style5"><?=$hdr?></span>
    </div></td>
    <td height="25" bgcolor="#b8b8b8" align="center"><div ><span class="style22"></span></div></td>
    <td height="25" bgcolor="#FFFFFF" align="center"><div class="style22"><strong>
      <span class="style5"><?=$skt?></span>
    </strong></div></td>
    <td height="25" bgcolor="#b8b8b8" align="center"><div ><span class="style22"></span></div></td>
    <td height="25" bgcolor="#FFFFFF" align="center"><div class="style22"><strong>
      <span class="style5"><?=$ijn?></span>
    </strong></div></td>
    <td height="25" bgcolor="#b8b8b8" align="center"><div ><span class="style22"></span></div></td>
    <td height="25" bgcolor="#FFFFFF" align="center"><div class="style22"><strong>
      <span class="style5"><?=$alp?></span>
    </strong></div></td>
    <td height="25" bgcolor="#b8b8b8" align="center"><div ><span class="style22"></span></div></td>
  </tr>
</table>
	
	</td>
  </tr>
  <tr>
    <td>
	 <table width="100%" border="0">
  <tr>
    <td rowspan="2" width="33%"><div align="center">Orang Tua/Wali Siswa</div></td>
    <td width="33%"><div align="center">Mengetahui,</div></td>
    <td rowspan="2" width="33%"><div align="center">Wali Kelas</div></td>
  </tr>
  <tr>
    <td width="33%"><div align="center">Kepala Sekolah 
      <?=$departemen?>
    </div></td>
  </tr>
  <tr>
    <td width="33%" height="50"><div align="center"></div></td>
    <td width="33%" height="50"><div align="center"></div></td>
    <td width="33%" height="50"><div align="center"></div></td>
  </tr>
  <tr>
    <td width="33%" rowspan="2"><div align="center">(.............................................)</div></td>
    <td width="33%"><div align="center">
      <u><?=$row_get_kepsek['namakepsek']?></u>
    </div></td>
    <td width="33%"><div align="center">
      <u><?=$row_get_w_kls['namawalikelas']?></u>
    </div></td>
  </tr>
  <tr>
    <td><div align="center">
      NIP : <?=$row_get_kepsek['nipkepsek']?>
    </div></td>
    <td width="33%"><div align="center">
      NIP : <?=$row_get_w_kls['nipwalikelas']?>
    </div></td>
  </tr>
</table>
	</td>
  </tr>
</table>

</div>
<?php } 
if ($harian!="false"){ 
?>
<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:
"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='page-break-before:always;
mso-break-type:section-break'>
</span>
<div class=Section4>
<!--                       Section 4                                   -->
<table width="100%" border="0">
  <tr>
    <td>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#666666">
  <tr>
    <td height="16" colspan="2" bgcolor="#FFFFFF"><div align="center" class="style1 style13">PRESENSI HARIAN</div></td>
    </tr>
  <tr>
    <td height="20"><span class="style5">Departemen</span></td>
    <td height="20"><span class="style5">:&nbsp;
        <?=$departemen?>
    </span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Tahun Ajaran</span></td>
    <?php
   
	
	?><td height="20"><span class="style5">:&nbsp;
        <?=$row_get_ta['tahunajaran']?>
    </span></td>
  </tr>
  <tr>
    <td width="6%" height="20"><span class="style5">NIS 
    </span></td>
    <td width="93%" height="20"><span class="style5">:&nbsp;
        <?=$nis?>
    </span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Nama</span></td>
    <?php
	
	
	?>
	<td height="20"><span class="style5">:&nbsp;
	    <?=$row_get_nama['nama']?>
	</span></td>
  </tr>
  <tr>
    <td height="20"><span class="style5">Kelas/Semester&nbsp;</span></td>
    
    <?php
   
	?>
    <td height="20"><span class="style5">:&nbsp;
        <?=$row_get_kls['kelas']."/".$row_get_sem['semester']?>
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
	  ?>
	<!-- Content Presensi disini -->
	<table width="100%" border="1" class="tab" id="table" cellpadding="0" cellspacing="0" bordercolor="#b8b8b8">
  <tr>
    <td height="25" colspan="2" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>Hadir</strong></font></td>
    <td height="25" colspan="2" bgcolor="#b8b8b8" align="center" class="headerlong"><font face="Verdana" size="2" color="#000000" ><strong>Sakit</strong></font></td>
    <td height="25" colspan="2" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>Ijin</strong></font></td>
    <td height="25" colspan="2" bgcolor="#b8b8b8" align="center" class="headerlong"><font face="Verdana" size="2" color="#000000" ><strong>Alpa</strong></font></td>
    <td height="25" colspan="2" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>Cuti</strong></font></td>
    </tr>
  <tr>
    <td width="6" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>Jumlah</strong></font></td>
    <td width="6" bgcolor="#b8b8b8" align="center"><font face="Verdana" size="2" color="#000000" ><strong>%</strong></font></td>
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
	  <td height="25" bgcolor="#FFFFFF"><div align="center" class="style13">
	    <?=$hadir?>
	      </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center" class="style13">
      <?=round($p_hadir,2)?>%</div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center" class="style13">
      <?=$sakit?>
    </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center" class="style13">
      <?=round($p_sakit,2)?>%</div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center" class="style13">
      <?=$ijin?>
    </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center" class="style13">
      <?=round($p_ijin,2)?>%</div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center" class="style13">
      <?=$alpa?>
    </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center" class="style13">
      <?=round($p_alpa,2)?>%</div></td>
      <td height="25" bgcolor="#FFFFFF"><div align="center" class="style13">
      <?=$cuti?>
    </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center" class="style13">
      <?=round($p_cuti,2)?>%</div></td>
	 </tr>
</table>
	
	</td>
  </tr>
  <tr>
    <td>
	 <table width="100%" border="0">
  <tr>
    <td rowspan="2" width="33%"><div align="center">Orang Tua/Wali Siswa</div></td>
    <td width="33%"><div align="center">Mengetahui,</div></td>
    <td rowspan="2" width="33%"><div align="center">Wali Kelas</div></td>
  </tr>
  <tr>
    <td width="33%"><div align="center">Kepala Sekolah 
      <?=$departemen?>
    </div></td>
  </tr>
  <tr>
    <td width="33%" height="50"><div align="center"></div></td>
    <td width="33%" height="50"><div align="center"></div></td>
    <td width="33%" height="50"><div align="center"></div></td>
  </tr>
  <tr>
    <td width="33%" rowspan="2"><div align="center">(.............................................)</div></td>
    <td width="33%"><div align="center">
      <u><?=$row_get_kepsek['namakepsek']?></u>
    </div></td>
    <td width="33%"><div align="center">
      <u><?=$row_get_w_kls['namawalikelas']?></u>
    </div></td>
  </tr>
  <tr>
    <td><div align="center">
      NIP : <?=$row_get_kepsek['nipkepsek']?>
    </div></td>
    <td width="33%"><div align="center">
      NIP : <?=$row_get_w_kls['nipwalikelas']?>
    </div></td>
  </tr>
</table>
	</td>
  </tr>
</table>
</div>
<?php } ?>
</body>

</html>