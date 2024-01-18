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
require_once('../include/imageresizer.php');
require_once('../include/fileinfo.php');

class DaftarPribadi
{
    public $ERRMSG;
    
    public $nip;
    public $pegawai;  // nama pegawai
    public $replid;   // replid pegawai
	
	 public $bagian;    
	 public $nuptk;
	 public $nrp;
    public $nama;
	 public $panggilan;
    public $gelarawal;
    public $gelarakhir;
    public $newnip;
    public $tmplahir;
    public $tgllahir;
    public $blnlahir;
    public $thnlahir;
    public $agama;
	 public $suku;
    public $nikah;
    public $kelamin;
    public $alamat;
    public $hp;
    public $telpon;
    public $email;
	 public $facebook;
	 public $twitter;
	 public $website;
    public $foto;
    public $status;
    public $tglmulai;
    public $blnmulai;
    public $thnmulai;
    public $keterangan;
    public $alasan;
    public $aktif;
    public $ketnonaktif;
    public $pns;
    public $idtambahan;

    // 2020-10-20
    public $pinpegawai;

    public function __construct()
    {
        $this->nip = $_REQUEST["nip"];
        $this->GetPegawaiInfo();
		
		$op = $_REQUEST['op'];
		if (isset($_REQUEST['btSubmit']))
            $this->SaveData();
        else
            $this->LoadData();
    }
    
    private function GetPegawaiInfo()
    {
        $sql = "SELECT TRIM(CONCAT(IFNULL(gelarawal,''), ' ' , nama, ' ', IFNULL(gelarakhir,''))) AS nama, replid
                  FROM jbssdm.pegawai p
                 WHERE p.nip='$this->nip'";
        $result = QueryDb($sql);
        $row = mysqli_fetch_row($result);
        $this->pegawai = $row[0];
        $this->replid = $row[1];
    }
    
    private function SaveData()
    {
		  $this->bagian = $_REQUEST['rbBagian'];
		  $this->nuptk = $_REQUEST['txNUPTK'];
		  $this->nrp = $_REQUEST['txNRP'];
        $this->nama = CQ($_REQUEST['txNama']);
		  $this->panggilan = CQ($_REQUEST['txPanggilan']);
        $this->gelarawal = $_REQUEST['txGelarAwal'];
        $this->gelarakhir = $_REQUEST['txGelarAkhir'];
        $this->newnip  = $_REQUEST['txNIP'];
        $this->tmplahir = $_REQUEST['txTmpLahir'];
        $this->tgllahir = $_REQUEST['cbTglLahir'];
        $this->blnlahir = $_REQUEST['cbBlnLahir'];
        $this->thnlahir = $_REQUEST['txThnLahir'];
        $this->agama = $_REQUEST['cbAgama'];
		  $this->suku = $_REQUEST['cbSuku'];
        $this->nikah = $_REQUEST['cbNikah'];
        $this->kelamin = $_REQUEST['cbKelamin'];
        $this->alamat = CQ($_REQUEST['txAlamat']);
        $this->hp = $_REQUEST['txHP'];
        $this->telpon = $_REQUEST['txTelpon'];
        $this->email = $_REQUEST['txEmail'];
		  $this->facebook = $_REQUEST['txFacebook'];
		  $this->twitter = $_REQUEST['txTwitter'];
		  $this->website = $_REQUEST['txWebsite'];
        $this->foto = $_FILES['foto'];
        $this->status = $_REQUEST['cbStatus'];
        $this->tglmulai = $_REQUEST['cbTglMulai'];
        $this->blnmulai = $_REQUEST['cbBlnMulai'];
        $this->thnmulai = $_REQUEST['txThnMulai'];
        $this->keterangan = CQ($_REQUEST['txKeterangan']);
        $this->alasan = CQ($_REQUEST['txAlasan']);
        $this->aktif = $_REQUEST['rbAktif'];
        $this->ketnonaktif = CQ($_REQUEST['txKetNonAktif']);
        $this->pns = $_REQUEST['rbPNS'];
        $this->idtambahan = $_REQUEST['idtambahan'];
	
        $sql = "SELECT replid FROM jbssdm.pegawai WHERE nip = '$this->newnip' AND nip <> '$this->nip'";
    	$result = QueryDb($sql);
        if (mysqli_num_rows($result) > 0)
        {
    		$this->ERRMSG = "Telah ada pegawai dengan NIP $nip";
        }
        else
        {
            $bday = "$this->thnlahir-$this->blnlahir-$this->tgllahir";
            $sday = "$this->thnmulai-$this->blnmulai-$this->tglmulai";
            
			$gantifoto = "";
			if (strlen((string) $this->foto['tmp_name']) != 0)
			{
				$output = "../temp/img.tmp";
				ResizeImage($this->foto, 320, 240, 70, $output);
				
				$foto_data = addslashes(fread(fopen($output,"r"), filesize($output)));
				$gantifoto = ", foto='$foto_data'";
				
				unlink($output);
			}
            
			$success = true;
			BeginTrans();
            
			$sql = "UPDATE jbssdm.pegawai
					   SET nama='$this->nama', panggilan='$this->panggilan', gelarawal='$this->gelarawal', gelarakhir='$this->gelarakhir', nip='$this->newnip',
						   nuptk='$this->nuptk', nrp='$this->nrp', tmplahir='$this->tmplahir',
						   tgllahir='$bday', alamat='$this->alamat', handphone='$this->hp', telpon='$this->telpon', email='$this->email', mulaikerja='$sday',
						   keterangan='$this->keterangan', nikah='$this->nikah', agama='$this->agama', suku='$this->suku',
						   kelamin='$this->kelamin', facebook='$this->facebook', twitter='$this->twitter', website='$this->website'
						   $gantifoto
					 WHERE nip='$this->nip'";
			QueryDbTrans($sql, $success);

            if ($success && strlen((string) $this->idtambahan) > 0)
            {
                if (!str_contains((string) $this->idtambahan, ","))
                    $arridtambahan = [$this->idtambahan];
                else
                    $arridtambahan = explode(",", (string) $this->idtambahan);

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
                    if (!isset($_REQUEST[$param])) continue;
                    $jenis = $_REQUEST[$param];

                    $param = "repliddata-$replid";
                    if (!isset($_REQUEST[$param])) continue;
                    $repliddata = $_REQUEST[$param];

                    if ($jenis == 1 || $jenis == 3)
                    {
                        $param = "tambahandata-$replid";
                        if (!isset($_REQUEST[$param])) continue;
                        $teks = $_REQUEST[$param];
                        $teks = CQ($teks);

                        if ($repliddata == 0)
                            $sql = "INSERT INTO jbssdm.tambahandatapegawai
                                       SET nip = '$this->nip', idtambahan = '$replid', jenis = '$jenis', teks = '".$teks."'";
                        else
                            $sql = "UPDATE jbssdm.tambahandatapegawai
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
                                $sql = "INSERT INTO jbssdm.tambahandatapegawai
                                           SET nip = '$this->nip', idtambahan = '$replid', jenis = '2', 
                                               filedata = '$datafile', filename = '$namefile', filemime = '$typefile', filesize = '".$sizefile."'";
                            else
                                $sql = "UPDATE jbssdm.tambahandatapegawai
                                           SET filedata = '$datafile', filename = '$namefile', filemime = '$typefile', filesize = '$sizefile'
                                         WHERE replid = '".$repliddata."'";

                            QueryDbTrans($sql, $success);
                        }
                    }
                }
            }
						
			if ($success)
			{
				CommitTrans();
				CloseDb(); ?>
				<script language="javascript">
					var r = Math.floor((Math.random()*1000000)+1); 
	                document.location.href = "daftarpribadi.php?r="+r+"&nip=<?=$this->newnip?>&replid=<?=$this->replid?>";
	            </script>
<?php 			exit();
			}
			else
			{
				$this->ERRMSG = "Gagal menyimpan data!";
				RollbackTrans();
			}
	    }
    }
    
    private function LoadData()
    {
        $sql = "SELECT * FROM jbssdm.pegawai WHERE nip='$this->nip'";
        $result = QueryDb($sql);
        $row = mysqli_fetch_array($result);
		$this->bagian = $row['bagian'];
		$this->nuptk = $row['nuptk'];
		$this->nrp = $row['nrp'];
        $this->nama = $row['nama']; //
		$this->panggilan = $row['panggilan']; //
        $this->tmplahir = $row['tmplahir']; //
        $this->tgllahir = GetDatePart($row['tgllahir'], "d"); //
        $this->blnlahir = GetDatePart($row['tgllahir'], "m"); //
        $this->thnlahir = GetDatePart($row['tgllahir'], "y"); //
        $this->nikah = $row['nikah']; //
        $this->agama = $row['agama']; //
		$this->suku = $row['suku']; //
        $this->kelamin = $row['kelamin']; //
        $this->alamat = $row['alamat']; //
        $this->hp = $row['handphone']; //
        $this->telpon = $row['telpon']; //
        $this->email = $row['email']; //
		$this->facebook = $row['facebook']; //
		$this->twitter = $row['twitter']; //
		$this->website = $row['website']; //
        $this->foto = $row['foto']; //
        $this->aktif = $row['aktif']; //
        $this->keterangan = $row['keterangan']; //
		$this->gelarawal = $row['gelarawal'];
        $this->gelarakhir = $row['gelarakhir'];
		$this->tglmulai = GetDatePart($row['mulaikerja'], "d");
        $this->blnmulai = GetDatePart($row['mulaikerja'], "m");
        $this->thnmulai = GetDatePart($row['mulaikerja'], "y");
		$this->pns = $row['status'];
		$this->ketnonaktif = $row['ketnonaktif'];

		// 2020-10-20
		$this->pinpegawai = $row['pinpegawai'];
    }
}
?>