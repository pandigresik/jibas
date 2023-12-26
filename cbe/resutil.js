$(window).resize(function ()
{
    ru_resizeContainer();
});

ru_resizeContainer = function()
{
    var menuWidth = $("#tdMenu").width();
    var docWidth = $(window).width();
    var contentWidth = docWidth - menuWidth - 30;
    $("#ru_divContainer").width(contentWidth);

    var topHeight = $("#trHeader").height();
    var footHeight = $("#trFooter").height();
    var docHeight = $(window).height();
    var contentHeight = docHeight - topHeight - footHeight - 100;
    $("#ru_divContainer").height(contentHeight);
};

ru_showResDir = function()
{
    $("#ru_divResDir").html("memuat ... ");

    $.ajax({
        url: "resutil.ajax.php",
        data: "op=showresdir",
        success: function(data)
        {
            $("#ru_divResDir").html(data);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

ru_delResDir = function(no)
{
    if (!confirm("Apakah anda yakin akan menghapus direktori ini?"))
        return;

    var path = $("#dir-" + no).val();

    var spDel = $("#spDel-" + no);
    spDel.css("pointer-events", "none");
    spDel.html("menghapus .. ");

    $.ajax({
        url: "resutil.ajax.php",
        data: "op=delresdir&path=" + encodeURI(path),
        success: function ()
        {
            ru_showResDir();
        },
        error: function (xhr)
        {
            spDel.css("pointer-events", "auto");
            spDel.html("hapus");

            alert(xhr.responseText);
        }
    })
};

ru_checkSize = function(no)
{
    var path = $("#dir-" + no).val();

    var spSize = $("#spSize-" + no);
    spSize.css("pointer-events", "none");
    spSize.html("menghitung ... ");

    $.ajax({
        url: "resutil.ajax.php",
        data: "op=checksize&path=" + encodeURI(path),
        success: function(data)
        {
            spSize.html(data);
        },
        error: function (xhr)
        {
            spSize.html("check size");
            spSize.css("pointer-events", "auto");

            alert(xhr.responseText);
        }
    })
};