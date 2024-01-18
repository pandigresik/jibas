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
		$this->nip="";
		if (isset($_REQUEST['nip']))
			$this->nip=$_REQUEST['nip'];
			
		$this->nama="";
		if (isset($_REQUEST['nama']))
			$this->nama=$_REQUEST['nama'];
			
		if (isset($_REQUEST['simpan']))
		{
			if ($_REQUEST['tingkat'] == '1')
			{
				$sql = "UPDATE ".get_db_name('user').".hakakses
						   SET tingkat='".$_REQUEST['tingkat']."',
							   departemen=NULL
						 WHERE login='$this->nip'
						   AND modul='SIMTAKA'";
			}
			else
			{
				$temp = explode(":", (string) $_REQUEST['perpustakaan']);
				$idperpustakaan = $temp[0];
		
				$sql = "UPDATE ".get_db_name('user').".hakakses
						   SET tingkat='".$_REQUEST['tingkat']."',
							   departemen='".$_REQUEST['dep']."',
							   info1=$idperpustakaan
					     WHERE login='$this->nip'
						   AND modul='SIMTAKA'";
			}
			QueryDb($sql);
			
			$this->success();
					
		}
		else
		{
			$sql = "SELECT *
				      FROM ".get_db_name('user').".hakakses
					 WHERE login='$this->nip'
					   AND modul='SIMTAKA' ";
			$result = QueryDb($sql);
			$row = @mysqli_fetch_array($result);
			$this->keterangan=$row['keterangan'];
			$this->nip=$row['login'];
			$this->tingkat=$row['tingkat'];
			if (isset($_REQUEST['tingkat']))
				$this->tingkat=$_REQUEST['tingkat'];
			$this->dep = $row['departemen'];	
			$this->perpustakaan = $row['info1'];
			
			$sql = "SELECT *
					  FROM ".get_db_name('sdm').".pegawai
					 WHERE nip='$this->nip'";
			//$echo $$this->tingkat;
			$result = QueryDb($sql);
			$row = @mysqli_fetch_array($result);
			$this->nama=$row['nama'];

		}
		
	}
	
	function exist()
	{	?>
        <script language="javascript">
			alert('Kode sudah digunakan!');
			document.location.href="format.add.php";
		</script>
<?php }
	
	function success()
	{	?>
        <script language="javascript">
			parent.opener.getfresh();
			window.close();
        </script>
<?php }
	
	function add()
	{
		?>
        <style type="text/css">
		<!--
		.style1 {color: #FF9900}
		-->
        </style>
        
        <form enctype="multipart/form-data" name="editpengguna" action="pengguna.edit.php" onsubmit="return validate_edit()" method="post">
		<table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr>
            <td colspan="2" align="left">
            	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        		<font style="font-size:18px; color:#999999">Ubah Pengguna</font></td>
  		</tr>
        <tr>
            <td width="7%">&nbsp;<strong>Pegawai</strong></td>
            <td width="93%">
				<input name="nip" type="text" class="cmbfrm2" id="nip" size="10" readonly="readonly"
					   value="<?=$this->nip?>">&nbsp;
				<input name="nama" type="text" class="cmbfrm2" id="nama" size="35" readonly="readonly"
					   value="<?=$this->nama?>">
			</td>
        </tr>
        <tr>
            <td>&nbsp;Tingkat</td>
            <td>
            	<select name="tingkat" id="tingkat" onchange="ChgTkt(1)">
                	<option value="1" <?=StringIsSelected('1',$this->tingkat)?> >Manajer Perpustakaan</option>
                    <option value="2" <?=StringIsSelected('2',$this->tingkat)?> >Staff Perpustakaan</option>
                </select>
            </td>
        </tr>
		<tr>
			<td>&nbsp;Departemen</td>
			<td>
				<select name='dep' id='dep' onchange='ChangeDep(2)'>
<?php 			if ($this->tingkat == 1)
				{ ?>
					<option value='--ALL--'>Semua Departemen</option>
<?php 			}
				else
				{
					$sql = "SELECT departemen
							  FROM jbsakad.departemen
							 WHERE aktif = 1
							 ORDER BY urutan";
					$result = QueryDb($sql);
					while($row = mysqli_fetch_row($result))
					{
						$sel = $this->dep == $row[0] ? "selected" : "";	?>
						<option value='<?=$row[0]?>' <?=$sel?>><?=$row[0]?></option>
<?php 				}
				}   ?>
				</select>
			</td>
		</tr>
        <tr>
            <td>&nbsp;Perpustakaan</td>
            <td>
				<div id='divPerpus'>
            	<select name="perpustakaan" id="perpustakaan">
<?php 					if ($this->tingkat=='1' || $this->tingkat=='')
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
						{
							$sel = $this->perpustakaan == $row['replid'] ? "selected" : ""; ?>
							<option value="<?=$row['replid'] . ":" . $row['nama']?>" <?=$sel?>>
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
            <td>
				<textarea name="keterangan" cols="45" rows="5" class="areatxt" id="keterangan"><?=$this->keterangan?></textarea>
			</td>
        </tr>
        <tr>
            <td colspan="2" align="center">
				<input type="submit" class="cmbfrm2" name="simpan" value="Simpan" >&nbsp;
				<input type="button" class="cmbfrm2" name="batal" value="Batal" onClick="window.close()" >
			</td>
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