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
class CAktivitas{
	function OnStart(){
		$op=$_REQUEST['op'];
		if ($op=="del"){
			$sql = "DELETE FROM aktivitas WHERE replid='".$_REQUEST['id']."'";
			QueryDb($sql);
		}
		$sqlDate = "SELECT DATE_FORMAT(now(),'%d-%m-%Y')";
		$resultDate = QueryDb($sqlDate);
		$rowDate = @mysqli_fetch_row($resultDate);
		$this->tglAwal = $rowDate[0];
		if (isset($_REQUEST['tglAwal']))
			$this->tglAwal = $_REQUEST['tglAwal'];
		$this->tglAkhir = $rowDate[0];
		if (isset($_REQUEST['tglAkhir']))
			$this->tglAkhir = $_REQUEST['tglAkhir'];	
	}
	function OnFinish(){
		?>
		<script language='JavaScript'>
			Tables('table', 1, 0);
		</script>
		<?php
    }
    function Content(){
		$sql = "SELECT * FROM aktivitas WHERE tanggal BETWEEN '".MysqlDateFormat($this->tglAwal)." 00:00:00' AND '".MysqlDateFormat($this->tglAkhir)." 23:59:59' ORDER BY tanggal DESC";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		?>
		<link href="../sty/style.css" rel="stylesheet" type="text/css">
		<div class="filter">
        	<table width="100%" border="0" cellpadding="0">
              <tr>
                <td width="7%"><strong>Periode</strong></td>
                <td width="93%">
                <input class="inptxt" name="tglAwal" id="tglAwal" type="text" value="<?=$this->tglAwal?>" style="width:100px" readonly="readonly" />&nbsp;<a href="javascript:TakeDate('tglAwal')" >&nbsp;<img src="../img/ico/calendar.png" width="16" height="16" border="0" /></a>&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input class="inptxt" name="tglAkhir" id="tglAkhir" type="text" value="<?=$this->tglAkhir?>"  style="width:100px" readonly="readonly"/><a href="javascript:TakeDate('tglAkhir')" >&nbsp;<img src="../img/ico/calendar.png" width="16" height="16" border="0" /></a>&nbsp;&nbsp;<em>*dd-mm-yyyy</em>
                </td>
              </tr>
            </table>
        </div>
        <div class="funct">
        	<a href="javascript:getFresh()"><img src="../img/ico/refresh.png" border="0">&nbsp;Refresh</a>&nbsp;&nbsp;
			<a href="javascript:cetak()"><img src="../img/ico/print1.png" border="0">&nbsp;Cetak</a>&nbsp;&nbsp; 
			<?php if (IsAdmin()) { ?>
			<a href="aktivitas.add.php"><img src="../img/ico/tambah.png" border="0">&nbsp;Tambah&nbsp;Aktivitas</a>&nbsp;        
			<?php } ?>
		</div>
        <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table">
          <tr>
            <td width="50" height="30" align="center" class="header">No</td>
            <td width="80" height="30" align="left" class="header">Tanggal</td>
            <td width="*" height="30" align="left" class="header">Aktivitas</td>
            <?php if (IsAdmin()) { ?>
            <td width="50" height="30" align="center" class="header">&nbsp;</td>
			<?php } ?>
		  </tr>
          <?php
		  if ($num>0){
		  	  $cnt=0;	
			  while ($row=@mysqli_fetch_array($result)){
			  ?>
			  <tr>
				<td width="50" height="25" align="center"><?=++$cnt?></td>
				<td width="150" height="25" align="left">&nbsp;<?=substr((string) $row['tanggal'],8,2)."-".substr((string) $row['tanggal'],5,2)."-".substr((string) $row['tanggal'],0,4)." ".substr((string) $row['tanggal'],11,8)?></td>
				<td height="25" align="left">
			    <div align="justify"><?=chg_p_to_div(stripslashes((string) $row['aktivitas']))?></div>
                </td>
				<?php if (IsAdmin()) { ?>
				<td width="50" height="25" align="center" bgcolor="#FFFFFF"><a href="javascript:ubah('<?=$row['replid']?>')"><img src="../img/ico/ubah.png" width="16" height="16" border="0"></a>&nbsp;<a href="javascript:hapus('<?=$row['replid']?>')"><img src="../img/ico/hapus.png" border="0"></a></td>
				<?php } ?>
			  </tr>
			  <?php
			  }
		  } else {
		  ?>
          <tr>
            <td height="25" colspan="4" align="center" class="nodata">Tidak ada data</td>
          </tr>
		  <?php
		  }
		  ?>	
        </table>

        <?php
	}
}
?>