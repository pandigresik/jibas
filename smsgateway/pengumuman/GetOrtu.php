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
<link href="style/style.css" rel="stylesheet" type="text/css" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="padding:5px">
    
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="padding-right:4px">Kelas</td>
            <td class="td">
              <select id="CmbDepOrtu" name="CmbDepOrtu" class="Cmb" onchange="ChgCmbDepOrtu()">
                	<?php
                    $sql = "SELECT departemen FROM $db_name_akad.departemen WHERE aktif=1 ORDER BY urutan";
                    $res = QueryDb($sql);
                    while ($row = @mysqli_fetch_row($res)){
                    if ($dep=="")
						$dep=$row[0];
					?>
                <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$dep)?>><?=$row[0]?></option>
                	<?php
                    }
                    ?>
                </select>
            </td>
			<td class="td">
            	<div id="DivCmbKelasOrtu">
					<select id="CmbKlsOrtu" name="CmbKlsOrtu" class="Cmb" onchange="ChgCmbKlsOrtu()">
						<?php
						$sql = "SELECT DISTINCT k.replid, CONCAT(ti.tingkat, ' - ', k.kelas) FROM $db_name_akad.kelas k, $db_name_akad.tingkat ti, $db_name_akad.tahunajaran ta ".
							   "WHERE k.aktif=1 AND ta.aktif=1 AND ti.aktif=1 AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid ".
							   "AND ta.departemen='$dep' AND ti.departemen='$dep' ORDER BY ti.urutan, k.kelas";
						$res = QueryDb($sql);
						while ($row = @mysqli_fetch_row($res)){
						if ($kls=="")
							$kls=$row[0];
						?>
					<option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$kls)?>><?=$row[1]?></option>
						<?php
						}
						?>
					</select>
				</div>
            </td>
			<td class="td">	
				<div class="Btn" onclick="ChgCmbKlsOrtu()" align="center" />Lihat</div>
			</td>
			<td style="padding-right:4px" valign="top" class="tdTop">&nbsp;&nbsp;&nbsp;Cari&nbsp;</td>
            <td class="td">
            	<input type="text" name="InpNISOrtu" id="InpNISOrtu" class="InputTxt" value='NIS Siswa'  onfocus="InputHover('NIS Siswa','InpNISOrtu','1')" onblur="InputHover('NIS Siswa','InpNISOrtu','0')" style="color:#636363" size='10' />
            </td>
            <td class="td">
            	<input type="text" name="InpNamaOrtu" id="InpNamaOrtu" class="InputTxt"  value='Nama Siswa'  onfocus="InputHover('Nama Siswa','InpNamaOrtu','1')" onblur="InputHover('Nama Siswa','InpNamaOrtu','0')" style="color:#636363" size='15' />  
            </td>
			<td align="center" valign="middle" class="td">
			<div class="Btn" onclick="SearchOrtu()" align="center" />Cari</div>
			</td>
		  </tr>
		  <tr>
			<td colspan='8' align='center'>
				<table cellpadding='0' cellspacing='0' border='0'>
					<tr>
						<td>
							<div id="ErrInpNISOrtu" class="ErrMsg"></div><div id="ErrInpNamaOrtu" class="ErrMsg"></div>
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
    <div id="TableOrtu">
    <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="TableOr">
      <tr class="Header">
        <td width='50'>No</td>
        <td width='100'>NIS</td>
		<td>Nama Siswa</td>
		<td>Nama Ortu Siswa</td>
        <td>HP Ortu Siswa</td>
        <td><input type="checkbox" id="CheckAllOrtu"></td>
      </tr>
<?php 	$sql = "SELECT nis, nama, namaayah, hportu, info1, info2, pinortu, pinortuibu, pinsiswa
				  FROM $db_name_akad.siswa
				 WHERE aktif=1
				   AND idkelas='$kls'
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
				  $namaortu = $row['nama'];  
				
				$nama = $row['nama'];
				
				for($j = 0; $j < count($hparr); $j++)
				{
					$hp = $hparr[$j]; ?>
					<tr>
					  <td align="center" class="td"><?=$cnt?></td>
					  <td class="td" align="center"><?=$row['nis']?></td>
					  <td class="td" ><?=$nama?></td>
					  <td class="td" ><?=$namaortu?></td>
					  <td class="td"><?=$hp?></td>
					  <td class="td" align="center">
						<input type="checkbox" class="checkboxortu" hp="<?=$hp?>"
							   nama="Ortu <?=$nama?>" nip="<?=$row['nis']?>" pinayah="<?=$row['pinsiswa']?>"
							   pinibu="<?=$row['pinsiswa']?>">
					  </td>			
					</tr>
<?php 				$cnt++;	
				} // end for 			
			} // end while
		} else {
		?>
      <tr>
        <td colspan="5" class="Ket" align="center">Tidak ada data</td>
      </tr>
      <?php
		}
			?>
    </table>
	</div>
    </td>
  </tr>
</table>
<?php
CloseDb();
?>