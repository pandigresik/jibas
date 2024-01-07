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
$idPayment = $_REQUEST["idpayment"]; // replid Payment Trans
$jBayar = $_REQUEST["jbayar"];
$alasan = $_REQUEST["alasan"];

try
{
    OpenDb();

    BeginTrans();

    $sql = "SELECT p.nis, a.departemen, pt.idtabungan AS iddatatabungan, p.idjurnaltabcust, t.replid,
                   dt.rekkas, dt.rekutang, p.jenistrans, IFNULL(p.idpenerimaanjtt, 0) AS idpenerimaanjtt, 
                   IFNULL(p.idpenerimaaniuran, 0) AS idpenerimaaniuran, IFNULL(p.iddatapenerimaan, 0) AS iddatapenerimaan
              FROM jbsfina.paymenttrans p
             INNER JOIN jbsakad.siswa s ON p.nis = s.nis
             INNER JOIN jbsakad.angkatan a ON s.idangkatan = a.replid
             INNER JOIN jbsfina.paymenttabungan pt ON pt.departemen = a.departemen AND pt.jenis = 2
             INNER JOIN jbsfina.tabungan t ON p.idjurnaltabcust = t.idjurnal
             INNER JOIN jbsfina.datatabungan dt ON t.idtabungan = dt.replid
             WHERE p.replid = $idPayment";
    echo "$sql<br>";
    $res = QueryDbEx($sql);
    if (mysqli_num_rows($res) == 0)
        throw new Exception("Tidak ditemukan data konfigurasi pembayaran siswa");

    $row = mysqli_fetch_row($res);
    Pre($row);

    $nis = $row[0];
    $departemen = $row[1];
    $idDataTabungan = $row[2];
    $idJurnalTabungan = $row[3];
    $idTabungan = $row[4];
    $rekKasTab = $row[5];
    $rekUtangTab = $row[6];
    $jenisTrans = $row[7];
    $idPenerimaanJtt = $row[8];
    $idPenerimaanIuran = $row[9];
    $idDataPenerimaan = $row[10];

    // Cek Saldo
    $sql = "SELECT SUM(kredit) - SUM(debet)
              FROM jbsfina.tabungan
             WHERE nis = '$nis'
               AND idtabungan = '".$idDataTabungan."'";
    echo "$sql<br>";
    $res = QueryDbEx($sql);
    $row = mysqli_fetch_row($res);
    $jSaldo = $row[0];
    echo "JSALDO = $jSaldo<br>";

    $sql = "SELECT debet
              FROM jbsfina.tabungan
             WHERE replid = $idTabungan";
    echo "$sql<br>";
    $res = QueryDbEx($sql);
    $row = mysqli_fetch_row($res);
    $debetAwal = (int)$row[0];
    echo "Debet Awal = $debetAwal<br>";

    if ($jSaldo + $debetAwal < $jBayar)
        throw new Exception("Saldo tabungan tidak mencukupi untuk penarikan!");

    if ($jenisTrans == 1)
    {
        $sql = "SELECT b.nis, b.besar, b.lunas, p.idbesarjtt, s.nama, p.idjurnal, p.jumlah, date_format(p.tanggal, '%d-%m-%Y') as tanggal, 
                       p.keterangan, pn.nama as namapenerimaan, pn.rekkas, pn.rekpendapatan, pn.rekpiutang, pn.info1 AS rekdiskon,
                       p.info1 AS diskon, pn.replid AS idpenerimaan 
                  FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b, jbsakad.siswa s, jbsfina.datapenerimaan pn 
                 WHERE p.replid = '$idPenerimaanJtt' 
                   AND p.idbesarjtt = b.replid 
                   AND b.nis = s.nis 
                   AND b.idpenerimaan = pn.replid";
        echo "$sql<br>";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_array($res);
        Pre($row);

        $nis = $row['nis'];
        $namaSiswa = $row['nama'];
        $idJurnal = $row['idjurnal'];
        $tanggal = $row['tanggal'];
        $keterangan = $row['keterangan'];
        $idPenerimaan = $row['idpenerimaan'];
        $namaPenerimaan = $row['namapenerimaan'];
        $besar = $row['jumlah'];
        $besardiskon = $row['diskon'];
        $idBesarJtt = $row['idbesarjtt'];
        $besarJtt = $row['besar'];
        $lunas = $row['lunas'];
        $rekkas = $row['rekkas'];
        $rekpiutang = $row['rekpiutang'];
        $rekpendapatan = $row['rekpendapatan'];
        $rekdiskon = $row['rekdiskon'];
        $jdiskon = $row['diskon'];
        $jbayar = $besar;
        $jcicilan = $jbayar + $jdiskon;

        $totalCicilan = 0;
        $totalDiskon = 0;
        $sql = "SELECT SUM(jumlah), SUM(info1) 
                  FROM jbsfina.penerimaanjtt 
                 WHERE idbesarjtt = $idBesarJtt 
                   AND replid <> $idPenerimaanJtt";
        echo "$sql<br>";
        $res = QueryDbEx($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $totalCicilan = $row[0];
            $totalDiskon = $row[1];
        }

        if ($totalCicilan + $totalDiskon + $jBayar > $besarJtt)
            throw new Exception("Maaf, pembayaran tidak dapat dilakukan! Jumlah pembayaran cicilan lebih besar daripada bayaran yang harus dilunasi");

        $lunas = 0;
        $ketJurnal = "";
        if ($totalCicilan + $totalDiskon + $jBayar == $besarJtt)
        {
            $ketjurnal = "Pelunasan $namaPenerimaan siswa $namaSiswa ($nis)";
            $lunas = 1;
        }
        else
        {
            $cicilan = 0;
            $sql = "SELECT replid 
                      FROM jbsfina.penerimaanjtt 
                     WHERE idbesarjtt = '$idBesarJtt' 
                     ORDER BY tanggal, replid ASC";
            $res = QueryDb($sql);
            while($row = mysqli_fetch_row($res))
            {
                $cicilan++;
                if ($row[0] == $idPenerimaanJtt)
                    break;
            }
            $ketjurnal = "Pembayaran ke-$cicilan $namaPenerimaan siswa $namaSiswa ($nis)";
            $lunas = 0;
        }

        // Ambil kode rekening dari jurnal bukan dari datapenerimaan
        $rekKas = AmbilKodeRekJurnal($idJurnal, "HARTA", $idPenerimaan);
        $rekPiutang = AmbilKodeRekJurnal($idJurnal, "PIUTANG", $idPenerimaan);
        $rekDiskon = AmbilKodeRekJurnal($idJurnal, "DISKON", $idPenerimaan);

        $sql = "UPDATE jbsfina.penerimaanjtt 
                   SET jumlah = '$jBayar', alasan='$alasan'
                 WHERE replid = '".$idPenerimaanJtt."'";
        echo "$sql<br>";
        $res = QueryDbEx($sql);

        $idJurnal = 0;
        $sql = "SELECT idjurnal 
                  FROM jbsfina.penerimaanjtt 
                 WHERE replid = $idPenerimaanJtt";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $idJurnal = $row[0];

        $sql = "UPDATE jbsfina.jurnal 
                   SET transaksi = '$ketJurnal' 
                 WHERE replid = '".$idJurnal."'";
        echo "$sql<br>";
        $res = QueryDbEx($sql);

        $sql = "UPDATE jbsfina.jurnaldetail 
                   SET debet = '$jBayar' 
                 WHERE idjurnal = '$idJurnal' 
                   AND koderek = '$rekKas' 
                   AND kredit = 0";
        echo "$sql<br>";
        $res = QueryDbEx($sql);

        $sql = "UPDATE jbsfina.jurnaldetail 
                   SET kredit = '$jBayar' 
                 WHERE idjurnal = '$idJurnal' 
                   AND koderek ='$rekPiutang' 
                   AND debet = 0";
        echo "$sql<br>";
        $res = QueryDbEx($sql);

        if ($success)
        {
            $sql = "SELECT COUNT(replid) FROM jurnaldetail WHERE idjurnal='$idjurnal' AND koderek='$rekdiskon'";
            $nJurnalDiskon = FetchSingle($sql);
            if ($nJurnalDiskon == 0 && $jdiskon > 0)
                $sql = "INSERT INTO jurnaldetail SET debet='$jdiskon', idjurnal='$idjurnal', koderek='$rekdiskon', kredit=0";
            else
                $sql = "UPDATE jurnaldetail SET debet='$jdiskon' WHERE idjurnal='$idjurnal' AND koderek='$rekdiskon' AND kredit=0";
            QueryDbTrans($sql, $success);
        }

        if ($success)
        {
            $sql = "SET @DISABLE_TRIGGERS = 1;";
            QueryDb($sql);

            $sql = "UPDATE besarjtt SET lunas=$lunas WHERE replid='$idbesarjtt'";
            QueryDbTrans($sql, $success);

            $sql = "SET @DISABLE_TRIGGERS = NULL;";
            QueryDb($sql);
        }
    }


    $sql = "UPDATE jbsfina.tabungan
               SET alasan = '$alasan',
                   debet = '$jBayar',
                   kredit = '0'
             WHERE replid = $idTabungan";
    echo "$sql<br>";
    QueryDbEx($sql);

    $sql = "UPDATE jbsfina.jurnaldetail
               SET debet = '0', kredit = '$jBayar'
             WHERE idjurnal = '$idJurnalTabungan'
               AND koderek = '".$rekKasTab."'";
    echo "$sql<br>";
    QueryDbEx($sql);

    $sql = "UPDATE jbsfina.jurnaldetail
               SET debet = '$jBayar', kredit = '0'
             WHERE idjurnal = '$idJurnalTabungan'
               AND koderek = '".$rekUtangTab."'";
    echo "$sql<br>";
    QueryDbEx($sql);




    RollbackTrans();
    CloseDb();

    echo "SUCCESS ROLLBACK<br>";
}
catch (Exception $exception)
{
    echo $exception->getMessage();
    CloseDb();
}


function Pre($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}
?>