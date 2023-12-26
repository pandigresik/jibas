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
require_once('../library/departemen.php');
require_once('../include/errorhandler.php');
require_once('../library/jurnal.php');
require_once('inputbayar.func.php');

$op = $_REQUEST["op"];
if ($op == "getpenerimaan")
{
    $departemen = $_REQUEST["departemen"];
    $idKategori = $_REQUEST["idkategori"];

    OpenDb();
    echo ShowSelectPenerimaan($departemen, $idKategori);
    CloseDb();
}
else if ($op == "gettingkat")
{
    $departemen = $_REQUEST["departemen"];
    $idKategori = $_REQUEST["idkategori"];

    OpenDb();

    if ($idKategori == "JTT")
        echo ShowSelectTingkatSiswa($departemen);
    else
        echo ShowSelectProsesCalonSiswa($departemen);

    CloseDb();
}
else if ($op == "getkelas")
{
    $departemen = $_REQUEST["departemen"];
    $idKategori = $_REQUEST["idkategori"];
    $idTingkat = $_REQUEST["idtingkat"];

    OpenDb();

    if ($idKategori == "JTT")
        echo ShowSelectKelasSiswa($departemen, $idTingkat);
    else
        echo ShowSelectKelompokCalonSiswa($departemen, $idTingkat);

    CloseDb();
}
else if ($op == "setbayar")
{
    $departemen = $_REQUEST["departemen"];
    $idKategori = $_REQUEST["idkategori"];
    $idPenerimaan = $_REQUEST["idpenerimaan"];
    $idTingkat = $_REQUEST["idtingkat"];
    $idKelas = $_REQUEST["idkelas"];
    $besar = $_REQUEST["besar"];
    $cicilan = $_REQUEST["cicilan"];
    $cicilanPertama = $_REQUEST["cicilanpertama"];

    OpenDb();

    if ($idKategori == "JTT")
        echo SimpanBesarSiswa($departemen, $idPenerimaan, $idTingkat, $idKelas, $besar, $cicilan, $cicilanPertama);
    else if ($idKategori == "CSWJB")
        echo SimpanBesarCalonSiswa($departemen, $idPenerimaan, $idTingkat, $idKelas, $besar, $cicilan, $cicilanPertama);

    CloseDb();

}























?>