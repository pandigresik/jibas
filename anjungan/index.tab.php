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
<div id="vtabs1">
<div>
    <ul>
        <li><a href="#vtabs-content-a"><img src="images/home.png" border="0"></a></li>
        <li><a href="#vtabs-content-b"><img src="images/news.png" border="0"></a></li>
        <li><a href="#vtabs-content-j"><img src="images/mading.png" border="0"></a></li>
        <li><a href="#vtabs-content-c"><img src="images/infosiswa.png" border="0"></a></li>
        <li><a href="#vtabs-content-i"><img src="images/jadkal.png" border="0"></a></li>
        <li><a href="#vtabs-content-k"><img src="images/pustaka.png" border="0"></a></li>
        <li><a href="#vtabs-content-g"><img src="images/pegawai.png" border="0"></a></li>
        <li><a href="#vtabs-content-h"><img src="images/psb.png" border="0"></a></li>
    </ul>
</div>
<div>
    <div id="#vtabs-content-a">
        <table border="0" cellpadding="2" cellspacing="2" width="100%">
        <tr><td align="right">
            <font style="font-family: Tahoma; font-size: 22px; color: #557d1d">&nbsp;</font>
        </td></tr>           
        </table>
        <div id="content-pane-a" style="overflow: auto; background-color: transparent;">
            &nbsp;
        </div>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr><td align="left">
            <a href="#" style="color:blue; font-weight:normal; text-decoration:underline;" onclick="b_Edit()">Edit</a>&nbsp;&nbsp;
            <a href="#" style="color:blue; font-weight:normal; text-decoration:underline;" onclick="b_ChangeBackground()">Atur gambar latar</a>&nbsp;&nbsp;
        </td></tr>           
        </table>
    </div>
    <div id="#vtabs-content-b">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="left" width="75%">
<?php              require_once("infosekolah/infosekolah.menu.php"); ?>
            </td>
            <td align="right" width="25%">
                <font style="font-family: Tahoma; font-size: 22px; color: #557d1d">INFO SEKOLAH</font>
            </td>
        </tr>
        </table>
        <div id="content-pane-b" style="position: relative; overflow: auto; background-color: transparent;">
            &nbsp;
        </div>
    </div>
    <div id="#vtabs-content-j">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="left" width="70%">
<?php              require_once("mading/mading.menu.php"); ?>
            </td>
            <td align="right" width="30%">
                <font style="font-family: Tahoma; font-size: 22px; color: #557d1d">MADING SISWA</font>
            </td>
        </tr>           
        </table>
        <div id="content-pane-j" style="position: relative; overflow: auto; background-color: transparent;">
            &nbsp;
        </div>
    </div>
    <div id="#vtabs-content-c">
        <table border="0" cellpadding="2" cellspacing="2" width="100%">
        <tr>
            <td align="right">
                <font style="font-family: Tahoma; font-size: 22px; color: #557d1d">INFORMASI SISWA</font>
            </td></tr>           
        </table>
        <div id="content-pane-c" style="overflow: auto; background-color: transparent">
            &nbsp;
        </div>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr><td align="left">
            <a href="#" style="color:blue; font-weight:normal; text-decoration:underline;"
               onclick="is_Pengaturan()">Pengaturan</a>&nbsp;&nbsp;
        </td></tr>           
        </table>
    </div>
    <div id="#vtabs-content-i">
        <table border="0" cellpadding="2" cellspacing="2" width="100%">
        <tr>
            <td align="left" width="50%">
<?php              require_once("jadkal/jadkal.menu.php"); ?>
            </td>
            <td align="right" width="50%">
                <font style="font-family: Tahoma; font-size: 22px; color: #557d1d">JADWAL & KALENDER AKADEMIK</font>
            </td>
        </tr>           
        </table>
        <div id="content-pane-i" style="overflow: auto; background-color: transparent">
            &nbsp;
        </div>
    </div>
    <div id="#vtabs-content-k">
        <table border="0" cellpadding="2" cellspacing="2" width="100%">
        <tr>
            <td align="left" width="50%">
<?php              require_once("pustaka/pustaka.menu.php"); ?>
            </td>
            <td align="right" width="50%">
                <font style="font-family: Tahoma; font-size: 22px; color: #557d1d">PERPUSTAKAAN</font>
            </td>
        </tr>           
        </table>
        <div id="content-pane-k" style="overflow: auto; background-color: transparent">
            &nbsp;
        </div>
    </div>
    <div id="#vtabs-content-g">
        <table border="0" cellpadding="2" cellspacing="2" width="100%">
        <tr><td align="right">
            <font style="font-family: Tahoma; font-size: 22px; color: #557d1d">KEPEGAWAIAN</font>
        </td></tr>           
        </table>
        <div id="content-pane-g" style="overflow: auto; background-color: transparent">
            &nbsp;
        </div>
    </div>
    <div id="#vtabs-content-h">
        <table border="0" cellpadding="2" cellspacing="2" width="100%">
        <tr>
            <td align="left" width="80%">
<?php              require_once("psb/psb.menu.php"); ?>
            </td>
            <td align="right" width="20%">
                <font style="font-family: Tahoma; font-size: 22px; color: #557d1d">PSB</font>
            </td>
        </tr>           
        </table>
        <div id="content-pane-h" style="overflow: auto; background-color: transparent">
            &nbsp;
        </div>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr><td align="left">
            <a href="#" style="color:blue; font-weight:normal; text-decoration:underline;" onclick="psb_Konfigurasi()">Konfigurasi</a>
        </td></tr>           
        </table>
    </div>	
</div>
</div>