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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['tahunajaran']))
    $tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];	
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];	
if (isset($_REQUEST['tgl1']))
	$tgl1 = $_REQUEST['tgl1'];	
if (isset($_REQUEST['bln1']))
	$bln1 = $_REQUEST['bln1'];	
if (isset($_REQUEST['th1']))
	$th1 = $_REQUEST['th1'];	
if (isset($_REQUEST['tgl2']))
	$tgl2 = $_REQUEST['tgl2'];		
if (isset($_REQUEST['bln2']))
	$bln2 = $_REQUEST['bln2'];	
if (isset($_REQUEST['th2']))
	$th2 = $_REQUEST['th2'];	
	
$tglawal = "$th1-$bln1-$tgl1";
if (isset($_REQUEST['tglawal']))
	$tglawal = $_REQUEST['tglawal'];	
$tglakhir = "$th2-$bln2-$tgl2";
if (isset($_REQUEST['tglakhir']))
	$tglakhir = $_REQUEST['tglakhir'];	
$urut = "k.kelas";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	
$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$filter1 = "AND t.departemen = '".$departemen."'";
if ($tingkat <> -1) 
	$filter1 = "AND k.idtingkat = '".$tingkat."'";

$filter2 = "";
if ($kelas <> -1) 
	$filter2 = "AND k.replid = '".$kelas."'";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Harian Data Siswa yang Tidak Hadir</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function lihat(nis) {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;
	newWindow('lap_hariansiswa_cetak.php?tglawal='+tglawal+'&tglakhir='+tglakhir+'&nis='+nis+'&lihat=0&urut=p.tanggal1&urutan=ASC', 'LaporanPresensiHarianSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak()
{
    var tahunajaran = document.getElementById('tahunajaran').value;
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var semester = document.getElementById('semester').value;
	var kelas = document.getElementById('kelas').value;	
	var tingkat = document.getElementById('tingkat').value;	
	var departemen = document.getElementById('departemen').value;	
	
	newWindow('lap_harianabsen_cetak.php?tahunajaran='+tahunajaran+'&tglawal='+tglawal+'&tglakhir='+tglakhir+'&semester='+semester+'&kelas='+kelas+'&tingkat='+tingkat+'&departemen='+departemen+'&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakLaporanHarianDataSiswayangTidakHadir','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel()
{
    var tahunajaran = document.getElementById('tahunajaran').value;
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var semester = document.getElementById('semester').value;
	var kelas = document.getElementById('kelas').value;	
	var tingkat = document.getElementById('tingkat').value;	
	var departemen = document.getElementById('departemen').value;	
	
	newWindow('lap_harianabsen_excel.php?tahunajaran='+tahunajaran+'&tglawal='+tglawal+'&tglakhir='+tglakhir+'&semester='+semester+'&kelas='+kelas+'&tingkat='+tingkat+'&departemen='+departemen+'&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakLaporanHarianDataSiswayangTidakHadir','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	var semester = document.getElementById('semester').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	var departemen = document.getElementById('departemen').value;
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "lap_harianabsen_footer.php?semester="+semester+"&kelas="+kelas+"&tingkat="+tingkat+"&departemen="+departemen+"&tglawal="+tglawal+"&tglakhir="+tglakhir+"&urut="+urut+"&urutan="+urutan;
	
}
</script>
</head>

<body>
<input type="hidden" name="tglawal" id="tglawal" value="<?=$tglawal?>">
<input type="hidden" name="tglakhir" id="tglakhir" value="<?=$tglakhir?>">
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>">
<input type="hidden" name="semester" id="semester" value="<?=$semester?>">
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>">
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>">
<input type="hidden" name="urut" id="urut" value="<?=$urut ?>" />
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan ?>" />


<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE UTAMA -->
<tr>
	<td>
    <?php 		
	OpenDb();	
	//$sql = "SELECT s.nis, s.nama, SUM(ph.hadir), SUM(ph.ijin), SUM(ph.sakit), SUM(ph.alpa), SUM(ph.cuti),  k.kelas, s.hportu, s.emailortu, s.alamatortu, s.telponortu, s.hpsiswa, s.emailsiswa FROM presensiharian p, phsiswa ph, siswa s, kelas k WHERE ph.nis = s.nis AND ph.idpresensi = p.replid AND p.idsemester = $semester AND s.idkelas = k.replid  $filter1 $filter2 AND (((p.tanggal1 BETWEEN '$tglawal' AND '$tglakhir') OR (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) OR (('$tglawal' BETWEEN p.tanggal1 AND p.tanggal2) OR ('$tglakhir' BETWEEN p.tanggal1 AND p.tanggal2))) AND (ph.ijin>0 OR ph.sakit>0 OR ph.cuti>0 OR ph.alpa>0) GROUP BY s.nis ORDER BY k.kelas,s.nama,p.tanggal1 ";
	
	$sql = "SELECT s.nis, s.nama, SUM(ph.hadir), SUM(ph.ijin) AS ijin, SUM(ph.sakit) AS sakit, SUM(ph.alpa) AS alpa, SUM(ph.cuti) AS cuti, k.kelas, s.hportu, s.emailayah, s.alamatortu, s.telponortu, s.hpsiswa, s.emailsiswa, s.aktif, s.emailibu FROM siswa s LEFT JOIN (phsiswa ph INNER JOIN presensiharian p ON p.replid = ph.idpresensi) ON ph.nis = s.nis, kelas k, tingkat t WHERE k.replid = s.idkelas AND k.idtingkat = t.replid $filter1 $filter2 AND p.idsemester = $semester AND (((p.tanggal1 BETWEEN '$tglawal' AND '$tglakhir') OR (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) OR (('$tglawal' BETWEEN p.tanggal1 AND p.tanggal2) OR ('$tglakhir' BETWEEN p.tanggal1 AND p.tanggal2))) GROUP BY s.nis HAVING ijin>0 OR sakit>0 OR cuti>0 OR alpa>0 ORDER BY $urut $urutan";
	//echo $sql;
	
	$result = QueryDb($sql);			 
	$jum = mysqli_num_rows($result);
	if ($jum > 0) { 
	?>  
        <table width="100%" border="0" align="center">
        <!-- TABLE LINK -->
        <tr>
            <td align="right"> 	
            <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
            <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onmouseover="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
            <a href="JavaScript:excel()"><img src="../images/ico/excel.png" border="0" onmouseover="showhint('Cetak dalam format Excel!', this, event, '50px')"/>&nbsp;Cetak Excel</a>&nbsp;&nbsp;
            </td>
        </tr>
        </table>
        <br />
        <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#0000000">		
    	<tr height="30" align="center" class="header">		
			<td width="5%">No</td>
		  	<td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nis','<?=$urutan?>')">N I S <?=change_urut('s.nis',$urut,$urutan)?></td>
            <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nama','<?=$urutan?>')">Nama <?=change_urut('s.nama',$urut,$urutan)?></td>
   		  	<td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('k.kelas','<?=$urutan?>')">Kelas <?=change_urut('k.kelas',$urut,$urutan)?></td>
            <td width="*">Ortu</td>
   		  	<td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('hadir','<?=$urutan?>')">Hadir <?=change_urut('hadir',$urut,$urutan)?></td>
		  	<td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('ijin','<?=$urutan?>')">Ijin <?=change_urut('ijin',$urut,$urutan)?></td>            
		  	<td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('sakit','<?=$urutan?>')">Sakit <?=change_urut('sakit',$urut,$urutan)?></td>
            <td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('alpa','<?=$urutan?>')">Alpa <?=change_urut('alpa',$urut,$urutan)?></td>
          	<td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('cuti','<?=$urutan?>')">Cuti <?=change_urut('cuti',$urut,$urutan)?></td>          	
          	<td width="5%"></td>
		</tr>
		<?php 
		$cnt = 0;
		while ($row = @mysqli_fetch_row($result)) {	
			
		if ($row[14] == 0) { 
		$pesan = "Status siswa tidak aktif lagi!";
		$color = "#FF0000";
		?>
		
        <!--<tr height="25" style="color:#FF0000">-->
		<?php } else { 
			$pesan = "Lihat!";
			$color = "#000000";
		?>
		<!--<tr height="25">-->
		<?php } ?>      
        <tr style="color:<?=$color?>">
			<td align="center"><?=++$cnt?></td>
			<td align="center"><?=$row[0]?></td>
            <td><?=$row[1]?></td>
            <td align="center"><?=$row[7]?></td>            
            <td>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="color:<?=$color?>">
            <tr>
                <td width="30%" >Handphone</td>
                <td>:</td>
                <td width="*" ><?=$row[8]?> </td>  
            </tr>                
            <tr>
                <td>Email</td>
                <td>:</td>
              	<td>
				<?php 	if ($row[9] <> "" && $row[15] <> "")
						echo $row[9].", ".$row[15];
				 	elseif ($row[15] == "")
						echo $row[9];
					else 
						echo $row[15];
				?>	
                </td>
            </tr>
            <tr>
                <td valign="top">Alamat</strong></td>
                <td valign="top">:</td>
              	<td><?=$row[10]?></td>
            </tr>
            <tr>
                <td>Telepon</strong></td>
              	<td>:</td>  
                <td><?=$row[11]?></td>
            </tr>
            <tr>
                <td>HP Siswa</strong></td>
              	<td>:</td>   
                <td><?=$row[12]?></td>
            </tr>
            <tr>
                <td>Email Siswa</strong></td>
              	<td>:</td>  
                <td><?=$row[13]?></td>
            </tr>
            </table>    
           	</td> 
  			<td align="center"><font size="4"><b><?=$row[2]?></b></font></td>
            <td align="center"><font size="4"><b><?=$row[3]?></b></font></td>    
            <td align="center"><font size="4"><b><?=$row[4]?></b></font></td>
            
            <td align="center"><font size="4"><b><?=$row[5]?></b></font></td>  
            <td align="center"><font size="4"><b><?=$row[6]?></b></font></td>  
            <td align="center"><a href="JavaScript:lihat('<?=$row[0]?>')"><img src="../images/ico/lihat.png" border="0" onmouseover="showhint('<?=$pesan?>', this, event, '80px')" /></a></td>    
    	</tr>
 	<?php 	
		} 
		CloseDb();	?>
		</table>
		<script language='JavaScript'>
   			Tables('table', 1, 0);
		</script>
<?php 	} else { ?>

	 <table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="250">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br />Tambah data presensi kelas di menu Presensi Harian pada bagian Presensi.</b></font>
		</td>
	</tr>
	</table>
<?php } ?> 
    </td>
</tr>

<!-- END OF TABLE UTAMA -->
</table>

</body>
</html>