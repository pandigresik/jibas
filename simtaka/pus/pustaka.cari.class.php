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
class CPustaka{
	function OnStart(){
		$op=$_REQUEST['op'];
		if ($op=="Gtytc6yt665476d6"){
			$sql = "DELETE FROM daftarpustaka WHERE pustaka='".$_REQUEST['replid']."'";
			QueryDb($sql);
			$sql = "DELETE FROM pustaka WHERE replid='".$_REQUEST['replid']."'";
			QueryDb($sql);
		}
		if (isset($_REQUEST['simpan']))
			$this->save();
		$this->numlines = 15;
		$this->page = 0;
		if (isset($_REQUEST['page'])) {
			$this->page = $_REQUEST['page'];
		}
	}
	function reload_page(){
		?>
		<script language='JavaScript'>
			document.location.href="pustaka.baru.php";
        </script>
		<?php
	}
	function OnFinish(){
		?>
		<script language='JavaScript'>
			Tables('table', 1, 0);
		</script>
		<?php
    }
	function GetPerpus(){
		$this->perpustakaan = $_REQUEST['perpustakaan'];
		if (SI_USER_LEVEL()==2){
			$sql = "SELECT replid,nama FROM perpustakaan WHERE replid='".SI_USER_IDPERPUS()."' ORDER BY nama";
		} else {
			$sql = "SELECT replid,nama FROM perpustakaan ORDER BY nama";
		}
		$result = QueryDb($sql);
		?>
		<link href="../sty/style.css" rel="stylesheet" type="text/css" />
		<select name="perpustakaan" id="perpustakaan" class="cmbfrm"  onchange="chg_perpus()">
		<?php
		if (SI_USER_LEVEL()!=2){
			echo "<option value='-1' ".IntIsSelected('-1',$this->perpustakaan).">(Semua)</option>";
		}
		while ($row = @mysqli_fetch_row($result)){
		if ($this->perpustakaan=="")
			$this->perpustakaan = $row[0];	
		?>
			<option value="<?=$row[0]?>" <?=IntIsSelected($row[0],$this->perpustakaan)?>><?=$row[1]?></option>
		<?php
		}
		?>
		</select>
		<?php
	}
	
	function Get_Kategori_Content($kat)
	{
		$this->keywords = $_REQUEST['keywords'];
		
		if ($kat == "rak")
			$ob = "rak";
		else
			$ob = "nama";
			
		$sql = "SELECT * FROM $kat ORDER BY $ob";
		$result = QueryDb($sql); ?>
		<select name="keywords" class="cmbfrm" id="keywords" onchange="chg_key()">
			<?php
			while ($row = @mysqli_fetch_array($result)){
				if ($this->keywords=="")
					$this->keywords=$row['replid'];
			?>
          	<option value="<?=$row['replid']?>" <?=StringIsSelected($this->keywords,$row['replid'])?> >
				<?php
				if ($kat!="rak")
					echo $row['kode']." - ".$row['nama'];
				else
					echo $row['rak'];
				?>
        	</option>
            <?php
			}
			?>
        </select>
		<?php
    }
    function Content(){
		$this->keywords = $_REQUEST['keywords'];
		$this->kategori = $_REQUEST['kategori'];
		if ($this->kategori=="")
			$this->kategori = "judul";	
		?>
		<link href="../sty/style.css" rel="stylesheet" type="text/css">
        <table width="100%" border="0" cellspacing="3" cellpadding="0">
          <tr>
            <td width="9%"><strong>Perpustakaan</strong></td>
            <td width="29%">&nbsp;<?=$this->GetPerpus()?></td>
            <td width="62%" rowspan="2"><a href="javascript:cari()"><img src="../img/view.png" width="48" height="48" border="0" /></a></td>
          </tr>
          <tr>
            <td><strong>Cari&nbsp;berdasarkan</strong></td>
            <td>
            	<div style="margin-left:3px">
                    <table width="100%" border="0" cellspacing="5" cellpadding="0">
                      <tr>
                        <td width='30%'>
                            <select name="kategori" class="cmbfrm" id="kategori" onchange="chg_kat()">
                                <option value="judul" <?=StringIsSelected($this->kategori,'judul')?> >Judul</option>
                                <option value="rak" <?=StringIsSelected($this->kategori,'rak')?> >Rak</option>
                                <option value="katalog" <?=StringIsSelected($this->kategori,'katalog')?> >Katalog</option>
                                <option value="penerbit" <?=StringIsSelected($this->kategori,'penerbit')?> >Penerbit</option>
                                <option value="penulis" <?=StringIsSelected($this->kategori,'penulis')?> >Penulis</option>
                                <option value="tahun" <?=StringIsSelected($this->kategori,'tahun')?> >Tahun Terbit</option>
                                <option value="abstraksi" <?=StringIsSelected($this->kategori,'abstraksi')?> >Abstraksi</option>
                                <option value="keteranganfisik" <?=StringIsSelected($this->kategori,'keteranganfisik')?> >Keterangan Fisik</option>
                          </select>                        </td>
                        <td><?php
                        if ($this->kategori=='rak' || $this->kategori=='katalog' || $this->kategori=='penerbit' || $this->kategori=='penulis'){
                            $this->Get_Kategori_Content($this->kategori);
                        } else {
                        ?>
							<input name="keywords" type="text" style='width: 240px' class="inputtxt" id="keywords" value="<?=$this->keywords?>" />
                        <?php
                        }
                        ?></td>
                      
                    </table>
              </div>
            </td>
          </tr>
        </table>

<div align="right">
 			<a href="javascript:document.location.reload()"><img src="../img/ico/refresh.png" width="16" height="16" border="0" />&nbsp;Refresh</a>&nbsp;&nbsp;<a href="javascript:cetak('XX')"><img src="../img/ico/print1.png" border="0" />&nbsp;Cetak</a>
        </div><br />
        <?php
		if (isset($_REQUEST['cari'])){
		?>
        <table width="100%" border="1" cellspacing="0" cellpadding="4" id="table">
          <tr class="header" height="30">
            <td width='4%' align="center">No</td>
			<td width='15%' align="center">Katalog</td>
            <td width='*' align="center">Judul</td>
            <td width='7%' align="center">Jumlah Tersedia</td>
            <td width='7%' align="center">Jumlah Dipinjam</td>
            <td width='7%' align="center">Cetak Label &amp; Barcode</td>
			<td width='7%' align="center">Tambah / Hapus Pustaka</td>
            <td width='10%' align="center">&nbsp;</td>
          </tr>
          <?php

		  $filter2="";
		  if ($this->perpustakaan!='-1')
			$filter2=" AND d.perpustakaan=".$this->perpustakaan;

		  $kategori = $_REQUEST['kategori'];	
		  $keywords = $_REQUEST['keywords'];
		  $filter = "";
		  if ($kategori=='judul')
			  $filter = "AND p.judul LIKE '%$keywords%' ";
		  if ($kategori=='tahun' && $keywords!='')
			  $filter = "AND p.tahun = $keywords ";
		  if ($kategori=='abstraksi')
			  $filter = "AND p.abstraksi LIKE '%$keywords%' ";
		  if ($kategori=='keteranganfisik')
			  $filter = "AND p.keteranganfisik LIKE '%$keywords%' ";
		  
		  $sql1 = "SELECT p.replid, p.judul, p.katalog FROM pustaka p, daftarpustaka d WHERE d.pustaka=p.replid $filter2 $filter GROUP BY d.pustaka ORDER BY p.judul ";
		  $sql2 = "SELECT p.replid, p.judul, p.katalog FROM pustaka p, daftarpustaka d WHERE d.pustaka=p.replid $filter2 $filter GROUP BY d.pustaka ORDER BY p.judul LIMIT ".(int)$this->page*(int)$this->numlines.",".$this->numlines;

		  if ($kategori=='rak'){
				$sql1 = "SELECT p.replid, p.judul, p.katalog FROM pustaka p, daftarpustaka d, rak r, katalog k WHERE d.pustaka=p.replid $filter2 AND r.replid='$keywords' AND p.katalog=k.replid AND k.rak=r.replid GROUP BY d.pustaka ORDER BY p.judul ";
				$sql2 = "SELECT p.replid, p.judul, p.katalog FROM pustaka p, daftarpustaka d, rak r, katalog k WHERE d.pustaka=p.replid $filter2 AND r.replid='$keywords' AND p.katalog=k.replid AND k.rak=r.replid GROUP BY d.pustaka ORDER BY p.judul LIMIT ".(int)$this->page*(int)$this->numlines.",".$this->numlines;
		  }
		  if ($kategori=='katalog'){
				$sql1 = "SELECT p.replid, p.judul, p.katalog FROM pustaka p, daftarpustaka d, katalog k WHERE d.pustaka=p.replid $filter2 AND k.replid='$keywords' AND p.katalog=k.replid GROUP BY d.pustaka ORDER BY p.judul ";
				$sql2 = "SELECT p.replid, p.judul, p.katalog FROM pustaka p, daftarpustaka d, katalog k WHERE d.pustaka=p.replid $filter2 AND k.replid='$keywords' AND p.katalog=k.replid GROUP BY d.pustaka ORDER BY p.judul LIMIT ".(int)$this->page*(int)$this->numlines.",".$this->numlines;
		  }	
          if ($kategori=='penerbit'){
				$sql1 = "SELECT p.replid, p.judul, p.katalog FROM pustaka p, daftarpustaka d, penerbit pb WHERE d.pustaka=p.replid $filter2 AND pb.replid='$keywords' AND p.penerbit=pb.replid GROUP BY d.pustaka ORDER BY p.judul ";
				$sql2 = "SELECT p.replid, p.judul, p.katalog FROM pustaka p, daftarpustaka d, penerbit pb WHERE d.pustaka=p.replid $filter2 AND pb.replid='$keywords' AND p.penerbit=pb.replid GROUP BY d.pustaka ORDER BY p.judul LIMIT ".(int)$this->page*(int)$this->numlines.",".$this->numlines;
	      }
		  if ($kategori=='penulis'){
				$sql1 = "SELECT p.replid, p.judul, p.katalog FROM pustaka p, daftarpustaka d, penulis pn WHERE d.pustaka=p.replid $filter2 AND pn.replid='$keywords' AND p.penulis=pn.replid GROUP BY d.pustaka ORDER BY p.judul ";
				$sql2 = "SELECT p.replid, p.judul, p.katalog FROM pustaka p, daftarpustaka d, penulis pn WHERE d.pustaka=p.replid $filter2 AND pn.replid='$keywords' AND p.penulis=pn.replid GROUP BY d.pustaka ORDER BY p.judul LIMIT ".(int)$this->page*(int)$this->numlines.",".$this->numlines;
		  }
		  //echo $sql1;
		  $result = QueryDb($sql1);
		  //$pagenum = @mysqli_num_rows($result);
		  $pagenum = ceil(mysqli_num_rows($result)/(int)$this->numlines);

		  $result = QueryDb($sql2);
		  $num = @mysqli_num_rows($result);
		  if ($num>0){
			  $cnt=1;
			  while ($row = @mysqli_fetch_row($result))
			  {
				$kode = "";
				$katalog = "";
				$sql = "SELECT kode, nama FROM katalog WHERE replid = $row[2]";
				$res = QueryDb($sql);
				if (mysqli_num_rows($res) > 0)
				{
					$row2 = mysqli_fetch_row($res);
					$kode = $row2[0];
					$katalog = $row2[1];
				}
				
				$rdipinjam = @mysqli_num_rows(QueryDb("SELECT * FROM daftarpustaka d WHERE d.pustaka='".$row[0]."' $filter2 AND d.status=0"));
				$rtersedia = @mysqli_num_rows(QueryDb("SELECT * FROM daftarpustaka d WHERE d.pustaka='".$row[0]."' $filter2 AND d.status=1"));
			  //echo "SELECT * FROM daftarpustaka d WHERE d.pustaka=$row[0] AND d.perpustakaan=".$this->perpustakaan." AND d.status=0"."<br>";
			  //$rdipinjam = @mysqli_num_rows(QueryDb("SELECT * FROM daftarpustaka d WHERE d.pustaka=$row[0] AND d.perpustakaan=".$this->perpustakaan." AND d.status=0"));
			  //$rtersedia = @mysqli_num_rows(QueryDb("SELECT * FROM daftarpustaka d WHERE d.pustaka=$row[0] AND d.perpustakaan=".$this->perpustakaan." AND d.status=1"));
			  ?>
			  <tr height='25'>
				<td align="center"><?=$cnt?></td>
				<td align="left"><?=$kode . " - " . $katalog?></td>
				<td><div class="tab_content"><?=stripslashes((string) $row[1])?></div></td>
				<td align="center"><?=$rtersedia?></td>
				<td align="center"><?=$rdipinjam?></td>
				<td align="center">
					<a title="Cetak Label" href="javascript:cetak_nomor('<?=$row[0]?>','<?=$this->perpustakaan?>')">
						<img src="../img/ico/print1.png" height="18" border="0" />
					</a>
				</td>
				<td align="center">
					<a title="Penambahan dan Pengurangan Pustaka" href="javascript:aturpustaka('<?=$row[0]?>', '<?=$this->perpustakaan?>')">
						<img src="../img/book.png" height="18" border="0" />
					</a>
				</td>
				<td align="center">
					<a title="Lihat Detail" href="javascript:lihat(<?=$row[0]?>)">
						<img src="../img/ico/lihat.png" width="16" height="16" border="0" />
					</a>
					&nbsp;
					<a href="javascript:ubah('<?=$row[0]?>','daftar','','','')">
						<img src="../img/ico/ubah.png" width="16" height="16" border="0" />
					</a>
					&nbsp;
                    <?php if (SI_USER_LEVEL() != 2) { ?>
					<a href="javascript:hapus(<?=$row[0]?>)">
						<img src="../img/ico/hapus.png" width="16" height="16" border="0" />
					</a>
					<?php } ?>
                </td>
			  </tr>
			  <?php
			  $cnt++;
			  }
			    if ($this->page==0){ 
					$disback="style='display:none;'";
					$disnext="style='display:;'";
				}
				if ($this->page<$pagenum && $this->page>0){
					$disback="style='display:;'";
					$disnext="style='display:;'";
				}
				if ($this->page==$pagenum-1 && $this->page>0){
					$disback="style='display:;'";
					$disnext="style='display:none;'";
				}
				if ($this->page==$pagenum-1 && $this->page==0){
					$disback="style='display:none;'";
					$disnext="style='display:none;'";
				}
		  } else {
		  ?>
          <tr>
            <td height="20" colspan="6" align="center" class="nodata">Tidak ada data</td>
          </tr>
          <?php 
		  }
		  ?>
        </table>
		<br>
		<?php if ($num>0){ ?>
		<table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
			<tr>
				<td width="30%" align="left" class="news_content1" colspan="2">Halaman
				<input <?=$disback?> type="button" class="cmbfrm2" name="back" value=" << " onClick="change_page('<?=(int)$this->page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
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
					/*for($a=0;$a<$pagenum;$a++){
						if ($this->page==$a){
							echo "<font face='verdana' color='red' size='4'><strong>".($a+1)."</strong></font> "; 
						} else { 
							echo "<a href='#' onClick=\"change_page('".$a."')\"><font face='verdana' color='green'>".($a+1)."</font></a> "; 
						}
							 
					}*/
					?>
					
				</td>
			</tr>
		</table>
<?php } ?>
        <?php
		$this->OnFinish();
		}
	}
	function CountPustaka(){
		$sql = "SELECT * FROM perpustakaan ORDER BY nama";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		return $num;
	}
}
?>