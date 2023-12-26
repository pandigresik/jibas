var validVideo = false;

playSelectedFile = function()
{
    var file = ($("#fileVideo"))[0].files[0];
    var type = file.type;

    var videoNode = document.querySelector('video');
    var canPlay = videoNode.canPlayType(type);

    validVideo = false;

    if (canPlay === '') canPlay = 'no';
    var message = 'Tidak bisa memainkan file video ini: "' + type + '": ' + canPlay;
    var isError = canPlay === 'no';
    if (isError)
    {
        $("#fileVideo").val("");
        alert(message);
        return
    }

    var size = file.size / (1024 * 1024);
    if (size >= G_MAX_VIDEO_SIZE)
    {
        $("#fileVideo").val("");
        alert("Ukuran file video tidak boleh melebihi " + G_MAX_VIDEO_SIZE + " MB");
        return;
    }

    validVideo = true;
    var fileURL = URL.createObjectURL(file);
    var video = $("#video").attr('src', fileURL);
    ($("#video"))[0].load();
};

createPreview = function()
{
    if ($.trim($("#fileVideo").val()) === 0)
    {
        alert("Tentukan dahulu video yang akan digunakan!");
        return;
    }

    var video = ($("#video"))[0];

    var w = video.videoWidth;//video.videoWidth * scaleFactor;
    var h = video.videoHeight;//video.videoHeight * scaleFactor;

    var sw = w / 260;
    var sh = h / 120;

    var scaleFactor = sw;
    if (sh > sw) scaleFactor = sh;

    w = w / scaleFactor;
    h = h / scaleFactor;

    var canvas = document.createElement('canvas');
    canvas.width = w;
    canvas.height = h;

    var ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, w, h);

    var data = canvas.toDataURL("image/jpg");
    $("#previewImage").attr('src', data);
    $("#coverImage").val(data);
};

validateFileSize = function (nid)
{
    var elId = "#file" + nid;
    var file = ($(elId))[0].files[0];

    var size = file.size / (1024 * 1024);
    if (size >= G_MAX_FILE_SIZE)
    {
        $(elId).val("");
        alert("Ukuran lampiran file tidak boleh melebihi " + G_MAX_FILE_SIZE + " MB");
    }
};

addFile = function()
{
    var nid = parseInt($("#nFile").val()) + 1;

    var row = "<tr id='filerow" + nid + "'>\r\n";
    row += "<td align='left'><input type='file' id='file" + nid + "' name='file" + nid + "' onchange='validateFileSize(" + nid + ")' style='width: 300px; height: 32px;' class='inputbox'></td>\r\n";
    row += "<td align='left'>\r\n";
    row += "<input type='textbox' id='info" + nid + "' name='info" + nid + "' style='width: 300px; height: 32px;' class='inputbox'>\r\n";
    row += "<input type='button' class='but' value=' X ' style='height: 26px;' onclick='removeFile(" + nid + ")'>\r\n";
    row += "</td>\r\n";
    row += "</tr>\r\n";

    $("#tableFile > tbody:last").append(row);
    $("#nFile").val(nid);
};

removeFile = function(id)
{
    if (!confirm('Hapus berkas ini?'))
        return;

    $("#filerow" + id).remove();
};

simpanMedia = function()
{
    if (!validVideo)
    {
        alert("File video tidak valid!")
        return;
    }

    if (!validateInput())
        return;

    if (!confirm("Data sudah lengkap dan benar?"))
        return;

    var idChannel = $("#idChannel").val();

    var formData = new FormData();
    formData.append("idChannel", idChannel);
    formData.append("judul", $.trim($("#judul").val()));
    formData.append("urutan", $.trim($("#urutan").val()));
    formData.append("prioritas", $("#prioritas").val());
    formData.append("deskripsi", $("#deskripsi").val());
    formData.append("objektif", $("#objektif").val());
    formData.append("pertanyaan", $("#pertanyaan").val());
    formData.append("fileVideo", $("#fileVideo")[0].files[0]);
    formData.append("coverImage", $("#coverImage").val());
    formData.append("kategori", $("#kategori").val());
    formData.append("kataKunci", $("#kataKunci").val());

    var nFile = parseInt($("#nFile").val());
    var cnt = 0;
    for(var i = 1; i <= nFile; i++)
    {
        if ($('#filerow' + i).length === 0)
            continue;

        var file = $("#file" + i).val();
        if ($.trim(file).length === 0)
            continue;

        cnt += 1;
        formData.append("file" + cnt, $("#file" + i)[0].files[0]);
        formData.append("info" + cnt, $("#info" + i).val());
    }
    formData.append("nFile", cnt);

    for (var key in formData)
    {
        console.log(key, formData[key]);
    }

    $.ajax({
        url: "media.add.save.php",
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) === -1)
            {
                alert("Gagal membaca response!");
                return;
            }

            var gnrt = parse.Data;
            if (parseInt(gnrt.Code) === -1)
            {
                alert("Gagal menyimpan: " + gnrt.Message);
                return;
            }

            document.location.href = "media.content.php?idChannel=" + idChannel;
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
           valx.TextLength("judul", "Judul", 5, 255) &&
           valx.EmptyText("urutan", "Urutan") &&
           valx.IsInteger("urutan", "Urutan") &&
           valx.EmptyText("fileVideo", "File video") &&
           valx.EmptyText("coverImage", "Thumbnail Image");
};