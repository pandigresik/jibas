<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 3.11 (May 02, 2018)
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
require_once("../include/common.php");
require_once("../include/db_functions.php");
require_once("../library/datearith.php");
require_once("psb.input.func.php");
require_once("psb.config.php");

$isReadOnly = (PSB_ENABLE_INPUT == 0) ? "readonly" : "";
$isDisabled = (PSB_ENABLE_INPUT == 0) ? "disabled" : "";

OpenDb();
?>
<form name="psb_form" id="psb_form" method="post">
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td colspan="2" align="left">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
        <font style="color: #557d1d; font-size: 16px;">Data Penerimaan Calon Siswa</font>
        <br><br>
    </td>
</tr>    
<tr>
    <td width="15%" align="right">
        Departemen:
    </td>
    <td width="*" align="left">
<?php      ShowDepartemenCombo() ?>        
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Penerimaan:
    </td>
    <td width="*" align="left">
        <div id="psb_divProses">
<?php      ShowPenerimaanCombo($selDept) ?>
        </div>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Kelompok:
    </td>
    <td width="*" align="left">
        <div id="psb_divKelompok">
<?php      ShowKelompokCombo($selProses) ?>
        </div>
    </td>
</tr>

</table>
<br>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td colspan="2" align="left">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
        <font style="color: #557d1d; font-size: 16px;">Data Pribadi Calon Siswa</font>
        <br><br>
    </td>
</tr>    
<tr>
    <td width="15%" align="right">
        N I S N:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_nisn" id="psb_nisn" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        N I K:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_nik" id="psb_nik" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        No UN Sebelumnya:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_noun" id="psb_noun" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Nama:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_nama" id="psb_nama" size="70" maxlength="255" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Panggilan:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_panggilan" id="psb_panggilan" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jenis Kelamin:
    </td>
    <td width="*" align="left">
        <input type="radio" name="psb_kelamin" id="psb_kelamin" value="l" class="inputbox" checked/>&nbsp;Laki-laki&nbsp;&nbsp;
        <input type="radio" name="psb_kelamin" id="psb_kelamin" value="p" class="inputbox"/>&nbsp;Perempuan
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Tempat Lahir:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_tmplahir" id="psb_tmplahir" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Tanggal Lahir:
    </td>
    <td width="*" align="left">
<?php      ShowYearCombo('psb_thnlahir', 'psb_changeTahunLahirSiswa()', 1980, date('Y') + 1, date('Y')); ?>&nbsp;
<?php      ShowMonthCombo('psb_blnlahir', 'psb_changeBulanLahirSiswa()', date('n')); ?>&nbsp;
        <span id="psb_divTglLahirSiswa">
<?php      ShowDateCombo('psb_tgllahir', 'psb_changeTanggalLahirSiswa()', date('Y'), date('n'), date('j')); ?>
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
        <input type="radio" name="psb_warga" id="psb_warga" value="WNI" checked/>&nbsp;WNI&nbsp;&nbsp;
        <input type="radio" name="psb_warga" id="psb_warga" value="WNA"/>&nbsp;WNA
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Anak ke:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_urutananak" id="psb_urutananak" size="3" maxlength="3" class="inputbox" <?= $isReadOnly ?>>&nbsp;dari&nbsp;
        <input type="text" name="psb_jumlahanak" id="psb_jumlahanak" size="3" maxlength="3" class="inputbox" <?= $isReadOnly ?>>&nbsp;bersaudara
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
        <input type="text" name="psb_jkandung" id="psb_jkandung" size="3" maxlength="3" class="inputbox" <?= $isReadOnly ?>>&nbsp;orang
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jumlah Saudara Tiri:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_jtiri" id="psb_jtiri" size="3" maxlength="3" class="inputbox" <?= $isReadOnly ?>>&nbsp;orang
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Bahasa:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_bahasa" id="psb_bahasa" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Alamat:
    </td>
    <td width="*" align="left">
        <textarea name="psb_alamatsiswa" id="psb_alamatsiswa" rows="2" cols="40" onkeyup="psb_CopyAlamat()" class="inputbox" <?= $isReadOnly ?>></textarea>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Kode Pos:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_kodepos" id="psb_kodepos" size="5" maxlength="8" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jarak ke Sekolah:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_jarak" id="psb_jarak" size="4" maxlength="4" class="inputbox" <?= $isReadOnly ?>>&nbsp;km
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Telpon:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_telponsiswa" id="psb_telponsiswa" size="20" maxlength="20" <?= $isReadOnly ?> class="inputbox">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Handphone:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_hpsiswa" id="psb_hpsiswa" size="20" maxlength="20" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Email:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_emailsiswa" id="psb_emailsiswa" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
        <font style="color: #557d1d; font-size: 16px;">Data Sekolah Calon Siswa</font>
        <br><br>
    </td>
</tr>    
<tr>
    <td width="15%" align="right" valign='top'>
        Asal Sekolah:
    </td>
    <td width="*" align="left">
<?php      ShowJenjangSekolahCombo() ?><br>
        <span id='psb_divAsalSekolah'>
<?php      ShowAsalSekolahCombo('TK/RA') ?>
        </span>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        No Ijasah:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_noijasah" id="psb_noijasah" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Tgl Ijasah:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_tglijasah" id="psb_tglijasah" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Keterangan:
    </td>
    <td width="*" align="left">
        <textarea name="psb_ketsekolah" id="psb_ketsekolah" rows="2" cols="40" class="inputbox" <?= $isReadOnly ?>></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
        <font style="color: #557d1d; font-size: 16px;">Riwayat Kesehatan Calon Siswa</font>
        <br><br>
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Golongan Darah:
    </td>
    <td width="*" align="left">
        <input type="radio" name="psb_gol" id="psb_gol" value="A"/>&nbsp;A&nbsp;&nbsp;
        <input type="radio" name="psb_gol" id="psb_gol" value="AB"/>&nbsp;AB&nbsp;&nbsp;
        <input type="radio" name="psb_gol" id="psb_gol" value="B"/>&nbsp;B&nbsp;&nbsp;
        <input type="radio" name="psb_gol" id="psb_gol" value="O"/>&nbsp;O&nbsp;&nbsp;
        <input type="radio" name="psb_gol" id="psb_gol" value="" checked/>&nbsp;<em>(belum ada data)</em>
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Berat:</td>
    <td colspan="2">
        <input type="text" name="psb_berat" id="psb_berat" size="4" maxlength="4" class="inputbox" <?= $isReadOnly ?>>&nbsp;kg        	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Tinggi:</td>
    <td colspan="2">
        <input type="text" name="psb_tinggi" id="psb_tinggi" size="4" maxlength="4" class="inputbox" <?= $isReadOnly ?>>&nbsp;cm      	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Riwayat Penyakit:
    </td>
    <td width="*" align="left">
        <textarea name="psb_kesehatan" id="psb_kesehatan" rows="3" cols="40" class="inputbox" <?= $isReadOnly ?>></textarea>
    </td>
</tr>
</table>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td colspan="4" align="left">
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
        <font style="color: #557d1d; font-size: 16px;">Data Orangtua Calon Siswa</font>
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
        <input type="text" name="psb_namaayah" id="psb_namaayah" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>><br>
        <input type="checkbox" name="psb_almayah" id="psb_almayah" value="1" title="Klik disini jika Ayah Almarhum"/>&nbsp;&nbsp;<font color="#990000" size="1">(Almarhum)</font>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="psb_namaibu" id="psb_namaibu" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>><br>
        <input type="checkbox" name="psb_almibu" id="psb_almibu" value="1" title="Klik disini jika Ayah Almarhumah"/>&nbsp;&nbsp;<font color="#990000" size="1">(Almarhumah)</font>
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
        <?php ShowStatusOrtuCombo('psb_statusayah') ?>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <?php ShowStatusOrtuCombo('psb_statusibu') ?>
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
        <input type="text" name="psb_tmplahirayah" id="psb_tmplahirayah" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="psb_tmplahiribu" id="psb_tmplahiribu" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
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
<?php      ShowYearCombo('psb_thnlahirayah', 'psb_changeTahunLahirAyah()', 1900, date('Y') + 1, date('Y')); ?>&nbsp;
<?php      ShowMonthCombo('psb_blnlahirayah', 'psb_changeBulanLahirAyah()', date('n')); ?>&nbsp;
        <span id="psb_divTglLahirAyah">
<?php      ShowDateCombo('psb_tgllahirayah', 'psb_changeTanggalLahirAyah()', date('Y'), date('n'), date('j')); ?>
        </span>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
<?php      ShowYearCombo('psb_thnlahiribu', 'psb_changeTahunLahirIbu()', 1900, date('Y') + 1, date('Y')); ?>&nbsp;
<?php      ShowMonthCombo('psb_blnlahiribu', 'psb_changeBulanLahirIbu()', date('n')); ?>&nbsp;
        <span id="psb_divTglLahirIbu">
<?php      ShowDateCombo('psb_tgllahiribu', 'psb_changeTanggalLahirIbu()', date('Y'), date('n'), date('j')); ?>
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
<?php      ShowPendidikanCombo('psb_pendidikanayah') ?>        
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
<?php      ShowPendidikanCombo('psb_pendidikanibu') ?>                
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
<?php      ShowPekerjaanCombo('psb_pekerjaanayah') ?>        
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
<?php      ShowPekerjaanCombo('psb_pekerjaanibu') ?>                
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
        <input type="text" name="psb_penghasilanayah" id="psb_penghasilanayah" size="40" maxlength="100" class="inputbox"
               onblur="formatRupiah('psb_penghasilanayah')" onfocus="unformatRupiah('psb_penghasilanayah')" <?= $isReadOnly ?>>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="psb_penghasilanibu" id="psb_penghasilanibu" size="40" maxlength="100" class="inputbox"
               onblur="formatRupiah('psb_penghasilanibu')" onfocus="unformatRupiah('psb_penghasilanibu')" <?= $isReadOnly ?>>
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
        <input type="text" name="psb_emailayah" id="psb_emailayah" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="psb_emailibu" id="psb_emailibu" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
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
        <input type="text" name="psb_namawali" id="psb_namawali" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
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
        <textarea name="psb_alamatortu" id="psb_alamatortu" rows="2" cols="30" class="inputbox" <?= $isReadOnly ?>></textarea>
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
        <input type="text" name="psb_telponortu" id="psb_telponortu" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
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
        <input type="text" name="psb_hportu" id="psb_hportu" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
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
        <input type="text" name="psb_hportu2" id="psb_hportu2" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
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
        <input type="text" name="psb_hportu3" id="psb_hportu3" size="40" maxlength="100" class="inputbox" <?= $isReadOnly ?>>
    </td>
    <td width="*" align="left">
        &nbsp;
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
</table>
<br>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td colspan="2" align="left">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
        <font style="color: #557d1d; font-size: 16px;">Informasi Tambahan</font>
        <br><br>
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Hobi:</td>
    <td align="left" valign="top">
        <textarea name="psb_hobi" id="psb_hobi" rows="2" cols="40" class="inputbox" <?= $isReadOnly ?>></textarea>    	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Alamat Surat:</td>
    <td align="left" valign="top">
        <textarea name="psb_alamatsurat" id="psb_alamatsurat" rows="2" cols="40" class="inputbox" <?= $isReadOnly ?>></textarea>    	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Keterangan:</td>
    <td align="left" valign="top">
        <textarea name="psb_keterangan" id="psb_keterangan" rows="2" cols="40" class="inputbox" <?= $isReadOnly ?>></textarea>    	
    </td>
</tr> 
</table>
<br>
<div id="psb_divTambahanData">
<?php
    ShowTambahanData($selDept)
?>
</div>
<br>
<div id="psb_divSumbangan">
<?php
ShowSumbangan($selProses)
?>
</div>
<br>
<div id="psb_divNilaiUjian">
<?php
ShowNilaiUjian($selProses)
?>
</div>
<br>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td width="15%" align="right" valign="top">&nbsp;</td>
    <td align="left" valign="top">
        <input type="button" value="Simpan" class="but" style="height: 30px; width: 140px;" onclick="psb_Simpan()" <?= $isDisabled ?> >
    </td>
</tr>
</table>
<br><br><br>
</form>    
<?php
CloseDb();
?>
