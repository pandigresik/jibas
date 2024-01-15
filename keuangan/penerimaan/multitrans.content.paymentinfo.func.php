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
require_once('../include/rupiah.php');

function ShowSelectRekKas($defrekkas)
{
    echo "<select name='rekkas' id='rekkas' style='width: 220px'>\r\n";
    $sql = "SELECT kode, nama
              FROM jbsfina.rekakun
             WHERE kategori = 'HARTA'
             ORDER BY nama";        
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $sel = $row[0] == $defrekkas ? "selected" : "";
        echo "<option value='".$row[0]."' $sel>{$row[0]} {$row[1]}</option>";
    }
    echo "</select>";
}

function InfoSkrSiswa()
{
    global $noid, $idpayment, $idtahunbuku;
    
    $sql = "SELECT rekkas
              FROM jbsfina.datapenerimaan
             WHERE replid = '".$idpayment."'";
    $defrekkas = FetchSingle($sql); 
    
    echo "<table border='0' cellpadding='2' cellspacing='0'>\r\n";
    echo "<tr>\r\n";
    echo "<td align='left' colspan='2'><strong><u>Pembayaran</u></strong></td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right' width='80'><strong>Jumlah:</strong></td>\r\n";
    echo "<td align='left'>\r\n";
    echo "&nbsp;&nbsp;<input type='text' name='jumlah' id='jumlah' size='15' onblur=\"formatRupiah('jumlah');\" onfocus=\"unformatRupiah('jumlah')\">\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right'><strong>Rek. Kas:</strong></td>\r\n";
    echo "<td align='left'>&nbsp;\r\n";
    ShowSelectRekKas($defrekkas);
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right'>Keterangan:</td>\r\n";
    echo "<td align='left'>\r\n";
    echo "&nbsp;&nbsp;<input type='text' name='keterangan' id='keterangan' size='35' $ro $rostyle value='$keterangan'>\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='left' colspan='2'>\r\n";
    echo "<input type='button' class='but' style='height: 30px' value='Tambah ke Daftar Pembayaran >' onclick='AddToPaymentList()'>";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='left' colspan='2'><br><strong><u>Riwayat Pembayaran</u></strong></td>\r\n";
    echo "</tr>\r\n";
    
    $sql = "SELECT SUM(p.jumlah) 
	          FROM penerimaaniuran p, jurnal j 
			 WHERE j.replid = p.idjurnal
               AND j.idtahunbuku = '$idtahunbuku'
               AND p.idpenerimaan = '$idpayment'
               AND p.nis = '".$noid."'";
    $total = FetchSingle($sql);           
    echo "<tr>\r\n";
    echo "<td align='right'>Total:</td>\r\n";
    echo "<td align='left'>\r\n&nbsp;&nbsp;";
    echo FormatRupiah($total);
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='left' colspan='2'><br>\r\n";
    echo "<div style='overflow: auto; height: 140px;'>\r\n";
    
    $sql = "SELECT p.replid AS id, j.nokas, date_format(p.tanggal, '%d-%b-%Y') as tanggal, p.keterangan, p.jumlah, p.petugas 
	          FROM penerimaaniuran p, jurnal j 
			 WHERE j.replid = p.idjurnal
               AND j.idtahunbuku = '$idtahunbuku'
               AND p.idpenerimaan = '$idpayment'
               AND p.nis = '$noid'
			 ORDER BY p.tanggal DESC, p.replid";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $no = 0;
        echo "<table border='1' cellpadding='5' cellspacing='1' style='border-color: #222; border-collapse: collapse; border-width: 1px;'>\r\n";
        echo "<tr>\r\n";
        echo "<td width='25' align='center' class='header' valign='top'>No</td>\r\n";
        echo "<td width='80' align='center' class='header' valign='top'>Tanggal</td>\r\n";
        echo "<td width='100' align='right' class='header' valign='top'>Jumlah</td>\r\n";
        echo "<td width='120' align='left' class='header' valign='top'>Keterangan</td>\r\n";
        echo "</tr>\r\n";
        while($row = mysqli_fetch_array($res))
        {
            $no++;
            
            echo "<tr>\r\n";
            echo "<td align='center' valign='top'>$no</td>\r\n";
            echo "<td align='center' valign='top'>". $row['tanggal'] . "</td>\r\n";
            echo "<td align='right' valign='top'>". FormatRupiah($row['jumlah']) . "</td>\r\n";
            echo "<td align='left' valign='top'>". $row['keterangan'] . "</td>\r\n";
            echo "</tr>\r\n";
        }
        echo "</table>\r\n";
    }
    else
    {
        echo "<em><center>Belum ada riwayat pembayaran</center></em>";
    }
    
    echo "</div>\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "</table>\r\n";
}

function InfoSkrCalonSiswa()
{
    global $noid, $idpayment, $idtahunbuku;
	
    $sql = "SELECT rekkas
              FROM jbsfina.datapenerimaan
             WHERE replid = '".$idpayment."'";
    $defrekkas = FetchSingle($sql);
    
	$sql = "SELECT replid
			  FROM jbsakad.calonsiswa
			 WHERE nopendaftaran = '$noid' ";
	$idcalon = FetchSingle($sql);		 
    
    echo "<table border='0' cellpadding='2' cellspacing='0'>\r\n";
    echo "<tr>\r\n";
    echo "<td align='left' colspan='2'><strong><u>Pembayaran</u></strong></td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right' width='80'><strong>Jumlah:</strong></td>\r\n";
    echo "<td align='left'>\r\n";
    echo "&nbsp;&nbsp;<input type='text' name='jumlah' id='jumlah' size='15' onblur=\"formatRupiah('jumlah');\" onfocus=\"unformatRupiah('jumlah')\">\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right'><strong>Rek. Kas:</strong></td>\r\n";
    echo "<td align='left'>&nbsp;\r\n";
    ShowSelectRekKas($defrekkas);
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right'>Keterangan:</td>\r\n";
    echo "<td align='left'>\r\n";
    echo "&nbsp;&nbsp;<input type='text' name='keterangan' id='keterangan' size='35' $ro $rostyle value='$keterangan'>\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='left' colspan='2'>\r\n";
    echo "<input type='button' class='but' style='height: 30px' value='Tambah ke Daftar Pembayaran >' onclick='AddToPaymentList()'>";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='left' colspan='2'><br><strong><u>Riwayat Pembayaran</u></strong></td>\r\n";
    echo "</tr>\r\n";
    
    $sql = "SELECT SUM(p.jumlah) 
	          FROM penerimaaniurancalon p, jurnal j 
			 WHERE j.replid = p.idjurnal
               AND j.idtahunbuku = '$idtahunbuku'
               AND p.idpenerimaan = '$idpayment'
               AND p.idcalon = '".$idcalon."'";
    $total = FetchSingle($sql);           
    echo "<tr>\r\n";
    echo "<td align='right'>Total:</td>\r\n";
    echo "<td align='left'>\r\n&nbsp;&nbsp;";
    echo FormatRupiah($total);
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='left' colspan='2'><br>\r\n";
    echo "<div style='overflow: auto; height: 140px;'>\r\n";
    
    $sql = "SELECT p.replid AS id, j.nokas, date_format(p.tanggal, '%d-%b-%Y') as tanggal, p.keterangan, p.jumlah, p.petugas 
	          FROM penerimaaniurancalon p, jurnal j 
			 WHERE j.replid = p.idjurnal
               AND j.idtahunbuku = '$idtahunbuku'
               AND p.idpenerimaan = '$idpayment'
               AND p.idcalon = '$idcalon'
			 ORDER BY p.tanggal DESC, p.replid";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $no = 0;
        echo "<table border='1' cellpadding='5' cellspacing='1' style='border-color: #222; border-collapse: collapse; border-width: 1px;'>\r\n";
        echo "<tr>\r\n";
        echo "<td width='25' align='center' class='header' valign='top'>No</td>\r\n";
        echo "<td width='80' align='center' class='header' valign='top'>Tanggal</td>\r\n";
        echo "<td width='100' align='right' class='header' valign='top'>Jumlah</td>\r\n";
        echo "<td width='120' align='left' class='header' valign='top'>Keterangan</td>\r\n";
        echo "</tr>\r\n";
        while($row = mysqli_fetch_array($res))
        {
            $no++;
            
            echo "<tr>\r\n";
            echo "<td align='center' valign='top'>$no</td>\r\n";
            echo "<td align='center' valign='top'>". $row['tanggal'] . "</td>\r\n";
            echo "<td align='right' valign='top'>". FormatRupiah($row['jumlah']) . "</td>\r\n";
            echo "<td align='left' valign='top'>". $row['keterangan'] . "</td>\r\n";
            echo "</tr>\r\n";
        }
        echo "</table>\r\n";
    }
    else
    {
        echo "<em><center>Belum ada riwayat pembayaran</center></em>";
    }
    
    echo "</div>\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "</table>\r\n";
}

function InfoWjbSiswa()
{
    global $noid, $idpayment, $idtahunbuku;
    
    $sql = "SELECT rekkas
              FROM jbsfina.datapenerimaan
             WHERE replid = '".$idpayment."'";
    $defrekkas = FetchSingle($sql);        
    
    $sql = "SELECT b.replid AS id, b.besar, b.keterangan, b.lunas, b.info1 AS idjurnal, cicilan
              FROM besarjtt b
             WHERE b.nis = '$noid'
               AND b.idpenerimaan = '$idpayment'
               AND b.info2 = '".$idtahunbuku."'";
    //echo "$sql<br>";           
    $result = QueryDb($sql);
    $newdata = (mysqli_num_rows($result) == 0);
    
    $idbesarjtt = 0;
    $lunas = 0;
    $tagihan = 0;
    $bcicilan = 0;
    $keterangan = "";
    $idjurnal = 0;
    $sisa = 0;
    $nbayar = 0;
    
    if (!$newdata)
    {
        $row = mysqli_fetch_array($result);
	
        $idbesarjtt = $row['id'];
        $lunas = $row['lunas'];
        $tagihan = $row['besar'];
        $bcicilan = $row['cicilan'];
        $keterangan = $row['keterangan'];
        $idjurnal = $row['idjurnal'];
        
        $sql = "SELECT SUM(jumlah) AS jumlah, SUM(info1) AS diskon
			  	  FROM penerimaanjtt
				 WHERE idbesarjtt = '".$idbesarjtt."'";
        $result = QueryDb($sql);
        $row = mysqli_fetch_row($result);
        $jbayar = $row[0];
        $jdiskon = $row[1];
        $sisa = $tagihan - $jbayar - $jdiskon;
        
        $sql = "SELECT COUNT(replid)
			  	  FROM penerimaanjtt
				 WHERE idbesarjtt = '".$idbesarjtt."'";
        $nbayar = FetchSingle($sql);
    }
    
    if ($lunas == 2)
        $statuslunas = "<font color='brown'><strong>GRATIS</strong></font>";
    else if ($lunas == 1)
        $statuslunas = "<font color='blue'><strong>LUNAS</strong></font>";
    else
        $statuslunas = "<font color='red'><strong>BELUM LUNAS</strong></font>";
    
    if ($idbesarjtt == 0)
    {
        $infocicil = "ke-1";
        $ncicil = 1;
        $tagihan = "";
        $bcicilan = "";
        $keterangan = "";
        $sisa = "";
        $ro = "";
        $rostyle = "";
    }
    else
    {
        $infocicil = "ke-" . ($nbayar + 1);
        $ncicil = $nbayar + 1;
        $tagihan = FormatRupiah($tagihan);
        $bcicilan = FormatRupiah($bcicilan);
        $sisa = FormatRupiah($sisa);
        $ro = "readonly";
        $rostyle = "style='background-color: #CCCC99;'";
    }
        
    echo "<table border='0' cellpadding='1' cellspacing='0'>\r\n";
    echo "<tr>\r\n";
    echo "<td align='left' colspan='2'>\r\n";
    echo "<strong><u>Informasi Tagihan</u></strong>\r\n";
    echo "<input type='hidden' id='idbesarjtt' value='$idbesarjtt'>\r\n";    
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right' width='80'><strong>Status:</strong></td>\r\n";
    echo "<td align='left'>&nbsp;&nbsp;$statuslunas</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right' width='80'><strong>Total<br>Tagihan:</strong></td>\r\n";
    echo "<td align='left'>\r\n";
    echo "&nbsp;&nbsp;<input type='text' name='tagihan' id='tagihan' size='15' $ro $rostyle value='$tagihan' onblur=\"formatRupiah('tagihan');\" onfocus=\"unformatRupiah('tagihan')\">\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right'><strong>Besar<br>Cicilan:</strong></td>\r\n";
    echo "<td align='left'>\r\n";
    echo "&nbsp;&nbsp;<input type='text' name='bcicilan' id='bcicilan' size='15' $ro $rostyle value='$bcicilan' onblur=\"formatRupiah('bcicilan');\" onfocus=\"unformatRupiah('bcicilan')\">\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right'>Keterangan:</td>\r\n";
    echo "<td align='left'>\r\n";
    echo "&nbsp;&nbsp;<input type='text' name='ktagihan' id='ktagihan' size='35' $ro $rostyle value='$keterangan'>\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
        
    if ($idbesarjtt > 0)
    {
        echo "<tr>\r\n";
        echo "<td align='right'>Sisa:</td>\r\n";
        echo "<td align='left'>\r\n";
        echo "&nbsp;&nbsp;<input type='text' name='sisa' id='sisa' readonly style='background-color: #CCCC99' size='15' value='$sisa'>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
    }
	
	
    if ($lunas == 0)
    {
        echo "<tr>\r\n";
        echo "<td align='left' colspan='2'><br><strong><u>$infocicil</u></strong></td>\r\n";
        echo "<input type='hidden' id='ncicil' name='ncicil' value='$ncicil'>\r\n";
        echo "</tr>\r\n";
        echo "<tr>\r\n";
        echo "<td align='right'><strong>Cicilan:</strong></td>\r\n";
        echo "<td align='left'>\r\n";
        echo "&nbsp;&nbsp;<input type='text' name='jcicilan' id='jcicilan' size='15' onblur=\"CalculatePay(); formatRupiah('jcicilan');\" onfocus=\"unformatRupiah('jcicilan')\">\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
        echo "<tr>\r\n";
        echo "<td align='right'>Diskon:</td>\r\n";
        echo "<td align='left'>\r\n";
        echo "&nbsp;&nbsp;<input type='text' name='jdiskon' id='jdiskon' size='15' onblur=\"CalculatePay(); formatRupiah('jdiskon');\" onfocus=\"unformatRupiah('jdiskon')\">\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
        echo "<tr>\r\n";
        echo "<td align='right'>Bayar:</td>\r\n";
        echo "<td align='left'>\r\n";
        echo "&nbsp;&nbsp;<input type='text' name='jbayar' id='jbayar' readonly style='background-color: #CCCC99' size='15'>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
        echo "<tr>\r\n";
        echo "<td align='right'><strong>Rek. Kas:</strong></td>\r\n";
        echo "<td align='left'>&nbsp;\r\n";
        ShowSelectRekKas($defrekkas);
        echo "</td>\r\n";
        echo "</tr>\r\n";
        echo "<tr>\r\n";
        echo "<td align='right'>Keterangan:</td>\r\n";
        echo "<td align='left'>\r\n";
        echo "&nbsp;&nbsp;<input type='text' name='kcicilan' id='kcicilan' size='35'>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
		
		echo "<tr>\r\n";
		echo "<td align='left' colspan='2'>\r\n";
		echo "<input type='button' class='but' style='height: 30px' value='Tambah ke Daftar Pembayaran >' onclick='AddToPaymentList()'>";
		echo "</td>\r\n";
        echo "</tr>\r\n";
    }
        
    echo "</table>\r\n";
    /*
    echo "Lunas = $lunas<br>";
    echo "Besar = $besar<br>";
    echo "Sisa = $sisa<br>";
    echo "IdBesarJtt = $idbesarjtt<br>";
    */
}

function InfoWjbCalonSiswa()
{
    global $noid, $idpayment, $idtahunbuku;
    
    $sql = "SELECT rekkas
              FROM jbsfina.datapenerimaan
             WHERE replid = '".$idpayment."'";
    $defrekkas = FetchSingle($sql); 
	
	$sql = "SELECT replid
			  FROM jbsakad.calonsiswa
			 WHERE nopendaftaran = '$noid' ";
	$idcalon = FetchSingle($sql);		
    
    $sql = "SELECT b.replid AS id, b.besar, b.keterangan, b.lunas, b.info1 AS idjurnal, cicilan
              FROM besarjttcalon b
             WHERE b.idcalon = '$idcalon'
               AND b.idpenerimaan = '$idpayment'
               AND b.info2 = '".$idtahunbuku."'";
    $result = QueryDb($sql);
    $newdata = (mysqli_num_rows($result) == 0);
    
    $idbesarjtt = 0;
    $lunas = 0;
    $tagihan = 0;
    $bcicilan = 0;
    $keterangan = "";
    $idjurnal = 0;
    $sisa = 0;
    $nbayar = 0;
    
    if (!$newdata)
    {
        $row = mysqli_fetch_array($result);
	
        $idbesarjtt = $row['id'];
        $lunas = $row['lunas'];
        $tagihan = $row['besar'];
        $bcicilan = $row['cicilan'];
        $keterangan = $row['keterangan'];
        $idjurnal = $row['idjurnal'];
        
        $sql = "SELECT SUM(jumlah) AS jumlah, SUM(info1) AS diskon
			  	  FROM penerimaanjttcalon
				 WHERE idbesarjttcalon = '".$idbesarjtt."'";
        $result = QueryDb($sql);
        $row = mysqli_fetch_row($result);
        $jbayar = $row[0];
        $jdiskon = $row[1];
        $sisa = $tagihan - $jbayar - $jdiskon;
        
        $sql = "SELECT COUNT(replid)
			  	  FROM penerimaanjttcalon
				 WHERE idbesarjttcalon = '".$idbesarjtt."'";
        $nbayar = FetchSingle($sql);         
    }
    
    if ($lunas == 2)
        $statuslunas = "<font color='brown'><strong>GRATIS</strong></font>";
    else if ($lunas == 1)
        $statuslunas = "<font color='blue'><strong>LUNAS</strong></font>";
    else
        $statuslunas = "<font color='red'><strong>BELUM LUNAS</strong></font>";
    
    if ($idbesarjtt == 0)
    {
        $infocicil = "Cicilan ke-1";
        $ncicil = 1;
        $tagihan = "";
        $bcicilan = "";
        $keterangan = "";
        $sisa = "";
        $ro = "";
        $rostyle = "";
    }
    else
    {
        $infocicil = "Cicilan ke-" . ($nbayar + 1);
        $ncicil = $nbayar + 1;
        $tagihan = FormatRupiah($tagihan);
        $bcicilan = FormatRupiah($bcicilan);
        $sisa = FormatRupiah($sisa);
        $ro = "readonly";
        $rostyle = "style='background-color: #CCCC99;'";
    }
        
    echo "<table border='0' cellpadding='1' cellspacing='0'>\r\n";
    echo "<tr>\r\n";
    echo "<td align='left' colspan='2'>\r\n";
    echo "<strong><u>Informasi Tagihan</u></strong>\r\n";
    echo "<input type='hidden' id='idbesarjtt' name='idbesarjtt' value='$idbesarjtt'>";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right' width='80'><strong>Status:</strong></td>\r\n";
    echo "<td align='left'>&nbsp;&nbsp;$statuslunas</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right' width='80'><strong>Total<br>Tagihan:</strong></td>\r\n";
    echo "<td align='left'>\r\n";
    echo "&nbsp;&nbsp;<input type='text' name='tagihan' id='tagihan' size='15' $ro $rostyle value='$tagihan' onblur=\"formatRupiah('tagihan');\" onfocus=\"unformatRupiah('tagihan')\">\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right'><strong>Besar<br>Cicilan:</strong></td>\r\n";
    echo "<td align='left'>\r\n";
    echo "&nbsp;&nbsp;<input type='text' name='bcicilan' id='bcicilan' size='15' $ro $rostyle value='$bcicilan' onblur=\"formatRupiah('bcicilan');\" onfocus=\"unformatRupiah('bcicilan')\">\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
    echo "<tr>\r\n";
    echo "<td align='right'>Keterangan:</td>\r\n";
    echo "<td align='left'>\r\n";
    echo "&nbsp;&nbsp;<input type='text' name='ktagihan' id='ktagihan' size='35' $ro $rostyle value='$keterangan'>\r\n";
    echo "</td>\r\n";
    echo "</tr>\r\n";
        
    if ($idbesarjtt > 0)
    {
        echo "<tr>\r\n";
        echo "<td align='right'>Sisa:</td>\r\n";
        echo "<td align='left'>\r\n";
        echo "&nbsp;&nbsp;<input type='text' name='sisa' id='sisa' readonly style='background-color: #CCCC99' size='15' value='$sisa'>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
    }
	
	
    if ($lunas == 0)
    {
        echo "<tr>\r\n";
        echo "<td align='left' colspan='2'><br><strong><u>$infocicil</u></strong></td>\r\n";
        echo "<input type='hidden' id='ncicil' name='ncicil' value='$ncicil'>\r\n";
        echo "</tr>\r\n";
        echo "<tr>\r\n";
        echo "<td align='right'><strong>Cicilan:</strong></td>\r\n";
        echo "<td align='left'>\r\n";
        echo "&nbsp;&nbsp;<input type='text' name='jcicilan' id='jcicilan' size='15' onblur=\"CalculatePay(); formatRupiah('jcicilan');\" onfocus=\"unformatRupiah('jcicilan')\">\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
        echo "<tr>\r\n";
        echo "<td align='right'>Diskon:</td>\r\n";
        echo "<td align='left'>\r\n";
        echo "&nbsp;&nbsp;<input type='text' name='jdiskon' id='jdiskon' size='15' onblur=\"CalculatePay(); formatRupiah('jdiskon');\" onfocus=\"unformatRupiah('jdiskon')\">\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
        echo "<tr>\r\n";
        echo "<td align='right'>Bayar:</td>\r\n";
        echo "<td align='left'>\r\n";
        echo "&nbsp;&nbsp;<input type='text' name='jbayar' id='jbayar' readonly style='background-color: #CCCC99' size='15'>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
        echo "<tr>\r\n";
        echo "<td align='right'><strong>Rek. Kas:</strong></td>\r\n";
        echo "<td align='left'>&nbsp;\r\n";
        ShowSelectRekKas($defrekkas);
        echo "</td>\r\n";
        echo "</tr>\r\n";
        echo "<tr>\r\n";
        echo "<td align='right'>Keterangan:</td>\r\n";
        echo "<td align='left'>\r\n";
        echo "&nbsp;&nbsp;<input type='text' name='kcicilan' id='kcicilan' size='35'>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
		
		echo "<tr>\r\n";
		echo "<td align='left' colspan='2'>\r\n";
		echo "<input type='button' class='but' style='height: 30px' value='Tambah ke Daftar Pembayaran >' onclick='AddToPaymentList()'>";
		echo "</td>\r\n";
        echo "</tr>\r\n";
    }
        
    echo "</table>\r\n";
    /*
    echo "Lunas = $lunas<br>";
    echo "Besar = $besar<br>";
    echo "Sisa = $sisa<br>";
    echo "IdBesarJtt = $idbesarjtt<br>";
    */
}
?>