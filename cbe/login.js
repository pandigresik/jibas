var mainBox = null;

$(document).ready(function()
{
    login_initView();
    login_loadBackground();
    login_centerMain();

    mainBox = new DialogBox("#divDialog", 420, 180);
});

login_initView = function ()
{
    $('#txLogin').val("Login");
    $('#txPassword').val("Password");
};

login_loadBackground = function ()
{
    $(document).bgStretcher({
        images: ['../images/background15.jpg'], imageWidth: 1680, imageHeight: 1050
    });
};

login_centerMain = function() {
    var WinHeight = 0;
    var WinWidth = 0;

    if (typeof( window.innerWidth ) == 'number' )
    {
        WinHeight = window.innerHeight;
        WinWidth = window.innerWidth;
    }
    else if (document.documentElement &&
        ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
        WinHeight = document.documentElement.clientHeight;
        WinWidth = document.documentElement.clientWidth;
    }
    else if( document.body &&
        ( document.body.clientWidth || document.body.clientHeight ) )
    {
        WinHeight = document.body.clientHeight;
        WinWidth = document.body.clientWidth;
    }

    document.getElementById('Main').style.left = (parseInt(WinWidth)/2-290)+"px";
    document.getElementById('Main').style.top = (parseInt(WinHeight)/2-150)+"px";
};

setFocus = function (idElement, valueDefault)
{
    var val = $(idElement).val().trim();
    if (val === valueDefault)
        $(idElement).val("");
};

setBlur = function (idElement, valueDefault)
{
    var val = $(idElement).val().trim();
    if (val === "")
        $(idElement).val(valueDefault);
};

index_lockInput = function (lock) {
    $("#txLogin").prop("disabled", lock);
    $("#txPassword").prop("disabled", lock);
    $("#btLogin").prop("disabled", lock);
};

btLogin_click = function ()
{
    var login = $('#txLogin').val().trim().toUpperCase();
    if (login === "Login") login = "";
    if (login === "")
    {
        alert("Masukan nomor identitas!");
        $('#txLogin').focus();
        return;
    }

    var password = $('#txPassword').val().trim();
    if (password === "Password") password = "";
    if (password === "")
    {
        alert("Masukan password!");
        $('#txPassword').focus();
        return;
    }

    index_lockInput(true);
    $("#lbInfo").html("menghubungi CBE Server ...");

    $.ajax({
        url: "login.ajax.php",
        data: "op=login&login=" + login + "&password=" + password,
        success: function(data)
        {
            var parse = jsonutil_tryParseJson(data);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "btLogin_click()");
                return;
            }

            //var ret = $.parseJSON(data);
            var ret = parse.Data;
            if (parseInt(ret.Code) < 0)
            {
                mainBox.show(ret.Message);

                index_lockInput(false);
                $("#lbInfo").html(".");
            }
            else
            {
                document.location.href = "main.php";
            }
        },
        error: function (xhr, response, error)
        {
            alert(xhr.responseText);

            index_lockInput(false);
            $("#lbInfo").val(".");
        }
    });
};

showClearConn = function ()
{
    var addr = "clearconn.php";
    newWindow(addr, 'ClearConn', '750', '500', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
};