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
function ShowSelectPetugasTf($id)
{
    $sql = "SELECT h.login, p.nama
              FROM jbsuser.hakakses h, jbssdm.pegawai p
             WHERE h.login = p.nip
               AND p.aktif = 1
               AND h.modul = 'KEUANGAN'
             ORDER BY p.nama";
    $res = QueryDb($sql);

    echo "<select id='$id' class='inputbox' style='width: 250px'>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
}

function ShowSelectTabungan()
{
    global $departemen;

    $sql = "SELECT replid, nama
              FROM jbsfina.datatabungan
             WHERE departemen = '$departemen' 
               AND aktif = 1
             ORDER BY nama";
    $res = QueryDb($sql);

    echo "<select id='seltabungan' class='inputbox' style='width: 250px'>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
}

function SimpanTransferBalik()
{
    try
    {
        $idPgTransLebih = $_REQUEST["idpgtranslebih"];
        $tanggal = $_REQUEST["tanggal"];
        $keterangan = SafeInput($_REQUEST["keterangan"]);
        $idPetugas = getLevel() == 0 ? "NULL" : "'" . getIdUser() . "'";
        $petugas = getUserName();

        $nomorTf = $_REQUEST["nomortf"];

        $buktiTf64 = "";
        $adaBukti = 0;
        $buktiTfValid = $_REQUEST["buktitfvalid"];
        if ($buktiTfValid == 1)
        {
            $adaBukti = 1;
            $buktiTf64 = base64_encode(file_get_contents($_FILES["buktitf"]["tmp_name"]));
        }

        OpenDb();

        $sql = "SELECT p.metode, p.nomor, p.jlebihtrans, p.jlebihsisa, p.bankno, b.departemen, b.rekkas, b.rekpendapatan, b.aktif
                  FROM jbsfina.pgtranslebih p, jbsfina.bank b
                 WHERE p.bankno = b.bankno
                   AND p.id = $idPgTransLebih";
        //$log->Log($sql);
        $res = QueryDbEx($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "[-1,\"ERROR: data transaksi tidak ditemukan\"]";
            return;
        }

        $row = mysqli_fetch_array($res);
        $metode = $row["metode"];
        $nomor = $row["nomor"];
        $jLebihTrans = $row["jlebihtrans"];
        $jLebihSisa = $row["jlebihsisa"];
        $jLebih = $jLebihTrans + $jLebihSisa;
        $bankNo = $row["bankno"];
        $departemen = $row["departemen"];
        $rekKasBank = $row["rekkas"];
        $rekPendapatanBank = $row["rekpendapatan"];
        $bankAktif = $row["aktif"];

        if ($bankAktif == 0)
        {
            echo "[-1,\"ERROR: status bank tidak aktif\"]";
            return;
        }

        $sql = "SELECT replid, awalan, cacah
                  FROM jbsfina.tahunbuku
                 WHERE departemen = '$departemen'
                   AND aktif = 1";
        $res = QueryDbEx($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "[-1,\"ERROR: tidak ditemukan data tahun buku\"]";
            return;
        }

        $row = mysqli_fetch_row($res);
        $idTahunBuku = $row[0];
        $awalan = $row[1];
        $cacah = $row[2];

        BeginTrans();

        $rp = FormatRupiah($jLebih);
        $transaksi = "Refund kelebihan transfer transaksi $nomor sebesar $rp";

        // -- [1] Mutasi pengambilan dana dari Bank ------------------
        $cacah += 1; // Increment cacah
        $noKasBank = $awalan . rpad($cacah, "0", 6); // Form nomor kas

        $idJurnal = OnlinePay_SimpanJurnal($idTahunBuku, $tanggal, $transaksi, $noKasBank, $keterangan, $idPetugas, $petugas, "lebihtransferrefund");
        OnlinePay_SimpanDetailJurnal($idJurnal, "K", $rekKasBank, $jLebih);
        OnlinePay_SimpanDetailJurnal($idJurnal, "D", $rekPendapatanBank, $jLebih);

        $sql = "UPDATE tahunbuku 
                   SET cacah = cacah + 1 
                 WHERE replid = $idTahunBuku";
        QueryDbEx($sql);

        $sql = "INSERT INTO jbsfina.bankmutasi
                   SET departemen = '$departemen', bankno = '$bankNo', jenis = 2, tanggal = '$tanggal', 
                       waktu = NOW(), keterangan = '$keterangan', petugas = $idPetugas, berkas = '$buktiTf64',
                       adaberkas = $adaBukti, nomormutasi = '".$nomorTf."'";
        QueryDbEx($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $idMutasi = $row[0];

        $sql = "INSERT INTO jbsfina.bankmutasidata
                       SET kategori = 'LB', idmutasi = $idMutasi, idpenerimaan = 0, idtabungan = 0, idtabunganp = 0,
                           iddeposit = 0, jumlah = $jLebih, keterangan = '$keterangan', nokas = '".$noKasBank."'";
        QueryDbEx($sql);

        $sql = "UPDATE jbsfina.banksaldo
                   SET saldo = saldo - $jLebih, lasttime = NOW()
                 WHERE departemen = '$departemen'
                   AND bankno = '$bankNo'
                   AND kategori = 'LB'";
        QueryDbEx($sql);

        $sql = "UPDATE jbsfina.pgtranslebih
                   SET prstatus = 1, prmetode = 2, prpetugas = '$petugas', prwaktu = NOW(), prket = '$keterangan',
                       prjurnalbank = '$noKasBank', prpetugastf = '$petugas', pridmutasi = '$idMutasi'
                 WHERE id = $idPgTransLebih";
        QueryDbEx($sql);

        CommitTrans();
        //RollbackTrans();

        echo "[1,\"OK\"]";
    }
    catch(Exception $ex)
    {
        RollbackTrans();

        $msg = $ex->getMessage();
        //$log->Log($msg);
        echo "[-1,\"$msg\"]";
    }
    finally
    {
        CloseDb();
        //$log->Close();
    }
}

function SimpanTabungan()
{
/*
    $log = new Logger();
    foreach($_REQUEST AS $k => $v)
    {
        $log->Log($k . " = " . $v);
    }
    $log->Close();

    echo "[1,\"OK\"]";
    return;
*/
    //$log = new Logger();
    try
    {
        $idPgTransLebih = $_REQUEST["idpgtranslebih"];
        $idTabungan = $_REQUEST["idtabungan"];
        $tanggal = $_REQUEST["tanggal"];
        $keterangan = SafeInput($_REQUEST["keterangan"]);
        $idPetugas = getLevel() == 0 ? "NULL" : "'" . getIdUser() . "'";
        $petugas = getUserName();

        $nomorTf = $_REQUEST["nomortf"];

        $buktiTf64 = "";
        $adaBukti = 0;
        $buktiTfValid = $_REQUEST["buktitfvalid"];
        if ($buktiTfValid == 1)
        {
            $adaBukti = 1;
            $buktiTf64 = base64_encode(file_get_contents($_FILES["buktitf"]["tmp_name"]));
        }

        OpenDb();

        $sql = "SELECT p.metode, p.nomor, p.jlebihtrans, p.jlebihsisa, p.bankno, b.departemen, b.rekkas, b.rekpendapatan, b.aktif
                  FROM jbsfina.pgtranslebih p, jbsfina.bank b
                 WHERE p.bankno = b.bankno
                   AND p.id = $idPgTransLebih";
        //$log->Log($sql);
        $res = QueryDbEx($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "[-1,\"ERROR: data transaksi tidak ditemukan\"]";
            return;
        }

        $row = mysqli_fetch_array($res);
        $metode = $row["metode"];
        $nomor = $row["nomor"];
        $jLebihTrans = $row["jlebihtrans"];
        $jLebihSisa = $row["jlebihsisa"];
        $jLebih = $jLebihTrans + $jLebihSisa;
        $bankNo = $row["bankno"];
        $departemen = $row["departemen"];
        $rekKasBank = $row["rekkas"];
        $rekPendapatanBank = $row["rekpendapatan"];
        $bankAktif = $row["aktif"];

        if ($bankAktif == 0)
        {
            echo "[-1,\"ERROR: status bank tidak aktif\"]";
            return;
        }

        $sql = "SELECT p.nis, s.nama
                  FROM jbsfina.pgtrans p
                 INNER JOIN jbsakad.siswa s ON p.nis = s.nis
                 WHERE p.nomor = '".$nomor."'";
        $res = QueryDbEx($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "[-1,\"ERROR: tidak ditemukan data siswa\"]";
            return;
        }
        $row = mysqli_fetch_row($res);
        $nis = $row[0];
        $namaSiswa = $row[1];

        $sql = "SELECT replid, awalan, cacah
                  FROM jbsfina.tahunbuku
                 WHERE departemen = '$departemen'
                   AND aktif = 1";
        $res = QueryDbEx($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "[-1,\"ERROR: tidak ditemukan data tahun buku\"]";
            return;
        }

        $row = mysqli_fetch_row($res);
        $idTahunBuku = $row[0];
        $awalan = $row[1];
        $cacah = $row[2];

        $sql = "SELECT rekkas, rekutang, aktif, nama
                  FROM jbsfina.datatabungan
                 WHERE replid = $idTabungan";
        $res = QueryDbEx($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "[-1,\"ERROR: tidak ditemukan data tabungan\"]";
            return;
        }

        $row = mysqli_fetch_row($res);
        $rekKasTab = $row[0];
        $rekUtangTab = $row[1];
        $aktifTab = $row[2];
        $namaTab = $row[3];

        if ($aktifTab == 0)
        {
            echo "[-1,\"ERROR: status tabungan tidak aktif\"]";
            return;
        }

        BeginTrans();

        $rp = FormatRupiah($jLebih);
        $transaksi = "Pengembalian kelebihan transfer transaksi $nomor sebesar $rp ke tabungan $namaTab";

        // -- [1] Mutasi Pengambilan Dana dari Bank
        $cacah += 1; // Increment cacah
        $noKasBank = $awalan . rpad($cacah, "0", 6); // Form nomor kas

        $idJurnal = OnlinePay_SimpanJurnal($idTahunBuku, $tanggal, $transaksi, $noKasBank, $keterangan, $idPetugas, $petugas, "lebihtransfertab");
        OnlinePay_SimpanDetailJurnal($idJurnal, "K", $rekKasBank, $jLebih);
        OnlinePay_SimpanDetailJurnal($idJurnal, "D", $rekPendapatanBank, $jLebih);

        $sql = "INSERT INTO jbsfina.bankmutasi
                   SET departemen = '$departemen', bankno = '$bankNo', jenis = 2, tanggal = '$tanggal', 
                       waktu = NOW(), keterangan = '$keterangan', petugas = $idPetugas, berkas = '$buktiTf64',
                       adaberkas = $adaBukti, nomormutasi = '".$nomorTf."'";
        QueryDbEx($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $idMutasi = $row[0];

        $sql = "INSERT INTO jbsfina.bankmutasidata
                   SET kategori = 'LB', idmutasi = $idMutasi, idpenerimaan = 0, idtabungan = 0, idtabunganp = 0,
                       iddeposit = 0, jumlah = $jLebih, keterangan = '$keterangan', nokas = '".$noKasBank."'";
        QueryDbEx($sql);

        $sql = "UPDATE jbsfina.banksaldo
                   SET saldo = saldo - $jLebih, lasttime = NOW()
                 WHERE departemen = '$departemen'
                   AND bankno = '$bankNo'
                   AND kategori = 'LB'";
        QueryDbEx($sql);

        // --- [2] Transaksi setoran tabungan
        $cacah += 1; // Increment cacah
        $noKasTab = $awalan . rpad($cacah, "0", 6); // Form nomor kas

        $idJurnal = OnlinePay_SimpanJurnal($idTahunBuku, $tanggal, $transaksi, $noKasTab, "", $idPetugas, $petugas, "setorantabungan");
        OnlinePay_SimpanDetailJurnal($idJurnal, "D", $rekKasTab, $jLebih);
        OnlinePay_SimpanDetailJurnal($idJurnal, "K", $rekUtangTab, $jLebih);

        $sql = "UPDATE tahunbuku 
                   SET cacah = cacah + 2 
                 WHERE replid = $idTahunBuku";
        QueryDbEx($sql);

        // simpan ke tabel tabungan
        $sql = "INSERT INTO tabungan
                   SET nis = '$nis', idtabungan='$idTabungan', debet='0', kredit='$jLebih',
                       keterangan = '$transaksi', 
                       petugas = '$petugas', idjurnal = '$idJurnal', tanggal = NOW()";
        //$log->Log($sql);
        QueryDbEx($sql);

        $sql = "UPDATE jbsfina.pgtranslebih
                   SET prstatus = 1, prmetode = 1, prpetugas = '$petugas', prwaktu = NOW(), prket = '$keterangan',
                       prjurnalbank = '$noKasBank', pridtabungan = '$idTabungan', prnamatabungan = '$namaTab', 
                       prjurnaltabungan = '$noKasTab', pridmutasi = '$idMutasi'
                 WHERE id = $idPgTransLebih";
        //$log->Log($sql);
        QueryDbEx($sql);

        $ketsms = "Setoran Tabungan $namaTab";
        CreateSMSTabungan('SISTAB',
            $departemen, $nis, $namaSiswa,
            RegularDateFormat($tanggal),
            FormatRupiah($jLebih),
            FormatRupiah($jLebih),
            $ketsms,
            $transaksi,
            $success);

        //RollbackTrans();
        CommitTrans();

        echo "[1,\"OK\"]";
    }
    catch(Exception $ex)
    {
        RollbackTrans();

        $msg = $ex->getMessage();
        //$log->Log($msg);
        echo "[-1,\"$msg\"]";
    }
    finally
    {
        CloseDb();
        //$log->Close();
    }



}
?>