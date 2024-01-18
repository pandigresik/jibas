<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 15 (January 02, 2019)
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

function ShowSelectKategori()
{
    global $idKategori;

    if ($idKategori == "") $idKategori = "JTT";
    ?>
    <select name="idkategori" id="idkategori" onChange="change_kategori();" style="width:200px" onKeyPress="return focusNext('departemen', event);">
        <option value="JTT" <?= $idKategori == "JTT" ? "selected" : "" ?>>Iuran Wajib Siswa</option>
        <option value="CSWJB" <?= $idKategori == "CSWJB" ? "selected" : "" ?>>Iuran Wajib Calon Siswa</option>
    </select>
<?php
}

function ShowSelectDepartemen()
{
    global $departemen;
?>
    <select name="departemen" id="departemen" onChange="change_kategori()" style="width:200px">
<?php     $dep = getDepartemen(getAccess());
        foreach($dep as $value)
        {
            if ($departemen == "") $departemen = $value;
            ?>
            <option value="<?= $value ?>" <?= $departemen == $value ? "selected" : "" ?> >
                <?=$value ?>
            </option>
<?php 	} ?>
    </select>
<?php
}

function ShowSelectPenerimaan($departemen, $idKategori)
{
    $sql = "SELECT replid, nama 
              FROM datapenerimaan 
             WHERE idkategori = '$idKategori' 
               AND departemen = '$departemen'
               AND aktif = 1
             ORDER BY nama"; ?>
    <select name="penerimaan" id="penerimaan" style="font-size: 14px; background-color: #fdffc7; width:200px">
<?php      $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            ?>
            <option value="<?= $row[0] ?>" ><?= $row[1] ?></option>
<?php 	} ?>
    </select>
    <?php
}

function ShowSelectTingkatSiswa($departemen)
{
    global $idTingkat;

    $sql = "SELECT replid, tingkat
              FROM jbsakad.tingkat
             WHERE departemen = '$departemen'
               AND aktif = 1
             ORDER BY urutan";
    ?>
    <select name="tingkat" id="tingkat" style="width:200px" onchange="change_tingkat()">
<?php      $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            if ($idTingkat == 0) $idTingkat = $row[0];
            ?>
            <option value="<?= $row[0] ?>" ><?= $row[1] ?></option>
<?php 	} ?>
    </select>
    <?php
}

function ShowSelectKelasSiswa($departemen, $idTingkat)
{
    $sql = "SELECT replid
              FROM jbsakad.tahunajaran
             WHERE departemen = '$departemen'
               AND aktif = 1";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return "";

    $row = mysqli_fetch_row($res);
    $idTahunAjaran = $row[0];

    $sql = "SELECT replid, kelas
              FROM jbsakad.kelas
             WHERE idtingkat = $idTingkat
               AND idtahunajaran = $idTahunAjaran
               AND aktif = 1
             ORDER BY kelas";
    $res = QueryDb($sql);
    echo "<table border='0' cellpadding='4' cellspacing='0'>";
    $n = 0;
    while($row = mysqli_fetch_row($res))
    {
        $n += 1;

        $id = $row[0];
        $kelas = $row[1];

        echo "<tr>";
        echo "<td align='left'>";
        echo "<input type='checkbox' id='ch$n'>&nbsp;$kelas";
        echo "<input type='hidden' id='id$n' value='$id'>";
        echo "</td></tr>";
    }
    echo "</table>";
    echo "<input type='hidden' id='nkelas' value='$n'>";
}

function ShowSelectProsesCalonSiswa($departemen)
{
    global $idTingkat;

    $sql = "SELECT replid, proses
              FROM jbsakad.prosespenerimaansiswa
             WHERE departemen = '$departemen'
               AND aktif = 1
             ORDER BY proses";
    ?>
    <select name="tingkat" id="tingkat" style="width:200px" onchange="change_tingkat()">
<?php      $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            if ($idTingkat == 0) $idTingkat = $row[0];
            ?>
            <option value="<?= $row[0] ?>" ><?= $row[1] ?></option>
<?php 	} ?>
    </select>
    <?php
}

function ShowSelectKelompokCalonSiswa($departemen, $idTingkat)
{
    $sql = "SELECT replid, kelompok
              FROM jbsakad.kelompokcalonsiswa
             WHERE idproses = $idTingkat
             ORDER BY kelompok";
    $res = QueryDb($sql);
    echo "<table border='0' cellpadding='4' cellspacing='0'>";
    $n = 0;
    while($row = mysqli_fetch_row($res))
    {
        $n += 1;

        $id = $row[0];
        $kelompok = $row[1];

        echo "<tr>";
        echo "<td align='left'>";
        echo "<input type='checkbox' id='ch$n'>&nbsp;$kelompok";
        echo "<input type='hidden' id='id$n' value='$id'>";
        echo "</td></tr>";
    }
    echo "</table>";
    echo "<input type='hidden' id='nkelas' value='$n'>";
}

function SimpanBesarSiswa($departemen, $idPenerimaan, $idTingkat, $idKelas, $besar, $cicilan, $cicilanPertama)
{
    $sql = "SELECT replid AS id, tahunbuku
              FROM tahunbuku
             WHERE aktif = 1
               AND departemen = '".$departemen."'";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return "SIMPAN: Tidak ada tahun buku yang aktif di departemen $departemen";

    $row = mysqli_fetch_row($res);
    $idTahunBuku = $row[0];

    $sql = "SELECT DISTINCT nis
              FROM jbsakad.siswa
             WHERE idkelas IN ($idKelas)
               AND aktif = 1";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return "SIMPAN: Tidak ditemukan data siswa";

    $lsNis = [];
    while($row = mysqli_fetch_row($res))
    {
        $nis = $row[0];

        $sql = "SELECT COUNT(*) 
                  FROM jbsfina.besarjtt 
                 WHERE nis = '$nis'
                   AND idpenerimaan = '$idPenerimaan'
                   AND info2 = '".$idTahunBuku."'";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($res2);
        $ndata = $row2[0];

        if ($ndata == 0)
        {
            $lsNis[] = $nis;
        }
    }

    $nSiswa = count($lsNis);
    if ($nSiswa == 0)
        return "SIMPAN: Tidak ada siswa yang belum di setting besar pembayarannya!";

    // Ambil informasi kode rekening berdasarkan jenis penerimaan
    $sql = "SELECT rekkas, rekpiutang, rekpendapatan, nama 
              FROM datapenerimaan 
             WHERE replid ='$idPenerimaan'";
    $row = FetchSingleRow($sql);
    $rekkas = $row[0];
    $rekpiutang = $row[1];
    $rekpendapatan = $row[2];
    $namapenerimaan = $row[3];

    $sql = "SELECT replid, awalan, cacah 
              FROM jbsfina.tahunbuku 
             WHERE departemen = '$departemen'
               AND aktif = 1";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return "SIMPAN: Belum ada tahun buku yang aktif untuk departemen $departemen!";
    $row = mysqli_fetch_row($res);
    $idTahunBuku = $row[0];
    $awalan = $row[1];
    $cacah = $row[2];

    $pengguna = getUserName();
    
    $success = true;
    BeginTrans();

    for($i = 0; $success && $i < $nSiswa; $i++)
    {
        $nis = $lsNis[$i];

        $sql = "SELECT nama FROM jbsakad.siswa WHERE nis = '".$nis."'";
        $row = FetchSingleRow($sql);
        $namasiswa = $row[0];

        $cacah += 1; // Increment cacah
        $nokas = $awalan . rpad($cacah, "0", 6); // Form nomor kas

        // tanggal & petugas pendata & keterangan
        $tcicilan = date("Y-m-d");
        $idpetugas = getIdUser();
        $petugas = getUserName();
        $keterangan = "Pendataan besar pembayaran $namapenerimaan siswa $namasiswa ($nis)";

        // status lunas
        $lunas = 0; // belum lunas
        if ($besar == 0)
            $lunas = 2; // GRATIS

        // simpan ke table jurnal
        $idjurnal = 0;
        if ($success)
            $success = SimpanJurnal($idTahunBuku, $tcicilan, $keterangan, $nokas, "", $idpetugas, $petugas, "penerimaanjtt", $idjurnal);

        // simpan ke tabel besarjtt
        if ($success)
        {
            $sql = "INSERT INTO besarjtt SET nis='$nis', idpenerimaan='$idPenerimaan', 
			        besar='$besar', cicilan='$cicilan', keterangan='', lunas=$lunas, 
					pengguna='$pengguna', info1='$idjurnal', info2='$idTahunBuku'";
            QueryDbTrans($sql, $success);
        }

        $idBesarJtt = 0;
        if ($success)
        {
            $sql = "SELECT LAST_INSERT_ID()";
            $idBesarJtt = FetchSingle($sql);
        }

        // simpan ke table jurnaldetail
        if ($success)
            $success = SimpanDetailJurnal($idjurnal, "D", $rekpiutang, $besar);

        if ($success)
            $success = SimpanDetailJurnal($idjurnal, "K", $rekpendapatan, $besar);

        if ($cicilanPertama == 0)
            continue;

        // SET CICILAN PERTAMA Rp 0
        $ketjurnal = "Pendataan besar pembayaran $namapenerimaan siswa $namasiswa ($nis)";

        // -- Ambil awalan untuk bikin nokas -------------
        $cacah += 1; //increment cacah
        $nokas = $awalan . rpad($cacah, "0", 6); //form nokas

        // -- Simpan ke jurnal -----------------------------------------------
        $idjurnal = 0;
        $success = SimpanJurnal($idTahunBuku, $tcicilan, $ketjurnal, $nokas, "", $idpetugas, $petugas, "penerimaanjtt", $idjurnal);

        //-- Simpan ke jurnaldetail ------------------------------------------
        if ($success)
            $success = SimpanDetailJurnal($idjurnal, "D", $rekkas, 0);
        if ($success)
            $success = SimpanDetailJurnal($idjurnal, "K", $rekpiutang, 0);

        // -- simpan data cicilan di penerimaanjtt ---------------------------
        $sql = "INSERT INTO penerimaanjtt SET idbesarjtt='$idBesarJtt', idjurnal='$idjurnal', tanggal='$tcicilan', 
				jumlah='0', keterangan='', petugas='$petugas', info1='0'";
        if ($success)
            QueryDbTrans($sql, $success);
    }

    //increment cacah di tahunbuku
    if ($success)
    {
        $sql = "UPDATE tahunbuku SET cacah = $cacah WHERE replid=$idTahunBuku";
        QueryDbTrans($sql, $success);
    }

    if ($success)
    {
        CommitTrans();
        return "SIMPAN: Berhasil menyimpan besar pembayaran untuk $nSiswa siswa";
    }

    RollbackTrans();
    return "SIMPAN: Gagal menyimpan besar pembayaran. Tidak ada data yang disimpan";
}

function SimpanBesarCalonSiswa($departemen, $idPenerimaan, $idTingkat, $idKelas, $besar, $cicilan, $cicilanPertama)
{
    $sql = "SELECT replid AS id, tahunbuku
              FROM tahunbuku
             WHERE aktif = 1
               AND departemen = '".$departemen."'";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return "SIMPAN: Tidak ada tahun buku yang aktif di departemen $departemen";

    $row = mysqli_fetch_row($res);
    $idTahunBuku = $row[0];

    $sql = "SELECT DISTINCT replid
              FROM jbsakad.calonsiswa
             WHERE idkelompok IN ($idKelas)
               AND aktif = 1";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return "SIMPAN: Tidak ditemukan data calon siswa";

    $lsIdCalon = [];
    while($row = mysqli_fetch_row($res))
    {
        $idCalon = $row[0];

        $sql = "SELECT COUNT(*) 
                  FROM jbsfina.besarjttcalon 
                 WHERE idcalon = '$idCalon'
                   AND idpenerimaan = '$idPenerimaan'
                   AND info2 = '".$idTahunBuku."'";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($res2);
        $ndata = $row2[0];

        if ($ndata == 0)
        {
            $lsIdCalon[] = $idCalon;
        }
    }

    $nSiswa = count($lsIdCalon);
    if ($nSiswa == 0)
        return "SIMPAN: Tidak ada calon siswa yang belum di setting besar pembayarannya!";

    // Ambil informasi kode rekening berdasarkan jenis penerimaan
    $sql = "SELECT rekkas, rekpiutang, rekpendapatan, nama 
              FROM datapenerimaan 
             WHERE replid='$idPenerimaan'";
    $row = FetchSingleRow($sql);
    $rekkas = $row[0];
    $rekpiutang = $row[1];
    $rekpendapatan = $row[2];
    $namapenerimaan = $row[3];

    $sql = "SELECT replid, awalan, cacah 
              FROM jbsfina.tahunbuku 
             WHERE aktif = 1
               AND departemen = '".$departemen."'";
    $row = FetchSingleRow($sql);
    $idTahunBuku = $row[0];
    $awalan = $row[1];
    $cacah = $row[2];

    $pengguna = getUserName();

    $success = true;
    BeginTrans();

    for($i = 0; $success && $i < $nSiswa; $i++)
    {
        $idCalon = $lsIdCalon[$i];

        $sql = "SELECT nama, nopendaftaran FROM jbsakad.calonsiswa WHERE replid = '".$idCalon."'";
        $row = FetchSingleRow($sql);
        $namasiswa = $row[0];
        $nopendaftaran = $row[1];

        $cacah += 1; // Increment cacah
        $nokas = $awalan . rpad($cacah, "0", 6); // Form nomor kas

        // tanggal & petugas pendata & keterangan
        $tcicilan = date("Y-m-d");
        $idpetugas = getIdUser();
        $petugas = getUserName();
        $keterangan = "Pendataan besar pembayaran $namapenerimaan calon siswa $namasiswa ($nopendaftaran)";

        // status lunas
        $lunas = 0; // belum lunas
        if ($besar == 0)
            $lunas = 2; // GRATIS

        // simpan ke table jurnal
        $idjurnal = 0;
        if ($success)
            $success = SimpanJurnal($idTahunBuku, $tcicilan, $keterangan, $nokas, "", $idpetugas, $petugas, "penerimaanjttcalon", $idjurnal);

        // simpan ke tabel besarjttcalon
        if ($success)
        {
            $sql = "INSERT INTO besarjttcalon SET idcalon='$idCalon', idpenerimaan='$idPenerimaan', 
			        besar='$besar', cicilan='$cicilan', keterangan='', lunas=$lunas, 
					pengguna='$pengguna', info1='$idjurnal', info2='$idTahunBuku'";
            QueryDbTrans($sql, $success);
        }

        $idBesarJtt = 0;
        if ($success)
        {
            $sql = "SELECT LAST_INSERT_ID()";
            $idBesarJtt = FetchSingle($sql);
        }

        // simpan ke table jurnaldetail
        if ($success)
            $success = SimpanDetailJurnal($idjurnal, "D", $rekpiutang, $besar);

        if ($success)
            $success = SimpanDetailJurnal($idjurnal, "K", $rekpendapatan, $besar);

        if ($cicilanPertama == 0)
            continue;

        // SET CICILAN PERTAMA Rp 0
        $ketjurnal = "Pendataan besar pembayaran $namapenerimaan calon siswa $namasiswa ($nopendaftaran)";

        // -- Ambil awalan untuk bikin nokas -------------
        $cacah += 1; //increment cacah
        $nokas = $awalan . rpad($cacah, "0", 6); //form nokas

        // -- Simpan ke jurnal -----------------------------------------------
        $idjurnal = 0;
        $success = SimpanJurnal($idTahunBuku, $tcicilan, $ketjurnal, $nokas, "", $idpetugas, $petugas, "penerimaanjttcalon", $idjurnal);

        //-- Simpan ke jurnaldetail ------------------------------------------
        if ($success)
            $success = SimpanDetailJurnal($idjurnal, "D", $rekkas, 0);
        if ($success)
            $success = SimpanDetailJurnal($idjurnal, "K", $rekpiutang, 0);

        // -- simpan data cicilan di penerimaanjttcalon ---------------------------
        $sql = "INSERT INTO penerimaanjttcalon SET idbesarjttcalon='$idBesarJtt', idjurnal='$idjurnal', tanggal='$tcicilan', 
				jumlah='0', keterangan='', petugas='$petugas', info1='0'";

        if ($success)
            QueryDbTrans($sql, $success);
    }

    //increment cacah di tahunbuku
    if ($success)
    {
        $sql = "UPDATE tahunbuku SET cacah = $cacah WHERE replid=$idTahunBuku";
        QueryDbTrans($sql, $success);
    }

    if ($success)
    {
        CommitTrans();
        return "SIMPAN: Berhasil menyimpan besar pembayaran untuk $nSiswa calon siswa";
    }

    RollbackTrans();
    return "SIMPAN: Gagal menyimpan besar pembayaran.  Tidak ada data yang disimpan";
}























?>