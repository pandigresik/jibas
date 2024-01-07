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
function SimpanJurnal($idtahunbuku, $tanggal, $transaksi, $nokas, $keterangan, $idpetugas, $petugas, $sumber, &$idjurnal) 
{
	//Simpan ke jurnal
	$success = 0;
	
	$idpetugas_value = $idpetugas == "landlord" ? "NULL" : "'$idpetugas'"; 
	
	$sql = "INSERT INTO jurnal
			   SET idtahunbuku=$idtahunbuku, tanggal='$tanggal', transaksi='$transaksi',
				   nokas='$nokas', keterangan='$keterangan',
				   idpetugas=$idpetugas_value, petugas='$petugas', sumber='$sumber'";
	$result = QueryDbTrans($sql, $success);
	
	$idjurnal = 0;
	if ($success) {
		$sql = "SELECT last_insert_id()";
		$result = QueryDbTrans($sql, $success);
		if ($success) {
			$row = @mysqli_fetch_row($result);	
			$idjurnal = $row[0];
		}	
	}

	return $success;
}

function SimpanDetailJurnal($idjurnal, $align, $koderek, $jumlah) 
{
	$success = 0;
	
	if ($align == "D")
		$sql = "INSERT INTO jurnaldetail SET idjurnal=$idjurnal,koderek='$koderek',debet=$jumlah";
	else
		$sql = "INSERT INTO jurnaldetail SET idjurnal=$idjurnal,koderek='$koderek',kredit=$jumlah";
	QueryDbTrans($sql, $success);
	
	return $success;
}

// $kateakun bisa HARTA, PENDAPATAN, DISKON, PIUTANG
function AmbilKodeRekJurnal($idjurnal, $kateakun, $idpenerimaan)
{
	$kategori = ($kateakun == "DISKON") ? "PENDAPATAN" : $kateakun;
	
	if ($kateakun == "PENDAPATAN")
	{
		$sql = "SELECT koderek
				  FROM jbsfina.jurnaldetail jd, jbsfina.rekakun rk
				 WHERE jd.koderek = rk.kode
				   AND jd.idjurnal = '$idjurnal'
				   AND rk.kategori = '$kategori'
				   AND jd.debet = 0
				   AND jd.kredit > 0";
	}
	elseif ($kateakun == "DISKON")
	{
		$sql = "SELECT koderek
				  FROM jbsfina.jurnaldetail jd, jbsfina.rekakun rk
				 WHERE jd.koderek = rk.kode
				   AND jd.idjurnal = '$idjurnal'
				   AND rk.kategori = '$kategori'
				   AND jd.debet > 0
				   AND jd.kredit = 0";
	}
	else
	{
		$sql = "SELECT koderek
				  FROM jbsfina.jurnaldetail jd, jbsfina.rekakun rk
				 WHERE jd.koderek = rk.kode
				   AND jd.idjurnal = '$idjurnal'
				   AND rk.kategori = '".$kategori."'";
	}
	
	$res = QueryDb($sql);
	if (mysqli_num_rows($res) == 0)
	{
		if ($kateakun == "HARTA")
			$colname = "rekkas";
		elseif ($kateakun == "PIUTANG")
			$colname = "rekpiutang";
		elseif ($kateakun == "PENDAPATAN")
			$colname = "rekpendapatan";
		elseif ($kateakun == "DISKON")
			$colname = "info1";
			
		$sql = "SELECT $colname
				  FROM jbsfina.datapenerimaan
				 WHERE replid = '".$idpenerimaan."'";
		//echo "$sql";		 
		$res = QueryDb($sql);
		if (mysqli_num_rows($res) > 0)
		{
			$row = mysqli_fetch_row($res);
			return $row[0];
		}
		else
		{
			return "";
		}
	}
	else
	{
		$row = mysqli_fetch_row($res);
		return $row[0];
	}
}
?>