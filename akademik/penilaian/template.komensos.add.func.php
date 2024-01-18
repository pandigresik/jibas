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
    global $idpelajaran, $idtingkat, $komentar, $jenis;

    if (isset($_REQUEST['idpelajaran']))
        $idpelajaran = $_REQUEST['idpelajaran'];

    if (isset($_REQUEST['idtingkat']))
        $idtingkat = $_REQUEST['idtingkat'];

    if (isset($_REQUEST['komentar']))
        $komentar = CQ($_REQUEST['komentar']);

    if (isset($_REQUEST['jenis']))
        $jenis = CQ($_REQUEST['jenis']);

    $komentar = urldecode((string) $komentar);
}

function SimpanData()
{
    global $idpelajaran, $idtingkat, $komentar, $jenis;

    if (!isset($_REQUEST['Simpan']))
        return;

    OpenDb();
    $komentar = CQ($komentar);
    $sql = "INSERT INTO pilihkomensos 
               SET idpelajaran = '$idpelajaran',
                   idtingkat = '$idtingkat',
                   jenis = '$jenis',
                   komentar = '".$komentar."'";
    $result = QueryDb($sql);
    if ($result)
    { 	?>
        <script language="javascript">
            opener.refreshListKomentarSos('<?=$jenis?>');
            window.close();
        </script>
        <?php
    }
    CloseDb();
    exit();

}
?>