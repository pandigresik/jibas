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
require_once('sessionchecker.php');
require_once('include/common.php');
require_once('include/sessioninfo.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once("include/theme.php"); 

OpenDb();
$sql="SELECT * FROM jbsvcr.mutiara ORDER BY RAND() LIMIT 1";
$result=QueryDb($sql);
$row=@mysqli_fetch_array($result);
$sql1="SELECT * FROM jbsvcr.galerifoto WHERE nis='".SI_USER_ID()."'";
$result1=QueryDb($sql);
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="design/dhtml/stmenu.js"></script>
<script language="javascript" type="text/javascript" src="script/tools.js"></script>
<script language="javascript" type="text/javascript" src="script/clock.js"></script>
<title>Untitled Document</title>

<script language="javascript" >
function change_password(){
	newWindow('pengaturan/ganti_password.php','PassChanger',406,150,'scrolling=0,resizable=0');
}
function chating_euy(){
	newWindow('buletin/chat/chat.php','ChattingYuk',626,565,'resizable=0,scrollbars=0,status=0,toolbar=0');
}
function menu(menuitem){
	if (menuitem=="home"){
	document.getElementById('homemenu').style.display='';
	document.getElementById('akademikmenu').style.display='none';
	document.getElementById('buletinmenu').style.display='none';
	document.getElementById('pengaturanmenu').style.display='none';
	document.getElementById('dotnetmenu').style.display='none';	//document.location.href="framemain.php";
	parent.framecenter.location.href="framecenter.php";
	}
	if (menuitem=="akademik"){
	document.getElementById('homemenu').style.display='none';
	document.getElementById('akademikmenu').style.display='';
	document.getElementById('buletinmenu').style.display='none';
	document.getElementById('pengaturanmenu').style.display='none';
	document.getElementById('dotnetmenu').style.display='none';
	}
	if (menuitem=="buletin"){
	document.getElementById('homemenu').style.display='none';
	document.getElementById('akademikmenu').style.display='none';
	document.getElementById('buletinmenu').style.display='';
	document.getElementById('pengaturanmenu').style.display='none';
	document.getElementById('dotnetmenu').style.display='none';
	}
	if (menuitem=="pengaturan"){
	document.getElementById('homemenu').style.display='none';
	document.getElementById('akademikmenu').style.display='none';
	document.getElementById('buletinmenu').style.display='none';
	document.getElementById('pengaturanmenu').style.display='';
	document.getElementById('dotnetmenu').style.display='none';
	}
	if (menuitem=="dotnet"){
	document.getElementById('homemenu').style.display='none';
	document.getElementById('akademikmenu').style.display='none';
	document.getElementById('buletinmenu').style.display='none';
	document.getElementById('pengaturanmenu').style.display='none';
	document.getElementById('dotnetmenu').style.display='';
	}
	if (menuitem=="logout"){
	document.getElementById('homemenu').style.display='none';
	document.getElementById('akademikmenu').style.display='none';
	document.getElementById('buletinmenu').style.display='none';
	document.getElementById('pengaturanmenu').style.display='none';
	document.getElementById('dotnetmenu').style.display='none';
		if (confirm('Kamu yakin mau keluar dari InfoSiswa ?'))
			document.location.href="logout.php";
	}

}
function msg_from_home(){
	document.getElementById('homemenu').style.visibility='hidden';
	document.getElementById('akademikmenu').style.visibility='hidden';
	document.getElementById('buletinmenu').style.visibility='visible';
	document.getElementById('pengaturanmenu').style.visibility='hidden';
	document.getElementById('dotnetmenu').style.visibility='hidden';
	parent.framecenter.location.href="buletin/pesan/pesan.php";
}
function get_fresh(){
	document.location.reload();
}
</script>
</head>
<body style="background-color:#FFFFFF" background="<?php echo GetThemeDir()?>bkmain_01.jpg" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="startclock('clock')">
<table id="Table_01" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<img src="<?php echo GetThemeDir()?>InfoSiswa_01.png" width="20" height="15" alt=""></td>
		<td width="716" height="15" colspan="2" background="<?php echo GetThemeDir()?>InfoSiswa_02.png">
        <span style="font-family:Verdana; color:#FFFFFF; font-size:10px; font-weight:bold; text-decoration:none">
        <!--
        <a href="javascript:menu('home');" style="font-family:Verdana; color:#FFFFFF; font-size:10px; font-weight:bold; text-decoration:none">Home</a>&nbsp;
        -->
        <a href="javascript:menu('buletin');" style="font-family:Verdana; color:#FFFFFF; font-size:10px; font-weight:bold; text-decoration:none">Buletin</a>&nbsp;
        <a href="javascript:menu('akademik');" style="font-family:Verdana; color:#FFFFFF; font-size:10px; font-weight:bold; text-decoration:none">Akademik</a>&nbsp;
        <!--
        <a href="javascript:menu('pengaturan');" style="font-family:Verdana; color:#FFFFFF; font-size:10px; font-weight:bold; text-decoration:none">Pengaturan</a>&nbsp;
        
        <a href="javascript:menu('dotnet');" style="font-family:Verdana; color:#FF9900; font-size:10px; font-weight:bold; text-decoration:none">JIBAS.Net</a>&nbsp;
        -->
        <a href="javascript:menu('logout');" style="font-family:Verdana; color:#00FF00; font-size:10px; font-weight:bold; text-decoration:none">Logout</a>&nbsp;    </span>        </td>
		<td>
			<img src="<?php echo GetThemeDir()?>InfoSiswa_03.png" width="136" height="15" alt=""></td>
		<td>
			<img src="<?php echo GetThemeDir()?>InfoSiswa_04.png" width="17" height="15" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="<?php echo GetThemeDir()?>InfoSiswa_05.png" width="20" height="64" alt=""></td>
		<td background="<?php echo GetThemeDir()?>InfoSiswa_06.png" width="100%" height="64">
        <!-- SUB MENU BEGIN -->
		<div id="homemenu" style="display:none" >&nbsp;</div>
		<div id="akademikmenu" style="display:none" >
			<table border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="akademik/jadwal/kalender_footer.php" target="framecenter" onmouseover="kalender.src='images/icon_menu/kalender_on.png'" name="kalender" id="kalender" onmouseout="kalender.src='images/icon_menu/kalender.png'"><img src="images/icon_menu/kalender.png"  border="0" name="kalender" id="kalender2"/><br>
				<font face="Verdana" size="1" color="#FFFFFF"><b>Kalender Akademik</b></font></a></div></td>
				<?php
				if (SI_USER_ID()!="landlord" && SI_USER_ID()!="LANDLORD" && SI_USER_ID()!="adminsiswa" && SI_USER_ID()!="ADMINSISWA") {
				?>
				<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="akademik/jadwal/jadwal_kelas_footer.php" target="framecenter" onmouseover="jadwal.src='images/icon_menu/jadwal_on.png'" name="jadwal" id="jadwal" onmouseout="jadwal.src='images/icon_menu/jadwal.png'"><img src="images/icon_menu/jadwal.png"  border="0" name="jadwal" id="jadwal"/><br>
				<font face="Verdana" size="1" color="#FFFFFF"><b>Jadwal Pelajaran</b></font></a></div></td>
				
				<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="akademik/penilaian/lap_pelajaran_main.php" target="framecenter" onmouseover="penilaian.src='images/icon_menu/penilaian_on.png'" name="penilaian" id="penilaian" onmouseout="penilaian.src='images/icon_menu/penilaian.png'"><img src="images/icon_menu/penilaian.png"  border="0" name="penilaian" id="penilaian"/><br>
				<font face="Verdana" size="1" color="#FFFFFF"><b>Penilaian</b></font></a></div></td>
				
				<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="infosiswa/infosiswa_content.php" target="framecenter" onmouseover="infosiswa.src='images/icon_menu/infosiswa_on.png'" name="infosiswa" id="infosiswa" onmouseout="infosiswa.src='images/icon_menu/infosiswa.png'"><img src="images/icon_menu/infosiswa.png"  border="0" name="infosiswa" id="infosiswa"/><br>
				<font face="Verdana" size="1" color="#FFFFFF"><b>Info Siswa</b></font></a></div></td><?php } ?>
                <td style="padding-right:5px"><div align="center"><a style="text-decoration:none;" href="perpustakaan/perpustakaan.php" target="framecenter" onmouseover="perpustakaan.src='images/icon_menu/perpustakaan_on.png'" name="perpustakaan" id="perpustakaan" onmouseout="perpustakaan.src='images/icon_menu/perpustakaan.png'"><img src="images/icon_menu/perpustakaan.png" name="perpustakaan" width="40" height="40" border="0" id="perpustakaan"/><br>
                <font face="Verdana" size="1" color="#FFFFFF"><b>Katalog Pustaka</b></font></a></div></td>
			  </tr>
			</table>
	  </div>
        <div id="buletinmenu"  >
			<table  border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<?php
				if (SI_USER_ID()!="landlord" && SI_USER_ID()!="LANDLORD" && SI_USER_ID()!="adminsiswa" && SI_USER_ID()!="ADMINSISWA") {
				?>
				<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="framecenter.php" target="framecenter" onmouseover="info.src='images/icon_menu/info_on.png'" name="info" id="info" onmouseout="info.src='images/icon_menu/info.png'"><img src="images/icon_menu/info.png" name="info" width="40" height="40" border="0" id="info"/><br>
    <font face="Verdana" size="1" color="#FFFFFF"><b>Notifikasi</b></font></a></div></td>
                
                <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/rubriksekolah/beritasekolah.php" target="framecenter" onmouseover="rubriksekolah.src='images/icon_menu/rubrik_on.png'" name="rubriksekolah" id="rubriksekolah" onmouseout="rubriksekolah.src='images/icon_menu/rubrik.png'"><img src="images/icon_menu/rubrik.png"  border="0" name="rubriksekolah" id="rubriksekolah"/><br>
				<font face="Verdana" size="1" color="#FFFFFF"><b>Rubrik&nbsp;Sekolah</b></font></a></div></td>
				
				<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/beritasiswa/beritasiswa.php" target="framecenter" onmouseover="beritasiswa.src='images/icon_menu/beritasiswa_on.png'" name="beritasiswa" id="beritasiswa" onmouseout="beritasiswa.src='images/icon_menu/beritasiswa.png'"><img src="images/icon_menu/beritasiswa.png"  border="0" name="beritasiswa" id="beritasiswa"/><br>
				<font face="Verdana" size="1" color="#FFFFFF"><b>Berita&nbsp;Siswa</b></font></a></div></td>
				
				<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/pesan/pesan.php" target="framecenter" onmouseover="pesansiswa.src='images/icon_menu/pesan_on.png'" name="pesansiswa" id="pesansiswa" onmouseout="pesansiswa.src='images/icon_menu/pesan.png'"><img src="images/icon_menu/pesan.png"  border="0" name="pesansiswa" id="pesansiswa"/><br>
				<font face="Verdana" size="1" color="#FFFFFF"><b>Pesan</b></font></a></div></td>
				
				<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/agendasiswa/agenda.php" target="framecenter" onmouseover="agendaguru.src='images/icon_menu/agenda_on.png'" name="agendaguru" id="agendaguru" onmouseout="agendaguru.src='images/icon_menu/agenda.png'"><img src="images/icon_menu/agenda.png"  border="0" name="agendaguru" id="agendaguru"/><br>
				<font face="Verdana" size="1" color="#FFFFFF"><b>Agenda&nbsp;Siswa</b></font></a></div></td>
				
				<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/galerifoto/galerifoto.php" target="framecenter" onmouseover="galerifoto.src='images/icon_menu/galeri_on.png'" name="galerifoto" id="galerifoto" onmouseout="galerifoto.src='images/icon_menu/galeri.png'"><img src="images/icon_menu/galeri.png"  border="0" name="galerifoto" id="galerifoto"/><br>
				<font face="Verdana" size="1" color="#FFFFFF"><b>Galeri&nbsp;Foto</b></font></a></div></td>
				<?php } ?>
				<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/filesharing/main.php" target="framecenter" onmouseover="filesharing.src='images/icon_menu/fileshare_on.png'" name="filesharing" id="filesharing" onmouseout="filesharing.src='images/icon_menu/fileshare.png'"><img src="images/icon_menu/fileshare.png"  border="0" name="filesharing" id="filesharing"/><br>
				<font face="Verdana" size="1" color="#FFFFFF"><b>File&nbsp;Sharing</b></font></a></div></td>
                				<?php 
				if (SI_USER_ID()=="adminsiswa" || SI_USER_ID()=="ADMINSISWA" || SI_USER_ID()=="landlord" || SI_USER_ID()=="LANDLORD") {
				?>
				<td style="padding-right:15px">
				<div align="center">
					<a style="text-decoration:none;" href="buletin/manage/beritasekolah.php" target="framecenter"
						onmouseover="daftar.src='images/icon_menu/beritasiswa_on.png'" name="daftar" id="daftar"
						onmouseout="daftar.src='images/icon_menu/beritasiswa.png'">
						<img src="images/icon_menu/beritasiswa.png"  border="0" name="daftar" id="daftar"/><br>
						<font face="Verdana" size="1" color="#FFFFFF"><b>Daftar Buletin</b></font>
					</a>
				</div>
				</td>
				<?php } ?>
                
			  </tr>
			</table>
	  </div>
		<div id="pengaturanmenu" style="display:none" >
			<table  border="0" cellspacing="0" cellpadding="0">
			  <tr>
              	<?php
				if (SI_USER_ID()!="landlord" && SI_USER_ID()!="LANDLORD" && SI_USER_ID()!="adminsiswa" && SI_USER_ID()!="ADMINSISWA") {
				?>	
			
				<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="pengaturan/theme_list.php" target="framecenter" onmouseover="gantilogon.src='images/icon_menu/gantitema_on.png'" name="gantilogon" id="gantilogon" onmouseout="gantilogon.src='images/icon_menu/gantitema.png'"><img src="images/icon_menu/gantitema.png"  border="0" name="gantilogon" id="gantilogon2"/><br>
				<font face="Verdana" size="1" color="#FFFFFF"><b>Ganti Tema</b></font></a></div></td><?php } ?>
			  </tr>
			</table>
	  </div>
		<div id="dotnetmenu" style="display:none" >
			<table  border="0" cellspacing="0" cellpadding="0">
			  <tr>
				&nbsp;
			  </tr>
			</table>
		</div>
        <!-- SUB MENU END -->
        </td>
		<td background="<?php echo GetThemeDir()?>InfoSiswa_06.png" width="150"></td>
		<td background="<?php echo GetThemeDir()?>InfoSiswa_07.png" width="136" height="64">		</td>
		<td>
			<img src="<?php echo GetThemeDir()?>InfoSiswa_08.png" width="17" height="64" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="<?php echo GetThemeDir()?>InfoSiswa_09.png" width="20" height="8" alt=""></td>
		<td colspan="2" background="<?php echo GetThemeDir()?>InfoSiswa_10.png" width="716" height="8">
		</td>
		<td background="<?php echo GetThemeDir()?>InfoSiswa_11.png" width="136" height="8">
		</td>
		<td>
			<img src="<?php echo GetThemeDir()?>InfoSiswa_12.png" width="17" height="8" alt=""></td>
	</tr>
</table>    
</body>
</html>