function validate()
{
	var urutananak=document.main.urutananak.value;
	var jumlahanak=document.main.jumlahanak.value;
	var jkandung=document.main.jkandung.value;
    var jtiri=document.main.jtiri.value;
	var kodepos=document.main.kodepos.value;
	var jarak=document.main.jarak.value;
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
	var tgllahir = document.main.tgllahir.value;
	var blnlahir = document.main.blnlahir.value;
	var thnlahir = document.main.thnlahir.value;
	var nama = document.main.nama.value;
	var tmplahir = document.main.tmplahir.value;
	var nis = document.main.nis.value;
	var tahunmasuk = document.main.tahunmasuk.value;
	var suku = document.main.suku.value;
	var agama = document.main.agama.value;
	var status = document.main.status.value;
	var kondisi = document.main.status.value;
	var kelas = document.main.kelas.value;
	var kelas_lama = document.main.kelas_lama.value;
	var tahunajaran = document.main.tahunajaran.value;
	var file = document.main.file_data.value;
	
	if (tahunajaran.length == 0)
	{
		alert("Tahun Ajaran tidak boleh kosong!");
		document.getElementById('departemen').focus();
		return false;
	} 
	
	if (kelas.length == 0)
	{
		alert("Kelas Siswa tidak boleh kosong!");
		document.getElementById('departemen').focus();
		return false;
	} 
		
	if (kelas != kelas_lama)
	{
		var kapasitas = parseInt(document.main.kapasitas.value);
		var isi = parseInt(document.main.isi.value);
		if (kapasitas <= isi)
		{
			alert("Kapasitas kelas tidak mencukupi untuk menambah data siswa baru!");
			document.getElementById('kelas').focus();
			return false;	
		}
	}
		
	if(tahunmasuk.length == 0)
	{
       	alert("Anda harus mengisikan data untuk Tahun Masuk!");
		document.main.tahunmasuk.focus();
        return false;
   	}	
	
	if (tahunmasuk.length > 0)
	{	
		if(isNaN(tahunmasuk))
		{
    		alert ('Data tahun masuk harus berupa bilangan!');; 
			document.main.tahunmasuk.focus();
        	return false;
		}
		if (tahunmasuk.length > 4 || tahunmasuk.length < 4)
		{
        	alert("Tahun masuk tidak boleh lebih atau kurang dari 4 karakter!"); 
			document.main.tahunmasuk.focus();
			return false;
    	}
    }
	
	if (nis.length == 0)
	{
		alert("Anda harus mengisikan data untuk NIS siswa!");
		document.getElementById('nis').focus();
		return false;
	}
	
	if (nama.length == 0)
	{
		alert("Anda harus mengisikan data untuk Nama siswa!");
		document.getElementById('nama').focus();
		return false;
	}
	
	if (thnlahir.length > 0)
	{	
		if(isNaN(thnlahir))
		{
    		alert ('Data tahun lahir harus berupa bilangan!');; 
			document.main.thnlahir.focus();
        	return false;
		}
		
		if (thnlahir.length > 4 || thnlahir.length < 4)
		{
        	alert("Tahun lahir tidak boleh lebih atau kurang dari 4 karakter!"); 
			document.main.thnlahir.focus();
			return false;
    	}
    }
	
	if (urutananak.length > 0)
	{
		if(isNaN(urutananak))
		{
			alert ('Data isian anak ke harus berupa bilangan!');
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
	}
	
	if (parseInt(urutananak) > parseInt(jumlahanak))
	{
		alert ('Urutan anak tidak boleh lebih dari jumlah saudara yang ada!');
		document.getElementById ('urutananak').focus();
		return false;
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
		if (hpsiswa.substring(0, 1) != "#" && isNaN(hpsiswa))
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
			alert ('Data berat harus berupa bilangan!');
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
			document.getElementById('penghasilanayah').focus();
			return false;
		}
	}	
	
	if (penghasilanibu.length > 0)
	{
		if(isNaN(penghasilanibu))
		{
			alert ('Data penghasilan Ibu harus berupa bilangan!');
			document.getElementById('penghasilanibu').focus();
			return false;
		}
	}
	
	if (telponortu.length > 0)
	{
		if(isNaN(telponortu))
		{
			alert ('Data telepon orang tua harus berupa bilangan');
			document.getElementById('telponortu').focus();
			return false;
		}
	}
	
	if (hportu.length > 0)
    {
		if (hportu.substring(0, 1) != "#" && isNaN(hportu))
        {
			alert ('Data nomor HP orang tua harus berupa bilangan\nTidak boleh menggunakan spasi.');
			document.getElementById('hportu').focus();
			return false;
		}
	}
    
    if (hportu2.length > 0)
    {
		if (hportu2.substring(0, 1) != "#" && isNaN(hportu2))
        {
			alert ('Data nomor HP orang tua harus berupa bilangan\nTidak boleh menggunakan spasi.');
			document.getElementById('hportu2').focus();
			return false;
		}
	}
    
    if (hportu3.length > 0)
    {
		if (hportu3.substring(0, 1) != "#" && isNaN(hportu3))
        {
			alert ('Data nomor HP orang tua harus berupa bilangan\nTidak boleh menggunakan spasi.');
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
	
	if (thnlahir.length != 0 && blnlahir.length != 0 && tgllahir.length != 0)
	{
		if (thnlahir % 4 == 0)
		{
			 if (blnlahir == 2)
			 {
				  if (tgllahir > 29)
				  {
					   alert ('Maaf, silahkan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+blnlahir+"&tgl="+tgllahir+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
				  }
			 }
			 
			 if (blnlahir == 4 || blnlahir == 6 || blnlahir == 9 || blnlahir == 11)
			 {
				  if (tgllahir>30)
				  {
					   alert ('Maaf, silahkan masukan ulang tanggal lahir!');
					   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+blnlahir+"&tgl="+tgllahir+"&namatgl="+namatgl+"&namabln="+namabln);	
					   document.getElementById("tgllahir").focus();
					   return false;
				  }
			 }
		}
		
		if (thnlahir % 4 != 0)
		{
			if (blnlahir == 2)
			{
				if (tgllahir>28)
				{
					alert ('Maaf, silahkan masukan ulang tanggal lahir!');
					sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+blnlahir+"&tgl="+tgllahir+"&namatgl="+namatgl+"&namabln="+namabln);	
					document.getElementById("tgllahir").focus();
					return false;
				}
			}
			
			if (blnlahir == 4 || blnlahir == 6 || blnlahir == 9 || blnlahir == 11)
			{
				if (tgllahir>30)
				{
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
			document.getElementById("file_data").value='';
			document.main.file_data.focus();
    		document.main.file_data.select();
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
	sendRequestText("siswa_add_getdepasal.php", showDep, "dep="+dep);
	//setTimeout("change_departemen(0)",1);
	
}
function showDep(x) {
	document.getElementById("depInfo").innerHTML = x;
	change_departemen(0);
}
function change_departemen(kode) {	
	var dep_asal = document.getElementById("dep_asal").value;
	wait_sekolah();
	if (kode==0){
		sendRequestText("siswa_add_getsekolah.php", showSekolah, "dep_asal="+dep_asal);
	} else {
		sendRequestText("siswa_add_getsekolah.php", showSekolah, "dep_asal="+dep_asal+"&sekolah="+kode);
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
	newWindow('siswa_add_status.php', 'tambahStatus','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_kondisi(){
	newWindow('siswa_add_kondisi.php', 'tambahKondisi','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_asal_sekolah(){
	var departemen = document.getElementById("dep_asal").value;
	newWindow('siswa_add_asalsekolah.php?departemen='+departemen, 'tambahAsalSekolah','500','400','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_pendidikan(){
	newWindow('siswa_add_pendidikan.php', 'tambahPendidikan','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_pekerjaan(){
	newWindow('siswa_add_pekerjaan.php', 'tambahPekerjaan','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function tutup(){
	parent.opener.refresh();
	window.close();
}

//Ajax suku ==========================================================
function suku_kiriman(suku_kiriman) {	
	suku = suku_kiriman
	//suku_kiriman2=suku_kiriman;
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
		sendRequestText("siswa_add_getstatus.php", show_status,"status=");
	} else {
		sendRequestText("siswa_add_getstatus.php", show_status,"status="+kode);
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
		sendRequestText("siswa_add_getkondisi.php", show_kondisi,"kondisi=");
	} else {
		sendRequestText("siswa_add_getkondisi.php", show_kondisi,"kondisi="+kode);
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
	//refresh_pendidikan(pendidikan);
	//pendidikan=pendidikan_kiriman;
	setTimeout("refresh_pendidikan(pendidikan)",1);
}

function refresh_pendidikan(kode_pendidikan) {
	wait_pendidikan();	
	if (kode_pendidikan==0) {
		sendRequestText("siswa_add_getpendidikan.php", show_pendidikan,"pendidikan=");
		sendRequestText("siswa_add_getpendidikanibu.php", show_pendidikan1,"pendidikan=");
	} else {
		sendRequestText("siswa_add_getpendidikan.php", show_pendidikan,"pendidikan="+kode_pendidikan);
		sendRequestText("siswa_add_getpendidikanibu.php", show_pendidikan1,"pendidikan="+kode_pendidikan);
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
	//refresh_pekerjaan(kerja);
	setTimeout("refresh_pekerjaan(pekerjaan)",1);
}

function refresh_pekerjaan(kode_pekerjaan) {
	wait_pekerjaan();
	if(kode_pekerjaan==0){
		sendRequestText("siswa_add_getpekerjaan.php", show_pekerjaan,"pekerjaan=");
		sendRequestText("siswa_add_getpekerjaanibu.php", show_pekerjaan1,"pekerjaan=");
	} else {
		sendRequestText("siswa_add_getpekerjaan.php", show_pekerjaan,"pekerjaan="+kode_pekerjaan);
		sendRequestText("siswa_add_getpekerjaanibu.php", show_pekerjaan1,"pekerjaan="+kode_pekerjaan);
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

function change_tgl() {	
	var thn = document.getElementById('thnlahir').value;
	var bln = parseInt(document.getElementById('blnlahir').value);	
	var tgl = parseInt(document.main.tgllahir.value);
	
	if(thn.length == 0) {
       	alert("Tahun lahir tidak boleh kosong"); 
		document.main.blnlahir.value = "";
		document.main.thnlahir.focus();
        return false;
	} else {	
		if(isNaN(thn)) {
    		alert("Tahun lahir harus berupa angka"); 
			document.main.thnlahir.focus();
        	return false;
		} else {	
			if (thn.length > 4 || thn.length < 4) {
            	alert("Tahun lahir tidak boleh lebih atau kurang dari 4 karakter"); 
				document.main.thnlahir.focus();
            	return false;
			}
		}
    }
	var namatgl = "tgllahir";
	var namabln = "blnlahir";

	sendRequestText("../library/gettanggal.php", show1, "tahun="+thn+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);	
}
function show1(x) {
	document.getElementById("tgl_info").innerHTML = x;
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
	/*
	var lain = new Array('nama','panggilan','tmplahir','thnlahir','urutananak','jumlahanak','bahasa','kodepos','telponsiswa','hpsiswa','emailsiswa','berat','tinggi','namaayah','namaibu','penghasilanayah1','penghasilanibu1','namawali','telponortu','hportu','emailortu','alamatsiswa','ketsekolah','kesehatan','alamatortu','alamatsurat','keterangan','tgllahir','blnlahir','suku','agama','status','kondisi','sekolah','dep_asal','Infopendidikanayah','Infopendidikanibu','Infopekerjaanayah','Infopekerjaanibu','nis','tahunmasuk','idangkatan','departemen','kelasInfo','tingkatInfo');
	
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
	*/
	document.getElementById(elem).style.background='#c9feb9';
}
function unfokus(elem){
	document.getElementById(elem).style.background='#FFFFFF';
}


function change_dep() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkatInfo').value;	
	
	sendRequestText("siswa_edit_gettahunajaran.php", show_tahunajaran,"departemen="+departemen);
	//sendRequestText("siswa_edit_gettingkat.php", show_tingkat,"departemen="+departemen);
	//sendRequestText("siswa_edit_getkelas.php", show_kelas,"tahunajaran="+tahunajaran+"&tingkat="+tingkat);	
	//sendRequestText("siswa_edit_getkelas.php", show_kelas,"departemen="+departemen);
	
	sendRequestText("siswa_edit_getangkatan.php", show_angkatan,"departemen="+departemen);
	sendRequestText("../siswa_baru/getasalsekolah1.php", show_asalsekolah,"departemen="+departemen);
	//change_tingkat();
}

function show_tahunajaran(x) {
	var departemen = document.getElementById("departemen").value;
	document.getElementById("tahunajaranInfo").innerHTML = x;
	sendRequestText("siswa_edit_gettingkat.php", show_tingkat,"departemen="+departemen);
	
}

function show_tingkat(x) {
	document.getElementById("InfoTingkat").innerHTML = x;		
	change_tingkat();
}

function show_angkatan(x) {
	document.getElementById("InfoAngkatan").innerHTML = x;
}

function change_tingkat() {
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkatInfo').value;	
	
	sendRequestText("siswa_edit_getkelas.php", show_kelas,"tahunajaran="+tahunajaran+"&tingkat="+tingkat);
}

function show_kelas(x) {
	document.getElementById("InfoKelas").innerHTML = x;
	change_kelas();	
}

function change_kelas() {
	var kelas = document.getElementById('kelasInfo').value;	
	sendRequestText("siswa_edit_getkapasitas.php", show_kapasitas,"kelas="+kelas);	
}

function show_kapasitas(x) {
	document.getElementById("InfoKapasitas").innerHTML = x;	
}

function show_asalsekolah(x) {	
	document.getElementById("InfoSekolah").innerHTML = x;
	change_departemen(0);
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
	//alert ('Masuk');
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