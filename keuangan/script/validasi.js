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

function validateEmptyText(elementId, elementName) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
		if (val.length == 0) {
			//alert ("woi");
		alert("Anda harus mengisikan nilai untuk "+elementName);
		document.getElementById(elementId).focus();
		return false;
	}
	return true;
}

function validateMaxText(elementId, maxLen, elementName) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	
	if (val.length > maxLen) {
		alert("Panjang untuk " + elementName + " tidak boleh melebihi " + maxLen + " karakter");
		document.getElementById(elementId).focus();
		return false;
	}
	return true;
}

function validateNumber(elementId, elementName) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	
	if (!vdIsNumber(val)) {
		alert(elementName + " harus berupa bilangan");
		document.getElementById(elementId).focus();
		return false;
	}
	return true;
}

function validateTgl(tgl,bln,th,tgl1,bln1,th1) {
	if (th > th1) {
		alert ('Pastikan batas tahun akhir tidak kurang dari batas tahun awal');
		return false;
	} 
	
	if (th == th1 && bln > bln1 ) {
		alert ('Pastikan batas bulan akhir tidak kurang dari batas bulan awal');
		return false; 
	}	
	
	if (th == th1 && bln == bln1 && tgl > tgl1 ) { 
		alert ('Pastikan batas tanggal akhir tidak kurang dari batas tanggal awal');
		return false;
	}	
	return true;
}