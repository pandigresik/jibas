<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 28.0 (November 14, 2022)
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
function ShowSelectDept()
{
    global $departemen;

    $dep = getDepartemen(getAccess());

    echo "<select name='departemen' id='departemen' class='inputbox' style='width:300px; font-size: 14px;' onchange='changeDep();'>";
    foreach($dep as $value)
    {
        if ($departemen == "")
            $departemen = $value;
        echo "<option value='$value' " . StringIsSelected($value, $departemen) . ">$value</option>";
    }
    echo "</select>";
}

function ShowSelectTahunBuku()
{
    global $departemen;
    global $idTahunBuku, $tahunBuku;

    echo "<select id='tahunbuku' name='tahunbuku' class='inputbox' style='width: 250px; font-size: 14px;' onchange='changeTahunBuku()'>";
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

function ShowAccYear()
{
    global $departemen;

    $sql = "SELECT replid AS id, tahunbuku
              FROM tahunbuku
             WHERE aktif = 1
               AND departemen='$departemen'";
    $result = QueryDb($sql);
    if (mysql_num_rows($result) > 0)
    {
        $row = mysql_fetch_array($result);
        echo "<input type='text' name='tahunbuku' id='tahunbuku' size='30' readonly class='inputbox'  style='background-color:#daefff; font-size:14px;' value='" . $row['tahunbuku'] . "'/>";
        echo "<input type='hidden' name='idtahunbuku' id='idtahunbuku' value='" . $row['id'] . "'/>";
    }
    else
    {
        echo "<input type='text' name='tahunbuku' id='tahunbuku' size='30' readonly style='background-color:#daefff; font-size:14px;' value=''/>";
        echo "<input type='hidden' name='idtahunbuku' id='idtahunbuku' value='0'/>";
    }
}

function ShowSelectBulan()
{
    echo "<select id='bulan' name='bulan' class='inputbox' style='width: 150px; font-size: 14px;' onchange='showInvoiceList()'>";
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
    echo "<select id='tahun' name='tahun' class='inputbox' style='width: 100px; font-size: 14px;' onchange='showInvoiceList()'>";

    $currThn = date('Y');
    for($thn = $currThn - 1; $thn <= $currThn + 1; $thn++)
    {
        $sel = $thn == $currThn ? "selected" : "";
        echo "<option value='$thn' $sel>$thn</option>";
    }
    echo "</select>";
}

function GetInvoiceList()
{
    try
    {
        $dept = $_REQUEST["dept"];
        $idTahunBuku = $_REQUEST["idtahunbuku"];
        $tahunBuku = $_REQUEST["tahunbuku"];
        $bulan = $_REQUEST["bulan"];
        $tahun = $_REQUEST["tahun"];
        $nis = $_REQUEST["nis"];
        $nama = $_REQUEST["nama"];
        $skipAlreadyPaid = $_REQUEST["skipalreadypaid"];

        // ----- penerimaan yg sudah Lunas atau Gratis
        $lsIuranSiswa = array();
        $sql = "SELECT DISTINCT b.idpenerimaan
                  FROM jbsfina.besarjtt b, jbsfina.datapenerimaan dp
                 WHERE b.idpenerimaan = dp.replid
                   AND b.nis = '$nis'
                   AND b.info2 = '$idTahunBuku'
                   AND b.lunas = 0";
        $res = QueryDbEx($sql);
        while($row = mysql_fetch_row($res))
        {
            $lsIuranSiswa[] = $row[0];
        }

        if (count($lsIuranSiswa) == 0)
        {
            echo "<br><br><br>";
            echo "<i>Tidak ada iuran yang dapat ditagihan, karena tagihannya sudah dibuat atau iurannya sudah dibayarkan / dilunasi</i>";
            return;
        }

        // ----- penerimaan yg sudah dibayarkan cicilannya pada bulan tahun terpilih
        $lsPaid = array();
        if ($skipAlreadyPaid == 1)
        {
            $sql = "SELECT DISTINCT b.idpenerimaan
                      FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b 
                     WHERE p.idbesarjtt  = b.replid
                       AND b.nis = '$nis'
                       AND b.info2 = '$idTahunBuku'
                       AND b.lunas = 0
                       AND p.jumlah > 0
                       AND MONTH(p.tanggal) = $bulan
                       AND YEAR(p.tanggal) = $tahun";
            //echo "$sql<br>";
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

        $lsIdInvoice = array();
        $nIdIuran = count($lsIuranSiswa);
        for($i = 0; $i < $nIdIuran; $i++)
        {
            $idIuran = $lsIuranSiswa[$i];

            /*
            if (in_array($idIuran, $lsFinished))
            {
                echo "$idIuran iuran sudah lunas atau gratis<br>";
                continue; // iuran sudah lunas atau gratis
            }
            */

            if (in_array($idIuran, $lsPaid))
            {
                //echo "$idIuran iuran sudah dibayarkan bulan ini<br>";
                continue; // iuran sudah dibayarkan bulan ini
            }

            if (in_array($idIuran, $lsPrepared))
            {
                //echo "$idIuran iuran sudah ada di tagihan yg telah dibuat<br>";
                continue; // iuran sudah ada di tagihan yg telah dibuat
            }

            if (in_array($idIuran, $lsBesarSet))
                $lsIdInvoice[] = $idIuran; // iuran sudah di set besar pembayarannya
        }

        if (count($lsIdInvoice) == 0)
        {
            echo "<br><br><br>";
            echo "<i>Tidak ada iuran yang dapat ditagihan, karena tagihannya sudah dibuat atau iurannya sudah dibayarkan / dilunasi</i>";
            return;
        }

        echo "<br><br>";
        echo "<strong>Iuran Wajib Siswa</strong>&nbsp;&nbsp;&nbsp;";
        echo "<img src='../images/selectall.png' width='16' onclick='checkIuran(1)' title='select all'>&nbsp;&nbsp";
        echo "<img src='../images/deselectall.png' width='16' onclick='checkIuran(0)' title='deselect all'><br><br>";

        echo "<table border='1' cellpadding='5' cellspacing='0' id='tabIuran'>";
        echo "<tr style='height: 30px'>";
        echo "<td width='30' align='center' class='header'>&nbsp;</td>";
        echo "<td width='250' align='left' class='header'>Iuran</td>";
        echo "<td width='140' align='left' class='header'>Tagihan</td>";
        echo "<td width='140' align='left' class='header'>Diskon</td>";
        echo "<td width='125' align='right' class='header'>Besar Iuran</td>";
        echo "<td width='125' align='right' class='header'>Dibayarkan</td>";
        echo "<td width='125' align='right' class='header'>Sisa</td>";
        echo "<td width='250' align='left' class='header'>Pembayaran Terakhir</td>";
        echo "</tr>";

        // --- ambil data besar tagihan, besar cicilan
        $stIdInvoice = json_encode($lsIdInvoice);
        $stIdInvoice = str_replace("[", "", $stIdInvoice);
        $stIdInvoice = str_replace("]", "", $stIdInvoice);
        $stIdInvoice = str_replace("\"", "", $stIdInvoice);

        $sql = "SELECT b.replid, b.besar, b.cicilan, b.idpenerimaan, dp.nama
                  FROM jbsfina.besarjtt b, jbsfina.datapenerimaan dp
                 WHERE b.idpenerimaan = dp.replid
                   AND b.idpenerimaan IN ($stIdInvoice)
                   AND b.nis = '$nis'
                   AND b.info2 = '$idTahunBuku'";  // change on 2023-03-31

        $res = QueryDbEx($sql);
        $no = 0;
        while($row = mysql_fetch_row($res))
        {
            $no += 1;

            $idBesarJtt = $row[0];
            $besarJtt = $row[1];
            $cicilan = $row[2];
            $idPenerimaan = $row[3];
            $penerimaan = $row[4];

            $jumlahBayar = 0;
            $jumlahSisa = 0;

            $sql = "SELECT SUM(jumlah) + SUM(info1)
                      FROM jbsfina.penerimaanjtt
                     WHERE idbesarjtt = $idBesarJtt";
            $res2 = QueryDbEx($sql);
            if ($row2 = mysql_fetch_row($res2))
            {
                $jumlahBayar = $row2[0];
                $jumlahSisa = $besarJtt - $jumlahBayar;
            }

            $tglAkhir = "";
            $cicilanAkhir = "";
            $diskonAkhir = "";

            $sql = "SELECT (jumlah + info1) AS cicilan, info1 AS diskon, DATE_FORMAT(tanggal, '%d-%b-%Y') AS ftanggal
                      FROM jbsfina.penerimaanjtt 
                     WHERE idbesarjtt = $idBesarJtt
                     ORDER BY tanggal DESC, replid DESC
                     LIMIT 1";
            $res2 = QueryDbEx($sql);
            if ($row2 = mysql_fetch_row($res2))
            {
                $cicilanAkhir = $row2[0];
                $diskonAkhir = $row2[1];
                $tglAkhir = $row2[2];
            }

            echo "<tr>";
            echo "<td align='center'>";
            echo "<input type='checkbox' id='chiuran$no' name='chiuran$no' onclick='onCheckIuran($no)'>";
            echo "</td>";
            echo "<td align='left'>";
            echo $penerimaan;
            echo "<input type='hidden' id='idiuran$no' name='idiuran$no' value='$idPenerimaan'>";
            echo "<input type='hidden' id='iuran$no' name='iuran$no' value='$penerimaan'>";
            echo "</td>";
            echo "<td align='left'>";
            $elTagihan = "tagihan$no";
            $rpTagihan = FormatRupiah($cicilan);
            echo "<input type='text' id='$elTagihan' name='$elTagihan' value='$rpTagihan' class='inputbox' style='width: 120px; background-color: #ccc ' disabled='disabled' onfocus=\"unformatRupiah('$elTagihan')\" onblur=\"formatRupiah('$elTagihan');  calculateTotalTagihan();\">";
            echo "</td>";
            echo "<td align='left'>";
            $elDiskon = "diskon$no";
            echo "<input type='text' id='$elDiskon' name='$elDiskon' value='Rp 0' class='inputbox' style='width: 120px; background-color: #ccc ' disabled='disabled' onfocus=\"unformatRupiah('$elDiskon')\" onblur=\"formatRupiah('$elDiskon'); calculateTotalTagihan();\">";
            echo "</td>";
            echo "<td align='right'>";
            echo FormatRupiah($besarJtt);
            echo "</td>";
            echo "<td align='right'>";
            echo FormatRupiah($jumlahBayar);
            echo "</td>";
            echo "<td align='right'>";
            echo FormatRupiah($jumlahSisa);
            echo "<input type='hidden' id='sisa$no' name='sisa$no' value='$jumlahSisa'>";
            echo "</td>";
            echo "<td align='left'>";
            if ($cicilanAkhir == "")
            {
                echo "<i>belum ada pembayaran</i>";
            }
            else
            {
                echo FormatRupiah($cicilanAkhir);
                echo " tanggal $tglAkhir";
                if ($diskonAkhir != 0)
                    echo "<br>diskon " . FormatRupiah($diskonAkhir);
            }
            echo "</td>";
            echo "</tr>";
        }

        echo "<tr>";
        echo "<td colspan='2' align='right' style='background-color: #cccccc'><span style='font-weight: bold; font-size: 14px'>TOTAL</span>&nbsp;&nbsp;</td>";
        echo "<td colspan='2' align='center' style='background-color: #ffb547'><span id='spTotal' style='font-weight: bold; font-size: 14px'>Rp 0</span>&nbsp;&nbsp;</td>";
        echo "<td colspan='4' align='left' style='background-color: #cccccc'><span id='spInfo' style='color: red'></span>&nbsp;&nbsp;";
        echo "<input type='hidden' id='valStatus' value='0'>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        echo "<input type='hidden' id='niuran' name='niuran' value='$no'><br><br>";
        echo "<strong>Keterangan:</strong><br>";
        echo "<input type='text' id='keterangan' name='keterangan' class='inputbox' style='width: 450px' maxlength='255'><br><br>";
        echo "<strong>Notifikasi:</strong><br>";
        echo "<input type='checkbox' id='chnotif' name='chnotif' checked='checked'>&nbsp;kirim notifikasi tagihan lewat Jendela Sekolah | Telegram | SMS<br><br>";
        echo "<div>";
        echo "<input type='button' id='btBuatTagihan' class='but' style='width: 120px; height: 45px' value='Buat Tagihan' onclick='createInvoice()'>&nbsp;&nbsp;&nbsp;";
        echo "<span id='spBuatTagihan' style='color: #0000FF;'></span>";
        echo "</div>";
    }
    catch (Exception $ex)
    {
        echo $ex->getMessage();
    }
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
        $nis = $_REQUEST["nis"];
        $nama = $_REQUEST["nama"];
        $stIdIuran = $_REQUEST["idiuran"];
        $idTahunBuku = $_REQUEST["idtahunbuku"];
        $stIuran = $_REQUEST["iuran"];
        $stTagihan = $_REQUEST["tagihan"];
        $stDiskon = $_REQUEST["diskon"];
        $bulan = $_REQUEST["bulan"];
        $namaBulan = NamaBulan($bulan);
        $tahun = $_REQUEST["tahun"];
        $keterangan = $_REQUEST["keterangan"];
        $sendNotif = $_REQUEST["sendnotif"];
        $skipAlreadyPaid = $_REQUEST["skipalreadypaid"];

        $bulanTahunTagihan  = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $bulanTahunTagihan .= substr($tahun, 2, 2);

        // ----- ambil tahun buku
        /*
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
            $pesanNotifikasiTagihan = "Kami informasikan {NAMA} {NIS} memiliki tagihan sebesar {BESAR} untuk {IURAN} bulan {BULAN} {TAHUN}";

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

        // --- buat tagihan set
        // change on 2023-08-02
        $counterTagihanSet += 1;
        $counterSet = str_pad($counterTagihanSet, 4, '0', STR_PAD_LEFT);
        $noTagihanSet = "TS.". $awalanNoTagihan . $bulanTahunTagihan . "." . $counterSet;
        $namaTagihan = "Tagihan $namaBulan $tahun, $dept siswa $nama $nis";
        $petugas = getLevel() == 0 ?  "NULL" : "'" . getIdUser() . "'";

        $sql = "INSERT INTO jbsfina.tagihanset
                   SET nomor = '$noTagihanSet', nama = '$namaTagihan', departemen = '$dept', idtahunbuku = '$idTahunBuku', 
                       idtingkat = NULL, petugas = $petugas, nis = '$nis', 
                       bulan = $bulan, tahun = $tahun, idkelas = NULL,
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

        //$log = new Logger();
        //$log->Log($stIdIuran);

        $lsTagihan = explode(",", $stTagihan);
        $lsDiskon = explode(",", $stDiskon);
        $lsIdIuran = explode(",", $stIdIuran);
        $nIdIuran = count($lsIdIuran);

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
                       AND b.info2 = '$idTahunBuku'
                       AND b.lunas = 0
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
        $lsTagihanInvoice = array();
        $lsDiskonInvoice = array();
        for($j = 0; $j < $nIdIuran; $j++)
        {
            $idIuran = $lsIdIuran[$j];

            if (in_array($idIuran, $lsFinished))
            {
                //$log->Log("$idIuran iuran sudah lunas atau gratis");
                continue; // iuran sudah lunas atau gratis
            }

            if (in_array($idIuran, $lsPaid))
            {
                //$log->Log("$idIuran iuran sudah dibayarkan bulan ini");
                continue; // iuran sudah dibayarkan bulan ini
            }

            if (in_array($idIuran, $lsPrepared))
            {
                //$log->Log("$idIuran iuran sudah ada di tagihan yg telah dibuat");
                continue; // iuran sudah ada di tagihan yg telah dibuat
            }

            if (in_array($idIuran, $lsBesarSet))
                $lsIdInvoice[] = $idIuran; // iuran sudah di set besar pembayarannya

            $lsTagihanInvoice[$idIuran] = $lsTagihan[$j];
            $lsDiskonInvoice[$idIuran] = $lsDiskon[$j];
        }

        //$log->Log("$nis $nama " . json_encode($lsIdInvoice));
        //$log->Close();

        $nInvoice = count($lsIdInvoice);
        if ($nInvoice == 0)
            return createJsonReturn(0, "Tidak ada tagihan yang disiapkan, karena tagihannya sudah dibuat atau iurannya sudah dibayarkan / dilunasi", "");

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
            $tagihan = $lsTagihanInvoice[$idIuran];
            $diskon = $lsDiskonInvoice[$idIuran];

            $lsIdBesarJtt[] = array($row[0], $row[1], $row[2], $row[3], $row[4], $tagihan, $diskon);
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
            $tagihan = $lsIdBesarJtt[$j][5];
            $diskon = $lsIdBesarJtt[$j][6];

            $jumlahBayar = 0;
            $jumlahSisa = 0;

            $sql = "SELECT IFNULL(SUM(jumlah) + SUM(info1), 0)
                      FROM jbsfina.penerimaanjtt
                     WHERE idbesarjtt = $idBesarJtt";
            $res = QueryDbEx($sql);
            if ($row = mysql_fetch_row($res))
            {
                $jumlahBayar = $row[0];
                $jumlahSisa = $besarJtt - $jumlahBayar;
            }

            $totalTagihan += $tagihan - $diskon;
            if ($stTagihan != "") $stTagihan .= ", ";
            $stTagihan .= $namaPenerimaan;

            $sql = "INSERT INTO jbsfina.tagihansiswadata
                       SET idtagihanset = $idTagihanSet, nis = '$nis', bulan = $bulan, tahun = $tahun, notagihan = '$noTagihan', 
                           idbesarjtt = $idBesarJtt, idpenerimaan = $idPenerimaan, penerimaan = '$namaPenerimaan', jtagihan = $tagihan, 
                           jdiskon = $diskon, jbesar = $besarJtt, jbayar = $jumlahBayar, jsisa = $jumlahSisa, status = 0, aktif = 1,
                           issync = 0, token = ROUND((RAND() * (99999 - 10000)) + 10000)";
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

        //RollbackTrans();
        CommitTrans();

        return createJsonReturn(1, "Berhasil menyiapkan tagihan untuk $nama ($nis)", "");
    }
    catch (Exception $ex)
    {
        RollbackTrans();

        //$log->Log($ex->getCode() . " " . $ex->getMessage());
        //$log->Close();

        return createJsonReturn(-1, "ERROR: " . $ex->getMessage(), "");
    }
    finally
    {
        CloseDb();
    }

}

function createJsonReturn($status, $message, $data)
{
    $ret = array($status, $message, $data);
    return json_encode($ret);
}
?>