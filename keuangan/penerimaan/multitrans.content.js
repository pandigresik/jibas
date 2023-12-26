var nData = -1;
var totalPayment = 0;

ChangeKate = function()
{
	var jenis = $("#kate").val();
	var dept = $("#departemen").val();
	
	$.ajax({
        type: "POST",
        url: "multitrans.content.changekate.php",
        data: "jenis="+jenis+"&departemen="+dept,
        success: function(response) {
			$("#divPaymentInfo").html("");
			$("#divSelectPayment").html(response);
        },
        error: function(xhr, ajaxOptions, thrownError) {
			$("#divSelectPayment").html("ERROR: " + xhr.responseText);
            Logger.HandleError(xhr.responseText);
        }
	});
}

ChangePayment = function()
{
	var idpayment = parseInt($("#payment").val());
	if (idpayment == 0)
	{
		$("#divPaymentInfo").html("");
		return;
	}
	
	var jenis = $("#kate").val();
	var dept = $("#departemen").val();
	var noid = $("#noid").val();
	var kelompok = $("#kelompok").val();
	var idtahunbuku = $("#idtahunbuku").val();
	$.ajax({
        type: "POST",
        url: "multitrans.content.paymentinfo.php",
        data: "jenis="+jenis+"&departemen="+dept+"&noid="+noid+"&idtahunbuku="+idtahunbuku+"&kelompok="+kelompok+"&idpayment="+idpayment,
        success: function(response) {
			$("#divPaymentInfo").html(response);
        },
        error: function(xhr, ajaxOptions, thrownError) {
			$("#divPaymentInfo").html("ERROR: " + xhr.responseText);
            Logger.HandleError(xhr.responseText);
        }
	});
}

CalculatePay = function()
{
	var cicilan = $("#jcicilan").val();
	var diskon = $("#jdiskon").val();
	cicilan = rupiahToNumber(cicilan);
	diskon = rupiahToNumber(diskon);
	
	var bayar = cicilan - diskon;
	$("#jbayar").val(numberToRupiah(bayar));
}

GetRupiahValueOf = function(id)
{
	return $.trim(rupiahToNumber($("#" + id).val()));
}

ValidateRupiahInput = function(id, name, allowEmpty)
{
	var input = GetRupiahValueOf(id);
	if (!allowEmpty && input.length == 0)
	{
		alert("Anda belum memasukkan " + name + "!");
		Validator.FocusErrorById(id);
		return false;
	}
	
	if ( isNaN(input) )
	{
		alert(name + " harus angka!");
		Validator.FocusErrorById(id);
		return false;
	}
	
	if (parseInt(input) < 0)
	{
		alert(name + " harus positif atau sama dengan nol!");
		Validator.FocusErrorById(id);
		return false;
	}
	
	return true;
}

DeletePayment = function(rowno)
{
	if (!confirm("Apakah anda yakin akan menghapus data ini?")) 
		return;
	
	var jcicilan = parseInt($("#i_jcicilan_" + rowno).val());
	var jdiskon = parseInt($("#i_jdiskon_" + rowno).val());
	
	//alert(totalPayment + " vs " + jcicilan + " - " + jdiskon)
	
	totalPayment -= jcicilan - jdiskon;
	$("#spanTotalInfo").text(numberToRupiah(totalPayment));
	
	$("#row" + rowno).remove();
	$("#flagrow" + rowno).val(0);
}

AddToPaymentListWjb = function()
{
	var kate = $("#kate").val();
	var idpayment = $("#payment").val();
	if ($("#" + kate + "_" + idpayment).length > 0)
	{
		alert("Jenis pembayaran ini sudah ada dalam Daftar Pembayaran!");
		return false;
	}
	
	var isok = true;
	var idbesarjtt = parseInt($("#idbesarjtt").val());
	if (idbesarjtt == 0)
	{
		isok = ValidateRupiahInput("tagihan", "Total Tagihan", false) &&
			   ValidateRupiahInput("bcicilan", "Besar Cicilan", false);
	}
	
	if (!isok)
		return false;
	
	isok = ValidateRupiahInput("jcicilan", "Pembayaran Cicilan", false) &&
		   ValidateRupiahInput("jdiskon", "Jumlah Diskon", true);
	
	if (!isok)
		return false;
	
	var tagihan = parseInt(GetRupiahValueOf("tagihan"));
	var bcicilan = parseInt(GetRupiahValueOf("bcicilan"));
	var jcicilan = parseInt(GetRupiahValueOf("jcicilan"));
	var jdiskon = GetRupiahValueOf("jdiskon");
	jdiskon = jdiskon.length == 0 ? 0 : parseInt(jdiskon); 
	
	var sisa = tagihan;
	if (idbesarjtt != 0)
		sisa = parseInt(GetRupiahValueOf("sisa"));
	
	/*
	console.log("tagihan = " + tagihan);
	console.log("jcicilan = " + jcicilan);
	console.log("jdiskon = " + jdiskon);
	console.log("sisa = " + sisa);
	*/
	
	if (jcicilan < jdiskon)
	{
		alert("Jumlah pembayaran harus lebih besar daripada jumlah diskon!");
		Validator.FocusErrorById("jdiskon");
		return false;
	}
			
	if (jcicilan - jdiskon > sisa)
	{
		alert("Jumlah pembayaran tidak boleh lebih besar daripada sisa tagihan!");
		Validator.FocusErrorById("jcicilan");
		return false;
	}
			
	//if (!confirm("Data sudah benar?"))
	//	return false;
	
	var ktagihan = $("#ktagihan").val().replace("'", "`");
	var kcicilan = $("#kcicilan").val().replace("'", "`");
	var ncicil = $("#ncicil").val();
	var infocicil = "Pembayaran ke-" + ncicil + " " + $("#payment option:selected").text();
	if (jcicilan - jdiskon == sisa)
		infocicil = "Pelunasan " + $("#payment option:selected").text();
	infocicil = infocicil.replace("'", "`");
	var rekkas = $("#rekkas").val();
	var namakas = $("#rekkas option:selected").text();
	
	var lunas = 0;
	if (tagihan == 0) 
		lunas = 2;
	else if (jcicilan - jdiskon == sisa) 
		lunas = 1;
		
	totalPayment += jcicilan - jdiskon;
	
	nData += 1;
	var data = "";
	data += "<tr id='row" + nData + "'>\r\n";
	data += "<input type='hidden' id='" + kate + "_" + idpayment + "' value='1'>\r\n";
	data += "<input type='hidden' name='i_kate_" + nData + "' value='" + kate + "'>\r\n";
	data += "<input type='hidden' name='i_idpayment_" + nData + "' value='" + idpayment + "'>\r\n";
	data += "<input type='hidden' name='i_idbesarjtt_" + nData + "' value='" + idbesarjtt + "'>\r\n";
	data += "<input type='hidden' name='i_tagihan_" + nData + "' value='" + tagihan + "'>\r\n";
	data += "<input type='hidden' name='i_bcicilan_" + nData + "' value='" + bcicilan + "'>\r\n";
	data += "<input type='hidden' name='i_ktagihan_" + nData + "' value='" + ktagihan + "'>\r\n";
	data += "<input type='hidden' id='i_jcicilan_" + nData + "' name='i_jcicilan_" + nData + "' value='" + jcicilan + "'>\r\n";
	data += "<input type='hidden' id='i_jdiskon_" + nData + "' name='i_jdiskon_" + nData + "' value='" + jdiskon + "'>\r\n";
	data += "<input type='hidden' name='i_kcicilan_" + nData + "' value='" + kcicilan + "'>\r\n";
	data += "<input type='hidden' name='i_infocicilan_" + nData + "' value='" + infocicil + "'>\r\n";
	data += "<input type='hidden' name='i_rekkas_" + nData + "' value='" + rekkas + "'>\r\n";
	data += "<input type='hidden' name='i_namakas_" + nData + "' value='" + namakas + "'>\r\n";
	data += "<input type='hidden' name='i_lunas_" + nData + "' value='" + lunas + "'>\r\n";
	data += "<td align='left'>" + namakas + "</td>\r\n";
	data += "<td align='left'>" + infocicil + "</td>\r\n";
	data += "<td align='right'>" + numberToRupiah(jcicilan) + "</td>\r\n";
	data += "<td align='right'>" + numberToRupiah(jdiskon) + "</td>\r\n";
	data += "<td align='right'>" + numberToRupiah(jcicilan - jdiskon) + "</td>\r\n";
	data += "<td align='center'><input type='button' class='but' value='hapus' onclick='DeletePayment(" + nData + ")'></td>\r\n";
	data += "</tr>\r\n";
	$("#tabPaymentList > tbody:last").append(data);
	$("#spanTotalInfo").text(numberToRupiah(totalPayment));
	
	if ($("#flagrow" + nData).length > 0)
	{
		$("#flagrow" + nData).val(1);
	}
	else
	{
		data = "<input type='hidden' name='flagrow" + nData + "' id='flagrow" + nData + "' value='1'>\r\n";
		$("#mainForm").append(data);
		$("#nflagrow").val(nData + 1);
	}
	
	$("#divPaymentInfo").text("");
}

AddToPaymentListSkr = function()
{
	var kate = $("#kate").val();
	var idpayment = $("#payment").val();
	if ($("#" + kate + "_" + idpayment).length > 0)
	{
		alert("Jenis pembayaran ini sudah ada dalam Daftar Pembayaran!");
		return false;
	}
	
	if (!ValidateRupiahInput("jumlah", "Pembayaran Iuran Sukarela", false))
		return false;
	
	var jumlah = parseInt(GetRupiahValueOf("jumlah"));
	var keterangan = $("#keterangan").val().replace("'", "`");
	var infocicil = "Pembayaran " + $("#payment option:selected").text();
	var rekkas = $("#rekkas").val();
	var namakas = $("#rekkas option:selected").text();
	
	totalPayment += jumlah;

	nData += 1;
	var data = "";
	data += "<tr id='row" + nData + "'>\r\n";
	data += "<input type='hidden' id='" + kate + "_" + idpayment + "' value='1'>\r\n";
	data += "<input type='hidden' name='i_kate_" + nData + "' value='" + kate + "'>\r\n";
	data += "<input type='hidden' name='i_idpayment_" + nData + "' value='" + idpayment + "'>\r\n";
	data += "<input type='hidden' id='i_jumlah_" + nData + "' name='i_jumlah_" + nData + "' value='" + jumlah + "'>\r\n";
	data += "<input type='hidden' id='i_jcicilan_" + nData + "' name='i_jcicilan_" + nData + "' value='" + jumlah + "'>\r\n";
	data += "<input type='hidden' name='i_rekkas_" + nData + "' value='" + rekkas + "'>\r\n";
	data += "<input type='hidden' name='i_namakas_" + nData + "' value='" + namakas + "'>\r\n";
	data += "<input type='hidden' id='i_jdiskon_" + nData + "' name='i_jdiskon_" + nData + "' value='0'>\r\n";
	data += "<input type='hidden' name='i_keterangan_" + nData + "' value='" + keterangan + "'>\r\n";
	data += "<input type='hidden' name='i_infocicilan_" + nData + "' value='" + infocicil + "'>\r\n";
	data += "<td align='left'>" + namakas + "</td>\r\n";
	data += "<td align='left'>" + infocicil + "</td>\r\n";
	data += "<td align='right'>" + numberToRupiah(jumlah) + "</td>\r\n";
	data += "<td align='right'>" + numberToRupiah(0) + "</td>\r\n";
	data += "<td align='right'>" + numberToRupiah(jumlah) + "</td>\r\n";
	data += "<td align='center'><input type='button' class='but' value='hapus' onclick='DeletePayment(" + nData + ")'></td>\r\n";
	data += "</tr>\r\n";
	$("#tabPaymentList > tbody:last").append(data);
	$("#spanTotalInfo").text(numberToRupiah(totalPayment));
	
	if ($("#flagrow" + nData).length > 0)
	{
		$("#flagrow" + nData).val(1);
	}
	else
	{
		data = "<input type='hidden' name='flagrow" + nData + "' id='flagrow" + nData + "' value='1'>\r\n";
		$("#mainForm").append(data);
		$("#nflagrow").val(nData + 1);
	}
	
	$("#divPaymentInfo").text("");
}

AddToPaymentList = function()
{
	var kate = $("#kate").val();
	
	if (kate == "JTT" || kate == "CSWJB")
		return AddToPaymentListWjb();
	else if (kate == "SKR" || kate == "CSSKR") 
		return AddToPaymentListSkr();
}

ValidateSave = function()
{
	if (nData < 0)
	{
		alert("Anda perlu memasukan minimal satu transaksi!")
		return false;
	}
	
	var nZero = 0;
	for (var i = 0; i < nData; i++)
	{
		var flag = parseInt($("#flagrow" + i).val());
		if (flag == 0)
			nZero += 1;
	}
	
	if (nData == nZero - 1)
	{
		alert("Anda perlu memasukan minimal satu transaksi!")
		return false;
	}
	
	return confirm("Semua data sudah benar?");
}