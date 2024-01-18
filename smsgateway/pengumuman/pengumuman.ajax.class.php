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
class PengumumanAjax
{
	function OnStart()
	{
		$op = $_REQUEST['op'];
		
		//Pilih Pegawai
		$bag = $_REQUEST['Bagian'];
				
		//Cari Pegawai
		$NIP 	= $_REQUEST['NIP'];
		$Nama 	= $_REQUEST['Nama'];
		
		//Pilih Siswa
		$dep 	= $_REQUEST['dep'];
		$thn 	= $_REQUEST['thn'];
		$tkt 	= $_REQUEST['tkt'];
		$kls 	= $_REQUEST['kls'];
		
		//Pilih Calon Siswa
		$dep 	= $_REQUEST['dep'];
		$proses	= $_REQUEST['proses'];
		$kelompok = $_REQUEST['kelompok'];
				
		//Cari Siswa
		$NIS 	= $_REQUEST['NIS'];
		
		//Cari Calon Siswa
		$NoCS 	= $_REQUEST['NoCS'];
		
		//Source
		$Source = $_REQUEST['Source'];
		$this->bag = $bag;
		$this->NIP = $NIP;
		$this->Nama = $Nama;
		$this->dep = $dep;
		$this->thn = $thn;
		$this->tkt = $tkt;
		$this->kls = $kls;
		$this->NIS = $NIS;
		$this->NoCS = $NoCS;
		$this->Source = $Source;
		$this->proses = $proses;
		$this->kelompok = $kelompok;
		
		if ($op=='GetTablePegawai')
			$this->GetTablePegawai();
		if ($op=='GetTableSiswa')
			$this->GetTableSiswa();
		if ($op=='GetTableOrtu')
			$this->GetTableOrtu();
		if ($op=='GetFilterSiswa')
			$this->GetFilterSiswa();
		if ($op=='GetFilterOrtu')
			$this->GetFilterOrtu();
		if ($op=='GetPegawai')
			$this->GetPegawai();
		
		if ($op == "GetProsesCalonSiswa")
			$this->GetProsesCalonSiswa("CmbProsesCS", "ChgCmbProsesCS()");
		if ($op == "GetProsesOrtuCalonSiswa")
			$this->GetProsesCalonSiswa("CmbProsesOrtuCS", "ChgCmbProsesOrtuCS()");	
		if ($op == "GetKelompokCalonSiswa")
			$this->GetKelompokCalonSiswa("CmbKelompokCS", "ChgCmbKelompokCS()");
		if ($op == "GetKelompokOrtuCalonSiswa")
			$this->GetKelompokCalonSiswa("CmbKelompokOrtuCS", "ChgCmbKelompokOrtuCS()");	
		if ($op == 'GetTableCalonSiswa')
			$this->GetTableCalonSiswa();
		if ($op == 'GetTableOrtuCalonSiswa')
			$this->GetTableOrtuCalonSiswa();	
	}
	
	function GetTableSiswa()
	{
		global $db_name_akad;
		
		$Nama = $this->Nama;
		$kls = $this->kls;
		$NIS = $this->NIS;
		$Source = $this->Source;
		?>
		<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="TableSis">
		<tr class="Header">
			<td width='50'>No</td>
			<td width="100">NIS</td>
            <td>Nama</td>
			<td width="100">HP Siswa</td>
			<td><input type="checkbox" id="CheckAllSiswa"></td>
		</tr>
<?php 		if ($Source=='Pilih')
			{
				$sql = "SELECT nis, nama, hpsiswa, pinsiswa
						  FROM $db_name_akad.siswa
						 WHERE aktif=1
						   AND idkelas='$kls'";
			}
			else
			{
				$sql = "SELECT nis, nama, hpsiswa, pinsiswa
						  FROM $db_name_akad.siswa
						 WHERE aktif = 1";
				if ($NIS!="" && $NIS!="NIS Siswa")
					$sql .= " AND nis LIKE '%$NIS%'";
				if ($Nama!="" && $Nama!="Nama Siswa")
					$sql .= " AND nama LIKE '%$Nama%'";	
			}
			$sql .= "  ORDER BY nama";	
			$res = QueryDb($sql);
			$num = @mysqli_num_rows($res);
			if ($num>0)
			{
				$cnt=1;
				while ($row = @mysqli_fetch_array($res))
				{
					$hp = trim((string) $row['hpsiswa']);
					if (strlen($hp) < 7)
						continue;
					if (str_starts_with($hp, "#"))
						continue;
			?>
			<tr>
				<td align="center" class="td"><?=$cnt?></td>
				<td class="td" align="center"><?=$row['nis']?></td>
				<td class="td" align="left"><?=$row['nama']?></td>
				<td class="td" align="left"><?=$hp?></td>
				<td class="td" align="center">
					<input type="checkbox" class="checkboxsiswa" hp="<?=$hp?>" nama="<?=$row['nama']?>"
						   nip="<?=$row['nis']?>" pin="<?=$row['pinsiswa']?>">
				</td>
			</tr>
<?php 			$cnt++;
				}
			}
			else
			{
			?>
			<tr>
				<td colspan="5" class="Ket" align="center">Tidak ada data</td>
			</tr>
<?php 		}	?>
		</table>
        <?php
	}
	
	function GetTableCalonSiswa()
	{
		global $db_name_akad;
		
		$Nama = $this->Nama;
		$kelompok = $this->kelompok;
		$NoCS = $this->NoCS;
		$Source = $this->Source; ?>
		
		<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="TableCSis">
		<tr class="Header">
			<td width='50'>No</td>
			<td width='120'>No.Pendaftaran</td>
			<td>Nama</td>
	        <td>HP Calon Siswa</td>
	        <td><input type="checkbox" id="CheckAllCalonSiswa"></td>
		</tr>
<?php 	if ($Source == 'Pilih')
		{
			$sql = "SELECT nopendaftaran, nama, hpsiswa, info3
					  FROM $db_name_akad.calonsiswa
					 WHERE aktif = 1
					   AND idkelompok='$kelompok'";
		}
		else
		{
			$sql = "SELECT nopendaftaran, nama, hpsiswa, info3
					  FROM $db_name_akad.calonsiswa
					 WHERE aktif = 1";
			if ($NoCS != "" && $NoCS != "No Calon Siswa")
				$sql .= " AND nopendaftaran LIKE '%$NoCS%'";
			if ($Nama != "" && $Nama != "Nama Calon Siswa")
				$sql .= " AND nama LIKE '%$Nama%'";	
		}
		$sql .= "  ORDER BY nama";
		
		//echo "$sql";
		$res = QueryDb($sql);
		$num = @mysqli_num_rows($res);
		if ($num > 0)
		{
			$cnt = 1;
			while ($row = @mysqli_fetch_array($res))
			{
				$hp = trim((string) $row['hpsiswa']);
				if (strlen($hp) < 7)
					continue;
				if (str_starts_with($hp, "#"))
					continue; ?>
			<tr>
				<td align="center" class="td"><?=$cnt?></td>
				<td class="td" align="center"><?=$row['nopendaftaran']?></td>
				<td class="td" align="left"><?=$row['nama']?></td>
				<td class="td" align="left"><?=$hp?></td>
				<td class="td" align="center">
					<input type="checkbox" class="checkboxcalonsiswa"
						   hp="<?=$hp?>" nama="<?=$row['nama']?>"
						   nip="<?=$row['nopendaftaran']?>" pin="<?=$row['info3']?>">
				</td>
			</tr>
			
<?php 		$cnt++;
			}
		}
		else
		{	?>
			<tr>
				<td colspan="5" class="Ket" align="center">Tidak ada data</td>
			</tr>
<?php 		}	?>
		</table>
        <?php
	}
	
	function GetTableOrtuCalonSiswa()
	{
		global $db_name_akad;
		
		$Nama = $this->Nama;
		$kelompok = $this->kelompok;
		$NoCS = $this->NoCS;
		$Source = $this->Source; ?>
		
		<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="TableOrCS">
		<tr class="Header">
			<td width='50'>No</td>
			<td width='120'>No.Pendaftaran</td>
			<td>Nama Calon Siswa</td>
			<td>Nama Ortu Calon Siswa</td>
	        <td>HP Ortu Calon Siswa</td>
	        <td><input type="checkbox" id="CheckAllOrtuCS"></td>
		</tr>
<?php 	if ($Source == 'Pilih')
		{
			$sql = "SELECT nopendaftaran, nama, namaayah, hportu, info1, info2, info3
					  FROM $db_name_akad.calonsiswa
					 WHERE aktif = 1
					   AND idkelompok='$kelompok'";
		}
		else
		{
			$sql = "SELECT nopendaftaran, nama, namaayah, hportu, info1, info2, info3
					  FROM $db_name_akad.calonsiswa
					 WHERE aktif = 1";
			if ($NoCS != "" && $NoCS != "No Calon Siswa")
				$sql .= " AND nopendaftaran LIKE '%$NoCS%'";
			if ($Nama != "" && $Nama != "Nama Calon Siswa")
				$sql .= " AND nama LIKE '%$Nama%'";	
		}
		$sql .= "  ORDER BY nama";
		
		//echo "$sql";
		$res = QueryDb($sql);
		$num = @mysqli_num_rows($res);
		if ($num > 0)
		{
			$cnt = 1;
			while ($row = @mysqli_fetch_array($res))
			{
				$n = 0;  
				$hparr = [];
					
				$temp = trim((string) $row['hportu']);
				if (strlen($temp) >= 7 && !str_starts_with($temp, "#"))
				{
					$hparr[$n] = $temp;
					$n += 1;
				}
					
				$temp = trim((string) $row['info1']);  
				if (strlen($temp) >= 7 && !str_starts_with($temp, "#"))
				{
					$hparr[$n] = $temp;
					$n += 1;
				}
					  
				$temp = trim((string) $row['info2']);    
				if (strlen($temp) >= 7 && !str_starts_with($temp, "#"))
					$hparr[$n] = $temp;
					
				$namaortu = $row['namaayah'];
				if (strlen((string) $row['namaayah']) == 0)
					$namaortu = "Ortu " . $row['nama'];
				
				$nama = $row['nama'];	
					
				for($j = 0; $j < count($hparr); $j++)
				{
					$hp = $hparr[$j]; ?>
					<tr>
					    <td align="center" class="td"><?=$cnt?></td>
					    <td class="td" align="center"><?=$row['nopendaftaran']?></td>
					    <td class="td" ><?=$nama?></td>
						<td class="td" ><?=$namaortu?></td>
					    <td class="td"><?=$hp?></td>
					    <td class="td" align="center">
					        <input type="checkbox" class="checkboxortucs" hp="<?=$hp?>"
					               nama="Ortu <?=$nama?>" nip="<?=$row['nopendaftaran']?>" pinayah="<?=$row['info3']?>"
					               pinibu="<?=$row['info3']?>" pin="<?=$row['info3']?>">
					    </td>			
					</tr>
<?php 				$cnt++;	
				} // end for
			} // end while 
		}
		else
		{	?>
			<tr>
				<td colspan="5" class="Ket" align="center">Tidak ada data</td>
			</tr>
<?php 		}	?>
		</table>
        <?php
	}
	
	function GetTableOrtu()
	{
		global $db_name_akad;
				
		$Nama = $this->Nama;
		$kls = $this->kls;
		$NIS = $this->NIS;
		$Source = $this->Source;
		?>
		<link href="style/style.css" rel="stylesheet" type="text/css" />
		
		<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="TableOr">
		  <tr class="Header">
			<td width='50'>No</td>
			<td width='100'>NIS</td>
			<td>Nama Siswa</td>
			<td>Nama Ortu Siswa</td>
			<td>HP Ortu Siswa</td>
			<td><input type="checkbox" id="CheckAllOrtu"></td>
		  </tr>
		  <?php
			if ($Source=='Pilih')
			{
				$sql = "SELECT * FROM $db_name_akad.siswa WHERE aktif=1 AND idkelas='$kls'";
			}
			else
			{
				$sql = "SELECT * FROM $db_name_akad.siswa WHERE replid>0";
				if ($NIS!="" && $NIS!="NIS Siswa")
					$sql .= " AND nis LIKE '%$NIS%'";
				if ($Nama!="" && $Nama!="Nama Siswa")
					$sql .= " AND nama LIKE '%$Nama%'";	
			}
			$sql .= " ORDER BY nama";
			//echo $sql;
			$res = QueryDb($sql);
			$num = @mysqli_num_rows($res);
			if ($num>0)
			{
				$cnt = 1;
				while ($row = @mysqli_fetch_array($res))
				{
					$n = 0;
					$hparr = [];
					
					$temp = trim((string) $row['hportu']);
					if (strlen($temp) >= 7 && !str_starts_with($temp, "#"))
					{
						$hparr[$n] = $temp;
						$n += 1;
					}
				
					$temp = trim((string) $row['info1']);  
					if (strlen($temp) >= 7 && !str_starts_with($temp, "#"))
					{
						$hparr[$n] = $temp;
						$n += 1;
					}
				  
					$temp = trim((string) $row['info2']);    
					if (strlen($temp) >= 7 && !str_starts_with($temp, "#"))
						$hparr[$n] = $temp;
						
					$namaortu = $row['namaayah'];
					if (strlen((string) $row['namaayah']) == 0)
						$namaortu = $row['nama'];
					
					$nama = $row['nama'];
					
					for($j = 0; $j < count($hparr); $j++)
					{
						$hp = $hparr[$j]; ?>	
						<tr>
							<td align="center" class="td"><?=$cnt?></td>
							<td class="td" align='center'><?=$row['nis']?></td>
							<td class="td"><?=$nama?></td>
							<td class="td"><?=$namaortu?></td>
							<td class="td"><?=$hp?></td>
							<td class="td" align="center">
								<input type="checkbox" class="checkboxortu" hp="<?=$hp?>" nama="Ortu <?=$nama?>" nip="<?=$row['nis']?>"
									   pinayah="<?=$row['pinsiswa']?>" pinibu="<?=$row['pinsiswa']?>">
							</td>
						</tr>
<?php 					$cnt++;
					} // end for
				} // end while
			} else {
			?>
		  <tr>
			<td colspan="5" class="Ket" align="center">Tidak ada data</td>
		  </tr>
		  <?php
			}
				?>
		</table>
        <?php
	}

	function GetFilterSiswa()
	{
		global $db_name_akad;
		global $db_name_sdm;
		global $db_name_fina;
		global $db_name_user;
		
		$dep = $this->dep; ?>
		
		<select id="CmbKlsSis" name="CmbKlsSis" class="Cmb" onchange="ChgCmbKlsSis()">
<?php 	$sql = "SELECT k.replid,k.kelas
				  FROM $db_name_akad.kelas k, $db_name_akad.tingkat ti, $db_name_akad.tahunajaran ta
				 WHERE k.aktif=1
				   AND ta.aktif=1
				   AND ti.aktif=1
				   AND k.idtahunajaran=ta.replid
				   AND k.idtingkat=ti.replid
				   AND ta.departemen='$dep'
				   AND ti.departemen='$dep'
				 ORDER BY k.kelas"; 
		$res = QueryDb($sql);
		while ($row = @mysqli_fetch_row($res))
		{
			if ($kls == "")
				$kls = $row[0];	?>
			<option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$kls)?>><?=$row[1]?></option>
<?php 	}	?>
		</select>
        <?php
	}
	
	function GetProsesCalonSiswa($elname, $actname)
	{
		global $db_name_akad;
		
		$dep = $this->dep; ?>
		
		<select id="<?=$elname?>" name="<?=$elname?>" class="Cmb" onchange="<?=$actname?>">
<?php 	$sql = "SELECT replid, proses
				  FROM $db_name_akad.prosespenerimaansiswa
				 WHERE departemen = '$dep'
				   AND aktif = 1
				 ORDER BY proses"; 
		$res = QueryDb($sql);
		while ($row = @mysqli_fetch_row($res))
		{	?>
            <option value="<?=$row[0]?>"><?=$row[1]?></option>
<?php 	}	?>
		</select>
        <?php
	}
	
	function GetKelompokCalonSiswa($elname, $actname)
	{
		global $db_name_akad;
		
		$proses = $this->proses ?>
		
		<select id="<?=$elname?>" name="<?=$elname?>" class="Cmb" onchange="<?=$actname?>">
<?php 	$sql = "SELECT replid, kelompok
                  FROM $db_name_akad.kelompokcalonsiswa
		  	     WHERE idproses = '$proses'
				 ORDER BY kelompok"; 
        $res = QueryDb($sql);
		while ($row = @mysqli_fetch_row($res))
        {	?>
            <option value="<?=$row[0]?>"><?=$row[1]?></option>
<?php 	}	?>
		</select>
        <?php
	}

	function GetFilterOrtu(){
		global $db_name_akad;
		global $db_name_sdm;
		global $db_name_fina;
		global $db_name_user;
		
		$dep = $this->dep;
		
		?>
				<select id="CmbKlsOrtu" name="CmbKlsOrtu" class="Cmb" onchange="ChgCmbKlsOrtu()">
					<?php
					$sql = "SELECT k.replid,k.kelas FROM $db_name_akad.kelas k, $db_name_akad.tingkat ti, $db_name_akad.tahunajaran ta ".
						   "WHERE k.aktif=1 AND ta.aktif=1 AND ti.aktif=1 AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid ".
						   "AND ta.departemen='$dep' AND ti.departemen='$dep' ORDER BY k.kelas"; 
					$res = QueryDb($sql);
					while ($row = @mysqli_fetch_row($res)){
						if ($kls=="")
							$kls=$row[0];
					?>
				<option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$kls)?>><?=$row[1]?></option>
					<?php
					}
					?>
				</select>
        <?php
	}
	
	function GetPegawai(){
		global $db_name_akad;
		global $db_name_sdm;
		global $db_name_fina;
		global $db_name_user;
		
		$bag = $this->bag;
		$NIP = $this->NIP;
		$Nama = $this->Nama;
		$Source = $this->Source;
		if ($bag=="")
			$bag='-1';
		if ($Source=='' || $Source=='Pilih'){
			$DispA = "style='display:block'";
			$DispB = "style='display:none'";
		} elseif ($Source=='Cari'){
			$DispA = "style='display:none'";
			$DispB = "style='display:block'";
		}
		?>
		<link href="style/style.css" rel="stylesheet" type="text/css" />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td style="padding:5px">
			<div style="width:230px; background-color:#CCC; height:18px; font-weight:bold; padding-top:4px; cursor:default; border:1px #666 solid" align="center" onclick="document.getElementById('PegSelect').style.display='block';document.getElementById('PegSearch').style.display='none';">Pilih Pegawai	</div>
			<div id="PegSelect" <?=$DispA?>>
				<table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td style="padding-right:4px">Bagian</td>
					<td class="td">
					  <select id="CmbBagPeg" name="CmbBagPeg" class="Cmb" onchange="ChgCmbBagPeg(this.value)">
						<option value="-1" <?=StringIsSelected('-1',$bag)?>>- Semua Bagian -</option>
						<?php
							$sql = "SELECT bagian FROM $db_name_sdm.bagianpegawai";
							$res = QueryDb($sql);
							while ($row = @mysqli_fetch_row($res)){
							?>
						<option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$bag)?>><?=$row[0]?></option>
						<?php
							}
							?>
						</select>
					  </td>
					</tr>
				</table>
			</div>
			<div style="width:230px; background-color:#CCC; height:18px; font-weight:bold; padding-top:4px; cursor:default; border:1px #666 solid; margin-top:3px" align="center" onclick="document.getElementById('PegSearch').style.display='block';document.getElementById('PegSelect').style.display='none';document.getElementById('InpNIPPeg').focus()">Cari Pegawai</div>
			<div id="PegSearch" <?=$DispB?>>
				<table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td style="padding-right:4px" valign="top" class="tdTop">NIP</td>
					<td class="td">
						<input type="text" name="InpNIPPeg" id="InpNIPPeg" class="InputTxt" value="<?=$NIP?>" />
						<div id="ErrInpNIPPeg" class="ErrMsg"></div>  
					</td>
					<td rowspan="2" align="center" valign="middle" class="td"><img src="images/ico/lihat.png" width="20" height="20" style="cursor:pointer" onclick="SearchPeg()" /></td>
				  </tr>
				  <tr>
					<td style="padding-right:4px" valign="top" class="tdTop">Nama</td>
					<td class="td">
						<input type="text" name="InpNamaPeg" id="InpNamaPeg" class="InputTxt" value="<?=$Nama?>" />  
						<div id="ErrInpNamaPeg" class="ErrMsg"></div>
					</td>
				  </tr>
				</table>
			</div>
			</td>
		  </tr>
		  <tr>
			<td>
			<div id="TablePegawai">
			<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab">
			  <tr class="Header">
				<td width='50'>No</td>
				<td width='100'>NIP</td>
				<td>Nama</td>
				<td>No. Ponsel</td>
				<td><input type="checkbox" id="CheckAllPegawai"></td>
			  </tr>
			  <?php
				if ($bag=='-1')
					$sql = "SELECT * FROM $db_name_sdm.pegawai ORDER BY nama";
				else
					$sql = "SELECT * FROM $db_name_sdm.pegawai WHERE bagian='$bag' ORDER BY nama";
				$res = QueryDb($sql);
				$num = @mysqli_num_rows($res);
				if ($num>0){
					$cnt=1;
					while ($row = @mysqli_fetch_array($res)){
			  ?>
			  <tr>
				<td align="center" class="td"><?=$cnt?></td>
				<td class="td" align='center'><?=$row['nip']?></td>
				<td class="td"><?=$row['nama']?></td>
				<td class="td"><?=$row['handphone']?></td>
				<td class="td" align="center">
				<?php if (strlen((string) $row['handphone'])>0){ ?>
				<input type="checkbox" class="checkboxpegawai" hp="<?=$row['handphone']?>" nama="<?=$row['nama']?>" nip="<?=$row['nip']?>"  pin="<?=$row['pinpegawai']?>">
				<!--span style="cursor:pointer" class="Link" onclick="InsertNewReceipt('<?=$row['handphone']?>','<?=$row['nama']?>','<?=$row['nip']?>')" align="center" />Pilih</span-->
				<?php } ?>
				</td>
			  </tr>
			  <?php
					$cnt++;
					}
				} else {
				?>
			  <tr>
				<td colspan="4" class="Ket" align="center">Tidak ada data</td>
			  </tr>
			  <?php
				}
					?>
			</table>
			</div>
			</td>
		  </tr>
		</table>
        <?php
	}
	
	function GetSiswa(){
		global $db_name_akad;
		global $db_name_sdm;
		global $db_name_fina;
		global $db_name_user;
		?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="padding:5px">
            <div style="width:400px; background-color:#CCC; height:18px; font-weight:bold; padding-top:4px; cursor:default; border:1px #666 solid" align="center" onclick="document.getElementById('SisSelect').style.display='block';document.getElementById('SisSearch').style.display='none';">Pilih Siswa	</div>
            <div id="SisSelect" <?=$DispA?>>
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style="padding-right:4px">Departemen</td>
                    <td class="td">
                      <select id="CmbDepSis" name="CmbDepSis" class="Cmb" onchange="ChgCmbDepSis()">
                            <?php
                            $sql = "SELECT departemen FROM $db_name_akad.departemen WHERE aktif=1 ORDER BY urutan";
                            $res = QueryDb($sql);
                            while ($row = @mysqli_fetch_row($res)){
                            if ($dep=="")
                                $dep=$row[0];
                            ?>
                        <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$dep)?>><?=$row[0]?></option>
                            <?php
                            }
                            ?>
                        </select>
                      </td>
                    <td class="td">Tingkat</td>
                    <td class="td">
                        <select id="CmbTktSis" name="CmbTktSis" class="Cmb" onchange="ChgCmbTktThnSis()">
                            <?php
                            $sql = "SELECT replid,tingkat FROM $db_name_akad.tingkat WHERE aktif=1 AND departemen='$dep'";
                            $res = QueryDb($sql);
                            while ($row = @mysqli_fetch_row($res)){
                            if ($tkt=="")
                                $tkt=$row[0];
                            ?>
                        <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$tkt)?>><?=$row[1]?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    </tr>
                  <tr>
                    <td style="padding-right:4px">Tahun Ajaran</td>
                    <td class="td">
                        <select id="CmbThnSis" name="CmbThnSis" class="Cmb" onchange="ChgCmbTktThnSis()">
                            <?php
                            $sql = "SELECT replid,tahunajaran FROM $db_name_akad.tahunajaran WHERE aktif=1 AND departemen='$dep'";
                            $res = QueryDb($sql);
                            while ($row = @mysqli_fetch_row($res)){
                            if ($thn=="")
                                $thn=$row[0];
                            ?>
                        <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$thn)?>><?=$row[1]?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td class="td">Kelas</td>
                    <td class="td">
                        <select id="CmbKlsSis" name="CmbKlsSis" class="Cmb" onchange="ChgCmbKlsSis()">
                            <?php
                            $sql = "SELECT replid,kelas FROM $db_name_akad.kelas WHERE aktif=1 AND idtahunajaran='$thn' AND idtingkat='$tkt' ";
                            $res = QueryDb($sql);
                            while ($row = @mysqli_fetch_row($res)){
                            if ($kls=="")
                                $kls=$row[0];
                            ?>
                        <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$kls)?>><?=$row[1]?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                  </tr>
                </table>
            </div>
            <div style="width:400px; background-color:#CCC; height:18px; font-weight:bold; padding-top:4px; cursor:default; border:1px #666 solid; margin-top:3px" align="center" onclick="document.getElementById('SisSearch').style.display='block';document.getElementById('SisSelect').style.display='none';">Cari Siswa</div>
            <div id="SisSearch" style="display:none">
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style="padding-right:4px" valign="top" class="tdTop">NIS</td>
                    <td class="td">
                        <input type="text" name="InpNISSis" id="InpNISSis" class="InputTxt" value="<?=$NIS?>" />
                        <div id="ErrInpNISSis" class="ErrMsg"></div>  
                    </td>
                    <td rowspan="2" align="center" valign="middle" class="td"><img src="images/ico/lihat.png" width="20" height="20" style="cursor:pointer" onclick="SearchSis()" /></td>
                  </tr>
                  <tr>
                    <td style="padding-right:4px" valign="top" class="tdTop">Nama</td>
                    <td class="td">
                        <input type="text" name="InpNamaSis" id="InpNamaSis" class="InputTxt" value="<?=$Nama?>" />  
                        <div id="ErrInpNamaSis" class="ErrMsg"></div>
                    </td>
                  </tr>
                </table>
            </div>
            </td>
          </tr>
          <tr>
            <td>
            <div id="TableSiswa">
            <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab">
              <tr class="Header">
                <td width='50'>No</td>
                <td width='100'>NIS</td>
				<td>Nama</td>
                <td>No. Ponsel Siswa</td>
                <td>&nbsp;</td>
              </tr>
              <?php
                $sql = "SELECT * FROM $db_name_akad.siswa WHERE aktif=1 AND idkelas='$kls' ORDER BY nama";
                $res = QueryDb($sql);
                $num = @mysqli_num_rows($res);
                if ($num>0){
                    $cnt=1;
                    while ($row = @mysqli_fetch_array($res)){
              ?>
              <tr>
                <td align="center" class="td"><?=$cnt?></td>
                <td class="td" align='center'><?=$row['nis']?></td>
				<td class="td"><?=$row['nama']?></td>
                <td class="td"><?=$row['hpsiswa']?></td>
                <td class="td" align="center">
                <?php if (strlen((string) $row['hpsiswa'])>0){ ?>
                <span style="cursor:pointer" align="center" class="Link" onclick="InsertNewReceipt2('<?=$row['hpsiswa']?>_<?=$row['hportu']?>','<?=$row['nama']?>_<?=$row['namaayah']?>','<?=$row['nis']?>')"  />Pilih</span>
                <?php } ?>
                </td>
              </tr>
              <?php
                    $cnt++;
                    }
                } else {
                ?>
              <tr>
                <td colspan="5" class="Ket" align="center">Tidak ada data</td>
              </tr>
              <?php
                }
                    ?>
            </table>
            </div>
            </td>
          </tr>
        </table>
    <?php
	}
	
	function GetTablePegawai(){
		global $db_name_akad;
		global $db_name_sdm;
		global $db_name_fina;
		global $db_name_user;		
		
		$bag = $this->bag;
		$NIP = $this->NIP;
		$Nama = $this->Nama;
		$Source = $this->Source;
		if ($bag=="")
			$bag='-1';
		?>
		<link href="style/style.css" rel="stylesheet" type="text/css" />
		<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="TablePeg">
		  <tr class="Header">
			<td width='50'>No</td>
			<td width='100'>NIP</td>
			<td>Nama</td>
			<td>No. Ponsel</td>
			<td><input type="checkbox" id="CheckAllPegawai"></td>
		  </tr>
		  <?php
			if ($Source=='Pilih'){
				if ($bag=='-1')
					$sql = "SELECT * FROM $db_name_sdm.pegawai ORDER BY nama";
				else
					$sql = "SELECT * FROM $db_name_sdm.pegawai WHERE bagian='$bag' ORDER BY nama";
			} else {
				$sql = "SELECT * FROM $db_name_sdm.pegawai WHERE replid>0";
				if ($NIP!="" && $NIP!="NIP Pegawai")
					$sql .= " AND nip LIKE '%$NIP%'";
				if ($Nama!="" && $Nama!="Nama Pegawai")
					$sql .= " AND nama LIKE '%$Nama%'";	
			}
			//echo $sql;
			$res = QueryDb($sql);
			$num = @mysqli_num_rows($res);
			if ($num>0){
				$cnt=1;
				while ($row = @mysqli_fetch_array($res)){
		  ?>
		  <tr>
			<td align="center" class="td"><?=$cnt?></td>
			<td class="td" align='center'><?=$row['nip']?></td>
			<td class="td"><?=$row['nama']?></td>
			<td class="td"><?=$row['handphone']?></td>
			<td class="td" align="center">
			<?php if (strlen((string) $row['handphone'])>0){ ?>
			<!--span style="cursor:pointer" class="Link" onclick="InsertNewReceipt('<?=$row['handphone']?>','<?=$row['nama']?>','<?=$row['nip']?>')" align="center"  />Pilih</span-->
			<input type="checkbox" class="checkboxpegawai" hp="<?=$row['handphone']?>" nama="<?=$row['nama']?>" nip="<?=$row['nip']?>" pin="<?=$row['pinpegawai']?>">
			<?php } ?>
			</td>
		  </tr>
		  <?php
				$cnt++;
				}
			} else {
			?>
		  <tr>
			<td colspan="4" class="Ket" align="center">Tidak ada data</td>
		  </tr>
		  <?php
			}
				?>
		</table>
		<?php
	}
}
?>