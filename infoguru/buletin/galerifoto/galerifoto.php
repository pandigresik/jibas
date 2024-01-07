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
require_once('../../include/common.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
require_once('../../include/fileutil.php');
require_once('../../include/sessionchecker.php');

$op = "";
if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];
  
$page = 't';
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
OpenDb();
$sql = "SELECT * FROM jbsvcr.galerifoto WHERE idguru='".SI_USER_ID()."'";
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
$cnt = 1;
while ($row = @mysqli_fetch_array($result))
{
	$ket[$cnt] = $row['keterangan'];
	$nama[$cnt] = $row['nama'];
	$fn[$cnt] = $row['filename'];
	$rep[$cnt] = $row['replid'];
	
	$cnt++;
}
CloseDb();

if ($op == "14075BUSYCODACALLDIFF")
{
	OpenDb();
	$sql = "SELECT * FROM jbsvcr.galerifoto WHERE replid = '".$_REQUEST['replid']."'";
	$res = QueryDb($sql);
	$r = mysqli_fetch_array($res);
	
	$fimage = "$FILESHARE_UPLOAD_DIR/galeriguru/photos/" . $r['filename'];
	if (file_exists($fimage))
	   delete($fimage);
	   
	$fimage = "$FILESHARE_UPLOAD_DIR/galeriguru/thumbnails/" . $r['filename'];
	if (file_exists($fimage))
	   delete($fimage);   
	   
	$sql = "DELETE FROM jbsvcr.galerifoto WHERE replid = '".$_REQUEST['replid']."'";
	QueryDb($sql);
	
	CloseDb(); ?>
	<script type="text/javascript">
		document.location.href="galerifoto.php?page=t";	
	</script>
<?php
}

if ($op == "DIFFBUSYCODACALL14077")
{
	OpenDb();
	$sql = "UPDATE jbsvcr.galerifoto
			   SET nama='".$_REQUEST['newName']."', keterangan='" . CQ($_REQUEST['newKet']) . "'
			 WHERE replid='".$_REQUEST['replid']."'";
	QueryDb($sql);
	CloseDb();
	?>
	<script type="text/javascript">
		document.location.href="galerifoto.php?page=t";	
	</script>
	<?php
}
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
function get_fresh()
{
	document.location.href="galerifoto.php";
}

function over(id)
{
	var actmenu = document.getElementById('actmenu').value;
	if (actmenu==id)
		return false;
		
	if (actmenu=='t')
		document.getElementById('tabimages').src='../../images/s_over.png';
	else 
		document.getElementById('tabimages').src='../../images/t_over.png';
}

function out(id)
{
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
function ubah(i,num)
{
	for (x=1; x <= num; x++) {
		if (x==i) {
			document.getElementById('spanNama'+x).style.display='none';
			document.getElementById('spanKet'+x).style.display='none';
			document.getElementById('delIcon'+x).style.display='none';
			document.getElementById('editIcon'+x).style.display='none';
			document.getElementById('txtNama'+x).style.display='';
			document.getElementById('txtKet'+x).style.display='';
			document.getElementById('cancelIcon'+x).style.display='';
			document.getElementById('saveIcon'+x).style.display='';
		} else {
			document.getElementById('spanNama'+x).style.display='';
			document.getElementById('spanKet'+x).style.display='';
			document.getElementById('delIcon'+x).style.display='';
			document.getElementById('editIcon'+x).style.display='';
			document.getElementById('txtNama'+x).style.display='none';
			document.getElementById('txtKet'+x).style.display='none';
			document.getElementById('cancelIcon'+x).style.display='none';
			document.getElementById('saveIcon'+x).style.display='none';
		}
			
	}
	
}
function batalUbah(x)
{
	document.getElementById('spanNama'+x).style.display='';
	document.getElementById('spanKet'+x).style.display='';
	document.getElementById('delIcon'+x).style.display='';
	document.getElementById('editIcon'+x).style.display='';
	document.getElementById('txtNama'+x).style.display='none';
	document.getElementById('txtKet'+x).style.display='none';
	document.getElementById('cancelIcon'+x).style.display='none';
	document.getElementById('saveIcon'+x).style.display='none';
}
function simpanUbah(x,replid)
{
	var newName=document.getElementById('txtNama'+x).value;
	var newKet=document.getElementById('txtKet'+x).value;
	document.location.href="galerifoto.php?op=DIFFBUSYCODACALL14077&replid="+replid+"&newName="+newName+"&newKet="+newKet;
}
</script>
<style type="text/css">
<!--
.style3 {font-size: 12px}
-->
</style>
<link href="SpryTabbedPanels.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style4 {
	font-family: Arial;
	font-weight: bold;
	color: #990000;
	font-size:12px
}
.style5 {
	color: #333333;
	font-style: italic;
}
-->
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="5">
  <tr>
    <td height="50" align="left"><font size="4" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" color="Gray">Galeri Foto</font><br />
<a href="../../home.php"  target="framecenter">Home</a> > <strong>Galeri Foto</strong><br /><br /></td>
    <td align="right" valign="bottom">
	<a href="galerifoto_ss.php"><img src="../../images/ico/filmstrip.gif" border="0">&nbsp;Slideshow</a>&nbsp;&nbsp;
	<a href="#" onClick="newWindow('tambahfoto.php?pagesource=tt','TambahFoto','550','207','resizable=1,scrollbars=0,status=0,toolbar=0');"><img src="../../images/ico/tambah.png" border="0" />&nbsp;Tambah Foto</a><br>
	</td>
  </tr>
  <tr>
    <td colspan="2" align="left">
       	<table width="100%" border="0" cellspacing="0" align="center">
			  <?php if ($num>0){ ?>  
			  <?php for ($i=1;$i<=$num;$i++)
					{
						$fphoto = "$FILESHARE_ADDR/galeriguru/photos/".$fn[$i];
						$fthumb = "$FILESHARE_ADDR/galeriguru/thumbnails/".$fn[$i]; ?>
			  <?php 	if ($i==1 || $i - 1 % 5==0)  { ?>
							<tr>
			  <?php 	} ?>
						<td height="125" align="center">
							<a title="<?=$ket[$i]?>" href="<?=$fphoto?>" rel="lytebox['vacation']" >
							<img title="Klik untuk melihat ukuran sebenarnya"
								 src="<?=$fthumb?>" width="80" style="cursor:pointer;">
							</a>
                            <br>
                            <span class="style4" id="spanNama<?=$i?>"><?=$nama[$i]?></span>
							<input type="text" id="txtNama<?=$i?>" value="<?=$nama[$i]?>" style="display:none">
							<br>
					    	<textarea id="txtKet<?=$i?>" name="txtKet<?=$i?>" style="display:none"><?=$ket[$i]?></textarea>
							<span id="spanKet<?=$i?>" class="style5"><?=$ket[$i]?>
							</span>
							<br>
							<img id="delIcon<?=$i?>" src="../../images/ico/hapus.png" onClick="if (confirm('Anda yakin akan menghapus gambar ini?')) document.location.href='galerifoto.php?op=14075BUSYCODACALLDIFF&replid=<?=$rep[$i]?>&page=t'" title="Hapus gambar ini" style="cursor:pointer;" />
							<img src="../../images/ico/arrow_undo.png" name="cancelIcon<?=$i?>" id="cancelIcon<?=$i?>" style="cursor:pointer; display:none" title="Batal ubah" onClick="batalUbah('<?=$i?>')" />
                            &nbsp;
							<img title="Ubah Nama&Keterangan" id="editIcon<?=$i?>" src="../../images/ico/ubah.png" style="cursor:pointer;" onClick="ubah('<?=$i?>','<?=$num?>')">
							<img title="Simpan" id="saveIcon<?=$i?>" src="../../images/ico/disk.png" style="cursor:pointer; display:none" onClick="simpanUbah('<?=$i?>','<?=$rep[$i]?>')">							</td>
			  <?php 	if ($i % 5==0) { ?>
							</tr>
			  <?php 	} ?>
			  <?php } ?>
			  <?php } else {?>
				<tr>
					<td>
						<div align="center"><em>Tidak ada foto</em></div>					</td>
				</tr>
			  <?php } ?>
           </table>
      </td>
  </tr>
</table>
</body>
</html>