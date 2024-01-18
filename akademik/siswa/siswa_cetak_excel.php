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
header('Content-Disposition: attachment; filename=Data_Siswa.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$kelas=$_REQUEST['kelas'];
$tahunajaran=$_REQUEST['tahunajaran'];
$tingkat=$_REQUEST['tingkat'];
$urut= $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

OpenDb();
$sql = "SELECT *, s.info1 AS hp2, s.info2 AS hp3, s.keterangan AS ketsiswa
	      FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t
		 WHERE s.idkelas = '$kelas'
		   AND k.idtahunajaran = '$tahunajaran'
		   AND k.idtingkat = '$tingkat'
		   AND s.idkelas = k.replid
		   AND t.replid = k.idtahunajaran
		   AND s.alumni = 0
		 ORDER BY $urut $urutan";
$result=QueryDb($sql);

if (@mysqli_num_rows($result)<>0)
{

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
            $sql_TA="SELECT * FROM jbsakad.tahunajaran WHERE replid='$tahunajaran'";
            $result_TA=QueryDb($sql_TA);
            $row_TA=@mysqli_fetch_array($result_TA);
            $departemen = $row_TA['departemen'];
            ?>
            <td width="9%">Departemen</td>
            <td width="91%"><strong>:</strong>&nbsp;<?=$row_TA['departemen']?></td>
        </tr>
        <tr>
            <td>Tahunajaran</td>
            <td><strong>:</strong>&nbsp;
                <?=$row_TA['tahunajaran'];
                ?>    </td>
        </tr>
        <tr>
            <td>Tingkat</td>
            <td><strong>:</strong>&nbsp;<?php
                $sql_Tkt="SELECT * FROM jbsakad.tingkat WHERE replid='$tingkat'";
                $result_Tkt=QueryDb($sql_Tkt);
                $row_Tkt=@mysqli_fetch_array($result_Tkt);
                echo $row_Tkt['tingkat'];
                ?></td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td><strong>:</strong>&nbsp;<?php
                $sql_Kls="SELECT * FROM jbsakad.kelas WHERE replid='$kelas'";
                $result_Kls=QueryDb($sql_Kls);
                $row_Kls=@mysqli_fetch_array($result_Kls);
                echo $row_Kls['kelas'];
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
            <td width="3" valign="middle" bgcolor="#666666"><div align="center" class="style1">No.</div></td>
            <td width="20" valign="middle" bgcolor="#666666"><div align="center" class="style1">NIS</div></td>
            <td width="20" valign="middle" bgcolor="#666666"><div align="center" class="style1">NISN</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">NIK</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">No UN Sebelumnya</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">PIN</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Nama</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Panggilan</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Kelamin</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tahun Masuk</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Asal Sekolah</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">No Ijasah</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tgl Ijasah</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tempat, Tanggal Lahir</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Alamat</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Kode Pos</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Jarak</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Telpon</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">HP</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Email</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Status</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Kondisi</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Kesehatan</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Bahasa</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Suku</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Agama</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Warga</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Berat</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tinggi</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Gol.Darah</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Anak Ke</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Bersaudara</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Status Anak</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Jml Saudara Kandung</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Jml Saudara Tiri</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Ayah</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Status Ayah</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tmp Lahir Ayah</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tgl Lahir Ayah</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Email</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">PIN Ayah</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Pendidikan</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Pekerjaan</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Penghasilan</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Ibu</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Status Ibu</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tmp Lahir Ibu</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tgl Lahir Ibu</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Email</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">PIN Ibu</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Pendidikan</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Pekerjaan</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Penghasilan</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Nama Wali</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Alamat</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Telpon</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">HP #1</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">HP #2</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">HP #3</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Hobi</div></td>
            <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Keterangan</div></td>
            <?php
            $arrDataTambahan = [];
            $sql = "SELECT replid, jenis, kolom
                      FROM tambahandata 
                     WHERE aktif = 1
                       AND departemen = '$departemen'
                     ORDER BY urutan  ";
            $res = QueryDb($sql);
            while($row = mysqli_fetch_row($res))
            {
                $arrDataTambahan[] = [$row[0], $row[1]];
                $kolom = $row[2];
                echo "<td valign=\"middle\" bgcolor=\"#666666\"><div align=\"center\" class=\"style1\"".$kolom."</div></td>";
            }
            ?>
        </tr>
<?php
            $cnt=1;
            while ($row=@mysqli_fetch_array($result))
            {
                $nis = $row['nis'];
                ?>
                <tr height="25">
                    <td width="3" align="center"><?=$cnt?></td>
                    <td align="left"><?= "'" . $row['nis'] ?></td>
                    <td align="left"><?= "'". $row['nisn'] ?></td>
                    <td align="left"><?= "'". $row['nik'] ?></td>
                    <td align="left"><?= "'". $row['noun'] ?></td>
                    <td align="left"><?= "'". $row['pinsiswa'] ?></td>
                    <td align="left"><?=$row['nama']?></td>
                    <td align="left"><?=$row['panggilan']?></td>
                    <td align="left"><?=$row['kelamin']?></td>
                    <td align="left"><?=$row['tahunmasuk']?></td>
                    <td align="left"><?=$row['asalsekolah']?></td>
                    <td align="left"><?= "'". $row['noijasah'] ?></td>
                    <td align="left"><?=$row['tglijasah']?></td>
                    <td align="left"><?=$row['tmplahir']?>, <?=format_tgl($row['tgllahir'])?></td>
                    <td align="left"><?=$row['alamatsiswa']?></td>
                    <td align="left"><?=$row['kodepossiswa']?></td>
                    <td align="left"><?=$row['jarak']?></td>
                    <td align="left"><?= "'". $row['telponsiswa'] ?></td>
                    <td align="left"><?= "'". $row['hpsiswa'] ?></td>
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
                    <td align="left"><?= "'". $row['pinortu']?></td>
                    <td align="left"><?=$row['pendidikanayah']?></td>
                    <td align="left"><?=$row['pekerjaanayah']?></td>
                    <td align="left"><?=$row['penghasilanayah']?></td>
                    <td align="left"><?=$row['namaibu']?></td>
                    <td align="left"><?=$row['statusibu']?></td>
                    <td align="left"><?=$row['tmplahiribu']?></td>
                    <td align="left"><?=$row['tgllahiribu']?></td>
                    <td align="left"><?=$row['emailibu']?></td>
                    <td align="left"><?= "'". $row['pinortuibu']?></td>
                    <td align="left"><?= "'". $row['pendidikanibu']?></td>
                    <td align="left"><?=$row['pekerjaanibu']?></td>
                    <td align="left"><?=$row['penghasilanibu']?></td>
                    <td align="left"><?=$row['wali']?></td>
                    <td align="left"><?=$row['alamatortu']?></td>
                    <td align="left"><?= "'". $row['telponortu']?></td>
                    <td align="left"><?= "'". $row['hportu']?></td>
                    <td align="left"><?= "'". $row['hp2']?></td>
                    <td align="left"><?= "'". $row['hp3']?></td>
                    <td align="left"><?=$row['hobi']?></td>
                    <td align="left"><?=$row['ketsiswa']?></td>

<?php               for($i = 0; $i < count($arrDataTambahan); $i++)
                    {
                        $idtambahan = $arrDataTambahan[$i][0];
                        $jenis = $arrDataTambahan[$i][1];

                        if ($jenis == 1 || $jenis == 3)
                            $sql = "SELECT teks FROM tambahandatasiswa WHERE nis = '$nis' AND idtambahan = '".$idtambahan."'";
                        else
                            $sql = "SELECT filename FROM tambahandatasiswa WHERE nis = '$nis' AND idtambahan = '".$idtambahan."'";

                        $data = "";
                        $res2 = QueryDb($sql);
                        if ($row2 = mysqli_fetch_row($res2))
                            $data = $row2[0];

                        echo "<td align=\"left\"".$data."</td>";
                    }
                    ?>
                </tr>
                <?php
                $cnt++;
            }
            ?>
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