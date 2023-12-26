function TambahS()
{
    var nip = document.getElementById('nip').value;
	var addr = "keluargaadd.php?nip="+nip;
    newWindow(addr, 'TambahKeluarga','450','290','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Ubah(id)
{
	var addr = "keluargaedit.php?id="+id;
    newWindow(addr, 'UbahKeluarga','450','290','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Refresh()
{
    var nip = document.getElementById('nip').value;
	document.location.href = "daftarkeluarga.php?nip="+nip;
}

function Hapus(id)
{
	if (confirm("Apakah anda yakin akan menghapus data ini?"))
	{
		var nip = document.getElementById('nip').value;
		document.location.href = "daftarkeluarga.php?id="+id+"&op=mnrmd2re2dj2mx2x2x3d2s33&nip="+nip;
	}
}

function Cetak()
{
    var nip = document.getElementById('nip').value;
	newWindow('daftarkeluarga_cetak.php?nip='+nip, 'CetakDaftarKeluarga','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
