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
include('sessionchecker.php');
require_once('include/sessioninfo.php');
$middle="0";
if (isset($_REQUEST['flag'])){
    $middle="1";
} else {
    $middle="0";
}
?>
<html>
<head>
    <title>Penilaian</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="style/tooltips.css">
    <script type="text/javascript" src="script/tooltips.js"></script>
    <script type="text/javascript">
        function get_fresh(){
            document.location.reload();
        }
        function change_theme(theme){
            parent.topcenter.location.href="topcenter.php?theme="+theme;
            parent.topleft.location.href="topleft.php?theme="+theme;
            parent.topright.location.href="topright.php?theme="+theme;
            parent.midleft.location.href="midleft.php?theme="+theme;
            get_fresh();
            parent.midright.location.href="midright.php?theme="+theme;
            parent.bottomleft.location.href="bottomleft.php?theme="+theme;
            parent.bottomcenter.location.href="bottomcenter.php?theme="+theme;
            parent.bottomright.location.href="bottomright.php?theme="+theme;
        }
        function scrollMiddle() {
            var myWidth = 0, myHeight = 0;

            if( typeof( window.innerWidth ) == 'number' ) {
                //Non-IE
                myWidth = window.innerWidth;
                myHeight = window.innerHeight;
            } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
                //IE 6+ in 'standards compliant mode'
                myWidth = document.documentElement.clientWidth;
                myHeight = document.documentElement.clientHeight;
            } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
                //IE 4 compatible
                myWidth = document.body.clientWidth;
                myHeight = document.body.clientHeight;
            }

            myHeight = myHeight / 0.5;
            window.scrollTo(myWidth, myHeight);
        }

        function scrollTop() {
            window.scrollTo(0, 0);
        }
    </script>
    <link href="style/style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" <?php if ($middle=="1") { ?>onload="scrollMiddle()" <?php } else { ?> onLoad="scrollTop()"  <?php } ?>>
<table width="100%" border="0">
    <tr>
        <td><p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Arial" color="Gray"><strong>EKSPOR &amp; IMPOR </strong></font></p></td>
    </tr>
    <tr>
        <td>
            <?php if (SI_USER_LEVEL()!=0) { ?>
            <br><br>
            <table border="0" cellpadding="10">
                <tr>
                    <td width="10">&nbsp;</td>
                    <td width="40">
                        <a href="penilaian/expnilai.php" style="text-decoration:none" onmouseover="showhint('Ekspor Form Nilai', this, event, '100px');">
                            <img src="images/EksporNilai.png">
                        </a>
                    </td>
                    <td width="200">
                        <a href="penilaian/expnilai.php" style="text-decoration:none" onmouseover="showhint('Ekspor Form Nilai', this, event, '100px');">
                            <strong>Ekspor Form Nilai</strong><br>
                            <font style="font-weight: normal"><i>Dokumen Excel berisi form pengisian data nilai per kelas</i></font>
                        </a>
                    </td>
                    <td width="20">&nbsp;</td>
                    <td width="40">
                        <a href="penilaian/impnilai.php" style="text-decoration:none" onmouseover="showhint('Impor Data Nilai', this, event, '100px');">
                            <img src="images/ImporNilai.png">
                        </a>
                    </td>
                    <td width="200">
                        <a href="penilaian/impnilai.php" style="text-decoration:none" onmouseover="showhint('Impor Data Nilai', this, event, '100px');">
                            <strong>Impor Nilai</strong><br>
                            <font style="font-weight: normal"><i>Impor data nilai dari form Excel</i></font>
                        </a>
                    </td>
                </tr>
            </table>
            <?php } ?>
        </td>
    </tr>
</table>

<!-- ImageReady Slices (Penilaian.psd) -->

<!-- End ImageReady Slices -->
</body>
</html>