function Ubah(){
	document.getElementById('BtnEdit').style.display='none';
	document.getElementById('BtnSave').style.display='block';
	document.getElementById('Format').disabled=false;
}
function Simpan(){
	var NewFormat = document.getElementById('Format').value;
	//alert (NewFormat);
	parent.HiddenFrame.location.href = "presensi.ajax.php?op=SaveFormat&NewFormat="+NewFormat;
	document.getElementById('BtnEdit').style.display='block';
	document.getElementById('BtnSave').style.display='none';
	document.getElementById('Format').disabled=true;
}