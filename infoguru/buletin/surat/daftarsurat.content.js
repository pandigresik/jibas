viewLetter = function(idsurat)
{
	var addr = "letter.view.php?idsurat="+idsurat;
	var name = "surat"+idsurat;
	
	newWindow(addr, name, 1200, 620, 'resizable=0,scrollbars=0,status=0,toolbar=0');
}