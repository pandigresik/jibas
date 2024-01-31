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
function ShowSelectDepartemen()
{
    global $departemen;

    echo "<select id='departemen' name='departemen' class='inputbox' style='width: 250px' onchange='changeDep()'>";
    $dep = getDepartemen(getAccess());
    foreach($dep as $value)
    {
        if ($departemen == "") $departemen = $value;
        $sel = $departemen == $value ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function ShowSelectTahunBuku()
{
    global $departemen;
    global $idTahunBuku, $tahunBuku;

    echo "<select id='tahunbuku' name='tahunbuku' class='inputbox' style='width: 250px'>";
    $sql = "SELECT replid, tahunbuku, aktif FROM jbsfina.tahunbuku WHERE departemen = '$departemen' ORDER BY aktif DESC, replid DESC";
    $res = QueryDb($sql);
    while($row = mysql_fetch_row($res))
    {
        if ($idTahunBuku == "")
        {
            $idTahunBuku = $row[0];
            $tahunBuku = $row[1];
        }

        $aktif = "";
        if ($row[2] == "1")
            $aktif = " (A)";

        echo "<option value='$row[0]'>$row[1] $aktif</option>";
    }
    echo "</select>";
}

function ShowSelectTingkat()
{
    global $departemen;
    global $idTingkat, $tingkat;

    echo "<select id='tingkat' name='tingkat' class='inputbox' style='width: 250px' onchange='changeTingkat()'>";
    $sql = "SELECT replid, tingkat FROM jbsakad.tingkat WHERE departemen = '$departemen' AND aktif = 1 ORDER BY urutan";
    $res = QueryDb($sql);
    while($row = mysql_fetch_row($res))
    {
        if ($idTingkat == "")
        {
            $idTingkat = $row[0];
            $tingkat = $row[1];
        }
        echo "<option value='$row[0]'>$row[1]</option>";
    }
    echo "</select>";
}

function ShowTableKelas()
{
    global $idTingkat, $departemen;

    $sql = "SELECT k.replid, k.kelas
              FROM jbsakad.kelas k, jbsakad.tahunajaran ta
             WHERE k.idtahunajaran = ta.replid
               AND k.idtingkat = $idTingkat
               AND ta.departemen = '$departemen'
               AND ta.aktif = 1
               AND k.aktif = 1
             ORDER BY k.kelas";
    $res = QueryDb($sql);
    $no = 0;

    echo "<table border='0' cellpadding='5' cellspacing='0'>";
    while($row = mysql_fetch_row($res))
    {
        $no += 1;

        echo "<tr>";
        echo "<td width='30' align='center'>";
        echo "<input type='checkbox' id='chkelas$no' name='chkelas$no'>";
        echo "</td>";
        echo "<td width='450' align='left'>";
        echo $row[1];
        echo "<input type='hidden' id='idkelas$no' name='idkelas$no' value='$row[0]'>";
        echo "<input type='hidden' id='kelas$no' name='kelas$no' value='$row[1]'>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<input type='hidden' id='nkelas' name='nkelas' value='$no'>";

}

function ShowTableIuran()
{
    global $departemen;

    $sql = "SELECT replid, nama 
              FROM jbsfina.datapenerimaan 
             WHERE aktif = 1 
               AND idkategori = 'JTT' 
               AND departemen = '$departemen' 
             ORDER BY nama DESC";
    $res = QueryDb($sql);
    $no = 0;

    echo "<table border='0' cellpadding='5' cellspacing='0' id='tabIuran'>";
    echo "<tr>";
    echo "<td width='30' align='center' class='header'>&nbsp;</td>";
    echo "<td width='250' align='left' class='header'>Iuran</td>";
    echo "<td width='250' align='left' class='header'>Diskon</td>";
    echo "</tr>";
    while($row = mysql_fetch_row($res))
    {
        $no += 1;

        echo "<tr>";
        echo "<td align='center'>";
        echo "<input type='checkbox' id='chiuran$no' name='chiuran$no' onclick='onCheckIuran($no)'>";
        echo "</td>";
        echo "<td align='left'>";
        echo $row[1];
        echo "<input type='hidden' id='idiuran$no' name='idiuran$no' value='$row[0]'>";
        echo "<input type='hidden' id='iuran$no' name='iuran$no' value='$row[1]'";
        echo "</td>";
        echo "<td align='left'>";
        $elDiskon = "diskon$no";
        echo "<input type='text' id='$elDiskon' name='$elDiskon' value='Rp 0' class='inputbox' style='width: 180px ' disabled='disabled' onfocus=\"unformatRupiah('$elDiskon')\" onblur=\"formatRupiah('$elDiskon')\">";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<input type='hidden' id='niuran' name='niuran' value='$no'>";
}

function ShowSelectBulan()
{
    echo "<select id='bulan' name='bulan' class='inputbox' style='width: 150px'>";
    for($bln = 1; $bln <= 12; $bln++)
    {
        $sel = $bln == date('n') ? "selected" : "";
        $nama = NamaBulan($bln);
        echo "<option value='$bln' $sel>$nama</option>";
    }
    echo "</select>";
}

function ShowSelectTahun()
{
    echo "<select id='tahun' name='tahun' class='inputbox' style='width: 100px'>";

    $currThn = date('Y');
    for($thn = $currThn - 1; $thn <= $currThn + 1; $thn++)
    {
        $sel = $thn == $currThn ? "selected" : "";
        echo "<option value='$thn' $sel>$thn</option>";
    }
    echo "</select>";
}

function createJsonReturn($status, $message, $data)
{
    $ret = array($status, $message, $data);
    return json_encode($ret);
}

function CreateInvoice()
{
    global $PG_SERVICE_FEE;
    //$log = new Logger();

    try
    {
        OpenDb();

        BeginTrans();

        $dept = $_REQUEST["dept"];
        $idTahunBuku = $_REQUEST["idtahunbuku"];
        $tahunBuku = $_REQUEST["tahunbuku"];
        $idTingkat = $_REQUEST["idtingkat"];
        $tingkat = $_REQUEST["tingkat"];
        $stIdKelas = $_REQUEST["idkelas"];
        $stKelas = $_REQUEST["kelas"];
        $stSkipSiswa = $_REQUEST["skiplist"];
        $stIdIuran = $_REQUEST["idiuran"];
        $stIuran = $_REQUEST["iuran"];
        $stDiskon = $_REQUEST["diskon"];
        $bulan = $_REQUEST["bulan"];
        $namaBulan = NamaBulan($bulan);
        $tahun = $_REQUEST["tahun"];
        $keterangan = $_REQUEST["keterangan"];
        $sendNotif = $_REQUEST["sendnotif"];
        $skipAlreadyPaid = $_REQUEST["skipalreadypaid"];

        $bulanTahunTagihan  = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $bulanTahunTagihan .= substr($tahun, 2, 2);

        /*
        // ----- ambil tahun buku
        $idTahunBuku = "0";
        $tahunBuku = "";
        $sql = "SELECT replid, tahunbuku
                  FROM jbsfina.tahunbuku
                 WHERE departemen = '$dept'
                   AND aktif = 1";
        $res = QueryDbEx($sql);
        if ($row = mysql_fetch_row($res))
        {
            $idTahunBuku = $row[0];
            $tahunBuku = $row[1];
        }
        */

        // ----- ambil format nomor tagihan
        $awalanNoTagihan = "";
        $sql = "SELECT awalan 
                  FROM jbsfina.formatnomortagihan
                 WHERE departemen = '$dept'";
        $res = QueryDbEx($sql);
        if ($row = mysql_fetch_row($res))
        {
            $awalanNoTagihan = $row[0];
        }
        else
        {
            $sql = "SELECT replid
                      FROM jbsakad.departemen 
                     WHERE departemen = '$dept'";
            $res = QueryDbEx($sql);
            if ($row = mysql_fetch_row($res))
            {
                $awalanNoTagihan = $row[0];

                $sql = "INSERT INTO jbsfina.formatnomortagihan
                           SET awalan = '$awalanNoTagihan', departemen = '$dept', issync = 0";
                QueryDbEx($sql);
            }
        }

        // ---- ambil pesan notifikasi tagihan
        $pesanNotifikasiTagihan = "";
        $sql = "SELECT pesan 
                  FROM jbsfina.formatpesanpg
                 WHERE departemen = '$dept'
                   AND kelompok = 'TAGIHAN'";
        $res = QueryDbEx($sql);
        if ($row = mysql_fetch_row($res))
        {
            $pesanNotifikasiTagihan = $row[0];
        }
        else
        {
            $pesanNotifikasiTagihan = "Kami informasikan {NAMA} {NIS} memiliki tagihan sebesar {JUMLAH} untuk {IURAN} bulan {BULAN} {TAHUN}";

            $sql = "INSERT INTO jbsfina.formatpesanpg
                       SET pesan = '$pesanNotifikasiTagihan', departemen = '$dept', kelompok = 'TAGIHAN', issync = 0";
            QueryDbEx($sql);
        }

        // ------- ambil counter tagihan
        $counterTagihan = 0;
        $sql = "SELECT counter
                  FROM jbsfina.tagihancount
                 WHERE departemen = '$dept'
                   AND bulan = $bulan
                   AND tahun = $tahun";
        $res = QueryDbEx($sql);
        if ($row = mysql_fetch_row($res))
        {
            $counterTagihan = $row[0];
        }
        else
        {
            $sql = "INSERT INTO jbsfina.tagihancount
                       SET departemen = '$dept', bulan = $bulan, tahun = $tahun, counter = 0";
            QueryDbEx($sql);
        }

        // ------- ambil counter tagihan set --- 2023-08-02
        $counterTagihanSet = 0;
        $sql = "SELECT counter
                  FROM jbsfina.tagihansetcount
                 WHERE departemen = '$dept'
                   AND bulan = $bulan
                   AND tahun = $tahun";
        $res = QueryDbEx($sql);
        if ($row = mysql_fetch_row($res))
        {
            $counterTagihanSet = $row[0];
        }
        else
        {
            $sql = "INSERT INTO jbsfina.tagihansetcount
                       SET departemen = '$dept', bulan = $bulan, tahun = $tahun, counter = 0";
            QueryDbEx($sql);
        }

        // ---- ambil data siswa
        $lsSiswa = array();
        $sql = "SELECT nis, nama 
                  FROM jbsakad.siswa
                 WHERE idkelas IN ($stIdKelas)
                   AND aktif = 1
                 ORDER BY nama";
        $res = QueryDbEx($sql);
        while($row = mysql_fetch_row($res))
        {
            $lsSiswa[] = array($row[0], $row[1]);
        }
        $nSiswa = count($lsSiswa);
        //$log->Log("NSiswa $nSiswa");

        if ($nSiswa == 0)
        {
            RollbackTrans();
            return createJsonReturn(0, "Tidak ditemukan data siswa", "");
        }

        // --- buat tagihan set
        // change on 2023-03-31
        $counterTagihanSet += 1;
        $counterSet = str_pad($counterTagihanSet, 4, '0', STR_PAD_LEFT);
        $noTagihanSet = "TS.". $awalanNoTagihan . $bulanTahunTagihan . "." . $counterSet;
        $namaTagihan = "Tagihan $namaBulan $tahun, $dept tingkat $tingkat kelas $stKelas";
        $petugas = getLevel() == 0 ?  "NULL" : "'" . getIdUser() . "'";

        $sql = "INSERT INTO jbsfina.tagihanset
                   SET nomor = '$noTagihanSet', nama = '$namaTagihan', departemen = '$dept', idtahunbuku = '$idTahunBuku', 
                       idtingkat = $idTingkat, petugas = $petugas, 
                       bulan = $bulan, tahun = $tahun, idkelas = '$stIdKelas',
                       idiuran = '$stIdIuran', stiuran = '$stIuran', 
                       keterangan = '$keterangan', tanggalbuat = NOW(),
                       issync = 0, token = ROUND((RAND() * (99999 - 10000)) + 10000)";
        QueryDbEx($sql);

        $idTagihanSet = 0;
        $sql = "SELECT LAST_INSERT_ID()";
        $res = QueryDbEx($sql);
        if ($row = mysql_fetch_row($res))
            $idTagihanSet = $row[0];

        // -- update counterset
        $sql = "UPDATE jbsfina.tagihansetcount
                   SET counter = $counterTagihanSet
                 WHERE departemen = '$dept'
                   AND bulan = $bulan
                   AND tahun = $tahun";
        QueryDbEx($sql);

        // ----  idpenerimaan yg belum dibayar, dijadikan invoice
        $nInvoiceCreated = 0;

        $lsSkipSiswa = array();
        $lsTemp = explode(",", $stSkipSiswa);
        for($i = 0; $i < count($lsTemp); $i++)
        {
            $temp = trim($lsTemp[$i]);
            if (strlen($temp) == 0) continue;
            $lsSkipSiswa[] = $temp;
        }

        $lsDiskon = explode(",", $stDiskon);

        $lsIdIuran = explode(",", $stIdIuran);
        $nIdIuran = count($lsIdIuran);
        for($i = 0; $i < $nSiswa; $i++)
        {
            $nis = $lsSiswa[$i][0];
            $nama = $lsSiswa[$i][1];

            if (in_array($nis, $lsSkipSiswa))
            {
                //$log->Log("Skip $nis");
                continue;
            }

            // ----- penerimaan yg sudah Lunas atau Gratis
            $lsFinished = array();
            $sql = "SELECT b.idpenerimaan
                      FROM jbsfina.besarjtt b 
                     WHERE b.nis = '$nis'
                       AND b.info2 = '$idTahunBuku'
                       AND b.lunas IN (1,2)";
            $res = QueryDbEx($sql);
            while($row = mysql_fetch_row($res))
            {
                $lsFinished[] = $row[0];
            }

            // ----- penerimaan yg sudah dibayarkan cicilannya pada bulan tahun terpilih
            $lsPaid = array();
            if ($skipAlreadyPaid == 1)
            {
                $sql = "SELECT DISTINCT b.idpenerimaan
                          FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b 
                         WHERE p.idbesarjtt  = b.replid
                           AND b.nis = '$nis'
                           AND b.lunas = 0
                           AND b.info2 = '$idTahunBuku'
                           AND p.jumlah > 0
                           AND MONTH(p.tanggal) = $bulan
                           AND YEAR(p.tanggal) = $tahun";
                $res = QueryDbEx($sql);
                while($row = mysql_fetch_row($res))
                {
                    $lsPaid[] = $row[0];
                }
            }

            // ----- besar penerimaan sudah diset?
            // change on 2023-03-31
            $lsBesarSet = array();
            $sql = "SELECT DISTINCT idpenerimaan
                      FROM jbsfina.besarjtt 
                     WHERE nis = '$nis'
                       AND info2 = '$idTahunBuku'";
            $res = QueryDbEx($sql);
            while($row = mysql_fetch_row($res))
            {
                $lsBesarSet[] = $row[0];
            }

            // ---- tagihan sudah dibuat
            $lsPrepared = array();
            $sql = "SELECT t.idpenerimaan
                      FROM jbsfina.tagihansiswadata t, jbsfina.besarjtt b
                     WHERE t.idbesarjtt = b.replid
                       AND b.info2 = '$idTahunBuku'
                       AND t.nis = '$nis'
                       AND t.bulan = $bulan
                       AND t.tahun = $tahun
                       AND t.status = 0
                       AND t.aktif = 1";
            $res = QueryDbEx($sql);
            while($row = mysql_fetch_row($res))
            {
                $lsPrepared[] = $row[0];
            }

            // ----- idinvoice berisi idpenerimaan yg belum lunas, belum gratis dan belum dibayarkan
            $lsIdInvoice = array();
            $lsDiskonInvoice = array();
            for($j = 0; $j < $nIdIuran; $j++)
            {
                $idIuran = $lsIdIuran[$j];

                if (in_array($idIuran, $lsFinished))
                    continue; // iuran sudah lunas atau gratis

                if (in_array($idIuran, $lsPaid))
                    continue; // iuran sudah dibayarkan bulan ini

                if (in_array($idIuran, $lsPrepared))
                    continue; // iuran sudah ada di tagihan yg telah dibuat

                if (in_array($idIuran, $lsBesarSet))
                    $lsIdInvoice[] = $idIuran; // iuran sudah di set besar pembayarannya

                $lsDiskonInvoice[$idIuran] = $lsDiskon[$j];
            }

            //$log->Log("$nis $nama " . json_encode($lsIdInvoice));

            $nInvoice = count($lsIdInvoice);
            if ($nInvoice == 0)
                continue; // tidak ada tagihan utk siswa ybs

            // --- ambil data besar tagihan, besar cicilan
            $stIdInvoice = json_encode($lsIdInvoice);
            $stIdInvoice = str_replace("[", "", $stIdInvoice);
            $stIdInvoice = str_replace("]", "", $stIdInvoice);
            $stIdInvoice = str_replace("\"", "", $stIdInvoice);

            $lsIdBesarJtt = array();
            $sql = "SELECT b.replid, b.besar, b.cicilan, b.idpenerimaan, dp.nama
                      FROM jbsfina.besarjtt b, jbsfina.datapenerimaan dp
                     WHERE b.idpenerimaan = dp.replid
                       AND b.idpenerimaan IN ($stIdInvoice)
                       AND b.nis = '$nis'
                       AND b.info2 = '$idTahunBuku'";  // change on 2023-03-31
            $res = QueryDbEx($sql);
            while($row = mysql_fetch_row($res))
            {
                $idIuran = $row[3];
                $diskon = $lsDiskonInvoice[$idIuran];

                $lsIdBesarJtt[] = array($row[0], $row[1], $row[2], $row[3], $row[4], $diskon);
            }

            //$log->Log(json_encode($lsIdBesarJtt));

            // --- format nomor tagihan
            $counterTagihan += 1;
            $counter = str_pad($counterTagihan, 6, '0', STR_PAD_LEFT);
            $noTagihan = "T.". $awalanNoTagihan . $bulanTahunTagihan . "." . $counter;
            //$log->Log("NO TAGIHAN $noTagihan");

            $nInvoiceCreated += 1;
            $tandaTransaksi = rand(10, 99);

            // ---------------
            $totalTagihan = 0;
            $stTagihan = "";
            $nIdBesarJtt = count($lsIdBesarJtt);
            for($j = 0; $j < $nIdBesarJtt; $j++)
            {
                $idBesarJtt = $lsIdBesarJtt[$j][0];
                $besarJtt = $lsIdBesarJtt[$j][1];
                $cicilanJtt = $lsIdBesarJtt[$j][2];
                $idPenerimaan = $lsIdBesarJtt[$j][3];
                $namaPenerimaan = $lsIdBesarJtt[$j][4];
                $diskon = $lsIdBesarJtt[$j][5];

                $jumlahBayar = 0;
                $jumlahSisa = 0;

                $sql = "SELECT IFNULL(SUM(jumlah) + SUM(info1), 0)
                          FROM jbsfina.penerimaanjtt
                         WHERE idbesarjtt = $idBesarJtt";
                //Logger::LogOnce($sql);
                $res = QueryDbEx($sql);
                if ($row = mysql_fetch_row($res))
                {
                    $jumlahBayar = $row[0];
                    $jumlahSisa = $besarJtt - $jumlahBayar;
                }

                $totalTagihan += $cicilanJtt - $diskon;
                if ($stTagihan != "") $stTagihan .= ", ";
                $stTagihan .= $namaPenerimaan;

                $sql = "INSERT INTO jbsfina.tagihansiswadata
                           SET idtagihanset = $idTagihanSet, nis = '$nis', bulan = $bulan, tahun = $tahun, notagihan = '$noTagihan', 
                               idbesarjtt = $idBesarJtt, idpenerimaan = $idPenerimaan, penerimaan = '$namaPenerimaan', jtagihan = $cicilanJtt, 
                               jdiskon = $diskon, jbesar = $besarJtt, jbayar = $jumlahBayar, jsisa = $jumlahSisa, status = 0, aktif = 1,
                               issync = 0, token = ROUND((RAND() * (99999 - 10000)) + 10000)";
                //Logger::LogOnce($sql);
                //$log->Log($sql);
                QueryDbEx($sql);
                //$log->Log(" Penerimaan $namaPenerimaan");
                //$log->Log(" Sisa $jumlahSisa");
            }

            $sql = "INSERT INTO jbsfina.tagihansiswadata
                           SET idtagihanset = $idTagihanSet, nis = '$nis', bulan = $bulan, tahun = $tahun, notagihan = '$noTagihan', 
                               idbesarjtt = NULL, idpenerimaan = NULL, penerimaan = 'Biaya Layanan', jtagihan = $tandaTransaksi, jdiskon = 0, 
                               jbesar = 0, jbayar = 0, jsisa = 0, status = 0, aktif = 1,
                               issync = 0, token = ROUND((RAND() * (99999 - 10000)) + 10000)";
            //$log->Log($sql);
            QueryDbEx($sql);

            $totalTagihan += $tandaTransaksi;

            $sql = "INSERT INTO jbsfina.tagihansiswainfo
                       SET idtagihanset = $idTagihanSet, nis = '$nis', bulan = $bulan, tahun = $tahun, notagihan = '$noTagihan', 
                           info = 'Tagihan bulan $namaBulan $tahun untuk $stTagihan', jumlah = $totalTagihan, status = 0, aktif = 1,
                           issync = 0, token = ROUND((RAND() * (99999 - 10000)) + 10000)";
            //$log->Log($sql);
            QueryDbEx($sql);

            // Kirim Notifikasi
            if ($sendNotif == 1)
            {
                $jumNotif = $totalTagihan + $PG_SERVICE_FEE;

                $pesan = str_replace("{NAMA}", $nama, $pesanNotifikasiTagihan);
                $pesan = str_replace("{NIS}", $nis, $pesan);
                $pesan = str_replace("{JUMLAH}", FormatRupiah($jumNotif), $pesan);
                $pesan = str_replace("{IURAN}", $stIuran, $pesan);
                $pesan = str_replace("{BULAN}", NamaBulan($bulan), $pesan);
                $pesan = str_replace("{TAHUN}", $tahun, $pesan);

                $success = 0;
                CreateSMSTunggakan("SISPAY", $dept, $nis, $nama, $pesan, $success);
            }
        }

        //$log->Log("DONE");
        //$log->Close();

        if ($nInvoiceCreated == 0)
        {
            RollbackTrans();
            return createJsonReturn(0, "Tidak ada tagihan yang disiapkan, karena tagihannya sudah dibuat atau iurannya sudah dibayarkan / dilunasi", "");
        }

        // -- update counter
        $sql = "UPDATE jbsfina.tagihancount
                   SET counter = $counterTagihan
                 WHERE departemen = '$dept'
                   AND bulan = $bulan
                   AND tahun = $tahun";
        QueryDbEx($sql);

        CommitTrans();
        //RollbackTrans();

        return createJsonReturn(1, "Berhasil menyiapkan $nInvoiceCreated tagihan", "");
    }
    catch (Exception $ex)
    {
        RollbackTrans();

        //$log->Log($ex->getCode() . " " . $ex->getMessage());
        //$log->Close();
        Logger::LogErrorOnce($ex, "k86e4");

        return createJsonReturn(-1, "ERROR: " . $ex->getMessage(), "");
    }
    finally
    {
        CloseDb();
    }

}
?>
