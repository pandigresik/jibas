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

$departemen=$_REQUEST['departemen'];
$tahun=$_REQUEST['tahun'];
OpenDb();	
$query_mutasi="SELECT s.nis,s.nama,s.statusmutasi,j.jenismutasi,m.tglmutasi,m.keterangan,s.replid FROM jbsakad.siswa s, jbsakad.jenismutasi j,jbsakad.mutasisiswa m,jbsakad.kelas k,jbsakad.tahunajaran t WHERE t.departemen='$departemen' AND t.replid=k.idtahunajaran AND k.replid=s.idkelas AND j.replid=s.statusmutasi AND j.replid=m.jenismutasi AND s.nis=m.nis AND YEAR(m.tglmutasi)='$tahun' ORDER BY j.replid";

$query_mutasi="SELECT s.nis,s.nama,s.statusmutasi,j.jenismutasi,m.tglmutasi,m.keterangan,s.replid FROM jbsakad.siswa s, jbsakad.jenismutasi j,jbsakad.mutasisiswa m,jbsakad.kelas k,jbsakad.tahunajaran t WHERE t.departemen='$departemen' AND t.replid=k.idtahunajaran AND k.replid=s.idkelas AND j.replid=s.statusmutasi AND j.replid=m.jenismutasi AND s.nis=m.nis ORDER BY j.replid";

$result_mutasi=QueryDb($query_mutasi);

if (isset($_REQUEST['op']))
$op=$_REQUEST['op'];
if ($op=="fdh6rt5dgh98rth658rg48ger"){ //Hapus siswa
	//$nis=$_REQUEST['nis'];

	BeginTrans();
	$success=0;
	$sql_hapus="DELETE FROM jbsakad.mutasisiswa WHERE nis='".$_REQUEST['nis']."'";
	QueryDbTrans($sql_hapus,$success);
	if ($success){
		$sql_hapus_rwyt_kelas="DELETE FROM jbsakad.riwayatkelassiswa WHERE nis='".$_REQUEST['nis']."'";
		QueryDbTrans($sql_hapus_rwyt_kelas,$success);
	}
	if ($success){
		$sql_hapus_rwyt_dep="DELETE FROM jbsakad.riwayatdeptsiswa WHERE nis='".$_REQUEST['nis']."'";
		QueryDbTrans($sql_hapus_rwyt_dep,$success);
	}
	if ($success){
		$sql_hapus_siswa="DELETE FROM jbsakad.siswa WHERE nis='".$_REQUEST['nis']."'";
		QueryDbTrans($sql_hapus_siswa,$success);
	}

	if ($success){
		CommitTrans();
		if ($from_left!=1){
		?>
		<SCRIPT type="text/javascript" language="javascript">
		refresh();	
		</script>
		<?php
		} else {
		?>
		<SCRIPT type="text/javascript" language="javascript">
		refresh2();	
		</script>
		<?php
		}
	} else {
		RollBackTrans();
		if ($from_left!=1){
		?>
		<SCRIPT type="text/javascript" language="javascript">
		alert ('Gagal menghapus data !');
		refresh();	
		</script>
		<?php
		} else {
		?>
		<SCRIPT type="text/javascript" language="javascript">
		alert ('Gagal menghapus data !');
		refresh2();
		</script>
		<?php
		}
		}
		
}
if ($op=="jkds7o34jd98wejbf9uwe"){ //Aktifkan kembali

	BeginTrans();
	$success=0;
	$sql_hapus_mutasi="DELETE FROM jbsakad.mutasisiswa WHERE nis='".$_REQUEST['nis']."'";
	QueryDbTrans($sql_hapus_mutasi,$success);
	if ($success){
		$sql_update_siswa="UPDATE jbsakad.siswa SET aktif=1 WHERE nis='".$_REQUEST['nis']."'";
		QueryDbTrans($sql_update_siswa,$success);
	}
	if ($success){
		CommitTrans();
		if ($from_left!=1){
		?>
		<SCRIPT type="text/javascript" language="javascript">
			document.location.href="daftar_mutasi_siswa_footer.php?departemen=<?=$departemen?>&tahun=<?=$tahun?>";
		</script>
		<?php
		} else {
		?>
		<SCRIPT type="text/javascript" language="javascript">
			document.location.href="daftar_mutasi_siswa_footer.php?from_left=1&departemen=<?=$departemen?>&tahun=<?=$tahun?>";
		</script>
		<?php
		}
	} else {
		RollBackTrans();
		if ($from_left!=1){
		?>
		<SCRIPT type="text/javascript" language="javascript">
			alert ('Gagal mengaktifkan siswa !');
			document.location.href="daftar_mutasi_siswa_footer.php?departemen=<?=$departemen?>&tahun=<?=$tahun?>";
		</script>
		<?php
		} else {
		?>
		<SCRIPT type="text/javascript" language="javascript">
			alert ('Gagal mengaktifkan siswa !');
			document.location.href="daftar_mutasi_siswa_footer.php?from_left=1&departemen=<?=$departemen?>&tahun=<?=$tahun?>";
		</script>
		<?php
		}
		}


}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Daftar Mutasi Siswa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT type="text/javascript" language="text/javascript" src="../script/tables.js"></SCRIPT>
	<SCRIPT type="text/javascript" language="javascript" src="../script/common.js"></script>
	<SCRIPT type="text/javascript" language="javascript" src="../script/tools.js"></script>
	<SCRIPT type="text/javascript" language="javascript" src="../script/tooltips.js"></script>
	<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<link href="../style/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {
	color: #660000;
	font-weight: bold;
	font-size: 16px;
}
-->
</style>
</head>
<script language="javascript">
function aktifkan(nis){
	if (confirm('Anda yakin akan mengaktifkan kembali siswa ini ?')){
	document.location.href="daftar_mutasi_siswa_footer.php?departemen=<?=$departemen?>&tahun=<?=$tahun?>&op=jkds7o34jd98wejbf9uwe&nis="+nis;
}
}
function aktifkan2(nis){
	if (confirm('Anda yakin akan mengaktifkan kembali siswa ini ?')){
	document.location.href="daftar_mutasi_siswa_footer.php?from_left=1&departemen=<?=$departemen?>&tahun=<?=$tahun?>&op=jkds7o34jd98wejbf9uwe&nis="+nis;
}
}
function hapus(nis){
	if (confirm('Anda yakin akan menghapus data siswa ini ?')){
		document.location.href="daftar_mutasi_siswa_footer.php?departemen=<?=$departemen?>&tahun=<?=$tahun?>&op=fdh6rt5dgh98rth658rg48ger&nis="+nis;
	}
}
function hapus(nis){
	if (confirm('Anda yakin akan menghapus data siswa ini ?')){
		document.location.href="daftar_mutasi_siswa_footer.php?from_left=1&departemen=<?=$departemen?>&tahun=<?=$tahun?>&op=fdh6rt5dgh98rth658rg48ger&nis="+nis;
	}
}
function refresh(){
	document.location.href="daftar_mutasi_siswa_footer.php?departemen=<?=$departemen?>&tahun=<?=$tahun?>";
}
function refresh2(){
	document.location.href="daftar_mutasi_siswa_footer.php?from_left=1&departemen=<?=$departemen?>&tahun=<?=$tahun?>";
}
</script>

<body>
<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" background="../images/ico/b_mutasi.png" style="background-repeat:no-repeat; margin:0px; padding:0px;">
  <tr>
    <td valign="top">
  
    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#666666">
      <tr>
        <td colspan="2"><div align="right"><?php if ($from_left!=0){ ?><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Daftar Siswa yang dimutasi</font><br />
            <a href="../mutasi.php" target="content"> <font size="1" color="#000000"><b>Mutasi</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Daftar Siswa yang dimutasi</b></font><?php } ?><br><br>
          <input name="action" type="hidden" id="action2" value="<?php if(!empty($_GET['action'])) echo $_GET['action'] ; else echo "tambahJenisMutasi" ;?>">
          <input name="state" type="hidden" id="state2" value="jenis"><?php if(mysqli_num_rows($result_mutasi)<>0)
		  	{ 
			if ($from_left!=1){	?>
          <a href="#" onClick="newWindow('daftar_mutasi_cetak.php?tampil=tampil&departemen=<?=$departemen?>&tahun=<?=$tahun?>','',819,754,'')" onMouseOver="showhint('Cetak Daftar Siswa Yang Sudah dimutasi', this, event, '80px')"><img src="../images/ico/print.png" border="0">Cetak</a><?php 
				} else {
			?>
			 <a href="#" onClick="newWindow('daftar_mutasi_cetak.php?tampil=tampil&departemen=<?=$departemen?>','',819,754,'')" onMouseOver="showhint('Cetak Daftar Siswa Yang Sudah dimutasi', this, event, '80px')"><img src="../images/ico/print.png" border="0">Cetak</a>
			<?php
			}
			}?><br>
          <br></div></td>
        </tr>
      <tr>
        <td><table width="100%" border="1" class="tab" align="center" cellpadding="0" cellspacing="0" id="table">
          <tr class="header">
            <td width="33" height="30"><div align="center">No</div></td>
            <td width="136" height="30"><div align="center">NIS</div></td>
            <td width="142" height="30"><div align="center">Nama</div></td>
            <td width="97" height="30"><div align="center">Tanggal Mutasi</div></td>
            <td width="104" height="30"><div align="center">Jenis Mutasi </div></td>
            <td width="92" height="30"><div align="center">Keterangan Mutasi</div></td>
            <td width="78" height="30"><div align="center"></div></td>
          </tr>
		  <?php 
	
		  $a=0;
		  while($row_mutasi=mysqli_fetch_row($result_mutasi)){$a++;
		  ?>
          <tr>
            <td height="25"><?=$a; ?></td>
            <td height="25"><?=$row_mutasi[0]?></td>
            <td height="25"><?=$row_mutasi[1]?></td>
            <td height="25"><?=TglTextLong($row_mutasi[4])?></td>
            <td height="25"><?=$row_mutasi[3]?></td>
            <td height="25"><?=$row_mutasi[5]?></td>
            <td height="25"><a href="#" onClick="newWindow('../library/detail_siswa.php?replid=<?=$row_mutasi[6]?>', 'DetailSiswa','660','657','resizable=0,scrollbars=1,status=0,toolbar=0')"><img src="../images/ico/lihat.png" border="0" width="16" height="16" onMouseOver="showhint('Lihat Detail Siswa', this, event, '120px')"></a>
            <?php if ($from_left!=1) { ?>
            <a href="#" onclick="newWindow('ubah_mutasi_siswa.php?tampil=tampil&nis=<?=$row_mutasi[0]?>&departemen=<?=$departemen?>&tahun=<?=$tahun?>&pop=1','UbahMutasiSiswa',488,406,'resizable=0,scrollbars=0,status=0,toolbar=0')"><img src="../images/ico/ubah.png" border="0" width="16" height="16" onMouseOver="showhint('Ubah Status Mutasi', this, event, '120px')"></a>
            <?php } else { ?>
             <a href="ubah_mutasi_siswa.php?tampil=tampil&nis=<?=$row_mutasi[0]?>&departemen=<?=$departemen?>&tahun=<?=$tahun?>"><img src="../images/ico/ubah.png" border="0" width="16" height="16" onMouseOver="showhint('Ubah Status Mutasi', this, event, '120px')"></a>
             <?php } ?>
             &nbsp;
             <?php if ($from_left!=1){ ?><img src="../images/ico/refresh.png" width="16" height="16" onClick="aktifkan('<?=$row_mutasi[0]?>')"  onMouseOver="showhint('Aktifkan kembali', this, event, '120px')"> &nbsp;<img src="../images/ico/hapus.png" width="16" height="16" onClick="hapus('<?=$row_mutasi[0]?>')"  onMouseOver="showhint('Hapus Siswa', this, event, '120px')">
             <?php } else { ?>
             <img src="../images/ico/refresh.png" width="16" height="16" onClick="aktifkan2('<?=$row_mutasi[0]?>')"  onMouseOver="showhint('Aktifkan kembali', this, event, '120px')"> &nbsp;<img src="../images/ico/hapus.png" width="16" height="16" onClick="hapus2('<?=$row_mutasi[0]?>')"  onMouseOver="showhint('Hapus Siswa', this, event, '120px')">
             <?php } ?></td>
          </tr>
		  <?php
		  }
		  if(mysqli_num_rows($result_mutasi)==0)
		  	{
		?>
		<tr>
			<td height="25" colspan="8" align="center"> <em>Data Belum Ada Siswa yang sudah dimutasi</em></td>
		</tr>	
		<?php 
			}
		CloseDb();
		  ?>
          
        </table>  
		
		<script language="javascript">
		Tables('table', 1, 0);
	</script>	</td>
      </tr>
    </table></td>
  </tr>
  <tr>
			<td height="25" colspan="8" align="center"></td>
	</tr>
  <tr>
    <td height="25" colspan="8" align="center"></td>
  </tr>	
</table>
</body>
</html>