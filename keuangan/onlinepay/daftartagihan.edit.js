$(document).ready(function ()
{

});

validateInput = function () {

};

calcPay1 = function ()
{
    var jtagihan = $("#jtagihan").val();
    var jdiskon = rupiahToNumber($("#jdiskon").val());

    calcPay(jtagihan, jdiskon);
};

calcPay2 = function ()
{
    var jtagihan = rupiahToNumber($("#jtagihan").val());
    var jdiskon = $("#jdiskon").val();

    calcPay(jtagihan, jdiskon);
};

calcPay = function (jtagihan, jdiskon)
{
    var jpembayaran = $("#jpembayaran");

    if (isNaN(jtagihan))
    {
        jpembayaran.html("ERROR");
        return;
    }

    if (isNaN(jdiskon))
    {
        jpembayaran.html("ERROR");
        return;
    }

    jtagihan = parseInt(jtagihan);
    jdiskon = parseInt(jdiskon);
    if (jtagihan <= jdiskon)
    {
        jpembayaran.html("ERROR");
        return;
    }

    var jbayar = jtagihan - jdiskon;
    jpembayaran.html(numberToRupiah(jbayar));
};

checkInvoiceStatusFromJsServer = function (execFunc, execData)
{
    var qs = "op=modifinvoice";
    qs += "&nomor=" + encodeURIComponent(execData);

    $.ajax({
        url: "appserver.sender.php",
        method: "POST",
        data: qs,
        success: function (json)
        {
            //console.log("checkInvoiceStatusFromServer result " + json);

            var gr = $.parseJSON(json);
            if (parseInt(gr.Value) !== 1)
            {
                $("#btSimpan").attr("disabled", false);
                $("#spInfo").css("color", "#FF0000");
                $("#spInfo").html("Maaf, ada kekeliruan: Tidak dapat menghubungi aplikasi JIBAS Sinkronisasi Jendela Sekolah (" + gr.Text + ")");

                alert("Maaf, ada kekeliruan: Tidak dapat menghubungi aplikasi JIBAS Sinkronisasi Jendela Sekolah (" + gr.Text + ")");
                return;
            }

            var grResult = $.parseJSON(gr.Data);
            if (parseInt(grResult.Value) !== 1)
            {
                $("#btSimpan").attr("disabled", false);
                $("#spInfo").css("color", "#FF0000");
                $("#spInfo").html("Maaf, tagihan ini tidak dapat dimodifikasi: " + grResult.Text);

                alert("Maaf, tagihan ini tidak dapat dimodifikasi: " + grResult.Text);
                return;
            }

            execFunc();
        },
        error: function(xhr)
        {
            $("#btSimpan").attr("disabled", false);
            $("#spInfo").css("color", "#FF0000");
            $("#spInfo").html(xhr.responseText);

            alert(xhr.responseText);
        }
    })
};

simpanEdit = function ()
{
    $("#btSimpan").attr("disabled", true);
    $("#spInfo").css("color", "#0000FF");
    $("#spInfo").html("menyimpan");

    var noTagihan = $("#notagihan").val();
    checkInvoiceStatusFromJsServer(doSimpanEdit, noTagihan);
};

doSimpanEdit = function ()
{
    var jcicilan = parseInt($("#jcicilan").val());
    var jsisa = parseInt($("#jsisa").val());
    var jtagihan = rupiahToNumber($("#jtagihan").val());
    var jdiskon = rupiahToNumber($("#jdiskon").val());

    if (isNaN(jtagihan))
    {
        alert("Jumlah tagihan harus angka!");
        $("#jtagihan").focus();
        return;
    }

    if (isNaN(jdiskon))
    {
        alert("Jumlah diskon harus angka!");
        $("#jdiskon").focus();
        return;
    }

    jtagihan = parseInt(jtagihan);
    jdiskon = parseInt(jdiskon);

    if (jtagihan <= jdiskon)
    {
        alert("Jumlah tagihan harus lebih besar daripada jumlah diskon!");
        $("#jtagihan").focus();
        return;
    }

    if (jtagihan === 0)
    {
        alert("Jumlah tagihan harus positif");
        $("#jtagihan").focus();
        return;
    }

    if (jtagihan < jcicilan)
    {
        alert("Jumlah tagihan harus lebih besar daripada cicilan pembayaran (" + numberToRupiah(jcicilan) + ")");
        $("#jtagihan").focus();
        return;
    }

    if (jtagihan - jdiskon > jsisa)
    {
        alert("Jumlah pembayaran harus lebih besar daripada sisa iuran (" + numberToRupiah(jsisa) + ")");
        $("#jtagihan").focus();
        return;
    }

    var data = "op=439278934234";
    data += "&idtagihandata=" + $("#idtagihandata").val();
    data += "&notagihan=" + $("#notagihan").val();
    data += "&jtagihan=" + jtagihan;
    data += "&jdiskon=" + jdiskon;

    $.ajax({
        url: "daftartagihan.edit.ajax.php",
        method: "POST",
        data: data,
        success: function (json)
        {
            //console.log(json);

            var result = $.parseJSON(json);
            if (parseInt(result[0]) === -1)
            {
                alert(result[2]);
                return;
            }

            sendToAppServer("datasync");

            opener.refreshTagihanData();

            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });

};