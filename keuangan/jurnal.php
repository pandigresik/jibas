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
function SimpanJurnal($idtahunbuku, $tanggal, $transaksi, $nokas, $keterangan, $petugas, $sumber, &$idjurnal) 
{
	//Simpan ke jurnal
	$success = 0;
	
	$sql = "INSERT INTO jurnal SET idtahunbuku=$idtahunbuku,tanggal='$tanggal',transaksi='$transaksi',nokas='$nokas',keterangan='$keterangan',petugas='$petugas', sumber='$sumber'";
	//echo  "$sql<br>";
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
		$sql = "INSERT INTO jurnaldetail SET idjurnal=$idjurnal,koderek='$koderek',debet='$jumlah'";
	else
		$sql = "INSERT INTO jurnaldetail SET idjurnal=$idjurnal,koderek='$koderek',kredit='$jumlah'";
	//echo  "$sql<br>";		
	QueryDbTrans($sql, $success);
	
	return $success;
}
?>