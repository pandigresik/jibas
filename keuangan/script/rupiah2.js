// JavaScript Document
// Last Update: 26 Agustus 2010

function rptrim(inputString) 
{
	var returnString = inputString + "";
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

function removeLeadingZero(number)
{
	if (number.length > 1)
	{
		while('' + number.charAt(0) == '0') 
		{
			number = number.substring(1, number.length);
		}
		
		if (number.length == 0)
			number = "0";
	}
	return number;
}

function numberToRupiah(number) 
{
	var original = number;
	number = rptrim(number);
	
	var positif = true;
	if (number.charAt(0) == '-')
	{
		positif = false;
		number = number.substring(1, number.length);
		number = rptrim(number);
	}
	
	number = removeLeadingZero(number);
	
	if (!rpIsNumber(number))
		return original;
	
	var result = "";
	if (number.length < 4)
	{
		result = "Rp " + number;
	}
	else
	{
		var count = 0;
		for(i = number.length - 1; i >= 0; i--) 
		{
			result = number.charAt(i) + result;
			count++;
			
			if ((count == 3) && (i > 0)) 
			{
				result = '.' + result;
				count = 0;
			}
		}
		result = "Rp " + result;
	}
	
	if (!positif)
		result = "(" + result + ")";
	
	return result;
}

function rupiahToNumber(rp) 
{
	var result = '';
	
	rp = rptrim(rp);
	var positif = true;
	var isvalid = true;
	if (rp.length > 0) 
	{
		if (rp.charAt(0) == "(")
		{
			positif = false;
			rp = rp.substring(1, rp.length);
			rp = rptrim(rp);
		}
		
		for (i = 0; isvalid && i < rp.length; i++)
		{
			var chr = rp.charAt(i);
			var asc = chr.charCodeAt(0);
			
			if (asc >= 48 && asc <= 57)
			{
				result = result + chr;
			}
			else
			{
				isvalid = (asc == 82 || asc == 114 || asc == 80 || asc == 112 || asc == 32 || asc == 46 || asc == 40 || asc == 41);
				//if (!isvalid)
					//alert(chr + " " + asc);
			}
		}
	}
	
	if (isvalid)
	{
		if (positif)
			return result;
		else
			return "-" + result;
	}
	else
	{
		return rp;
	}
}

function rpIsNumber(input) 
{
	var isnum = true;
	for (i = 0; isnum && i < input.length; i++)
	{
		var asc = input.charCodeAt(i);
		isnum = (asc >= 48 && asc <= 57);
	}
	
	return isnum;
	
	//return (!isNaN(parseInt(input))) ? true : false;
}

function formatRupiah(id) 
{
	var num = document.getElementById(id).value;	
	document.getElementById(id).value = numberToRupiah(num);
}

function unformatRupiah(id) 
{
	var num = document.getElementById(id).value;
	document.getElementById(id).value = rupiahToNumber(num);
}
