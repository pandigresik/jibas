changeFollowSort = function()
{
    var type = $("#followType").val();
    var sortBy = $("#sortFollowBy").val();

    $.ajax({
        url: "following.ajax.php",
        data: "op=changeOrder&type="+type+"&sortBy="+sortBy,
        success: function (html)
        {
            $("#divFollowing").html(html);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }

    })
};