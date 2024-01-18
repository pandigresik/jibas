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
require_once("../include/common.php");
require_once("../include/rupiah.php");
require_once("../include/db_functions.php");
require_once("../library/datearith.php");
require_once("psb.edit.func.php");

OpenDb();

/*
$nocalon = "PSMA1130018";
$namacalon = "Senthot Budhi Santoso";
$idkelompok = 3;
$page = 1;
$npage = 2;
*/

/*
$nocalon = $_REQUEST['nocalon'];
$namacalon = $_REQUEST['namacalon'];
$idkelompok = $_REQUEST['idkelompok'];
$page = $_REQUEST['page'];
$npage = $_REQUEST['npage'];
*/

$sql = "SELECT k.idproses, p.departemen
          FROM jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p
         WHERE k.idproses = p.replid
           AND k.replid = $idkelompok";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$selProses = $row[0];
$selDept = $row[1];
$selKelompok = $idkelompok;

$sql = "SELECT *
          FROM jbsakad.calonsiswa
         WHERE nopendaftaran = '".$nocalon."'";
$res = QueryDb($sql);
$row = mysqli_fetch_array($res);
$replid = $row['replid'];
$asalsekolah = $row['asalsekolah'];

$sql = "SELECT departemen
          FROM jbsakad.asalsekolah
         WHERE sekolah = '".$asalsekolah."'";
$res2 = QueryDb($sql);
$row2 = mysqli_fetch_array($res2);
$jenjangsekolah = $row2['departemen'];
?>
<form name="psb_form" id="psb_form" method="post">
<input type="hidden" id="psb_replid" name="psb_replid" value="<?= $replid ?>">
<input type="hidden" id="psb_nocalon" name="psb_nocalon" value="<?= $nocalon ?>">
<input type="hidden" id="psb_namacalon" name="psb_namacalon" value="<?= $namacalon ?>">
<input type="hidden" id="psb_idkelompok" name="psb_idkelompok" value="<?= $idkelompok ?>">
<input type="hidden" id="psb_page" name="psb_page" value="<?= $page ?>">
<input type="hidden" id="psb_npage" name="psb_npage" value="<?= $npage ?>">   
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td colspan="2" align="left">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
        <font style="color: #557d1d; font-size: 16px;">Data Penerimaan Siswa Baru</font>
        <br><br>
    </td>
</tr>    
<tr>
    <td width="15%" align="right">
        Departemen:
    </td>
    <td width="*" align="left">
<?php      ShowDepartemenCombo2() ?>        
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Penerimaan:
    </td>
    <td width="*" align="left">
        <div id="psb_divProses">
<?php      ShowPenerimaanCombo2($selDept) ?>
        </div>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Kelompok:
    </td>
    <td width="*" align="left">
        <div id="psb_divKelompok">
<?php      ShowKelompokCombo2($selProses) ?>
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
        <font style="color: #557d1d; font-size: 16px;">Data Pribadi Siswa</font>
        <br><br>
    </td>
</tr>    
<tr>
    <td width="15%" align="right">
        N I S N:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_nisn" id="psb_nisn" size="40" maxlength="100" class="inputbox" value="<?=$row['nisn']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        N I K:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_nik" id="psb_nik" size="40" maxlength="100" class="inputbox" value="<?=$row['nik']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        No UN Sebelumnya:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_noun" id="psb_noun" size="40" maxlength="100" class="inputbox" value="<?=$row['noun']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        No Pendaftaran
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_nopendaftaran" id="psb_nopendaftaran" size="40" maxlength="100" class="inputbox" value="<?=$row['nopendaftaran']?>" style='background-color: #ccc' readonly >
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Nama:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_nama" id="psb_nama" size="70" maxlength="255" class="inputbox" value="<?=$row['nama']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Panggilan:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_panggilan" id="psb_panggilan" size="40" maxlength="100" class="inputbox" value="<?=$row['panggilan']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jenis Kelamin:
    </td>
    <td width="*" align="left">
        <input type="radio" name="psb_kelamin" id="psb_kelamin" value="l" class="inputbox" <?= StringIsChecked($row['kelamin'], "l") ?> />&nbsp;Laki-laki&nbsp;&nbsp;
        <input type="radio" name="psb_kelamin" id="psb_kelamin" value="p" class="inputbox" <?= StringIsChecked($row['kelamin'], "p") ?> />&nbsp;Perempuan
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Tempat Lahir:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_tmplahir" id="psb_tmplahir" size="40" maxlength="100" class="inputbox" value="<?=$row['tmplahir']?>">
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
<?php      ShowYearCombo('psb_thnlahir', 'psb_changeTahunLahirSiswa()', 1980, date('Y') + 1, $thn); ?>&nbsp;
<?php      ShowMonthCombo('psb_blnlahir', 'psb_changeBulanLahirSiswa()', $bln); ?>&nbsp;
        <span id="psb_divTglLahirSiswa">
<?php      ShowDateCombo('psb_tgllahir', 'psb_changeTanggalLahirSiswa()', date('Y'), date('n'), $tgl); ?>
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
        <input type="radio" name="psb_warga" id="psb_warga" value="WNI" <?= StringIsChecked($row['warga'], "WNI") ?> />&nbsp;WNI&nbsp;&nbsp;
        <input type="radio" name="psb_warga" id="psb_warga" value="WNA" <?= StringIsChecked($row['warga'], "WNA") ?>/>&nbsp;WNA
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Anak ke:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_urutananak" id="psb_urutananak" size="3" maxlength="3" class="inputbox" value="<?=$row['anakke']?>">&nbsp;dari&nbsp;
        <input type="text" name="psb_jumlahanak" id="psb_jumlahanak" size="3" maxlength="3" class="inputbox" value="<?=$row['jsaudara']?>">&nbsp;bersaudara
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
        <input type="text" name="psb_jkandung" id="psb_jkandung" size="3" maxlength="3" class="inputbox" value="<?=$row['jkandung']?>">&nbsp;orang
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jumlah Saudara Tiri:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_jtiri" id="psb_jtiri" size="3" maxlength="3" class="inputbox" value="<?=$row['jtiri']?>">&nbsp;orang
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Bahasa:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_bahasa" id="psb_bahasa" size="40" maxlength="100" class="inputbox" value="<?=$row['bahasa']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Alamat:
    </td>
    <td width="*" align="left">
        <textarea name="psb_alamatsiswa" id="psb_alamatsiswa" rows="2" cols="40" class="inputbox" ><?= $row['alamatsiswa'] ?></textarea>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Kode Pos:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_kodepos" id="psb_kodepos" size="5" maxlength="8" class="inputbox" value="<?=$row['kodepossiswa']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Jarak ke Sekolah:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_jarak" id="psb_jarak" size="4" maxlength="4" class="inputbox" value="<?=$row['jarak']?>">&nbsp;km
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Telpon:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_telponsiswa" id="psb_telponsiswa" size="20" maxlength="20" class="inputbox" value="<?=$row['telponsiswa']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Handphone:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_hpsiswa" id="psb_hpsiswa" size="20" maxlength="20" class="inputbox" value="<?=$row['hpsiswa']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Email:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_emailsiswa" id="psb_emailsiswa" size="40" maxlength="100" class="inputbox" value="<?=$row['emailsiswa']?>">
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
        <span id='psb_divAsalSekolah'>
<?php      ShowAsalSekolahCombo($jenjangsekolah, $asalsekolah) ?>
        </span>
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        No Ijasah:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_noijasah" id="psb_noijasah" size="40" maxlength="100" class="inputbox" value="<?=$row['noijasah']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right">
        Tgl Ijasah:
    </td>
    <td width="*" align="left">
        <input type="text" name="psb_tglijasah" id="psb_tglijasah" size="40" maxlength="100" class="inputbox" value="<?=$row['tglijasah']?>">
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Keterangan:
    </td>
    <td width="*" align="left">
        <textarea name="psb_ketsekolah" id="psb_ketsekolah" rows="2" cols="40" class="inputbox"><?=$row['ketsekolah']?></textarea>
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
        <input type="radio" name="psb_gol" id="psb_gol" value="A" <?= StringIsChecked($row['darah'], "A") ?>/>&nbsp;A&nbsp;&nbsp;
        <input type="radio" name="psb_gol" id="psb_gol" value="AB" <?= StringIsChecked($row['darah'], "AB") ?>/>&nbsp;AB&nbsp;&nbsp;
        <input type="radio" name="psb_gol" id="psb_gol" value="B" <?= StringIsChecked($row['darah'], "B") ?>/>&nbsp;B&nbsp;&nbsp;
        <input type="radio" name="psb_gol" id="psb_gol" value="O" <?= StringIsChecked($row['darah'], "O") ?>/>&nbsp;O&nbsp;&nbsp;
        <input type="radio" name="psb_gol" id="psb_gol" value=""  <?= StringIsChecked($row['darah'], "") ?>/>&nbsp;<em>(belum ada data)</em>
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Berat:</td>
    <td colspan="2">
        <input type="text" name="psb_berat" id="psb_berat" size="4" maxlength="4" class="inputbox" value="<?=$row['berat']?>">&nbsp;kg        	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Tinggi:</td>
    <td colspan="2">
        <input type="text" name="psb_tinggi" id="psb_tinggi" size="4" maxlength="4" class="inputbox" value="<?=$row['tinggi']?>">&nbsp;cm      	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">
        Riwayat Penyakit:
    </td>
    <td width="*" align="left">
        <textarea name="psb_kesehatan" id="psb_kesehatan" rows="3" cols="40" class="inputbox"><?= $row['kesehatan'] ?></textarea>
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
        <input type="text" name="psb_namaayah" id="psb_namaayah" size="40" maxlength="100" class="inputbox" value="<?=$row['namaayah']?>"><br>
        <input type="checkbox" name="psb_almayah" id="psb_almayah" value="1" title="Klik disini jika Ayah Almarhum" <?= IntIsChecked($row['almayah'], 1) ?>/>&nbsp;&nbsp;<font color="#990000" size="1">(Almarhum)</font>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="psb_namaibu" id="psb_namaibu" size="40" maxlength="100" class="inputbox" value="<?=$row['namaibu']?>"><br>
        <input type="checkbox" name="psb_almibu" id="psb_almibu" value="1" title="Klik disini jika Ayah Almarhumah" <?= IntIsChecked($row['almibu'], 1) ?>/>&nbsp;&nbsp;<font color="#990000" size="1">(Almarhumah)</font>
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
        <?php ShowStatusOrtuCombo('psb_statusayah', $row['statusayah']) ?>
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <?php ShowStatusOrtuCombo('psb_statusibu', $row['statusibu']) ?>
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
        <input type="text" name="psb_tmplahirayah" id="psb_tmplahirayah" size="40" maxlength="100" class="inputbox" value="<?=$row['tmplahirayah']?>">
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="psb_tmplahiribu" id="psb_tmplahiribu" size="40" maxlength="100" class="inputbox" value="<?=$row['tmplahiribu']?>">
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
<?php      ShowYearCombo('psb_thnlahirayah', 'psb_changeTahunLahirAyah()', 1900, date('Y') + 1, $thn); ?>&nbsp;
<?php      ShowMonthCombo('psb_blnlahirayah', 'psb_changeBulanLahirAyah()', $bln); ?>&nbsp;
        <span id="psb_divTglLahirAyah">
<?php      ShowDateCombo('psb_tgllahirayah', 'psb_changeTanggalLahirAyah()', date('Y'), date('n'), $tgl); ?>
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
<?php      ShowYearCombo('psb_thnlahiribu', 'psb_changeTahunLahirIbu()', 1900, date('Y') + 1, $thn); ?>&nbsp;
<?php      ShowMonthCombo('psb_blnlahiribu', 'psb_changeBulanLahirIbu()', $bln); ?>&nbsp;
        <span id="psb_divTglLahirIbu">
<?php      ShowDateCombo('psb_tgllahiribu', 'psb_changeTanggalLahirIbu()', date('Y'), date('n'), $tgl); ?>
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
<?php      ShowPendidikanCombo('psb_pendidikanayah', $row['pendidikanayah']) ?>        
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
<?php      ShowPendidikanCombo('psb_pendidikanibu', $row['pendidikanibu']) ?>                
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
<?php      ShowPekerjaanCombo('psb_pekerjaanayah', $row['pekerjaanayah']) ?>        
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
<?php      ShowPekerjaanCombo('psb_pekerjaanibu', $row['pekerjaanibu']) ?>                
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
               onblur="formatRupiah('psb_penghasilanayah')" onfocus="unformatRupiah('psb_penghasilanayah')" value="<?= FormatRupiah($row['penghasilanayah']) ?>" >
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="psb_penghasilanibu" id="psb_penghasilanibu" size="40" maxlength="100" class="inputbox"
               onblur="formatRupiah('psb_penghasilanibu')" onfocus="unformatRupiah('psb_penghasilanibu')" value="<?= FormatRupiah($row['penghasilanibu']) ?>" >
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
        <input type="text" name="psb_emailayah" id="psb_emailayah" size="40" maxlength="100" class="inputbox" value="<?=$row['emailayah']?>">
    </td>
    <td width="*" align="left" bgcolor="#E9AFCF">
        <input type="text" name="psb_emailibu" id="psb_emailibu" size="40" maxlength="100" class="inputbox" value="<?=$row['emailibu']?>">
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
        <input type="text" name="psb_namawali" id="psb_namawali" size="40" maxlength="100" class="inputbox" value="<?=$row['wali']?>">
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
        <textarea name="psb_alamatortu" id="psb_alamatortu" rows="2" cols="30" class="inputbox"><?= $row['alamatortu'] ?></textarea>
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
        <input type="text" name="psb_telponortu" id="psb_telponortu" size="40" maxlength="100" class="inputbox" value="<?=$row['telponortu']?>">
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
        <input type="text" name="psb_hportu" id="psb_hportu" size="40" maxlength="100" class="inputbox" value="<?=$row['hportu']?>">
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
        <input type="text" name="psb_hportu2" id="psb_hportu2" size="40" maxlength="100" class="inputbox" value="<?=$row['info1']?>">
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
        <input type="text" name="psb_hportu3" id="psb_hportu3" size="40" maxlength="100" class="inputbox" value="<?=$row['info2']?>">
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
        <textarea name="psb_hobi" id="psb_hobi" rows="2" cols="40" class="inputbox"><?= $row['hobi'] ?></textarea>    	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Alamat Surat:</td>
    <td align="left" valign="top">
        <textarea name="psb_alamatsurat" id="psb_alamatsurat" rows="2" cols="40" class="inputbox"><?= $row['alamatsurat'] ?></textarea>    	
    </td>
</tr>
<tr>
    <td width="15%" align="right" valign="top">Keterangan:</td>
    <td align="left" valign="top">
        <textarea name="psb_keterangan" id="psb_keterangan" rows="2" cols="40" class="inputbox"><?= $row['keterangan'] ?></textarea>    	
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
               AND departemen = '$selDept'
             ORDER BY urutan";
    $res = QueryDb($sql);
    $idtambahan = "";
    while($row = mysqli_fetch_row($res))
    {
        $replid = $row[0];
        $kolom = $row[1];
        $jenis = $row[2];

        if ($jenis == 2) continue;

        if ($idtambahan != "") $idtambahan .= ",";
        $idtambahan .= $replid;

        $replid_data = 0;
        $data = "";
        if ($jenis == 1)
        {
            $sql = "SELECT replid, teks FROM tambahandatacalon WHERE nopendaftaran = '$nocalon' AND idtambahan = '".$replid."'";
            $res2 = QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
            {
                $replid_data = $row2[0];
                $data = $row2[1];
            }
        }
        else if ($jenis == 3)
        {
            $sql = "SELECT replid, teks FROM tambahandatacalon WHERE nopendaftaran = '$nocalon' AND idtambahan = '".$replid."'";
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
        <tr style="height: 24px">
            <td width="15%" align="right" valign="top"><?=$kolom?></td>
            <td colspan="2">
                <?php if ($jenis == 1) { ?>
                    <input type="hidden" id="psb_jenisdata-<?=$replid?>" name="psb_jenisdata-<?=$replid?>" value="1">
                    <input type="hidden" id="psb_repliddata-<?=$replid?>" name="psb_repliddata-<?=$replid?>" value="<?=$replid_data?>">
                    <input type="text" class="inputbox" name="psb_tambahandata-<?=$replid?>" id="psb_tambahandata-<?=$replid?>" size="40" maxlength="1000" value="<?=$data?>">
                <?php } else if ($jenis == 2) { ?>
                    <input type="hidden" id="psb_jenisdata-<?=$replid?>" name="psb_jenisdata-<?=$replid?>" value="2">
                    <input type="hidden" id="psb_repliddata-<?=$replid?>" name="psb_repliddata-<?=$replid?>" value="<?=$replid_data?>">
                    <input type="file" class="inputbox" name="psb_tambahandata-<?=$replid?>" id="psb_tambahandata-<?=$replid?>" size="40" style="width: 255px""/>
                    <i><?=$data?></i>
                <?php } else { ?>
                    <input type="hidden" id="psb_jenisdata-<?=$replid?>" name="psb_jenisdata-<?=$replid?>" value="3">
                    <input type="hidden" id="psb_repliddata-<?=$replid?>" name="psb_repliddata-<?=$replid?>" value="<?=$replid_data?>">
                    <select class="inputbox" name="psb_tambahandata-<?=$replid?>" id="psb_tambahandata-<?=$replid?>" style="width:215px">
                        <?= $opt ?>
                    </select>
                <?php } ?>

            </td>
        </tr>
        <?php
    }
    ?>
    <input type="hidden" id="psb_idtambahan" name="psb_idtambahan" value="<?=$idtambahan?>">
</table>
<br>

<div id="psb_divSumbangan">   
<?php
ShowSumbangan($selProses, $row)
?>
</div>
<br>
<div id="psb_divNilaiUjian">
<?php
ShowNilaiUjian($selProses, $row)
?>
</div>
<br>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td width="15%" align="right" valign="top">&nbsp;</td>
    <td align="left" valign="top">
        <input type="button" value="Simpan" class="but" style="height: 30px; width: 140px;"  onclick="psb_SimpanEdit()">
    </td>
</tr> 
</table>
</form>    
<?php
CloseDb();
?>
