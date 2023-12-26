var hm_pageLiked = 1;
var hm_pageViewed = 1;

hm_resetPageCounter = function () {
    hm_pageLiked = 1;
    hm_pageViewed = 1;
};

hm_nextRandomVideo = function()
{
    var dept = $("#homeDept").val();

    $.ajax({
        url: "home.ajax.php",
        data: "op=nextRandomVideo&dept=" + dept,
        success: function (html) {
            $("#divRandomVideo").html(html);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

hm_nextMostLiked = function ()
{
    var idList = $("#idListMostLiked").val();
    if ($.trim(idList).length === 0)
        return;

    hm_pageLiked += 1;

    $.ajax({
        url: "home.ajax.php",
        data: "op=nextMostLiked&idList=" + idList + "&page=" + hm_pageLiked,
        success: function (data)
        {
            if ($.trim(data).length === 0)
                return;

            $('#tabMostLiked > tbody:last').append(data);
            $('#divMostLiked').animate({ scrollTop: $('#divMostLiked')[0].scrollHeight}, 1500);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

hm_nextMostViewed = function()
{
    var idList = $("#idListMostViewed").val();
    if ($.trim(idList).length === 0)
        return;

    hm_pageViewed += 1;

    $.ajax({
        url: "home.ajax.php",
        data: "op=nextMostViewed&idList=" + idList + "&page=" + hm_pageViewed,
        success: function (data)
        {
            if ($.trim(data).length === 0)
                return;

            $('#tabMostViewed > tbody:last').append(data);
            $('#divMostViewed').animate({ scrollTop: $('#divMostViewed')[0].scrollHeight}, 1500);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

hm_changeDept = function()
{
    var dept = $("#homeDept").val();

    $("#divRandomVideo").empty();
    $("#tabMostLikedBody").empty();
    $("#tabMostViewedBody").empty();

    hm_resetPageCounter();

    $.ajax({
        url: "home.ajax.php",
        data: "op=getRandomVideo&dept=" + dept,
        success: function (html)
        {
            $("#divRandomVideo").html(html);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });

    $.ajax({
        url: "home.ajax.php",
        data: "op=getMostLikedVideoIdList&dept=" + dept,
        success: function (idList)
        {
            $("#idListMostLiked").val(idList);

            $.ajax({
                url: "home.ajax.php",
                data: "op=showMostLikedVideo&idList=" + idList,
                success: function (data)
                {
                    $('#tabMostLiked > tbody:last').append(data);
                    $('#divMostLiked').animate({ scrollTop: $('#divMostLiked')[0].scrollHeight}, 1500);
                },
                error: function(xhr)
                {
                    alert(xhr.responseText);
                }
            });
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });

    $.ajax({
        url: "home.ajax.php",
        data: "op=getMostViewedVideoIdList&dept=" + dept,
        success: function (idList)
        {
            $("#idListMostViewed").val(idList);

            $.ajax({
                url: "home.ajax.php",
                data: "op=showMostViewedVideo&idList=" + idList,
                success: function (data)
                {
                    $('#tabMostViewed > tbody:last').append(data);
                    $('#divMostViewed').animate({ scrollTop: $('#divMostViewed')[0].scrollHeight}, 1500);
                },
                error: function(xhr)
                {
                    alert(xhr.responseText);
                }
            });
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });


};