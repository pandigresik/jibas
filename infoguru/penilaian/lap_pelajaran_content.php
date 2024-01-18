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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once("../include/theme.php");
$pelajaran=$_REQUEST['pelajaran'];
$kelas=$_REQUEST['kelas'];
$nis=$_REQUEST['nis'];
$departemen=$_REQUEST['departemen'];

OpenDb();
$sql_sem = "SELECT * FROM semester WHERE departemen = '".$departemen."'";
$result_sem = QueryDb($sql_sem);
$i = 0;
while ($row_sem = @mysqli_fetch_array($result_sem)) {
	$sem[$i] = [$row_sem['replid'], $row_sem['semester']];
	$i++;
}
$sql_pel = "SELECT nama FROM pelajaran WHERE replid = '".$pelajaran."'";
$result_pel = QueryDb($sql_pel);
$row_pel = @mysqli_fetch_array($result_pel);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<link rel="stylesheet" type="text/css" href="../script/tooltips.css" />
<link href="../script/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/ajax.js"></script>
<!--<script language="javascript" src="../script/tools.js"></script>-->
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/newwindow.js"></script>
<script src="../script/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script language="javascript">
var win = null;
function newWindow(mypage,myname,w,h,features) {
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
}
function cetak(semester) {
	//alert ('mau dicetak '+semester);
	newWindow('cetak_lap_pelajaran.php?semester='+semester+'&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&nis=<?=$nis?>&departemen=<?=$departemen?>','',704,549,'resizable=1,scrollbars=1,status=0,toolbar=0');
}

</script>
<script language="javascript" src="../script/cal2.js"></script>
<script language="javascript" src="../script/cal_conf2.js"></script>

<style type="text/css">
<!--
.style1 {color: #666666}
-->
</style>
</head>

<body leftmargin="0" topmargin="0">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />&nbsp;please wait...
</div>
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
    <table border="0"width="100%">
    <!-- TABLE TITLE -->
    <tr>
     
      <td width="50%" align="right" valign="top">
      <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Nilai Siswa</font><br />
            <a href="../penilaian.php" target="content"> <font size="1" color="#000000"><b>Penilaian</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Laporan Nilai Siswa</b></font>
      </td>
    </tr>
	</table>
<table border="0" cellpadding="20" bgcolor="#FFFFFF" cellspacing="0" width="100%" >
    <tr>
    	<td width="100%" bgcolor="white" valign="top">
		
       

        </td>
    </tr>
    <tr height="600">
    	<td width="100%" bgcolor="#FFFFFF" valign="top">
        <div id="TabbedPanels1" class="TabbedPanels">
          <ul class="TabbedPanelsTabGroup">
            <?php for($k=0;$k<sizeof($sem);$k++) {?>
            <li class="TabbedPanelsTab" tabindex="<?=$k?>">Semester <?=$sem[$k][1]?></li>
            <?php } ?>
          </ul>
          <div class="TabbedPanelsContentGroup">
            <?php for($k=0;$k<sizeof($sem);$k++) {?>
            <div class="TabbedPanelsContent">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
                    <table width="100%" border="0" height="100%">
                    <tr>
                        <td valign="top">
                            <font size="2"><span class="style1"><b>Pelajaran 
                            <?=$row_pel[0]?>
                            </b><br />
                            <b>Semester 
                            <?=$sem[$k][1]?>
                            </b></span></font><font size="2" color="#000000"><br />
                            </font></td>      	
                        <td align="right" valign="top"><a href="JavaScript:cetak('<?=$sem[$k][0]?>')"><img src="../images/ico/print.png" border="0" onmouseover="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a></td>
                    </tr>
                    <tr>
                        <td valign="right"></td>
                    </tr>
                    
                    
            <?php $sql = "SELECT j.replid, j.jenisujian FROM jenisujian j WHERE j.idpelajaran = '$pelajaran' GROUP BY j.jenisujian";
                //echo $sql;
                $result = QueryDb($sql);
                while($row = @mysqli_fetch_array($result)){			
            ?>
                    <tr>
                        <td colspan="2">
                        <br /><strong> <?=$row['jenisujian']?> </strong>
                        <br /><br />		
                        <table border="1" width="100%" id="table" class="tab" bordercolor="#000000">
                        <tr>		
                            <td width="5" height="30" align="center" class="header">No</td>
                            <td width="120" class="header" align="center" height="30">Tanggal</td>
                            <td width="100" height="30" align="center" class="header">Nilai</td>
                            <td width="400" class="header" align="center" height="30">Keterangan</td>
                        </tr>
                <?php 	OpenDb();		
                    $sql1 = "SELECT u.tanggal, n.nilaiujian, n.keterangan FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas' AND u.idpelajaran = '$pelajaran' AND u.idsemester = '".$sem[$k][0]."' AND u.idjenis = '".$row['replid']."' AND u.replid = n.idujian AND n.nis = '$nis' ORDER BY u.tanggal";
                    $result1 = QueryDb($sql1);
                    $sql2 = "SELECT AVG(n.nilaiujian) as rata FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas' AND u.idpelajaran = '$pelajaran' AND u.idsemester = '".$sem[$k][0]."' AND u.idjenis = '".$row['replid']."' AND u.replid = n.idujian AND n.nis = '$nis' ";
                    $result2 = QueryDb($sql2);
                    $row2 = @mysqli_fetch_array($result2);
                    $rata = $row2['rata'];
                  
                    $cnt = 1;
                    if (@mysqli_num_rows($result1)>0){
                        while($row1 = @mysqli_fetch_array($result1)){			
                 ?>
                        <tr>        			
                            <td height="25" align="center"><?=$cnt?></td>
                            <td height="25" align="center"><?=format_tgl($row1[0])?></td>
                            <td height="25" align="center"><?=$row1[1]?></td>
                            <td height="25"><?=$row1[2]?></td>            
                        </tr>	
                <?php 	$cnt++;
                        } ?>
                        <tr style="background-color:#E1FFFF">        			
                            <td colspan="2" height="25" align="center"><strong>Nilai rata rata</strong></td>
                            <td height="25" align="center"><?=round($rata,2)?></td>
                            <td height="25">&nbsp;</td>            
                        </tr>                    
                <?php } else { ?>
                        <tr>        			
                            <td colspan="4" height="25" align="center">Tidak ada nilai</td>
                        </tr>
                <?php } ?>
                        </table>                    </td>	
                    </tr>
            <?php } ?>
                    </table>
                    </td>
                </tr>
                </table>
            </div>
            <?php } ?>
          </div>
        </div>
        <script type="text/javascript">
        <!--
        var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
        //-->
        </script>
        </td>
    </tr>
    </table>
     <!-- END OF CONTENT //--->
</body>
</html>