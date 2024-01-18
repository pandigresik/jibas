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
            <select id="CmbDepCSis" name="CmbDepCSis" class="Cmb" onchange="ChgCmbDepCSis()">
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
			<div id="DivCmbProsesCS">
			<select id="CmbProsesCS" name="CmbProsesCS" class="Cmb" onchange="ChgCmbProsesCS()">
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
			<div id="DivCmbKelompokCS">
			<select id="CmbKelompokCS" name="CmbKelompokCS" class="Cmb" onchange="ChgCmbKelompokCS()">
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
			<div class="Btn" onclick="ChgCmbKelompokCS()" align="center" />Lihat</div>
		</td>
		<td style="padding-right:4px" valign="top" class="tdTop">&nbsp;&nbsp;&nbsp;Cari&nbsp;</td>
        <td class="td">
            <input type="text" name="InpNoCS" id="InpNoCS" class="InputTxt"
                   value="No Calon Siswa"
                   onfocus="InputHover('No Calon Siswa', 'InpNoCS','1')"
                   onblur="InputHover('No Calon Siswa', 'InpNoCS','0')"
                   style="color:#636363"
                   size='10' />
        </td>
        <td class="td">
            <input type="text" name="InpNamaCS" id="InpNamaCS" class="InputTxt"
                   value="Nama Calon Siswa"
                   onfocus="InputHover('Nama Calon Siswa', 'InpNamaCS','1')"
                   onblur="InputHover('Nama Calon Siswa', 'InpNamaCS','0')"
                   style="color:#636363"
                   size='15' />  
        </td>
		<td align="center" valign="middle" class="td">
			<div class="Btn" onclick="SearchCS()" align="center" />Cari</div>
		</td>
    </tr>
	<tr>
		<td colspan='8' align='center'>
		<table cellpadding='0' cellspacing='0' border='0'>
		<tr>
			<td>
				<div id="ErrInpNoCS" class="ErrMsg"></div>
                <div id="ErrInpNamaCS" class="ErrMsg"></div>
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
    <div id="TableCalonSiswa">
    <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="TableCSis">
    <tr class="Header">
        <td width='50'>No</td>
        <td width='120'>No.Pendaftaran</td>
		<td>Nama</td>
        <td>HP Calon Siswa</td>
        <td><input type="checkbox" id="CheckAllCalonSiswa"></td>
    </tr>
<?php $sql = "SELECT *
              FROM $db_name_akad.calonsiswa
             WHERE aktif=1
               AND idkelompok='$kelompok'
             ORDER BY nama";
	$res = QueryDb($sql);
	$num = @mysqli_num_rows($res);
	if ($num > 0)
	{
		$cnt = 1;
		while ($row = @mysqli_fetch_array($res))
		{
			$hp = trim((string) $row['hpsiswa']);
			if (strlen($hp) < 7)
				continue;
			if (str_starts_with($hp, "#"))
				continue;  ?>
            <tr>
                <td align="center" class="td"><?=$cnt?></td>
                <td class="td" align="center"><?=$row['nopendaftaran']?></td>
        		<td class="td" ><?=$row['nama']?></td>
                <td class="td"><?=$hp?></td>
                <td class="td" align="center">
                    <input type="checkbox" class="checkboxcalonsiswa"
                           hp="<?=$hp?>" nama="<?=$row['nama']?>" nip="<?=$row['nopendaftaran']?>"
                           pin="<?=$row['info3']?>">
                </td>
            </tr>
<?php 		$cnt++;
		}
	}
    else
    {   ?>
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