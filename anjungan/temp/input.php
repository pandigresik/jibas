<?php
require_once("../include/config.php");
require_once("../include/common.php");
require_once("../include/db_functions.php");
require_once("../library/datearith.php");
require_once("input.func.php");

OpenDb();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="input.cs">
<link rel="stylesheet" type="text/css" href="../script/themes/ui-lightness/jquery.ui.all.css"  />    
<script type="text/javascript" src='../script/jquery-latest.js'></script>
<script type="text/javascript" src="../script/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src='../script/rupiah2.js'></script>
<script type="text/javascript" src='../script/validator.js'></script>
<script type="text/javascript" src='input.js'></script>
</head>
<body>
<form name="main" method="post" onsubmit="return ValidateInput()">
<input type="submit" value="Kirim">
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td colspan="2" align="left">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="color: #999; font-size: 16px;">Data Pribadi Siswa</font>
        <br><br>
    </td>
</tr>    
<tr>
    <td width="15%" align="right">
        N I S N:
    </td>
    <td width="*" align="left">
        <input type="text" name="nisn" id="nisn" size="40" maxlength="100" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        N I K:
    </td>
    <td width="*" align="left">
        <input type="text" name="nik" id="nik" size="40" maxlength="100" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        No UN Sebelumnya:
    </td>
    <td width="*" align="left">
        <input type="text" name="noun" id="noun" size="40" maxlength="100" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        <strong>Nama</strong>:
    </td>
    <td width="*" align="left">
        <input type="text" name="nama" id="nama" size="70" maxlength="255" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Panggilan:
    </td>
    <td width="*" align="left">
        <input type="text" name="panggilan" id="panggilan" size="40" maxlength="100" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jenis Kelamin:
    </td>
    <td width="*" align="left">
        <input type="radio" name="kelamin" id="kelamin" value="l" class="inputbox" checked/>&nbsp;Laki-laki&nbsp;&nbsp;
        <input type="radio" name="kelamin" id="kelamin" value="p" class="inputbox"/>&nbsp;Perempuan
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Tempat Lahir:
    </td>
    <td width="*" align="left">
        <input type="text" name="tmplahir" id="tmplahir" size="40" maxlength="100" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Tanggal Lahir:
    </td>
    <td width="*" align="left">
<?php      ShowYearCombo('thnlahir', 'changeTahunLahirSiswa()', 1990, date('Y') + 1, date('Y')); ?>&nbsp;
<?php      ShowMonthCombo('blnlahir', 'changeBulanLahirSiswa()', date('n')); ?>&nbsp;
        <span id="divTglLahirSiswa">
<?php      ShowDateCombo('tgllahir', 'changeTanggalLahirSiswa()', date('Y'), date('n'), date('j')); ?>
        </span>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Agama:
    </td>
    <td width="*" align="left">
<?php      ShowAgamaCombo(); ?>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Suku:
    </td>
    <td width="*" align="left">
<?php      ShowSukuCombo(); ?>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Status:
    </td>
    <td width="*" align="left">
<?php      ShowStatusCombo(); ?>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Kondisi:
    </td>
    <td width="*" align="left">
<?php      ShowKondisiCombo(); ?>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Kewarganegaraan:
    </td>
    <td width="*" align="left">
        <input type="radio" name="warga" id="warga" value="WNI" checked/>&nbsp;WNI&nbsp;&nbsp;
        <input type="radio" name="warga" id="warga" value="WNA"/>&nbsp;WNA
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Anak ke:
    </td>
    <td width="*" align="left">
        <input type="text" name="urutananak" id="urutananak" size="3" maxlength="3" class="inputbox">&nbsp;dari&nbsp;
        <input type="text" name="jumlahanak" id="jumlahanak" size="3" maxlength="3" class="inputbox">&nbsp;bersaudara
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Status Anak:
    </td>
    <td width="*" align="left">
<?php      ShowStatusAnakCombo(); ?>        
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jumlah Saudara Kandung:
    </td>
    <td width="*" align="left">
        <input type="text" name="jkandung" id="jkandung" size="3" maxlength="3" class="inputbox">&nbsp;orang
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jumlah Saudara Tiri:
    </td>
    <td width="*" align="left">
        <input type="text" name="jtiri" id="jtiri" size="3" maxlength="3" class="inputbox">&nbsp;orang
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Bahasa:
    </td>
    <td width="*" align="left">
        <input type="text" name="bahasa" id="bahasa" size="40" maxlength="100" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Alamat:
    </td>
    <td width="*" align="left">
        <textarea name="alamatsiswa" id="alamatsiswa" rows="2" cols="40" class="inputbox"></textarea>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Kode Pos:
    </td>
    <td width="*" align="left">
        <input type="text" name="kodepos" id="kodepos" size="5" maxlength="8" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jarak ke Sekolah:
    </td>
    <td width="*" align="left">
        <input type="text" name="jarak" id="jarak" size="4" maxlength="4" class="inputbox">&nbsp;km
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Telpon:
    </td>
    <td width="*" align="left">
        <input type="text" name="telponsiswa" id="telponsiswa" size="20" maxlength="20" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Handphone:
    </td>
    <td width="*" align="left">
        <input type="text" name="hpsiswa" id="hpsiswa" size="20" maxlength="20" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Email:
    </td>
    <td width="*" align="left">
        <input type="text" name="emailsiswa" id="emailsiswa" size="40" maxlength="100" class="inputbox">
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="color: #999; font-size: 16px;">Data Sekolah Siswa</font>
        <br><br>
    </td>
</tr>    
<tr>
    <td width="15%" align="right" valign='top'>
        Asal Sekolah:
    </td>
    <td width="*" align="left">
<?php      ShowJenjangSekolahCombo() ?><br>
        <span id='divAsalSekolah'>
<?php      ShowAsalSekolahCombo('TK/RA') ?>
        </span>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        No Ijasah:
    </td>
    <td width="*" align="left">
        <input type="text" name="noijasah" id="noijasah" size="40" maxlength="100" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Tgl Ijasah:
    </td>
    <td width="*" align="left">
        <input type="text" name="tglijasah" id="tglijasah" size="40" maxlength="100" class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Keterangan:
    </td>
    <td width="*" align="left">
        <textarea name="ketsekolah" id="ketsekolah" rows="2" cols="40" class="inputbox"></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="color: #999; font-size: 16px;">Riwayat Kesehatan Siswa</font>
        <br><br>
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Golongan Darah:
    </td>
    <td width="*" align="left">
        <input type="radio" name="gol" id="gol" value="A"/>&nbsp;A&nbsp;&nbsp;
        <input type="radio" name="gol" id="gol" value="AB"/>&nbsp;AB&nbsp;&nbsp;
        <input type="radio" name="gol" id="gol" value="B"/>&nbsp;B&nbsp;&nbsp;
        <input type="radio" name="gol" id="gol" value="O"/>&nbsp;O&nbsp;&nbsp;
        <input type="radio" name="gol" id="gol" value="" checked/>&nbsp;<em>(belum ada data)</em>
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Berat:</td>
    <td colspan="2">
        <input type="text" name="berat" id="berat" size="4" maxlength="4" class="inputbox">&nbsp;kg        	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Tinggi:</td>
    <td colspan="2">
        <input type="text" name="tinggi" id="tinggi" size="4" maxlength="4" class="inputbox">&nbsp;cm      	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Riwayat Penyakit:
    </td>
    <td width="*" align="left">
        <textarea name="kesehatan" id="kesehatan" rows="3" cols="40" class="inputbox"></textarea>
    </td>
</tr>
</table>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td colspan="4" align="left">
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="color: #999; font-size: 16px;">Data Orangtua Siswa</font>
        <br><br>
    </td>
</tr>
<tr height='25'>
    <td width="15%" align="right" valign="top">
        &nbsp;
    </td>
    <td width="20%" align="center" valign="middle" bgcolor="#DBD8F3">
        <strong>Ayah</strong>
    </td>
    <td width="20%" align="center" valign="middle" bgcolor="#E9AFCF">
        <strong>Ibu</strong>
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Nama:
    </td>
    <td width="*" align="left" bgcolor="#DBD8F3">
        <input type="text" name="namaayah" id="namaayah" size="40" maxlength="100" class="inputbox"><br>
        <input type="checkbox" name="almayah" id="almayah" value="1" title="Klik disini jika Ayah Almarhum"/>&nbsp;&nbsp;<font color="#990000" size="1">(Almarhum)</font>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="namaibu" id="namaibu" size="40" maxlength="100" class="inputbox"><br>
        <input type="checkbox" name="almibu" id="almibu" value="1" title="Klik disini jika Ayah Almarhumah"/>&nbsp;&nbsp;<font color="#990000" size="1">(Almarhumah)</font>
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Status Orangtua:
    </td>
    <td width="*" align="left" bgcolor="#DBD8F3">
        <?php ShowStatusOrtuCombo('statusayah') ?>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <?php ShowStatusOrtuCombo('statusibu') ?>
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Tempat Lahir:
    </td>
    <td width="*" align="left" bgcolor="#DBD8F3">
        <input type="text" name="tmplahirayah" id="tmplahirayah" size="40" maxlength="100" class="inputbox">
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="tmplahiribu" id="tmplahiribu" size="40" maxlength="100" class="inputbox">
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Tanggal Lahir:
    </td>
    <td width="*" align="left" bgcolor="#DBD8F3">
<?php      ShowYearCombo('thnlahirayah', 'changeTahunLahirAyah()', 1970, date('Y') + 1, date('Y')); ?>&nbsp;
<?php      ShowMonthCombo('blnlahirayah', 'changeBulanLahirAyah()', date('n')); ?>&nbsp;
        <span id="divTglLahirAyah">
<?php      ShowDateCombo('tgllahirayah', 'changeTanggalLahirAyah()', date('Y'), date('n'), date('j')); ?>
        </span>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
<?php      ShowYearCombo('thnlahiribu', 'changeTahunLahirIbu()', 1970, date('Y') + 1, date('Y')); ?>&nbsp;
<?php      ShowMonthCombo('blnlahiribu', 'changeBulanLahirIbu()', date('n')); ?>&nbsp;
        <span id="divTglLahirIbu">
<?php      ShowDateCombo('tgllahiribu', 'changeTanggalLahirIbu()', date('Y'), date('n'), date('j')); ?>
        </span>
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Pendidikan:
    </td>
    <td width="*" align="left" bgcolor="#DBD8F3">
<?php      ShowPendidikanCombo('pendidikanayah') ?>        
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
<?php      ShowPendidikanCombo('pendidikanibu') ?>                
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Pekerjaan:
    </td>
    <td width="*" align="left" bgcolor="#DBD8F3">
<?php      ShowPekerjaanCombo('pekerjaanayah') ?>        
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
<?php      ShowPekerjaanCombo('pekerjaanibu') ?>                
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Penghasilan:
    </td>
    <td width="*" align="left" bgcolor="#DBD8F3">
        <input type="text" name="penghasilanayah" id="penghasilanayah" size="40" maxlength="100" class="inputbox"
               onblur="formatRupiah('penghasilanayah')" onfocus="unformatRupiah('penghasilanayah')">
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="penghasilanibu" id="penghasilanibu" size="40" maxlength="100" class="inputbox"
               onblur="formatRupiah('penghasilanibu')" onfocus="unformatRupiah('penghasilanibu')">
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Email:
    </td>
    <td width="*" align="left" bgcolor="#DBD8F3">
        <input type="text" name="emailayah" id="emailayah" size="40" maxlength="100" class="inputbox">
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="emailibu" id="emailibu" size="40" maxlength="100" class="inputbox">
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Nama Wali:
    </td>
    <td width="*" align="left">
        <input type="text" name="namawali" id="namawali" size="40" maxlength="100" class="inputbox">
    </td>
    <td width="*" align="left">
        &nbsp;
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Alamat Orangtua:
    </td>
    <td width="*" align="left">
        <textarea name="alamatortu" id="alamatortu" rows="2" cols="30" class="inputbox"></textarea>
    </td>
    <td width="*" align="left">
        &nbsp;
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Telepon:
    </td>
    <td width="*" align="left">
        <input type="text" name="telponortu" id="telponortu" size="40" maxlength="100" class="inputbox">
    </td>
    <td width="*" align="left">
        &nbsp;
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        HP Ortu #1:
    </td>
    <td width="*" align="left">
        <input type="text" name="hportu" id="hportu" size="40" maxlength="100" class="inputbox">
    </td>
    <td width="*" align="left">
        &nbsp;
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        HP Ortu #2:
    </td>
    <td width="*" align="left">
        <input type="text" name="hportu2" id="hportu2" size="40" maxlength="100" class="inputbox">
    </td>
    <td width="*" align="left">
        &nbsp;
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        HP Ortu #3:
    </td>
    <td width="*" align="left">
        <input type="text" name="hportu3" id="hportu3" size="40" maxlength="100" class="inputbox">
    </td>
    <td width="*" align="left">
        &nbsp;
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
</table>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td colspan="2" align="left">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="color: #999; font-size: 16px;">Informasi Tambahan</font>
        <br><br>
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Hobi:</td>
    <td colspan="2">
        <textarea name="hobi" id="hobi" rows="2" cols="30" class="inputbox"></textarea>    	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Alamat Surat:</td>
    <td colspan="2">
        <textarea name="alamatsurat" id="alamatsurat" rows="2" cols="30" class="inputbox"></textarea>    	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Keterangan:</td>
    <td colspan="2">
        <textarea name="keterangan" id="keterangan" rows="2" cols="30" class="inputbox"></textarea>    	
    </td>
</tr> 
</table>
<input type="submit" value="Kirim">
</form>    
<br><br><br>
</body>
</html>
<?php
CloseDb();
?>
