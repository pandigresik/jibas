tambahMedia = function()
{
    var idChannel = $("#idChannel").val();

    document.location.href = "media.add.php?idChannel=" + idChannel;
};

playVideo = function(url)
{
    var addr = "video.play.php?videoUrl=" + encodeURIComponent(url);
    newWindow(addr, 'PlayVideo', '1150', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

changeVideo = function (no, idMedia)
{
    var addr = "media.edit.video.php?no=" + no + "&idMedia=" + idMedia;
    newWindow(addr, 'EditVideo', '1000', '500', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

changeInfo = function (no, idMedia)
{
    var addr = "media.edit.info.php?no=" + no + "&idMedia=" + idMedia;
    newWindow(addr, 'EditInfo', '800', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

changeFile = function (no, idMedia)
{
    var addr = "media.edit.file.php?no=" + no + "&idMedia=" + idMedia;
    newWindow(addr, 'EditFile', '800', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

refreshVideo = function (no, idMedia)
{
    $.ajax({
        url: "media.content.ajax.php",
        data: "op=refreshVideo&idMedia=" + idMedia,
        success: function(response)
        {
            $("#listVideo" + no).html(response);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

refreshInfo = function (no, idMedia)
{
    $.ajax({
        url: "media.content.ajax.php",
        data: "op=refreshInfo&idMedia=" + idMedia,
        success: function(response)
        {
            $("#listInfo" + no).html(response);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

refreshFile = function (no, idMedia)
{
    $.ajax({
        url: "media.content.ajax.php",
        data: "op=refreshFile&no=" + no + "&idMedia=" + idMedia,
        success: function(response)
        {
            $("#listFile" + no).html(response);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

hapusFile = function (no, idMedia, idFile)
{
    if (!confirm("Hapus file ini?"))
        return;

    $.ajax({
        url: "media.content.ajax.php",
        data: "op=removeFile&idFile=" + idFile,
        success: function()
        {
            $.ajax({
                url: "media.content.ajax.php",
                data: "op=refreshFile&no=" + no + "&idMedia=" + idMedia,
                success: function(response)
                {
                    $("#listFile" + no).html(response);
                },
                error: function(xhr)
                {
                    alert(xhr.responseText);
                }
            })
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

setStatusAktif = function(no, idMedia, newAktif)
{
    $.ajax({
        url: "media.content.ajax.php",
        data: "op=setStatusAktif&idMedia=" + idMedia + "&newAktif=" + newAktif,
        success: function()
        {
            $.ajax({
                url: "media.content.ajax.php",
                data: "op=getStatusAktif&no=" + no + "&idMedia=" + idMedia,
                success: function(response)
                {
                    $("#spStatusAktif" + no).html(response);
                },
                error: function(xhr)
                {
                    alert(xhr.responseText);
                }
            })
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

removeMedia = function(idMedia)
{
    if (!confirm("Apakah anda yakin akan menghapus media ini?"))
        return;

    $.ajax({
        url: "media.content.remove.php",
        data: "idMedia=" + idMedia,
        success: function()
        {
            document.location.reload();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

refreshPage = function()
{
    document.location.reload();
};

viewLiker = function (idMedia)
{
    var addr = "media.liker.php?idMedia=" + idMedia;
    newWindow(addr, 'MediaLiker', '700', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

