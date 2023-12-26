var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var status = document.getElementById('status').value;
var departemen = document.getElementById('departemen').value;
if (status == "Calon") { 		
	sendRequestText("../library/pilih_calonsiswa.php", show_panel0, "departemen=" + departemen);
	sendRequestText("../library/cari_calonsiswa.php", show_panel1, "departemen=" + departemen);
} else {
	
	sendRequestText("../library/pilih_siswa.php", show_panel0, "departemen=" + departemen);
	sendRequestText("../library/cari_siswa.php", show_panel1, "departemen=" + departemen);
}

function show_panel0(x) {
	document.getElementById("panel0").innerHTML = x;
	Tables('table', 1, 0);
	if (status == "Calon") {
		//var spryselect1 = new Spry.Widget.ValidationSelect("depart2");
		var spryselect2 = new Spry.Widget.ValidationSelect("proses");
		document.getElementById('proses').focus();
		var spryselect3 = new Spry.Widget.ValidationSelect("kelompok");
	} else {
		var spryselect2 = new Spry.Widget.ValidationSelect("angkatan");
		document.getElementById('angkatan').focus();
		var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
		var spryselect4 = new Spry.Widget.ValidationSelect("kelas");	
	}
}
		
function show_panel1(x) {
	document.getElementById("panel1").innerHTML = x;
	var sprytextfield1 = new Spry.Widget.ValidationTextField("nama");
	document.getElementById('nama').focus();	
	if (status == "Calon") {
		//var spryselect1 = new Spry.Widget.ValidationSelect("depart3");
		var sprytextfield2 = new Spry.Widget.ValidationTextField("no");
	} else 	{
		//var spryselect1 = new Spry.Widget.ValidationSelect("depart1");
		var sprytextfield2 = new Spry.Widget.ValidationTextField("nis");
	}
}

function show_panel2(x) {
	document.getElementById("panel1").innerHTML = x;
	Tables('table1', 1, 0);
}

function carilah(){
	var nis = document.getElementById('nis').value;
	var nama = document.getElementById('nama').value;
	var departemen = document.getElementById('depart1').value;
	
	if (nis == "" && nama == "") {
		alert ('NIS atau Nama Siswa tidak boleh kosong!');
		document.getElementById("nama").focus();	
		return false;
	}	
	sendRequestText("../library/cari_siswa.php", show_panel2, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen);
	parent.content.location.href="tabungan.trans.input.blank.php";
	
}

function carilah1(){
	var no = document.getElementById('no').value;
	var nama = document.getElementById('nama').value;
	var departemen = document.getElementById('depart3').value;
	
	if (no == "" && nama == "") {
		alert ('No Pendaftaran atau Nama Calon tidak boleh kosong!');
		document.getElementById("nama").focus();	
		return false;
	}	
	sendRequestText("../library/cari_calonsiswa.php", show_panel2, "submit=1&no="+no+"&nama="+nama+"&departemen="+departemen);
	parent.content.location.href="tabungan.trans.input.blank.php";
}

/*
function change_departemen(tipe){	
	if (tipe == 0) {
		var departemen = document.getElementById('depart').value;
		sendRequestText("pilih_siswa.php", show_panel0, "departemen="+departemen);	
	} else if (tipe == 1) {
		var departemen = document.getElementById('depart1').value;		
		sendRequestText("cari_siswa.php", show_panel1, "departemen="+departemen);	
	} else if (tipe == 2) {		
		var departemen = document.getElementById('depart2').value;			
		sendRequestText("pilih_calonsiswa.php", show_panel0, "departemen="+departemen);	
	} else if (tipe == 3) {		
		var departemen = document.getElementById('depart3').value;			
		sendRequestText("cari_calonsiswa.php", show_panel1, "departemen="+departemen);	
	}
}
*/
function change(){
	var departemen = document.getElementById('depart').value;
	var angkatan = document.getElementById('angkatan').value;
	var tingkat = document.getElementById('tingkat').value;
	sendRequestText("../library/pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat);
	parent.content.location.href="tabungan.trans.input.blank.php";	
}

function change_kelas(){
	var departemen = document.getElementById('depart').value;
	var angkatan = document.getElementById('angkatan').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;
	sendRequestText("../library/pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat+"&kelas="+kelas);	
	parent.content.location.href="tabungan.trans.input.blank.php";
}

function change_proses(){
	var departemen = document.getElementById('depart2').value;
	var proses = document.getElementById('proses').value;
	sendRequestText("../library/pilih_calonsiswa.php", show_panel0, "departemen="+departemen+"&proses="+proses);	
	parent.content.location.href="tabungan.trans.input.blank.php";
}

function change_kelompok(){
	var departemen = document.getElementById('depart2').value;
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	sendRequestText("../library/pilih_calonsiswa.php", show_panel0, "departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok);	
	parent.content.location.href="tabungan.trans.input.blank.php";
}


function cari(x) {
	document.getElementById("caritabel").innerHTML = x;		
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    } else {		
		sendRequestText("../get_blank.php", cari, "");
	}
    return true;
}

function focusNext1(elemName, evt, st, no, aktif) {
  	evt = (evt) ? evt : event;	
    var charCode = (evt.charCode) ? evt.charCode :
  		((evt.which) ? evt.which : evt.keyCode);
   	if (charCode == 13) {	
		var point = parseInt(no);		
		var mundur = point-1;
		var maju = point+1;
		
		if (aktif == 1) {
			mod = point % 2;
				if (mod != 0 && point != 1) 
					document.getElementById(elemName+st+mundur).style.background = "#E7E7CF";
				else if (mod == 0 && point != 1)
					document.getElementById(elemName+st+mundur).style.background = "#FFFFFF";
			document.getElementById(st+elemName+maju).focus();
			document.getElementById(elemName+st+no).style.background = "#FFFF00";
			
		} else {
			document.getElementById(st+elemName+no).focus();
			document.getElementById(elemName+st+no).style.background = "#FFFF00";
			
		}
		
        return false;
   	} 
	return true;
}

function change_urut(urut,urutan,tipe) {
	if (tipe == "daftar") 	{
		var departemen = document.getElementById('depart').value;
		var angkatan = document.getElementById('angkatan').value;
		var tingkat = document.getElementById('tingkat').value;
		var kelas = document.getElementById('kelas').value;		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("../library/pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat+"&kelas="+kelas+"&urut="+urut+"&urutan="+urutan);
	} else if (tipe == "cari") {
		var departemen=document.getElementById("depart1").value;
		var nis=document.getElementById("nis").value;
		var nama=document.getElementById("nama").value;	
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("../library/cari_siswa.php", show_panel2, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen+"&urut1="+urut+"&urutan1="+urutan);
		
	} else if (tipe == "daftarcalon") {		
		var departemen = document.getElementById('depart2').value;
		var proses = document.getElementById('proses').value;
		var kelompok = document.getElementById('kelompok').value;
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("../library/pilih_calonsiswa.php", show_panel0, "departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok+"&urut2="+urut+"&urutan2="+urutan);
		
	} else {		
		var departemen = document.getElementById('depart3').value;
		var no = document.getElementById('no').value;
		var nama = document.getElementById('nama').value;
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		sendRequestText("../library/cari_calonsiswa.php", show_panel2, "submit=1&no="+no+"&nama="+nama+"&departemen="+departemen+"&urut3="+urut+"&urutan3="+urutan);
	
	}
}