function ChgDepPel(){
	var DepPel = document.getElementById('DepPel').value;
	//alert (DepPel);
	ShowWait('PelInfo');
	sendRequestText('penilaian.ajax.php',ShowPelInfo,'DepPel='+DepPel+'&op=getPelInfo');
}
function ShowPelInfo(x){
	document.getElementById('PelInfo').innerHTML = x;
	ChgPelPel();
}
function ChgPelPel(){
	var PelPel = document.getElementById('PelPel').value;
	//alert (PelPel);
	ShowWait('UjiInfo');
	sendRequestText('penilaian.ajax.php',ShowUjiInfo,'PelPel='+PelPel+'&op=getUjiInfo');
}
function ShowUjiInfo(x){
	document.getElementById('UjiInfo').innerHTML = x;
}
function SetActive(state,ID1,ID2,ID3){//'1','NISList','dep','kelas'
	if (state=='0'){
		document.getElementById(ID1).disabled=false;
		document.getElementById(ID2).disabled=true;
		document.getElementById(ID3).disabled=true;
	} else {
		document.getElementById(ID1).disabled=true;
		document.getElementById(ID2).disabled=false;
		document.getElementById(ID3).disabled=false;	
	}
}
function ChgDep(x){
	//alert ('masuk');
	ShowWait('klsInfo');
	sendRequestText('penilaian.ajax.php',ShowKelasInfo,'Dep='+x+'&op=getKelas');
}

function ShowKelasInfo(x){
	document.getElementById('klsInfo').innerHTML = x;
}
function Send(){
	//ErrAll,ErrDestination,ErrSenderName,ErrPresDate,ErrNISList;
	HideError('ErrPelajaran');
	HideError('ErrAll');
	HideError('ErrDestination');
	HideError('ErrSenderName');
	HideError('ErrPresDate');
	HideError('ErrNISList');
	
	var addr2 = "";
	//Penerima
	var type;
	var type0 = document.getElementById('type0').checked;
	var type1 = document.getElementById('type1').checked;
	if (type0)
		type=0;
	else
		type=1;
	
	if (type==0){
		var NISList = document.getElementById('NISList').value;
		NISList = trimAll(NISList);
		if (NISList==''){
			ShowError('ErrNISList','Silakan masukan NIS \n dipisahkan koma jika lebih dari 1','NISList');
			return false;
		}
		addr2 += "&Type=0&NIS="+NISList;
	} else {
		var Kls 	= document.getElementById('kls').value;
		var Dep 	= document.getElementById('dep').value;
		addr2 += "&Type=1&Dep="+Dep+"&Kls="+Kls;
	}
	
	//Penilaian
	var DepPel = document.getElementById('DepPel').value;
	var PelPel = document.getElementById('PelPel').value;
	var UjiPel = document.getElementById('UjiPel').value;
	if (DepPel==''){
		ShowError('ErrPelajaran','Belum ada Departemen!','');
		return false;
	} else {
		if (PelPel==''){
			ShowError('ErrPelajaran','Tidak ada Pelajaran di departemen '+DepPel+'!','');
			return false;
		} else {
			addr2 += "&IDPel="+PelPel+"&IDUjian="+UjiPel;
		}
	}
	
	//Penilaian
	var NumData = document.getElementById('Limit').value;
	addr2 += "&NumData="+NumData;
	
	//Periode presensi
	var Date2 = document.getElementById('Year1').value+'-'+document.getElementById('Month1').value+'-'+document.getElementById('Date1').value;
	var Date1 = document.getElementById('Year2').value+'-'+document.getElementById('Month2').value+'-'+document.getElementById('Date2').value;
	
	if (Date1>Date2){
		//alert ('Tgl1 > Tgl2');
		ShowError('ErrPresDate','Periode awal harus lebih kecil dari periode akhir!','');
		return false;
	} else {
		addr2 += "&Date1="+Date1+"&Date2="+Date2;
	}
	
	//Pengirim
	var Sender = document.getElementById('Sender').value;
	if (Sender==''){
		//alert ('Tgl1 > Tgl2');
		ShowError('ErrSenderName','Silakan isi nama pengirim!','Sender');
		return false;
	} else {
		addr2 += "&Sender="+Sender;
	}
	
	//Tujuan
	var kesiswa = document.getElementById('kesiswa').checked;
	var keortu = document.getElementById('keortu').checked;
	if (!kesiswa && !keortu){
		//alert ('Tgl1 > Tgl2');
		ShowError('ErrDestination','Silakan pilih tujuan pesan akan dikirim!','');
		return false;
	} else {
		if (kesiswa)
			addr2 += "&KeSiswa=1";
		if (keortu)
			addr2 += "&KeOrtu=1";	
	}
	
	//Tgl kirim
	var SendDate = document.getElementById('SendYear').value+'-'+document.getElementById('SendMonth').value+'-'+document.getElementById('SendDate').value+' '+document.getElementById('SendHour').value+':'+document.getElementById('SendMinute').value+':00';
	addr2 += "&SendDate="+SendDate;
	//alert (addr2);
	parent.HiddenFrame.location.href="SendMessage.php?op=SavePenilaian"+addr2;
}

function PenilaianAfterSend(nope){
	var msg = "<img src='../images/ico/unblock.png' />&nbsp;Berhasil mengirim laporan penilaian ke "+nope+" penerima.";
	//var tbl = document.getElementById('TableLogs');
	//var row = tbl.insertRow(0);
	//var cell = row.insertCell(0);
	//cell.innerHTML = msg;
	document.getElementById('SuccessMsg').innerHTML = msg;
}