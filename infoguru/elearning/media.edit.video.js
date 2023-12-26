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
        alert("Video belum ada!");
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

simpanVideo = function ()
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

    var no = $("#no").val();
    var idMedia = $("#idMedia").val();

    var formData = new FormData();
    formData.append("idMedia", idMedia);
    formData.append("fileVideo", $("#fileVideo")[0].files[0]);
    formData.append("coverImage", $("#coverImage").val());

    $.ajax({
        url: "media.edit.video.save.php",
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function ()
        {
            opener.refreshVideo(no, idMedia);
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
    return valx.EmptyText("fileVideo", "File video") &&
           valx.EmptyText("coverImage", "Thumbnail Image");
};