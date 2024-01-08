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
require_once('penilaian.list.class.php');
OpenDb();
//$PL = new PresensiList();
//$P->OnStart();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Penilaian List</title>
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<script language="javascript" src="../script/ShowError.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="penilaian.list.js"></script>
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

</head>
<body onresize="resize()" onload="resize()">
<div id="SubTitle" align="right">
<span style="color:#F90; background-color:#F90; font-size:20px">&nbsp;</span>&nbsp;<span style="color:#060; font-size:16px; font-weight:bold">Daftar Penilaian</span><br />
<a href="penilaian.main.php">Penilaian</a> > Daftar Laporan Penilaian
</div>
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="300" valign="top">
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td>
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="padding-right:4px">Bulan</td>
                <td style="padding-right:4px">
                <select id="Month" class="Cmb" onchange="ChgCmb()">
                    <?php
                    for ($i=1; $i<=12; $i++){
                        ?>
                        <option value="<?=$i?>" <?=StringIsSelected($i,date('m'))?>><?=$SMonth[$i-1]?></option>
                        <?php
                    }
                    ?>
                </select>
                </td>
                <td style="padding-right:2px">
                <select id="Year" class="Cmb" onchange="ChgCmb()">
                    <?php
                    for ($i=G_START_YEAR; $i<=date('Y'); $i++){
                        ?>
                        <option value="<?=$i?>" <?=StringIsSelected($i,date('Y'))?>><?=$i?></option>
                        <?php
                    }
                    ?>
                </select>
                </td>
              </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td>
        <div id="InfoGenList" style="overflow:auto; width:318px; padding-left:2px">
        	
        </div>
        </td>
      </tr>
    </table>
    </td>
    <td valign="top">
    <div id="DetailInfoGenList" style="overflow:auto; padding-left:2px">
    
    </div>
    </td>
  </tr>
</table>
<script language="javascript">
	OnLoad();
</script>
<iframe name="HiddenFrame" style="display:none"></iframe>
</body>
</html>
<script language="javascript">
function resize(){
	if( typeof( window.innerWidth ) == 'number' ) {
		WinHeight = window.innerHeight;
	  } else if( document.documentElement &&
		  ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		WinHeight = document.documentElement.clientHeight;
	  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		WinHeight = document.body.clientHeight;
	  }
	document.getElementById('InfoGenList').style.height = (WinHeight-105)+"px";
	document.getElementById('DetailInfoGenList').style.height = (WinHeight-75)+"px";
}
</script>