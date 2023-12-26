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
require_once('phonebook.class.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SMS</title>
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<script language="javascript" src="../script/ShowError.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/jquery-1.4.2.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="phonebook.js"></script>
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
</head>
<body onload="ResizeTabHeight()" onresize="ResizeTabHeight()">
<table id="MainTable" border="0" cellpadding="2" cellspacing="2" style="height:500px" width="100%">
  <tr>
    <td height="50"  valign="top" align="right">
    <div id="SubTitle" align="right">
    <span style="color:#F90; background-color:#F90; font-size:20px">&nbsp;</span>&nbsp;<span style="color:#060; font-size:16px; font-weight:bold">Phonebook</span>
    </div>
    </td>
  </tr>
  <tr>
    <td valign="top">
    	<div>
        	
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left">
                	<table border="0" cellspacing="2" cellpadding="0">
                      <tr>
                        <td>
                            <table border="0" cellspacing="0" cellpadding="1">
                              <tr>
                                <td>Jenis</td>
                                <td>
                                    <select id="jenis" class="Cmb">
                                        <option value="-1"> - Semua - </option>
                                        <option value="0">Siswa</option>
                                        <option value="1">Orangtua</option>
                                        <option value="2">Pegawai</option>
                                        <option value="3">Lainnya</option>
                                    </select>
                                </td>
                              </tr>
                            </table>
                        </td>
                        <td>
                            <table border="0" cellspacing="0" cellpadding="2">
                              <tr>
                                <td>Huruf&nbsp;Depan&nbsp;</td>
                                <td>
                                    <select id="alpha" class="Cmb">
                                        <?php
                                        echo "<option value='-1'> - Semua - </option>";
                                        foreach($Alphabet as $alp)
                                            echo "<option value='$alp'>$alp</option>";
                                        ?>
                                    </select>
                                </td>
                                </tr>
                            </table>
                        </td>
                        <td><input type="button" class="BtnSilver90" id="btnview" value="Lihat" /></td>
                        <td rowspan="2">
                            <button type="button" class="Btn" id='btnAdd'>
                                <table border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td><img src="../images/ico/tambah.png" /></td>
                                    <td>Tambah</td>
                                  </tr>
                                </table>
                            </button>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                            <table border="0" cellspacing="0" cellpadding="2" width='100%'>
                              <tr>
                                <td width='1'>Cari&nbsp;</td>
                                <td width='1'>
                                    <select id="field" class="Cmb">
                                        <option value="nohp">Nomor</option>
                                        <option value="nama">Nama</option>
                                    </select>
                                </td>
                                <td width='*'>
                                    <input type="text" class="InputTxt" id="keyword" style='width:95%' />
                                </td>
                              </tr>
                            </table>
                        </td>
                        <td width="143"><input type="button" class="BtnSilver90" value="Cari"  id="btnsearch" /></td>
                      </tr>
                    </table>
                </td>
                <td align="right">
                	<button type="button" class="Btn" id='btnImport'>
                        <table border="0" cellspacing="0" cellpadding="2">
                          <tr>
                            <td><img src="../images/ico/down.png" /></td>
                            <td>Import Data Siswa &amp; Pegawai</td>
                          </tr>
                        </table>
                    </button>
                </td>
              </tr>
            </table>

        </div>
    	<div id="phonebookList">
        </div>
    </td>
  </tr>
</table>
</body>
</html>
<script language="javascript">
	
</script>