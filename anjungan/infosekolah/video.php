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
require_once("video.input.config.php");
require_once("video.list.config.php");

CreateVideoInputConfigJavaScript();
CreateVideoListConfigJavaScript();
?>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../script/lytebox.css"  />   
<link rel="stylesheet" type="text/css" href="../script/themes/south-street/jquery.ui.all.css"  />
<link rel="stylesheet" type="text/css" href="../script/flowplayer/skin/minimalist.css">
<link rel="stylesheet" type="text/css" href="infosekolah.css">
<link rel="stylesheet" type="text/css" href="video.css">
<link rel="stylesheet" type="text/css" href="video.list.css">
<link rel="stylesheet" type="text/css" href="video.view.css">
<script type="text/javascript" language="javascript" src='../script/jquery-latest.js'></script>
<script type="text/javascript" language="javascript" src="../script/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" language="javascript" src='../script/validator.js'></script>
<script type="text/javascript" language="javascript" src='../script/lytebox.js'></script>
<script type="text/javascript" language="javascript" src='../script/tools.js'></script>
<script type="text/javascript" language="javascript" src="../script/flowplayer/flowplayer.min.js"></script>
<script type="text/javascript" src="video.js"></script>
<script type="text/javascript" src="video.list.js"></script>
<script type="text/javascript" src="video.list.config.js"></script>
<script type="text/javascript" src="video.input.js"></script>
<script type="text/javascript" src="video.input.config.js"></script>
<script type="text/javascript" src="video.view.js"></script>
<script type="text/javascript" src="video.edit.js"></script>
<script type="text/javascript" src="video.index.js"></script>
<script type="text/javascript" src="infosekolah.common.func.js"></script>
<div id='vid_container' style="position: relative; width: 100%;">
    <div id="vidinp_content" style="position: absolute; width: 100%; background-color: #fff; visibility: hidden;">
<?php
    //require_once("video.input.php");
?>                                
    </div>
    <div id="vidvw_content" style="position: absolute; width: 100%; background-color: #fff; visibility: hidden;">
<?php
    //require_once("video.view.php");
?>                                
    </div>
    <div id="vided_content" style="position: absolute; width: 100%; background-color: #fff; visibility: hidden;">
<?php
    //require_once("video.edit.php");
?>                                
    </div>
    <div id="vidlst_content" style="position: absolute; width: 100%; background-color: #fff; visibility: visible;">
<?php
    require_once("video.list.php");
?>                                
    </div>
    <div id="vididx_content" style="position: absolute; width: 100%; background-color: #fff; visibility: hidden;">
<?php
    //require_once("video.index.php");
?>                                
    </div>       
</div>    