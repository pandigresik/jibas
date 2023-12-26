function chg_perpus()
{
	var perpustakaan = document.getElementById('perpustakaan').value;
	document.location.href="pustaka.daftar.php?perpustakaan="+perpustakaan;
}

function lihat(id)
{
	newWindow('pustaka.view.detail.php?replid='+id, 'DetailPustaka','509','456','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cetak(id)
{
	if (id=='XX')
	{
		var perpustakaan = document.getElementById('perpustakaan').value;
		newWindow('pustaka.print.php?asal=daftar&perpustakaan='+perpustakaan, 'CetakPustaka','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
	}
	else
	{
		newWindow('pustaka.print.detail.php?replid='+id, 'CetakDetailPustaka','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
	}
}

function cetak_nomor(id, perpus)
{
	newWindow('pustaka.print.nomor.php?replid='+id+'&perpustakaan='+perpus, 'CetakNomorPustaka','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function aturpustaka(idpustaka, perpus)
{
	document.location.href = "pustaka.adddel.php?idpustaka="+idpustaka+"&perpus="+perpus;
}

function ubah(id,asal,perpustakaan,kategori,keywords)
{
	document.location.href = "pustaka.ubah.php?replid="+id+"&asal="+asal+"&perpustakaan="+perpustakaan+"&kategori="+kategori+"&keywords="+keywords;
}

function hapus(id)
{
	var perpustakaan = document.getElementById('perpustakaan').value;
	if (confirm('Anda yakin akan menghapus pustaka ini?'))
		document.location.href = "pustaka.daftar.php?op=Gtytc6yt665476d6&replid="+id+"&perpustakaan="+perpustakaan;
}

function change_page(page)
{
	var perpustakaan = document.getElementById('perpustakaan').value;
	if (page == "XX")
		page = document.getElementById('page').value;
		
	document.location.href="pustaka.daftar.php?page="+page+"&perpustakaan="+perpustakaan;
}
