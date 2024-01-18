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
require_once('infosiswa.session.php');
require_once('infosiswa.security.php');
require_once('infosiswa.config.php');
require_once('infosiswa.profile.func.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/rupiah.php');
require_once('../library/datearith.php');
require_once('../include/db_functions.php');

OpenDb();

$nis = $_SESSION["infosiswa.nis"];

$sql = "SELECT *
	      FROM jbsakad.siswa
		 WHERE nis = '".$nis."'";
$res = QueryDb($sql);
$row = mysqli_fetch_array($res);
$asalsekolah = $row['asalsekolah'];

$sql = "SELECT departemen
          FROM jbsakad.asalsekolah
         WHERE sekolah = '".$asalsekolah."'";
$res2 = QueryDb($sql);
$row2 = mysqli_fetch_array($res2);
$jenjangsekolah = $row2['departemen'];

$sql = "SELECT departemen 
          FROM jbsakad.siswa s, jbsakad.angkatan a 
         WHERE s.idangkatan = a.replid
           AND s.nis = '".$nis."'";
$res2 = QueryDb($sql);
$row2 = mysqli_fetch_array($res2);
$departemen = $row2['departemen'];
?>
<script language="javascript" src="infosiswa/infosiswa.profile.js"></script>
<div id="is_profile">
<form name="is_form" id="is_form" method="post">
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td colspan="2" align="left">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
        <font style="color: #557d1d; font-size: 16px;">Data Pribadi Siswa</font>
        <br><br>
    </td>
</tr>    
<tr>
    <td width="15%" align="right">
        N I S N:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_nisn" id="is_nisn" size="40" maxlength="100" class="inputbox" value="<?=$row['nisn']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        N I K:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_nik" id="is_nik" size="40" maxlength="100" class="inputbox" value="<?=$row['nik']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        No UN Sebelumnya:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_noun" id="is_noun" size="40" maxlength="100" class="inputbox" value="<?=$row['noun']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        N I S
    </td>
    <td width="*" align="left">
        <input type="text" name="is_nis" id="is_nis" size="40" maxlength="100" class="inputbox" value="<?=$row['nis']?>" style='background-color: #ccc' readonly >
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Nama:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_nama" id="is_nama" size="70" maxlength="255" class="inputbox" value="<?=$row['nama']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Panggilan:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_panggilan" id="is_panggilan" size="40" maxlength="100" class="inputbox" value="<?=$row['panggilan']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jenis Kelamin:
    </td>
    <td width="*" align="left">
        <input type="radio" name="is_kelamin" id="is_kelamin" value="l" class="inputbox" <?= StringIsChecked($row['kelamin'], "l") ?> />&nbsp;Laki-laki&nbsp;&nbsp;
        <input type="radio" name="is_kelamin" id="is_kelamin" value="p" class="inputbox" <?= StringIsChecked($row['kelamin'], "p") ?> />&nbsp;Perempuan
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Tempat Lahir:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_tmplahir" id="is_tmplahir" size="40" maxlength="100" class="inputbox" value="<?=$row['tmplahir']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Tanggal Lahir:
    </td>
    <td width="*" align="left">
<?php      $thn = date('Y');
        $bln = date('n');
        $tgl = date('j');
        
        $date = explode("-", (string) $row['tgllahir']);
        if (count($date) == 3)
        {
            $thn = $date[0];
            $bln = $date[1];
            $tgl = $date[2];
        } ?>        
<?php      ShowYearCombo('is_thnlahir', 'is_changeTahunLahirSiswa()', 1990, date('Y') + 1, $thn); ?>&nbsp;
<?php      ShowMonthCombo('is_blnlahir', 'is_changeBulanLahirSiswa()', $bln); ?>&nbsp;
        <span id="is_divTglLahirSiswa">
<?php      ShowDateCombo('is_tgllahir', 'is_changeTanggalLahirSiswa()', date('Y'), date('n'), $tgl); ?>
        </span>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Agama:
    </td>
    <td width="*" align="left">
<?php      ShowAgamaCombo($row['agama']); ?>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Suku:
    </td>
    <td width="*" align="left">
<?php      ShowSukuCombo($row['suku']); ?>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Status:
    </td>
    <td width="*" align="left">
<?php      ShowStatusCombo($row['status']); ?>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Kondisi:
    </td>
    <td width="*" align="left">
<?php      ShowKondisiCombo($row['kondisi']); ?>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Kewarganegaraan:
    </td>
    <td width="*" align="left">
        <input type="radio" name="is_warga" id="is_warga" value="WNI" <?= StringIsChecked($row['warga'], "WNI") ?> />&nbsp;WNI&nbsp;&nbsp;
        <input type="radio" name="is_warga" id="is_warga" value="WNA" <?= StringIsChecked($row['warga'], "WNA") ?>/>&nbsp;WNA
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Anak ke:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_urutananak" id="is_urutananak" size="3" maxlength="3" class="inputbox" value="<?=$row['anakke']?>">&nbsp;dari&nbsp;
        <input type="text" name="is_jumlahanak" id="is_jumlahanak" size="3" maxlength="3" class="inputbox" value="<?=$row['jsaudara']?>">&nbsp;bersaudara
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Status Anak:
    </td>
    <td width="*" align="left">
<?php      ShowStatusAnakCombo( $row['statusanak'] ); ?>        
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jumlah Saudara Kandung:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_jkandung" id="is_jkandung" size="3" maxlength="3" class="inputbox" value="<?=$row['jkandung']?>">&nbsp;orang
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jumlah Saudara Tiri:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_jtiri" id="is_jtiri" size="3" maxlength="3" class="inputbox" value="<?=$row['jtiri']?>">&nbsp;orang
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Bahasa:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_bahasa" id="is_bahasa" size="40" maxlength="100" class="inputbox" value="<?=$row['bahasa']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Alamat:
    </td>
    <td width="*" align="left">
        <textarea name="is_alamatsiswa" id="is_alamatsiswa" rows="2" cols="40" class="inputbox" ><?= $row['alamatsiswa'] ?></textarea>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Kode Pos:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_kodepos" id="is_kodepos" size="5" maxlength="8" class="inputbox" value="<?=$row['kodepossiswa']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jarak ke Sekolah:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_jarak" id="is_jarak" size="4" maxlength="4" class="inputbox" value="<?=$row['jarak']?>">&nbsp;km
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Telpon:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_telponsiswa" id="is_telponsiswa" size="20" maxlength="20" class="inputbox" value="<?=$row['telponsiswa']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Handphone:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_hpsiswa" id="is_hpsiswa" size="20" maxlength="20" class="inputbox" value="<?=$row['hpsiswa']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Email:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_emailsiswa" id="is_emailsiswa" size="40" maxlength="100" class="inputbox" value="<?=$row['emailsiswa']?>">
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
        <font style="color: #557d1d; font-size: 16px;">Data Sekolah Siswa</font>
        <br><br>
    </td>
</tr>    
<tr>
    <td width="15%" align="right" valign='top'>
        Asal Sekolah:
    </td>
    <td width="*" align="left">
<?php      ShowJenjangSekolahCombo($jenjangsekolah) ?><br>
        <span id='is_divAsalSekolah'>
<?php      ShowAsalSekolahCombo($jenjangsekolah, $asalsekolah) ?>
        </span>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        No Ijasah:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_noijasah" id="is_noijasah" size="40" maxlength="100" class="inputbox" value="<?=$row['noijasah']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Tgl Ijasah:
    </td>
    <td width="*" align="left">
        <input type="text" name="is_tglijasah" id="is_tglijasah" size="40" maxlength="100" class="inputbox" value="<?=$row['tglijasah']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Keterangan:
    </td>
    <td width="*" align="left">
        <textarea name="is_ketsekolah" id="is_ketsekolah" rows="2" cols="40" class="inputbox"><?=$row['ketsekolah']?></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
        <font style="color: #557d1d; font-size: 16px;">Riwayat Kesehatan Siswa</font>
        <br><br>
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Golongan Darah:
    </td>
    <td width="*" align="left">
        <input type="radio" name="is_gol" id="is_gol" value="A" <?= StringIsChecked($row['darah'], "A") ?>/>&nbsp;A&nbsp;&nbsp;
        <input type="radio" name="is_gol" id="is_gol" value="AB" <?= StringIsChecked($row['darah'], "AB") ?>/>&nbsp;AB&nbsp;&nbsp;
        <input type="radio" name="is_gol" id="is_gol" value="B" <?= StringIsChecked($row['darah'], "B") ?>/>&nbsp;B&nbsp;&nbsp;
        <input type="radio" name="is_gol" id="is_gol" value="O" <?= StringIsChecked($row['darah'], "O") ?>/>&nbsp;O&nbsp;&nbsp;
        <input type="radio" name="is_gol" id="is_gol" value=""  <?= StringIsChecked($row['darah'], "") ?>/>&nbsp;<em>(belum ada data)</em>
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Berat:</td>
    <td colspan="2">
        <input type="text" name="is_berat" id="is_berat" size="4" maxlength="4" class="inputbox" value="<?=$row['berat']?>">&nbsp;kg        	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Tinggi:</td>
    <td colspan="2">
        <input type="text" name="is_tinggi" id="is_tinggi" size="4" maxlength="4" class="inputbox" value="<?=$row['tinggi']?>">&nbsp;cm      	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Riwayat Penyakit:
    </td>
    <td width="*" align="left">
        <textarea name="is_kesehatan" id="is_kesehatan" rows="3" cols="40" class="inputbox"><?= $row['kesehatan'] ?></textarea>
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
        <font style="color: #557d1d; font-size: 16px;">Data Orangtua Siswa</font>
        <br><br>
    </td>
</tr>
<tr height='25'>
    <td width="18%" align="right" valign="top">
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
        <input type="text" name="is_namaayah" id="is_namaayah" size="40" maxlength="100" class="inputbox" value="<?=$row['namaayah']?>"><br>
        <input type="checkbox" name="is_almayah" id="is_almayah" value="1" title="Klik disini jika Ayah Almarhum" <?= IntIsChecked($row['almayah'], 1) ?>/>&nbsp;&nbsp;<font color="#990000" size="1">(Almarhum)</font>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="is_namaibu" id="is_namaibu" size="40" maxlength="100" class="inputbox" value="<?=$row['namaibu']?>"><br>
        <input type="checkbox" name="is_almibu" id="is_almibu" value="1" title="Klik disini jika Ayah Almarhumah" <?= IntIsChecked($row['almibu'], 1) ?>/>&nbsp;&nbsp;<font color="#990000" size="1">(Almarhumah)</font>
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
        <?php ShowStatusOrtuCombo('is_statusayah', $row['statusayah']) ?>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <?php ShowStatusOrtuCombo('is_statusibu', $row['statusibu']) ?>
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
        <input type="text" name="is_tmplahirayah" id="is_tmplahirayah" size="40" maxlength="100" class="inputbox" value="<?=$row['tmplahirayah']?>">
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="is_tmplahiribu" id="is_tmplahiribu" size="40" maxlength="100" class="inputbox" value="<?=$row['tmplahiribu']?>">
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
<?php      $thn = date('Y');
        $bln = date('n');
        $tgl = date('j');
        
        $date = explode("-", (string) $row['tgllahirayah']);
        if (count($date) == 3)
        {
            $thn = $date[0];
            $bln = $date[1];
            $tgl = $date[2];
        } ?>          
<?php      ShowYearCombo('is_thnlahirayah', 'is_changeTahunLahirAyah()', 1900, date('Y') + 1, $thn); ?>&nbsp;
<?php      ShowMonthCombo('is_blnlahirayah', 'is_changeBulanLahirAyah()', $bln); ?>&nbsp;
        <span id="is_divTglLahirAyah">
<?php      ShowDateCombo('is_tgllahirayah', 'is_changeTanggalLahirAyah()', date('Y'), date('n'), $tgl); ?>
        </span>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
<?php      $thn = date('Y');
        $bln = date('n');
        $tgl = date('j');
        
        $date = explode("-", (string) $row['tgllahiribu']);
        if (count($date) == 3)
        {
            $thn = $date[0];
            $bln = $date[1];
            $tgl = $date[2];
        } ?>          
<?php      ShowYearCombo('is_thnlahiribu', 'is_changeTahunLahirIbu()', 1900, date('Y') + 1, $thn); ?>&nbsp;
<?php      ShowMonthCombo('is_blnlahiribu', 'is_changeBulanLahirIbu()', $bln); ?>&nbsp;
        <span id="is_divTglLahirIbu">
<?php      ShowDateCombo('is_tgllahiribu', 'is_changeTanggalLahirIbu()', date('Y'), date('n'), $tgl); ?>
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
<?php      ShowPendidikanCombo('is_pendidikanayah', $row['pendidikanayah']) ?>        
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
<?php      ShowPendidikanCombo('is_pendidikanibu', $row['pendidikanibu']) ?>                
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
<?php      ShowPekerjaanCombo('is_pekerjaanayah', $row['pekerjaanayah']) ?>        
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
<?php      ShowPekerjaanCombo('is_pekerjaanibu', $row['pekerjaanibu']) ?>                
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
        <input type="text" name="is_penghasilanayah" id="is_penghasilanayah" size="40" maxlength="100" class="inputbox"
               onblur="formatRupiah('is_penghasilanayah')" onfocus="unformatRupiah('is_penghasilanayah')" value="<?= FormatRupiah($row['penghasilanayah']) ?>" >
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="is_penghasilanibu" id="is_penghasilanibu" size="40" maxlength="100" class="inputbox"
               onblur="formatRupiah('is_penghasilanibu')" onfocus="unformatRupiah('is_penghasilanibu')" value="<?= FormatRupiah($row['penghasilanibu']) ?>" >
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
        <input type="text" name="is_emailayah" id="is_emailayah" size="40" maxlength="100" class="inputbox" value="<?=$row['emailayah']?>">
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="is_emailibu" id="is_emailibu" size="40" maxlength="100" class="inputbox" value="<?=$row['emailibu']?>">
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
        <input type="text" name="is_namawali" id="is_namawali" size="40" maxlength="100" class="inputbox" value="<?=$row['wali']?>">
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
        <textarea name="is_alamatortu" id="is_alamatortu" rows="2" cols="30" class="inputbox"><?= $row['alamatortu'] ?></textarea>
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
        <input type="text" name="is_telponortu" id="is_telponortu" size="40" maxlength="100" class="inputbox" value="<?=$row['telponortu']?>">
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
        <input type="text" name="is_hportu" id="is_hportu" size="40" maxlength="100" class="inputbox" value="<?=$row['hportu']?>">
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
        <input type="text" name="is_hportu2" id="is_hportu2" size="40" maxlength="100" class="inputbox" value="<?=$row['info1']?>">
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
        <input type="text" name="is_hportu3" id="is_hportu3" size="40" maxlength="100" class="inputbox" value="<?=$row['info2']?>">
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
        <textarea name="is_hobi" id="is_hobi" rows="2" cols="40" class="inputbox"><?= $row['hobi'] ?></textarea>    	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Alamat Surat:</td>
    <td align="left" valign="top">
        <textarea name="is_alamatsurat" id="is_alamatsurat" rows="2" cols="40" class="inputbox"><?= $row['alamatsurat'] ?></textarea>    	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Keterangan:</td>
    <td align="left" valign="top">
        <textarea name="is_keterangan" id="is_keterangan" rows="2" cols="40" class="inputbox"><?= $row['keterangan'] ?></textarea>    	
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
        <font style="color: #557d1d; font-size: 16px;">Data Tambahan</font>
        <br><br>
    </td>
</tr>
<?php
    $sql = "SELECT replid, kolom, jenis
              FROM tambahandata 
             WHERE aktif = 1
               AND departemen = '$departemen'
             ORDER BY urutan";
    $res = QueryDb($sql);
    $idtambahan = "";
    while($row = mysqli_fetch_row($res))
    {
        $replid = $row[0];
        $kolom = $row[1];
        $jenis = $row[2];

        if ($idtambahan != "") $idtambahan .= ",";
        $idtambahan .= $replid;

        $replid_data = 0;
        $data = "";
        if ($jenis == 1)
        {
            $sql = "SELECT replid, teks FROM tambahandatasiswa WHERE nis = '$nis' AND idtambahan = '".$replid."'";
            $res2 = QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
            {
                $replid_data = $row2[0];
                $data = $row2[1];
            }
        }
        else if ($jenis == 2)
        {
            $sql = "SELECT replid, filename FROM tambahandatasiswa WHERE nis = '$nis' AND idtambahan = '".$replid."'";
            $res2 = QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
            {
                $replid_data = $row2[0];
                $filename = $row2[1];
                $data = "<a href='infosiswa/infosiswa.profile.file.php?replid=$replid_data'>$filename</a>";
            }
            else
            {
                $replid_data = 0;
                $data = "(belum ada)";
            }
        }
        else if ($jenis == 3)
        {
            $sql = "SELECT replid, teks FROM tambahandatasiswa WHERE nis = '$nis' AND idtambahan = '".$replid."'";
            $res2 = QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
            {
                $replid_data = $row2[0];
                $data = $row2[1];
            }

            $sql = "SELECT pilihan 
                      FROM jbsakad.pilihandata 
                     WHERE idtambahan = '$replid'
                       AND aktif = 1
                     ORDER BY urutan";
            $res2 = QueryDb($sql);

            $arrList = [];
            if (mysqli_num_rows($res2) == 0)
                $arrList[] = "-";

            while($row2 = mysqli_fetch_row($res2))
            {
                $arrList[] = $row2[0];
            }

            $opt = "";
            for($i = 0; $i < count($arrList); $i++)
            {
                $pilihan = CQ($arrList[$i]);
                $sel = $pilihan == $data ? "selected" : "";
                $opt .= "<option value='$pilihan' $sel>$pilihan</option>";
            }
        }

        ?>
        <tr style="height: 24px;">
            <td width="15%" align="right" valign="top"><?=$kolom?>:</td>
            <td colspan="2">
                <?php if ($jenis == 1) { ?>
                    <input type="hidden" id="is_jenisdata-<?=$replid?>" name="is_jenisdata-<?=$replid?>" value="1">
                    <input type="hidden" id="is_repliddata-<?=$replid?>" name="is_repliddata-<?=$replid?>" value="<?=$replid_data?>">
                    <input type="text" class="inputbox" name="is_tambahandata-<?=$replid?>" id="is_tambahandata-<?=$replid?>" size="40" maxlength="1000" value="<?=$data?>">
                <?php } else if ($jenis == 2) { ?>
                    <input type="hidden" id="is_jenisdata-<?=$replid?>" name="is_jenisdata-<?=$replid?>" value="2">
                    <input type="hidden" id="is_repliddata-<?=$replid?>" name="is_repliddata-<?=$replid?>" value="<?=$replid_data?>">
                    <input type="hidden" name="is_tambahandata-<?=$replid?>" id="is_tambahandata-<?=$replid?>" value="">
                    <i><?=$data?></i>
                <?php } else { ?>
                    <input type="hidden" id="is_jenisdata-<?=$replid?>" name="is_jenisdata-<?=$replid?>" value="3">
                    <input type="hidden" id="is_repliddata-<?=$replid?>" name="is_repliddata-<?=$replid?>" value="<?=$replid_data?>">
                    <select class="inputbox" name="is_tambahandata-<?=$replid?>" id="is_tambahandata-<?=$replid?>" style="width:215px">
                        <?= $opt ?>
                    </select>
                <?php } ?>
            </td>
        </tr>
        <?php
    }
    ?>
    <input type="hidden" id="is_idtambahan" name="is_idtambahan" value="<?=$idtambahan?>">
</table>
<br>

<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td width="15%" align="right" valign="top">&nbsp;</td>
    <td align="left" valign="top">
<?php 	if ($IsAllowStudentEdit) { ?>		
        <input type="button" value="Simpan" class="but" style="height: 30px; width: 140px;"  onclick="is_Simpan()">
<?php 	} else { ?>
		<font style='color: red; font-weight: bold;'>
			Tidak dapat mengubah data pribadi siswa. Silahkan hubungi Administrator JIBAS untuk mengatur perubahan data pribadi siswa.
		</font>
<?php 	} ?>
    </td>
</tr> 
</table>
</form>
</div>
<?php
CloseDb();
?>