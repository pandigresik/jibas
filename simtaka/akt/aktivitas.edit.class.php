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
class CAktivitasEdit{
	function OnStart(){
		$this->replid = $_REQUEST['replid'];
		$sql = "SELECT * FROM aktivitas WHERE replid=".$this->replid;
		$result = QueryDb($sql);
		$row = @mysqli_fetch_array($result);
		$this->perpustakaan = $row['perpustakaan'];
		$tgl = explode(' ',(string) $row['tanggal']);
		$this->tanggal = $tgl[0];
		$this->aktivitas = $row['aktivitas'];
		$sqlDate = "SELECT DATE_FORMAT(now(),'%Y-%m-%d'),DATE_FORMAT(now(),'%H:%i:%s')";
		$resultDate = QueryDb($sqlDate);
		$rowDate = @mysqli_fetch_row($resultDate);
		$this->tglInput = $rowDate[0];		
		$timeInput = $rowDate[1];
		if (isset($_REQUEST['simpan'])){
			$perpustakaan = trim(addslashes((string) $_REQUEST['perpustakaan']));
			$tanggal = trim(addslashes((string) $_REQUEST['tglInput']));
			$aktivitas = trim(addslashes((string) $_REQUEST['aktivitas']));
			$sql = "UPDATE aktivitas SET perpustakaan='$perpustakaan',tanggal='".MysqlDateFormat($tanggal)." ".$timeInput."',aktivitas='$aktivitas' WHERE replid=".$this->replid;
			//echo $sql;exit;
			$result = QueryDb($sql);
			if ($result)
				$this->success();
		}

	}
	function success(){
		?>
        <script language="javascript">
			document.location.href='aktivitas.php';
        </script>
        <?php
	}
	function add(){
		?>
        <form enctype="multipart/form-data" name="editaktivitas" action="aktivitas.edit.php" onSubmit="return validate()" method="post">
		<input name="replid" id="replid" type="hidden" value="<?=$this->replid?>" />
		<table width="400" border="0" cellspacing="2" cellpadding="2" align="center">
          <tr>
            <td colspan="2" align="left">
            	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        		<font style="font-size:18px; color:#999999">Ubah Aktivitas</font></td>
  		  </tr>
          <tr>
            <td>&nbsp;<strong>Perpustakaan</strong></td>
            <td><?=$this->GetPerpus(); ?></td>
          </tr>
          <tr>
            <td>&nbsp;<strong>Tanggal</strong></td>
            <td>
           	  <input class="inptxt" name="tglInput" id="tglInput" type="text" value="<?=RegularDateFormat($this->tanggal)?>" style="width:100px" readonly="readonly" />&nbsp;
                <a href="javascript:TakeDate('tglInput')" >
                	&nbsp;<img src="../img/ico/calendar.png" width="16" height="16" border="0" />
                </a>
            </td>
          </tr>
          <tr>
            <td width="6%">&nbsp;<strong>Aktivitas</strong></td>
            <td width="94%"><textarea name="aktivitas" cols="80" rows="25" class="areatxt" id="aktivitas"><?=stripslashes((string) $this->aktivitas)?></textarea></td>
          </tr>
          <tr>
            <td colspan="2" align="center"><input type="submit" class="cmbfrm2" name="simpan" value="Simpan" >&nbsp;<input type="button" class="cmbfrm2" name="batal" value="Batal" onClick="document.location.href='aktivitas.php'" ></td>
          </tr>
        </table>
		</form>
		<?php
	}
	function GetPerpus(){
		//$this->perpustakaan = $_REQUEST['perpustakaan'];
		if (SI_USER_LEVEL()==2){
			$sql = "SELECT replid,nama FROM perpustakaan WHERE replid='".SI_USER_IDPERPUS()."' ORDER BY nama";
		} else {
			$sql = "SELECT replid,nama FROM perpustakaan ORDER BY nama";
		}
		$result = QueryDb($sql);
		?>
		<link href="../sty/style.css" rel="stylesheet" type="text/css" />
		<select name="perpustakaan" id="perpustakaan" class="cmbfrm"  >
		<?php
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
}
?>