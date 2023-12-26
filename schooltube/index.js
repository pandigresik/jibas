var mainBox = null; // main dialog box

$(document).ready(function()
{
    ix_initModal();

    ix_resizeContent();

    ix_initControls();
});

$(window).resize(function ()
{
    ix_resizeContent();
});

ix_saveHistory = function(content, page, data, title)
{
    $.ajax({
        url: "index.ajax.php",
        data: "op=getCurrentSession",
        success: function(userId)
        {
            var state = {content: content, page: page, data: data, userId: userId};
            history.pushState(state, title, ".");
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });

};

ix_initModal = function()
{
    mainBox = new DialogBox("#divDialog", 500, 240);
};

ix_resizeContent = function()
{
    var topHeight = $("#trHeader").height();
    var footHeight = $("#trFooter").height();
    var docHeight = $(window).height();

    var contentHeight = docHeight - topHeight - footHeight - 20;
    $("#divContent").height(contentHeight);
};

ix_initControls = function()
{
    $("#searchKey").on("keyup", function (e)
    {
        $("#searchInfo").html("");

        if (e.keyCode !== 13)
            return;

        sr_startSearch();
    });

    window.onpopstate = function(e)
    {
        ix_setDivContent(e);
    };
};

ix_clearSearch = function()
{
    $("#searchKey").val("");
};

ix_setDivContent = function(e)
{
    if (e.state)
    {
        if (e.state.content === null)
        {

        }
        else
        {
            var stateUserId = e.state.userId;
            if (stateUserId === "---")
            {
                $("#divContent").html(e.state.content);
            }
            else
            {
                $.ajax({
                    url: "index.ajax.php",
                    data: "op=getCurrentSession",
                    success: function(userId)
                    {
                        if (userId === stateUserId)
                            $("#divContent").html(e.state.content);
                        else
                            $("#divContent").html("Document Expired!");
                    },
                    error: function (xhr) {
                        alert(xhr.responseText);
                    }
                });
            }
        }
    }
};

ix_doLogin = function () {

    var login = $.trim($("#ix_login").val());
    var password = $.trim($("#ix_password").val());

    if (login.length === 0 || password.length === 0)
        return;

    if (login.toLowerCase() === "jibas")
    {
        alert("Administrator JIBAS tidak bisa login!")
        return;
    }

    $.ajax({
        url: "index.ajax.php",
        data: "op=doLogin&login=" + login + "&password=" + password,
        success: function (json)
        {
            console.log(json);

            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "ix_doLogin");
                return;
            }

            var gnrt = parse.Data;
            if (parseInt(gnrt.Code) < 0)
            {
                mainBox.show(gnrt.Message, "");
                return;
            }

            ix_showLoginMenu();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })

};

ix_showLoginMenu = function ()
{
    $.ajax({
        url: "index.ajax.php",
        data: "op=showLoginMenu",
        success: function (html) {
            $("#divMenu").html(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

ix_showDefaultMenu = function ()
{
    $.ajax({
        url: "index.ajax.php",
        data: "op=showDefaultMenu",
        success: function (html) {
            $("#divMenu").html(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

ix_showHome = function()
{
    $.ajax({
        url: "home.php",
        success: function (html) {
            $("#divContent").html(html);

            hm_resetPageCounter();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

ix_loginAdmin = function()
{
    var addr = "admin.php";
    newWindow(addr, 'Admin', '800', '400', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
};

ix_browseChannel = function()
{
    $.ajax({
        url: "browse.php",
        success: function (html) {
            $("#divContent").html(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

ix_doLogout = function()
{
    if (!confirm("Logout?"))
        return;

    $.ajax({
        url: "index.ajax.php",
        data: "op=logout",
        success: function () {
            ix_showDefaultMenu();
            ix_showHome();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

ix_showFollow = function(type)
{
    $.ajax({
        url: "following.php",
        data: "type=" + type,
        success: function (html)
        {
            ix_saveHistory(html, "following.php", "type=" + type);

            $("#divContent").html(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

ix_showFavVideo = function()
{
    $.ajax({
        url: "fav.php",
        success: function (html)
        {
            ix_saveHistory(html, "fav.php", "");

            fav_resetPage();

            $("#divContent").html(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};