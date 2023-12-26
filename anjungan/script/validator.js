Validator = function() { }

Validator.FocusError = function(object)
{
	object.focus();
	object.addClass('ui-state-error');
	
	setTimeout(function(){
		object.removeClass('ui-state-error');
	}, 4000);
}

Validator.CheckNumber = function(object, name)
{
	if ($.trim(object.val()).length == 0)
		return true;
	
	if ( isNaN(object.val()) )
	{
		Validator.FocusError(object);
		alert("Nilai " + name + " harus angka!");
		
		return false;
	}
	return true;
}

Validator.CheckLength = function(object, name, min, max) {
	
	if ( object.val().length > max || object.val().length < min )
	{
		Validator.FocusError(object);
		
		if (min != max)
			alert("Panjang " + name + " harus di antara " + min + " dan " + max + " karakter");
		else
			alert("Panjang " + name + " harus " + min + " karakter");
			
		return false;
	}
	else
	{
		return true;
	}
}

Validator.CheckInteger = function(object, name, min, max) {
	
	if ( parseInt(object.val()) > max || parseInt(object.val()) < min )
	{
		Validator.FocusError(object);
		
		if (min != max)
			alert("Nilai " + name + " harus di antara " + min + " dan " + max);
		else
			alert("Nilai " + name + " harus " + min);
			
		return false;
	}
	else
	{
		return true;
	}
	
}

Validator.CompareValue = function(object1, name1, object2, name2, operator) {
	
	var val1 = object1.val();
	var val2 = object2.val();
	var test = false;
	var info = "";
	
	if (operator == "==")
	{
		test = val1 == val2;
		info = "sama dengan";
	}
	else if (operator == "<")
	{
		test = val1 < val2;
		info = "lebih kecil";
	}
	else if (operator == "<=")
	{
		test = val1 <= val2;
		info = "lebih kecil atau sama dengan";
	}
	else if (operator == ">")
	{
		test = val1 > val2;
		info = "lebih besar";
	}
	else if (operator == ">=")
	{
		test = val1 >= val2;
		info = "lebih besar atau sama dengan";
	}
	else if (operator == "!=")
	{
		test = val1 != val2;
		info = "tidak sama dengan";
	}
	
	if (!test)
	{
		Validator.FocusError(object1);
		
		alert("Nilai " + name1 + " harus " + info + " " + name2);
		return false;
	}
	
	return true;
}

Validator.CheckFloat = function(object, name, min, max) {
	
	if ( parseFloat(object.val()) > max || parseFloat(object.val()) < min )
	{
		Validator.FocusError(object);
		
		if (min != max)
			alert("Nilai " + name + " harus di antara " + min + " dan " + max);
		else
			alert("Nilai " + name + " harus " + min);
			
		return false;
	}
	else
	{
		return true;
	}
	
}

Validator.CheckRegexp = function(object, tips, regexp) 
{
	if ( !regexp.test(object.val()) )
	{
		Validator.FocusError(object);
		alert(tips);
		
		return false;
	}
	else
	{
		return true;
	}
}

Validator.CheckEmail = function(object)
{
	if ($.trim(object.val()).length == 0)
		return true;
	
	return Validator.CheckRegexp(object,
								 "eg: yourname@email.com",
								 /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);		
}

Validator.CheckFileExtension = function(object, tips, extension)
{
	var value = $.trim(object.val());
	if (value.length == 0)
	{
		Validator.FocusError(object);
		alert(tips);
		
	    return false;
	}
	
	var ext = value.substring(value.lastIndexOf('.') + 1).toLowerCase();
	var found = false;
	for(i = 0; i < extension.length && !found; i++)
	{
		found = extension[i] == ext;
	}
	
	if (!found)
	{
		Validator.FocusError(object);
		alert(tips);
		
		return false;
	}
	
	return true;  
}

Validator.CheckImageExtension = function(object)
{
	var imageExt = ['jpg', 'jpeg', 'gif', 'bmp', 'png'];	
	return Validator.CheckFileExtension(object, "Ekstensi file gambar harus .jpg .jpeg .gif .bmp .png", imageExt);
}

Validator.CheckYear = function(object, name) {
	
	return Validator.CheckLength(object, name, 4, 4) &&
		   Validator.CheckNumber(object) &&
		   Validator.CheckInteger(object, 1970, 2999);
		   
}

Validator.CheckMonth = function(object, name) {
	
	return Validator.CheckLength(object, name, 1, 2) &&
		   Validator.CheckNumber(object) &&
		   Validator.CheckInteger(object, 1, 12);
		   
}

Validator.CheckDay = function(object, name, year, month) {
	
	var day = object.val();
	var nday = Validator.GetDayInYearMonth(year, month);
	
	if (day < 1 || day > nday)
	{
		Validator.FocusError(object);
		alert("Tanggal " + name + " harus diantara 1 s/d " + nday);
		
		return false;
	}
	
	return true;
}

Validator.GetDayInYearMonth = function(year, month) {
	
	switch(parseInt(month))
	{
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			return 31;
			break;
		case 4:
		case 6:
		case 9:
		case 11:
			return 30;
			break;
		default: //case 2
			if ((Math.abs(year - 2000) % 4) == 0)
				return 29;
			else
				return 28;
	}
	
}

Validator.CheckHour = function(object, name) {
	
	return Validator.CheckLength(object, name, 1, 2) &&
		   Validator.CheckNumber(object) &&
		   Validator.CheckInteger(object, 0, 23);
		   
}

Validator.CheckMinute = function(object, name) {
	
	return Validator.CheckLength(object, name, 1, 2) &&
		   Validator.CheckNumber(object) &&
		   Validator.CheckInteger(object, 0, 59);
		   
}

Validator.CheckSecond = function(object, name) {
	
	return Validator.CheckLength(object, name, 1, 2) &&
		   Validator.CheckNumber(object) &&
		   Validator.CheckInteger(object, 0, 59);
		   
}

// REQUIRE rupiah2.js !!!!
Validator.CheckRupiah = function(object, name) {
	
	if ($.trim(object.val()) == 0)
		return true;
	
	var value = rupiahToNumber(object.val());
	if (isNaN(value))
	{
		Validator.FocusError(object);
		alert("Nilai " + name + " harus berupa bilangan!");
		
		return false;
	}

	return true;
}
