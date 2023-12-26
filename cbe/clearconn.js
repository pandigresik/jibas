btHapus_click = function ()
{
    var login = $('#txLogin').val().trim();
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

    $("#lbInfo").html("menghubungi CBE Server ...");

    $.ajax({
        url: "clearconn.ajax.php",
        data: "op=clearconn&login=" + login + "&password=" + password,
        success: function(data)
        {
            console.log(data);

            var parse = jsonutil_tryParseJson(data);
            if (parseInt(parse.Code) < 0)
            {
                alert("KESALAHAN:\r\n" + parse.Message);
                return;
            }

            //var ret = $.parseJSON(data);
            var ret = parse.Data;
            if (parseInt(ret.Code) < 0)
            {
                alert("KESALAHAN:\r\n" + ret.Message);
                $("#lbInfo").html(".");
                return;
            }

            alert("INFORMASI:\r\n" + ret.Message);
            $("#lbInfo").html(".");

            window.close();
        },
        error: function (xhr, response, error)
        {
            alert(xhr.responseText);
            $("#lbInfo").val(".");
        }
    });
};

btTutup_click = function ()
{
    window.close();
};