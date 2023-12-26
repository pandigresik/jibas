$( function() {
    $( "#tabs" ).tabs();
} );

setMediaLike = function(like, idMedia)
{
    $.ajax({
        url: "show.media.ajax.php",
        data: "op=setLike&like=" + like + "&idMedia=" + idMedia,
        success: function (nLike) {
            $("#spLikeCount").html(nLike);

            var alike = parseInt(like) === 1 ? 0 : 1;
            var img = parseInt(like) === 1 ? "images/alike.png" : "images/nlike.png";
            var ainfo = parseInt(like) === 1 ? "unlike" : "like";

            var addr = "<a style='cursor: pointer' onclick=\"setMediaLike(" + alike + "," + idMedia + ")\">";
            addr += "<img src='" + img + "' title='klik untuk " + ainfo + " video ini'></a>";

            $("#spLikeButton").html(addr);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })  
};

saveNotes = function(idMedia)
{
    var notes = $.trim($("#notes").val());
    if (notes.length === 0)
        return;

    if (notes.length > 1000)
    {
        alert("Maksimal panjang notes 1000 karakter");
        return;
    }

    $("#btSaveNotes").attr("disabled", true);

    $.ajax({
        url: "show.media.ajax.php",
        data: "op=saveNotes&idMedia=" + idMedia + "&notes=" + encodeURIComponent(notes),
        success: function () {
            $("#notes").val("");
            $("#divNotesList").html("memuat ...");

            $.ajax({
                url: "show.media.ajax.php",
                data: "op=reloadNotes&idMedia=" + idMedia,
                success: function (html) {
                    $("#divNotesList").html(html);
                    $("#spCountNotes").html("(" + $("#nNotes").val() + ")");
                },
                error: function (xhr) {
                    alert(xhr.responseText);
                }
            })
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })

    $("#btSaveNotes").attr("disabled", false);
};

removeNotes = function(idMedia, idNotes)
{
    if (!confirm("Hapus notes ini?"))
        return;

    $.ajax({
        url: "show.media.ajax.php",
        data: "op=removeNotes&idNotes="+idNotes,
        success: function () {
            $.ajax({
                url: "show.media.ajax.php",
                data: "op=reloadNotes&idMedia=" + idMedia,
                success: function (html) {
                    $("#divNotesList").html(html);
                    $("#spCountNotes").html("(" + $("#nNotes").val() + ")");
                },
                error: function (xhr) {
                    alert(xhr.responseText);
                }
            })
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};