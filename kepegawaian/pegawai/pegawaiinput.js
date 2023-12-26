function validate() 
{
	return validateEmptyText('txNama', 'Nama Pegawai') &&
		   validateEmptyText('txNIP', 'NIP Pegawai') && 
		   validateEmptyText('txTmpLahir', 'Tempat Kelahiran Pegawai') && 
		   validateEmptyText('txThnLahir', 'Tahun Kelahiran Pegawai') && 
		   validateInteger('txThnLahir', 'Tahun Kelahiran Pegawai') && 
		   validateLength('txThnLahir', 'Tahun Kelahiran Pegawai', 4) && 
		   validateEmptyText('txThnMulai', 'Tahun Mulai Kerja Pegawai') && 
		   validateInteger('txThnMulai', 'Tahun Mulai Kerja Pegawai') && 
		   validateLength('txThnMulai', 'Tahun Mulai Kerja Pegawai', 4) &&
		   confirm("Data sudah lengkap?");
}

function focusNext(elemName, evt)
{
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13)
	{
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}

function tambah_suku(){
	newWindow('../library/suku.php', 'tambahSuku','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function tambah_agama(){
	newWindow('../library/agama.php', 'tambahAgama','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function kirim_agama(agama_kiriman){	
	agama=agama_kiriman;
	setTimeout("refresh_agama(agama)",1);
}

function refresh_agama(kode){
	wait_agama();
	
	if (kode==0){
		sendRequestText("../library/getagama.php", show_agama, "agama=");
	} else {
		sendRequestText("../library/getagama.php", show_agama, "agama="+kode);
	}
}

function wait_agama() {
	show_wait("agama_info"); 
}

function show_agama(x) {
	document.getElementById("agama_info").innerHTML = x;
}

function ref_del_agama(){
	setTimeout("refresh_agama(0)",1);
}

function suku_kiriman(suku_kiriman) {	
	suku = suku_kiriman;
	setTimeout("refresh_suku(suku)",1);
}

function refresh_suku(kode){
	wait_suku();
	if (kode==0){
		sendRequestText("../library/getsuku.php", show_suku, "suku=");
	} else {
		sendRequestText("../library/getsuku.php", show_suku, "suku="+kode);
	}
}

function wait_suku() {
	show_wait("suku_info"); 
}

function show_suku(x) {
	document.getElementById("suku_info").innerHTML = x;
}

function refresh_delete(){
	setTimeout("refresh_suku(0)",1);
}