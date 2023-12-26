<?php
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

function ShowAgamaCombo()
{
	$sql = "SELECT agama
              FROM jbsumum.agama
             ORDER BY urutan";
	$res = QueryDB($sql);
    
    echo "<select name='agama' id='agama' class='inputbox'>";
	while ($row = mysqli_fetch_array($res))
    {
		echo "<option value='" . $row['agama'] . "' >" . $row['agama'] . "</option>";
	}
    echo "</select>";
}

function ShowSukuCombo()
{
	$sql = "SELECT suku
              FROM jbsumum.suku
             ORDER BY urutan";
	$res = QueryDB($sql);
    
    echo "<select name='suku' id='suku' class='inputbox'>";
	while ($row = mysqli_fetch_array($res))
    {
		echo "<option value='" . $row['suku'] . "' >" . $row['suku'] . "</option>";
	}
    echo "</select>";
}

function ShowStatusCombo()
{
	$sql = "SELECT status
              FROM jbsakad.statussiswa
             ORDER BY urutan";
	$res = QueryDB($sql);
    
    echo "<select name='status' id='status' class='inputbox'>";
	while ($row = mysqli_fetch_array($res))
    {
		echo "<option value='" . $row['status'] . "' >" . $row['status'] . "</option>";
	}
    echo "</select>";
}

function ShowKondisiCombo()
{
	$sql = "SELECT kondisi
              FROM jbsakad.kondisisiswa
             ORDER BY urutan";
	$res = QueryDB($sql);
    
    echo "<select name='kondisi' id='kondisi' class='inputbox'>";
	while ($row = mysqli_fetch_array($res))
    {
		echo "<option value='" . $row['kondisi'] . "' >" . $row['kondisi'] . "</option>";
	}
    echo "</select>";
}

function ShowStatusAnakCombo()
{
    echo "<select name='statusanak' id='statusanak' class='inputbox'>";
	echo "<option value='Kandung'>Anak Kandung</option>";
    echo "<option value='Angkat'>Anak Angkat</option>";
    echo "<option value='Tiri'>Anak Tiri</option>";
    echo "<option value='Lainnya'>Lainnya</option>";
    echo "</select>";
}

function ShowJenjangSekolahCombo()
{
    echo "<select name='dep_asal' id='dep_asal' class='inputbox' onchange='changeJenjangSekolah()'>";
	echo "<option value='TK/RA'>TK | RA</option>";
    echo "<option value='SD/MI'>SD | MI</option>";
    echo "<option value='SMP/MTS'>SMP | MTS</option>";
    echo "<option value='SMA/MA'>SMA | MA</option>";
    echo "<option value='Lainnya'>Lainnya</option>";
    echo "</select>";
}

function ShowAsalSekolahCombo($jenjang)
{
    $sql = "SELECT sekolah
              FROM jbsakad.asalsekolah
             WHERE departemen = '$jenjang'
             ORDER BY sekolah ASC";
	$res = QueryDB($sql);
    $ndata = mysqli_num_rows($res);
    
    if ($ndata == 0)
    {
        echo "<input type='hidden' id='sekolah' name='sekolah' value='-1'>";
        echo "<input type='hidden' id='inputsekolah' name='inputsekolah' value='1'>";
        echo "<input type='textbox' style='visibility: visible' class='inputbox' id='newsekolah' name='newsekolah' size='30' maxlength='100'>";
    }
    else
    {
        echo "<select name='sekolah' id='sekolah' class='inputbox' onchange='changeAsalSekolah()'>";
        while($row = mysqli_fetch_row($res))
        {
            echo "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
        }
        echo "<option value='-1'>Lainnya</option>";
        echo "</select>";
        echo "<input type='hidden' id='inputsekolah' name='inputsekolah' value='0'>";
        echo "&nbsp;<input type='textbox' style='visibility: hidden' class='inputbox' id='newsekolah' name='newsekolah' size='30' maxlength='100'>";
    }
}

function ShowStatusOrtuCombo($id)
{
    echo "<select name='$id' id='$id' class='inputbox'>";
	echo "<option value='Kandung'>Ortu Kandung</option>";
    echo "<option value='Angkat'>Ortu Angkat</option>";
    echo "<option value='Tiri'>Ortu Tiri</option>";
    echo "<option value='Lainnya'>Lainnya</option>";
    echo "</select>";
}

function ShowPendidikanCombo($id)
{
	$sql = "SELECT pendidikan
              FROM jbsumum.tingkatpendidikan
             ORDER BY pendidikan";
	$res = QueryDB($sql);
    
    echo "<select name='$id' id='$id' class='inputbox'>";
	while ($row = mysqli_fetch_row($res))
    {
		echo "<option value='" . $row[0] . "' >" . $row[0] . "</option>";
	}
    echo "</select>";
}

function ShowPekerjaanCombo($id)
{
	$sql = "SELECT pekerjaan
              FROM jbsumum.jenispekerjaan
             ORDER BY pekerjaan";
	$res = QueryDB($sql);
    
    echo "<select name='$id' id='$id' class='inputbox'>";
	while ($row = mysqli_fetch_row($res))
    {
		echo "<option value='" . $row[0] . "' >" . $row[0] . "</option>";
	}
    echo "</select>";
}
?>