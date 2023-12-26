var pageList = 0;

nextTabunganList = function()
{
    pageList += 1;
	
	var nip = $("#nip").val();
	var idtahunbuku = $("#idtahunbuku").val();
	var idtabungan = $("#idtabungan").val();
	    
    $.ajax({
        type: "POST",
        url: "tabungan.trans.input.ajax.php",
        data: "page="+pageList+"&nip="+nip+"&idtahunbuku="+idtahunbuku+"&idtabungan="+idtabungan,
        success: function(data) {
			$('#tabTabunganList > tbody:last').append(data);
            $('#divTabunganList').animate({ scrollTop: $('#divSptFgrUser')[0].scrollHeight}, 1500);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	});
};

var win = null;
function newWindow(mypage,myname,w,h,features) 
{
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
}

function salinangkasetor()
{	
	var angka = document.getElementById("jsetor").value;
	document.getElementById("angkasetor").value = angka;
}

function salinangkatarik()
{	
	var angka = document.getElementById("jtarik").value;
	document.getElementById("angkatarik").value = angka;
}

function salincicilan()
{	
	var angka = document.getElementById("cicilan").value;
	document.getElementById("angkacicilan").value = angka;
}

function validasiSetoran()
{
	var isok = validateEmptyText('jsetor','Setoran Tabungan') &&
			   validateEmptyText('rekkassetor','Rekening Kas Tabungan') &&
		 	   validasiJumlahSetoran() &&
		 	   validateMaxText('keterangansetor', 255, 'Keterangan Setoran Tabungan');
	
	return isok && confirm("Data SETORAN sudah benar?");
}

function validasiJumlahSetoran() 
{
	var angka = document.getElementById("angkasetor").value;
	if(isNaN(angka)) 
	{
		alert ('Setoran tabungan harus berupa bilangan!');
		document.getElementById('jsetor').value = "";
		document.getElementById('jsetor').focus();
		return false;
	}
	else if (angka <= 0)
	{
		alert ('Setoran tabungan harus positif!');
		document.getElementById('jsetor').focus();
		return false;
	}
	
	return true;
}

function validateSetorSubmit() 
{	
	if (validasiSetoran()) 
	{
		var idtabungan = document.getElementById('idtabungan').value;
		var nip = document.getElementById('nip').value;
		var idtahunbuku = document.getElementById('idtahunbuku').value;		
		var jsetor = document.getElementById('jsetor').value;
		var rekkas = document.getElementById('rekkassetor').value;
		var keterangan = document.getElementById('keterangansetor').value;		
		jsetor = rupiahToNumber(jsetor);
		keterangan = escape(trim(keterangan));
		
		var addr = "tabungan.trans.input.php?op=348328947234923&idtabungan="+idtabungan+"&nip="+nip+"&idtahunbuku="+idtahunbuku+"&jsetor="+jsetor+"&keterangan="+keterangan+"&rekkas="+rekkas;
		document.location.href = addr;
	}
	else
	{
		//alert("ERR");
		document.getElementById('simpansetor').disabled = false;
	}
}

function validasiTarikan()
{
	var isok = validateEmptyText('jtarik','Tarikan Tabungan') &&
			   validateEmptyText('rekkastarik','Rekening Kas Tabungan') &&	
		 	   validasiJumlahTarikan() &&
		 	   validateMaxText('keterangantarik', 255, 'Keterangan Tarikan Tabungan');
	
	return isok && confirm("Data TARIKAN sudah benar?");
}

function validasiJumlahTarikan() 
{
	var angka = document.getElementById("angkatarik").value;
	if(isNaN(angka)) 
	{
		alert ('Tarikan tabungan harus berupa bilangan!');
		document.getElementById('jtarik').value = "";
		document.getElementById('jtarik').focus();
		return false;
	}
	else if (angka <= 0)
	{
		alert ('Tarikan tabungan harus positif!');
		document.getElementById('jtarik').focus();
		return false;
	}
	
	return true;
}

function validateTarikSubmit() 
{	
	if (validasiTarikan()) 
	{
		var idtabungan = document.getElementById('idtabungan').value;
		var nip = document.getElementById('nip').value;
		var idtahunbuku = document.getElementById('idtahunbuku').value;		
		var jtarik = document.getElementById('jtarik').value;
		var rekkas = document.getElementById('rekkastarik').value;
		var keterangan = document.getElementById('keterangantarik').value;		
		jtarik = rupiahToNumber(jtarik);
		keterangan = escape(trim(keterangan));
		
		var addr = "tabungan.trans.input.php?op=348328947234925&idtabungan="+idtabungan+"&nip="+nip+"&idtahunbuku="+idtahunbuku+"&jtarik="+jtarik+"&keterangan="+keterangan+"&rekkas="+rekkas;
		document.location.href = addr;
		//alert(addr);
		//document.getElementById('simpansetor').disabled = false;
	}
	else
	{
		//alert("ERR");
		document.getElementById('simpantarik').disabled = false;
	}
}

function cetakkuitansi(id) 
{
	newWindow('tabungan.trans.kuitansi.php?id='+id, 'CetakKuitansi','360','650','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function editpembayaran(id) 
{
	newWindow('tabungan.trans.edit.php?idpembayaran='+id,'EditPembayaranTabungan','425','450','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() 
{	
	var idtabungan = document.getElementById('idtabungan').value;
	var nip = document.getElementById('nip').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var addr = "tabungan.trans.input.php?idtabungan="+idtabungan+"&nip="+nip+"&idtahunbuku="+idtahunbuku;
	
	document.location.href = addr;
}

function cetak() 
{
	var idtabungan = document.getElementById('idtabungan').value;
	var nip = document.getElementById('nip').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var addr = "tabungan.trans.cetak.php?idtabungan="+idtabungan+"&nip="+nip+"&idtahunbuku="+idtahunbuku+"&page="+pageList;
	newWindow(addr, 'CetakTabungan','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
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
	var lain = new Array('jsetor','keterangan');
	for (i=0; i < lain.length; i++) 
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