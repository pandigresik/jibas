change_kategori = function ()
{
    var dept = $("#departemen").val();
    var idkategori = $("#idkategori").val();

    $("#divPenerimaan").html("memuat..");

    $.ajax({
        url: "inputbayar.ajax.php",
        data: "op=getpenerimaan&departemen=" + dept + "&idkategori=" + idkategori,
        success: function (html)
        {
            $("#divPenerimaan").html(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });

    if (idkategori === "JTT")
    {
        $("#lbTingkat").html("Tingkat");
        $("#lbKelas").html("Kelas");
    }
    else if (idkategori === "CSWJB")
    {
        $("#lbTingkat").html("Proses Penerimaan");
        $("#lbKelas").html("Kelompok");
    }

    $("#divTingkat").html("memuat..");
    $("#divKelas").html("memuat..");

    $.ajax({
        url: "inputbayar.ajax.php",
        data: "op=gettingkat&departemen=" + dept + "&idkategori=" + idkategori,
        success: function (html)
        {
            $("#divTingkat").html(html);

            if ($("#divTingkat option").size() == 0)
                return;

            var idtingkat = $("#divTingkat option:selected").val()
            $.ajax({
                url: "inputbayar.ajax.php",
                data: "op=getkelas&departemen=" + dept + "&idtingkat=" + idtingkat + "&idkategori=" + idkategori,
                success: function (html)
                {
                    $("#divKelas").html(html);
                },
                error: function (xhr)
                {
                    alert(xhr.responseText);
                }
            })
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

change_tingkat = function()
{
    var dept = $("#departemen").val();
    var idtingkat = $("#tingkat").val();
    var idkategori = $("#idkategori").val();

    $("#divKelas").html("memuat..");

    $.ajax({
        url: "inputbayar.ajax.php",
        data: "op=getkelas&departemen=" + dept + "&idtingkat=" + idtingkat + "&idkategori=" + idkategori,
        success: function (html)
        {
            $("#divKelas").html(html);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

simpan = function ()
{
    if ($("#penerimaan option").length === 0)
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
    else if (besar <= 0)
    {
        alert("Besar pembayaran harus positif!");
        $("#besar").focus();
        return;
    }
    else if (isNaN(besar))
    {
        alert("Besar pembayaran harus bilangan!");
        $("#besar").focus();
        return;
    }

    var cicilan = rupiahToNumber($("#cicilan").val());
    if ($.trim(cicilan).length === 0)
    {
        alert("Cicilan pembayaran belum ditentukan!");
        $("#cicilan").focus();
        return;
    }
    else if (cicilan <= 0)
    {
        alert("Cicilan pembayaran harus positif!");
        $("#cicilan").focus();
        return;
    }
    else if (isNaN(cicilan))
    {
        alert("Cicilan pembayaran harus bilangan!");
        $("#cicilan").focus();
        return;
    }

    if ($("#tingkat option").length === 0)
    {
        alert("Tidak ditemukan data tingkat/penerimaan!");
        return;
    }

    if ($("#nkelas").length === 0)
    {
        alert("Tidak ditemukan data kelas/kelompok!");
        return;
    }

    var nchecked = 0;
    var idkelas = "";
    var nkelas = $("#nkelas").val();
    for(var i = 1; i <= nkelas; i++)
    {
        if ($('#ch' + i).is(":checked"))
        {
            nchecked += 1;

            if (idkelas !== "") idkelas += ",";
            idkelas += $("#id" + i).val();
        }
    }

    if (nchecked === 0)
    {
        alert("Data kelas/kelompok belum dipilih!");
        return;
    }

    if (!confirm("Data sudah benar?"))
        return;

    var dept = $("#departemen").val();
    var idkategori = $("#idkategori").val();
    var idpenerimaan = $("#penerimaan").val();
    var idtingkat = $("#tingkat").val();
    var cicilanpertama = $("#cicilanpertama").is(":checked") ? 1 : 0;

    var data = "op=setbayar&departemen=" + dept;
    data += "&idkategori=" + idkategori;
    data += "&idpenerimaan=" + idpenerimaan;
    data += "&idtingkat=" + idtingkat;
    data += "&idkelas=" + idkelas;
    data += "&besar=" + besar;
    data += "&cicilan=" + cicilan;
    data += "&cicilanpertama=" + cicilanpertama;

    $.ajax({
        url: "inputbayar.ajax.php",
        data: data,
        success: function (html)
        {
            $("#besar").val("");
            $("#cicilan").val("");
            $("#cicilanpertama").prop("checked", false);
            for(var i = 1; i <= nkelas; i++)
            {
                $('#ch' + i).prop("checked", false);
            }

            alert(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });

};


























