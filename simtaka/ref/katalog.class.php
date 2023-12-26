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
class CKatalog{
	function OnStart(){
		$op=$_REQUEST['op'];
		if ($op=="del"){
			$sql = "DELETE FROM katalog WHERE replid='".$_REQUEST['id']."'";
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
	function GetRak(){
		$this->rak = $_REQUEST['rak'];
		$sql = "SELECT replid,rak FROM rak ORDER BY rak";
		$result = QueryDb($sql);
		?>
		<select name="rak" id="rak" class="cmbfrm" onchange="getfresh('XX')">
		<?php
		while ($row = @mysqli_fetch_row($result)){
		if ($this->rak=="")
			$this->rak = $row[0];	
		?>
			<option value="<?=$row[0]?>" <?=IntIsSelected($row[0],$this->rak)?>><?=$row[1]?></option>
		<?php
		}
		?>
		</select>
		<?php
	}
    function Content(){
		?>
		<link href="../sty/style.css" rel="stylesheet" type="text/css">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="50%">
				&nbsp;<b>Rak : </b><?=$this->GetRak()?>
				</td>
				<td align="right">
					<div class="funct">
						<a href="javascript:getfresh('XX')"><img src="../img/ico/refresh.png" border="0">&nbsp;Refresh</a>&nbsp;&nbsp;
						<a href="javascript:cetak()"><img src="../img/ico/print1.png" border="0">&nbsp;Cetak</a>&nbsp;&nbsp;
						<?php if(IsAdmin()){ ?>
						<a href="javascript:tambah()"><img src="../img/ico/tambah.png" border="0">&nbsp;Tambah</a>&nbsp;        
						<?php } ?>
					</div>
				</td>
			</tr>
		</table><br>
		<?php
		if ($this->rak=="")
			exit;
		$sql = "SELECT * FROM katalog WHERE rak=".$this->rak." ORDER BY kode";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		?>
		<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table">
          <tr>
            <td width="3%" align="center" class="header">No</td>
            <td width="3%" height="30" align="center" class="header">Kode</td>
            <td width="*" height="30" align="center" class="header">Nama</td>
            <td width="77" height="30" align="center" class="header">Jumlah&nbsp;Judul</td>
            <td width="94" height="30" align="center" class="header">Jumlah&nbsp;Pustaka</td>
            <td width="*" height="30" align="center" class="header">Keterangan</td>
            <?php if(IsAdmin()){ ?>
			<td width="50" height="30" align="center" class="header">&nbsp;</td>
			<?php } ?>
		  </tr>
          <?php
		  if ($num>0){
		  	  $cnt=1;	
			  while ($row=@mysqli_fetch_array($result)){
		            $num_judul = @mysqli_num_rows(QueryDb("SELECT * FROM pustaka p, katalog k WHERE k.replid='".$row['replid']."' AND k.replid=p.katalog"));
					$num_pustaka = @mysqli_fetch_row(QueryDb("SELECT COUNT(d.replid) FROM pustaka p, daftarpustaka d, katalog k WHERE d.pustaka=p.replid AND k.replid='".$row['replid']."' AND p.katalog=k.replid"));
			  ?>
			  <tr>
			    <td align="center"><?=$cnt?></td>
				<td height="25" align="center"><?=$row['kode']?></td>
				<td height="25">&nbsp;<?=$row['nama']?></td>
				<td height="25" align="center">&nbsp;<?=$num_judul?>
                	<?php if ($num_judul!=0){ ?>
                    &nbsp;<img src="../img/ico/lihat.png" style="cursor:pointer" onclick="ViewByTitle('<?=$row['replid']?>')" />
                	<?php } ?>                </td>
				<td height="25" align="center">&nbsp;<?=(int)$num_pustaka[0]?></td>
				<td height="25">&nbsp;<?=$row['keterangan']?></td>
				<?php if(IsAdmin()){ ?>
				<td width="50" height="25" align="center" bgcolor="#FFFFFF"><a href="javascript:ubah('<?=$row['replid']?>')"><img src="../img/ico/ubah.png" width="16" height="16" border="0"></a>&nbsp;<a href="javascript:hapus('<?=$row['replid']?>')"><img src="../img/ico/hapus.png" border="0"></a></td>
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