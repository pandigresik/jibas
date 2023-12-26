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
require_once('../inc/sessionchecker.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/rupiah.php');

OpenDb();

$nis = $_SESSION["infosiswa.nis"];

$sql  =	"SELECT *, c.keterangan FROM siswa c, kelas k, tahunajaran t ".
		"WHERE c.nis='".$nis."' AND k.replid = c.idkelas AND k.idtahunajaran = t.replid ";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
?>
<form name="paneldp">

<input type="hidden" name="nis" id="nis" value="<?=$nis?>" />
<br>
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="left" >
<tr height="30">
    <td colspan="4" align="left" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#87c759">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Pribadi Siswa</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
    <td align="right"><a href="javascript:CetakProfile()"><img src="../img/print.png" border="0" />&nbsp;Cetak</a></td>
</tr>
<tr height="20">
    <td width="5%" rowspan="15" bgcolor="#FFFFFF" ></td>
    <td class="tab2">1.</td>
    <td class="tab2">NISN</td>
    <td class="tab2">:
        <?=$row['nisn']?></td>
    <td rowspan="15" bgcolor="#FFFFFF"><div align="center"><img src="../lib/gambar.php?nis=<?=$nis?>"  /> </div></td>
</tr>
<tr height="20">
    <td width="5%" class="tab2">2.</td>
    <td colspan="2" class="tab2">Nama Peserta Didik</td>
</tr>
<tr height="20">
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td width="20%" class="tab2">a. Lengkap</td>
    <td class="tab2">:
        <?=$row['nama']?></td>
</tr>
<tr height="20">
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td class="tab2">b. Panggilan</td>
    <td class="tab2">:
        <?=$row['panggilan']?></td>
</tr>
<tr height="20">
    <td class="tab2" >3.</td>
    <td class="tab2">Jenis Kelamin</td>
    <td class="tab2" >:
        <?php 	if ($row['kelamin']=="l")
            echo "Laki-laki";
        if ($row['kelamin']=="p")
            echo "Perempuan";
        ?></td>
</tr>
<tr height="20">
    <td class="tab2">4.</td>
    <td class="tab2">Tempat Lahir</td>
    <td class="tab2">:
        <?=$row['tmplahir']?></td>
</tr>
<tr height="20">
    <td class="tab2">5.</td>
    <td class="tab2">Tanggal Lahir</td>
    <td class="tab2">:
        <?=LongDateFormat($row['tgllahir']) ?></td>
</tr>
<tr height="20">
    <td class="tab2">6.</td>
    <td class="tab2" >Agama</td>
    <td class="tab2">:
        <?=$row['agama']?></td>
</tr>
<tr height="20">
    <td class="tab2">7.</td>
    <td class="tab2">Kewarganegaraan</td>
    <td class="tab2">:
        <?=$row['warga']?></td>
</tr>
<tr height="20">
    <td class="tab2">8.</td>
    <td class="tab2">Anak ke berapa</td>
    <td class="tab2">:
        <?php if ($row['anakke']!=0) { echo $row['anakke']; }?></td>
</tr>
<tr height="20">
    <td class="tab2">9.</td>
    <td class="tab2">Jumlah Saudara</td>
    <td class="tab2">:
        <?php if ($row['jsaudara']!=0) { echo $row['jsaudara']; }?></td>
</tr>
<tr height="20">
    <td class="tab2">10.</td>
    <td class="tab2">Kondisi Siswa</td>
    <td class="tab2">:
        <?=$row['kondisi']?></td>
</tr>
<tr height="20">
    <td class="tab2">11.</td>
    <td class="tab2">Status Siswa</td>
    <td class="tab2">:
        <?=$row['status']?></td>
</tr>
<tr height="20">
    <td class="tab2">12.</td>
    <td class="tab2">Bahasa Sehari-hari</td>
    <td class="tab2">:
        <?=$row['bahasa']?></td>
</tr>
<tr>
    <td bgcolor="#FFFFFF" colspan="4">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#87c759">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Tempat Tinggal</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="5" bgcolor="#FFFFFF"></td>
    <td class="tab2">13.</td>
    <td class="tab2">Alamat</td>
    <td colspan="2" class="tab2">:
        <?=$row['alamatsiswa']?></td>
</tr>
<tr height="20">
    <td class="tab2">14.</td>
    <td class="tab2">Telepon</td>
    <td colspan="2" class="tab2">:
        <?=$row['telponsiswa']?></td>
</tr>
<tr height="20">
    <td class="tab2">15.</td>
    <td class="tab2">Handphone</td>
    <td colspan="2" class="tab2">:
        <?=$row['hpsiswa']?></td>
</tr>
<tr height="20">
    <td class="tab2">16.</td>
    <td class="tab2">Email</td>
    <td colspan="2" class="tab2">:
        <?=$row['emailsiswa']?></td>
</tr>
<tr>
    <td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#87c759">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Kesehatan</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="5" bgcolor="#FFFFFF"></td>
    <td class="tab2">17.</td>
    <td class="tab2" >Berat Badan</td>
    <td colspan="2" class="tab2">:
        <?php if ($row['berat']!=0) { echo $row['berat']." Kg"; }?></td>
</tr>
<tr height="20">
    <td class="tab2">18.</td>
    <td class="tab2">Tinggi Badan</td>
    <td colspan="2" class="tab2">:
        <?php if ($row['tinggi']!=0) { echo $row['tinggi']." cm"; }?></td>
</tr>
<tr height="20">
    <td class="tab2">19.</td>
    <td class="tab2" >Golongan Darah</td>
    <td colspan="2" class="tab2">:
        <?=$row['darah']?></td>
</tr>
<tr height="20">
    <td class="tab2">20.</td>
    <td class="tab2" >Riwayat Penyakit</td>
    <td colspan="2" class="tab2">:
        <?=$row['kesehatan']?></td>
</tr>
<tr >
    <td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#87c759">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Pendidikan Sebelumnya</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="2" bgcolor="#FFFFFF"></td>
    <td class="tab2">21.</td>
    <td class="tab2" >Asal Sekolah</td>
    <td colspan="2" class="tab2">:
        <?=$row['asalsekolah']?></td>
</tr>
<tr height="20">
    <td class="tab2">22.</td>
    <td class="tab2" >Keterangan</td>
    <td colspan="2" class="tab2">:
        <?=$row['ketsekolah']?></td>
</tr>
<tr >
    <td colspan="5" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#87c759">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Orang Tua</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="10" bgcolor="#FFFFFF"></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td class="news_content1"><strong>Orang Tua</strong></td>
    <td width="30%" bgcolor="#FFFFCC" class="news_content1"><div align="center"><strong>Ayah</strong></div></td>
    <td bgcolor="#FFCCFF" class="news_content1"><div align="center"><strong>Ibu</strong></div></td>
</tr>
<tr height="20">
    <td class="tab2">23.</td>
    <td class="tab2" >Nama</td>
    <td bgcolor="#FFFFCC" class="tab2" >:
        <?=$row['namaayah']?>
        <?php
        if ($row['almayah']==1)
            echo "&nbsp;(alm)";
        ?></td>
    <td bgcolor="#FFCCFF" class="tab2"><?=$row['namaibu']?>
        <?php
        if ($row['almibu']==1)
            echo "&nbsp;(alm)";
        ?></td>
</tr>
<tr height="20">
    <td class="tab2">24.</td>
    <td class="tab2" >Pendidikan</td>
    <td bgcolor="#FFFFCC" class="tab2" >:
        <?=$row['pendidikanayah']?></td>
    <td bgcolor="#FFCCFF" class="tab2"><?=$row['pendidikanibu']?></td>
</tr>
<tr height="20">
    <td class="tab2">25.</td>
    <td class="tab2" >Pekerjaan</td>
    <td bgcolor="#FFFFCC" class="tab2" >:
        <?=$row['pekerjaanayah']?></td>
    <td bgcolor="#FFCCFF" class="tab2"><?=$row['pekerjaanibu']?></td>
</tr>
<tr height="20">
    <td class="tab2">26.</td>
    <td class="tab2" >Penghasilan</td>
    <td bgcolor="#FFFFCC" class="tab2" >:
        <?php if ($row['penghasilanayah']!=0){ echo FormatRupiah($row['penghasilanayah']) ; } ?></td>
    <td bgcolor="#FFCCFF" class="tab2"><?php if ($row['penghasilanibu']!=0){ echo FormatRupiah($row['penghasilanibu']) ; } ?></td>
</tr>
<tr height="20">
    <td class="tab2">27.</td>
    <td class="tab2" >Email</td>
    <td bgcolor="#FFFFCC" class="tab2" >: <?=$row['emailayah']?></td>
    <td bgcolor="#FFCCFF" class="tab2"><?=$row['emailibu']?></td>
</tr>
<tr height="20">
    <td class="tab2">28. </td>
    <td class="tab2" >Nama Wali</td>
    <td colspan="2" class="tab2">:
        <?=$row['wali']?></td>
</tr>
<tr >
    <td class="tab2">29.</td>
    <td class="tab2" >Alamat</td>
    <td colspan="2" class="tab2">:
        <?=$row['alamatortu']?></td>
</tr>
<tr height="20">
    <td class="tab2">30.</td>
    <td class="tab2" >Telepon</td>
    <td colspan="2" class="tab2">:
        <?=$row['telponortu']?></td>
</tr>
<tr height="20">
    <td class="tab2">31.</td>
    <td class="tab2" >Handphone</td>
    <td colspan="2" class="tab2">:
        <?=$row['hportu']?></td>
</tr>
<tr height="20">
    <td bgcolor="#FFFFFF"></td>
    <td bgcolor="#FFFFFF" >&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="6" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#87c759">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Lainnya</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="2" bgcolor="#FFFFFF"></td>
    <td class="tab2">32.</td>
    <td class="tab2">Alamat Surat</td>
    <td colspan="2" class="tab2">:
        <?=$row['alamatsurat']?></td>
</tr>
<tr height="20">
    <td class="tab2">33.</td>
    <td class="tab2" >Keterangan</td>
    <td colspan="2">:
        <?=$row['keterangan']?></td>
</tr>
<?php
    $nis = $row['nis'];
    $sql = "SELECT ds.replid, ds.idtambahan, td.kolom, ds.jenis, ds.teks, ds.filename 
              FROM tambahandatasiswa ds, tambahandata td
             WHERE ds.idtambahan = td.replid
               AND ds.nis = '$nis'
             ORDER BY td.urutan";
    $res = QueryDb($sql);
    $ntambahandata = mysqli_num_rows($res);

    if ($ntambahandata > 0)
    {
        ?>
        <tr height="20">
            <td bgcolor="#FFFFFF"></td>
            <td bgcolor="#FFFFFF" >&nbsp;</td>
        </tr>
        <tr height="30">
            <td colspan="6" bgcolor="#FFFFFF">
                <font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#87c759">&nbsp;</font>&nbsp;
                <font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Tambahan</strong></font>
                <hr width="300" style="line-height:1px; border-style:dashed" align="left" />
            </td>
        </tr>
<?php
        $no = 33;
        $first = true;
        while($row = mysqli_fetch_array($res))
        {
            $no += 1;
            $replid = $row['replid'];
            $kolom = $row['kolom'];
            $jenis = $row['jenis'];

            if ($jenis == 1 || $jenis == 3)
            {
                $data = $row['teks'];
            }
            else
            {
                $filename = $row['filename'];
                $data = "<a href='infosiswa.profile.file.php?replid=$replid'>$filename</a>";
            }

            $rowspan = "";
            if ($first)
            {
                $rowspan = "<td rowspan='$ntambahandata' bgcolor='#FFFFFF'></td>";
                $first = false;
            }
            ?>
            <tr height="20">
                <?=$rowspan?>
                <td><?=$no?>.</td>
                <td><?=$kolom?></td>
                <td colspan="2">:
                    <?=$data?></td>
            </tr>
        <?php  }
    }
    ?>
</table>

</form>
<?php
CloseDb();
?>