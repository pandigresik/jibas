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

$proses = $_REQUEST['psb_proses'];

$nisn = $_REQUEST['psb_nisn'];
$nik = $_REQUEST['psb_nik'];
$noun = $_REQUEST['psb_noun'];
$tahunmasuk = date('Y');
$nama = $_REQUEST['psb_nama'];
$panggilan = $_REQUEST['psb_panggilan'];
$kelamin = $_REQUEST['psb_kelamin'];
$tmplahir = $_REQUEST['psb_tmplahir'];
$tgllahir = $_REQUEST['psb_tgllahir']; 
$blnlahir = $_REQUEST['psb_blnlahir'];
$thnlahir = $_REQUEST['psb_thnlahir'];
$lahir = $thnlahir . "-" . $blnlahir . "-" . $tgllahir;

$suku = $_REQUEST['psb_suku'];
$agama = $_REQUEST['psb_agama'];
$status = $_REQUEST['psb_status'];
$kondisi = $_REQUEST['psb_kondisi'];
$warga = $_REQUEST['psb_warga'];
$urutananak = (int)$_REQUEST['psb_urutananak'];
$jumlahanak = (int)$_REQUEST['psb_jumlahanak'];
$statusanak = $_REQUEST['psb_statusanak'];
$jkandung = (int)$_REQUEST['psb_jkandung'];
$jtiri = (int)$_REQUEST['psb_jtiri'];
$bahasa = $_REQUEST['psb_bahasa'];
$alamatsiswa = $_REQUEST['psb_alamatsiswa'];
$kodepos = $_REQUEST['psb_kodepos'];
$jarak = (float)$_REQUEST['psb_jarak'];
$telponsiswa = $_REQUEST['psb_telponsiswa'];
$hpsiswa = trim((string) $_REQUEST['psb_hpsiswa']);
$emailsiswa= $_REQUEST['psb_emailsiswa'] ;
$dep_asal = $_REQUEST['psb_dep_asal'];
$inputsekolah = (int)$_REQUEST['psb_inputsekolah']; // 0-> Sekolah Baru, 1-> Sekolah Yg Sudah Ada
$newsekolah = $_REQUEST['psb_newsekolah'];
$sekolah = $inputsekolah == 1 ? $_REQUEST['psb_newsekolah'] : $_REQUEST['psb_sekolah'];
$ketsekolah = $_REQUEST['psb_ketsekolah'];
$noijasah = $_REQUEST['psb_noijasah'];
$tglijasah = $_REQUEST['psb_tglijasah'];
$gol = $_REQUEST['psb_gol'];
$berat = (float)$_REQUEST['psb_berat'];
$tinggi = (float)$_REQUEST['psb_tinggi'];
$kesehatan = $_REQUEST['psb_kesehatan'];
$namaayah = $_REQUEST['psb_namaayah'];
$almayah = isset($_REQUEST['psb_almayah']) ? 1 : 0;
$namaibu = $_REQUEST['psb_namaibu'];
$almibu = isset($_REQUEST['psb_almibu']) ? 1 : 0;
$statusayah = $_REQUEST['psb_statusayah'];
$statusibu = $_REQUEST['psb_statusibu'];
$tmplahirayah = $_REQUEST['psb_tmplahirayah'];
$tmplahiribu = $_REQUEST['psb_tmplahiribu'];
$tgllahirayah = $_REQUEST['psb_tgllahirayah'];
$blnlahirayah = $_REQUEST['psb_blnlahirayah'];
$thnlahirayah = $_REQUEST['psb_thnlahirayah'];
$lahirayah = "$thnlahirayah-$blnlahirayah-$tgllahirayah";
$tgllahiribu = $_REQUEST['psb_tgllahiribu'];
$blnlahiribu = $_REQUEST['psb_blnlahiribu'];
$thnlahiribu = $_REQUEST['psb_thnlahiribu'];
$lahiribu = "$thnlahiribu-$blnlahiribu-$tgllahiribu";
$pendidikanayah = $_REQUEST['psb_pendidikanayah'];
$pendidikanibu = $_REQUEST['psb_pendidikanibu'];
$pekerjaanayah = $_REQUEST['psb_pekerjaanayah'];
$pekerjaanibu = $_REQUEST['psb_pekerjaanibu'];
$penghasilanayah = UnformatRupiah($_REQUEST['psb_penghasilanayah']);
$penghasilanibu = UnformatRupiah($_REQUEST['psb_penghasilanibu']);
$namawali = $_REQUEST['psb_namawali'];
$alamatortu = $_REQUEST['psb_alamatortu'];
$telponortu = $_REQUEST['psb_telponortu'];
$hportu = $_REQUEST['psb_hportu'];
$hportu2 = $_REQUEST['psb_hportu2'];
$hportu3 = $_REQUEST['psb_hportu3'];
$emailayah =$_REQUEST['psb_emailayah'];
$emailibu = $_REQUEST['psb_emailibu'];
$alamatsurat = $_REQUEST['psb_alamatsurat'];
$keterangan = $_REQUEST['psb_keterangan'];
$hobi = $_REQUEST['psb_hobi'];
$departemen = $_REQUEST['psb_departemen'];
$kelompok = $_REQUEST['psb_kelompok'];
$idtambahan = $_REQUEST['psb_idtambahan'];

$sumbujian = "";
for($i = 1; $i <= 2; $i++)
{
	if ($sumbujian != "")
		$sumbujian .= ", ";
    $fname = "sum$i";    
	$fkd = "psb_sum$i";
	$kd = trim((string) $_REQUEST[$fkd]);
	$kd = (strlen($kd) == 0) ? "0" : $kd;
	$kd = UnformatRupiah($kd);
	$sumbujian .= "$fname = '".$kd."'";
}

for($i = 1; $i <= 10; $i++)
{
	if ($sumbujian != "")
		$sumbujian .= ", ";
    $fname = "ujian$i";        
	$fkd = "psb_ujian$i";
	$kd = trim((string) $_REQUEST[$fkd]);
	$kd = (strlen($kd) == 0) ? 0 : $kd;
	$sumbujian .= "$fname = '".$kd."'";
}

$pin = random(5);

OpenDb();
BeginTrans();
try
{
    $sql = "SELECT COUNT(replid)
              FROM jbsakad.calonsiswa
             WHERE idkelompok = '$kelompok'
               AND aktif = 1";
    $ndata = FetchSingleEx($sql);
    
    $sql = "SELECT kapasitas
              FROM jbsakad.kelompokcalonsiswa
             WHERE replid = '".$kelompok."'";
    $kapasitas = FetchSingleEx($sql);
    
    if ($ndata >= $kapasitas)
    {
        CommitTrans();
        CloseDb();
        
        http_response_code(200);
    
        echo "<font style='font-family: Tahoma; font-size: 16px; color: #557d1d;'>";
        echo "Maaf, pendaftaran tidak dapat dilakukan karena kapasitas kelompok telah melebihi kapasitas!";
        echo "</font>";
        echo "<br><br><br>";
        echo "Informasikan peringatan ini kepada Administrator JIBAS di sekolah<br><br>";
        exit();
    }
    
    // -- A.BEGIN Buat No Pendaftaran Calon Siswa
    
    $sql = "SELECT kodeawalan
              FROM jbsakad.prosespenerimaansiswa
             WHERE replid = '".$proses."'";	
    $kode_no = FetchSingleEx($sql);
    $kodelen = strlen((string) $kode_no);
  
    $sql = "SELECT MAX(LPAD(nopendaftaran, " . ($kodelen + 20) . ",'*'))
              FROM jbsakad.calonsiswa
             WHERE idproses = '".$proses."'";
    $nom = FetchSingleEx($sql);
    
    $nom = str_replace("*", "", (string) $nom);
    $counter = (int)substr($nom, $kodelen + 2);
    $thn_no = substr(date('Y'), 2);
    do
    {
        $counter += 1;
        $no = $kode_no . $thn_no . sprintf("%04d", $counter);
        
        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.calonsiswa
                 WHERE nopendaftaran='$no'";
        $ndata = (int)FetchSingleEx($sql);
    }
    while($ndata > 0);
    
    // -- A.END

    // -- B.BEGIN: Simpan asal sekolah baru
    
    if ($inputsekolah == 1)
    {
        $sql = "INSERT INTO jbsakad.asalsekolah
                   SET departemen = '$dep_asal', sekolah = '".$sekolah."'";
        //echo "$sql<br>";
        QueryDbEx($sql);
    }
    
    // -- C.BEGIN: Simpan calon siswa
    
    $sql =  "INSERT INTO jbsakad.calonsiswa
                SET nopendaftaran='$no', nama='$nama', panggilan='$panggilan', tahunmasuk='$tahunmasuk', idproses='$proses',
                    idkelompok='$kelompok', suku='$suku', agama='$agama', status='$status', kondisi='$kondisi', kelamin='$kelamin',
                    tmplahir='$tmplahir', tgllahir='$lahir', warga='$warga', anakke='$urutananak', jsaudara='$jumlahanak', statusanak='$statusanak', jkandung='$jkandung', jtiri='$jtiri',
                    bahasa='$bahasa', berat='$berat', tinggi='$tinggi', darah='$gol', alamatsiswa='$alamatsiswa', kodepossiswa='$kodepos', jarak='$jarak', telponsiswa='$telponsiswa',
                    hpsiswa='$hpsiswa', emailsiswa='$emailsiswa', kesehatan='$kesehatan', asalsekolah='$sekolah', noijasah='$noijasah', tglijasah='$tglijasah', ketsekolah='$ketsekolah',
                    namaayah='$namaayah', namaibu='$namaibu', almayah='$almayah', almibu='$almibu',
                    statusayah='$statusayah', statusibu='$statusibu', tmplahirayah='$tmplahirayah', tmplahiribu='$tmplahiribu', tgllahirayah='$lahirayah', tgllahiribu='$lahiribu',
                    pendidikanayah='$pendidikanayah', pendidikanibu='$pendidikanibu', pekerjaanayah='$pekerjaanayah', pekerjaanibu='$pekerjaanibu', wali='$namawali', penghasilanayah='$penghasilanayah',
                    penghasilanibu='$penghasilanibu', alamatortu='$alamatortu', telponortu='$telponortu', hportu='$hportu',
                    info1='$hportu2', info2='$hportu3', info3='$pin', pinsiswa='$pin', emailayah='$emailayah', emailibu='$emailibu', alamatsurat='$alamatsurat',
                    keterangan='$keterangan', hobi='$hobi', nisn='$nisn', nik='$nik', noun='$noun', $sumbujian";
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

            $param = "psb_jenisdata-$replid";
            if (!isset($_REQUEST[$param])) continue;
            $jenis = $_REQUEST[$param];

            $param = "psb_repliddata-$replid";
            if (!isset($_REQUEST[$param])) continue;
            $repliddata = $_REQUEST[$param];

            if ($jenis == 1 || $jenis == 3)
            {
                $param = "psb_tambahandata-$replid";
                if (!isset($_REQUEST[$param])) continue;
                $teks = $_REQUEST[$param];
                $teks = CQ($teks);

                if ($repliddata == 0)
                    $sql = "INSERT INTO jbsakad.tambahandatacalon
                               SET nopendaftaran = '$no', idtambahan = '$replid', jenis = '$jenis', teks = '".$teks."'";
                else
                    $sql = "UPDATE jbsakad.tambahandatacalon
                               SET teks = '$teks'
                             WHERE replid = '".$repliddata."'";

                QueryDbEx($sql);
            }
        }
    }
    
    CommitTrans();
    CloseDb();

    http_response_code(200);
    
    echo "<font style='font-family: Tahoma; font-size: 20px; color: #557d1d;'>";
    echo "Pendaftaran telah diterima.";
    echo "</font>";
    echo "<br><br><br>";
    echo "Berikut informasi pendaftaran anda:<br><br>";
    echo "<table border='0' cellpadding='2' cellspacing='0' style='line-height: 18px; font-size: 14px; color: Black; font-weight: bold;'>";
    echo "<tr>";
    echo "<td align='right' width='140'>Nama:</td>";
    echo "<td align='right' width='5'>&nbsp;</td>";
    echo "<td align='left'>$nama</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='right'>No Pendaftaran:</td>";
    echo "<td align='right' width='5'>&nbsp;</td>";
    echo "<td align='left'>$no</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='right'>P I N:</td>";
    echo "<td align='right' width='5'>&nbsp;</td>";
    echo "<td align='left'>$pin</td>";
    echo "</tr>";
    echo "</table>";
    echo "<br>Catat dan gunakan No Pendaftaran dan PIN untuk mengubah data pendaftaran anda<br>";
    echo "<br><br>";
    echo "<input type='button' value='Selesai' class='but' onclick='psb_InputPsbClicked()'>";
}
catch(DbException $dbe)
{
    RollbackTrans();
    CloseDb();
    
    http_response_code(500);
    echo "<font style='font-family: Tahoma; font-size: 20px; color: red;'>";
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
    echo "<font style='font-family: Tahoma; font-size: 20px; color: red;'>";
    echo "Oops, something has gone wrong";
    echo "</font>";
    echo "<br><br><br>";
    echo $e->getMessage();
}
?>