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
require_once('../script/as-diagrams.php');
require_once('../cek.php');

if(isset($_REQUEST["kelas"]))
	$kelas = $_REQUEST["kelas"];
if(isset($_REQUEST["rpp"]))
	$rpp = $_REQUEST["rpp"];

$ujian = "";
if(isset($_REQUEST["ujian"]))
	$ujian = $_REQUEST["ujian"];

OpenDb();
$sql="SELECT r.rpp, p.nama, r.idpelajaran, r.idsemester FROM pelajaran p, rpp r WHERE p.replid=r.idpelajaran AND r.replid='$rpp'";
$result=QueryDb($sql);
$row = mysqli_fetch_array($result);
$materi = $row['rpp'];
$namapel = $row['nama'];
$pelajaran = $row['idpelajaran'];
$semester = $row['idsemester'];

//echo $sql_ujian;		 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript">
function chg(){
	var rpp=document.getElementById('rpp').value;
	var ujian=document.getElementById('ujian').value;
	document.location.href="ujian_rpp_siswa_content.php?kelas=<?=$kelas?>&rpp=<?=$rpp?>&ujian="+ujian;
}

function cetak(){
	var rpp=document.getElementById('rpp').value;
	var ujian=document.getElementById('ujian').value;
	newWindow('ujian_rpp_siswa_cetak.php?kelas=<?=$kelas?>&rpp='+rpp+'&ujian='+ujian,'CetakNilai',665,592,'resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
<title></title>
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('ujian').focus()">
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>" />
<input type="hidden" name="rpp" id="rpp" value="<?=$rpp?>" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
	<table width="100%" border="0" height="100%">
  	<tr>
    	<td colspan="2">
        <table width="100%" border="0">
        <!--<tr>
            <td width="17%"><strong>Pelajaran</strong></td>
            <td><strong>: <?=$namapel ?> </strong></td>
            <td rowspan="2"></td>
        </tr>
        <tr>
            <td><strong>RPP</strong></td>
            <td><strong>: <?=$materi?></strong></td>            
        </tr>-->
        <tr>
        	<td width="17%"><strong>Jenis Pengujian</strong></td>
            <td> 
           
				<select name="ujian" id="ujian" onchange="chg()"  style="width:160px">
			<?php
				//$sql_ujian = "SELECT DISTINCT j.replid, j.jenisujian FROM jbsakad.jenisujian j, jbsakad.ujian u, jbsakad.kelas k, jbsakad.tingkat t WHERE u.idjenis=j.replid AND u.idrpp = $rpp AND u.idkelas = $kelas ORDER BY jenisujian";
				$sql_ujian = "SELECT * FROM jenisujian WHERE idpelajaran = '$pelajaran' ORDER BY jenisujian";
				$result_ujian=QueryDb($sql_ujian);
				$jum = mysqli_num_rows($result_ujian);	
				while ($row_ujian=@mysqli_fetch_array($result_ujian)){
					if ($ujian=="")
						$ujian=$row_ujian['replid'];
			?>
				<option value="<?=$row_ujian['replid']?>" <?=IntIsSelected($row_ujian['replid'],$ujian)?>>
				<?=$row_ujian['jenisujian']?></option>
			<?php }	?>
				</select>    
        	</td>
		<?php 
		//$sql1 = "SELECT s.nis, round(SUM(nilaiujian)/(COUNT(DISTINCT u.replid)),2) FROM nilaiujian n, siswa s, ujian u, jenisujian j WHERE n.idujian = u.replid AND u.idsemester = $semester AND u.idkelas = $kelas AND u.idjenis = $ujian AND u.idrpp = $rpp AND u.idpelajaran = $pelajaran AND s.nis = n.nis AND u.idjenis = j.replid AND s.idkelas = $kelas AND s.aktif = 1 GROUP BY s.nis ORDER BY s.nama";
		$sql1 = "SELECT s.nis, round(SUM(nilaiujian)/(COUNT(DISTINCT u.replid)),2), s.nama FROM nilaiujian n, siswa s, ujian u, jenisujian j WHERE n.idujian = u.replid AND u.idsemester = '$semester' AND u.idkelas = '$kelas' AND u.idjenis = '$ujian' AND u.idrpp = '$rpp' AND u.idpelajaran = '$pelajaran' AND s.nis = n.nis AND u.idjenis = j.replid AND s.idkelas = $kelas AND s.aktif = 1 GROUP BY s.nis UNION SELECT s.nis, 0, s.nama FROM siswa s WHERE s.idkelas = $kelas AND s.aktif = 1 AND s.nis NOT IN (SELECT s.nis FROM nilaiujian n, siswa s, ujian u, jenisujian j WHERE n.idujian = u.replid AND u.idsemester = '$semester' AND u.idkelas = '$kelas' AND u.idjenis = '$ujian' AND u.idrpp = '$rpp' AND u.idpelajaran = '$pelajaran' AND s.nis = n.nis AND u.idjenis = j.replid AND s.idkelas = '$kelas' AND s.aktif = 1 GROUP BY s.nis) ORDER BY nama";
		$result1 = QueryDb($sql1);		
			
		if ($jum > 0 && mysqli_num_rows($result1)){ ?>
            <td align="right">
            <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onmouseover="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    		<a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onmouseover="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
            </td>
		</tr>
        </table>        
        
        
        <br />
  		</td>
 	</tr> 
    <tr>
    	<td align="center">
 
<?php      
		//echo $sql1;
		$data_title = "<font size='4'>Rata-rata RPP Setiap Siswa</font>"; // title for the diagram

        // sample data array
        //$data = array();
		$cnt =0;
        while($row1 = mysqli_fetch_row($result1)) {
            //$data[] = array($row1[1],$row1[2],$row1[3],$row1[4],$row1[5]);			
            //$legend_x[] = $row1[0];			
			$legend_x[] = ++$cnt;
			$data[] = [$row1[1]];
			//$data[] = $row1[1];
        }
		
		$legend_y = ['Rata'];
		//$legend_y = 'Rata';
				
        $graph = new CAsBarDiagram;
        $graph->bwidth = 10; // set one bar width, pixels
        //$graph->bt_total = 'Total'; // 'totals' column title, if other than 'Totals'
        $graph->showtotals = 0;  // uncomment it if You don't need 'totals' column
        
		$graph->precision = 1;  // decimal precision
        // call drawing function
        $graph->DiagramBar($legend_x, $legend_y, $data, $data_title);
		//$graph->DiagramBar('X-A', 'rata2', '47.47', $data_title);
		?>
        <!--<img src="statistik_batang.php?semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&ujian=<?=$ujian?>&rpp=<?=$rpp?>" />-->
        
	
        </td>
	</tr>
    <tr>
        <td>
        <table width="100%" border="1" class="tab" id="table" bordercolor="#000000">
      	<tr align="center">
        	<td height="30" class="header">No</td>
        	<td height="30" class="header">N I S</td>
            <td height="30" class="header">Nama</td>
        	<td height="30" class="header">Rata-rata RPP</td>
      	</tr>
	<?php 	
      	$sql="SELECT nis, nama FROM siswa WHERE idkelas = '$kelas' AND aktif = 1 ORDER BY nama";
		
		//$sql = "SELECT s.nis, s.nama, round(SUM(nilaiujian)/(COUNT(DISTINCT u.replid)),2) as rata FROM nilaiujian n, siswa s, ujian u, jenisujian j WHERE n.idujian = u.replid AND u.idsemester = $semester AND u.idkelas = $kelas AND u.idjenis = $ujian AND u.idrpp = $rpp AND u.idpelajaran = $pelajaran AND s.nis = n.nis AND u.idjenis = j.replid AND s.idkelas = $kelas AND s.aktif = 1 GROUP BY s.nis ORDER BY s.nama";
		 
      	$result=QueryDb($sql);
      	$cnt=0;
      	while ($row=@mysqli_fetch_array($result)){			
			$sql2 = "SELECT round(SUM(nilaiujian)/(COUNT(DISTINCT u.replid)),2), SUM(nilaiujian) FROM nilaiujian n, siswa s, ujian u, jenisujian j WHERE n.idujian = u.replid AND u.idsemester = = '".$semester."' AND u.idkelas = '$kelas' AND u.idjenis = '$ujian' AND u.idrpp = '$rpp' AND u.idpelajaran = '$pelajaran' AND s.nis = n.nis AND u.idjenis = j.replid AND s.nis = '".$row['nis']."' AND s.aktif = 1 ORDER BY s.nama";
			//echo $sql2;
			$result2 = QueryDb($sql2);
			$row2 = mysqli_fetch_row($result2);
    ?>
      	<tr>
        	<td height="25" align="center"><?=++$cnt?></td>
        	<td height="25"><div align="center"><?=$row['nis']?></div></td>
        	<td height="25"><?=$row['nama']?></td>
            <td height="25" align="center"><?php
            	if ($row2[1] > 0) 
					echo $row2[0];
				else 
					echo '0';		
				//echo $row['rata'];
					?></td>
      	</tr>
<?php 	}  ?>
        </table>
        <script language='JavaScript'>
            Tables('table', 1, 0);
        </script>
        </td>
   	</tr>
    </table>
<?php } else { ?>
		<td width = "50%"></td>
    </tr>
    </table>
    <!--<table width="100%" border="0" align="center">          
    <tr>
        <td colspan="2"><hr style="border-style:dotted" color="#000000"/></td>
    </tr>
    </table>-->
    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="250">
        
            <font color ="red" size="2"><b>Tidak ditemukan adanya data. <br /><br />Tambah data nilai ujian siswa untuk RPP <?=$materi?> dan mata pelajaran <?=$namapel?><br />di menu Penilaian Pelajaran pada bagian Penilaian.
    		</b></font> 
    	</td>
    </tr>
    </table>
<?php } ?>
    
	</td>
</tr>
</table>
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("ujian");
</script>