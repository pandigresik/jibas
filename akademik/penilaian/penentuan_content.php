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
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');
require_once('recountnr.php');
require_once('../library/dpupdate.php');

OpenDb();

if(isset($_REQUEST["tahun"]))
	$tahun = $_REQUEST["tahun"];
if(isset($_REQUEST["departemen"]))
	$departemen = $_REQUEST["departemen"];
if(isset($_REQUEST["tingkat"]))
	$tingkat = $_REQUEST["tingkat"];
if(isset($_REQUEST["pelajaran"]))
	$pelajaran = $_REQUEST["pelajaran"];
if(isset($_REQUEST["nip"]))
	$nip = $_REQUEST["nip"];
if(isset($_REQUEST["kelas"]))
	$kelas = $_REQUEST["kelas"];
if(isset($_REQUEST["semester"]))
	$semester = $_REQUEST["semester"];
if(isset($_REQUEST["aspek"]))
	$aspek = $_REQUEST["aspek"];
if(isset($_REQUEST["aspekket"]))
	$aspekket = $_REQUEST["aspekket"];	

$sql = "SELECT nama FROM pelajaran WHERE replid = '".$pelajaran."'";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$namapel = $row[0];
	
if (isset($_REQUEST["Simpan"]))
{
	$numdata = $_REQUEST["numdata"];
	$nilaimin = $_REQUEST["nilaimin"];
	$Simpan = $_REQUEST["Simpan"];
	
	$success = true;
	BeginTrans();
		
	$idinfo = 0;
	if (isset($_REQUEST['idinfo']))
		$idinfo = $_REQUEST['idinfo'];							
	
	if ($idinfo == 0)
	{
		$sql = "INSERT INTO jbsakad.infonap SET idpelajaran='$pelajaran', idsemester='$semester', idkelas='$kelas', nilaimin=$nilaimin";
		QueryDbTrans($sql, $success);
		
		$sql = "SELECT LAST_INSERT_ID()";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_row($res);
		$idinfo = $row[0];
	}
	else
	{
		$sql = "UPDATE jbsakad.infonap SET nilaimin = '$nilaimin' WHERE replid = '".$idinfo."'";
		QueryDbTrans($sql, $success);
	}
	
	if ($success)
	{
		// Hanya mengambil satu id aturannhb agar menjadi link ke dasarpenilaian
		$sql = "SELECT a.replid
				  FROM jbsakad.aturannhb a, jbsakad.kelas k 
				 WHERE a.nipguru='$nip' AND a.idtingkat=k.idtingkat AND k.replid='$kelas' 
				   AND a.idpelajaran='$pelajaran' AND a.dasarpenilaian='$aspek' ORDER BY a.replid ASC LIMIT 1";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_array($res);
		$idaturan = $row['replid'];
	
		$konter = 1;
		while ($success && $konter <= $numdata) 
		{
			$nis = $_REQUEST["nis_".$konter];
			$pk = $_REQUEST["PK_".$konter];
			$gpk = $_REQUEST["G_PK_".$konter];
			$predikat = $_REQUEST["predikat_".$konter];
			$predikat = 3;
		
			if ($Simpan == "Simpan")
			{
				if ($success)
				{
					$sql = "INSERT INTO jbsakad.nap SET nis='$nis', idinfo='$idinfo', idaturan='$idaturan', nilaiangka='$pk', nilaihuruf='$gpk'";
					QueryDbTrans($sql, $success);
				}

				if ($success)
				{
					$sql = "INSERT INTO jbsakad.komennap SET nis='$nis', idinfo='$idinfo', predikat='$predikat', komentar=''";
					QueryDbTrans($sql, $success);
				}
			} 
			else if ($Simpan == "Ubah")
			{
				if ($success)
				{
					$sql = "UPDATE jbsakad.nap SET nilaiangka='$pk', nilaihuruf='$gpk' WHERE nis='$nis' AND idinfo='$idinfo' AND idaturan='$idaturan'";
					QueryDbTrans($sql, $success);
				}
					
				if ($success)
				{
					$sql = "UPDATE jbsakad.komennap SET predikat='$predikat' WHERE nis='$nis' AND idinfo='$idinfo'";
					QueryDbTrans($sql, $success);
				}
			}
			$konter++;
		}
	}
	
	if ($success)
		CommitTrans();
	else
		RollbackTrans();
}

if ($_REQUEST["op"]  == "dw984j5hx3vbdc") 
{
	$replid = $_REQUEST["replid"];
	
	$success = true;
	BeginTrans();
	
	$sql = "DELETE FROM jbsakad.nap WHERE idinfo='$replid'";
	$res = QueryDbTrans($sql, $success);
	
	if ($success)
	{
		$sql = "DELETE FROM jbsakad.infonap WHERE replid='$replid'";
		$res = QueryDbTrans($sql, $success); 
	}
	
	if ($success)
	{	
		CommitTrans(); 
		CloseDb(); ?>
		<script language = "javascript" type = "text/javascript">
            alert ('Data telah dihapus');
            document.location.href="penentuan_content.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&semester=<?=$semester?>&nip=<?=$nip?>&tahun=<?=$tahun?>&aspek=<?=urlencode((string) $aspek)?>&aspekket=<?=urlencode((string) $aspekket)?>";
        </script>
<?php 	exit();
	}
	else
	{
		RollbackTrans(); 
		CloseDb(); ?>
		<script language = "javascript" type = "text/javascript">
            alert ('Gagal menghapus data!');
            document.location.href="penentuan_content.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&semester=<?=$semester?>&nip=<?=$nip?>&tahun=<?=$tahun?>&aspek=<?=urlencode((string) $aspek)?>&aspekket=<?=urlencode((string) $aspekket)?>";
        </script>
<?php 	exit();
	}
}

//cek keberadaan nap dan idinfo
$idinfo = 0;
$nap_ada = 0;
$nilaimin = "";
$sql = "SELECT replid, nilaimin FROM jbsakad.infonap WHERE idpelajaran='$pelajaran' AND idsemester='$semester' AND idkelas='$kelas'";
$res = QueryDb($sql);
if (mysqli_num_rows($res) > 0)
{
	$row = mysqli_fetch_row($res);
	$idinfo = $row[0];
	$nilaimin = $row[1];
	
	$sql = "SELECT COUNT(n.replid)
			  FROM jbsakad.aturannhb a, jbsakad.kelas k, jbsakad.nap n
			 WHERE n.idaturan = a.replid AND a.nipguru = '$nip' AND a.idtingkat = k.idtingkat AND k.replid = '$kelas'
			   AND a.idpelajaran = '$pelajaran' AND a.dasarpenilaian = '$aspek'
			   AND n.idinfo = '$idinfo' ";
	$res = QueryDb($sql);
	$row = mysqli_fetch_row($res);
	$nap_ada = $row[0];
}

// Hitung jumlah bobot dan banyaknya aturan
$sql = "SELECT SUM(bobot) as bobotPK, COUNT(a.replid) 
		  FROM jbsakad.aturannhb a, kelas k 
		 WHERE a.nipguru = '$nip' AND a.idtingkat = k.idtingkat AND k.replid = '$kelas' 
		   AND a.idpelajaran = '$pelajaran' AND a.dasarpenilaian = '$aspek' AND a.aktif = 1";
$res = QueryDb($sql);
$row = @mysqli_fetch_row($res);
$bobot_PK = $row[0];
$jum_nhb = $row[1];

// get jumlah pengujian
$sql = "SELECT j.jenisujian as jenisujian, a.bobot as bobot, a.replid, a.idjenisujian 
		  FROM jbsakad.aturannhb a, jbsakad.jenisujian j, kelas k 
		 WHERE a.idtingkat = k.idtingkat AND k.replid = '$kelas' AND a.nipguru = '$nip' 
		   AND a.idpelajaran = '$pelajaran' AND a.dasarpenilaian = '$aspek' 
		   AND a.idjenisujian = j.replid AND a.aktif = 1 
	  ORDER BY a.replid";  
$result_get_aturan_PK = QueryDb($sql);
$jum_PK = @mysqli_num_rows($result_get_aturan_PK);

//Ambil nilai grading
$sql = "SELECT grade 
		  FROM aturangrading a, kelas k 
		 WHERE a.idpelajaran = '$pelajaran' AND a.idtingkat = k.idtingkat AND k.replid = '$kelas' 
		   AND a.dasarpenilaian = '$aspek' AND a.nipguru = '$nip'
	  ORDER BY nmin DESC";
$res = QueryDb($sql);
$cntgrad = 0;
while ($row = @mysqli_fetch_array($res)) 
{
	$grading[$cntgrad] = $row['grade'];
	$cntgrad++;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Perhitungan Rapor ['Content']</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript">

function cek()
{
	var nilaimin = document.getElementById("nilaimin").value;
	
	if (nilaimin.length == 0)
	{
		alert ('Anda harus memasukan Nilai Kriteria Ketuntasan Minimal');
		document.getElementById("nilaimin").focus();
		return false;
	} 
	else 
	{	
		if (isNaN(nilaimin))
		{
			alert ('Nilai KKM harus berupa bilangan!');			
			document.getElementById("nilaimin").focus();
			return false;
		}
		if (parseInt(nilaimin) > 100)
		{
			alert ('Rentang nilai KKM harus di antara 0 s/d 100!');
			document.getElementById("nilaimin").focus();
			return false;
		}
	}

//	var numdata = document.getElementById("numdata").value;
//	var counter = 1;
//	while (counter <= numdata)
//	{
//		var nis = document.getElementById("nis_"+counter).value;
//		var pk = document.getElementById("PK_"+counter).value;
//		var gpk = document.getElementById("G_PK_"+counter).value;
//		var p = document.getElementById("P_"+counter).value;
//		var gp = document.getElementById("G_P_"+counter).value;
//		//alert ('NIS='+nis+' ,Nil PK='+pk+' ,Grade PK='+gpk+' ,Nil P='+p+' ,Grade P='+gp);
//		counter++;
//	}
	return true;
}

function hapus(replid)
{
	if (confirm('Anda yakin akan menghapus data nilai dan komentar siswa di kelas ini?'))
		document.location.href="penentuan_content.php?op=dw984j5hx3vbdc&replid="+replid+"&pelajaran=<?=$pelajaran?>&departemen=<?=$departemen?>&kelas=<?=$kelas?>&nip=<?=$nip?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahun=<?=$tahun?>&aspek=<?=urlencode((string) $aspek)?>&aspekket=<?=urlencode((string) $aspekket)?>";
}

function recount()
{
	if (confirm('Anda yakin akan menghitung ulang nilai rapor siswa di kelas ini?'))
		document.location.href="penentuan_content.php?op=b91c61e239xn8e3b61ce1&pelajaran=<?=$pelajaran?>&departemen=<?=$departemen?>&kelas=<?=$kelas?>&nip=<?=$nip?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahun=<?=$tahun?>&aspek=<?=urlencode((string) $aspek)?>&aspekket=<?=urlencode((string) $aspekket)?>";
}

function cetak_excel()
{
	newWindow('penentuan_cetak_excel.php?pelajaran=<?=$pelajaran?>&departemen=<?=$departemen?>&kelas=<?=$kelas?>&nip=<?=$nip?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahun=<?=$tahun?>&aspek=<?=urlencode((string) $aspek)?>&aspekket=<?=urlencode((string) $aspekket)?>','CetakExcel','100','100','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function detail(replid)
{
	newWindow('../library/detail_siswa.php?replid='+replid, 'DetailSiswa','660','657','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function focusNext(elemName, evt) 
{
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) 
	{
		document.getElementById(elemName).focus();
		return false;
	}
	return true;
}

function panggil(elem, total)
{	
	var x, y, i, z, m, n, g;
	var lain = new Array();
	lain[0] = "nilaimin";
	for (x=1;x<=total;x++){
		//var z = parseInt(x)+1;
		lain[x] = "PK_"+x;
		y = parseInt(total) + 1 + x ;
		lain[y] = "G_PK_"+x;
		m = parseInt(total) + y;
		lain[m] = "P_"+x;
		n = parseInt(total) + m;
		lain[n] = "G_P_"+x;
		g = parseInt(total) + n;
		lain[g] = "predikat_"+x;
	}
	
	for (i=0;i<lain.length;i++) 
	{
		if (lain[i] == elem) 
		{
			document.getElementById(elem).style.background='#4cff15';
		} 
		else 
		{
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
	
}

</script>

<style type="text/css">
<!--
.style1 {color: #FFFF00}
.style3 {color: #00FFFF}
-->
</style>
</head>
<body topmargin="5" leftmargin="5" onLoad="document.getElementById('PK_1').focus()">
<form action="penentuan_content.php" method="get" onSubmit="return cek()">
<input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>">	 
<input type="hidden" name="semester" id="semester" value="<?=$semester?>">
<input type="hidden" name="nip" id="nip" value="<?=$nip?>">
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>">	 
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>">
<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>">
<input type="hidden" name="aspek" id="aspek" value="<?=$aspek?>">
<input type="hidden" name="aspekket" id="aspekket" value="<?=$aspekket?>">

<font style="font-size:18px; color:#999; font-family:Verdana, Geneva, sans-serif"><strong><?=$namapel?></strong> - <?=$aspekket?></font>
<br /><br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <table width="100%" border="0" height="100%">
	 <tr>
    <td align="left" width="60%">
    <strong>Nilai Kriteria Ketuntasan Minimal (KKM): </strong>
    <input type="text" name="nilaimin" id="nilaimin" value="<?=$nilaimin?>" size="7" maxlength="5" />
    </td>
    <td align="right" width="40%">
         <a href="#" style="cursor:pointer" onClick="document.location.reload()">
         	<img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh
         </a>&nbsp;&nbsp;
         <a href="JavaScript:cetak_excel()">
         	<img src="../images/ico/excel.png" border="0" onMouseOver="showhint('Cetak dalam format Excel!', this, event, '80px')"/>&nbsp;Cetak Excel
         </a>
    </td>
    </tr>
    </table>
    <br />    
	<table width="100%" border="1" class="tab" id="table" bordercolor="#000000">  
  	<tr align="center">
    	<td height="30" class="headerlong" width="4%" rowspan="2">No</td>
        <td height="30" class="headerlong" width="10%" rowspan="2">N I S</td>
        <td height="30" class="headerlong" width="*" rowspan="2">Nama</td>    	    
        <td height="15" colspan="<?=(int)$jum_PK?>" class="headerlong">Nilai Akhir</td>
		<td height="15" colspan="2" class="headerlong" width="13%"><span class="style1">Nilai <?=$aspekket?></span></td>
    </tr>
    <tr height="15" class="header" align="center">
	<?php $i = 0;
		while ($row_PK = @mysqli_fetch_array($result_get_aturan_PK)) 
		{			
            $ujian[$i++] = [$row_PK['replid'], $row_PK['bobot'], $row_PK['idjenisujian'], $aspek];  ?>
    		<td width="8%" class="headerlong">
            	<span class="style1"><?= $row_PK['jenisujian']." (".$row_PK['bobot'].")" ?></span>
            </td>
    <?php } ?>
		<td align="center" class="headerlong"><span class="style1">Angka</span></td>
        <td align="center" class="headerlong"><span class="style1">Huruf</span></td>
	</tr>
<?php //Mulai perulangan siswa
	$sql = "SELECT replid, nis, nama 
	          FROM jbsakad.siswa 
			 WHERE idkelas='$kelas' AND aktif=1 
		  ORDER BY nama";
  	$res_siswa = QueryDb($sql);
  	$cnt = 1;
	$total = mysqli_num_rows($res_siswa);
  	while ($row_siswa = @mysqli_fetch_array($res_siswa)) 
	{ ?>
  	<tr height="25">
    	<td align="center"><?=$cnt?></td>
    	<td align="center">
        	<a href="#" onMouseOver="showhint('Lihat Detail Siswa', this, event, '80px')" 
               onClick="detail(<?=$row_siswa['replid']?>)"><?=$row_siswa['nis']?>
            </a>
        </td>
    	<td><?=$row_siswa['nama']?></td>
	<?php foreach ($ujian as $value) 
		{ 
			$sql = "SELECT n.nilaiAU as nilaiujian 
			          FROM jbsakad.nau n, jbsakad.aturannhb a 
				     WHERE n.idpelajaran = '$pelajaran' AND n.idkelas = '$kelas' 
					   AND n.nis = '".$row_siswa['nis']."' AND n.idsemester = '$semester' 
				       AND n.idjenis = '".$value[2]."' AND n.idaturan = a.replid 
					   AND a.replid = '$value[0]'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_array($res);
			echo "<td align='center'>" . $row['nilaiujian'] . "</td>";
		}  	?>
	   	<td align="center"><strong>
	<?php 	$ext_idinfo = "";
		if ($idinfo != "")
			$ext_idinfo = " AND i.replid = '".$idinfo."'";
			
		$sql = "SELECT n.nilaihuruf, n.nilaiangka, i.nilaimin
				  FROM jbsakad.nap n, jbsakad.aturannhb a, jbsakad.infonap i 
				 WHERE n.idinfo = i.replid 
				   AND n.nis = '".$row_siswa['nis']."' 
				   AND i.idpelajaran = '$pelajaran' 
				   AND i.idsemester = '$semester'
				   AND i.idkelas = '$kelas'
				   AND n.idaturan = a.replid   
				   AND a.dasarpenilaian = '$aspek' 
				       $ext_idinfo";
		$res = QueryDb($sql);
		$nilaiangka_pemkonsep = @mysqli_num_rows($res);
		$row_get_nap_pemkonsep = @mysqli_fetch_row($res);
		
		if ($nilaiangka_pemkonsep == 0) 
		{		
			//Belum ada data nilai di database
			$jumlah = 0;
			foreach ($ujian as $value) 
			{		
				$sql = "SELECT n.nilaiAU 
						  FROM jbsakad.nau n, jbsakad.aturannhb a 
						 WHERE n.idkelas = = '".$kelas."' AND n.nis = '".$row_siswa['nis']."' 
						   AND n.idsemester = '$semester' AND n.idpelajaran = '$pelajaran'
						   AND n.idjenis = '".$value[2]."' AND n.idaturan = a.replid 
						   AND a.replid = '$value[0]'";   
				$res = QueryDb($sql);
				$row = @mysqli_fetch_array($res);
				$nau = $row["nilaiAU"];
				$bobot = $value[1];
				$nap = $nau * $bobot;
				$jumlah = $jumlah + $nap;
			}
			$nilakhirpk = round($jumlah / $bobot_PK, 2); ?>		
            
    		<input <?=$dis_text?> type="text" name="PK_<?=$cnt?>" id="PK_<?=$cnt?>" value="<?=$nilakhirpk?>" 
             size="4" maxlength="5" onKeyPress="return focusNext('G_PK_<?=$cnt?>',event)" onFocus="panggil('PK_<?=$cnt?>',<?=$total?>)">
             
	<?php } 
		else 
		{ 
			//Ada data nilai di database
			$nilakhirpk = $row_get_nap_pemkonsep[1];
			$warna = "";
			if ($nilakhirpk < $row_get_nap_pemkonsep[2])
				$warna = "onMouseOver=\"showhint('Nilai di bawah nilai standar kelulusan', this, event, '100px')\" class='text_merah'";	?>
                
				<input <?=$dis_text?> type="text" name="PK_<?=$cnt?>" id="PK_<?=$cnt?>" value="<?=$nilakhirpk?>" 
                 size="4" <?=$warna?> maxlength="5" onKeyPress="return focusNext('G_PK_<?=$cnt?>',event)" onFocus="panggil('PK_<?=$cnt?>',<?=$total?>)">
                 
	<?php 	} ?>
			</strong>
    	</td>
        <!-- Grading Pemahaman konsep -->
        <td height="25" align="center"><strong>
	<?php  if ($nilakhirpk == "") 
		{
			$grade_PK = $grading[count($grading)-1];
		} 
		else 
		{
			if ($nilaiangka_pemkonsep == 0) 
			{ 
				$sql = "SELECT grade 
				          FROM aturangrading a, kelas k 
					 	 WHERE a.idpelajaran = '$pelajaran' AND a.idtingkat = k.idtingkat AND k.replid = '$kelas' 
						   AND a.dasarpenilaian = '$aspek' AND a.nipguru = '$nip' AND '$nilakhirpk' BETWEEN a.nmin AND a.nmax";
				$res = QueryDb($sql);
				$row = @mysqli_fetch_array($res);
				$grade_PK = $row['grade'];
			} 
			else 
			{
				$grade_PK = $row_get_nap_pemkonsep[0];
			} 
		}	
	?>
   		<select <?=$dis?> name="G_PK_<?=$cnt?>" id="G_PK_<?=$cnt?>" 
         onkeypress="return focusNext('P_<?=$cnt?>',event)" onFocus="panggil('G_PK_<?=$cnt?>',<?=$total?>)"> 
         
	<?php foreach ($grading as $valgrade)
		{  ?>
			<option value="<?=$valgrade?>" <?=StringIsSelected($valgrade, $grade_PK)?>><?= $valgrade ?></option>
	<?php }  ?>
		</select>
		</strong>
        </td>
  	  </tr>
  	  <input type="hidden" name="nis_<?=$cnt?>" id="nis_<?=$cnt?>" value="<?=$row_siswa['nis']?>">
<?php  	$cnt++;
  	} ?>
      <tr height="25">
	      <td bgcolor="#996600" colspan="<?=$jum_nhb+8; ?>">
		    <input type="hidden" name="numdata" id="numdata" value="<?=$cnt - 1?>"/>&nbsp;
            <input type="hidden" name="idinfo" id="idinfo" value="<?= $idinfo ?>">
	<?php 	if ($nap_ada > 0)
			{	?>
				
				<input <?=$dis?> class="but" type="submit" value="Ubah" name="Simpan" id="simpan" />&nbsp;&nbsp;
			    <input <?=$dis?> class="but" type="button" value="Hitung Ulang Angka & Huruf" name="Recount" id="Recount" onClick="recount()"/>&nbsp;&nbsp;
			    <a href="#" onClick="hapus('<?=$idinfo?>')">
                	<img src="../images/ico/hapus.png" border="0"><font color="#FFFFFF">&nbsp;Hapus Nilai dan Komentar Rapor Kelas Ini</font></a>
    <?php 		} 
			else 
			{ ?>
				<input <?=$dis?> class="but" type="submit" value="Simpan" name="Simpan" id="simpan"/>
	<?php 		} ?>
        </td>
	</tr>
</table>
</form>
<script language='JavaScript'>
	Tables('table', 1, 0);
</script>
</body>
</html>
<?php
CloseDb();
?>