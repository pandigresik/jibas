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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/rupiah.php');
require_once('../include/imageresizer.php');
require_once('../include/db_functions.php');

$proses = $_REQUEST['proses'];

OpenDb();

$sql = "SELECT kodeawalan FROM jbsakad.prosespenerimaansiswa WHERE replid = '".$proses."'";	
$res = QueryDb($sql);	
$row = mysqli_fetch_row($res);	
$kode_no = $row[0];
$kodelen = strlen((string) $kode_no);
//echo "$kode_no<br>";

$sql = "SELECT MAX(LPAD(nopendaftaran, " . ($kodelen + 20) . ",'*')) FROM jbsakad.calonsiswa WHERE idproses = '".$proses."'";
$res = QueryDb($sql);	
$row = mysqli_fetch_row($res);
$nom = $row[0];
//echo "$nom<br>";

$nom = str_replace("*", "", (string) $nom);
//echo "$nom<br>";

$counter = (int)substr($nom, $kodelen + 2);
//echo "$counter<br>";

$thn_no = substr(date('Y'), 2);
//echo "$thn_no<br>";
do
{
    $counter += 1;
    $no = $kode_no . $thn_no . sprintf("%04d", $counter);
    
    $sql = "SELECT COUNT(replid) FROM jbsakad.calonsiswa WHERE nopendaftaran='$no'";
    //echo "$sql<br>";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $ndata = (int)$row[0];
}
while($ndata > 0);

//echo "$no is Unique<br>";
//exit();

$nisn = $_REQUEST['nisn'];
$nik = $_REQUEST['nik'];
$noun = $_REQUEST['noun'];
$tahunmasuk = $_REQUEST['tahunmasuk'];
$nama = $_REQUEST['nama'];
$panggilan = $_REQUEST['panggilan'];
$kelamin = $_REQUEST['kelamin'];
$tmplahir = $_REQUEST['tmplahir'];
$tgllahir = strlen((string) $_REQUEST['tgllahir']) == 0 ? "1" : $_REQUEST['tgllahir']; 
$blnlahir = strlen((string) $_REQUEST['blnlahir']) == 0 ? "1" : $_REQUEST['blnlahir'];
$thnlahir = strlen((string) $_REQUEST['thnlahir']) == 0 ? "1970" : $_REQUEST['thnlahir'];
$lahir = $thnlahir . "-" . $blnlahir . "-" . $tgllahir;
$suku = strlen((string) $_REQUEST['suku']) == 0 ? "NULL" : "'" . $_REQUEST['suku'] . "'";
$agama = strlen((string) $_REQUEST['agama']) == 0 ? "NULL" : "'" . $_REQUEST['agama'] . "'";
$status = strlen((string) $_REQUEST['status']) == 0 ? "NULL" : "'" . $_REQUEST['status'] . "'";
$kondisi = strlen((string) $_REQUEST['kondisi']) == 0 ? "NULL" : "'" . $_REQUEST['kondisi'] . "'";
$warga = $_REQUEST['warga'];
$urutananak = strlen((string) $_REQUEST['urutananak']) == 0 ? 0 : $_REQUEST['urutananak'];
$jumlahanak = strlen((string) $_REQUEST['jumlahanak']) == 0 ? 0 : $_REQUEST['jumlahanak'];
$statusanak = $_REQUEST['statusanak'];
$jkandung = strlen((string) $_REQUEST['jkandung']) == 0 ? 0 : $_REQUEST['jkandung'];
$jtiri = strlen((string) $_REQUEST['jtiri']) == 0 ? 0 : $_REQUEST['jtiri'];
$bahasa = $_REQUEST['bahasa'];
$alamatsiswa = $_REQUEST['alamatsiswa'];
$kodepos = $_REQUEST['kodepos'];
$kodepos_sql = "kodepossiswa = '".$kodepos."'";
if ($kodepos == "")
	$kodepos_sql = "kodepossiswa = NULL";
$jarak = (float)$_REQUEST['jarak'];
$telponsiswa=CQ($_REQUEST['telponsiswa']);
$hpsiswa=CQ(trim((string) $_REQUEST['hpsiswa']));
$hpsiswa=str_replace(' ','',(string) $hpsiswa);
$emailsiswa=CQ($_REQUEST['emailsiswa']);
$dep_asal=$_REQUEST['dep_asal'];
$sekolah=$_REQUEST['sekolah'];
$sekolah_sql = "asalsekolah = '".$sekolah."'";
if ($sekolah == "")
	$sekolah_sql = "asalsekolah = NULL";
$ketsekolah = $_REQUEST['ketsekolah'];
$noijasah = $_REQUEST['noijasah'];
$tglijasah = $_REQUEST['tglijasah'];
$gol = $_REQUEST['gol'];
$berat = $_REQUEST['berat'];
if ($_REQUEST['berat']=="")
	$berat = 0;
$tinggi = $_REQUEST['tinggi'];
if ($_REQUEST['tinggi']=="")
	$tinggi = 0;
$kesehatan=$_REQUEST['kesehatan'];
$namaayah=$_REQUEST['namaayah'];
$almayah = $_REQUEST['almayah'];
if ($_REQUEST['almayah']<> "1")
	$almayah=0;
$namaibu=$_REQUEST['namaibu'];
$almibu = $_REQUEST['almibu'];
if ($_REQUEST['almibu']<> "1")
	$almibu=0;
$statusayah = $_REQUEST['statusayah'];
$statusibu = $_REQUEST['statusibu'];
$tmplahirayah = $_REQUEST['tmplahirayah'];
$tmplahiribu = $_REQUEST['tmplahiribu'];
$tgllahirayah = $_REQUEST['tgllahirayah'];
$tgllahiribu = $_REQUEST['tgllahiribu'];
$pendidikanayah = $_REQUEST['pendidikanayah'];
$pendidikanayah_sql = "pendidikanayah = '".$pendidikanayah."'";
if ($pendidikanayah == "")
	$pendidikanayah_sql = "pendidikanayah = NULL";
$pendidikanibu=$_REQUEST['pendidikanibu'];
$pendidikanibu_sql = "pendidikanibu = '".$pendidikanibu."'";
if ($pendidikanibu == "")
	$pendidikanibu_sql = "pendidikanibu = NULL";
$pekerjaanayah=$_REQUEST['pekerjaanayah'];
$pekerjaanayah_sql = "pekerjaanayah = '".$pekerjaanayah."'";
if ($pekerjaanayah == "")
	$pekerjaanayah_sql = "pekerjaanayah = NULL";
$pekerjaanibu=$_REQUEST['pekerjaanibu'];
$pekerjaanibu_sql = "pekerjaanibu = '".$pekerjaanibu."'";
if ($pekerjaanibu == "")
	$pekerjaanibu_sql = "pekerjaanibu = NULL";
$penghasilanayah = $_REQUEST['penghasilanayah'];
if ($_REQUEST['penghasilanayah']=="")
	$penghasilanayah = 0;
$penghasilanibu = $_REQUEST['penghasilanibu'];
if ($_REQUEST['penghasilanibu']=="")
	$penghasilanibu = 0;
$namawali=$_REQUEST['namawali'];
$alamatortu=$_REQUEST['alamatortu'];
$telponortu=$_REQUEST['telponortu'];
$hportu=trim((string) $_REQUEST['hportu']);
$hportu=str_replace(' ','',$hportu);
$hportu2=trim((string) $_REQUEST['hportu2']);
$hportu2=str_replace(' ','',$hportu2);
$hportu3=trim((string) $_REQUEST['hportu3']);
$hportu3=str_replace(' ','',$hportu3);
$emailayah=$_REQUEST['emailayah'];
$emailibu=$_REQUEST['emailibu'];
$alamatsurat=$_REQUEST['alamatsurat'];
$keterangan=$_REQUEST['keterangan'];
$hobi=$_REQUEST['hobi'];
$departemen=$_REQUEST['departemen'];
$kelompok = $_REQUEST['kelompok'];
$idtambahan = $_REQUEST['idtambahan'];

// --- check temporary directory
$tmp_path = realpath(".") . "/../../temp";
$tmp_exists = file_exists($tmp_path) && is_dir($tmp_path);
if (!$tmp_exists)
	mkdir($tmp_path, 0755);
	
$foto = $_FILES["nama_foto"];
$uploadedfile = $foto['tmp_name'];
$uploadedtypefile = $foto['type'];
$uploadedsizefile = $foto['size'];
if ($uploadedfile != "")
{
	$filename = "$tmp_path/ad-cs-tmp.jpg";
	ResizeImage($foto, 159, 120, 100, $filename);
	
	$fh = fopen($filename, "r");
	$foto_data = addslashes(fread($fh, filesize($filename)));
	fclose($fh);
	
	$gantifoto = ", foto='$foto_data'";
} 
else 
{
	if ($_REQUEST['action'] == 'ubah') 
	{
	    $sql = "SELECT isnull(foto) FROM jbsakad.calonsiswa WHERE replid='".$_REQUEST['replid']."'";
	    $res2 = QueryDb($sql);
	    $row2 = mysqli_fetch_row($res2);
	    $isFotoNull = (int) $row2[0];

	    if ($isFotoNull == 0)
	    {
	        $sql = "SELECT foto FROM jbsakad.calonsiswa WHERE replid='".$_REQUEST['replid']."'";
	        $res2 = QueryDb($sql);
	        $row2 = mysqli_fetch_row($res2);
	        $data = $row2[0];

            $filename = "$tmp_path/ed-cs-tmp.jpg";
            $src_img = imagecreatefromstring($data);
            $dst_img = imagecreatetruecolor(120, 159);
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, 120, 159, imagesx($src_img), imagesy($src_img));
            imagejpeg($dst_img, $filename, 100);

            $fh = fopen($filename, "r");
            $foto_data = addslashes(fread($fh, filesize($filename)));
            fclose($fh);

            $gantifoto = ", foto='$foto_data'";
        }
        else
        {
            $gantifoto = "";
        }

	}
}

$set = "";
for($i = 1; $i <= 2; $i++)
{
	if ($set != "")
		$set .= ", ";
	$fkd = "sum$i";
	$kd = trim((string) $_REQUEST[$fkd]);
	$kd = (strlen($kd) == 0) ? "0" : $kd;
	$kd = UnformatRupiah($kd);
	$set .= "$fkd = '".$kd."'";
}

for($i = 1; $i <= 10; $i++)
{
	if ($set != "")
		$set .= ", ";
	$fkd = "ujian$i";
	$kd = trim((string) $_REQUEST[$fkd]);
	$kd = (strlen($kd) == 0) ? 0 : $kd;
	$set .= "$fkd = '".$kd."'";
}

if ($_REQUEST['action'] == 'ubah')
{	
	$sql = "UPDATE jbsakad.calonsiswa
			   SET nama='$nama', panggilan='$panggilan', idproses=$proses, idkelompok=$kelompok, suku=$suku,
				   agama=$agama, status=$status, kondisi=$kondisi, kelamin='$kelamin', tmplahir='$tmplahir',
				   tgllahir='$lahir', warga='$warga', anakke=$urutananak, jsaudara=$jumlahanak, statusanak='$statusanak', jkandung='$jkandung', jtiri='$jtiri',
				   bahasa='$bahasa',berat=$berat, tinggi=$tinggi, darah='$gol', alamatsiswa='$alamatsiswa', $kodepos_sql, jarak='$jarak',
				   telponsiswa='$telponsiswa', hpsiswa='$hpsiswa', emailsiswa='$emailsiswa', kesehatan='$kesehatan', $sekolah_sql, noijasah='$noijasah', tglijasah='$tglijasah',
				   ketsekolah='$ketsekolah', namaayah='$namaayah', namaibu='$namaibu', almayah=$almayah, almibu=$almibu,
				   statusayah='$statusayah', statusibu='$statusibu', tmplahirayah='$tmplahirayah', tmplahiribu='$tmplahiribu', tgllahirayah='$tgllahirayah', tgllahiribu='$tgllahiribu',
				   $pendidikanayah_sql, $pendidikanibu_sql,  $pekerjaanayah_sql, $pekerjaanibu_sql, wali='$namawali', penghasilanayah=$penghasilanayah,
				   penghasilanibu=$penghasilanibu, alamatortu='$alamatortu', telponortu='$telponortu',
				   hportu='$hportu', info1='$hportu2', info2='$hportu3', emailayah='$emailayah', emailibu='$emailibu',
				   alamatsurat='$alamatsurat', keterangan='$keterangan', hobi='$hobi', nisn='$nisn', nik='$nik', noun='$noun', $set $gantifoto
			 WHERE replid = '".$_REQUEST['replid']."'";
}
else
{
	$pin = random(5);
	
	$sql = "INSERT INTO jbsakad.calonsiswa
			   SET nopendaftaran='$no', nama='$nama', panggilan='$panggilan', tahunmasuk='$tahunmasuk', idproses='$proses',
				   idkelompok='$kelompok', suku=$suku, agama=$agama, status=$status, kondisi=$kondisi, kelamin='$kelamin',
				   tmplahir='$tmplahir', tgllahir='$lahir', warga='$warga', anakke='$urutananak', jsaudara='$jumlahanak', statusanak='$statusanak', jkandung='$jkandung', jtiri='$jtiri',
				   bahasa='$bahasa', berat='$berat', tinggi='$tinggi', darah='$gol', alamatsiswa='$alamatsiswa', $kodepos_sql, jarak='$jarak', telponsiswa='$telponsiswa',
				   hpsiswa='$hpsiswa', emailsiswa='$emailsiswa', kesehatan='$kesehatan', $sekolah_sql, noijasah='$noijasah', tglijasah='$tglijasah', ketsekolah='$ketsekolah',
				   namaayah='$namaayah', namaibu='$namaibu', almayah='$almayah', almibu='$almibu',
				   statusayah='$statusayah', statusibu='$statusibu', tmplahirayah='$tmplahirayah', tmplahiribu='$tmplahiribu', tgllahirayah='$tgllahirayah', tgllahiribu='$tgllahiribu',
				   $pendidikanayah_sql, $pendidikanibu_sql, $pekerjaanayah_sql, $pekerjaanibu_sql, wali='$namawali', penghasilanayah='$penghasilanayah',
				   penghasilanibu='$penghasilanibu', alamatortu='$alamatortu', telponortu='$telponortu', hportu='$hportu',
				   info1='$hportu2', info2='$hportu3', emailayah='$emailayah', emailibu='$emailibu', alamatsurat='$alamatsurat',
				   keterangan='$keterangan', hobi='$hobi', nisn='$nisn', nik='$nik', noun='$noun', info3='$pin', pinsiswa='$pin', $set $gantifoto";
}

$success = true;
BeginTrans();

QueryDbTrans($sql, $success);

if ($success && $_REQUEST['action'] == 'ubah')
{
    $sql = "SELECT nopendaftaran FROM jbsakad.calonsiswa WHERE replid ='". $_REQUEST['replid']."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $no = $row[0];
}

if ($success)
{
    if ($uploadedfile != "")
    {
        $sql = "INSERT INTO jbsakad.riwayatfoto SET nic = '$no', foto = '$foto_data', tanggal = NOW()";
        QueryDbTrans($sql, $success);
    }
}

if ($success && strlen((string) $idtambahan) > 0)
{
    if (!str_contains((string) $idtambahan, ","))
        $arridtambahan = [$idtambahan];
    else
        $arridtambahan = explode(",", (string) $idtambahan);

    for($i = 0; $success && $i < count($arridtambahan); $i++)
    {
        $replid = $arridtambahan[$i];

        $param = "jenisdata-$replid";
        if (!isset($_REQUEST[$param])) continue;
        $jenis = $_REQUEST[$param];

        $param = "repliddata-$replid";
        if (!isset($_REQUEST[$param])) continue;
        $repliddata = $_REQUEST[$param];

        // READ WARNING IMAGE
        $warnimg = "../images/warningimg.jpg";
        $fh = fopen($warnimg,"r");
        $warnsize = filesize($warnimg);
        $warnfile = addslashes(fread($fh, $warnsize));
        $warntype = "image/jpeg";
        $warnname = "warning.jpg";
        fclose($fh);

        if ($jenis == 1 || $jenis == 3)
        {
            $param = "tambahandata-$replid";
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

            QueryDbTrans($sql, $success);
        }
        else
        {
            $param = "tambahandata-$replid";
            if (!isset($_FILES[$param])) continue;
            $file = $_FILES[$param];
            $tmpfile = $file['tmp_name'];

            if (strlen((string) $tmpfile) != 0)
            {
                if (filesize($tmpfile) <= 256000)
                {
                    $fh = fopen($tmpfile, "r");
                    $datafile = addslashes(fread($fh, filesize($tmpfile)));
                    fclose($fh);

                    $namefile = $file['name'];
                    $typefile = $file['type'];
                    $sizefile = $file['size'];
                }
                else
                {
                    $datafile = $warnfile;
                    $namefile = $warnname;
                    $typefile = $warntype;
                    $sizefile = $warnsize;
                }

                if ($repliddata == 0)
                    $sql = "INSERT INTO jbsakad.tambahandatacalon
                               SET nopendaftaran = '$no', idtambahan = '$replid', jenis = '2', 
                                   filedata = '$datafile', filename = '$namefile', filemime = '$typefile', filesize = '".$sizefile."'";
                else
                    $sql = "UPDATE jbsakad.tambahandatacalon
                               SET filedata = '$datafile', filename = '$namefile', filemime = '$typefile', filesize = '$sizefile'
                             WHERE replid = '".$repliddata."'";

                QueryDbTrans($sql, $success);
            }
        }
    }
}

if ($success)
    CommitTrans();
else
    RollbackTrans();

if ($success) { ?>

<script language="javascript">
	parent.opener.refresh_simpan('<?=$departemen?>','<?=$proses?>','<?=$kelompok?>');
	window.close();
</script> 
<?php  } 
//} 
//}

CloseDb();
?>

<!--</body>
</html>-->