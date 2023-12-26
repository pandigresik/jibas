function tambah() 
{
	var status = $('#status').val();
	var departemen = $('#departemen').val();

	document.location.href = 'lampiran.editor.php?mode=new&departemen='+departemen+'&status='+status;
}

function refresh() 
{
	var status = $('#status').val();
	var departemen = $('#departemen').val();

	document.location.href = "lampiran.php?departemen="+departemen+"&status="+status;
}

function ubah(id)
{
	var status = $('#status').val();
	var departemen = $('#departemen').val();

	document.location.href = "lampiran.editor.php?mode=edit&id="+id+"&departemen="+departemen+"&status="+status;
}

function hapus(id)
{
	if (!confirm('Apakah anda yakin akan menghapus data ini?'))
		return;
	
	var status = $('#status').val();
	var departemen = $('#departemen').val();

	document.location.href = "lampiran.php?op=cqiqywpxwq&id="+id+"&departemen="+departemen+"&status="+status;
}

function setStatus(newstatus, id)
{
	var msg = newstatus == 0 ? "Apakah anda akan me NON AKTIF kan data ini?" : "Apakah anda akan meng AKTIF kan data ini?"
	if (!confirm(msg))
		return false;
	
	var status = $('#status').val();
	var departemen = $('#departemen').val();

	document.location.href = "lampiran.php?op=mxd238mhde2&id="+id+"&newstatus="+newstatus+"&departemen="+departemen+"&status="+status;
}

function changeSelect()
{
	refresh();
}