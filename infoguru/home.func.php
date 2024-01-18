<?php
function ShowCbDepartemen($id, $onchange)
{
	echo "<select id='$id' onchange='$onchange' onkeyup='$onchange'>";
	$sql = "SELECT departemen
			  FROM jbsakad.departemen
			 WHERE aktif = 1
			 ORDER BY urutan";
	$res = QueryDb($sql);
	echo "<option value='ALL'>(Semua)</option>";
	while($row = mysqli_fetch_row($res))
	{
		echo "<option value='".$row[0]."'>".$row[0]."</option>";
	}
	echo "</select>";
}

function ShowCbTahun()
{
	global $G_START_YEAR;
	
	echo "<select id='tahun' onchange='changeCbTahun()' onkeyup='changeCbTahun()'>";
	for($i = $G_START_YEAR; $i <= date('Y'); $i++)
	{
		$sel = $i == date('Y') ? "selected" : "";
		echo "<option value='$i' $sel>$i</option>";
	}
	echo "</select>";
}

function ShowCbBulan()
{
	echo "<select id='bulan' onchange='changeCbBulan()' onkeyup='changeCbBulan()'>";
	for($i = 1; $i <= 12; $i++)
	{
		$sel = $i == date('n') ? "selected" : "";
		echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
	}
	echo "</select>";	
}

function ShowCbTanggal($tahun, $bulan)
{
	$yy = $tahun ?? date('Y');
	$mm = $bulan ?? date('n');
	$maxday = DateArith::DaysInMonth($mm, $yy);
	
	echo "<select id='tanggal' onchange='changeCbTanggal()' onkeyup='changeCbTanggal()'>";
	for($i = 1; $i <= $maxday; $i++)
	{
		$sel = "";
		if ($yy == date('Y') && $mm == date('n'))
			$sel = $i == date('j') ? "selected" : "";
			
		echo "<option value='$i' $sel>$i</option>";
	}
	echo "</select>";	
}

function ShowLastAgenda($maxListAgendaTs, $offsetListAgenda)
{
	global $HOME_NLIST_AGENDA;
	
	$filterId = $maxListAgendaTs == 0 ? "" : "AND CONCAT(UNIX_TIMESTAMP(tanggal), LPAD(replid, 7, '0')) > '$maxListAgendaTs'";
	
	$sql = "SELECT replid, DATE_FORMAT(tanggal, '%d-%b-%Y') AS tanggal, judul,
				   CONCAT(UNIX_TIMESTAMP(tanggal), LPAD(replid, 7, '0')) AS ts
			  FROM jbsvcr.agenda
			 WHERE idguru = '" . SI_USER_ID() . "'
			   AND tanggal >= CURDATE()
				   $filterId
			 ORDER BY tanggal ASC, replid ASC
			 LIMIT $HOME_NLIST_AGENDA";

	//$fsql = str_replace("\n", " ", $sql);
	//$fsql = str_replace("\r", " ", $fsql);
	//$fsql = str_replace("\t", " ", $fsql);
	
	$res = QueryDb($sql);
    if (0 == mysqli_num_rows($res))
    {
        $html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
        $html .= "<br><center>belum ada agenda</center>";
		$html .= "</td>";
		$html .= "</tr>";
		
		echo '{"maxListAgendaTs" : "NA", "html" : "' . $html . '"}';
        return;
    }
	
	$html = "";
	$no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        
		$replid = $row['replid'];
		$ts = $row['ts'];
		if ($maxListAgendaTs == 0)
		{
			$maxListAgendaTs = $ts;
		}
		else
		{
			if ($ts > $maxListAgendaTs)
				$maxListAgendaTs = $ts;
		}
		
		$html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
		$html .= "<a href='#' onclick='bacaAgenda($replid)'>";
        $html .= "<font style='color: maroon; font-size: 10px;'><em>" . $row['tanggal'] . "</em></font>&nbsp;&nbsp;";
        $html .= "<font style='font-weight: normal; font-style: italic; font-size: 10px;'>" . $row['judul'] . "</font>";
		//$html .= "$fsql";
		$html .= "</a>";
		$html .= "</td>";
		$html .= "</tr>";
    }
	
	echo '{"maxListAgendaTs" : "' . $maxListAgendaTs . '", "html" : "' . $html . '"}';
}

function ShowLinkNextListAgenda($maxListAgendaTs)
{
	global $HOME_NLIST_AGENDA;
	
	$filterId = $maxListAgendaTs == 0 ? "" : "AND CONCAT(UNIX_TIMESTAMP(tanggal), LPAD(replid, 7, '0')) > '$maxListAgendaTs'";
	
    $sql = "SELECT replid
			  FROM jbsvcr.agenda
			 WHERE idguru = '" . SI_USER_ID() . "' 
			   AND tanggal >= CURDATE()
				   $filterId
			 ORDER BY tanggal ASC
             LIMIT 1";
	
	//$fsql = str_replace("\n", " ", $sql);
	//$fsql = str_replace("\r", " ", $fsql);
	//$fsql = str_replace("\t", " ", $fsql);
	
	$res = QueryDb($sql);
	$ndata = mysqli_num_rows($res);
	if ($ndata == 0)
	{
		echo "";
		return;
	}
	
	$html  = "<tr>";
	$html .= "<td align='center' valign='middle'>";
	$html .= "<a href='#' onclick='showLastAgenda()'>";
	$html .= "<font style='color: green; font-weight: normal;'><em>selanjutnya</em>&nbsp;&nbsp;</font>";
	$html .= "</a>";
	$html .= "</td>";
	$html .= "</tr>";
	
	echo $html;
}

function ShowLastPesan($minListPesanId, $offsetListPesan)
{
	global $HOME_NLIST_PESAN;
	
	$filterId = $minListPesanId == 0 ? "" : "AND pg.replid < $minListPesanId";
	
    $sql = "SELECT pg.idguru as idguru, pg.nis as nis, IF(nis IS NULL, 'P', 'S') AS sendertype,
				   pg.judul as judul, DATE_FORMAT(pg.tanggalpesan, '%d-%b-%Y') as tanggal,
				   TIME_FORMAT(pg.tanggalpesan, '%H:%i') as waktu, t.baru as baru, t.replid, pg.replid AS pgreplid
			  FROM jbsvcr.pesan pg, jbsvcr.tujuanpesan t
			 WHERE t.idpesan = pg.replid
			   AND t.idpenerima = '".SI_USER_ID()."'
			   AND t.aktif = 1
			   $filterId
			 ORDER BY pg.replid DESC
             LIMIT $HOME_NLIST_PESAN";
	//$fsql = str_replace("\n", "\\n", $sql);
	//$fsql = str_replace("\r", "\\r", $fsql);
	//$fsql = str_replace("\t", "\\t", $fsql);
	
	$res = QueryDb($sql);
    if (0 == mysqli_num_rows($res))
    {
		$html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
        $html .= "<br><center>belum ada pesan</center>";
		$html .= "</td>";
		$html .= "</tr>";
		
		echo '{"minLastPesanId" : -1, "html" : "' . $html . '"}';
        return;
    }
    
	$html = "";
	
    $no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        
		$pgreplid = $row['pgreplid'];
		if ($minListPesanId == 0)
		{
			$minListPesanId = $pgreplid;
		}
		else
		{
			if ($pgreplid < $minListPesanId)
				$minListPesanId = $pgreplid;
		}
		
        $sendername = "";
        $sendertype = $row['sendertype'];
        if ($sendertype == "P")
        {
            $nip = $row['idguru'];
            $sql = "SELECT nama
                      FROM jbssdm.pegawai
                     WHERE nip = '".$nip."'";
            $res2 = QueryDb($sql);
            $row2 = mysqli_fetch_row($res2);
            
            $sendername = $row2[0];
        }
        else
        {
            $nis = $row['nis'];
            $sql = "SELECT nama
                      FROM jbsakad.siswa
                     WHERE nis = '".$nis."'";
            $res2 = QueryDb($sql);
            $row2 = mysqli_fetch_row($res2);
            
            $sendername = $row2[0];
        }
		
		$html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
		$html .= "<a href='#' onclick='bacaPesan($pgreplid)'>";
        $html .= "<font style='color: maroon; font-size: 10px;'><em>" . $row['tanggal'] . " " . $row["waktu"] . "</em>&nbsp;&nbsp;</font><font style='color: blue'>$sendername</font><br>";
        $html .= "<font style='font-weight: normal; font-style: italic; font-size: 10px;'>" . $row['judul'] . "</font>";
		$html .= "</a>";
		$html .= "</td>";
		$html .= "</tr>";
    }
	
	echo '{"minLastPesanId" : ' . $minListPesanId . ', "html" : "' . $html . '"}';
}

function ShowLinkNextListPesan($minListPesanId)
{
	global $HOME_NLIST_PESAN;
	
	$filterId = $minListPesanId == 0 ? "" : "AND pg.replid < $minListPesanId";
	
    $sql = "SELECT pg.replid
			  FROM jbsvcr.pesan pg, jbsvcr.tujuanpesan t
			 WHERE t.idpesan = pg.replid
			   AND t.idpenerima = '".SI_USER_ID()."'
			   AND t.aktif = 1
			   $filterId
			 ORDER BY pg.replid DESC  
             LIMIT 1";
	
	$res = QueryDb($sql);
	$ndata = mysqli_num_rows($res);
	if ($ndata == 0)
	{
		echo "";
		return;
	}
	
	$html  = "<tr>";
	$html .= "<td align='center' valign='middle'>";
	$html .= "<a href='#' onclick='showLastPesan()'>";
	$html .= "<font style='color: green; font-weight: normal;'><em>selanjutnya</em>&nbsp;&nbsp;</font>";
	$html .= "</a>";
	$html .= "</td>";
	$html .= "</tr>";
	
	echo $html;
}

function ShowBirthdayList($dd, $mm, $yy)
{
	$sql = "SELECT id, nama, usia, kelompok, jenis
			  FROM (	
			SELECT s.nis AS id, s.nama, $yy - YEAR(s.tgllahir) AS usia, k.kelas AS kelompok, 'S' AS jenis
			  FROM jbsakad.siswa s, jbsakad.kelas k
			 WHERE s.idkelas = k.replid
			   AND MONTH(s.tgllahir) = $mm
			   AND DAY(s.tgllahir) = $dd
			   AND s.aktif = 1
			   AND s.alumni = 0
			 UNION
			SELECT nip AS id, nama, $yy - YEAR(tgllahir) AS usia, bagian AS kelompok, 'P' AS jenis
			  FROM jbssdm.pegawai
			 WHERE MONTH(tgllahir) = $mm
			   AND DAY(tgllahir) = $dd
			   AND aktif = 1
				   ) AS X
			 ORDER BY nama";
	
	$html = "";
	
	$res = QueryDb($sql);
	if (0 == mysqli_num_rows($res))
    {
		$html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
        $html .= "<br><center>tidak ada data</center>";
		$html .= "</td>";
		$html .= "</tr>";
		
		echo $html;		
        return;
    }
	
	while($row = mysqli_fetch_array($res))
	{
		$jenis = $row['jenis'];
		$info = $jenis == "S" ? "kelas" : "bagian";
		
		$html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
		$html .= "<a href='#' onclick=\"showBdayInfo('" . $row['id'] . "', '" . $row['jenis'] . "')\">";
		$html .= "<font style='font-style: italic; font-weight: normal; font-size: 10px'>";
		$html .= $row['id'] . " - $info " . $row['kelompok'];
		$html .= "</font><br>";
		$html .= "<font style='font-weight: bold; font-size: 10px'>";
		$html .= $row['nama'] . " (" . $row['usia'] . " tahun)";
		$html .= "</font>";
		$html .= "</a>";
		$html .= "</td>";
		$html .= "</tr>";
	}
	
	echo $html;
}

function ShowLastNotes($minListNotesId, $offsetListNotes, $departemen)
{
	global $HOME_NLIST_NOTES;
	
	if ("Akademik" != SI_USER_GROUP())
    {
		$html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
        $html .= "<br><center>-</center>";
		$html .= "</td>";
		$html .= "</tr>";
		
		echo '{"minListNotesId" : -1, "html" : "' . $html . '"}';
        return;
    }
	
	$filterId = $minListNotesId == 0 ? "" : "AND c.replid < $minListNotesId";
	
	if ($departemen == "ALL")
	{
		$sql = "SELECT c.nis, s.nama AS namasis, DATE_FORMAT(c.tanggal, '%d-%b-%Y') as tanggal,
					   c.judul, c.nip, p.nama AS namapeg, k.kategori, c.replid
				  FROM jbsvcr.catatansiswa c, jbsvcr.catatankategori k, jbsakad.siswa s, jbssdm.pegawai p
				 WHERE c.idkategori = k.replid
				   AND c.nis = s.nis
				   AND c.nip = p.nip
				   AND s.aktif = 1
				   AND s.alumni = 0
					   $filterId
				 ORDER BY c.replid DESC
				 LIMIT $HOME_NLIST_NOTES";	
	}
	else
	{
		$sql = "SELECT c.nis, s.nama AS namasis, DATE_FORMAT(c.tanggal, '%d-%b-%Y') as tanggal,
					   c.judul, c.nip, p.nama AS namapeg, k.kategori, c.replid
				  FROM jbsvcr.catatansiswa c, jbsvcr.catatankategori k,
					   jbsakad.siswa s, jbssdm.pegawai p, jbsakad.angkatan a
				 WHERE c.idkategori = k.replid
				   AND c.nis = s.nis
				   AND c.nip = p.nip
				   AND s.idangkatan = a.replid
				   AND a.departemen = '$departemen'
				   AND s.aktif = 1
				   AND s.alumni = 0
					   $filterId
				 ORDER BY c.replid DESC
				 LIMIT $HOME_NLIST_NOTES";
	}
	
	//$fsql = str_replace("\n", "\\n", $sql);
	//$fsql = str_replace("\r", "\\r", $fsql);
	//$fsql = str_replace("\t", "\\t", $fsql);
	
	//echo '{"minLastNotesId" : -1, "html" : "' . $fsql . '"}';
    //return;
	
	$html = "";		 
	$res = QueryDb($sql);
    if (0 == mysqli_num_rows($res))
    {
		$html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
        $html .= "<br><center>belum ada catatan siswa</center>";
		$html .= "</td>";
		$html .= "</tr>";
		
		echo '{"minListNotesId" : -1, "html" : "' . $html . '"}';
        return;
    }
	
	$no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        
		$replid = $row['replid'];
		if ($minListNotesId == 0)
		{
			$minListNotesId = $replid;
		}
		else
		{
			if ($replid < $minListNotesId)
				$minListNotesId = $replid;
		}
		
		$html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
		$html .= "<a href='#' onclick='bacaNotes($replid)'>";
        $html .= "<font style='font-weight: normal; color: maroon; font-size: 10px;'><em>" . $row['tanggal'] . "</em>&nbsp;&nbsp;</font><font style='font-weight: normal; color: black'>" . $row["kategori"] . "</font><br>";
        $html .= "<font style='font-size: 10px;'>" . $row['nis'] . " " . $row['namasis'] . "</font><br>";
		$html .= "<font style='font-weight: normal; font-size: 10px;'>" . $row['judul'] . "</font><br>";
		$html .= "<font style='font-weight: normal; font-size: 10px;'><em>oleh " . $row['namapeg'] . "</em></font>";
		$html .= "</a>";
		$html .= "</td>";
		$html .= "</tr>";
    }
	
	echo '{"minListNotesId" : ' . $minListNotesId . ', "html" : "' . $html . '"}';
}

function ShowLinkNextListNotes($minListNotesId, $departemen)
{
	global $HOME_NLIST_NOTES;
	
	if ("Akademik" != SI_USER_GROUP())
    {
		echo "";
		return;
    }
	
	$filterId = $minListNotesId == 0 ? "" : "AND c.replid < $minListNotesId";
	
	if ($departemen == "ALL")
	{
		$sql = "SELECT c.replid
				  FROM jbsvcr.catatansiswa c, jbsakad.siswa s
				 WHERE c.nis = s.nis
				   AND s.aktif = 1
				   AND s.alumni = 0
					   $filterId
				 ORDER BY c.replid DESC
				 LIMIT $HOME_NLIST_NOTES";	
	}
	else
	{
		$sql = "SELECT c.replid
				  FROM jbsvcr.catatansiswa c, jbsakad.siswa s, jbsakad.angkatan a
				 WHERE c.nis = s.nis
				   AND s.idangkatan = a.replid
				   AND a.departemen = '$departemen'
				   AND s.aktif = 1
				   AND s.alumni = 0
					   $filterId
				 ORDER BY c.replid DESC
				 LIMIT $HOME_NLIST_NOTES";
	}
	
	$res = QueryDb($sql);
	$ndata = mysqli_num_rows($res);
	if ($ndata == 0)
	{
		echo "";
		return;
	}
	
	$html  = "<tr>";
	$html .= "<td align='center' valign='middle'>";
	$html .= "<a href='#' onclick='showLastNotes()'>";
	$html .= "<font style='color: green; font-weight: normal;'><em>selanjutnya</em>&nbsp;&nbsp;</font>";
	$html .= "</a>";
	$html .= "</td>";
	$html .= "</tr>";
	
	echo $html;
}

function ShowLastBeritaSekolah($maxListBSekolahTs)
{
	global $HOME_NLIST_BSEKOLAH;
	
	$filterId = $maxListBSekolahTs == 0 ? "" : "AND CONCAT(UNIX_TIMESTAMP(DATE(b.tanggal)), LPAD(b.replid, 7, '0')) > '$maxListBSekolahTs'";
	
	$sql = "SELECT b.replid, DATE_FORMAT(b.tanggal, '%d-%b-%Y') AS tanggal, b.judul,
				   CONCAT(UNIX_TIMESTAMP(DATE(b.tanggal)), LPAD(b.replid, 7, '0')) AS ts,
				   b.idpengirim, p.nama
			  FROM jbsvcr.beritasekolah b, jbssdm.pegawai p
			 WHERE b.idpengirim = p.nip
			   AND DATE(b.tanggal) <= CURDATE()
				   $filterId
			 ORDER BY b.tanggal DESC, b.replid DESC
			 LIMIT $HOME_NLIST_BSEKOLAH";
	
	//$fsql = str_replace("\n", " ", $sql);
	//$fsql = str_replace("\r", " ", $fsql);
	//$fsql = str_replace("\t", " ", $fsql);
	
	//echo '{"minLastNotesId" : -1, "html" : "' . $fsql . '"}';
    //return;
	
	$html = "";
	
	$res = QueryDb($sql);
    if (0 == mysqli_num_rows($res))
    {
        $html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
        $html .= "<br><center>belum ada berita sekolah</center>";
		$html .= "</td>";
		$html .= "</tr>";
		
		echo '{"maxListBSekolahTs" : "NA", "html" : "' . $html . '"}';
        return;
    }
	
	$no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        
		$replid = $row['replid'];
		$ts = $row['ts'];
		if ($maxListBSekolahTs == 0)
		{
			$maxListBSekolahTs = $ts;
		}
		else
		{
			if ($ts > $maxListBSekolahTs)
				$maxListBSekolahTs = $ts;
		}
		
		$html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
		$html .= "<a href='#' onclick='bacaBSekolah($replid)'>";
        $html .= "<font style='font-weight: normal; color: maroon; font-size: 11px;'><em>" . $row['tanggal'] . " oleh " . $row['nama'] . "</em></font><br>";
        $html .= "<font style='font-weight: weight; font-style: italic; font-size: 11px;'>" . $row['judul'] . "</font>";
		$html .= "</a>";
		$html .= "</td>";
		$html .= "</tr>";
    }
	
	echo '{"maxListBSekolahTs" : "' . $maxListBSekolahTs . '", "html" : "' . $html . '"}';
}

function ShowLinkNextListBeritaSekolah($maxListBSekolahTs)
{
	global $HOME_NLIST_BSEKOLAH;
	
	$filterId = $maxListBSekolahTs == 0 ? "" : "AND CONCAT(UNIX_TIMESTAMP(DATE(b.tanggal)), LPAD(b.replid, 7, '0')) > '$maxListBSekolahTs'";
	
	$sql = "SELECT b.replid
			  FROM jbsvcr.beritasekolah b
			 WHERE DATE(b.tanggal) <= CURDATE()
				   $filterId
			 ORDER BY b.tanggal DESC, b.replid DESC
			 LIMIT 1";
	
	//$fsql = str_replace("\n", " ", $sql);
	//$fsql = str_replace("\r", " ", $fsql);
	//$fsql = str_replace("\t", " ", $fsql);
	
	$res = QueryDb($sql);
	$ndata = mysqli_num_rows($res);
	if ($ndata == 0)
	{
		echo "";
		return;
	}
	
	$html  = "<tr>";
	$html .= "<td align='center' valign='middle'>";
	$html .= "<a href='#' onclick='showLastBeritaSekolah()'>";
	$html .= "<font style='color: green; font-weight: normal;'><em>selanjutnya</em>&nbsp;&nbsp;</font>";
	//$html .= "<br>$fsql";
	$html .= "</a>";
	$html .= "</td>";
	$html .= "</tr>";
	
	echo $html;		 
}

function ShowLastBeritaGuru($maxListBGuruTs)
{
	global $HOME_NLIST_BGURU;
	
	$filterId = $maxListBGuruTs == 0 ? "" : "AND CONCAT(UNIX_TIMESTAMP(DATE(b.tanggal)), LPAD(b.replid, 7, '0')) > '$maxListBGuruTs'";
	
	$sql = "SELECT b.replid, DATE_FORMAT(b.tanggal, '%d-%b-%Y') AS tanggal, b.judul,
				   CONCAT(UNIX_TIMESTAMP(DATE(b.tanggal)), LPAD(b.replid, 7, '0')) AS ts,
				   b.idguru, p.nama
			  FROM jbsvcr.beritaguru b, jbssdm.pegawai p
			 WHERE b.idguru = p.nip
			   AND DATE(b.tanggal) <= CURDATE()
				   $filterId
			 ORDER BY b.tanggal DESC, b.replid DESC
			 LIMIT $HOME_NLIST_BGURU";
	
	//$fsql = str_replace("\n", " ", $sql);
	//$fsql = str_replace("\r", " ", $fsql);
	//$fsql = str_replace("\t", " ", $fsql);
	
	//echo '{"minLastNotesId" : -1, "html" : "' . $fsql . '"}';
    //return;
	
	$html = "";
	
	$res = QueryDb($sql);
    if (0 == mysqli_num_rows($res))
    {
        $html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
        $html .= "<br><center>belum ada berita guru</center>";
		$html .= "</td>";
		$html .= "</tr>";
		
		echo '{"maxListBGuruTs" : "NA", "html" : "' . $html . '"}';
        return;
    }
	
	$no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        
		$replid = $row['replid'];
		$ts = $row['ts'];
		if ($maxListBGuruTs == 0)
		{
			$maxListBGuruTs = $ts;
		}
		else
		{
			if ($ts > $maxListBGuruTs)
				$maxListBGuruTs = $ts;
		}
		
		$html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
		$html .= "<a href='#' onclick='bacaBGuru($replid)'>";
        $html .= "<font style='font-weight: normal; color: maroon; font-size: 11px;'><em>" . $row['tanggal'] . " oleh " . $row['nama'] . "</em></font><br>";
        $html .= "<font style='font-weight: weight; font-style: italic; font-size: 11px;'>" . $row['judul'] . "</font>";
		$html .= "</a>";
		$html .= "</td>";
		$html .= "</tr>";
    }
	
	echo '{"maxListBGuruTs" : "' . $maxListBGuruTs . '", "html" : "' . $html . '"}';
}

function ShowLinkNextListBeritaGuru($maxListBGuruTs)
{
	global $HOME_NLIST_BGURU;
	
	$filterId = $maxListBGuruTs == 0 ? "" : "AND CONCAT(UNIX_TIMESTAMP(DATE(b.tanggal)), LPAD(b.replid, 7, '0')) > '$maxListBGuruTs'";
	
	$sql = "SELECT b.replid
			  FROM jbsvcr.beritaguru b
			 WHERE DATE(b.tanggal) <= CURDATE()
				   $filterId
			 ORDER BY b.tanggal DESC, b.replid DESC
			 LIMIT 1";
	
	//$fsql = str_replace("\n", " ", $sql);
	//$fsql = str_replace("\r", " ", $fsql);
	//$fsql = str_replace("\t", " ", $fsql);
	
	$res = QueryDb($sql);
	$ndata = mysqli_num_rows($res);
	if ($ndata == 0)
	{
		echo "";
		return;
	}
	
	$html  = "<tr>";
	$html .= "<td align='center' valign='middle'>";
	$html .= "<a href='#' onclick='showLastBeritaGuru()'>";
	$html .= "<font style='color: green; font-weight: normal;'><em>selanjutnya</em>&nbsp;&nbsp;</font>";
	//$html .= "<br>$fsql";
	$html .= "</a>";
	$html .= "</td>";
	$html .= "</tr>";
	
	echo $html;		 
}

function ShowLastBeritaSiswa($maxListBSiswaTs)
{
	global $HOME_NLIST_BSISWA;
	
	$filterId = $maxListBSiswaTs == 0 ? "" : "AND CONCAT(UNIX_TIMESTAMP(DATE(b.tanggal)), LPAD(b.replid, 7, '0')) > '$maxListBSiswaTs'";
	
	$sql = "SELECT b.replid, DATE_FORMAT(b.tanggal, '%d-%b-%Y') AS tanggal, b.judul,
				   CONCAT(UNIX_TIMESTAMP(DATE(b.tanggal)), LPAD(b.replid, 7, '0')) AS ts,
				   b.idguru, b.idpengirim, IF(b.idguru IS NULL, 'S', 'P') AS jenis
			  FROM jbsvcr.beritasiswa b
			 WHERE DATE(b.tanggal) <= CURDATE()
				   $filterId
			 ORDER BY DATE(b.tanggal) DESC, b.replid DESC
			 LIMIT $HOME_NLIST_BSISWA";
	
	//$fsql = str_replace("\n", " ", $sql);
	//$fsql = str_replace("\r", " ", $fsql);
	//$fsql = str_replace("\t", " ", $fsql);
	
	//echo '{"minLastNotesId" : -1, "html" : "' . $fsql . '"}';
    //return;
	
	$html = "";
	
	$res = QueryDb($sql);
    if (0 == mysqli_num_rows($res))
    {
        $html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
        $html .= "<br><center>belum ada berita siswa</center>";
		$html .= "</td>";
		$html .= "</tr>";
		
		echo '{"maxListBSiswaTs" : "NA", "html" : "' . $html . '"}';
        return;
    }
	
	$no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
		
		$jenis = $row['jenis'];
		if ($jenis == "S")
		{
			$sql = "SELECT nama
					  FROM jbsakad.siswa
					 WHERE nis = '" . $row['idpengirim'] ."'";
		}
		else
		{
			$sql = "SELECT nama
					  FROM jbssdm.pegawai
					 WHERE nip = '" . $row['idguru'] ."'";
		}
		$res2 = QueryDb($sql);
		$row2 = mysqli_fetch_row($res2);
		$nama = $row2[0];
        
		$replid = $row['replid'];
		$ts = $row['ts'];
		if ($maxListBSiswaTs == 0)
		{
			$maxListBSiswaTs = $ts;
		}
		else
		{
			if ($ts > $maxListBSiswaTs)
				$maxListBSiswaTs = $ts;
		}
		
		$html .= "<tr>";
		$html .= "<td align='left' valign='top'>";
		$html .= "<a href='#' onclick='bacaBSiswa($replid)'>";
        $html .= "<font style='font-weight: normal; color: maroon; font-size: 11px;'><em>" . $row['tanggal'] . " oleh " . $nama . "</em></font><br>";
        $html .= "<font style='font-weight: weight; font-style: italic; font-size: 11px;'>" . $row['judul'] . "</font>";
		$html .= "</a>";
		$html .= "</td>";
		$html .= "</tr>";
    }
	
	echo '{"maxListBSiswaTs" : "' . $maxListBSiswaTs . '", "html" : "' . $html . '"}';
}

function ShowLinkNextListBeritaSiswa($maxListBSiswaTs)
{
	global $HOME_NLIST_BSISWA;
	
	$filterId = $maxListBSiswaTs == 0 ? "" : "AND CONCAT(UNIX_TIMESTAMP(DATE(b.tanggal)), LPAD(b.replid, 7, '0')) > '$maxListBSiswaTs'";
	
	$sql = "SELECT b.replid
			  FROM jbsvcr.beritasiswa b
			 WHERE DATE(b.tanggal) <= CURDATE()
				   $filterId
			 ORDER BY DATE(b.tanggal) DESC, b.replid DESC
			 LIMIT 1";
	
	//$fsql = str_replace("\n", " ", $sql);
	//$fsql = str_replace("\r", " ", $fsql);
	//$fsql = str_replace("\t", " ", $fsql);
	
	$res = QueryDb($sql);
	$ndata = mysqli_num_rows($res);
	if ($ndata == 0)
	{
		echo "";
		return;
	}
	
	$html  = "<tr>";
	$html .= "<td align='center' valign='middle'>";
	$html .= "<a href='#' onclick='showLastBeritaSiswa()'>";
	$html .= "<font style='color: green; font-weight: normal;'><em>selanjutnya</em>&nbsp;&nbsp;</font>";
	//$html .= "<br>$fsql";
	$html .= "</a>";
	$html .= "</td>";
	$html .= "</tr>";
	
	echo $html;		 
}

function ShowImageUser()
{
	$nip = SI_USER_ID();
	
	$sql = "SELECT foto, foto IS NULL AS isnull
			  FROM jbssdm.pegawai
			 WHERE nip = '".$nip."'";
	$res = QueryDb($sql);	

	if (mysqli_num_rows($res) > 0)
	{
		$row = mysqli_fetch_array($res);
		if ($row['isnull'] == 0)
		{
			$pict = base64_encode((string) $row['foto']);    
		}
		else
		{
			$pict = GetNoUserImage();
		}
		
	}
	else
	{
		$pict = GetNoUserImage();
	}
	
	echo "<img src='data:image/jpeg;base64,$pict' height='60'>";
}

function GetNoUserImage()
{
    $filename = "images/no_user.png";
    $contents = "";
    
    if ($handle = @fopen($filename, "r"))
    {
        $contents = @fread($handle, filesize($filename));
        @fclose($handle);
    }

	return base64_encode($contents);
}

function GetTimeState()
{
	$hour = (int)date("H");
	
	if ($hour >= 4 && $hour <= 9)
		return "Pagi";
	elseif ($hour > 9 && $hour <= 14)
		return "Siang";
	elseif ($hour > 15 && $hour <= 18)
		return "Sore";
	
	return "Malam";
}
?>