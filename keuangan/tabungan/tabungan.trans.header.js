function change_dep()
{
	var departemen = document.getElementById('departemen').value;
	document.location.href = "tabungan.trans.header.php?departemen="+departemen;
	parent.contentblank.location.href = "tabungan.trans.blank.php";
}

function change_tabungan()
{
	parent.contentblank.location.href = "tabungan.trans.blank.php";
}

function show_pembayaran()
{
	var idtabungan = document.getElementById('idtabungan').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var departemen = document.getElementById('departemen').value;
					
	if (idtahunbuku.length == 0)
    {
		alert ('Tahun buku tidak boleh kosong !');
		document.getElementById('idtahunbuku').focus();
		return false;			
	}
    else if (idtabungan.length == 0)
    {
		alert ('Pastikan jenis tabungan sudah ada!');
		document.getElementById('idtabungan').focus();
		return false;	
	}
	
	parent.contentblank.location.href = "tabungan.trans.main.php?idtabungan="+idtabungan+"&idtahunbuku="+idtahunbuku+"&departemen="+departemen;	
}