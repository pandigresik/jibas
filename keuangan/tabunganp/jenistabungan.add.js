function validasi()
{
	return validateEmptyText('nama', 'Nama Jenis Penerimaan') &&
           validateEmptyText('rekkas', 'Rekening Kas') &&
           validateEmptyText('rekutang', 'Rekening Utang') &&
           validateMaxText('keterangan', 255, 'Keterangan Jenis Penerimaan');
}

function accept_rekening(kode, nama, flag)
{
	if (flag == 1)
	{
		document.getElementById('rekkas').value = kode + " " + nama;
		document.getElementById('norekkas').value = kode;
	}
	else if (flag == 2)
	{
		document.getElementById('rekutang').value = kode + " " + nama;
		document.getElementById('norekutang').value = kode;
	}
}

function cari_rek(flag, kategori)
{
	newWindow('../carirek.php?option=ro&flag='+flag+'&kategori='+kategori, 'CariRekening','550','438','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function focusNext(elemName, evt)
{
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13)
	{
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function panggil(elem)
{
	var lain = new Array('nama','keterangan');
	for (i=0;i<lain.length;i++)
	{
		if (lain[i] == elem)
		{
			document.getElementById(elem).style.background='#FFFF99';
		}
		else
		{
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}