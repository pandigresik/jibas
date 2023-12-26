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

$sql="SELECT c.nis, c.nama, c.panggilan, c.tahunmasuk, c.idkelas, c.suku, c.agama, c.status, c.kondisi, c.kelamin, c.tmplahir, DAY(c.tgllahir) AS tanggal, MONTH(c.tgllahir) AS bulan, YEAR(c.tgllahir) AS tahun, c.tgllahir, c.warga, c.anakke, c.jsaudara, c.bahasa, c.berat, c.tinggi, c.darah, c.foto, c.alamatsiswa, c.kodepossiswa, c.telponsiswa, c.hpsiswa, c.emailsiswa, c.kesehatan, c.asalsekolah, c.ketsekolah, c.namaayah, c.namaibu, c.almayah, c.almibu, c.pendidikanayah, c.pendidikanibu, c.pekerjaanayah, c.pekerjaanibu, c.wali, c.penghasilanayah, c.penghasilanibu, c.alamatortu, c.telponortu, c.hportu, c.emailayah, c.emailibu, c.alamatsurat, c.keterangan,t.departemen, t.tahunajaran, k.kelas, i.tingkat FROM siswa c, kelas k, tahunajaran t, tingkat i WHERE c.replid=$replid AND k.replid = c.idkelas AND k.idtahunajaran = t.replid AND k.idtingkat = i.replid";
$result=QueryDB($sql);
$row_siswa = mysqli_fetch_array($result); 
CloseDb();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<title>JIBAS INFOGURU [Cetak Data Siswa]</title>
</head>
<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">

<?=getHeader($row_siswa['departemen'])?>

<center>
  <font size="4"><strong>DATA SISWA</strong></font><br />
 </center><br /><br />
 	<table width="100%">    
	<tr>
		<td width="26%"><strong>Departemen Terakhir/Aktif</strong> </td> 
		<td width="*"><strong>:&nbsp;<?=$row_siswa['departemen']?></strong></td>
	</tr>
    <tr>
		<td><strong>Tahun Ajaran Terakhir/Aktif</strong> </td> 
		<td width="*"><strong>:&nbsp;<?=$row_siswa['tahunajaran']?></strong></td>
	</tr>
	<tr>
		<td><strong>Kelas Terakhir/Aktif</strong></td>
		<td><strong>:&nbsp;<?=$row_siswa['tingkat']." - ".$row_siswa['kelas']?></strong></td>        		
    </tr>
    <tr>
		<td><strong>NIS Terakhir/Aktif</strong></td>
    	<td><strong>:&nbsp;<?=$row_siswa['nis']?></strong></td>
    </tr>
	</table>
    <br />
    <table width="100%">
    <tr>    	
    	<td align="center" width="150" valign="top">
        <img src="../library/gambar.php?replid=<?=$replid?>&table=siswa"  /> 
        <div align="center"><br /><br /><br />Tanda Tangan<br /><br /><br /><br /><br />
        <strong>(<?=$row_siswa['nama']?>)</strong></div>
        </td>
        <td>
    <table border="1" class="tab" id="table" width="100%" cellpadding="0" style="border-collapse:collapse" cellspacing="0" >
    <tr>
    	<td valign="top">
        <table border="0" cellpadding="0" style="border-collapse:collapse" cellspacing="0" width="100%">
          <tr class="header" height="30">
            <td align="center"><strong>A. </strong></td>
            <td colspan="4"><strong>KETERANGAN PRIBADI</strong></td>
          </tr>
          <tr height="20">
            <td rowspan="14"></td>
            <td width="5%">1.</td>
            <td colspan="2">Nama Peserta Didik</td>           
            <td rowspan="14">&nbsp;</td>
          </tr>
          <tr height="20">
            <td>&nbsp;</td>
            <td width="20%">a. Lengkap</td>
            <td>:
              <?=$row_siswa['nama']?></td>
          </tr>
          <tr height="20">
            <td>&nbsp;</td>
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
              <?=$row_siswa['anakke']?></td>
          </tr>
          <tr height="20">
            <td>8.</td>
            <td>Jumlah Saudara</td>
            <td>:
              <?=$row_siswa['jsaudara']?></td>
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
            <td>&nbsp;</td>
          </tr>
          <tr class="header" height="30">
            <td width="5%" align="center"><strong>B. </strong></td>
            <td colspan="5"><strong>KETERANGAN TEMPAT TINGGAL</strong></td>
          </tr>
          <tr height="20">
            <td rowspan="5"></td>
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
            <td>&nbsp;</td>
          </tr>
          <tr class="header" height="30">
            <td width="5%" align="center"><strong>C. </strong></td>
            <td colspan="5"><strong>KETERANGAN KESEHATAN</strong></td>
          </tr>
          <tr height="20">
            <td rowspan="5"></td>
            <td>16.</td>
            <td >Berat Badan</td>
            <td colspan="2">:
              <?=$row_siswa['berat']?></td>
          </tr>
          <tr height="20">
            <td>17.</td>
            <td>Tinggi Badan</td>
            <td colspan="2">:
              <?=$row_siswa['tinggi']?></td>
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
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr class="header" height="30">
            <td width="5%" align="center"><strong>D. </strong></td>
            <td colspan="5"><strong>KETERANGAN PENDIDIKAN SEBELUMNYA</strong></td>
          </tr>
          <tr height="20">
            <td rowspan="3"></td>
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
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr class="header" height="30">
            <td width="5%" align="center"><strong>E. </strong></td>
            <td colspan="5"><strong>KETERANGAN ORANG TUA</strong></td>
          </tr>
          <tr height="20">
            <td rowspan="12"></td>
            <td>&nbsp;</td>
            <td><strong>Orang Tua</strong></td>
            <td width="30%"><strong>Ayah</strong></td>
            <td><strong>Ibu</strong></td>
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
            <td colspan="2"><?=$row_siswa['namaibu']?>
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
            <td colspan="2"><?=$row_siswa['pendidikanibu']?></td>
          </tr>
          <tr height="20">
            <td>24.</td>
            <td >Pekerjaan</td>
            <td >:
              <?=$row_siswa['pekerjaanayah']?></td>
            <td colspan="2"><?=$row_siswa['pekerjaanibu']?></td>
          </tr>
          <tr height="20">
            <td>25.</td>
            <td >Penghasilan</td>
            <td >:
              <?=FormatRupiah($row_siswa['penghasilanayah']); ?></td>
            <td colspan="2"><?=FormatRupiah($row_siswa['penghasilanibu']); ?></td>
          </tr>
          <tr height="20">
            <td>26.</td>
            <td >Email Ortu</td>
            <td >:
              <?=$row_siswa['emailayah']; ?></td>
            <td colspan="2"><?=$row_siswa['emailibu']; ?></td>
          </tr>
          <tr height="20">
            <td>26. </td>
            <td >Nama Wali</td>
            <td colspan="2">:
              <?=$row_siswa['wali']?></td>
          </tr>
          <tr >
            <td>27.</td>
            <td >Alamat</td>
            <td colspan="2">:
              <?=$row_siswa['alamatortu']?></td>
          </tr>
          <tr height="20">
            <td>28.</td>
            <td >Telepon</td>
            <td colspan="2">:
              <?=$row_siswa['telponortu']?></td>
          </tr>
          <tr height="20">
            <td>29.</td>
            <td >Handphone</td>
            <td colspan="2">:
              <?=$row_siswa['hportu']?></td>
          </tr>
          <tr height="20">
            <td>30.</td>
            <td >Email</td>
            <td colspan="2">:
              <?=$row_siswa['emailortu']?></td>
          </tr>
          <tr height="20">
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td  colspan="2">&nbsp;</td>
          </tr>
          <tr height="30" class="header">
            <td align="center"><strong>F.</strong></td>
            <td colspan="5"><strong>KETERANGAN LAINNYA</strong></td>
          </tr>
          <tr height="20">
            <td rowspan="2"></td>
            <td>31.</td>
            <td>Alamat Surat</td>
            <td colspan="2">:
              <?=$row_siswa['alamatsurat']?></td>
          </tr>
          <tr height="20">
            <td>32.</td>
            <td >Keterangan</td>
            <td colspan="2">: <?=$row_siswa['keterangan']?></td>
          </tr>        
        </table></td>
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