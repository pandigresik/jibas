simpanKonfigurasi = function ()
{
    if (!$("#departemen").val())
        return;

    if (!$("#tabungan").val())
        return;

    if (!$("#rekkasvendor").val())
        return;

    if (!$("#rekutangvendor").val())
        return;

    var dept = $("#departemen").val();
    var idPt = $("#idpt").val();
    var idTabungan = $("#tabungan").val();
    var maxTrans = $("#maxtrans").val();
    maxTrans = rupiahToNumber(maxTrans);
    if ($.trim(maxTrans).length === 0) maxTrans = 0;

    var rekKasVendor = $("#rekkasvendor").val();
    var rekUtangVendor = $("#rekutangvendor").val();

    var data = "op=435353456346346&dept=" + dept + "&idpt=" + idPt + "&idtabungan=" + idTabungan + "&maxtrans=" + maxTrans + "&rekkas=" + rekKasVendor + "&rekutang=" + rekUtangVendor;

    console.log(dept + " "  + idPt + " " + idTabungan + " " + maxTrans + " " + rekKasVendor + " " + rekUtangVendor);

    $.ajax({
        url: "konfigurasi.pegawai.dialog.ajax.php",
        data: data,
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


changeDept = function()
{
    if (!$("#departemen").val())
        return;

    var dept = $("#departemen").val();

    var data = "op=654736547624&dept=" + dept;
    $.ajax({
        url: "konfigurasi.pegawai.dialog.ajax.php",
        data: data,
        success: function (html)
        {
            $("#spTabungan").html(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};