changeKategori = function ()
{
    var departemen = $("#departemen").val();
    var idKategori = $("#idKategori").val();

    $("#spPenerimaan").html("memuat ..");

    $.ajax({
        url: "autotrans.setting.penerimaan.ajax.php",
        data: "op=getpenerimaan&idkategori=" + idKategori + "&departemen=" + departemen,
        success: function (html)
        {
            $("#spPenerimaan").html(html);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

simpanPenerimaan = function ()
{
    if ($("#idPenerimaan option").length === 0)
    {
        alert("Belum ada data penerimaan");
        return;
    }

    var besar = rupiahToNumber($("#besar").val());
    if ($.trim(besar).length === 0)
    {
        alert("Besar pembayaran belum ditentukan!");
        $("#besar").focus();
        return;
    }
    else if (isNaN(besar))
    {
        alert("Besar pembayaran harus bilangan!");
        $("#besar").focus();
        return;
    }
    else if (besar <= 0)
    {
        alert("Besar pembayaran harus positif!");
        $("#besar").focus();
        return;
    }

    var urutan = $("#urutan").val();
    if (isNaN(urutan))
    {
        alert("Urutan harus bilangan!");
        $("#urutan").focus();
        return;
    }
    else if (urutan <= 0)
    {
        alert("Urutan harus positif!");
        $("#urutan").focus();
        return;
    }

    var idPenerimaan = $("#idPenerimaan").val();
    var penerimaan = $("#idPenerimaan option:selected").text();
    var keterangan = $.trim($("#keterangan").val());

    window.close();
    window.opener.acceptPenerimaan(idPenerimaan, penerimaan, besar, urutan, keterangan);
};