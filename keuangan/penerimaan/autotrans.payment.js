var arrPaymentInfo = [];

$(document).ready(function() {
    setTimeout(function () {
        $('#txBarcode').focus();
    }, 300);
});

SearchUser = function()
{
    var dep = $("#departemen").val();
    var addr = "multitrans.searchuser.php?departemen="+dep;
    newWindow(addr, 'CariUser', '550', '590', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

change_dep = function ()
{
    var dep = $("#departemen").val();
    document.location.href = "autotrans.payment.php?departemen=" + dep;
};

AcceptSearch = function(data, noid, nama, kelas)
{
    $("#kelompok").val(data);
    $("#noid").val(noid);
    $("#nama").val(nama);
    $("#kelas").val(kelas);

    showPaymentSelect();
};

tutupPembayaran = function()
{
    if (!confirm("Tutup pembayaran?"))
        return;

    $("#kelompok").val("");
    $("#noid").val("");
    $("#nama").val("");
    $("#kelas").val("");

    $("#divPaymentSelect").html("");
    $("#divPaymentList").html("");
    $("#divPaymentInfo").html("");
};

showPaymentSelect = function()
{
    var departemen = $('#departemen').val();
    if (departemen.length === 0)
        return;

    var kelompok = $("#kelompok").val() === "siswa" ? 1 : 2;

    var divSelect = $("#divPaymentSelect");
    var divList = $("#divPaymentList");
    var divInfo = $("#divPaymentInfo");

    divSelect.html("memuat ..");
    divList.html("");

    $.ajax({
        url: "autotrans.payment.ajax.php",
        data: "op=getselect&departemen=" + departemen + "&kelompok=" + kelompok,
        success: function (json)
        {
            var arrJson = JSON.parse(json);

            var idFirst = parseInt(arrJson[0]);
            var html = arrJson[1];
            var arrKetJson = arrJson[2];

            divSelect.html(html);

            if (idFirst !== 0)
            {
                arrPaymentInfo = JSON.parse(arrKetJson);
                divInfo.html(arrPaymentInfo[0]);

                showPaymentList(idFirst);
            }
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

changePayment = function ()
{
    var index = $("#divPaymentSelect option:selected").index();
    $("#divPaymentInfo").html(arrPaymentInfo[index]);

    var idAutoTrans = $("#divPaymentSelect option:selected").val();
    showPaymentList(idAutoTrans);
};

refreshList = function ()
{
    var idAutoTrans = $("#divPaymentSelect option:selected").val();
    showPaymentList(idAutoTrans);
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

chPaymentChange = function(no)
{
    var checked = $("#chPayment-" + no).is(":checked");
    var kategori = $("#kategori-" + no).val();
    var color = checked ? "#666" : "#fff";

    $("#bayar-" + no).prop({"background-color" : color, "disabled" : !checked});
    $("#diskon-" + no).prop({"background-color" : color, "disabled" : !checked});
    $("#keterangan-" + no).prop({"background-color" : color, "disabled" : !checked});

    if (kategori === "SKR" || kategori === "CSSKR")
        $("#diskon-" + no).prop({"background-color" : "#666", "disabled" : true});
};

calculatePay = function ()
{
    var ndata = parseInt($("#ndata").val());

    var total = 0;
    var valid = true;
    for(var i = 1; valid && i <= ndata; i++)
    {
        var checked = $("#chPayment-" + i).is(":checked");
        if (!checked) continue;

        valid = validatePayment("#bayar-" + i, "Besar pembayaran") &&
                validatePayment("#diskon-" + i, "Diskon");

        if (valid)
        {
            var bayar = parseInt(rupiahToNumber($("#bayar-" + i).val()));
            var diskon = parseInt(rupiahToNumber($("#diskon-" + i).val()));

            var sisa = parseInt($("#sisa-" + i).val());
            var idBesarJtt = parseInt($("#idbesarjtt-" + i).val());

            if (idBesarJtt !== 0 )
            {
                if (sisa < bayar - diskon)
                {
                    $("#spInfoBayar").html("Jumlah pembayaran harus melebihi sisa pembayaran!");
                    $("#bayar-" + i).focus();
                    valid = false;
                }
                else if (bayar === 0 && diskon === 0)
                {
                    $("#spInfoBayar").html("Jumlah pembayaran dan diskon harus lebih dari nol!");
                    $("#bayar-" + i).focus();
                    valid = false;
                }
                else
                {
                    total += bayar - diskon;
                }
            }
            else
            {
                total += bayar - diskon;
            }
        }
    }

    if (valid)
    {
        $("#spTotalBayar").html(numberToRupiah(total));
        $("#total").val(total);
    }
    else
    {
        $("#spTotalBayar").html("ERROR");
        $("#total").val(-1);
    }
};

validateSubmit = function ()
{
    var idtahunbuku = parseInt($("#idtahunbuku").val());
    if (idtahunbuku === 0)
    {
        alert("Tahun buku untuk departemen terpilih belum ditentukan!");
        return false;
    }

    var total = parseInt($("#total").val());
    if (total === -1)
    {
        alert("Belum dapat melakukan transaksi karena masih ada error!");
        return false;
    }
    else if (total === 0)
    {
        alert("Jumlah pembayaran tidak dapat kosong!");
        return false;
    }
    else if (total < 0)
    {
        alert("Jumlah pembayaran tidak dapat negatif!");
        return false;
    }

    var ndata = parseInt($("#ndata").val());
    var nChecked = 0;
    var valid = true;
    for(var i = 1; valid && i <= ndata; i++)
    {
        var checked = $("#chPayment-" + i).is(":checked");
        if (checked) nChecked += 1;
        if (!checked) continue;

        valid = validatePayment("#bayar-" + i, "Besar pembayaran") &&
                validatePayment("#diskon-" + i, "Diskon");

        if (valid)
        {
            var bayar = parseInt(rupiahToNumber($("#bayar-" + i).val()));
            var diskon = parseInt(rupiahToNumber($("#diskon-" + i).val()));

            var sisa = parseInt($("#sisa-" + i).val());
            var idBesarJtt = parseInt($("#idbesarjtt-" + i).val());

            if (idBesarJtt !== 0 )
            {
                if (sisa < bayar - diskon)
                {
                    $("#spInfoBayar").html("Jumlah pembayaran harus melebihi sisa pembayaran!");
                    $("#bayar-" + i).focus();
                    valid = false;
                }
                else if (bayar === 0 && diskon === 0)
                {
                    $("#spInfoBayar").html("Jumlah pembayaran dan diskon harus lebih dari nol!");
                    $("#bayar-" + i).focus();
                    valid = false;
                }
            }
        }
    }

    if (!valid)
    {
        alert("Belum dapat melakukan transaksi karena masih ada error!");
        return false;
    }

    if (nChecked === 0)
    {
        alert("Tidak ada data pembayaran yang dipilih!");
        return false;
    }

    return confirm("Sudah benar?");
};

showPaymentList = function(idAutoTrans)
{
    var divList = $("#divPaymentList");
    divList.html("memuat ..");

    var kelompok = $("#kelompok").val();
    var noid = $("#noid").val();

    $.ajax({
        url: "autotrans.payment.ajax.php",
        data: "op=getlist&idautotrans=" + idAutoTrans + "&kelompok=" + kelompok + "&noid=" + noid,
        success: function (json)
        {
            var arrJson = JSON.parse(json);

            var num = parseInt(arrJson[0]);
            var data = arrJson[1];

            if (num === 0)
            {
                divList.html(data);
            }
            else
            {
                divList.html(data);
                Tables('tabDaftar', 1, 0);
            }
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

scanBarcode = function (e)
{
    var keycode = (e.keyCode ? e.keyCode : e.which);
    if (keycode != 13)
        return;

    var kode = $.trim($('#txBarcode').val());
    if (kode.length == 0)
        return;

    var departemen = $('#departemen').val();
    if (departemen.length == 0)
        return;

    $('#spScanInfo').html("");

    var qsdata = "kode="+kode+"&departemen="+departemen;
    $.ajax({
        url: "multitrans.barcode.php",
        type: 'GET',
        data: qsdata,
        success: function (response)
        {
            $('#txBarcode').val('');

            var dataCard = $.parseJSON(response);
            if (dataCard.status == "1")
            {
                $("#kelompok").val(dataCard.data);
                $("#noid").val(dataCard.noid);
                $("#nama").val(dataCard.nama);
                $("#kelas").val(dataCard.kelas);

                showPaymentSelect();
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
}
