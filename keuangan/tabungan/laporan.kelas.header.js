var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
var spryselect1 = new Spry.Widget.ValidationSelect("idangkatan");	
var spryselect1 = new Spry.Widget.ValidationSelect("idtingkat");
var spryselect2 = new Spry.Widget.ValidationSelect("idkelas");
var spryselect3 = new Spry.Widget.ValidationSelect("lunas");
var spryselect4 = new Spry.Widget.ValidationSelect("idpenerimaan");
var spryselect4 = new Spry.Widget.ValidationSelect("idkategori");
    
function change_dep()
{
	var dep = document.getElementById('departemen').value;
		
	document.location.href = "laporan.kelas.header.php?departemen="+dep;
	parent.content.location.href = "laporan.kelas.blank.php";
}

function change_ang()
{
	var idtabungan = document.getElementById('idtabungan').value;
	var dep = document.getElementById('departemen').value;
	var idtingkat = document.getElementById('idtingkat').value;
    var idkelas = document.getElementById('idkelas').value;
    var idang = document.getElementById('idangkatan').value;
	
	document.location.href = "laporan.kelas.header.php?idtabungan="+idtabungan+"&departemen="+dep+"&idangkatan="+idang+"&idtingkat="+idtingkat+"&idkelas="+idkelas;
	parent.content.location.href = "laporan.kelas.blank.php";
}

function change_kelas()
{
	parent.content.location.href = "laporan.kelas.blank.php";
}

function change_tabungan()
{
	parent.content.location.href = "laporan.kelas.blank.php";
}

function show_pembayaran()
{
	var dep = document.getElementById('departemen').value;
	var idtabungan = document.getElementById('idtabungan').value;
	var idangkatan = document.getElementById('idangkatan').value;
	var idtingkat = document.getElementById('idtingkat').value;
	var idkelas = document.getElementById('idkelas').value;
	
	if (idangkatan.length == 0)
    {	
		alert ('Pastikan angkatan sudah ada!');	
		document.getElementById('idangkatan').focus();
		return false;		
	}
    else if (idtabungan.length == 0)
    {
		alert ('Pastikan jenis tabungan sudah ada!');
		document.getElementById('idtabungan').focus();
		return false;	
	}
    	
	parent.content.location.href = "laporan.kelas.content.php?idtabungan="+idtabungan+"&idkelas="+idkelas+"&idangkatan="+idangkatan+"&idtingkat="+idtingkat+"&departemen="+dep;
}

function focusNext(elemName, evt) {
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :
		((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) {
		if (elemName == "tampil") 
			show_pembayaran();
		else 
			document.getElementById(elemName).focus();
		return false;
	}
	return true;
}