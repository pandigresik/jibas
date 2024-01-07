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
require_once("HitungRata.php");

OpenDb();

require_once("impnilai.simpan.validate.php");

$departemen = $_REQUEST['departemen'];
$idpelajaran = $_REQUEST['pelajaran'];
$idkelas = $_REQUEST['idkelas'];
$idaturan = $_REQUEST['idaturan'];
$kodeujian = SafeText($_REQUEST['kodeujian']);
$nnilai = $_REQUEST['nnilai'];

$thn = $_REQUEST['tahun'];
$bln = $_REQUEST['bulan'];
$tgl = $_REQUEST['tanggal'];

$tanggal = "$thn-$bln-$tgl";

// --- MUST HAVE
$idrpp = $_REQUEST['idrpp'];
$keterangan = SafeText($_REQUEST['materi']);

$sql = "SELECT replid 
          FROM semester 
         WHERE departemen = '$departemen' 
           AND aktif = 1";
$idsemester = (int) FetchSingle($sql);

$sql = "SELECT idjenisujian
          FROM jbsakad.aturannhb
         WHERE replid = '".$idaturan."'";
$idjenisujian = (int) FetchSingle($sql);

BeginTrans();
$success = true;

$sql = "SELECT nilaiAU, replid, keterangan 
	      FROM jbsakad.nau 
	     WHERE idkelas = '$idkelas' 
	       AND idsemester = '$idsemester' 
	       AND idaturan = '".$idaturan."'";
//echo "$sql<br>";
$res = QueryDb($sql);
if (mysqli_num_rows($res) > 0)
{
    $sql = "DELETE FROM jbsakad.nau 
		     WHERE idkelas = '$idkelas' 
		       AND idsemester = '$idsemester' 
			   AND idaturan = '".$idaturan."'";
    //echo "$sql<br>";
    QueryDbTrans($sql, $success);
}

$rpp = $idrpp == -1 ? "NULL" : "'$idrpp'";

$sql = "INSERT INTO jbsakad.ujian 
           SET idpelajaran = '$idpelajaran', 
               idkelas = '$idkelas', 
		       idsemester = '$idsemester', 
		       idjenis = '$idjenisujian', 
		       deskripsi = '$keterangan', 
			   tanggal = '$tanggal', 
			   idaturan = '$idaturan', 
			   kode = '$kodeujian',
			   idrpp = $rpp";
//echo "$sql<br>";
QueryDbTrans($sql,$success);

$sql = "SELECT LAST_INSERT_ID()";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$idujian = $row[0];
//echo "idujian = $idujian<br>";

for($i = 1; $i <= $nnilai; $i++)
{
    $param = "nis$i";
    $nis = $_REQUEST[$param];

    $param = "nilai$i";
    $nilai = $_REQUEST[$param];

    $param = "keterangan$i";
    $ket = SafeText($_REQUEST[$param]);

    if ($success)
    {
        $sql = "INSERT INTO jbsakad.nilaiujian 
                   SET nilaiujian = '$nilai', 
                       nis = '$nis', 
                       idujian = '$idujian', 
                       keterangan = '".$ket."'";
        //echo "$sql<br>";
        QueryDbTrans($sql, $success);
    }

    if ($success)
        HitungRataSiswa($idkelas, $idsemester, $idaturan, $nis, $success);
}

if ($success)
    HitungRataKelasUjian($idkelas, $idsemester, $idaturan, $idujian, $success);

if ($success)
{
    echo "<br><font style='font-size: 16px; color: blue;'>Data Nilai Telah Berhasil di Impor!</font>";
    CommitTrans(); // RollbackTrans();
    CloseDb();
}
else
{
    echo "<br><font style='font-size: 16px; color: red;'>Impor Data Nilai Gagal!</font>";
    RollbackTrans();
    CloseDb();
}

/*
echo "SUCCESS = $success<br>";

foreach ($_REQUEST as $key => $value)
{
    echo "$key = $value<br>";
}
http_response_code(200);
*/
?>