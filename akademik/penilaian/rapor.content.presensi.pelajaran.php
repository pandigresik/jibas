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
<fieldset>
<legend><strong>Presensi Pelajaran</strong></legend>
<table width="100%" border="1" class="tab" id="table" bordercolor="#000000" style="border-collapse: collapse; border-width: 1px;">
<tr>
    <td width="27%" rowspan="2" class="headerlong"><div align="center">Pelajaran</div></td>
    <td height="25" colspan="2" class="headerlong"><div align="center">Hadir</div></td>
    <td height="25" colspan="2" class="headerlong"><div align="center">Sakit</div></td>
    <td height="25" colspan="2" class="headerlong"><div align="center">Ijin</div></td>
    <td height="25" colspan="2" class="headerlong"><div align="center">Alpa</div></td>
</tr>
<tr>
    <td width="6" class="headerlong"><div align="center">Jumlah</div></td>
    <td width="6" class="headerlong"><div align="center">%</div></td>
    <td width="6" class="headerlong"><div align="center">Jumlah</div></td>
    <td width="6" class="headerlong"><div align="center">%</div></td>
    <td width="6" class="headerlong"><div align="center">Jumlah</div></td>
    <td width="6" class="headerlong"><div align="center">%</div></td>
    <td width="6" class="headerlong"><div align="center">Jumlah</div></td>
    <td width="6" class="headerlong"><div align="center">%</div></td>
</tr>
<!-- Ambil pelajaran per departemen-->
<?php
$sql_get_pelajaran_presensi =
    "SELECT pel.replid as replid,pel.nama as nama 
       FROM presensipelajaran ppel, ppsiswa pp, siswa sis, pelajaran pel 
      WHERE pp.nis=sis.nis 
        AND ppel.replid=pp.idpp 
        AND ppel.idpelajaran=pel.replid 
        AND ppel.idsemester='$semester' 
        AND ppel.idkelas='$kelas' 
        AND sis.nis='$nis' 
        AND ppel.tanggal BETWEEN '$tglawal' AND '$tglakhir'
      GROUP BY pel.nama";
$result_get_pelajaran_presensi=QueryDb($sql_get_pelajaran_presensi);
$cntpel_presensi=1;

while ($row_get_pelajaran_presensi=@mysqli_fetch_array($result_get_pelajaran_presensi))
{
    //ambil semua jumlah presensi per pelajaran
    $sql_get_all_presensi =
        "SELECT count(*) as jumlah 
           FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp 
          WHERE pel.idpelajaran ='".$row_get_pelajaran_presensi['replid']."' 
            AND pel.idsemester ='$semester' 
            AND pel.idkelas ='$kelas' 
            AND pel.replid =pp.idpp 
            AND pel.tanggal BETWEEN '$tglawal' AND '$tglakhir'
            AND pp.nis='$nis'";
    $result_get_all_presensi=QueryDb($sql_get_all_presensi);
    $row_get_all_presensi=@mysqli_fetch_array($result_get_all_presensi);
    //dapet nih jumlahnya
    $jumlah_presensi=$row_get_all_presensi['jumlah'];

    //ambil yang hadir
    $sql_get_hadir=
        "SELECT count(*) as hadir 
           FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp 
          WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' 
            AND pel.idsemester='$semester' 
            AND pel.idkelas='$kelas' 
            AND pel.replid=pp.idpp 
            AND pp.nis='$nis' 
            AND pel.tanggal BETWEEN '$tglawal' AND '$tglakhir'
            AND pp.statushadir=0";
    $result_get_hadir=QueryDb($sql_get_hadir);
    $row_get_hadir=@mysqli_fetch_array($result_get_hadir);
    $hadir=$row_get_hadir['hadir'];
    $hh[$cntpel_presensi]=$hadir;

    //ambil yang sakit
    $sql_get_sakit =
        "SELECT count(*) as sakit 
           FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp 
          WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' 
            AND pel.idsemester='$semester' 
            AND pel.idkelas='$kelas' 
            AND pel.replid=pp.idpp 
            AND pp.nis='$nis' 
            AND pel.tanggal BETWEEN '$tglawal' AND '$tglakhir'
            AND pp.statushadir=1";
    $result_get_sakit=QueryDb($sql_get_sakit);
    $row_get_sakit=@mysqli_fetch_array($result_get_sakit);
    $sakit=$row_get_sakit['sakit'];
    $ss[$cntpel_presensi]=$sakit;

    //ambil yang ijin
    $sql_get_ijin=
        "SELECT count(*) as ijin 
           FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp 
          WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' 
            AND pel.idsemester='$semester' 
            AND pel.idkelas='$kelas' 
            AND pel.replid=pp.idpp 
            AND pp.nis='$nis' 
            AND pel.tanggal BETWEEN '$tglawal' AND '$tglakhir'
            AND pp.statushadir=2";
    $result_get_ijin=QueryDb($sql_get_ijin);
    $row_get_ijin=@mysqli_fetch_array($result_get_ijin);
    $ijin=$row_get_ijin['ijin'];
    $ii[$cntpel_presensi]=$ijin;

    //ambil yang alpa
    $sql_get_alpa=
        "SELECT count(*) as alpa 
           FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp 
          WHERE pel.idpelajaran='".$row_get_pelajaran_presensi['replid']."' 
            AND pel.idsemester='$semester' 
            AND pel.idkelas='$kelas' 
            AND pel.replid=pp.idpp 
            AND pp.nis='$nis' 
            AND pel.tanggal BETWEEN '$tglawal' AND '$tglakhir'
            AND pp.statushadir=3";
    $result_get_alpa=QueryDb($sql_get_alpa);
    $row_get_alpa=@mysqli_fetch_array($result_get_alpa);
    $alpa=$row_get_alpa['alpa'];
    $aa[$cntpel_presensi]=$alpa;

    //hitung prosentase kalo jumlahnya gak 0
    if ($jumlah_presensi<>0){
        $p_hadir=round(($hadir/$jumlah_presensi)*100);
        $p_sakit=round(($sakit/$jumlah_presensi)*100);
        $p_ijin=round(($ijin/$jumlah_presensi)*100);
        $p_alpa=round(($alpa/$jumlah_presensi)*100);
    } else {
        $p_hadir=0;
        $p_sakit=0;
        $p_ijin=0;
        $p_alpa=0;
    }
    ?>
    <tr>
        <td height="25"><?=$row_get_pelajaran_presensi['nama']?></td>
        <td height="25"><div align="center"><?=$hadir?></div></td>
        <td height="25"><div align="center"><?=$p_hadir?>%</div></td>
        <td height="25"><div align="center"><?=$sakit?></div></td>
        <td height="25"><div align="center"><?=$p_sakit?>%</div></td>
        <td height="25"><div align="center"><?=$ijin?></div></td>
        <td height="25"><div align="center"><?=$p_ijin?>%</div></td>
        <td height="25"><div align="center"><?=$alpa?></div></td>
        <td height="25"><div align="center"><?=$p_alpa?>%</div></td>
    </tr>
<?php
    $cntpel_presensi++;
}

    $hdr = 0;
    for ($i=1;$i<=count($hh);$i++)
        $hdr += $hh[$i];

    $skt = 0;
    for ($i=1;$i<=count($ss);$i++)
        $skt += $ss[$i];

    $ijn = 0;
    for ($i=1;$i<=count($ii);$i++)
        $ijn += $ii[$i];

    $alp = 0;
    for ($i=1;$i<=count($aa);$i++)
        $alp += $aa[$i]; ?>
<tr>
    <td height="25" bgcolor="#FFFFFF"><div align="center" class="style6">Total</div></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><?=$hdr?></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"></div></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><?=$skt?></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"></div></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><?=$ijn?></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"></div></td>
    <td height="25" bgcolor="#CCCCCC"><div align="center"><?=$alp?></div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center"></div></td>
</tr>
</table>
</fieldset>
<br>