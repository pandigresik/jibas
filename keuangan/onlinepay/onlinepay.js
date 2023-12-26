$(document).ready(function ()
{
    $.ajax({
        url: "onlinepay.ajax.php",
        method: "POST",
        data: "op=874562834763284",
        success: function (result) {

        },
        error: function (xhr) {

        }
    })
});

checkAllConfigReady = function (refParam)
{
    $.ajax({
        url: "onlinepay.ajax.php",
        method: "POST",
        data: "op=787458343894734",
        success: function (jsonResult)
        {
            //console.log(jsonResult);
            var lsResult = $.parseJSON(jsonResult);
            if (parseInt(lsResult[0]) !== 1)
            {
                alert(lsResult[1]);
                return;
            }

            var refNo = parseInt(refParam);
            switch (refNo)
            {
                case 1:
                    document.location.href = "tagihan.php"; break;
                case 2:
                    document.location.href = "tagihansiswa.php"; break;
                case 3:
                    document.location.href = "daftartagihan.php"; break;
                case 4:
                    document.location.href = "caritagihan.php"; break;
                case 5:
                    document.location.href = "riwayattrans.php"; break;
                case 6:
                    document.location.href = "saldobank.php"; break;
                case 7:
                    document.location.href = "mutasibank.php"; break;
                case 8:
                    document.location.href = "lebihtrans.php"; break;
                case 9:
                    document.location.href = "statistik.php"; break;
                default:
                    document.location.href = "onlinepay.php";
            }
        },
        error: function (xhr) {

        }
    })
};
