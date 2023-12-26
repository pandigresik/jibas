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
require_once('../include/sessioninfo.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../library/departemen.php');
require_once('../library/datearith.php');
require_once('../cek.php');
require_once('penyusunan.func.php');

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<link rel="stylesheet" type="text/css" href="penyusunan.css">
<link rel="stylesheet" type="text/css" href="../script/themes/ui-lightness/jquery.ui.all.css" />
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/jquery-1.9.1.js"></script>
<script language="javascript" src="../script/jquery-ui-1.10.3.custom.min.js"></script>
<script language="javascript" src="penyusunan.js"></script>
</head>

<body>
<table border="0" width="100%" height="100%">
<tr><td align="center" valign="top" background="../images/ico/b_daftarmutasi.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<tr height="300">
    <td align="left" valign="top">

	<table border="0"width="95%" align="center">
    <tr>
        <td align="right">
            <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Penyusunan Surat</font>
        </td>
    </tr>
    <tr>
        <td align="right"><a href="../pelaporanmenu.php" target="content">
            <font size="1" face="Verdana" color="#000000"><b>Pelaporan</b></font></a>&nbsp>&nbsp <font size="1" face="Verdana" color="#000000"><b>Penyusunan Surat</b></font>
        </td>
    </tr>
	</table>
    
    <form name='main' method='post' action='penyusunan.report.php'
          onsubmit="return validate()">
    
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <tr>
		<td align="left" width='7%'>
			
		</td>	
		<td align="left" width='30%'>
            
            <table border='0' cellpadding='2'>
            <tr>
                <td colspan="2" align="left">
                    <font style="font-size: 16px; font-family: Arial; font-weight: bold;">
                        TUJUAN SURAT
                    </font>
                </td>
            </tr>
            <tr>
                <td width='15' align='center' valign='top' style="line-height: 6px">
                    &nbsp;
                </td>
                <td width="600" align="left" valign="top" style="line-height: 24px">
                    
                    <fieldset style='width: 760px'>
                    <legend>
                    <strong>Departemen:</strong>
                    <span id="divCbDepartemen">
                    <select class='inputbox' name="departemen" id="departemen" style="width:130px;"
                             onchange='changeCbDepartemen()'>
        <?php 		$dep = getDepartemen(SI_USER_ACCESS());    
                    foreach($dep as $value)
                    {
                        if ($departemen == "")
                            $departemen = $value;
                        $sel = $departemen == $value ? "selected" : "";	?>
                        <option value="<?=$value?>" <?=$sel?> ><?=$value ?></option>
        <?php 		} ?>
                    </select>
                    </span>    
                    </legend>
                    
                    <table border='0' cellpadding='2'>
                    <tr>
                    <td width='15' align='center' valign='top' style="line-height: 6px">
                        <br>
                        <input class='largecheckbox' type="radio" id="tipe" onchange="changeType(1)" name="tipe" value="1" checked="checked">
                    </td>
                    <td width="600" align="left" valign="top" style="line-height: 24px">
                        
                        <strong>Berdasarkan Kelas</strong><br>
                        <div id="divPanelKelas">
                            
                            &nbsp;Tingkat:
                            <span id="divCbTingkat">
    <?php                      ShowCbTingkat($departemen) ?>
                            </span>
                            
                            &nbsp;Kelas:
                            <span id="divCbKelas">
    <?php                      ShowCbKelas(0) ?>
                            </span>
    
                        </div>
                        
                    </td>
                    </tr>
                    <tr>
                        <td width='15' align='center' valign='top' style="line-height: 6px">
                            <br>
                            <input class='largecheckbox' type="radio" onchange="changeType(2)" id="tipe" name="tipe" value="2">
                        </td>
                        <td width="600" align="left" valign="top" style="line-height: 24px">
                            <strong>Berdasarkan daftar NIS</strong> <em><font style='color: #777'>*) pisahkan dengan koma</font></em><br>
                                
                            <div id="divPanelNis">
                            <textarea class='inputbox' id="nisinfo" name="nisinfo" rows="5" cols="80"></textarea><br>
                            </div>
                        </td>
                    </tr>
                    </table>    
                    
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td width='15' align='center' valign='top' style="line-height: 6px">
                    &nbsp;
                </td>
                <td width="600" align="left" valign="top" style="line-height: 24px">
                    <strong>Alamat:</strong>
                    <select class='inputbox' id="alamat" name="alamat">
                        <option value='1'>Alamat Siswa</option>
                        <option value='2'>Alamat Orangtua</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width='15' align='center' valign='top' style="line-height: 6px">
                    &nbsp;
                </td>
                <td width="600" align="left" valign="top" style="line-height: 24px">
                    <strong>Posisi cetak alamat:</strong>
                    <select class='inputbox' id="posisi" name="posisi">
                        <option value='1'>Kanan</option>
                        <option value='2'>Kiri</option>
                    </select>
                </td>
            </tr>
            </table>
            
            <br>
            <table border='0' cellpadding='2'>
            <tr>
                <td colspan="2" align="left">
                    <font style="font-size: 16px; font-family: Arial; font-weight: bold;">
                        PENGANTAR SURAT
                    </font>
                </td>
            </tr>    
            <tr>
                <td width='15' align='center' valign='top'>
                    &nbsp;
                </td>
                <td width="600" align="left" valign="top">
                    Pengantar:
                    <span id="divCbPengantar">
<?php                  $idpengantar = ShowCbPengantar($departemen) ?>
                    </span>
                    <div class='inputbox' id='divPengantar'
                         style='border-width: 1px; border-style: solid; border-color: #666; overflow: auto; height: 140px; width: 780px;'>
<?php                  ShowPengantar($idpengantar) ?>                        
                    </div>
                </td>
            </tr>
            </table>
            
            <br>
            <table border='0' cellpadding='2'>
            <tr>
                <td colspan="2" align="left">
                    <font style="font-size: 16px; font-family: Arial; font-weight: bold;">
                        LAMPIRAN SURAT
                    </font>
                </td>
            </tr>    
            <tr>
                <td width='15' align='center' valign='top'>
                    &nbsp;
                </td>
                <td width="600" align="left" valign="top">
                    <input class='largecheckbox' type="checkbox" class='inputbox'
                           id="chLampiran" name="chLampiran" onchange='changeLampiran()'>&nbsp;
                    <font style='color: blue;'><strong>Halaman lampiran</strong></font>        
                    <span id='divCbLampiran'>
<?php                      $idlampiran = ShowCbLampiran($departemen) ?>
                    </span><br>
                    <div id='divLampiran' class='inputbox' disabled='disabled'
                         style='border-width: 1px; border-style: solid; border-color: #666; overflow: auto; height: 140px; width: 780px; background-color: #DDD'>
<?php                      ShowLampiran($idlampiran) ?>                        
                    </div>
                </td>
            </tr>
            </table>
            
            <br>
            <table border='0' cellpadding='2'>
            <tr>
                <td colspan="2" align="left">
                    <font style="font-size: 16px; font-family: Arial; font-weight: bold;">
                        INFORMASI SURAT
                    </font>
                </td>
            </tr>    
            <tr>
                <td width='15' align='center' valign='top'>
                    &nbsp;
                </td>
                <td width="800" align="left" valign="top">
                    
                    <table border='0' cellpadding='8' cellspacing='0'>
                    <tr>
                        <td width='25' style='background-color: #eee' align='center' valign='middle'>
                            <input class='largecheckbox' type="checkbox" id="chNilai" name="chNilai" onchange="changeCbInfo('chNilai', 'cbNilai')">
                        </td>
                        <td align='left' valign='top'>
                            <font style='color: blue; font-size: 11px;'><strong>Nilai Harian Siswa</strong></font><br>
                            <em><font color='maroon'>data diambil dari JIBAS Akademik</font></em><br>
                            Tanggal:
<?php                          ShowCbDateRange('cbNilai', false)    ?>                            
                        </td>
                    </tr>
                    <tr>
                        <td width='25' style='background-color: #eee' align='center' valign='middle'>
                            <input class='largecheckbox' type="checkbox" id="chKeuangan" name="chKeuangan" onchange="changeCbInfo('chKeuangan', 'cbKeuangan')">
                        </td>
                        <td align='left' valign='top'>
                            <font style='color: blue; font-size: 11px;'><strong>Pembayaran Siswa</strong></font><br>
                            <em><font color='maroon'>data diambil dari JIBAS Keuangan (Iuran Wajib, Iuran Sukarela, Tabungan)</font></em><br>
                            Tanggal:
<?php                          ShowCbDateRange('cbKeuangan', false)    ?>                         
                        </td>
                    </tr>
                    <tr>
                        <td width='25' style='background-color: #eee' align='center' valign='middle'>
                            <input class='largecheckbox' type="checkbox" id="chPresensi" name="chPresensi" onchange="changeCbInfo('chPresensi', 'cbPresensi')">
                        </td>
                        <td align='left' valign='top'>
                            <font style='color: blue; font-size: 11px;'><strong>Presensi Harian Siswa</strong></font><br>
                            <em><font color='maroon'>data diambil dari JIBAS SPT Fingerprint</font></em><br>
                            Tanggal:
<?php                          ShowCbDateRange('cbPresensi', false)    ?>                            
                        </td>
                    </tr>
                    <tr>
                        <td width='25' style='background-color: #eee' align='center' valign='middle'>
                            <input class='largecheckbox' type="checkbox" id="chKegiatan" name="chKegiatan" onchange="changeCbInfo('chKegiatan', 'cbKegiatan')">
                        </td>
                        <td align='left' valign='top'>
                            <font style='color: blue; font-size: 11px;'><strong>Presensi Kegiatan Siswa</strong></font><br>
                            <em><font color='maroon'>data diambil dari JIBAS SPT Fingerprint</font></em><br>
                            Tanggal:
<?php                          ShowCbDateRange('cbKegiatan', false)    ?>                       
                        </td>
                    </tr>
                    <tr>
                        <td width='25' style='background-color: #eee' align='center' valign='middle'>
                            <input class='largecheckbox' type="checkbox" id="chCbe" name="chCbe" onchange="changeCbInfo('chCbe', 'cbCbe')">
                        </td>
                        <td align='left' valign='top'>
                            <font style='color: blue; font-size: 11px;'><strong>Nilai Computer Based Exam</strong></font><br>
                            <em><font color='maroon'>data diambil dari JIBAS Computer Based Exam (Ujian Khusus)</font></em><br>
                            Tanggal:
<?php                          ShowCbDateRange('cbCbe', false)    ?>
                        </td>
                    </tr>
                    </table>
                    
                </td>
            </tr>
            </table>
            <br><br>
            
            
		</td>
	</tr>
	<tr>
		<td align="left" width='7%'>
			&nbsp;
		</td>
		<td align="left" width='*' colspan="2">
			<input type='submit' class='but' style='height: 40px; width: 140px' value='Buat Surat'>
		</td>	
	</tr>
    </table>
    
    </form>
    
    </td>
</tr>
</table>

</td></tr>
</table>    

</body>
</html>
<?php
CloseDb();
?>