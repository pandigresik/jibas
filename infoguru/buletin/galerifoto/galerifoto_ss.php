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
require_once('../../include/sessionchecker.php');
require_once('../../include/common.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');

$op="";
if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];
	
$page='t';
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
OpenDb();
$sql="SELECT * FROM jbsvcr.galerifoto WHERE idguru='".SI_USER_ID()."'";
$result=QueryDb($sql);
$num=@mysqli_num_rows($result);
$cnt=1;
while ($row=@mysqli_fetch_array($result))
{
	$ket[$cnt]=$row['keterangan'];
	$nama[$cnt]=$row['nama'];
	$fn[$cnt]=$row['filename'];
	$rep[$cnt]=$row['replid'];
	$cnt++;
}
CloseDb();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="../../script/TinySlideshow/style.css" />
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../../script/contentslider.css" />
<script type="text/javascript" language="javascript" src="../../style/lytebox.js"></script>
<script type="text/javascript" language="javascript" src="../../script/tools.js"></script>
<link rel="stylesheet" href="../../style/lytebox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../../script/contentslider.js"></script>
<script language="javascript" src="../../script/ajax.js"></script>
<script src="SpryTabbedPanels.js" type="text/javascript"></script>
<script language="javascript" >
function get_fresh(){
	document.location.href="galerifoto_ss.php";
}
function over(id){
	var actmenu = document.getElementById('actmenu').value;
	if (actmenu==id)
		return false;
		
	if (actmenu=='t')
		document.getElementById('tabimages').src='../../images/s_over.png';
	else 
		document.getElementById('tabimages').src='../../images/t_over.png';
}
function out(id){
	var actmenu = document.getElementById('actmenu').value;
	if (actmenu==id)
		return false;
	
	if (actmenu=='t')
		document.getElementById('tabimages').src='../../images/t.png';
	else
		document.getElementById('tabimages').src='../../images/s.png';
}
function show(id){
	
	if (id=='t'){
		document.getElementById('actmenu').value='t';
		document.getElementById('tabimages').src='../../images/t.png';
		document.getElementById('slice_t').style.display='';
		document.getElementById('salideshow').style.display='none';
	} else {
		document.getElementById('actmenu').value='s';
		document.getElementById('tabimages').src='../../images/s.png';
		document.getElementById('slice_t').style.display='none';
		document.getElementById('salideshow').style.display='';
	}
	
}

</script>
<style type="text/css">
<!--
.style1 {
	font-size: 0.7px;
	font-family: Verdana;
}
.style3 {font-size: 12px}
-->
</style>
<link href="SpryTabbedPanels.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" border="0" cellspacing="5">
  <tr>
    <td align="left"><font size="4" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" color="Gray">Galeri Foto</font><br />
        <a href="../../home.php"  target="framecenter">Home</a> > <strong>Galeri Foto</strong><br />
      <br /></td>
    <td align="right" valign="bottom">
	<a href="galerifoto.php"><img src="../../images/ico/thumbnail.gif" border="0">&nbsp;Thumbnails</a>&nbsp;&nbsp;
	<a href="#" onClick="newWindow('tambahfoto.php?pagesource=ss','TambahFoto','550','207','resizable=1,scrollbars=0,status=0,toolbar=0');"><img src="../../images/ico/tambah.png" border="0" />&nbsp;Tambah Foto</a><br>
	</td>
  </tr>
  <tr>
    <td align="left" colspan='2'>
	<table border='0' width='100%'>
      <tr>
        <td width='25%'>&nbsp;</td>
        <td width='*'>
<?php 		if ($num>0)
		{ ?>
			<ul id="slideshow">
<?php 			for ($i = 1; $i <= $num; $i++)
			{
				$fphoto = "$FILESHARE_ADDR/galeriguru/photos/".$fn[$i];
				$fthumb = "$FILESHARE_ADDR/galeriguru/thumbnails/".$fn[$i]; ?>
				<li>
					<h3><?=$nama[$i]?></h3>
					<span><?=$fphoto?></span>
					<p><?=$ket[$i]?></p>
					<a href="#"><img src="<?=$fthumb?>" height="480" alt="" /></a>
				</li>
<?php 			} ?>
            </ul>
			<div id="wrapper" >
                <div id="fullsize" style="width: 800px; height: 600px;">
                  <div id="imgprev" class="imgnav" title="Previous Image"></div>
                  <div id="imglink"></div>
                  <div id="imgnext" class="imgnav" title="Next Image"></div>
                  <div id="image"></div>
                  <div id="information">
                    <h3></h3>
                    <p></p>
                  </div>
                </div>
				<div id="thumbnails" style="visibility: hidden;">
				   <div id="slideleft" title="Slide Left"></div>
				   <div id="slidearea">
					  <div id="slider"></div>
				   </div>
				   <div id="slideright" title="Slide Right"></div>
				</div>
			</div>
			<script type="text/javascript" src="../../script/TinySlideshow/compressed.js"></script>
            <script type="text/javascript">
                $('slideshow').style.display='none';
                $('wrapper').style.display='block';
                var slideshow=new TINY.slideshow("slideshow");
                window.onload=function(){
                    slideshow.auto=true;
                    slideshow.speed=5;
                    slideshow.link="linkhover";
                    slideshow.info="information";
                    slideshow.thumbs="slider";
                    slideshow.left="slideleft";
                    slideshow.right="slideright";
                    slideshow.scrollSpeed=4;
					slideshow.height=480;
                    slideshow.spacing=5;
                    slideshow.active="#fff";
                    slideshow.init("slideshow","image","imgprev","imgnext","imglink");
                }
            </script>
<?php 	   } else { ?>
            <table width="100%" border="0" cellspacing="0" align="center">
            <tr>
              <td><div align="center"><em>Tidak ada foto</em></div></td>
            </tr>
            </table>
<?php 	   } ?>
        </td>
        <td width='25%'>&nbsp;</td>
      </tr>
    </table>
	</td>
  </tr>
</table>
</body>
</html>