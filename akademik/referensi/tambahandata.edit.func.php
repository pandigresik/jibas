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
    global $replid, $kolom, $jenis, $keterangan, $departemen, $urutan;

    $replid = $_REQUEST['replid'];

    if (isset($_REQUEST['departemen']))
        $departemen = $_REQUEST['departemen'];

    if (isset($_REQUEST['kolom']))
        $kolom = CQ($_REQUEST['kolom']);

    if (isset($_REQUEST['jenis']))
        $jenis = $_REQUEST['jenis'];

    if (isset($_REQUEST['urutan']))
        $urutan = $_REQUEST['urutan'];

    if (isset($_REQUEST['keterangan']))
        $keterangan = CQ($_REQUEST['keterangan']);
}

function ReadData()
{
    global $replid, $kolom, $jenis, $keterangan, $departemen, $urutan;

    OpenDb();

    $sql = "SELECT kolom, jenis, keterangan, departemen, urutan 
              FROM tambahandata 
             WHERE replid = '".$replid."'";
    $result = QueryDb($sql);
    if (mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_row($result);
        $kolom = $row[0];
        $jenis = $row[1];
        $keterangan = $row[2];
        $departemen = $row[3];
        $urutan = $row[4];
    }

    CloseDb();
}

function SaveData()
{
    global $replid, $kolom, $jenis, $keterangan, $departemen, $urutan;

    global $ERROR_MSG;

    $ERROR_MSG = "";
    if (!isset($_REQUEST['Simpan']))
        return;

    OpenDb();

    $sql = "SELECT * 
              FROM tambahandata 
             WHERE kolom = '$kolom' 
               AND departemen = '$departemen'
               AND replid <> '$replid'";
    $result = QueryDb($sql);

    if (mysqli_num_rows($result) > 0)
    {
        CloseDb();
        $ERROR_MSG = "Nama kolom $kolom sudah digunakan!";
     }
    else
    {
        $sql = "UPDATE tambahandata
                   SET kolom = '$kolom',
                       jenis = '$jenis', 
                       keterangan = '$keterangan',
                       urutan = '$urutan'
                 WHERE replid = '".$replid."'";
        $result = QueryDb($sql);
        if ($result)
        { ?>
            <script language="javascript">
                opener.refresh();
                window.close();
            </script>
        <?php
        }
        CloseDb();
        exit();
    }
}
?>
