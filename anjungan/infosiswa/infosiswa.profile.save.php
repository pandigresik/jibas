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
require_once("../include/config.php");
require_once("../include/common.php");
require_once("../include/db_functions.php");
require_once("../include/rupiah.php");
require_once("../include/compatibility.php");

function Safe($string)
{
    $string = str_replace("'", "`", (string) $string);
    $string = str_replace("\"", "`", $string);
    $string = str_replace("<", "&lt;", $string);
    $string = str_replace(">", "&gt;", $string);

    return $string;
}

foreach ($_REQUEST as $key => $value)
{
    $_REQUEST[$key] = Safe($value);
}

$nis = $_REQUEST['is_nis'];
$nisn = $_REQUEST['is_nisn'];
$nik = $_REQUEST['is_nik'];
$noun = $_REQUEST['is_noun'];
$tahunmasuk = date('Y');
$nama = $_REQUEST['is_nama'];
$panggilan = $_REQUEST['is_panggilan'];
$kelamin = $_REQUEST['is_kelamin'];
$tmplahir = $_REQUEST['is_tmplahir'];
$tgllahir = $_REQUEST['is_tgllahir']; 
$blnlahir = $_REQUEST['is_blnlahir'];
$thnlahir = $_REQUEST['is_thnlahir'];
$lahir = $thnlahir . "-" . $blnlahir . "-" . $tgllahir;

$suku = $_REQUEST['is_suku'];
$agama = $_REQUEST['is_agama'];
$status = $_REQUEST['is_status'];
$kondisi = $_REQUEST['is_kondisi'];
$warga = $_REQUEST['is_warga'];
$urutananak = (int)$_REQUEST['is_urutananak'];
$jumlahanak = (int)$_REQUEST['is_jumlahanak'];
$statusanak = $_REQUEST['is_statusanak'];
$jkandung = (int)$_REQUEST['is_jkandung'];
$jtiri = (int)$_REQUEST['is_jtiri'];
$bahasa = $_REQUEST['is_bahasa'];
$alamatsiswa = $_REQUEST['is_alamatsiswa'];
$kodepos = $_REQUEST['is_kodepos'];
$jarak = (float)$_REQUEST['is_jarak'];
$telponsiswa = $_REQUEST['is_telponsiswa'];
$hpsiswa = trim((string) $_REQUEST['is_hpsiswa']);
$emailsiswa= $_REQUEST['is_emailsiswa'] ;
$dep_asal = $_REQUEST['is_dep_asal'];
$inputsekolah = (int)$_REQUEST['is_inputsekolah']; // 0-> Sekolah Baru, 1-> Sekolah Yg Sudah Ada
$newsekolah = $_REQUEST['is_newsekolah'];
$sekolah = $inputsekolah == 1 ? $_REQUEST['is_newsekolah'] : $_REQUEST['is_sekolah'];
$ketsekolah = $_REQUEST['is_ketsekolah'];
$noijasah = $_REQUEST['is_noijasah'];
$tglijasah = $_REQUEST['is_tglijasah'];
$gol = $_REQUEST['is_gol'];
$berat = (float)$_REQUEST['is_berat'];
$tinggi = (float)$_REQUEST['is_tinggi'];
$kesehatan = $_REQUEST['is_kesehatan'];
$namaayah = $_REQUEST['is_namaayah'];
$almayah = isset($_REQUEST['is_almayah']) ? 1 : 0;
$namaibu = $_REQUEST['is_namaibu'];
$almibu = isset($_REQUEST['is_almibu']) ? 1 : 0;
$statusayah = $_REQUEST['is_statusayah'];
$statusibu = $_REQUEST['is_statusibu'];
$tmplahirayah = $_REQUEST['is_tmplahirayah'];
$tmplahiribu = $_REQUEST['is_tmplahiribu'];
$tgllahirayah = $_REQUEST['is_tgllahirayah'];
$blnlahirayah = $_REQUEST['is_blnlahirayah'];
$thnlahirayah = $_REQUEST['is_thnlahirayah'];
$lahirayah = "$thnlahirayah-$blnlahirayah-$tgllahirayah";
$tgllahiribu = $_REQUEST['is_tgllahiribu'];
$blnlahiribu = $_REQUEST['is_blnlahiribu'];
$thnlahiribu = $_REQUEST['is_thnlahiribu'];
$lahiribu = "$thnlahiribu-$blnlahiribu-$tgllahiribu";
$pendidikanayah = $_REQUEST['is_pendidikanayah'];
$pendidikanibu = $_REQUEST['is_pendidikanibu'];
$pekerjaanayah = $_REQUEST['is_pekerjaanayah'];
$pekerjaanibu = $_REQUEST['is_pekerjaanibu'];
$penghasilanayah = UnformatRupiah($_REQUEST['is_penghasilanayah']);
$penghasilanibu = UnformatRupiah($_REQUEST['is_penghasilanibu']);
$namawali = $_REQUEST['is_namawali'];
$alamatortu = $_REQUEST['is_alamatortu'];
$telponortu = $_REQUEST['is_telponortu'];
$hportu = $_REQUEST['is_hportu'];
$hportu2 = $_REQUEST['is_hportu2'];
$hportu3 = $_REQUEST['is_hportu3'];
$emailayah =$_REQUEST['is_emailayah'];
$emailibu = $_REQUEST['is_emailibu'];
$alamatsurat = $_REQUEST['is_alamatsurat'];
$keterangan = $_REQUEST['is_keterangan'];
$hobi = $_REQUEST['is_hobi'];
$departemen = $_REQUEST['is_departemen'];
$kelompok = $_REQUEST['is_kelompok'];
$idtambahan = $_REQUEST['is_idtambahan'];

OpenDb();
BeginTrans();
try
{
    // -- B.BEGIN: Simpan asal sekolah baru
    
    if ($inputsekolah == 1)
    {
        $sql = "INSERT INTO jbsakad.asalsekolah
                   SET departemen = '$dep_asal', sekolah = '".$sekolah."'";
        //echo "$sql<br>";
        QueryDbEx($sql);
    }
    
    // -- C.BEGIN: Simpan calon siswa
    
    $sql =  "UPDATE jbsakad.siswa
                SET nama='$nama', panggilan='$panggilan', tahunmasuk='$tahunmasuk', 
                    suku='$suku', agama='$agama', status='$status', kondisi='$kondisi', kelamin='$kelamin',
                    tmplahir='$tmplahir', tgllahir='$lahir', warga='$warga', anakke='$urutananak', jsaudara='$jumlahanak', statusanak='$statusanak', jkandung='$jkandung', jtiri='$jtiri',
                    bahasa='$bahasa', berat='$berat', tinggi='$tinggi', darah='$gol', alamatsiswa='$alamatsiswa', kodepossiswa='$kodepos', jarak='$jarak', telponsiswa='$telponsiswa',
                    hpsiswa='$hpsiswa', emailsiswa='$emailsiswa', kesehatan='$kesehatan', asalsekolah='$sekolah', noijasah='$noijasah', tglijasah='$tglijasah', ketsekolah='$ketsekolah',
                    namaayah='$namaayah', namaibu='$namaibu', almayah='$almayah', almibu='$almibu',
                    statusayah='$statusayah', statusibu='$statusibu', tmplahirayah='$tmplahirayah', tmplahiribu='$tmplahiribu', tgllahirayah='$lahirayah', tgllahiribu='$lahiribu',
                    pendidikanayah='$pendidikanayah', pendidikanibu='$pendidikanibu', pekerjaanayah='$pekerjaanayah', pekerjaanibu='$pekerjaanibu', wali='$namawali', penghasilanayah='$penghasilanayah',
                    penghasilanibu='$penghasilanibu', alamatortu='$alamatortu', telponortu='$telponortu', hportu='$hportu',
                    info1='$hportu2', info2='$hportu3', emailayah='$emailayah', emailibu='$emailibu', alamatsurat='$alamatsurat',
                    keterangan='$keterangan', hobi='$hobi', nisn='$nisn', nik='$nik', noun='$noun'
              WHERE nis = '".$nis."'";
    //echo "$sql<br>";
    QueryDbEx($sql);

    if (strlen((string) $idtambahan) > 0)
    {
        if (!str_contains((string) $idtambahan, ","))
            $arridtambahan = [$idtambahan];
        else
            $arridtambahan = explode(",", (string) $idtambahan);

        for($i = 0; $i < count($arridtambahan); $i++)
        {
            $replid = $arridtambahan[$i];

            $param = "is_jenisdata-$replid";
            if (!isset($_REQUEST[$param])) continue;
            $jenis = $_REQUEST[$param];

            $param = "is_repliddata-$replid";
            if (!isset($_REQUEST[$param])) continue;
            $repliddata = $_REQUEST[$param];

            if ($jenis == 1 || $jenis == 3)
            {
                $param = "is_tambahandata-$replid";
                if (!isset($_REQUEST[$param])) continue;
                $teks = $_REQUEST[$param];
                $teks = CQ($teks);

                if ($repliddata == 0)
                    $sql = "INSERT INTO jbsakad.tambahandatasiswa
                               SET nis = '$nis', idtambahan = '$replid', jenis = '$jenis', teks = '".$teks."'";
                else
                    $sql = "UPDATE jbsakad.tambahandatasiswa
                               SET teks = '$teks'
                             WHERE replid = '".$repliddata."'";

                QueryDbEx($sql);
            }
        }
    }
    
    CommitTrans();
    CloseDb();

    http_response_code(200);
    echo "<br><br><br>";
    echo "<font style='font-family: Tahoma; font-size: 16px; color: #557d1d;'>";
    echo "Data siswa telah berhasil diubah.";
    echo "</font>";
    echo "<br><br><br>";
    echo "<input type='button' value='Selesai' class='but' onclick='GetReportContent()'>";
}
catch(DbException $dbe)
{
    RollbackTrans();
    CloseDb();
    
    http_response_code(500);
    echo "<font style='font-family: Tahoma; font-size: 16px; color: red;'>";
    echo "Oops, something has gone wrong";
    echo "</font>";
    echo "<br><br><br>";
    echo $dbe->getMessage();
}
catch(Exception $e)
{
    RollbackTrans();
    CloseDb();
    
    http_response_code(500);
    echo "<font style='font-family: Tahoma; font-size: 16px; color: red;'>";
    echo "Oops, something has gone wrong";
    echo "</font>";
    echo "<br><br><br>";
    echo $e->getMessage();
}
?>