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
class PenilaianAjax{
	function OnStart(){
		//echo "<pre>";
		//print_r($_REQUEST);
		//echo "</pre>";
		$op = $_REQUEST['op'];
		//echo $op;
	
		//Source
		$DepPel = $_REQUEST['DepPel'];
		$this->DepPel = $DepPel;
		$NewFormat = $_REQUEST['NewFormat'];
		$this->NewFormat = $NewFormat;
		$PelPel = $_REQUEST['PelPel'];
		$this->PelPel = $PelPel;
		$Dep = $_REQUEST['Dep'];
		$this->Dep = $Dep;
		//getKelas
		if ($op=='getPelInfo')
			$this->getPelInfo();
		if ($op=='getUjiInfo')
			$this->getUjiInfo();
		if ($op=='SaveFormat')
			$this->SaveFormat();
		if ($op=='getKelas')
			$this->getKelas();
	}
	function getPelInfo(){
		global $db_name_akad;
		global $db_name_sdm;
		global $db_name_fina;
		global $db_name_user;
		$DepPel = $this->DepPel;
		?>
        <select id="PelPel" class="Cmb" onchange="ChgPelPel()">
		<option value="-1">- Semua -</option>
		<?php
		//SELECT * FROM `demo_jbsakad`.`jenisujian`;
		$sql = "SELECT replid,nama FROM $db_name_akad.pelajaran WHERE aktif=1 AND departemen='$DepPel'";
		$res = QueryDb($sql);
		while ($row = @mysqli_fetch_row($res)){
			?>
			<option value="<?=$row[0]?>" ><?=$row[1]?></option>
			<?php
		}
		?>
		</select>
        <?php
	}
	function getUjiInfo(){
		global $db_name_akad;
		global $db_name_sdm;
		global $db_name_fina;
		global $db_name_user;
		$PelPel = $this->PelPel;
		?>
        <select id="UjiPel" class="Cmb">
		<option value="-1">- Semua -</option>
		<?php
		if ($PelPel!='-1'){
			//SELECT * FROM `demo_jbsakad`.`jenisujian`;
			$sql = "SELECT replid,jenisujian FROM $db_name_akad.jenisujian WHERE idpelajaran='$PelPel'";
			$res = QueryDb($sql);
			while ($row = @mysqli_fetch_row($res)){
				?>
				<option value="<?=$row[0]?>"><?=$row[1]?></option>
				<?php
			}
		}
		?>
		</select>
        <?php
	}
	function getKelas(){
		global $db_name_akad;
		global $db_name_sdm;
		global $db_name_fina;
		global $db_name_user;
		$dep = $this->Dep;
		?>
        <select id="kls" class="Cmb">
        <option value="-1" <?=StringIsSelected('-1',$kls)?>>- Semua -</option>
        <?php
        $sql = "SELECT k.replid, k.kelas FROM $db_name_akad.kelas k,$db_name_akad.tahunajaran ta,$db_name_akad.tingkat ti WHERE k.aktif=1 AND ta.aktif=1 AND ti.aktif=1 AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ta.departemen='$dep' AND ti.departemen='$dep' ORDER BY ti.urutan,k.kelas";
        $res = QueryDb($sql);
        while ($row = @mysqli_fetch_row($res)){
            ?>
            <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$kls)?>><?=$row[1]?></option>
            <?php
        }
        ?>
        </select>
        <?php
	}
	function SaveFormat(){
		$sql = "SELECT * FROM format WHERE tipe=1";
        $res = QueryDb($sql);
		$num = @mysqli_num_rows($res);
		if ($num>0)
			$sql = "UPDATE format SET format='$this->NewFormat' WHERE tipe=1";	
		else
			$sql = "INSERT INTO format SET format='$this->NewFormat',tipe=1";
		QueryDb($sql);
	}
}