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
function ReadParams()
{
    global $departemen, $semester, $tingkat, $tahunajaran, $pelajaran, $kelas, $nis, $nama, $komentar;

    if (isset($_REQUEST['departemen']))
        $departemen = $_REQUEST['departemen'];

    if (isset($_REQUEST['semester']))
        $semester = $_REQUEST['semester'];

    if (isset($_REQUEST['tingkat']))
        $tingkat = $_REQUEST['tingkat'];

    if (isset($_REQUEST['tahunajaran']))
        $tahunajaran = $_REQUEST['tahunajaran'];

    if (isset($_REQUEST['pelajaran']))
        $pelajaran = $_REQUEST['pelajaran'];

    if (isset($_REQUEST['kelas']))
        $kelas = $_REQUEST['kelas'];

    if (isset($_REQUEST['nis']))
        $nis = $_REQUEST['nis'];

    if (isset($_REQUEST['komentar']))
        $komentar = CQ($_REQUEST['komentar']);

    $sql = "SELECT nama FROM jbsakad.siswa WHERE nis = '".$nis."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $nama = $row[0];
}

function ShowUserInfo()
{
    global $nis, $nama;
?>
    <table border="0" cellpadding="2" width="400" style="font-weight: bold; color: white; background-color: #4a88cc">
        <tr>
            <td width="60">NIS:</td>
            <td><?=$nis?></td>
        </tr>
        <tr>
            <td>Nama:</td>
            <td><?=$nama?></td>
        </tr>
    </table>
<?php
}

function SafeText($text)
{
    $text = str_replace("'", "`", (string) $text);
    return $text;
}

function SimpanData()
{
    if (!isset($_REQUEST['Simpan']))
        return;

    $success = true;
    BeginTrans();

    $naspek = $_REQUEST['naspek'];
    for($i = 0; $success && $i < $naspek; $i++)
    {
        $param = "idnap$i";
        $idnap = $_REQUEST[$param];

        if ($idnap == 0)
            continue;

        $param = "komentar$i";
        $komentar = SafeText($_REQUEST[$param]);

        $sql = "UPDATE jbsakad.nap SET komentar = '$komentar' WHERE replid = '".$idnap."'";
        //echo "$sql<br>";
        QueryDbTrans($sql, $success);
    }

    if ($success)
    {
        CommitTrans();
    }
    else
    {
        RollbackTrans();
    }
}

function ShowListKomentar($idpelajaran, $idtingkat, $kdaspek, $no)
{
    $select = GetListKomentar($idpelajaran, $idtingkat, $kdaspek);
    echo "<div id='divpilihkomentar$no'>$select</div>";
}

function GetListKomentar($idpelajaran, $idtingkat, $kdaspek, $no)
{
    $opt = "";

    $sql = "SELECT replid, komentar
              FROM jbsakad.pilihkomenpel
             WHERE idpelajaran = '$idpelajaran'
               AND idtingkat = '$idtingkat'
               AND dasarpenilaian = '".$kdaspek."'";
    $res2 = QueryDb($sql);
    $numlen = strlen(mysqli_num_rows($res2));
    $cnt = 0;
    while($row2 = mysqli_fetch_row($res2))
    {
        $cnt += 1;
        $nocnt = str_pad($cnt, $numlen, "0", STR_PAD_LEFT);

        $replid = $row2[0];
        $komentar = $row2[1];

        $komentar = strip_tags((string) $komentar);
        if (strlen($komentar) > 50)
            $komentar = substr($komentar, 0, 50) . " ..";

        $opt .= "<option value='$replid'>$nocnt. $komentar</option>";
    }

    return "<select name='pilihkomentar$no' id='pilihkomentar$no' style='width: 300px;'>$opt</select>";
}

function GetKomentar($replid)
{
    $sql = "SELECT komentar
              FROM jbsakad.pilihkomenpel
             WHERE replid = '".$replid."'";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        return $row[0];

    return "";
}
?>