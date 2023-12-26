is_Login = function() {
    var nis = trim($('#is_nis').val());
    var pin = trim($('#is_pin').val());
    
    if (nis.length == 0 || pin.length == 0)
        return;
    
    $.ajax({
        url : 'infosiswa/infosiswa.dologin.php?nis='+nis+'&pin='+pin,
        type: 'get',
        success : function(html) {
            $('#is_main').html(html);
        }
    })
}

is_Logout = function() {
    if (!confirm('Apakah anda akan keluar dari halaman Informasi Siswa ini?'))
        return;
    
    $.ajax({
        url : 'infosiswa/infosiswa.logout.php',
        type: 'get',
        success : function(html) {
            $('#is_main').html(html);
        }
    })
}

function showInfoTabK(x){
	document.getElementById("keuangan").innerHTML = x;
}
function showInfoTabPH(x){
	document.getElementById("ph").innerHTML = x;
}
function showInfoTabPP(x){
	document.getElementById("pp").innerHTML = x;
}

function showInfoTabN(x){
	document.getElementById("nilai").innerHTML = x;
	var TabbedPanels3 = new Spry.Widget.TabbedPanels("TabbedPanels3");
}

function ChangeTabNilai(idtab)
{
    var TabbedPanels3 = new Spry.Widget.TabbedPanels("TabbedPanels3");
    TabbedPanels3.showPanel(idtab);
}

function showInfoTabR(x){
	document.getElementById("rapor").innerHTML = x;
}

function showInfoTabP(x){
	document.getElementById("perpus").innerHTML = x;
}

function showInfoTabSemester(x){
	var active_tab = document.getElementById('active_tab').value;
	document.getElementById('sem'+active_tab).innerHTML = x;
}


function ShowBulanCatatanSiswa(x) {
	document.getElementById("tabel_ck").innerHTML = x;
}

function show_catatan(html)
{
	document.getElementById("contentcatatan").innerHTML = html;
}

function ChangePerpusOption(select)
{
      var tahun = document.panelperpus.tahun.value;
      
      show_wait("infosiswa.content");
	  sendRequestText('infosiswa/infosiswa.perpustakaan.php', showInfoSiswaContent, "tahun="+tahun);
}

function ChangeKeuOption(select)
{
      var nis = document.panelkeu.nis_awal.value;
      if (select == 'departemen')
      {
            var dep = document.panelkeu.departemen.value;
            var param = 'nis_awal='+nis+'&departemen='+dep;
      }
      else
      {
            var dep = document.panelkeu.departemen.value;
            var tb = document.panelkeu.idtahunbuku.value;
            var param = 'nis_awal='+nis+'&departemen='+dep+'&idtahunbuku='+tb;
      }
      
      show_wait("infosiswa.content");
	  sendRequestText('infosiswa/infosiswa.keuangan.php', showInfoSiswaContent, param);
}

function ChangePresensiHarianOption2(select)
{
      var nis = document.panelph.nis_awal.value;
      if (select == "departemen")
      {
            var dep = document.panelph.departemen.value;
            var param = 'nis_awal='+nis+'&departemen='+dep;
      }
      else if (select == "tahunajaran")
      {
            var dep = document.panelph.departemen.value;
            var ta = document.panelph.tahunajaran.value;
            var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta;
      }
      else
      {
            var dep = document.panelph.departemen.value;
            var ta = document.panelph.tahunajaran.value;
            var k =	document.panelph.kelas.value;
            var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta+'&kelas='+k;
      }
      
      show_wait("infosiswa.content");
	  sendRequestText('infosiswa/infosiswa.presensiharian.php', showInfoSiswaContent, param);
}

function CetakPresensiHarian4()
{
      var nis = document.panelph.current_nis.value;
      var nis_aktif = document.panelph.nis_awal.value;
      var dep = document.panelph.departemen.value;
      var ta = document.panelph.tahunajaran.value;
	  var kelas = document.panelph.kelas.value;
      
	  addr = 'infosiswa/infosiswa.presensiharian.cetak.php?nis='+nis+'&nis_aktif='+nis_aktif+'&departemen='+dep+'&tahunajaran='+ta+'&kelas='+kelas;
      newWindow(addr, 'CetakPH', '800', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ChangePresensiPelajaranOption2(select)
{
      var nis = document.panelpp.nis_awal.value;
      if (select == "departemen")
      {
            var dep = document.panelpp.departemen.value;
            var param = 'nis_awal='+nis+'&departemen='+dep;
      }
      else if (select == "tahunajaran")
      {
            var dep = document.panelpp.departemen.value;
            var ta = document.panelpp.tahunajaran.value;
            var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta;
      }
      else
      {
            var dep = document.panelpp.departemen.value;
            var ta = document.panelpp.tahunajaran.value;
            var k =	document.panelpp.kelas.value;
            var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta+'&kelas='+k;
      }
      
      show_wait("infosiswa.content");
      sendRequestText('infosiswa/infosiswa.presensipelajaran.php', showInfoSiswaContent, param);
}

function CetakPresensiPelajaran2()
{
      var nis = document.panelpp.current_nis.value;
      var nis_aktif = document.panelpp.nis_awal.value;
      var dep = document.panelpp.departemen.value;
      var ta = document.panelpp.tahunajaran.value;
	  var kelas = document.panelpp.kelas.value;
      
	  addr = 'pp_cetak.php?nis='+nis+'&kelas='+kelas;
      newWindow(addr, 'CetakPP', '800', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ChangeRaporOption2(select)
{
      var nis = document.panelrapor.nis_awal.value;
      if (select == 'departemen')
      {
            var dep = document.panelrapor.departemen.value;
            var param = 'nis_awal='+nis+'&departemen='+dep;
      }
      else if (select == 'tahunajaran')
      {
            var dep = document.panelrapor.departemen.value;
            var ta = document.panelrapor.tahunajaran.value;
            var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta;
      }
      else if (select == 'kelas')
      {
            var dep = document.panelrapor.departemen.value;
            var ta = document.panelrapor.tahunajaran.value;
            var k = document.panelrapor.kelas.value;
            var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta+'&kelas='+k;
      }
      else if (select == 'semester')
      {
            var dep = document.panelrapor.departemen.value;
            var ta = document.panelrapor.tahunajaran.value;
            var k = document.panelrapor.kelas.value;
            var s = document.panelrapor.semester.value;
            var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta+'&kelas='+k+'&semester='+s;
      }
    
      show_wait("infosiswa.content");
      sendRequestText('infosiswa/infosiswa.rapor.php', showInfoSiswaContent, param);
}

function CetakRapor3()
{
      var nis = document.panelrapor.current_nis.value;
      var nis_aktif = document.panelrapor.nis_awal.value;
	  var kelas = document.panelrapor.kelas.value;
      var semester = document.panelrapor.semester.value;
      
      addr = 'infosiswa.rapor.cetak.php?nis='+nis+'&nis_aktif='+nis_aktif+'&kelas='+kelas+'&semester='+semester;
      newWindow(addr, 'CetakRapor', '800', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ChangeNilaiOption2(select)
{
      var nis	 = document.panelnilai.nis_awal.value;
      if (select == "departemen")
      {
            var dep  = document.panelnilai.departemen.value;
            var param = 'nis_awal='+nis+'&departemen='+dep;
      }
      else if (select == "tahunajaran")
      {
            var dep  = document.panelnilai.departemen.value;
            var ta   = document.panelnilai.tahunajaran.value;
            var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta;  
      }
      else if (select == "kelas")
      {
            var dep  = document.panelnilai.departemen.value;
            var ta   = document.panelnilai.tahunajaran.value;
            var k    = document.panelnilai.kelas.value;
            var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta+'&kelas='+k;
      }
      else
      {
            var dep  = document.panelnilai.departemen.value;
            var ta   = document.panelnilai.tahunajaran.value;
           	var k    = document.panelnilai.kelas.value;
            var p    = document.panelnilai.pelajaran.value;
            var param = 'nis_awal='+nis+'&departemen='+dep+'&tahunajaran='+ta+'&kelas='+k+'&pelajaran='+p;
      }

      show_wait("infosiswa.content");      
      sendRequestText('infosiswa/infosiswa.nilai.php', showInfoSiswaContent, param);  
}

function CetakNilai2(semester)
{
      var nis = document.panelnilai.current_nis.value;
      var nis_aktif = document.panelnilai.nis_awal.value;
      var kelas = document.panelnilai.kelas.value;
      var pelajaran = document.panelnilai.pelajaran.value;
      var tahunajaran = document.panelnilai.tahunajaran.value;

      addr = 'infosiswa.nilai.cetak.php?nis='+nis+'&kelas='+kelas+'&pelajaran='+pelajaran+'&semester='+semester+'&tahunajaran='+tahunajaran;
      newWindow(addr, 'CetakNilai', '800', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function showInfoSiswaContent(html)
{
    document.getElementById("infosiswa.content").innerHTML = html;
    var TabbedPanels3 = new Spry.Widget.TabbedPanels("TabbedPanels3");
    TabbedPanels3.showPanel(0);
}

function show_wait(areaId)
{
	//var x = document.getElementById("waitBox").innerHTML;
	//document.getElementById(areaId).innerHTML = x;
}

function getTabContent(){
	show_wait('pilihsiswa');
	sendRequestText('pilihsiswa.php',showTabPilih,'');
	show_wait('carisiswa');
	sendRequestText('carisiswa.php',showTabCari,'');
}
function showTabPilih(x){
	document.getElementById('pilihsiswa').innerHTML = x;
}
function showTabCari(x){
	document.getElementById('carisiswa').innerHTML = x;
}
function chg_dep(){
	//alert ('asup kehed');
	var dep = document.frmPilih.dep.value;
	show_wait('tktInfo');
	sendRequestText('gettkt.php',showtktInfo,'dep='+dep); 
}
function showtktInfo(x){
	document.getElementById('tktInfo').innerHTML = x;
	chg_tkt();
}
function chg_tkt(){
	var tkt = document.frmPilih.tkt.value;
	var ta = document.frmPilih.ta.value;
	//alert (ta+' '+tkt);
	show_wait('klsInfo');
	sendRequestText('getkls.php',showklsInfo,'tkt='+tkt+'&ta='+ta); 
}

function showklsInfo(x){
	document.getElementById('klsInfo').innerHTML = x;
	chg_kls();
}

function chg_kls(){
	var kls = document.frmPilih.kls.value;
	show_wait('sisInfo');
	sendRequestText('getsis.php',showsisInfo,'kls='+kls); 
}

function showsisInfo(x){
	document.getElementById('sisInfo').innerHTML = x;
}

function load_first_dep() { }

function pilihsiswa(nis)
{
	show_wait('content');
	sendRequestText('infosiswa/infosiswa.content.php', showInfo, 'nis='+nis);
}

function showInfo(html)
{
    document.getElementById('is_main').innerHTML = html;
}

function showInfoInitTabbedPanel(html)
{
    document.getElementById('is_main').innerHTML = html;
    var TabbedPanels3 = new Spry.Widget.TabbedPanels("TabbedPanels3");
    TabbedPanels3.showPanel(0);
}

function GetReportContent()
{
    var reporttype = document.getElementById('reporttype').value;
	
    show_wait('content');
   
    if (reporttype == 'NILAI')
        sendRequestText('infosiswa/infosiswa.content.php', showInfoInitTabbedPanel, 'reporttype='+reporttype);
    else
        sendRequestText('infosiswa/infosiswa.content.php', showInfo, 'reporttype='+reporttype);
}

function pk_changeBulan()
{
	var bulan = document.getElementById('pk_cbBulan').value;
	var tahun = document.getElementById('pk_cbTahun').value;
	var param = "bulan="+bulan+"&tahun="+tahun;
	
	show_wait("infosiswa.content");      
    sendRequestText('infosiswa/infosiswa.presensikegiatan.php', showInfoSiswaContent, param);  
}

var pk_divDetail_Target = "";
function pk_showDetail(cnt, idkegiatan)
{
	var bulan = document.getElementById('pk_cbBulan').value;
	var tahun = document.getElementById('pk_cbTahun').value;
	var param = "idkegiatan="+idkegiatan+"&bulan="+bulan+"&tahun="+tahun+"&cnt="+cnt;
	
	pk_divDetail_Target = "pk_divDetail_" + cnt;
	show_wait(pk_divDetail_Target);      
    sendRequestText('infosiswa/infosiswa.presensikegiatan.detail.php', showDetailPresensiKegiatan, param);  
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

function is_Pengaturan()
{
	$.ajax({
        url : 'infosiswa/infosiswa.adminlogin.php',
        type: 'get',
        data: 'op=adminlogin',
        success : function(html) {
            $('#is_main').html(html);
        }
    })
}

function is_Admin_Login()
{
    var password = trim($('#is_admin_password').val());
    if (password.length == 0)
        return;
    
    $.ajax({
        url : 'infosiswa/infosiswa.doadminlogin.php',
        type: 'get',
        data: 'password='+password,
        success : function(html) {
            $('#is_main').html(html);
        }
    })
}

function is_Admin_Cancel()
{
	$.ajax({
        url : 'infosiswa/infosiswa.login.php',
        type: 'get',
        success : function(html) {
            $('#is_main').html(html);
        }
    })   
}

function is_SaveConfig()
{
	if (!confirm('Simpan konfigurasi?'))
		return;
	
	var allow = $('#is_allowedit').prop('checked') ? 1 : 0;
	$.ajax({
        url : 'infosiswa/infosiswa.saveconfig.php',
        type: 'post',
        data: 'allow='+allow,
        success : function(html) {
            $('#is_main').html(html);
        }
    })
}

function is_CloseConfig()
{
	$.ajax({
        url : 'infosiswa/infosiswa.doadminlogout.php',
        type: 'get',
        success : function(html) {
            $('#is_main').html(html);
        }
    })   
}