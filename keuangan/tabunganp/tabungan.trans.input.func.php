<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 23.0 (November 12, 2020)
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
function FetchDataPegawai()
{
    global $nip, $nama, $telpon, $hp, $namatingkat, $namakelas, $alamattinggal, $keterangansiswa, $replid;
        
    $sql = "SELECT replid, nip, nama, bagian, handphone FROM jbssdm.pegawai WHERE nip = '".$nip."'";
    $result = QueryDb($sql);
    if (mysqli_num_rows($result) == 0) 
    {
        // tidak ditemukan data siswa, aplikasi keluar!
        CloseDb();
        exit();
    } 
    else 
    {
        $row = mysqli_fetch_array($result);
        $replid = $row['replid'];
        $nama = $row['nama'];
        $telpon = $row['telpon'];
        $hp = $row['handphone'];
        $alamattinggal = "";
        $namakelas = $row['bagian'];
        $keterangansiswa = $row['keterangan'];
    }    
}

function FetchDataTabungan()
{
    global $idtabungan, $namatabungan, $defrekkas, $smsinfo;
    
    $sql = "SELECT nama, rekkas, info2 FROM datatabunganp WHERE replid = '".$idtabungan."'";
    $result = QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $namatabungan = $row[0];
    $defrekkas = $row[1];  // Default Rekening Kas
    $smsinfo = (int)$row[2];
}

function SimpanTransaksi()
{
    $op = $_REQUEST['op'];
    
    if ($op == "348328947234923")
        SimpanSetoran();
        
    if ($op == "348328947234925")
        SimpanTarikan();
}

function SimpanTarikan()
{
    global $smsinfo;
    
    $nip = $_REQUEST['nip'];
    $idtabungan = $_REQUEST['idtabungan'];
    $idtahunbuku = $_REQUEST['idtahunbuku'];
    $jtarik = (int)$_REQUEST['jtarik'];
    $keterangantarik = $_REQUEST['keterangan'];
    
    $sql = "SELECT SUM(kredit - debet)
              FROM tabunganp
             WHERE nip = '$nip'
               AND idtabungan = '".$idtabungan."'";
    $jsaldo = (int)FetchSingle($sql);
    if ($jtarik > $jsaldo)
    {
        $errmsg = "Saldo tabungan tidak mencukupi untuk penarikan";
        
        $r = random_int(10000, 99999);		
        header("Location: tabungan.trans.input.php?r=$r&idtabungan=$idtabungan&nip=$nip&idtahunbuku=$idtahunbuku&errmsg=$errmsg&jtarik=$jtarik&keterangantarik=$keterangantarik");
	
        exit();   
    }
    
    // Ambil informasi kode rekening berdasarkan jenis penerimaan
	$sql = "SELECT rekkas, rekutang, nama
              FROM datatabunganp
             WHERE replid = '".$idtabungan."'";
	$row = FetchSingleRow($sql);
	$rekkas = $row[0];
	$rekutang = $row[1];
	$namatabungan = $row[2];
    
    if (isset($_REQUEST['rekkas']))
        $rekkas = $_REQUEST['rekkas'];
    
    // linked variable
	$pengguna = getUserName();
	$errmsg = "";
    
    //Ambil nama siswa
    $sql = "SELECT nama FROM jbssdm.pegawai WHERE nip = '".$nip."'";
    $row = FetchSingleRow($sql);
    $namapegawai = $row[0];
    
    //Ambil awalan dan cacah tahunbuku untuk bikin nokas;
    $sql = "SELECT awalan, cacah FROM tahunbuku WHERE replid = '".$idtahunbuku."'";
    $row = FetchSingleRow($sql);
    $awalan = $row[0];
    $cacah = $row[1];
    
    $cacah += 1; // Increment cacah
    $nokas = $awalan . rpad($cacah, "0", 6); // Form nomor kas
        
    // tanggal & petugas pendata & keterangan
    $ttarik = date("Y-m-d");
    $idpetugas = getIdUser();
    $petugas = getUserName();
    $keterangan = "Penarikan Tabungan $namatabungan pegawai $namapegawai ($nip)";
    $ketsms = "Penarikan Tabungan $namatabungan";
    
    $success = true;
    BeginTrans();
    
    // simpan ke table jurnal
    $idjurnal = 0;
    if ($success)
        $success = SimpanJurnal($idtahunbuku, $ttarik, $keterangan, $nokas, "", $idpetugas, $petugas, "tarikantabunganp", $idjurnal);
    
    // simpan ke tabel tabungan
    if ($success) 
    {
        $sql = "INSERT INTO tabunganp
                   SET nip='$nip', idtabungan='$idtabungan', debet='$jtarik', kredit='0',
                       keterangan = '" . CQ($keterangantarik) . "', 
                       petugas='$idpetugas', idjurnal='$idjurnal', tanggal=NOW()";		
        QueryDbTrans($sql, $success);
    }
    
    // simpan ke table jurnaldetail
    if ($success) 
        $success = SimpanDetailJurnal($idjurnal, "K", $rekkas, $jtarik);
        
    if ($success) 
        $success = SimpanDetailJurnal($idjurnal, "D", $rekutang, $jtarik);
        
    //increment cacah di tahunbuku
    if ($success) 
    {
        $sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid=$idtahunbuku";
        QueryDbTrans($sql, $success);
    }
    
    // -- Kirim SMS Informasi Pembayaran Pegawai
    if ($success && $smsinfo == 1)
    {
        $sql = "SELECT departemen
                  FROM jbsfina.tahunbuku
                 WHERE replid = '".$idtahunbuku."'";
        $departemen = FetchSingle($sql);

        $jsaldo = $jsaldo - $jtarik;
        
        CreateSMSTabungan('PEGTAB',
                             $departemen, $nip, $namapegawai,
                             RegularDateFormat($ttarik),
                             FormatRupiah($jtarik),
                             FormatRupiah($jsaldo),
                             $ketsms,
                             $keterangantarik,
                             $success);
    }
    
    if ($success) 
        CommitTrans();				
    else 
        RollbackTrans();
        
    CloseDb();

    $r = random_int(10000, 99999);		
	header("Location: tabungan.trans.input.php?r=$r&idtabungan=$idtabungan&nip=$nip&idtahunbuku=$idtahunbuku&errmsg=$errmsg&jtarik=$jtarik&keterangantarik=$keterangantarik");
	
	exit();   
}

function SimpanSetoran()
{
    global $smsinfo;

    $nip = $_REQUEST['nip'];
    $idtabungan = $_REQUEST['idtabungan'];

    // Ambil informasi kode rekening berdasarkan jenis penerimaan
	$sql = "SELECT rekkas, rekutang, nama
              FROM datatabunganp
             WHERE replid = '".$idtabungan."'";

	$row = FetchSingleRow($sql);
	$rekkas = $row[0];
	$rekutang = $row[1];
	$namatabungan = $row[2];

    $sql = "SELECT SUM(kredit - debet)
              FROM tabunganp
             WHERE nip = '$nip'
               AND idtabungan = '".$idtabungan."'";
    $jsaldo = (int)FetchSingle($sql);
    
    if (isset($_REQUEST['rekkas']))
        $rekkas = $_REQUEST['rekkas'];
    
    // linked variable
	$pengguna = getUserName();
	$jsetor = $_REQUEST['jsetor'];
	$keterangansetor = $_REQUEST['keterangan'];
	$errmsg = "";
    
    //Ambil nama siswa

    $sql = "SELECT nama FROM jbssdm.pegawai WHERE nip = '".$nip."'";
    $row = FetchSingleRow($sql);
    $namapegawai = $row[0];
    
    //Ambil awalan dan cacah tahunbuku untuk bikin nokas;
    $idtahunbuku = $_REQUEST['idtahunbuku'];
    $sql = "SELECT awalan, cacah FROM tahunbuku WHERE replid = '".$idtahunbuku."'";
    $row = FetchSingleRow($sql);
    $awalan = $row[0];
    $cacah = $row[1];
    
    $cacah += 1; // Increment cacah
    $nokas = $awalan . rpad($cacah, "0", 6); // Form nomor kas
        
    // tanggal & petugas pendata & keterangan
    $tsetor = date("Y-m-d");
    $idpetugas = getIdUser();
    $petugas = getUserName();
    $keterangan = "Setoran Tabungan $namatabungan pegawai $namapegawai ($nip)";
    $ketsms = "Setoran Tabungan $namatabungan";

    $success = true;
    BeginTrans();
    
    // simpan ke table jurnal
    $idjurnal = 0;
    if ($success)
        $success = SimpanJurnal($idtahunbuku, $tsetor, $keterangan, $nokas, "", $idpetugas, $petugas, "setorantabunganp", $idjurnal);
    
    // simpan ke tabel tabungan
    if ($success) 
    {
        $sql = "INSERT INTO tabunganp
                   SET nip='$nip', idtabungan='$idtabungan', debet='0', kredit='$jsetor',
                       keterangan = '" . CQ($keterangansetor) . "', 
                       petugas='$idpetugas', idjurnal='$idjurnal', tanggal=NOW()";		
        QueryDbTrans($sql, $success);
    }
    // simpan ke table jurnaldetail
    if ($success) 
        $success = SimpanDetailJurnal($idjurnal, "D", $rekkas, $jsetor);
        
    if ($success) 
        $success = SimpanDetailJurnal($idjurnal, "K", $rekutang, $jsetor);
        
    //increment cacah di tahunbuku
    if ($success) 
    {
        $sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid=$idtahunbuku";
        QueryDbTrans($sql, $success);
    }
    
    // -- Kirim SMS Informasi Pembayaran Siswa
    if ($success && $smsinfo == 1)
    {
        $sql = "SELECT departemen
                  FROM jbsfina.tahunbuku
                 WHERE replid = '".$idtahunbuku."'";
        $departemen = FetchSingle($sql);

        $jsaldo = $jsaldo + $jsetor;
        
        CreateSMSTabungan('PEGTAB',
                           $departemen, $nip, $namapegawai,
                           RegularDateFormat($tsetor),
                           FormatRupiah($jsetor),
                           FormatRupiah($jsaldo),
                           $ketsms,
                           $keterangansetor,
                           $success);
    }
    
    if ($success) 
        CommitTrans();				
    else 
        RollbackTrans();
        
    CloseDb();
    
    $r = random_int(10000, 99999);		
	header("Location: tabungan.trans.input.php?r=$r&idtabungan=$idtabungan&nip=$nip&idtahunbuku=$idtahunbuku&errmsg=$errmsg&jsetor=$jsetor&keterangansetor=$keterangansetor");
	
	exit();    
}



?>