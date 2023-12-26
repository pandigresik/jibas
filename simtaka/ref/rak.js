function getfresh(){
	document.location.href = "rak.php";
}
function hapus(id){
	if (confirm('Anda yakin akan menghapus rak ini?'))
		document.location.href = "rak.php?op=del&id="+id;
}
function tambah(){
	newWindow('rak.add.php', 'TambahRak','360','230','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak(){
	newWindow('rak.cetak.php', 'CetakRak','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function ubah(id){
	newWindow('rak.edit.php?id='+id, 'UbahRak','360','230','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function validate(){
	var rak = document.getElementById('rak').value;
	if (rak.length==0){
		alert ('Anda harus mengisikan nilai untuk rak!');
		document.getElementById('rak').focus();
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
	var addr = "../lib/ViewByTitle.php?id="+id+"&state=3";
	newWindow(addr, 'LihatJudul','687','578','resizable=1,scrollbars=1,status=0,toolbar=0')
}
