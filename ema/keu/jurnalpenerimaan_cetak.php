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
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$ndepartemen = $departemen;
	
$idtahunbuku = "";
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];
$ntahunbuku = getname2('tahunbuku',$db_name_fina.'.tahunbuku','replid',$idtahunbuku);	

$idkategori = -1;
if (isset($_REQUEST['idkategori']))
	$idkategori = $_REQUEST['idkategori'];
	
if ($idkategori=='-1')
	$nkategori = "Semua Kategori";
else	
	$nkategori = getname2('kategori',$db_name_fina.'.kategoripenerimaan','replid',$idkategori);	

if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
$nperiode = LongDateFormat($tanggal1)." s.d. ".LongDateFormat($tanggal2);

switch($idkategori) {
	case "JTT"	: $sumber = "AND sumber = 'penerimaanjtt'";
				  $kriteria = 5;	
		break;
	case "SKR"	: $sumber = "AND sumber = 'penerimaaniuran'";
				  $kriteria = 6;
		break;
	case "CSWJB": $sumber = "AND sumber = 'penerimaanjttcalon'";
				  $kriteria = 7;
		break;
	case "CSSKR": $sumber = "AND sumber = 'penerimaaniurancalon'";
				  $kriteria = 8;
		break;
	case "LNN" 	: $sumber = "AND sumber = 'penerimaanlain'";
				  $kriteria = 9;
		break;
	default		: $sumber = "AND sumber LIKE 'penerimaan%'";
				  $kriteria = 10;
		break;
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Jurnal Penerimaan]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>JURNAL PENERIMAAN</strong></font><br />
 </center><br /><br />
<table width="100%">
<tr>
	<td width="8%" class="news_content1"><strong>Departemen</strong></td>
    <td width="35%" class="news_content1">: 
      <?=$departemen ?></td>
    <td width="7%" class="news_content1"><strong>Kategori</strong></td>
    <td width="50%" class="news_content1">: 
      <?=$nkategori ?></td>
</tr>
<tr>
  <td class="news_content1"><strong>Tahun Buku</strong></td>
  <td class="news_content1">: 
      <?=$ntahunbuku ?></td>
  <td class="news_content1"><strong>Periode</strong></td>
  <td class="news_content1">: 
      <?=$nperiode ?></td>
</tr>
</table>
<br />
<?php
$sql = "SELECT * FROM $db_name_fina.jurnal WHERE idtahunbuku = '$idtahunbuku' $sumber AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0) {

?>  
	<input type="hidden" name="total" id="total" value="<?=$total?>"/>  
    <table border="1" style="border-collapse:collapse;" cellpadding="5" cellspacing="0" width="100%" class="tab" bordercolor="#000000">
    <tr height="30">
        <td width="4%" align="center" class="header">No</td>
        <td width="15%" align="center" class="header">No. Jurnal/Tanggal</td>
        <td width="35%" align="center" class="header">Transaksi</td>
        <td align="center" class="header">Detail Jurnal</td>  
        <?php //if ((getLevel() != 2)) { ?>
        <!--<td width="3%" align="center" class="header">&nbsp;</td>--> 
        <?php //} ?>
    </tr>

<?php
	//if ($page==0)
		$cnt = 1;
	//else	
		//$cnt = (int)$page*(int)$varbaris+1;
		
	while ($row = mysqli_fetch_array($result)) {
		if ($cnt % 2 == 0)
			$bgcolor = "#FFFFB7";
		else
			$bgcolor = "#FFFFB7";
?>
    <tr height="25">
        <td align="center" rowspan="2" bgcolor="<?=$bgcolor ?>"><font size="4"><strong><?=$cnt ?></strong></font></td>
        <td align="center" bgcolor="<?=$bgcolor ?>"><strong><?=$row['nokas']?></strong><br /><em><?=LongDateFormat($row['tanggal'])?></em></td>
        <td valign="top" bgcolor="<?=$bgcolor ?>"><?=$row['transaksi'] ?>
    <?php if (strlen((string) $row['keterangan']) > 0 )  { ?>
            <br /><strong>Keterangan:</strong><?=$row['keterangan'] ?> 
    <?php } ?>    
        </td>
        <td rowspan="2" valign="top" bgcolor="#E8FFE8">
            <table border="1" style="border-collapse:collapse;" width="100%" height="100%" cellpadding="2" bgcolor="#FFFFFF" bordercolor="#000000">    
        <?php $idjurnal = $row['replid'];
            $sql = "SELECT jd.koderek,ra.nama,jd.debet,jd.kredit FROM $db_name_fina.jurnaldetail jd, $db_name_fina.rekakun ra WHERE jd.idjurnal = '$idjurnal' AND jd.koderek = ra.kode ORDER BY jd.replid";    
            $result2 = QueryDb($sql); 
            while ($row2 = mysqli_fetch_array($result2)) { ?>
            <tr height="25">
                <td width="12%" align="center"><?=$row2['koderek'] ?></td>
                <td width="*" align="left"><?=$row2['nama'] ?></td>
                <td width="23%" align="right"><?=FormatRupiah($row2['debet']) ?></td>
                <td width="23%" align="right"><?=FormatRupiah($row2['kredit']) ?></td>
            </tr>
        <?php } ?>    
            </table>
        </td>
	<?php //if ((getLevel() != 2)) { ?>
        <!--<td rowspan="2" align="center">
            <a href="JavaScript:edit(<?=$idjurnal ?>)"><img src="images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Jurnal Penerimaan!', this, event, '80px')"/></a>
    
        </td>-->
	<?php //} ?>
    </tr>
    <tr>    
        <td valign="top"><strong>Petugas: </strong><?=$row['petugas'] ?></td>
        <td valign="top">
        <strong>Sumber: </strong>
    <?php 	switch($row['sumber']) {	
            case 'penerimaanjtt':
                echo "Penerimaan Iuran Wajib Siswa"; break;
            case 'penerimaaniuran':
                echo "Penerimaan Iuran Sukarela Siswa"; break;
            case 'penerimaanlain':
                echo "Penerimaan Lain-Lain"; break;
			case 'penerimaanjttcalon':
                echo "Penerimaan Iuran Wajib Calon Siswa"; break;
			case 'penerimaaniurancalon':
                echo "Penerimaan Iuran Sukarela Calon Siswa"; break;
        } ?>        </td>
    </tr>
    <tr style="height:2px">
        <td colspan="5" bgcolor="#EFEFDE"></td>
    </tr>
    <?php
            $cnt++;
    }
	
    CloseDb();
    ?>
    </table>
<?php } ?>
  </td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>

</html>