function Tambah()
{
    var nip = document.getElementById('nip').value;
	var addr = "goladd.php?nip="+nip;
    newWindow(addr, 'TambahGolongan','480','250','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Ubah(id)
{
	var addr = "goledit.php?id="+id;
    newWindow(addr, 'UbahGolongan','480','285','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Refresh()
{
	var r = Math.floor((Math.random()*1000000)+1);
    var nip = document.getElementById('nip').value;
	document.location.href = "daftargolongan.php?r="+r+"&nip="+nip;
}

function Hapus(id)
{
	if (confirm("Apakah anda yakin akan menghapus data ini?"))
	{
		var nip = document.getElementById('nip').value;
		document.location.href = "daftargolongan.php?id="+id+"&op=mnrmd2re2dj2mx2x2x3d2s33&nip="+nip;
	}
}

function ChangeLast(id)
{
	if (confirm('Apakah anda yakin akan mengubah menjadi golongan yang aktif saat ini?'))
    {
        var nip = document.getElementById('nip').value;
		document.location.href = "daftargolongan.php?id="+id+"&op=cn0948cm2478923c98237n23&nip="+nip;
    }
}

function Cetak() {
    var nip = document.getElementById('nip').value;
	newWindow('daftargolongan_cetak.php?nip='+nip, 'CetakDaftarGolongan','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
