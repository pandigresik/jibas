
function Refresh()
{
    var nip = document.getElementById('nip').value;
	document.location.href = "daftarpensiun.php?nip="+nip;
}

function Cetak()
{
    var nip = document.getElementById('nip').value;
	newWindow('daftarpensiun_cetak.php?nip='+nip, 'CetakDaftarPensiun','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}