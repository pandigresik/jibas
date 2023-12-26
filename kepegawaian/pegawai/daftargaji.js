function Tambah()
{
    var nip = document.getElementById('nip').value;
	var addr = "gajiaddjadwal.php?nip="+nip;
    newWindow(addr, 'TambahJadwalGaji','480','250','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Ubah(id)
{
	var addr = "gajieditjadwal.php?id="+id;
    newWindow(addr, 'UbahJadwalGaji','480','250','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Refresh()
{
    var nip = document.getElementById('nip').value;
	document.location.href = "daftargaji.php?nip="+nip;
}

function Hapus(id)
{
	if (confirm('Apakah anda yakin akan menghapus jadwal ini?'))
    {
        var nip = document.getElementById('nip').value;
		document.location.href = "daftargaji.php?id="+id+"&op=1dn09387120n89x713891203712089312&nip="+nip;
    }
}

function UbahR(id)
{
	var addr = "gajiedit.php?id="+id;
    newWindow(addr, 'UbahGaji','480','250','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function TambahR()
{
    var nip = document.getElementById('nip').value;
	var addr = "gajiadd.php?nip="+nip;
    newWindow(addr, 'TambahGaji','480','250','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function HapusR(id)
{
	if (confirm("Apakah anda yakin akan menghapus data ini?"))
	{
		var nip = document.getElementById('nip').value;
		document.location.href = "daftargaji.php?id="+id+"&op=mnrmd2re2dj2mx2x2x3d2s33&nip="+nip;
	}
}

function Cetak()
{
    var nip = document.getElementById('nip').value;
	newWindow('daftargaji_cetak.php?nip='+nip, 'CetakDaftarGaji','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function ChangeLast(id)
{
	if (confirm('Apakah anda yakin akan mengubah menjadi gaji yang aktif saat ini?'))
    {
        var nip = document.getElementById('nip').value;
		document.location.href = "daftargaji.php?id="+id+"&op=cn0948cm2478923c98237n23&nip="+nip;
    }
}