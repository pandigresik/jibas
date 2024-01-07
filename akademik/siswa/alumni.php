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
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../library/departemen.php');
require_once('../cek.php');

OpenDb();
$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

$varbaris=20;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];
	
$tahun = "";
if (isset($_REQUEST['tahun']))
	$tahun = $_REQUEST['tahun'];
	
$urut = "s.nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql="SELECT a.tktakhir, a.klsakhir, a.nis, k.idtahunajaran FROM alumni a, kelas k WHERE a.replid='".$_REQUEST['replid']."' AND a.klsakhir = k.replid AND k.idtingkat=a.tktakhir";
	$result=QueryDb($sql);
	$row = mysqli_fetch_array($result);
	$nis = $row['nis'];
	$idtingkat = $row['tktakhir'];
	$idkelas = $row['klsakhir'];
	$idtahunajaran = $row['idtahunajaran'];
	
	BeginTrans();
	$success=0;
	
	$sql1="UPDATE jbsakad.riwayatkelassiswa SET aktif=1 WHERE nis='$nis' AND idkelas = '".$idkelas."'";
	$result1=QueryDbTrans($sql1, $success);
	
	if ($success){
		$sql1="UPDATE jbsakad.riwayatdeptsiswa SET aktif=1 WHERE nis='$nis' AND departemen='$departemen'";
		$result1=QueryDbTrans($sql1, $success);
	}
	
	if ($success){
		$sql1="UPDATE jbsakad.siswa SET aktif=1,alumni=0 WHERE nis='$nis'";
		$result=QueryDbTrans($sql1, $success);
	}
	
	if ($success){
		$sql1="DELETE FROM jbsakad.alumni WHERE replid='".$_REQUEST['replid']."'";
		$result1=QueryDbTrans($sql1, $success);
	}
	
	if ($success)
		CommitTrans();
	else
		RollbackTrans();
	
	CloseDb();
	$page=0;
	$hal=0;
}
OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function tambah() {
	var departemen = document.getElementById('departemen').value;
	document.location.href="alumni_main.php?departemen="+departemen;
}

function hapus(replid){
	var departemen = document.getElementById('departemen').value;
	var tahun = document.getElementById('tahun').value;
	
	if (confirm("Apakah anda yakin akan mengembalikan siswa ini ke Departemen, Tingkat dan Kelas sebelumnya?"))
		document.location.href = "alumni.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen+"&tahun="+tahun+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function cetak() {
	var departemen=document.getElementById("departemen").value;
	var tahun=document.getElementById("tahun").value;
	var total=document.getElementById("total").value;
	newWindow('alumni_cetak.php?departemen='+departemen+'&tahun='+tahun+'&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakAlumni','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function change_dep() {
	var departemen=document.getElementById("departemen").value;
	document.location.href = "alumni.php?departemen="+departemen;
}

function refresh() {
	var departemen=document.getElementById("departemen").value;
	var tahun=document.getElementById("tahun").value;
	document.location.href = "alumni.php?tahun="+tahun+"&departemen="+departemen;
}

function change_urut(urut,urutan){
	var departemen = document.getElementById('departemen').value;
	var tahun = document.getElementById('tahun').value;
	
	if (urutan =="ASC")
		urutan="DESC";
	else
		urutan="ASC";
		
	document.location.href="alumni.php?departemen="+departemen+"&tahun="+tahun+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function change_page(page) {
	var departemen = document.getElementById('departemen').value;
	var tahun = document.getElementById('tahun').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "alumni.php?departemen="+departemen+"&tahun="+tahun+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}

function change_hal() {
	var departemen=document.getElementById("departemen").value;
	var tahun=document.getElementById("tahun").value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="alumni.php?departemen="+departemen+"&tahun="+tahun+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var departemen=document.getElementById("departemen").value;
	var tahun=document.getElementById("tahun").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href= "alumni.php?departemen="+departemen+"&tahun="+tahun+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

</script>
</head>
<body onload="document.getElementById('departemen').focus()">
<table border="0" width="100%" height="100%">
<tr><td align="center" valign="top" background="../images/ico/b_alumni.png" style="margin:0;padding:0;background-repeat:no-repeat;">
<table border="0" width="100%" align="center">
<tr>
  <td align="left" valign="top">
	<table border="0"width="95%" align="center">
    <tr>
        <td align="right"> <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Daftar Alumni</font>
        </td>
   	</tr>
    <tr>
        <td align="right"><a href="../kelulusan.php" target="content"> 
        <font size="1" color="#000000"><b>Kenaikan & Kelulusan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Daftar Alumni</b></font></td>
    </tr>
    <tr>
      <td align="left">&nbsp;</td>
      </tr>
	</table><br />
    
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <!-- TABLE LINK -->
    <tr>
    	<td width="15%" rowspan="2">&nbsp;</td>
        <td width="12%"><strong>Departemen&nbsp;</strong></td>
        <td width="20%">
        	<select name="departemen" id="departemen" onChange="change_dep()" style="width:155px" onKeyPress="return focusNext('tahun', event)">
			<?php
              $dep = getDepartemen(SI_USER_ACCESS());    
              foreach($dep as $value) {
                    if ($departemen == "")
                        $departemen = $value; ?>
                <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
                <?=$value ?> 
                </option>
            <?php } ?>
            </select>
       </td>
	</tr>
    <tr>
    	<td><strong>Tahun Lulus &nbsp;</strong></td>
    	<td>
        <select name="tahun" id="tahun" onChange="refresh()" style="width:155px">
         
		<?php  OpenDb();
            $sql="SELECT YEAR(tgllulus) AS tahun FROM alumni WHERE departemen='$departemen' GROUP BY tahun ORDER BY tahun DESC";
            $result=QueryDb($sql);
  			$jum_tahun = mysqli_num_rows($result);         
            while ($row=@mysqli_fetch_array($result)){
				if ($tahun=="")
					$tahun=$row['tahun'];
        ?>
            <option value="<?=$row['tahun']?>" <?=IntIsSelected($row['tahun'], $tahun) ?>><?=$row['tahun']?>
            </option>
        <?php  
            }
            CloseDb();
    	?>
    	</select>
    	</td>
<?php
OpenDb();
//echo "Jumtahun".$jum_tahun;
if ($jum_tahun > 0){

	$sql_tot = "SELECT s.replid, s.nis, s.nama, k.kelas, al.tgllulus, al.klsakhir, al.tktakhir, al.replid, t.tingkat, a.angkatan FROM alumni al, kelas k, tingkat t, siswa s, angkatan a WHERE k.idtingkat=t.replid AND t.replid=al.tktakhir AND k.replid=al.klsakhir AND YEAR(al.tgllulus) = '$tahun' AND al.departemen = '$departemen' AND s.nis = al.nis AND s.idangkatan = a.replid";
	//echo $sql_tot;
	$result_tot = QueryDb($sql_tot);
	$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	
	$sql_siswa = "SELECT s.replid AS replidsiswa, s.nis, s.nama, k.kelas, al.tgllulus, al.klsakhir, al.tktakhir, al.replid, t.tingkat, a.angkatan FROM alumni al, kelas k, tingkat t, siswa s, angkatan a WHERE k.idtingkat=t.replid AND t.replid=al.tktakhir AND k.replid=al.klsakhir AND YEAR(al.tgllulus) = '$tahun' AND al.departemen = '$departemen' AND s.nis = al.nis AND s.idangkatan = a.replid ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	
	$result_siswa = QueryDb($sql_siswa);
	
	if (@mysqli_num_rows($result_siswa) > 0) {
	?> 
    	<input type="hidden" name="total" id="total" value="<?=$total?>"/>
    	<td align="right">
      	<a href="#" onClick="refresh()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    	<a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')" />&nbsp;Cetak</a>&nbsp;&nbsp;
	<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
	    <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')" />&nbsp;Tambah Alumnus</a>
	<?php  } ?></td>
    </tr>
    </table>
    <br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
    <tr height="30" class="header" align="center">
    	<td width="4%" >No</td>
        <td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nis','<?=$urutan?>')">N I S <?=change_urut('s.nis',$urut,$urutan)?></td>
        <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nama','<?=$urutan?>')">Nama <?=change_urut('s.nama',$urut,$urutan)?></td>
        <td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('a.angkatan','<?=$urutan?>')"> Angkatan <?=change_urut('a.angkatan',$urut,$urutan)?></td>
        <!--<td width="15%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('t.tingkat','<?=$urutan?>')"> Tingkat Terakhir <?=change_urut('t.tingkat',$urut,$urutan)?></td>-->
        <td width="15%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('t.tingkat, k.kelas','<?=$urutan?>')">Kelas Terakhir <?=change_urut('t.tingkat, k.kelas',$urut,$urutan)?></td>
		<td width="15%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('al.tgllulus','<?=$urutan?>')">Tanggal Lulus <?=change_urut('al.tgllulus',$urut,$urutan)?></td>
        <td width="10%">&nbsp;</td>
    </tr>
	<?php 	
		if ($page==0)
			$cnt = 0;
		else
			$cnt = (int)$page*(int)$varbaris;
		
		while ($row = mysqli_fetch_array($result_siswa)) { ?>
    <tr height="25">
    	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['nis'] ?></td>
        <td><?=$row['nama'] ?></td>
        <td align="center"><?=$row['angkatan']; ?></td>
        <!--<td align="center"><?=$row['tingkat']; ?></td>-->
        <td align="center"><?=$row['tingkat']; ?> - <?=$row['kelas']; ?></td>
		<td align="center"><?=LongDateFormat($row['tgllulus']); ?></td>
        <td align="center"><a href="#" onClick="newWindow('../library/detail_siswa.php?replid=<?=$row['replidsiswa']?>', 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="../images/ico/lihat.png" width="16" height="16" border="0" onMouseOver="showhint('Detail Data Alumnus!', this, event, '80px')"/></a>&nbsp;
<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?> 
			<a href="#" onClick="newWindow('siswa_cetak_detail.php?replid=<?=$row['replidsiswa']?>', 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak Detail Data Alumnus!', this, event, '100px')"/></a>&nbsp;
            <a href="JavaScript:hapus('<?=$row['replid'] ?>')"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Batalkan sebagai Alumnus!', this, event, '75px')"/></a>
<?php 	} ?>        
		</td>
  	</tr>
<?php 	} CloseDb(); ?>
   
    <!-- END TABLE CONTENT -->
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>

	<?php if ($page==0){ 
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page<$total && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page==$total-1 && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:hidden;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:hidden;'";
		}
	?>
    </td>
</tr> 
<tr>
    <td>
    <table border="0"width="95%" align="center"cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left">Halaman
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
	  	dari <?=$total?> halaman
		
		<?php 
     // Navigasi halaman berikutnya dan sebelumnya
        ?>
        </td>
    	<!--td align="center">
    <input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
		<?php
		/*for($a=0;$a<$total;$a++){
			if ($page==$a){
				echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
			}
				 
	    }*/
		?>
	     <input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
 		</td-->
        <td width="30%" align="right">Jumlah baris per halaman
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>

<?php } 
	} else { ?>
<td width = "65%"></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td width="16%"></td>
	<td><hr style="border-style:dotted" color="#000000" /></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Belum ada data Alumni pada departemen <?=$departemen?>.
        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        <?php } ?>
        </b></font>
	</td>
</tr>
</table>  
<?php } ?>

</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    

</body>
</html>
<script language="javascript">
	var spryselect = new Spry.Widget.ValidationSelect("departemen");
	var spryselect = new Spry.Widget.ValidationSelect("tahun");
</script>