function getfresh(){
	document.location.href = "format.php";
}
function hapus(id){
	if (confirm('Anda yakin akan menghapus format ini?'))
		document.location.href = "format.php?op=del&id="+id;
}
function tambah(){
	newWindow('format.add.php', 'TambahFormat','360','230','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak(){
	newWindow('format.cetak.php', 'CetakFormat','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function ubah(id){
	newWindow('format.edit.php?id='+id, 'UbahFormat','360','230','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function validate(){
	var kode = document.getElementById('kode').value;
	var nama = document.getElementById('nama').value;
	if (kode.length==0){
		alert ('Anda harus mengisikan nilai untuk kode!');
		document.getElementById('kode').focus();
		return false;
	}
	if (nama.length==0){
		alert ('Anda harus mengisikan nilai untuk nama!');
		document.getElementById('nama').focus();
		return false;
	}
}
function success(){
	parent.opener.getfresh();
	window.close();
}
function ViewByTitle(id){
	//State:
	//1.Perpustakaan
	//2.Format
	//3.Rak
	//4.Katalog
	//5.Penerbit
	//6.Penulis
	var addr = "../lib/ViewByTitle.php?id="+id+"&state=2";
	newWindow(addr, 'LihatJudul','687','578','resizable=1,scrollbars=1,status=0,toolbar=0')
}
