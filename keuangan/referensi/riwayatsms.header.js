change_tgl = function()
{
	parent.content.location.href = "riwayatsms.blank.php";
}

change_dep = function()
{
    parent.content.location.href = "riwayatsms.blank.php";
}

change_kate = function()
{
    parent.content.location.href = "riwayatsms.blank.php";
}

ShowRiwayatSms = function()
{
	var iddep = $('#departemen').val();
	var tgl = $('#tgl').val();
	var bln = $('#bln').val();
	var thn = $('#thn').val();
	var kate = $('#kate').val();
	
	parent.content.location.href = "riwayatsms.content.php?iddep="+iddep+"&tgl="+tgl+"&bln="+bln+"&thn="+thn+"&kate="+kate;
}