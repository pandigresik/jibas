var blankMessage = "<br><span style='font-size: 14px; color: #999; font-weight: bold;'>Klik tombol \"Lihat Transaksi\" di panel kiri untuk menampilkan Riwayat Transaksi</span>";
var colMenuOpen = 1;


showDatePicker1 = function ()
{
    var selDate = $("#dttanggal1").val();
    $("#tanggal1").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date) {
            $("#dttanggal1").val(date);
            $("#tanggal1").val(dateutil_formatInaDate(date));
            clearContent();
        }
    }).focus();
};

showDatePicker2 = function ()
{
    var selDate = $("#dttanggal2").val();
    $("#tanggal2").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date) {
            $("#dttanggal2").val(date);
            $("#tanggal2").val(dateutil_formatInaDate(date));
            clearContent();
        }
    }).focus();
};

clearContent = function ()
{
    $("#dvContent").html(blankMessage);
};

changeDept = function ()
{
    $("#pembayaran").val("ALL").change();
    $("#siswa").val("ALL").change();
};

changePembayaran = function()
{
    var departemen = $("#departemen").val();
    var pembayaran = $("#pembayaran").val();

    var trJenisData = $("#trJenisData");
    if (pembayaran === "ALL")
    {
        trJenisData.css({"visibility": "hidden",  "display" : "none"});
    }
    else if (pembayaran === "JTT")
    {
        $("#namaJenis").html("Iuran Wajib");
        trJenisData.css({"visibility": "visible", "display" : "table-row"});

        var qs = "op=893478934732";
        qs += "&departemen=" + encodeURIComponent(departemen);

        var dvJenis = $("#dvJenis");
        dvJenis.html("memuat ..");

        $.ajax({
            url: "riwayattrans.ajax.php",
            method: "POST",
            data: qs,
            success: function (result) {
                dvJenis.html(result);
            },
            error: function (xhr) {
                alert(xhr.responseText);
            }
        })
    }
    else if (pembayaran === "SKR")
    {
        $("#namaJenis").html("Iuran Sukarela");
        trJenisData.css({"visibility": "visible", "display" : "table-row"});

        var qs = "op=987589345789345";
        qs += "&departemen=" + encodeURIComponent(departemen);

        var dvJenis = $("#dvJenis");
        dvJenis.html("memuat ..");

        $.ajax({
            url: "riwayattrans.ajax.php",
            method: "POST",
            data: qs,
            success: function (result) {
                dvJenis.html(result);
            },
            error: function (xhr) {
                alert(xhr.responseText);
            }
        })
    }
    else if (pembayaran === "SISTAB")
    {
        $("#namaJenis").html("Tabungan Siswa");
        trJenisData.css({"visibility": "visible", "display" : "table-row"});

        var qs = "op=437682489324234";
        qs += "&departemen=" + encodeURIComponent(departemen);

        var dvJenis = $("#dvJenis");
        dvJenis.html("memuat ..");

        $.ajax({
            url: "riwayattrans.ajax.php",
            method: "POST",
            data: qs,
            success: function (result) {
                dvJenis.html(result);
            },
            error: function (xhr) {
                alert(xhr.responseText);
            }
        })
    }
};

changeSiswa = function ()
{
    var siswa = $("#siswa").val();

    var trPilihSiswa = $("#trPilihSiswa");
    if (siswa === "ALL")
    {
        trPilihSiswa.css({"visibility": "hidden",  "display" : "none"});
    }
    else
    {
        trPilihSiswa.css({"visibility": "visible", "display" : "table-row"});
    }
};

SearchUser = function()
{
    var addr = "carisiswa.php?selectdept=1";
    newWindow(addr, 'CariSiswa', '550', '590', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

AcceptSearch = function(data, noid, nama, kelas)
{
    $("#kelompok").val(data);
    $("#noid").val(noid);
    $("#nama").val(nama);
    $("#kelas").val(kelas);

    clearContent();
};

getDepartemenText = function ()
{
    return $("#departemen option:selected").text();
};

getTanggal = function ()
{
    return $("#tanggal1").val() + " s/d " + $("#tanggal2").val();
};

getMetodeText = function ()
{
    return $("#metode option:selected").text();
};

getPembayaranVal = function ()
{
    return $("#pembayaran").val();
};

getIdPembayaranText = function ()
{
    if ($("#idpembayaran").length)
        return $("#idpembayaran option:selected").text();

    return "-";
};

getSiswaVal = function ()
{
    return $("#siswa").val();
};

getSiswaText = function ()
{
    return $("#siswa option:selected").text();
};

getNamaSiswa = function ()
{
    return $("#nama").val() + " (" + $("#noid").val() + ")";
};

getBank = function ()
{
    return $("#bankno option:selected").text();
};

getPetugas = function ()
{
    return $("#idpetugas option:selected").text();
};

getIdPembayaranVal = function ()
{
    if ($("#idpembayaran").length)
        return $("#idpembayaran").val();

    return "0";
};

getPembayaranText = function ()
{
    return $("#pembayaran option:selected").text();
};



getRiwayatTableContent = function ()
{
    if ($("#dvTableContent").length)
        return $("#dvTableContent").html();

    return "-";
};

getRekapTableContent = function ()
{
    if ($("#dvTableContent").length)
        return $("#dvTableContent").html();

    return "-";
};

showTrans = function ()
{
    var siswa = $("#siswa").val();
    var nis = "";
    if (siswa === "PILIH")
    {
        nis = $("#noid").val();
        if (nis.length === 0)
        {
            alert("Siswa belum dipilih!");
            return;
        }

        nis = encodeURIComponent(nis);
    }

    var pembayaran = $("#pembayaran").val();

    var qs = "op=989834789234";
    qs += "&departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&tanggal1=" + encodeURIComponent($("#dttanggal1").val());
    qs += "&tanggal2=" + encodeURIComponent($("#dttanggal2").val());
    qs += "&metode=" + $("#metode").val();
    qs += "&pembayaran=" + pembayaran;
    if (pembayaran === "ALL")
        qs += "&idpembayaran=0";
    else
        qs += "&idpembayaran=" + $("#idpembayaran").val();
    qs += "&siswa=" + siswa;
    if (siswa === "ALL")
        qs += "&nis=";
    else
        qs += "&nis=" + nis;
    qs += "&bankno=" + encodeURIComponent($("#bankno").val());
    qs += "&idpetugas=" + encodeURIComponent($("#idpetugas").val());
    qs += "&laporan=" + encodeURIComponent($("#laporan").val());
    qs += "&page=1";

    var dvContent = $("#dvContent");
    dvContent.html("memuat ..");

    $.ajax({
        url: "riwayattrans.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvContent.html(result);

            if ($("#tabReport").length)
                Tables('tabReport', 0, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

changeReportPage = function ()
{
    var report = $("#report").val();
    var url = report === "RIWAYAT" ? "riwayattrans.riwayat.php" : "riwayattrans.rekap.php";
    var stIdPgTrans = $("#stidpgtrans").val();
    var nData = $("#ndata").val();
    var page = $("#page").val();

    var qs = "op=83473984643984";
    qs += "&stidpgtrans=" + encodeURIComponent(stIdPgTrans);
    qs += "&ndata=" + encodeURIComponent(nData);
    qs += "&page=" + page;

    var dvContent = $("#dvContent");
    dvContent.html("memuat ..");

    $.ajax({
        url: url,
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvContent.html(result);

            if ($("#tabReport").length)
                Tables('tabReport', 0, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

showRekapDetail = function (stIdPgTrans, kategori, idPenerimaan, penerimaan, tanggal)
{
    //console.log(stIdPgTrans);
    //console.log(kategori);
    //console.log(idPenerimaan);
    //console.log(tanggal);

    var qs = "stidpgtrans=" + encodeURIComponent(stIdPgTrans);
    qs += "&kategori=" + encodeURIComponent(kategori);
    qs += "&idpenerimaan=" + encodeURIComponent(idPenerimaan);
    qs += "&penerimaan=" + encodeURIComponent(penerimaan);
    qs += "&tanggal=" + encodeURIComponent(tanggal);
    qs += "&page=1";

    var addr = "riwayattrans.rekap.detail.data.php?" + qs;
    newWindow(addr, 'RiwayatTrans', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

showRekapDetail2 = function (stIdPgTrans, jsonPen, jsonTgl)
{
    //console.log(stIdPgTrans);
    //console.log(jsonPen);
    //console.log(jsonTgl);

    var qs = "stidpgtrans=" + encodeURIComponent(stIdPgTrans);
    qs += "&jsonpen=" + encodeURIComponent(jsonPen);
    qs += "&jsontgl=" + encodeURIComponent(jsonTgl);
    qs += "&page=1";

    var addr = "riwayattrans.rekap.detail.php?" + qs;
    newWindow(addr, 'RiwayatTrans', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

cetakRiwayat = function()
{
    var departemen = $("#departemen").val();

    var addr = "riwayattrans.riwayat.cetak.php?departemen=" + departemen;
    newWindow(addr, 'RiwayatTransCetak', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelRiwayat = function ()
{
    var stIdPgTrans = $("#stidpgtrans").val();

    var addr = "riwayattrans.riwayat.excel.php?stidpgtrans=" + encodeURIComponent(stIdPgTrans);
    newWindow(addr, 'RiwayatTransExcel', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};


cetakRekap = function()
{
    var departemen = $("#departemen").val();

    var addr = "riwayattrans.rekap.cetak.php?departemen=" + departemen;
    newWindow(addr, 'RekapTransCetak', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelRekap = function ()
{
    var departemen = $("#departemen").val();
    var stIdPgTrans = $("#stidpgtrans").val();
    var jsonPen = $("#jsonpen").val();
    var jsonTgl = $("#jsontgl").val();

    var qs = "stidpgtrans=" + encodeURIComponent(stIdPgTrans);
    qs += "&jsonpen=" + encodeURIComponent(jsonPen);
    qs += "&jsontgl=" + encodeURIComponent(jsonTgl);
    qs += "&departemen=" + encodeURIComponent(departemen);

    var addr = "riwayattrans.rekap.excel.php?" + qs;
    newWindow(addr, 'RekapTransExcel', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

showRincianJurnal = function (no)
{
    var el = "#stnojurnal-" + no;
    var stNoJurnal = $(el).val();

    var addr = "rincianjurnal.php?stnojurnal=" + encodeURIComponent(stNoJurnal);
    newWindow(addr, 'RincianJurnal' + no, '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

changeColMenu = function()
{
    //console.log(colMenuOpen);

    if (colMenuOpen === 1)
    {
        $("#btcolmenu").val("   >   ");
        $("#dvcolmenu").css("width", "50px");
        colMenuOpen = 0;
    }
    else
    {
        $("#btcolmenu").val("   <   ");
        $("#dvcolmenu").css("width", "370px");
        colMenuOpen = 1;
    }
};