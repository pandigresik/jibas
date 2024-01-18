<?php
// -------------------------------------------------
// Injection Script For penentuan_content.php       
// this will recount existing nilai rapor           
// 2011-Mar-07 19:15                                
// -------------------------------------------------

$op = $_REQUEST['op'];
if ($op == "b91c61e239xn8e3b61ce1")
{
	OpenDb();
	$CRC = new RecountNilaiRapor();
	$CRC->Recount();
	CloseDb();
}

class RecountNilaiRapor
{
	private $idpelajaran;
	private $idkelas;
	private $idsemester;
	private $nip;
	private $idtingkat;
	private $departemen;
	private $idtahun;
	private $aspek;
	private $aspekket;
	
	private $idinfo = 0;
	private $idpemkon = 0;
	private ?array $ujian = null;
		
	public function __construct()
	{
		$this->idpelajaran = $_REQUEST['pelajaran'];
		$this->idkelas = $_REQUEST['kelas'];
		$this->idsemester = $_REQUEST['semester'];
		$this->nip = $_REQUEST['nip'];
		$this->idtingkat = $_REQUEST['tingkat'];
		$this->departemen = $_REQUEST['departemen'];
		$this->idtahun = $_REQUEST['tahun'];
		$this->aspek = $_REQUEST['aspek'];
		$this->aspekket = $_REQUEST['aspekket'];
	}
	
	public function Recount()
	{
		$this->GetIdInfo();
		if ($this->idinfo == 0)
			return;
	
		$this->GetAturanInfo();
		if ($this->idpemkon == 0)
			return;
		
		$this->GetUjianInfo();
		$this->DoRecount();
	}
	
	private function GetIdInfo()
	{
		$sql = "SELECT replid FROM jbsakad.infonap 
		         WHERE idpelajaran = '$this->idpelajaran' AND idsemester = '$this->idsemester' AND idkelas = '$this->idkelas'";
		$res = QueryDb($sql);
		if (mysqli_num_rows($res) == 0)
		{
			$this->idinfo = 0;
		}
		else
		{
			$row = mysqli_fetch_row($res);
			$this->idinfo = $row[0];
		}
	}
	
	private function GetAturanInfo()
	{
		// Ambil satu id aturannhb untuk menjadi link ke dasarpenilaian
		$sql = "SELECT a.replid FROM jbsakad.aturannhb a, kelas k 
				 WHERE a.nipguru = '$this->nip' AND a.idtingkat = k.idtingkat AND k.replid = '$this->idkelas' 
				   AND a.idpelajaran = '$this->idpelajaran' AND a.dasarpenilaian = '$this->aspek' 
			  ORDER BY a.replid ASC LIMIT 1";
		$res = QueryDb($sql);
		if (mysqli_num_rows($res) == 0)
		{
			$this->idpemkon = 0;
		}
		else
		{
			$row = mysqli_fetch_row($res);
			$this->idpemkon = $row[0];
		}
	}
	
	private function GetUjianInfo()
	{
		if ($this->idpemkon == 0)
			return;
			
		// -- Ambil data bobot dan jenisujian
		$sql = "SELECT j.jenisujian AS jenisujian, a.bobot AS bobot, a.replid, a.idjenisujian 
				  FROM jbsakad.aturannhb a, jbsakad.jenisujian j, kelas k 
				 WHERE a.idtingkat = k.idtingkat AND k.replid = '$this->idkelas' AND a.nipguru = '$this->nip' 
				   AND a.idpelajaran = '$this->idpelajaran' AND a.dasarpenilaian = '$this->aspek' 
				   AND a.idjenisujian = j.replid AND a.aktif = 1 ORDER BY a.replid";
		$res = QueryDb($sql);
		while ($row = mysqli_fetch_array($res))
		{
			$this->ujian[] = [$row['replid'], $row['bobot'], $row['idjenisujian'], $this->aspek];
		}
	}
	
	private function DoRecount()
	{
		if ($this->idpemkon == 0)
			return;
			
		// -- Ambil data siswa
		$sql = "SELECT nis FROM jbsakad.siswa WHERE idkelas='$this->idkelas' AND aktif=1 ORDER BY nama";
		$res = QueryDb($sql);
		
		$success = true;
		BeginTrans();
		
		// -- Hitung ulang semua siswa
		while ($success && ($row = mysqli_fetch_row($res)))
		{
			$nis = $row[0];
			
			$jumlah = 0;
			$bobotpk = 0;
			foreach($this->ujian as $value)
			{
				$sql = "SELECT n.nilaiau FROM jbsakad.nau n, jbsakad.aturannhb a 
						 WHERE n.idkelas = '$this->idkelas' AND n.nis = '$nis' AND n.idsemester = '$this->idsemester' 
						   AND n.idjenis = '".$value[2]."' AND n.idaturan = a.replid AND a.replid = '$value[0]'";
				$res2 = QueryDb($sql);
				$row2 = mysqli_fetch_row($res2);
				$nau = $row2[0];
				$bobot = $value[1];
				$nap = $nau * $bobot;
				
				$jumlah = $jumlah + $nap;
				$bobotpk = $bobotpk + $bobot;
			}
			$nilakhirpk = round($jumlah / $bobotpk, 2);
			
			$sql = "SELECT grade FROM aturangrading a, kelas k 
					 WHERE a.idpelajaran = '$this->idpelajaran' AND a.idtingkat = k.idtingkat 
					   AND k.replid = '$this->idkelas' AND a.dasarpenilaian = '$this->aspek' 
					   AND a.nipguru = '$this->nip' AND '$nilakhirpk' BETWEEN a.nmin AND a.nmax";
			// echo "$sql<br>";	
			$res2 = QueryDb($sql);
			$row2 = mysqli_fetch_row($res2);
			$gradepk = $row2[0];
			
			$sql = "UPDATE jbsakad.nap SET nilaiangka = '$nilakhirpk', nilaihuruf = '$gradepk' 
					 WHERE nis = '$nis' AND idinfo = '$this->idinfo' AND idaturan = '$this->idpemkon'";
			//echo "$sql<br>";
			QueryDbTrans($sql, $success);
		}
		
		if ($success)
			CommitTrans();
		else
			RollbackTrans();
	}
}
?>