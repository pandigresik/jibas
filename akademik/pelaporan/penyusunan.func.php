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
function ShowCbTingkat($departemen)
{
    $sql = "SELECT replid, tingkat
              FROM jbsakad.tingkat
             WHERE departemen = '$departemen'
               AND aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);
    
    echo "<select class='inputbox' name='tingkat' id='tingkat' onchange='changeCbTingkat()'>";
    echo "<option value='0' selected>(Semua Tingkat)</option>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
}

function ShowCbKelas($idtingkat)
{
    if ($idtingkat == 0)
    {
        echo "<select class='inputbox' name='kelas' id='kelas'>";
        echo "<option value='0' selected>(Semua Kelas)</option>";
        echo "</select>";
    }
    else
    {
        $sql = "SELECT k.replid, k.kelas
                  FROM jbsakad.kelas k, jbsakad.tahunajaran ta
                 WHERE k.idtahunajaran = ta.replid
                   AND k.idtingkat = '$idtingkat'
                   AND k.aktif = 1
                   AND ta.aktif = 1
                 ORDER BY k.kelas";
        $res = QueryDb($sql);
    
        echo "<select class='inputbox' name='kelas' id='kelas'>";
        echo "<option value='0' selected>(Semua Kelas)</option>";
        while($row = mysqli_fetch_row($res))
        {
            echo "<option value='".$row[0]."'>".$row[1]."</option>";
        }
        echo "</select>";
    }
}

function ShowCbPengantar($departemen)
{
    $sql = "SELECT replid, CONCAT(DATE_FORMAT(tanggal, '%Y-%m-%d'), ' ', judul) AS xjudul
              FROM jbsumum.pengantarsurat
             WHERE departemen = '$departemen'
               AND aktif = 1
             ORDER BY tanggal DESC, judul";
    $res = QueryDb($sql);
    
    $idpengantar = 0;
    echo "<select class='inputbox' name='pengantar' id='pengantar' style='width: 440px' onchange='changeCbPengantar()'>";
    while($row = mysqli_fetch_row($res))
    {
        if ($idpengantar == 0)
            $idpengantar = $row[0];
            
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
    
    return $idpengantar;
}

function ShowPengantar($idpengantar)
{
    $sql = "SELECT pengantar
              FROM jbsumum.pengantarsurat
             WHERE replid = $idpengantar";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    
    echo $row[0];
}

function ShowCbLampiran($departemen)
{
    $sql = "SELECT replid, CONCAT(DATE_FORMAT(tanggal, '%Y-%m-%d'), ' ', judul) AS xjudul
              FROM jbsumum.lampiransurat
             WHERE departemen = '$departemen'
               AND aktif = 1
             ORDER BY tanggal DESC, judul";
    $res = QueryDb($sql);
    
    $idlampiran = 0;
    echo "<select class='inputbox' name='lampiran' id='lampiran' disabled='disabled' style='width: 440px; background-color: #DDD' onchange='changeCbLampiran()'>";
    while($row = mysqli_fetch_row($res))
    {
        if ($idlampiran == 0)
            $idlampiran = $row[0];
            
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
    
    return $idlampiran;
}

function ShowLampiran($idlampiran)
{
    $sql = "SELECT pengantar
              FROM jbsumum.lampiransurat
             WHERE replid = $idlampiran";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    
    echo $row[0];
}

function GetCbDate($month, $year, $cbDate)
{
    $maxDay = DateArith::DaysInMonth($month, $year);
    $dtSel = ($month == date('n') && $year == date('Y')) ? date('j') : 1;
    
    echo "<select class='inputbox' id='$cbDate'>";
    for($i = 1; $i <= $maxDay; $i++)
    {
        $sel = $i == $dtSel ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
}

function ShowCbDate($selYear, $selMonth, $selDate, $cbYear, $cbMonth, $cbDate, $divDate, $enabled)
{
    global $G_START_YEAR;
    
    $disabled = "";
    $bgcolor = "style='background-color: #FFF;'";
    if (!$enabled)
    {
        $disabled = "disabled = 'disabled'";
        $bgcolor = "style='background-color: #DDD;'";
    }
    
    echo "<select class='inputbox' id='$cbYear' name='$cbYear' $disabled $bgcolor onchange=\"cbDateChanged('$cbYear', '$cbMonth', '$cbDate', '$divDate')\">";
    for($i = $G_START_YEAR; $i <= $selYear + 1; $i++)
    {
        $sel = $i == $selYear ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
    
    echo "<select class='inputbox' id='$cbMonth' name='$cbMonth' $disabled $bgcolor onchange=\"cbDateChanged('$cbYear', '$cbMonth', '$cbDate', '$divDate')\">";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == $selMonth ? "selected" : "";
        echo "<option value='$i' $sel>" .  NamaBulan($i) . "</option>";
    }
    echo "</select>";
    
    $maxDay = DateArith::DaysInMonth($selMonth, $selYear);
    echo "<span id='$divDate'>";
    echo "<select class='inputbox' id='$cbDate' name='$cbDate' $disabled $bgcolor>";
    for($i = 1; $i <= $maxDay; $i++)
    {
        $sel = $i == $selDate ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
    echo "</span>";
}

function ShowCbDateRange($basename, $enabled)
{
    $sql = "SELECT YEAR(DATE_SUB(NOW(), INTERVAL 30 DAY)),
                   MONTH(DATE_SUB(NOW(), INTERVAL 30 DAY)),
                   DAY(DATE_SUB(NOW(), INTERVAL 30 DAY)),
                   YEAR(NOW()),
                   MONTH(NOW()),
                   DAY(NOW())";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    
    $cbYear = $basename . "Yr30";
    $cbMonth = $basename . "Mn30";
    $cbDate = $basename . "Dt30";
    $divDate = $basename . "Dt30Div";
    ShowCbDate($row[0], $row[1], $row[2], $cbYear, $cbMonth, $cbDate, $divDate, $enabled);
    
    echo " s/d ";
    
    $cbYear = $basename . "Yr";
    $cbMonth = $basename . "Mn";
    $cbDate = $basename . "Dt";
    $divDate = $basename . "DtDiv";
    ShowCbDate($row[3], $row[4], $row[5], $cbYear, $cbMonth, $cbDate, $divDate, $enabled);
}
?>