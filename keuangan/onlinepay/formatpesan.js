simpanPesanTagihan = function ()
{
    var nDept = $("#ndept").val();
    for(var i = 1; i <= nDept; i++)
    {
        var dept = $("#npdept" + i).val();
        var pesan = $.trim($("#pesan" + i).val());

        if (pesan.length < 25)
        {
            $("#pesan" + i).focus();
            alert("Pesan tagihan " + dept + " minimal 25 karakter!");
            return;
        }
    }

    var request = new RequestFactory();
    request.add("op", "246792384762398746");
    request.add("ndept", nDept);
    for(i = 1; i <= nDept; i++)
    {
        dept = $("#npdept" + i).val();
        pesan = $.trim($("#pesan" + i).val());

        request.add("dept" + i, dept);
        request.add("pesan" + i, pesan);
    }

    var qs = request.createQs();

    $.ajax({
        url: "formatpesan.ajax.php",
        method: "POST",
        data: qs,
        success: function (json)
        {
            //console.log(json);

            var result = $.parseJSON(json);
            if (parseInt(result[0]) < 0)
            {
                alert(result[1]);
                return;
            }

            $("#statuspesan").html("tersimpan");

            setTimeout(function () {
                $("#statuspesan").html("");
            }, 1500);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })

};

simpanPesanPembayaran = function ()
{
    var nDept = $("#ndept").val();
    for(var i = 1; i <= nDept; i++)
    {
        var dept = $("#npdeptb" + i).val();
        var pesan = $.trim($("#pesanb" + i).val());

        if (pesan.length < 25)
        {
            $("#pesanb" + i).focus();
            alert("Pesan pembayaran " + dept + " minimal 25 karakter!");
            return;
        }
    }

    var request = new RequestFactory();
    request.add("op", "4242452453414");
    request.add("ndept", nDept);
    for(i = 1; i <= nDept; i++)
    {
        dept = $("#npdeptb" + i).val();
        pesan = $.trim($("#pesanb" + i).val());

        request.add("dept" + i, dept);
        request.add("pesan" + i, pesan);
    }

    var qs = request.createQs();

    $.ajax({
        url: "formatpesan.ajax.php",
        method: "POST",
        data: qs,
        success: function (json)
        {
            //console.log(json);

            var result = $.parseJSON(json);
            if (parseInt(result[0]) < 0)
            {
                alert(result[1]);
                return;
            }

            $("#statuspesanb").html("tersimpan");

            setTimeout(function () {
                $("#statuspesanb").html("");
            }, 1500);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })

};