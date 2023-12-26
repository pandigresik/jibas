jQuery(document).ready(function()
{
	var accds = $('#kodepustaka').val();
	$('#kodepustaka').autocomplete("GetCode.php?search="+accds, {
		width: 400,
		selectFirst: false
	});

    setTimeout(function () {
        $('#txBarcode').focus();
    }, 300);
});

function getfresh(){
	document.location.href = "pinjam.php";
}

function cari(){
	var num = document.getElementById('num').value;
	var status = document.getElementById('statuspeminjam').value;
	var addr;
	if (status=='0')
	{
		addr = "../lib/pegawai.php";
	}
	if (status=='1')
	{
		addr = "../lib/siswa.php";
	}
	if (status=='2')
	{
		addr = "../lib/anggota.php";
	}
		
	if (num==0)	{
		newWindow(addr, 'CariPeminjam','523','425','resizable=1,scrollbars=1,status=0,toolbar=0')
	} else {
		if (confirm('Peminjaman belum disimpan, \nAnda yakin akan membatalkan peminjaman?')){
			newWindow(addr, 'CariPeminjam','523','425','resizable=1,scrollbars=1,status=0,toolbar=0')
			CancelPeminjamanByReq(num);
		}
	}
}

function focusKodePustaka()
{
	$('#kodepustaka').focus();	
}

function acceptPegawai(noanggota,nama,flag)
{
	var status = document.getElementById('statuspeminjam').value;
	document.location.href = "../pjm/pinjam.php?op=newuser&state="+status+"&noanggota="+noanggota+"&nama="+nama;
}

function fillstate(val){
	document.getElementById('statuspeminjam').value=val;
	document.getElementById('noanggota').value='';
	document.getElementById('nama').value='';
}
function success(){
	parent.opener.getfresh();
	window.close();
}
function CariPustaka(){
	var addr = "../lib/pustaka.php";
	newWindow(addr, 'CariPustaka','523','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function AcceptPustaka(kodepustaka){//(replid,kodepustaka,judul)
	var status = document.getElementById('statuspeminjam').value;
	var noanggota = document.getElementById('noanggota').value;
	var nama = document.getElementById('nama').value;
	document.location.href="../pjm/pinjam.php?op=addnew&state="+status+"&noanggota="+noanggota+"&nama="+nama+"&kodepustaka="+kodepustaka;
}

function AddToChart()
{
	var jenisanggota = document.getElementById('statuspeminjam').value;
	var borrowed = parseInt(document.getElementById('borrowed').value);
	var maxqueue = parseInt(document.getElementById('max_queue').value);
	var kodepustaka = document.getElementById('kodepustaka').value;
	
	if (borrowed>=maxqueue)
	{
			alert ('Tidak dapat menyimpan data\nkarena Anggota sedang meminjam '+borrowed+' pustaka dan belum mengembalikannya');
	}
	else
	{
		if (kodepustaka=="")
		{
			alert ('Anda harus mengisikan kode pustaka!');
			document.getElemntById('kodepustaka').focus();
		}
		else
		{
			var status = document.getElementById('statuspeminjam').value;
			var noanggota = document.getElementById('noanggota').value;
			var nama = document.getElementById('nama').value;
			var tglpinjam = document.getElementById('tglpjm').value;
			var tglkembali = document.getElementById('tglkem').value;
			var keterangan = document.getElementById('keterangan').value;
			document.location.href="../pjm/pinjam.php?op=addtochart&state="+status+"&jenisanggota="+jenisanggota+"&noanggota="+noanggota+"&nama="+nama+"&kodepustaka="+kodepustaka+"&tglpinjam="+tglpinjam+"&tglkembali="+tglkembali+"&keterangan="+keterangan;
		}
	}
}

function ValidatePeminjaman()
{
	var borrowed = document.getElementById('borrowed').value;
	var status = document.getElementById('statuspeminjam').value;
	var noanggota = document.getElementById('noanggota').value;
	var nama = document.getElementById('nama').value;
	var num = parseInt(document.getElementById('num').value);
	var maxqueue = parseInt(document.getElementById('max_queue').value);
	var idstr;
	
	if (num > maxqueue)
	{
		alert('Jumlah peminjaman tidak boleh melebihi '+maxqueue);
	}
	else
	{
		if (num === 0)
		{
			alert ('Tidak ada pustaka yang akan dipinjam!');
		}
		else
		{
			for (i=1; i<=num; i++)
			{
				var id = document.getElementById('idpinjam'+i).value;
				if (i==1)
				{
					idstr = id;
				}
				else
				{
					idstr += ','+id;
				}
			}
			
			if (confirm('Data sudah benar?'))
			{
				document.location.href="../pjm/pinjam.php?op=Save&state="+status+"&noanggota="+noanggota+"&nama="+nama+"&idstr="+idstr;
			}
		}
	}
}

function CancelPeminjaman(){
	var status = document.getElementById('statuspeminjam').value;
	var noanggota = document.getElementById('noanggota').value;
	var nama = document.getElementById('nama').value;
	var num = document.getElementById('num').value;
	var idstr;
	if (num!=0)
	{
		for (i=1; i<=num; i++)
		{
			var id = document.getElementById('idpinjam'+i).value;
			if (i==1)
			{
				idstr = id;
			} else {
				idstr += ','+id;
			}
			
		}
	}
	if (num>0)
	{
		if (confirm('Peminjaman belum disimpan, \nAnda yakin akan membatalkan peminjaman?'))
		{
			document.location.href = "pinjam.php?op=DontSave&state="+status+"&noanggota="+noanggota+"&nama="+nama+"&idstr="+idstr;
		}
	} else {
		document.location.href = "pinjam.php?state="+status+"&noanggota="+noanggota+"&nama="+nama;
	}
}
function CancelPeminjamanByReq(num){
	var status = document.getElementById('statuspeminjam').value;
	var noanggota = document.getElementById('noanggota').value;
	var nama = document.getElementById('nama').value;
	var idstr;
	for (i=1; i<=num; i++)
	{
		var id = document.getElementById('idpinjam'+i).value;
		if (i==1)
		{
			idstr = id;
		} else {
			idstr += ','+id;
		}
		
	}
	document.location.href = "pinjam.php?openuser=1&op=DontSave&state="+status+"&noanggota="+noanggota+"&nama="+nama+"&idstr="+idstr;
}
function HapusPeminjaman(replid){
	var status = document.getElementById('statuspeminjam').value;
	var noanggota = document.getElementById('noanggota').value;
	var nama = document.getElementById('nama').value;
	if (confirm('Anda yakin akan menghapus pustaka yang akan dipinjam ini?'))
	{
		document.location.href="../pjm/pinjam.php?op=delqueue&state="+status+"&noanggota="+noanggota+"&nama="+nama+"&replid="+replid;
	}
}
function TakeDate(elementid){
	var addr = "../lib/cals.php?elementid="+elementid;
	newWindow(addr, 'CariTanggal','338','216','resizable=0,scrollbars=0,status=0,toolbar=0')
}
function AcceptDate(date,elementid){
	document.getElementById(elementid).value=date;
}
function KeyPress(elemName, evt) {
		
    var KodeValue=document.getElementById('kodepustaka').value;
	evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
	alert(charCode);	
    if (charCode == 13) {
		alert ('Masuk');
		AcceptPustaka(KodeValue);
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

    AcceptPustaka(kode);
}

function ClearData()
{
	$('#kodepustaka').val('');
	$('#judul').val('');
	$('#tglpjm').val('');
	$('#tglkem').val('');
	$('#keterangan').val('');
	
	$('#kodepustaka').focus();
}