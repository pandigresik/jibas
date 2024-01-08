<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 29.0 (Sept 20, 2023)
 * @notes: JIBAS Education Community will be managed by Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 * 
 * Copyright (C) 2009 Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');

$nis = SI_USER_ID();

OpenDb();
$sql = "SELECT nama FROM siswa WHERE nis = '".$nis."'";
$result=QueryDb($sql);
$row_siswa = mysqli_fetch_array($result); 

CloseDb();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS InfoSiswa</title>
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<link rel="stylesheet" type="text/css" href="../script/tooltips.css" />
<script src="../script/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tooltips.js"></script>
<link rel="stylesheet" type="text/css" href="../style/calendar-win2k-1.css">
<style type="text/css">
<!--
.style2 {
	font-family: Calibri;
	font-size: 16px;
	font-weight: bold;
	color: #FFCC00;
}
.style3 {font-size: 24px}
.style4 {
	font-family: Calibri;
	font-size: 24px;
	color: #006633;
}
-->
</style>
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF">
<div id="waitBox" style="position:absolute; visibility:hidden;">
	<img src="../images/ico/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>
<table width="100%">
    <tr>	
        <td width="0" style="background-color:#FFFFFF">
        <!-- CONTENT GOES HERE //--->	
            <table border="0" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" width="100%" >
              <tr>
                  <td width="40%" align="right" ><span class="style2 style3" style="background-color:#999999">&nbsp;<?=$nis?>&nbsp;</span>&nbsp;                    </td>
                <td valign="middle" >&nbsp;<span class="style4"><?=$row_siswa['nama']?></span>                  </td>
              </tr>
                <tr height="500">
                    <td width="100%" bgcolor="#FFFFFF" valign="top" colspan="2">
                        <br />
                        <div id="TabbedPanels1" class="TabbedPanels">
                            <ul class="TabbedPanelsTabGroup">
                                <li class="TabbedPanelsTab">Data&nbsp;Pribadi</li>
								<li class="TabbedPanelsTab">Keuangan</li>
                                <li class="TabbedPanelsTab">Presensi&nbsp;Harian</li>
                                <li class="TabbedPanelsTab">Presensi&nbsp;Pelajaran</li>
								<li class="TabbedPanelsTab">Presensi&nbsp;Kegiatan</li>
                                <li class="TabbedPanelsTab">Nilai</li>
                                <li class="TabbedPanelsTab">Rapor</li>
                                <li class="TabbedPanelsTab">Perpustakaan</li>
                            </ul>
                            <div class="TabbedPanelsContentGroup">
                                <div class="TabbedPanelsContent" id="datapribadi"></div>
								<div class="TabbedPanelsContent" id="keuangan"></div>
                                <div class="TabbedPanelsContent" id="ph"></div>
                                <div class="TabbedPanelsContent" id="pp"></div>
								<div class="TabbedPanelsContent" id="pk"></div>
                                <div class="TabbedPanelsContent" id="nilai"></div>
                                <div class="TabbedPanelsContent" id="rapor"></div>
                                <div class="TabbedPanelsContent" id="perpus"></div>
                            </div>
                        </div>
                   </td>
                </tr>
            </table>
         <!-- END OF CONTENT //--->
        </td>
    </tr>
</table>
   
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
sendRequestText("keuangan.php", showKeu, "nis=<?=$nis?>");
sendRequestText("data_pribadi.php", showDP, "nis=<?=$nis?>");
sendRequestText("presensi_harian.php", showPH, "nis_awal=<?=$nis?>");
sendRequestText("presensi_pelajaran.php", showPP, "nis_awal=<?=$nis?>");
sendRequestText("infosiswa.presensikegiatan.php", showPK, "nis=<?=$nis?>");
sendRequestText("nilai.php", showNIL, "nis_awal=<?=$nis?>");
sendRequestText("rapor.php", showRAP, "nis_awal=<?=$nis?>");
sendRequestText("perpustakaan.php", showPerpus, "nis=<?=$nis?>");

function showKeu(x) {
    document.getElementById('keuangan').innerHTML = x;
}

function showDP(x) {
    document.getElementById('datapribadi').innerHTML = x;
}

function showPH(x) {
    document.getElementById('ph').innerHTML = x;
}

function showPP(x) {
    document.getElementById('pp').innerHTML = x;
}


function showPK(x) {
    document.getElementById('pk').innerHTML = x;
}


function showNIL(x) {
    document.getElementById('nilai').innerHTML = x;
    var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
}

function showRAP(x){
    document.getElementById('rapor').innerHTML = x;
}

function showPerpus(x){
    document.getElementById('perpus').innerHTML = x;
}

function chg_tab_sem(nis,replid,pelajaran,kelas,i,nmsem,num){
    //alert ('Masoooookkkkkkk_'+i);
    document.getElementById('active_tab').value = i;
    show_wait('sem'+i);
    sendRequestText("get_nil_sem.php", showInfoTabSemester, "nis="+nis+"&semester="+replid+"&pelajaran="+pelajaran+"&kelas="+kelas+"&namasemester="+nmsem);
    //sendRequestText('get_nil_sem.php',showInfoTabSemester,'nis_awal='+nis);
}
function showInfoTabSemester(x){
    var active_tab = document.getElementById('active_tab').value;
    document.getElementById('sem'+active_tab).innerHTML = x;
}
function show_panel(x) {
    document.getElementById("panel").innerHTML = x;
}

function show_content(x) {
    document.getElementById("ajaxcontentarea").innerHTML = x;
}

function show_pp(x) {
    document.getElementById("content_pp").innerHTML = x;
}
function show_content2(x) {
    document.getElementById("ajaxcontentarea2").innerHTML = x;
}
function show_ck(bulan,tahun) {
    show_wait('contentcatatan');
    sendRequestText("get_catatansiswa.php", show_catatan, "nis=<?=$nis?>&bulan="+bulan+"&tahun="+tahun);
}
function chg_thn_ck(nis) {
    var tahun=document.getElementById("tahun").value;
    show_wait('tabel_ck');
    sendRequestText("get_bln_ck.php", show_bln_ck, "tahun="+tahun+"&nis="+nis);
    sendRequestText("get_blank.php", show_catatan, "");
}
function chg_pel_pp(nis) {
    var pelajaran=document.getElementById("pel").value;
    show_wait('content_pp');
    sendRequestText("get_pp_siswa.php", show_pp, "pelajaran="+pelajaran+"&nis="+nis);
}
function show_catatan(x) {
    document.getElementById("contentcatatan").innerHTML = x;
}
function show_bln_ck(x) {
    document.getElementById("tabel_ck").innerHTML = x;
}

function show_tahun(x) {
    document.getElementById("thn_catatan").innerHTML = x;
}
function view_detail_pres(bulan,status) {
    var departemen=document.getElementById("departemen").value;
    var tahunajaran=document.getElementById("tahunajaran").value;
    var k=document.panel2.kelas.value;
    newWindow('detail_pp.php?nis_awal=<?=$nis?>&bulan='+bulan+'&status='+status+'&kelas='+k+'&departemen='+departemen+'&tahunajaran='+tahunajaran,'DetilPP',583,321,'resizable=0,scrollbars=1,status=0,toolbar=0');
}
function show_ph(x) {
    document.getElementById("contentph").innerHTML = x;
}

function tab_sem(nis,sem,pel,kls) {
    show_wait('ajaxcontentarea');
    sendRequestText("semester.php", show_content, "nis="+nis+"&semester="+sem+"&pelajaran="+pel+"&kelas="+kls);
}
function show_ph_siswa() {
    var tglawal=document.getElementById("tglawal").value;
    var tglakhir=document.getElementById("tglakhir").value;
    if (tglakhir!="" && tglawal!=""){
        show_wait('contentph');
        sendRequestText("get_ph_siswa.php", show_ph, "nis=<?=$nis?>&tglawal="+tglawal+"&tglakhir="+tglakhir);
    } else {
        alert ('Periode awal dan periode akhir harus diisi!');
    }
}
function cetak_pp(nis,pelajaran) {
    newWindow('cat_pp_cetak.php?nis='+nis+'&pelajaran='+pelajaran, 'CetakCatatanPPSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak_ph(nis,tglawal,tglakhir) {
    newWindow('cat_ph_cetak.php?nis='+nis+'&tglawal='+tglawal+'&tglakhir='+tglakhir, 'CetakCatatanPHSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak_ck(nis,bulan,tahun) {
    newWindow('cat_ck_cetak.php?nis='+nis+'&bulan='+bulan+'&tahun='+tahun, 'CetakCKSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function cetak(semester,panel) {
    if (panel == 0) {

        newWindow('data_pribadi_cetak.php?nis=<?=$nis?>', 'CetakDataPribadi','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')

    } else if (panel == 1)  {
        var num = document.panel1.num.value;
        if (num > 0) {
            var kelas = document.panel1.kelas.value;
            newWindow('presensi_harian_cetak.php?nis=<?=$nis?>&kelas='+kelas, 'CetakStatistikPresensiHarian','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
        } else {
            alert ('Tidak bisa melakukan pencetakan karena belum ada data!');
        }
    } else if (panel == 2)  {
        var num = document.panel2.num.value;
        if (num > 0) {
            var kelas = document.panel2.kelas.value;
            newWindow('presensi_pelajaran_cetak.php?nis=<?=$nis?>&kelas='+kelas, 'CetakStatistikPresensiPelajaran','790','650','resizable=1,scrollbars=1,status=0,toolbar=0'	)
        } else {
            alert ('Tidak bisa melakukan pencetakan karena belum ada data!');
        }

    } else if (panel == 3)  {
        var departemen = document.panel3.departemen.value;
        var tahunajaran = document.panel3.tahunajaran.value;
        var kelas = document.panel3.kelas.value;
        var pelajaran = document.panel3.pelajaran.value;
        newWindow('nilai_cetak.php?nis=<?=$nis?>&kelas='+kelas+'&tahunajaran='+tahunajaran+'&departemen='+departemen+'&semester='+semester+'&pelajaran='+pelajaran, 'CetakNilai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0'	)


    }
}
function ChgDepNil(Val){
    var nis = document.panel3.nis.value;
    var nis_awal = document.panel3.nis_awal.value;
    show_wait('nilai');
    sendRequestText("nilai.php", showNIL, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+Val);
}
function ChgTANil(Val){
    var nis = document.panel3.nis.value;
    var nis_awal = document.panel3.nis_awal.value;
    var departemen = document.panel3.departemen.value;
    show_wait('nilai');
    sendRequestText("nilai.php", showNIL, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen+"&tahunajaran="+Val);
}
function ChgKlsNil(Val){
    var nis = document.panel3.nis.value;
    var nis_awal = document.panel3.nis_awal.value;
    var departemen = document.panel3.departemen.value;
    var tahunajaran = document.panel3.tahunajaran.value;
    var pelajaran = document.panel3.pelajaran.value;
    show_wait('nilai');
    sendRequestText("nilai.php", showNIL, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&pelajaran="+pelajaran+"&kelas="+Val);
}
function ChgPelNil(Val){
    var nis = document.panel3.nis.value;
    var nis_awal = document.panel3.nis_awal.value;
    var departemen = document.panel3.departemen.value;
    var tahunajaran = document.panel3.tahunajaran.value;
    var kelas = document.panel3.kelas.value;
    show_wait('nilai');
    sendRequestText("nilai.php", showNIL, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&pelajaran="+Val+"&kelas="+kelas);
}
function change_dep(panel) {
    if (panel == 1) {
        var nis = document.panel1.nis.value;
        var nis_awal = document.panel1.nis_awal.value;
        var departemen = document.panel1.departemen.value;
        show_wait('ph');
        sendRequestText("presensi_harian.php", showPH, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen);
    } else if (panel == 2) {
        var nis = document.panel2.nis.value;
        var nis_awal = document.panel2.nis_awal.value;
        var departemen = document.panel2.departemen.value;
        show_wait('pp');
        sendRequestText("presensi_pelajaran.php", showPP, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen);
    } else if (panel == 3) {
        var nis = document.panel3.nis.value;
        var nis_awal = document.panel3.nis_awal.value;
        var departemen = document.panel3.departemen.value;
        show_wait('nilai');
        sendRequestText("nilai.php", showNIL, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen);
    } else if (panel == 4) {
        var nis = document.panel4.nis.value;
        var nis_awal = document.panel4.nis_awal.value;
        var departemen = document.panel4.departemen.value;
        show_wait('rapor');
        sendRequestText("rapor.php", showRAP, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen);
    }
}

function change(panel) {
    if (panel == 1) {
        var nis = document.panel1.nis.value;
        var nis_awal = document.panel1.nis_awal.value;
        var departemen = document.panel1.departemen.value;
        var tahunajaran = document.panel1.tahunajaran.value;
        var kelas = document.panel1.kelas.value;
        show_wait('ph');
        sendRequestText("presensi_harian.php", showPH, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&kelas="+kelas);
    } else if (panel == 2) {
        var nis = document.panel2.nis.value;
        var nis_awal = document.panel2.nis_awal.value;
        var departemen = document.panel2.departemen.value;
        var tahunajaran = document.panel2.tahunajaran.value;
        var kelas = document.panel2.kelas.value;
        show_wait('pp');
        sendRequestText("presensi_pelajaran.php", showPP, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&kelas="+kelas);
    } else if (panel == 3) {
        var nis = document.panel3.nis.value;
        var nis_awal = document.panel3.nis_awal.value;
        var departemen = document.panel3.departemen.value;
        var tahunajaran = document.panel3.tahunajaran.value;
        var kelas = document.panel3.kelas.value;
        show_wait('nilai');
        sendRequestText("nilai.php", showNIL, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&kelas="+kelas);
    } else if (panel == 4) {
        var nis = document.panel4.nis.value;
        var nis_awal = document.panel4.nis_awal.value;
        var departemen = document.panel4.departemen.value;
        var tahunajaran = document.panel4.tahunajaran.value;
        var kelas = document.panel4.kelas.value;
        var semester = document.panel4.semester.value;
        //alert ('Masuk panel4 ');
        show_wait('rapor');
        sendRequestText("rapor.php", showRAP, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&kelas="+kelas+"&semester="+semester);
    }
}

function change_ta(panel)
{
    if (panel == 4)
    {
        var nis = document.panel4.nis.value;
        var nis_awal = document.panel4.nis_awal.value;
        var departemen = document.panel4.departemen.value;
        var tahunajaran = document.panel4.tahunajaran.value;
        show_wait('rapor');
        sendRequestText("rapor.php", showRAP, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen+"&tahunajaran="+tahunajaran);
    }
}

function change_pel() {
    var nis = document.panel3.nis.value;
    var nis_awal = document.panel3.nis_awal.value;
    var departemen = document.panel3.departemen.value;
    var tahunajaran = document.panel3.tahunajaran.value;
    var kelas = document.panel3.kelas.value;
    var pelajaran = document.panel3.pelajaran.value;
    show_wait('nilai');
    sendRequestText("nilai.php", showNIL, "nis_awal="+nis_awal+"&nis="+nis+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&kelas="+kelas+"&pelajaran="+pelajaran);

}

function show_wait(areaId) {
    var x = document.getElementById("waitBox").innerHTML;
    document.getElementById(areaId).innerHTML = x;
}

function cetaknil(panel){
    var addr;
    var x = panel.explode('_');
    var semester = x[1];
    var nis = document.panel3.nis_awal.value;
    var kelas = document.panel3.kelas.value;
    var pelajaran = document.panel3.pelajaran.value;
    var tahunajaran = document.panel3.tahunajaran.value;
    //alert ("Sem="+semester+"Nis="+nis+"kel="+kelas+"Pel="+pelajaran);
    addr = 'nilai_cetak.php?nis='+nis+'&kelas='+kelas+'&pelajaran='+pelajaran+'&semester='+semester+'&tahunajaran='+tahunajaran;
    newWindow(addr, 'CetakData','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function pk_changeBulan()
{
    var bulan = document.getElementById('pk_cbBulan').value;
    var tahun = document.getElementById('pk_cbTahun').value;
    var param = "bulan="+bulan+"&tahun="+tahun;

    sendRequestText('infosiswa.presensikegiatan.php', showPK, param);
}

var pk_divDetail_Target = "";
function pk_showDetail(cnt, idkegiatan)
{
    var bulan = document.getElementById('pk_cbBulan').value;
    var tahun = document.getElementById('pk_cbTahun').value;
    var param = "idkegiatan="+idkegiatan+"&bulan="+bulan+"&tahun="+tahun+"&cnt="+cnt;

    pk_divDetail_Target = "pk_divDetail_" + cnt;
    sendRequestText('infosiswa.presensikegiatan.detail.php', showDetailPresensiKegiatan, param);
}

function showDetailPresensiKegiatan(html)
{
    document.getElementById(pk_divDetail_Target).innerHTML = html;
}

function pk_closeDetail(cnt, idkegiatan)
{
    var ahref = "<a href='#' onclick='pk_showDetail(" + cnt + "," + idkegiatan + ")' style='color: blue; font-weight: normal;'>detail</a>";
    document.getElementById("pk_divDetail_" + cnt).innerHTML = ahref;
}

function ChangePresensiPelajaranOption2(select)
{
    var nis = document.panel2.nis_awal.value;
    if (select == "departemen")
    {
        var dep = document.panel2.departemen.value;
        var param = 'nis_awal='+nis+'&departemen='+dep;
    }
    else if (select == "tahunajaran")
    {
        var dep = document.panel2.departemen.value;
        var ta = document.panel2.tahunajaran.value;
        var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta;
    }
    else
    {
        var dep = document.panel2.departemen.value;
        var ta = document.panel2.tahunajaran.value;
        var k =	document.panel2.kelas.value;
        var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta+'&kelas='+k;
    }

    show_wait('pp');
    sendRequestText("presensi_pelajaran.php", showPP, param);

    //show_wait("infosiswa.content");
    //sendRequestText('infosiswa/infosiswa.presensipelajaran.php', showInfoSiswaContent, param);
}
</script>
</body>
</html>