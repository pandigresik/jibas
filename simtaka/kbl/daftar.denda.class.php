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
class CDaftarDenda
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
		if (isset($_REQUEST['tglAwal']))
			$this->tglAwal = $_REQUEST['tglAwal'];
			
		$this->tglAkhir = $rDate[0]."-".$rDate[1]."-".$rDate[2];	
		if (isset($_REQUEST['tglAkhir']))
			$this->tglAkhir = $_REQUEST['tglAkhir'];
			
		$this->denda=0;
		if (isset($_REQUEST['denda']))
			$this->denda=$_REQUEST['denda'];
			
		$this->telat=1;
		if (isset($_REQUEST['telat']) && $_REQUEST['telat']!="")
			$this->telat=$_REQUEST['telat'];
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
			$sql = "SELECT *, d.replid AS replid
				      FROM pinjam p, denda d
					 WHERE p.status=2
					   AND p.replid=d.idpinjam
					 ORDER BY p.tglditerima DESC
					 LIMIT 100";
		elseif ($this->kriteria=='tglpinjam')
			$sql = "SELECT *, d.replid AS replid
				      FROM pinjam p,denda d
					 WHERE p.status=2
					   AND p.tglpinjam BETWEEN '".MySqlDateFormat($this->tglAwal)."' AND '".MySqlDateFormat($this->tglAkhir)."'
					   AND p.replid=d.idpinjam
					 ORDER BY p.tglditerima DESC";
		elseif ($this->kriteria=='tglkembali')
			$sql = "SELECT *, d.replid AS replid
			          FROM pinjam p,denda d
					 WHERE p.status=2
					   AND p.tglkembali BETWEEN '".MySqlDateFormat($this->tglAwal)."' AND '".MySqlDateFormat($this->tglAkhir)."'
					   AND p.replid=d.idpinjam
					 ORDER BY p.tglditerima DESC";
		elseif ($this->kriteria=='nip' || ($this->kriteria=='nis'))
			$sql = "SELECT *, d.replid AS replid
					  FROM pinjam p, denda d
					 WHERE p.status=2
					   AND p.idanggota='$this->noanggota'
					   AND p.tglditerima <= '".$now."'
					   AND p.replid=d.idpinjam
					 ORDER BY p.tglditerima DESC";
		elseif ($this->kriteria=='denda')
		{
			if ($this->denda==0)
				$sql = "SELECT *, d.replid AS replid
						  FROM pinjam p,denda d
						 WHERE p.status=2
						   AND p.replid=d.idpinjam
						   AND d.denda='0'
						 ORDER BY p.tglditerima DESC";
			elseif ($this->denda==1)
				$sql = "SELECT *, d.replid AS replid
						  FROM pinjam p, denda d
						 WHERE p.status=2
						   AND p.replid=d.idpinjam
						   AND d.denda>0
						   AND d.denda<5000
						 ORDER BY p.tglditerima DESC";
			elseif ($this->denda==2)
				$sql = "SELECT *, d.replid AS replid
					      FROM pinjam p, denda d
						 WHERE p.status=2
						   AND p.replid=d.idpinjam
						   AND d.denda>0
						   AND d.denda<10000
						 ORDER BY p.tglditerima DESC";
			elseif ($this->denda==3)
				$sql = "SELECT *, d.replid AS replid
					      FROM pinjam p,denda d
						 WHERE p.status=2
						   AND p.replid=d.idpinjam
						   AND d.denda>10000
						 ORDER BY p.tglditerima DESC";			
		} elseif ($this->kriteria=='telat'){
			$sql = "SELECT *, d.replid AS replid
				      FROM pinjam p,denda d
					 WHERE p.status=2
					   AND p.replid=d.idpinjam
					   AND DATEDIFF(p.tglditerima,p.tglkembali)='$this->telat'
					 ORDER BY p.tglditerima DESC";
		}
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		?>
        <div class="filter">
        <table width="100%" border="0" cellpadding="2" cellspacing="2">
        <tr>
            <td width="110">Tampilkan&nbsp;berdasarkan</td>
            <td width="400">
            <select name="kriteria" id="kriteria" onchange="chgKrit()">
				<option value="all" <?=StringIsSelected('all',$this->kriteria)?> >100 Penerimaan Denda Terakhir</option>
				<option value="tglpinjam" <?=StringIsSelected('tglpinjam',$this->kriteria)?>>Tanggal Peminjaman</option>
				<option value="tglkembali" <?=StringIsSelected('tglkembali',$this->kriteria)?>>Jadwal Pengembalian</option>
				<option value="nis" <?=StringIsSelected('nis',$this->kriteria)?>>NIS Siswa</option>
				<option value="nip" <?=StringIsSelected('nip',$this->kriteria)?>>NIP Pegawai</option>
				<option value="denda" <?=StringIsSelected('denda',$this->kriteria)?>>Denda</option>
				<option value="telat" <?=StringIsSelected('telat',$this->kriteria)?>>Terlambat</option>
            </select>
            </td>
            <td rowspan="2">
				<?php if ($this->kriteria=='telat') { ?>
				<a href="javascript:ViewContent()"><img src="../img/view.png" border="0" /></a>
				<?php } ?>
			</td>
        </tr>
		
<?php 		if ($this->kriteria!='all')
		{
			if ($this->kriteria=='tglpinjam' ||$this->kriteria=='tglkembali' )
			{ ?>
			<tr id="tgl">
				<td width="110" align="right">Periode</td>
				<td>
					<table width="100%" border="0" cellpadding="0">
					<tr>
						<td valign="bottom">
							<input class="inptxt" name="tglAwal" id="tglAwal" type="text"
								   value="<?=$this->tglAwal?>" style="width:100px" readonly="readonly" />&nbsp;
							<a href="javascript:TakeDate('tglAwal')" >&nbsp;
							<img src="../img/ico/calendar.png" width="16" height="16" border="0" />
							</a>
							&nbsp;&nbsp;s.d.&nbsp;&nbsp;
							<input class="inptxt" name="tglAkhir" id="tglAkhir" type="text"
								   value="<?=$this->tglAkhir?>"  style="width:100px" readonly="readonly"/>
							<a href="javascript:TakeDate('tglAkhir')" >&nbsp;
							<img src="../img/ico/calendar.png" width="16" height="16" border="0" />
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
				<td width="110" align="right"><?=strtoupper((string) $this->kriteria)?></td>
				<td colspan="3">
					<input type="hidden" id="statuspeminjam" value="<?=$this->statuspeminjam?>" />
					<input type="text" class="inptxt-small-text" name="noanggota" id="noanggota"
						   readonly="readonly"  onclick="cari()" value="<?=$this->noanggota?>" style="width:150px" />
					&nbsp;
					<input id="nama" class="inptxt-small-text" name="nama" type="text" readonly="readonly"
						   onclick="cari()" value="<?=$this->nama?>" size="50" style="width:200px"/>
					&nbsp;
					<a href="javascript:cari()"><img src="../img/ico/cari.png" width="16" height="16" border="0" /> </a>
				</td>
			</tr>
<?php 			}
	
			if ($this->kriteria=='denda')
			{ ?>
			<tr id="telat">
				<td width="110" align="right">Besar Denda</td>
				<td colspan="3">
				<select name="denda" id="denda" onchange="chgDenda()" >
					<option value="0" <?=StringIsSelected('0',$this->denda)?>>Tanpa Denda</option>
					<option value="1" <?=StringIsSelected('1',$this->denda)?>>< Rp 5.000</option>
					<option value="2" <?=StringIsSelected('2',$this->denda)?>>< Rp 10.000</option>
					<option value="3" <?=StringIsSelected('3',$this->denda)?>>> Rp 10.000</option>
				</select>          	
				</td>
			</tr>
<?php 			}
			
			if ($this->kriteria=='telat')
			{ ?>
			<tr id="tlt">
				<td width="110" align="right" valign="top">Keterlambatan</td>
				<td width="194" valign="top">
					<input type="text" name="telat" id="telat" value="<?=$this->telat?>" class="inptxt" style="width:25px" />
					&nbsp;hari&nbsp;dari&nbsp;tanggal&nbsp;pengembalian          	
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
			<td width='4%' align="center" class="header">No</td>
			<td width='12%' align="center" class="header">Tanggal<br>Pengembalian</td>
            <td width='20%' align="center" class="header">Anggota</td>
            <td width='*' align="center" class="header">Kode Pustaka</td>
            <td width='8%' align="center" class="header">Terlambat</td>
            <td width='8%' align="center" class="header">Denda</td>
            <td width='12%' align="center" class="header">Keterangan</td>
        </tr>
<?php   	if ($num>0)
		{
			$totaldenda=0;
			$cnt = 0;
			while ($row=@mysqli_fetch_array($result))
			{
				$cnt += 1;
				
				$kodepustaka = $row['kodepustaka'];
				$sql = "SELECT p.judul
						  FROM daftarpustaka dp, pustaka p
						 WHERE dp.pustaka = p.replid
						   AND dp.kodepustaka = '".$kodepustaka."'";
				$res2 = QueryDb($sql);
				$row2 = @mysqli_fetch_array($res2);
				$judul = $row2['judul'];
				
				$sql = "SELECT pi.idanggota, pi.kodepustaka, pi.info1
					      FROM pustaka p, daftarpustaka d, pinjam pi
						 WHERE d.pustaka=p.replid
						   AND d.kodepustaka=pi.kodepustaka
						   AND pi.replid='".$row['idpinjam']."'";
				//echo $sql;
				$res = QueryDb($sql);
				$r = @mysqli_fetch_row($res);
				$this->idanggota = $r[0];
				$this->jenisanggota = $r[2];
				
				$NamaAnggota = $this->GetMemberName();
				$totaldenda += $row['denda'];  ?>
				<tr height="25" style="color:<?=$color?>; <?=$weight?>">
					<td align="center"><?=$cnt?></td>
					<td align="center"><?=LongDateFormat($row['tglditerima'])?></td>
					<td align="left">
						<font style='font-size: 9px'><?=$r[0]?></font><br>
						<font style='font-size: 11px; font-weight: bold;'><?=$this->GetMemberName()?></font>
					</td>
					<td align="left">
						<font style='font-size: 9px'><?=$kodepustaka?></font><br>
						<font style='font-size: 11px; font-weight: bold;'><?=$judul?></font>
					</td>
					<td align="center"><?=$row['telat']?> hari</td>
					<td align="right"><?=FormatRupiah($row['denda'])?></td>
					<td align="center"><?=$row['keterangan']?></td>
				</tr>
<?php 	  	}  ?>
			<tr style="color:<?=$color?>; <?=$weight?>">
			    <td height="25" colspan="5" align="right" bgcolor="#CCCCCC">Jumlah&nbsp;&nbsp;</td>
			    <td height="25" align="right" bgcolor="#FFFFFF"><?=FormatRupiah($totaldenda)?></td>
				<td height="25" align="center" bgcolor="#CCCCCC">&nbsp;</td>
			</tr>
<?php   	}
		else
		{  ?>
			<tr>
				<td height="25" colspan="5" align="center" class="nodata">Tidak ada data</td>
			</tr>
<?php     }  ?>	
        </table>
<?php }
	
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