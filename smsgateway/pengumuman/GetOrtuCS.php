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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');

OpenDb();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td style="padding:5px">
    
    <table border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td style="padding-right:4px">Kelas</td>
        <td class="td">
            <select id="CmbDepOrtuCS" name="CmbDepOrtuCS" class="Cmb" onchange="ChgCmbDepOrtuCS()">
<?php          $sql = "SELECT departemen
                      FROM $db_name_akad.departemen
                     WHERE aktif=1
                     ORDER BY urutan";
            $res = QueryDb($sql);
            while ($row = @mysqli_fetch_row($res))
            {
                if ($dep == "")
					$dep = $row[0];	?>
                <option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $dep)?>><?=$row[0]?></option>
<?php          }   ?>
            </select>
        </td>
		<td class="td">
            <div id="DivCmbProsesOrtuCS">
			<select id="CmbProsesOrtuCS" name="CmbProsesOrtuCS" class="Cmb" onchange="ChgCmbProsesOrtuCS()">
<?php 		$sql = "SELECT replid, proses
                      FROM $db_name_akad.prosespenerimaansiswa
					 WHERE departemen = '$dep'
                       AND aktif = 1
                     ORDER BY proses"; 
            $res = QueryDb($sql);
			while ($row = @mysqli_fetch_row($res))
            {
				if ($proses == "")
					$proses = $row[0];	?>
                <option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $proses)?>><?=$row[1]?></option>
<?php 		}	?>
			</select>
			</div>
        </td>
        <td class="td">
			<div id="DivCmbKelompokOrtuCS">
			<select id="CmbKelompokOrtuCS" name="CmbKelompokOrtuCS" class="Cmb" onchange="ChgCmbKelompokOrtuCS()">
<?php 		$sql = "SELECT replid, kelompok
                      FROM $db_name_akad.kelompokcalonsiswa
					 WHERE idproses = '$proses'
                     ORDER BY kelompok"; 
            $res = QueryDb($sql);
			while ($row = @mysqli_fetch_row($res))
            {
				if ($kelompok == "")
					$kelompok = $row[0];	?>
                <option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $kelompok)?>><?=$row[1]?></option>
<?php 		}	?>
			</select>
            </div>
        </td>
		<td class="td">	
			<div class="Btn" onclick="ChgCmbKelompokOrtuCS()" align="center" />Lihat</div>
		</td>
		<td style="padding-right:4px" valign="top" class="tdTop">&nbsp;&nbsp;&nbsp;Cari&nbsp;</td>
        <td class="td">
            <input type="text" name="InpNoOrtuCS" id="InpNoOrtuCS" class="InputTxt" value='No Calon Siswa'
                   onfocus="InputHover('No Calon Siswa','InpNoOrtuCS','1')"
                   onblur="InputHover('No Calon Siswa','InpNoOrtuCS','0')"
                   style="color:#636363" size='10' />
        </td>
        <td class="td">
            <input type="text" name="InpNamaOrtuCS" id="InpNamaOrtuCS" class="InputTxt"  value='Nama Calon Siswa'
                   onfocus="InputHover('Nama Calon Siswa','InpNamaOrtuCS','1')"
                   onblur="InputHover('Nama Calon Siswa','InpNamaOrtuCS','0')"
                   style="color:#636363" size='15' />  
        </td>
		<td align="center" valign="middle" class="td">
			<div class="Btn" onclick="SearchOrtuCS()" align="center" />Cari</div>
		</td>
	</tr>
	<tr>
		<td colspan='8' align='center'>
			
            <table cellpadding='0' cellspacing='0' border='0'>
			<tr>
				<td>
					<div id="ErrInpNoOrtuCS" class="ErrMsg"></div>
                    <div id="ErrInpNamaOrtuCS" class="ErrMsg"></div>
				</td>
				</tr>
			</table>
		</td>
	</tr>
    </table>
    
    </td>
</tr>
<tr>
    <td>
        
    <div id="TableOrtuCalonSiswa">
        
    <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="TableOrCS">
    <tr class="Header">
        <td width='50'>No</td>
        <td width='120'>No.Pendaftaran</td>
		<td>Nama Calon Siswa</td>
        <td>Nama Ortu Calon Siswa</td>
        <td>HP Ortu Calon Siswa</td>
        <td><input type="checkbox" id="CheckAllOrtuCS"></td>
    </tr>
<?php $sql = "SELECT nopendaftaran, nama, namaayah, hportu, info1, info2, info3
			  FROM $db_name_akad.calonsiswa
			 WHERE aktif=1
			   AND idkelompok = '$kelompok'
			 ORDER BY nama";
       
	$res = QueryDb($sql);
	$num = @mysqli_num_rows($res);
	if ($num > 0)
	{
		$cnt = 1;
		while ($row = @mysqli_fetch_array($res))
		{
			$n = 0;  
			$hparr = [];
				
			$temp = trim((string) $row['hportu']);
			if (strlen($temp) >= 7 && !str_starts_with($temp, "#"))
			{
				$hparr[$n] = $temp;
				$n += 1;
			}
				
            $temp = trim((string) $row['info1']);  
            if (strlen($temp) >= 7 && !str_starts_with($temp, "#"))
            {
                $hparr[$n] = $temp;
                $n += 1;
            }
				  
            $temp = trim((string) $row['info2']);    
            if (strlen($temp) >= 7 && !str_starts_with($temp, "#"))
                $hparr[$n] = $temp;
				
            $namaortu = $row['namaayah'];
            if (strlen((string) $row['namaayah']) == 0)
                $namaortu = "Ortu " . $row['nama'];  
			
            $nama = $row['nama'];
            
            for($j = 0; $j < count($hparr); $j++)
            {
                $hp = $hparr[$j]; ?>
                <tr>
                    <td align="center" class="td"><?=$cnt?></td>
                    <td class="td" align="center"><?=$row['nopendaftaran']?></td>
                    <td class="td" ><?=$nama?></td>
                    <td class="td" ><?=$namaortu?></td>
                    <td class="td"><?=$hp?></td>
                    <td class="td" align="center">
                        <input type="checkbox" class="checkboxortucs" hp="<?=$hp?>"
                               nama="Ortu <?=$nama?>" nip="<?=$row['nopendaftaran']?>" pinayah="<?=$row['info3']?>"
                               pinibu="<?=$row['info3']?>" pin="<?=$row['info3']?>">
                    </td>			
                </tr>
<?php 			$cnt++;	
            } // end for 			
		} // end while
	}
    else
    {	?>
        <tr>
            <td colspan="5" class="Ket" align="center">Tidak ada data</td>
        </tr>
<?php }	?>
    </table>
    
	</div>
    </td>
  </tr>
</table>
<?php
CloseDb();
?>