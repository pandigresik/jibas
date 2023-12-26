var cn_pageVideo = 1;

cn_resetPageCounter = function () {
    cn_pageVideo = 1;
};

cn_reload = function(idChannel)
{
    cn_resetPageCounter();

    $.ajax({
        url: "channel.php",
        data: "idChannel=" + idChannel,
        success: function (html) {
            $("#divContent").html(html);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

cn_getFollowerCount = function(idChannel)
{
    $.ajax({
        url: "channel.ajax.php",
        data: "op=getFollowerCount&idChannel=" + idChannel,
        success: function (count) {
            $("#spFollowCount").html(count);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

cn_setFollow = function(follow, idChannel)
{
    $.ajax({
        url: "channel.ajax.php",
        data: "op=setFollow&follow=" + follow + "&idChannel=" + idChannel,
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

            var html = "<a style='cursor: pointer' onclick='cn_setFollow(" + aFollow + "," + idChannel + ")'>";
            html += "<img src='" + img + "' style='height: 32px;' title='klik untuk " + aInfo + "'></a>";

            $("#spFollow").html(html);

            cn_getFollowerCount(idChannel);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

cn_nextVideoChannelResult = function()
{
    var idMediaList = $.trim($("#idVideoChannelList").val());
    if (idMediaList.length === 0)
        return;

    cn_pageVideo += 1;

    $.ajax({
        url: "channel.ajax.php",
        data: "op=nextSearch&idMediaList="+idMediaList+"&page=" + cn_pageVideo,
        success: function (data)
        {
            $('#tabVideoChannel > tbody:last').append(data);
            $('#divChannelVideo').animate({ scrollTop: $('#divChannelVideo')[0].scrollHeight}, 1500);

            ix_saveHistory($("#divContent").html(), "channel.ajax.php", "op=nextSearch&idMediaList="+idMediaList+"&page=" + cn_pageVideo, "");
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

cn_playVideo = function(idMedia)
{
    if (!G_VIEW_MEDIA_ALLOW)
    {
        var content = "Maaf, tidak diperkenankan membuka video:<br><br>" + G_VIEW_MEDIA_INFO;
        mainBox.show(content, "");
        return;
    }

    var addr = "show.media.php?idMedia=" + idMedia;
    newWindow(addr, 'ShowMedia' + idMedia, '1150', '1000', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
};

cn_changeVideoOrder = function()
{
    var idChannel = $("#idChannel").val();
    var urutan = $("#cbUrutanChannelVideo").val();

    $("#tabVideoChannel > tbody").empty();

    $.ajax({
        url: "channel.ajax.php",
        data: "op=changeVideoOrder&idChannel=" + idChannel + "&urutan=" + urutan,
        success: function(idList)
        {
            $("#idVideoChannelList").val(idList);

            cn_pageVideo = 1;

            $.ajax({
                url: "channel.ajax.php",
                data: "op=reloadVideoList&idList=" + idList,
                success: function(data)
                {
                    $('#tabVideoChannel > tbody:last').append(data);
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

cn_showModul = function(idModul)
{
    $.ajax({
        url: "modul.php",
        data: "idModul=" + idModul,
        success: function (html)
        {
            ix_saveHistory(html, "modul.php", "idModul=" + idModul);

            $("#divContent").html(html);

            md_resetPageCounter();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};