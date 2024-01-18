<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 28.0 (Oct 10, 2022)
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

    echo "<select name='departemen' id='departemen' style='width:100px; font-size: 14px;' onchange='change_dep();'>";
    foreach($dep as $value)
    {
        if ($departemen == "")
            $departemen = $value;
        echo "<option value='$value' " . StringIsSelected($value, $departemen) . ">".$value."</option>";
    }
    echo "</select>";
}

function GetSelectPayment($departemen, $kelompok)
{
    $sql = "SELECT replid, judul, keterangan
              FROM jbsfina.autotrans
             WHERE departemen = '$departemen'
               AND aktif = 1
               AND kelompok = $kelompok
             ORDER BY urutan";
    $res = QueryDb($sql);
    $num = mysqli_num_rows($res);

    if ($num == 0)
    {
        $arrJson = [0, "Belum ada konfigurasi pembayaran!", ""];
    }
    else
    {
        $arrKet = [];

        $idFirst = 0;
        $data = "<select id='paymentSelect' onchange='changePayment()' style='width: 350px; font-size: 14px;'>";
        while($row = mysqli_fetch_row($res))
        {
            $arrKet[] = $row[2];

            if ($idFirst == 0)
            {
                $idFirst = $row[0];
                $data .= "<option value='".$row[0]."' selected>".$row[1]."</option>";
            }
            else
            {
                $data .= "<option value='".$row[0]."'>".$row[1]."</option>";
            }
        }
        $data .= "<select>";

        $arrJson = [$idFirst, $data, json_encode($arrKet, JSON_THROW_ON_ERROR)];
    }

    echo json_encode($arrJson, JSON_THROW_ON_ERROR);
}

function GetPaymentList($idAutoTrans, $kelompok, $noid, $idTahunBuku)
{
    $sql = "SELECT smsinfo
              FROM jbsfina.autotrans
             WHERE replid = $idAutoTrans";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $smsinfo = $row[0];

    $sql = "SELECT ad.idpenerimaan, dp.nama, dp.idkategori, ad.besar, ad.keterangan
              FROM jbsfina.autotransdata ad, jbsfina.datapenerimaan dp
             WHERE ad.idpenerimaan = dp.replid
               AND ad.idautotrans = $idAutoTrans
               AND ad.aktif = 1
             ORDER BY ad.urutan";
    $res = QueryDb($sql);
    $num = mysqli_num_rows($res);

    if ($num == 0)
    {
        $arrJson = [0, "Tidak ditemukan daftar pembayaran!"];
    }
    else
    {
        $tab  = "<br>";
        $tab .= "<table border='0' cellpadding='0' cellspacing='0' width='1100'>";
        $tab .= "<tr><td align='right'>";
        $tab .= "<a onclick='refreshList()' style='cursor: pointer;'><img src='../images/ico/refresh.png' border='0'> muat ulang</a>";
        $tab .= "</td></tr></table>";
        $tab .= "<table id='tabDaftar' border='1' cellpadding='5' style='border-collapse: collapse; border-width: 1px;' cellspacing='0' width='1100'>";
        $tab .= "<tr style='height: 20px;'>";
	    $tab .= "<td class='header' width='40' align='center'>No</td>";
	    $tab .= "<td class='header' width='50'>&nbsp;</td>";
        $tab .= "<td class='header' width='390'>Penerimaan</td>";
	    $tab .= "<td class='header' width='150' align='center'>Cicilan/Tagihan</td>";
	    $tab .= "<td class='header' width='120' align='center'>Diskon</td>";
        $tab .= "<td class='header' width='350' align='center'>Keterangan</td>";
        $tab .= "</tr>";

        $no = 0;
        $total = 0;
        $validTrans = true;
        while($row = mysqli_fetch_row($res))
        {
            $no += 1;
            $bayar = FormatRupiah($row[3]);
            $diskon = FormatRupiah(0);
            $kategori = $row[2];
            $idPayment = $row[0];
            $payment = $row[1];
            $informasi = $row[4];

            $idBesarJtt = 0;
            $besar = 0;
            $jumlah = 0;
            $lunas = 0;
            $sisa = 0;
            $cicilan = 0;
            $fcicilan = "";
            $infoValid = "";
            $infoBesar = "";
            $disabledDiskon = "";

            if ($kategori == "JTT" || $kategori == "CSWJB")
            {
                if ($kategori == "JTT")
                {
                    $sql = "SELECT replid, besar, lunas, cicilan
                              FROM jbsfina.besarjtt
                             WHERE nis = '$noid'
                               AND idpenerimaan = '$idPayment'
                               AND info2 = '".$idTahunBuku."'";
                }
                else if ($kategori == "CSWJB")
                {
                    $sql = "SELECT b.replid, b.besar, b.lunas, b.cicilan 
                              FROM jbsfina.besarjttcalon b, jbsakad.calonsiswa cs
                             WHERE b.idcalon = cs.replid
                               AND cs.nopendaftaran = '$noid'
                               AND b.idpenerimaan = '$idPayment'
                               AND b.info2 = '".$idTahunBuku."'";
                }

                $res2 = QueryDb($sql);
                $num2 = mysqli_num_rows($res2);
                if ($num2 == 0)
                {
                    $validTrans = false;
                    $infoValid = "<br><span style='font-size: 10px; color: red;'>(besar pembayaran belum ditentukan)</span>";
                }
                else
                {
                    $row2 = mysqli_fetch_row($res2);
                    $idBesarJtt = $row2[0];
                    $besar = $row2[1];
                    $lunas = $row2[2];
                    $cicilan = $row2[3];
                    $fcicilan = FormatRupiah($row2[3]);

                    if ($kategori == "JTT")
                    {
                        $sql = "SELECT SUM(jumlah + info1)
                                  FROM jbsfina.penerimaanjtt
                                 WHERE idbesarjtt = $idBesarJtt";
                    }
                    else
                    {
                        $sql = "SELECT SUM(jumlah + info1)
                                  FROM jbsfina.penerimaanjttcalon
                                 WHERE idbesarjttcalon = $idBesarJtt";
                    }
                    $res2 = QueryDb($sql);
                    $row2 = mysqli_fetch_row($res2);
                    $jumlah = $row2[0];

                    $sisa = $besar - $jumlah;
                    if ($lunas == 0)
                        $infoBesar = "<br><span style='color: #777'>Besar tagihan: <b>" . FormatRupiah($besar) . "</b> Sisa: <b>" . FormatRupiah($sisa) . "</b></span>";
                    else
                        $infoBesar = "<br><span style='color: blue; font-weight: bold;'>LUNAS</span>";
                }
            }
            else
            {
                $disabledDiskon = "disabled";
            }

            $checked = "checked";
            $readonly = "";
            $disabled = "";
            if ($lunas == 1)
            {
                $checked = "";
                $readonly = "readonly";
                $disabled = "disabled";
            }
            else
            {
                $total += $cicilan;
            }

            $tab .= "<tr>";
            $tab .= "<td align='center'>$no</td>";
            $tab .= "<td align='center' valign='middle' style='background-color: #3994c6'>";
            $tab .= "<input type='checkbox' id='chPayment-$no' name='chPayment-$no' $checked $disabled $readonly onchange='chPaymentChange($no); calculatePay();' >";
            $tab .= "<input type='hidden' id='idPayment-$no' name='idPayment-$no' value='".$row[0]."'>";
            $tab .= "<input type='hidden' id='payment-$no' name='payment-$no' value='$payment'>";
            $tab .= "<input type='hidden' id='kategori-$no' name='kategori-$no' value='".$row[2]."'>";
            $tab .= "<input type='hidden' id='besar-$no' name='besar-$no' value='$besar'>";
            $tab .= "<input type='hidden' id='idbesarjtt-$no' name='idbesarjtt-$no' value='$idBesarJtt'>";
            $tab .= "<input type='hidden' id='jumlah-$no' name='jumlah-$no' value='$jumlah'>";
            $tab .= "<input type='hidden' id='sisa-$no' name='sisa-$no' value='$sisa'>";
            $tab .= "<input type='hidden' id='lunas-$no' name='lunas-$no' value='$lunas'>";
            $tab .= "</td>";
            $tab .= "<td align='left' valign='top'>$row[1] $infoValid $infoBesar</td>";
            $tab .= "<td align='center' valign='top'>";
            $element = "bayar-$no";
            $tab .= "<input type='text' $disabled $readonly id='$element' name='$element' value='$fcicilan' style='width: 115px' onblur=\"calculatePay(); formatRupiah('$element');\" onfocus=\"unformatRupiah('$element')\">";
            $tab .= "</td>";
            $tab .= "<td align='center' valign='top'>";
            $element = "diskon-$no";
            $tab .= "<input type='text' $disabledDiskon $disabled $readonly id='$element' name='$element' value='$diskon' style='width: 115px' onblur=\"calculatePay(); formatRupiah('$element');\" onfocus=\"unformatRupiah('$element')\">";
            $tab .= "</td>";
            $tab .= "<td align='left' valign='top'>";
            $element = "keterangan-$no";
            $tab .= "<input type='text' $disabled $readonly id='$element' name='$element' value='' style='width: 330px'><br>";
            $tab .= "<i>$informasi</i>";
            $tab .= "</td>";
            $tab .= "</tr>";
        }
        $tab .= "<tr style='width: 25px; font-weight: bold; background-color: #eaeaea'>";
        $tab .= "<td colspan='3' align='right' style='background-color: #ddd;'>TOTAL PEMBAYARAN</td>";
        $tab .= "<td colspan='2' align='center' style='background-color: #ffb547'><span id='spTotalBayar' style='font-size: 14px;'>";
        $tab .= FormatRupiah($total);
        $tab .= "</span></td>";
        $tab .= "<td style='background-color: #ddd;'><span id='spInfoBayar' style='font-weight: normal; color: red;'></span></td>";
        $tab .= "</tr>";
        $tab .= "</table><br>";
        $tab .= "<input type='hidden' id='ndata' name='ndata' value='$num'>";
        $tab .= "<input type='hidden' id='total' name='total' value='$total'>";

        if ($validTrans)
        {
            $checked = $smsinfo == 1 ? "checked" : "";

            $tab .= "<table border='0'>";
            $tab .= "<tr><td valign='top'>";
            $tab .= "<input type='checkbox' id='smsinfo' name='smsinfo' $checked>&nbsp;Notifikasi SMS | Telegram | Jendela Sekolah<br><br>";
            $tab .= "Keterangan Transaksi:<br>";
            $tab .= "<textarea id='ktransaksi' name='ktransaksi' rows='2' cols='50'></textarea>";

            $tab .= "</td>";
            $tab .= "<td valign='middle'>";
            $tab .= "<input type='submit' class='but' value='Simpan Pembayaran' style='height: 40px; width: 160px;'>";
            $tab .= "</td>";
            $tab .= "<td valign='middle'>";
            $tab .= "<input type='button' class='but' value='Tutup' onclick='tutupPembayaran()' style='height: 40px; width: 60px;'>";
            $tab .= "</td></tr>";
            $tab .= "</table>";
        }
        else
        {
            $tab .= "<span style='font-color: red; font-style: italic'>(tidak dapat menyimpan transaksi)</span>";
        }

        if ($no > 0)
            $arrJson = [$num, $tab];
        else
            $arrJson = [0, "Tidak ada daftar pembayaran yang aktif!"];
    }
    echo json_encode($arrJson, JSON_THROW_ON_ERROR);
}

function ShowAccYear()
{
    global $departemen;

    $sql = "SELECT replid AS id, tahunbuku
              FROM tahunbuku
             WHERE aktif = 1
               AND departemen='$departemen'";
    $result = QueryDb($sql);
    if (mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_array($result);
        echo "<input type='text' name='tahunbuku' id='tahunbuku' size='30' readonly style='background-color:#daefff; font-size:14px;' value='" . $row['tahunbuku'] . "'/>";
        echo "<input type='hidden' name='idtahunbuku' id='idtahunbuku' value='" . $row['id'] . "'/>";
    }
    else
    {
        echo "<input type='text' name='tahunbuku' id='tahunbuku' size='30' readonly style='background-color:#daefff; font-size:14px;' value=''/>";
        echo "<input type='hidden' name='idtahunbuku' id='idtahunbuku' value='0'/>";
    }
}
?>