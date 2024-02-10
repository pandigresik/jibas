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
include_once '../../vendor/autoload.php';
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../sessionchecker.php');
require_once('expnilai.content.func.php');

OpenDb();

ReadParam();

// Create new PHPExcel object
$objPHPExcel = new PhpOffice\PhpSpreadsheet\Spreadsheet();

// Set document properties
$objPHPExcel->getProperties()->setCreator("JIBAS Akademik")
    ->setLastModifiedBy("JIBAS Akademik")
    ->setTitle("Form Nilai Pelajaran")
    ->setSubject("Form Nilai Pelajaran")
    ->setDescription("Form Nilai Pelajaran")
    ->setKeywords("Form Nilai Pelajaran")
    ->setCategory("Form Nilai Pelajaran");

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B1', 'FORM NILAI')
    ->setCellValue('B3', 'Departemen (*):')
    ->setCellValue('C3', $departemen)
    ->setCellValue('B4', 'Kelas:')
    ->setCellValue('C4', $nmkelas)
    ->setCellValue('D4', 'Tingkat:')
    ->setCellValue('E4', $nmtingkat)
    ->setCellValue('F4', 'Id Kelas (*):')
    ->setCellValue('G4', $kelas)
    ->setCellValue('B5', 'NIP Guru (*):')
    ->setCellValue('D5', 'Nama Guru:')
    ->setCellValue('B6', 'Pelajaran:')
    ->setCellValue('D6', 'Aspek:')
    ->setCellValue('F6', 'Jenis Ujian:')
    ->setCellValue('B7', 'Kode Ujian (*):')
    ->setCellValue('B8', 'Tanggal (*):')
    ->setCellValue('D8', 'Bulan (*):')
    ->setCellValue('F8', 'Tahun (*):')
    ->setCellValue('B9', 'RPP:')
    ->setCellValue('B10', 'Materi (*):')
    ->setCellValue('B11', 'Keterangan:');

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A13', 'No')
    ->setCellValue('B13', 'NIS (*)')
    ->setCellValue('C13', 'Nama')
    ->setCellValue('D13', 'Nilai (*)')
    ->setCellValue('E13', 'Keterangan');


$sql = "SELECT nis, nama FROM siswa WHERE idkelas='$kelas' AND aktif=1 AND alumni=0 ORDER BY nama ASC";
$res2 = QueryDb($sql);

$no = 0;
$row = 13;
while($row2 = mysqli_fetch_array($res2))
{
    $no += 1;
    $row += 1;

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A$row", $no)
        ->setCellValue("B$row", $row2['nis'])
        ->setCellValue("C$row", $row2['nama']);
}
CloseDb();

/*
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,false,false);
echo "<pre>";
var_dump($sheetData);
echo "</pre>";
*/
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>
