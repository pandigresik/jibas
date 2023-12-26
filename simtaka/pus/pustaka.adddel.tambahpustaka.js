function ValidateSubmit()
{
	var npus = $('#npus').val();
	var total = 0;
	for(var i = 1; i <= npus; i++)
	{
		var jumlah = $.trim($('#jumlah' + i).val());
		//alert("jumlah " + i + " = " + jumlah);
		if (jumlah == "")
			jumlah = "0";
			
		total += parseInt(jumlah);
	}
	
	//alert(total);
	if (total == 0)
	{
		alert('Anda harus memasukkan jumlah penambahan pustaka di salah satu perpustakaan!');
		return false;
	}
	
	return confirm('Data sudah benar?');
}