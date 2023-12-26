function getfresh()
{
	document.location.href = "pengguna.php";
}

function hapus(login)
{
	if (confirm('Anda yakin akan menghapus pengguna ini dari SIMTAKA?'))
		document.location.href = "pengguna.php?op=del&login="+login;
}

function tambah()
{
	newWindow('pengguna.add.php', 'TambahPengguna','460','350','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cetak()
{
	newWindow('pengguna.cetak.php', 'CetakPengguna','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cari()
{
	newWindow('../lib/pegawai.php', 'CariPegawai','563','428','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function acceptPegawai(nip,nama,flag)
{
	document.location.href = "../atr/pengguna.add.php?nip="+nip+"&nama="+nama;
}

function ubah(login)
{
	newWindow('pengguna.edit.php?nip='+login, 'UbahAnggota','460','350','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function validate()
{
	var nip = document.getElementById('nip').value;
	var password1 = document.getElementById('password1').value;
	var password2 = document.getElementById('password2').value;
	
	if (nip.length==0)
	{
		alert ('Anda harus mengisikan nilai untuk nip dan nama pegawai!\nKlik field NIP untuk membuka daftar pegawai');
		return false;
	}
	
	if (password1.length==0)
	{
		alert ('Anda harus mengisikan nilai untuk password!');
		document.getElementById('password1').focus();
		return false;
	}
	
	if (password2.length==0)
	{
		alert ('Anda harus mengisikan nilai untuk konfirmasi password!');
		document.getElementById('password2').focus();
		return false;
	}
	
	if (password1 != password2)
	{
		alert ('Password dan konfirmasi password harus sama!');
		document.getElementById('password2').value="";
		document.getElementById('password2').focus();
		return false;
	}
	
	if (!perpustakaan)
	{
		alert('Anda belum menentukan perpustakaan!');
		$('#perpustakaan').focus();
		return false;
	}

	return true;
}

function validate_edit()
{
	var perpustakaan = $('#perpustakaan').val();
	if (!perpustakaan)
	{
		alert('Anda belum menentukan perpustakaan!');
		$('#perpustakaan').focus();
		return false;
	}

	return true;
}

function success()
{
	parent.opener.getfresh();
	window.close();
}

function setaktif(login,newaktif)
{
	var msg;
	if (newaktif==1)
		msg='Apakah Anda yakin akan mengaktifkan pengguna ini di SIMTAKA?';
	else 
		msg='Apakah Anda yakin akan menonaktifkan pengguna ini dari SIMTAKA?';
	
	if (confirm(msg))
		document.location.href = "pengguna.php?op=nyd6j287sy388s3h8s8&login="+login+"&newaktif="+newaktif;
	
}

function ChgTkt(id)
{
	var nip = document.getElementById('nip').value;
	var nama = document.getElementById('nama').value;
	var tingkat = document.getElementById('tingkat').value;
	
	if (id==1)
		document.location.href = "pengguna.edit.php?nip="+nip+"&nama="+nama+"&tingkat="+tingkat;
	else
		document.location.href = "pengguna.add.php?nip="+nip+"&nama="+nama+"&tingkat="+tingkat;
}

function ChangeDep(id)
{
	var tingkat = $('#tingkat').val();
	var dep = $('#dep').val();
	
	$.ajax({
		url: "pengguna.ajax.php",
		data: "op=getperpus&tingkat="+tingkat+"&dep="+dep,
		type: "POST",
		success: function (response)
		{
			$("#divPerpus").html(response);
        },
		error: function (xhr, response, error)
		{
			alert(xhr.responseText);
		}
	});
	
}