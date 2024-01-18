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
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/getheader.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');

$tahunajaran = $_REQUEST['tahunajaran']; 
$semester = $_REQUEST['semester']; 
$pelajaran = $_REQUEST['pelajaran']; 
$nis = $_REQUEST['nis'];
$nis_aktif = $_REQUEST['nis_aktif'];
$kelas = $_REQUEST['kelas'];

OpenDb();
$sql = "SELECT departemen FROM pelajaran WHERE replid='$pelajaran'";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$departemen = $row[0];
CloseDb();
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Laporan Nilai]</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tables.js"></script>
</head>
<body >
<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>LAPORAN NILAI</strong></font><br />
 </center><br /><br />
  <table width="100%" border="0" height="100%">
            <tr>
                <td width="7%" valign="top">
                <?php 	OpenDb();
                    $sql = "SELECT * FROM semester WHERE replid = '".$semester."'";
                    $result = QueryDb($sql);
                    $row = mysqli_fetch_array($result);
                    
                ?>	
                <font color="#000000" size="2" class="nav_title"><b>Departemen </b></font>
                <?php CloseDb(); ?>               </td>      	
              <td width="33%" valign="top" class="news_content1">&nbsp;<?=$row['departemen']?></td>
                <td width="5%" valign="top"><?php 	OpenDb();
                    $sql = "SELECT * FROM semester WHERE replid = '".$semester."'";
                    $result = QueryDb($sql);
                    $row = mysqli_fetch_array($result);
                    
                ?>	
                <font color="#000000" size="2" class="nav_title"><b>Semester </b></font>
                <?php CloseDb(); ?>               </td>
              <td width="55%" valign="top" class="news_content1">&nbsp;<?=$row['semester']?></td>
      </tr>
      <tr>
                <td valign="top">
                <?php 	OpenDb();
                    $sql = "SELECT * FROM tahunajaran WHERE replid = '".$tahunajaran."'";
                    $result = QueryDb($sql);
                   
                    $row = mysqli_fetch_array($result);
                    
                ?>	
                <font color="#000000" size="2" class="nav_title"><b>Tahun&nbsp;Ajaran </b></font>
                <?php CloseDb(); ?>               </td>      	
        <td valign="top" class="news_content1">&nbsp;<?=$row['tahunajaran']?></td>
                <td valign="top"><?php 	OpenDb();
                    $sql = "SELECT * FROM kelas WHERE replid = '".$kelas."'";
                    $result = QueryDb($sql);
                    $row = mysqli_fetch_array($result);
                    
                ?>	
                <font color="#000000" size="2" class="nav_title"><b>Kelas </b></font>
                <?php CloseDb(); ?>               </td>
        <td valign="top" class="news_content1">&nbsp;<?=$row['kelas']?></td>
      </tr>
            <tr>
              <td valign="top">
			    <?php 	OpenDb();
                    $sql = "SELECT * FROM pelajaran WHERE replid = '".$pelajaran."'";
                    //echo $sql;
					$result = QueryDb($sql);
                   
                    $row = mysqli_fetch_array($result);
                    
                ?>	
                <font color="#000000" size="2" class="nav_title"><b>Pelajaran </b></font>                </td>
              <td valign="top" class="news_content1">&nbsp;<?=$row['nama']?><?php CloseDb(); ?></td>
              <td valign="top"><?php 	OpenDb();
                    $sql = "SELECT * FROM siswa WHERE nis = '".$nis."'";
                    $result = QueryDb($sql);
                    $row = mysqli_fetch_array($result);
                    
                ?>	
                <font color="#000000" size="2" class="nav_title"><b>Siswa </b></font>
                <?php CloseDb(); ?>               </td>
              <td valign="top" class="news_content1">&nbsp;<?=$row['nis']."-".$row['nama']?></td>
      </tr>
            <tr>
              <td colspan="4" valign="top">
              <table width="100%" border="0" height="100%" >
                <?php OpenDb();
                    $sql = "SELECT j.replid, j.jenisujian FROM jenisujian j, ujian u WHERE j.idpelajaran = '$pelajaran' AND u.idjenis = j.replid ".
                    "GROUP BY j.jenisujian";
                    //echo $sql;
                    $result = QueryDb($sql);
                    if (mysqli_num_rows($result) > 0) { //2
                    while($row = @mysqli_fetch_array($result)){	//1		
                ?>
                <tr>
                    <td colspan="2"><fieldset><legend><span class="news_title2"><?=$row['jenisujian']?></span></legend>
                    <br />
                    <?php 	OpenDb();		
                        $sql1 = "SELECT u.tanggal, n.nilaiujian, n.keterangan FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas' AND u.idpelajaran = '$pelajaran' AND u.idsemester = '".$semester."' AND u.idjenis = '".$row['replid']."' AND u.replid = n.idujian AND n.nis = '$nis' ".
                        "ORDER BY u.tanggal";
                        $result1 = QueryDb($sql1);
                        
                        if (@mysqli_num_rows($result1) > 0){
                    ?>
                        <table border="1" width="100%" id="table19" class="tab" >
                            <tr class="header" align="center" height="30">		
                                <td width="5%">No</td>
                                <td width="20%">Tanggal</td>
                                <td width="10%">Nilai</td>
                                <td width="*">Keterangan</td>
                            </tr>
                            <?php 
                                $sql2 = "SELECT AVG(n.nilaiujian) as rata FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas' AND u.idpelajaran = '$pelajaran' AND u.idsemester = '".$semester."' AND u.idjenis = '".$row['replid']."' AND u.replid = n.idujian AND n.nis = '$nis' ";
                                $result2 = QueryDb($sql2);	
                                $row2 = @mysqli_fetch_array($result2);
                                $rata = $row2['rata'];
                                $cnt = 1;
                                while($row1 = @mysqli_fetch_array($result1)){			
                            ?>
                            <tr>        			
                                <td width="5" height="25" align="center"><?=$cnt?></td>
                                <td width="250" height="25" align="center"><?=LongDateFormat($row1[0])?></td>
                                <td width="10" height="25" align="center"><?=$row1[1]?></td>
                                <td height="25"><?=$row1[2]?></td>            
                            </tr>	
                        <?php 	$cnt++;
                                }	?>
                            <tr>        			
                                <td colspan="2" height="25" align="center"><strong>Nilai rata rata</strong></td>
                                <td width="10" height="25" align="center"><?=round($rata,2)?></td>
                                <td height="25">&nbsp;</td>            
                            </tr>
                        </table>
                        <?php } else { ?>
                        <table width="100%" border="0" align="center" id="table1">          
                            <tr>
                                <td align="center" valign="middle" height="50">
                                <font color ="red" size = "2" class="err"><b>Tidak ditemukan adanya data.</b></font>                    </td>
                            </tr>
                        </table>
                        <?php } ?>
                    </fieldset>                    </td>	
                </tr>
<?php } //1 ?>
<?php } else { //2?>
                <tr>
                    <td width="72%" height="50" align="center" valign="middle">
                    <table border="0" width="100%" id="table1" cellpadding="0" cellspacing="0">
                    <tr align="center" valign="middle" >
                        <td><span class="err">Tidak ditemukan adanya data.</span></td>
                    </tr>
                    </table>                    </td>
                </tr>
<?php } //2?>
            </table>
              </td>
            </tr>
            </table>
  </td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>

</html>