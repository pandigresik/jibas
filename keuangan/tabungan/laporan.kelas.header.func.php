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
function ReadRequest()
{
    global $idtabungan, $departemen, $idangkatan, $idtingkat, $idkelas;
    
    $departemen = "";
    if (isset($_REQUEST['departemen']))
        $departemen = $_REQUEST['departemen'];
        
    $idangkatan = 0;
    if (isset($_REQUEST['idangkatan']))
        $idangkatan = (int)$_REQUEST['idangkatan'];
        
    $idtingkat = -1;
    if (isset($_REQUEST['idtingkat']))
        $idtingkat = (int)$_REQUEST['idtingkat'];
    
    $idkelas = -1;
    if (isset($_REQUEST['idkelas']))
        $idkelas = (int)$_REQUEST['idkelas'];
        
    $idtabungan = 0;
    if (isset($_REQUEST['idtabungan']))
        $idtabungan = (int)$_REQUEST['idtabungan'];        
}

function SelectDepartemen()
{
    global $departemen;
?>
    <select id="departemen" name="departemen" style="width:188px" onchange="change_dep()"
            onKeyPress="return focusNext('idangkatan', event)">
<?php  $dep = getDepartemen(getAccess());
    foreach($dep as $value)
    {
        if ($departemen == "")
            $departemen = $value; ?>
        <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?>><?=$value ?></option>
<?php } ?>  
    </select>
<?php
}
?>

<?php
function SelectAngkatan()
{
    global $departemen, $idangkatan;
?>
    <select id="idangkatan" name="idangkatan" style="width:100px" onchange="change_ang()" onKeyPress="return focusNext('idtingkat', event)">
    <?php 	$sql = "SELECT replid, angkatan FROM jbsakad.angkatan WHERE departemen = '$departemen' AND aktif = 1 ORDER BY angkatan";
        $result = QueryDb($sql);
        while($row = mysqli_fetch_row($result)) {
            if ($idangkatan == 0)
                $idangkatan = $row[0]; ?>
            <option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $idangkatan)?> > <?=$row[1]?></option>
    <?php } ?>
    </select>
<?php
}
?>

<?php
function SelectTingkat()
{
    global $departemen, $idtingkat;
?>
    <select name="idtingkat" id="idtingkat" onChange="change_ang()" style="width:80px;" onkeypress="return focusNext('lunas', event)" >
    <option value="-1" <?=IntIsSelected(-1, $idtingkat)?>>(Semua)</option>
    <?php
        $sql="SELECT * FROM jbsakad.tingkat WHERE departemen='$departemen' AND aktif = 1 ORDER BY urutan";
        $result=QueryDb($sql);
        
        while ($row=@mysqli_fetch_array($result)) {            
    ?> 
        <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $idtingkat)?>><?=$row['tingkat']?></option>
    <?php 	} ?> 
    </select>
<?php
}
?>

<?php
function SelectKelas()
{
    global $idtingkat, $idkelas;
?>
    <select id="idkelas" name="idkelas" style="width:103px" onchange="change_kelas()" onkeypress="return focusNext('lunas', event)">
    <option value="-1">(Semua)</option>
    <?php  $sql = "SELECT DISTINCT k.replid, k.kelas
                  FROM jbsakad.tahunajaran t, jbsakad.kelas k
                 WHERE t.replid = k.idtahunajaran AND k.aktif = 1
                   AND k.idtingkat = '$idtingkat' AND t.aktif = 1 ORDER BY k.kelas";
        $result = QueryDb($sql);
        while($row = mysqli_fetch_row($result)) {
    ?>       
            <option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $idkelas)?> > <?=$row[1]?></option>
    <?php 	} ?>
            
    </select>
<?php
}
?>

<?php
function SelectTabungan()
{
    global $departemen, $idtabungan;
?>
    <select name="idtabungan" id="idtabungan" style="width:188px;" onchange="change_tabungan()" onkeypress="return focusNext('idpenerimaan', event)">
    <?php  $sql = "SELECT replid, nama
                  FROM datatabungan
                 WHERE departemen = '$departemen'
                   AND aktif = 1";
        $result = QueryDb($sql);
        while ($row = mysqli_fetch_array($result))
        {   ?>
            <option value="<?=$row['replid'] ?>" <?=IntIsSelected($row['replid'], $idtabungan)?>> <?=$row['nama'] ?></option>
    <?php  } ?>
    </select>       
<?php
}
?>
