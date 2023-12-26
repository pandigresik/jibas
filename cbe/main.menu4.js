main_showHome = function ()
{
    $("#divContent").html("memuat..").load("welcome.php");

    welcome_getCurrentJadwal();
};

main_showUjianKhusus = function()
{
    $("#divContent").html("memuat..").load("ujiankhusus.php");

    setTimeout(uks_initUjianKhusus, 600);
};

main_showJadwalUjian = function()
{
    $("#divContent").html("memuat..").load("jadwal.php");

    setTimeout(jadwal_getRuangan, 600);
};

main_showRekapUjian = function()
{
    $("#divContent").html("memuat..").load("rekap.php");

    setTimeout(rekap_getPelajaran, 600);
};

main_showUjianUmum = function()
{
    var userTupe = $("#menu_userTupe").val();

    if (userTupe === "siswa")
    {
        $("#divContent").html("memuat..").load("ujianumumsiswa.php");

        setTimeout(ums_getPilihanUjian, 200);
    }
    else
    {
        $("#divContent").html("memuat..").load("ujianumum.php");

        setTimeout(um_getPilihanUjian, 200);
    }
};

main_showUjianRemedial = function()
{
    $("#divContent").html("memuat..").load("ujianremed.php");

    setTimeout(ur_getPilihanPelajaran, 200);
};

main_showBankSoal = function()
{
    $("#divContent").html("memuat..").load("banksoal.php");

    bs_getPilihanDept();
};

main_showCariSoal = function()
{
    $("#divContent").html("memuat..").load("carisoal.php");
};

main_showDaftarPeserta = function()
{
    $("#divContent").html("memuat..").load("userlist.php");

    setTimeout(ul_getUserList, 200);
    setTimeout(ul_resizeContainer, 200);
    setTimeout(ul_autoRefresh, 60000);
};

main_showPesertaUjianOffline = function ()
{
    $("#divContent").html("memuat..").load("useroffline.php");

    setTimeout(of_getUserOfflineList, 200);
    setTimeout(of_resizeContainer, 200);
    setTimeout(of_autoRefresh, 60000);
};

main_showResUtil = function()
{
    $("#divContent").html("memuat..").load("resutil.php");

    setTimeout(ru_showResDir, 200);
    setTimeout(ru_resizeContainer, 200);

};

main_showTestConn = function()
{
    $("#divContent").html("memuat..").load("testconn.php");
};