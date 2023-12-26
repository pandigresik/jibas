var fav_page = 0;

fav_resetPage = function ()
{
    fav_page = 0;
};

fav_changeFavSort = function()
{
    var sortBy = $("#sortFavBy").val();
    $("#tableFavBody").empty();

    $.ajax({
        url: "fav.ajax.php",
        data: "op=changeOrder&sortBy=" + sortBy,
        success: function (idMediaList)
        {
            $("#fav_idMediaList").val(idMediaList);

            fav_resetPage();

            $.ajax({
                url: "fav.ajax.php",
                data: "op=nextSearch&idMediaList="+idMediaList+"&page=0",
                success: function (data)
                {
                    $('#tableFav > tbody:last').append(data);
                    $('#divFav').animate({ scrollTop: $('#divFav')[0].scrollHeight}, 1500);

                    ix_saveHistory($("#divContent").html(), "fav.ajax.php", "op=nextSearch&idMediaList="+idMediaList+"&page=0", "");
                },
                error: function (xhr)
                {
                    alert(xhr.responseText);
                }
            })
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

fav_nextVideoResult = function ()
{
    var idMediaList = $.trim($("#fav_idMediaList").val());
    if (idMediaList.length === 0)
        return;

    fav_page += 1;

    $.ajax({
        url: "fav.ajax.php",
        data: "op=nextSearch&idMediaList="+idMediaList+"&page=" + fav_page,
        success: function (data)
        {
            $('#tableFav > tbody:last').append(data);
            $('#divFav').animate({ scrollTop: $('#divFav')[0].scrollHeight}, 1500);

            ix_saveHistory($("#divContent").html(), "fav.ajax.php", "op=nextSearch&idMediaList="+idMediaList+"&page=" + fav_page, "");
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};