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
function SetAktifDeposit()
{
    $idDeposit = $_REQUEST["iddeposit"];
    $newAktif = $_REQUEST["newaktif"];

    $sql = "UPDATE jbsfina.bankdeposit SET aktif = $newAktif WHERE replid = $idDeposit";
    QueryDb($sql);

    if ($newAktif == 1)
        echo "<a href='#' onclick='setDepositAktif($idDeposit, 0)'><img src='../images/ico/aktif.png' title='klik set non aktif'></a>";
    else
        echo "<a href='#' onclick='setDepositAktif($idDeposit, 1)'><img src='../images/ico/nonaktif.png' title='klik set aktif'></a>";
}

function HapusDeposit()
{
    $idDeposit = $_REQUEST["iddeposit"];

    $sql = "SELECT COUNT(*) 
              FROM jbsfina.banksaldo
             WHERE iddeposit = $idDeposit";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $nData = $row[0];

    if ($nData > 0)
    {
        echo "[\"-1\",\"Tidak dapat menghapus data ini karena sudah digunakan!\"]";
    }
    else
    {
        $sql = "DELETE FROM jbsfina.bankdeposit
                 WHERE replid = $idDeposit";
        QueryDb($sql);

        echo "[\"1\",\"OK\"]";
    }
}
?>