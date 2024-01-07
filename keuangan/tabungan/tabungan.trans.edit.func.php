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
function GetDataTransaksi()
{
    global $idpembayaran;
    global $nis, $namasiswa, $idjurnal, $tanggal, $keterangan, $idtabungan, $namatabungan;
    global $debet, $kredit, $rekkas, $rekutang, $action, $jbayar, $rekkastrans;
    
    // -- ambil data-data pembayaran ---------------------------------
    $sql = "SELECT s.nis, s.nama, p.idjurnal, p.debet, p.kredit, DATE_FORMAT(p.tanggal, '%d-%m-%Y') AS tanggal, 
                   p.keterangan, pn.nama as namatabungan, pn.rekkas, pn.rekutang, pn.replid AS idtabungan
              FROM tabungan p, jbsakad.siswa s, datatabungan pn 
             WHERE p.replid = '$idpembayaran'
               AND p.nis = s.nis
               AND p.idtabungan = pn.replid";
    $row = FetchSingleArray($sql);
    
    $nis = $row['nis'];
    $namasiswa = $row['nama'];
    $idjurnal = $row['idjurnal'];
    $tanggal = $row['tanggal'];
    $keterangan = $row['keterangan'];
    $idtabungan = $row['idtabungan'];
    $namatabungan = $row['namatabungan'];
    $debet = (int) $row['debet'];
    $kredit = (int) $row['kredit'];
    $rekkas = $row['rekkas'];
    $rekutang = $row['rekutang'];
    $action = ($debet == 0) ? "setor" : "tarik";
    $jbayar = ($debet == 0) ? $kredit : $debet;
    
    $sql = "SELECT jd.koderek, jd.replid
              FROM jurnaldetail jd, rekakun r
             WHERE jd.koderek = r.kode
               AND r.kategori = 'HARTA'
               AND jd.idjurnal = '".$idjurnal."'";
    $row = FetchSingleArray($sql);
    $rekkastrans = $row['koderek'];
    $idjurnalrekkas = $row['replid'];
}

function SimpanTransaksi()
{
    global $idpembayaran, $errmsg;
    global $nis, $namasiswa, $idjurnal, $tanggal, $keterangan, $idtabungan, $namatabungan;
    global $debet, $kredit, $rekkas, $rekutang, $action, $jbayar, $rekkastrans, $idjurnalrekkas;
    
    $jbayar = (int)UnformatRupiah($_REQUEST['jbayar']);
    $rekkas = $_REQUEST['rekkas'];
	$tbayar = MySqlDateFormat($_REQUEST['tbayar']);
	$kbayar = CQ($_REQUEST['keterangan']);
    $alasan = CQ($_REQUEST['alasan']);
	$petugas = getUserName();
    
    BeginTrans();
	$success = true;
    $errmsg = "";
    
    if (($debet == 0 && $jbayar == $kredit) || ($kredit == 0 && $jbayar == $debet))
    {
        //--------------------------------------------------------------
		// Hanya mengubah informasi pembayaran tanpa mengubah besarnya  
		// -------------------------------------------------------------
       	
		$sql = "UPDATE tabungan
				   SET tanggal='$tbayar',
                       keterangan='$kbayar',
                       alasan='$alasan',
					   petugas='$petugas'
				 WHERE replid=$idpembayaran";
		$result = QueryDbTrans($sql, $success);        
    }
    else
    {
        //----------------------------
		// Mengubah besar pembayaran  
		// ---------------------------
                
        $action = $_REQUEST['action'];
        
        if ($action == "tarik")
        {
            // Cek Saldo
            $sql = "SELECT SUM(debet), SUM(kredit)
                      FROM tabungan
                     WHERE nis = '$nis'
                       AND idtabungan = '".$idtabungan."'";
            $result = QueryDbTrans($sql, $success);
            $row = mysqli_fetch_row($result);
            $jsetor = (int)$row[1];
            $jtarik = (int)$row[0];
            $jsaldo = $jsetor - $jtarik;
        
            $sql = "SELECT debet
                      FROM tabungan
                     WHERE replid = $idpembayaran";
            $result = QueryDbTrans($sql, $success);
            $row = mysqli_fetch_row($result);
            $debetawal = (int)$row[0];
                        
            $jsaldo = $jsaldo + $debetawal;
            if ($jsaldo < $jbayar)
            {
                $errmsg = "Saldo tabungan tidak mencukupi untuk penarikan!";
                return;
            }
        }
        else
        {
            // Cek Saldo
            $sql = "SELECT kredit
                      FROM tabungan
                     WHERE replid = $idpembayaran";
            $result = QueryDbTrans($sql, $success);
            $row = mysqli_fetch_row($result);
            $kreditawal = (int)$row[0];
            
            if ($jbayar < $kreditawal)
            {
                $sql = "SELECT SUM(debet), SUM(kredit)
                          FROM tabungan
                         WHERE nis = '$nis'
                           AND idtabungan = '".$idtabungan."'";
                $result = QueryDbTrans($sql, $success);
                $row = mysqli_fetch_row($result);
                $jsetor = (int)$row[1];
                $jtarik = (int)$row[0];
                                
                $jsetor = $jsetor - $kreditawal + $jbayar;
                //$errmsg = "$jsetor vs $jtarik";
                //return;
                if ($jsetor < $jtarik)
                {
                    $errmsg = "Saldo tabungan akan menjadi NEGATIF!";
                    return;    
                }
            }
        }
        
        if ($action == "setor")
        {
            $debet = 0;
            $kredit = $jbayar;
        }
        else
        {
            $debet = $jbayar;
            $kredit = 0;
        }
        
        $sql = "UPDATE tabungan
				   SET tanggal='$tbayar',
                       keterangan='$kbayar',
                       alasan='$alasan',
					   petugas='$petugas',
                       debet='$debet',
                       kredit='$kredit'
				 WHERE replid=$idpembayaran";
		$result = QueryDbTrans($sql, $success);
        
        $idjurnal = 0;
        if ($success)
        {
            $sql = "SELECT idjurnal FROM tabungan WHERE replid = '".$idpembayaran."'";
            $idjurnal = FetchSingle($sql);
        }
        
        if ($success)
        {
            $sql = "UPDATE jurnaldetail
                       SET debet='$kredit', kredit='$debet'
                     WHERE idjurnal='$idjurnal'
                       AND koderek='$rekkastrans'";
            QueryDbTrans($sql, $success);	
        }
                
        if ($success)
        {
            $sql = "UPDATE jurnaldetail
                       SET debet='$debet', kredit='$kredit'
                     WHERE idjurnal='$idjurnal'
                       AND koderek='$rekutang'";
            QueryDbTrans($sql, $success);	
        }
    }
    
    if ($rekkas != $rekkastrans)
    {
        $sql = "UPDATE jurnaldetail
                   SET koderek='$rekkas'
                 WHERE idjurnal='$idjurnal'
                   AND koderek='$rekkastrans'";
        QueryDbTrans($sql, $success);	
    }
    
    if ($success) 
    {
        CommitTrans();
        CloseDb();
        echo  "<script language='javascript'>";
        echo  "opener.refresh();";
        echo  "window.close();";
        echo  "</script>";
        exit();
    } 
    else 
    {
        RollbackTrans();
        CloseDb();
        echo  "<script language='javascript'>";
        echo  "alert('Gagal mengubah data!');";
        echo  "</script>";
        exit();
    }
}
?>