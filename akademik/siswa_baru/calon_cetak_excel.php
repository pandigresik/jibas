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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Data_Calon_Siswa.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$departemen=$_REQUEST['departemen'];
$proses=$_REQUEST['proses'];
$kelompok=$_REQUEST['kelompok'];
$urut= $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

OpenDb();

$sql = "SELECT *,c.info1 AS hp2,c.info2 AS hp3
		  FROM calonsiswa c, kelompokcalonsiswa k, prosespenerimaansiswa p 
		 WHERE c.idproses = '$proses'
		   AND c.idkelompok = '$kelompok'
		   AND k.idproses = p.replid 
		   AND c.idproses = p.replid
		   AND c.idkelompok = k.replid
		 ORDER BY $urut $urutan";
		$result = QueryDb($sql);
		
if (@mysqli_num_rows($result)<>0){
?>
<html>
<head>
<title>
Data Siswa per Kelas
</title>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style2 {font-size: 14px}
-->
</style>
</head>
<body>
<table width="700" border="0">
<tr>
<td>
    <table width="100%" border="0">
    <tr>
        <td colspan="2"><div align="center">Data Siswa per Kelas</div></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <?php
        $sql_proses = "SELECT proses FROM prosespenerimaansiswa where replid='$proses'";
        $result_proses = QueryDb($sql_proses);
        $row_proses = @mysqli_fetch_array($result_proses);
        ?>
        <td width="24%">Departemen</td>
        <td width="76%"><strong>:</strong>&nbsp;<?=$departemen?></td>
    </tr>
    <tr>
        <td>Proses Penerimaan</td>
        <td><strong>:</strong>&nbsp;
            <?=$row_proses['proses'];
            ?>    </td>
    </tr>
    <tr>
        <td>Kelompok Calon Siswa</td>
        <td><strong>:</strong>&nbsp;<?php
            $sql_kel = "SELECT kelompok FROM kelompokcalonsiswa WHERE replid='$kelompok'";
            $result_kel = QueryDb($sql_kel);
            $row_kel=@mysqli_fetch_array($result_kel);
            echo $row_kel['kelompok'];
            ?></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </table>

</td>
</tr>

<tr>
<td>
    <table border="1">
    <tr height="30">
        <td width="3" rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">No.</div></td>
        <td width="20" rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">No. Pendaftaran</div></td>
        <td width="20" rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">NISN</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">NIK</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">No UN Sebelumnya</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Nama</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Panggilan</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Kelamin</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Tahun Masuk</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Asal Sekolah</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">No Ijasah</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Tgl Ijasah</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Tempat, Tanggal Lahir</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Alamat</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Kode Pos</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Jarak</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Telpon</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">HP</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Email</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Status</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Kondisi</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Kesehatan</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Bahasa</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Suku</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Agama</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Warga</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Berat</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Tinggi</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Gol.Darah</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Anak Ke</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Bersaudara</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Status Anak</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Jml Saudara Kandung</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Jml Saudara Tiri</div></td>
        <td colspan="8" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ayah</div></td>
        <td colspan="8" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ibu</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Alamat</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Telpon</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">HP #1</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">HP #2</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">HP #3</div></td>
<?php
        $arrDataTambahan = [];
        $sql = "SELECT replid, jenis, kolom
                  FROM tambahandata 
                 WHERE aktif = 1
                   AND departemen = '$departemen'
                 ORDER BY urutan";
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $arrDataTambahan[] = [$row[0], $row[1]];
            $kolom = $row[2];
            echo "<td rowspan=\"2\" valign=\"middle\" bgcolor=\"#666666\"><div align=\"center\" class=\"style1\"".$kolom."</div></td>";
        }
            ?>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Sumbangan 1</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Sumbangan 2</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ujian 1</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ujian 2</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ujian 3</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ujian 4</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ujian 5</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ujian 6</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ujian 7</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ujian 8</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ujian 9</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Ujian 10</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center" class="style1">Status Aktif</div></td>
    </tr>
    <tr height="30">
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Nama</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Status</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Kelahiran</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tgl Lahir</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Email</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Pendidikan</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Pekerjaan</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Penghasilan</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Nama</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Status</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Kelahiran</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tgl Lahir</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Email</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Pendidikan</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Pekerjaan</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Penghasilan</div></td>
    </tr>
<?php
    $cnt=1;
    while ($row=@mysqli_fetch_array($result)){
        $siswa = "";
        if ($row['replidsiswa'] <> 0) {
            $sql3 = "SELECT nis FROM jbsakad.siswa WHERE replid = ".$row['replidsiswa']."";
            $result3 = QueryDb($sql3);
            $row3 = @mysqli_fetch_array($result3);
            $siswa = "<br>NIS Siswa: <b>".$row3['nis']."</b>";
        }
            ?>
            <tr height="25">
                <td width="3" align="center"><?=$cnt?></td>
                <td align="left"><?=$row['nopendaftaran']?></td>
                <td align="left"><?=$row['nisn']?></td>
                <td align="left"><?=$row['nik']?></td>
                <td align="left"><?=$row['noun']?></td>
                <td align="left"><?=$row['nama']?></td>
                <td align="left"><?=$row['panggilan']?></td>
                <td align="left"><?=$row['kelamin']?></td>
                <td align="left"><?=$row['tahunmasuk']?></td>
                <td align="left"><?=$row['asalsekolah']?></td>
                <td align="left"><?=$row['noijasah']?></td>
                <td align="left"><?=$row['tglijasah']?></td>
                <td align="left"><?=$row['tmplahir']?>, <?=format_tgl($row['tgllahir'])?></td>
                <td align="left"><?=$row['alamatsiswa']?></td>
                <td align="left"><?=$row['kodepossiswa']?></td>
                <td align="left"><?=$row['jarak']?></td>
                <td align="left"><?=$row['telponsiswa']?></td>
                <td align="left"><?=$row['hpsiswa']?></td>
                <td align="left"><?=$row['emailsiswa']?></td>
                <td align="left"><?=$row['status']?></td>
                <td align="left"><?=$row['kondisi']?></td>
                <td align="left"><?=$row['kesehatan']?></td>
                <td align="left"><?=$row['bahasa']?></td>
                <td align="left"><?=$row['suku']?></td>
                <td align="left"><?=$row['agama']?></td>
                <td align="left"><?=$row['warga']?></td>
                <td align="left"><?=$row['berat']?></td>
                <td align="left"><?=$row['tinggi']?></td>
                <td align="left"><?=$row['darah']?></td>
                <td align="left"><?=$row['anakke']?></td>
                <td align="left"><?=$row['jsaudara']?></td>
                <td align="left"><?=$row['statusanak']?></td>
                <td align="left"><?=$row['jkandung']?></td>
                <td align="left"><?=$row['jtiri']?></td>
                <td align="left"><?=$row['namaayah']?></td>
                <td align="left"><?=$row['statusayah']?></td>
                <td align="left"><?=$row['tmplahirayah']?></td>
                <td align="left"><?=$row['tgllahirayah']?></td>
                <td align="left"><?=$row['emailayah']?></td>
                <td align="left"><?=$row['pendidikanayah']?></td>
                <td align="left"><?=$row['pekerjaanayah']?></td>
                <td align="left"><?=$row['penghasilanayah']?></td>
                <td align="left"><?=$row['namaibu']?></td>
                <td align="left"><?=$row['statusibu']?></td>
                <td align="left"><?=$row['tmplahiribu']?></td>
                <td align="left"><?=$row['tgllahiribu']?></td>
                <td align="left"><?=$row['emailibu']?></td>
                <td align="left"><?=$row['pendidikanibu']?></td>
                <td align="left"><?=$row['pekerjaanibu']?></td>
                <td align="left"><?=$row['penghasilanibu']?></td>
                <td align="left"><?=$row['alamatortu']?></td>
                <td align="left"><?=$row['telponortu']?></td>
                <td align="left"><?=$row['hportu']?></td>
                <td align="left"><?=$row['hp2']?></td>
                <td align="left"><?=$row['hp3']?></td>
                <?php
                $no = $row['nopendaftaran'];
                for($i = 0; $i < count($arrDataTambahan); $i++)
                {
                    $idtambahan = $arrDataTambahan[$i][0];
                    $jenis = $arrDataTambahan[$i][1];

                    if ($jenis == 1 || $jenis == 3)
                        $sql = "SELECT teks FROM tambahandatacalon WHERE nopendaftaran = '$no' AND idtambahan = '".$idtambahan."'";
                    else
                        $sql = "SELECT filename FROM tambahandatacalon WHERE nopendaftaran = '$no' AND idtambahan = '".$idtambahan."'";

                    $data = "";
                    $res2 = QueryDb($sql);
                    if ($row2 = mysqli_fetch_row($res2))
                        $data = $row2[0];

                    echo "<td align=\"left\"".$data."</td>";
                }
                ?>
                <td align="left"><?=$row['sum1']?></td>
                <td align="left"><?=$row['sum2']?></td>
                <td align="left"><?=$row['ujian1']?></td>
                <td align="left"><?=$row['ujian2']?></td>
                <td align="left"><?=$row['ujian3']?></td>
                <td align="left"><?=$row['ujian4']?></td>
                <td align="left"><?=$row['ujian5']?></td>
                <td align="left"><?=$row['ujian6']?></td>
                <td align="left"><?=$row['ujian7']?></td>
                <td align="left"><?=$row['ujian8']?></td>
                <td align="left"><?=$row['ujian9']?></td>
                <td align="left"><?=$row['ujian10']?></td>

                <td align="center"><?php

                    if ($row['aktif']==1)
                        echo "Aktif".$siswa;
                    if ($row['aktif']==0)
                        echo "Tidak aktif".$siswa;
                    ?></td>
            </tr>
            <?php
            $cnt++;
        } ?>
    </table>

</td>
</tr>
</table>


</body>
</html>
<?php
}
CloseDb();
?>