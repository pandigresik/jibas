function getfresh(rak){
	if (rak=="XX")
	{
		rak = document.getElementById('rak').value;
	}
	document.location.href = "katalog.php?rak="+rak;
}
function hapus(id){
	var rak = document.getElementById('rak').value;
	if (confirm('Anda yakin akan menghapus katalog ini?'))
		document.location.href = "katalog.php?op=del&id="+id+"&rak="+rak;
}
function tambah(){
	var rak = document.getElementById('rak').value;
	newWindow('katalog.add.php?rak='+rak, 'TambahKatalog','355','250','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak(){
	var rak = document.getElementById('rak').value;
	newWindow('katalog.cetak.php?rak='+rak, 'CetakKatalog','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function ubah(id){
	newWindow('katalog.edit.php?id='+id, 'UbahKatalog','355','250','resizable=1,scrollbars=1,status=0,toolbar=0')
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
	var addr = "../lib/ViewByTitle.php?id="+id+"&state=4";
	newWindow(addr, 'LihatJudul','687','578','resizable=1,scrollbars=1,status=0,toolbar=0')
}