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
class CPengguna
{
	function OnStart()
	{
		$op=$_REQUEST['op'];
		if ($op=="del")
		{
			$sql = "DELETE FROM ".get_db_name('user').".hakakses WHERE login='".$_REQUEST['login']."' AND modul='SIMTAKA'";
			QueryDb($sql);
			
			$sql = "SELECT * FROM ".get_db_name('user').".hakakses WHERE login='".$_REQUEST['login']."' AND modul<>'SIMTAKA'";
			$result = QueryDb($sql);
			$num = @mysqli_num_rows($result);
			if ($num==0)
			{
				$sql = "DELETE FROM ".get_db_name('user').".login WHERE login='".$_REQUEST['login']."'";
				QueryDb($sql);
			}
		}
		
		if ($op=="nyd6j287sy388s3h8s8")
		{
			$sql = "UPDATE ".get_db_name('user').".hakakses SET aktif='".$_REQUEST['newaktif']."' WHERE login='".$_REQUEST['login']."' AND modul='SIMTAKA'";
			QueryDb($sql);
		}
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
		$sql = "SELECT h.login, h.aktif, h.lastlogin, h.departemen, h.tingkat, h.keterangan, h.info1 AS idperpustakaan
				  FROM ".get_db_name('user').".hakakses h, ".get_db_name('user').".login l
				 WHERE h.modul='SIMTAKA'
				   AND l.login=h.login";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		?>
        <div class="funct">
        	<a href="javascript:getfresh()"><img src="../img/ico/refresh.png" border="0">&nbsp;Refresh</a>&nbsp;&nbsp;
			<a href="javascript:cetak()"><img src="../img/ico/print1.png" border="0">&nbsp;Cetak</a>&nbsp;&nbsp;
<?php 			if (IsAdmin())
			{ ?>
				<a href="javascript:tambah()"><img src="../img/ico/tambah.png" border="0">&nbsp;Tambah&nbsp;Pengguna</a>&nbsp;        
<?php 			} ?>
		</div>
        <table width="100%" border="1" cellspacing="0" cellpadding="5" class="tab" id="table">
        <tr height="30" >
			<td width='4%' align="center" class="header">No</td>
            <td width='10%' align="center" class="header">NIP</td>
            <td width='15%' align="center" class="header">Nama</td>
            <td width='15%' align="center" class="header">Tingkat</td>
            <td width='15%' align="center" class="header">Perpustakaan</td>
			<td width='15%' align="center" class="header">Departemen</td>
			<td width='*' align="center" class="header">Keterangan</td>
<?php 			if (IsAdmin())
			{ ?>
				<td width='5%' align="center" class="header">&nbsp;</td>
				<td width='10%' align="center" class="header">&nbsp;</td>
<?php 			} ?>
		</tr>
<?php 	if ($num > 0)
		{
			$cnt = 0;
			while ($row=@mysqli_fetch_row($result))
			{
				$cnt += 1;
				
				$sql = "SELECT nama FROM ".get_db_name('sdm').".pegawai WHERE nip='".$row[0]."'";
				$res = QueryDb($sql);
				$r = @mysqli_fetch_row($res);
				$namapeg = $r[0];
				
				if ($row[4] == 2)
				{
					$sql = "SELECT nama FROM perpustakaan WHERE replid='".$row[6]."'";
					$res = QueryDb($sql);
					$r = @mysqli_fetch_row($res);
					$namaperpus = $r[0];
					$namatingkat = "Staf Perpustakaan";
				}
				else
				{
					$namaperpus = "<i>Semua</i>";
					$namatingkat = "Manajer Perpustakaan";
				}  ?>
			  <tr height="25">
				<td align="center"><?=$cnt?></td>
				<td align="left"><?=$row[0]?></td>
				<td align="left"><?=$namapeg?></td>
				<td align="left"><?=$namatingkat?></td>
				<td align="left"><?=$namaperpus?></td>
				<td align="center"><?=$row[3]?></td>
				<td align="left"><?=$row[5]?></td>
				<?php if (IsAdmin()) { ?>
				<td align="center">
                	<?php if ($row[1]==1) { ?>
                    	<a href="javascript:setaktif('<?=$row[0]?>','0')"><img src="../img/ico/aktif.png" width="16" height="16" border="0" /></a>
                    <?php } else { ?>    
                        <a href="javascript:setaktif('<?=$row[0]?>','1')"><img src="../img/ico/nonaktif.png" width="16" height="16" border="0" /></a>
                	<?php } ?>
				</td>
				<td align="center">
                    <a href="javascript:ubah('<?=$row[0]?>')"><img src="../img/ico/ubah.png" border="0"></a>&nbsp;
                    <a href="javascript:hapus('<?=$row[0]?>')"><img src="../img/ico/hapus.png" border="0"></a>
                </td>
				<?php } ?>
			  </tr>
			  <?php
			  }
		  } else {
		  ?>
          <tr>
            <td height="25" colspan="9" align="center" class="nodata">Tidak ada data</td>
          </tr>
		  <?php
		  }
		  ?>	
        </table>

        <?php
	}
}
?>