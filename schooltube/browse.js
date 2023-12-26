bw_reload = function()
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

bw_changeDept = function()
{
    if ($("#cbDepartemen").has('option').length === 0)
        return;

    var departemen = $("#cbDepartemen").val();

    $("#divBrowse").empty();
    $("#spCbPelajaran").html("memuat ..");

    $.ajax({
        url: "browse.ajax.php",
        data: "op=getPelajaran&departemen=" + departemen,
        success: function (data) {
            $("#spCbPelajaran").html(data);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

bw_changePel = function()
{
    $("#divBrowse").empty();
};

bw_showChannel = function()
{
    if ($("#cbDepartemen").has('option').length === 0)
        return;

    var idPelajaran = parseInt($("#cbPelajaran").val());
    if (idPelajaran === 0)
        return;

    $.ajax({
        url: "browse.ajax.php",
        data: "op=browseChannel&idPelajaran=" + idPelajaran,
        success: function (data)
        {
            $("#divBrowse").html(data);

            ix_saveHistory($("#divContent").html(), "browse.ajax.php", "op=browseChannel&idPelajaran=" + idPelajaran);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })

};

bw_showChannelView = function(idChannel)
{
    $.ajax({
        url: "channel.php",
        data: "idChannel=" + idChannel,
        success: function (html)
        {
            ix_saveHistory(html, "channel.php", "idChannel=" + idChannel);

            $("#divContent").html(html);

            cn_resetPageCounter();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};