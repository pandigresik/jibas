refreshMedia = function()
{
    document.location.reload();
};

tambahMedia = function()
{
    var departemen = $("#departemen").val();
    var idPelajaran = $("#idPelajaran").val();
    var idChannel = $("#idChannel").val();
    var idModul = $("#idModul").val();

    var addr = "media.browse.php?departemen=" + departemen + "&idPelajaran=" + idPelajaran + "&idChannel=" + idChannel + "&idModul=" + idModul;
    newWindow(addr, 'BrowseMedia', '1250', '800', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

acceptInfo = function(idMedia, urutan, keterangan)
{
    var idModul = $("#idModul").val();

    $.ajax({
        url: "modul.media.ajax.php",
        data: "op=addMedia&idModul=" + idModul + "&idMedia=" + idMedia + "&urutan=" + urutan + "&keterangan=" + encodeURIComponent(keterangan),
        success: function()
        {
            document.location.reload();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};

playVideo = function(url)
{
    var addr = "video.play.php?videoUrl=" + encodeURIComponent(url);
    newWindow(addr, 'PlayVideo', '1150', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

setStatusMediaModulAktif = function(no, idMediaModul, newAktif)
{
    $.ajax({
        url: "modul.media.ajax.php",
        data: "op=setStatusAktif&idMediaModul=" + idMediaModul + "&newAktif=" + newAktif,
        success: function()
        {
            $.ajax({
                url: "modul.media.ajax.php",
                data: "op=getStatusAktif&no=" + no + "&idMediaModul=" + idMediaModul,
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

acceptMediaModulInfo = function(idMediaModul, no, urutan, keterangan)
{
    $.ajax({
        url: "modul.media.ajax.php",
        data: "op=editKeterangan&idMediaModul=" + idMediaModul + "&urutan=" + urutan + "&keterangan=" + encodeURIComponent(keterangan),
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

editMediaModul = function (no, idMediaModul)
{
    var addr = "modul.media.dialog.php?no="+ no + "&idMediaModul="+idMediaModul;
    newWindow(addr, 'EditInfoMedia', '600', '380', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

hapusMediaModul = function(no, idMediaModul)
{
    if (!confirm("Hapus media ini dari modul?"))
        return;

    $.ajax({
        url: "modul.media.ajax.php",
        data: "op=hapusMediaModul&idMediaModul=" + idMediaModul,
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

backToModul = function ()
{
    var idChannel = $("#idChannel").val();
    var departemen = $("#departemen").val();
    var idPelajaran = $("#idPelajaran").val();

    document.location.href = "modul.content.php?departemen="+departemen+"&idPelajaran="+idPelajaran+"&idChannel=" + idChannel;
};