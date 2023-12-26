$(document).ready(function() {
    Tables('table');
});

function changeBagian()
{
    var bagian = $("#bagian").val();
    $.ajax({
        url: "tabungan.trans.pegawai.ajax.php",
        data: "op=getpegawai&bagian=" + bagian,
        success: function(response)
        {
            $("#divPegawai").html(response);
            Tables('table');
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
}
