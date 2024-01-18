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
class CPenggunaAdd
{
	
	function OnStart()
	{
		$this->nip = "";
		if (isset($_REQUEST['nip']))
			$this->nip = $_REQUEST['nip'];
			
		$this->tingkat = 1;
		if (isset($_REQUEST['tingkat']))
			$this->tingkat = $_REQUEST['tingkat'];
			
		$this->nama = "";
		if (isset($_REQUEST['nama']))
			$this->nama = $_REQUEST['nama'];
			
		$this->dep = "";
		if (isset($_REQUEST['dep']))
			$this->dep = $_REQUEST['dep'];
		
		$perpus = $_REQUEST['perpustakaan'];
		$temp = explode(":", (string) $perpus);
		$this->perpustakaan = $temp[1];
		$this->idperpustakaan = $temp[0];
		
		$this->keterangan = CQ($_REQUEST['keterangan']);
		
		if (isset($_REQUEST['simpan']))
		{
			$nip = $_REQUEST['nip'];
			
			$sql = "SELECT *
					  FROM ".get_db_name('user').".login
					 WHERE login='$nip'";
			//echo "$sql<br>";
			
			$result = QueryDb($sql);
			$num = @mysqli_num_rows($result);
			if ($num > 0)
			{
				$sql = "SELECT *
						  FROM ".get_db_name('user').".hakakses
						 WHERE login='$nip'
						   AND modul='SIMTAKA'";
				//echo "$sql<br>";
				
				$result = QueryDb($sql);
				$num = @mysqli_num_rows($result);
				if ($num == 0)
				{
					if ($this->tingkat=='1')
						$sql = "INSERT INTO ".get_db_name('user').".hakakses
								   SET login='$nip', modul='SIMTAKA', tingkat='$this->tingkat',
									   keterangan='$this->keterangan'";
					else
						$sql = "INSERT INTO ".get_db_name('user').".hakakses
								   SET login='$nip', modul='SIMTAKA', tingkat='$this->tingkat',
									   departemen='$this->dep', info1='$this->idperpustakaan',
									   keterangan='$this->keterangan'";
					//echo "$sql<br>";
					
					$result = QueryDb($sql);
					if ($result)
						$this->success();
				}
				else
				{
					$this->success();
				}	
			}
			else
			{
				$password = trim(addslashes((string) $_REQUEST['password1']));
				
				$sql = "INSERT INTO ".get_db_name('user').".login
						   SET login='$nip', password='".md5($password)."'";
				QueryDb($sql);
				
				$sql = "SELECT *
						  FROM ".get_db_name('user').".hakakses
						 WHERE login='$nip' AND modul='SIMTAKA' ";
				$result = QueryDb($sql);
				
				$num = @mysqli_num_rows($result);
				if ($num == 0)
				{
                    if ($this->tingkat=='1')
                        $sql = "INSERT INTO ".get_db_name('user').".hakakses
                                   SET login='$nip', modul='SIMTAKA', tingkat='$this->tingkat',
                                       keterangan='$this->keterangan'";
                    else
                        $sql = "INSERT INTO ".get_db_name('user').".hakakses
                                   SET login='$nip', modul='SIMTAKA', tingkat='$this->tingkat',
                                       departemen='$this->dep', info1='$this->idperpustakaan',
                                       keterangan='$this->keterangan'";
					$result = QueryDb($sql);
					if ($result)
						$this->success();
				}
				else
				{
					$this->success();
				}		
			}
		}
		
	}
	
	function exist()
	{
		?>
        <script language="javascript">
			alert('Kode sudah digunakan!');
			document.location.href="format.add.php";
		</script>
        <?php
	}
	
	function success()
	{
		//exit();
		?>
        <script language="javascript">
			parent.opener.getfresh();
			window.close();
        </script>
<?php
	}
	
	function add()
	{
		
		?>
        <style type="text/css">
		<!--
		.style1 {color: #FF9900}
		-->
        </style>
        
        <form enctype="multipart/form-data" name="addpengguna" action="pengguna.add.php" onsubmit="return validate()" method="post">
		<table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr>
            <td colspan="2" align="left">
            	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        		<font style="font-size:18px; color:#999999">Tambah Pengguna</font>
			</td>
		</tr>
        <tr>
            <td width="7%">&nbsp;<strong>Pegawai</strong></td>
            <td width="93%">
				<input name="nip" type="text" class="cmbfrm2" id="nip" size="10" readonly="readonly" onclick="cari()" value="<?=$this->nip?>">&nbsp;<input name="nama" type="text" class="cmbfrm2" id="nama" size="35" readonly="readonly" onclick="cari()" value="<?=$this->nama?>">&nbsp;
				<a href="javascript:cari()"><img src="../img/ico/cari.png" border="0" /></a>
			</td>
        </tr>
<?php      if ($this->nip != "")
		{
			$sql = "SELECT *
					  FROM ".get_db_name('user').".login
					 WHERE login='$this->nip'";
			$result = QueryDb($sql);
			$num = @mysqli_num_rows($result);
			if ($num==0)
			{  ?>
				<tr>
					<td>&nbsp;Password</td>
					<td><input name="password1" type="password" class="inputtxt" id="password1" /></td>
				</tr>
				<tr>
					<td>&nbsp;Password(konfirmasi)</td>
					<td><input name="password2" type="password" class="inputtxt" id="password2" /></td>
				</tr>
<?php 		}
			else
			{  ?>
				<tr>
					<td colspan="2" align="center" class="err style1">Pengguna sudah memiliki password
					  <input name="password1" type="hidden" class="inputtxt" id="password1" value="xxx" />
					  <input name="password2" type="hidden" class="inputtxt" id="password2" value="xxx" />
					</td>
				</tr>
<?php 		}
		}
		else
		{  ?>
			<input name="password1" type="hidden" class="inputtxt" id="password1" value="xxx" />
			<input name="password2" type="hidden" class="inputtxt" id="password2" value="xxx" />
<?php   	}	?>
		<tr>
            <td>&nbsp;Tingkat</td>
            <td>
            	<select name="tingkat" id="tingkat" onchange="ChgTkt(2)">
                	<option value="1" <?=StringIsSelected('1',$this->tingkat)?> >Manajer Perpustakaan</option>
                    <option value="2" <?=StringIsSelected('2',$this->tingkat)?>>Staff Perpustakaan</option>
                </select>
            </td>
        </tr>
		<tr>
			<td>&nbsp;Departemen</td>
			<td>
				<select name='dep' id='dep' onchange='ChangeDep(2)'>
<?php 					if ($this->tingkat == 1)
					{ ?>					
					<option value='--ALL--'>Semua Departemen</option>
<?php 					}
					else
					{					
						$sql = "SELECT departemen
								  FROM jbsakad.departemen
								 WHERE aktif = 1
								 ORDER BY urutan";
						$result = QueryDb($sql);
						while($row = mysqli_fetch_row($result))
						{ ?>
							<option value='<?=$row[0]?>'><?=$row[0]?></option>
<?php 					}
					} 	?>
				</select>
			</td>
		</tr>
        <tr>
            <td>&nbsp;Perpustakaan</td>
            <td>
				<div id='divPerpus'>
            	<select name="perpustakaan" id="perpustakaan">
<?php 					if ($this->tingkat == 1)
					{ ?>
	                	<option value="-1" >Semua Perpustakaan</option>
<?php 					}
					else
					{
						$sql = "SELECT *
								  FROM perpustakaan
								 ORDER BY replid";
						$result = QueryDb($sql);						
						while ($row = @mysqli_fetch_array($result))
						{ ?>
							<option value="<?=$row['replid'] . ":" . $row['nama']?>" <?=StringIsSelected($row['nama'], $this->perpustakaan)?>>
							<?=$row['nama']?>
							</option>
<?php 					}
					} ?>
                </select>
				</div>
            </td>
        </tr>
        <tr>
            <td valign='top'>&nbsp;Keterangan</td>
            <td><textarea name="keterangan" cols="45" rows="5" class="areatxt" id="keterangan"></textarea></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" class="cmbfrm2" name="simpan" value="Simpan" >&nbsp;<input type="button" class="cmbfrm2" name="batal" value="Batal" onClick="window.close()" ></td>
        </tr>
        </table>
		</form>
<?php }
	
	function get_noreg()
	{
		return "ANG".date('YmdHis');
	}
}
?>