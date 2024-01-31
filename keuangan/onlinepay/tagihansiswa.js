$(document).ready(function() {
    setTimeout(function () {
        $('#txBarcode').focus();
    }, 300);

    $(document).ready(function () {
        if ($("#tabIuran").length)
            Tables('tabIuran', 1, 0);
    });
});

changeDep = function ()
{
    $("#divContent").html("");

    var dept = $("#departemen").val();
    fetchTahunBuku(dept);
};

changeTahunBuku = function ()
{
    $("#divContent").html("");
};

fetchTahunBuku = function (dept)
{
    $.ajax({
        url: "tagihansiswa.ajax.php",
        method: "POST",
        data: "op=fetchtahunbuku&dept=" + dept,
        success: function (data)
        {
            $("#divtahunbuku").html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

scanBarcode = function (e)
{
    var keycode = (e.keyCode ? e.keyCode : e.which);
    if (keycode !== 13)
        return;

    var kode = $.trim($('#txBarcode').val());
    if (kode.length === 0)
        return;

    var departemen = $('#departemen').val();
    if (departemen.length === 0)
        return;

    $('#spScanInfo').html("");

    var qsdata = "kode="+kode+"&departemen="+departemen;
    $.ajax({
        url: "barcode.php",
        type: 'GET',
        data: qsdata,
        success: function (response)
        {
            $('#txBarcode').val('');

            var dataCard = $.parseJSON(response);
            if (dataCard.status === "1")
            {
                $("#kelompok").val(dataCard.data);
                $("#noid").val(dataCard.noid);
                $("#nama").val(dataCard.nama);
                $("#kelas").val(dataCard.kelas);

                showInvoiceList();
            }
            else
            {
                $('#spScanInfo').html(dataCard.message);
            }
        },
        error: function (xhr, response, error)
        {
            alert(xhr.responseText);
        }
    });
};

SearchUser = function()
{
    var dep = $("#departemen").val();
    var addr = "carisiswa.php?departemen="+dep+"&selectdept=0";
    newWindow(addr, 'CariSiswa', '550', '590', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

AcceptSearch = function(data, noid, nama, kelas)
{
    $("#kelompok").val(data);
    $("#noid").val(noid);
    $("#nama").val(nama);
    $("#kelas").val(kelas);

    showInvoiceList();
};

showInvoiceList = function()
{
    if (!$("#departemen").length) return;
    if (!$("#tahunbuku").length) return;

    var nis = $.trim($("#noid").val());
    if (nis.length === 0)
        return;

    var nama = $.trim($("#nama").val());

    var request = new RequestFactory();
    request.add("op", "invoicelist");
    request.add("dept", $("#departemen").val());
    request.add("idtahunbuku", $("#tahunbuku").val());
    request.add("tahunbuku", $("#tahunbuku option:selected").text());
    request.add("dept", $("#departemen").val());
    request.add("bulan", $("#bulan").val());
    request.add("tahun", $("#tahun").val());
    request.add("nis", nis);
    request.add("nama",  nama);
    request.add("skipalreadypaid", $("#skipinvoice").is(":checked") ? 1 : 0);
    var qs = request.createQs();

    $("#divContent").html("<br><br><br><br><i>memuat ..</i>");

    $.ajax({
        url: "tagihansiswa.ajax.php",
        data: qs,
        success: function (data)
        {
            $("#divContent").html(data);

            if ($("#tabIuran").length)
                Tables('tabIuran', 1, 0);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

onCheckIuran = function (no)
{
    var isChecked = $("#chiuran" + no).prop("checked");
    var color = isChecked ? "#fff" : "#ccc";

    $("#tagihan" + no).prop("disabled", !isChecked);
    $("#diskon" + no).prop("disabled", !isChecked);
    $("#tagihan" + no).css("background-color", color);
    $("#diskon" + no).css("background-color", color);

    if (isChecked)
        $("#tagihan" + no).focus();

    calculateTotalTagihan();
};

checkIuran = function(state)
{
    if (!$("#niuran").length)
        return;

    var nIuran = $("#niuran").val();
    var isChecked = state === 1;
    var color = isChecked ? "#fff" : "#ccc";

    for(var i = 1; i <= nIuran; i++)
    {
        $("#chiuran" + i).prop("checked", isChecked);
        $("#tagihan" + i).prop("disabled", !isChecked);
        $("#diskon" + i).prop("disabled", !isChecked);
        $("#tagihan" + i).css("background-color", color);
        $("#diskon" + i).css("background-color", color);
    }

    if (isChecked)
        calculateTotalTagihan();
    else
        $("#spTotal").html(numberToRupiah("0"));
};

validatePayment = function (element, title)
{
    var besar = rupiahToNumber($(element).val());
    if ($.trim(besar).length === 0)
    {
        $("#spInfoBayar").html(title + " belum ditentukan!");
        $(element).focus();
        return false;
    }
    else if (isNaN(besar))
    {
        $("#spInfoBayar").html(title + " harus bilangan!");
        $(element).focus();
        return false;
    }
    else if (besar < 0)
    {
        $("#spInfoBayar").html(title + " harus positif!");
        $(element).focus();
        return false;
    }

    $("#spInfoBayar").html("");
    return true;
};

validatePayment = function (element, title)
{
    var besar = rupiahToNumber($(element).val());
    if ($.trim(besar).length === 0)
    {
        $("#spInfo").html(title + " belum ditentukan!");
        $(element).focus();
        return false;
    }
    else if (isNaN(besar))
    {
        $("#spInfo").html(title + " harus bilangan!");
        $(element).focus();
        return false;
    }
    else if (besar < 0)
    {
        $("#spInfo").html(title + " harus positif!");
        $(element).focus();
        return false;
    }

    $("#spInfo").html("");
    return true;
};

calculateTotalTagihan = function ()
{
    var total = 0;
    var nIuran = $("#niuran").val();
    var isOk = true;

    for(var no = 1; no <= nIuran; no++)
    {
        var isChecked = $("#chiuran" + no).prop("checked");
        if (!isChecked) continue;

        var isValid = validatePayment("#tagihan" + no, "Cicilan ke-" + no) &&
            validatePayment("#diskon" + no, "Diskon ke-" + no);

        if (!isValid)
        {
            $("#spTotal").html("ERROR");
            $("#valStatus").val(-1);
            isOk = false;
            break;
        }

        var tagihan = parseInt(rupiahToNumber($("#tagihan" + no).val()));
        var diskon = parseInt(rupiahToNumber($("#diskon" + no).val()));
        var sisa = parseInt($("#sisa" + no).val());
        var subTotal = tagihan - diskon;
        if (subTotal > sisa)
        {
            $("#spTotal").html("ERROR");
            $("#spInfo").html("Jumlah tagihan lebih besar daripada sisa bayaran!")
            $("#valStatus").val(-1);
            isOk = false;
            break;
        }

        total += tagihan - diskon;
    }

    if (isOk)
    {
        $("#valStatus").val(1);
        $("#spTotal").html(numberToRupiah(total));
    }
};

createInvoice = function ()
{
    var nIuran = $("#niuran").val();
    var stIdIuran = "";
    var stIuran = "";
    var invalidTagihan = 0;
    var stTagihan = "";
    var invalidDiskon = 0;
    var stDiskon = "";
    for(var i = 1; i <= nIuran && invalidDiskon === 0 && invalidTagihan === 0; i++)
    {
        if (!$("#chiuran" + i).is(":checked"))
            continue;

        var idIuran = $("#idiuran" + i).val();
        if (stIdIuran !== "") stIdIuran += ",";
        stIdIuran += idIuran;

        var iuran = $("#iuran" + i).val();
        if (stIuran !== "") stIuran += ", ";
        stIuran += iuran;

        var tagihan = rupiahToNumber($("#tagihan" + i).val());
        if (isNaN((tagihan)))
            invalidTagihan = i;

        if (stTagihan !== "") stTagihan += ",";
        stTagihan += tagihan;

        var diskon = rupiahToNumber($("#diskon" + i).val());
        if (isNaN((diskon)))
            invalidDiskon = i;

        if (stDiskon !== "") stDiskon += ",";
        stDiskon += diskon;
    }

    if (invalidTagihan !== 0)
    {
        alert("Tagihan harus berupa angka");
        $("#tagihan" + invalidTagihan).focus();
        return;
    }

    if (invalidDiskon !== 0)
    {
        alert("Diskon harus berupa angka");
        $("#diskon" + invalidDiskon).focus();
        return;
    }

    if (stIdIuran === "")
    {
        alert("Pilih minimal satu iuran");
        return;
    }

    if (!confirm("Data sudah benar?"))
        return;

    var request = new RequestFactory();
    request.add("op", "createinvoice");
    request.add("dept", $("#departemen").val());
    request.add("idtahunbuku", $("#tahunbuku").val());
    request.add("nis", $("#noid").val());
    request.add("nama", $("#nama").val());
    request.add("idiuran", stIdIuran);
    request.add("iuran", stIuran);
    request.add("tagihan", stTagihan);
    request.add("diskon", stDiskon);
    request.add("bulan", $("#bulan").val());
    request.add("tahun", $("#tahun").val());
    request.add("keterangan", $("#keterangan").val());
    request.add("sendnotif", $("#chnotif").is(":checked") ? 1 : 0);
    request.add("skipalreadypaid", $("#skipinvoice").is(":checked") ? 1 : 0);
    var qs = request.createQs();

    $("#btBuatTagihan").prop("disabled", true);
    $("#spBuatTagihan").html("menyimpan ..");
    $("#divContent").html("<br><br><br><br><i>menyimpan .. </i>");

    $.ajax({
        url: "tagihansiswa.ajax.php",
        method: "POST",
        data: qs,
        success: function (json)
        {
            //console.log(json);

            var result = $.parseJSON(json);

            var color = "#0000FF";
            if (result[0] === -1)
                color = "#FF0000";
            else if (result[0] === 0)
                color = "#9932CC";

            if (result[0] === 1)
                sendToAppServer("datasync");

            $("#btBuatTagihan").prop("disabled", false);
            $("#spBuatTagihan").css("color", color);
            $("#spBuatTagihan").html(result[1]);

            $("#divContent").html("<br><br><br><br>" + result[1]);

            alert(result[1]);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};