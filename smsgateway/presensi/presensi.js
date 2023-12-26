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
	ShowWait('klsInfo');
	sendRequestText('presensi.ajax.php',ShowKelasInfo,'dep='+x+'&op=getKelas');
}
function ShowKelasInfo(x){
	document.getElementById('klsInfo').innerHTML = x;
}
function Send(){
	//ErrAll,ErrDestination,ErrSenderName,ErrPresDate,ErrNISList;
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
	
	//Jenis presensi
	var pres;
	var pres0 = document.getElementById('harian').checked;
	var pres1 = document.getElementById('pelajaran').checked;
	if (pres0)
		pres=0;
	else
		pres=1;
		
	addr2 += "&Pres="+pres;	
	//OrderYear+'-'+OrderMonth+'-'+OrderDate;
	
	//Periode presensi
	var Tgl1  = document.getElementById('Date1').value;
	if (Tgl1.length=='1')
		Tgl1  = "0"+Tgl1;
	var Bln1  = document.getElementById('Month1').value;
	if (Bln1.length=='1')
		Bln1  = "0"+Bln1;
	var Thn1  = document.getElementById('Year1').value;

	var Tgl2  = document.getElementById('Date2').value;
	if (Tgl2.length=='1')
		Tgl2  = "0"+Tgl2;
	var Bln2  = document.getElementById('Month2').value;
	if (Bln2.length=='1')
		Bln2  = "0"+Bln2;
	var Thn2  = document.getElementById('Year2').value;
	
	var Date1 = parseInt(Thn1+Bln1+Tgl1);
	var Dt1   = Thn1+"-"+Bln1+"-"+Tgl1;

	var Date2 = parseInt(Thn2+Bln2+Tgl2);
	var Dt2   = Thn2+"-"+Bln2+"-"+Tgl2;

	//alert (Date1+"\n"+Date2+"\n"+Dt1+"\n"+Dt2+"\n");
	if (Date1<Date2){
		
		ShowError('ErrPresDate','Periode awal harus lebih kecil dari periode akhir!','');
		return false;
	} else {
		addr2 += "&Date1="+Dt2+"&Date2="+Dt1;
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
	parent.HiddenFrame.location.href="SendMessage.php?op=SavePresensi"+addr2;
}
function PresensiAfterSend(nope){
	var msg = "<img src='../images/ico/unblock.png' />&nbsp;Berhasil mengirim laporan presensi ke "+nope+" penerima.";
	//var tbl = document.getElementById('TableLogs');
	//var row = tbl.insertRow(0);
	//var cell = row.insertCell(0);
	//cell.innerHTML = msg;
	document.getElementById('SuccessMsg').innerHTML = msg;
}