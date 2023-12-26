// JavaScript Document
function rptrim(inputString) {
	var returnString = '' + inputString + '';
	var removeChar = ' ';

	if (removeChar.length > 0) {
	   while('' + returnString.charAt(0) == removeChar) {
		   returnString = returnString.substring(1, returnString.length);
   	   }
   	   while('' + returnString.charAt(returnString.length - 1) == removeChar) {
  	       returnString = returnString.substring(0, returnString.length - 1);
       }
	}
	return returnString;
}

function numberToRupiah(num) {
	var result = '';
	
	var negatif = false;
	if (num < 0) {
		negatif = true;
		num = -1 * num;
	}
		
	num = rptrim(num);
	if (num.length > 0) {
		var count = 0;
		for(i = num.length - 1; i >= 0; i--) {
			result = num.charAt(i) + result;
			count++;
			if ((count == 3) && (i > 0)) {
				result = '.' + result;
				count = 0;
			}
		}
		result = 'Rp ' + result;
	}
	
	if (negatif)
		result = '(' + result + ')';
	
	return result;
}

function rupiahToNumber(rp) {
	var result = '';
	rp = rptrim(rp);
	
	negatif = (rp.indexOf('(') >= 0);
	
	if (rp.length > 0) {
		for (i = 0; i < rp.length; i++)
			if (rpIsNumber(rp.charAt(i))) 
				result = result + rp.charAt(i);
	}
	
	if (negatif)
		return '-' + result;
	
	return result;
}

function rpIsNumber(input) {
  return (!isNaN(parseInt(input))) ? true : false;
}

function formatRupiah(id) {
	var num = document.getElementById(id).value;
	if (rpIsNumber(num))
		document.getElementById(id).value = numberToRupiah(num);
	document.getElementById(id).style.background = "#FFFFFF";	
      
}
function formatRupiahbg(id,id2) {
	var num = document.getElementById(id).value;
	//document.getElementById(id2).value=num;
	if (rpIsNumber(num))
		document.getElementById(id).value = numberToRupiah(num);
		document.getElementById(id2).value = rupiahToNumber(num);
}

function unformatRupiah(id) {
	var num = document.getElementById(id).value;
	document.getElementById(id).value = rupiahToNumber(num);
	document.getElementById(id).style.background = "#66FFFF";	
}

