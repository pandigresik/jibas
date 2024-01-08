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
OpenDb();

$dep_asal="";
$idkelas=$_REQUEST['kelas']; 
$idtingkat=$_REQUEST['tingkat']; 
$idtahunajaran=$_REQUEST['tahunajaran']; 
$departemen=$_REQUEST['departemen'];
$cek = 0;

$nis = trim($_REQUEST['nis']);
$nik = trim($_REQUEST['nik']);
$noun = trim($_REQUEST['noun']);
$nisn = trim($_REQUEST['nisn']);
$idangkatan = $_REQUEST['idangkatan'];
$tahunmasuk = $_REQUEST['tahunmasuk'];
$nama = trim($_REQUEST['nama']);
$panggilan = trim($_REQUEST['panggilan']);
$kelamin = $_REQUEST['kelamin'];
$tmplahir = trim($_REQUEST['tmplahir']);
$tgllahir = strlen($_REQUEST['tgllahir']) == 0 ? "1" : $_REQUEST['tgllahir']; 
$blnlahir = strlen($_REQUEST['blnlahir']) == 0 ? "1" : $_REQUEST['blnlahir'];
$thnlahir = strlen($_REQUEST['thnlahir']) == 0 ? "1970" : $_REQUEST['thnlahir'];
$lahir = $thnlahir . "-" . $blnlahir . "-" . $tgllahir;
$suku = $_REQUEST['suku'];
$agama = $_REQUEST['agama'];
$status = $_REQUEST['status'];
$kondisi = $_REQUEST['kondisi'];
$warga = isset($_REQUEST['warga']) ? $_REQUEST['warga'] : "WNI";
$urutananak = strlen($_REQUEST['urutananak']) == 0 ? 0 : $_REQUEST['urutananak'];
$jumlahanak = strlen($_REQUEST['jumlahanak']) == 0 ? 0 : $_REQUEST['jumlahanak'];
$statusanak = $_REQUEST['statusanak'];
$jkandung = strlen($_REQUEST['jkandung']) == 0 ? 0 : $_REQUEST['jkandung'];
$jtiri = strlen($_REQUEST['jtiri']) == 0 ? 0 : $_REQUEST['jtiri'];

$bahasa = trim($_REQUEST['bahasa']);
$alamatsiswa = trim($_REQUEST['alamatsiswa']);
$kodepos = trim($_REQUEST['kodepos']);
$jarak = (float)$_REQUEST['jarak'];
$telponsiswa = trim($_REQUEST['telponsiswa']);
$hpsiswa = trim(($_REQUEST['hpsiswa']));
$hpsiswa = str_replace(' ','',$hpsiswa);
$emailsiswa = trim($_REQUEST['emailsiswa']);
$dep_asal = $_REQUEST['dep_asal'];

$sekolah = $_REQUEST['sekolah'];
$noijasah = trim($_REQUEST['noijasah']);
$tglijasah = trim($_REQUEST['tglijasah']);
$ketsekolah = trim($_REQUEST['ketsekolah']);

$gol = $_REQUEST['gol'];
$berat = isset($_REQUEST['berat']) ? $_REQUEST['berat'] : "0";
$tinggi = isset($_REQUEST['tinggi']) ? $_REQUEST['tinggi'] : "0";
$kesehatan=trim($_REQUEST['kesehatan']);

$namaayah = trim($_REQUEST['namaayah']);
$namaibu = trim($_REQUEST['namaibu']);
$statusayah = $_REQUEST['statusayah'];
$statusibu = $_REQUEST['statusibu'];
$tmplahirayah = trim($_REQUEST['tmplahirayah']);
$tmplahiribu = trim($_REQUEST['tmplahiribu']);
$tgllahirayah = trim($_REQUEST['tgllahirayah']);
$tgllahiribu = trim($_REQUEST['tgllahiribu']);
$pendidikanayah = $_REQUEST['pendidikanayah'];
$pendidikanibu = $_REQUEST['pendidikanibu'];
$pekerjaanayah = $_REQUEST['pekerjaanayah'];
$pekerjaanibu = $_REQUEST['pekerjaanibu'];
$penghasilanayah = (isset($_REQUEST['penghasilanayah']))?$_REQUEST['penghasilanayah']:"0";
$penghasilanibu = (isset($_REQUEST['penghasilanibu']))?$_REQUEST['penghasilanibu']:"0";

$namawali=trim($_REQUEST['namawali']);
$alamatortu=trim($_REQUEST['alamatortu']);
$telponortu=trim($_REQUEST['telponortu']);
$hportu=trim($_REQUEST['hportu']);
$hportu=str_replace(' ','',$hportu);
$hportu2=trim($_REQUEST['hportu2']);
$hportu2=str_replace(' ','',$hportu2);
$hportu3=trim($_REQUEST['hportu3']);
$hportu3=str_replace(' ','',$hportu3);
$emailayah=trim($_REQUEST['emailayah']);
$emailibu=trim($_REQUEST['emailibu']);
$alamatsurat=trim($_REQUEST['alamatsurat']);
$keterangan=trim($_REQUEST['keterangan']);
$hobi=trim($_REQUEST['hobi']);
$almayah = (isset($_REQUEST['almayah']))?$_REQUEST['almayah']:"0";
$almibu = (isset($_REQUEST['almibu']))?$_REQUEST['almibu']:"0";
$idtambahan = $_REQUEST['idtambahan'];

$ERROR_MSG = "";
if (isset($_REQUEST['Simpan']))
{
	$sql_cek = "SELECT nis,nama from jbsakad.siswa where nis ='$nis'";
	$hasil_cek=QueryDb($sql_cek);
	$row=@mysqli_num_rows($hasil_cek);
	if (mysqli_num_rows($hasil_cek) > 0)
	{
		$cek = 1;
		$ERROR_MSG = "NIS $nis sudah digunakan!";
	} 
	else 
	{	
		$date=date('j');
		$month=date('m');
		$year=date('Y');
		$kumplit = date('Y')."-".date('m')."-".date('j');
		
        $suku_sql = ($suku == "") ? "suku = NULL" : "suku = '".$suku."'";
        $agama_sql = ($agama == "") ? "agama = NULL" : "agama = '".$agama."'";
        $status_sql = ($status == "") ? "status = NULL" : "status = '".$status."'";
        $kondisi_sql = ($kondisi == "") ? "kondisi = NULL" : "kondisi = '".$kondisi."'";
		$sekolah_sql = ($sekolah == "") ? "asalsekolah = NULL" : "asalsekolah = '".$sekolah."'";
		$pendidikanayah_sql = ($pendidikanayah == "") ? "pendidikanayah = NULL" : "pendidikanayah = '".$pendidikanayah."'";
		$pendidikanibu_sql = ($pendidikanibu == "") ? "pendidikanibu = NULL" : "pendidikanibu = '".$pendidikanibu."'";
		$pekerjaanayah_sql = ($pekerjaanayah == "") ? "pekerjaanayah = NULL" : "pekerjaanayah = '".$pekerjaanayah."'";
		$pekerjaanibu_sql = ($pekerjaanibu == "") ? "pekerjaanibu = NULL" : "pekerjaanibu = '".$pekerjaanibu."'";
		$kodepos_sql = ($kodepos == "") ? "kodepossiswa = NULL" : "kodepossiswa = '".$kodepos."'";
			
		$foto=$_FILES["file_data"];
		$uploadedfile = $foto['tmp_name'];
		$uploadedtypefile = $foto['type'];
		$uploadedsizefile = $foto['size'];
		if (strlen($uploadedfile)!=0)
		{
			$tmp_path = realpath(".") . "/../../temp";
			$tmp_exists = file_exists($tmp_path) && is_dir($tmp_path);
			if (!$tmp_exists)
				mkdir($tmp_path, 0755);
			
			$filename = "$tmp_path/ad-sis-tmp.jpg";
			ResizeImage($foto, 159, 120, 100, $filename);

			$fh = fopen($filename,"r");
			$foto_data = addslashes(fread($fh, filesize($filename)));
			fclose($fh);
			
			$gantifoto=", foto='$foto_data'";
		}
        else
        {
			$gantifoto="";
		}
		
		$pinsiswa = random(5);
		$pinortu = random(5);
		$pinortuibu = random(5);
		
		BeginTrans();
		
		$success = true;
		$sql_simpan = "INSERT INTO jbsakad.siswa
                          SET nis='$nis', nisn='$nisn', nik='$nik', noun='$noun', nama='$nama', panggilan='$panggilan', tahunmasuk='$tahunmasuk', idangkatan=$idangkatan,
                              idkelas='$idkelas', $suku_sql, $agama_sql, $status_sql, $kondisi_sql, kelamin='$kelamin', tmplahir='$tmplahir',
                              tgllahir='$lahir', warga='$warga', anakke=$urutananak, jsaudara=$jumlahanak, statusanak='$statusanak', jkandung='$jkandung', jtiri='$jtiri',
                              bahasa='$bahasa', berat=$berat, tinggi=$tinggi, darah='$gol', foto='$foto_data', alamatsiswa='$alamatsiswa', jarak='$jarak', $kodepos_sql, telponsiswa='$telponsiswa', hpsiswa='$hpsiswa',
                              emailsiswa='$emailsiswa', kesehatan='$kesehatan', $sekolah_sql, noijasah='$noijasah', tglijasah='$tglijasah', ketsekolah='$ketsekolah', namaayah='$namaayah', namaibu='$namaibu',
                              almayah=$almayah, almibu=$almibu, statusayah='$statusayah', statusibu='$statusibu', tmplahirayah='$tmplahirayah', tmplahiribu='$tmplahiribu', tgllahirayah='$tgllahirayah', tgllahiribu='$tgllahiribu',
                              $pendidikanayah_sql, $pendidikanibu_sql, $pekerjaanayah_sql, $pekerjaanibu_sql, wali='$namawali',
                              penghasilanayah=$penghasilanayah, penghasilanibu=$penghasilanibu, alamatortu='$alamatortu', telponortu='$telponortu',
                              hportu='$hportu', info1='$hportu2', info2='$hportu3', emailayah='$emailayah', emailibu='$emailibu', alamatsurat='$alamatsurat',
                              keterangan='$keterangan', hobi='$hobi', pinsiswa = '$pinsiswa', pinortu = '$pinortu', pinortuibu = '".$pinortuibu."'";
		QueryDbTrans($sql_simpan,$success);
		
		if ($success)
		{
			$sql_dept = "INSERT INTO jbsakad.riwayatdeptsiswa SET nis='$nis',departemen='$departemen',mulai='$kumplit'";		
			QueryDbTrans($sql_dept, $success);
		}
				
		if ($success)
		{
			$sql_kls="INSERT INTO jbsakad.riwayatkelassiswa SET nis='$nis',idkelas='$idkelas',mulai='$kumplit'";
			QueryDbTrans($sql_kls, $success);
		}

		if ($success)
        {
            if ($gantifoto != "")
            {
                $sql = "INSERT INTO jbsakad.riwayatfoto SET nis = '$nis', foto = '$foto_data', tanggal = NOW()";
                QueryDbTrans($sql, $success);
            }
        }

		if ($success && strlen($idtambahan) > 0)
        {
            if (strpos($idtambahan, ",") === false)
                $arridtambahan = array($idtambahan);
            else
                $arridtambahan = explode(",", $idtambahan);

            // READ WARNING IMAGE
            $warnimg = "../images/warningimg.jpg";
            $fh = fopen($warnimg,"r");
            $warnsize = filesize($warnimg);
            $warnfile = addslashes(fread($fh, $warnsize));
            $warntype = "image/jpeg";
            $warnname = "warning.jpg";
            fclose($fh);

            for($i = 0; $success && $i < count($arridtambahan); $i++)
            {
                $replid = $arridtambahan[$i];

                $param = "jenisdata-$replid";
                if (!isset($_REQUEST[$param]))
                    continue;

                $jenis = $_REQUEST[$param];
                if ($jenis == 1 || $jenis == 3)
                {
                    $param = "tambahandata-$replid";
                    if (!isset($_REQUEST[$param]))
                        continue;

                    $teks = $_REQUEST[$param];
                    $teks = CQ($teks);

                    $sql = "INSERT INTO jbsakad.tambahandatasiswa
                               SET nis = '$nis', idtambahan = '$replid', jenis = '$jenis', teks = '".$teks."'";
                    QueryDbTrans($sql, $success);
                }
                else if ($jenis == 2)
                {
                    $param = "tambahandata-$replid";
                    if (!isset($_FILES[$param]))
                        continue;

                    $file = $_FILES[$param];
                    $tmpfile = $file['tmp_name'];

                    if (strlen($tmpfile) != 0)
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

                        $sql = "INSERT INTO jbsakad.tambahandatasiswa
                                   SET nis = '$nis', idtambahan = '$replid', jenis = '2', 
                                       filedata = '$datafile', filename = '$namefile', filemime = '$typefile', filesize = '".$sizefile."'";
                        QueryDbTrans($sql, $success);
                    }
                }
            }
        }
	
		if ($success)
		{
			CommitTrans();
            CloseDb();
            ?>
			<script language="javascript">
				parent.opener.refresh_after_add();
				window.close();
			</script>
		<?php 	
		} 
		else 
		{
			RollbackTrans(); ?>
			<script language="javascript">
				alert ('Data gagal disimpan');
			</script>
<?php 	}
	}
}
?>