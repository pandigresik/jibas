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
$bag = $_REQUEST['Bagian'];
if ($bag=="")
	$bag='-1';
$NIP 	= $_REQUEST['NIP'];
$Nama 	= $_REQUEST['Nama'];
$Source = $_REQUEST['Source'];
if ($Source=='' || $Source=='Pilih'){
	$DispA = "style='display:block'";
	$DispB = "style='display:none'";
} elseif ($Source=='Cari'){
	$DispA = "style='display:none'";
	$DispB = "style='display:block'";
}
?>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="padding:5px">
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="padding-right:4px">Bagian</td>
            <td class="td">
              <select id="CmbBagPeg" name="CmbBagPeg" class="Cmb" onchange="ChgCmbBagPeg(this.value)">
                <option value="-1" <?=StringIsSelected('-1',$bag)?>>- Semua -</option>
                <?php
                    $sql = "SELECT bagian FROM $db_name_sdm.bagianpegawai";
                    $res = QueryDb($sql);
                    while ($row = @mysqli_fetch_row($res)){
                    ?>
                <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$bag)?>><?=$row[0]?></option>
                <?php
                    }
                    ?>
                </select>
            </td>
			<td class="td">
			<div class="Btn" onclick="ChgCmbBagPeg(document.getElementById('CmbBagPeg').value)" align="center" />Lihat</div>
			</td>
			<td style="padding-right:4px" valign="top" class="tdTop">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cari</td>
            <td class="td">
            	<input type="text" name="InpNIPPeg" id="InpNIPPeg" class="InputTxt"  value="NIP Pegawai" onfocus="InputHover('NIP Pegawai','InpNIPPeg','1')" onblur="InputHover('NIP Pegawai','InpNIPPeg','0')" style="color:#636363" size='10' />
            </td>
            <td class="td">
            	<input type="text" name="InpNamaPeg" id="InpNamaPeg" class="InputTxt" value="Nama Pegawai" onfocus="InputHover('Nama Pegawai','InpNamaPeg','1')" onblur="InputHover('Nama Pegawai','InpNamaPeg','0')" style="color:#636363" size='15' />  
            </td>
			<td align="center" valign="middle" class="td">
			<div class="Btn" onclick="SearchPeg()" align="center" />Cari</div>
			</td>
            
          </tr>
		  <tr>
			<td colspan='7' align='center'>
				<table cellpadding='0' cellspacing='0' border='0'>
					<tr>
						<td>
							<div id="ErrInpNIPPeg" class="ErrMsg"></div><div id="ErrInpNamaPeg" class="ErrMsg"></div>
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
    <div id="TablePegawai">
    <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="TablePeg">
      <tr class="Header">
        <td height='20' width='50'>No</td>
        <td height='20' width='100'>NIP</td>
		<td height='20'>Nama</td>
        <td height='20'>No. Ponsel</td>
        <td height='20'><input type="checkbox" id="CheckAllPegawai"></td>
      </tr>
      <?php
		if ($bag=='-1')
			$sql = "SELECT * FROM $db_name_sdm.pegawai ORDER BY nama";
		else
			$sql = "SELECT * FROM $db_name_sdm.pegawai WHERE bagian='$bag' ORDER BY nama";
		$res = QueryDb($sql);
		$num = @mysqli_num_rows($res);
		if ($num>0){
			$cnt=1;
			while ($row = @mysqli_fetch_array($res)){
	  ?>
      <tr>
        <td align="center" class="td"><?=$cnt?></td>
        <td class="td" align="center"><?=$row['nip']?></td>
		<td class="td"><?=$row['nama']?></td>
        <td class="td"><?=$row['handphone']?></td>
        <td class="td" align="center">
        <?php if (strlen((string) $row['handphone'])>0){ ?>
        <!--<span style="cursor:pointer" class="Link" onclick="InsertNewReceipt('<?=$row['handphone']?>','<?=$row['nama']?>','<?=$row['nip']?>')" align="center" />Pilih</span>-->
		<input type="checkbox" class="checkboxpegawai" hp="<?=$row['handphone']?>" nama="<?=$row['nama']?>" nip="<?=$row['nip']?>" pin="<?=$row['pinpegawai']?>">
        <?php } ?>
        </td>
      </tr>
      <?php
			$cnt++;
			}
		} else {
		?>
      <tr>
        <td colspan="4" class="Ket" align="center">Tidak ada data</td>
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