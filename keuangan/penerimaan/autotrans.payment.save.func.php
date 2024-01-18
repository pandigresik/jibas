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
function GetNoKas()
{
    global $idtahunbuku;

    //Ambil awalan dan cacah tahunbuku untuk bikin nokas;
    $sql = "SELECT awalan, cacah
              FROM tahunbuku
             WHERE replid = '".$idtahunbuku."'";
    //echo "$sql<br>";
    $row = FetchSingleRow($sql);
    $awalan = $row[0];
    $cacah = $row[1];

    $cacah += 1; // Increment cacah
    $nokas = $awalan . rpad($cacah, "0", 6); // Form nomor kas

    return $nokas;
}

function CreateSMSReport()
{
    global $transactions, $studentid, $studentname, $departemen, $kelompok;

    $jenis = $kelompok == "siswa" ? "SISPAY" : "CSISPAY";
    $tbayar = date("Y-m-d");

    $total = 0;
    $ketsms = "";
    for($i = 0; $i < count($transactions); $i++)
    {
        $total += $transactions[$i][2] - $transactions[$i][3];

        if ($ketsms != "")
            $ketsms .= ", ";
        $ketsms .= $transactions[$i][1];
    }

    $success = true;
    CreateSMSPaymentInfo($jenis,
        $departemen, $studentid, $studentname,
        RegularDateFormat($tbayar),
        FormatRupiah($total),
        $ketsms,
        $success);
    return $success;
}

function CountTotalPayment()
{
    global $transactions;

    $total = 0;
    for($i = 0; $i < count($transactions); $i++)
    {
        $total += $transactions[$i][2] - $transactions[$i][3];
    }
    echo "\r\n<input type='hidden' id='total' name='total' value='$total'>";
}

function CreateDivPrintReportCompact()
{
    global $transactions;

    $list = "";
    for($i = 0; $i < count($transactions); $i++)
    {
        if ($list != "")
            $list .= ", ";
        $list .= $transactions[$i][1];
    }
    echo "\r\n<input type='hidden' id='paymentlist' name='paymentlist' value='$list'>";
}

function CreateDivPrintReportDetail()
{
    global $transactions;

    echo "<table border='1' cellpadding='2' cellspacing='0' style='border-width: 1px; border-collapse: collapse;'>";
    echo "<tr height='25'>";
    echo "<td width='25' align='center'>No</td>";
    echo "<td width='100' align='center'>No Transaksi</td>";
    echo "<td width='180' align='center'>Transaksi</td>";
    echo "<td width='90' align='center'>Jumlah</td>";
    echo "<td width='90' align='center'>Diskon</td>";
    echo "<td width='110' align='center'>Sub Total</td>";
    echo "<td width='90' align='center'>Sisa</td>";
    echo "</tr>";

    $total = 0;
    for($i = 0; $i < count($transactions); $i++)
    {
        $subtotal = $transactions[$i][2] - $transactions[$i][3];
        $total += $subtotal;

        echo "<tr height='35' style='font-size: 8px;'>";
        echo "<td align='center' valign='top'>" . ($i + 1) . "</td>";
        echo "<td align='center' valign='top'>" . $transactions[$i][0] . "</td>";
        echo "<td align='left' valign='top'>" . $transactions[$i][1] . "</td>";
        echo "<td align='right' valign='top'>" . FormatRupiah($transactions[$i][2]) . "</td>";
        echo "<td align='right' valign='top'>" . FormatRupiah($transactions[$i][3]) . "</td>";
        echo "<td align='right' valign='top'>" . FormatRupiah($subtotal) . "</td>";

        if ($transactions[$i][4] == "JTT" || $transactions[$i][4] == "CSWJB")
            echo "<td align='right' valign='top'>" . FormatRupiah($transactions[$i][5]) . "</td>";
        else
            echo "<td align='right' valign='top'>-</td>";

        echo "</tr>";
    }

    echo "<tr height='25'>";
    echo "<td colspan='5' align='right'><strong>TOTAL</strong></td>";
    echo "<td align='right'><strong>". FormatRupiah($total) . "</strong></td>";
    echo "<td align='right'><strong>&nbsp;</strong></td>";
    echo "</tr>";
    echo "</table>";
}

function SaveIuranWajibSiswa($no)
{
    global $studentid, $studentname, $idtahunbuku;
    global $transactions;

    $idpayment = $_REQUEST["idPayment-$no"];
    $idbesarjtt = (int)$_REQUEST["idbesarjtt-$no"];
    $jcicilan = (int)UnformatRupiah($_REQUEST["bayar-$no"]);
    $jdiskon = (int)UnformatRupiah($_REQUEST["diskon-$no"]);
    $jbayar = $jcicilan - $jdiskon;
    $kcicilan = $_REQUEST["keterangan-$no"];
    $tagihan = (int)$_REQUEST["besar-$no"];
    $infocicil = $_REQUEST["payment-$no"];
    $infociciljurnal = "$infocicil siswa $studentname ($studentid)";
    $tcicilan = date("Y-m-d");

    $success = true;

    // Ambil informasi kode rekening berdasarkan jenis penerimaan
    $sql = "SELECT rekkas, rekpiutang, rekpendapatan, info1, nama
              FROM jbsfina.datapenerimaan
             WHERE replid = '".$idpayment."'";
    //echo "$sql<br>";
    $row = FetchSingleRow($sql);
    $rekkas = $row[0];
    $rekpiutang = $row[1];
    $rekpendapatan = $row[2];
    $rekdiskon = $row[3];
    $paymentname = $row[4];

    //-- petugas pendata & keterangan
    $idpetugas = getIdUser();
    $petugas = getUserName();

    //-- Cari jumlah terbayar, yg sudah dibayarkan siswa
    $sql = "SELECT SUM(jumlah), SUM(info1)
              FROM jbsfina.penerimaanjtt
             WHERE idbesarjtt = '".$idbesarjtt."'";
    //echo "$sql<br>";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $terbayar = $row[0];
    $terdiskon = $row[1];

    //-- Hitung jumlah tersisa
    $tersisa = $tagihan - $terbayar - $terdiskon - $jcicilan;
    $lunas = $tersisa == 0 ? 1 : 0;

    // -- Simpan ke jurnal -----------------------------------------------
    $idjurnal = 0;
    if ($success)
    {
        $nokas = GetNoKas();

        $transactions[] = [$nokas, $infocicil, $jcicilan, $jdiskon, "JTT", $tersisa];

        //echo "SimpanJurnal($idtahunbuku, $tcicilan, $infocicil, $nokas, '', $petugas, 'penerimaanjtt', $idjurnal)<br>";
        $success = SimpanJurnal($idtahunbuku, $tcicilan, $infociciljurnal, $nokas, "", $idpetugas, $petugas, "penerimaanjtt", $idjurnal);
    }

    //-- Simpan ke jurnaldetail ------------------------------------------
    if ($success)
    {
        //echo "SimpanDetailJurnal($idjurnal, 'D', $rekkas, $jbayar)<br>";
        $success = SimpanDetailJurnal($idjurnal, "D", $rekkas, $jbayar);
    }
    if ($success)
    {
        //echo "SimpanDetailJurnal($idjurnal, 'K', $rekpiutang, $jcicilan)<br>";
        $success = SimpanDetailJurnal($idjurnal, "K", $rekpiutang, $jcicilan);
    }
    if ($jdiskon > 0 && $success)
    {
        //echo "SimpanDetailJurnal($idjurnal, 'D', $rekdiskon, $jdiskon)<br>";
        $success = SimpanDetailJurnal($idjurnal, "D", $rekdiskon, $jdiskon);
    }

    // -- increment cacah di tahunbuku -----------------------------------
    if ($success)
    {
        $sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid='$idtahunbuku'";
        //echo "$sql<br>";
        QueryDbTrans($sql, $success);
    }

    // -- simpan data cicilan di penerimaanjtt ---------------------------
    if ($success)
    {
        $sql = "INSERT INTO jbsfina.penerimaanjtt
                   SET idbesarjtt='$idbesarjtt', idjurnal='$idjurnal', tanggal='$tcicilan', 
                       jumlah='$jbayar', keterangan='$kcicilan', petugas='$petugas', info1='$jdiskon'";
        //echo "$sql<br>";
        QueryDbTrans($sql, $success);
    }

    if ($lunas == 1)
    {
        if ($success)
        {
            $sql = "SET @DISABLE_TRIGGERS = 1;";
            //echo "$sql<br>";
            QueryDb($sql);

            $sql = "UPDATE jbsfina.besarjtt SET lunas=1 WHERE replid='$idbesarjtt'";
            //echo "$sql<br>";
            QueryDbTrans($sql, $success);

            $sql = "SET @DISABLE_TRIGGERS = NULL;";
            //echo "$sql<br>";
            QueryDb($sql);
        }
    }

    return $success;
}

function SaveIuranSukarelaSiswa($no)
{
    global $studentid, $studentname, $idtahunbuku;
    global $transactions;

    $success = true;

    $idpayment = $_REQUEST["idPayment-$no"];
    $jumlah = (int)UnformatRupiah($_REQUEST["bayar-$no"]);
    if ($jumlah == 0)
        return $success;

    $keterangan = $_REQUEST["keterangan-$no"];
    $infocicil = $_REQUEST["payment-$no"];
    //$infocicil = "$infocicil siswa $studentname ($studentid)";

    // Ambil informasi kode rekening berdasarkan jenis penerimaan
    $sql = "SELECT rekkas, rekpiutang, rekpendapatan, info1, nama
              FROM jbsfina.datapenerimaan
             WHERE replid = '".$idpayment."'";
    //echo "$sql<br>";
    $row = FetchSingleRow($sql);
    $rekkas = $row[0];
    $rekpiutang = $row[1];
    $rekpendapatan = $row[2];
    $rekdiskon = $row[3];
    $paymentname = $row[4];

    $tanggal = date("Y-m-d");
    $idpetugas = getIdUser();
    $petugas = getUserName();

    $nokas = GetNoKas();

    //Simpan ke jurnal
    $ketjurnal = "Pembayaran $paymentname tanggal $tanggal siswa $studentname ($studentid)";
    $idjurnal = 0;

    //echo "SimpanJurnal($idtahunbuku, $tanggal, $ketjurnal, $nokas, '', $petugas, 'penerimaaniuran', $idjurnal);<br>";
    $success = SimpanJurnal($idtahunbuku, $tanggal, $ketjurnal, $nokas, "", $idpetugas, $petugas, "penerimaaniuran", $idjurnal);

    //Simpan ke jurnaldetail
    if ($success)
    {
        //echo "SimpanDetailJurnal($idjurnal, 'D', $rekkas, $jumlah);<br>";
        $success = SimpanDetailJurnal($idjurnal, "D", $rekkas, $jumlah);
    }
    if ($success)
    {
        //echo "SimpanDetailJurnal($idjurnal, 'K', $rekpendapatan, $jumlah);<br>";
        $success = SimpanDetailJurnal($idjurnal, "K", $rekpendapatan, $jumlah);
    }

    //increment cacah di tahunbuku
    if ($success)
    {
        $sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid=$idtahunbuku";
        //echo "$sql<br>";
        QueryDbTrans($sql, $success);
    }

    if ($success)
    {
        $sql = "INSERT INTO penerimaaniuran
				   SET idpenerimaan='$idpayment', nis='$studentid', idjurnal='$idjurnal',
					   jumlah='$jumlah', tanggal='$tanggal', keterangan='$keterangan', petugas='$petugas'";
        //echo "$sql<br>";
        QueryDbTrans($sql, $success);
    }

    if ($success)
    {
        $transactions[] = [$nokas, $infocicil, $jumlah, 0, "SKR", 0];
    }

    return $success;
}

function SaveIuranWajibCalonSiswa($no)
{
    global $studentid, $studentname, $idtahunbuku;
    global $transactions;

    $idpayment = $_REQUEST["idPayment-$no"];
    $idbesarjtt = (int)$_REQUEST["idbesarjtt-$no"];
    $jcicilan = (int)UnformatRupiah($_REQUEST["bayar-$no"]);
    $jdiskon = (int)UnformatRupiah($_REQUEST["diskon-$no"]);
    $jbayar = $jcicilan - $jdiskon;
    $kcicilan = $_REQUEST["keterangan-$no"];
    $tagihan = (int)$_REQUEST["besar-$no"];
    $infocicil = $_REQUEST["payment-$no"];
    $infociciljurnal = "$infocicil siswa $studentname ($studentid)";
    $tcicilan = date("Y-m-d");

    //echo "idbesarjtt = $idbesarjtt ------<br>";
    $success = true;

    // Ambil informasi kode rekening berdasarkan jenis penerimaan
    $sql = "SELECT rekkas, rekpiutang, rekpendapatan, info1, nama
              FROM jbsfina.datapenerimaan
             WHERE replid = '".$idpayment."'";
    //echo "$sql<br>";
    $row = FetchSingleRow($sql);
    $rekkas = $row[0];
    $rekpiutang = $row[1];
    $rekpendapatan = $row[2];
    $rekdiskon = $row[3];
    $paymentname = $row[4];

    // tanggal & petugas pendata & keterangan
    $tcicilan = date("Y-m-d");
    $idpetugas = getIdUser();
    $petugas = getUserName();

    //-- Cari jumlah terbayar, yg sudah dibayarkan siswa
    $sql = "SELECT SUM(jumlah), SUM(info1)
              FROM jbsfina.penerimaanjttcalon
             WHERE idbesarjttcalon = '".$idbesarjtt."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);

    $terbayar = $row[0];
    $terdiskon = $row[1];

    // jumlah tersisa
    $tersisa = $tagihan - $terbayar - $terdiskon - $jcicilan;
    $lunas = $tersisa == 0 ? 1 : 0;

    // -- Simpan ke jurnal -----------------------------------------------
    $idjurnal = 0;
    if ($success)
    {
        $nokas = GetNoKas();

        $transactions[] = [$nokas, $infocicil, $jcicilan, $jdiskon, "CSWJB", $tersisa];

        //echo "SimpanJurnal($idtahunbuku, $tcicilan, $infocicil, $nokas, '', $petugas, 'penerimaanjttcalon', $idjurnal)<br>";
        $success = SimpanJurnal($idtahunbuku, $tcicilan, $infociciljurnal, $nokas, "", $idpetugas, $petugas, "penerimaanjttcalon", $idjurnal);
    }

    //-- Simpan ke jurnaldetail ------------------------------------------
    if ($success)
    {
        //echo "SimpanDetailJurnal($idjurnal, 'D', $rekkas, $jbayar)<br>";
        $success = SimpanDetailJurnal($idjurnal, "D", $rekkas, $jbayar);
    }
    if ($success)
    {
        //echo "SimpanDetailJurnal($idjurnal, 'K', $rekpiutang, $jcicilan)<br>";
        $success = SimpanDetailJurnal($idjurnal, "K", $rekpiutang, $jcicilan);
    }
    if ($jdiskon > 0 && $success)
    {
        //echo "SimpanDetailJurnal($idjurnal, 'D', $rekdiskon, $jdiskon)<br>";
        $success = SimpanDetailJurnal($idjurnal, "D", $rekdiskon, $jdiskon);
    }

    // -- increment cacah di tahunbuku -----------------------------------
    if ($success)
    {
        $sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid='$idtahunbuku'";
        //echo "$sql<br>";
        QueryDbTrans($sql, $success);
    }

    // -- simpan data cicilan di penerimaanjtt ---------------------------
    if ($success)
    {
        $sql = "INSERT INTO jbsfina.penerimaanjttcalon
                   SET idbesarjttcalon='$idbesarjtt', idjurnal='$idjurnal', tanggal='$tcicilan', 
                       jumlah='$jbayar', keterangan='$kcicilan', petugas='$petugas', info1='$jdiskon'";
        //echo "$sql<br>";
        QueryDbTrans($sql, $success);
    }

    if ($lunas == 1)
    {
        if ($success)
        {
            $sql = "SET @DISABLE_TRIGGERS = 1;";
            //echo "$sql<br>";
            QueryDb($sql);

            $sql = "UPDATE jbsfina.besarjttcalon SET lunas=1 WHERE replid='$idbesarjtt'";
            //echo "$sql<br>";
            QueryDbTrans($sql, $success);

            $sql = "SET @DISABLE_TRIGGERS = NULL;";
            //echo "$sql<br>";
            QueryDb($sql);
        }
    }

    return $success;
}

function SaveIuranSukarelaCalonSiswa($no)
{
    global $studentid, $studentname, $idtahunbuku;
    global $transactions;

    $success = true;

    $idpayment = $_REQUEST["idPayment-$no"];
    $jumlah = (int)UnformatRupiah($_REQUEST["bayar-$no"]);
    if ($jumlah == 0)
        return $success;

    $keterangan = $_REQUEST["keterangan-$no"];
    $infocicil = $_REQUEST["payment-$no"];
    //$infocicil = "$infocicil siswa $studentname ($studentid)";

    $sql = "SELECT replid
			  FROM jbsakad.calonsiswa
			 WHERE nopendaftaran = '$studentid' ";
    $idcalon = FetchSingle($sql);

    // Ambil informasi kode rekening berdasarkan jenis penerimaan
    $sql = "SELECT rekkas, rekpiutang, rekpendapatan, info1, nama
              FROM jbsfina.datapenerimaan
             WHERE replid = '".$idpayment."'";
    //echo "$sql<br>";
    $row = FetchSingleRow($sql);
    $rekkas = $row[0];
    $rekpiutang = $row[1];
    $rekpendapatan = $row[2];
    $rekdiskon = $row[3];
    $paymentname = $row[4];

    $tanggal = date("Y-m-d");
    $idpetugas = getIdUser();
    $petugas = getUserName();
    $nokas = GetNoKas();

    //Simpan ke jurnal
    $ketjurnal = "Pembayaran $paymentname tanggal $tanggal calon siswa $studentname ($studentid)";
    $idjurnal = 0;
    //echo "SimpanJurnal($idtahunbuku, $tanggal, $ketjurnal, $nokas, '', $petugas, 'penerimaaniurancalon', $idjurnal);<br>";
    $success = SimpanJurnal($idtahunbuku, $tanggal, $ketjurnal, $nokas, "", $idpetugas, $petugas, "penerimaaniurancalon", $idjurnal);

    //Simpan ke jurnaldetail
    if ($success)
    {
        //echo "SimpanDetailJurnal($idjurnal, 'D', $rekkas, $jumlah);<br>";
        $success = SimpanDetailJurnal($idjurnal, "D", $rekkas, $jumlah);
    }
    if ($success)
    {
        //echo "SimpanDetailJurnal($idjurnal, 'K', $rekpendapatan, $jumlah);<br>";
        $success = SimpanDetailJurnal($idjurnal, "K", $rekpendapatan, $jumlah);
    }

    //increment cacah di tahunbuku
    if ($success)
    {
        $sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid=$idtahunbuku";
        //echo "$sql<br>";
        QueryDbTrans($sql, $success);
    }

    if ($success)
    {
        $sql = "INSERT INTO penerimaaniurancalon
				   SET idpenerimaan='$idpayment', idcalon='$idcalon', idjurnal='$idjurnal',
					   jumlah='$jumlah', tanggal='$tanggal', keterangan='$keterangan', petugas='$petugas'";
        //echo "$sql<br>";
        QueryDbTrans($sql, $success);
    }

    if ($success)
    {
        $transactions[] = [$nokas, $infocicil, $jumlah, 0, "CSSKR", 0];
    }

    return $success;
}

// 2020-09-12
// MultiTransInfo dibutuhkan di SchoolPay
function SaveMultiTransInfo()
{
    global $studentid, $idtahunbuku, $ktransaksi;
    global $transactions;

    $success = true;

    if (count($transactions) == 0)
        return $success;

    $petugas = getUserName();

    $kategori = $transactions[0][4];
    $userCol = "nis";
    if ($kategori == "CSWJB" || $kategori == "CSSKR")
        $userCol = "nic";

    $sql = "INSERT INTO jbsfina.multitransinfo
               SET idtahunbuku = $idtahunbuku, $userCol = '$studentid', tanggal = NOW(), 
                   petugas = '$petugas', keterangan = '$ktransaksi', paymentstatus = 0";
    QueryDbTrans($sql, $success);

    $idinfo = 0;
    if ($success)
    {
        $sql = "SELECT LAST_INSERT_ID()";
        $res = QueryDbTrans($sql, $success);
        if ($success)
        {
            $row = mysqli_fetch_row($res);
            $idinfo = $row[0];
        }
    }

    if ($success)
    {
        for($i = 0; $success && $i < count($transactions); $i++)
        {
            $notrans = $transactions[$i][0];
            $kategori = $transactions[$i][4];

            $sql = "INSERT INTO jbsfina.multitransdata
                       SET idinfo = $idinfo, notrans = '$notrans', kategori = '".$kategori."'";
            QueryDbTrans($sql, $success);
        }
    }

    return $success;
}
?>
