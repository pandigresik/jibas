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

class PegawaiInput
{
    public $ERRMSG;
    
    public $nama = "";
    public $panggilan = "";
    public $gelarawal = "";
    public $gelarakhir = "";
    public $nip  = "";
    public $nuptk  = "";
    public $nrp  = "";
    public $tmplahir = "";
    public $tgllahir = 1;
    public $blnlahir = 1;
    public $thnlahir = "";
    public $agama = "";
    public $suku = "";
    public $nikah = "";
    public $kelamin = "";
    public $alamat = "";
    public $hp = "";
    public $telpon = "";
    public $email = "";
    public $facebook = "";
    public $twitter = "";
    public $website = "";
    public $tglmulai = 1;
    public $blnmulai = 1;
    public $thnmulai = "";
    public $keterangan = "";
    public $pns = 1;
    public $bagian = "Akademik";
    public $idtambahan = "";
    
    public function __construct()
    {
        $this->InitVariables();
                
        if (isset($_REQUEST['btSubmit']))
            $this->SaveData();
    }
    
    private function InitVariables()
    {
        $this->nama = CQ($_REQUEST['txNama']);
        $this->panggilan = CQ($_REQUEST['txPanggilan']);
        $this->gelarawal = $_REQUEST['txGelarAwal'];
        $this->gelarakhir = $_REQUEST['txGelarAkhir'];
        $this->nip  = $_REQUEST['txNIP'];
        $this->nuptk  = $_REQUEST['txNUPTK'];
        $this->nrp  = $_REQUEST['txNRP'];
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
        $this->tglmulai = $_REQUEST['cbTglMulai'];
        $this->blnmulai = $_REQUEST['cbBlnMulai'];
        $this->thnmulai = $_REQUEST['txThnMulai'];
        $this->keterangan = CQ($_REQUEST['txKeterangan']);
        $this->pns = $_REQUEST['rbPNS'];
        $this->bagian = $_REQUEST['rbBagian'];
        $this->idtambahan = $_REQUEST['idtambahan'];
        
        if (!isset($_REQUEST['rbPNS']))
            $this->pns = "PNS";
    }
    
    private function SaveData()
    {
    	$sql = "SELECT replid FROM jbssdm.pegawai WHERE nip='$this->nip'";
    	$result = QueryDb($sql);
    	if (mysqli_num_rows($result) > 0)
        {
    		$this->ERRMSG = "Telah ada pegawai dengan NIP $this->nip";
            return;        
    	}
        
        $bday = "$this->thnlahir-$this->blnlahir-$this->tgllahir";
        $sday = "$this->thnmulai-$this->blnmulai-$this->tglmulai";
    
        if (strlen((string) $this->foto['tmp_name']) != 0)
        {
            $output = "../temp/img.tmp";
            ResizeImage($this->foto, 320, 240, 75, $output);
            
            $foto_data = addslashes(fread(fopen($output,"r"), filesize($output)));
            $foto = ", foto='$foto_data'";
            
            unlink($output);
        }
        else
        {
            $foto = "";
        }
        
        $success = true;
        BeginTrans();
        
        $pin = random(5);    
        $sql = "INSERT INTO jbssdm.pegawai SET 
        			nip='$this->nip',
                    nuptk='$this->nuptk',
                    nrp='$this->nrp',
					nama='$this->nama',
					panggilan='$this->panggilan',
					tmplahir='$this->tmplahir',
					tgllahir='$bday',
					agama='$this->agama',
					suku='$this->suku',
					alamat='$this->alamat',
					telpon='$this->telpon',
					handphone='$this->hp',
					email='$this->email',
                    facebook='$this->facebook',
                    twitter='$this->twitter',
                    website='$this->website',
					bagian='$this->bagian',
					pinpegawai='$pin',
					nikah='$this->nikah',
					keterangan='$this->keterangan',
					kelamin='$this->kelamin',
                    mulaikerja='$sday',
					gelarawal='$this->gelarawal',
					gelarakhir='$this->gelarakhir',
					status='$this->pns'
                    $foto";
        QueryDbTrans($sql, $success);
        
        if ($success)
        {
            $sql = "INSERT INTO jbssdm.peglastdata SET nip='$this->nip'";
            QueryDbTrans($sql, $success);
		}

		if ($success)
        {
            if ($foto != "")
            {
                $sql = "INSERT INTO jbsakad.riwayatfoto SET nip = '$this->nip', foto = '$foto_data', tanggal = NOW()";
                QueryDbTrans($sql, $success);
            }
        }

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

                    $sql = "INSERT INTO jbssdm.tambahandatapegawai
                               SET nip = '$this->nip', idtambahan = '$replid', jenis = '$jenis', teks = '".$teks."'";
                    QueryDbTrans($sql, $success);
                }
                else if ($jenis == 2)
                {
                    $param = "tambahandata-$replid";
                    if (!isset($_FILES[$param]))
                        continue;

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

                        $sql = "INSERT INTO jbssdm.tambahandatapegawai
                                   SET nip = '$this->nip', idtambahan = '$replid', jenis = '2', 
                                       filedata = '$datafile', filename = '$namefile', filemime = '$typefile', filesize = '".$sizefile."'";
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
                alert("Data telah tersimpan")
				document.location.href = "pegawaiinput.php";
	        </script>
<?php 		exit();	          
		}
        else
        {
			$this->ERRMSG = "Gagal menyimpan data!";
			RollbackTrans();
            CloseDb();
		}
    }
}
?>