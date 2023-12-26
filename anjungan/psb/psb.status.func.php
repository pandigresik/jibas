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
$selDept = "";
$selProses = "";

function ShowDepartemenCombo()
{
    global $selDept;
    
    $sql = "SELECT departemen
              FROM jbsakad.departemen
             WHERE aktif = 1
             ORDER BY urutan";
    $res = QueryDB($sql);
    
    echo "<select name='psb_departemen' id='psb_departemen' class='inputbox' onchange='psb_StatusPsbChangeDepartemen()'>";
	while ($row = mysqli_fetch_row($res))
    {
        if ($selDept == "")
            $selDept = $row[0];
            
		echo "<option value='" . $row[0] . "' >" . $row[0] . "</option>";
	}
    echo "</select>";         
}

function ShowPenerimaanCombo($selDept)
{
    global $selProses;
    
    $sql = "SELECT replid, proses
              FROM jbsakad.prosespenerimaansiswa
             WHERE aktif = 1
               AND departemen='$selDept'";
    $res = QueryDB($sql);
    
    if (mysqli_num_rows($res) == 0)
    {
        echo "<input type='hidden' name='psb_proses' id='psb_proses' value='-1'>";
        echo "<em>Belum ada data proses penerimaan</em>";
    }
    else
    {
        echo "<select name='psb_proses' id='psb_proses' class='inputbox' onchange='psb_StatusPsbChangeProses()'>";
        while ($row = mysqli_fetch_row($res))
        {
            if ($selProses == "")
                $selProses = $row[0];
                
            echo "<option value='" . $row[0] . "' >" . $row[1] . "</option>";
        }
        echo "</select>";             
    }
}

function ShowStatusPsb($idproses, $page)
{
    $nRow = 10;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsakad.calonsiswa
             WHERE idproses = '$idproses'
               AND aktif = 1
               AND NOT replidsiswa IS NULL";
    //echo $sql;            
    $nData = (int)FetchSingle($sql);
    $nPage = ceil($nData / $nRow);
    
    $offset = ($page - 1) * $nRow;
    ?>
    
    <table border="0" cellpadding="0" cellspacing="0" width="96%">
    <tr>
    <td align="left" valign="bottom">
        <input type="button" value=" < " class="but" style="height: 22px; width: 35px;" onclick="psb_StatusPsbGotoPage(<?=$idproses?>, <?=$page-1?>, <?=$nPage?>)" >
        <input type="button" value=" > " class="but" style="height: 22px; width: 35px;" onclick="psb_StatusPsbGotoPage(<?=$idproses?>, <?=$page+1?>, <?=$nPage?>)" >
        Halaman <select name="psb_StatusPsbPage" id="psb_StatusPsbPage" id="page" style="height: 22px; width: 40px;" onchange="psb_StatusPsbChangePage(<?=$idproses?>)">
<?php      for($i = 1; $i <= $nPage; $i++)  { ?>
            <option value="<?= $i ?>" <?= ($i == $page) ? "selected" : "" ?> ><?= $i ?></option>
<?php      } ?>
        </select> dari <?= $nPage ?>
    </td>    
    </tr>    
    </table>
    <table border="1" cellpadding="1" cellspacing="0" width="96%" style="border-collapse: collapse; border-width: 1px;">
    <tr height="22">
        <td width="4%" class="header">No</td>
        <td width="22%" class="header">NIS / No Pendaftaran</td>
        <td width="*" class="header">Nama</td>
        <td width="20%" class="header">Tmp/Tgl Lahir</td>
        <td width="24%" class="header">Asal Sekolah</td>
    </tr>
<?php
    $sql = "SELECT c.nopendaftaran, c.nama, c.tmplahir, DATE_FORMAT(c.tgllahir, '%d %b %Y') AS tgllahir, c.asalsekolah, s.nis
              FROM jbsakad.calonsiswa c, jbsakad.siswa s
             WHERE c.replidsiswa = s.replid
               AND c.idproses = '$idproses'
               AND c.aktif = 1
             ORDER BY c.nama
             LIMIT $offset, $nRow";
    //echo $sql;         
    $res = QueryDb($sql);
    $cnt = $offset;
    while($row = mysqli_fetch_array($res))
    {
        ?>
        <tr height="24" style="background-color: #fff;">
            <td align="left"><?= ++$cnt ?></td>
            <td align="left"><strong><?= $row['nis'] ?></strong><br><?= $row['nopendaftaran'] ?></td>
            <td align="left"><?= $row['nama'] ?></td>
            <td align="left"><?= $row['tmplahir'] . ", " . $row['tgllahir'] ?></td>
            <td align="left"><?= $row['asalsekolah'] ?></td>
        </tr>
        <?php
    }
    echo "</table>";
}
?>