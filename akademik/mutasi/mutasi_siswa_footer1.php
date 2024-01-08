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
require_once('../library/departemen.php');
$departemen=$_REQUEST['departemen'];
$mode=$_REQUEST['mode'];
$nis=$_REQUEST['nis'];
$kelas=$_REQUEST['kelas'];
$nama=$_REQUEST['nama'];
if ($mode=="text"){
if ($nis=="" && $nama<>"")
	$tambahan="AND s.nama LIKE '%$nama%'";
if ($nis<>"" && $nama=="")
	$tambahan="AND s.nis LIKE '%$nis%'";
if ($nis<>"" && $nama<>"")
	$tambahan="AND s.nis LIKE '%$nis%' OR s.nama LIKE '%$nama%'";
//echo $tambahan;
}
if ($mode=="kelas"){
if ($kelas<>"")
	$tambahan="AND s.idkelas='$kelas'";
//echo $tambahan;
}
OpenDb();
$query_mutasi="SELECT s.nis,s.nama,a.angkatan,k.kelas,s.statusmutasi FROM jbsakad.siswa s, jbsakad.angkatan a, jbsakad.kelas k WHERE s.idangkatan=a.replid AND s.aktif=1 AND k.replid=s.idkelas AND a.departemen='$departemen' $tambahan ORDER BY s.nis";
//echo $query_mutasi;
$result_mutasi=QueryDb($query_mutasi);
/*
if (isset($_REQUEST['op']))
$op=$_REQUEST['op'];
if ($op=="gu7jkds894h98uj32uhi9d8"){
	$sql_hapus="DELETE FROM jbsakad.jenismutasi WHERE replid='".$_REQUEST['replid']."'";
	$result_hapus=QueryDb($sql_hapus);
	if ($result_hapus){
		
		<SCRIPT type="text/javascript" language="javascript">
		document.location.href="jenis_mutasi_siswa.php";	
		</script>
		
	} else {
		
		<SCRIPT type="text/javascript" language="javascript">
		alert ('Gagal menghapus data !');
		document.location.href="jenis_mutasi_siswa.php";	
		</script>
		
		}
}
	*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Daftar Siswa</title>
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
	color: #666666;
	font-weight: bold;
}
.style2 {color: #000000}
-->
</style>
</head>
<script language="javascript">
function mutasi(nis){
	//alert ('NIS='+nis);
	//if (confirm('Anda yakin akan mutasikan siswa ini ?')){
		newWindow('siswa_mutasi.php?tampil=tampil&nis='+nis,'Mutasi',582,396,'');
	//}
}
function cetak(){
	//alert ('NIS='+nis);
	//if (confirm('Anda yakin akan mutasikan siswa ini ?')){
		newWindow('mutasi_cetak.php?mode=<?=$mode?>&nis=<?=$nis?>&kelas=<?=$kelas?>&nama=<?=$nama?>&departemen=<?=$departemen?>','Mutasi',795,505,'resizable=1,scrollbars=1,status=1,toolbar=0');
	//}
}
function lihat(nis){
	//alert ('NIS='+nis);
	//if (confirm('Anda yakin akan mutasikan siswa ini ?')){
		newWindow('../library/siswa_tampil.php?&nis='+nis+'&departemen=<?=$departemen?>','Mutasi',771,500,'resizable=1,scrollbars=1,status=1,toolbar=0');
	//}
}

function refresh(){
	//alert ('NIS='+nis);
	//if (confirm('Anda yakin akan mutasikan siswa ini ?')){
		//newWindow('siswa_mutasi.php?tampil=tampil&nis='+nis,'Mutasi',473,330,'');
	document.location.href="mutasi_siswa_footer.php?departemen=<?=$_REQUEST['departemen']?>&nis=<?=$_REQUEST['nis']?>&nama=<?=$_REQUEST['nama']?>&kelas=<?=$_REQUEST['kelas']?>&mode=<?=$_REQUEST['mode']?>";

	//}
}
</script>

<body topmargin="0">
<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" background="../images/ico/b_mutasi.png" style="background-repeat:no-repeat; margin:0px; padding:0px;">
  <tr>
    <td valign="top">
    <br>
    <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#666666">
      <tr>
        <td colspan="2"><div align="right">
          <input name="action" type="hidden" id="action2" value="<?php if(!empty($_GET['action'])) echo $_GET['action'] ; else echo "tambahJenisMutasi" ;?>">
          <input name="state" type="hidden" id="state2" value="jenis"><?php if (mysqli_num_rows($result_mutasi)<>0) {
		  ?>
          <a href="#" onclick="cetak()"  onMouseOver="showhint('Cetak Daftar Siswa', this, event, '120px')">Cetak<img src="../images/ico/print.png" border="0"></a><?php } ?><br>
          <br></div></td>
        </tr>
      <tr>
        <td><table width="100%" border="1" class="tab" align="center" cellpadding="0" cellspacing="0" id="table" bordercolor="#000000">
          <tr class="header">
            <td width="34" height="30"><div align="center">No</div></td>
            <td width="139" height="30"><div align="center">NIS</div></td>
            <td width="144" height="30"><div align="center">Nama</div></td>
            <td width="154" height="30"><div align="center">Angkatan</div></td>
            <td width="159" height="30"><div align="center">Kelas</div></td>
            <td width="68" height="30">&nbsp;</td>
          </tr>
		  <?php 
		
	
		  $a=0;
		  while($row_mutasi=mysqli_fetch_row($result_mutasi)){$a++;
		  ?>
          <tr>
            <td height="25"><?=$a; ?></td>
            <td height="25"><?=$row_mutasi[0]?></td>
            <td height="25"><?=$row_mutasi[1]?></td>
            <td height="25"><?=$row_mutasi[2]?></td>
            <td height="25"><?=$row_mutasi[3]?></td>
            <td height="25"><img src="../images/ico/lihat.png" width="16" height="16" onclick="lihat('<?=$row_mutasi[0]?>')"  onMouseOver="showhint('Lihat Siswa', this, event, '120px')">&nbsp;
            <?php if ($row_mutasi[4]==0){ ?>
            <img src="../images/ico/mutasi.png" width="16" height="16" onclick="mutasi('<?=$row_mutasi[0]?>')"  onMouseOver="showhint('Mutasikan siswa ini', this, event, '120px')">
			<?php } else { ?>
            <img src="../images/ico/refresh.png" width="16" height="16" onclick="batalkan_mutasi('<?=$row_mutasi[0]?>')"  onMouseOver="showhint('Batalkan mutasi', this, event, '120px')">
            <?php } ?></td>
          </tr>
		  <?php
		  }
		  if(mysqli_num_rows($result_mutasi)==0)
		  	{
		?>
		<tr>
			<td height="25" colspan="6" align="center"> "Data Belum Ada"</td>
		</tr>	
		<?php 
			}
		  ?>
        </table>  
		
		<script language="javascript">
		Tables('table', 1, 0);
	</script>	</td>
      </tr>
    </table></td>
  </tr>
  <tr><td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
CloseDb();
?>