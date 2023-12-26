// JavaScript Document
function vdtrim(inputString) {
	var returnString = inputString;
	var removeChar = ' ';

	if (removeChar.length) {
	   while('' + returnString.charAt(0) == removeChar) {
		   returnString = returnString.substring(1, returnString.length);
   	   }
   	   while('' + returnString.charAt(returnString.length - 1) == removeChar) {
  	       returnString = returnString.substring(0, returnString.length - 1);
       }
	}
	return returnString;
}

function vdIsNumber(input) {
    return (!isNaN(parseInt(input))) ? true : false;
}

function vdIsDecimal(input) {
  return (!isNaN(parseFloat(input))) ? true : false;  
}

function validateEmptyText(elementId, elementName) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	
	if (val.length == 0) {
		alert("Anda harus mengisikan data untuk " + elementName);
		document.getElementById(elementId).focus();
		//document.getElementById(elementId).style.background='#fa9e9e';
		return false;
	}
	return true;
}

function validateEmptyCombo(elementId) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	if (val.length == 0) {
		alert("Tidak dapat menyimpan siswa karena tidak ada kelas terpilih");
		document.getElementById(elementId).focus();
		return false;
	}
	return true;
}




/*function valNotEmpty(elementId, elemenName){
	var val=document.getElementById(elementId).value;
	val = vdtrim(val);
	if (val.length>0)
	{
		
		if (!vdIsNumber(val)) {
		alert(elementName + " harus berupa bilangan ");
		document.getElementById(elementId).focus();
		return false;
	}
	return true;
	}
}
*/
function validateMaxText(elementId, maxLen, elementName) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	
	if (val.length > maxLen) {
		alert("Panjang data untuk " + elementName + " tidak boleh melebihi " + maxLen + " karakter");
		document.getElementById(elementId).focus();
		//document.getElementById(elementId).style.background='#fa9e9e';
		return false;
	}
	return true;
}

function validateNumber(elementId, elementName) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	if (!vdIsNumber(val)) {
		alert(elementName + " harus berupa bilangan ");
		document.getElementById(elementId).value=0;
		document.getElementById(elementId).focus();
		//document.getElementById(elementId).style.background='#fa9e9e';
		return false;
	}
	return true;
}

function validateDecimal(elementId, elementName) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	
	if (vdIsDecimal(val)) {
		alert("Desimal " + elementName + " harus berupa titik ");
		document.getElementById(elementId).focus();
		//document.getElementById(elementId).style.background='#fa9e9e';
		return false;
	}
	
	return true;
}

function validateString(elementId, elementName) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	
	if (vdIsNumber(val)) {
		alert(elementName + " harus berupa huruf ");
		document.getElementById(elementId).focus();
		//document.getElementById(elementId).style.background='#fa9e9e';
		return false;
	}
	return true;
}

function validateEmptyTextMCE(elementId, elementName) {
	var val = tinyMCE.get(elementId).getContent()
	val = vdtrim(val);
	
	if (val.length == 0) {
		alert("Anda harus mengisikan teks untuk " + elementName);
		document.getElementById(elementId).focus();
		return false;
	}
	return true;
}
/*
function validateEmail(elementId) {		
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	for (i = 0; i < val.length; i++) {
		if (val.charAt(i) == "@") 			
			return true;
		} else { 
			alert("Alamat email tidak lengkap");
			document.getElementById(elementId).focus();
			return false;
		}
			
	}
	
}*/
	