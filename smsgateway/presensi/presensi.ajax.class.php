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
class PresensiAjax{
	function OnStart(){
		//echo "<pre>";
		//print_r($_REQUEST);
		//echo "</pre>";
		$op = $_REQUEST['op'];
		//echo $op;
	
		//SourceNewFormat
		$dep = $_REQUEST['dep'];
		$this->dep = $dep;

		$NewFormat = CQ($_REQUEST['NewFormat']);
		$this->NewFormat = $NewFormat;
		
		if ($op=='getKelas')
			$this->getKelas();

		if ($op=='SaveFormat')
			$this->SaveFormat();
	}
	function getKelas(){
		global $db_name_akad;
		global $db_name_sdm;
		global $db_name_fina;
		global $db_name_user;
		$dep = $this->dep;
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
		$sql = "SELECT * FROM format WHERE tipe=0";
        $res = QueryDb($sql);
		$num = @mysqli_num_rows($res);
		if ($num>0)
			$sql = "UPDATE format SET format='$this->NewFormat' WHERE tipe=0";	
		else
			$sql = "INSERT INTO format SET format='$this->NewFormat',tipe=0";
		QueryDb($sql);
	}
}