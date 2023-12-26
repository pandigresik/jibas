ad_login = function ()
{
    var password = $.trim($("#txPassword").val());
    if (password.length === 0)
        return;

    $.ajax({
        url: "admin.ajax.php",
        data: "op=doLogin&password=" + password,
        success: function (json)
        {
            console.log(json);

            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                alert(parse.Message);
                return;
            }

            var gnrt = parse.Data;
            if (parseInt(gnrt.Code) < 0)
            {
                alert(gnrt.Message);
                return;
            }

            document.location.reload();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

ad_saveSetting = function ()
{
    var allow = $("#chAllow").prop("checked") ? "true" : "false";
    var info = $.trim($("#txKeterangan").val());

    $.ajax({
        url: "admin.ajax.php",
        data: "op=saveSetting&allow=" + allow + "&info=" + encodeURIComponent(info),
        success: function ()
        {
            alert("Pengaturan telah disimpan");
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

ad_logout = function()
{
    $.ajax({
        url: "admin.ajax.php",
        data: "op=doLogout",
        success: function ()
        {
            window.close();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};