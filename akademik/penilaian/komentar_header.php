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

OpenDb();

$error = "";

if (isset($_REQUEST['error']))
	$error = $_REQUEST['error'];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$tahunajaran ="";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];

$semester = "";
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];

$pelajaran = "";
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];

$tingkat = "";
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];

$kelas = "";
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];

$jenis = 0;
if (isset($_REQUEST['jenis']))
    $jenis = $_REQUEST['jenis'];

$op = "";
if (isset($_REQUEST['op']))
{
	$op = $_REQUEST['op'];

    $sql1 = "SELECT k.replid as replid, s.nis, s.nama, k.komentar 
               FROM siswa s, komennap k, infonap i 
              WHERE s.idkelas = '$kelas' AND k.nis = s.nis AND k.idinfo = i.replid 
                AND i.idkelas = '$kelas' AND i.idpelajaran = '$pelajaran' AND i.idsemester='$semester'";
    $result1 = QueryDb($sql1);
    if ($op=="lihat")
    {
        if (@mysqli_num_rows($result1)>0)
        {
?>
	    <script language="javascript">
	    parent.header.location.href = "komentar_header.php?departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>";
		parent.footer.location.href = "komentar.lihat.php?departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>";
		</script>
<?php
        }
        else
        {
?>
        <script language="javascript">
	    parent.header.location.href = "komentar_header.php?departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&error=1";
	    parent.footer.location.href = "blank_komentar.php";
	    </script>
<?php
        }
    }

    if ($op=="show")
    {
	    if (@mysqli_num_rows($result1)>0)
	    {
	    ?>
	    <script language="javascript">
	    parent.header.location.href = "komentar_header.php?departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>";
		parent.footer.location.href = "komentar_footer.php?departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&jenis=<?=$jenis?>";
		</script>
<?php      }
        else
        { ?>
        <script language="javascript">
	    parent.header.location.href = "komentar_header.php?departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&error=1";
	    parent.footer.location.href = "blank_komentar.php";
        </script>
<?php      }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Komentar Nilai Rapor</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/cal2.js"></script>
<script language="javascript" src="../script/cal_conf2.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">
function change() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;
	var kelas = document.getElementById("kelas").value;
		
	document.location.href = "komentar_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas;
	parent.footer.location.href = "blank_komentar.php";}

function change_dep() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var pelajaran = document.getElementById("pelajaran").value;
		
	parent.header.location.href = "komentar_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&pelajaran="+pelajaran;
parent.footer.location.href = "blank_komentar.php";}

function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;	
	
	parent.header.location.href = "komentar_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat;
parent.footer.location.href = "blank_komentar.php";
}


function show(jenis) {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;	
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;
	var kelas = document.getElementById("kelas").value;

	
	if (kelas.length == 0) {	
		alert ('Pastikan kelas sudah ada!');
	} else if (semester.length == 0){
		alert ('Pastikan semester sudah ada!');
	} else if (pelajaran.length == 0){
		alert ('Pastikan pelajaran sudah ada!');			
	} else {		
		document.location.href = "komentar_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas+"&op=show&jenis="+jenis;
		//parent.footer.location.href = "komentar_footer.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas;
	}
}
function lihat() {	
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;	
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;
	var kelas = document.getElementById("kelas").value;
	
	if (kelas.length == 0) {	
		alert ('Pastikan kelas sudah ada!');
	} else if (semester.length == 0){
		alert ('Pastikan semester sudah ada!');
	} else if (pelajaran.length == 0){
		alert ('Pastikan pelajaran sudah ada!');			
	} else {		
		document.location.href = "komentar_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas+"&op=lihat";
		//parent.footer.location.href = "komentar_lihat.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas;
	}
}

</script>
</head>
	
<body topmargin="0" leftmargin="0">
<form action="komentar_header.php" method="post" name="main">
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	
	<td align="left" valign="top">
      <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <!-- TABLE TITLE -->
      <tr>
          <td align="left" width="12%"><strong>Departemen </strong></td>
          <td width="20%">
              <select name="departemen" id="departemen" onChange="change_dep()" style="width:180px;">
<?php           $dep = getDepartemen(SI_USER_ACCESS());
              foreach($dep as $value) {
                  if ($departemen == "")
                      $departemen = $value; ?>
                  <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
                      <?=$value ?>
                  </option>
\<?php           } ?>
              </select>
          </td>
          <td align="left" width="10%"><strong>Tingkat </strong></td>
          <td width="20%">
              <select name="tingkat" id="tingkat" onChange="change_tingkat()" style="width:180px;">
<?php         $sql2 = "SELECT replid,tingkat FROM tingkat WHERE aktif=1 AND departemen='$departemen' ORDER BY urutan";
              $result2 = QueryDb($sql2);

              while($row2 = mysqli_fetch_array($result2))
              {
                  if ($tingkat == "") {
                      $tingkat = $row2['replid'];
                  } ?>
                  <option value="<?=urlencode((string) $row2['replid'])?>" <?=IntIsSelected($row2['replid'], $tingkat) ?>>
                      <?=$row2['tingkat']?>
                  </option>
<?php
              } //while
              ?>
              </select>
          </td>
          <td width="37%" rowspan="2" align="right" valign="top">
              <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
              <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Komentar Nilai Rapor</font><br />
              <a href="../penilaian.php" target="content"> <font size="1" color="#000000"><b>Penilaian</b></font></a>
              &nbsp>&nbsp <font size="1" color="#000000"><b>Komentar Nilai Rapor</b></font>
          </td>
      </tr>
      <tr>
          <td align="left"><strong>Tahun Ajaran</strong></td>
          <td>
<?php         $sql3 = "SELECT replid, tahunajaran FROM tahunajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY replid DESC";
              $result3 = QueryDb($sql3);

              $row3 = @mysqli_fetch_array($result3);
              $tahunajaran = $row3['replid']; ?>
              <input type="text" name="tahun" id="tahun" size="20" readonly class="disabled" value="<?=$row3['tahunajaran']?>"  style="width:170px;"/>
              <input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$row3['replid']?>" />
          </td>
          <td><strong>Kelas </strong></td>
          <td>
              <select name="kelas" id="kelas" onChange="change()" style="width:180px;">
<?php         $sql4 = "SELECT replid,kelas FROM kelas WHERE aktif=1 AND idtahunajaran = '$tahunajaran' AND idtingkat = '$tingkat' ORDER BY kelas";
			  $result4 = QueryDb($sql4);

			  while($row4 = mysqli_fetch_array($result4))
              {
			       if ($kelas == "") {
              $kelas = $row4['replid'];
          }
			?>
                    <option value="<?=urlencode((string) $row4['replid'])?>" <?=IntIsSelected($row4['replid'], $kelas) ?>>
                    <?=$row4['kelas']?>
                    </option>
<?php         } //while
			?>
              </select>
           </td>
      </tr>
      <tr>
          <td align="left"><strong>Pelajaran</strong></td>
          <td>
              <select name="pelajaran" id="pelajaran" onChange="change()" style="width:180px;">
                  <?php

                  $sql5 = "SELECT replid,nama FROM pelajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY nama";
                  $result5 = QueryDb($sql5);

                  while ($row5 = @mysqli_fetch_array($result5)) {
                      if ($pelajaran == "")
                          $pelajaran = $row5['replid'];
                      ?>
                      <option value="<?=urlencode((string) $row5['replid'])?>" <?=IntIsSelected($row5['replid'], $pelajaran)?> >
                          <?=$row5['nama']?>
                      </option>
                      <?php
                  }
                  ?>
              </select>
          </td>
          <td colspan="3" rowspan="2">
              <table border="0" cellspacing="0">
              <tr>
                  <td>
                      <input type="button" name="input" value="Input Komentar Pelajaran" class="but" style="width:200px; height: 40px;"  title="Input Komentar Pelajaran"  onClick="show(0)" />
                  </td>
                  <td>
                      <input type="button" name="input" value="Input Komentar Spiritual & Sosial" class="but" style="width:240px; height: 40px;"  title="Input Komentar Spiritual & Sosial" onClick="show(1)" />
                  </td>
                  <td>
                      <input type="button" name="tampil" value="Lihat Komentar" class="but" style="width:200px; height: 40px;" title="Lihat Komentar per Kelas" onClick="lihat()" />
                  </td>
              </tr>
              </table>

          </td>
      </tr>
      <tr>
          <td align="left"><strong>Semester </strong></td>
          <td>
              <select name="semester" id="semester" onChange="change()" style="width:180px;">
                  <?php

                  $sql6 = "SELECT replid,semester FROM semester where departemen='$departemen' AND aktif = 1 ORDER BY replid DESC";
                  $result6 = QueryDb($sql6);

                  while ($row6 = @mysqli_fetch_array($result6)) {
                      if ($semester == "")
                          $semester = $row6['replid'];

                      ?>
                      <option value="<?=urlencode((string) $row6['replid'])?>" <?=IntIsSelected($row6['replid'], $semester)?> >
                          <?=$row6['semester']?>
                      </option>
                      <?php
                  }
                  ?>
              </select>
          </td>
      </tr>
    </table>

    </td>
</tr>
</table>
</form>
<hr width="95%" align="left" style="border-style:dashed; border-width:1px" />
</body>
<?php
if ($error>0){
	?>
	<script language="javascript">
			alert ('Belum ada data rapor untuk kriteria-kriteria ini  \nSilakan hitung terlebih dahulu nilai rapor untuk kriteria-kriteria tersebut!');
	</script>
	<?php
}
?>
</html>
<?php
CloseDb();
?>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
	var spryselect5 = new Spry.Widget.ValidationSelect("pelajaran");
	var spryselect6 = new Spry.Widget.ValidationSelect("semester");
</script>