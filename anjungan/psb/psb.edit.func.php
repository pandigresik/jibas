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
$selKelompok = "";

function ShowDepartemenCombo2()
{
    global $selDept;
    
    $sql = "SELECT departemen
              FROM jbsakad.departemen
             WHERE aktif = 1
             ORDER BY urutan";
    $res = QueryDB($sql);
    
    echo "<select name='psb_departemen' id='psb_departemen' class='inputbox' onchange='psb_changeDepartemenEdit()'>";
	while ($row = mysqli_fetch_row($res))
    {
        if ($selDept == "")
            $selDept = $row[0];
 
        $sel = ($selDept == $row[0]) ? "selected" : "";
            
		echo "<option value='" . $row[0] . "' $sel>" . $row[0] . "</option>";
	}
    echo "</select>";         
}

function ShowPenerimaanCombo2($selDept)
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
        echo "<select name='psb_proses' id='psb_proses' class='inputbox' onchange='psb_changeProsesEdit()'>";
        while ($row = mysqli_fetch_row($res))
        {
            if ($selProses == "")
                $selProses = $row[0];
                
            $sel = ($selProses == $row[0]) ? "selected" : "";    
                
            echo "<option value='" . $row[0] . "' $sel>" . $row[1] . "</option>";
        }
        echo "</select>";             
    }
}

function ShowKelompokCombo2($selProses)
{
    global $selKelompok;
    
    $sql = "SELECT replid, kelompok, kapasitas
              FROM jbsakad.kelompokcalonsiswa
             WHERE idproses = '$selProses'
             ORDER BY kelompok";
    $res = QueryDB($sql);
    
    if (mysqli_num_rows($res) == 0)
    {
        echo "<input type='hidden' name='psb_kelompok' id='psb_kelompok' value='-1'>";
        echo "<em>Belum ada data kelompok penerimaan</em>";
    }
    else
    {
        echo "<select name='psb_kelompok' id='psb_kelompok' class='inputbox'>";
        while ($row = mysqli_fetch_row($res))
        {
            $sql = "SELECT COUNT(replid)
                      FROM jbsakad.calonsiswa
                     WHERE idkelompok = '".$row[0]."'
                       AND aktif = 1";
            $ndata = FetchSingle($sql);           
            
            if ($selKelompok == "")
                $selKelompok = $row[0];
            
            $sel = ($selKelompok == $row[0]) ? "selected" : "";
                
            echo "<option value='" . $row[0] . "' $sel >" . $row[1] . ", kapasitas: " . $row[2] . ", terisi: " . $ndata . "</option>";
        }
        echo "</select>";
    }
}

function ShowYearCombo($id, $onChangeFunc, $startYear, $endYear, $selectedYear)
{
    echo "<select name='$id' id='$id' onchange='$onChangeFunc' class='inputbox'>";
    for($i = $startYear; $i <= $endYear; $i++)
    {
        $sel = $selectedYear == $i ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
}

function ShowMonthCombo($id, $onChangeFunc, $selectedMonth)
{
    echo "<select name='$id' id='$id' onchange='$onChangeFunc' class='inputbox'>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $selectedMonth == $i ? "selected" : "";
        echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
    }
    echo "</select>";
}

function ShowDateCombo($id, $onChangeFunc, $year, $month, $selectedDate)
{
    $maxDate = DateArith::DaysInMonth($month, $year);
    echo "<select name='$id' id='$id' onchange='$onChangeFunc' class='inputbox'>";
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
    
    echo "<select name='psb_agama' id='psb_agama' class='inputbox'>";
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
    
    echo "<select name='psb_suku' id='psb_suku' class='inputbox'>";
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
    
    echo "<select name='psb_status' id='psb_status' class='inputbox'>";
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
    
    echo "<select name='psb_kondisi' id='psb_kondisi' class='inputbox'>";
	while ($row = mysqli_fetch_array($res))
    {
        $sel = $selKondisi == $row['kondisi'] ? "selected" : "";
		echo "<option value='" . $row['kondisi'] . "' $sel >" . $row['kondisi'] . "</option>";
	}
    echo "</select>";
}

function ShowStatusAnakCombo($selStatus)
{
    echo "<select name='psb_statusanak' id='psb_statusanak' class='inputbox'>";
	echo "<option value='Kandung' " . StringIsSelected($selStatus, "Kandung") . ">Anak Kandung</option>";
    echo "<option value='Angkat' " . StringIsSelected($selStatus, "Angkat") . ">Anak Angkat</option>";
    echo "<option value='Tiri' " . StringIsSelected($selStatus, "Tiri") . ">Anak Tiri</option>";
    echo "<option value='Lainnya' " . StringIsSelected($selStatus, "Lainnya") . ">Lainnya</option>";
    echo "</select>";
}

function ShowJenjangSekolahCombo($selJenjang)
{
    echo "<select name='psb_dep_asal' id='psb_dep_asal' class='inputbox' onchange='psb_changeJenjangSekolah()'>";
	echo "<option value='TK/RA' " . StringIsSelected($selJenjang, "TK/RA") . ">TK | RA</option>";
    echo "<option value='SD/MI' " . StringIsSelected($selJenjang, "SD/MI") . ">SD | MI</option>";
    echo "<option value='SMP/MTS' " . StringIsSelected($selJenjang, "SMP/MTS") . ">SMP | MTS</option>";
    echo "<option value='SMA/SMK/MA' " . StringIsSelected($selJenjang, "SMA/SMK/MA") . ">SMA | SMK | MA</option>";
    echo "<option value='Lainnya' " . StringIsSelected($selJenjang, "Lainnya") . ">Lainnya</option>";
    echo "</select>";
}

function ShowAsalSekolahCombo($jenjang, $selSekolah)
{
    $sql = "SELECT sekolah
              FROM jbsakad.asalsekolah
             WHERE departemen = '$jenjang'
             ORDER BY sekolah ASC";
	$res = QueryDB($sql);
    $ndata = mysqli_num_rows($res);
    
    if ($ndata == 0)
    {
        echo "<input type='hidden' id='psb_sekolah' name='psb_sekolah' value='-1'>";
        echo "<input type='hidden' id='psb_inputsekolah' name='psb_inputsekolah' value='1'>";
        echo "<input type='textbox' style='visibility: visible' class='inputbox' id='psb_newsekolah' name='psb_newsekolah' size='30' maxlength='100'>";
    }
    else
    {
        echo "<select name='psb_sekolah' id='psb_sekolah' class='inputbox' onchange='psb_changeAsalSekolah()'>";
        while($row = mysqli_fetch_row($res))
        {
			$sel = ($selSekolah == $row[0]) ? "selected" : "";
            echo "<option value='" . $row[0] . "' $sel >" . $row[0] . "</option>";
        }
        echo "<option value='-1'>Lainnya</option>";
        echo "</select>";
        echo "<input type='hidden' id='psb_inputsekolah' name='psb_inputsekolah' value='0'>";
        echo "&nbsp;<input type='textbox' style='visibility: hidden' class='inputbox' id='psb_newsekolah' name='psb_newsekolah' size='30' maxlength='100'>";
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

function ShowSumbangan($proses, $rowdata)
{
    ?>
    
    <table border="0" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td colspan="2" align="left">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
            <font style="color: #557d1d; font-size: 16px;">Data Sumbangan</font>
            <br><br>
        </td>
    </tr>
    
<?php  $sql = "SELECT COUNT(replid)
              FROM jbsakad.settingpsb
             WHERE idproses = '".$proses."'";
	$ndata = FetchSingle($sql);
	if ($ndata > 0)
	{
		$sql = "SELECT *
                  FROM jbsakad.settingpsb
                 WHERE idproses = '".$proses."'";
		$res = QueryDb($sql);
		$row = mysqli_fetch_array($res);
		
		$kdsum1 = $row['kdsum1']; 
		$kdsum2 = $row['kdsum2'];  ?>
        
        <tr>
            <td width="15%" align="right" valign="top">Sumbangan #1:</td>
            <td align="left" valign="top">
                <input type="text" name="psb_sum1" id="psb_sum1" size="20" maxlength="20" class="inputbox"
                       onblur="formatRupiah('psb_sum1')" onfocus="unformatRupiah('psb_sum1')" value="<?= FormatRupiah($rowdata['sum1']) ?>">&nbsp;<?=$kdsum1?>
            </td>
        </tr>
        <tr>
            <td width="15%" align="right" valign="top">Sumbangan #2:</td>
            <td align="left" valign="top">
                <input type="text" name="psb_sum2" id="psb_sum2" size="20" maxlength="20" class="inputbox"
                       onblur="formatRupiah('psb_sum2')" onfocus="unformatRupiah('psb_sum2')" value="<?= FormatRupiah($rowdata['sum2']) ?>">&nbsp;<?=$kdsum2?>
            </td>
        </tr>
<?php
	}
    else
    {
        echo "<tr>";
        echo "<td width='15%' align='right' valign='top'>&nbsp;</td>";
        echo "<td align='left' valign='top'>";
        echo "<em>Belum ada konfigurasi Sumbangan untuk Proses Penerimaan ini</em>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function ShowNilaiUjian($proses, $rowdata)
{
    ?>
    
    <table border="0" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <td colspan="2" align="left">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <font style="background-color: #557d1d; font-size: 16px;">&nbsp;&nbsp;</font>&nbsp;
            <font style="color: #557d1d; font-size: 16px;">Data Nilai Ujian</font>
            <br><br>
        </td>
    </tr>
    
<?php  $sql = "SELECT COUNT(replid)
              FROM jbsakad.settingpsb
             WHERE idproses = '".$proses."'";
	$ndata = FetchSingle($sql);
	if ($ndata > 0)
	{
		$sql = "SELECT *
                  FROM jbsakad.settingpsb
                 WHERE idproses = '".$proses."'";
		$res = QueryDb($sql);
        $row = mysqli_fetch_array($res);
        
        for($n = 1; $n <= 10; $n++)
        {
            $id = "psb_ujian$n";
            $col = "kdujian$n";
            $ujian = $row[$col];
            
            $col = "ujian$n";
            $val = $rowdata[$col];
            ?>
            <tr>
                <td width="15%" align="right" valign="top">Ujian #<?=$n?>:</td>
                <td align="left" valign="top">
                    <input type="text" name="<?=$id?>" id="<?=$id?>" size="5" maxlength="5" class="inputbox" value="<?=$val?>">&nbsp;<?=$ujian?>
                </td>
            </tr>
<?php      }
	}
    else
    {
        echo "<tr>";
        echo "<td width='15%' align='right' valign='top'>&nbsp;</td>";
        echo "<td align='left' valign='top'>";
        echo "<em>Belum ada konfigurasi Nilai Ujian untuk Proses Penerimaan ini</em>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";    
}

?>