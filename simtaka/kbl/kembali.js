jQuery(document).ready(function() {
	    var accd = $('#kodepustaka').val();
		$('#kodepustaka').autocomplete("GetCode.php?search="+accd, {
			width: 400,
			selectFirst: false			
		});
});

function ProsesKode()
{
	var kodepustaka = $.trim($('#kodepustaka').val());
	if (kodepustaka.length == 0)
		return;

	document.location.href = "kembali.php?op=ViewPeminjaman&kodepustaka="+kodepustaka;
}

function BatalkanPengembalian()
{
	var kodepustaka = document.getElementById('kodepustaka').value;
	document.location.href = "kembali.php?kodepustaka="+kodepustaka;
}

function TakeDate(elementid){
    var addr = "../lib/cals.php?elementid="+elementid;
    newWindow(addr, 'CariTanggal','338','216','resizable=0,scrollbars=0,status=0,toolbar=0')
}

function Kembalikan()
{
	var kodepustaka = document.getElementById('kodepustaka').value;
	var idpinjam = document.getElementById('idpinjam').value;
	var denda = document.getElementById('denda').value;
	var telat = document.getElementById('telat').value;
	var keterangan = document.getElementById('keterangan').value;
	var msg;
	if (isNaN(denda))
	{	
		alert ('Besarnya denda harus berupa bilangan');
		document.getElementById('dendanya').focus();
		return false;
	}
	if (denda!=0)
	{
		msg = "Data sudah benar?\nBesar denda "+numberToRupiah(denda);
	} else {
		msg = "Data sudah benar?";
	}
	if (confirm(msg))
	{
		document.location.href = "kembali.php?op=KembalikanPustaka&idpinjam="+idpinjam+"&denda="+denda+"&kodepustaka="+kodepustaka+"&telat="+telat+"&keterangan="+keterangan;
	}
}

function KeyPress(elemName, evt) {
    var KodeValue=document.getElementById('kodepustaka').value;
	evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		if (KodeValue=="Tidak ada Kode Pustaka yang sedang dipinjam")
		{
			document.getElementById('kodepustaka').value="";
		} else {
			ProsesKode();
		}
        return false;
    }
    return true;
}

function OnEnterKodePustaka(e)
{
    var keycode = (e.keyCode ? e.keyCode : e.which);
	if (keycode != 13)
		return;

	var kode = $.trim($('#kodepustaka').val());
	if (kode.length == 0)
		return;

    ProsesKode();
}