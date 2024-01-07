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
<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CONTENT -->
<tr>
	<td>

<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width?>" align="left" bordercolor="#000000">
<tr height="30" align="center" class="header">
	<td width="30">No</td>
	<td width="80" background="../style/formbg2.gif"
		style="cursor:pointer;" onClick="change_urut('nis','<?=$urutan?>')">N I S  <?=change_urut('nis',$urut,$urutan)?></td>
	<td width="140" background="../style/formbg2.gif"
		style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama  <?=change_urut('nama',$urut,$urutan)?></td>
	<td width="75" background="../style/formbg2.gif"
		style="cursor:pointer;" onClick="change_urut('kelas','<?=$urutan?>')">Kelas <?=change_urut('kelas',$urut,$urutan)?></td>
	<td width="150" background="../style/formbg2.gif">Saldo Tabungan</td>
	<td width="150" background="../style/formbg2.gif">Total Setoran</td>
	<td width="150" background="../style/formbg2.gif">Setoran Terakhir</td>
	<td width="150" background="../style/formbg2.gif">Total Tarikan</td>
	<td width="150" background="../style/formbg2.gif">Tarikan Terakhir</td>
</tr>
<?php
if ($idtingkat == -1)
{
	$sqlTotal = "SELECT COUNT(DISTINCT s.nis)
		           FROM jbsfina.tabungan t, jbsakad.siswa s, jbsakad.kelas k
			      WHERE s.nis = t.nis
			        AND s.idkelas = k.replid
			        AND t.idtabungan = $idtabungan 
			        AND s.idangkatan = $idangkatan";
			 
	$sql = "SELECT DISTINCT s.nis, s.nama, k.kelas
		      FROM jbsfina.tabungan t, jbsakad.siswa s, jbsakad.kelas k
			 WHERE s.nis = t.nis
			   AND s.idkelas = k.replid
			   AND t.idtabungan = $idtabungan 
			   AND s.idangkatan = $idangkatan
		     ORDER BY $urut $urutan";
             
    if ($pageLimit)         
        $sql .= " LIMIT " . (int)$page * (int)$varbaris . ", $varbaris"; 
}
else if ($idkelas == -1)
{
	$sqlTotal = "SELECT COUNT(DISTINCT s.nis)
			       FROM jbsfina.tabungan t, jbsakad.siswa s, jbsakad.kelas k
			      WHERE s.nis = t.nis
			        AND s.idkelas = k.replid
			        AND t.idtabungan = $idtabungan 
			        AND s.idangkatan = $idangkatan
			        AND k.idtingkat = $idtingkat";
			 
	$sql = "SELECT DISTINCT s.nis, s.nama, k.kelas
			  FROM jbsfina.tabungan t, jbsakad.siswa s, jbsakad.kelas k
			 WHERE s.nis = t.nis
			   AND s.idkelas = k.replid
			   AND t.idtabungan = $idtabungan 
			   AND s.idangkatan = $idangkatan
			   AND k.idtingkat = $idtingkat
		     ORDER BY $urut $urutan";
             
    if ($pageLimit)         
        $sql .= " LIMIT " . (int)$page * (int)$varbaris . ", $varbaris";              
}
else
{
	$sqlTotal = "SELECT COUNT(DISTINCT s.nis)
			       FROM jbsfina.tabungan t, jbsakad.siswa s, jbsakad.kelas k
			      WHERE s.nis = t.nis
			        AND s.idkelas = k.replid
			        AND t.idtabungan = $idtabungan 
			        AND s.idangkatan = $idangkatan
			        AND k.replid = $idkelas";
			 
	$sql = "SELECT DISTINCT s.nis, s.nama, k.kelas
			  FROM jbsfina.tabungan t, jbsakad.siswa s, jbsakad.kelas k
			 WHERE s.nis = t.nis
			   AND s.idkelas = k.replid
			   AND t.idtabungan = $idtabungan 
			   AND s.idangkatan = $idangkatan
			   AND k.replid = $idkelas
		     ORDER BY $urut $urutan";
    
    if ($pageLimit)         
        $sql .= " LIMIT " . (int)$page * (int)$varbaris . ", $varbaris";              
}

$ndata = FetchSingle($sqlTotal);
$total = ceil($ndata / (int)$varbaris);
$akhir = ceil($ndata / 5) * 5;

$nislist = "";

$res = QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
	echo "<tr height='80'><td colspan='9' align='center' valign='middle'><i>Tidak ditemukan data tabungan di kelas terpilih</i></td></tr>";
}
else
{
	if ($page == 0)
		$cnt = 0;
	else 
		$cnt = (int)$page*(int)$varbaris;

	while($rowsis = mysqli_fetch_row($res))
	{
		$nis = $rowsis[0];
		$nama = $rowsis[1];
		$kelas = $rowsis[2];

		if ($nislist != "") $nislist .= ",";
		$nislist .= "'$nis'";
		
		$totaltarik = 0;
		$totalsetor = 0;
		$saldo = 0;
		
		$sql = "SELECT SUM(debet), SUM(kredit)
			      FROM jbsfina.tabungan
				 WHERE idtabungan = '$idtabungan'
				   AND nis = '".$nis."'";
		$result = QueryDb($sql);
		if ($row = mysqli_fetch_row($result))
		{
			$totaltarik = $row[0];
			$totalsetor = $row[1];
			$saldo = $totalsetor - $totaltarik;
		}
		
		$setorakhir = 0;
		$tglsetorakhir = "";
		
		$sql = "SELECT DATE_FORMAT(tanggal, '%d-%b-%Y %H:%i:%s'), kredit
				  FROM jbsfina.tabungan
				 WHERE idtabungan = '$idtabungan'
				   AND nis = '$nis'
				   AND kredit <> 0
				 ORDER BY replid
				 LIMIT 1";
		$result = QueryDb($sql);
		if ($row = mysqli_fetch_row($result))
		{
			$tglsetorakhir = $row[0];
			$setorakhir = $row[1];
		}
		
		$tarikakhir = 0;
		$tgltarikakhir = "";
		
		$sql = "SELECT DATE_FORMAT(tanggal, '%d-%b-%Y %H:%i:%s'), debet
				  FROM jbsfina.tabungan
				 WHERE idtabungan = '$idtabungan'
				   AND nis = '$nis'
				   AND debet <> 0
				 ORDER BY replid
				 LIMIT 1";
		$result = QueryDb($sql);
		if ($row = mysqli_fetch_row($result))
		{
			$tgltarikakhir = $row[0];
			$tarikakhir = $row[1];
		}		
		?>
		<tr>
			<td align='center'><?=++$cnt?></td>
			<td align='left'><?=$nis?></td>
			<td align='left'><?=$nama?></td>
			<td align='left'><?=$kelas?></td>
			<td align='right' style='background-color:#DBF4C1'><b><?=FormatRupiah($saldo)?></b></td>
			<td align='right' style='background-color:#E0F3FF'><b><?=FormatRupiah($totalsetor)?></b></td>
			<td align='right' style='background-color:#E0F3FF'><b><?=FormatRupiah($setorakhir)?></b><br><i><?=$tglsetorakhir?></i></td>
			<td align='right' style='background-color:#F2E9C6'><b><?=FormatRupiah($totaltarik)?></b></td>
			<td align='right' style='background-color:#F2E9C6'><b><?=FormatRupiah($tarikakhir)?></b><br><i><?=$tgltarikakhir?></i></td>
		</tr>
<?php 	
	}
}
?>
</table>

<!-- TABLE CONTENT -->
    </td>
</tr>
<tr>
    <td>

<?php
        if ($nislist != "")
        {
            $allsetor = 0;
            $alltarik = 0;
            $allsaldo = 0;

            $sql = "SELECT SUM(debet), SUM(kredit)
              FROM jbsfina.tabungan
             WHERE idtabungan = '$idtabungan'
               AND nis IN ($nislist)";
            $result = QueryDb($sql);
            if ($row = mysqli_fetch_row($result))
            {
                $alltarik = $row[0];
                $allsetor = $row[1];
                $allsaldo = $allsetor - $alltarik;
            }
            ?>
            <br>
            <table border="0" cellpadding="5" cellspacing="2" style="border-width: 1px; border-color: #ddd; border-collapse: collapse">
            <tr>
                <td align="left" width="200px" style="background-color: #ececec"><strong>Semua Setoran</strong></td>
                <td align="right" width="160px" style="font-size: 12px; font-weight: bold; background-color:#E0F3FF"><?=FormatRupiah($allsetor)?></td>
            </tr>
            <tr>
                <td align="left" style="background-color: #ececec"><strong>Semua Tarikan</strong></td>
                <td align="right" style="font-size: 12px; font-weight: bold; background-color:#F2E9C6"><?=FormatRupiah($alltarik)?></td>
            </tr>
            <tr>
                <td align="left" style="background-color: #ececec"><strong>Semua Saldo</strong></td>
                <td align="right" style="font-size: 12px; font-weight: bold; background-color:#DBF4C1"><?=FormatRupiah($allsaldo)?></td>
            </tr>
            </table>
            <br>
<?php
        }
        ?>

    </td>
</tr>
</table>