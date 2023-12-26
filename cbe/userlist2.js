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
    var contentHeight = docHeight - topHeight - footHeight - 100 - 150;
    $("#ul_divContainer").height(contentHeight);

    //$('#ul_divSearchUser').top($("#ul_divContainer").top() + contentHeight + 10);
    $('#ul_divSearchUser').width(contentWidth);
    $('#ul_divSearchUser').height(120);
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

ul_cariUser = function()
{
    var searchUserId = $.trim($("#ul_searchUserId").val());
    if (searchUserId.length === 0)
        return;

    if ($("#ul_jsonUserList").length === 0)
        return;

    var jsonUserList = $("#ul_jsonUserList").val();
    if (jsonUserList.length === 0)
    {
        alert("Tidak ada data user!");
        return;
    }

    var parse = jsonutil_tryParseJson(jsonUserList);
    if (parseInt(parse.Code) < 0)
    {
        mainBox.showError(parse.Message, "", "ul_cariUser()");
        return;
    }

    var lsUser = parse.Data;
    var nUser = lsUser.length;
    var lsFound = [];

    for(var i = 0; i < nUser; i++)
    {
        var userId = lsUser[i][3];
        var userName = lsUser[i][4];
        var sessionId = lsUser[i][5];
        var application = lsUser[i][6];

        if (userId === searchUserId)
            lsFound.push([userId, userName, sessionId, application]);
    }

    if (lsFound.length === 0)
    {
        $("#ul_divSearchResult").html("Tidak ditemukan data user!");
        return;
    }

    var tab = "<table border='1' cellpadding='2' cellspacing='0' style='background-color: #fff; border-width: 1px; border-collapse: collapse;'>";
    for(i = 0; i < lsFound.length; i++)
    {
        userId = lsFound[i][0];
        userName = lsFound[i][1];
        sessionId = lsFound[i][2];
        application = lsFound[i][3];

        tab += "<tr>";
        tab += "<td width='25'>" + (i + 1) + "</td>";
        tab += "<td width='120'>" + userId + "</td>";
        tab += "<td width='180'>" + userName + "</td>";
        tab += "<td width='160'>" + sessionId + "</td>";
        tab += "<td width='140'>" + application + "</td>";
        tab += "<td width='120'><input type='button' class='BtnPrimary' value='hapus' onclick=\"ul_closeConn('" + userId + "','" + sessionId + "')\"></td>";
        tab += "</tr>";
    }
    tab += "</table>";

    $("#ul_divSearchResult").html(tab);
};