/* AJAX */

function newXMLRequest() {
	var xml = false;

	if(window.XMLHttpRequest) {
		xml = new XMLHttpRequest();
	} else if(window.ActiveXObject) {
		try {
			xml = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e1) {
			try {
				xml = new AxtiveXObject("Microsoft.XMLHTTP");
			} catch(e2) {}
		}
	}

	return xml;
}


function getReadyStateHandlerText(req, responseHandler) {
	return function() {
		if(req.readyState == 4) {
			if(req.status == 200) {
				responseHandler(req.responseText);
			} else {
				if (req.status==404)
				{
					alert("HTTP Error " + req.status + ":\n Tidak ditemukan halaman yang dituju");
				}
				else
				{
				alert("HTTP Error " + req.status + ": " + req.statusText + "\n" + req.responseText);
				}
			}
		}
	}
}

function sendRequestText(url, handler, cmd) {
	request = newXMLRequest();
	request.onreadystatechange = getReadyStateHandlerText(request, handler);
	request.open("POST", url, true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send(cmd);
}
function leftTrim(sString){
	while (sString.substring(0,1) == ' '){
		sString = sString.substring(1, sString.length);
	}
	return sString;
}
function rightTrim(sString){
	while (sString.substring(sString.length-1, sString.length) == ' '){
		sString = sString.substring(0,sString.length-1);
	}
	return sString;
}
function trimAll(sString){
	while (sString.substring(0,1) == ' '){
		sString = sString.substring(1, sString.length);
	}
	while (sString.substring(sString.length-1, sString.length) == ' '){
		sString = sString.substring(0,sString.length-1);
	}
	return sString;
}

