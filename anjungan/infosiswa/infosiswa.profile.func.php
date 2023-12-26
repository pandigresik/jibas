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
function ShowYearCombo($id, $onChangeFunc, $startYear, $endYear, $selectedYear)
{
	global $isDisabled;
	
    echo "<select name='$id' id='$id' onchange='$onChangeFunc' class='inputbox' $isDisabled>";
    for($i = $startYear; $i <= $endYear; $i++)
    {
        $sel = $selectedYear == $i ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
}

function ShowMonthCombo($id, $onChangeFunc, $selectedMonth)
{
	global $isDisabled;
	
    echo "<select name='$id' id='$id' onchange='$onChangeFunc' class='inputbox' $isDisabled>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $selectedMonth == $i ? "selected" : "";
        echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
    }
    echo "</select>";
}

function ShowDateCombo($id, $onChangeFunc, $year, $month, $selectedDate)
{
	global $isDisabled;
	
    $maxDate = DateArith::DaysInMonth($month, $year);
    echo "<select name='$id' id='$id' onchange='$onChangeFunc' class='inputbox' $isDisabled>";
    for($i = 1; $i <= $maxDate; $i++)
    {
        $sel = $selectedDate == $i ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
}

function ShowAgamaCombo($selAgama)
{
	$sql = "SELECT agama
              FROM jbsumum.agama
             ORDER BY urutan";
	$res = QueryDB($sql);
    
    echo "<select name='is_agama' id='is_agama' class='inputbox'>";
	while ($row = mysqli_fetch_array($res))
    {
        $sel = $selAgama == $row['agama'] ? "selected" : "";
		echo "<option value='" . $row['agama'] . "' $sel>" . $row['agama'] . "</option>";
	}
    echo "</select>";
}

function ShowSukuCombo($selSuku)
{
	$sql = "SELECT suku
              FROM jbsumum.suku
             ORDER BY urutan";
	$res = QueryDB($sql);
    
    echo "<select name='is_suku' id='is_suku' class='inputbox'>";
	while ($row = mysqli_fetch_array($res))
    {
        $sel = $selSuku == $row['suku'] ? "selected" : "";
		echo "<option value='" . $row['suku'] . "' $sel>" . $row['suku'] . "</option>";
	}
    echo "</select>";
}

function ShowStatusCombo($selStatus)
{
	$sql = "SELECT status
              FROM jbsakad.statussiswa
             ORDER BY urutan";
	$res = QueryDB($sql);
    
    echo "<select name='is_status' id='is_status' class='inputbox'>";
	while ($row = mysqli_fetch_array($res))
    {
        $sel = $selStatus == $row['status'] ? "selected" : "";
		echo "<option value='" . $row['status'] . "' $sel >" . $row['status'] . "</option>";
	}
    echo "</select>";
}

function ShowKondisiCombo($selKondisi)
{
	$sql = "SELECT kondisi
              FROM jbsakad.kondisisiswa
             ORDER BY urutan";
	$res = QueryDB($sql);
    
    echo "<select name='is_kondisi' id='is_kondisi' class='inputbox'>";
	while ($row = mysqli_fetch_array($res))
    {
        $sel = $selKondisi == $row['kondisi'] ? "selected" : "";
		echo "<option value='" . $row['kondisi'] . "' $sel >" . $row['kondisi'] . "</option>";
	}
    echo "</select>";
}

function ShowStatusAnakCombo($selStatus)
{
    echo "<select name='is_statusanak' id='is_statusanak' class='inputbox'>";
	echo "<option value='Kandung' " . StringIsSelected($selStatus, "Kandung") . ">Anak Kandung</option>";
    echo "<option value='Angkat' " . StringIsSelected($selStatus, "Angkat") . ">Anak Angkat</option>";
    echo "<option value='Tiri' " . StringIsSelected($selStatus, "Tiri") . ">Anak Tiri</option>";
    echo "<option value='Lainnya' " . StringIsSelected($selStatus, "Lainnya") . ">Lainnya</option>";
    echo "</select>";
}

function ShowJenjangSekolahCombo($selJenjang)
{
    echo "<select name='is_dep_asal' id='is_dep_asal' class='inputbox' onchange='is_changeJenjangSekolah()'>";
	echo "<option value='TK/RA' " . StringIsSelected($selJenjang, "TK/RA") . ">TK | RA</option>";
    echo "<option value='SD/MI' " . StringIsSelected($selJenjang, "SD/MI") . ">SD | MI</option>";
    echo "<option value='SMP/MTS' " . StringIsSelected($selJenjang, "SMP/MTS") . ">SMP | MTS</option>";
    echo "<option value='SMA/SMK/MA' " . StringIsSelected($selJenjang, "SMA/SMK/MA") . ">SMA | SMK | MA</option>";
    echo "<option value='Lainnya' " . StringIsSelected($selJenjang, "Lainnya") . ">Lainnya</option>";
    echo "</select>";
}

function ShowAsalSekolahCombo($jenjang, $sekolah)
{
    $sql = "SELECT sekolah
              FROM jbsakad.asalsekolah
             WHERE departemen = '$jenjang'
             ORDER BY sekolah ASC";
	$res = QueryDB($sql);
    $ndata = mysqli_num_rows($res);
    
    if ($ndata == 0)
    {
        echo "<input type='hidden' id='is_sekolah' name='is_sekolah' value='-1'>";
        echo "<input type='hidden' id='is_inputsekolah' name='is_inputsekolah' value='1'>";
        echo "<input type='textbox' style='visibility: visible' class='inputbox' id='is_newsekolah' name='is_newsekolah' size='30' maxlength='100'>";
    }
    else
    {
        echo "<select name='is_sekolah' id='is_sekolah' class='inputbox' onchange='is_changeAsalSekolah()'>";
        while($row = mysqli_fetch_row($res))
        {
            echo "<option value='" . $row[0] . "' " . StringIsSelected($row[0], $sekolah) . ">" . $row[0] . "</option>";
        }
        echo "<option value='-1'>Lainnya</option>";
        echo "</select>";
        echo "<input type='hidden' id='is_inputsekolah' name='is_inputsekolah' value='0'>";
        echo "&nbsp;<input type='textbox' style='visibility: hidden' class='inputbox' id='is_newsekolah' name='is_newsekolah' size='30' maxlength='100'>";
    }
}

function ShowStatusOrtuCombo($id, $selStatus)
{
    echo "<select name='$id' id='$id' class='inputbox'>";
	echo "<option value='Kandung' " . StringIsSelected($selStatus, "Kandung") . ">Ortu Kandung</option>";
    echo "<option value='Angkat' " . StringIsSelected($selStatus, "Angkat") . ">Ortu Angkat</option>";
    echo "<option value='Tiri' " . StringIsSelected($selStatus, "Tiri") . ">Ortu Tiri</option>";
    echo "<option value='Lainnya' " . StringIsSelected($selStatus, "Lainnya") . ">Lainnya</option>";
    echo "</select>";
}

function ShowPendidikanCombo($id, $selPendidikan)
{
	$sql = "SELECT pendidikan
              FROM jbsumum.tingkatpendidikan
             ORDER BY pendidikan";
	$res = QueryDB($sql);
    
    echo "<select name='$id' id='$id' class='inputbox'>";
	while ($row = mysqli_fetch_row($res))
    {
        $sel = ($selPendidikan == $row[0]) ? "selected" : "";
		echo "<option value='" . $row[0] . "' $sel>" . $row[0] . "</option>";
	}
    echo "</select>";
}

function ShowPekerjaanCombo($id, $selPekerjaan)
{
	$sql = "SELECT pekerjaan
              FROM jbsumum.jenispekerjaan
             ORDER BY pekerjaan";
	$res = QueryDB($sql);
    
    echo "<select name='$id' id='$id' class='inputbox'>";
	while ($row = mysqli_fetch_row($res))
    {
        $sel = ($selPekerjaan == $row[0]) ? "selected" : "";
		echo "<option value='" . $row[0] . "' $sel>" . $row[0] . "</option>";
	}
    echo "</select>";
}
?>