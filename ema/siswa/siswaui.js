function show_wait(areaId)
{
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}

function getTabContent(){
	show_wait('pilihsiswa');
	sendRequestText('pilihsiswa.php',showTabPilih,'');
	show_wait('carisiswa');
	sendRequestText('carisiswa.php',showTabCari,'');
}
function showTabPilih(x){
	document.getElementById('pilihsiswa').innerHTML = x;
}
function showTabCari(x){
	document.getElementById('carisiswa').innerHTML = x;
}
function chg_dep(){
	//alert ('asup kehed');
	var dep = document.frmPilih.dep.value;
	show_wait('tktInfo');
	sendRequestText('gettkt.php',showtktInfo,'dep='+dep); 
}
function showtktInfo(x){
	document.getElementById('tktInfo').innerHTML = x;
	chg_tkt();
}
function chg_tkt(){
	var tkt = document.frmPilih.tkt.value;
	var ta = document.frmPilih.ta.value;
	//alert (ta+' '+tkt);
	show_wait('klsInfo');
	sendRequestText('getkls.php',showklsInfo,'tkt='+tkt+'&ta='+ta); 
}

function showklsInfo(x){
	document.getElementById('klsInfo').innerHTML = x;
	chg_kls();
}

function chg_kls(){
	var kls = document.frmPilih.kls.value;
	show_wait('sisInfo');
	sendRequestText('getsis.php',showsisInfo,'kls='+kls); 
}

function showsisInfo(x){
	document.getElementById('sisInfo').innerHTML = x;
}

function load_first_dep(){
	//alert ('Asup');
	//setTimeout(chg_dep(),3000);
}

function pilihsiswa(nis)
{
	show_wait('content');
	sendRequestText('infosiswa.content.php', showInfo, 'nis='+nis);
	
	/*
	setTimeout(function (){
		sendRequestText('infosiswa.content.php', showInfo, 'nis='+nis);
		setTimeout(function() {getInfoTabContent(nis)}, 700);
	});
	*/
}

function showInfo(x)
{
	document.getElementById('content').innerHTML = x;
    //var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
}

function GetReportContent()
{
	var reporttype = document.getElementById('reporttype').value;
	
	show_wait('content');
	sendRequestText('infosiswa.content.php', showInfo, 'reporttype='+reporttype);
}

function ShowReportContent(html)
{
	document.getElementById('infosiswacontent').innerHTML = html;
}


function CariSiswa(){
	var nis = document.frmCari.nis.value;
	var nisn = document.frmCari.nisn.value;
	var nama = document.frmCari.nama.value;
	var next = false;
	if (nis.length>=3 || nisn.length>=3 || nama.length>=3){
		next = true;
		
	}
	if (!next){
		alert ('NIS,NISN atau Nama harus diisi dan tidak boleh kurang dari 3 karakter!');
		document.frmCari.nis.focus();
		return false;
	}
	var addr2 = "";
	if (nis.length>=3)
		addr2 += 'nis='+nis;
	if (nama.length>=3)
		addr2 += (addr2!="")?'&nama='+nama:'nama='+nama;
	if (nisn.length>=3)
		addr2 += (addr2!="")?'&nisn='+nisn:'nisn='+nisn;
	
	show_wait('sisInfoCari');
	sendRequestText('getsiscari.php',showsisInfoCari,addr2);
	
}
function showsisInfoCari(x){
	document.getElementById('sisInfoCari').innerHTML = x;
}
