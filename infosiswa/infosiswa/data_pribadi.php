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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/rupiah.php');
require_once('../include/sessionchecker.php');

$nis=$_REQUEST['nis'];

OpenDb();

$sql="SELECT c.nis, c.nama, c.panggilan, c.tahunmasuk, c.idkelas, c.suku, c.agama, c.status, c.kondisi, c.kelamin, c.tmplahir, DAY(c.tgllahir) AS tanggal, MONTH(c.tgllahir) AS bulan, YEAR(c.tgllahir) AS tahun, c.tgllahir, c.warga, c.anakke, c.jsaudara, c.bahasa, c.berat, c.tinggi, c.darah, c.foto, c.alamatsiswa, c.kodepossiswa, c.telponsiswa, c.hpsiswa, c.emailsiswa, c.kesehatan, c.asalsekolah, c.ketsekolah, c.namaayah, c.namaibu, c.almayah, c.almibu, c.pendidikanayah, c.pendidikanibu, c.pekerjaanayah, c.pekerjaanibu, c.wali, c.penghasilanayah, c.penghasilanibu, c.alamatortu, c.telponortu, c.hportu, c.emailayah, c.emailibu, c.alamatsurat, c.keterangan,t.departemen, t.tahunajaran, k.kelas, c.replid as rep  FROM siswa c, kelas k, tahunajaran t WHERE c.nis='$nis' AND k.replid = c.idkelas AND k.idtahunajaran = t.replid";
$result=QueryDb($sql);
$row_siswa = mysqli_fetch_array($result); 
?>
<!--<body leftmargin="0" topmargin="0">-->
<table width="100%">
<tr>
	<td align="right">
      	<a href="#" onClick="cetak(0,0)"><img src="../images/ico/print.png" width="16" height="16" border="0" />&nbsp;Cetak</a>&nbsp;
    </td>
</tr>
</table>

<br />

<table border="0" cellpadding="0" cellspacing="0" width="100%"  id="table">
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Pribadi Siswa</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td width="5%" rowspan="14" bgcolor="#FFFFFF" ></td>
    <td width="5%">1.</td>
    <td colspan="2">Nama Peserta Didik</td>
    <td rowspan="14" bgcolor="#FFFFFF"><div align="center"><img src="gambar.php?nis=<?=$nis?>" /> </div></td>
</tr>
<tr height="20">
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td width="20%">a. Lengkap</td>
    <td>:
        <?=$row_siswa['nama']?></td>
</tr>
<tr height="20">
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td>b. Panggilan</td>
    <td>:
        <?=$row_siswa['panggilan']?></td>
</tr>
<tr height="20">
    <td >2.</td>
    <td>Jenis Kelamin</td>
    <td >:
        <?php 	if ($row_siswa['kelamin']=="l")
            echo "Laki-laki";
        if ($row_siswa['kelamin']=="p")
            echo "Perempuan";
        ?></td>
</tr>
<tr height="20">
    <td>3.</td>
    <td>Tempat Lahir</td>
    <td>:
        <?=$row_siswa['tmplahir']?></td>
</tr>
<tr height="20">
    <td>4.</td>
    <td>Tanggal Lahir</td>
    <td>:
        <?=format_tgl($row_siswa['tgllahir']) ?></td>
</tr>
<tr height="20">
    <td>5.</td>
    <td >Agama</td>
    <td>:
        <?=$row_siswa['agama']?></td>
</tr>
<tr height="20">
    <td>6.</td>
    <td>Kewarganegaraan</td>
    <td>:
        <?=$row_siswa['warga']?></td>
</tr>
<tr height="20">
    <td>7.</td>
    <td>Anak ke berapa</td>
    <td>:
        <?php if ($row_siswa['anakke']!=0) { echo $row_siswa['anakke']; }?></td>
</tr>
<tr height="20">
    <td>8.</td>
    <td>Jumlah Saudara</td>
    <td>:
        <?php if ($row_siswa['jsaudara']!=0) { echo $row_siswa['jsaudara']; }?></td>
</tr>
<tr height="20">
    <td>9.</td>
    <td>Kondisi Siswa</td>
    <td>:
        <?=$row_siswa['kondisi']?></td>
</tr>
<tr height="20">
    <td>10.</td>
    <td>Status Siswa</td>
    <td>:
        <?=$row_siswa['status']?></td>
</tr>
<tr height="20">
    <td>11.</td>
    <td>Bahasa Sehari-hari</td>
    <td>:
        <?=$row_siswa['bahasa']?></td>
</tr>
<tr>
    <td bgcolor="#FFFFFF" colspan="4">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Tempat Tinggal</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="5" bgcolor="#FFFFFF"></td>
    <td>12.</td>
    <td>Alamat</td>
    <td colspan="2">:
        <?=$row_siswa['alamatsiswa']?></td>
</tr>
<tr height="20">
    <td>13.</td>
    <td>Telepon</td>
    <td colspan="2">:
        <?=$row_siswa['telponsiswa']?></td>
</tr>
<tr height="20">
    <td>14.</td>
    <td>Handphone</td>
    <td colspan="2">:
        <?=$row_siswa['hpsiswa']?></td>
</tr>
<tr height="20">
    <td>15.</td>
    <td>Email</td>
    <td colspan="2">:
        <?=$row_siswa['emailsiswa']?></td>
</tr>
<tr>
    <td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Kesehatan</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="5" bgcolor="#FFFFFF"></td>
    <td>16.</td>
    <td >Berat Badan</td>
    <td colspan="2">:
        <?php if ($row_siswa['berat']!=0) { echo $row_siswa['berat']." Kg"; }?></td>
</tr>
<tr height="20">
    <td>17.</td>
    <td>Tinggi Badan</td>
    <td colspan="2">:
        <?php if ($row_siswa['tinggi']!=0) { echo $row_siswa['tinggi']." cm"; }?></td>
</tr>
<tr height="20">
    <td>18.</td>
    <td >Golongan Darah</td>
    <td colspan="2">:
        <?=$row_siswa['darah']?></td>
</tr>
<tr height="20">
    <td>19.</td>
    <td >Riwayat Penyakit</td>
    <td colspan="2">:
        <?=$row_siswa['kesehatan']?></td>
</tr>
<tr >
    <td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Pendidikan Sebelumnya</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="2" bgcolor="#FFFFFF"></td>
    <td>20.</td>
    <td >Asal Sekolah</td>
    <td colspan="2">:
        <?=$row_siswa['asalsekolah']?></td>
</tr>
<tr height="20">
    <td>21.</td>
    <td >Keterangan</td>
    <td colspan="2">:
        <?=$row_siswa['ketsekolah']?></td>
</tr>
<tr >
    <td colspan="5" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Orang Tua</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="11" bgcolor="#FFFFFF"></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td><strong>Orang Tua</strong></td>
    <td width="30%"><div align="center"><strong>Ayah</strong></div></td>
    <td><div align="center"><strong>Ibu</strong></div></td>
</tr>
<tr height="20">
    <td>22.</td>
    <td >Nama</td>
    <td >:
        <?=$row_siswa['namaayah']?>
        <?php
        if ($row_siswa['almayah']==1)
            echo "&nbsp;(alm)";
        ?></td>
    <td><?=$row_siswa['namaibu']?>
        <?php
        if ($row_siswa['almibu']==1)
            echo "&nbsp;(alm)";
        ?></td>
</tr>
<tr height="20">
    <td>23.</td>
    <td >Pendidikan</td>
    <td >:
        <?=$row_siswa['pendidikanayah']?></td>
    <td><?=$row_siswa['pendidikanibu']?></td>
</tr>
<tr height="20">
    <td>24.</td>
    <td >Pekerjaan</td>
    <td >:
        <?=$row_siswa['pekerjaanayah']?></td>
    <td><?=$row_siswa['pekerjaanibu']?></td>
</tr>
<tr height="20">
    <td>25.</td>
    <td >Penghasilan</td>
    <td >:
        <?php if ($row_siswa['penghasilanayah']!=0){ echo FormatRupiah($row_siswa['penghasilanayah']) ; } ?></td>
    <td><?php if ($row_siswa['penghasilanibu']!=0){ echo FormatRupiah($row_siswa['penghasilanibu']) ; } ?></td>
</tr>
<tr height="20">
    <td>26.</td>
    <td >Email</td>
    <td >:
        <?=$row_siswa['emailayah']?></td>
    <td><?=$row_siswa['emailibu']?></td>
</tr>
<tr height="20">
    <td>27. </td>
    <td >Nama Wali</td>
    <td colspan="2">:
        <?=$row_siswa['wali']?></td>
</tr>
<tr >
    <td>28.</td>
    <td >Alamat</td>
    <td colspan="2">:
        <?=$row_siswa['alamatortu']?></td>
</tr>
<tr height="20">
    <td>29.</td>
    <td >Telepon</td>
    <td colspan="2">:
        <?=$row_siswa['telponortu']?></td>
</tr>
<tr height="20">
    <td>30.</td>
    <td >Handphone</td>
    <td colspan="2">:
        <?=$row_siswa['hportu']?></td>
</tr>
<tr >
    <td colspan="5" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Lainnya</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="2" bgcolor="#FFFFFF"></td>
    <td>31.</td>
    <td>Alamat Surat</td>
    <td colspan="2">:
        <?=$row_siswa['alamatsurat']?></td>
</tr>
<tr height="20">
    <td>32.</td>
    <td >Keterangan</td>
    <td colspan="2">:
        <?=$row_siswa['keterangan']?></td>
</tr>
<?php
$nis = $row_siswa['nis'];
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
        <td bgcolor="#FFFFFF" >&nbsp;</td>
    </tr>
    <tr height="30">
        <td colspan="5" align="left" bgcolor="#FFFFFF">
            <font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
            <font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Tambahan</strong></font>
            <hr width="300" style="line-height:1px; border-style:dashed" align="left" />
        </td>
    </tr>
<?php
    $no = 32;
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
            $data = "<a href='data_pribadi.file.php?replid=$replid'>$filename</a>";
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
        <?php
    }
}
?>
</table>
<script language='JavaScript'>
    Tables('table', 0, 0);
</script>
<?php
CloseDb();
?>