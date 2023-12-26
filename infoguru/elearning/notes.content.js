$(document).ready(function() {
    Tables('tableNotes');
});

changeVideo = function()
{
    var idMedia = $("#media").val();
    var date1 = $("#date1").val();
    var date2 = $("#date2").val();

    $.ajax({
        url: "notes.content.ajax.php",
        data: "op=getNotes&idMedia=" + idMedia + "&date1=" + date1 + "&date2=" + date2,
        success: function (html) {
            $("#divNotes").html(html);
            Tables('tableNotes');
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });

};