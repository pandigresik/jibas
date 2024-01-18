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
class CPustaka
{
	function OnStart()
	{
		$this->replid = $_REQUEST['replid'];
		$this->asal = $_REQUEST['asal'];
		$this->perpustakaan = $_REQUEST['perpustakaan'];
		$this->kategori = $_REQUEST['kategori'];
		$this->keyword = $_REQUEST['keyword'];
		
		$sql = "SELECT * FROM pustaka WHERE replid=".$this->replid;
		$result = QueryDb($sql);
		$row = @mysqli_fetch_array($result);
		$this->judul = stripslashes((string) $row['judul']);
		$this->harga = $row['harga'];
		$this->katalog = $row['katalog'];
		$this->penerbit = $row['penerbit'];
		$this->penulis = $row['penulis'];
		$this->tahun = $row['tahun'];
		$this->format = stripslashes((string) $row['format']);
		$this->keyword = stripslashes((string) $row['keyword']);
		$this->keteranganfisik = stripslashes((string) $row['keteranganfisik']);
		$this->abstraksi = stripslashes((string) $row['abstraksi']);
		$this->keterangan = stripslashes((string) $row['keterangan']);
		//echo $this->katalog."<hr>";
		//echo $this->penulis."<hr>";
		if (isset($_REQUEST['simpan']))
			$this->save();
	}
	
	function reload_page()
	{
		$this->replid = $_REQUEST['replid'];
		?>
		<script language='JavaScript'>
			document.location.href="pustaka.<?=$this->asal?>.php?replid=<?=$this->replid?>&perpustakaan=<?=$this->perpustakaan?>&kategori=<?=$this->kategori?>&keyword=<?=$this->keyword?>&cari";
        </script>
		<?php
	}
	
	function OnFinish()
	{
		//
    }
	
	function GetKatalog()
	{	
		$sql = "SELECT MAX(LENGTH(kode))
			      FROM katalog";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$maxlen = $row[0];	?>
		
		<select name="katalog" id="katalog" class="cmbfrm" style="width:100%; font-family:'Courier New'; font-size: 14px;">
<?php      $sql = "SELECT replid, kode, nama
			      FROM katalog
				 ORDER BY nama";
		$result = QueryDb($sql);
		while ($row = @mysqli_fetch_row($result))
		{
			$len = strlen(trim((string) $row[1]));
			$space = $this->GetSpace($maxlen, $len);
			
			if ($this->katalog=="")
				$this->katalog = $row[0];	?>
			<option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $this->katalog)?>>
				<?= "$space$row[1] - $row[2]" ?>
            </option>
<?php 	} ?>
		</select>
		<?php
	}
	
	function GetPenulis()
	{
		$sql = "SELECT MAX(LENGTH(kode))
			      FROM penulis";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$maxlen = $row[0];	?>
		
		<select name="penulis" id="penulis" class="cmbfrm" style="width:100%; font-family:'Courier New'; font-size: 14px;">
			
<?php 	$sql = "SELECT replid, kode, nama
			      FROM penulis
				 ORDER BY nama";
		$result = QueryDb($sql);
		while ($row = @mysqli_fetch_row($result))
		{
			$len = strlen(trim((string) $row[1]));
			$space = $this->GetSpace($maxlen, $len);
			
			if ($this->penulis=="")
				$this->penulis = $row[0]; ?>
				
			<option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $this->penulis)?>>
				<?= "$space$row[1] - $row[2]" ?>
            </option>
			
<?php 	}	?>
		</select>
		<?php
	}
	
	function GetPenerbit()
	{
		$sql = "SELECT MAX(LENGTH(kode))
			      FROM penerbit";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$maxlen = $row[0];	?>
		
		<select name="penerbit" id="penerbit" class="cmbfrm" style="width:100%; font-family:'Courier New'; font-size: 14px;">
			
<?php      $sql = "SELECT replid, kode, nama
				  FROM penerbit
				 ORDER BY nama";
		$result = QueryDb($sql);
		while ($row = @mysqli_fetch_row($result))
		{
			$len = strlen(trim((string) $row[1]));
			$space = $this->GetSpace($maxlen, $len);
			if ($this->penerbit=="")
				$this->penerbit = $row[0];	?>
			<option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $this->penerbit)?>>
				<?= "$space$row[1] - $row[2]" ?>
			</option>
<?php 	}	?>
		</select>
		<?php
	}
	
	function GetFormat()
	{
		$sql = "SELECT MAX(LENGTH(kode))
			      FROM format";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$maxlen = $row[0]; ?>
		
		<select name="format" id="format" class="cmbfrm" style="width:100%; font-family:'Courier New'; font-size: 14px;">
			
<?php 	$sql = "SELECT replid, kode, nama
				  FROM format
				 ORDER BY nama";
		$result = QueryDb($sql);
		
		while ($row = @mysqli_fetch_row($result))
		{
			$len = strlen(trim((string) $row[1]));
			$space = $this->GetSpace($maxlen, $len);
			
			if ($this->format == "")
				$this->format = $row[0];	?>
				
			<option value="<?=$row[0]?>" <?=IntIsSelected($row[0],$this->format)?>>
				<?= "$space$row[1] - $row[2]" ?>
			</option>
			
<?php 	}	?>
		</select>
		<?php
	}
	
    function Content()
	{
		?>
        <form action="pustaka.ubah.php" method="post" onsubmit="return validate(<?=$this->CountPustaka()?>)" enctype="multipart/form-data">
		<input name="replid" type="hidden" class="inputtxt" id="replid" value="<?=$this->replid?>"/>
		<input name="asal" type="hidden" class="inputtxt" id="asal" value="<?=$this->asal?>"/>
		<input name="perpustakaan" type="hidden" class="inputtxt" id="perpustakaan" value="<?=$this->perpustakaan?>"/>
		<input name="kategori" type="hidden" class="inputtxt" id="kategori" value="<?=$this->kategori?>"/>
		<input name="keyword" type="hidden" class="inputtxt" id="keyword" value="<?=$this->keyword?>"/>
		<input name="katalogasli" type="hidden" class="inputtxt" id="katalogasli" value="<?=$this->katalog?>"/>
		<table width="100%" border="0" cellspacing="2" cellpadding="5">
          <tr>
            <td width="60%" valign="top">
                <fieldset style="background-color: #e8fcff; border-width: 1px; border-color: #ececec;">
                    <legend style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI'">&nbsp;Informasi&nbsp;</legend>
                        <table width="90%" border="0" cellspacing="5" cellpadding="2">
                        <tr>
                            <td width="10%" align="right" valign="top">&nbsp;<strong>Judul</strong></td>
                            <td colspan="2"><textarea name="judul" cols="60" rows="2" class="areatxt2" id="judul" style="font-size: 16px;"><?=$this->judul?></textarea></td>
                        </tr>
                        <tr>
                            <td align="right">&nbsp;<strong>Harga&nbsp;Satuan</strong></td>
                            <td colspan="2">
                                <input name="harga" type="text" class="inputtxt" id="harga" value="<?=FormatRupiah($this->harga)?>"
                                       onfocus="unformatRupiah('harga')" onblur="formatRupiah('harga')"
                                       style="height: 28px; font-size: 16px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">&nbsp;<strong>Katalog</strong></td>
                            <td colspan="2">
                                <?=$this->GetKatalog()?>
						    </td>
                        </tr>
                        <tr>
                            <td align="right">&nbsp;<strong>Penulis</strong></td>
                            <td colspan="2">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td><div id="PenulisInfo"><?=$this->GetPenulis()?></div></td>
                                    <td width="18" align="right"><a href="javascript:ManagePenulis()"><img src="../img/ico/tambah.png" width="16" height="16" border="0" /></a></td>
                                </tr>
                                </table>
						    </td>
                        </tr>
                        <tr>
                            <td align="right"><strong>&nbsp;Penerbit</strong></td>
                            <td colspan="2">
							    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td><div id="PenerbitInfo"><?=$this->GetPenerbit()?></div></td>
                                    <td width="18" align="right"><a href="javascript:ManagePenerbit()"><img src="../img/ico/tambah.png" width="16" height="16" border="0" /></a></td>
                                </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">&nbsp;<strong>Tahun&nbsp;Terbit</strong></td>
                            <td width="27%">
							    <input name="tahun" type="text" class="inputtxt" id="tahun" size='5' maxlength="4" value="<?=$this->tahun?>" style="height: 28px; font-size: 16px;" />
						    </td>
                            <td width="49%">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="right"><strong>Format&nbsp;</strong></td>
                                    <td><?=$this->GetFormat()?></td>
                                </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">&nbsp;<strong>Keyword</strong></td>
                            <td colspan="2"><input name="keyword" type="text" class="inputtxt" id="keyword" size="48" value="<?=$this->keyword?>" style="height: 28px; font-size: 16px;" /></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top">&nbsp;<strong>Keterangan<br>Fisik</strong></td>
                            <td colspan="2"><textarea name="keteranganfisik" cols="60" rows="2" class="areatxt2" id="keteranganfisik" style="font-size: 16px;"><?=$this->keteranganfisik?></textarea></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top">&nbsp;<strong>Keterangan<br>Tambahan</strong></td>
                            <td colspan="2">
                                <textarea name="keterangan" id="keterangan" cols="60" rows="2" style="font-size: 16px;"><?=$this->keterangan?></textarea>
                            </td>
                        </tr>
                	</table>            
            	</fieldset>
            </td>
            <td width="40%" rowspan="2" valign="top">
                <table width="100%" border="0" cellspacing="2" cellpadding="0">
                <tr>
                    <td>
                    	<fieldset style="background-color: #ececec; border-width: 1px; border-color: #ececec;">
							<legend style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI'">&nbsp;Gambar Sampul&nbsp;</legend><br>
                    		<table width="130" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
                            <tr height="130">
								<td align="center" valign="middle"><img src="../inc/gambar.php?replid=<?=$this->replid?>&table=pustaka" /></td>
                            </tr>
                            </table>
							<br />
							<input name="cover" id="cover" type="file" />&nbsp;
							<span class="posted">* Isi untuk ganti cover</span>
						</fieldset>
					</td>
                </tr>
                <tr>
                    <td>
                        <br>
						<fieldset style="background-color: #e7ffec; border-width: 1px; border-color: #ececec;">
							<legend style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI' ">&nbsp;Abstraksi&nbsp;</strong></legend><br>
							<textarea name="abstraksi" id="abstraksi" cols="" rows="" style="width:100%; height: 280px;"><?=$this->abstraksi?></textarea>
						</fieldset>
					</td>
                </tr>
            </table>
			</td>
        </tr>
        <tr>
            <td valign="top">
                <br>
                <fieldset style="background-color: #f8ed94; border-width: 1px; border-color: #ececec;">
                    <legend style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI'">&nbsp;Alokasi Jumlah&nbsp;</legend>
                    <br>
                    <?=$this->GetPustaka()?>
                </fieldset>
            </td>
        </tr>
        <tr>
          	<td colspan="2" align="center">
            	<input name="simpan" type="submit" class="cmbfrm2" value="    Simpan    "
                       style="font-size: 14px; background-color: #0a931e; color: #fff; height: 28px;"/>
            </td>
        </tr>
        </table>
		</form>
        <?php
	}
	
	function GetPustaka()
	{
		?>
		<table width="60%" border="0" cellspacing="0" cellpadding="2" class="tab" style="border-width: 1px; border-collapse: collapse; border-color: #ececec;">
        <?php
			$cnt=0;
			if (SI_USER_LEVEL()==2)
				$sql = "SELECT * FROM perpustakaan WHERE replid='".SI_USER_IDPERPUS()."' ORDER BY nama";
			else
			 	$sql = "SELECT * FROM perpustakaan ORDER BY nama";
				
			$result = QueryDb($sql);
			while ($row = @mysqli_fetch_array($result))
			{
				$sql = "SELECT COUNT(replid)
						  FROM daftarpustaka
						 WHERE pustaka = '" . $this->replid . "'
						   AND perpustakaan = '".$row['replid']."'";
				$res = QueryDb($sql);		   
			 	$num = @mysqli_fetch_row($res); ?>
				<tr>
					<td width="70%" valign="middle">&nbsp;<?=$row['nama']?></td>
					<td width="30%" align="left" valign="middle">
						<input type="text" readonly='readonly' maxlength='3' size='4' name="jumlah<?=$cnt?>" id="jumlah<?=$cnt?>" class="inputtxt" value="<?=$num[0]?>" style="background-color: #ccc; height: 22px; font-size: 16px;" />&nbsp;buah
						<input type="hidden" readonly='readonly' style='background-color: #ccc;' name="replid<?=$cnt?>" id="replid<?=$cnt?>" class="inputtxt" value="<?=$row['replid']?>" />     
					</td>
				</tr>
            <?php $cnt++;
			}	?>
        </table>
<?php
		if (SI_USER_LEVEL() == 2)
		{
			$sql = "SELECT *
					  FROM perpustakaan
					 WHERE replid <> '".SI_USER_IDPERPUS()."'
					 ORDER BY nama";
			$result = QueryDb($sql);
			$cnt=1;
			while ($row = @mysqli_fetch_array($result))
			{
				$sql = "SELECT COUNT(replid)
						  FROM daftarpustaka
						 WHERE pustaka = '".$this->replid."'
						   AND perpustakaan = '".$row['replid']."'";
				$res = QueryDb($sql);		   
				$num = @mysqli_fetch_row($res);	?>
				<input type="hidden" name="jumlah<?=$cnt?>" id="jumlah<?=$cnt?>" class="inputtxt" value="<?=$num[0]?>" />
 		        <input type="hidden" name="replid<?=$cnt?>" id="replid<?=$cnt?>" class="inputtxt" value="<?=$row['replid']?>" />
<?php          	$cnt++;
			}
		}
    }
	
	function CountPustaka()
	{
		$sql = "SELECT * FROM perpustakaan ORDER BY nama";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		
		return $num;
	}
	
	function save()
	{
		$replid = $_REQUEST['replid'];
		$judul = trim(addslashes((string) $_REQUEST['judul']));
		$abstraksi = trim(addslashes((string) $_REQUEST['abstraksi']));
		$keyword = trim(addslashes((string) $_REQUEST['keyword']));
		$tahun = trim(addslashes((string) $_REQUEST['tahun']));
		$harga = UnformatRupiah(trim(addslashes((string) $_REQUEST['harga'])));
		$keteranganfisik = trim(addslashes((string) $_REQUEST['keteranganfisik']));
		$penulis = trim(addslashes((string) $_REQUEST['penulis']));
		$penerbit = trim(addslashes((string) $_REQUEST['penerbit']));
		$format = trim(addslashes((string) $_REQUEST['format']));
		$katalog = trim(addslashes((string) $_REQUEST['katalog']));
		$katalogasli = trim(addslashes((string) $_REQUEST['katalogasli']));
		$keterangan = trim(addslashes((string) $_REQUEST['keterangan']));
		$cover = $_FILES['cover'];
		$uploadedfile = $cover['tmp_name'];
		$uploadedfile_name = $cover['name'];

		if (strlen((string) $uploadedfile)!=0)
		{
			$tmp_path = realpath(".") . "/../../temp";
			$tmp_exists = file_exists($tmp_path) && is_dir($tmp_path);
			if (!$tmp_exists)
				mkdir($tmp_path, 0755);
			
			$filename = "$tmp_path/ed-pus-tmp.jpg";
			ResizeImage($cover, 160, 120, 70, $filename);
			
			$fh = fopen($filename, "r");
        	$cover_binary = addslashes(fread($fh, filesize($filename)));
			fclose($fh);
			
			$fill_cover = ", cover='$cover_binary'"; 
		}
		
		BeginTrans();
		$success = true;

		$sql = "UPDATE pustaka
				   SET harga='$harga', judul='$judul', abstraksi='$abstraksi',
					   keyword='$keyword', tahun='$tahun', keteranganfisik='$keteranganfisik',
					   penulis='$penulis', format='$format', katalog='$katalog', penerbit='$penerbit',
					   keterangan='$keterangan' $fill_cover
				 WHERE replid='$replid'";
		//echo "$sql<br>";
		QueryDbTrans($sql, $success);
		
		if ($success && $katalog != $katalogasli)
		{
			$sql = "SELECT counter
					  FROM katalog
					 WHERE replid = '".$katalogasli."'";
			//echo "$sql<br>";
			$result = QueryDb($sql);
			$r = @mysqli_fetch_row($result);
			$counterasli = $r[0];
			
			$sql = "SELECT counter
					  FROM katalog
					 WHERE replid = '".$katalog."'";
			//echo "$sql<br>";
			$result = QueryDb($sql);
			$r = @mysqli_fetch_row($result);
			$newcounter = $r[0];
			
			$sql = "SELECT replid
					  FROM daftarpustaka
					 WHERE pustaka = '".$replid."'";
			//echo "$sql<br>";
			$r = QueryDb($sql);
			while($success && $row = mysqli_fetch_row($r))
			{
				$iddp = $row[0];
				
				$newcounter++;
				$kodepustaka = $this->GenKodePustaka($katalog, $penulis, $judul, $format, $newcounter);
				$sql = "UPDATE daftarpustaka
						   SET kodepustaka = '$kodepustaka'
						 WHERE replid = '".$iddp."'";
				//echo "$sql<br>";
				QueryDbTrans($sql, $success);		 
			}
			
			if ($success)
			{
				$sql = "UPDATE katalog
						   SET counter = counter - $counterasli
						 WHERE replid = '".$katalogasli."'";
				//echo "$sql<br>";
				QueryDbTrans($sql, $success);
			}
			
			if ($success)
			{
				$sql = "UPDATE katalog
						   SET counter = counter + $newcounter
						 WHERE replid = '".$katalog."'";
				//echo "$sql<br>";		 
				QueryDbTrans($sql, $success);
			}
		}
		
		if ($success)
		{
			//echo "OK";
			//RollbackTrans();
			CommitTrans();
		}
		else
		{
			//echo "FAILED";
			RollbackTrans();
		}
		
		//exit();
		$this->reload_page();	
	}
	
	function GenKodePustaka($katalog, $penulis, $judul, $format, $counter)
	{
		$sql = "SELECT kode FROM katalog WHERE replid='$katalog'";
		$result = QueryDb($sql);
		$ktlg = @mysqli_fetch_row($result);

		$sql = "SELECT kode FROM penulis WHERE replid='$penulis'";
		$result = QueryDb($sql);
		$pnls = @mysqli_fetch_row($result);
		
		$jdl = substr((string) $judul, 0, 1);
	
		$sql = "SELECT kode FROM format WHERE replid='$format'";
		$result = QueryDb($sql);
		$frmt = @mysqli_fetch_row($result);
		
		$cnt = str_pad((string) $counter, 5, "0", STR_PAD_LEFT);

		$unique = true;
		$addcnt = 0;
		do
		{
			$kode = $ktlg[0] . "/" . $pnls[0] . "/" . $jdl . "/" . $cnt . "/" . $frmt[0];
			
			$sql = "SELECT COUNT(replid) FROM daftarpustaka WHERE kodepustaka = '".$kode."'";
			$result = QueryDb($sql);
			$row = mysqli_fetch_row($result);
			
			if ($row[0] > 0)
			{
				$addcnt++;
				$cnt = "$cnt.$addcnt";
				$unique = false;
			}
			else
			{
				$unique = true;
			}
		}
		while(!$unique);
		
		return $kode;
	}
	
	function GetSpace($maxlength,$length)
	{
		$spacer = "";
		for ($i = 1; $i <= $maxlength - $length; $i++)
			$spacer .= "&nbsp;";
			
		return $spacer;	
	}
}
?>