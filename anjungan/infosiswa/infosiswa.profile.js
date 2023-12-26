is_changeTahunLahirSiswa = function() {
    var y = $("#is_thnlahir").val();
	var m = $("#is_blnlahir").val();
    $.ajax({
        url : 'infosiswa/infosiswa.profile.ajax.php?op=setTglLahirSiswa&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#is_divTglLahirSiswa').html(html);
        }
    })
}

is_changeBulanLahirSiswa = function() {
    var y = $("#is_thnlahir").val();
    var m = $("#is_blnlahir").val();
    $.ajax({
        url : 'infosiswa/infosiswa.profile.ajax.php?op=setTglLahirSiswa&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#is_divTglLahirSiswa').html(html);
        }
    })
}

is_changeTanggalLahirSiswa = function() {
    
}

is_CopyAlamat = function() {
	
	var alamat = $.trim($("#psb_alamatsiswa").val());
	if (alamat.length == 0)
		return;

	$("#psb_alamatortu").val(alamat);
	$("#psb_alamatsurat").val(alamat);
}

is_changeTahunLahirAyah = function() {
    var y = $("#is_thnlahirayah").val();
	var m = $("#is_blnlahirayah").val();
    $.ajax({
        url : 'infosiswa/infosiswa.profile.ajax.php?op=setTglLahirAyah&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#is_divTglLahirAyah').html(html);
        }
    })
}

is_changeBulanLahirAyah = function() {
    var y = $("#is_thnlahirayah").val();
    var m = $("#is_blnlahirayah").val();
    $.ajax({
        url : 'infosiswa/infosiswa.profile.ajax.php?op=setTglLahirAyah&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#is_divTglLahirAyah').html(html);
        }
    })
}

is_changeTanggalLahirAyah = function() {
    
}

is_changeTahunLahirIbu = function() {
    var y = $("#is_thnlahiribu").val();
	var m = $("#is_blnlahiribu").val();
    $.ajax({
        url : 'infosiswa/infosiswa.profile.ajax.php?op=setTglLahirIbu&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#is_divTglLahirIbu').html(html);
        }
    })
}

is_changeBulanLahirIbu = function() {
    var y = $("#is_thnlahiribu").val();
    var m = $("#is_blnlahiribu").val();
    $.ajax({
        url : 'infosiswa/infosiswa.profile.ajax.php?op=setTglLahirIbu&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#is_divTglLahirIbu').html(html);
        }
    })
}

is_changeTanggalLahirIbu = function() {
    
}

is_changeJenjangSekolah = function() {
    var jenjang = $("#is_dep_asal").val();
    $.ajax({
        url : 'infosiswa/infosiswa.profile.ajax.php?op=setAsalSekolah&jenjang='+jenjang,
        type: 'get',
        success : function(html) {
            $('#is_divAsalSekolah').html(html);
        }
    })
}

is_changeAsalSekolah = function() {
    
    var sekolah = $("#is_sekolah").val();
    if (sekolah == -1)
    {
        $("#is_inputsekolah").val(1);
        $("#is_newsekolah").css('visibility', 'visible');
    }
    else
    {
        $("#is_inputsekolah").val(0);
        $("#is_newsekolah").css('visibility', 'hidden');
    }
}

is_ValidateInput = function() {
    
	return Validator.CheckLength($("#is_nama"), "Nama Siswa", 4, 255) &&
           Validator.CheckLength($("#is_panggilan"), "Nama Panggilan Siswa", 4, 100) &&
           Validator.CheckLength($("#is_tmplahir"), "Tempat Kelahiran Siswa", 4, 100) &&
           Validator.CheckLength($("#is_urutananak"), "Urutan Anak", 1, 3) &&
           Validator.CheckNumber($("#is_urutananak"), "Urutan Anak") &&
           Validator.CheckInteger($("#is_urutananak"), "Urutan Anak", 1, 100) &&
           Validator.CheckLength($("#is_jumlahanak"), "Jumlah Anak", 1, 3) &&
           Validator.CheckNumber($("#is_jumlahanak"), "Jumlah Anak") &&
           Validator.CheckInteger($("#is_jumlahanak"), "Jumlah Anak", 1, 100) &&
           Validator.CompareValue($("#is_urutananak"), "Urutan Anak", $("#is_jumlahanak"), "Jumlah Anak", "<=") &&
           Validator.CheckLength($("#is_jkandung"), "Jumlah Saudara Kandung", 1, 3) &&
           Validator.CheckNumber($("#is_jkandung"), "Jumlah Saudara Kandung") &&
           Validator.CheckInteger($("#is_jkandung"), "Jumlah Saudara Kandung", 0, 100) &&
           Validator.CheckLength($("#is_jtiri"), "Jumlah Saudara Tiri", 1, 3) &&
           Validator.CheckNumber($("#is_jtiri"), "Jumlah Saudara Tiri") &&
           Validator.CheckInteger($("#is_jtiri"), "Jumlah Saudara Tiri", 0, 100) &&
           Validator.CheckLength($("#is_bahasa"), "Bahasa Sehari-hari", 5, 50) &&
           Validator.CheckLength($("#is_alamatsiswa"), "Alamat", 10, 255) &&
           Validator.CheckLength($("#is_kodepos"), "Kode Pos", 5, 7) &&
           Validator.CheckNumber($("#is_kodepos"), "Kode Pos") &&
           Validator.CheckLength($("#is_jarak"), "Jarak ke Sekolah", 1, 4) &&
           Validator.CheckNumber($("#is_jarak"), "Jarak ke Sekolah") &&
           Validator.CheckNumber($("#is_telponsiswa"), "Telpon Siswa") &&
           Validator.CheckNumber($("#is_hpsiswa"), "Handphone Siswa") &&
           Validator.CheckEmail($("#is_emailsiswa"), "Email Siswa") &&
           ($('#is_inputsekolah').val() == 1 ? Validator.CheckLength($('#is_newsekolah'), "Nama Asal Sekolah", 10, 100) : true) &&
           Validator.CheckNumber($("#is_berat"), "Berat Badan Siswa") &&
           Validator.CheckNumber($("#is_tinggi"), "Tinggi Badan Siswa") &&
           Validator.CheckLength($("#is_namaayah"), "Nama Ayah", 4, 100) &&
           Validator.CheckLength($("#is_namaibu"), "Nama Ayah", 4, 100) &&
           Validator.CheckLength($("#is_tmplahirayah"), "Tempat Lahir Ayah", 5, 100) &&
           Validator.CheckLength($("#is_tmplahiribu"), "Tempat Lahir Ibu", 5, 100) &&
           Validator.CheckRupiah($("#is_penghasilanayah"), "Penghasilan Ayah") &&
           Validator.CheckRupiah($("#is_penghasilanibu"), "Penghasilan Ibu") &&
           Validator.CheckEmail($("#is_emailayah"), "Email Ayah") &&
           Validator.CheckEmail($("#is_emailibu"), "Email Ibu") &&
           Validator.CheckNumber($("#is_telponortu"), "Telpon Orangtua") &&
           Validator.CheckNumber($("#is_hportu"), "Handphone Orangtua") &&
           Validator.CheckNumber($("#is_hportu2"), "Handphone Orangtua") &&
           Validator.CheckNumber($("#is_hportu3"), "Handphone Orangtua") &&
           confirm('Data sudah benar?');
}

is_Simpan = function() {
	
	if (!is_ValidateInput())
		return;
	
	var data = "";
	var inputs = $('#is_form :input:not([type=radio],[type=checkbox])');
	inputs.each(function() {
		if (data != "")
			data += "&";
		
		data += this.name + "=" + $(this).val();
	});
	
	inputs = $('#is_form :input[type=radio]:checked');
	inputs.each(function() {
		if (data != "")
			data += "&";
		
		data += this.name + "=" + $(this).val();
	});
	
	inputs = $('#is_form :input[type=checkbox]:checked');
	inputs.each(function() {
		if (data != "")
			data += "&";
		
		data += this.name + "=" + $(this).val();
	});

	$.ajax({
		type: 'POST',
		url: 'infosiswa/infosiswa.profile.save.php',
		data: data,
		success: function(html) {
			$('#is_profile').html(html);
		},
		error: function(xhr, options, error) {
			$('#is_profile').html(xhr.responseText);
		}
	});
}