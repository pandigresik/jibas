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

    global $semester, $pelajaran, $kelas, $nis;

    $arrjenis = ["SPI", "SOS"];
    $arrnmjenis = ["Spiritual", "Sosial"];

    $success = true;
    BeginTrans();

    for($i = 0; $success && $i < count($arrjenis); $i++)
    {
        $jenis = $arrjenis[$i];
        $lwjenis = strtolower($jenis);
        $nmjenis = $arrnmjenis[$i];

        $param = "predikat$lwjenis";
        $predikat = $_REQUEST[$param];

        $param = "komentar$lwjenis";
        $komentar = SafeText($_REQUEST[$param]);

        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.komenrapor
                 WHERE nis = '$nis'
                   AND idsemester = '$semester'
                   AND idkelas = '$kelas'
                   AND jenis = '".$jenis."'";
        //echo "$sql<br>";
        $ndata = (int) FetchSingle($sql);

        if ($ndata == 0)
            $sql = "INSERT INTO jbsakad.komenrapor
                       SET nis = '$nis', idsemester = '$semester', idkelas = '$kelas',
                           predikat = '$predikat', komentar = '$komentar', jenis = '".$jenis."'";
        else
            $sql = "UPDATE jbsakad.komenrapor
                       SET predikat = '$predikat', komentar = '$komentar', jenis = '$jenis'
                     WHERE nis = '$nis' AND idsemester = '$semester' AND idkelas = '".$kelas."'";
        //echo "$sql<br>";

        QueryDbTrans($sql, $success);
    }

    if ($success)
    {
        //echo "OK";
        //RollbackTrans();
        CommitTrans();
    }
    else
    {
        //echo "FAILED";
        RollbackTrans();
    }
}

function ShowListKomentar($idpelajaran, $idtingkat, $jenis)
{
    $select = GetListKomentar($idpelajaran, $idtingkat, $jenis);
    echo "<div id='divpilihkomentar$jenis'>$select</div>";
}

function GetListKomentar($idpelajaran, $idtingkat, $jenis)
{
    $opt = "";

    $sql = "SELECT replid, komentar
              FROM jbsakad.pilihkomensos
             WHERE idpelajaran = '$idpelajaran'
               AND idtingkat = '$idtingkat'
               AND jenis = '".$jenis."'";
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

    return "<select name='pilihkomentar$jenis' id='pilihkomentar$jenis' style='width: 300px;'>$opt</select>";
}

function GetKomentar($replid)
{
    $sql = "SELECT komentar
              FROM jbsakad.pilihkomensos
             WHERE replid = '".$replid."'";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        return $row[0];

    return "";
}
?>