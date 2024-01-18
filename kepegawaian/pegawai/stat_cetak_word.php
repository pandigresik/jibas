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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
//require_once('../include/theme.php');

$stat = $_REQUEST['stat'];
if ($stat==6 || $stat==7 || $stat==5){
	require_once("../include/chartfactory.php");
	require_once("../include/as-diagrams.php");
}
switch ($stat){
	case 1 : $judul="SATUAN KERJA";
	break;
	case 2 : $judul="PENDIDIKAN SEKOLAH";
	break;
	case 3 : $judul="GOLONGAN";
	break;
	case 4 : $judul="USIA";
	break;
	case 5 : $judul="DIKLAT";
	break;
	case 6 : $judul="JENIS KELAMIN";
	break;
	case 7 : $judul="STATUS PERKAWINAN";
	break;
}
header('Content-Type: application/vnd.ms-word'); //IE and Opera  
header('Content-Type: application/w-msword'); // Other browsers  
header('Content-Disposition: attachment; filename=Statistik_Pegawai.doc');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
?>
<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 12">
<meta name=Originator content="Microsoft Word 12">
<link rel=File-List href="Hal%201_files/filelist.xml">
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>comp</o:Author>
  <o:LastAuthor>comp</o:LastAuthor>
  <o:Revision>2</o:Revision>
  <o:TotalTime>2</o:TotalTime>
  <o:Created>2008-12-20T14:37:00Z</o:Created>
  <o:LastSaved>2008-12-20T14:37:00Z</o:LastSaved>
  <o:Pages>2</o:Pages>
  <o:Words>6</o:Words>
  <o:Characters>36</o:Characters>
  <o:Lines>1</o:Lines>
  <o:Paragraphs>1</o:Paragraphs>
  <o:CharactersWithSpaces>41</o:CharactersWithSpaces>
  <o:Version>12.00</o:Version>
 </o:DocumentProperties>
</xml><![endif]-->
<link rel=themeData href="Hal%201_files/themedata.thmx">
<link rel=colorSchemeMapping href="Hal%201_files/colorschememapping.xml">
<!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:SpellingState>Clean</w:SpellingState>
  <w:GrammarState>Clean</w:GrammarState>
  <w:TrackMoves>false</w:TrackMoves>
  <w:TrackFormatting/>
  <w:PunctuationKerning/>
  <w:DrawingGridHorizontalSpacing>5.5 pt</w:DrawingGridHorizontalSpacing>
  <w:DisplayHorizontalDrawingGridEvery>2</w:DisplayHorizontalDrawingGridEvery>
  <w:ValidateAgainstSchemas/>
  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
  <w:DoNotPromoteQF/>
  <w:LidThemeOther>EN-GB</w:LidThemeOther>
  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>
  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>
  <w:Compatibility>
   <w:BreakWrappedTables/>
   <w:SnapToGridInCell/>
   <w:WrapTextWithPunct/>
   <w:UseAsianBreakRules/>
   <w:DontGrowAutofit/>
   <w:DontUseIndentAsNumberingTabStop/>
   <w:FELineBreak11/>
   <w:WW11IndentRules/>
   <w:DontAutofitConstrainedTables/>
   <w:AutofitLikeWW11/>
   <w:HangulWidthLikeWW11/>
   <w:UseNormalStyleForList/>
  </w:Compatibility>
  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel>
  <m:mathPr>
   <m:mathFont m:val="Cambria Math"/>
   <m:brkBin m:val="before"/>
   <m:brkBinSub m:val="--"/>
   <m:smallFrac m:val="off"/>
   <m:dispDef/>
   <m:lMargin m:val="0"/>
   <m:rMargin m:val="0"/>
   <m:defJc m:val="centerGroup"/>
   <m:wrapIndent m:val="1440"/>
   <m:intLim m:val="subSup"/>
   <m:naryLim m:val="undOvr"/>
  </m:mathPr></w:WordDocument>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="true"
  DefSemiHidden="true" DefQFormat="false" DefPriority="99"
  LatentStyleCount="267">
  <w:LsdException Locked="false" Priority="0" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Normal"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="heading 1"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 2"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 3"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 4"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 5"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 6"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 7"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 8"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 9"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 1"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 2"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 3"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 4"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 5"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 6"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 7"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 8"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 9"/>
  <w:LsdException Locked="false" Priority="35" QFormat="true" Name="caption"/>
  <w:LsdException Locked="false" Priority="10" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Title"/>
  <w:LsdException Locked="false" Priority="1" Name="Default Paragraph Font"/>
  <w:LsdException Locked="false" Priority="11" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtitle"/>
  <w:LsdException Locked="false" Priority="22" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Strong"/>
  <w:LsdException Locked="false" Priority="20" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Emphasis"/>
  <w:LsdException Locked="false" Priority="59" SemiHidden="false"
   UnhideWhenUsed="false" Name="Table Grid"/>
  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Placeholder Text"/>
  <w:LsdException Locked="false" Priority="1" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="No Spacing"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 1"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 1"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 1"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 1"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 1"/>
  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Revision"/>
  <w:LsdException Locked="false" Priority="34" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="List Paragraph"/>
  <w:LsdException Locked="false" Priority="29" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Quote"/>
  <w:LsdException Locked="false" Priority="30" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Quote"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 1"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 1"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 1"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 1"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 1"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 1"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 2"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 2"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 2"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 2"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 2"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 2"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 2"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 2"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 3"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 3"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 3"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 3"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 3"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 3"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 3"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 3"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 4"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 4"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 4"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 4"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 4"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 4"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 4"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 4"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 5"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 5"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 5"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 5"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 5"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 5"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 5"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 5"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 6"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 6"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 6"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 6"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 6"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 6"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 6"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 6"/>
  <w:LsdException Locked="false" Priority="19" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtle Emphasis"/>
  <w:LsdException Locked="false" Priority="21" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Emphasis"/>
  <w:LsdException Locked="false" Priority="31" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtle Reference"/>
  <w:LsdException Locked="false" Priority="32" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Reference"/>
  <w:LsdException Locked="false" Priority="33" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Book Title"/>
  <w:LsdException Locked="false" Priority="37" Name="Bibliography"/>
  <w:LsdException Locked="false" Priority="39" QFormat="true" Name="TOC Heading"/>
 </w:LatentStyles>
</xml><![endif]-->
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:"Cambria Math";
	panose-1:2 4 5 3 5 4 6 3 2 4;
	mso-font-charset:1;
	mso-generic-font-family:roman;
	mso-font-format:other;
	mso-font-pitch:variable;
	mso-font-signature:0 0 0 0 0 0;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-1610611985 1073750139 0 0 159 0;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-parent:"";
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:0cm;
	line-height:115%;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";
	mso-fareast-font-family:Calibri;
	mso-bidi-font-family:"Times New Roman";
	mso-fareast-language:EN-US;}
span.SpellE
	{mso-style-name:"";
	mso-spl-e:yes;}
.MsoChpDefault
	{mso-style-type:export-only;
	mso-default-props:yes;
	mso-ascii-font-family:Calibri;
	mso-fareast-font-family:Calibri;
	mso-hansi-font-family:Calibri;}
@page Section1
	{size:1008.0pt 612.0pt;
	mso-page-orientation:landscape;
	margin:36.0pt 36.0pt 36.0pt 36.0pt;
	mso-header-margin:35.4pt;
	mso-footer-margin:35.4pt;
	mso-paper-source:0;}
div.Section1
	{page:Section1;}
.style19 {
	font-family: Arial;
	font-size: 12px;
	font-weight: bold;
}
.style68 {font-size: 9; font-weight: bold; color: #FFFFFF; }
.style69 {
	color: #FFFFFF;
	font-weight: bold;
}
.style70 {color: #FFFFFF}
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
	mso-style-priority:99;
	mso-style-qformat:yes;
	mso-style-parent:"";
	mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
	mso-para-margin:0cm;
	mso-para-margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Calibri","sans-serif";}
table.MsoTableGrid
	{mso-style-name:"Table Grid";
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-priority:59;
	mso-style-unhide:no;
	border:solid black 1.0pt;
	mso-border-alt:solid black .5pt;
	mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
	mso-border-insideh:.5pt solid black;
	mso-border-insidev:.5pt solid black;
	mso-para-margin:0cm;
	mso-para-margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Calibri","sans-serif";}
</style>
<![endif]--><!--[if gte mso 9]><xml>
 <o:shapedefaults v:ext="edit" spidmax="2050"/>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <o:shapelayout v:ext="edit">
  <o:idmap v:ext="edit" data="1"/>
 </o:shapelayout></xml><![endif]-->
</head>

<body lang=EN-GB style='tab-interval:36.0pt'>
<div class=Section1>
<p class=MsoNormal><o:p>&nbsp;</o:p></p>
<table width="1150" border="0" cellspacing="0" align="center">
  <tr>
    <td colspan="2" align="center"><span class="style19">GRAFIK JUMLAH PEGAWAI BERDASARKAN <?=$judul?></span></td>
    </tr>
</table>
<p class=MsoNormal><o:p>&nbsp;</o:p></p>
<p class=MsoNormal><o:p>&nbsp;</o:p></p>
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td align="center">
    <?php
    if ($stat==6){
    	$sql = "SELECT j.satker, SUM(IF(p.kelamin = 'L', 1, 0)) AS Pria, SUM(IF(p.kelamin = 'P', 1, 0)) AS Wanita
				  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
				  WHERE p.aktif = 1 AND p.pns = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid
				  GROUP BY j.satker HAVING NOT j.satker IS NULL";
		
		OpenDb();
		$result = QueryDb($sql);
		while($row = mysqli_fetch_row($result)) {
			$data[] = [$row[1], $row[2]];
			$legend_x[] = $row[0];
		}
		CloseDb();
		
		$legend_y = ["Pria", "Wanita"];
		
		$title = "<font face='Arial' size='-1' color='black'>Jumlah Pegawai Berdasarkan Jenis Kelamin</font>"; // title for the diagram
		
		$graph = new CAsBarDiagram;
		$graph->bwidth = 10; // set one bar width, pixels
		$graph->bt_total = 'Total'; // 'totals' column title, if other than 'Totals'
		// $graph->showtotals = 0;  // uncomment it if You don't need 'totals' column
		$graph->precision = 0;  // decimal precision
		// call drawing function
		$graph->DiagramBar($legend_x, $legend_y, $data, $title);

    } elseif ($stat==7){
    
		$sql = "SELECT j.satker, SUM(IF(p.nikah = 'Nikah', 1, 0)) AS Nikah, SUM(IF(p.nikah = 'Belum Nikah', 1, 0)) AS Belum
				  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
				  WHERE p.aktif = 1 AND p.pns = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid
				  GROUP BY j.satker HAVING NOT j.satker IS NULL";
		
		OpenDb();
		$result = QueryDb($sql);
		while($row = mysqli_fetch_row($result)) {
			$data[] = [$row[1], $row[2]];
			$legend_x[] = $row[0];
		}
		CloseDb();
		
		$legend_y = ["Nikah", "Belum"];
		
		$title = "<font face='Arial' size='-1' color='black'>Jumlah Pegawai Berdasarkan Status Pernikahan</font>"; 
		
		$graph = new CAsBarDiagram;
		$graph->bwidth = 10; // set one bar width, pixels
		$graph->bt_total = 'Total'; // 'totals' column title, if other than 'Totals'
		// $graph->showtotals = 0;  // uncomment it if You don't need 'totals' column
		$graph->precision = 0;  // decimal precision
		// call drawing function
		$graph->DiagramBar($legend_x, $legend_y, $data, $title);
	
    } elseif ($stat==5) {
		$sql = "SELECT j.eselon, SUM(IF(NOT pl.idpegdiklat IS NULL, 1, 0)) AS Sudah, SUM(IF(pl.idpegdiklat IS NULL, 1, 0)) AS Belum
				FROM   pegawai p, peglastdata pl, pegjab pj, jabatan j, jenisjabatan jj
				WHERE p.aktif = 1 AND p.pns = 1 AND pl.nip = p.nip AND pl.idpegjab = pj.replid
				AND pj.idjabatan = j.replid AND pj.jenis = jj.jenis AND jj.jabatan = 'S' GROUP BY j.eselon ORDER BY j.eselon";
		
		OpenDb();
		$result = QueryDb($sql);
		while($row = mysqli_fetch_row($result)) {
			$data[] = [$row[1], $row[2]];
			$legend_x[] = $row[0];
		}
		CloseDb();
		
		$legend_y = ["Sudah", "Belum"];
		
		$title = "<font face='Arial' size='-1' color='black'>Jumlah Pejabat Struktural Berdasarkan Diklat</font>"; // title for the diagram
		
		$graph = new CAsBarDiagram;
		$graph->bwidth = 10; // set one bar width, pixels
		$graph->bt_total = 'Total'; // 'totals' column title, if other than 'Totals'
		// $graph->showtotals = 0;  // uncomment it if You don't need 'totals' column
		$graph->precision = 0;  // decimal precision
		// call drawing function
		$graph->DiagramBar($legend_x, $legend_y, $data, $title);
    } else {
	?>
		<img width="550" width="300" src='<?= $url . "statimage.php?dotype=bar-$stat" ?>' />
	<?php } ?>
    </td>
    <td align="center"><img width="550" width="300" src='<?= $url . "statimage.php?dotype=pie-$stat" ?>' /></td>
  </tr>
</table>
<span style='font-size:11.0pt;line-height:115%;font-family:"Calibri","sans-serif";
mso-fareast-font-family:Calibri;mso-bidi-font-family:"Times New Roman";
mso-ansi-language:EN-GB;mso-fareast-language:EN-US;mso-bidi-language:AR-SA'><br
clear=all style='mso-special-character:line-break;page-break-before:always'>
</span>
<?php if ($stat!=5){ ?>
<p class=MsoNormal><o:p>&nbsp;</o:p></p>
<?php } ?>
<p class=MsoNormal><o:p>&nbsp;</o:p></p>
<table width="1150" border="0" cellspacing="0" align="center">
  <tr>
    <td colspan="2" align="center"><span class="style19">REKAP JUMLAH PEGAWAI BERDASARKAN <?=$judul?></span></td>
    </tr>
</table>
<?php if ($stat!=5){ ?>
<p class=MsoNormal><o:p>&nbsp;</o:p></p>
<?php } ?>
<p class=MsoNormal><o:p>&nbsp;</o:p></p>
<?php if ($stat==1){ ?>
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="80%" align="center">
<tr height="30">
	<td width="5%" align="center" bgcolor="#666666" class="style2 header"><span class="style68">No</span></td>
    <td width="40%" align="center" bgcolor="#666666" class="style2 header"><span class="style68">Satuan Kerja</span></td>
    <td width="15%" align="center" bgcolor="#666666" class="style2 header"><span class="style68">Jumlah<br>
      Pegawai</span></td>
    <td width="20%" align="center" bgcolor="#666666" class="style2 header"><span class="style68">Persentase</span></td>
</tr>
<?php
OpenDb();
$sql = "SELECT j.satker, COUNT(p.nip) FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
     	  WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND NOT j.satker IS NULL
		  GROUP BY j.satker";	
$result = QueryDb($sql);
$cnt = 0;
$total = 0;
while ($row = mysqli_fetch_row($result)) 
{
	$cnt++;
	$data[$cnt-1][0] = $row[0];
	$data[$cnt-1][1] = $row[1];
	$total += $row[1];
}
CloseDb();

for($i = 0; $i < $cnt; $i++)
{
	$pct = "";
	if ($data[$i][1] > 0)
		$pct = round($data[$i][1] / $total, 2) * 100;
		
?>
<tr height="25">
	<td align="center" valign="top"><?=$i+1?></td>
    <td align="center" valign="top"><?=$data[$i][0]?></td>
    <td align="center" valign="top"><?=$data[$i][1]?></td>
    <td align="center" valign="top"><?=$pct?>%</td>
</tr>
<?php
}
?>
<tr height="30">
	<td style="background-color:#CCCCCC" align="center" valign="middle">&nbsp;</td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong>JUMLAH</strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$total?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong>100%</strong></td>
</tr>
</table>
<?php } ?>

<?php
if ($stat==2){
OpenDb();
$sql = "SELECT tingkat FROM pendidikan ORDER BY urutan";
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) {
	$tingkat[] = $row[0];
}
$width = floor(60 / count($tingkat));

$sql = "SELECT satker FROM satker";
$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) {
	$satker[] = $row['satker'];
}

$sql = "SELECT j.satker, ps.tingkat, COUNT(p.nip) AS cnt FROM
        pegawai p, peglastdata pl, pegsekolah ps, pegjab pj, jabatan j, pendidikan pd
        WHERE p.aktif = 1 AND p.pns = 1 AND p.nip = pl.nip AND pl.idpegsekolah = ps.replid AND ps.tingkat = pd.tingkat AND
        pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND NOT j.satker IS NULL GROUP BY j.satker, ps.sekolah 
        ORDER BY pd.urutan";
$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) {
	$s = $row['satker'];
	$t = $row['tingkat'];
	$data[$s][$t] = $row['cnt'];
}
?>
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="80%" align="center">
<tr height="25">
	<td width="5%" align="center" bgcolor="#666666" class="style2 header"><span class="style69">No</span></td>
    <td width="23%" align="center" bgcolor="#666666" class="style2 header"><span class="style69">Satuan Kerja</span></td>
<?php 	for ($i = 0; $i < count($tingkat); $i++) { ?>
	<td width="<?=$width?>%" align="center" bgcolor="#666666" class="header">	  <span class="style4 style70">
	  <?=$tingkat[$i]?>
	</span> </td>
<?php 	} ?>    
    <td width="10%" align="center" bgcolor="#666666" class="style2 header"><span class="style70"><strong>Jumlah</strong></span></td>
</tr>
<?php
$cnt = 0;
for($i = 0; $i < count($satker); $i++) {
	$sk = $satker[$i];
?>
<tr height="20">
	<td align="center" valign="top"><?=++$cnt?></td>
    <td align="left" valign="top"><?=$sk?></td>
    <?php 
	$jrow = 0;
	for($j = 0; $j < count($tingkat); $j++) 
	{ 
		$t = $tingkat[$j];
		$nilai = $data[$sk][$t];	
		$jrow += $nilai; 
		$ttingkat[$t] += $nilai; ?>
	    <td align="center" valign="top"><?=$nilai?></td>
    <?php 
	}
	$tjrow += $jrow;
	?>
    <td align="center" valign="top"><?=$jrow?></td>
</tr>
<?php
}
?>
<tr height="30">
	<td style="background-color:#E9E9E9" align="center" valign="top">&nbsp;</td>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>JUMLAH</strong></td>
    <?php 
	$total = 0;
	for($j = 0; $j < count($tingkat); $j++) 
	{ 
		$t = $tingkat[$j];
		$nilai = $ttingkat[$t]; 
		$total += $nilai; ?>
	    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$nilai?></strong></td>
    <?php 
	}
	?>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$total?></strong></td>
</tr>
<?php if ($total > 0) { ?>
<tr height="30">
	<td style="background-color:#E9E9E9" align="center" valign="top">&nbsp;</td>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>PERSENTASE</strong></td>
    <?php 
	for($j = 0; $j < count($tingkat); $j++) 
	{ 
		$t = $tingkat[$j];
		$nilai = $ttingkat[$t]; 
		$pct = "";
		$pct = round($nilai / $total, 2) * 100;	?>
	    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$pct?>%</strong></td>
    <?php 
	}
	?>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>100%</strong></td>
</tr>
<?php } ?>
</table>
<?php } ?>
<?php if ($stat==3){ ?>
<?php
OpenDb();
$sql = "SELECT golongan FROM golongan ORDER BY urutan";
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) {
	$gol[] = $row[0];
}
$width = floor(75 / count($gol));

$sql = "SELECT satker FROM satker";
$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) {
	$satker[] = $row['satker'];
}

$sql = "SELECT j.satker, pg.golongan, count(p.nip) AS cnt FROM
		pegawai p, peglastdata pl, peggol pg, pegjab pj, jabatan j
 		WHERE p.aktif = 1 AND p.pns = 1 AND p.nip = pl.nip AND pl.idpeggol = pg.replid AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid        AND NOT j.satker IS NULL 
		GROUP BY j.satker, pg.golongan";
$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) {
	$s = $row['satker'];
	$g = $row['golongan'];
	$data[$s][$g] = $row['cnt'];
}
CloseDb();
?>
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%" align="center">
<tr height="30">
	<td width="3%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>No</strong></span></td>
    <td width="12%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>Satuan Kerja</strong></span></td>
<?php 	for ($i = 0; $i < count($gol); $i++) { ?>
	<td width="<?=$width?>%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>
	  <?=$gol[$i]?>
	</strong></span></td>
<?php 	} ?>    
    <td width="10%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>Jumlah</strong></span></td>
</tr>
<?php
$cnt = 0;
for($i = 0; $i < count($satker); $i++) {
	$sk = $satker[$i];
?>
<tr height="25">
	<td align="center" valign="top"><?=++$cnt?></td>
    <td align="left" valign="top"><?=$sk?></td>
    <?php 
	$jrow = 0;
	for($j = 0; $j < count($gol); $j++) 
	{ 
		$g = $gol[$j];
		$nilai = $data[$sk][$g];	
		$jrow += $nilai; 
		$tgol[$g] += $nilai; ?>
	    <td align="center" valign="top"><?=$nilai?></td>
    <?php 
	}
	$tjrow += $jrow;
	?>
    <td align="center" valign="top"><?=$jrow?></td>
</tr>
<?php
}
?>
<tr height="30">
	<td style="background-color:#E9E9E9" align="center" valign="top">&nbsp;</td>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>JUMLAH</strong></td>
    <?php 
	$total = 0;
	for($j = 0; $j < count($gol); $j++) 
	{ 
		$g = $gol[$j];
		$nilai = $tgol[$g]; 
		$total += $nilai; ?>
	    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$nilai?></strong></td>
    <?php 
	}
	?>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$total?></strong></td>
</tr>
<?php if ($total > 0) { ?>
<tr height="30">
	<td style="background-color:#E9E9E9" align="center" valign="top">&nbsp;</td>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>PERSENTASE</strong></td>
    <?php 
	for($j = 0; $j < count($gol); $j++) 
	{ 
		$g = $gol[$j];
		$nilai = $tgol[$g]; 
		$pct = "";
		$pct = round($nilai / $total, 2) * 100;	?>
	    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$pct?>%</strong></td>
    <?php 
	}
	?>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>100%</strong></td>
</tr>
<?php } 

?>
</table>
<?php } ?>
<?php if ($stat==4){ ?>
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="80%" align="center">
<tr height="25">
	<td width="5%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>No</strong></span></td>
    <td width="23%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>Satuan Kerja</strong></span></td>
    <td width="7%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong><24</strong></span></td>
    <td width="7%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>24-29</strong></span></td>
    <td width="7%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>30-34</strong></span></td>
    <td width="7%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>35-39</strong></span></td>
    <td width="7%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>40-44</strong></span></td>
    <td width="7%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>45-49</strong></span></td>
    <td width="7%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>50-55</strong></span></td>
    <td width="7%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>56></strong></span></td>
    <td width="10%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>Jumlah</strong></span></td>
</tr>
<?php
OpenDb();
$sql = "SELECT XX.G, j.satker, COUNT(XX.nip) AS cnt FROM (
  SELECT nip, IF(usia < 24, '<24',
              IF(usia >= 24 AND usia <= 29, '24-29',
              IF(usia >= 30 AND usia <= 34, '30-34',
              IF(usia >= 35 AND usia <= 39, '35-39',
              IF(usia >= 40 AND usia <= 44, '40-44',
              IF(usia >= 45 AND usia <= 49, '45-49',
              IF(usia >= 50 AND usia <= 55, '50-55', '>56'))))))) AS G FROM
    (SELECT nip, FLOOR(DATEDIFF(NOW(), tanggallahir) / 365) AS usia FROM pegawai WHERE aktif = 1 AND pns = 1) AS X) AS XX, peglastdata pl, pegjab pj, jabatan j
WHERE XX.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid  AND NOT j.satker IS NULL 
GROUP BY XX.G, j.satker";

$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) 
{
	$g = $row['G'];
	$s = $row['satker'];
	$data[$s][$g] = $row['cnt'];
}

$usia = ["<24", "24-29", "30-34", "35-39", "40-44", "45-49", "50-55", ">56"];

$sql = "SELECT satker FROM satker";
$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) 
{
	$satker[] = $row['satker'];
}
CloseDb();

$tjrow = 0;
for($i = 0; $i < count($satker); $i++) {
	$sk = $satker[$i];
?>
<tr height="20">
	<td align="center" valign="top"><?=++$cnt?></td>
    <td align="left" valign="top"><?=$sk?></td>
    <?php 
	$jrow = 0;
	for($j = 0; $j < count($usia); $j++) 
	{ 
		$u = $usia[$j];
		$nilai = $data[$sk][$u];	
		$jrow += $nilai; 
		$tusia[$u] += $nilai; ?>
	    <td align="center" valign="top"><?=$nilai?></td>
    <?php 
	}
	$tjrow += $jrow;
	?>
    <td align="center" valign="top"><?=$jrow?></td>
</tr>
<?php
}
?>
<tr height="30">
	<td style="background-color:#E9E9E9" align="center" valign="top">&nbsp;</td>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>JUMLAH</strong></td>
    <?php 
	$total = 0;
	for($j = 0; $j < count($usia); $j++) 
	{ 
		$u = $usia[$j];
		$nilai = $tusia[$u]; 
		$total += $nilai; ?>
	    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$nilai?></strong></td>
    <?php 
	}
	?>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$total?></strong></td>
</tr>
<?php if ($total > 0) { ?>
<tr height="30">
	<td style="background-color:#E9E9E9" align="center" valign="top">&nbsp;</td>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>PERSENTASE</strong></td>
    <?php 
	for($j = 0; $j < count($usia); $j++) 
	{ 
		$u = $usia[$j];
		$nilai = $tusia[$u]; 
		$pct = "";
		$pct = round($nilai / $total, 2) * 100;	?>
	    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$pct?>%</strong></td>
    <?php 
	}
	?>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>100%</strong></td>
</tr>
<?php } ?>
</table>
<?php } ?>
<?php if ($stat==5){ ?>
<?php
OpenDb();

$idgroup;

$namadiklat;
$iddiklat;

$idgroupf;
$namadiklatf;
$iddiklatf;

function traversediklat($idxgroup, $rootid) {
	global $namadiklat;
	global $iddiklat;
	
	$idlevel;
	
	$sql = "SELECT replid, diklat FROM diklat WHERE rootid=$rootid";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_row($result)) {
			if (strlen((string) $iddiklat[$idxgroup]) > 0)
				$iddiklat[$idxgroup] = $iddiklat[$idxgroup] . ",";
			$iddiklat[$idxgroup] = $iddiklat[$idxgroup] . $row[0];
			
			if (strlen((string) $namadiklat[$idxgroup]) > 0)
				$namadiklat[$idxgroup] = "/ " . $namadiklat[$idxgroup];
			$namadiklat[$idxgroup] = $row[1] . $namadiklat[$idxgroup];
		
			$idlevel[] = $row[0];
		}
		
		for($i = 0; $i < count($idlevel); $i++) 
			traversediklat($idxgroup, $idlevel[$i]);
	}
}

function traversediklatf($idxgroup, $rootid) {
	global $namadiklatf;
	global $iddiklatf;
	
	$idlevel;
	
	$sql = "SELECT replid, diklat FROM diklat WHERE rootid=$rootid";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_row($result)) {
			if (strlen((string) $iddiklatf[$idxgroup]) > 0)
				$iddiklatf[$idxgroup] = $iddiklatf[$idxgroup] . ",";
			$iddiklatf[$idxgroup] = $iddiklatf[$idxgroup] . $row[0];
			
			if (strlen((string) $namadiklatf[$idxgroup]) > 0)
				$namadiklatf[$idxgroup] = "/ " . $namadiklatf[$idxgroup];
			$namadiklatf[$idxgroup] = $row[1] . $namadiklatf[$idxgroup];
		
			$idlevel[] = $row[0];
		}
		
		for($i = 0; $i < count($idlevel); $i++) 
			traversediklatf($idxgroup, $idlevel[$i]);
	}
}

?>
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="960" align="center">
<tr height="25">
    <td align="center" bgcolor="#F2F2F2" rowspan="2" width="200">Pejabat Struktural /<br />Fungsional</td>
    <td align="center" bgcolor="#F2F2F2" rowspan="2" width="50">Jumlah</td>
    <td align="center" bgcolor="#F2F2F2" colspan="4" width="400">Diklat Struktural</td>
    <td align="center" bgcolor="#F2F2F2" rowspan="2" width="50">Sudah<br />Diklat</td>
	<td align="center" bgcolor="#F2F2F2" rowspan="2" width="50">Belum<br />Diklat</td>
    <td align="center" bgcolor="#F2F2F2" colspan="4" width="200">Diklat Fungsional</td>
</tr>
<tr height="25">
<?php
$sql = "SELECT replid, diklat FROM diklat WHERE tingkat=2 AND jenis='S' ORDER BY diklat";
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) {
	$idgroup[] = $row[0];
	
	$namadiklat[] = $row[1] . "";
	$iddiklat[] = "";
}
for($i = 0; $i < count($idgroup); $i++) {
	traversediklat($i, $idgroup[$i]);
}

$sql = "SELECT replid, diklat FROM diklat WHERE tingkat=2 AND jenis='F' ORDER BY diklat";
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) {
	$idgroupf[] = $row[0];
	
	$namadiklatf[] = $row[1] . "";
	$iddiklatf[] = "";
}
for($i = 0; $i < count($idgroupf); $i++) {
	traversediklatf($i, $idgroupf[$i]);
}

$width = 400 / count($idgroup);
for($i = 0; $i < count($idgroup); $i++) 
	echo "<td align='center' bgcolor='#F2F2F2' width='$width'>" . $namadiklat[$i] . "</td>";

$width = 200 / count($idgroupf);
for($i = 0; $i < count($idgroupf); $i++) 
	echo "<td align='center' bgcolor='#F2F2F2' width='$width'>" . $namadiklatf[$i] . "</td>";
?>
</tr>

<?php

$eselon;
$jpeg;

$sql = "SELECT DISTINCT eselon FROM jabatan ORDER BY eselon";
$result = QueryDb($sql);
while($row = mysqli_fetch_row($result)) 
	$eselon[] = $row[0];			

$data;

$ndiklat = count($iddiklat);
$neselon = count($eselon);	
for($i = 0; $i < $neselon; $i++) {
	$es = $eselon[$i];
	
	$sql = "SELECT COUNT(*) FROM pegawai p, peglastdata pl, pegjab pj, jabatan j, jenisjabatan jj
	          WHERE p.aktif = 1 AND p.pns = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid 
			  AND pj.idjabatan = j.replid AND pj.jenis = jj.jenis 
			  AND j.eselon ='$es' AND jj.jabatan = 'S'";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$jp = $row[0];
	
	$data[$i][0] = $jp;
?>
<tr height="25">
	<td align="left" valign="middle"><?=$eselon[$i]?></td>
    <td align="center" valign="middle"><?=$jp?></td>
<?php $nrow = 0;
	for ($j = 0; $j < $ndiklat; $j++) {
		$idstr = $iddiklat[$j];
		
		$sql = "SELECT count(*) FROM pegawai p, peglastdata pl, pegdiklat pd, pegjab pj, jabatan j, jenisjabatan jj
	    	    WHERE  p.aktif = 1 AND p.pns = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND pj.jenis = jj.jenis
				AND jj.jabatan = 'S' AND j.eselon = '$es' AND pl.idpegdiklat = pd.replid AND pd.iddiklat IN ($idstr)";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);	
		$cnt = "-";
		if ($row[0] > 0) {	
			$cnt = $row[0];	
			$nrow += $cnt;	
			$data[$i][$j + 1] = $cnt;
		} else {
			$data[$i][$j + 1] = 0;
		} ?>
    <td align="center" valign="middle"><?=$cnt?></td>
<?php } 
	$data[$i][$ndiklat + 1] = $nrow;
	$data[$i][$ndiklat + 2] = $jp - $nrow;
	?>    
	<td align="center" valign="middle"><?=$nrow?></td>
    <td align="center" valign="middle"><?=$jp - $nrow?></td>
<?php for ($j = 0; $j < count($iddiklatf); $j++) { ?>
	<td align="center" valign="middle">-</td>
<?php } ?>    
</tr>
<?php
}
?>

<tr height="30">
	<td align="left"  bgcolor="#99CC00" valign="middle"><strong>SUB JUMLAH</strong></td>
<?php for($i = 0; $i < $ndiklat + 3; $i++) { 
		$sum = 0;
		for($j = 0; $j < $neselon; $j++) 
			$sum += $data[$j][$i] ?> 
	<td align="center"  bgcolor="#99CC00" valign="middle"><strong><?=$sum?></strong></td>	            
<?php } ?>    
<?php for ($j = 0; $j < count($iddiklatf); $j++) { ?>
	<td align="center"  bgcolor="#99CC00" valign="middle">-</td>
<?php } ?>    
</tr>

<?php
$sql = "SELECT jenis FROM jenisjabatan WHERE jabatan='F' ORDER BY jenis";
$result = QueryDb($sql);
$jabf;
while ($row = mysqli_fetch_row($result)) 
	$jabf[] = $row[0];
$njabf = count($jabf);

$ndiklatf = count($iddiklatf);

$totpeg = 0;
for($i = 0; $i < $njabf; $i++) {	
	$jab = $jabf[$i];
	
	$sql = "SELECT COUNT(*) FROM pegawai p, peglastdata pl, pegjab pj, jenisjabatan jj
	          WHERE p.aktif = 1 AND p.pns = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid 
			  AND pj.jenis = jj.jenis AND pj.jenis ='$jab' AND jj.jabatan = 'F'";
			  
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$jp = $row[0];
	$totpeg += $jp;
	
	$data[$neselon + $i][0] = $jp; ?>
<tr height="30">
	<td align="left" valign="middle"><?=$jabf[$i]?></td>
    <td align="center" valign="middle"><?=$jp?></td>
<?php for ($j = 0; $j < $ndiklat + 2; $j++) { 	?>
	<td align="center" valign="middle">-</td>
<?php } ?>    
<?php for ($j = 0; $j < $ndiklatf; $j++) {
		$idstr = $iddiklatf[$j];
		
		$sql = "SELECT count(*) FROM pegawai p, peglastdata pl, pegdiklat pd, pegjab pj, jenisjabatan jj
	    	    WHERE p.aktif = 1 AND p.pns = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.jenis = jj.jenis
				AND jj.jabatan = 'F' AND pj.jenis = '$jab' AND pl.idpegdiklat = pd.replid AND pd.iddiklat IN ($idstr)";
						
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);	
		$cnt = "-";
		if ($row[0] > 0) {	
			$cnt = $row[0];	
			$data[$i][$ndiklat + 3 + $j] = $jp;			  	
		} else {
			$data[$i][$ndiklat + 3 + $j] = 0;
		} ?>
    <td align="center" valign="middle"><?=$cnt?></td>
<?php } ?>
</tr>
<?php
}
?>

<tr height="30">
	<td align="left"  bgcolor="#99CC00" valign="middle"><strong>SUB JUMLAH</strong></td>
    <td align="center"  bgcolor="#99CC00" valign="middle"><strong><?=$totpeg?></strong></td>
<?php for ($j = 0; $j < $ndiklat + 2; $j++) { ?>
	<td align="center"  bgcolor="#99CC00" valign="middle">-</td>
<?php } ?>    
<?php for($i = 0; $i < $ndiklatf; $i++) { 
		$sum = 0;
		for($j = 0; $j < $njabf; $j++) 
			$sum += $data[$j][$ndiklat + 3 + $i]; ?> 
	<td align="center"  bgcolor="#99CC00" valign="middle"><strong><?=$sum?></strong></td>	            
<?php } ?> 
</tr>

<tr height="50">
	<td align="left" bgcolor="#FFCC00" valign="middle"><strong>JUMLAH</strong></td>
<?php for ($i = 0; $i < $ndiklat + 3 + $ndiklatf; $i++) { 
		$sum = 0;
		for ($j = 0; $j < $neselon + $njabf; $j++) 
			$sum += $data[$j][$i]; ?>
	<td align="center"  bgcolor="#FFCC00" valign="middle"><strong><?=$sum?></strong></td>
<?php } ?>    
</tr>

</table>
<?php } ?>
<?php if ($stat==6){ ?>
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="80%" align="center">
<tr height="25">
	<td width="5%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>No</strong></span></td>
    <td width="40%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>Satuan Kerja</strong></span></td>
    <td width="15%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>Jumlah<br>
      Pegawai</strong></span></td>
    <td width="10%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>Pria</strong></span></td>
    <td width="10%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>%tase</strong></span></td>
    <td width="10%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>Wanita</strong></span></td>
    <td width="10%" align="center" bgcolor="#666666" class="style68"><span class="style2"><strong>%tase</strong></span></td>
</tr>
<?php
OpenDb();
$sql = "SELECT j.satker, SUM(IF(p.kelamin = 'L', 1, 0)) AS Pria, SUM(IF(p.kelamin = 'P', 1, 0)) AS Wanita
		  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
		  WHERE p.aktif = 1 AND p.pns = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid
		  GROUP BY j.satker HAVING NOT j.satker IS NULL";	
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) {
	$jpeg = $row[1] + $row[2];
	$tpeg = $tpeg + $jpeg;
	
	$tp   = $tp   + $row[1];
	$tw   = $tw   + $row[2];
	
	$pctp = "";
	$pctw = "";
	if ($jpeg > 0) {
		$pctp = round($row[1] / $jpeg, 2) * 100;
		$pctw = round($row[2] / $jpeg, 2) * 100;
	}
?>
<tr height="20">
	<td align="center" valign="top"><?=++$cnt?></td>
    <td align="center" valign="top"><?=$row[0]?></td>
    <td align="center" valign="top"><?=$jpeg?></td>
    <td align="center" valign="top"><?=$row[1]?></td>
    <td align="center" valign="top"><?=$pctp . "%"?></td>
    <td align="center" valign="top"><?=$row[2]?></td>
    <td align="center" valign="top"><?=$pctw . "%"?></td>
</tr>
<?php
}
CloseDb();

$pctw = "";
$pctp = "";
if ($tpeg > 0) {
	$pctp = round($tp / $tpeg, 2) * 100;
	$pctw = round($tw / $tpeg, 2) * 100;
}	
?>
<tr height="30">
	<td style="background-color:#CCCCCC" align="center" valign="middle">&nbsp;</td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong>JUMLAH</strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$tpeg?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$tp?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$pctp . "%"?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$tw?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$pctw . "%"?></strong></td>
</tr>
</table>
<?php } ?>
<?php if ($stat==7){ ?>
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="80%" align="center">
<tr height="25">
	<td width="5%" align="center" bgcolor="#666666" class="style68"><strong>No</strong></td>
    <td width="40%" align="center" bgcolor="#666666" class="style68"><strong>Satuan Kerja</strong></td>
    <td width="15%" align="center" bgcolor="#666666" class="style68"><strong>Jumlah<br>
      Pegawai</strong></td>
    <td width="10%" align="center" bgcolor="#666666" class="style68"><strong>Nikah</strong></td>
    <td width="10%" align="center" bgcolor="#666666" class="style68"><strong>%tase</strong></td>
    <td width="10%" align="center" bgcolor="#666666" class="style68"><strong>Belum</strong></td>
    <td width="10%" align="center" bgcolor="#666666" class="style68"><strong>%tase</strong></td>
</tr>
<?php
OpenDb();
$sql = "SELECT j.satker, SUM(IF(p.nikah = 'Nikah', 1, 0)) AS Nikah, SUM(IF(p.nikah = 'Belum Nikah', 1, 0)) AS Belum
		  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
		  WHERE p.aktif = 1 AND p.pns = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid 
		  AND NOT j.satker IS NULL 
		  GROUP BY j.satker";	
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) {
	$jpeg = $row[1] + $row[2];
	
	$tpeg = $tpeg + $jpeg;
	$tn   = $tn   + $row[1];
	$tb   = $tb   + $row[2];
	
	$pctn = "";
	$pctb = "";
	if ($jpeg > 0) {
		$pctn = round($row[1] / $jpeg, 2) * 100;
		$pctb = round($row[2] / $jpeg, 2) * 100;
	}
?>
<tr height="20">
	<td align="center" valign="top"><?=++$cnt?></td>
    <td align="center" valign="top"><?=$row[0]?></td>
    <td align="center" valign="top"><?=$jpeg?></td>
    <td align="center" valign="top"><?=$row[1]?></td>
    <td align="center" valign="top"><?=$pctn . "%"?></td>
    <td align="center" valign="top"><?=$row[2]?></td>
    <td align="center" valign="top"><?=$pctb . "%"?></td>
</tr>
<?php
}
CloseDb();

$pctn = "";
$pctb = "";
if ($tpeg > 0) {
	$pctn = round($tn / $tpeg, 2) * 100;
	$pctb = round($tb / $tpeg, 2) * 100;
}	
?>
<tr height="30">
	<td style="background-color:#CCCCCC" align="center" valign="middle">&nbsp;</td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong>JUMLAH</strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$tpeg?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$tn?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$pctn . "%"?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$tb?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$pctb . "%"?></strong></td>
</tr>
</table>
<?php } ?>
</div>
</body>

</html>