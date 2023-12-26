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
				alert("HTTP Error " + req.status + ": " + req.statusText + "\n" + req.responseText);
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


