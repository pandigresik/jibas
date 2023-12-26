function getfresh(){
	var page = document.getElementById('page').value;
	document.location.href = "penerbit.php?page="+page;
}
function hapus(id){
	if (confirm('Anda yakin akan menghapus penerbit ini?'))
		document.location.href = "penerbit.php?op=del&id="+id;
}
function tambah(){
	newWindow('penerbit.add.php', 'TambahPenerbit','355','448','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cetak(){
	//page = document.getElementById('page').value;
	newWindow('penerbit.cetak.php', 'CetakPenerbit','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function ubah(id){
	newWindow('penerbit.edit.php?id='+id, 'UbahPenerbit','355','448','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function validate(){
	var kode = document.getElementById('kode').value;
	var nama = document.getElementById('nama').value;
	if (kode.length==0){
		alert ('Anda harus mengisikan nilai untuk kode penerbit!');
		document.getElementById('kode').focus();
		return false;
	}
	if (nama.length==0){
		alert ('Anda harus mengisikan nilai untuk nama penerbit!');
		document.getElementById('nama').focus();
		return false;
	}
}
function success(){
	parent.opener.getfresh();
	window.close();
}
function change_page(page) {
	if (page=="XX")
		page = document.getElementById('page').value;
	document.location.href="penerbit.php?page="+page;
}
function ViewByTitle(id){
	//State:
	//1.Perpustakaan
	//2.Format
	//3.Rak
	//4.Katalog
	//5.Penerbit
	//6.Penulis
	var addr = "../lib/ViewByTitle.php?id="+id+"&state=5";
	newWindow(addr, 'LihatJudul','687','578','resizable=1,scrollbars=1,status=0,toolbar=0')
}
