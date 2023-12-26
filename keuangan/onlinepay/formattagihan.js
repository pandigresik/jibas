simpanNomorTagihan = function ()
{
    var lsAwalan = [];
    var nDept = $("#ndept").val();
    for(var i = 1; i <= nDept; i++)
    {
        var dept = $("#ntdept" + i).val();
        var awalan = $.trim($("#awalan" + i).val());

        if (awalan.length === 0)
        {
            $("#awalan" + i).focus();
            alert("Isi kode awalan " + dept);
            return;
        }
        else if (awalan.length > 2)
        {
            $("#awalan" + i).focus();
            alert("kode awalan " + dept + " maksimal 2 karakter!");
            return;
        }
        else if (isNaN(awalan))
        {
            $("#awalan" + i).focus();
            alert("kode awalan " + dept + " harus angka");
            return;
        }
        else if (lsAwalan.includes(awalan))
        {
            $("#awalan" + i).focus();
            alert("kode awalan " + dept + " sudah digunakan");
            return;
        }

        lsAwalan.push(awalan);
    }

    var request = new RequestFactory();
    request.add("op", "938749237490236542");
    request.add("ndept", nDept);
    for(i = 1; i <= nDept; i++)
    {
        dept = $("#ntdept" + i).val();
        awalan = $.trim($("#awalan" + i).val());

        request.add("dept" + i, dept);
        request.add("awalan" + i, awalan);
    }
    var qs = request.createQs();

    $.ajax({
        url: "formattagihan.ajax.php",
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

            $("#statusawalan").html("tersimpan");

            setTimeout(function () {
                $("#statusawalan").html("");
            }, 1500);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};