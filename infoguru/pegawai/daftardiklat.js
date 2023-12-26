function Tambah()
{
    var nip = document.getElementById('nip').value;
	var addr = "diklatadd.php?nip="+nip;
    newWindow(addr, 'TambahDiklat','450','250','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Ubah(id)
{
	var addr = "diklatedit.php?id="+id;
    newWindow(addr, 'UbahDiklat','450','285','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Refresh()
{
    var nip = document.getElementById('nip').value;
	document.location.href = "daftardiklat.php?nip="+nip;
}

function Hapus(id)
{
	if (confirm("Apakah anda yakin akan menghapus data ini?"))
	{
		var nip = document.getElementById('nip').value;
		document.location.href = "daftardiklat.php?id="+id+"&op=mnrmd2re2dj2mx2x2x3d2s33&nip="+nip;
	}
}

function ChangeLast(id)
{
	if (confirm('Apakah anda yakin akan mengubah data ini menjadi diklat terakhir?'))
    {
        var nip = document.getElementById('nip').value;
		document.location.href = "daftardiklat.php?id="+id+"&op=cn0948cm2478923c98237n23&nip="+nip;
    }
}

function Cetak()
{
    var nip = document.getElementById('nip').value;
	newWindow('daftardiklat_cetak.php?nip='+nip, 'CetakDaftarDiklat','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}