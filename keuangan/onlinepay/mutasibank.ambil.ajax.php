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
               SET departemen = '$departemen', bankno = '$bankNo', jenis = 2, tanggal = '$tglMutasi', 
                   waktu = NOW(), keterangan = '$keterangan', petugas = $idPetugas, berkas = '$buktiTransfer64',
                   adaberkas = $adaBukti, nomormutasi = '".$nomorTransfer."'";
    QueryDbEx($sql);

    $sql = "SELECT LAST_INSERT_ID()";
    $res = QueryDbEx($sql);
    $row = mysqli_fetch_row($res);
    $idMutasi = $row[0];

    $nJurnal = 0;
    $nData = $_REQUEST["ndata"];
    for($i = 1; $i <= $nData; $i++)
    {
        $el = "idpen-$i";
        $idPen = $_REQUEST[$el];

        $el = "pen-$i";
        $pen = $_REQUEST[$el];

        $el = "kate-$i";
        $kate = SafeInput($_REQUEST[$el]);

        $el = "jum-$i";
        $jumlah = $_REQUEST[$el];

        $el = "ket-$i";
        $keterangan = SafeInput($_REQUEST[$el]);

        $noKas = "";
        if ($kate == "DPST" || $kate == "BL")
        {
            $nJurnal += 1;

            $cacah += 1; // Increment cacah
            $noKas = $awalan . rpad($cacah, "0", 6); // Form nomor kas

            $transaksi = "Mutasi pengambilan $pen";
            $idJurnal = OnlinePay_SimpanJurnal($idTahunBuku, $tglMutasi, $transaksi, $noKas, $keterangan, $idPetugas, $petugas, "mutasiambil");
            OnlinePay_SimpanDetailJurnal($idJurnal, "D", $rekPendapatan, $jumlah);
            OnlinePay_SimpanDetailJurnal($idJurnal, "K", $rekKas, $jumlah);
        }

        if ($kate == "JTT" || $kate == "SKR")
        {
            $sql = "INSERT INTO jbsfina.bankmutasidata
                       SET kategori = '$kate', idmutasi = $idMutasi, idpenerimaan = $idPen, idtabungan = 0, idtabunganp = 0,
                           iddeposit = 0, jumlah = $jumlah, keterangan = '$keterangan', nokas = '".$noKas."'";
            QueryDbEx($sql);

            $sql = "INSERT INTO jbsfina.banksaldo (departemen, bankno, kategori, idpenerimaan, idtabungan, idtabunganp, iddeposit, kelompok, saldo, lasttime) 
                    VALUES ('$departemen','$bankNo','$kate', $idPen, 0, 0, 0, 1, $jumlah, NOW())
                        ON DUPLICATE KEY UPDATE saldo = saldo - $jumlah, lasttime = NOW()";
            QueryDbEx($sql);
        }
        else if ($kate == "SISTAB")
        {
            $sql = "INSERT INTO jbsfina.bankmutasidata
                       SET kategori = '$kate', idmutasi = $idMutasi, idpenerimaan = 0, idtabungan = $idPen, idtabunganp = 0,
                           iddeposit = 0, jumlah = $jumlah, keterangan = '$keterangan', nokas = '".$noKas."'";
            QueryDbEx($sql);

            $sql = "INSERT INTO jbsfina.banksaldo (departemen, bankno, kategori, idpenerimaan, idtabungan, idtabunganp, iddeposit, kelompok, saldo, lasttime) 
                    VALUES ('$departemen','$bankNo','$kate', 0, $idPen, 0, 0, 1, $jumlah, NOW())
                        ON DUPLICATE KEY UPDATE saldo = saldo - $jumlah, lasttime = NOW()";
            QueryDbEx($sql);
        }
        else if ($kate == "PEGTAB")
        {
            $sql = "INSERT INTO jbsfina.bankmutasidata
                       SET kategori = '$kate', idmutasi = $idMutasi, idpenerimaan = 0, idtabungan = 0, idtabunganp = $idPen,
                           iddeposit = 0, jumlah = $jumlah, keterangan = '$keterangan', nokas = '".$noKas."'";
            QueryDbEx($sql);

            $sql = "INSERT INTO jbsfina.banksaldo (departemen, bankno, kategori, idpenerimaan, idtabungan, idtabunganp, iddeposit, kelompok, saldo, lasttime) 
                    VALUES ('$departemen','$bankNo','$kate', 0, 0, $idPen, 0, 1, $jumlah, NOW())
                        ON DUPLICATE KEY UPDATE saldo = saldo - $jumlah, lasttime = NOW()";
            QueryDbEx($sql);
        }
        else if ($kate == "DPST")
        {
            $sql = "INSERT INTO jbsfina.bankmutasidata
                       SET kategori = '$kate', idmutasi = $idMutasi, idpenerimaan = 0, idtabungan = 0, idtabunganp = 0,
                           iddeposit = $idPen, jumlah = $jumlah, keterangan = '$keterangan', nokas = '".$noKas."'";
            QueryDbEx($sql);

            $sql = "INSERT INTO jbsfina.banksaldo (departemen, bankno, kategori, idpenerimaan, idtabungan, idtabunganp, iddeposit, kelompok, saldo, lasttime) 
                    VALUES ('$departemen','$bankNo','$kate', 0, 0, 0, $idPen, 1, $jumlah, NOW())
                        ON DUPLICATE KEY UPDATE saldo = saldo - $jumlah, lasttime = NOW()";
            QueryDbEx($sql);
        }
    }

    if ($nJurnal != 0)
    {
        $sql = "UPDATE jbsfina.tahunbuku 
                   SET cacah = $cacah 
                 WHERE replid = $idTahunBuku";
        QueryDbEx($sql);
    }

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