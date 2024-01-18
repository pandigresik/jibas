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
require_once('../sessionchecker.php');
require_once('../library/dpupdate.php');
require_once('HitungRata.php');


if(isset($_REQUEST["semester"]))
	$semester = $_REQUEST["semester"];
if(isset($_REQUEST["kelas"]))
	$kelas = $_REQUEST["kelas"];
if(isset($_REQUEST["idaturan"]))
	$idaturan = $_REQUEST["idaturan"];
$manual = 0;
if(isset($_REQUEST["manual"]))//10
	$manual = $_REQUEST["manual"];
if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];

OpenDb();
$sql = "SELECT p.nama, p.replid AS pelajaran, a.dasarpenilaian, j.jenisujian, j.replid AS jenis, dp.keterangan 
          FROM jbsakad.aturannhb a, jbsakad.pelajaran p, jenisujian j, dasarpenilaian dp 
		 WHERE a.dasarpenilaian = dp.dasarpenilaian AND a.replid='$idaturan' AND p.replid = a.idpelajaran AND a.idjenisujian = j.replid";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
$namapel = $row['nama'];
$pelajaran = $row['pelajaran'];
$aspek = $row['dasarpenilaian'];
$aspekket = $row['keterangan'];
$namajenis = $row['jenisujian'];
$jenis = $row['replid'];
$idjenisujian = $row['jenis'];

if ($op == "jfd84rkj843h834jjduw3")
{
	BeginTrans();
	$success = true;
	
	$sql_hapus_nau = "DELETE FROM jbsakad.nau WHERE idaturan='".$_REQUEST['idaturan']."' AND idkelas = '$kelas' AND idsemester = '".$semester."'";
	QueryDbTrans($sql_hapus_nau, $success);
	
	if ($success)
	{
		$sql_hapus_nilai_ujian = "DELETE FROM jbsakad.nilaiujian WHERE idujian='".$_REQUEST['replid']."'";	
		QueryDbTrans($sql_hapus_nilai_ujian, $success);
	}

	if ($success)
	{
		$sql_hapus_ujian = "DELETE FROM jbsakad.ujian WHERE replid='".$_REQUEST['replid']."'";	
		QueryDbTrans($sql_hapus_ujian, $success);
	}
	
	if ($success)
	{
		$sql_hapus_ratauk = "DELETE FROM jbsakad.ratauk WHERE idujian = '".$_REQUEST['replid']."' AND idkelas = '$kelas' AND idsemester = '".$semester."'";
		QueryDbTrans($sql_hapus_ratauk, $success);
	}
	
	if ($success)
	{
		HitungUlangRataSiswa($kelas, $semester, $_REQUEST['idaturan'], $success);
	}
	
	if ($success) 
	{ 
		CommitTrans();	
	} 
	else 
	{ 
		RollbackTrans(); ?>
		<script language="javascript">
			alert ('Data gagal dihapus');
		</script>
<?php 	}	
} 
else if ($op == "osdiui4903i03j490dj")
{
	$sql_hapus_nau = "DELETE FROM jbsakad.nau WHERE idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan'";
	$result_hapus_nau = QueryDb($sql_hapus_nau);	
}
elseif ($op == "bwe24sssd2p24237lwi0234")
{
	// Hitung ulang Rata-rata Kelas & Siswa
	
	$success = true;
	BeginTrans();
	
	$sql = "SELECT nis FROM jbsakad.siswa WHERE idkelas='$kelas' AND aktif=1";
	$ressis = QueryDb($sql);
	while($success && ($rowsis = mysqli_fetch_row($ressis)))
	{
		$nis = $rowsis[0];
		HitungRataSiswa($kelas, $semester, $idaturan, $nis, $success);
	}
	
	$sql = "SELECT replid FROM jbsakad.ujian WHERE idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan'";
	$resuj = QueryDb($sql);
	while($success && ($rowuj = mysqli_fetch_row($resuj)))
	{
		$iduj = $rowuj[0];
		HitungRataKelasUjian($kelas, $semester, $idaturan, $iduj, $success);
	}
	
	if ($success)
		CommitTrans();
	else
		RollbackTrans();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<title>Penilaian [Content]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript">
function refresh(){			
	document.location.reload();		
}

function tambah(){
	newWindow('nilai_pelajaran_add.php?idaturan=<?=$idaturan?>&semester=<?=$semester?>&kelas=<?=$kelas?>','TambahNilai','680','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah_nilai(idujian,nis) {	
	newWindow('tambah_nilai_ujian.php?idujian='+idujian+'&nis='+nis,'TambahDataNilaiUjian','495','310','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah_nau(replid) {	
	newWindow('tambah_nau_persiswa.php?replid='+replid,'TambahDataNilaiAkhirUjian','400','450','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ubah_nilai(id) {	
	newWindow('ubah_nilai_ujian.php?id='+id+'&totnis=<?=$totnis?>','UbahDataNilaiUjian','495','307','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function edit(replid) {
	newWindow('ubah_ujian.php?replid='+replid,'UbahDataUjian','485','280','resizable=1,scrollbars=1,status=0,toolbar=0');
}	

function validate()
{
	var isi = 0;
	var jum = document.getElementById('jumujian').value;	
	for (i=1; i<=jum; i++) 
	{				
		var cek = document.getElementById('jenisujian'+i).checked;	
		var ujian = document.getElementById('ujian'+i).value;		
		var bobot = document.getElementById('bobot'+i).value;
		
		if (cek) 
		{
			document.getElementById('jenisujian'+i).value = 1;
			
			isi = 1;
			if (bobot.length > 0)
			{
				if (isNaN(bobot))
				{
					alert("Bobot nilai harus berupa bilangan!");
					document.getElementById('bobot'+i).focus();				
					return false;									
				} 
			} 
			else 
			{
				alert ("Anda harus mengisikan data untuk bobot nilai!"); 
				document.getElementById('bobot'+i).focus();				
				return false;
			} 
		}
		else
		{
			document.getElementById('jenisujian'+i).value = 0;
			document.getElementById('bobot'+i).value = "";
			bobot = "";
		}
		
		if (bobot.length > 0) 
		{
			if (!cek) 
			{
				alert ("Anda harus memberi centang terlebih dahulu!"); 
				document.getElementById('jenisujian'+i).focus();				
				return false;
			}
		}		
	}
	
	if (isi == 0) 
	{
		alert ("Anda harus mengisi setidaknya satu data bobot untuk menghitung otomatis!");
		return false; 
	}
	
	document.getElementById('tampil_nilai_pelajaran').submit(); 
}

function manual(){
	document.location.href="input_manual_nau.php?kelas=<?=$kelas?>&semester=<?=$semester?>&idaturan=<?=$idaturan?>";
}
function hapus(replid,i,nama) {
	if (confirm('Anda yakin akan menghapus nilai '+nama+'-'+i+' ini ?')){
	document.location.href="nilai_pelajaran_content.php?op=jfd84rkj843h834jjduw3&kelas=<?=$kelas?>&semester=<?=$semester?>&idaturan=<?=$idaturan?>&replid="+replid;
	}
}

function hitungUlangRata() 
{
	if (confirm('Apakah anda akan menghitung ulang rata-rata kelas dan siswa?'))
		document.location.href="nilai_pelajaran_content.php?op=bwe24sssd2p24237lwi0234&kelas=<?=$kelas?>&semester=<?=$semester?>&idaturan=<?=$idaturan?>";
}

function ubah_nau(replid){
	newWindow('ubah_nau_persiswa.php?replid='+replid,'UbahNilaiAkhirUjian',447,252,'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus_nau(){
	if (confirm('Anda yakin akan menghapus data nilai akhir ini?')){
	document.location.href="nilai_pelajaran_content.php?op=osdiui4903i03j490dj&kelas=<?=$kelas?>&semester=<?=$semester?>&idaturan=<?=$idaturan?>";
	}
}

function cetak_excel() {
	newWindow('nilai_pelajaran_excel.php?semester=<?=$semester?>&kelas=<?=$kelas?>&idaturan=<?=$idaturan?>','','120','150','resizable=1,scrollbars=1,status=0,toolbar=0')

}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

</script>
</head>
<body topmargin="0" leftmargin="0">
<form name="tampil_nilai_pelajaran" id="tampil_nilai_pelajaran" action="nilai_akhir_simpan.php" method="post" onSubmit="return validate();">
<input type="hidden" name="semester" id="semester" value="<?=$semester?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>" />
<input type="hidden" name="idaturan" id="idaturan" value="<?=$idaturan?>" />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <table width="100%" border="0" height="100%">
   	<tr>
    	<td>
        <table width="100%" border="0">
        <tr>
            <td width="17%"><strong>Pelajaran</strong></td>
            <td><strong>: <?=$namapel ?> </strong></td>
            <td rowspan="2"></td>
        </tr>
        <tr>
            <td><strong>Aspek Penilaian</strong></td>
            <td><strong>: <?=$aspekket?></strong></td>            
        </tr>
    	<tr>
            <td><strong>Jenis Pengujian</strong></td>
            <td><strong>: <?=$namajenis?></strong></td>            
<?php 	$sql_cek_ujian = "SELECT u.replid, u.tanggal, u.deskripsi, u.idrpp 
						FROM jbsakad.ujian u 
					   WHERE u.idaturan='$idaturan' AND u.idkelas='$kelas' AND u.idsemester='$semester' ORDER by u.tanggal ASC";
    $result_cek_ujian = QueryDb($sql_cek_ujian);		
	$jumlahujian = @mysqli_num_rows($result_cek_ujian);
	if (mysqli_num_rows($result_cek_ujian) > 0) 
	{ ?>
            
            <td align="right">
                    
            <a href="#" style="cursor:pointer" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
            <a href="JavaScript:cetak_excel()"><img src="../images/ico/excel.png" border="0" onMouseOver="showhint('Cetak dalam format Excel!', this, event, '80px')"/>&nbsp;Cetak Excel</a>&nbsp;&nbsp;   
	  		<a href="#" style="cursor:pointer" onClick="tambah();" ><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah Penilaian!', this, event, '60px')"/>&nbsp;Tambah Penilaian</a>
      		</td>
  		</tr>
        </table>
        <br />
  		
        <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
       	<tr>
            <td height="30" class="headerlong" align="center" width="4%">No</td>
            <td height="30" class="headerlong" align="center" width="10%">N I S</td>
            <td height="30" class="headerlong" align="center" width="*">Nama</td>
		<?php
       
        $i=1;
        while ($row_cek_ujian=@mysqli_fetch_array($result_cek_ujian)){
			$sql_get_rpp_name = "SELECT rpp FROM rpp WHERE replid='".$row_cek_ujian['idrpp']."'";
			if (!empty($row_cek_ujian['idrpp'])) {
				$res_get_rpp_name = QueryDb($sql_get_rpp_name);
				$rpp = @mysqli_fetch_array($res_get_rpp_name);
				$namarpp = $rpp['rpp'];
			} else {
				$namarpp = "Tanpa RPP";
			}
			$nilaiujian[$i] = 0;
			$idujian[$i] = $row_cek_ujian['replid'];			
            $tgl = explode("-",(string) $row_cek_ujian['tanggal']);
			
        ?>
           <td height="30" width="50" class="headerlong" align="center" onMouseOver="showhint('RPP: <?=$namarpp .'<br>'?> Materi: <?=$row_cek_ujian['deskripsi']?>', this, event, '120px')"><?=$namajenis."-".$i?>&nbsp;
			<br /><?=$tgl[2]."/".$tgl[1]."/".substr($tgl[0],2)?><br />
      		<a href="JavaScript:edit(<?=$row_cek_ujian['replid']?>)"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Ujian!', this, event, '50px')" /></a>&nbsp;
		<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) {	?>            
            <a href="JavaScript:hapus(<?=$row_cek_ujian['replid']?>, <?=$i?>, '<?=$namajenis?>')"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Ujian!', this, event, '50px')" /></a>
		<?php } ?>
    		</td>
		<?php
        	$i++;
        }
        ?>
            <td height="30" class="headerlong" align="center" width="50">Rata- rata Siswa</td>
            <td height="30" class="headerlong" align="center" width="55">NA <?=$namajenis?>
	<?php $sql_get_nau_per_kelas="SELECT nilaiAU,keterangan FROM jbsakad.nau WHERE idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan'";
        	//echo $sql_get_nau_per_kelas;
		$result_get_nau_per_kelas=QueryDb($sql_get_nau_per_kelas);
		if (@mysqli_num_rows($result_get_nau_per_kelas)<>0){           
			$manual=@mysqli_num_rows($result_get_ket_nau_per_kelas);
			if (SI_USER_LEVEL() != $SI_USER_STAFF) 
			{	?>	
            	<br /><a href="JavaScript:hapus_nau()"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Nilai Akhir Ujian!', this, event, '100px')" /></a>&nbsp;
<?php 			}
        }	 ?>
    		</td>
		</tr>
	<?php
        $sql_siswa="SELECT * FROM jbsakad.siswa WHERE idkelas='$kelas' AND aktif=1 ORDER BY nama ASC";
        $result_siswa=QueryDb($sql_siswa);
        $cnt=1;
        $jumsiswa = mysqli_num_rows($result_siswa);
        while ($row_siswa=@mysqli_fetch_array($result_siswa)){
            $nilai = 0;
		?>
  		<tr height="25">
            <td align="center"><?=$cnt?></td>
            <td align="center"><?=$row_siswa['nis']?></td>
            <td><a href="#" onMouseOver="showhint('Lihat Detail Siswa!', this, event, '80px')"  onClick="newWindow('../library/detail_siswa.php?replid=<?=$row_siswa['replid']?>', 'DetailSiswa','660','657','resizable=1,scrollbars=1,status=0,toolbar=0')"><?=$row_siswa['nama']?></a></td>
          
		<?php 	for ($j=1;$j<=count($idujian);$j++) { ?>
            <td align="center">							
			<?php $sql_cek_nilai_ujian="SELECT * FROM jbsakad.nilaiujian WHERE idujian='".$idujian[$j]."' AND nis='".$row_siswa['nis']."'";
                $result_cek_nilai_ujian=QueryDb($sql_cek_nilai_ujian);
               	if (@mysqli_num_rows($result_cek_nilai_ujian)>0){
                    $row_cek_nilai_ujian=@mysqli_fetch_array($result_cek_nilai_ujian);
                	$nilaiujian[$j] = $nilaiujian[$j]+$row_cek_nilai_ujian['nilaiujian'];					
                	$nilai = $nilai+$row_cek_nilai_ujian['nilaiujian'];  ?>
					<a href="JavaScript:ubah_nilai(<?=$row_cek_nilai_ujian['replid']?>)" onMouseOver="showhint('Ubah Nilai Ujian!', this, event, '80px')"><?=$row_cek_nilai_ujian['nilaiujian']?></a>          
            <?php 	if ($row_cek_nilai_ujian['keterangan']<>"")
                        echo "<strong><font color='blue'>)*</font></strong>";
                	} else {   ?>         	
                    	<a href="JavaScript:tambah_nilai(<?=$idujian[$j]?>,'<?=$row_siswa['nis']?>')"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah Nilai Ujian!', this, event, '80px')" /></a>
			<?php 	} ?>
            </td>
		<?php  } ?>
    		<td align="center">
			<?php 
			   GetRataSiswa2($pelajaran, $idjenisujian, $kelas, $semester, $idaturan, $row_siswa['nis']);
			?>
            </td>
    		<td align="center">
	<?php 				
			$sql_get_nau_per_nis="SELECT nilaiAU,replid,keterangan FROM jbsakad.nau WHERE nis='".$row_siswa['nis']."' AND idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan'";
		
			//echo $sql_get_nau_per_nis;			
			$result_get_nau_per_nis=QueryDb($sql_get_nau_per_nis);
			if (mysqli_num_rows($result_get_nau_per_nis) > 0) 
			{
				$row_get_nau_per_nis=@mysqli_fetch_array($result_get_nau_per_nis);
				if ($row_get_nau_per_nis['nilaiAU'] <> 0) 
				{ 	?>
            		<a href="Javascript:ubah_nau('<?=$row_get_nau_per_nis['replid']?>')" onMouseOver="showhint('Ubah Nilai Akhir Ujian!', this, event, '80px')" ><?=$row_get_nau_per_nis['nilaiAU']?></a>            
<?php 			} 
				else 
				{		?>
					<a href="JavaScript:tambah_nau(<?=$row_get_nau_per_nis['replid']?>)"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah Nilai Akhir Ujian!', this, event, '80px')" /></a>    
			<?php 	}
				if ($row_get_nau_per_nis['keterangan']<>"")
					echo "<font color='#067900'><strong>)*</strong></font>";
			} ?>
            </td>
    	</tr>
  <?php
  		$cnt++;
  		} ?>
		<tr>
        	<td height="25" class="header" align="center" colspan="3">Rata-rata Kelas</td>
            
	<?php 	$rata = 0;
        for ($j=1;$j<=count($idujian);$j++) { 
        	$rata = $rata+($nilaiujian[$j]/$jumsiswa);
    ?>
			<td align="center" bgcolor="#FFFFFF"><?=GetRataKelas($_REQUEST['kelas'],$_REQUEST['semester'],$idujian[$j])?><?//=round(($nilaiujian[$j]/$jumsiswa),2)?></td>
	<?php 	} ?>
            <td align="center" bgcolor="#FFFFFF"><?=round($rata/count($idujian),2)?></td>
            <td align="center" bgcolor="#FFFFFF">
   	<?php 				
		$sql_get_nau_per_nis="SELECT SUM(nilaiAU)/$jumsiswa FROM jbsakad.nau WHERE idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan'";
		
		//echo $sql_get_nau_per_nis;			
		$result_get_nau_per_nis=QueryDb($sql_get_nau_per_nis);
		if (mysqli_num_rows($result_get_nau_per_nis) > 0) {
			$row = mysqli_fetch_row($result_get_nau_per_nis);     
			echo round($row[0],2);
     	} ?>        
            </td>
        </tr>
		</table>
		<script language='JavaScript'>
            Tables('table', 1, 0);
    	</script>
		</td>
    </tr>
    <tr>
    	<td align="right"><strong><font color="blue">)*</font> ada keterangan &nbsp;&nbsp; 
		<strong><font color="#067900">)*</font> Nilai Akhir Siswa dihitung manual </strong>&nbsp;&nbsp;
        <input type="button" class="but" value="Hitung Ulang Rata-rata Kelas & Siswa" name="recountrk" id="recountrk" onClick="hitungUlangRata()" />
        </td>
   	</tr>
  	<tr>
  		<td>
        <br />
        <fieldset style="background-color:#FFFFC6"><legend><strong>Hitung Nilai Akhir <?=$namajenis?> Berdasarkan </strong></legend>    
        <table width="100%" border="0">
      
        <tr>
            <td><strong>A. Perhitungan Otomatis</strong></td>
            <td><strong>B. Perhitungan Manual</strong></td>
        </tr>
        
        <tr>
    		<td width="55%">  
                <input <?=$dis_btn?> type="button" name="hitung" id="hitung" 
                 value="Hitung dan Simpan Nilai Akhir <?=$namajenis?>" class="but" 
                 onClick="return validate(); document.getElementById('tampil_nilai_pelajaran').submit();" />
                <hr width="350" align="left"/>
                <input type="hidden" name="pilih" value="1"> 
                <table class="tab" id="table" border="1" style="border-collapse:collapse" width="350" bordercolor="#000000">    		
                <tr height="30" class="header" align="center">
                    <td width="85%" colspan="2"><?=$namajenis?></td>
                    <td width="15%">Bobot</td>
                </tr>
<?php 			$sql_cek_ujian = "SELECT *, u.replid as replid 
							  FROM jbsakad.ujian u 
							  WHERE u.idaturan = '$idaturan' AND u.idkelas = '$kelas' AND u.idsemester = '$semester' 
							  ORDER by u.tanggal ASC";
			$result_cek_ujian = QueryDb($sql_cek_ujian);
			$jumujian = mysqli_num_rows($result_cek_ujian);
			$ibobot = 1;
			while ($row_cek_ujian = @mysqli_fetch_array($result_cek_ujian))
			{
				if (!empty($row_cek_ujian['idrpp'])) 
				{
					$sql_get_rpp_name = "SELECT rpp FROM rpp WHERE replid = '".$row_cek_ujian['idrpp']."'";
					$res_get_rpp_name = QueryDb($sql_get_rpp_name);
					$rpp = @mysqli_fetch_array($res_get_rpp_name);
					$namarpp = $rpp['rpp'];
				} 
				else 
				{
					$namarpp = "Tanpa RPP";
				}
				
				$sql_get_bobotnya = "SELECT b.replid, b.bobot FROM jbsakad.bobotnau b WHERE b.idujian='".$row_cek_ujian['replid']."'";								
				$result_get_bobotnya = QueryDb($sql_get_bobotnya);
				$nilai_bobotnya = @mysqli_fetch_array($result_get_bobotnya);	?>
	    		<tr height="25">
					<td width="10%" height="25">
<?php 				if (mysqli_num_rows($result_get_bobotnya) > 0) 
				{ ?>
					<input <?=$dis?> type="checkbox" name="<?='jenisujian'.$ibobot ?>" id="<?='jenisujian'.$ibobot ?>" 
                     value="1" checked  onKeyPress="return focusNext('bobot<?=$ibobot?>',event)">				
            		<input type="hidden" name="<?='replid'.$ibobot?>" id = "<?='replid'.$ibobot?>" value="<?=$nilai_bobotnya['replid']?>">
<?php  			} 
				else 
				{ ?>
					<input <?=$dis?> type="checkbox" name="<?='jenisujian'.$ibobot ?>" id="<?='jenisujian'.$ibobot ?>" 
                     value="1"  onKeyPress="return focusNext('bobot<?=$ibobot?>',event)"> 
<?php 				} ?>
	               	</td>
    	            <td> 
					<?=$namajenis."-".$ibobot." (".format_tgl($row_cek_ujian['tanggal']).")"; ?>
                	<br>RPP: <?=$namarpp?>
                    <input type="hidden" name="<?='ujian'.$ibobot?>" id = "<?='ujian'.$ibobot?>" value="<?=$row_cek_ujian['replid']?>"</td>
	                <td align="center">
    	            <input type="text" maxlength="3" name="<?='bobot'.$ibobot ?>" id="<?='bobot'.$ibobot ?>" size="2" value ="<?=$nilai_bobotnya['bobot']?>"  <?php if ($ibobot!=$jumujian) { ?> onKeyPress="return focusNext('jenisujian<?=(int)$ibobot+1?>',event)" <?php } else { ?> onkeypress="return focusNext('hitung',event)" <?php } ?> >
                </td>
            </tr>
			<?php
				$ibobot++;
			}
			?>
            	<input type="hidden" name="jumujian" id="jumujian" value="<?=$jumujian?>" />
            </table>
            <script language='JavaScript'>
                Tables('table', 1, 0);
            </script>
        	<hr width="350" align="left"/><br />
			</td>
    		<td valign="top">
				<input type="button" name="hitungmanual" value="Hitung Manual Nilai Akhir <?=$namajenis?>" class="but" onClick="manual()">
            </td>
  		</tr>
		</table>
    	</fieldset>
        </td>
	</tr>
	</table>
 <?php } else { ?> 		
<td></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200"> 
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data nilai ujian. 
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        </b></font>
     
	</td>
</tr>
</table>
<?php } ?> 
   	</td>
</tr>
</table>
</form>
</body>
</html>