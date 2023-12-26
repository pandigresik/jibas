function chg(){
	var perpustakaan=document.getElementById('perpustakaan').value;
	var Limit = document.getElementById('Limit').value;
	var BlnAwal = document.getElementById('BlnAwal').value;
	var ThnAwal = document.getElementById('ThnAwal').value;
	var BlnAkhir = document.getElementById('BlnAkhir').value;
	var ThnAkhir = document.getElementById('ThnAkhir').value;
	document.location.href = "stat.pustaka.php?perpustakaan="+perpustakaan+"&BlnAwal="+BlnAwal+"&ThnAwal="+ThnAwal+"&BlnAkhir="+BlnAkhir+"&ThnAkhir="+ThnAkhir+"&Limit="+Limit;
}
function show(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	var Limit = document.getElementById('Limit').value;
	var BlnAwal = document.getElementById('BlnAwal').value;
	var ThnAwal = document.getElementById('ThnAwal').value;
	var BlnAkhir = document.getElementById('BlnAkhir').value;
	var ThnAkhir = document.getElementById('ThnAkhir').value;
	document.location.href = "stat.pustaka.php?perpustakaan="+perpustakaan+"&BlnAwal="+BlnAwal+"&ThnAwal="+ThnAwal+"&BlnAkhir="+BlnAkhir+"&ThnAkhir="+ThnAkhir+"&Limit="+Limit+"&ShowState=true";
}

function ViewList(replid){
	var BlnAwal = document.getElementById('BlnAwal').value;
	var ThnAwal = document.getElementById('ThnAwal').value;
	var BlnAkhir = document.getElementById('BlnAkhir').value;
	var ThnAkhir = document.getElementById('ThnAkhir').value;
	var from = ThnAwal+'-'+BlnAwal+'-01';
	var to = ThnAkhir+'-'+BlnAkhir+'-31';
	show_wait("ListInfo");
	sendRequestText("GetPustakaList.php", showList, "idpustaka="+replid+"&from="+from+"&to="+to);					
}

function showList(x) {
	document.getElementById("ListInfo").innerHTML = x;
}

function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}

function ViewDetail(id){
	newWindow('pustaka.view.detail.php?replid='+id, 'DetailPustaka','509','456','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Cetak(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	var Limit = document.getElementById('Limit').value;
	var BlnAwal = document.getElementById('BlnAwal').value;
	var ThnAwal = document.getElementById('ThnAwal').value;
	var BlnAkhir = document.getElementById('BlnAkhir').value;
	var ThnAkhir = document.getElementById('ThnAkhir').value;
	var from = ThnAwal+'-'+BlnAwal+'-01';
	var to = ThnAkhir+'-'+BlnAkhir+'-31';
	newWindow('stat.pustaka.cetak.php?perpustakaan='+perpustakaan+'&limit='+Limit+'&from='+from+'&to='+to,'CetakStatPustaka','790','610','resizable=1,scrollbars=1,status=0,toolbar=0');
}
