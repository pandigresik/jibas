function Tambah()
{
    var nip = document.getElementById('nip').value;
	var addr = "pensiunadd.php?nip="+nip;
    newWindow(addr, 'TambahPensiun','480','250','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Ubah(id)
{
	var addr = "pensiunedit.php?id="+id;
    newWindow(addr, 'UbahPensiun','480','250','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Refresh()
{
    var nip = document.getElementById('nip').value;
	document.location.href = "daftarpensiun.php?nip="+nip;
}

function Hapus(id)
{
	if (confirm('Apakah anda yakin akan menghapus data ini?'))
    {
        var nip = document.getElementById('nip').value;
		document.location.href = "daftarpensiun.php?id="+id+"&op=1dn09387120n89x713891203712089312&nip="+nip;
    }
}

function Cetak()
{
    var nip = document.getElementById('nip').value;
	newWindow('daftarpensiun_cetak.php?nip='+nip, 'CetakDaftarPensiun','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}