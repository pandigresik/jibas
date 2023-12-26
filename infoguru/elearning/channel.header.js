function ch_changeDept()
{
    if ($("#departemen").has('option').length === 0)
        return;

    var dept = $("#departemen").val();
    $.ajax({
        url: "channel.header.ajax.php",
        data: "op=getpelajaran&dept=" + dept,
        success: function (html)
        {
            $("#divPelajaran").html(html);
        },
        error: function (xhr, response, error)
        {
            alert(xhr.responseText);
        }
    });

    parent.content.location.href = "channel.blank.php";
}

function ch_changePel()
{
    parent.content.location.href = "channel.blank.php";
}

function ch_tampil()
{
    if ($("#departemen").has('option').length === 0)
        return;

    if ($("#pelajaran").has('option').length === 0)
        return;

    var dept = $("#departemen").val();
    var idpel = $("#pelajaran").val();

    parent.content.location.href = "channel.content.php?dept=" + dept + "&idpel=" + idpel;
}