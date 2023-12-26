var lsPenerimaan = [];

$(document).ready(function () {
    Tables("tabDaftar", 1, 0);
});

tambahPenerimaan = function ()
{
    var dept = $("#departemen").val();
    var kelompok = $("#kelompok").val();
    var addr = "autotrans.setting.penerimaan.php?departemen=" + dept + "&idautotrans=0&kelompok=" + kelompok;

    newWindow(addr, 'AutoTransPenerimaan','550','350','resizable=1,scrollbars=1,status=0,toolbar=0');
};

tutup = function () {
    window.close();
};

acceptPenerimaan = function(idPenerimaan, penerimaan, besar, urutan, keterangan)
{
    var lsLen = lsPenerimaan.length;

    var found = false;
    for(var i = 0; !found && i < lsLen; i++)
    {
        var data = lsPenerimaan[i];
        if (data.Hapus === 1)
            continue;

        found = data.IdPenerimaan === idPenerimaan;
    }

    if (found)
    {
        alert("Penerimaan " + penerimaan + " sudah dimasukan!");
        return;
    }

    var info = {};
    info.IdData = 0;
    info.IdPenerimaan = idPenerimaan;
    info.Aktif = 1;
    info.Hapus = 0;
    info.Besar = besar;
    info.Urutan = urutan;
    info.Keterangan = keterangan;
    lsPenerimaan.push(info);
    lsLen += 1;

    for(i = 0; i < lsLen; i++)
    {
        data = lsPenerimaan[i];
    }

    var ix = lsLen - 1;
    var table = "<tr id='tabDaftarRow-" + ix + "' style='height: 25px'>";
    table += "<td align='center'>" + lsLen + "</td>";
    table += "<td align='left'>" + penerimaan + "</td>";
    table += "<td align='right'>" + numberToRupiah(besar) + "</td>";
    table += "<td align='center'><a onclick='setAktif(" + ix + ")' style='cursor: pointer'><img id='imgAktif-" + ix + "' src='../images/ico/aktif.png' title='aktif'></a></td>";
    table += "<td align='center'>" + urutan + "</td>";
    table += "<td align='left'>" + keterangan + "</td>";
    table += "<td align='center'><a onclick='hapusData(" + ix + ")' style='cursor: pointer'><img src='../images/ico/hapus.png' title='hapus'></a></td>";
    table += "</tr>";

    $("#tabDaftar tr:last").after(table);

    var json = JSON.stringify(lsPenerimaan);

    $("#lsPenerimaan").val(json);

    Tables("tabDaftar", 1, 0);
};

hapusData = function (ix)
{
    if (!confirm("Yakin akan menghapus data ini?"))
        return;

    var info = lsPenerimaan[ix];
    info.Hapus = 1;

    $("#tabDaftarRow-" + ix).remove();
};

setAktif = function(ix)
{
    var info = lsPenerimaan[ix];
    var newAktif = info.Aktif === 1 ? 0 : 1;

    if (newAktif === 0)
    {
        if (!confirm("Apakah akan menonaktifkan data ini?"))
            return;
    }

    info.Aktif = newAktif;

    var src = newAktif === 0 ? '../images/ico/nonaktif.png' : '../images/ico/aktif.png';
    $("#imgAktif-" + ix).attr('src', src);
};

saveForm = function ()
{
    var judul = $.trim($("#judul").val());
    if (judul.length === 0)
    {
        alert("Judul belum diinput!");
        $("#judul").focus();
        return false;
    }

    var urutan = $("#urutan").val();
    if (isNaN(urutan))
    {
        alert("Urutan harus bilangan!");
        $("#urutan").focus();
        return false;
    }
    else if (urutan <= 0)
    {
        alert("Urutan harus positif!");
        $("#urutan").focus();
        return false;
    }

    if (lsPenerimaan.length === 0)
    {
        alert("Daftar penerimaan belum ditentukan!");
        return false;
    }

    var json = JSON.stringify(lsPenerimaan);
    $("#lsPenerimaan").val(json);

    return true;
};