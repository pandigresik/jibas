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
require_once('../include/getheader.php');
$replid=$_REQUEST['replid'];

OpenDb();

$sql="SELECT c.nopendaftaran, c.nama, c.panggilan, c.tahunmasuk, c.idproses, c.idkelompok, c.suku, c.agama,
		     c.status, c.kondisi, c.kelamin, c.tmplahir, DAY(c.tgllahir) AS tanggal, MONTH(c.tgllahir) AS bulan, YEAR(c.tgllahir) AS tahun,
			 c.tgllahir, c.warga, c.anakke, c.jsaudara, c.bahasa, c.berat, c.tinggi, c.darah, c.foto, c.alamatsiswa, c.kodepossiswa, c.telponsiswa,
			 c.hpsiswa, c.emailsiswa, c.kesehatan, c.asalsekolah, c.ketsekolah, c.namaayah, c.namaibu, c.almayah, c.almibu, c.pendidikanayah,
			 c.pendidikanibu, c.pekerjaanayah, c.pekerjaanibu, c.wali, c.penghasilanayah, c.penghasilanibu, c.alamatortu, c.telponortu, c.hportu,
			 c.emailayah, c.emailibu, c.alamatsurat, c.keterangan, p.proses, p.departemen, p.kodeawalan, k.kelompok, k.keterangan AS ket, c.nisn,
			 c.nik, c.noun, c.noijasah, c.tglijasah, c.statusanak, c.jkandung, c.jtiri, c.statusayah, c.statusibu, c.tmplahirayah,
			 c.tmplahiribu, c.tgllahirayah, c.tgllahiribu, c.hobi, c.jarak
		FROM calonsiswa c, kelompokcalonsiswa k, prosespenerimaansiswa p
	   WHERE c.replid='$replid'
	     AND p.replid = c.idproses
		 AND k.replid = c.idkelompok
		 AND p.replid = k.idproses";

$result=QueryDB($sql);
$row_siswa = mysqli_fetch_array($result); 


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<title>JIBAS SIMAKA [Cetak Data Calon Siswa]</title>
</head>
<body>
<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
<td align="left" valign="top" colspan="2">

    <?=getHeader($row_siswa['departemen'])?>

    <center>
        <font size="4"><strong>DATA CALON SISWA</strong></font><br />
    </center><br /><br />
    <table width="100%">
    <tr>
        <td width="20%"><strong>Departemen</strong> </td>
        <td width="*"><strong>:&nbsp;<?=$row_siswa['departemen']?></strong></td>
    </tr>
    <tr>
        <td width="20%"><strong>Proses Penerimaan</strong> </td>
        <td width="*"><strong>:&nbsp;<?=$row_siswa['proses']?></strong></td>
    </tr>
    <tr>
        <td><strong>Kelompok Calon Siswa</strong></td>
        <td><strong>:&nbsp;<?=$row_siswa['kelompok']?></strong></td>
    </tr>
    <tr>
        <td><strong>No. Pendaftaran</strong></td>
        <td><strong>:&nbsp;<?=$row_siswa['nopendaftaran']?></strong></td>
    </tr>
    </table>

    <br />

    <table width="100%">
    <tr>
        <td align="center" width="150" valign="top">
            <img src="../library/gambar.php?replid=<?=$replid?>&table=calonsiswa" />
            <div align="center"><br /><br /><br />Tanda Tangan<br /><br /><br /><br /><br />
                <strong>(<?=$row_siswa['nama']?>)</strong></div>
        </td>
        <td>

            <table border="0"  id="table" width="100%" cellpadding="0" style="border-collapse:collapse" cellspacing="0" >
            <tr>
            <td valign="top">

                <table border="1" cellpadding="0" style="border-collapse:collapse" cellspacing="0" width="100%">
                <tr  height="30">
                    <td align="center" bgcolor="#CCCCCC"><strong>A. </strong></td>
                    <td colspan="4" bgcolor="#CCCCCC"><strong>KETERANGAN PRIBADI</strong></td>
                </tr>

                <tr height="20">
                    <td rowspan="19"></td>
                    <td width="5%" >1.</td>
                    <td>NISN</td>
                    <td colspan="2">:
                        <?=$row_siswa['nisn']?>
                    </td>
                </tr>
                <tr height="20">
                    <td width="5%" >2.</td>
                    <td>NIK</td>
                    <td colspan="2">:
                        <?=$row_siswa['nik']?>
                    </td>
                </tr>
                <tr height="20">
                    <td width="5%" >3.</td>
                    <td>No. UN Sebelumnya</td>
                    <td colspan="2">:
                        <?=$row_siswa['noun']?>
                    </td>
                </tr>
                <tr height="20">
                    <td width="5%">4.</td>
                    <td colspan="3">Nama Peserta Didik</td>
                </tr>
                <tr height="20">
                    <td width="5%" rowspan="2" valign="top">&nbsp;</td>
                    <td width="20%">a. Lengkap</td>
                    <td colspan="2">:
                        <?=$row_siswa['nama']?></td>
                </tr>
                <tr height="20">
                    <td>b. Panggilan</td>
                    <td colspan="2">:
                        <?=$row_siswa['panggilan']?></td>
                </tr>
                <tr height="20">
                    <td >5.</td>
                    <td>Jenis Kelamin</td>
                    <td colspan="2" >:
                        <?php 	if ($row_siswa['kelamin']=="l")
                            echo "Laki-laki";
                        if ($row_siswa['kelamin']=="p")
                            echo "Perempuan";
                        ?></td>
                </tr>
                <tr height="20">
                    <td>6.</td>
                    <td>Tempat Lahir</td>
                    <td colspan="2">:
                        <?=$row_siswa['tmplahir']?></td>
                </tr>
                <tr height="20">
                    <td>7.</td>
                    <td>Tanggal Lahir</td>
                    <td colspan="2">:
                        <?=format_tgl($row_siswa['tgllahir']) ?></td>
                </tr>
                <tr height="20">
                    <td>8.</td>
                    <td >Agama</td>
                    <td colspan="2">:
                        <?=$row_siswa['agama']?></td>
                </tr>
                <tr height="20">
                    <td>9.</td>
                    <td>Kewarganegaraan</td>
                    <td colspan="2">:
                        <?=$row_siswa['warga']?></td>
                </tr>
                <tr height="20">
                    <td>10.</td>
                    <td>Anak ke</td>
                    <td colspan="2">:
                        <?=$row_siswa['anakke']?></td>
                </tr>
                <tr height="20">
                    <td>11.</td>
                    <td>Dari</td>
                    <td colspan="2">:
                        <?=$row_siswa['jsaudara']?> bersaudara</td>
                </tr>
                <tr height="20">
                    <td>12.</td>
                    <td>Status Anak</td>
                    <td colspan="2">:
                        <?=$row_siswa['statusanak']?></td>
                </tr>
                <tr height="20">
                    <td>13.</td>
                    <td>Jml Saudara Kandung</td>
                    <td colspan="2">:
                        <?=$row_siswa['jkandung']?> orang
                    </td>
                </tr>
                <tr height="20">
                    <td>14.</td>
                    <td>Jml Saudara Tiri</td>
                    <td colspan="2">:
                        <?=$row_siswa['jtiri']?> orang
                    </td>
                </tr>
                <tr height="20">
                    <td>15.</td>
                    <td>Kondisi Siswa</td>
                    <td colspan="2">:
                        <?=$row_siswa['kondisi']?>
                    </td>
                </tr>
                <tr height="20">
                    <td>16.</td>
                    <td>Status Siswa</td>
                    <td colspan="2">:
                        <?=$row_siswa['status']?>
                    </td>
                </tr>
                <tr height="20">
                    <td>17.</td>
                    <td>Bahasa Sehari-hari</td>
                    <td colspan="2">:
                        <?=$row_siswa['bahasa']?></td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr  height="30">
                    <td width="5%" align="center" bgcolor="#CCCCCC"><strong>B. </strong></td>
                    <td colspan="5" bgcolor="#CCCCCC"><strong>KETERANGAN TEMPAT TINGGAL</strong></td>
                </tr>
                <tr height="20">
                    <td rowspan="7"></td>
                    <td>18.</td>
                    <td>Alamat</td>
                    <td colspan="2">:
                        <?=$row_siswa['alamatsiswa']?>
                    </td>
                </tr>
                <tr height="20">
                    <td>19.</td>
                    <td>Kode Pos</td>
                    <td colspan="2">:
                        <?=$row_siswa['kodepossiswa']?>
                    </td>
                </tr>
                <tr height="20">
                    <td>20.</td>
                    <td>Jarak ke Sekolah</td>
                    <td colspan="2">:
                        <?=$row_siswa['jarak']?>&nbsp;km
                    </td>
                </tr>
                <tr height="20">
                    <td>21.</td>
                    <td>Telepon</td>
                    <td colspan="2">:
                        <?=$row_siswa['telponsiswa']?></td>
                </tr>
                <tr height="20">
                    <td>22.</td>
                    <td>Handphone</td>
                    <td colspan="2">:
                        <?=$row_siswa['hpsiswa']?></td>
                </tr>
                <tr height="20">
                    <td>23.</td>
                    <td>Email</td>
                    <td colspan="2">:
                        <?=$row_siswa['emailsiswa']?></td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr  height="30">
                    <td width="5%" align="center" bgcolor="#CCCCCC"><strong>C. </strong></td>
                    <td colspan="5" bgcolor="#CCCCCC"><strong>KETERANGAN KESEHATAN</strong></td>
                </tr>
                <tr height="20">
                    <td rowspan="5"></td>
                    <td>24.</td>
                    <td >Berat Badan</td>
                    <td colspan="2">:
                        <?=$row_siswa['berat']?></td>
                </tr>
                <tr height="20">
                    <td>25.</td>
                    <td>Tinggi Badan</td>
                    <td colspan="2">:
                        <?=$row_siswa['tinggi']?></td>
                </tr>
                <tr height="20">
                    <td>26.</td>
                    <td >Golongan Darah</td>
                    <td colspan="2">:
                        <?=$row_siswa['darah']?></td>
                </tr>
                <tr height="20">
                    <td>27.</td>
                    <td >Riwayat Penyakit</td>
                    <td colspan="2">:
                        <?=$row_siswa['kesehatan']?></td>
                </tr>
                <tr >
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr  height="30">
                    <td width="5%" align="center" bgcolor="#CCCCCC"><strong>D. </strong></td>
                    <td colspan="5" bgcolor="#CCCCCC"><strong>KETERANGAN PENDIDIKAN SEBELUMNYA</strong></td>
                </tr>
                <tr height="20">
                    <td rowspan="5"></td>
                    <td>28.</td>
                    <td >Asal Sekolah</td>
                    <td colspan="2">:
                        <?=$row_siswa['asalsekolah']?>
                    </td>
                </tr>
                <tr height="20">
                    <td>29.</td>
                    <td>No Ijasah</td>
                    <td colspan="2">:
                        <?=$row_siswa['noijasah']?>
                    </td>
                </tr>
                <tr height="20">
                    <td>30.</td>
                    <td>Tgl Ijasah</td>
                    <td colspan="2">:
                        <?=$row_siswa['tglijasah']?>
                    </td>
                </tr>
                <tr height="20">
                    <td>31.</td>
                    <td >Keterangan</td>
                    <td colspan="2">:
                        <?=$row_siswa['ketsekolah']?>
                    </td>
                </tr>
                <tr >
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr  height="30">
                    <td width="5%" align="center" bgcolor="#CCCCCC"><strong>E. </strong></td>
                    <td colspan="5" align="left" bgcolor="#CCCCCC"><strong>KETERANGAN ORANG TUA</strong></td>
                </tr>
                <tr height="20">
                    <td rowspan="14"></td>
                    <td colspan="2" align="center"><strong>Orang Tua</strong></td>
                    <td width="30%" align="center"><strong>Ayah</strong></td>
                    <td align="center"><strong>Ibu</strong></td>
                </tr>
                <tr height="20">
                    <td>32.</td>
                    <td >Nama</td>
                    <td >:
                        <?=$row_siswa['namaayah']?>
                        <?php
                        if ($row_siswa['almayah']==1)
                            echo "&nbsp;(alm)";
                        ?></td>
                    <td colspan="2"><?=$row_siswa['namaibu']?>
                        <?php
                        if ($row_siswa['almibu']==1)
                            echo "&nbsp;(alm)";
                        ?></td>
                </tr>
                <tr height="20">
                    <td>33.</td>
                    <td>Status Orangtua</td>
                    <td>:
                        <?=$row_siswa['statusayah']?></td>
                    <td colspan="2"><?=$row_siswa['statusibu']?></td>
                </tr>
                <tr height="20">
                    <td>34.</td>
                    <td>Tempat Lahir</td>
                    <td>:
                        <?=$row_siswa['tmplahirayah']?></td>
                    <td colspan="2"><?=$row_siswa['tmplahiribu']?></td>
                </tr>
                <tr height="20">
                    <td>35.</td>
                    <td>Tanggal Lahir</td>
                    <td>:
                        <?=$row_siswa['tgllahirayah']?></td>
                    <td colspan="2"><?=$row_siswa['tgllahiribu']?></td>
                </tr>
                <tr height="20">
                    <td>36.</td>
                    <td >Pendidikan</td>
                    <td >:
                        <?=$row_siswa['pendidikanayah']?></td>
                    <td colspan="2"><?=$row_siswa['pendidikanibu']?></td>
                </tr>
                <tr height="20">
                    <td>37.</td>
                    <td >Pekerjaan</td>
                    <td >:
                        <?=$row_siswa['pekerjaanayah']?></td>
                    <td colspan="2"><?=$row_siswa['pekerjaanibu']?></td>
                </tr>
                <tr height="20">
                    <td>38.</td>
                    <td >Penghasilan</td>
                    <td >:
                        <?=FormatRupiah($row_siswa['penghasilanayah']); ?></td>
                    <td colspan="2"><?=FormatRupiah($row_siswa['penghasilanibu']); ?></td>
                </tr>
                <tr height="20">
                    <td>39.</td>
                    <td >Email Orang Tua</td>
                    <td >:
                        <?=$row_siswa['emailayah']?></td>
                    <td colspan="2"><?=$row_siswa['emailibu']?></td>
                </tr>
                <tr height="20">
                    <td>40. </td>
                    <td >Nama Wali</td>
                    <td colspan="2">:
                        <?=$row_siswa['wali']?></td>
                </tr>
                <tr >
                    <td>41.</td>
                    <td height="20" >Alamat</td>
                    <td colspan="2">:
                        <?=$row_siswa['alamatortu']?></td>
                </tr>
                <tr height="20">
                    <td>42.</td>
                    <td >Telepon</td>
                    <td colspan="2">:
                        <?=$row_siswa['telponortu']?></td>
                </tr>
                <tr height="20">
                    <td>43.</td>
                    <td >Handphone</td>
                    <td colspan="2">:
                        <?=$row_siswa['hportu']?></td>
                </tr>
                <tr height="20">
                    <td colspan="5" >&nbsp;</td>
                </tr>
                <tr height="30" >
                    <td bgcolor="#CCCCCC"><strong>F.</strong></td>
                    <td colspan="6" align="left" bgcolor="#CCCCCC"><strong>KETERANGAN LAINNYA</strong></td>
                </tr>
                <tr height="20">
                    <td rowspan="3"></td>
                    <td>44.</td>
                    <td>Hobi</td>
                    <td colspan="2">:
                        <?=$row_siswa['hobi']?></td>
                </tr>
                <tr height="20">
                    <td>45.</td>
                    <td>Alamat Surat</td>
                    <td colspan="2">:
                        <?=$row_siswa['alamatsurat']?></td>
                </tr>
                <tr height="20">
                    <td>46.</td>
                    <td >Keterangan</td>
                    <td colspan="2">: <?=$row_siswa['keterangan']?></td>
                </tr>
<?php
                $no = $row_siswa['nopendaftaran'];
                $sql = "SELECT ds.replid, ds.idtambahan, td.kolom, ds.jenis, ds.teks, ds.filename 
                          FROM tambahandatacalon ds, tambahandata td
                         WHERE ds.idtambahan = td.replid
                           AND ds.nopendaftaran = '$no'
                         ORDER BY td.urutan  ";
                $res = QueryDb($sql);
                $ntambahandata = mysqli_num_rows($res);

                if ($ntambahandata > 0)
                {   ?>

                    <tr height="20">
                        <td colspan="5" >&nbsp;</td>
                    </tr>
                    <tr height="30" >
                        <td bgcolor="#CCCCCC"><strong>G.</strong></td>
                        <td colspan="6" align="left" bgcolor="#CCCCCC"><strong>TAMBAHAN DATA</strong></td>
                    </tr>
<?php
                    $no = 46;
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
                            $data = $filename;
                        }

                        $rowspan = "";
                        if ($first)
                        {
                            $nrowspan = $ntambahandata + 1;
                            $rowspan = "<td rowspan='$nrowspan' bgcolor='#FFFFFF'></td>";
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
<?php                  }
                    echo "<tr height='20'><td colspan='5'>&nbsp;</td></tr>";
                } ?>
                </table>

            </td>
            </tr>
            </table>

        </td>
    </tr>
    </table>

</td>
</tr>
</table>
<script language="javascript">
    window.print();
</script>
</body>
</html>
<?php
CloseDb();
?>