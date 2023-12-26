var win = null;
function newWindow(mypage,myname,w,h,features)
{
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
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
      return false;
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


function ShowCatatanHarian()
{
      var tglawal = document.getElementById("tglawal").value;
      var tglakhir = document.getElementById("tglakhir").value;
      var nis = document.getElementById("niscthar").value;
      
      if (tglakhir != "" && tglawal != "")
      {
            show_wait('contentph');
            sendRequestText("infosiswa.catatanharian.ajax.php", ShowCatatanHarianContent, "nis="+nis+"&tglawal="+tglawal+"&tglakhir="+tglakhir);		
      }
      else
      {
            alert ('Periode awal dan periode akhir harus diisi!');
      }
}

function ShowCatatanHarianContent(html)
{
	document.getElementById("contentph").innerHTML = html;
}

function ChangePelajaranCatatanPelajaran(nis)
{
    var pelajaran = document.getElementById("pel").value;
	show_wait('content_pp');
	sendRequestText("infosiswa.catatanpelajaran.ajax.php", ShowCatatanPelajaran, "pelajaran="+pelajaran+"&nis="+nis);  
}

function ShowCatatanPelajaran(html)
{
      document.getElementById("content_pp").innerHTML = html;
}


function ChangeTahunCatatanSiswa(nis)
{
    var tahun = document.getElementById("tahun").value;
	show_wait('tabel_ck');
	sendRequestText("infosiswa.catatansiswa.getbulan.ajax.php", ShowBulanCatatanSiswa, "tahun="+tahun+"&nis="+nis);
	document.getElementById("contentcatatan").innerHTML = "&nbsp;";
}

function ShowBulanCatatanSiswa(x) {
	document.getElementById("tabel_ck").innerHTML = x;
}

function ShowCatatanSiswa(nis, bulan, tahun)
{
    show_wait('contentcatatan');
	sendRequestText("infosiswa.catatansiswa.ajax.php", show_catatan, "nis="+nis+"&bulan="+bulan+"&tahun="+tahun);  
}

function show_catatan(html)
{
	document.getElementById("contentcatatan").innerHTML = html;
}

function CetakKeuangan()
{
      var nis = document.panelkeu.current_nis.value;
      var dept = document.panelkeu.current_dept.value;
      var tb = document.panelkeu.current_tb.value;
      
	  addr = 'keuangan_cetak.php?nis='+nis+'&departemen='+dept+'&idtahunbuku='+tb;
      newWindow(addr, 'CetakKEU', '800', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function CetakProfile()
{
      var nis = document.paneldp.nis.value;
      
	  addr = 'datapribadi_cetak.php?nis='+nis;
      newWindow(addr, 'CetakDP', '800', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ChangePerpusOption(select)
{
      var tahun = document.panelperpus.tahun.value;
      
      show_wait("infosiswa.content");
	  sendRequestText('infosiswa.perpustakaan.php', showInfoSiswaContent, "tahun="+tahun);
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
	  sendRequestText('infosiswa.keuangan.php', showInfoSiswaContent, param);
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
	  sendRequestText('infosiswa.presensiharian.php', showInfoSiswaContent, param);
}

function CetakPresensiHarian4()
{
      var nis = document.panelph.current_nis.value;
      var nis_aktif = document.panelph.nis_awal.value;
      var dep = document.panelph.departemen.value;
      var ta = document.panelph.tahunajaran.value;
	  var kelas = document.panelph.kelas.value;
      
	  addr = 'infosiswa.presensiharian.cetak.php?nis='+nis+'&nis_aktif='+nis_aktif+'&departemen='+dep+'&tahunajaran='+ta+'&kelas='+kelas;
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
      sendRequestText('infosiswa.presensipelajaran.php', showInfoSiswaContent, param);
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
      sendRequestText('infosiswa.rapor.php', showInfoSiswaContent, param);
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
      sendRequestText('infosiswa.nilai.php', showInfoSiswaContent, param);  
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
}

function cetak(panel){
	//alert('Masuk');
	var addr;
	if (panel==1)
	{
		var nis = document.paneldp.nis.value;
		addr = 'datapribadi_cetak.php?nis='+nis;

	}
	else if (panel==2)
	{
		var nis = document.panelkeuangan.nis.value;
		addr = 'keuangan_cetak.php?nis='+nis;

	}
	else if (panel==3)
	{
		var nis = document.panelph.nis_awal.value;
		var kelas = document.panelph.kelas.value;
		addr = 'ph_cetak.php?nis='+nis+'&kelas='+kelas;
	}
	else if (panel==4)
	{
		var nis = document.panelpp.nis_awal.value;
		var kelas = document.panelpp.kelas.value;
		addr = 'pp_cetak.php?nis='+nis+'&kelas='+kelas;
	}
	else if (panel==6)
	{
		var nis = document.panelpp.nis_awal.value;
		var kelas = document.panelpp.kelas.value;
		addr = 'rapor_cetak.php?nis='+nis+'&kelas='+kelas;
	}
	else 
	{
		var x = panel.split('_');
		var semester = x[1];
		var nis = document.panelnilai.nis_awal.value;
		var kelas = document.panelnilai.kelas.value;
		var pelajaran = document.panelnilai.pelajaran.value;
		var tahunajaran = document.panelnilai.tahunajaran.value;
		//alert ("Sem="+semester+"Nis="+nis+"kel="+kelas+"Pel="+pelajaran);
		addr = 'nilai_cetak.php?nis='+nis+'&kelas='+kelas+'&pelajaran='+pelajaran+'&semester='+semester+'&tahunajaran='+tahunajaran;
	}
	
	newWindow(addr, 'CetakData','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function pk_changeBulan()
{
	var bulan = document.getElementById('pk_cbBulan').value;
	var tahun = document.getElementById('pk_cbTahun').value;
	var param = "bulan="+bulan+"&tahun="+tahun;
	
	show_wait("infosiswa.content");      
    sendRequestText('infosiswa.presensikegiatan.php', showInfoSiswaContent, param);  
}

var pk_divDetail_Target = "";
function pk_showDetail(cnt, idkegiatan)
{
	var bulan = document.getElementById('pk_cbBulan').value;
	var tahun = document.getElementById('pk_cbTahun').value;
	var param = "idkegiatan="+idkegiatan+"&bulan="+bulan+"&tahun="+tahun+"&cnt="+cnt;
	
	pk_divDetail_Target = "pk_divDetail_" + cnt;
	show_wait(pk_divDetail_Target);      
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
