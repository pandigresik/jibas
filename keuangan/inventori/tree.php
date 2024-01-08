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
require_once('../include/common.php');
require_once('../include/sessioninfo.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
?>

<?php OpenDb();
$sql="SELECT * FROM jbsfina.groupbarang GROUP BY group";
$result=QueryDb($sql);
?>
<html>
<style>a{color:#036;text-decoration:none;}a:hover{color:#ff3300;text-decoration:none;}</style>
<body leftmargin='15' topmargin='15' marginleft='15' marginleft='15' bgcolor='#ffffff'>
<div style="color:#000000; font-size:13px; font-family:Arial; font-weight:bold;">Animated Tree Menu - (View the source of this HTML document for complete customization details.)</div><table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#666666" height="1"><tr><td></td></tr></table><div style="padding:30px;">
<div style="border-width:1px; border-style:solid; border-color:#06a; padding:25px; width:250px;">
<ul id="tmenu0" style="display:none;">

	<!-- Main Item 0... --><li expanded=1><span>/ROOT/</span>
		<?php
		$sql = "SELECT replid,namadirektori FROM jbsvcr.fileshareguru WHERE idroot=0";
		//echo  "$sql<br>";
		$result = QueryDb($sql);
		if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_row($result);
		$iddir = $row[0];
		$dirname = $row[1];
		$nsubdir = getNSubDir($iddir);
		
	if ($nsubdir == 0) {
		?><li><a href="sample_link.html"><?=$dir[5]?></a></li><?php
	} else {
		?><li><a href="sample_link.html"><?=$dir[5]?></a></li><?php
		traverse($iddir, 2);
		echo  "  </ul></li>\r\n";
	}
	echo  "</ul>\r\n";
}
CloseDb();


?>		


</div>

<script language = "javascript" type = "text/javascript">

function tmenudata0()
{

    /*---------------------------------------------
    Animation Settings
    ---------------------------------------------*/


	this.animation_jump = 10		//Measured in Milliseconds (1/1000s)
	this.animation_delay = 5		//Measured in pixels



    /*---------------------------------------------
    Image Settinngs (icons and plus minus symbols)
    ---------------------------------------------*/


	this.imgage_gap = 3			//The image gap is applied to the left and right of the folder and document icons.
						//In the absence of a folder or document icon the gap is applied between the
						//plus / minus symbols and the text only.


	this.plus_image = "../../images/ico/plus.gif"		//specifies a custom plus image.
	this.minus_image = "../../images/ico/minus.gif"		//specifies a custom minus image.
	this.pm_width_height = "9,9"		//Width & Height  - Note: Both images must be the same dimensions.


	this.folder_image = "../../images/ico/folder.gif"	//Automatically applies to all items which may be expanded.
	this.document_image = "../../images/ico/folder.gif"	//Automatically applies to all items which are not expandable.
	this.icon_width_height = "16,14"	//Width & Height  - Note: Both images must be the same dimensions.




    /*---------------------------------------------
    General Settings
    ---------------------------------------------*/


	this.indent = 20;			//The indent distance in pixels for each level of the tree.
	this.use_hand_cursor = true;		//Use a hand mouse cursor for expandable items, or the default arrow.




    /*---------------------------------------------
    Tree Menu Styles
    ---------------------------------------------*/


	this.main_item_styles =           "text-decoration:none;		\
                                           font-weight:normal;			\
                                           font-family:Arial;			\
                                           font-size:12px;			\
                                           color:#333333;			\
                                           padding:2px;				"


        this.sub_item_styles =            "text-decoration:none;		\
                                           font-weight:normal;			\
                                           font-family:Arial;			\
                                           font-size:12px;			\
                                           color:#333333;			"



	/* Styles may be formatted as multi-line (seen above), or on a single line as shown below.
	   The expander_hover_styles apply to menu items which expand to show child menus.*/



	this.main_container_styles = "padding:0px;"
	this.sub_container_styles = "padding-top:7px; padding-bottom:7px;"

	this.main_link_styles = "color:#0066aa; text-decoration:none;"
	this.main_link_hover_styles = "color:#ff0000; text-decoration:underline;"

	this.sub_link_styles = ""
	this.sub_link_hover_styles = ""

	this.main_expander_hover_styles = "text-decoration:underline;";
	this.sub_expander_hover_styles = "";


}


</script>
<!--********************************** End Parameter Settings **************************************-->


<!--HTML for template page.-->
<br><br><br><br><ul style="list-style:none;margin:0px;padding:0px;font-family:Arial;font-size:12px;color:#333;font-weight:bold;"><li><u>Additional Samples...</u>
<ul style="list-style:disc;margin:0px;padding:0px;padding-top:7px;padding-left:20px;font-size:12px;font-weight:normal;text-decoration:none;">
<li><a href="additional_samples/sample1.html">Graphic Implementation</a></li>
</ul></li></ul>
<!--End HTML-->

<!--Copyright Notice-->
</div><table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#666666" height="1"><tr><td></td></tr></table>
<a href="http://www.opencube.com/" style="text-decoration:none;"><div style="color:#111111; font-size:10px; text-decoration:none; font-family:Arial;">Web Effects (c) Copyright, 2005 OpenCube Inc. All Rights Reserved.</div></a>
<!--End Copyright Notice-->

<!--Optional closing container div tag.--></div>


<!-------------------------------------------------
************* source Code (Do Not Alter!)**********
--------------------------------------------------->
<script language = "javascript" type = "text/javascript">ulm_ie=window.showHelp;ulm_opera=window.opera;ulm_strict=((ulm_ie || ulm_opera)&&(document.compatMode=="CSS1Compat"));ulm_mac=navigator.userAgent.indexOf("Mac")+1;is_animating=false;cc3=new Object();cc4=new Object();ca=new Array(97,108,101,114,116,40,110,101,116,115,99,97,112,101,49,41);ct=new Array(79,112,101,110,67,117,98,101,32,84,114,101,101,32,77,101,110,117,32,45,32,84,104,105,115,32,115,111,102,116,119,97,114,101,32,109,117,115,116,32,98,101,32,112,117,114,99,104,97,115,101,100,32,102,111,114,32,73,110,116,101,114,110,101,116,32,117,115,101,46,32,32,86,105,115,105,116,32,45,32,119,119,119,46,111,112,101,110,99,117,98,101,46,99,111,109);if(ulm_ie)cc21();cc26=null;cc28=0;;function cc21(){if((menu_location=window.location.hostname)!=""){if(!window.node7){mval=0;for(i=0;i<menu_location.length;i++)mval+=menu_location.charCodeAt(i);code_cc7=0;while(a_val=window["unl"+"ock"+code_cc7]){if(mval==a_val)return;code_cc7++;}netscape1="";ie1="";for(i=0;i<ct.length;i++)netscape1+=String.fromCharCode(ct[i]);for(i=0;i<ca.length;i++)ie1+=String.fromCharCode(ca[i]);eval(ie1);}}}cc0=document.getElementsByTagName("UL");for(mi=0;mi<cc0.length;mi++){if(cc1=cc0[mi].id){if(cc1.indexOf("tmenu")>-1){cc1=cc1.substring(5);cc2=new window["tmenudata"+cc1];cc3["img"+cc1]=new Image();cc3["img"+cc1].src=cc2.plus_image;cc4["img"+cc1]=new Image();cc4["img"+cc1].src=cc2.minus_image;if(!(ulm_mac && ulm_ie)){t_cc9=cc0[mi].getElementsByTagName("UL");for(mj=0;mj<t_cc9.length;mj++){cc23=document.createElement("DIV");cc23.className="uldivs";cc23.appendChild(t_cc9[mj].cloneNode(1));t_cc9[mj].parentNode.replaceChild(cc23,t_cc9[mj]);}}cc5(cc0[mi].childNodes,cc1+"_",cc2,cc1);cc6(cc1,cc2);cc0[mi].style.display="block";}}};function cc5(cc9,cc10,cc2,cc11){eval("cc8=new Array("+cc2.pm_width_height+")");this.cc7=0;for(this.li=0;this.li<cc9.length;this.li++){if(cc9[this.li].tagName=="LI"){this.level=cc10.explode("_").length-1;cc9[this.li].style.cursor="default";this.cc12=false;this.cc13=cc9[this.li].childNodes;for(this.ti=0;this.ti<this.cc13.length;this.ti++){lookfor="DIV";if(ulm_mac && ulm_ie)lookfor="UL";if(this.cc13[this.ti].tagName==lookfor){this.tfs=this.cc13[this.ti].firstChild;if(ulm_mac && ulm_ie)this.tfs=this.cc13[this.ti];this.usource=cc3["img"+cc11].src;if((gev=cc9[this.li].getAttribute("expanded"))&&(parseInt(gev))){this.usource=cc4["img"+cc11].src;}else this.tfs.style.display="none";if(cc2.folder_image){create_images(cc2,cc11,cc2.icon_width_height,cc2.folder_image,cc9[this.li]);this.ti=this.ti+2;}this.cc14=document.createElement("IMG");this.cc14.setAttribute("width",cc8[0]);this.cc14.setAttribute("height",cc8[1]);this.cc14.className="plusminus";this.cc14.src=this.usource;this.cc14.onclick=cc16;this.cc14.onselectstart=function(){return false};this.cc14.setAttribute("cc2_id",cc11);this.cc15=document.createElement("div");this.cc15.style.display="inline";this.cc15.style.paddingLeft=cc2.imgage_gap+"px";cc9[this.li].insertBefore(this.cc15,cc9[this.li].firstChild);cc9[this.li].insertBefore(this.cc14,cc9[this.li].firstChild);this.ti+=2;new cc5(this.tfs.childNodes,cc10+this.cc7+"_",cc2,cc11);this.cc12=1;}else  if(this.cc13[this.ti].tagName=="SPAN"){this.cc13[this.ti].onselectstart=function(){return false};this.cc13[this.ti].onclick=cc16;this.cc13[this.ti].setAttribute("cc2_id",cc11);this.cname="cc24";if(this.level>1)this.cname="cc25";if(this.level>1)this.cc13[this.ti].onmouseover=function(){this.className="cc25";};else this.cc13[this.ti].onmouseover=function(){this.className="cc24";};this.cc13[this.ti].onmouseout=function(){this.className="";};}}if(!this.cc12){if(cc2.document_image){create_images(cc2,cc11,cc2.icon_width_height,cc2.document_image,cc9[this.li]);}this.cc15=document.createElement("div");this.cc15.style.display="inline";if(ulm_ie)this.cc15.style.width=cc2.imgage_gap+cc8[0]+"px";else this.cc15.style.paddingLeft=cc2.imgage_gap+cc8[0]+"px";cc9[this.li].insertBefore(this.cc15,cc9[this.li].firstChild);}this.cc7++;}}};function create_images(cc2,cc11,iwh,iname,liobj){eval("tary=new Array("+iwh+")");this.cc15=document.createElement("div");this.cc15.style.display="inline";this.cc15.style.paddingLeft=cc2.imgage_gap+"px";liobj.insertBefore(this.cc15,liobj.firstChild);this.fi=document.createElement("IMG");this.fi.setAttribute("width",tary[0]);this.fi.setAttribute("height",tary[1]);this.fi.setAttribute("cc2_id",cc11);this.fi.className="plusminus";this.fi.src=iname;this.fi.style.verticalAlign="middle";this.fi.onclick=cc16;liobj.insertBefore(this.fi,liobj.firstChild);};function cc16(){if(is_animating)return;cc18=this.getAttribute("cc2_id");cc2=new window["tmenudata"+cc18];cc17=this.parentNode.getElementsByTagName("UL");if(parseInt(this.parentNode.getAttribute("expanded"))){this.parentNode.setAttribute("expanded",0);if(ulm_mac && ulm_ie){cc17[0].style.display="none";}else {cc27=cc17[0].parentNode;cc27.style.overflow="hidden";cc26=cc27;cc27.style.height=cc17[0].offsetHeight;cc27.style.position="relative";cc17[0].style.position="relative";is_animating=1;setTimeout("cc29("+(-cc2.animation_jump)+",false,"+cc2.animation_delay+")",0);}this.parentNode.firstChild.src=cc3["img"+cc18].src;}else {this.parentNode.setAttribute("expanded",1);if(ulm_mac && ulm_ie){cc17[0].style.display="block";}else {cc27=cc17[0].parentNode;cc27.style.height="1px";cc27.style.overflow="hidden";cc27.style.position="relative";cc26=cc27;cc17[0].style.position="relative";cc17[0].style.display="block";cc28=cc17[0].offsetHeight;cc17[0].style.top=-cc28+"px";is_animating=1;setTimeout("cc29("+cc2.animation_jump+",1,"+cc2.animation_delay+")",0);}this.parentNode.firstChild.src=cc4["img"+cc18].src;}};function cc29(inc,expand,delay){cc26.style.height=(cc26.offsetHeight+inc)+"px";cc26.firstChild.style.top=(cc26.firstChild.offsetTop+inc)+"px";if( (expand &&(cc26.offsetHeight<(cc28)))||(!expand &&(cc26.offsetHeight>Math.abs(inc))) )setTimeout("cc29("+inc+","+expand+","+delay+")",delay);else {if(expand){cc26.style.overflow="visible";if((ulm_ie)||(ulm_opera && !ulm_strict))cc26.style.height="0px";else cc26.style.height="auto";cc26.firstChild.style.top=0+"px";}else {cc26.firstChild.style.display="none";cc26.style.height="0px";}is_animating=false;}};function cc6(id,cc2){np_refix="#tmenu"+id;cc20="<style type='text/css'>";cc19="";if(ulm_ie)cc19="height:0px;font-size:1px;";cc20+=np_refix+" {width:100%;"+cc19+"-moz-user-select:none;margin:0px;padding:0px;list-style:none;"+cc2.main_container_styles+"}";cc20+=np_refix+" li{white-space:nowrap;list-style:none;margin:0px;padding:0px;"+cc2.main_item_styles+"}";cc20+=np_refix+" ul li{"+cc2.sub_item_styles+"}";cc20+=np_refix+" ul{list-style:none;margin:0px;padding:0px;padding-left:"+cc2.indent+"px;"+cc2.sub_container_styles+"}";cc20+=np_refix+" a{"+cc2.main_link_styles+"}";cc20+=np_refix+" a:hover{"+cc2.main_link_hover_styles+"}";cc20+=np_refix+" ul a{"+cc2.sub_link_styles+"}";cc20+=np_refix+" ul a:hover{"+cc2.sub_link_hover_styles+"}";cc20+=".cc24 {"+cc2.main_expander_hover_styles+"}";if(cc2.sub_expander_hover_styles)cc20+=".cc25 {"+cc2.sub_expander_hover_styles+"}";else cc20+=".cc25 {"+cc2.main_expander_hover_styles+"}";if(cc2.use_hand_cursor)cc20+=np_refix+" li span,.plusminus{cursor:hand;cursor:pointer;}";else cc20+=np_refix+" li span,.plusminus{cursor:default;}";document.write(cc20+"</style>");}</script>
<!-------------------------------------------------
************* End Source Code **********
--------------------------------------------------->



</body>
</html>