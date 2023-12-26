var md_pageVideo = 1;

md_resetPageCounter = function ()
{
    md_pageVideo = 1;
};

md_reload = function(idModul)
{
    md_resetPageCounter();

    $.ajax({
        url: "modul.php",
        data: "idModul=" + idModul,
        success: function (html)
        {
            $("#divContent").html(html);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

md_getFollowerCount = function(idModul)
{
    $.ajax({
        url: "modul.ajax.php",
        data: "op=getFollowerCount&idModul=" + idModul,
        success: function (count) {
            $("#spFollowCount").html(count);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

md_setFollow = function(follow, idModul)
{
    $.ajax({
        url: "modul.ajax.php",
        data: "op=setFollow&follow=" + follow + "&idModul=" + idModul,
        success: function () {

            var img = "images/following.png";
            var aInfo = "unfollow";
            var aFollow = 0;
            if (parseInt(follow) === 0)
            {
                img = "images/follow.png";
                aInfo = "follow";
                aFollow = 1;
            }

            var html = "<a style='cursor: pointer' onclick='md_setFollow(" + aFollow + "," + idModul + ")'>";
            html += "<img src='" + img + "' style='height: 32px;' title='klik untuk " + aInfo + "'></a>";

            $("#spFollow").html(html);

            md_getFollowerCount(idModul);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

md_nextVideoResult = function()
{
    var idMediaList = $.trim($("#idVideoModulList").val());
    if (idMediaList.length === 0)
        return;

    md_pageVideo += 1;

    $.ajax({
        url: "modul.ajax.php",
        data: "op=nextSearch&idMediaList="+idMediaList+"&page=" + md_pageVideo,
        success: function (data)
        {
            $('#tabVideoModul > tbody:last').append(data);
            $('#divModulVideo').animate({ scrollTop: $('#divModulVideo')[0].scrollHeight}, 1500);

            ix_saveHistory($("#divContent").html(), "modul.ajax.php", "op=nextSearch&idMediaList="+idMediaList+"&page=" + md_pageVideo, "");
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

md_changeVideoOrder = function()
{
    var idModul = $("#idModul").val();
    var urutan = $("#cbUrutanModulVideo").val();

    $("#tabVideoModul > tbody").empty();

    $.ajax({
        url: "modul.ajax.php",
        data: "op=changeVideoOrder&idModul=" + idModul + "&urutan=" + urutan,
        success: function(idList)
        {
            $("#idVideoModulList").val(idList);

            md_pageVideo = 1;

            $.ajax({
                url: "modul.ajax.php",
                data: "op=reloadVideoList&idList=" + idList,
                success: function(data)
                {
                    $('#tabVideoModul > tbody:last').append(data);
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