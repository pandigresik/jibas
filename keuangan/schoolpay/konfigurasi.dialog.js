simpanKonfigurasi = function ()
{
    if (!$("#tabungan").val())
        return;

    if (!$("#rekkasvendor").val())
        return;

    if (!$("#rekutangvendor").val())
        return;

    var dept = $("#dept").val();
    var idPt = $("#idpt").val();
    var idTabungan = $("#tabungan").val();
    var maxTrans = $("#maxtrans").val();
    maxTrans = rupiahToNumber(maxTrans);
    if ($.trim(maxTrans).length === 0) maxTrans = 0;

    var rekKasVendor = $("#rekkasvendor").val();
    var rekUtangVendor = $("#rekutangvendor").val();

    // var data = "op=123873891273&dept=" + dept + "&idpt=" + idPt + "&idtabungan=" + idTabungan + "&maxtrans=" + maxTrans + "&rekkas=" + rekKasVendor + "&rekutang=" + rekUtangVendor;

    var request = new RequestFactory();
    request.add("op", "123873891273");
    request.add("dept", dept);
    request.add("idpt", idPt);
    request.add("idtabungan", idTabungan);
    request.add("maxtrans", maxTrans);
    request.add("rekkas", rekKasVendor);
    request.add("rekutang", rekUtangVendor);
    var qs = request.createQs();

    $.ajax({
        url: "konfigurasi.dialog.ajax.php",
        method: "POST",
        data: qs,
        success: function ()
        {
            opener.location.reload();
            window.close();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })

};