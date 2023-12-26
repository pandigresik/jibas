ValidateInput = function()
{
	return Validator.CheckLength($('#formatsms'), 'Format SMS', 25, 255);
}

ChangeDept = function()
{
	var dept = $('#departemen').val();
	document.location.href = 'formatsms.php?departemen='+dept;
}