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
require_once('include/config.php');
require_once('include/sessioninfo.php');

session_name("JBSSMS");
session_start();
if (!IsLoggedIn()){
	include('login.php');
} else {
?>
<html>
<head>
<title>JIBAS SMS Gateway</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="images/jibas2015.ico" rel="shortcut icon" />
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function alertSize() {
  /**/
  var WinHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    WinHeight = window.innerHeight;
  } else if( document.documentElement &&
      ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    WinHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    WinHeight = document.body.clientHeight;
  }
  document.getElementById('bottom').style.height = (parseInt(WinHeight)-130)+"px";
  //document.getElementById('bottom').style.height = (parseInt(WinHeight)-300)+"px";
	
}
function Logout(){
	if (confirm('Anda yakin akan keluar dari Jibas SMS Gateway?'))
		window.location.href="logout.php";
}
function ChgPass(){
	var addr="chgpass.php";
	newWindow(addr,'GantiPasswordUser','419','200','resizeable=0,scrollbars=0,status=0,toolbar=0');
}

function CekSession(state){
}
</script>
<style type="text/css">
<!--
html, body{overflow:hidden}
.aWhite {
	color: #FFF;
}
-->
</style>
<link href="style/style.css" rel="stylesheet" type="text/css">
</head>
<body style="padding:0px; margin:0px" bgcolor="#5f5f5f" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="alertSize()" onResize="alertSize()" >
<!-- ImageReady Slices (InfoGuru4_GREEN_PADI.psd) -->
<table id="Table_01" width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr height="12">
		<td width="21"><img src="images/slice/BGJbsSMS_01.jpg" width="21" height="12" alt=""></td>
		<td width="26">
			<img src="images/slice/BGJbsSMS_02.jpg" width="26" height="12" alt=""></td>
		<td background="images/slice/BGJbsSMS_03.jpg" width="*" height="12">
			</td>
		<td width="100">
			<img src="images/slice/BGJbsSMS_04.jpg" width="100" height="12" alt=""></td>
		<td width="199">
			<img src="images/slice/BGJbsSMS_05.jpg" width="199" height="12" alt=""></td>
		<td width="15">
			<img src="images/slice/BGJbsSMS_06.jpg" width="15" height="12" alt=""></td>
		<td width="30">
			<img src="images/slice/BGJbsSMS_07.jpg" width="30" height="12" alt=""></td>
	</tr>
	<tr height="63">
		<td>
			<img src="images/slice/BGJbsSMS_08.jpg" width="21" height="63" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_09.jpg" width="26" height="63" alt=""></td>
		<td background="images/slice/BGJbsSMS_10.jpg" width="*" height="63">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" style="padding-right:15px">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" href="inbox/inbox.php" target="bottom"><img src="images/IcoInbox.png" width="37" height="37" border="0"></a>
                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" class="LinkMenu" href="inbox/inbox.php" target="bottom">Kotak&nbsp;Masuk</a>
                        </td>
                      </tr>
                    </table>
                </td>
                <td align="center" style="padding-right:15px">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" href="presensi/presensi.main.php" target="bottom"><img src="images/IcoPresence.png" width="37" height="37" border="0"></a>
                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" class="LinkMenu" href="presensi/presensi.main.php" target="bottom">Presensi</a>
                        </td>
                      </tr>
                    </table>
                </td>
                <td align="center" style="padding-right:15px">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" href="penilaian/penilaian.main.php" target="bottom"><img src="images/IcoNilai.png" width="37" height="37" border="0"></a>
                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" class="LinkMenu" href="penilaian/penilaian.main.php" target="bottom">Penilaian</a>
                        </td>
                      </tr>
                    </table>
                </td>
                <td align="center" style="padding-right:15px">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" href="pengumuman/pengumuman.main.php" target="bottom"><img src="images/IcoNews.png" width="37" height="37" border="0"></a>
                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" class="LinkMenu" href="pengumuman/pengumuman.main.php" target="bottom">Pengumuman</a>
                        </td>
                      </tr>
                    </table>
                </td>
                <td align="center" style="padding-right:15px">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" href="kritik/kritik.php" target="bottom"><img src="images/IcoKritik.png" width="37" height="37" border="0"></a>
                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" class="LinkMenu" href="kritik/kritik.php" target="bottom">Kritik&nbsp;&&nbsp;Saran</a>
                        </td>
                      </tr>
                    </table>
                </td>
                <td align="center" style="padding-right:15px">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" href="phonebook/phonebook.php"  target="bottom"><img src="images/phonebook.png" width="37" height="37" border="0"></a>                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" class="LinkMenu" href="phonebook/phonebook.php"  target="bottom">Phone Book</a>
                        </td>
                      </tr>
                    </table>
                </td>
				<?php
				if ($_SESSION['tingkat']==2){
				?>
				<td align="center" style="padding-right:15px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" href="javascript:ChgPass()" ><img src="images/IcoChgPass.png" width="37" height="37" border="0"></a>                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" class="LinkMenu" href="javascript:ChgPass()" >Ganti Password</a>
                        </td>
                      </tr>
                    </table>
                </td>
				<?php
				}
				?>
				<?php
				if ($_SESSION['tingkat']<2){
				?>
				<td align="center" style="padding-right:15px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" href="user/user.php"  target="bottom" ><img src="images/user.png" width="37" height="37" border="0"></a>                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" class="LinkMenu" href="user/user.php"  target="bottom" >Pengguna</a>
                        </td>
                      </tr>
                    </table>
                </td>
				<?php
				}
				?>
                <td align="center" style="padding-right:15px">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" href="javascript:Logout()" ><img src="images/IcoLogout.png" width="37" height="37" border="0"></a>
                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                        <a onClick="CekSession()" class="LinkMenu" href="javascript:Logout()" >Logout</a>
                        </td>
                      </tr>
                    </table>
                </td>
                
              </tr>
            </table>
        </td>
		<td>
			<img src="images/slice/BGJbsSMS_11.jpg" width="100" height="63" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_12.jpg" width="199" height="63" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_13.jpg" width="15" height="63" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_14.jpg" width="30" height="63" alt=""></td>
	</tr>
	<tr height="14">
		<td>
			<img src="images/slice/BGJbsSMS_15.jpg" width="21" height="14" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_16.jpg" width="26" height="14" alt=""></td>
		<td background="images/slice/BGJbsSMS_17.jpg" width="*" height="14">
			</td>
		<td>
			<img src="images/slice/BGJbsSMS_18.jpg" width="100" height="14" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_19.jpg" width="199" height="14" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_20.jpg" width="15" height="14" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_21.jpg" width="30" height="14" alt=""></td>
	</tr>
	<tr height="*">
		<td background="images/slice/BGJbsSMS_22.jpg" width="21">
			</td>
		<td background="images/slice/BGJbsSMS_23.jpg" width="26">
			</td>
		<td colspan="3" width="*" style="background-color:#FFF">
        <!--iframe src="pesanmasuk/inbox.php" id="bottom" name="bottom" width="100%" frameborder="0" style="border:none; min-height:521px"></iframe-->
        <iframe src="presensi/presensi.main.php" id="bottom" name="bottom" width="100%" frameborder="0" style="border:none; min-height:200px; min-width:700px"></iframe>
        </td>
		<td background="images/slice/BGJbsSMS_25.jpg" width="15">
			</td>
		<td background="images/slice/BGJbsSMS_26.jpg" width="30">
			</td>
	</tr>
	<tr>
		<td>
			<img src="images/slice/BGJbsSMS_27.jpg" width="21" height="11" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_28.jpg" width="26" height="11" alt=""></td>
		<td background="images/slice/BGJbsSMS_29.jpg" width="*" height="11">
			</td>
		<td>
			<img src="images/slice/BGJbsSMS_30.jpg" width="100" height="11" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_31.jpg" width="199" height="11" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_32.jpg" width="15" height="11" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_33.jpg" width="30" height="11" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/slice/BGJbsSMS_34.jpg" width="21" height="18" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_35.jpg" width="26" height="18" alt=""></td>
		<td width="*" height="18" background="images/slice/BGJbsSMS_36.jpg" class="LinkMenu" style="color:#FF0; text-decoration:none">Selamat datang <?=$_SESSION['nama']?>
			</td>
		<td height="18" colspan="2" background="images/slice/BGJbsSMS_38.jpg" class="Ket" style="color:#CCC">
	    <?=G_COPYRIGHT?></td>
		<td>
			<img src="images/slice/BGJbsSMS_39.jpg" width="15" height="18" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_40.jpg" width="30" height="18" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/slice/BGJbsSMS_41.jpg" width="21" height="12" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_42.jpg" width="26" height="12" alt=""></td>
		<td background="images/slice/BGJbsSMS_43.jpg" width="*" height="12">
			</td>
		<td>
			<img src="images/slice/BGJbsSMS_44.jpg" width="100" height="12" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_45.jpg" width="199" height="12" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_46.jpg" width="15" height="12" alt=""></td>
		<td>
			<img src="images/slice/BGJbsSMS_47.jpg" width="30" height="12" alt=""></td>
	</tr>
</table>
<!-- End ImageReady Slices -->
<iframe name="HiddenFrame" style="display:none; background-color:#fff; width:100%; height:0px; "></iframe>
</body>
</html>
<?php
}
?>