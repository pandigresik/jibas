function chg_perpus(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	var kategori = document.getElementById('kategori').value;
	document.location.href="pustaka.cari.php?perpustakaan="+perpustakaan+"&kategori="+kategori;
}
function chg_kat(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	var kategori = document.getElementById('kategori').value;
	document.location.href="pustaka.cari.php?perpustakaan="+perpustakaan+"&kategori="+kategori;
}
function chg_key(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	var kategori = document.getElementById('kategori').value;
	var keywords = document.getElementById('keywords').value;
	document.location.href="pustaka.cari.php?perpustakaan="+perpustakaan+"&kategori="+kategori+"&keywords="+keywords;
}
function cari(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	var kategori = document.getElementById('kategori').value;
	var keywords = document.getElementById('keywords').value;
	if (kategori=='tahun')
	{
		if (isNaN(keywords))
		{
			alert ('Tahun terbit harus berupa bilangan!');
			document.getElementById('keywords').value="";
			document.getElementById('keywords').focus();
			return false();
		}
	}
	document.location.href="pustaka.cari.php?perpustakaan="+perpustakaan+"&kategori="+kategori+"&keywords="+keywords+"&cari=1";
}
function lihat(id){
	newWindow('pustaka.view.detail.php?replid='+id, 'DetailPustaka','509','456','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak_nomor(id,perpus){
	newWindow('pustaka.print.nomor.php?replid='+id+'&perpustakaan='+perpus, 'CetakNomorPustaka','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak(id){
	if (id=='XX')
	{
		var perpustakaan = document.getElementById('perpustakaan').value;
		var kategori = document.getElementById('kategori').value;
		var keywords = document.getElementById('keywords').value;
		newWindow('pustaka.print.php?asal=cari&perpustakaan='+perpustakaan+"&kategori="+kategori+"&keywords="+keywords, 'CetakPustaka','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
	}
	else
	{
		newWindow('pustaka.print.detail.php?replid='+id, 'CetakDetailPustaka','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
	}
}
function ubah(id,asal,perpustakaan,kategori,keywords){
	document.location.href = "pustaka.ubah.php?replid="+id+"&asal="+asal+"&perpustakaan="+perpustakaan+"&kategori="+kategori+"&keywords="+keywords;
}
function change_page(page) {
	var perpustakaan = document.getElementById('perpustakaan').value;
	var kategori = document.getElementById('kategori').value;
	var keywords = document.getElementById('keywords').value;
	if (page=="XX")
		page = document.getElementById('page').value;
	document.location.href="pustaka.cari.php?perpustakaan="+perpustakaan+"&kategori="+kategori+"&keywords="+keywords+"&cari=1&page="+page;
}

function aturpustaka(idpustaka, perpus)
{
	document.location.href = "pustaka.adddel.php?idpustaka="+idpustaka+"&perpus="+perpus;
}