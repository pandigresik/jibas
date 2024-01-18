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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/errorhandler.php');
require_once('../include/compatibility.php');
require_once('../library/date.func.php');
require_once('../library/logger.php');

$op = $_REQUEST["op"];
if ($op <> "7834682374672834324")
    return;

$vendorId = $_REQUEST["vendorid"];
$departemen = $_REQUEST["departemen"];
$idTahunBuku = $_REQUEST["idtahunbuku"];
$idPenerima = $_REQUEST["idpenerima"];
$keterangan = $_REQUEST["keterangan"];
$nTanggal = $_REQUEST["ntanggal"];

$lsTanggal = [];
$lsTagihan = [];
$stTanggal = "";
$totalTagihan = 0;
$stAllIdPayment = "";
for($i = 1; $i <= $nTanggal; $i++)
{
    $param = "tanggal$i";
    $tanggal = $_REQUEST[$param];
    $lsTanggal[] = $tanggal;

    $param = "tagihan$i";
    $tagihan = $_REQUEST[$param];
    $lsTagihan[] = $tagihan;
    $totalTagihan += $tagihan;

    $param = "replid$i";
    if ($stAllIdPayment != "") $stAllIdPayment .= ",";
    $stAllIdPayment .= $_REQUEST[$param];

    if ($stTanggal <> "") $stTanggal .= ",";
    $stTanggal .= "'$tanggal'";
}

try
{
    OpenDb();
    BeginTrans();

    // Ambil Konfigurasi Pembayaran utk Siswa
    $rekKasVendorSiswa = "---";
    $rekUtangVendorSiswa = "---";
    $sql = "SELECT rekkasvendor, rekutangvendor
              FROM jbsfina.paymenttabungan
             WHERE jenis = 2
               AND departemen = '".$departemen."'";
    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $rekKasVendorSiswa = $row[0];
        $rekUtangVendorSiswa = $row[1];
    }

    // Ambil Konfigurasi Pembayaran utk Pegawai
    // Karena bisa beda rek kas dan rek utang yang digunakan
    $deptPeg = "---";
    $rekKasVendorPegawai = "---";
    $rekUtangVendorPegawai = "---";
    $sql = "SELECT departemen, rekkasvendor, rekutangvendor
              FROM jbsfina.paymenttabungan
             WHERE jenis = 1";
    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $deptPeg = $row[0];
        $rekKasVendorPegawai = $row[1];
        $rekUtangVendorPegawai = $row[2];
    }

    // Ambil jumlah tagihan dari transaksi siswa
    $tagihanSiswa = 0;
    $sql = "SELECT IFNULL(SUM(p.jumlah), 0)
              FROM jbsfina.paymenttrans p 
             WHERE p.jenis = 2 
               AND p.replid IN ($stAllIdPayment)";
    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_row($res))
        $tagihanSiswa = $row[0];

    // Ambil jumlah tagihan dari transaksi pegawai
    $tagihanPegawai = 0;
    $sql = "SELECT IFNULL(SUM(p.jumlah), 0)
              FROM jbsfina.paymenttrans p 
             WHERE p.jenis = 1  
               AND p.replid IN ($stAllIdPayment)";
    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_row($res))
        $tagihanPegawai = $row[0];

    $idPetugas = getIdUser();
    $petugas = getUserName();
    if ($idPetugas == "landlord")
        $idPetugas = "NULL";
    else
        $idPetugas =  "'$idPetugas'";

    //Ambil awalan dan cacah tahunbuku untuk bikin nokas;
    $sql = "SELECT awalan, cacah
              FROM jbsfina.tahunbuku
             WHERE replid = '".$idTahunBuku."'";
    $row = FetchSingleRow($sql);
    $awalan = $row[0];
    $cacah = $row[1];

// Simpan Jurnal untuk pembayaran tagihan dari transaksi Siswa
    $idJurnalSiswa = 0;
    if ($tagihanSiswa <> 0)
    {
        $cacah += 1;
        $noKas = $awalan . rpad($cacah, "0", 6); // Form nomor kas
        
        $transaksi = "Pembayaran refund penerimaan vendor dari pembayaran non tunai siswa";
        $sql = "INSERT INTO jbsfina.jurnal
                   SET idtahunbuku = $idTahunBuku, tanggal = CURDATE(), transaksi = '$transaksi',
                       nokas='$noKas', keterangan = '$keterangan',
                       idpetugas = $idPetugas, petugas = '$petugas', sumber='schoolpay'";
        QueryDbEx($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $res = QueryDbEx($sql);
        if ($row = mysqli_fetch_row($res))
            $idJurnalSiswa = $row[0];

        $sql = "INSERT INTO jbsfina.jurnaldetail 
                   SET idjurnal = $idJurnalSiswa, koderek = '$rekUtangVendorSiswa', debet = $tagihanSiswa";
        QueryDbEx($sql);

        $sql = "INSERT INTO jbsfina.jurnaldetail 
                   SET idjurnal = $idJurnalSiswa, koderek = '$rekKasVendorSiswa', kredit = $tagihanSiswa";
        QueryDbEx($sql);

        $sql = "UPDATE jbsfina.tahunbuku SET cacah = cacah + 1 WHERE replid = $idTahunBuku";
        QueryDbEx($sql);
    }

    // Simpan Jurnal untuk pembayaran tagihan dari transaksi Pegawai
    $idJurnalPegawai = 0;
    if ($tagihanPegawai <> 0)
    {
        $cacah += 1; // Increment cacah
        $noKas = $awalan . rpad($cacah, "0", 6); // Form nomor kas

        $transaksi = "Pembayaran refund penerimaan vendor dari pembayaran non tunai pegawai";
        $sql = "INSERT INTO jbsfina.jurnal
 		           SET idtahunbuku = $idTahunBuku, tanggal = CURDATE(), transaksi = '$transaksi',
         			   nokas='$noKas', keterangan = '$keterangan',
	    		       idpetugas = $idPetugas, petugas = '$petugas', sumber='schoolpay'";
        QueryDbEx($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $res = QueryDbEx($sql);
        if ($row = mysqli_fetch_row($res))
            $idJurnalPegawai = $row[0];

        $sql = "INSERT INTO jbsfina.jurnaldetail 
                   SET idjurnal = $idJurnalPegawai, koderek = '$rekUtangVendorPegawai', debet = $tagihanPegawai";
        QueryDbEx($sql);

        $sql = "INSERT INTO jbsfina.jurnaldetail 
                   SET idjurnal = $idJurnalPegawai, koderek = '$rekKasVendorPegawai', kredit = $tagihanPegawai";
        QueryDbEx($sql);

        $sql = "UPDATE jbsfina.tahunbuku SET cacah = cacah + 1 WHERE replid = $idTahunBuku";
        QueryDbEx($sql);
    }

    //  Simpan di riwayat refund
    $sql = "INSERT INTO jbsfina.refund
               SET idtahunbuku = $idTahunBuku, vendorid = '$vendorId', waktu = NOW(), nip = $idPetugas, 
                   jumlah = $totalTagihan, idpenerima = '$idPenerima', keterangan = '$keterangan', 
                   idjurnalsiswa = $idJurnalSiswa, idjurnalpegawai = $idJurnalPegawai ";
    QueryDbEx($sql);

    $idRefund = 0;
    $sql = "SELECT LAST_INSERT_ID()";
    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_row($res))
        $idRefund = $row[0];

    for($i = 0; $i < count($lsTanggal); $i++)
    {
        $tanggal = $lsTanggal[$i];

        $sql = "INSERT INTO jbsfina.refunddate
                   SET idrefund = $idRefund, tanggal = '".$tanggal."'";
        QueryDbEx($sql);
    }

    // Update paymenttrans yg sudah di refund
    $sql = "UPDATE jbsfina.paymenttrans 
               SET idrefund = $idRefund 
             WHERE replid IN ($stAllIdPayment)";
    QueryDbEx($sql);

    CommitTrans();
    CloseDb();

    echo $idRefund;
    http_response_code(200);
}
catch (Exception)
{
    RollbackTrans();
    CloseDb();

    http_response_code(500);
}
?>