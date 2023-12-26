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
require_once("../include/sessionchecker.php");
require_once('../require/config.php');
require_once('../require/db_functions.php');
openDb();
	
		 $query="SELECT * FROM sistoakademik.kalenderakademik WHERE Departemen='".$_GET['departemen']."'";							
			
		$result=querydb($query);		
		
		
		if(!empty($_GET['kalender']))
			{
			$query1="SELECT * FROM sistoakademik.kalenderakademik WHERE Departemen='".$_GET['departemen']."' AND Replid=".$_GET['kalender'];							
			
			$result2=querydb($query1);		

			$fetch1=mysqli_fetch_array($result2);

			}
			 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../css/mystyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<script language="javascript">


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
function ubah(){
	a=document.form1.kalender.value;
	newWindow('form_tambah_kalender.php?open=<?=$_GET['kalender'];?>&departemen=<?=$_GET['departemen'];?>&action=edit&replid='+a,'tambahKalender','580px','150','');
}
function ganti(){
	a=document.form1.kalender.value;
	window.location='frame_tengah_kalender.php?departemen=<?=$_GET['departemen'];?>&kalender='+a;
	parent.bawah.window.location="frame_bawah_kalender.php?kalender="+a;
		
}

function hapus(){
	tanya=confirm('Apakah anda yakin untuk menghapus data ini?');
	if(tanya==true)
		{
			a=document.form1.kalender.value;
			window.location='proses_kalender.php?departemen=<?=$_GET['departemen'];?>&action=deleteKalender&replid='+a;
		}
}
</script>
<form name="form1" method="post" action="">
  <table width="80%"  border="0" align="center">
    <tr>
      <td width="139">Kalender Akademik</td>
      <td width="34">:</td>
      <td colspan="2"><table width="80%"  border="0">
          <tr>
            <td width="10">			<select name="kalender" id="kalender" onChange="ganti()">
							<option value="">--Pilih Kalender--</option>
            <?php 
					while($fetch=mysqli_fetch_array($result))
						{
							if($_GET['kalender']==$fetch['Replid'])
								{
			?>
									  <option value="<?=$fetch['Replid'];?>" selected><?=$fetch['Kalender'];?></option>
            <?php
								}
							else
								{
			?>
									  <option value="<?=$fetch['Replid'];?>"><?=$fetch['Kalender'];?></option>
			<?php
								}
						 }
				
			?>
			</select></td>
            <td><img style="cursor:pointer" src="../images/ico/tambah.png" width="16" height="16" onClick="newWindow('form_tambah_kalender.php?open=<?=$_GET['kalender'];?>&departemen=<?=$_GET['departemen'];?>','tambahKalender','580px','150','')"><span id="com1"><img style="cursor:pointer" src="../images/ico/ubah.png" width="16" height="16" onClick="ubah()"><img style="cursor:pointer" src="../images/ico/hapus.png" width="16" height="16" onClick="hapus()"></span></td>
          </tr>
      </table></td>
    </tr>
	<?php
	if(!empty($_GET['kalender'])){
	?>
    <tr>
      <td>Periode</td>
      <td>:</td>
      <td colspan="2"><table  border="0">
        <tr>
          <td width="100"><?=$fetch1['TglMulai'];?></td>
          <td width="50" align="center">s/d</td>
          <td width="100"><?=$fetch1['TglAkhir'];?></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td>Status</td>
      <td>:</td>
      <td width="101"  ><a href="proses_kalender.php?kalender=<?=$_GET['kalender'];?>&replid=<?=$fetch1['Replid'];?>&action=statusAktif&status=<?=$fetch1['StatusAktif']; ?>&departemen=<?=$_GET['departemen'];?>"><?php  if($fetch1['StatusAktif']==1) echo "<img src=../images/ico/aktif.png border=0> Aktif"; else echo "<img src=../images/ico/nonaktif.png border=0> Tidak Aktif";?></a></td>
      <td width="499"> <?php
	  					if ($fetch1['StatusAktif']==1)
							{
						?>
	  						<img src=../images/ico/visible.png border=0> <b>Terlihat</b>
						<?php
							}
						else
							{
						?>		
								<a href="proses_kalender.php?kalender=<?=$_GET['kalender'];?>&replid=<?=$fetch1['Replid'];?>&action=statusTerlihat&status=<?=$fetch1['StatusTerlihat']; ?>&departemen=<?=$_GET['departemen'];?>"><?php  if($fetch1['StatusTerlihat']==1)echo "<img src=../images/ico/visible.png border=0> Terlihat"; else echo "<img src=../images/ico/invisible.png border=0> Tidak Terlihat";?></a></td>
    					<?php
							}
						?>
	</tr>
	<?php
	}
	?>
  </table>
</form>
<script language="javascript">
<?php
if(empty($_GET['kalender']))
	{
?>
	document.getElementById('com1').style.display='none';
	parent.bawah.window.location='blank.php';
<?php
	}
?>
</script>

</body>
</html>