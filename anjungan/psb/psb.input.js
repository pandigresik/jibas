psb_changeDepartemen = function() {
	
	//alert('Get Proses');
	var dept = $("#psb_departemen").val();
	$.ajax({
        url : 'psb/psb.input.ajax.php?op=getTambahanData&dept='+dept,
        type: 'get',
        success : function(html) {
            $('#psb_divTambahanData').html(html);
        }
    });

	$.ajax({
        url : 'psb/psb.input.ajax.php?op=setProsesPsb&dept='+dept,
        type: 'get',
        success : function(html) {
            $('#psb_divProses').html(html);
			
			var proses = $("#psb_proses").val();
			//alert('Get Kelompok with Proses ' + proses);
			$.ajax({
				url : 'psb/psb.input.ajax.php?op=setKelompokPsb&proses='+proses,
				type: 'get',
				success : function(html) {
					$('#psb_divKelompok').html(html);
				}
			});
			
			//alert('Get Sumbangan with Proses ' + proses);
			$.ajax({
				url : 'psb/psb.input.ajax.php?op=setSumbanganPsb&proses='+proses,
				type: 'get',
				success : function(html) {
					$('#psb_divSumbangan').html(html);
				}
			});
			
			//alert('Get Nilai Ujian with Proses ' + proses);
			$.ajax({
				url : 'psb/psb.input.ajax.php?op=setUjianPsb&proses='+proses,
				type: 'get',
				success : function(html) {
					//alert(html.length);
					$('#psb_divNilaiUjian').html(html);
				}
			});
        }
    });
	
}

psb_changeProses = function() {
	
	var proses = $("#psb_proses").val();
	$.ajax({
		url : 'psb/psb.input.ajax.php?op=setKelompokPsb&proses='+proses,
		type: 'get',
		success : function(html) {
			$('#psb_divKelompok').html(html);
		}
	})
}

psb_changeTahunLahirSiswa = function() {
    var y = $("#psb_thnlahir").val();
	var m = $("#psb_blnlahir").val();
    $.ajax({
        url : 'psb/psb.input.ajax.php?op=setTglLahirSiswa&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#psb_divTglLahirSiswa').html(html);
        }
    })
}

psb_changeBulanLahirSiswa = function() {
    var y = $("#psb_thnlahir").val();
    var m = $("#psb_blnlahir").val();
    $.ajax({
        url : 'psb/psb.input.ajax.php?op=setTglLahirSiswa&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#psb_divTglLahirSiswa').html(html);
        }
    })
}

psb_changeTanggalLahirSiswa = function() {
    
}

psb_CopyAlamat = function() {
	
	var alamat = $.trim($("#psb_alamatsiswa").val());
	if (alamat.length == 0)
		return;

	$("#psb_alamatortu").val(alamat);
	$("#psb_alamatsurat").val(alamat);
}

psb_changeTahunLahirAyah = function() {
    var y = $("#psb_thnlahirayah").val();
	var m = $("#psb_blnlahirayah").val();
    $.ajax({
        url : 'psb/psb.input.ajax.php?op=setTglLahirAyah&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#psb_divTglLahirAyah').html(html);
        }
    })
}

psb_changeBulanLahirAyah = function() {
    var y = $("#psb_thnlahirayah").val();
    var m = $("#psb_blnlahirayah").val();
    $.ajax({
        url : 'psb/psb.input.ajax.php?op=setTglLahirAyah&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#psb_divTglLahirAyah').html(html);
        }
    })
}

psb_changeTanggalLahirAyah = function() {
    
}

psb_changeTahunLahirIbu = function() {
    var y = $("#psb_thnlahiribu").val();
	var m = $("#psb_blnlahiribu").val();
    $.ajax({
        url : 'psb/psb.input.ajax.php?op=setTglLahirIbu&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#psb_divTglLahirIbu').html(html);
        }
    })
}

psb_changeBulanLahirIbu = function() {
    var y = $("#psb_thnlahiribu").val();
    var m = $("#psb_blnlahiribu").val();
    $.ajax({
        url : 'psb/psb.input.ajax.php?op=setTglLahirIbu&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#psb_divTglLahirIbu').html(html);
        }
    })
}

psb_changeTanggalLahirIbu = function() {
    
}

psb_changeJenjangSekolah = function() {
    var jenjang = $("#psb_dep_asal").val();
    $.ajax({
        url : 'psb/psb.input.ajax.php?op=setAsalSekolah&jenjang='+jenjang,
        type: 'get',
        success : function(html) {
            $('#psb_divAsalSekolah').html(html);
        }
    })
}

psb_changeAsalSekolah = function() {
    
    var sekolah = $("#psb_sekolah").val();
    if (sekolah == -1)
    {
        $("#psb_inputsekolah").val(1);
        $("#psb_newsekolah").css('visibility', 'visible');
    }
    else
    {
        $("#psb_inputsekolah").val(0);
        $("#psb_newsekolah").css('visibility', 'hidden');
    }
}

psb_ValidateInput = function() {
    
	$proses = $("#psb_proses").val();
	$kelompok = $("#psb_kelompok").val();
	
	if ($proses == -1 || $kelompok == -1)
	{
		alert("Belum ada data Proses atau Kelompok PSB!");
		return false;
	}
	
    return Validator.CheckLength($("#psb_nama"), "Nama Siswa", 4, 255) &&
           Validator.CheckLength($("#psb_panggilan"), "Nama Panggilan Siswa", 4, 100) &&
           Validator.CheckLength($("#psb_tmplahir"), "Tempat Kelahiran Siswa", 4, 100) &&
           Validator.CheckLength($("#psb_urutananak"), "Urutan Anak", 1, 3) &&
           Validator.CheckNumber($("#psb_urutananak"), "Urutan Anak") &&
           Validator.CheckInteger($("#psb_urutananak"), "Urutan Anak", 1, 100) &&
           Validator.CheckLength($("#psb_jumlahanak"), "Jumlah Anak", 1, 3) &&
           Validator.CheckNumber($("#psb_jumlahanak"), "Jumlah Anak") &&
           Validator.CheckInteger($("#psb_jumlahanak"), "Jumlah Anak", 1, 100) &&
           Validator.CompareValue($("#psb_urutananak"), "Urutan Anak", $("#psb_jumlahanak"), "Jumlah Anak", "<=") &&
           Validator.CheckLength($("#psb_jkandung"), "Jumlah Saudara Kandung", 1, 3) &&
           Validator.CheckNumber($("#psb_jkandung"), "Jumlah Saudara Kandung") &&
           Validator.CheckInteger($("#psb_jkandung"), "Jumlah Saudara Kandung", 0, 100) &&
           Validator.CheckLength($("#psb_jtiri"), "Jumlah Saudara Tiri", 1, 3) &&
           Validator.CheckNumber($("#psb_jtiri"), "Jumlah Saudara Tiri") &&
           Validator.CheckInteger($("#psb_jtiri"), "Jumlah Saudara Tiri", 0, 100) &&
           Validator.CheckLength($("#psb_bahasa"), "Bahasa Sehari-hari", 5, 50) &&
           Validator.CheckLength($("#psb_alamatsiswa"), "Alamat", 10, 255) &&
           Validator.CheckLength($("#psb_kodepos"), "Kode Pos", 5, 7) &&
           Validator.CheckNumber($("#psb_kodepos"), "Kode Pos") &&
           Validator.CheckLength($("#psb_jarak"), "Jarak ke Sekolah", 1, 4) &&
           Validator.CheckNumber($("#psb_jarak"), "Jarak ke Sekolah") &&
           Validator.CheckNumber($("#psb_telponsiswa"), "Telpon Siswa") &&
           Validator.CheckNumber($("#psb_hpsiswa"), "Handphone Siswa") &&
           Validator.CheckEmail($("#psb_emailsiswa"), "Email Siswa") &&
           ($('#psb_inputsekolah').val() == 1 ? Validator.CheckLength($('#psb_newsekolah'), "Nama Asal Sekolah", 10, 100) : true) &&
           Validator.CheckNumber($("#psb_berat"), "Berat Badan Siswa") &&
           Validator.CheckNumber($("#psb_tinggi"), "Tinggi Badan Siswa") &&
           Validator.CheckLength($("#psb_namaayah"), "Nama Ayah", 5, 100) &&
           Validator.CheckLength($("#psb_namaibu"), "Nama Ayah", 5, 100) &&
           Validator.CheckLength($("#psb_tmplahirayah"), "Tempat Lahir Ayah", 5, 100) &&
           Validator.CheckLength($("#psb_tmplahiribu"), "Tempat Lahir Ibu", 5, 100) &&
           Validator.CheckRupiah($("#psb_penghasilanayah"), "Penghasilan Ayah") &&
           Validator.CheckRupiah($("#psb_penghasilanibu"), "Penghasilan Ibu") &&
           Validator.CheckEmail($("#psb_emailayah"), "Email Ayah") &&
           Validator.CheckEmail($("#psb_emailibu"), "Email Ibu") &&
           Validator.CheckNumber($("#psb_telponortu"), "Telpon Orangtua") &&
           Validator.CheckNumber($("#psb_hportu"), "Handphone Orangtua") &&
           Validator.CheckNumber($("#psb_hportu2"), "Handphone Orangtua") &&
           Validator.CheckNumber($("#psb_hportu3"), "Handphone Orangtua") &&
		   Validator.CheckRupiah($("#psb_sum1"), "Sumbangan PSB") &&
		   Validator.CheckRupiah($("#psb_sum2"), "Sumbangan PSB") &&
		   Validator.CheckNumber($("#psb_ujian1"), "Nilai Ujian") &&
		   Validator.CheckNumber($("#psb_ujian2"), "Nilai Ujian") &&
		   Validator.CheckNumber($("#psb_ujian3"), "Nilai Ujian") &&
		   Validator.CheckNumber($("#psb_ujian4"), "Nilai Ujian") &&
		   Validator.CheckNumber($("#psb_ujian5"), "Nilai Ujian") &&
		   Validator.CheckNumber($("#psb_ujian6"), "Nilai Ujian") &&
		   Validator.CheckNumber($("#psb_ujian7"), "Nilai Ujian") &&
		   Validator.CheckNumber($("#psb_ujian8"), "Nilai Ujian") &&
		   Validator.CheckNumber($("#psb_ujian9"), "Nilai Ujian") &&
		   Validator.CheckNumber($("#psb_ujian10"), "Nilai Ujian") &&
           confirm('Data sudah benar?');
}

psb_Simpan = function() {
	
	if (!psb_ValidateInput())
		return;
	
	var data = "";
	var inputs = $('#psb_form :input:not([type=radio],[type=checkbox])');
	inputs.each(function() {
		if (data != "")
			data += "&";
		
		data += this.name + "=" + $(this).val();
	});
	
	inputs = $('#psb_form :input[type=radio]:checked');
	inputs.each(function() {
		if (data != "")
			data += "&";
		
		data += this.name + "=" + $(this).val();
	});
	
	inputs = $('#psb_form :input[type=checkbox]:checked');
	inputs.each(function() {
		if (data != "")
			data += "&";
		
		data += this.name + "=" + $(this).val();
	});
	
	$.ajax({
		type: 'POST',
		url: 'psb/psb.input.save.php',
		data: data,
		success: function(html) {
			$('#psb_content').html(html);
		},
		error: function(xhr, options, error) {
			$('#psb_content').html(xhr.responseText);
			//alert(xhr.responseText);
		}
	});
}