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
<title>Untitled-2</title>
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
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" <?php if ($middle=="1") { ?>onload="scrollMiddle()" <?php } else { ?> onLoad="scrollTop()"  <?php } ?>>
<!-- ImageReady Slices (Untitled-2) -->

<table width="100%" border="0">
  <tr>
    <td><p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana" color="Gray"><strong>KALENDER AKADEMIK</strong></font></p></td>
  </tr>
  <tr>
    <td><br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="akademik/jadwal/kalender_footer.php" onMouseOver="showhint('Pendataan Kalender Akademik', this, event, '100px')"><img src="images/kalender_07.jpg" alt="" width="126" height="129" border="0"></a>    </td>
  </tr>
</table>
    </td>
  </tr>
</table>
<div style="right:5px; bottom:5px; position:absolute;" align="right">
<script type="text/javascript" language="JavaScript1.2">
<!--
stm_bm(["menu6126",650,"","blank.gif",0,"","",0,0,250,0,500,1,0,0,"","",0,0,1,2,"default","hand",""],this);
stm_bp("p0",[1,4,0,0,0,2,0,0,100,"",-2,"",-2,90,0,0,"#000000","transparent","",3,0,0,"#000000"]);
stm_ai("p0i0",[0,"Themes","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,1,1,"#CC9999",1,"#CC6666",1,"script/button1.gif","script/button2.gif",3,3,0,0,"#000000","#000000","#CCCCCC","#E0FF7D","bold 8pt Arial","bold 8pt Arial",0,0],146,21);
stm_bpx("p1","p0",[1,2,2,0,0,0,25,0,90,"progid:DXImageTransform.Microsoft.Wipe(GradientSize=1.0,wipeStyle=0,motion=forward,enabled=0,Duration=0.83)",6,"stEffect(\"slip\")",-2,27]);
stm_ai("p1i0",[6,10,"#000000","",-1,-1,0]);
stm_aix("p1i1","p0i0",[0,"Green","","",-1,-1,0,"javascript:change_theme('1')","_self","","","","",25,0,0,"","",0,0,0,0,1,"#3D3D3D",1,"#66CC33",1,"script/button1.gif","script/button2.gif",3,3,0,0,"#CCCC00","#CCCC00"],146,21);
stm_aix("p1i2","p1i1",[0,"Pink","","",-1,-1,0,"javascript:change_theme('2')"],146,21);
stm_aix("p1i3","p1i1",[0,"Casual","","",-1,-1,0,"javascript:change_theme('3')"],146,21);
stm_aix("p1i4","p1i1",[0,"Apple","","",-1,-1,0,"javascript:change_theme('4')"],146,21);
stm_aix("p1i5","p1i1",[0,"Vista","","",-1,-1,0,"javascript:change_theme('5')"],146,21);
stm_aix("p1i6","p1i1",[0,"Coffee","","",-1,-1,0,"javascript:change_theme('6')"],146,21);
stm_aix("p1i7","p1i1",[0,"Wood","","",-1,-1,0,"javascript:change_theme('7')"],146,21);
stm_aix("p1i8","p1i1",[0,"Gold","","",-1,-1,0,"javascript:change_theme('8')"],146,21);
stm_aix("p1i9","p1i1",[0,"Granite","","",-1,-1,0,"javascript:change_theme('9')"],146,21);
stm_aix("p1i10","p1i0",[]);
stm_ep();
stm_ep();
stm_sc(1,["transparent","transparent","","",3,3,0,0,"#FFFFF7","#000000","script/up_disabled.gif","script/up_enabled.gif",7,9,0,"script/down_disabled.gif","script/down_enabled.gif",7,9,0,0,200]);
stm_em();
//-->
</script>
</div>
<!-- End ImageReady Slices -->
</body>
</html>