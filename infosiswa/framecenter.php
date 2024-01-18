<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 2.3.0 (September 24, 2010)
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
require_once("include/sessioninfo.php");
require_once("include/db_functions.php");
require_once("include/config.php");
require_once("include/common.php");
require_once("sessionchecker.php");
OpenDb();
$sql="SELECT * FROM jbsvcr.profil WHERE nis <> '".SI_USER_ID()."' GROUP BY nis ORDER BY RAND() LIMIT 1";
$sql1="SELECT YEAR(tanggal) as thn,MONTH(tanggal) as bln,DAY(tanggal) as tgl,replid,judul,abstrak FROM jbsvcr.beritasiswa ORDER BY replid DESC LIMIT 0,2";
$sql2="SELECT YEAR(tanggal) as thn,MONTH(tanggal) as bln,DAY(tanggal) as tgl,replid,judul,abstrak FROM jbsvcr.beritasekolah WHERE jenisberita='1' ORDER BY replid DESC LIMIT 0,2";
$sql3="SELECT YEAR(tanggal) as thn,MONTH(tanggal) as bln,DAY(tanggal) as tgl,replid,judul,abstrak FROM jbsvcr.beritasekolah WHERE jenisberita='2' ORDER BY replid DESC LIMIT 0,2";
//echo $sql1."<br>";
//echo $sql2."<br>";
//echo $sql3."<br>";
$result1=QueryDb($sql1);
$result2=QueryDb($sql2);
$result3=QueryDb($sql3);
$namabulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];	
$tglberita=$row1['tgl']." ".$namabulan[$row1['bln']-1]." ".$row1['thn'];
$tglberita2=$row2['tgl']." ".$namabulan[$row2['bln']-1]." ".$row2['thn'];
$tglberita3=$row3['tgl']." ".$namabulan[$row3['bln']-1]." ".$row3['thn'];
$result=QueryDb($sql);
CloseDb();
$num=@mysqli_num_rows($result);
$row=@mysqli_fetch_array($result);
$replid=$row['replid']

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<!--<script type="text/javascript" src="../script/prototype.js"></script>
<script type="text/javascript" src="../script/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="../script/lightbox.js"></script>-->
<script type="text/javascript" language="javascript" src="style/lytebox.js"></script>
<link rel="stylesheet" href="style/lytebox.css" type="text/css" media="screen" />
<script language = "javascript" type = "text/javascript" src="script/resizing_background.js"></script>
<link rel="stylesheet" href="style/style2.css" type="text/css" media="screen" />
<link href="style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="script/tools.js"></script>
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-weight: bold;
}
.style8 {font-family: monospace, cursive, "Arial Black", "Bookman Old Style"; font-size: 12px; }
.style18 {font-family: calibri; font-size: 12px; font-weight: bold; }
.style20 {font-family: calibri; font-weight: bold; }
.style22 {font-family: calibri; font-size: 12px; }
.style23 {
	font-weight: bold;
	font-size: 24px;
	padding-left:5px;
	font-family: calibri;
	color: #990000;
}
-->
</style>
<script language="javascript">
function bacaberitasiswa(replid){
	//parent.frametop.buletin();
	//document.location.href="bacaberitaguru.php?replid="+replid;
	newWindow('bacaberitasiswa.php?replid='+replid,'BacaBeritanyaSekolah',738,525,'scrollbars=1');
	
}

function bacaberitasekolah(replid){
	//parent.frametop.buletin();
	//document.location.href="bacaberitaguru.php?replid="+replid;
	newWindow('bacaberitasekolah.php?replid='+replid,'BacaBeritanyaSekolah',738,525,'scrollbars=1');
	
}
function get_fresh(){
	document.location.href="home.php";
}
function show_msg(){
	parent.frametop.msg_from_home();
}
</script>
</head>

<body>
<table border="0" cellspacing="5" cellpadding="5">
  <tr>
    <td valign="top" align="left">
        <div align="left" style="padding-bottom:10px"> <span style="background-color:#FF9900;color:#FF9900; font-size:18px">&nbsp;</span>&nbsp;<span style="color:#999999; font-size:14px; font-weight:bold">Pesan  Baru</span> </div>
        <div align="left"> <span style="color:#999999">
        <?php
	  	OpenDb();
		$sql4="SELECT * FROM jbsvcr.tujuanpesan t, jbsvcr.pesan p WHERE t.idpenerima='".SI_USER_ID()."' AND t.baru=1 AND t.idpesan=p.replid ORDER BY t.replid DESC";
		//echo $sql2;
		$result4=QueryDb($sql4);
		$row4=@mysqli_fetch_array($result4);
		if (@mysqli_num_rows($result4)>0){
			$pesanbaru = "Ada <font color=\"red\">".@mysqli_num_rows($result4)."</font> pesan baru yang belum dibaca !".
					 	 "<div align='right' style='text-align:right'><img src='images/ico/arr1.gif' />&nbsp;&nbsp;<a onclick='show_msg()' href='#'>Masuk ke kotak pesan</a></div>";
		} else {
			$pesanbaru = "Tidak ada pesan baru !";
		}
		echo $pesanbaru;
	    ?>
        </span>
        </div>
        
        <div align="left" style="padding-bottom:10px; padding-top:15px;"> <span style="background-color:#FF9900;color:#FF9900; font-size:18px">&nbsp;</span>&nbsp;<span style="color:#999999; font-size:14px; font-weight:bold">Berita Penting</span> </div>
        <div align="left">
        <?php 
            while ($row2=@mysqli_fetch_array($result2)){
             $tglberita2=$row2['tgl']." ".$namabulan[$row2['bln']-1]." ".$row2['thn'];
          ?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                	<em><?php echo $tglberita2?></em>      <br />
                  <font color="#3C859C" size="-2"><?php echo $row2['judul']?></font>
                  <span class="style8"><?php echo $row2['abstrak']?></span>
                  <div align="right" style="text-align:right"><img src="images/ico/arr1.gif" />&nbsp;&nbsp;<a href="#" onclick="bacaberitasekolah('<?php echo $row2['replid']?>')" >Baca Selengkapnya</a></div>
                </td>
              </tr>
              <tr>
              	<td style="background-image:url(images/box_hr1.gif); background-repeat:repeat-x">&nbsp;</td>
              </tr>
            </table><br />
           <?php } ?>
        </div>
        <div align="left" style="padding-bottom:10px"> <span style="background-color:#FF9900;color:#FF9900; font-size:18px">&nbsp;</span>&nbsp;<span style="color:#999999; font-size:14px; font-weight:bold">Rubrik Sekolah</span> </div>
        <div align="left">
        <?php 
            while ($row3=@mysqli_fetch_array($result3)){
             $tglberita3=$row3['tgl']." ".$namabulan[$row3['bln']-1]." ".$row3['thn'];
          ?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
          <em><?php echo $tglberita3?></em>      <br />
          <font color="#3C859C" size="-2"><?php echo $row3['judul']?></font>
          <span class="style8"><?php echo $row3['abstrak']?></span>
          <div align="right" style="text-align:right"><img src="images/ico/arr1.gif" />&nbsp;&nbsp;<a href="#" onclick="bacaberitasekolah('<?php echo $row3['replid']?>')" >Baca Selengkapnya</a></div>
          		</td>
          	</tr>
          	<tr>
            	<td style="background-image:url(images/box_hr1.gif); background-repeat:repeat-x">&nbsp;</td>
          	</tr>
           </table><br />
          <?php } ?>
        </div>
        <div align="left" style="padding-bottom:10px"> <span style="background-color:#FF9900;color:#FF9900; font-size:18px">&nbsp;</span>&nbsp;<span style="color:#999999; font-size:14px; font-weight:bold">Berita Siswa</span> </div>
        <div align="left">
    <?php 
		while ($row1=@mysqli_fetch_array($result1)){
	     $tglberita=$row1['tgl']." ".$namabulan[$row1['bln']-1]." ".$row1['thn'];
	  ?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
        <em><?php echo $tglberita?></em>      <br />
        <font color="#3C859C" size="-2"><?php echo $row1['judul']?></font>
        <span class="style8"><?php echo $row1['abstrak']?>
          </span>
        <div align="right" style="text-align:right"><img src="images/ico/arr1.gif" />&nbsp;&nbsp;<a href="#" onclick="bacaberitasiswa('<?php echo $row1['replid']?>')" >Baca Selengkapnya</a></div>
          		</td>
          	</tr>
          	<tr>
            	<td style="background-image:url(images/box_hr1.gif); background-repeat:repeat-x">&nbsp;</td>
          	</tr>
           </table>
			<br />
        <?php } ?>
    </div>
    </td>
    <td valign="top" align="right">
    <?php
	if ($num!=0){
	  ?>
    <div id="header">
     <table width="450" id="profile">
       <tr>
         <td colspan="4" ><span class="style23">Profil Temanku hari ini</span><br />
         <br /></td>
       </tr>
       <tr>
         <td width="155" rowspan="7" valign="top"><a href="library/gambar.php?replid=<?php echo $replid?>&table=jbsvcr.profil" rel="lytebox['vacation']"><img src="library/gambar.php?replid=<?php echo $replid?>&table=jbsvcr.profil" 
          width="80px" height="100px" id="theImage"/></a></td>
         <td colspan="3"></td>
       </tr>
       <tr>
         <td width="53"><span class="style18">Nama</span></td>
         <td width="12"><span class="style20">: </span></td>
         <td width="359"><span class="style22"><?php echo  $row['nama']; ?></span></td>
       </tr>
       <tr>
         <td height="22"><span class="style18">Alamat</span></td>
         <td><span class="style20">:</span></td>
         <td><span class="style22"><?php echo  $row['alamat']; ?></span></td>
       </tr>
       <tr>
         <td><span class="style18">Telepon</span></td>
         <td><span class="style20">:</span></td>
         <td><span class="style22"><?php echo  $row['telpon']; ?></span></td>
       </tr>
       <tr>
         <td><span class="style18">Hp</span></td>
         <td><span class="style20">:</span></td>
         <td><span class="style22"><?php echo  $row['hp']; ?></span></td>
       </tr>
       <tr>
         <td><span class="style18">Email</span></td>
         <td><span class="style20">:</span></td>
         <td><span class="style22"><?php echo  $row['email']; ?></span></td>
       </tr>
       <tr>
         <td><span class="style18">Hobi</span></td>
         <td><span class="style20">:</span></td>
         <td><span class="style22"><?php echo  $row['hobi']; ?></span></td>
       </tr>
       <tr>
         <td colspan="2">&nbsp;</td>
       </tr>
     </table>
 	</div>
	  <?php
	  }
	  ?>
    
    
    </td>
  </tr>
</table>
<div>
 <div class="tengah1">
   
 </div>
 <div id="tengah2">
 	   
 </div>
</div>
</body>
</html>