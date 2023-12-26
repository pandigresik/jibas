function getfresh(){
	document.location.href = "pustaka.php";
}
function hapus(id){
	if (confirm('Anda yakin akan menghapus perpustakaan ini?'))
		document.location.href = "pustaka.php?op=del&id="+id;
}
function cetak(){
	newWindow('pustaka.cetak.php', 'CetakPerpustakaan','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah(){
	newWindow('pustaka.add.php', 'TambahPerpustakaan','360','230','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function ubah(id){
	newWindow('pustaka.edit.php?id='+id, 'UbahPerpustakaan','360','230','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function validate(){
	var nama = document.getElementById('nama').value;
	if (rak.length==0){
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
	var addr = "../lib/ViewByTitle.php?id="+id+"&state=1";
	newWindow(addr, 'LihatJudul','687','578','resizable=1,scrollbars=1,status=0,toolbar=0')
}