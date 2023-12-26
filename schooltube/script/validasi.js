// JavaScript Document
function vdtrim(inputString) 
{
	var returnString = '' + inputString + '';
	var removeChar = ' ';

	if (removeChar.length) 
	{
	   while('' + returnString.charAt(0) == removeChar) 
	   {
		   returnString = returnString.substring(1, returnString.length);
   	   }
   	   while('' + returnString.charAt(returnString.length - 1) == removeChar) 
	   {
  	       returnString = returnString.substring(0, returnString.length - 1);
       }
	}
	return returnString;
}

function vdIsNumber(input) 
{
    return (!isNaN(parseInt(input))) ? true : false;
}

function validateEmptyText(elementId, elementName) 
{
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	
	if (val.length == 0) 
	{
		alert("Anda harus mengisikan nilai untuk " + elementName);
		document.getElementById(elementId).focus();
		return false;
	}
	return true;
}

function validateValue(elementId, expectedValue, message) 
{
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	
	if (val != expectedValue) 
	{
		alert(message);
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

function vdIsInteger(input) {
	var digit = ['-','0','1','2','3','4','5','6','7','8','9'];
	
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


function vdIsFloat(input) {
	var digit = ['-','.','0','1','2','3','4','5','6','7','8','9'];
	
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


function validateInteger(elementId, elementName) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	
	if (!vdIsInteger(val)) {
		alert(elementName + " harus berupa bilangan integer");
		document.getElementById(elementId).focus();
		return false;
	}
	return true;
}

function validateDecimal(elementId, elementName) {
	var val = document.getElementById(elementId).value;
	val = vdtrim(val);
	
	if (!vdIsFloat(val)) {
		alert(elementName + " harus berupa bilangan riil");
		document.getElementById(elementId).focus();
		return false;
	}
	return true;
}


function validateChoice(elementId, elementName, choiceArr) {
	var input = document.getElementById(elementId).value;
	var inputlow = input.toLowerCase();
	
	var i = 0;
	var found = false;
	while ((i < choiceArr.length) && !found) {
		var choice = choiceArr[i];
		choice = choice.toLowerCase();
		
		found = (choice == inputlow);
		i++;
	}
	
	if (!found) {
		var choicestr = "";
		for (i = 0; i < choiceArr.length; i++) {
			if (choicestr.length > 0) choicestr = choicestr + ", ";
			choicestr = choicestr + "" + choiceArr[i];
		}
		alert("Nilai " + elementName + " harus salah satu dari pilihan ( " + choicestr + " )!" );
		document.getElementById(elementId).focus();
		return false;
	} else {
		return true;
	}
}

function validateRange(elementId, elementName, minRange, maxRange) {
	var input = document.getElementById(elementId).value;
	
	if (vdIsInteger(input)) {
		input = parseInt(input);
		minRange = parseInt(minRange);
		maxRange = parseInt(maxRange);
	} else if (vdIsFloat(input)) {
		input = parseFloat(input);
		minRange = parseFloat(minRange);
		maxRange = parseFloat(maxRange);
	} else {
		return false;
	}
			
	if ((input >= minRange) && (input <= maxRange)) {
		return true;
	} else {
		alert('Rentang nilai untuk ' + elementName + ' haruslah berada di antara ' + minRange + ' s/d ' + maxRange);
		document.getElementById(elementId).focus();		
		return false;
	}
}

function validateLength(elementId, elementName, len) {
	var input = document.getElementById(elementId).value;
	
	if (input.length != len) {
		alert('Panjang ' + elementName + ' haruslah ' + len + ' digit!');
		document.getElementById(elementId).focus();		
		return false;
	} else {
		return true;
	}
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