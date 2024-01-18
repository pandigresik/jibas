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
require_once("../include/sessionchecker.php");
require_once('../library/dpupdate.php');

OpenDb();

if(isset($_REQUEST["semester"]))
	$semester = $_REQUEST["semester"];
if(isset($_REQUEST["kelas"]))
	$kelas = $_REQUEST["kelas"];
if(isset($_REQUEST["idaturan"]))
	$idaturan = $_REQUEST["idaturan"];

$sql = "SELECT p.nama, p.replid AS pelajaran, a.dasarpenilaian, j.jenisujian, j.replid AS jenis, dp.keterangan 
		  FROM jbsakad.aturannhb a, jbsakad.pelajaran p, jenisujian j, dasarpenilaian dp 
		 WHERE a.replid='$idaturan' AND p.replid = a.idpelajaran AND a.idjenisujian = j.replid 
		   AND a.dasarpenilaian = dp.dasarpenilaian";
$result=QueryDb($sql);

$row=@mysqli_fetch_array($result);
$namapel = $row['nama'];
$pelajaran = $row['pelajaran'];
$aspek = $row['keterangan'];
$namajenis = $row['jenisujian'];
$jenis = $row['replid'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Input Manual Nilai Akhir</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/tooltips.js"></script>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language="javascript">

function kembali(){
	document.location.href="nilai_pelajaran_content.php?kelas=<?=$kelas?>&semester=<?=$semester?>&idaturan=<?=$idaturan?>";
}

function validate(){
	var num=document.getElementById("jumsiswa").value;
	
	for (i=1;i<=num;i++) {			
		var nau = document.getElementById("nau"+i).value;
		if (nau.length == 0){
			alert ('Anda harus mengisikan data untuk Nilai Akhir!');
			document.getElementById("nau"+y).focus();
			return false;
		} else {
			if (isNaN(nau)){
				alert ('Nilai Akhir harus berupa bilangan!');
				document.getElementById("nau"+i).focus();
				return false;
			}
			
			if (parseInt(nau) > 100){
				alert ('Rentang Nilai Akhir antara 0 - 100!');
				document.getElementById("nau"+y).focus();
				return false;
			}
		}
	}
	document.getElementById('tampil_nilai_pelajaran').submit();	
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

</script>
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('nau1').focus();">
<form name="tampil_nilai_pelajaran" id="tampil_nilai_pelajaran" action="nilai_akhir_simpan.php" method="post">
<input type="hidden" name="semester" value="<?=$semester?>" />
<input type="hidden" name="kelas" value="<?=$kelas?>" />
<input type="hidden" name="idaturan" value="<?=$idaturan?>" />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <table width="100%" border="0" height="100%">
   	<tr>
    	<td>
        <table width="100%" border="0">
        <tr>        	
            <td width="17%"><strong>Pelajaran</strong></td>
            <td><strong>: <?=$namapel ?> </strong></td>
            <td rowspan="3" align="right" valign="bottom"><strong><font size="4">HITUNG MANUAL</font></strong></td>
        </tr>
        <tr>
            <td><strong>Aspek Penilaian</strong></td>
            <td><strong>: <?=$aspek?></strong></td>            
        </tr>
    	<tr>
            <td><strong>Jenis Pengujian</strong></td>
            <td><strong>: <?=$namajenis?></strong></td>   
		</tr>
        </table>
        <br />
        <table border="1" width="100%" id="table" class="tab">
        <tr>
            <td height="30" class="headerlong" align="center" width="4%">No</td>
            <td height="30" class="headerlong" align="center" width="10%">N I S</td>
            <td height="30" class="headerlong" align="center" width="*">Nama</td>
        <?php
        $sql_cek_ujian = "SELECT * FROM jbsakad.ujian WHERE idaturan='$idaturan' AND idkelas='$kelas' AND idsemester='$semester' ORDER by tanggal ASC";
        //echo $sql_cek_ujian;
        $result_cek_ujian=QueryDb($sql_cek_ujian);
        $jumlahujian=@mysqli_num_rows($result_cek_ujian);
        $i=1;
        while ($row_cek_ujian=@mysqli_fetch_array($result_cek_ujian)){
            $idujian[$i] = $row_cek_ujian['replid'];			
            $tgl = explode("-",(string) $row_cek_ujian['tanggal']);
            
        ?>
           <td height="30" width="50" class="headerlong" align="center" onMouseOver="showhint('Deskripsi :\n <?=$row_cek_ujian['deskripsi']?>', this, event, '120px')"><?=$namajenis."-".$i?>&nbsp;
            <br /><?=$tgl[2]."/".$tgl[1]."/".substr($tgl[0],2)?>
            </td>
        <?php
            $i++;
        }
        ?>
            <td height="30" class="headerlong" align="center" width="50">Rata2 Siswa</td>
            <td height="30" class="headerlong" align="center" width="50">NA <?=$namajenis?>
            </td>
        </tr>
        <?php
        //if ($jumlahujian<>0){
            
            $sql_siswa="SELECT * FROM jbsakad.siswa WHERE idkelas='$kelas' AND aktif=1 ORDER BY nama ASC";
            $result_siswa=QueryDb($sql_siswa);
            $cnt=1;
            $jumsiswa = mysqli_num_rows($result_siswa);
            while ($row_siswa=@mysqli_fetch_array($result_siswa)){
                $nilai = 0;	
                
        ?>
        <tr height="25">
            <td align="center"><?=$cnt?></td>
            <td align="center"><?=$row_siswa['nis']?></a></td>
            <td><?=$row_siswa['nama']?></td>
            <?php for ($j=1;$j<=count($idujian);$j++) { 
                    echo "<td align='center'>";		
                    $sql_cek_nilai_ujian="SELECT * FROM jbsakad.nilaiujian WHERE idujian='".$idujian[$j]."' AND nis='".$row_siswa['nis']."'";
                    //echo $sql_cek_nilai_ujian;
                    $result_cek_nilai_ujian=QueryDb($sql_cek_nilai_ujian);
                    $row_cek_nilai_ujian=@mysqli_fetch_array($result_cek_nilai_ujian);
                    $nilai = $nilai+$row_cek_nilai_ujian['nilaiujian'];
                    
                    if (@mysqli_num_rows($result_cek_nilai_ujian)>0){ 			
                            
                        echo $row_cek_nilai_ujian['nilaiujian'];
                        if ($row_cek_nilai_ujian['keterangan']<>"")
                            echo "<strong>*</strong>";
                        
                    } else { 
                            echo "0";
                    }
                        echo "</td>";
                }
            ?>
            
            <td height="25" align="center"><?=round($nilai/count($idujian),2);?></td>
            <td height="25" align="center">
            
            <?php
                $sql_get_nau_per_nis="SELECT nilaiAU,replid,keterangan FROM jbsakad.nau WHERE nis='".$row_siswa['nis']."' AND idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan'";
				
            	//echo $sql_get_nau_per_nis;			
                $result_get_nau_per_nis=QueryDb($sql_get_nau_per_nis);
                if (mysqli_num_rows($result_get_nau_per_nis) > 0) {
                    $row_get_nau_per_nis=@mysqli_fetch_array($result_get_nau_per_nis);
                    $na = $row_get_nau_per_nis['nilaiAU'];
				?>	
					<input type="hidden" name="nau[<?=$row_siswa['nis']?>][1]" value="<?=$row_get_nau_per_nis['replid'] ?>">
           	<?php  } else { 
                    $na = 0;
                }
            ?>
                    
                <input type="text" name="nau[<?=$row_siswa['nis']?>][0]" id="nau<?=$cnt?>" value="<?=$na?>" size="5" maxlength="5" <?php if ($cnt == $jumsiswa) {?> onkeypress="focusNext('simpan',event);" <?php } else { ?> onkeypress="focusNext('nau<?=(int)$cnt+1 ?>',event);" <?php } ?> />
                

                
            </td>
        </tr>
        <?php 	$cnt++;
            } 
        ?>
        </table>
        <script language='JavaScript'>
            Tables('table', 1, 0);
        </script>
        <input type="hidden" name="jumsiswa" id="jumsiswa" value="<?=$jumsiswa?>" />
        </td>
    </tr>
    <tr>
        <td><strong>* ada keterangan <strong></strong>
        </td>
    </tr>
    <tr>
        <td align="center">
            <input type="hidden" name="action" id="action" value="manual" />
            <input style="width:150px" type="button" name="simpan" id="simpan" value="Simpan Nilai Akhir" class="but" onclick="return validate();document.tampil_nilai_pelajaran.submit();">&nbsp;
            <input style="width:150px" type="button" name="batal" value="Kembali" class="but" onClick="kembali();">
    
        </td>
    </tr>
    </table>
    </td>
</tr>
</table>
</form>
</body>
</html>
<script type="text/javascript">
<!--
var num=document.getElementById("jumnis").value;
var x=1;
while (x<=num){
var sprytextfield = new Spry.Widget.ValidationTextField("nau_"+x);
x++;
}
//-->
</script>