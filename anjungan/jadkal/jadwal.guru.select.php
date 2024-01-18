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
require_once("../include/db_functions.php");

$dept = $_REQUEST['dept'] ?? "";
$jadwal = isset($_REQUEST['jadwal']) ? (int)$_REQUEST['jadwal'] : 0;
$guru = $_REQUEST['guru'] ?? "";

OpenDb();
?>
<table border="0" cellpadding="2" cellspacing="0">
<tr>
<td align="right">
<strong>Jadwal:</strong>
</td>
<td align="left" width="300">
    
    <select id='jg_departemen' onchange='jg_ChangeDept()' class='inputbox'>
<?php  $sql = "SELECT departemen
              FROM jbsakad.departemen
             WHERE aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        if ($dept == "")
            $dept = $row[0];
        $sel = $dept == $row[0] ? "selected" : "";
        ?>
        <option value='<?=$row[0]?>' <?=$sel?> ><?=$row[0]?></option>
<?php  }   ?>
    </select>
    
<?php  $sql = "SELECT replid
              FROM jbsakad.tahunajaran
             WHERE departemen = '$dept'
               AND aktif = 1";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $idtahunajaran = (int)$row[0]; ?>
    <input type="hidden" id="jd_tahunajaran" value="<?=$idtahunajaran?>">
    
    <select id='jg_jadwal' onchange='jg_ChangeJadwal()' class='inputbox'>
<?php  $sql = "SELECT replid, deskripsi
              FROM jbsakad.infojadwal
             WHERE idtahunajaran = '$idtahunajaran'
               AND aktif = 1
             ORDER BY deskripsi";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        if ($jadwal == 0)
            $jadwal = $row[0];
        $sel = $jadwal == $row[0] ? "selected" : "";
        ?>
        <option value='<?=$row[0]?>' <?=$sel?> ><?=$row[1]?></option>
<?php  }   ?>
    </select>    
    
</td>
<td rowspan="2">
<input type='button' style='height: 40px; width: 120px;' value='Lihat' class='but' onclick='jg_ShowContent()'>
</td>
</tr>
<tr>
    <td align="right">
    <strong>Guru:</strong>     
    </td>
    <td align="left">

    <select id='jg_guru' onchange='jg_ChangeGuru()' class='inputbox'>
<?php  $sql = "SELECT DISTINCT j.nipguru, p.nama
              FROM jbsakad.jadwal j, jbssdm.pegawai p
             WHERE j.nipguru = p.nip
               AND infojadwal = '$jadwal'
             ORDER BY nama";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {   ?>
        <option value='<?=$row[0]?>'><?=$row[1]?></option>
<?php  }   ?>
    </select>     
    
        
    </td>
</tr>
</table>
<?php
CloseDb();
?>