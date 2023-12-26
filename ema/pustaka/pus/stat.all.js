function chg(){
	var perpustakaan=document.getElementById('perpustakaan').value;
	var BlnAwal = document.getElementById('BlnAwal').value;
	var ThnAwal = document.getElementById('ThnAwal').value;
	var BlnAkhir = document.getElementById('BlnAkhir').value;
	var ThnAkhir = document.getElementById('ThnAkhir').value;
	document.location.href = "stat.all.php?perpustakaan="+perpustakaan+"&BlnAwal="+BlnAwal+"&ThnAwal="+ThnAwal+"&BlnAkhir="+BlnAkhir+"&ThnAkhir="+ThnAkhir;
}
function show(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	var BlnAwal = document.getElementById('BlnAwal').value;
	var ThnAwal = document.getElementById('ThnAwal').value;
	var BlnAkhir = document.getElementById('BlnAkhir').value;
	var ThnAkhir = document.getElementById('ThnAkhir').value;
	document.location.href = "stat.all.php?perpustakaan="+perpustakaan+"&BlnAwal="+BlnAwal+"&ThnAwal="+ThnAwal+"&BlnAkhir="+BlnAkhir+"&ThnAkhir="+ThnAkhir+"&ShowState=true";
}

function ViewList(waktu){
	show_wait("ListInfo");
	sendRequestText("GetAllList.php", showList, "waktu="+waktu);					
}

function showList(x) {
	document.getElementById("ListInfo").innerHTML = x;
}

function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}

function Cetak(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	var BlnAwal = document.getElementById('BlnAwal').value;
	var ThnAwal = document.getElementById('ThnAwal').value;
	var BlnAkhir = document.getElementById('BlnAkhir').value;
	var ThnAkhir = document.getElementById('ThnAkhir').value;
	var from = ThnAwal+'-'+BlnAwal+'-01';
	var to = ThnAkhir+'-'+BlnAkhir+'-31';
	newWindow('stat.all.cetak.php?perpustakaan='+perpustakaan+'&from='+from+'&to='+to,'CetakStatAll','790','610','resizable=1,scrollbars=1,status=0,toolbar=0');
}