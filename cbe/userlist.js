$(window).resize(function ()
{
    ul_resizeContainer();
});

ul_resizeContainer = function()
{
    var menuWidth = $("#tdMenu").width();
    var docWidth = $(window).width();
    var contentWidth = docWidth - menuWidth - 275;
    $("#ul_divContainer").width(contentWidth);

    var topHeight = $("#trHeader").height();
    var footHeight = $("#trFooter").height();
    var docHeight = $(window).height();
    var contentHeight = docHeight - topHeight - footHeight - 100;
    $("#ul_divContainer").height(contentHeight);
};

ul_getUserList = function()
{
    $("#ul_divDaftarPeserta").html("memuat ..");

    $.ajax({
        url: "userlist.ajax.php",
        data: "op=getuserlist",
        success: function (data)
        {
            $("#ul_divDaftarPeserta").html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

ul_autoRefresh = function()
{
    ul_getUserList();

    setTimeout(ul_autoRefresh, 60000);
};

ul_closeConn = function(userId, sessionId)
{
    if (!confirm("Apakah anda akan menghapus koneksi pengguna ini?"))
        return;

    $.ajax({
        url: "userlist.ajax.php",
        data: "op=removeuserconn&userid=" + userId + "&sessionid=" + sessionId,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "ul_closeConn()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) <= 0)
            {
                mainBox.showError(result.Message, "", "ul_closeConn()");
                return;
            }

            ul_getUserList();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};
