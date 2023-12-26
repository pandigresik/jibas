var srv_page = 0;

sr_resetPage = function ()
{
    srv_page = 0;
};

sr_startVideoSearch = function (searchDept, searchKey)
{
    srv_page = 0;

    $.ajax({
        url: "search.video.ajax.php",
        data: "op=search&searchDept="+searchDept+"&searchKey="+searchKey+"&page=" + srv_page,
        success: function (response)
        {
            $("#divContent").html(response);

            ix_saveHistory($("#divContent").html(), "search.video.ajax.php", "op=search&searchKey="+searchKey+"&page=" + srv_page, "");
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

sr_nextVideoSearchResult = function ()
{
    var idMediaList = $.trim($("#sr_idMediaList").val());
    if (idMediaList.length === 0)
        return;

    srv_page += 1;

    $.ajax({
        url: "search.video.ajax.php",
        data: "op=nextSearch&idMediaList="+idMediaList+"&page=" + srv_page,
        success: function (data)
        {
            $('#tableSearch > tbody:last').append(data);
            $('#divSearch').animate({ scrollTop: $('#divSearch')[0].scrollHeight}, 1500);

            ix_saveHistory($("#divContent").html(), "search.video.ajax.php", "op=nextSearch&idMediaList="+idMediaList+"&page=" + srv_page, "");
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

sr_playVideo = function (idMedia)
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