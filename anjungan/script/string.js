// JavaScript Document
function trim(inputString) {
	var returnString = '' + inputString + '';
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

function isInteger(input) {
	var digit = ['0','1','2','3','4','5','6','7','8','9'];
	
	var i = 0;
	var isInt = true;	
	while ((i < input.length) && isInt) {
		var ch = input.charAt(i);
		
		var j = 0;
		var found = false;
		while ((j < digit.length) && !found) {
			found = (digit[j] == ch);
			j++;
		}
		
		isInt = found;
		i++;
	}
	
	return isInt;
}

function isNumber(input) {
  return (!isNaN(parseInt(input))) ? true : false;
}