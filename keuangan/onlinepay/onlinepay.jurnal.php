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
function OnlinePay_SimpanJurnal($idtahunbuku, $tanggal, $transaksi, $nokas, $keterangan, $idpetugas, $petugas, $sumber)
{
    $sql = "INSERT INTO jurnal
			   SET idtahunbuku=$idtahunbuku, tanggal='$tanggal', transaksi='$transaksi',
				   nokas='$nokas', keterangan='$keterangan',
				   idpetugas=$idpetugas, petugas='$petugas', sumber='$sumber'";
    QueryDbEx($sql);

    $sql = "SELECT LAST_INSERT_ID()";
    $res = QueryDbEx($sql);
    $row = @mysqli_fetch_row($res);

    return $row[0];
}

function OnlinePay_SimpanDetailJurnal($idjurnal, $align, $koderek, $jumlah)
{
    if ($align == "D")
        $sql = "INSERT INTO jurnaldetail SET idjurnal=$idjurnal,koderek='$koderek',debet=$jumlah";
    else
        $sql = "INSERT INTO jurnaldetail SET idjurnal=$idjurnal,koderek='$koderek',kredit=$jumlah";

    QueryDbEx($sql);
}
?>