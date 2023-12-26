$(document).ready(function() {
    Tables('tableChannel');
});


function ch_tambahChannel()
{
    var dept = $("#departemen").val();
    var idpel = $("#idpelajaran").val();

    var addr = "channel.content.dialog.php?idchannel=0&dept="+dept+"&idpel="+idpel;
    newWindow(addr, 'NewChannel', '600', '380', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ch_refresh()
{
    document.location.reload();
}

ch_editChannel = function (idChannel)
{
    var dept = $("#departemen").val();
    var idpel = $("#idpelajaran").val();

    var addr = "channel.content.dialog.php?idchannel=" + idChannel + "&dept="+dept+"&idpel="+idpel;
    newWindow(addr, 'EditChannel', '600', '380', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

ch_hapusChannel = function (idChannel)
{
    var nMedia = parseInt($("#nmedia-" + idChannel).val());
    var nModul = parseInt($("#nmodul-" + idChannel).val());

    if (nMedia !== 0 && nModul !== 0)
    {
        alert("Tidak dapat menghapus channel yang sudah berisi data!")
        return;
    }

    if (!confirm("Hapus channel ini?"))
        return;

    $.ajax({
        url: "channel.content.ajax.php",
        data: "op=f094n782130948nm219048fn12390421&idchannel=" + idChannel,
        success: function (json) {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) === -1)
            {
                alert("Gagal membaca response!" + json);
                return;
            }

            var gnrt = parse.Data;
            if (parseInt(gnrt.Code) === -1)
            {
                alert("Tidak bisa menghapus channel!");
                return;
            }

            ch_refresh();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

setStatusAktif = function(no, idChannel, newAktif)
{
    $.ajax({
        url: "channel.content.ajax.php",
        data: "op=setStatusAktif&idChannel=" + idChannel + "&newAktif=" + newAktif,
        success: function()
        {
            $.ajax({
                url: "channel.content.ajax.php",
                data: "op=getStatusAktif&no=" + no + "&idChannel=" + idChannel,
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

ch_viewFollower = function (idChannel)
{
    var addr = "channel.follower.php?idChannel=" + idChannel;
    newWindow(addr, 'ChannelFollowe', '700', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};