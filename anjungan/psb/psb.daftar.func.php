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
$selDept = "";
$selProses = "";

function ShowDepartemenCombo()
{
    global $selDept;
    
    $sql = "SELECT departemen
              FROM jbsakad.departemen
             WHERE aktif = 1
             ORDER BY urutan";
    $res = QueryDB($sql);
    
    echo "<select name='psb_departemen' id='psb_departemen' class='inputbox' onchange='psb_DaftarPsbChangeDepartemen()'>";
	while ($row = mysqli_fetch_row($res))
    {
        if ($selDept == "")
            $selDept = $row[0];
            
		echo "<option value='" . $row[0] . "' >" . $row[0] . "</option>";
	}
    echo "</select>";         
}

function ShowPenerimaanCombo($selDept)
{
    global $selProses;
    
    $sql = "SELECT replid, proses
              FROM jbsakad.prosespenerimaansiswa
             WHERE aktif = 1
               AND departemen='$selDept'";
    $res = QueryDB($sql);
    
    if (mysqli_num_rows($res) == 0)
    {
        echo "<input type='hidden' name='psb_proses' id='psb_proses' value='-1'>";
        echo "<em>Belum ada data proses penerimaan</em>";
    }
    else
    {
        echo "<select name='psb_proses' id='psb_proses' class='inputbox' onchange='psb_DaftarPsbChangeProses()'>";
        while ($row = mysqli_fetch_row($res))
        {
            if ($selProses == "")
                $selProses = $row[0];
                
            echo "<option value='" . $row[0] . "' >" . $row[1] . "</option>";
        }
        echo "</select>";             
    }
}

function ShowKelompokCombo($selProses)
{
    $sql = "SELECT replid, kelompok, kapasitas
              FROM jbsakad.kelompokcalonsiswa
             WHERE idproses = '$selProses'
             ORDER BY kelompok";
    $res = QueryDB($sql);
    
    if (mysqli_num_rows($res) == 0)
    {
        echo "<input type='hidden' name='psb_kelompok' id='psb_kelompok' value='-1'>";
        echo "<em>Belum ada data kelompok penerimaan</em>";
    }
    else
    {
        echo "<select name='psb_kelompok' id='psb_kelompok' class='inputbox' onchange='psb_DaftarPsbChangeKelompok()'>";
        while ($row = mysqli_fetch_row($res))
        {
            $sql = "SELECT COUNT(replid)
                      FROM jbsakad.calonsiswa
                     WHERE idkelompok = '".$row[0]."'
                       AND aktif = 1";
            $ndata = FetchSingle($sql);           
            
            echo "<option value='" . $row[0] . "' >" . $row[1] . ", kapasitas: " . $row[2] . ", terisi: " . $ndata . "</option>";
        }
        echo "</select>";
    }
}

function ShowDaftarPsb($idkelompok, $page)
{
    require_once("psb.config.php");
    
    $nRow = 10;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsakad.calonsiswa
             WHERE idkelompok = '$idkelompok'
               AND aktif = 1";
    $nData = (int)FetchSingle($sql);
    $nPage = ceil($nData / $nRow);
    
    $offset = ($page - 1) * $nRow;
    ?>
    
    <table border="0" cellpadding="1" cellspacing="0" width="96%">
    <tr>
    <td align="left" valign="bottom">
        <input type="button" value=" < " class="but" style="height: 22px; width: 35px;" onclick="psb_DaftarPsbGotoPage(<?=$idkelompok?>, <?=$page-1?>, <?=$nPage?>)" >
        <input type="button" value=" > " class="but" style="height: 22px; width: 35px;" onclick="psb_DaftarPsbGotoPage(<?=$idkelompok?>, <?=$page+1?>, <?=$nPage?>)" >
        Halaman <select name="psb_DaftarPsbPage" id="psb_DaftarPsbPage" id="page" style="height: 22px; width: 40px;" onchange="psb_DaftarPsbChangePage(<?=$idkelompok?>)">
<?php      for($i = 1; $i <= $nPage; $i++)  { ?>
            <option value="<?= $i ?>" <?= ($i == $page) ? "selected" : "" ?> ><?= $i ?></option>
<?php      } ?>
        </select> dari <?= $nPage ?>
    </td>    
    </tr>    
    </table>    
    <table border="1" cellpadding="1" cellspacing="0" width="96%" style="border-collapse: collapse; border-width: 1px;">
    <tr height="22">
        <td width="4%" class="header">No</td>
        <td width="16%" class="header">No Pendaftaran</td>
        <td width="*" class="header">Nama</td>
        <td width="20%" class="header">Tmp/Tgl Lahir</td>
        <td width="24%" class="header">Asal Sekolah</td>
        <td width="5%" class="header">&nbsp;</td>
    </tr>
<?php
    $sql = "SELECT nopendaftaran, nama, tmplahir, DATE_FORMAT(tgllahir, '%d %b %Y') AS tgllahir, asalsekolah
              FROM jbsakad.calonsiswa
             WHERE idkelompok = '$idkelompok'
               AND aktif = 1
             ORDER BY nama
             LIMIT $offset, $nRow";
    $res = QueryDb($sql);
    $cnt = $offset;
    while($row = mysqli_fetch_array($res))
    {
        ?>
        <tr height="24" style="background-color: #fff;">
            <td align="left"><?= ++$cnt ?></td>
            <td align="left"><?= $row['nopendaftaran'] ?></td>
            <td align="left"><?= $row['nama'] ?></td>
            <td align="left"><?= $row['tmplahir'] . ", " . $row['tgllahir'] ?></td>
            <td align="left"><?= $row['asalsekolah'] ?></td>
            <td align="center">
<?php          if (PSB_ENABLE_INPUT == 1)
            { ?>                
                <a href="#" onclick="psb_DaftarPsbUbah('<?= $row['nopendaftaran'] ?>', '<?= $row['nama'] ?>', <?= $idkelompok ?>, <?= $page ?>, <?= $nPage ?>)"><img src="images/ubah.png" title="Ubah" border="0"></a>
<?php          }
            else
            {
                echo "&nbsp;";
            } ?>
            </td>
        </tr>
        <?php
    }
    echo "</table>";
}


function ShowFormUbahData($nocalon, $namacalon, $idkelompok, $page, $npage)
{
    ?>
    <br><br>
    <center>
    <fieldset style="border-color: #557d1d; border-width: 1px; width: 420px;">
    <legend>
        <font style="color: #557d1d; font-size: 12px; font-weight: bold; text-align: left;">Ubah Data Calon Siswa</font>
    </legend>    
    <input type="hidden" id="psb_UbahDataNoCalon" value="<?= $nocalon ?>" >
    <input type="hidden" id="psb_UbahDataNamaCalon" value="<?= $namacalon ?>" >
    <input type="hidden" id="psb_UbahDataIdKelompok" value="<?= $idkelompok ?>" >
    <input type="hidden" id="psb_UbahDataPage" value="<?= $page ?>" >
    <input type="hidden" id="psb_UbahDataNPage" value="<?= $npage ?>" >
    <table border="0" cellpadding="3" cellspacing="0" width="96%" style='line-height: 20px;'>
    <tr>
        <td align="left" colspan="2">
            <font style='color: #557d1d'>
            Isikan PIN Calon Siswa untuk mengubah data pendaftaran anda. Anda dapat menanyakan PIN Calon Siswa kepada staf sekolah apabila anda tidak mengetahuinya.
            </font>
        </td>
    </tr>
    <tr>
        <td width="35%" align="right"><strong>No Pendaftaran:</strong></td>
        <td align="left"><strong><?= $nocalon ?></strong></td>
    </tr>
    <tr>
        <td align="right"><strong>Nama:</strong></td>
        <td align="left"><strong><?= $namacalon ?></strong></td>
    </tr>
    <tr>
        <td align="right"><strong>P I N:</strong></td>
        <td align="left">
            <input type="password" class="inputbox" id="psb_PinCalon" name="psb_PinCalon" size="6" maxlength="6">
            <span style='color: red' id='psb_CheckPinInfo'></span>   
        </td>
    </tr>
    <tr>
        <td align="right">&nbsp;</td>
        <td align="left">
            <input type="button" value="Login" class="but" style="width: 100px; height: 30px" onclick="psb_CheckPinCalon()">
            <input type="button" value="Batal" class="but" style="width: 100px; height: 30px" onclick="psb_BatalUbahDataCalon()">
        </td>
    </tr>    
    </table>
    <br>
    </fieldset>
    </center>
<?php        
}

function PinIsValid($nocalon, $pincalon)
{
    $sql = "SELECT COUNT(replid)
              FROM jbsakad.calonsiswa
             WHERE nopendaftaran = '$nocalon'
               AND pinsiswa = '$pincalon'
               AND pinsiswa IS NOT NULL";
    $ndata = (int)FetchSingle($sql);
    
    return ($ndata != 0);
}
?>