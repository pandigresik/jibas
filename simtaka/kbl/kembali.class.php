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
class CKembali
{
	function GetDenda($anggota)
	{
		$sql = "SELECT * FROM konfigurasi";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_array($result);
		return $row['denda'];
	}
	
	function OnStart()
	{
		$this->kodepustaka = trim((string) $_REQUEST['kodepustaka']);
		
		$sql = "SELECT DATE_FORMAT(now(), '%Y-%m-%d')";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_row($result);
		$this->datenow = $row[0];
			
		$this->op=$_REQUEST['op'];
		$this->num=0;
		if ($this->op=="ViewPeminjaman")
		{
			$sql = "SELECT p.*, dp.status
					  FROM pinjam p, daftarpustaka dp
					 WHERE p.kodepustaka = dp.kodepustaka
					   AND (dp.kodepustaka = '" . $this->kodepustaka . "' OR dp.info1 = '" . $this->kodepustaka . "')
					   AND p.status=1";
			//echo $sql;
			$result = QueryDb($sql);
			if ($this->kodepustaka=="Tidak ada Kode Pustaka yang sedang dipinjam")
				$this->nothing=1;
			else
				$this->nothing=0;
				
			$this->num = @mysqli_num_rows($result);
			$row = @mysqli_fetch_array($result);
			
			$this->idpinjam = $row['replid'];
			$this->tglpinjam = $row['tglpinjam'];
			$this->tglkembali = $row['tglkembali'];
			$this->keterangan = $row['keterangan'];
			$this->idanggota = $row['idanggota'];
			$this->jenisanggota = $row['info1'];
			$this->kodepustaka = $row['kodepustaka'];
			$this->namaanggota = $this->GetMemberName();
			$this->isavailable = (0 == (int)$row['status']) ? 1 : -1;
			
			$sql = "SELECT DATEDIFF(NOW(),'".$this->tglkembali."')";
			$result = QueryDb($sql);
			$row = @mysqli_fetch_row($result);
			if ($row[0] > 0)
			{
				$this->denda = $row[0] * (int)$this->GetDenda();
				$this->telat = $row[0];
			}
			else
			{
				$this->denda = 0;
				$this->telat = 0;
			}
			
			$sql = "SELECT judul
					  FROM pustaka p, daftarpustaka d
					 WHERE d.pustaka=p.replid
					   AND d.kodepustaka = '" . $this->kodepustaka . "'";
			$result = QueryDb($sql);
			$row = @mysqli_fetch_array($result);
			$this->judul = $row['judul'];
		}
		
		if ($this->op=="KembalikanPustaka")
		{
			$this->denda = $_REQUEST['denda'];
			$this->idpinjam = $_REQUEST['idpinjam'];
			$this->telat = $_REQUEST['telat'];
			$this->keterangan = CQ($_REQUEST['keterangan']);
			
			$sql = "UPDATE pinjam
					   SET status=2, tglditerima=NOW(), keterangan='$this->keterangan'
					 WHERE replid='".$this->idpinjam."'";
			QueryDb($sql);
			
			$sql = "UPDATE daftarpustaka
					   SET status=1
					 WHERE kodepustaka='".$this->kodepustaka."'";
			QueryDb($sql);

            if($this->denda!=0)
            {
                $sql = "INSERT INTO denda
                           SET idpinjam='" . $this->idpinjam . "', denda='" . $this->denda . "', telat='" . $this->telat . "'";
                QueryDb($sql);
            }

			$this->ReloadPage();
		}
	}
	
	function ReloadPage()
	{
		?>
		<script language='JavaScript'>
			document.location.href="kembali.php";
		</script>
		<?php
	}
	
	function OnLoad()
	{
		$onload="onload=\"document.getElementById('kodepustaka').focus()\"";
		if (isset($_REQUEST['op']) && $this->op=="ViewPeminjaman")
		{
			if ($this->num!=0)
			{
				$onload="onload=\"document.getElementById('BtnKembali').focus()\"";	
			}
		}
		return ($onload);
	}
	
	function OnFinish()
	{
		//
    }
	
    function Content()
	{
		?>
		<input type="hidden" id="source" value="<?=$this->source?>" />
        <table width="100%" border="0" cellspacing="2" cellpadding="0">
        <tr>
            <td>
            	<fieldset style="background-color: #d3f3e2; border-width: 1px; border-color: #ececec;">
					<legend class="welc" style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI'">Kode Pustaka</legend>
   					<table width="100%" border="0" cellspacing="5" cellpadding="0">
                    <tr>
                        <td width="10%" align="right"><strong>Kode Pustaka</strong></td>
                        <td width="90%">
							<input name="kodepustaka" type="text" id="kodepustaka"
								   value="<?=$this->kodepustaka?>"
								   onkeyup="return OnEnterKodePustaka(event)" size="35"
                                    style="font-size: 18px;"/>&nbsp;
							<input type="button" class="cmbfrm2" value="  cari  " style="font-size: 14px; background-color: #4285da; color: #fff; height: 28px;" onclick="ProsesKode()" /><br>
							<font style='font-size: 10px; color: blue;'>
								masukkan kode pustaka atau scan barcode
							</font>
						</td>
                    </tr>
                    </table>
                </fieldset>
			</td>
        </tr>
<?php   	if ($this->op=="ViewPeminjaman")
		{
			if ($this->nothing==0)
			{
			  	if ($this->num>0)
				{  ?>
				<tr>
					<td>
                        <br>
						<fieldset style="background-color: #e8fcff; border-width: 1px; border-color: #ececec;">
							<legend class="welc" style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI'">Informasi Peminjaman</legend>
							<table width="100%" border="0" cellspacing="4" cellpadding="2">
							<tr>
								<td width="10%" align="right">Anggota</td>
								<td width="90%">
                                    <input name="anggota" id="anggota" type="text" value="<?=$this->idanggota?> - <?=$this->namaanggota?>" size="50"
                                           readonly="readonly" style="font-size: 16px; height: 25px;" />
                                </td>
							</tr>
							<tr>
								<td align="right">Judul&nbsp;Pustaka</td>
								<td><div id="title" class="btnfrm" style="height:25px; font-size: 16px;  background-color: #fff;">&nbsp;<?=$this->judul?></div></td>
							</tr>
							<tr>
								<td align="right">Tanggal&nbsp;Peminjaman</td>
								<td><input name="tglpinjam" id="tglpinjam" type="text" value="<?=LongDateFormat($this->tglpinjam)?>" readonly="readonly" /></td>
							</tr>
							<tr>
								<td align="right">Jadwal Pengembalian</td>
								<td>
                                    <input name="tglkembali" id="tglkembali" type="text" value="<?=LongDateFormat($this->tglkembali)?>" readonly="readonly" />
                                </td>
							</tr>
                            <tr>
                                <td align="right">Tanggal&nbsp;Penerimaan</td>
                                <td>
                                    <input name="tglnow" id="tglnow" type="text" value="<?=LongDateFormat($this->datenow)?>" readonly="readonly" />
                                </td>
                            </tr>
							<tr>
								<td align="right">Denda</td>
								<td>
									<input name="dendanya" id="dendanya" type="text" value="<?=FormatRupiah($this->denda)?>" onfocus="unformatRupiah('dendanya')" onblur="formatRupiah('dendanya')" onkeyup="Copy('dendanya','denda')" />
									<input name="denda" id="denda" type="hidden" value="<?=$this->denda?>" />
									<input name="idpinjam" id="idpinjam" type="hidden" value="<?=$this->idpinjam?>" />
									<input name="telat" id="telat" type="hidden" value="<?=$this->telat?>" />
								</td>
							</tr>
							<tr>
								<td align="right">Keterangan</td>
								<td><textarea name="keterangan" id="keterangan" class="btnfrm" cols="100"></textarea></td>
							</tr>
							</table>
						</fieldset>
					</td>
				</tr>
				<tr>
				<td>
                    <br>

						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td align="center">
<?php 							if ($this->isavailable == 1)
							{ ?>
								<input id="BtnKembali" type="button" class="cmbfrm2" value="   Terima   "
                                       style="width: 120px; height: 30px; background-color: #0a931e;  color: #fff; font-size: 14px;"
                                       onclick="Kembalikan()"  />&nbsp;
								<input name="" type="button" class="cmbfrm2"
                                       style="width: 120px; height: 30px; background-color: #6e2e16; color: #fff; font-size: 14px;"
                                       value="Batal" onclick="BatalkanPengembalian()" />
<?php 							}
							else
							{ ?>
								<font style='color: red; font-weight: bold;'>Status buku ini tidak sedang dipinjam</font>
<?php 							} ?>
							</td>
						</tr>
						</table>

				</td>
				</tr>
<?php 			}
				else
				{  ?>
				<tr>
					<td height="30" align="center" class="err">
						Pustaka dengan kode pustaka <?=$this->kodepustaka?> tidak sedang dipinjam
					</td>
				</tr>
<?php         		}
			}
		}  ?>
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