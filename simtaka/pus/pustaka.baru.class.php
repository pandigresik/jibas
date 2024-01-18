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
		$op = $_REQUEST['op'];
		if ($op == "del")
		{
			$sql = "DELETE FROM format
					 WHERE replid='".$_REQUEST['id']."'";
			QueryDb($sql);
		}
		
		if (isset($_REQUEST['simpan']))
			$this->save();
	}
	
	function reload_page()
	{
		?>
		<script language='JavaScript'>
			document.location.href="pustaka.baru.php";
        </script>
		<?php
	}
	
	function OnFinish()
	{
		//
    }
	
	function GetKatalog()
	{
		$this->katalog = $_REQUEST['katalog'];
		
		$sql = "SELECT MAX(LENGTH(kode))
			      FROM katalog";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$maxlen = $row[0];
		?>
		
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
		$this->penulis = $_REQUEST['penulis'];
		
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
		$this->penerbit = $_REQUEST['penerbit'];
		
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
		$this->format = $_REQUEST['format'];
		
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
	{	?>
        <form action="pustaka.baru.php" method="post" onsubmit="return validate(<?=$this->CountPustaka()?>)" enctype="multipart/form-data">
		<table width="100%" border="0" cellspacing="2" cellpadding="5">
        <tr>
            <td width="60%" valign="top">
            	<fieldset style="background-color: #e8fcff; border-width: 1px; border-color: #ececec;">
					<legend style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI'">&nbsp;Informasi&nbsp;</legend>

                    <table width="90%" border="0" cellspacing="5" cellpadding="2">
                    <tr>
                        <td width="10%" align="right" valign="top">&nbsp;<strong>Judul</strong></td>
                        <td colspan="2">
                            <textarea name="judul" cols="60" rows="2" class="areatxt2" id="judul" style="font-size: 16px;"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">&nbsp;<strong>Harga&nbsp;Satuan</strong></td>
                        <td colspan="2">
                            <input name="harga" type="text" class="inputtxt" id="harga" onfocus="unformatRupiah('harga')" onblur="formatRupiah('harga')" onkeyup="tempel('harga','hargaasli')" style="height: 28px; font-size: 16px;" />
                            <input name="hargaasli" type="hidden" class="inputtxt" id="hargaasli" />
                        </td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;<strong>Katalog</strong></td>
                        <td colspan="2"><?=$this->GetKatalog()?></td>
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
                        <td width="27%"><input name="tahun" type="text" class="inputtxt" id="tahun" size='5' maxlength="4" style="height: 28px; font-size: 16px;" /></td>
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
                        <td colspan="2"><input name="keyword" type="text" class="inputtxt" id="keyword" size="48" style="height: 28px; font-size: 16px;" /></td>
                      </tr>
                      <tr>
                        <td align="right" valign="top">&nbsp;<strong>Keterangan<br>Fisik</strong></td>
                        <td colspan="2">
                            <textarea name="keteranganfisik" cols="60" rows="2"
                                      class="areatxt2" id="keteranganfisik" style="font-size: 16px;"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td align="right" valign="top">&nbsp;<strong>Keterangan<br>Tambahan</strong></td>
                        <td colspan="2">
                            <textarea name="keterangan" cols="60" rows="2"
                                      class="areatxt2" id="keterangan" style="font-size: 16px;"></textarea>
                        </td>
                      </tr>
                	</table>
            	</fieldset>
				<br>
				<fieldset style="background-color: #f8ed94; border-width: 1px; border-color: #ececec;">
                    <legend style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI'">&nbsp;Alokasi Jumlah&nbsp;</legend>
                    <br>
				    <?=$this->GetPustaka()?>
                </fieldset>
				
			</td>
            <td width="40%" valign="top">
                <table width="100%" border="0" cellspacing="2" cellpadding="0">
                <tr>
                    <td colspan="2">
                    	<fieldset style="background-color: #ececec; border-width: 1px; border-color: #ececec;">
							<legend style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI'">&nbsp;Gambar Sampul&nbsp;</strong></legend><br>
                    		<table width="130" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
                            <tr height="130">
								<td align="center" valign="middle"><img src="../img/noimage.png" /></td>
                            </tr>
                            </table><br />
							<input name="cover" id="cover" type="file" />
                        </fieldset>
					</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br>
						<fieldset style="background-color: #e7ffec; border-width: 1px; border-color: #ececec;">
							<legend style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI' ">&nbsp;Abstraksi&nbsp;</legend><br>
							<textarea name="abstraksi" id="abstraksi" cols="" rows="" style="width:100%; height: 280px;"></textarea>
						</fieldset>
					</td>
                </tr>

                </table>
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
		if (SI_USER_LEVEL()==2)
			$sql = "SELECT *
					  FROM perpustakaan
					 WHERE replid = '".SI_USER_IDPERPUS()."'
					 ORDER BY nama";
		 else 
			$sql = "SELECT *
					  FROM perpustakaan
					 ORDER BY nama";
		 
		$result = QueryDb($sql); ?>
		<table width="60%" border="0" cellspacing="0" cellpadding="2" class="tab" style="border-width: 1px; border-collapse: collapse; border-color: #ececec;">

<?php 	$cnt = 0;
		while ($row = @mysqli_fetch_array($result))
		{
			?>
            <tr>
               <td width="70%" valign="middle">&nbsp;<?=$row['nama']?></td>
               <td width="30%" align="left" valign="middle">
               		<input type="text" name="jumlah<?=$cnt?>" maxlength='3' size='4' id="jumlah<?=$cnt?>" class="inputtxt" style="height: 22px; font-size: 16px;" />&nbsp;buah
 		            <input type="hidden" name="replid<?=$cnt?>" id="replid<?=$cnt?>" class="inputtxt" value="<?=$row['replid']?>" />     
               </td>
             </tr>
<?php  		$cnt++;
		}  ?>
        </table>
		
<?php 	if (SI_USER_LEVEL() == 2)
		{
			$sql = "SELECT * FROM perpustakaan WHERE replid<>".SI_USER_IDPERPUS()." ORDER BY nama";
			$result = QueryDb($sql);
			$cnt=1;
			while ($row = @mysqli_fetch_array($result)){ ?>
					<input type="hidden" name="jumlah<?=$cnt?>" id="jumlah<?=$cnt?>" class="inputtxt" />
 		            <input type="hidden" name="replid<?=$cnt?>" id="replid<?=$cnt?>" class="inputtxt" value="<?=$row['replid']?>" />
			<?php
            $cnt++;
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
	
	function GenerateBarcode($length = 7)
	{
		$dict = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$barcode = "";
		for($i = 0; $i < $length; $i++)
		{
			$pos = random_int(0, strlen($dict) - 1);
			$barcode .= substr($dict, $pos, 1);
		}
		
		return $barcode;
	}
	
	function GetNewBarcode()
	{
		$barcode = "";
		do
		{
			$barcode = $this->GenerateBarcode(7);
			
			$sql = "SELECT COUNT(replid)
					  FROM daftarpustaka
					 WHERE info1 = '".$barcode."'";
			$res = QueryDb($sql);
			$row = mysqli_fetch_row($res);
			$ndata = (int)$row[0];		 
		}
		while($ndata != 0);
		
		return $barcode;
	}
	
	function save()
	{
		$judul = trim(addslashes((string) $_REQUEST['judul']));
		$abstraksi = trim(addslashes((string) $_REQUEST['abstraksi']));
		$keyword = trim(addslashes((string) $_REQUEST['keyword']));
		$harga = UnformatRupiah(trim(addslashes((string) $_REQUEST['harga'])));
		$tahun = trim(addslashes((string) $_REQUEST['tahun']));
		$keteranganfisik = trim(addslashes((string) $_REQUEST['keteranganfisik']));
		$penulis = trim(addslashes((string) $_REQUEST['penulis']));
		$penerbit = trim(addslashes((string) $_REQUEST['penerbit']));
		$format = trim(addslashes((string) $_REQUEST['format']));
		$katalog = trim(addslashes((string) $_REQUEST['katalog']));
		$keterangan = trim(addslashes((string) $_REQUEST['keterangan']));
		$cover = $_FILES['cover'];
		$uploadedfile = $cover['tmp_name'];
		$uploadedfile_name = $cover['name'];

		if (strlen((string) $uploadedfile) != 0)
		{
			$tmp_path = realpath(".") . "/../../temp";
			$tmp_exists = file_exists($tmp_path) && is_dir($tmp_path);
			if (!$tmp_exists)
				mkdir($tmp_path, 0755);
			
			$filename = "$tmp_path/ad-pus-tmp.jpg";
			ResizeImage($cover, 160, 120, 70, $filename);
			
			$fh = fopen($filename, "r");
        	$cover_binary = addslashes(fread($fh, filesize($filename)));
			fclose($fh);
			
			$fill_cover = ", cover='$cover_binary'"; 
		}
		
		BeginTrans();
		$success = true;

		$sql = "SELECT *
				  FROM pustaka
				 WHERE judul='$judul'
				   AND penulis='$penulis'
				   AND format='$format'
				   AND katalog='$katalog'
				   AND penerbit='$penerbit'";
		//echo "$sql<br>";		   
		$result = QueryDbTrans($sql, $success);
		$num = @mysqli_num_rows($result);
		$goon = 0;
		
		if ($success)
		{
			if ($num == 0) 
			{
				$sql = "INSERT INTO pustaka
						   SET harga='$harga', judul='$judul', abstraksi='$abstraksi', keyword='$keyword',
							   tahun='$tahun', keteranganfisik='$keteranganfisik', penulis='$penulis',
							   format='$format', katalog='$katalog', penerbit='$penerbit',
							   keterangan='$keterangan' $fill_cover ";
				//echo "$sql<br>";			   
				$result = QueryDbTrans($sql, $success);
				if ($success) 
				{
					$sql = "SELECT LAST_INSERT_ID()";
					//echo "$sql<br>";
					$result = QueryDb($sql);
					$row = @mysqli_fetch_row($result);
					$lastid = $row[0];
					
					$goon = 1;
				}
			} 
			else 
			{
				$sql = "SELECT replid
						  FROM pustaka
						 WHERE judul='$judul'
						   AND penulis='$penulis'
						   AND format='$format'
						   AND katalog='$katalog'
						   AND penerbit='$penerbit'
						 ORDER BY replid DESC
						 LIMIT 1";
				//echo "$sql<br>";		 
				$result = QueryDbTrans($sql, $success);
				$row = @mysqli_fetch_row($result);
				$lastid = $row[0];
				
				$goon = 1;
			}
		}
		
		if ($success && $goon == 1)
		{
			$sql = "SELECT counter
					  FROM katalog
					 WHERE replid = '".$katalog."'";
			//echo "$sql<br>";		 
			$result = QueryDbTrans($sql, $success);
			$r = @mysqli_fetch_row($result);
			$counter = $r[0];
			for ($i = 0; $success && $i < $this->CountPustaka(); $i++)
			{
				$replid = $_REQUEST['replid'.$i];
				$parm = "jumlah$i";
				if ($_REQUEST[$parm] != "" && $_REQUEST[$parm] > 0)
				{
					for ($j = 1; $success && $j <= $_REQUEST[$parm]; $j++)
					{
						$counter++;
						$kodepustaka = $this->GenKodePustaka($katalog, $penulis, $judul, $format, $counter);
						$barcode = $this->GetNewBarcode();
						$sql = "INSERT INTO daftarpustaka
								   SET pustaka='$lastid', perpustakaan='$replid',
									   kodepustaka='$kodepustaka', info1='$barcode'";
						//echo "$sql<br>";			   
						QueryDbTrans($sql, $success);
					}
				}
			}
			
			if ($success)
			{
				$sql = "UPDATE katalog
						   SET counter = $counter
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
	
	function GenKodePustaka($katalog,$penulis,$judul,$format,$counter)
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
	
	function GetSpace($maxlength, $length)
	{
		$spacer = "";
		for ($i = 1; $i <= $maxlength - $length; $i++)
			$spacer .= "&nbsp;";
			
		return $spacer;	
	}
}
?>