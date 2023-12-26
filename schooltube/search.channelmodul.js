var srcm_page = 0;

sr_startChannelModulSearch = function (searchBy, searchDept, searchKey)
{
    srcm_page = 0;

    $.ajax({
        url: "search.channelmodul.ajax.php",
        data: "op=search&searchBy="+searchBy+"&searchDept="+searchDept+"&searchKey="+searchKey+"&page=" + srcm_page,
        success: function (response)
        {
            $("#divContent").html(response);

            ix_saveHistory($("#divContent").html(), "search.channelmodul.ajax.php", "op=search&searchBy="+searchBy+"&searchKey="+searchKey+"&page=" + srcm_page, "");
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

sr_nextChannelModulSearchResult = function ()
{
    var idList = $.trim($("#sr_idList").val());
    if (idList.length === 0)
        return;

    var searchBy = $("#sr_searchBy").val();

    srcm_page += 1;

    $.ajax({
        url: "search.channelmodul.ajax.php",
        data: "op=nextSearch&idList=" + idList + "&searchBy=" + searchBy + "&page=" + srcm_page,
        success: function (data)
        {
            $('#tableChannelModulSearch > tbody:last').append(data);
            $('#divChannelModulSearch').animate({ scrollTop: $('#divChannelModulSearch')[0].scrollHeight}, 1500);

            ix_saveHistory($("#divContent").html(), "search.channelmodul.ajax.php", "op=nextSearch&idList=" + idList + "&searchBy=" + searchBy + "&page=" + srcm_page, "");
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
    
};

sr_showModulView = function(idModul)
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

sr_showChannelView = function(idChannel)
{
    $.ajax({
        url: "channel.php",
        data: "idChannel=" + idChannel,
        success: function (html)
        {
            ix_saveHistory(html, "channel.php", "idChannel=" + idChannel);

            $("#divContent").html(html);

            cn_resetPageCounter();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};