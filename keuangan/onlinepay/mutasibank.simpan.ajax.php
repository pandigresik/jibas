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
require_once("../library/logger.php");
require_once('onlinepay.util.func.php');
require_once('onlinepay.jurnal.php');

try
{
    OpenDb();



    //$log = new Logger();
    //$log->Log("EXIST " . isset($_FILES["buktitransfer"]));
    //$log->Log("SIZE " . $_FILES["buktitransfer"]["size"]);
    //$log->Log("NAME " . $_FILES["buktitransfer"]["name"]);
    //$log->Log("PATH " . $_FILES["buktitransfer"]["tmp_name"]);

    $departemen = $_REQUEST["departemen"];
    $bankNo = $_REQUEST["bankno"];
    $tglMutasi = $_REQUEST["tglmutasi"];
    $buktiValid = $_REQUEST["buktivalid"];
    $nomorTransfer = $_REQUEST["nomortransfer"];
    $buktiTransfer64 = "";
    $adaBukti = 0;
    if ($buktiValid == 1)
    {
        $adaBukti = 1;
        $buktiTransfer64 = base64_encode(file_get_contents($_FILES["buktitransfer"]["tmp_name"]));
    }
    $keterangan = SafeInput($_REQUEST["keterangan"]);

    //$log->Log("64 " . $buktiTransfer64);
    //$log->Close();

    $idTahunBuku = 0;
    $awalan = "";
    $cacah = 0;
    $sql = "SELECT replid, awalan, cacah
              FROM jbsfina.tahunbuku
             WHERE departemen = '$departemen'
               AND aktif = 1";
    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $idTahunBuku = $row[0];
        $awalan = $row[1];
        $cacah = $row[2];
    }
    else
    {
        echo "[\"-1\",\"ERROR\",\"Tidak ditemukan data tahun buku\"]";
        return;
    }

    $rekKas = "";
    $rekPendapatan = "";
    $sql = "SELECT rekkas, rekpendapatan 
              FROM jbsfina.bank
             WHERE bankno = '".$bankNo."'";
    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $rekKas = $row[0];
        $rekPendapatan = $row[1];
    }
    else
    {
        echo "[\"-1\",\"ERROR\",\"Tidak ditemukan data rekening bank\"]";
        return;
    }

    $idPetugas = getLevel() == 0 ? "NULL" : "'" . getIdUser() . "'";
    $petugas = getUserName();

    BeginTrans();

    $sql = "INSERT INTO jbsfina.bankmutasi
               SET departemen = '$departemen', bankno = '$bankNo', jenis = 1, tanggal = '$tglMutasi', 
                   waktu = NOW(), keterangan = '$keterangan', petugas = $idPetugas, berkas = '$buktiTransfer64',
                   adaberkas = $adaBukti, nomormutasi = '".$nomorTransfer."'";
    QueryDbEx($sql);

    $sql = "SELECT LAST_INSERT_ID()";
    $res = QueryDbEx($sql);
    $row = mysqli_fetch_row($res);
    $idMutasi = $row[0];

    $nData = $_REQUEST["ndata"];
    for($i = 1; $i <= $nData; $i++)
    {
        $el = "iddeposit-$i";
        $idDeposit = $_REQUEST[$el];

        $el = "deposit-$i";
        $deposit = SafeInput($_REQUEST[$el]);

        $el = "jum-$i";
        $jumlah = $_REQUEST[$el];

        $el = "ket-$i";
        $keterangan = SafeInput($_REQUEST[$el]);

        $cacah += 1; // Increment cacah
        $noKas = $awalan . rpad($cacah, "0", 6); // Form nomor kas

        $transaksi = "Mutasi simpan $deposit";
        $idJurnal = OnlinePay_SimpanJurnal($idTahunBuku, $tglMutasi, $transaksi, $noKas, $keterangan, $idPetugas, $petugas, "mutasisimpan");
        OnlinePay_SimpanDetailJurnal($idJurnal, "K", $rekKas, $jumlah);
        OnlinePay_SimpanDetailJurnal($idJurnal, "D", $rekPendapatan, $jumlah);

        $sql = "INSERT INTO jbsfina.bankmutasidata
                   SET kategori = 'DPST', idmutasi = $idMutasi, idpenerimaan = 0, idtabungan = 0, idtabunganp = 0,
                       iddeposit = $idDeposit, jumlah = $jumlah, keterangan = '$keterangan', nokas = '".$noKas."'";
        QueryDbEx($sql);

        $sql = "INSERT INTO jbsfina.banksaldo (departemen, bankno, kategori, idpenerimaan, idtabungan, idtabunganp, iddeposit, kelompok, saldo, lasttime) 
                VALUES ('$departemen','$bankNo','DPST', 0, 0, 0, $idDeposit, 1, $jumlah, NOW())
                    ON DUPLICATE KEY 
                UPDATE saldo = saldo + $jumlah, lasttime = NOW()";
        QueryDbEx($sql);
    }

    $sql = "UPDATE jbsfina.tahunbuku 
               SET cacah = $cacah 
             WHERE replid = $idTahunBuku";
    QueryDbEx($sql);

    CommitTrans();

    echo "[\"1\",\"OK\"]";
}
catch (Exception $ex)
{
    RollbackTrans();

    $msg = $ex->getMessage();
    echo "[\"-1\",\"ERROR\",\"$msg\"]";
}
finally
{
    CloseDb();
}

?>