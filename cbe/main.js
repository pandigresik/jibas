var mainBox = null; // main dialog box

$(document).ready(function()
{
    main_initModal();

    main_startService();

    main_resizeContent();
});

$(window).resize(function ()
{
    main_resizeContent();
});

main_resizeContent = function()
{
    var topHeight = $("#trHeader").height();
    var footHeight = $("#trFooter").height();
    var docHeight = $(window).height();

    var contentHeight = docHeight - topHeight - footHeight - 20;
    $("#divContent").height(contentHeight);
};

main_initModal = function()
{
    mainBox = new DialogBox("#divDialog", 500, 240);
};

main_startService = function()
{
    pingSvc_Start(".");
};

main_Logout = function()
{
    if (!confirm("Keluar dari aplikasi JIBAS CBE?"))
        return;

    $.ajax({
        url: "main.ajax.php",
        data: "op=logout",
        success: function(json)
        {
            /*
            var data = $.parseJSON(json);
            if (parseInt(data.Code) < 0)
                mainBox.show(data.Message);
            else
            */

            document.location.href = "include/logout.php";
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};
