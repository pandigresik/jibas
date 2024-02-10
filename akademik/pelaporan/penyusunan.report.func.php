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
function GetFileName()
{
    $tipe = $_REQUEST['tipe'];
    if ($tipe == 1)
    {
        // Berdasarkan NIS
        $departemen = $_REQUEST['departemen'];
        $tingkat = $_REQUEST['tingkat'];
        $kelas = $_REQUEST['kelas'];
        
        $name = "D$departemen";
        if ($tingkat == 0)
        {
            $name .= "_TALL";
        }
        else
        {
            $sql = "SELECT tingkat
                      FROM jbsakad.tingkat
                     WHERE replid = '".$tingkat."'";
            $res = QueryDb($sql);
            $row = mysqli_fetch_row($res);
            
            $name .= "_T" . FormatName($row[0]);
        }
        
        if ($kelas == 0)
        {
            $name .= "_KALL";
        }
        else
        {
            $sql = "SELECT kelas
                      FROM jbsakad.kelas
                     WHERE replid = '".$kelas."'";
            $res = QueryDb($sql);
            $row = mysqli_fetch_row($res);
            
            $name .= "_K" . FormatName($row[0]);
        }
    }
    else
    {
        $name = "NISLIST";
    }
    
    return "SURAT_" . date('Ymd') . "_" . date('Hi') . "_" . $name . ".doc";
}

function FormatName($name)
{
    $name = str_replace(" ", "_", (string) $name);
    $name = str_replace("'", "", $name);
    
    return $name;
}

function GetNisList(&$ndata)
{
    $departemen = $_REQUEST['departemen'];
    
    $tipe = $_REQUEST['tipe'];
    if ($tipe == 1)
    {
        // Berdasarkan NIS
        $tingkat = $_REQUEST['tingkat'];
        $kelas = $_REQUEST['kelas'];
        
        if ($tingkat == 0)
        {
            // Semua Tingkat
            $sql = "SELECT s.nis
                      FROM jbsakad.siswa s, jbsakad.angkatan a
                     WHERE s.idangkatan = a.replid
                       AND s.aktif = 1
                       AND s.alumni = 0
                       AND a.departemen = '$departemen'
                     ORDER BY s.nama";
        }
        elseif ($kelas == 0)
        {
            // Semua Kelas
            $sql = "SELECT s.nis
                      FROM jbsakad.siswa s, jbsakad.kelas k
                     WHERE s.idkelas = k.replid
                       AND s.aktif = 1
                       AND s.alumni = 0
                       AND k.idtingkat = '$tingkat'
                     ORDER BY s.nama";
        }
        else
        {
            // Di kelas tertentu
            $sql = "SELECT s.nis
                      FROM jbsakad.siswa s
                     WHERE s.aktif = 1
                       AND s.alumni = 0
                       AND s.idkelas = $kelas
                     ORDER BY s.nama";
        }
    }
    else
    {
        // Berdasarkan NIS LIST
        
        // Cek dulu apa NIS nya valid 
        $nisinfo = $_REQUEST['nisinfo'];
        $temp = explode(",", (string) $nisinfo);
        
        if (0 == count($temp))
        {
            $test = "'$nisinfo'";
        }
        else
        {
            $test = "";
            for($i = 0; $i < count($temp); $i++)
            {
                $nis = trim($temp[$i]);
                if (strlen($nis) == 0)
                    continue;
                
                if ($test != "")
                    $test .= ",";
                $test .= "'" . $nis . "'";    
            }    
        }
        
        $sql = "SELECT s.nis
                  FROM jbsakad.siswa s, jbsakad.angkatan a
                 WHERE s.idangkatan = a.replid
                   AND s.aktif = 1
                   AND s.alumni = 0
                   AND a.departemen = '$departemen'
                   AND s.nis IN ($test)
                 ORDER BY s.nama";
    }
    
    // NIS LIST
    $ndata = 0;
    $nisList = "";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        if ($nisList != "")
            $nisList .= ",";
        $nisList .= "'" . $row[0] . "'";
        $ndata += 1;
    }
    
    return $nisList;
}

function SetDocHeader($ndata, $title){
echo <<<HTML
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 9">
<meta name=Originator content="Microsoft Word 9">
<title>$title</title>
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>JIBAS</o:Author>
  <o:Template>Normal</o:Template>
  <o:LastAuthor>JIBAS</o:LastAuthor>
 </o:DocumentProperties>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:View>Print</w:View>
 </w:WordDocument>
</xml><![endif]-->
<style>
<!--
 /* Style Definitions */
p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-parent:"";
	margin:0in;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:12.0pt;
	font-family:"Verdana";
	mso-fareast-font-family:"Verdana";}
HTML;
$showLampiran = isset($_REQUEST['chLampiran']);
$showInfo = isset($_REQUEST['chNilai']) ||
            isset($_REQUEST['chKeuangan']) ||
            isset($_REQUEST['chPresensi']) ||
            isset($_REQUEST['chKegiatan']);
            
for($i = 1; $i <= $ndata; $i++)
{
echo <<<HTML
@page Section$i
	{size:595.3pt 841.9pt;
	margin:35.45pt 72.0pt 35.45pt 72.0pt;
	mso-header-margin:35.4pt;
	mso-footer-margin:35.4pt;
	mso-paper-source:0;}
div.Section$i
	{page:Section$i;}
HTML;    
if ($showLampiran) {
echo <<<HTML
@page SectionLampiran$i
	{size:595.3pt 841.9pt;
	margin:35.45pt 72.0pt 35.45pt 72.0pt;
	mso-header-margin:35.4pt;
	mso-footer-margin:35.4pt;
	mso-paper-source:0;}
div.SectionLampiran$i
	{page:SectionLampiran$i;}
HTML;
}
if ($showInfo) {
echo <<<HTML
@page SectionInfo$i
	{size:595.3pt 841.9pt;
	margin:35.45pt 72.0pt 35.45pt 72.0pt;
	mso-header-margin:35.4pt;
	mso-footer-margin:35.4pt;
	mso-paper-source:0;}
div.SectionInfo$i
	{page:SectionInfo$i;}
HTML;
}

} // end for
echo <<<HTML
</style>
</head>

<body lang=EN-US style='tab-interval:.5in'>
HTML;
}

function SetDocFooter()
{
echo '
</body>
</html>
';   
}

function SectionBreak()
{
echo <<<HTML
<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:
"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='page-break-before:always;
mso-break-type:section-break'>
</span>    
HTML;
}

function SetAddress()
{
    global $nama, $ortu, $alamat, $kodepos;
    
    $posisi = $_REQUEST['posisi'];
    if ($posisi == 1)
    {
        // CETAK ALAMAT KANAN
        echo "<table border='0' cellpadding='2' cellspacing='0' width='100%'>\r\n";
        echo "<tr>\r\n";
        echo "<td align='left' width='55%'>&nbsp;</td>\r\n";
        echo "<td align='left' valign='top' width='*'>\r\n";
        
        echo "<font style='font-family: Verdana; line-height: 18px; font-size: 14px;'>\r\n";
        echo "Kepada Yth:<br>\r\n";
        if (strlen(trim((string) $ortu)) != 0)
            echo "<strong>$ortu</strong><br>\r\n";
        echo "Orangtua <strong>" . strtoupper((string) $nama) . "</strong><br>\r\n";
        echo "$alamat<br>\r\n";
        echo "$kodepos<br>\r\n";
        echo "</font>\r\n";
        
        echo "</td>\r\n";
        echo "<td align='left' width='10%'>&nbsp;</td>\r\n";
        echo "</tr>\r\n";
        echo "</table>\r\n";
        echo "<br><br>\r\n";    
    }
    else
    {
        // CETAK ALAMAT KIRI
        echo "<table border='0' cellpadding='2' cellspacing='0' width='100%'>\r\n";
        echo "<tr>\r\n";
        echo "<td align='left' valign='top' width='50%'>\r\n";
        
        echo "<font style='font-family: Verdana; line-height: 18px; font-size: 14px;'>\r\n";
        echo "Kepada Yth:<br>\r\n";
        if (strlen(trim((string) $ortu)) != 0)
            echo "<strong>$ortu</strong><br>\r\n";
        echo "Orangtua <strong>" . strtoupper((string) $nama) . "</strong><br>\r\n";
        echo "$alamat<br>\r\n";
        echo "$kodepos<br>\r\n";
        echo "</font>\r\n";
        
        echo "</td>\r\n";
        echo "<td align='left' width='*'>&nbsp;</td>\r\n";
        echo "</tr>\r\n";
        echo "</table>\r\n";
        echo "<br><br>\r\n";            
    }
    
}

function SetHeader()
{
    global $departemen, $full_url;
    
	$sql = "SELECT replid, nama, alamat1, alamat2, telp1, telp2, telp3, telp4,
                   fax1, fax2, situs, email
              FROM jbsumum.identitas
             WHERE departemen = '".$departemen."'";

	$result = QueryDb($sql);
    $num = @mysqli_num_rows($result);
	$row = @mysqli_fetch_row($result);
	$replid  = $row[0];
	$nama	 = $row[1];
	$alamat1 = $row[2];
	$alamat2 = $row[3];
	$te1p1   = $row[4];
	$telp2   = $row[5];
	$telp3   = $row[6];
	$telp4   = $row[7];
	$fax1    = $row[8];
	$fax2    = $row[9];
	$situs   = $row[10];
	$email   = $row[11];
    
	echo "<table style='font-family: Arial; font-size: 12px; font-weight: bold;' border='0' cellpadding='0' cellspacing='0' width='100%'>\r\n";
	echo "<tr>\r\n";
	echo "  <td width='20%' align='center'>\r\n";
	echo "      <img src='" . $full_url . "library/gambar.php?replid=".$replid."&table=jbsumum.identitas&" . random_int(1, 5000) . "' />\r\n";
	echo "  </td>\r\n";
	echo "	<td valign='top' align='left'>\r\n";
	if ($num > 0)
    {	
        echo "<font size='5'><strong>$nama</strong></font><br />";
              
		if ($alamat2 <> "" && $alamat1 <> "")
            echo "Lokasi 1: ";
		
        if ($alamat1 != "") 
            echo $alamat1;
									
		if ($te1p1 != "" || $telp2 != "") 
            echo "<br>Telp. ";	
		
        if ($te1p1 != "" ) 
            echo $te1p1;	
						
        if ($te1p1 != "" && $telp2 != "") 
            echo "				, ";
		
        if ($telp2 != "" ) 
            echo $telp2;			
		
        if ($fax1 != "" ) 
            echo "&nbsp;&nbsp;Fax. ".$fax1."&nbsp;&nbsp;";
            
		if ($alamat2 <> "" && $alamat1 <> "")
        {
            echo "<br>";
            echo "Lokasi 2: ";
            echo $alamat2;
											
			if ($telp3 != "" || $telp4 != "") 
                echo "<br>Telp. ";
                
			if ($telp3 != "" ) 					
                echo $telp3;
                
			if ($telp3 != "" && $telp4 != "") 
                echo "				, ";
                
			if ($telp4 != "" ) 
                echo $telp4;
                
			if ($fax2 != "" ) 
                echo "&nbsp;&nbsp;Fax. ".$fax2;	
		}
        
		if ($situs != "" || $email != "")
            echo "<br>";
            
		if ($situs != "" ) 
            echo " Website: ".$situs."&nbsp;&nbsp;";
            
		if ($email != "" ) 
            echo " Email: ".$email;
						
        echo "</strong>";
	}
    
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan=\"2\"><hr width=\"100%\" /></td>";
	echo "</tr>";
	echo "</table>";
	echo "<br />";
}

function SetAutoFormat($text)
{
    global $nis, $nama, $kelas, $pin, $departemen;
       
    $ftext = str_replace("{NIS}", $nis, (string) $text);
    $ftext = str_replace("{NAMA}", $nama, $ftext);
    $ftext = str_replace("{KELAS}", $kelas, $ftext);
    $ftext = str_replace("{PIN}", $pin, $ftext);
    $ftext = str_replace("{DEPARTEMEN}", $departemen, $ftext);
    $ftext = str_replace("{TANGGAL}", date('d'), $ftext);
    $ftext = str_replace("{BULAN}", NamaBulan(date('n')), $ftext);
    $ftext = str_replace("{TAHUN}", date('Y'), $ftext);
    
    return $ftext;
}

function SetPengantar()
{
    global $pengantar;
    
    echo SetAutoFormat($pengantar);
}

function SetLampiran()
{
    global $lampiran;
    
    echo SetAutoFormat($lampiran);
}

function SetInfoNilai()
{
    global $nis;
    
    $yr30 = $_REQUEST['cbNilaiYr30'];
    $mn30 = $_REQUEST['cbNilaiMn30'];
    $dt30 = $_REQUEST['cbNilaiDt30'];
    $yr = $_REQUEST['cbNilaiYr'];
    $mn = $_REQUEST['cbNilaiMn'];
    $dt = $_REQUEST['cbNilaiDt'];
    
    $start = "$yr30-$mn30-$dt30";
    $end = "$yr-$mn-$dt";
    
    $sql = "SELECT nu.nilaiujian, nu.keterangan, DATE_FORMAT(u.tanggal, '%d-%b-%Y') AS xtanggal,
                   ju.info1 AS kodeujian, ju.jenisujian, p.nama AS pelajaran, p.kode AS kodepelajaran,
                   IF(u.idrpp IS NULL, 0, u.idrpp) AS idrpp, rk.nilaiRK
              FROM jbsakad.nilaiujian nu, jbsakad.ujian u,
                   jbsakad.jenisujian ju, jbsakad.pelajaran p, jbsakad.ratauk rk
             WHERE nu.idujian = u.replid
               AND u.idjenis = ju.replid
               AND ju.idpelajaran = p.replid
               AND rk.idujian = u.replid
               AND nu.nis = '$nis'
               AND u.tanggal BETWEEN '$start' AND '$end'
             ORDER BY tanggal";
    $res = QueryDb($sql);
    
    echo "<font style='font-family: Arial; font-size: 14px; font-weight: bold'>";
    echo "NILAI HARIAN";
    echo "</font><br>";
    echo "<font style='font-family: Verdana; font-size: 11px;'>";
    echo "Tanggal: " . $dt30 . " " . NamaBulan($mn30) . " " . $yr30;
    echo " s/d " . $dt . " " . NamaBulan($mn) . " " . $yr;
    echo "</font><br>";
    
    echo "<table border='1' style='border-width: 1px; border-collapse: collapse; font-family: Verdana; font-size: 11px;' width='100%'>\r\n";
    echo "<tr height='30' style='color: #FFF; background-color: #000;'>\r\n";
    echo "<td width='5%' align='center'>No</td>\r\n";
    echo "<td width='15%' align='center'>Tanggal</td>\r\n";
    echo "<td width='17%' align='center'>Pelajaran</td>\r\n";
    echo "<td width='17%' align='center'>Ujian</td>\r\n";
    echo "<td width='8%' align='center'>Nilai</td>\r\n";
    echo "<td width='8%' align='center'>Rata-rata Kelas</td>\r\n";
    echo "<td width='8%' align='center'>&nbsp;</td>\r\n";
    echo "<td width='*' align='center'>Keterangan</td>\r\n";
    echo "</tr>";
    if (mysqli_num_rows($res) == 0)
    {
        echo "<tr height='40'>\r\n";
        echo "<td colspan='8' align='center' valign='middle'>\r\n";
        echo "<em>belum ada data nilai</em>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
    }
    else
    {
        $no = 0;
        while($row = mysqli_fetch_array($res))
        {
            $no += 1;
            
            $dev = ($row['nilaiujian'] - $row['nilaiRK']) / $row['nilaiRK'];
            $dev = round(100 * $dev, 2);
            $colordev = $dev >= 0 ? "blue" : "red";
            $mark = $dev >= 0 ? "+" : "";
            $dev = $mark . $dev . "%";
            
            echo "<tr height='24'>\r\n";
            echo "<td align='center'>$no</td>\r\n";
            echo "<td align='center'>".$row['xtanggal']."</td>\r\n";
            echo "<td align='left'>".$row['pelajaran']."</td>\r\n";
            echo "<td align='left'>".$row['jenisujian']."</td>\r\n";
            echo "<td align='center'>".$row['nilaiujian']."</td>\r\n";
            echo "<td align='center'>".$row['nilaiRK']."</td>\r\n";
            echo "<td align='center'><font style='color: $colordev'>$dev</font></td>\r\n";
            echo "<td align='left'>".$row['keterangan']."</td>\r\n";
            echo "</tr>\r\n";
            
        }
    }
    echo "</table>\r\n";
    echo "<br><br>\r\n";
}

function SetInfoKeuangan()
{
    global $nis;
    
    $yr30 = $_REQUEST['cbKeuanganYr30'];
    $mn30 = $_REQUEST['cbKeuanganMn30'];
    $dt30 = $_REQUEST['cbKeuanganDt30'];
    $yr = $_REQUEST['cbKeuanganYr'];
    $mn = $_REQUEST['cbKeuanganMn'];
    $dt = $_REQUEST['cbKeuanganDt'];
    
    $start = "$yr30-$mn30-$dt30";
    $end = "$yr-$mn-$dt";
    
    $sql = "SELECT p.idbesarjtt, p.jumlah, DATE_FORMAT(p.tanggal, '%d-%b-%Y') AS xtanggal,
                   dp.nama, dp.idkategori, p.info1 AS diskon, p.keterangan, b.besar
              FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b, jbsfina.datapenerimaan dp
             WHERE p.idbesarjtt = b.replid
               AND b.idpenerimaan = dp.replid
               AND b.nis = '$nis'
               AND p.tanggal BETWEEN '$start' AND '$end'
             ORDER BY tanggal";
    $res = QueryDb($sql);
    
    echo "<font style='font-family: Arial; font-size: 14px; font-weight: bold'>";
    echo "KEUANGAN";
    echo "</font><br>";
    echo "<font style='font-family: Verdana; font-size: 11px;'>";
    echo "Tanggal: " . $dt30 . " " . NamaBulan($mn30) . " " . $yr30;
    echo " s/d " . $dt . " " . NamaBulan($mn) . " " . $yr;
    echo "</font><br>";
    
    echo "<table border='1' style='border-width: 1px; border-collapse: collapse; font-family: Verdana; font-size: 11px;' width='100%'>\r\n";
    echo "<tr height='30' style='color: #FFF; background-color: #000;'>\r\n";
    echo "<td width='5%' align='center'>No</td>\r\n";
    echo "<td width='15%' align='center'>Tanggal</td>\r\n";
    echo "<td width='25%' align='center'>Pembayaran</td>\r\n";
    echo "<td width='14%' align='center'>Jumlah<br>Pembayaran</td>\r\n";
    echo "<td width='14%' align='center'>Diskon</td>\r\n";
    echo "<td width='14%' align='center'>Sisa</td>\r\n";
    echo "</tr>\r\n";
    
    echo "<tr height='25'>\r\n";
    echo "<td style='background-color: #ddd' align='left' colspan='6' valign='middle'><strong>Pembayaran Iuran Wajib</strong></td>\r\n";
    echo "</tr>\r\n";
    
    if (mysqli_num_rows($res) == 0)
    {
        echo "<tr height='40'>\r\n";
        echo "<td colspan='6' align='center' valign='middle'>\r\n";
        echo "<em>belum ada data pembayaran iuran wajib</em>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
    }
    else
    {
        $no = 0;
        while($row = mysqli_fetch_array($res))
        {
            $no += 1;
            
            $keterangan = trim((string) $row['keterangan']);
            $rowspan = strlen($keterangan) == 0 ? "" : "rowspan='2'";
            
            $sql = "SELECT SUM(jumlah) + SUM(info1)
                      FROM jbsfina.penerimaanjtt
                     WHERE idbesarjtt = '".$row['idbesarjtt']."'";
            $res2 = QueryDb($sql);
            $row2 = mysqli_fetch_row($res2);
            $sisa = $row['besar'] - $row2[0];
            
            echo "<tr height='24'>\r\n";
            echo "<td align='center' valign='middle' $rowspan>$no</td>\r\n";
            echo "<td align='center'>".$row['xtanggal']."</td>\r\n";
            echo "<td align='left'>".$row['nama']."</td>\r\n";
            echo "<td align='right'>" . FormatRupiah($row['jumlah']) . "</td>\r\n";
            echo "<td align='right'>" . FormatRupiah($row['diskon']) . "</td>\r\n";
            echo "<td align='right'>" . FormatRupiah($sisa) . "</td>\r\n";
            echo "</tr>\r\n";
            
            if (strlen($keterangan) > 0)
            {
                echo "<tr height='24'>\r\n";
                echo "<td align='left' valign='top' colspan='5'>Keterangan: $keterangan</td>\r\n";
                echo "</tr>\r\n";
            }
            
        }
    }
    
    $sql = "SELECT p.jumlah, DATE_FORMAT(p.tanggal, '%d-%b-%Y') AS xtanggal,
                   dp.nama, dp.idkategori, p.keterangan
              FROM jbsfina.penerimaaniuran p, jbsfina.datapenerimaan dp
             WHERE p.idpenerimaan = dp.replid
               AND p.nis = '$nis'
               AND p.tanggal BETWEEN '$start' AND '$end'
             ORDER BY tanggal";
    $res = QueryDb($sql);
    
    echo "<tr height='25'>\r\n";
    echo "<td style='background-color: #ddd' align='left' colspan='6' valign='middle'><strong>Pembayaran Iuran Sukarela</strong></td>\r\n";
    echo "</tr>\r\n";
    
    if (mysqli_num_rows($res) == 0)
    {
        echo "<tr height='40'>\r\n";
        echo "<td colspan='6' align='center' valign='middle'>\r\n";
        echo "<em>belum ada data pembayaran iuran sukarela</em>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
    }
    else
    {
        $no = 0;
        while($row = mysqli_fetch_array($res))
        {
            $no += 1;
            
            $keterangan = trim((string) $row['keterangan']);
            $rowspan = strlen($keterangan) == 0 ? "" : "rowspan = '2'";
            
            echo "<tr height='24'>\r\n";
            echo "<td align='center' valign='middle' $rowspan>$no</td>\r\n";
            echo "<td align='center'>".$row['xtanggal']."</td>\r\n";
            echo "<td align='left'>".$row['nama']."</td>\r\n";
            echo "<td align='right'>" . FormatRupiah($row['jumlah']) . "</td>\r\n";
            echo "<td align='right'>-</td>\r\n";
            echo "<td align='right'>-</td>\r\n";
            echo "</tr>\r\n";
            
            if (strlen($keterangan) > 0)
            {
                echo "<tr height='24'>\r\n";
                echo "<td align='left' valign='top' colspan='5'>Keterangan: $keterangan</td>\r\n";
                echo "</tr>\r\n";
            }
        }
    }
    
    echo "</table>\r\n";
    echo "<br><br>\r\n";

    echo "<table border='1' style='border-width: 1px; border-collapse: collapse; font-family: Verdana; font-size: 11px;' width='100%'>\r\n";
    echo "<tr height='30' style='color: #FFF; background-color: #000;'>\r\n";
    echo "<td width='5%' align='center'>No</td>\r\n";
    echo "<td width='20%' align='center'>Tanggal</td>\r\n";
    echo "<td width='16%' align='center'>Debet</td>\r\n";
    echo "<td width='16%' align='center'>Kredit</td>\r\n";
    echo "<td width='20%' align='center'>Saldo</td>\r\n";
    echo "<td width='*' align='center'>Keterangan</td>\r\n";
    echo "</tr>\r\n";

    $start = "$start 00:00:00";
    $end = "$end 23:59:59";

    $sql =	"SELECT DISTINCT t.idtabungan, d.nama
               FROM jbsfina.tabungan t, jbsfina.datatabungan d
              WHERE t.idtabungan = d.replid
                AND t.tanggal BETWEEN '$start' AND '$end'
                AND t.nis = '$nis'
              ORDER BY nama";
    $res = QueryDb($sql);

    while($row = mysqli_fetch_array($res))
    {
        $idTab = $row['idtabungan'];
        $nmTab = $row['nama'];

        $totsaldo = 0;
        $sql = "SELECT SUM(debet), SUM(kredit)
                  FROM jbsfina.tabungan
                 WHERE idtabungan = '$idTab'
                   AND nis = '".$nis."'";
        $res2 = QueryDb($sql);
        if ($row2 = mysqli_fetch_row($res2))
        {
            $totsaldo = $row2[1] - $row2[0];
        }

        $lastsaldo = 0;
        $sql = "SELECT SUM(debet), SUM(kredit)
                  FROM jbsfina.tabungan
                 WHERE idtabungan = '$idTab'
                   AND nis = '$nis'
                   AND tanggal < '$start'";
        $res2 = QueryDb($sql);
        if ($row2 = mysqli_fetch_row($res2))
        {
            $lastsaldo = $row2[1] - $row2[0];
        }

        $rowsaldo = $lastsaldo;

        $no = 0;
        $first = true;
        $sql = "SELECT DATE_FORMAT(tanggal, '%d-%m-%Y %H:%i') AS tanggal, debet, kredit, keterangan
                  FROM jbsfina.tabungan
                 WHERE idtabungan = '$idTab'
                   AND nis = '$nis'
                   AND tanggal BETWEEN '$start' AND '$end'";
        $res2 = QueryDb($sql);
        while($row2 = mysqli_fetch_array($res2))
        {
            if ($first)
            {
                $first = false;

                echo "<tr height='25'>\r\n";
                echo "<td style='background-color: #ddd' align='left' colspan='6' valign='middle'><strong>$nmTab</strong></td>\r\n";
                echo "</tr>\r\n";
            }

            $debet = $row2['debet'];
            $kredit = $row2['kredit'];
            $rowsaldo = $debet > 0 ? $rowsaldo - $debet : $rowsaldo + $kredit;

            $no += 1;
            echo "<tr height='24'>\r\n";
            echo "<td align='center' valign='middle'>$no</td>\r\n";
            echo "<td align='center'>".$row2['tanggal']."</td>\r\n";
            echo "<td align='right'>" . FormatRupiah($debet) . "</td>\r\n";
            echo "<td align='right'>" . FormatRupiah($kredit) . "</td>\r\n";
            echo "<td align='right'>" . FormatRupiah($rowsaldo) . "</td>\r\n";
            echo "<td align='left'>".$row2['keterangan']."</td>\r\n";
            echo "</tr>\r\n";
        }
    }

    echo "</table>\r\n";
    echo "<br><br>\r\n";
}

function SetInfoPresensi()
{
    global $nis;
    
    $yr30 = $_REQUEST['cbPresensiYr30'];
    $mn30 = $_REQUEST['cbPresensiMn30'];
    $dt30 = $_REQUEST['cbPresensiDt30'];
    $yr = $_REQUEST['cbPresensiYr'];
    $mn = $_REQUEST['cbPresensiMn'];
    $dt = $_REQUEST['cbPresensiDt'];
    
    $start = "$yr30-$mn30-$dt30";
    $end = "$yr-$mn-$dt";
    
    $sql = "SELECT DATE_FORMAT(date_in, '%d-%b-%Y') AS xtanggal, time_in, time_out, info1, description
              FROM jbssat.frpresence
             WHERE nis = '$nis'
               AND date_in BETWEEN '$start' AND '$end'
             ORDER BY date_in";
    $res = QueryDb($sql);
    
    echo "<font style='font-family: Arial; font-size: 14px; font-weight: bold'>";
    echo "PRESENSI HARIAN";
    echo "</font><br>";
    echo "<font style='font-family: Verdana; font-size: 11px;'>";
    echo "Tanggal: " . $dt30 . " " . NamaBulan($mn30) . " " . $yr30;
    echo " s/d " . $dt . " " . NamaBulan($mn) . " " . $yr;
    echo "</font><br>";
    
    echo "<table border='1' style='border-width: 1px; border-collapse: collapse; font-family: Verdana; font-size: 11px;' width='100%'>\r\n";
    echo "<tr height='30' style='color: #FFF; background-color: #000;'>\r\n";
    echo "<td width='5%' align='center'>No</td>\r\n";
    echo "<td width='18%' align='center'>Tanggal</td>\r\n";
    echo "<td width='14%' align='center'>Jam Masuk</td>\r\n";
    echo "<td width='14%' align='center'>Jam Pulang</td>\r\n";
    echo "<td width='14%' align='center'>Keterlambatan</td>\r\n";
    echo "<td width='*' align='center'>Keterangan</td>\r\n";
    echo "</tr>\r\n";
    
    if (mysqli_num_rows($res) == 0)
    {
        echo "<tr height='40'>\r\n";
        echo "<td colspan='6' align='center' valign='middle'>\r\n";
        echo "<em>belum ada data presensi harian</em>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
    }
    else
    {
        $no = 0;
        while($row = mysqli_fetch_array($res))
        {
            $no += 1;
            $telat = 0 == (int)$row['info1'] ? "&nbsp" : $row['info1'] . " menit";
            
            echo "<tr height='24'>\r\n";
            echo "<td align='center'>$no</td>\r\n";
            echo "<td align='center'>".$row['xtanggal']."</td>\r\n";
            echo "<td align='center'>".$row['TIME_IN']."</td>\r\n";
            echo "<td align='center'>".$row['TIME_OUT']."</td>\r\n";
            echo "<td align='center'>$telat</td>\r\n";
            echo "<td align='left'>".$row['keterangan']."</td>\r\n";
            echo "</tr>\r\n";
        }
    }
    
    echo "</table>\r\n";
    echo "<br><br>\r\n";
    
}

function SetInfoKegiatan()
{
    global $nis;
    
    $yr30 = $_REQUEST['cbKegiatanYr30'];
    $mn30 = $_REQUEST['cbKegiatanMn30'];
    $dt30 = $_REQUEST['cbKegiatanDt30'];
    $yr = $_REQUEST['cbKegiatanYr'];
    $mn = $_REQUEST['cbKegiatanMn'];
    $dt = $_REQUEST['cbKegiatanDt'];
    
    $start = "$yr30-$mn30-$dt30";
    $end = "$yr-$mn-$dt";
    
    $sql = "SELECT DATE_FORMAT(p.date_in, '%d-%b-%Y') AS xtanggal, p.time_in, p.time_out, p.info1, p.description, k.kegiatan
              FROM jbssat.frpresensikegiatan p, jbssat.frkegiatan k
             WHERE p.idkegiatan = k.replid
               AND p.nis = '$nis'
               AND p.date_in BETWEEN '$start' AND '$end'
             ORDER BY p.date_in";
    $res = QueryDb($sql);
    
    echo "<font style='font-family: Arial; font-size: 14px; font-weight: bold'>";
    echo "PRESENSI KEGIATAN";
    echo "</font><br>";
    echo "<font style='font-family: Verdana; font-size: 11px;'>";
    echo "Tanggal: " . $dt30 . " " . NamaBulan($mn30) . " " . $yr30;
    echo " s/d " . $dt . " " . NamaBulan($mn) . " " . $yr;
    echo "</font><br>";
    
    echo "<table border='1' style='border-width: 1px; border-collapse: collapse; font-family: Verdana; font-size: 11px;' width='100%'>\r\n";
    echo "<tr height='30' style='color: #FFF; background-color: #000;'>\r\n";
    echo "<td width='5%' align='center'>No</td>\r\n";
    echo "<td width='15%' align='center'>Tanggal</td>\r\n";
    echo "<td width='25%' align='center'>Kegiatan</td>\r\n";
    echo "<td width='12%' align='center'>Jam Masuk</td>\r\n";
    echo "<td width='12%' align='center'>Jam Pulang</td>\r\n";
    echo "<td width='12%' align='center'>Keterlambatan</td>\r\n";
    echo "<td width='*' align='center'>Keterangan</td>\r\n";
    echo "</tr>\r\n";
    
    if (mysqli_num_rows($res) == 0)
    {
        echo "<tr height='40'>\r\n";
        echo "<td colspan='7' align='center' valign='middle'>\r\n";
        echo "<em>belum ada data presensi kegiatan</em>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
    }
    else
    {
        $no = 0;
        while($row = mysqli_fetch_array($res))
        {
            $no += 1;
            $telat = 0 == (int)$row['info1'] ? "&nbsp" : $row['info1'] . " menit";
            
            echo "<tr height='24'>\r\n";
            echo "<td align='center'>$no</td>\r\n";
            echo "<td align='center'>".$row['xtanggal']."</td>\r\n";
            echo "<td align='left'>".$row['kegiatan']."</td>\r\n";
            echo "<td align='center'>".$row['TIME_IN']."</td>\r\n";
            echo "<td align='center'>".$row['TIME_OUT']."</td>\r\n";
            echo "<td align='center'>$telat</td>\r\n";
            echo "<td align='left'>".$row['keterangan']."</td>\r\n";
            echo "</tr>\r\n";
        }
    }
    
    echo "</table>\r\n";
    echo "<br><br>\r\n";
    
}

function SetNilaiCbe()
{
    global $nis;

    $yr30 = $_REQUEST['cbCbeYr30'];
    $mn30 = $_REQUEST['cbCbeMn30'];
    $dt30 = $_REQUEST['cbCbeDt30'];
    $yr = $_REQUEST['cbCbeYr'];
    $mn = $_REQUEST['cbCbeMn'];
    $dt = $_REQUEST['cbCbeDt'];

    $status = 1; // 0 Umum, 1 Khusus
    $start = "$yr30-$mn30-$dt30 00:00:00";
    $end = "$yr-$mn-$dt 23:59:59";

    echo "<font style='font-family: Arial; font-size: 14px; font-weight: bold'>";
    echo "NILAI COMPUTER BASED EXAM";
    echo "</font><br>";
    echo "<font style='font-family: Verdana; font-size: 11px;'>";
    echo "Tanggal: " . $dt30 . " " . NamaBulan($mn30) . " " . $yr30;
    echo " s/d " . $dt . " " . NamaBulan($mn) . " " . $yr;
    echo "</font><br>";

    echo "<table border='1' style='border-width: 1px; border-collapse: collapse; font-family: Verdana; font-size: 11px;' width='100%'>\r\n";
    echo "<tr height='30' style='color: #FFF; background-color: #000;'>\r\n";
    echo "<td width='5%' align='center'>No</td>\r\n";
    echo "<td width='*' align='center'>Pelajaran / Ujian</td>\r\n";
    echo "<td width='25%' align='center'>Nilai</td>\r\n";
    echo "<td width='25%' align='center'>Status</td>\r\n";
    echo "</tr>\r\n";

    $sql = "SELECT DISTINCT IFNULL(us.idujianremed, us.idujian), DATE_FORMAT(us.tanggal, '%d-%m-%Y %h:%i:%s'), p.idpelajaran, pel.nama 
              FROM jbscbe.ujian u, jbscbe.ujianserta us, jbscbe.pengujian p, jbsakad.pelajaran pel
             WHERE u.id = us.idujian
               AND u.idpengujian = p.id
               AND p.idpelajaran = pel.replid
               AND us.nis = '$nis'
               AND us.tanggal BETWEEN '$start' AND '$end'
               AND p.status = $status
             ORDER BY us.tanggal DESC";

    $lsId = [];
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $lsId[] = [$row[0], $row[1], $row[2], $row[3]];
    }

    if (count($lsId) == 0)
    {
        echo "<tr height='24'>\r\n";
        echo "<td colspan='4' align='center' valign='middle'>\r\n";
        echo "<em>belum ada data nilai computer based exam</em>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
        echo "</table>";

        return;
    }

    $lsIdUjian = [];
    for($i = 0; $i < count($lsId); $i++)
    {
        $id = $lsId[$i][0];
        $tglUjian = $lsId[$i][1];
        $idPelajaran = $lsId[$i][2];
        $pelajaran = $lsId[$i][3];

        $sql = "SELECT u.id, IFNULL(u.idremedujian, 0), u.judul
                  FROM jbscbe.ujian u 
                 WHERE u.id = $id";
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $lsIdUjian[] = [$row[0], $row[1], $row[2], $tglUjian, $idPelajaran, $pelajaran];
        }
    }

    if (count($lsIdUjian) == 0)
    {
        echo "<tr height='24'>\r\n";
        echo "<td colspan='4' align='center' valign='middle'>\r\n";
        echo "<em>belum ada hasil ujian</em>\r\n";
        echo "</td>\r\n";
        echo "</tr>\r\n";
        echo "</table>";

        return;
    }

    $no = 0;
    for($i = 0; $i < count($lsIdUjian); $i++)
    {
        $idUjian = $lsIdUjian[$i][0];
        $idRemedUjian = $lsIdUjian[$i][1];
        $judul = $lsIdUjian[$i][2];
        $tglUjian = $lsIdUjian[$i][3];
        $idPelajaran = $lsIdUjian[$i][4];
        $pelajaran = $lsIdUjian[$i][5];

        $idUjianInUjianSerta = $idRemedUjian != 0 ? $idRemedUjian : $idUjian;

        $haveRemed = false;
        $sql = "SELECT COUNT(id) 
                  FROM jbscbe.ujianserta
                 WHERE idujian = $idUjianInUjianSerta
                   AND nis = '$nis'
                   AND idujianremed IS NOT NULL";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $haveRemed = $row[0] > 0;

        $sifatUjian = 0; // 0 Umum 1 Tertutup
        $sql = "SELECT p.status
                  FROM jbscbe.ujian u, jbscbe.pengujian p
                 WHERE u.idpengujian = p.id
                   AND u.id = $idUjian";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $sifatUjian = $row[0];

        $skalanilai = 10;
        $nilaikkm = 0;
        $viewresult = true;

        $sql = "SELECT skalanilai, kkm, viewresult
                  FROM jbscbe.ujian
                 WHERE id = $idUjianInUjianSerta";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $skalanilai = $row[0];
            $nilaikkm = $row[1];
            $viewresult = $row[2] == 1;
        }

        $cf = new ColorFactory(0, $skalanilai);

        $sql = "SELECT u.id, u.jbenar, u.jsalah, u.tbobot, u.tnilai, 
                       u.nilai, u.elapsed, u.idujian, 
                       IFNULL(u.idujianremed, 0) AS idujianremed,
                       DATE_FORMAT(u.tanggal, '%d-%m-%Y %H:%i') AS tanggal, u.status,
                       DATE_FORMAT(u.tanggal, '%Y%m%d%H%i') AS tanggalsort     
                  FROM jbscbe.ujianserta u
                 WHERE u.idujian = $idUjianInUjianSerta
                   AND u.nis = '$nis' 
                   AND u.status IN (1,2)";

        if ($sifatUjian == 1)
        {
            // Untuk ujian tertutup
            if ($idRemedUjian != 0)
            {
                // Bila yang dipilih ujian remedial, maka nilai terakhir adalah hasil remedial
                $sql .= " AND u.idujianremed = $idUjian";
            }
            else
            {
                // Bila yang dipilih ujian awal, maka nilai ditampilkan adalah
                //  bila ada remedial, maka nilai awal
                $sql .= " AND u.lastdata = " . ($haveRemed ? 0 : 1);
            }
        }

        $sql .= " ORDER BY u.tanggal DESC";

        $res = QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
            continue;

        while($row = mysqli_fetch_array($res))
        {
            $no += 1;

            $idUjianRemed = $row["idujianremed"];
            $isRemed = $idUjianRemed == 0 ? 0 : 1;
            $statusUjian = getStatusUjian($row["status"], $isRemed);
            $statusNilai = getStatusNilai($row["nilai"], $nilaikkm, $row["status"]);

            if ($viewresult)
            {
                $nilaiInfo = $row["status"] != 2 ? "--<br>" : $row["nilai"] . "<br>";
                $nilaiInfo .= "Benar: " . $row['jbenar'] . "<br>";
                $nilaiInfo .= "Salah: " . $row['jbsalah'] . "<br>";

                $nilaiColor = $cf->GetColorCode($row['nilai'] );
            }
            else
            {
                $nilaiInfo = "<font style='font-style: italic; color: #333;'>(hasil tidak ditampilkan)</font>";
                $nilaiColor = "#ededed";
            }

            $ujian  = "<span style='font-size: 11px; font-weight: bold'>" . $judul . "</span><br>";
            $ujian .= "Pelajaran: " . $pelajaran . "<br>";
            $ujian .= "Tanggal: " . $row['tanggal'] . "<br>";
            $ujian .= "Waktu: " . $row['elapsed'] . " menit";

            $status  = "Nilai KKM: " . $nilaikkm . "<br>";
            if ($viewresult)
            {
                $status .= "Hasil: " . $statusNilai . "<br>";
                $status .= "Status: " . $statusUjian;
            }

            echo "<tr style='height: 60px'>";
            echo "<td align='center' valign='top' style='background-color: #ededed'>$no</td>";
            echo "<td align='left' valign='top' style='background-color: #fff'>$ujian</td>";
            echo "<td align='left' valign='top' style='color: #fff; background-color: $nilaiColor'>$nilaiInfo</td>";
            echo "<td align='left' valign='top' style='background-color: #fff'>$status</td>";
            echo "</tr>";
        }
    }

    echo "</table>\r\n";
    echo "<br><br>\r\n";
}

function getStatusNilai($nilai, $kkm, $statusUjian)
{
    if ($statusUjian != 2)
        return "--";

    if ($nilai >= $kkm)
        return "Lulus";

    return "Kurang";
}

function getStatusUjian($status, $isRemed)
{
    $statusUjian = $isRemed == 1 ? "Remedial, " : "";

    if ($status == -1)
        $statusUjian .= "Pending";

    if ($status == 0)
        $statusUjian .= "Sedang Berlangsung";

    if ($status == 1)
        $statusUjian .= "Tunggu Verifikasi Esai";

    if ($status == 2)
        $statusUjian .= "Selesai";

    return $statusUjian;

}
?>

