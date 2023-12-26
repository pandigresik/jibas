function ValidateSubmit() 
{
	var isok = 	validateEmptyText('jbayar', 'Besarnya Tabungan') &&
                validateEmptyText('rekkas', 'Rekening Kas Tabungan') &&
			 	validasiAngka() &&
		    	validateEmptyText('tbayar', 'Tanggal Pembayaran') &&
		    	validateEmptyText('alasan', 'Alasan Perubahan') &&
		    	validateMaxText('alasan', 500, 'Alasan Perubahan') &&
		    	validateMaxText('keterangan', 255, 'Keterangan Cicilan') &&
		    	confirm('Data sudah benar?');	
	
	document.getElementById('issubmit').value = isok ? 1 : 0;
	
	if (isok)
		document.main.submit();
	else
		document.getElementById('Simpan').disabled = false;
}

function validasiAngka() 
{
	var angka = document.getElementById("angkabayar").value;
	if(isNaN(angka)) 
	{
		alert ('Besar tabungan harus berupa bilangan!');
		document.getElementById('jbayar').value = "";
		document.getElementById('jbayar').focus();
		return false;
	}
	else if(parseInt(angka) < 0)
	{
		alert ('Besar cicilan harus positif!');
		document.getElementById('jbayar').focus();
		return false;
	}
	
	return true;
}

function salinangka()
{	
	var angka = document.getElementById("jbayar").value;
	document.getElementById("angkabayar").value = angka;
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