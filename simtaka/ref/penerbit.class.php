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
class CPenerbit{
	function OnStart(){
		$op=$_REQUEST['op'];
		if ($op=="del"){
			$sql = "DELETE FROM penerbit WHERE replid='".$_REQUEST['id']."'";
			QueryDb($sql);
		}
		$this->numlines = 10;
		$this->page = 0;
		if (isset($_REQUEST['page'])) {
			$this->page = $_REQUEST['page'];
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
		?>
		<link href="../sty/style.css" rel="stylesheet" type="text/css">
        <div class="funct">
        	<a href="javascript:getfresh('XX')"><img src="../img/ico/refresh.png" border="0">&nbsp;Refresh</a>&nbsp;&nbsp;
			<a href="javascript:cetak()"><img src="../img/ico/print1.png" border="0">&nbsp;Cetak</a>&nbsp;&nbsp;
			<a href="javascript:tambah()"><img src="../img/ico/tambah.png" border="0">&nbsp;Tambah</a>&nbsp;        </div>
		<?php
		$sql = "SELECT * FROM penerbit ORDER BY kode"; 
		$result = QueryDb($sql);
		//$pagenum = @mysqli_num_rows($result);
		$pagenum = ceil(mysqli_num_rows($result)/(int)$this->numlines);

		$sql = "SELECT * FROM penerbit ORDER BY kode LIMIT ".(int)$this->page*(int)$this->numlines.",".$this->numlines;
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		?>
		<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table">
          <tr>
            <td align="center" class="header">No</td>
            <td height="30" align="center" class="header">Kode</td>
            <td height="30" align="center" class="header">Nama</td>
            <td height="30" align="center" class="header">Jumlah Judul</td>
            <td height="30" align="center" class="header">Jumlah Pustaka</td>
			<td height="30" align="center" class="header">Alamat</td>
			<td height="30" align="center" class="header">Telepon</td>
            <td height="30" align="center" class="header">Keterangan</td>
            <td height="30" align="center" class="header">&nbsp;</td>
          </tr>
          <?php
		  if ($num>0){
		      	if ($this->page==0){
					$cnt = 1;
				}else{ 
					$cnt = (int)$this->page*(int)$this->numlines+1;
				}
			  while ($row=@mysqli_fetch_array($result)){
		            $num_judul = @mysqli_num_rows(QueryDb("SELECT * FROM pustaka p, penerbit pb WHERE pb.replid='".$row['replid']."' AND pb.replid=p.penerbit"));
					$num_pustaka = @mysqli_fetch_row(QueryDb("SELECT COUNT(d.replid) FROM pustaka p, daftarpustaka d, penerbit pb WHERE d.pustaka=p.replid AND pb.replid='".$row['replid']."' AND p.penerbit=pb.replid"));			  
			  ?>
			  <tr>
			    <td align="center"><?=$cnt?></td>
				<td height="25" align="center"><?=$row['kode']?></td>
				<td height="25"><div class="tab_content"><?=$row['nama']?></div></td>
				<td height="25" align="center"><?=$num_judul?>
                	<?php if ($num_judul!=0){ ?>
                    &nbsp;<img src="../img/ico/lihat.png" style="cursor:pointer" onclick="ViewByTitle('<?=$row['replid']?>')" />
                	<?php } ?>                </td>
				<td height="25" align="center"><?=(int)$num_pustaka[0]?></td>
				<td height="25"><div class="tab_content"><?=$row['alamat']?></div></td>
				<td height="25"><div class="tab_content"><?=$row['telpon']?></div></td>
				<td height="25"><div class="tab_content"><?=$row['keterangan']?></div></td>
				<td height="25" align="center" bgcolor="#FFFFFF">
                	<table width="100%" border="0" cellspacing="2" cellpadding="0">
                      <tr>
                        <td><a href="javascript:ubah('<?=$row['replid']?>')"><img src="../img/ico/ubah.png" width="16" height="16" border="0"></a></td>
						<?php if(IsAdmin()){ ?>
                        <td><a href="javascript:hapus('<?=$row['replid']?>')"><img src="../img/ico/hapus.png" border="0"></a></td>
						<?php } ?>
					  </tr>
                    </table>                </td>
			  </tr>
			  <?php
			  $cnt++;
			  }
			  	if ($this->page==0){ 
					$disback="style='display:none;'";
					$disnext="style='display:visible;'";
				}
				if ($this->page<$pagenum && $this->page>0){
					$disback="style='display:visible;'";
					$disnext="style='display:visible;'";
				}
				if ($this->page==$pagenum-1 && $this->page>0){
					$disback="style='display:visible;'";
					$disnext="style='display:none;'";
				}
				if ($this->page==$pagenum-1 && $this->page==0){
					$disback="style='display:none;'";
					$disnext="style='display:none;'";
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
		<br>
		<table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
			<tr>
				<td align="left" class="news_content1">Halaman
                  <input <?=$disback?> type="button" class="cmbfrm2" name="back" value=" << " onclick="change_page('<?=(int)$this->page-1?>')" onmouseover="showhint('Sebelumnya', this, event, '75px')" />
				<select name="page" class="cmbfrm" id="page" onChange="change_page('XX')">
				<?php for ($m=0; $m<$pagenum; $m++) {?>
					 <option value="<?=$m ?>" <?=IntIsSelected($this->page,$m) ?>><?=$m+1 ?></option>
				<?php } ?>
				</select>
                <input <?=$disnext?> type="button" class="cmbfrm2" name="next" value=" >> " onClick="change_page('<?=(int)$this->page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
				dari <?=$pagenum?> halaman
				
				<?php 
			 // Navigasi halaman berikutnya dan sebelumnya
				?>        
					
					<?php
					//for($a=0;$a<$pagenum;$a++){
						//if ($this->page==$a){
						//	echo "<font face='verdana' color='red' size='4'><strong>".($a+1)."</strong></font> "; 
						//} else { 
						//	echo "<a href='#' onClick=\"change_page('".$a."')\"><font face='verdana' color='green'>".($a+1)."</font></a> "; 
						//}
							 
					//}
					?>				</td>
			</tr>
		</table>
<?php
	}
}
?>