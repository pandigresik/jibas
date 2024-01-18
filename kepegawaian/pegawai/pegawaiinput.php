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
require_once("../include/sessionchecker.php");
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");
require_once("pegawaiinput.class.php");

OpenDb();
$P = new PegawaiInput();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="pegawaiinput.js"></script>
<style type="text/css">
.style1 { color: #666666; font-style: italic; }
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#ffffff">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>
<form method="post" enctype="multipart/form-data" name="main" onSubmit="return validate()">
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="right" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:24px">&nbsp;&nbsp;</font>
        <font class="subtitle">Input Pegawai</font><br />
        <a href="pegawai.php">Kepegawaian</a> &gt; Input Pegawai
    </td>
</tr>
<tr><td>

<table border="0" cellpadding="5" cellspacing="0" width="100%">
<?php if (strlen((string) $P->ERRMSG) > 0) { ?>
<tr>
	<td colspan="2" align="center">
        <table border="1" style="border-style:dashed; border-color:#CC0000; background-color:#FFFFCC" width="300">
        <tr><td align="center"><?=$P->ERRMSG?></td></tr>
        </table>    </td>
</tr>
<?php } ?>
<tr>
	<td align="right" valign="top"><strong>Status </strong>:</td>
    <td width="*" align="left" valign="top">
	 <input type="radio" name="rbPNS" id="rbPNS" value="PNS" <?=StringIsChecked("PNS", $P->pns)?> />&nbsp;PNS&nbsp;&nbsp;
	 <input type="radio" name="rbPNS" id="rbPNS" value="CPNS" <?=StringIsChecked("CPNS", $P->pns)?>/>&nbsp;CPNS&nbsp;&nbsp;
	 <input type="radio" name="rbPNS" id="rbPNS" value="HONORER" <?=StringIsChecked("HONORER", $P->pns)?>/>&nbsp;Honorer&nbsp;
	 <input type="radio" name="rbPNS" id="rbPNS" value="SWASTA" <?=StringIsChecked("SWASTA", $P->pns)?>/>&nbsp;Swasta&nbsp;
    </td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Bagian </strong>:</td>
    <td width="*" align="left" valign="top">
<?php  $sql = "SELECT bagian FROM jbssdm.bagianpegawai ORDER BY urutan";
	 $res = QueryDb($sql);
	 $checked = "checked='checked'";
	 while ($row = @mysqli_fetch_row($res))
	 {
		 echo "<input type='radio' $checked name='rbBagian' id='rbBagian' value='".$row[0]."' " .  StringIsChecked($row[0], $P->bagian) . " />&nbsp;$row[0]&nbsp;";
		 $checked = "";
	 } ?>
    </td>
</tr>
<tr>
	<td width="140" align="right" valign="top"><strong>Nama </strong>:</td>
    <td width="*" align="left" valign="top">
    
    <table border="0" cellpadding="0" cellspacing="0">
    <tr>
    	<td width="80"><input type="text" name="txGelarAwal" id="txGelarAwal" size="10" maxlength="45" value="<?=$P->gelarawal?>" onKeyPress="return focusNext('txNama', event)"/></td>
        <td width="260"><input type="text" name="txNama" id="txNama" size="40" maxlength="255" value="<?=$P->nama?>" onKeyPress="return focusNext('txGelarAkhir', event)"/></td>
        <td width="120"><input type="text" name="txGelarAkhir" id="txGelarAkhir" size="10" maxlength="45" value="<?=$P->gelarakhir?>" onKeyPress="return focusNext('txPanggilan', event)"/></td>
    </tr>
    <tr>
    	<td><font color="#999999"><em>gelar depan</em></font>&nbsp;</td>
        <td>&nbsp;</td>
        <td><font color="#999999"><em>gelar belakang</em></font></td>
    </tr>
    </table>
	
	</td>
</tr>
<tr>
	<td width="140" align="right" valign="top"><strong>Panggilan </strong>:</td>
    <td width="*" align="left" valign="top">
	 <input type="text" name="txPanggilan" id="txPanggilan" size="40" maxlength="255" value="<?=$P->panggilan?>" onKeyPress="return focusNext('txNIP', event)"/>
    </td>
</tr>
<tr>
	<td align="right" valign="top"><strong>NIP </strong>:</td>
    <td width="*" align="left" valign="top">
		<input type="text" name="txNIP" id="txNIP" size="20" maxlength="30" value="<?=$P->nip?>" onKeyPress="return focusNext('txNUPTK', event)"/>
	</td>
</tr>
<tr>
	<td align="right" valign="top">NUPTK :</td>
    <td width="*" align="left" valign="top">
		<input type="text" name="txNUPTK" id="txNUPTK" size="20" maxlength="30" value="<?=$P->nuptk?>" onKeyPress="return focusNext('txNRG', event)"/>
	</td>
</tr>
<tr>
	<td align="right" valign="top">NRP :</td>
    <td width="*" align="left" valign="top">
		<input type="text" name="txNRP" id="txNRP" size="20" maxlength="30" value="<?=$P->nrg?>" onKeyPress="return focusNext('txTmpLahir', event)"/>
	</td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Tempat, Tgl Lahir </strong>:</td>
    <td width="*" align="left" valign="top">
    <input type="text" name="txTmpLahir" id="txTmpLahir" size="20" maxlength="25" value="<?=$P->tmplahir?>" onKeyPress="return focusNext('cbTglLahir', event)"/>, 
    <select id="cbTglLahir" name="cbTglLahir" onKeyPress="return focusNext('cbBlnLahir', event)">
<?php for ($i = 1; $i <= 31; $i++) { ?>    
    	<option value="<?=$i?>" <?=IntIsSelected($i, $P->tgllahir)?>><?=$i?></option>	
<?php } ?>    
	</select>
    <select id="cbBlnLahir" name="cbBlnLahir" onKeyPress="return focusNext('txThnLahir', event)">
<?php for ($i = 1; $i <= 12; $i++) { ?>    
    	<option value="<?=$i?>" <?=IntIsSelected($i, $P->blnlahir)?>><?=NamaBulan($i)?></option>	
<?php } ?>    
	</select>
    <input type="text" name="txThnLahir" id="txThnLahir" size="4" maxlength="4" value="<?=$P->thnlahir?>" onKeyPress="return focusNext('cbAgama', event)"/>    </td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Agama :</strong>    </td>
    <td width="*" align="left" valign="top">
	<span id="agama_info">
    <select name="cbAgama" id="cbAgama" onKeyPress="return focusNext('cbSuku', event)">
<?php $sql = "SELECT agama FROM jbsumum.agama ORDER BY urutan";
	$result = QueryDb($sql);
	while ($row = mysqli_fetch_row($result)) { ?>    
    	<option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $P->agama)?> ><?=$row[0]?></option>
<?php } ?>    
    </select>&nbsp;
<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
    <img src="../images/ico/tambah.png" border="0" onClick="tambah_agama();"">
<?php 	} ?>
	</span>
	</td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Suku :</strong>    </td>
    <td width="*" align="left" valign="top">
	<span id="suku_info">
    <select name="cbSuku" id="cbSuku" onKeyPress="return focusNext('cbNikah', event)">
<?php 	$sql = "SELECT suku FROM jbsumum.suku";
		$res = QueryDb($sql);
	    while ($row = @mysqli_fetch_row($res))
		  echo "<option value='".$row[0]."' " . StringIsSelected($row[0], $P->suku) . " >".$row[0]."</option>";
?>
    </select>&nbsp;
<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
		<img src="../images/ico/tambah.png" onClick="tambah_suku();" />
<?php 	} ?>
    </span>	
	</td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Status Menikah :</strong>    </td>
    <td width="*" align="left" valign="top">
	 <select name="cbNikah" id="cbNikah" onKeyPress="return focusNext('cbKelamin', event)">
		 <option value="menikah" <?=StringIsSelected("menikah", $P->nikah)?> >Menikah</option>
		 <option value="belum" <?=StringIsSelected("belum", $P->nikah)?> >Belum</option>
		 <option value="tak_ada" <?=StringIsSelected("tak_ada", $P->nikah)?> >Tidak Ada Data</option>
	 </select>&nbsp;
	</td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Jenis Kelamin :</strong>    </td>
    <td width="*" align="left" valign="top">
    <select name="cbKelamin" id="cbKelamin" onKeyPress="return focusNext('txAlamat', event)">
	   	<option value="L" <?=StringIsSelected("L", $P->kelamin)?>>Laki-laki</option>
        <option value="P" <?=StringIsSelected("P", $P->kelamin)?>>Perempuan</option>
    </select>&nbsp;    </td>
</tr>

<tr>
	<td align="right" valign="top">Alamat :</td>
    <td width="*" align="left" valign="top"><input type="text" name="txAlamat" id="txAlamat" size="100" onKeyPress="return focusNext('txHP', event)" maxlength="255" value="<?=$P->alamat?>"/></td>
</tr>
<tr>
	<td align="right" valign="top">HP :</td>
    <td width="*" align="left" valign="top"><input type="text" name="txHP" id="txHP" size="15" maxlength="15" value="<?=$P->hp?>" onKeyPress="return focusNext('txTelpon', event)"/>
    Telpon: <input type="text" name="txTelpon" id="txTelpon" size="15" maxlength="15" value="<?=$P->telpon?>" onKeyPress="return focusNext('txEmail', event)"/>    </td>
</tr>
<tr>
	<td align="right" valign="top">Email :</td>
    <td width="*" align="left" valign="top">
		<input type="text" name="txEmail" id="txEmail" size="45" maxlength="255" value="<?=$P->email?>" onKeyPress="return focusNext('txFacebook', event)"/>
	</td>
</tr>
<tr>
	<td align="right" valign="top">Facebook :</td>
    <td width="*" align="left" valign="top">
		<input type="text" name="txFacebook" id="txFacebook" size="45" maxlength="255" value="<?=$P->facebook?>" onKeyPress="return focusNext('txTwitter', event)"/>
	</td>
</tr>
<tr>
	<td align="right" valign="top">Twitter :</td>
    <td width="*" align="left" valign="top">
		<input type="text" name="txTwitter" id="txTwitter" size="45" maxlength="255" value="<?=$P->twitter?>" onKeyPress="return focusNext('txWebsite', event)"/>
	</td>
</tr>
<tr>
	<td align="right" valign="top">Website :</td>
    <td width="*" align="left" valign="top">
		<input type="text" name="txWebsite" id="txWebsite" size="45" maxlength="255" value="<?=$P->website?>" onKeyPress="return focusNext('foto', event)"/>
	</td>
</tr>
<tr>
	<td align="right" valign="top">Foto :</td>
	<td align="left" valign="top">
		<input type="file" name="foto" id="foto" size="30" />
	</td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Mulai Kerja :</strong></td>
    <td width="*" align="left" valign="top">
    <select id="cbTglMulai" name="cbTglMulai" onKeyPress="return focusNext('cbBlnMulai', event)">
<?php for ($i = 1; $i <= 31; $i++) { ?>    
    	<option value="<?=$i?>" <?=IntIsSelected($i, $P->tglmulai)?>><?=$i?></option>	
<?php } ?>    
	</select>
    <select id="cbBlnMulai" name="cbBlnMulai" onKeyPress="return focusNext('txThnMulai', event)">
<?php $M = date("m");
	for ($i = 1; $i <= 12; $i++) { ?>    
    	<option value="<?=$i?>" <?=IntIsSelected($i, $P->blnmulai)?>><?=NamaBulan($i)?></option>	
<?php } ?>    
	</select>
    <input type="text" name="txThnMulai" id="txThnMulai" onKeyPress="return focusNext('cbGolongan', event)" size="4" maxlength="4" value="<?=$P->thnmulai?>"/>    </td>
</tr>
<tr>
	<td align="right" valign="top">Keterangan :</td>
    <td width="*" align="left" valign="top">
    <textarea id="txKeterangan" name="txKeterangan" rows="3" cols="60" onKeyPress="return focusNext('btSubmit', event)"><?=$P->keterangan?></textarea>    </td>
</tr>
<?php
    $sql = "SELECT replid, kolom, jenis
              FROM tambahandata 
             WHERE aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);
    $idtambahan = "";
    while($row = mysqli_fetch_row($res))
    {
        $replid = $row[0];
        $kolom = $row[1];
        $jenis = $row[2];

        if ($idtambahan != "") $idtambahan .= ",";
        $idtambahan .= $replid;

        if ($jenis == 3)
        {
            $sql = "SELECT pilihan 
                      FROM pilihandata 
                     WHERE idtambahan = '$replid'
                       AND aktif = 1
                     ORDER BY urutan";
            $res2 = QueryDb($sql);

            $arrList = [];
            if (mysqli_num_rows($res2) == 0)
                $arrList[] = "-";

            while($row2 = mysqli_fetch_row($res2))
            {
                $arrList[] = $row2[0];
            }

            $opt = "";
            for($i = 0; $i < count($arrList); $i++)
            {
                $pilihan = CQ($arrList[$i]);
                $opt .= "<option value='$pilihan'>$pilihan</option>";
            }
        }

        ?>
        <tr>
            <td align="right" valign="top"><?=$kolom?> :</td>
            <td width="*" align="left" valign="top">
                <?php if ($jenis == 1) { ?>
                    <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="1">
                    <input type="text" name="tambahandata-<?=$replid?>" id="tambahandata-<?=$replid?>" size="40" maxlength="1000"/>
                <?php } else if ($jenis == 2) { ?>
                    <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="2">
                    <input type="file" name="tambahandata-<?=$replid?>" id="tambahandata-<?=$replid?>" size="25" style="width:215px"/>
                <?php } else { ?>
                    <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="3">
                    <select name="tambahandata-<?=$replid?>" id="tambahandata-<?=$replid?>" style="width:215px">
                        <?= $opt ?>
                    </select>
                <?php } ?>
            </td>
        </tr>
        <?php
    }
    ?>
    <input type="hidden" id="idtambahan" name="idtambahan" value="<?=$idtambahan?>">
<tr>
	<td align="center" valign="top" colspan="2" bgcolor="#CCCCCC">
    <input type="submit" value="Simpan" name="btSubmit" id="btSubmit" class="but" />    </td>
</tr>
</table>
<?php
CloseDb();
?>    
</td></tr>
<tr><td align="center">
<img src="../images/border.jpg">
<br><br>
</td></tr>
</table>
</form>

</body>
</html>