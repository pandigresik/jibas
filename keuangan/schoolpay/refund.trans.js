$(document).ready(function() {
    setTimeout(function () {
        setTimeout(setTableStyle, 100);
    }, 300);
});

setTableStyle = function () {
    Tables('table', 1, 0);
};

chTagihanChange = function(no)
{
    var tagihan = calculateTagihan();
    $("#jumlah").val(numberToRupiah(tagihan));
};

calculateTagihan = function()
{
    var nData = parseInt($("#ndata").val());

    var tagihan = 0;
    for(var i = 1; i <= nData; i++)
    {
        var checked = $("#chTagihan" + i).is(":checked");
        if (!checked)
            continue;

        tagihan += parseInt($("#tagihan" + i).val());
    }

    return tagihan;
};

bayarTagihan = function ()
{
    if (!$("#penerima").val())
        return;

    var tagihan = calculateTagihan();
    if (tagihan === 0)
    {
        alert("Belum ada tagihan yang akan dibayarkan!")
        return;
    }

    if (!confirm("Data sudah benar?"))
        return;

    var req = new RequestFactory();
    req.add("op", "7834682374672834324");
    req.add("vendorid", $("#vendorid").val());
    req.add("departemen", $("#departemen").val());
    req.add("idtahunbuku", $("#idtahunbuku").val());
    req.add("idpenerima", $("#penerima").val());
    req.add("keterangan", $("#keterangan").val());

    var cnt = 0;
    var nData = parseInt($("#ndata").val());
    for(var i = 1; i <= nData; i++)
    {
        var checked = $("#chTagihan" + i).is(":checked");
        if (!checked)
            continue;

        var itanggal = $("#tanggal" + i).val();
        var itagihan = $("#tagihan" + i).val();
        var ireplid = $("#replid" + i).val();

        cnt += 1;

        var param = "tanggal" + cnt;
        req.add(param, itanggal);

        param = "tagihan" + cnt;
        req.add(param, itagihan);

        param = "replid" + cnt;
        req.add(param, ireplid);
    }
    req.add("ntanggal", cnt);

    //console.log(req.createQs());
    $.ajax({
        url: "refund.trans.save.php",
        method: "POST",
        data: req.createQs(),
        success: function (idRefund)
        {
            //opener.showRefundHistory();
            opener.refreshReport(idRefund);
            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};