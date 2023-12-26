simpanMediaInfo = function()
{
    if (!validateInput())
        return;

    if (!confirm("Data sudah lengkap dan benar?"))
        return;

    var no = $("#no").val();
    var idMedia = $("#idMedia").val();

    var formData = new FormData();
    formData.append("idMedia", idMedia);
    formData.append("judul", $.trim($("#judul").val()));
    formData.append("urutan", $.trim($("#urutan").val()));
    formData.append("prioritas", $("#prioritas").val());
    formData.append("deskripsi", $("#deskripsi").val());
    formData.append("objektif", $("#objektif").val());
    formData.append("pertanyaan", $("#pertanyaan").val());
    formData.append("kategori", $("#kategori").val());
    formData.append("kataKunci", $("#kataKunci").val());

    $.ajax({
        url: "media.edit.info.save.php",
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function ()
        {
            opener.refreshInfo(no, idMedia);
            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

validateInput = function ()
{
    var valx = new ValidatorX();

    return valx.EmptyText("judul", "Judul") &&
           valx.TextLength("judul", "Judul", 10, 100) &&
           valx.EmptyText("urutan", "Urutan") &&
           valx.IsInteger("urutan", "Urutan");
};