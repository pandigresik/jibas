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
require_once('../inc/sessioninfo.php');
require_once('../inc/common.php');
$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];
$kriteria = "";
if (isset($_REQUEST['kriteria']))
	$kriteria = $_REQUEST['kriteria'];
$keyword = "";
if (isset($_REQUEST['keyword']))
	$keyword = $_REQUEST['keyword'];
$perpustakaan = "";
if (isset($_REQUEST['perpustakaan']))
	$perpustakaan = $_REQUEST['perpustakaan'];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daftar Pustaka</title>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function chg_krit(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	var kriteria = document.getElementById('kriteria').value;
	var keyword = document.getElementById('keyword').value;
	document.location.href = "pustaka.php?kriteria="+kriteria+"&keyword="+keyword+"&perpustakaan="+perpustakaan;
}
function view_all(){
	document.getElementById('keyword').value = "";
	var perpustakaan = document.getElementById('perpustakaan').value;
	document.location.href = "pustaka.php?op=all&perpustakaan="+perpustakaan;
}
function view(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	var kriteria = document.getElementById('kriteria').value;
	var keyword = document.getElementById('keyword').value;
	if (keyword=='' || keyword.length<3){
		alert ('Anda harus mengisikan Kata Kunci\nKata kunci harus lebih dari 3 karakter');
		document.getElementById('keyword').focus();
	} else {
		document.location.href = "pustaka.php?op=view&kriteria="+kriteria+"&keyword="+keyword+"&perpustakaan="+perpustakaan;
	}
}
function pilih(id){
	//var replid = document.getElementById('replid'+id).value;
	var kodepustaka = document.getElementById('kodepustaka'+id).value;
	//var judul = document.getElementById('judul'+id).value;
	parent.opener.AcceptPustaka(kodepustaka);
	window.close();
}
</script>
</head>

<body topmargin="0" leftmargin="0">
	<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Daftar Pustaka</font><br />
    </div>
    <div>
        <table width="100%" border="0" cellspacing="3" cellpadding="0">
          <tr>
            <td width="38%">&nbsp;&nbsp;&nbsp;
            <table width="100%" border="0" cellspacing="2" cellpadding="0">
              <tr>
                <td>Perpustakaan</td>
                <td>
                	<?php
					OpenDb();
					if (SI_USER_LEVEL()==2){
						$sql = "SELECT replid,nama FROM perpustakaan WHERE replid=".SI_USER_IDPERPUS()." ORDER BY nama";
					} else {
						$sql = "SELECT replid,nama FROM perpustakaan ORDER BY nama";
					}
					$result = QueryDb($sql);
					?>
                	<select name="perpustakaan" id="perpustakaan" class="cmbfrm"  onchange="chg_krit()">
					<?php
                    if (SI_USER_LEVEL()!=2){
                        echo "<option value='-1' ".IntIsSelected('-1',$perpustakaan).">(Semua)</option>";
                    }
                    while ($row = @mysqli_fetch_row($result)){
                    if ($perpustakaan == "")
                        $perpustakaan = $row[0];	
                    ?>
                        <option value="<?=$row[0]?>" <?=IntIsSelected($row[0],$perpustakaan)?>><?=$row[1]?></option>
                    <?php
                    }
                    ?>
                    </select>
                </td>
              </tr>
              <tr>
                <td>Cari&nbsp;berdasarkan&nbsp;</td>
                <td><select name="kriteria" class="cmbfrm" id="kriteria" style="width:145px" onchange="chg_krit()">
                  <option value="judul" <?=StringIsSelected('judul',$kriteria)?>>Judul</option>
                  <option value="keyword" <?=StringIsSelected('keyword',$kriteria)?>>Keyword</option>
                </select></td>
              </tr>
              <tr>
                <td>Kata Kunci</td>
                <td><input name="keyword" class="inputtxt" id="keyword" value="<?=$keyword?>" /></td>
              </tr>
            </table></td>
            <td width="5%"><a href="javascript:view()"><img src="../img/view.png" border="0" /></a></td>
            <td width="57%" align="right"><input onclick="view_all()" name="button" type="submit" class="cmbfrm3" id="button" value="Tampilkan Semua" />
            &nbsp;&nbsp;</td>
          </tr>
        </table>
    </div>
    <?php if ($op!=""){ ?>
<div>
    <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab">
      <tr>
        <td width="28" height="25" align="center" class="header">No</td>
        <td width="275" height="25" align="center" class="header">Kode Pustaka</td>
        <td width="788" height="25" align="center" class="header">Judul</td>
        <td width="40" align="center" class="header">&nbsp;</td>
      </tr>
      <?php
	  OpenDb();
	  $filter="";
	  if ($op=="all"){
	  	if ($perpustakaan!='-1')
			$filter=" AND d.perpustakaan=".$perpustakaan;
	  	$sql = "SELECT p.replid,p.judul,d.kodepustaka FROM pustaka p,daftarpustaka d WHERE d.pustaka=p.replid AND d.status=1 $filter ORDER BY p.judul ASC";
	  } else {
	  	if ($perpustakaan!='-1')
			$filter=" AND d.perpustakaan=".$perpustakaan;
	  	if ($kriteria=='judul')
			$sql = "SELECT p.replid,p.judul,d.kodepustaka FROM pustaka p,daftarpustaka d WHERE d.pustaka=p.replid AND p.judul LIKE '%$keyword%'  AND d.status=1 $filter ORDER BY p.judul ASC";
		else 
			$sql = "SELECT p.replid,p.judul,d.kodepustaka FROM pustaka p,daftarpustaka d WHERE d.pustaka=p.replid AND p.keyword LIKE '%$keyword%' AND d.status=1 $filter ORDER BY p.replid ";	
	  }
	  //echo $sql;
	  $result = QueryDb($sql);	  
	  $num = @mysqli_num_rows($result);
	  if ($num>0){
		  $cnt=1;
		  while ($row = @mysqli_fetch_row($result)){
		  ?>
          <input type="hidden" name="replid<?=$cnt?>" id="replid<?=$cnt?>" value="<?=$row[0]?>" />
          <input type="hidden" name="kodepustaka<?=$cnt?>" id="kodepustaka<?=$cnt?>" value="<?=$row[2]?>" />
          <input type="hidden" name="judul<?=$cnt?>" id="judul<?=$cnt?>" value="<?=$row[1]?>" />
		  <tr>
			<td width="28" height="20" align="center" class="tab_content"><?=$cnt?></td>
			<td height="20" class="tab_content"><div class="tab_content"><?=$row[2]?></div></td>
            <td height="20" class="tab_content"><div class="tab_content"><?=$row[1]?></div></td>
			<td align="center" class="tab_content"><input name="button2" type="button" class="cmbfrm2" id="button2" value="Pilih" onclick="pilih('<?=$cnt?>')" /></td>
		  </tr>
          <?php $cnt++; ?>
		  <?php } ?>
      <?php } else { ?>
      <tr>
        <td height="20" colspan="4" align="center" class="nodata">Tidak ada data</td>
      </tr>
      <?php } ?>
    </table>
</div>
    <?php } else { ?>
    <br />
	<?php } ?>
	<br />
<div align="center">
<input type="button" class="cmbfrm2" value="Tutup" onclick="window.close()" />
</div>    
</body>
</html>