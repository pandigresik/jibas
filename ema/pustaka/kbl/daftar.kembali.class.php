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
class CDaftarKembali
{
	function OnStart()
	{
		$op=$_REQUEST['op'];
	
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
		$this->tglAkhir = $rDate[0]."-".$rDate[1]."-".$rDate[2];
		if (isset($_REQUEST['tglAwal']))
			$this->tglAwal = $_REQUEST['tglAwal'];
		if (isset($_REQUEST['tglAkhir']))
			$this->tglAkhir = $_REQUEST['tglAkhir'];	
		$this->denda=0;
		if (isset($_REQUEST['denda']))
			$this->denda=$_REQUEST['denda'];
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
		global $db_name_perpus;
		
		$sql = "SELECT DATE_FORMAT(now(),'%Y-%m-%d')";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_row($result);
		$now = $row[0];
		
		if ($this->kriteria=='all')
			$sql = "SELECT *
					  FROM jbsperpus.pinjam
					 WHERE status=2
					 ORDER BY tglditerima DESC
					 LIMIT 100";
		elseif ($this->kriteria=='tglpinjam')
			$sql = "SELECT *, replid
					  FROM jbsperpus.pinjam
					 WHERE status=2
					   AND tglpinjam BETWEEN '".MySqlDateFormat($this->tglAwal)."' AND '".MySqlDateFormat($this->tglAkhir)."'
					 ORDER BY tglditerima DESC";
		elseif ($this->kriteria=='tglkembali')
			$sql = "SELECT *, replid
				      FROM jbsperpus.pinjam
					 WHERE status=2
					   AND tglkembali BETWEEN '".MySqlDateFormat($this->tglAwal)."' AND '".MySqlDateFormat($this->tglAkhir)."'
					 ORDER BY tglditerima DESC";
		elseif ($this->kriteria=='nip' || ($this->kriteria=='nis'))
			$sql = "SELECT *, replid
					  FROM jbsperpus.pinjam
					 WHERE status=2
					   AND (nis = '$this->noanggota' OR nip = '$this->noanggota')
					   AND tglkembali<='".$now."'
					 ORDER BY tglditerima DESC";
		elseif ($this->kriteria=='denda')
		{
			if ($this->denda==0)
				$sql = "SELECT *, p.replid AS replid
						  FROM jbsperpus.pinjam p, jbsperpus.denda d
						 WHERE p.status=2
						   AND p.replid=d.idpinjam
						   AND d.denda='0'
						 ORDER BY p.tglditerima DESC";
			elseif ($this->denda==1)
				$sql = "SELECT *, p.replid AS replid
						  FROM jbsperpus.pinjam p, jbsperpus.denda d
						 WHERE p.status=2
						   AND p.replid=d.idpinjam
						   AND d.denda>0
						   AND d.denda<5000
						 ORDER BY p.tglditerima DESC";
			elseif ($this->denda==2)
				$sql = "SELECT *, p.replid AS replid
				          FROM jbsperpus.pinjam p, jbsperpus.denda d
						 WHERE p.status=2
						   AND p.replid=d.idpinjam
						   AND d.denda>0
						   AND d.denda<10000
						 ORDER BY p.tglditerima DESC";
			elseif ($this->denda==3)
				$sql = "SELECT *, p.replid AS replid
						  FROM jbsperpus.pinjam p, jbsperpus.denda d
						 WHERE p.status=2
						   AND p.replid=d.idpinjam
						   AND d.denda>10000
						 ORDER BY p.tglditerima DESC";			
		}
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		?>
        <div class="filter">
        <table width="100%" border="0" cellpadding="2" cellspacing="2">
        <tr>
            <td width="9%">Tampilkan&nbsp;berdasarkan</td>
            <td width="91%">
            <select name="kriteria" id="kriteria" onchange="chgKrit()" class='cmbfrm'>
				<option value="all" <?=StringIsSelected('all',$this->kriteria)?> >100 Pengembalian Terakhir</option>
				<option value="tglpinjam" <?=StringIsSelected('tglpinjam',$this->kriteria)?>>Tanggal Peminjaman</option>
				<option value="tglkembali" <?=StringIsSelected('tglkembali',$this->kriteria)?>>Jadwal Pengembalian</option>
				<option value="nis" <?=StringIsSelected('nis',$this->kriteria)?>>NIS Siswa</option>
				<option value="nip" <?=StringIsSelected('nip',$this->kriteria)?>>NIP Pegawai</option>
				<option value="denda" <?=StringIsSelected('denda',$this->kriteria)?>>Denda</option>
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
						<td valign="bottom"><input class="inptxt" name="tglAwal" id="tglAwal" type="text" value="<?=$this->tglAwal?>" style="width:100px" readonly="readonly" />&nbsp;<a href="javascript:TakeDate('tglAwal')" >&nbsp;<img src="../../img/ico/calendar.png" width="16" height="16" border="0" /></a>&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input class="inptxt" name="tglAkhir" id="tglAkhir" type="text" value="<?=$this->tglAkhir?>"  style="width:100px" readonly="readonly"/><a href="javascript:TakeDate('tglAkhir')" >&nbsp;<img src="../../img/ico/calendar.png" width="16" height="16" border="0" /></a>&nbsp;&nbsp;<em>*dd-mm-yyyy</em></td>
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
					<input type="text" class="inptxt-small-text" name="noanggota" id="noanggota" readonly="readonly"  onclick="cari()" value="<?=$this->noanggota?>" style="width:150px" />
					&nbsp;
					<input id="nama" class="inptxt-small-text" name="nama" type="text" readonly="readonly"  onclick="cari()" value="<?=$this->nama?>" size="50" style="width:200px"/>
					&nbsp;
					<a href="javascript:cari()"><img src="../../img/ico/cari.png" width="16" height="16" border="0" /> </a>
				</td>
			</tr>
<?php 			}

			if ($this->kriteria=='denda')
			{ ?>
			<tr id="nis">
				<td align="right">Besar Denda</td>
				<td>
					<select name="denda" id="denda" onchange="chgDenda()" >
						<option value="0" <?=StringIsSelected('0',$this->denda)?>>Tanpa Denda</option>
						<option value="1" <?=StringIsSelected('1',$this->denda)?>>< Rp 5.000</option>
						<option value="2" <?=StringIsSelected('2',$this->denda)?>>< Rp 10.000</option>
						<option value="3" <?=StringIsSelected('3',$this->denda)?>>> Rp 10.000</option>
					</select>	    
				</td>
			</tr>
<?php 			}
		} ?>
        </table>
        </div>
		
      	<div class="funct" align="right" style="padding-bottom:5px">
        	<a href="javascript:getFresh()"><img src="../../img/ico/refresh.png" border="0">&nbsp;Refresh</a>&nbsp;&nbsp;
			<a href="javascript:cetak()"><img src="../../img/ico/print1.png" border="0">&nbsp;Cetak</a>&nbsp;&nbsp;        
        </div>
		
        <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table">
        <tr height="30">
			<td width='4%' align="center" class="header">No</td>
			<td width='10%' align="center" class="header">Tanggal<br>Pengembalian</td>
			<td width='10%' align="center" class="header">Jadwal Kembali</td>
			<td width='10%' align="center" class="header">Tanggal Pinjam</td>
            <td width='15%' align="center" class="header">Anggota</td>
            <td width='*' align="center" class="header">Kode Pustaka</td>            
            <td width='8%' align="center" class="header">Denda</td>
            <td width='10%' align="center" class="header">Keterangan</td>
        </tr>
<?php   	if ($num>0)
		{
			$cnt = 0;
			
			while ($row=@mysqli_fetch_array($result))
			{
				$cnt += 1;
				
				$sql = "SELECT denda
						  FROM jbsperpus.denda
						 WHERE idpinjam='".$row['replid']."'";
				$res2 = QueryDb($sql);
				$row2 = @mysqli_fetch_array($res2);
				$denda = $row2['denda'];
				
				$kodepustaka = $row['kodepustaka'];
				$sql = "SELECT p.judul
						  FROM jbsperpus.daftarpustaka dp, jbsperpus.pustaka p
						 WHERE dp.pustaka = p.replid
						   AND dp.kodepustaka = '".$kodepustaka."'";
				$res2 = QueryDb($sql);
				$row2 = @mysqli_fetch_array($res2);
				$judul = $row2['judul'];
				
				$this->idanggota = $row['idanggota'];
				$this->jenisanggota = $row['info1'];
				
				$NamaAnggota = $this->GetMemberName();  ?>
				
				<tr height="25" style="color:<?=$color?>; <?=$weight?>">
					<td align='center'><?=$cnt?></td>
					<td align="center"><?=LongDateFormat($row['tglditerima'])?></td>
					<td align="center"><?=LongDateFormat($row['tglkembali'])?></td>
					<td align="center"><?=LongDateFormat($row['tglpinjam'])?></td>
					<td align="left">
						<font style='font-size: 9px'><?=$row['idanggota']?></font><br>
						<font style='font-size: 11px; font-weight: bold;'><?=$this->GetMemberName()?></font>
					</td>
					<td align="left">
						<font style='font-size: 9px'><?=$kodepustaka?></font><br>
						<font style='font-size: 11px; font-weight: bold;'><?=$judul?></font>
					</td>
					<td align="right"><?=FormatRupiah($denda)?></td>
					<td align="center"><?=$row['keterangan']?></td>
				</tr>
<?php 	  	}
		}
		else
		{  ?>
			<tr>
				<td height="25" colspan="7" align="center" class="nodata">Tidak ada data</td>
			</tr>
<?php   	}  ?>	
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