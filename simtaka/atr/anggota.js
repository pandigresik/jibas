function getfresh(){
	document.location.href = "anggota.php";
}
function hapus(id){
	if (confirm('Anda yakin akan menghapus anggota ini?'))
		document.location.href = "anggota.php?op=del&id="+id;
}
function tambah(){
	newWindow('anggota.add.php', 'TambahAnggota','390','493','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak(){
	newWindow('anggota.cetak.php', 'CetakAnggota','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function ubah(id){
	newWindow('anggota.edit.php?replid='+id, 'UbahAnggota','433','459','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function validate(){
	var nama = document.getElementById('nama').value;
	var alamat = document.getElementById('alamat').value;
	if (nama.length==0){
		alert ('Anda harus mengisikan nilai untuk nama!');
		document.getElementById('nama').focus();
		return false;
	}
	if (alamat.length==0){
		alert ('Anda harus mengisikan nilai untuk alamat!');
		document.getElementById('alamat').focus();
		return false;
	}
}
function success(){
	parent.opener.getfresh();
	window.close();
}
function setaktif(id,newaktif){
	var msg;
	if (newaktif==1)
	{
		msg='Apakah Anda yakin akan mengaktifkan anggota ini?';
	} else {
		msg='Apakah Anda yakin akan menonaktifkan anggota ini?';
	}
	if (confirm(msg))
	{
		document.location.href = "anggota.php?op=nyd6j287sy388s3h8s8&replid="+id+"&newaktif="+newaktif;
	}
}