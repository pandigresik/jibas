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
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/sessioninfo.php');
OpenDb();
if (isset($_REQUEST['ac'])){
	if ($_REQUEST['ac']=="nd7bw6g25gdf"){
		$sql = "DELETE FROM $db_name_sms.kritiksaran WHERE replid='".$_REQUEST['id']."'";
		QueryDb($sql);
	}
}
$Year  = $_REQUEST['Year'] ?? date('Y');

$Month = $_REQUEST['Month'] ?? date('N');

$Type  = $_REQUEST['Type'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function hapus(id){
	var Y	= document.getElementById('Year').value;
	var M	= document.getElementById('Month').value;
	var T	= document.getElementById('Type').value; 
	if (confirm('Anda yakin akan menghapus kritik/saran ini?'))
		document.location.href = "kritik.php?ac=nd7bw6g25gdf&id="+id+"&Year="+Y+"&Month="+M+"&Type="+T;
}
function ChgCmb(){
	var Y	= document.getElementById('Year').value;
	var M	= document.getElementById('Month').value;
	var T	= document.getElementById('Type').value;
	document.location.href = "kritik.php?Year="+Y+"&Month="+M+"&Type="+T;
}
</script>
</head>

<body>
<div align="right">
<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
    <span class="news_title2">Daftar Kritik & Saran </span>
</div><br />
<div align="left" style="padding-bottom:10px">
    <table border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td style="padding-right:4px">Bulan</td>
        <td style="padding-right:4px">
        <select id="Month" class="cmbfrm" onchange="ChgCmb()">
            <?php
            for ($i=1; $i<=12; $i++){
                if ($Month=='')
                    $Month = date('m');
                ?>
                <option value="<?=$i?>" <?=StringIsSelected($i,$Month)?>><?=NamaBulan($i)?></option>
                <?php
            }
            ?>
        </select>        </td>
        <td style="padding-right:2px">
        <select id="Year" class="cmbfrm" onchange="ChgCmb()">
            <?php
            for ($i=$G_START_YEAR; $i<=date('Y'); $i++){
                if ($Year=='')
                    $Year = date('Y');
                ?>
                <option value="<?=$i?>" <?=StringIsSelected($i,$Year)?>><?=$i?></option>
                <?php
            }
            ?>
        </select>        </td>
      </tr>
      <tr>
        <td style="padding-right:4px">Jenis</td>
        <td colspan="2" style="padding-right:4px"><span style="padding-right:2px">
          <select name="Type" class="cmbfrm" id="Type" onchange="ChgCmb()">
            <?php
        if ($Type=="")
            $Type="kritik";
        ?>
            <option value="kritik" <?=StringIsSelected($Type,'kritik')?>>Kritik</option>
            <option value="saran" <?=StringIsSelected($Type,'saran')?>>Saran</option>
            <option value="pesan" <?=StringIsSelected($Type,'pesan')?>>Pesan</option>
          </select>
        </span></td>
      </tr>
    </table>
</div>
<table width="100%" border="1" class="tab">
  <tr>
    <td width="7%" height="25" align="center" class="header">No.</td>
    <td width="12%" height="25" align="center" class="header">No Pengirim</td>
    <td width="14%" align="center" class="header">Tanggal</td>
    <td width="44%" align="center" class="header"><?=ucfirst((string) $Type) ?></td>
    <!--td height="25" align="center" class="header">Status</td-->
    <td width="8%" height="25" align="center" class="header">&nbsp;</td>
  </tr>
  <?php
  $sql = "SELECT replid,sender,`from`,DATE_FORMAT(senddate,'%e %M %Y %H:%i:%s'),message,`type` FROM $db_name_sms.kritiksaran WHERE YEAR(senddate)='$Year' AND MONTH(senddate)='$Month' AND `type`='$Type'  ORDER BY replid DESC";
  $result = QueryDb($sql);
  $num = @mysqli_num_rows($result);
  if ($num>0){
  $cnt=1;
  while ($row = @mysqli_fetch_row($result)){
	$nohp  = str_replace("+62","",(string) $row[1]);	
	$sqlph = "SELECT nama FROM $db_name_sms.phonebook WHERE nohp LIKE '%$nohp'";
	$resph = QueryDb($sqlph);
	$rowph = @mysqli_fetch_row($resph);
	$nama  = $rowph[0];
  ?>
  <tr>
    <td align="center"><?=$cnt?></td>
    <td align="center"><?="($row[1]) $nama"?></td>
    <td align="center"><?=$row[3]?></td>
    <td align="left"><?=$row[4]?></td>
    <!--td align="center">
    	<?php if (is_admin()){ ?>
			<?php if ($row[2]==1){ ?>
            <a href="javascript:setaktif('<?=$row[0]?>','0')"><img src="../img/aktif.png" width="16" height="16" border="0" /></a>
            <?php } else { ?>
            <a href="javascript:setaktif('<?=$row[0]?>','1')"><img src="../img/nonaktif.png" alt="" width="16" height="16" border="0" /></a>
            <?php } ?>
        <?php } else { ?>
        	<?php if ($row[2]==1){ ?>
            <img src="../img/aktif.png" width="16" height="16" border="0" />
            <?php } else { ?>
            <img src="../img/nonaktif.png" alt="" width="16" height="16" border="0" />
            <?php } ?>
        <?php } ?>        
    </td-->
    <td align="center"><?php if (is_admin()){ ?><a href="javascript:hapus('<?=$row[0]?>')"><img src="../img/hapus.png" width="16" height="16" border="0" /></a><?php } ?></td>
  </tr>
  <?php
  $cnt++;
  }
  } else { 
  ?>
  <tr>
    <td colspan="7" align="center" class="nodata">Tidak ada data</td>
  </tr>
  <?php
  }
  ?>
</table>

</body>
</html>