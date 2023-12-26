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
require_once("sessionchecker.php");
require_once("include/sessioninfo.php");
require_once('include/common.php');
require_once('include/config.php');
require_once('include/db_functions.php');

$menu="";
if (isset($_REQUEST['menu']))
	$menu=$_REQUEST['menu'];

if ($menu=="akademik")
{	
?>
<table  border="0" cellspacing="0" cellpadding="0" class="tab">
  <tr>
    <td scope="row" style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="jadwal/kalender_main.php" target="framecenter" onmouseover="kalender.src='images/icon_menu/kalender_on.png'" name="kalender" id="kalender" onmouseout="kalender.src='images/icon_menu/kalender.png'"><img src="images/icon_menu/kalender.png" name="kalender" width="30" height="30" border="0" id="kalender"/><br>
    <span class="iconTitle">Kalender&nbsp;Akademik</span></a></div></td>
    
    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="jadwal.php" target="framecenter" onmouseover="jadwal.src='images/icon_menu/jadwal_on.png'" name="jadwal" id="jadwal" onmouseout="jadwal.src='images/icon_menu/jadwal.png'"><img src="images/icon_menu/jadwal.png" name="jadwal" width="30" height="30" border="0" id="jadwal"/><br>
    <span class="iconTitle">Jadwal&nbsp;Mengajar</span></a></div></td>
    
    <?php if (SI_USER_LEVEL()!=0){ ?>
    <td  style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="pelajaran.php" target="framecenter" onmouseover="pelajaran.src='images/icon_menu/pelajaran_on.png'" name="pelajaran" id="pelajaran" onmouseout="pelajaran.src='images/icon_menu/pelajaran.png'"><img src="images/icon_menu/pelajaran.png" name="pelajaran" width="30" height="30" border="0" id="pelajaran"/><br>
    <span class="iconTitle">Pelajaran</span></a></div></td>
    <?php } ?>

    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="penilaian.php" target="framecenter" onmouseover="penilaian.src='images/icon_menu/penilaian_on.png'" name="penilaian" id="penilaian" onmouseout="penilaian.src='images/icon_menu/penilaian.png'"><img src="images/icon_menu/penilaian.png" name="penilaian" width="30" height="30" border="0" id="penilaian"/><br>
    <span class="iconTitle">Penilaian</span></a></div></td>

    <?php if (SI_USER_LEVEL()!=0){ ?>
      <td style="padding-right:15px">
          <div align="center">
              <a style="text-decoration:none;" href="exim.php" target="framecenter" onmouseover="exim.src='images/icon_menu/exim_on.png'"
                 name="exim" id="exim" onmouseout="exim.src='images/icon_menu/exim.png'">
                  <img src="images/icon_menu/exim.png" name="exim" width="30" height="30" border="0" id="exim"/><br>
                  <span class="iconTitle">Ekspor &amp; Impor</span>
              </a>
          </div>
      </td>
    <?php } ?>
    
    <?php if (SI_USER_LEVEL()!=0){ ?>
	<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="presensi.php" target="framecenter" onmouseover="presensi.src='images/icon_menu/presensi_on.png'" name="presensi" id="presensi" onmouseout="presensi.src='images/icon_menu/presensi.png'"><img src="images/icon_menu/presensi.png" name="presensi" width="30" height="30" border="0" id="presensi"/><br>
    <span class="iconTitle">Presensi</span></a></div></td>
    <?php } ?>


    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="siswa/siswa.php" target="framecenter" onmouseover="infosiswa.src='images/icon_menu/infosiswa_on.png'" name="infosiswa" id="infosiswa" onmouseout="infosiswa.src='images/icon_menu/infosiswa.png'"><img src="images/icon_menu/infosiswa.png" name="infosiswa" width="30" height="30" border="0" id="infosiswa"/><br>
    <span class="iconTitle">Info&nbsp;Siswa</span></a></div></td>

	<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="catatankejadian.php" target="framecenter" onmouseover="kejadian.src='images/icon_menu/catatankejadian_on.png'" name="kejadian" id="kejadian" onmouseout="kejadian.src='images/icon_menu/catatankejadian.png'"><img src="images/icon_menu/catatankejadian.png" name="kejadian" width="30" height="30" border="0" id="kejadian"/><br>
    <span class="iconTitle">Catatan&nbsp;Siswa</span></a></div></td>
    
    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="perpustakaan/perpustakaan.php" target="framecenter" onmouseover="perpustakaan.src='images/icon_menu/perpustakaan_on.png'" name="perpustakaan" id="perpustakaan" onmouseout="perpustakaan.src='images/icon_menu/perpustakaan.png'"><img src="images/icon_menu/perpustakaan.png" name="perpustakaan" width="30" height="30" border="0" id="perpustakaan"/><br>
    <span class="iconTitle">Katalog Pustaka</span></a></div></td>
  </tr>
</table>
<?php } 
if ($menu=="buletin"){	
?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="home.php" target="framecenter" onmouseover="info.src='images/icon_menu/info_on.png'" name="info" id="info" onmouseout="info.src='images/icon_menu/info.png'"><img src="images/icon_menu/info.png" name="info" width="30" height="30" border="0" id="info"/><br>
    <span class="iconTitle">Beranda</span></a></div></td>

    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/rubriksekolah/beritasekolah.php" target="framecenter" onmouseover="rubrik.src='images/icon_menu/rubrik_on.png'" name="rubrik" id="rubrik" onmouseout="rubrik.src='images/icon_menu/rubrik.png'"><img src="images/icon_menu/rubrik.png" name="rubrik" width="30" height="30" border="0" id="rubrik"/><br>
    <span class="iconTitle">Berita Sekolah</span></a></div></td>
  
    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/beritaguru/beritaguru.php" target="framecenter" onmouseover="beritaguru.src='images/icon_menu/beritaguru_on.png'" name="beritaguru" id="beritaguru" onmouseout="beritaguru.src='images/icon_menu/beritaguru.png'"><img src="images/icon_menu/beritaguru.png" name="beritaguru" width="30" height="30" border="0" id="beritaguru"/><br>
    <span class="iconTitle">Berita Guru</span></a></div></td>
    
    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/beritasiswa/beritasiswa.php" target="framecenter" onmouseover="beritasiswa.src='images/icon_menu/beritasiswa_on.png'" name="beritasiswa" id="beritasiswa" onmouseout="beritasiswa.src='images/icon_menu/beritasiswa.png'"><img src="images/icon_menu/beritasiswa.png" name="beritasiswa" width="30" height="30" border="0" id="beritasiswa"/><br>
    <span class="iconTitle">Berita Siswa</span></a></div></td>
	
	<?php if (SI_USER_LEVEL() != 0){ ?>
    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/pesan/pesan.php" target="framecenter" onmouseover="pesan.src='images/icon_menu/pesan_on.png'" name="pesan" id="pesan" onmouseout="pesan.src='images/icon_menu/pesan.png'"><img src="images/icon_menu/pesan.png" name="pesan" width="30" height="30" border="0" id="pesan"/><br>
    <span class="iconTitle">Pesan</span></a></div></td>
    <?php } ?>
    
    <?php if (SI_USER_LEVEL()!=0){ ?>
    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/agendaguru/agenda.php" target="framecenter" onmouseover="agendaguru.src='images/icon_menu/agenda_on.png'" name="agendaguru" id="agendaguru" onmouseout="agendaguru.src='images/icon_menu/agenda.png'"><img src="images/icon_menu/agenda.png" name="agendaguru" width="30" height="30" border="0" id="agendaguru"/><br>
    <span class="iconTitle">Agenda Guru</span></a></div></td>
   
    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/galerifoto/galerifoto.php" target="framecenter" onmouseover="galerifoto.src='images/icon_menu/galeri_on.png'" name="galerifoto" id="galerifoto" onmouseout="galerifoto.src='images/icon_menu/galeri.png'"><img src="images/icon_menu/galeri.png" name="galerifoto" width="30" height="30" border="0" id="galerifoto"/><br>
    <span class="iconTitle">Galeri Foto</span></a></div></td>
    <?php } ?>
     <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/filesharing/main.php" target="framecenter" onmouseover="filesharing.src='images/icon_menu/fileshare_on.png'" name="filesharing" id="filesharing" onmouseout="filesharing.src='images/icon_menu/fileshare.png'"><img src="images/icon_menu/fileshare.png" name="filesharing" width="30" height="30" border="0" id="filesharing"/><br>
    <span class="iconTitle">File Sharing</b></span></a></div></td>
	 
	<?php if (SI_USER_LEVEL() != 0){ ?>
    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="buletin/surat/daftarsurat.main.php" target="framecenter" onmouseover="surat.src='images/icon_menu/surat_on.png'" name="surat" id="surat" onmouseout="surat.src='images/icon_menu/surat.png'"><img src="images/icon_menu/surat.png" name="surat" width="30" height="30" border="0" id="pesan"/><br>
    <span class="iconTitle">Kotak Surat</span></a></div></td>
    <?php } ?>
  </tr>
</table>
<?php } ?>

<?php if ($menu=="pengaturan") { ?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="#" onmouseover="gantipassword.src='images/icon_menu/gantipassword_on.png'" name="gantipassword" id="gantipassword" onmouseout="gantipassword.src='images/icon_menu/gantipassword.png'" onclick="ganti()"><img src="images/icon_menu/gantipassword.png" name="gantipassword" width="30" height="30" border="0" id="gantipassword"/><br>
    <span class="iconTitle">Ganti Password</span></a></div></td>
<?php  if (SI_USER_ID()=="landlord" || SI_USER_ID()=="LANDLORD") {	?>
    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="pengaturan/user.php" target="framecenter" onmouseover="daftarpengguna.src='images/icon_menu/pengguna_on.png'" name="daftarpengguna" id="daftarpengguna" onmouseout="daftarpengguna.src='images/icon_menu/pengguna.png'"><img src="images/icon_menu/pengguna.png" name="daftarpengguna" width="30" height="30" border="0" id="daftarpengguna"/><br>
    <span class="iconTitle">Daftar Pengguna</span></a></div></td>
<?php  } ?>
<?php  if (SI_USER_ID()=="landlord" || SI_USER_ID()=="LANDLORD") { ?>
    <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="pengaturan/schooltube.php" target="framecenter"><img src="images/schooltube.png" width="30" height="30" border="0" /><br>
    <span class="iconTitle">School Tube</span></a></div></td>
<?php  } ?>
<?php  if (SI_USER_ID()=="landlord" || SI_USER_ID()=="LANDLORD") { ?>
  <td style="padding-right:15px"><div align="center"><a style="text-decoration:none;" href="pengaturan/queryerror.php" target="framecenter"><img src="images/ico/b_warning.png" width="30" height="30" border="0" /><br>
              <span class="iconTitle">Query Error Log</span></a></div></td>
<?php  } ?>
  </tr>
</table>
<?php } ?>

<?php if ($menu=="kepegawaian") { ?>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
  <td style="padding-right:15px">
	<div align="center">
	<a style="text-decoration:none;" href="pegawai/data.php" target="framecenter"
	   onmouseover="info.src='images/icon_menu/pegawai_data_on.png'" name="info" id="info"
	   onmouseout="info.src='images/icon_menu/pegawai_data.png'">
	<img src="images/icon_menu/pegawai_data.png" name="info" width="30" height="30" border="0" id="info"/><br>
    <span class="iconTitle">Data Pegawai</span></a>
	</div>
  </td>
  <td style="padding-right:15px">
	<div align="center">
	<a style="text-decoration:none;" href="pegawai/struktur.php" target="framecenter"
	   onmouseover="info2.src='images/icon_menu/pegawai_struktur_on.png'" name="info" id="info"
	   onmouseout="info2.src='images/icon_menu/pegawai_struktur.png'">
	<img src="images/icon_menu/pegawai_struktur.png" name="info2" width="30" height="30" border="0" id="info2"/><br>
    <span class="iconTitle">Struktur Jabatan</span></a>
	</div>
  </td>
  <td style="padding-right:15px">
	<div align="center">
	<a style="text-decoration:none;" href="pegawai/dukpangkat.php" target="framecenter"
	   onmouseover="info3.src='images/icon_menu/pegawai_duk_on.png'" name="info" id="info"
	   onmouseout="info3.src='images/icon_menu/pegawai_duk.png'">
	<img src="images/icon_menu/pegawai_duk.png" name="info3" width="30" height="30" border="0" id="info3"/><br>
    <span class="iconTitle">Daftar Urut Kepangkatan</span></a>
	</div>
  </td>
</tr>
</table>
<?php } ?>

<?php if ($menu=="elearning") { ?>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
    <td style="padding-right:15px" width="60px">
        <div align="center">
            <a style="text-decoration:none;" href="elearning/channel.php" target="framecenter"
               onmouseover="info.src='images/icon_menu/pegawai_data_on.png'" name="info" id="info"
               onmouseout="info.src='images/icon_menu/pegawai_data.png'">
                <img src="images/channel.png" name="info" width="30" height="30" border="0" id="info"/><br>
                <span class="iconTitle">Channel</span></a>
        </div>
    </td>
    <td style="padding-right:15px" width="100px">
        <div align="center">
            <a style="text-decoration:none;" href="elearning/media.php" target="framecenter"
               onmouseover="info.src='images/icon_menu/pegawai_data_on.png'" name="info" id="info"
               onmouseout="info.src='images/icon_menu/pegawai_data.png'">
                <img src="images/video.png" name="info" width="26" height="26" border="0" id="info"/><br>
                <span class="iconTitle">Upload Video</span></a>
        </div>
    </td>
    <td style="padding-right:15px" width="100px">
        <div align="center">
            <a style="text-decoration:none;" href="elearning/modul.php" target="framecenter"
               onmouseover="info.src='images/icon_menu/pegawai_data_on.png'" name="info" id="info"
               onmouseout="info.src='images/icon_menu/pegawai_data.png'">
                <img src="images/modul.png" name="info" width="30" height="30" border="0" id="info"/><br>
                <span class="iconTitle">Modul Video</span></a>
        </div>
    </td>
    <td style="padding-right:15px" width="100px">
        <div align="center">
            <a style="text-decoration:none;" href="elearning/notes.php" target="framecenter"
               onmouseover="info.src='images/icon_menu/pegawai_data_on.png'" name="info" id="info"
               onmouseout="info.src='images/icon_menu/pegawai_data.png'">
                <img src="images/notes.png" name="info" width="26" height="26" border="0" id="info"/><br>
                <span class="iconTitle">Daftar Catatan</span></a>
        </div>
    </td>
    <td style="padding-right:15px" width="60px">
        <div align="center">
            <a style="text-decoration:none;" href="elearning/statistik.php" target="framecenter"
               onmouseover="info.src='images/icon_menu/pegawai_data_on.png'" name="info" id="info"
               onmouseout="info.src='images/icon_menu/pegawai_data.png'">
                <img src="images/statistik.png" name="info" width="30" height="30" border="0" id="info"/><br>
                <span class="iconTitle">Statistik</span></a>
        </div>
    </td>

</tr>
</table>
<?php } ?>
