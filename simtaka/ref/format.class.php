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
class CFormat{
	function OnStart(){
		$op=$_REQUEST['op'];
		if ($op=="del"){
			$sql = "DELETE FROM format WHERE replid='".$_REQUEST['id']."'";
			QueryDb($sql);
		}
	}
	function OnFinish(){
		?>
		<script language='JavaScript'>
			Tables('table', 1, 0);
		</script>
		<?php
    }
    function Content(){
		$sql = "SELECT * FROM format ORDER BY kode";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		?>
		<link href="../sty/style.css" rel="stylesheet" type="text/css">
        <div class="funct">
        	<a href="javascript:getfresh()"><img src="../img/ico/refresh.png" border="0">&nbsp;Refresh</a>&nbsp;&nbsp;
			<a href="javascript:cetak()"><img src="../img/ico/print1.png" border="0">&nbsp;Cetak</a>&nbsp;&nbsp;
			<?php if(IsAdmin()){ ?>
			<a href="javascript:tambah()"><img src="../img/ico/tambah.png" border="0">&nbsp;Tambah</a>&nbsp;        
			<?php } ?>
		</div>
        <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table">
          <tr>
            <td align="center" class="header">No</td>
            <td height="30" align="center" class="header">Kode</td>
            <td height="30" align="center" class="header">Nama</td>
            <td height="30" align="center" class="header">Jumlah Judul</td>
            <td height="30" align="center" class="header">Jumlah Pustaka</td>
            <td height="30" align="center" class="header">Keterangan</td>
            <?php if(IsAdmin()){ ?>
			<td height="30" align="center" class="header">&nbsp;</td>
			<?php } ?>
		  </tr>
          <?php
		  if ($num>0){
		  	  $cnt=1;	
			  while ($row=@mysqli_fetch_array($result)){
			  $num_judul = @mysqli_num_rows(QueryDb("SELECT * FROM pustaka WHERE format='".$row['replid']."'"));
			  $num_pustaka = @mysqli_fetch_row(QueryDb("SELECT COUNT(d.replid) FROM pustaka p, daftarpustaka d WHERE d.pustaka=p.replid AND p.format='".$row['replid']."'"));
			  ?>
			  <tr>
			    <td align="center"><?=$cnt?></td>
				<td height="25" align="center"><?=$row['kode']?></td>
				<td height="25" align="center">&nbsp;<?=$row['nama']?></td>
				<td height="25" align="center">&nbsp;<?=$num_judul?>
                	<?php if ($num_judul!=0){ ?>
                    &nbsp;<img src="../img/ico/lihat.png" style="cursor:pointer" onclick="ViewByTitle('<?=$row['replid']?>')" />
                	<?php } ?>                </td>
				<td height="25" align="center">&nbsp;<?=(int)$num_pustaka[0]?></td>
				<td height="25" align="center">&nbsp;<?=$row['keterangan']?></td>
				<?php if(IsAdmin()){ ?>
				<td height="25" align="center" bgcolor="#FFFFFF">
					<table border='0'>
						<tr>
							<td style='padding:2px'>
								<a href="javascript:ubah('<?=$row['replid']?>')"><img src="../img/ico/ubah.png" width="16" height="16" border="0"></a>
							</td>
							<td style='padding:2px'>
								<a href="javascript:hapus('<?=$row['replid']?>')"><img src="../img/ico/hapus.png" border="0"></a>
							</td>
						</tr>
					</table>
				</td>
				<?php } ?>
			  </tr>
			  <?php
			  $cnt++;
			  }
		  } else {
		  ?>
          <tr>
            <td height="25" colspan="7" align="center" class="nodata">Tidak ada data</td>
          </tr>
		  <?php
		  }
		  ?>	
        </table>

        <?php
	}
}
?>