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
    <legend><strong>Presensi Harian</strong></legend>
<?php  $sql_harian = "SELECT SUM(ph.hadir) as hadir, SUM(ph.ijin) as ijin, SUM(ph.sakit) as sakit, SUM(ph.cuti) as cuti, SUM(ph.alpa) as alpa, SUM(ph.hadir+ph.sakit+ph.ijin+ph.alpa+ph.cuti) as tot ".
                    "FROM presensiharian p, phsiswa ph, siswa s ".
                   "WHERE ph.idpresensi = p.replid ".
                     "AND ph.nis = s.nis ".
                     "AND ph.nis = '$nis' ".
                     "AND ((p.tanggal1 ".
                 "BETWEEN '$tglawal' ".
                     "AND '$tglakhir') ".
                      "OR (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) ".
                   "ORDER BY p.tanggal1"; ;?>
    <!-- Content Presensi disini -->
<table width="100%" border="1" class="tab" id="table"  bordercolor="#000000" style="border-collapse: collapse; border-width: 1px;">
<tr>
    <td height="25" colspan="2" background="../style/formbg2agreen.gif"><div align="center" class="style1">Hadir</div></td>
    <td height="25" colspan="2" class="headerlong"><div align="center">Sakit</div></td>
    <td height="25" colspan="2" background="../style/formbg2agreen.gif"><div align="center" class="style1">Ijin</div></td>
    <td height="25" colspan="2" class="headerlong"><div align="center">Alpa</div></td>
    <td height="25" colspan="2" background="../style/formbg2agreen.gif"><div align="center" class="style1">Cuti</div></td>
</tr>
<tr>
    <td width="6" background="../style/formbg2agreen.gif"><div align="center" class="style1">Jumlah</div></td>
    <td width="6" background="../style/formbg2agreen.gif"><div align="center" class="style1">%</div></td>
    <td width="6" class="headerlong"><div align="center">Jumlah</div></td>
    <td width="6" class="headerlong"><div align="center">%</div></td>
    <td width="6" background="../style/formbg2agreen.gif"><div align="center" class="style1">Jumlah</div></td>
    <td width="6" background="../style/formbg2agreen.gif"><div align="center" class="style1">%</div></td>
    <td width="6" class="headerlong"><div align="center">Jumlah</div></td>
    <td width="6" class="headerlong"><div align="center">%</div></td>
    <td width="6" background="../style/formbg2agreen.gif"><div align="center" class="style1">Jumlah</div></td>
    <td width="6" background="../style/formbg2agreen.gif"><div align="center" class="style1">%</div></td>
</tr>
        <!-- Ambil pelajaran per departemen-->
<?php
$result_harian=QueryDb($sql_harian);
$row_harian=@mysqli_fetch_array($result_harian);
$hadir=$row_harian['hadir'];
$sakit=$row_harian['sakit'];
$ijin=$row_harian['ijin'];
$alpa=$row_harian['alpa'];
$cuti=$row_harian['cuti'];
$all=$row_harian['tot'];
if ($hadir!=0 && $all !=0)
    $p_hadir=$hadir/$all*100;

if ($sakit!=0 && $all !=0)
    $p_sakit=$sakit/$all*100;

if ($ijin!=0 && $all !=0)
    $p_ijin=$ijin/$all*100;

if ($alpa!=0 && $all !=0)
    $p_alpa=$alpa/$all*100;

if ($cuti!=0 && $all !=0)
    $p_cuti=$cuti/$all*100;
?>

<tr>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
            <?=$hadir?>
        </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
            <?=round($p_hadir,2)?>
            &nbsp;%</div></td>
    <td height="25" bgcolor="#FFFFCC"><div align="center">
            <?=$sakit?>
        </div></td>
    <td height="25" bgcolor="#FFFFCC"><div align="center">
            <?=round($p_sakit,2)?>
            &nbsp;%</div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
            <?=$ijin?>
        </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
            <?=round($p_ijin,2)?>
            &nbsp;%</div></td>
    <td height="25" bgcolor="#FFFFCC"><div align="center">
            <?=$alpa?>
        </div></td>
    <td height="25" bgcolor="#FFFFCC"><div align="center">
            <?=round($p_alpa,2)?>
            &nbsp;%</div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
            <?=$cuti?>
        </div></td>
    <td height="25" bgcolor="#FFFFFF"><div align="center">
            <?=round($p_cuti,2)?>
            &nbsp;%</div></td>
</tr>
</table>
</fieldset>
<br>