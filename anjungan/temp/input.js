
changeTahunLahirSiswa = function() {
    var y = $("#thnlahir").val();
    $.ajax({
        url : 'input.ajax.php?op=setTglLahirSiswa&y='+y,
        type: 'get',
        success : function(html) {
            $('#divTglLahirSiswa').html(html);
        }
    })
}

changeBulanLahirSiswa = function() {
    var y = $("#thnlahir").val();
    var m = $("#blnlahir").val();
    $.ajax({
        url : 'input.ajax.php?op=setTglLahirSiswa&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#divTglLahirSiswa').html(html);
        }
    })
}

changeTanggalLahirSiswa = function() {
    
}

changeTahunLahirAyah = function() {
    var y = $("#thnlahirayah").val();
    $.ajax({
        url : 'input.ajax.php?op=setTglLahirAyah&y='+y,
        type: 'get',
        success : function(html) {
            $('#divTglLahirAyah').html(html);
        }
    })
}

changeBulanLahirAyah = function() {
    var y = $("#thnlahirayah").val();
    var m = $("#blnlahirayah").val();
    $.ajax({
        url : 'input.ajax.php?op=setTglLahirAyah&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#divTglLahirAyah').html(html);
        }
    })
}

changeTanggalLahirAyah = function() {
    
}

changeTahunLahirIbu = function() {
    var y = $("#thnlahiribu").val();
    $.ajax({
        url : 'input.ajax.php?op=setTglLahirIbu&y='+y,
        type: 'get',
        success : function(html) {
            $('#divTglLahirIbu').html(html);
        }
    })
}

changeBulanLahirIbu = function() {
    var y = $("#thnlahiribu").val();
    var m = $("#blnlahiribu").val();
    $.ajax({
        url : 'input.ajax.php?op=setTglLahirIbu&y='+y+'&m='+m,
        type: 'get',
        success : function(html) {
            $('#divTglLahirIbu').html(html);
        }
    })
}

changeTanggalLahirIbu = function() {
    
}

changeJenjangSekolah = function() {
    var jenjang = $("#dep_asal").val();
    $.ajax({
        url : 'input.ajax.php?op=setAsalSekolah&jenjang='+jenjang,
        type: 'get',
        success : function(html) {
            $('#divAsalSekolah').html(html);
        }
    })
}

changeAsalSekolah = function() {
    
    var sekolah = $("#sekolah").val();
    if (sekolah == -1)
    {
        $("#inputsekolah").val(1);
        $("#newsekolah").css('visibility', 'visible');
    }
    else
    {
        $("#inputsekolah").val(0);
        $("#newsekolah").css('visibility', 'hidden');
    }
}

ValidateInput = function() {
    
    return Validator.CheckLength($("#nama"), "Nama Siswa", 4, 255) &&
           Validator.CheckLength($("#panggilan"), "Nama Panggilan Siswa", 4, 100) &&
           Validator.CheckLength($("#tmplahir"), "Tempat Kelahiran Siswa", 4, 100) &&
           Validator.CheckLength($("#urutananak"), "Urutan Anak", 1, 3) &&
           Validator.CheckNumber($("#urutananak"), "Urutan Anak") &&
           Validator.CheckInteger($("#urutananak"), "Urutan Anak", 1, 100) &&
           Validator.CheckLength($("#jumlahanak"), "Jumlah Anak", 1, 3) &&
           Validator.CheckNumber($("#jumlahanak"), "Jumlah Anak") &&
           Validator.CheckInteger($("#jumlahanak"), "Jumlah Anak", 1, 100) &&
           Validator.CompareValue($("#urutananak"), "Urutan Anak", $("#jumlahanak"), "Jumlah Anak", "<=") &&
           Validator.CheckLength($("#jkandung"), "Jumlah Saudara Kandung", 1, 3) &&
           Validator.CheckNumber($("#jkandung"), "Jumlah Saudara Kandung") &&
           Validator.CheckInteger($("#jkandung"), "Jumlah Saudara Kandung", 0, 100) &&
           Validator.CheckLength($("#jtiri"), "Jumlah Saudara Tiri", 1, 3) &&
           Validator.CheckNumber($("#jtiri"), "Jumlah Saudara Tiri") &&
           Validator.CheckInteger($("#jtiri"), "Jumlah Saudara Tiri", 0, 100) &&
           Validator.CheckLength($("#bahasa"), "Bahasa Sehari-hari", 5, 50) &&
           Validator.CheckLength($("#alamatsiswa"), "Alamat", 10, 255) &&
           Validator.CheckLength($("#kodepos"), "Kode Pos", 5, 7) &&
           Validator.CheckNumber($("#kodepos"), "Kode Pos") &&
           Validator.CheckLength($("#jarak"), "Jarak ke Sekolah", 1, 4) &&
           Validator.CheckNumber($("#jarak"), "Jarak ke Sekolah") &&
           Validator.CheckNumber($("#telponsiswa"), "Telpon Siswa") &&
           Validator.CheckNumber($("#hpsiswa"), "Handphone Siswa") &&
           Validator.CheckEmail($("#emailsiswa"), "Email Siswa") &&
           ($('#inputsekolah').val() == 1 ? Validator.CheckLength($('#newsekolah'), "Nama Asal Sekolah", 10, 100) : true) &&
           Validator.CheckNumber($("#berat"), "Berat Badan Siswa") &&
           Validator.CheckNumber($("#tinggi"), "Tinggi Badan Siswa") &&
           Validator.CheckLength($("#namaayah"), "Nama Ayah", 5, 100) &&
           Validator.CheckLength($("#namaibu"), "Nama Ayah", 5, 100) &&
           Validator.CheckLength($("#tmplahirayah"), "Tempat Lahir Ayah", 5, 100) &&
           Validator.CheckLength($("#tmplahiribu"), "Tempat Lahir Ibu", 5, 100) &&
           Validator.CheckRupiah($("#penghasilanayah"), "Penghasilan Ayah") &&
           Validator.CheckRupiah($("#penghasilanibu"), "Penghasilan Ibu") &&
           Validator.CheckEmail($("#emailayah"), "Email Ayah") &&
           Validator.CheckEmail($("#emailibu"), "Email Ibu") &&
           Validator.CheckNumber($("#telponortu"), "Telpon Orangtua") &&
           Validator.CheckNumber($("#hportu"), "Handphone Orangtua") &&
           Validator.CheckNumber($("#hportu2"), "Handphone Orangtua") &&
           Validator.CheckNumber($("#hportu3"), "Handphone Orangtua") &&
           confirm('Data sudah benar?');
}