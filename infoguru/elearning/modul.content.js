$(document).ready(function() {
    Tables('tableModul');
});

tambahModul = function()
{
    var idChannel = $("#idChannel").val();
    var addr = "modul.content.dialog.php?idChannel=" + idChannel + "&idModul=0";
    newWindow(addr, 'NewModul', '600', '380', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

refreshPage = function ()
{
    document.location.reload();
};

setStatusAktif = function(no, idModul, newAktif)
{
    $.ajax({
        url: "modul.content.ajax.php",
        data: "op=setStatusAktif&idModul=" + idModul + "&newAktif=" + newAktif,
        success: function()
        {
            $.ajax({
                url: "modul.content.ajax.php",
                data: "op=getStatusAktif&no=" + no + "&idModul=" + idModul,
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

hapusModul = function(no, idModul)
{
    var nMedia = parseInt($("#nMedia" + no).val());
    if (nMedia !== 0)
    {
        alert("Tidak dapat menghapus modul ini karena sudah digunakan!");
        return;
    }

    if (!confirm("Hapus modul ini?"))
        return;

    $.ajax({
        url: "modul.content.ajax.php",
        data: "op=removeModul&idModul=" + idModul,
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

editModul = function(idModul)
{
    var idChannel = $("#idChannel").val();
    var addr = "modul.content.dialog.php?idChannel=" + idChannel + "&idModul=" + idModul;
    newWindow(addr, 'EditModul', '600', '380', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

modulMedia = function(idModul)
{
    var departemen = $("#departemen").val();
    var idPelajaran = $("#idPelajaran").val();
    var idChannel = $("#idChannel").val();

    document.location.href = "modul.media.php?departemen=" + departemen + "&idPelajaran=" + idPelajaran + "&idChannel=" + idChannel + "&idModul=" + idModul;
};

viewFollower = function(idModul)
{
    var addr = "modul.follower.php?idModul=" + idModul;
    newWindow(addr, 'ModulFollower', '700', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};