// JavaScript Document
var win = null;
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

function getOSType()
{
	var OSType = "UNKNOWN";
	
	if (navigator.appVersion.indexOf("Win") != -1) OSType = "WIN";
	if (navigator.appVersion.indexOf("Mac") != -1) OSType = "MAC";
	if (navigator.appVersion.indexOf("X11") != -1) OSType = "UNIX";
	if (navigator.appVersion.indexOf("Linux") != -1) OSType = "UNIX";
	
	return OSType;
}

function getOSSlash()
{
	var OSSlash = "\\"; // default WIN
	if (getOSType() == "UNIX")
		OSSlash = "/";
	
	return OSSlash;
}

function urldecode(url) {
    return decodeURIComponent(url.replace(/\+/g, ' '));
}