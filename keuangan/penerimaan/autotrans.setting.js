$(document).ready(function () {
    Tables('table', 1, 0);
});

tambahAutoTrans = function ()
{
    var dept = $("#departemen").val();
    var addr = "autotrans.setting.manage.php?departemen=" + dept + "&idautotrans=0";
    newWindow(addr, 'AutoTransManage','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
};

setAktif = function (no, idAutoTrans)
{
    var aktif = parseInt($("#aktif-" + no).val());
    var newAktif = aktif === 1 ? 0 : 1;
    if (newAktif === 0)
    {
        if (!confirm("Yakin akan menonaktifkan data ini?"))
            return;
    }

    $.ajax({
        url: "autotrans.setting.ajax.php",
        data: "op=setaktif&idautotrans=" + idAutoTrans + "&newaktif=" + newAktif,
        success: function (html)
        {
            var src = newAktif === 1 ? '../images/ico/aktif.png' : '../images/ico/nonaktif.png';
            $("#img-" + no).attr('src', src);
            $("#aktif-" + no).val(newAktif);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

edit = function (idAutoTrans)
{
    var dept = $("#departemen").val();
    var addr = "autotrans.setting.manage.php?departemen=" + dept + "&idautotrans=" + idAutoTrans;
    newWindow(addr, 'AutoTransManage','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
};

hapus = function (idAutoTrans)
{
    if (!confirm("Yakin akan menghapus data ini?"))
        return;

    $.ajax({
        url: "autotrans.setting.ajax.php",
        data: "op=hapus&idautotrans=" + idAutoTrans,
        success: function (html)
        {
            refreshPage();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

refreshPage = function ()
{
    var dept = $("#departemen").val();

    $("#divDaftar").html("memuat ..");
    $.ajax({
        url: "autotrans.setting.ajax.php",
        data: "op=getdaftar&dept=" + dept,
        success: function(html)
        {
            $("#divDaftar").html(html);
            Tables('table', 1, 0);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};