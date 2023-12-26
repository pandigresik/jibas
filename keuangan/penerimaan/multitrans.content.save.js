var win = null;

OpenWindow = function(mypage, myname, w, h, features)
{
	var winl = (screen.width-w)/2;
	var wint = (screen.height-h)/2;
	if (winl < 0) winl = 0;
	if (wint < 0) wint = 0;
	
	var settings = 'height=' + h + ',';
	settings += 'width=' + w + ',';
	settings += 'top=' + wint + ',';
	settings += 'left=' + winl + ',';
	settings += features;
	
	win = window.open(mypage,myname,settings);
	win.window.focus();
}

GetReportDetail = function()
{
	return $("#divReportDetail").html();
}

PrintDetail = function()
{
	var dept = $("#departemen").val();
	var jenis = $("#kelompok").val();
	var noid = $("#studentid").val();
	var jumlah = $("#total").val();
	var ktransaksi = $("#ktransaksi").val();
	
	var addr = "multitrans.content.print.detail.php?departemen="+dept+"&jenis="+jenis+"&noid="+noid+"&jumlah="+jumlah+"&ktransaksi="+ktransaksi;
	OpenWindow(addr, 'PrintDetail', '790', '590', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

PrintCompact = function()
{
	var dept = $("#departemen").val();
	var jenis = $("#kelompok").val();
	var noid = $("#studentid").val();
	var jumlah = $("#total").val();
	var ktransaksi = $("#ktransaksi").val();
	var paymentlist = $("#paymentlist").val();
	
	var addr = "multitrans.content.print.compact.php?departemen="+dept+"&jenis="+jenis+"&noid="+noid+"&jumlah="+jumlah+"&ktransaksi="+ktransaksi+"&paymentlist="+paymentlist;
	OpenWindow(addr, 'PrintCompact', '390', '590', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}