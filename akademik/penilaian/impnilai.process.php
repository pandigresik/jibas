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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');
require_once("impnilai.process.func.php");

OpenDb();

/** READ EXCEL */
include_once '../../vendor/autoload.php';

$fexcel = $_REQUEST['fexcel'];
$objReader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$objPHPExcel = $objReader->load($fexcel);
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
unlink($fexcel);

require_once("impnilai.process.validate.php");
?>
<form name="excelForm" id="excelForm">
<input type="hidden" name="idsemester" id="idsemester" value="<?=$idsemester?>">
<input type="hidden" name="idtingkat" id="idtingkat" value="<?=$idtingkat?>">
<input type="hidden" name="idkelas" id="idkelas" value="<?=$idkelas?>">
<input type="hidden" name="nipguru" id="nipguru" value="<?=$nip?>">

<font style="font-size: 14px; font-weight: bold; color: blue;">A. Data File Excel:</font><br><br>
<table border="1" cellpadding="2" cellspacing="0" border="1" style="border-width: 1px; border-collapse: collapse;">
<tr style="background-color: #e5f6ff; height: 24px;">
    <td width="40">&nbsp;</td>
    <td width="40">A</td>
    <td width="125">B</td>
    <td width="200">C</td>
    <td width="150">D</td>
    <td width="150">E</td>
    <td width="120">F</td>
    <td width="150">G</td>
</tr>
<?php
$nilaiCnt = 0;
$nrow = count($sheetData);
for($i = 1; $i <= $nrow; $i++)
{
    echo "<tr style='height: 24px;'>";
    echo "<td style=\"background-color: #e5f6ff;\" align='center'>$i</td>";

    $ncol = count($sheetData[$i]);
    for($j = 1; $j <= $ncol; $j++)
    {
        $col = 64 + $j;
        $colChar = chr($col);

        $ixInputSet = isInputCell($i, $colChar);
        if ($ixInputSet == -1)
        {
            echo "<td>" . $sheetData[$i][$colChar] . "</td>";
        }
        else if ($ixInputSet == -2)
        {
            $nilaiCnt += 1;

            $colName = "nis$nilaiCnt";
            echo "<td><input type='text' readonly style='background-color: #eee' name='$colName' id='$colName' size='15' maxlength='255' value='" . $sheetData[$i][$colChar] . "'></td>";
        }
        else if ($ixInputSet == -3)
        {
            $colName = "nilai$nilaiCnt";
            echo "<td><input type='text' style='background-color: #f9ffc9' name='$colName' id='$colName' size='15' maxlength='255' value='" . $sheetData[$i][$colChar] . "'></td>";
        }
        else if ($ixInputSet == -4)
        {
            $colName = "keterangan$nilaiCnt";
            echo "<td><input type='text' style='background-color: #f9ffc9' name='$colName' id='$colName' size='15' maxlength='255' value='" . $sheetData[$i][$colChar] . "'></td>";
        }
        else
        {
            $colName = $inputSet[$ixInputSet][2];
            $isEnabled = $inputSet[$ixInputSet][3];

            $enabled = !$isEnabled ? "readonly" : "";
            $bgcolor = $isEnabled ? "#f9ffc9" : "#eee";
            echo "<td><input type='text' $enabled style='background-color: $bgcolor' name='$colName' id='$colName' size='15' maxlength='255' value='" . $sheetData[$i][$colChar] . "'></td>";
        }
    }

    echo "</tr>";
}
echo "</table>";

if (count($errmsg) == 0) {
?>
    <br>
    <input type="hidden" name="nnilai" id="nnilai" value="<?= $nilaiCnt ?>">

    <font style="font-size: 14px; font-weight: bold; color: blue;">B. Informasi Pelajaran:</font><br><br>
    <table border="1" cellpadding="5" style="border-width: 1px; border-collapse: collapse;">
    <tr>
        <td width="140"><strong>Pelajaran:</strong></td>
        <td><?= SelectPelajaran(); ?></td>
    </tr>
    <tr>
        <td><strong>Aspek Penilaian:</strong></td>
        <td>
            <span id="divAspek">
            <?= SelectAspek(); ?>
            </span>
        </td>
    </tr>
    <tr>
        <td><strong>Jenis Pengujian:</strong></td>
        <td>
            <span id="divJenisUjian">
            <?= SelectJenisUjian(); ?>
            </span>
        </td>
    </tr>
    <tr>
        <td><strong>RPP:</strong></td>
        <td>
        <span id="divRpp">
        <?= SelectRpp(); ?>
        </span>
        <input type="button" class="but" value=" ... " onclick="addRpp()">
        </td>
    </tr>
    <tr>
        <td><strong>Keterangan:</strong></td>
        <td>
            <textarea name="keterangan"  id="keterangan" style='background-color: #f9ffc9;' rows="2" cols="30"><?=$keterangan?></textarea>
        </td>
    </tr>
    </table>
    <br>
    <input type="button" value="Simpan" class="but" onclick="simpanData()" style="height: 40px; width: 100px;">
<?php
} else {
    echo "<font style='color:red'><strong>PESAN KESALAHAN:</strong></font><br>";
    for($i = 0; $i < count($errmsg); $i++)
    {
        echo "<font style='color:red'><strong>- " . $errmsg[$i] . "</strong></font><br>";
    }
}
?>
</form>

<?php CloseDb(); ?>