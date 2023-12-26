cs_showCariSoal = function()
{
    var idSoal = $("#cs_idSoal").val().trim();
    if (idSoal.length === 0)
        return;

    if (!$.isNumeric(idSoal))
    {
        alert("ID Soal harus berupa bilangan!")
        return;
    }

    if (parseInt(idSoal) <= 0)
    {
        alert("ID Soal harus lebih besar daripada nol!")
        return;
    }

    $.ajax({
        url: "carisoal.ajax.php",
        data: "op=carisoal&idsoal=" + idSoal,
        success: function (data)
        {
            $("#cs_divContent").html(data);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};