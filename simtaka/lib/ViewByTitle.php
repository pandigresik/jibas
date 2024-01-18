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
require_once('../inc/db_functions.php');
$id=$_REQUEST['id'];
$state=$_REQUEST['state'];
OpenDb();
	//State:
	//1.Perpustakaan
	//2.Format
	//3.Rak
	//4.Katalog
	//5.Penerbit
	//6.Penulis
if ($state=='1')
	$sql = "SELECT pu.replid FROM pustaka pu, daftarpustaka d WHERE d.perpustakaan='$id' AND pu.replid=d.pustaka GROUP BY d.pustaka";
elseif ($state=='2')
	$sql = "SELECT pu.replid FROM pustaka pu WHERE pu.format='$id'";
elseif ($state=='3')
	$sql = "SELECT pu.replid FROM pustaka pu, rak r, katalog k WHERE pu.katalog=k.replid AND k.rak=r.replid AND r.replid='$id'";
elseif ($state=='4')
	$sql = "SELECT pu.replid FROM pustaka pu, katalog k WHERE pu.katalog=k.replid AND k.replid='$id' ";	
elseif ($state=='5')
	$sql = "SELECT pu.replid FROM pustaka pu WHERE pu.penerbit='$id'";	
elseif ($state=='6')
	$sql = "SELECT pu.replid FROM pustaka pu WHERE pu.penulis='$id'";			
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
//echo $sql."<br>";
//echo $num;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daftar Judul</title>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../scr/tools.js"></script>
<script language="javascript">
	function hover(id,state){
		if (state=='1')
			document.getElementById('TD'+id).style.background='#f8f7b9';
		else
			document.getElementById('TD'+id).style.background='#ffffff';
	}
	function ViewDetail(id){
		var addr = '../pus/pustaka.view.detail.php?replid='+id;
		newWindow(addr, 'DetailPustaka','509','456','resizable=1,scrollbars=1,status=0,toolbar=0')
	}
</script>
</head>

<body style="margin-left:0px; margin-top:0px">
	<div id="title" align="left">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Daftar Judul</font><br />
    </div>
	<div style="padding-left:5px; padding-top:5px; padding-right:5px; padding-bottom:5px;">
		<table width="100%" border="0" cellspacing="5" cellpadding="5">
		<?php $i=1; ?>
		<?php while ($row = @mysqli_fetch_row($result)){ ?>
        <?php
			$sql2 = "SELECT pu.judul, pn.gelardepan, pn.nama, pn.gelarbelakang, pb.nama, pu.replid FROM pustaka pu, penulis pn, penerbit pb WHERE pu.replid=$row[0] AND pu.penerbit=pb.replid AND pu.penulis=pn.replid";
			//echo $sql2;
			$result2 = QueryDb($sql2);
			$row2 = @mysqli_fetch_row($result2);
		?>
		<?php //for ($i=1; $i<=$num; $i++){ ?>
        <?php if ($i==1 || $i%2!=0) { ?>  
          <tr>
        <?php } ?>    
            <td align="center">
                <table width="100%" border="0" cellspacing="1" cellpadding="1" id="TD<?=$i?>" onmouseover="hover('<?=$i?>','1')" onmouseout="hover('<?=$i?>','0')" onclick="ViewDetail(<?=$row[0]?>)" style="cursor:pointer">
                  <tr>
                    <td rowspan="3" align="center" valign="middle" width="157">
                    	<div style="margin-left:10px">
                          <table width="90" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td height="90" align="center" valign="middle" bgcolor="#CCCCCC"><img src="../inc/gambar2.php?replid=<?=$row[0]?>&table=pustaka" /></td>
                              </tr>
                          </table>
                        </div>
                    </td>
                    <td>
                    <span class="news_content1"><?=stripslashes((string) $row2[0])?></span></td>
                  </tr>
                  <tr>
                    <td>
                    <span class="welc"><?=$row2[1]?> <?=$row2[2]?> <?=$row2[3]?></span></td>
                  </tr>
                  <tr>
                    <td>
                    <span class="err"><?=$row2[4]?></span></td>
                  </tr>
                </table>
            </td>
          <?php if ($i%2==0) { ?>
          </tr>
		  <?php } ?>
          <?php $i++; ?>
<?php } ?>
        </table>
	</div>
</body>
</html>