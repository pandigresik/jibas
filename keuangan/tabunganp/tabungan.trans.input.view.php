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
function ShowInfoPegawai()
{
    global $nip, $replid, $nama, $namatingkat, $namakelas, $hp, $telpon, $alamattinggal, $keterangansiswa; ?>
    
<fieldset style="background:url('../images/bttable400.png'); height:210px;">
<legend></legend>
<table border="0" cellpadding="2" cellspacing="2">
<tr height="25">
    <td colspan="4" class="header" align="center">Data Pegawai</td>
</tr>
<tr valign="top">                    
    <td width="5%"><strong>N I P</strong></td>
    <td><strong>:</strong></td>
    <td><strong><?=$nip ?></strong> </td>
    <td rowspan="5" width="25%">
    <img src='<?="../library/gambar.php?replid=".$replid."&table=jbssdm.pegawai";?>' width='100' height='100'></td>
</tr>
<tr>
    <td valign="top"><strong>Nama</strong></td>
    <td valign="top"><strong>:</strong></td> 
    <td><strong><?=$nama ?></strong></td>
</tr>
<tr>
    <td><strong>Bagian</strong></td>
     <td><strong>:</strong></td>
    <td><strong><?= $namakelas ?></strong></td>
</tr>
<tr>
    <td><strong>HP</strong></td>
     <td><strong>:</strong></td>
    <td><strong><?=$hp ?></strong></td>
</tr>
<tr>
    <td><strong>Telpon</strong></td>
     <td><strong>:</strong></td>
    <td><strong><?=$telpon ?></strong></td>
</tr>
</table>
</fieldset>
<?php    
}
?>

<?php
function ShowSetoranInput()
{
    global $idtabungan, $nip, $idtahunbuku, $namatabungan, $jsetor, $tgl_jurnal, $keterangansetor, $defrekkas;
?>
<fieldset style="background-color: #B8E3FC; width: 400px;" >
<legend></legend>
<form name="main" method="post">   	
<input type="hidden" name="action" id="action" value="setor" />
<input type="hidden" name="idtabungan" id="idtabungan" value="<?=$idtabungan ?>" />
<input type="hidden" name="nip" id="nip" value="<?=$nip ?>" />
<input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku ?>" />
<table border="0" cellpadding="2" cellspacing="2" align="center" width="95%">
<tr height="25">
    <td colspan="2" class="header" align="center">SETORAN TABUNGAN</td>
</tr>
<tr>
    <td width="25%"><strong>Tabungan</strong></td>                
    <td><input type="text" readonly="readonly" size="20" value="<?=$namatabungan?>" style="background-color:#CCCC99"  /></td>
</tr>
<tr>
    <td><strong>Jumlah</strong></td>
    <td>
        <input type="text" name="jsetor" id="jsetor" size="20" value="<?=FormatRupiah($jsetor) ?>"
               onblur="formatRupiah('jsetor');" onfocus="unformatRupiah('jsetor'); panggil('jsetor');"
               onKeyPress="return focusNext('keterangansetor', event);" onkeyup="salinangkasetor();"/>
        <input type="hidden" name="angkasetor" id="angkasetor" value="<?=$bsetor?>" />
    </td>
</tr>
<tr>
    <td><strong>Rek. Kas</strong></td>
    <td colspan="2">
        <select name="rekkassetor" id="rekkassetor" style="width: 220px">
<?php          $sql = "SELECT kode, nama
                      FROM jbsfina.rekakun
                     WHERE kategori = 'HARTA'
                     ORDER BY nama";        
            $res = QueryDb($sql);
            while($row = mysqli_fetch_row($res))
            {
                $sel = $row[0] == $defrekkas ? "selected" : "";
                echo "<option value='".$row[0]."' $sel>{$row[0]} {$row[1]}</option>";
            } ?>                
        </select>
    </td>
</tr>
<tr>
    <td>Tgl.Jurnal</td>                
    <td><input type="text" readonly="readonly" size="20" value="<?=$tgl_jurnal?>" style="background-color:#CCCC99"  /></td>
</tr>
<tr>
    <td valign="top" align="left">Keterangan</td>
    <td>
        <textarea id="keterangansetor" name="keterangansetor" rows="2" cols="25"
                  onKeyPress="return focusNext('simpansetor', event)" onfocus="panggil('keterangan')" style="width:225px; height:30px">
        <?=$keterangansetor ?>
        </textarea>
    </td>
</tr>
<tr>
    <td valign="top" align="left">&nbsp;</td>
    <td align="left">
        <input type="button" name="simpansetor" id="simpansetor" class="but" value="SETOR" style="height: 30px; width: 120px;"
               onclick="this.disabled = true; validateSetorSubmit();" style="width:100px" onfocus="panggil('simpansetor')"/>
    </td>
</tr>
</table>
</form>
</fieldset>
<?php    
}
?>

<?php
function ShowTarikanInput()
{
    global $idtabungan, $nip, $idtahunbuku, $namatabungan, $jsetor, $tgl_jurnal, $keterangansetor, $defrekkas;
?>
<fieldset style="background-color: #FFD472; width: 400px;" >
<legend></legend>
<form name="main" method="post">   	
<input type="hidden" name="action" id="action" value="tarik" />
<input type="hidden" name="idtabungan" id="idtabungan" value="<?=$idtabungan ?>" />
<input type="hidden" name="nip" id="nip" value="<?=$nip ?>" />
<input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku ?>" />
<table border="0" cellpadding="2" cellspacing="2" align="center" width="95%">
<tr height="25">
    <td colspan="2" class="header" align="center">PENARIKAN TABUNGAN</td>
</tr>
<tr>
    <td width="25%"><strong>Tabungan</strong></td>                
    <td><input type="text" readonly="readonly" size="20" value="<?=$namatabungan?>" style="background-color:#CCCC99"  /></td>
</tr>
<tr>
    <td><strong>Jumlah</strong></td>
    <td>
        <input type="text" name="jtarik" id="jtarik" size="20" value="<?=FormatRupiah($jtarik) ?>"
               onblur="formatRupiah('jtarik');" onfocus="unformatRupiah('jtarik'); panggil('jtarik');"
               onKeyPress="return focusNext('keterangantarik', event);" onkeyup="salinangkatarik();"/>
        <input type="hidden" name="angkatarik" id="angkatarik" value="<?=$bsetor?>" />
    </td>
</tr>
<tr>
    <td><strong>Rek. Kas</strong></td>
    <td colspan="2">
        <select name="rekkastarik" id="rekkastarik" style="width: 220px">
<?php          $sql = "SELECT kode, nama
                      FROM jbsfina.rekakun
                     WHERE kategori = 'HARTA'
                     ORDER BY nama";        
            $res = QueryDb($sql);
            while($row = mysqli_fetch_row($res))
            {
                $sel = $row[0] == $defrekkas ? "selected" : "";
                echo "<option value='".$row[0]."' $sel>{$row[0]} {$row[1]}</option>";
            } ?>                
        </select>
    </td>
</tr>
<tr>
    <td>Tgl.Jurnal</td>                
    <td><input type="text" readonly="readonly" size="20" value="<?=$tgl_jurnal?>" style="background-color:#CCCC99"  /></td>
</tr>
<tr>
    <td valign="top" align="left">Keterangan</td>
    <td>
        <textarea id="keterangantarik" name="keterangantarik" rows="2" cols="25"
                  onKeyPress="return focusNext('simpantarik', event)" onfocus="panggil('keterangantarik')" style="width:225px; height:30px"><?=$keterangansetor?></textarea>
    </td>
</tr>
<tr>
    <td valign="top" align="left">&nbsp;</td>
    <td align="left">
        <input type="button" name="simpantarik" id="simpantarik" class="but" value="TARIK" style="height: 30px; width: 120px;"
               onclick="this.disabled = true; validateTarikSubmit();" style="width:100px" onfocus="panggil('simpantarik')"/>
    </td>
</tr>
</table>
</form>
</fieldset>
<?php    
}
?>

<?php
function ShowTransaksi($page)
{
?>
<br><br>
<fieldset>
<legend><font size="2" color="#003300"><strong>Transaksi Tabungan</strong></font></legend>
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
    <td align="right">
    <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;        
    </td>
</tr>
</table>        
<br />
<div id='divTabunganList' style="overflow:auto; height: 380px;">
<table class="tab" id="tabTabunganList" border="1" style="border-collapse:collapse"
       width="100%" align="center" bordercolor="#000000">
<tr height="30" align="center">
    <td class="header" width="5%">No</td>
    <td class="header" width="18%">No. Jurnal/Tgl</td>
    <td class="header" width="15%">Debet</td>
    <td class="header" width="15%">Kredit</td>
    <td class="header" width="*">Keterangan</td>
    <td class="header" width="12%">Petugas</td>
    <td class="header">&nbsp;</td>
</tr>
<?php  ShowTransaksiList($page); ?>
</table>
</div>
<br>
<a onclick="nextTabunganList()">selanjutnya</a>
<br>
</fieldset>
<?php    
}
?>

<?php
function ShowTransaksiList($page)
{
    global $nip, $idtabungan, $idtahunbuku;
    
    $nItemPerPage = 10;
    $startIndex = $page * $nItemPerPage;
    
    $sql = "SELECT COUNT(p.replid)
              FROM tabunganp p, jurnal j
             WHERE p.idjurnal = j.replid
               AND p.nip = '$nip'
               AND j.idtahunbuku = '$idtahunbuku'
               AND p.idtabungan = '".$idtabungan."'";
    $nData = FetchSingle($sql);
    
    $sql = "SELECT p.replid AS id, j.nokas, date_format(p.tanggal, '%d-%b-%Y %H:%i:%s') as tanggal,
                   p.keterangan, p.debet, p.kredit, p.petugas
              FROM tabunganp p, jurnal j
             WHERE p.idjurnal = j.replid
               AND p.nip = '$nip'
               AND j.idtahunbuku = '$idtahunbuku'
               AND p.idtabungan = '$idtabungan'
             ORDER BY p.replid DESC
             LIMIT $startIndex, $nItemPerPage";
    $result = QueryDb($sql);
    if ($nData == 0)
    {
        echo "<tr height='100'><td colspan='7' align='center' valign='middle'><i>Belum ada data tabungan</i></td></tr>";
    }
    else if (mysqli_num_rows($result) == 0)
    {
        echo "";
    }
    else
    {
        
        $cnt = $startIndex;
        while ($row = mysqli_fetch_array($result))
        {
            $kredit = (int) $row['kredit'];
            $bgcolor = $kredit != 0 ? "#E0F3FF" : "#F9F6EA";
            
            $no = $nData - $cnt;
            $cnt += 1;

            $idTabungan = $row['id'];
            $sql = "SELECT jenistrans
                      FROM jbsfina.paymenttrans
                     WHERE idtabunganp = $idTabungan";
            $res2 = QueryDb($sql);
            $isSchoolPay = mysqli_num_rows($res2) > 0;

            $infoSchoolPay = "";
            if ($row2 = mysqli_fetch_row($res2))
            {
                $jenisTrans = $row2[0];
                if ($jenisTrans == 0)
                    $jenisTrans = "&nbsp;SchoolPay&nbsp;<span style='background-color: #636363; color: #ffffff'>&nbsp;Vendor&nbsp;</span>";
                else if ($jenisTrans == 1)
                    $jenisTrans = "&nbsp;SchoolPay&nbsp;<span style='background-color: #47973c; color: #ffffff'>&nbsp;Iuran&nbsp;</span>";
                else if ($jenisTrans == 2)
                    $jenisTrans = "&nbsp;SchoolPay&nbsp;<span style='background-color: #9f4aa3; color: #ffffff'>&nbsp;Iuran&nbsp;</span>";

                $infoSchoolPay = $isSchoolPay ? "<span style='background-color: #296eeb; color: #ffffff; font-size: 10px;'>$jenisTrans</span>" : "";
            }
            ?>
            <tr height="25" style='background-color: <?=$bgcolor?>;'>
                <td align="center"><?=$no?></td>
                <td align="center"><?="<strong>" . $row['nokas'] . "</strong><br><i>" . $row['tanggal']?></i></td>
                <td align="right"><?=FormatRupiah($row['debet'])?></td>
                <td align="right"><?=FormatRupiah($row['kredit'])?></td>
                <td align="left"><?=$row['keterangan'] ?></td>
                <td align="center"><?=$row['petugas'] ?></td>
                <td align="center">
                    <a href="#" onclick="cetakkuitansi(<?=$row['id'] ?>)"><img src="../images/ico/print.png" border="0"
                       onMouseOver="showhint('Cetak Kuitansi Tabungan!', this, event, '100px')"/></a>&nbsp;
                <?php  if (!$isSchoolPay && getLevel() != 2) { ?>
                    <a href="#" onclick="editpembayaran(<?=$row['id'] ?>)"><img src="../images/ico/ubah.png" border="0"
                       onMouseOver="showhint('Ubah Transaksi Tabungan!', this, event, '120px')" /></a>
                <?php } ?>
                    <br><?=$infoSchoolPay?>
                </td>
            </tr>
<?php     }
    }
}
?>

<?php
function ShowInfoTabungan()
{
    global $nip, $idtabungan, $idtahunbuku;
    
    $sql = "SELECT SUM(debet), SUM(kredit)
              FROM tabunganp
             WHERE nip = '$nip'
               AND idtabungan = '".$idtabungan."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $jsetor = $row[1];
    $jtarik = $row[0];
    $jsaldo = $jsetor - $jtarik;
    
    $sql = "SELECT DATE_FORMAT(tanggal, '%d-%b-%Y %H:%i:%s'), kredit
              FROM tabunganp
             WHERE nip = '$nip'
               AND idtabungan = '$idtabungan'
               AND kredit <> 0
             ORDER BY replid DESC
             LIMIT 1";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);             
    $tglsetorakhir = $row[0];
    $setorakhir = $row[1];
    
    $sql = "SELECT DATE_FORMAT(tanggal, '%d-%b-%Y %H:%i:%s'), debet
              FROM tabunganp
             WHERE nip = '$nip'
               AND idtabungan = '$idtabungan'
               AND debet <> 0
             ORDER BY replid DESC
             LIMIT 1";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);             
    $tgltarikakhir = $row[0];
    $tarikakhir = $row[1];
    ?>
<fieldset>
<legend><strong>Informasi Tabungan</strong></legend>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
    <td width="25%"><strong>Saldo:</strong></td>
    <td><strong><?= FormatRupiah($jsaldo) ?></strong></td>
</tr>
<tr>
    <td><strong>Jumlah Setoran:</strong></td>
    <td><strong><?= FormatRupiah($jsetor) ?></strong></td>
</tr>
<tr>
    <td><strong>Setoran Terakhir:</strong></td>
    <td>
        <strong><?= FormatRupiah($setorakhir) ?></strong><br>
        <i><?=$tglsetorakhir?></i>
    </td>
</tr>
<tr>
    <td><strong>Jumlah Penarikan:</strong></td>
    <td><strong><?= FormatRupiah($jtarik) ?></strong></td>
</tr>
<tr>
    <td><strong>Penarikan Terakhir:</strong></td>
    <td>
        <strong><?= FormatRupiah($tarikakhir) ?></strong><br>
        <i><?=$tgltarikakhir?></i>
    </td>
</tr>
</table>    
    
</fieldset>	
<?php    
}
?>