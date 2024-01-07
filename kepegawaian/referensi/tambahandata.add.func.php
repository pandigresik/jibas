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
    global $kolom, $jenis, $keterangan, $urutan;

    if (isset($_REQUEST['kolom']))
        $kolom = CQ($_REQUEST['kolom']);

    if (isset($_REQUEST['urutan']))
        $urutan = CQ($_REQUEST['urutan']);

    if (isset($_REQUEST['jenis']))
        $jenis = CQ($_REQUEST['jenis']);

    if (isset($_REQUEST['keterangan']))
        $keterangan = CQ($_REQUEST['keterangan']);
}

function SimpanData()
{
    global $ERROR_MSG;
    global $kolom, $jenis, $keterangan, $urutan;

    $ERROR_MSG = "";
    if (!isset($_REQUEST['Simpan']))
        return;

    OpenDb();

    $sql = "SELECT replid 
              FROM jbssdm.tambahandata 
             WHERE kolom = '".$kolom."'";
    $result = QueryDb($sql);

    if (mysqli_num_rows($result) > 0)
    {
        CloseDb();
        $ERROR_MSG = "Nama kolom $kolom sudah digunakan!";
        $cek = 0;
    }
    else
    {
        $sql = "INSERT INTO jbssdm.tambahandata 
                   SET kolom = '$kolom', urutan = '$urutan',
                       jenis = '$jenis', aktif = 1, keterangan = '".$keterangan."'";
        $result = QueryDb($sql);
        if ($result)
        { 	?>
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
