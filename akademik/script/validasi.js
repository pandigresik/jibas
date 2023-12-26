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
		return false;
	}
	return true;
}

function validateNumber(elementId, elementName) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	if (!vdIsNumber(val)) {
		alert(elementName + " harus berupa bilangan ");
		//document.getElementById(elementId).value="";
		document.getElementById(elementId).focus();
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



function validateEmail(elementId) {	
	var val = document.getElementById(elementId).value;
	return val == '' || /^[a-z0-9_+.-]+\@([a-z0-9-]+\.)+[a-z0-9]{2,4}$/i.test( val );
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

