function getfresh(){
	var page = document.getElementById('page').value;
	document.location.href = "penulis.php?page="+page;
}
function hapus(id){
	if (confirm('Anda yakin akan menghapus penulis ini?'))
		document.location.href = "penulis.php?op=del&id="+id;
}
function tambah(){
	newWindow('penulis.add.php', 'TambahPenulis','377','433','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak(){
	newWindow('penulis.cetak.php', 'CetakPenulis','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function ubah(id){
	newWindow('penulis.edit.php?id='+id, 'UbahPenulis','377','433','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function validate(){
	var kode = document.getElementById('kode').value;
	var nama = document.getElementById('nama').value;
	if (kode.length==0){
		alert ('Anda harus mengisikan nilai untuk kode penulis!');
		document.getElementById('kode').focus();
		return false;
	}
	if (nama.length==0){
		alert ('Anda harus mengisikan nilai untuk nama penulis!');
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
	document.location.href="penulis.php?page="+page;
}
function ViewByTitle(id){
	//State:
	//1.Perpustakaan
	//2.Format
	//3.Rak
	//4.Katalog
	//5.Penerbit
	//6.Penulis
	var addr = "../lib/ViewByTitle.php?id="+id+"&state=6";
	newWindow(addr, 'LihatJudul','687','578','resizable=1,scrollbars=1,status=0,toolbar=0')
}
