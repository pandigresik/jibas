function TakeDate(elementid){
	var addr = "../lib/cals.php?elementid="+elementid;
	newWindow(addr, 'CariTanggal','338','216','resizable=0,scrollbars=0,status=0,toolbar=0')
}
function AcceptDate(date,elementid){
	document.getElementById(elementid).value=date;
}
function validate(){
	var aktivitas = tinyMCE.get('aktivitas').getContent();
	if (aktivitas.length==0)
	{
		alert ('Anda harus mengisikan aktivitas!');
		return false;
	}
}