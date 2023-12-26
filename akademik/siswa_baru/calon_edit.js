function validate()
{
	var urutananak=document.main.urutananak.value;
	var jumlahanak=document.main.jumlahanak.value;
    var jkandung=document.main.jkandung.value;
    var jtiri=document.main.jtiri.value;
	var kodepos=document.main.kodepos.value;
	var telponsiswa=document.main.telponsiswa.value;
	var hpsiswa=document.main.hpsiswa.value;	
	var berat=document.getElementById('berat').value;
	var tinggi=document.main.tinggi.value;
	var penghasilanayah=document.main.penghasilanayah.value;
	var penghasilanibu=document.main.penghasilanibu.value;
	var telponortu=document.main.telponortu.value;
	var hportu=document.main.hportu.value;
	var hportu2=document.main.hportu2.value;
	var hportu3=document.main.hportu3.value;
	var emailsiswa = document.main.emailsiswa.value;
	var emailayah = document.main.emailayah.value;
	var emailibu = document.main.emailibu.value;
	var tgllahir = document.getElementById("tgllahir").value;
	var blnlahir = document.getElementById("blnlahir").value;
	var thnlahir = document.getElementById("thnlahir").value;
	var nama = document.main.nama.value;
	var tmplahir = document.main.tmplahir.value;	
	var agama = document.main.agama.value;
	var suku = document.main.suku.value;
	var status = document.main.status.value;
	var kondisi = document.main.kondisi.value;	
	var kelompok = document.main.kelompok.value;
	var kel_lama = document.main.kel_lama.value;
	var file=document.getElementById("file").value;
	
	if (kelompok.length == 0)
    {
		alert("Kelompok Calon Siswa tidak boleh kosong!");
		document.getElementById('departemen').focus();
		return false;
	} 
		
	if (kelompok != kel_lama)
    {
		var kapasitas = parseInt(document.main.kapasitas.value);
		var isi = parseInt(document.main.isi.value);
		if (kapasitas <= isi)
        {
			alert("Kapasitas kelompok tidak mencukupi untuk menambah data calon baru!");
			document.getElementById('kelompok').focus();
			return false;	
		}
	}
	
	if (nama.length == 0)
    {
		alert("Anda harus mengisikan data untuk Nama!");
		document.getElementById('nama').focus();
		return false;
	}
		
	if (urutananak.length > 0)
    {
		if(isNaN(urutananak))
        {
			alert ('Data isian anak ke harus berupa bilangan!');
			document.main.urutananak.value="";
			document.main.urutananak.focus();
			return false;
		}
        
		if (urutananak == 0)
        {
			alert("Urutan Anak harus lebih besar dari 0!");
			document.getElementById('urutananak').focus();
			return false;
		}
        
		if (jumlahanak.length == 0)
        {
			alert("Jumlah saudara tidak boleh kosong!");
			document.getElementById('jumlahanak').focus();
			return false;
		}
	} 

	if (jumlahanak.length > 0)
    {
		if(isNaN(jumlahanak))
        {
			alert ('Data jumlah anak harus berupa bilangan!');		
			document.getElementById('jumlahanak').focus();
			return false;
		}
        
        if (jumlahanak == 0)
        {
			alert("Jumlah Anak harus lebih besar dari 0!");
			document.getElementById('jumlahanak').focus();
			return false;
		}
	}
    
    if (jkandung.length > 0)
    {
		if(isNaN(jkandung))
        {
			alert ('Data jumlah saudara kandung harus berupa bilangan!');		
			document.getElementById('jkandung').focus();
			return false;
		}
	}
    
    if (jtiri.length > 0)
    {
		if(isNaN(jtiri))
        {
			alert ('Data jumlah saudara tiri harus berupa bilangan!');		
			document.getElementById('jtiri').focus();
			return false;
		}
	}

	if (parseInt(urutananak) > parseInt(jumlahanak))
    {
		alert ('Urutan anak tidak boleh lebih dari jumlah saudara yang ada!');
		document.getElementById ('urutananak').focus();
		return false;
	}
		
	if (kodepos.length > 0)
    {
		if(isNaN(kodepos))
        {
			alert ('Data kodepos harus berupa bilangan!');
			document.getElementById('kodepos').focus();
			return false;
		}
	}	

	if (telponsiswa.length > 0)
    {
		if(isNaN(telponsiswa))
        {
			alert ('Data nomor telepon harus berupa bilangan!');
			document.getElementById('telponsiswa').focus();
			return false;
		}
	}
		
	if (hpsiswa.length > 0)
	{
		if(hpsiswa.substring(0, 1) != "#" && isNaN(hpsiswa))
		{
			alert ('Data nomor HP harus berupa bilangan!\nTidak boleh menggunakan spasi.');
			document.getElementById('hpsiswa').focus();
			return false;
		}
	}	
	
	if (berat.length > 0)
    {
		if(isNaN(berat))
        {
			alert ('Data berat harus berupa bilangan');
			document.getElementById('berat').focus();
			return false;
		}
	}		

	if (tinggi.length > 0)
    {
		if(isNaN(tinggi))
        {
			alert ('Data tinggi harus berupa bilangan!');
			document.getElementById('tinggi').focus();
			return false;
		}
	}	
	
	if (penghasilanayah.length > 0)
    {
		if(isNaN(penghasilanayah))
        {
			alert ('Data penghasilan Ayah harus berupa bilangan!');
			document.getElementById('penghasilanayah1').focus();
			return false;
		}
	}	
		
	if (penghasilanibu.length > 0)
    {
		if(isNaN(penghasilanibu))
        {
			alert ('Data penghasilan Ibu harus berupa bilangan!');
			document.getElementById('penghasilanibu1').focus();
			return false;
		}
	}

	if (telponortu.length > 0)
    {
		if(isNaN(telponortu))
        {
			alert ('Data telepon orang tua harus berupa bilangan!');
			document.getElementById('telponortu').focus();
			return false;
		}
	}
	
	if (hportu.length > 0)
	{
		if (hportu.substring(0, 1) != "#" && isNaN(hportu))
		{
			alert ('Data nomor HP orang tua harus berupa bilangan!\nTidak boleh menggunakan spasi.');
			document.getElementById('hportu').focus();
			return false;
		}
	}
	
	if (hportu2.length > 0)
	{
		if (hportu2.substring(0, 1) != "#" && isNaN(hportu2))
		{
			alert ('Data nomor HP orang tua harus berupa bilangan!\nTidak boleh menggunakan spasi.');
			document.getElementById('hportu2').focus();
			return false;
		}
	}
	
	if (hportu3.length > 0)
	{
		if (hportu3.substring(0, 1) != "#" && isNaN(hportu3))
		{
			alert ('Data nomor HP orang tua harus berupa bilangan!\nTidak boleh menggunakan spasi.');
			document.getElementById('hportu3').focus();
			return false;
		}
	}
	
	if (emailsiswa.length > 0)
    {
		if (!validateEmail("emailsiswa") )
        { 
			alert( "Email yang Anda masukkan bukan merupakan alamat email!" );
			document.main.emailsiswa.focus();
			return false;	
		}	
	}
	
	if (emailayah.length > 0)
    {
		if (!validateEmail("emailayah") )
        { 
			alert( "Email yang Anda masukkan bukan merupakan alamat email!" );
			document.main.emailayah.focus();
			return false;	
		}	
	}
	
	if (emailibu.length > 0)
    {
		if (!validateEmail("emailibu") )
        { 
			alert( "Email yang Anda masukkan bukan merupakan alamat email!" );
			document.main.emailibu.focus();
			return false;	
		}	
	}
		
	var namatgl = "tgllahir";
	var namabln = "blnlahir";
		
	if (thnlahir.length != 0 && blnlahir.length != 0 && tgllahir.length != 0){
		if (thnlahir % 4 == 0){
			 if (blnlahir == 2){
				  if (tgllahir>29){
					   alert ('Maaf, silahkan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+blnlahir+"&tgl="+tgllahir+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
				  }
			 }
			 if (blnlahir == 4 || blnlahir == 6 || blnlahir == 9 || blnlahir == 11){
				  if (tgllahir>30){
					   alert ('Maaf, silahkan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+blnlahir+"&tgl="+tgllahir+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
				  }
			 }
		}
		if (thnlahir % 4 != 0){
			 if (blnlahir == 2){
				 if (tgllahir>28){
					   alert ('Maaf, silahkan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+blnlahir+"&tgl="+tgllahir+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
					   
				  }
			 }
			 if (blnlahir == 4 || blnlahir == 6 || blnlahir == 9 || blnlahir == 11){
				  if (tgllahir>30){
					   alert ('Maaf, silahkan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+blnlahir+"&tgl="+tgllahir+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
					   
				  }
			 }
		}
	}
	
	if (file.length>0)
    {
		var ext = "";
		var x = file.split('.');
		ext = x[(x.length-1)];
		if (ext!='JPG' && ext!='jpg' && ext!='Jpg' && ext!='JPg' && ext!='JPEG' && ext!='jpeg')
        {
			alert ('Format Gambar harus ber-extensi jpg atau JPG !');
			document.getElementById("foto").value='';
			document.main.file.focus();
    		document.main.file.select();
			return false;
		} 
	} 
	return true;
}

function sekolah_kiriman(sekolah, dep) {	
	var dep_asal = document.getElementById("dep_asal").value;
	if (dep_asal == dep) {
		change_departemen(sekolah);
	} else {
		setTimeout("change_departemen(0)",1);
	}
}
function sekolah_kiriman2(dep) {
	show_wait("depInfo");
	sendRequestText("../siswa/siswa_add_getdepasal.php", showDep, "dep="+dep);
}
function showDep(x) {
	document.getElementById("depInfo").innerHTML = x;
	change_departemen(0);
}
function change_departemen(kode) {		
	var dep_asal = document.getElementById("dep_asal").value;
	wait_sekolah();
	if (kode==0){
		sendRequestText("../siswa/siswa_add_getsekolah.php", showSekolah, "dep_asal="+dep_asal);		
	} else {		
		sendRequestText("../siswa/siswa_add_getsekolah.php", showSekolah, "dep_asal="+dep_asal+"&sekolah="+kode);
	}
}

function wait_sekolah() {
	show_wait("sekolahInfo");
}
function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}
function showSekolah(x) {
	document.getElementById("sekolahInfo").innerHTML = x;
}
function refresh_delete_sekolah(){
	setTimeout("change_departemen(0)",1);
}

function tambah_suku(){
	newWindow('../library/suku.php', 'tambahSuku','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_agama(){
	newWindow('../library/agama.php', 'tambahAgama','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_status(){
	newWindow('../siswa/siswa_add_status.php', 'tambahStatus','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_kondisi(){
	newWindow('../siswa/siswa_add_kondisi.php', 'tambahKondisi','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_asal_sekolah(){
	var departemen = document.getElementById("dep_asal").value;
	newWindow('../siswa/siswa_add_asalsekolah.php?departemen='+departemen, 'tambahAsalSekolah','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_pendidikan(){
	newWindow('../siswa/siswa_add_pendidikan.php', 'tambahPendidikan','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_pekerjaan(){
	newWindow('../siswa/siswa_add_pekerjaan.php', 'tambahPekerjaan','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function tutup(){
	var departemen = document.getElementById('departemen').value;
	var proses = document.getElementById('proses').value;	
	var kelompok = document.getElementById('kelompok').value;
	parent.opener.location.href = "calon_content.php?departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok;
	window.close();
}

function ambil_alamat_ortu(){
	var alamatsiswa = document.getElementById("alamatsiswa").value;
	document.getElementById("alamatortu").value=alamatsiswa;
}

function ambil_alamat_surat(){
	var alamatsiswa = document.getElementById("alamatsiswa").value;
	document.getElementById("alamatsurat").value=alamatsiswa;
}

//Ajax suku ==========================================================
function suku_kiriman(suku_kiriman) {	
	suku = suku_kiriman
	setTimeout("refresh_suku(suku)",1);
}
function refresh_suku(kode){
	wait_suku();
	if (kode==0){
		sendRequestText("../library/getsuku.php", showSuku, "suku=");
	} else {
		sendRequestText("../library/getsuku.php", showSuku, "suku="+kode);
	}
}
function wait_suku() {
	show_wait("InfoSuku");
}
function showSuku(x) {
	document.getElementById("InfoSuku").innerHTML = x;
}
function refresh_delete(){
	setTimeout("refresh_suku(0)",1);
}
// end of Ajax Suku ====================================================

//ajax Agama============================================================
function kirim_agama(agama_kiriman){
	agama=agama_kiriman;
	setTimeout("refresh_agama(agama)",1);
}
function refresh_agama(kode){
	wait_agama();
	if (kode==0){
		sendRequestText("../library/getagama.php", showAgama, "agama=");
	} else {
		sendRequestText("../library/getagama.php", showAgama, "agama="+kode);
	}
}
function wait_agama() {
	show_wait("InfoAgama");
}
function showAgama(x) {
	document.getElementById("InfoAgama").innerHTML = x;
}
function ref_del_agama(){
	setTimeout("refresh_agama(0)",1);
}
// end of Ajax agama=====================================================

//------- Ajax Status====================================================
function status_kiriman(st){
	refresh_status(st);
	//setTimeout("refresh_status(st)",1);
}
function refresh_status(kode) {
	wait_status();
	if (kode==0) {
		sendRequestText("../siswa/siswa_add_getstatus.php", show_status,"status=");
	} else {
		sendRequestText("../siswa/siswa_add_getstatus.php", show_status,"status="+kode);
	}
}
function wait_status() {
	show_wait("InfoStatus"); //lihat div id 
}
function show_status(x) {
	document.getElementById("InfoStatus").innerHTML = x;
}
function ref_del_status(){
	setTimeout("refresh_status(0)",1);
}
// End of Ajax Status===================================================

//====== Ajax Kondisi ==================================================
function kondisi_kiriman(kondisi){	
	refresh_kondisi(kondisi);
	//setTimeout("refresh_kondisi(kon)",1);
}
function refresh_kondisi(kode) {	
	wait_kondisi();	
	if (kode==0){
		sendRequestText("../siswa/siswa_add_getkondisi.php", show_kondisi,"kondisi=");
	} else {
		sendRequestText("../siswa/siswa_add_getkondisi.php", show_kondisi,"kondisi="+kode);
	}
}
function wait_kondisi() {
	show_wait("InfoKondisi"); //lihat div id 
}
function show_kondisi(x) {
	document.getElementById("InfoKondisi").innerHTML = x;
}
function ref_del_kondisi(){
	setTimeout("refresh_kondisi(0)",1);
}
//======End of Ajax Kondisi==============================================

//====Ajax pendidikan====================================================
function pendidikan_kiriman(pendidikan){
	setTimeout("refresh_pendidikan(pendidikan)",1);
}

function refresh_pendidikan(kode_pendidikan) {		
	wait_pendidikan();
	if (kode_pendidikan==0) {
		sendRequestText("../siswa/siswa_add_getpendidikan.php", show_pendidikan,"pendidikan=");
		sendRequestText("../siswa/siswa_add_getpendidikanibu.php", show_pendidikan1,"pendidikan=");
	} else {
		sendRequestText("../siswa/siswa_add_getpendidikan.php", show_pendidikan,"pendidikan="+kode_pendidikan);
		sendRequestText("../siswa/siswa_add_getpendidikanibu.php", show_pendidikan1,"pendidikan="+kode_pendidikan);
	}
}

function show_pendidikan(x) {
	document.getElementById("pendidikanayahInfo").innerHTML = x;
}

function show_pendidikan1(x) {
	document.getElementById("pendidikanibuInfo").innerHTML = x;
}

function wait_pendidikan() {
	show_wait("pendidikanayahInfo");
	show_wait("pendidikanibuInfo");
}

function ref_del_pendidikan() {
	setTimeout("refresh_pendidikan(0)",1);
}
//=======End Of ajax pendidikan==========================================

//========Ajax Pekerjaan ================================================
function pekerjaan_kiriman(kerja){
	setTimeout("refresh_pekerjaan(pekerjaan)",1);
}

function refresh_pekerjaan(kode_pekerjaan) {
	wait_pekerjaan();
	if(kode_pekerjaan==0){
		sendRequestText("../siswa/siswa_add_getpekerjaan.php", show_pekerjaan,"pekerjaan=");
		sendRequestText("../siswa/siswa_add_getpekerjaanibu.php", show_pekerjaan1,"pekerjaan=");
	} else {
		sendRequestText("../siswa/siswa_add_getpekerjaan.php", show_pekerjaan,"pekerjaan="+kode_pekerjaan);
		sendRequestText("../siswa/siswa_add_getpekerjaanibu.php", show_pekerjaan1,"pekerjaan="+kode_pekerjaan);
	}
	
}

function wait_pekerjaan() {
	show_wait("pekerjaanayahInfo");
	show_wait("pekerjaanibuInfo");
}

function show_pekerjaan(x) {
	document.getElementById("pekerjaanayahInfo").innerHTML = x;
}

function show_pekerjaan1(x) {
	document.getElementById("pekerjaanibuInfo").innerHTML = x;
}

function ref_del_pekerjaan(){
	setTimeout("refresh_pekerjaan(0)",1);
}
//======== End Of Ajax Pekerjaan ========================================

function change_alamat(){	
	var alamatsiswa = document.getElementById("alamatsiswa").value;
	document.getElementById("alamatortu").value=alamatsiswa;
	document.getElementById("alamatsurat").value=alamatsiswa;
}
function penghasilan_ayah(){	
	var ayah = document.getElementById("penghasilanayah1").value;
	document.getElementById("penghasilanayah").value=ayah;
}
function penghasilan_ibu(){	
	var ibu = document.getElementById("penghasilanibu1").value;
	document.getElementById("penghasilanibu").value=ibu;
}


function change_bln() {	
	var thn = document.getElementById('thnlahir').value;
	var bln = parseInt(document.getElementById('blnlahir').value);	
	var tgl = parseInt(document.getElementById('tgllahir').value);
	var namatgl = "tgllahir";
	var namabln = "blnlahir";
	
	if(thn.length != 0) {
    	if(isNaN(thn)) {
    		alert("Tahun lahir harus berupa angka"); 
			document.getElementById('thnlahir').focus();
        	return false;
		} else {	
			if (thn.length > 4 || thn.length < 4) {
            	alert("Tahun lahir tidak boleh lebih atau kurang dari 4 karakter!"); 
				document.getElementById('thnlahir').focus();
            	return false;
			}
		}
    
	sendRequestText("../library/gettanggal.php", show1, "tahun="+thn+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);
	
	}
}

function show1(x) {
	document.getElementById("tgl_info").innerHTML = x;
}

function tampil_kelompok() {
	var departemen = document.getElementById("departemen").value;
	var proses = document.getElementById("proses").value;
	
	newWindow('kelompok_tampil.php?departemen='+departemen+'&proses='+proses,'tampilKelompok','750','450','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function kelompok_kiriman(a,b,c) {	
	var departemen = document.getElementById("departemen").value;	
	change_kelompok(a,b,c);
}

function change_kelompok(kel,pro,dep) {
	var departemen = document.getElementById("departemen").value;
	var proses = document.getElementById("proses").value;
	var kelompok = document.getElementById("kelompok").value;	
	
	if (kel == 0) {
		change_kel()
	} else {
		sendRequestText("getdepartemen.php", show_dep,"departemen="+dep);
		sendRequestText("getproses.php", show_proses,"departemen="+dep);
		sendRequestText("getkelompok.php", show_kelompok,"departemen="+dep+"&kelompok="+kel);	
		sendRequestText("getasalsekolah.php", show_asalsekolah,"departemen="+dep);
				
	}
	
}

function change_dep() {
	var departemen = document.getElementById("departemen").value;
	sendRequestText("getproses.php", show_proses,"departemen="+departemen);
	sendRequestText("getkelompok.php", show_kelompok,"departemen="+departemen);	
	sendRequestText("getasalsekolah1.php", show_asalsekolah,"departemen="+departemen);	
		
}

function showSekolah1(x) {
	document.getElementById("sekolahInfo").innerHTML = x;
}

function change_kel() {
	var kelompok = document.getElementById("kelompok").value;	
	sendRequestText("getketerangan.php", show_keterangan,"kelompok="+kelompok);
}

function show_dep(x) {
	document.getElementById("InfoDepartemen").innerHTML = x;
}
	
function show_proses(x) {
	document.getElementById("InfoProses").innerHTML = x;
}

function show_kelompok(x) {

	document.getElementById("InfoKelompok").innerHTML = x;
	change_kel();
}

function show_keterangan(x) {
	document.getElementById("InfoKeterangan").innerHTML = x;
}

function wait_asal() {
	show_wait("InfoSekolah"); //lihat div id 
}

function show_asalsekolah(x) {	
	document.getElementById("InfoSekolah").innerHTML = x;
	change_departemen(0);
}

function focusNext(elemName, evt) {
	evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
   
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		return false;
    } 
    return true;
}

function panggil(elem){
	document.getElementById(elem).style.background='#4cff15';
}

function unfokus(elem){
	document.getElementById(elem).style.background='#FFFFFF';
}