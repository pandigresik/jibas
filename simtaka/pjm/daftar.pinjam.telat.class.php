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
class CTelat
{
	function OnStart()
	{
		$op = $_REQUEST['op'];
		
		$this->kriteria='all';
		if (isset($_REQUEST['kriteria']))
			$this->kriteria=$_REQUEST['kriteria'];
			
		if ($this->kriteria=='nip')
			$this->statuspeminjam=0;
		elseif ($this->kriteria=='nis')
			$this->statuspeminjam=1;
			
		$this->noanggota = $_REQUEST['noanggota'];
		$this->nama = $_REQUEST['nama'];
		$sqlDate="SELECT DAY(now()),MONTH(now()),YEAR(now())";
		$resultDate = QueryDb($sqlDate);
		$rDate = @mysqli_fetch_row($resultDate);
		
		$this->tglAwal = $rDate[0]."-".$rDate[1]."-".$rDate[2];
		if (isset($_REQUEST['tglAwal']))
			$this->tglAwal = $_REQUEST['tglAwal'];
			
		$this->tglAkhir = $rDate[0]."-".$rDate[1]."-".$rDate[2];	
		if (isset($_REQUEST['tglAkhir']))
			$this->tglAkhir = $_REQUEST['tglAkhir'];	
	}
	
	function OnFinish()
	{
		?>
		<script language='JavaScript'>
			Tables('table', 1, 0);
		</script>
		<?php
    }
	
    function Content()
	{
		$sql = "SELECT DATE_FORMAT(now(),'%Y-%m-%d')";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_row($result);
		$now = $row[0];
		if ($this->kriteria=='all')
			$sql = "SELECT *
					  FROM pinjam
					 WHERE status=1
					   AND tglkembali<'".$now."'
					 ORDER BY tglpinjam
					 LIMIT 100";
		elseif ($this->kriteria=='tglpinjam')
			$sql = "SELECT *
					  FROM pinjam
					 WHERE status=1
					   AND tglpinjam BETWEEN '".MySqlDateFormat($this->tglAwal)."' AND '".MySqlDateFormat($this->tglAkhir)."'
					   AND tglkembali<'".$now."'
					 ORDER BY tglpinjam";
		elseif ($this->kriteria=='tglkembali')
			$sql = "SELECT *
				      FROM pinjam
					 WHERE status=1
					   AND tglkembali BETWEEN '".MySqlDateFormat($this->tglAwal)."' AND '".MySqlDateFormat($this->tglAkhir)."'
					   AND tglkembali<'".$now."'
					 ORDER BY tglpinjam";
		else
			$sql = "SELECT *
					  FROM pinjam
					 WHERE status=1
					   AND (nis = '$this->noanggota' OR nip = '$this->noanggota')
					   AND tglkembali<'".$now."'
					 ORDER BY tglpinjam";
		
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		?>
		<div class="filter">
        <table width="100%" border="0" cellpadding="2" cellspacing="2">
        <tr>
            <td width="9%">Tampilkan&nbsp;berdasarkan</td>
            <td width="91%">
				<select name="kriteria" id="kriteria" onchange="chgKrit()">
					<option value="all" <?=StringIsSelected('all',$this->kriteria)?> >100 Peminjaman Yang Terlambat Terakhir</option>
					<option value="tglpinjam" <?=StringIsSelected('tglpinjam',$this->kriteria)?>>Tanggal Peminjaman</option>
					<option value="tglkembali" <?=StringIsSelected('tglkembali',$this->kriteria)?>>Jadwal Pengembalian</option>
					<option value="nis" <?=StringIsSelected('nis',$this->kriteria)?>>NIS Siswa</option>
					<option value="nip" <?=StringIsSelected('nip',$this->kriteria)?>>NIP Pegawai</option>
				</select>
            </td>
        </tr>
<?php 		if ($this->kriteria!='all')
		{
			if ($this->kriteria=='tglpinjam' ||$this->kriteria=='tglkembali' )
			{ ?>
			<tr id="tgl">
				<td align="right">Periode</td>
				<td>
					<table width="100%" border="0" cellpadding="0">
					<tr>
						<td valign="bottom">
							<input class="inptxt" name="tglAwal" id="tglAwal" type="text"
								   value="<?=$this->tglAwal?>" style="width:100px" readonly="readonly" />&nbsp;
							<a href="javascript:TakeDate('tglAwal')" >
							&nbsp;<img src="../img/ico/calendar.png" width="16" height="16" border="0" />
							</a>
							&nbsp;&nbsp;s.d.&nbsp;&nbsp;
							<input class="inptxt" name="tglAkhir" id="tglAkhir" type="text"
								   value="<?=$this->tglAkhir?>"  style="width:100px" readonly="readonly"/>
							<a href="javascript:TakeDate('tglAkhir')" >
								&nbsp;<img src="../img/ico/calendar.png" width="16" height="16" border="0" />
							</a>
							&nbsp;&nbsp;<em>*dd-mm-yyyy</em>
						</td>
					</tr>
					</table>
				</td>
			</tr>
<?php 			}

			if ($this->kriteria=='nis' || $this->kriteria=='nip' )
			{ ?>
			<tr id="nis">
				<td align="right"><?=strtoupper((string) $this->kriteria)?></td>
				<td>
					<input type="hidden" id="statuspeminjam" value="<?=$this->statuspeminjam?>" />
					<input type="text" class="inptxt-small-text" name="noanggota" id="noanggota" readonly="readonly"
						   onclick="cari()" value="<?=$this->noanggota?>" style="width:150px" />&nbsp;
					<input id="nama" class="inptxt-small-text" name="nama" type="text" readonly="readonly"
						   onclick="cari()" value="<?=$this->nama?>" size="50" style="width:200px"/>&nbsp;
					<a href="javascript:cari()"><img src="../img/ico/cari.png" width="16" height="16" border="0" /> 
				</td>
			</tr>
<?php 			}
		} ?>
        </table>
        </div>
		
      	<div class="funct">
        	<a href="javascript:getFresh()"><img src="../img/ico/refresh.png" border="0">&nbsp;Refresh</a>&nbsp;&nbsp;
            <a href="javascript:cetak()"><img src="../img/ico/print1.png" border="0">&nbsp;Cetak</a>&nbsp;&nbsp;        
        </div>
		
        <table width="100%" border="1" cellspacing="0" cellpadding="5" class="tab" id="table">
        <tr height="30">
			<td width='4%' height="30" align="center" class="header">No</td>
			<td width='10%' align="center" class="header">Tanggal Pinjam</td>
            <td width='10%' align="center" class="header">Jadwal Kembali</td>
            <td width='22%' align="center" class="header">Anggota</td>
            <td width='*' align="center" class="header">Kode Pustaka</td>
			<td width='15%' align="center" class="header">Keterangan</td>
            <td width='5%' align="center" class="header">Telat<br>(<em>hari</em>)</td>
			<td width='5%' align="center" class="header">Pengem<br>balian</td>
            <td width='5%' align="center" class="header">Perpan<br>jangan</td>
        </tr>
<?php 	if ($num > 0)
		{
			$cnt = 0;
			while ($row = @mysqli_fetch_array($result))
			{
				$cnt += 1;
				
				$sql = "SELECT judul
						  FROM pustaka p, daftarpustaka d
						 WHERE d.pustaka=p.replid
						   AND d.kodepustaka='{$row['kodepustaka']}'";
				//echo $sql;
				$res = QueryDb($sql);
				$r = @mysqli_fetch_row($res);
				$judul = $r[0];
				
				$this->idanggota = $row['idanggota'];
				$this->jenisanggota = $row['info1'];
				$NamaAnggota = $this->GetMemberName();
				$color = '#000000';
				$weight = '';
				$alt = 'OK';
				$img = '<img src="../img/ico/Valid.png" width="16" height="16" title='.$alt.' />';
				if ($row['tglkembali']<=$now)
				{
					if ($row['tglkembali']==$now)
					{
						$alt = 'Hari&nbsp;ini&nbsp;batas&nbsp;pengembalian&nbsp;terakhir';
						$color='#cb6e01';
						$weight='font-weight:bold';
					}
					elseif ($row['tglkembali']<$now)
					{
						$diff = @mysqli_fetch_row(QueryDb("SELECT DATEDIFF('".$now."','".$row['tglkembali']."')"));
						$alt = 'Terlambat&nbsp;'.$diff[0].'&nbsp;hari';
						$color='red';
						$weight='font-weight:bold';
						$telat=$diff[0];
					}
					$img='<img src="../img/ico/Alert2.png" width="16" height="16" title='.$alt.' />';
				}  ?>
				<tr height="25" style="color:<?=$color?>; <?=$weight?>">
					<td align='center'><?=$cnt?></td>
					<td align="center"><?=LongDateFormat($row['tglpinjam'])?></td>
					<td align="center"><?=LongDateFormat($row['tglkembali'])?></td>
					<td align="left"><?=$row['idanggota']?><br><?=$this->GetMemberName();?></td>
					<td align="left"><?= $row['kodepustaka'] . "<br>$judul" ?></td>
					<td align="left"><?=$row['keterangan']?></td>
					<td align="center"><?=$telat?></td>
					<td align="center">
						<a title="Kembalikan Sekarang" href="javascript:kembalikan('<?= $row['kodepustaka'] ?>');">
						<img src="../img/ico/refresh.png" width="16" height="16" border="0" />
						</a>
					</td>
					<td align="center">
						<a title="Perpanjangan" href="javascript:perpanjang('<?= $row['kodepustaka'] ?>');">
						<img src="../img/ico/tambah.png" width="16" height="16" border="0" />
						</a>
					</td>
				</tr>
<?php 		} // while
		}
		else
		{  ?>
			<tr>
				<td height="25" colspan="9" align="center" class="nodata">Tidak ada data</td>
			</tr>
<?php 	}  ?>	
		</table>
<?php
	}
	
	function GetMemberName()
	{
		if ($this->jenisanggota == "siswa")
		{
			$sql = "SELECT nama
					  FROM jbsakad.siswa
					 WHERE nis = '$this->idanggota'";
		}
		elseif ($this->jenisanggota == "pegawai")
		{
			$sql = "SELECT nama
					  FROM jbssdm.pegawai
					 WHERE nip = '$this->idanggota'";
		}
		else
		{
			$sql = "SELECT nama
					  FROM jbsperpus.anggota
					 WHERE noregistrasi = '$this->idanggota'";
		}
		$res = QueryDb($sql);
		$row = mysqli_fetch_row($res);
		$namaanggota = $row[0];
		
		return $namaanggota;
	}
}
?>