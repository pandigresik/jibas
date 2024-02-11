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
require_once("../inc/session.checker.php");
require_once("../inc/config.php");
require_once("../inc/db_functions.php");
require_once("../inc/common.php");
$perpustakaan='alls';
if (isset($_REQUEST['perpustakaan']))
	$perpustakaan=$_REQUEST['perpustakaan'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../scr/tools.js"></script>

<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function ChgPerpus(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	document.location.href = "Header.php?perpustakaan="+perpustakaan;
}
function AddLogo(p,op){
	newWindow('AddLogo.php?perpustakaan='+p+'&op='+op, 'TambahLogo','400','269','resizable=0,scrollbars=0,status=0,toolbar=0')
}
function AddInfo(p,op){
	newWindow('AddInfo.php?perpustakaan='+p+'&op='+op, 'TambahInfo','278','298','resizable=0,scrollbars=0,status=0,toolbar=0')
}
function Fresh(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	document.location.href = "Header.php?perpustakaan="+perpustakaan;
}
function Cetak(){
	var p = document.getElementById('perpustakaan').value;
	newWindow('Header.Cetak.php?perpustakaan='+p, 'CetakHeader','789','402','resizable=0,scrollbars=0,status=0,toolbar=0')
}
</script>
</head>

<body>
<div id="title" align="right">
	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
	<font style="font-size:18px; color:#999999">Header Cetak</font><br />
</div>
<br>
<table width="700" align="center" border="0">
  <tr>
    <td width="567">&nbsp;&nbsp;<strong>Perpustakaan :</strong> 
	<?php
    OpenDb();
    $sql 	= "SELECT * FROM perpustakaan ORDER BY nama";
    $result = QueryDb($sql);
    ?>
    <select name="perpustakaan" class="cmbfrm" id="perpustakaan"  onchange="ChgPerpus()">
        <option value="alls" <?=StringIsSelected('alls',$perpustakaan) ?> ><i>Semua</i></option>
        <?php
        while ($row = @mysqli_fetch_array($result)){
        ?>
        <option value="<?=$row['replid']?>" <?=StringIsSelected($row['replid'],$perpustakaan) ?> ><?=$row['nama']?></option>
        <?php
        }
        ?>
    </select>	</td>
    <td width="123" align="right"><a href="#" onclick="Cetak()"><img src="../img/ico/print1.png" width="16" height="16" border="0" />&nbsp;Cetak&nbsp;Header</a></td>
  </tr>
  <tr>
    <td colspan="2">
    <table width="100%" border="1" class="tab" align="center">
      <tr>
        <td width="200" height="30" align="center" class="header">Logo</td>
        <td height="30" align="center" class="header">Keterangan</td>
      </tr>
      <tr>
        <td width="200" align="center">
        <?php
        $sql	= "SELECT * FROM ".$db_name_umum.".identitas WHERE status=1 AND perpustakaan='$perpustakaan'";
        $result = QueryDb($sql);
        $num	= @mysqli_num_rows($result);
		$row	= @mysqli_fetch_array($result);
		
        if ($num==0){
			$sql3 = "INSERT INTO ".$db_name_umum.".identitas SET status=1, perpustakaan='$perpustakaan', departemen='P_".$perpustakaan."'"; 
			QueryDb($sql3);
		}
		if (strlen((string) $row['foto'])==0){
			if (strlen((string) $row['foto'])==0 && $perpustakaan=='alls'){
				echo "<div align='center' style='padding-top:20px'>Belum ada logo untuk semua perpustakaan</div>";
			} elseif (strlen((string) $row['foto'])==0 && $perpustakaan!='alls'){
				$sql2 	= "SELECT nama FROM perpustakaan WHERE replid='$perpustakaan'";
				$result2= QueryDb($sql2);
				$row2	= @mysqli_fetch_array($result2);
				echo "<div align='center' style='padding-top:20px'>Belum ada logo untuk perpustakaan ".$row2['nama']."</div>";
			}
			echo "<div align='center' style='padding-top:20px'><a href=\"javascript:AddLogo('".$perpustakaan."','Add')\"><img src='../img/ico/tambah.png' border='0' />Tambah</a></div>";
		} else {
			?>
            <img src="../lib/gambar.php?replid=<?=$row['replid']?>&table=<?=$db_name_umum.".identitas"?>&field=foto">
            <?="<br><a href=\"javascript:AddLogo('".$perpustakaan."','Edit')\"><img src='../img/ico/ubah.png' border='0' />Ubah</a>";
		}
        ?>        </td>
        <td>
        <?php
		if (strlen((string) $row['nama'])==0){
			if (strlen((string) $row['nama'])==0 && $perpustakaan=='alls'){
				echo "<div align='center' style='padding-top:20px'>Belum ada logo untuk semua perpustakaan</div>";
			} elseif (strlen((string) $row['nama'])==0 && $perpustakaan!='alls'){
				$sql2 	= "SELECT nama FROM perpustakaan WHERE replid='$perpustakaan'";
				$result2= QueryDb($sql2);
				$row2	= @mysqli_fetch_array($result2);
				echo "<div align='center' style='padding-top:20px'>Belum ada informasi untuk perpustakaan ".$row2['nama']."</div>";
			}
			echo "<div align='center' style='padding-top:20px'><a href=\"javascript:AddInfo('".$perpustakaan."','Add')\"><img src='../img/ico/tambah.png' border='0' />Tambah</a></div>";
		} else {
			?>
            <span style="font-family:Arial; font-size:22px; font-weight:bold; color:#000000">
				<?=$row['nama']?>
            </span>
            <br />
            <strong>
			<?=$row['ALAMAT1']?>
            <?php
			if ($row['TELP1']!='' || $row['TELP2']!=''){
				echo " <br>Telp : ";
				if ($row['TELP1']!='' && $row['TELP2']=='')
					echo $row['TELP1'];
				elseif ($row['TELP2']!='' && $row['TELP1']=='')
					echo $row['TELP2'];
				elseif ($row['TELP2']!='' && $row['TELP1']!='')
					echo $row['TELP1']." , ".$row['TELP2'];		
			}
			if ($row['TELP1']!='' || $row['TELP2']!=''){
				if ($row['FAX1']!='')
					echo " Fax : ".$row['FAX1'];
			} else {
				if ($row['FAX1']!='')
					echo " Fax : ".$row['FAX1'];
			}
			
			?>
            <br />
            <?php
			if ($row['situs']!='' || $row['email']!=''){
				if ($row['situs']!='' && $row['email']=='')
					echo "Website : ".$row['situs'];
				elseif ($row['email']!='' && $row['situs']=='')
					echo "Email : ".$row['email'];
				elseif ($row['email']!='' && $row['situs']!='')
					echo "Website : ".$row['situs']." Email : ".$row['email'];		
			}
			echo "</strong>";
            echo "<div align='center' style='padding-top:20px'><a href=\"javascript:AddInfo('".$perpustakaan."','Edit')\"><img src='../img/ico/ubah.png' border='0' />Ubah</a></div>";
		}
        ?>        </td>
      </tr>
    </table>    </td>
  </tr>
</table>
</body>
</html>