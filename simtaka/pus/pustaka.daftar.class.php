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
		if ($op == "Gtytc6yt665476d6")
		{
		    $idpustaka = $_REQUEST['replid'];
		    $idperpus = $_REQUEST['perpustakaan'];

		    $sqlperpus = "";
		    if ($idperpus != -1)
                $sqlperpus = "AND perpustakaan = $idperpus";

			$sql = "SELECT COUNT(*) 
                      FROM daftarpustaka 
                     WHERE pustaka='$idpustaka' 
                           $sqlperpus";
			$res = QueryDb($sql);
			$row = mysqli_fetch_row($res);
			$numdel = $row[0];

			$sql = "SELECT katalog 
                      FROM pustaka 
                     WHERE replid='$idpustaka'";
			$res = QueryDb($sql);
			$row = mysqli_fetch_array($res);
			$idkatalog = $row['katalog'];

			$sql = "SELECT counter 
                      FROM katalog 
                     WHERE replid = '".$idkatalog."'";
            $res = QueryDb($sql);
            $row = mysqli_fetch_row($res);
			$counter = $row[0];

            $sql = "DELETE FROM daftarpustaka 
                     WHERE pustaka='$idpustaka' 
                           $sqlperpus";
            QueryDb($sql);

			$diff = $counter - $numdel;
			$sql = "UPDATE katalog 
                       SET counter=$diff 
                     WHERE replid='$idkatalog'";
			QueryDb($sql);

			$sql = "SELECT COUNT(*) 
                      FROM daftarpustaka 
                     WHERE pustaka='$idpustaka' 
                           $sqlperpus";
            $res = QueryDb($sql);
            $row = mysqli_fetch_row($res);
			$count = $row[0];
			if ($count == 0)
			{
				$sql = "DELETE FROM pustaka 
                         WHERE replid='$idpustaka'";
				QueryDb($sql);
			}
		}
		
		$this->numlines = 15;
		$this->page = 0;
		if (isset($_REQUEST['page']))
		{
			$this->page = $_REQUEST['page'];
		}
		
		$this->perpustakaan = '-1';
		if (isset($_REQUEST['perpustakaan']))
			$this->perpustakaan = $_REQUEST['perpustakaan'];
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
		?>
		<script language='JavaScript'>
			Tables('table', 1, 0);
		</script>
		<?php
    }
	
	function GetPerpus()
	{
		if (SI_USER_LEVEL()==2)
			$sql = "SELECT replid,nama FROM perpustakaan WHERE replid='".SI_USER_IDPERPUS()."' ORDER BY nama";
		else 
			$sql = "SELECT replid,nama FROM perpustakaan ORDER BY nama";
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
	
    function Content(){
		?>
		<link href="../sty/style.css" rel="stylesheet" type="text/css">
        <div align="left">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="59%">Perpustakaan <?=$this->GetPerpus()?></td>
            <td width="41%" align="right"><a href="javascript:chg_perpus()"><img src="../img/ico/refresh.png" width="16" height="16" border="0" />&nbsp;Refresh</a>&nbsp;&nbsp;<a href="javascript:cetak('XX')"><img src="../img/ico/print1.png" border="0" />&nbsp;Cetak</a></td>
          </tr>
        </table>

        	
        </div><br />
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
		  $filter="";
		  if ($this->perpustakaan!='-1')
			$filter=" AND d.perpustakaan=".$this->perpustakaan;	
          $sql = "SELECT p.replid, p.judul FROM pustaka p, daftarpustaka d WHERE d.pustaka=p.replid $filter GROUP BY d.pustaka ORDER BY p.judul"; 
		  //echo $sql;
		  $result = QueryDb($sql);
		  //$pagenum = @mysqli_num_rows($result);
		  $pagenum = ceil(mysqli_num_rows($result)/(int)$this->numlines);

		  $sql = "SELECT p.replid, p.judul, p.katalog  FROM pustaka p, daftarpustaka d WHERE d.pustaka=p.replid $filter GROUP BY d.pustaka ORDER BY p.judul LIMIT ".(int)$this->page*(int)$this->numlines.",".$this->numlines;
		  $result = QueryDb($sql);
		  $num = @mysqli_num_rows($result);
		  if ($num>0){
			  if ($this->page==0)
				$cnt=1;
			  else
				$cnt=$this->page*$this->numlines+1;	
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
				$rdipinjam = @mysqli_num_rows(QueryDb("SELECT * FROM daftarpustaka d WHERE d.pustaka='".$row[0]."' $filter AND d.status=0"));
				$rtersedia = @mysqli_num_rows(QueryDb("SELECT * FROM daftarpustaka d WHERE d.pustaka='".$row[0]."' $filter AND d.status=1"));
			  ?>
			  <tr height='25'>
				<td align="center"><?=$cnt?></td>
				<td align="left"><?=$kode . " - " . $katalog?></td>
				<td ><div class="tab_content"><?=stripslashes((string) $row[1])?></div></td>
				<td align="center"><?=$rtersedia?></td>
				<td align="center"><?=$rdipinjam?></td>
				<td align="center">
					<a title="Cetak Label" href="javascript:cetak_nomor('<?=$row[0]?>','<?=$this->perpustakaan?>')">
						<img src="../img/barcode.png" height="18" border="0" />
					</a>
				</td>
				<td align="center">
					<a title="Penambahan dan Pengurangan Pustaka" href="javascript:aturpustaka('<?=$row[0]?>', '<?=$this->perpustakaan?>')">
						<img src="../img/book.png" height="18" border="0" />
					</a>
				</td>
				<td height="25" align="center">               	  
					<a title="Lihat Detail" href="javascript:lihat(<?=$row[0]?>)">
						<img src="../img/ico/lihat.png" width="16" height="16" border="0" />
					</a>
					&nbsp;
					<a href="javascript:ubah('<?=$row[0]?>','daftar','','','')">
						<img src="../img/ico/ubah.png" width="16" height="16" border="0" />
					</a>
					&nbsp;
                    <?php if (SI_USER_LEVEL()!=2) { ?>
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
				</td>
			</tr>
		</table>
        <?php } ?>
		<?php
	}
	function CountPustaka(){
		$sql = "SELECT * FROM perpustakaan ORDER BY nama";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		return $num;
	}
}
?>