$(window).resize(function ()
{
    of_resizeContainer();
});

of_resizeContainer = function()
{
    var menuWidth = $("#tdMenu").width();
    var docWidth = $(window).width();
    var contentWidth = docWidth - menuWidth - 275;
    $("#of_divContainer").width(contentWidth);

    var topHeight = $("#trHeader").height();
    var footHeight = $("#trFooter").height();
    var docHeight = $(window).height();
    var contentHeight = docHeight - topHeight - footHeight - 100;
    $("#of_divContainer").height(contentHeight);
};

of_getUserOfflineList = function()
{
    $("#of_divDaftarPeserta").html("memuat ..");

    $.ajax({
        url: "useroffline.ajax.php",
        data: "op=getuserofflinelist",
        success: function (data)
        {
            $("#of_divDaftarPeserta").html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

of_autoRefresh = function()
{
    of_getUserOfflineList();

    setTimeout(of_autoRefresh, 60000);
};