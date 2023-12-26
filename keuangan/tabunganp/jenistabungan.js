function change_jenis()
{
	var departemen = document.getElementById('departemen').value;
	document.location.href = "jenistabungan.php?departemen="+departemen;
}

function change_dep()
{
	change_jenis();
}

function refresh()
{
	var page = document.getElementById('page').value;
	var hal = document.getElementById('hal').value;
	var varbaris = document.getElementById('varbaris').value;
	var departemen = document.getElementById('departemen').value;
	
	document.location.href = "jenistabungan.php?departemen="+departemen+"&page="+page+"&hal="+hal+"&varbaris="+varbaris;
}

function refreshAll()
{
	var departemen = document.getElementById('departemen').value;
	
	document.location.href = "jenistabungan.php?departemen="+departemen;
}

function set_aktif(id, aktif)
{
	var newaktif;
	var msg;
	
	if (aktif == 1)
	{
		newaktif = 0;	
		msg = "Apakah anda yakin akan mengganti status data ini menjadi TIDAK AKTIF?";
	}
	else
	{
		newaktif = 1;	
		msg = "Apakah anda yakin akan mengganti status data ini menjadi AKTIF?";
	}
	
	if (confirm(msg))
	{
		var page = document.getElementById('page').value;
		var hal = document.getElementById('hal').value;
		var varbaris = document.getElementById('varbaris').value;
		var departemen = document.getElementById('departemen').value;
				
		document.location.href = "jenistabungan.php?op=d28xen32hxbd32dn239dx&departemen="+departemen+"&id="+id+"&newaktif="+newaktif+"&page="+page+"&hal="+hal+"&varbaris="+varbaris;
	}
}

function hapus(id)
{
	if (confirm("Apakah anda yakin akan menghapus data ini?"))
	{
		var page = document.getElementById('page').value;
		var hal = document.getElementById('hal').value;
		var varbaris = document.getElementById('varbaris').value;
		var departemen = document.getElementById('departemen').value;
		
		document.location.href = "jenistabungan.php?op=12134892y428442323x423&departemen="+departemen+"&id="+id+"&page="+page+"&hal="+hal+"&varbaris="+varbaris;
	}
}

function cetak()
{
	var departemen = document.getElementById('departemen').value;
	var total = document.getElementById("total").value;
	var page = document.getElementById('departemen').value;
	var varbaris = document.getElementById('varbaris').value;
	
	var addr = "jenistabungan.cetak.php?departemen="+departemen+"&varbaris="+varbaris+"&page="+page+"&total="+total;
	newWindow(addr, 'CetakJenisTabungan','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah()
{
	var departemen = document.getElementById('departemen').value;
	newWindow('jenistabungan.add.php?departemen='+departemen, 'JenisTabungan','500','395','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function focusNext(elemName, evt)
{
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function change_page(page)
{
	var departemen = document.getElementById('departemen').value;
	var varbaris = document.getElementById("varbaris").value;
		
	document.location.href = "jenistabungan.php?page="+page+"&varbaris="+varbaris+"&hal="+page+"&idkategori="+idkategori+"&departemen="+departemen;
}

function change_hal()
{
	var hal = document.getElementById("hal").value;
	var varbaris = document.getElementById("varbaris").value;
	var departemen = document.getElementById('departemen').value;
		
	document.location.href="jenistabungan.php?page="+hal+"&hal="+hal+"&varbaris="+varbaris+"&departemen="+departemen;
}

function change_baris()
{
	var departemen = document.getElementById('departemen').value;
	var varbaris = document.getElementById("varbaris").value;
	
	document.location.href="jenistabungan.php?varbaris="+varbaris+"&departemen="+departemen;
}