change_dep = function () {
	var dep = $("#departemen").val();
	
	document.location.href = "multitrans.header.php?departemen="+dep;
	parent.content.href = "blank.php";
	
	$("#kelompok").val("");
	$("#noid").val("");
	$("#nama").val("");
}

SearchUser = function() {
	var dep = $("#departemen").val();
	var addr = "multitrans.searchuser.php?departemen="+dep;
	newWindow(addr, 'CariUser', '550', '590', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

AcceptSearch = function(data, noid, nama, kelas) {
	$("#kelompok").val(data);
	$("#noid").val(noid);
	$("#nama").val(nama);
	$("#kelas").val(kelas);
	
	StartPayment();
}

StartPayment = function()
{
	var departemen = $.trim($("#departemen").val());
	if (departemen.length == 0)
	{
		alert("Belum ada data Departemen!")
		return;
	}
	
	var idtahunbuku = parseInt($("#idtahunbuku").val());
	if (idtahunbuku == 0)
	{
		alert("Belum ada Tahun Buku yang berjalan di Departemen terpilih!")
		return;
	}
	
	var kelompok = $("#kelompok").val();
	var noid = $("#noid").val();
	var nama = $("#nama").val();
	var kelas = $("#kelas").val();
	if ($.trim(noid).length == 0)
	{
		alert("Anda belum menentukan Siswa/Calon Siswa!")
		return;
	}
	
	var addr = "multitrans.content.php?departemen="+departemen+"&idtahunbuku="+idtahunbuku+"&kelompok="+kelompok+"&noid="+noid+"&nama="+nama+"&kelas="+kelas;
	parent.content.location.href = addr;
}