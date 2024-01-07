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
    global $replid, $komentar, $no;

    if (isset($_REQUEST['replid']))
        $replid = $_REQUEST['replid'];

    if (isset($_REQUEST['komentar']))
        $komentar = CQ($_REQUEST['komentar']);

    if (isset($_REQUEST['no']))
        $no = CQ($_REQUEST['no']);

    $komentar = urldecode($komentar);
}

function ReadData()
{
    global $replid, $komentar;

    OpenDb();
    $sql = "SELECT komentar FROM pilihkomenpel WHERE replid = '".$replid."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $komentar = $row[0];
    CloseDb();
}

function SimpanData()
{
    global $replid, $komentar, $no;

    OpenDb();
    $komentar = CQ($komentar);
    $sql = "UPDATE pilihkomenpel 
               SET komentar = '$komentar'
             WHERE replid = '".$replid."'";
    $result = QueryDb($sql);
    if ($result)
    { 	?>
        <script language="javascript">
            opener.refreshListKomentar(<?=$no?>);
            window.close();
        </script>
        <?php
    }
    CloseDb();
    exit();

}
?>