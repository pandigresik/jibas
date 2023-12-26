function getFresh(){
	var tglAwal = document.getElementById('tglAwal').value;
	var tglAkhir = document.getElementById('tglAkhir').value;
	document.location.href = "aktivitas.list.php?tglAwal="+tglAwal+"&tglAkhir="+tglAkhir;
}
function hapus(id){
	if (confirm('Anda yakin akan menghapus aktivitas ini?'))
		document.location.href = "aktivitas.list.php?op=del&id="+id;
}
function cetak(){
	var tglAwal = document.getElementById('tglAwal').value;
	var tglAkhir = document.getElementById('tglAkhir').value;
	var addr = "aktivitas.cetak.php?tglAwal="+tglAwal+"&tglAkhir="+tglAkhir;
	newWindow(addr, 'CetakAktivitas','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function ubah(id){
	document.location.href='aktivitas.edit.php?replid='+id;
	//newWindow('aktivitas.edit.php?replid='+id, 'UbahAktivitas','433','459','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function TakeDate(elementid){
	var addr = "../lib/cals.php?elementid="+elementid;
	newWindow(addr, 'CariTanggal','338','216','resizable=0,scrollbars=0,status=0,toolbar=0')
}
function AcceptDate(date,elementid){
	document.getElementById(elementid).value=date;
	var tglAwal = document.getElementById('tglAwal').value;
	var tglAkhir = document.getElementById('tglAkhir').value;
	document.location.href = "../akt/aktivitas.list.php?tglAwal="+tglAwal+"&tglAkhir="+tglAkhir;
}