var nflag = -1;

changeDate = function(selno)
{
	var selyear = $("#tahun" + selno).val();
	var selmonth = $("#bulan" + selno).val();
	var selday = $("#tanggal" + selno).val();
	var data = "selyear="+selyear+"&selmonth="+selmonth+"&selday="+selday+"&selno="+selno;
	
	$.ajax({
        type: "POST",
        url: "lembur.input.getdate.php",
        data: data,
        success: function(response) {
			$("#spdate" + selno).html(response);
        },
        error: function(xhr, ajaxOptions, thrownError) {
			
        }
    });
}

cariPegawai = function()
{
	var nip = $.trim($("#nip0").val());
	if (nip.length == 0)
	{
		$("#flag0").val(-1);
		$("#nama0").html("");
		return;
	}
	
	$.ajax({
        type: "POST",
        url: "lembur.input.getpegawai.php",
        data: "nip="+nip,
        success: function(response) {
			var flag = response.substr(0, 1);
			var nama = response.substr(1);
			
			$("#flagnip0").val(flag);
			$("#nama0").html(nama);
        },
        error: function(xhr, ajaxOptions, thrownError) {
			$("#flagnip0").val(0);
			$("#nama0").html(xhr.responseText);
        }
    });
}

hapusLembur = function(rowno)
{
	if (!confirm("Apakah anda yakin akan menghapus data ini?")) 
		return;
	
	$("#row" + rowno).remove();
	$("#flagrow" + rowno).val(0);
}

checkData = function()
{
	// Check NIP Pegawai
	var isok = $("#flagnip0").val();
	if (isok != 1)
	{
		alert("Anda belum menentukan pegawai!");
		
		$("#nip0").focus();
		$("#nip0").addClass('ui-state-error');
		setTimeout(function(){
			$("#nip0").removeClass('ui-state-error');
		}, 4000);
		
		return false;
	}
	
	// Cek apa sudah dimasukkan sebelumnya?
	var nip = $("#nip0").val();
	var nama = $("#nama0").text();
	var tahun = $("#tahun0").val();
	var bulan = $("#bulan0").val();
	var tanggal = $("#tanggal0").val();
	
	var nrow = $("#tabLembur tr").length - 1;
	//console.log(nrow);
	if (nrow > 0)
	{
		for (var i = 0; i < nrow; i++)
		{
			var cnip = $("#i_nip" + i).val();
			var ctahun = $("#i_tahun" + i).val();
			var cbulan = $("#i_bulan" + i).val();
			var ctanggal = $("#i_tanggal" + i).val();
			//console.log(cnip + " " + ctahun + " " + cbulan + " " + ctanggal);
			
			if (nip == cnip && tahun == ctahun && bulan == cbulan && tanggal == ctanggal)
			{
				alert("Data lembur pegawai ini sudah didaftarkan!");
				return false;
			}
		}
	}

	// Check apa jam masuk & pulang nya sudah betul?
	isok = Validator.CheckHour($("#jammasuk0"), "Jam Masuk") &&
		   Validator.CheckMinute($("#menitmasuk0"), "Menit Masuk") &&
		   Validator.CheckHour($("#jampulang0"), "Jam Pulang") &&
		   Validator.CheckMinute($("#menitpulang0"), "Menit Pulang");
	if (!isok)
		return false;
	
	var jammasuk = $("#jammasuk0").val();
	var menitmasuk = $("#menitmasuk0").val();
	var jampulang = $("#jampulang0").val();
	var menitpulang = $("#menitpulang0").val();
	var keterangan = $.trim($("#keterangan0").val());

	$.ajax({
        type: "POST",
        url: "lembur.input.cekpresensi.php",
        data: "nip="+nip+"&tahun="+tahun+"&bulan="+bulan+"&tanggal="+tanggal,
        success: function(response) {
			if (response == "DUP") 
			{
				alert("Sudah ada presensi pegawai di tanggal ini!");
				return false;
			}
			
			nflag += 1;
			
			// Insert Data Into Table
			var data = "";
			data += "<tr id='row" + nflag + "'>\r\n";
			data += "<input type='hidden' name='i_nip" + nflag + "' id='i_nip" + nflag + "' value='" + nip + "'>\r\n";
			data += "<input type='hidden' name='i_tahun" + nflag + "' id='i_tahun" + nflag + "' value='" + tahun + "'>\r\n";
			data += "<input type='hidden' name='i_bulan" + nflag + "' id='i_bulan" + nflag + "' value='" + bulan + "'>\r\n";
			data += "<input type='hidden' name='i_tanggal" + nflag + "' id='i_tanggal" + nflag + "' value='" + tanggal + "'>\r\n";
			data += "<input type='hidden' name='i_jammasuk" + nflag + "' id='i_jammasuk" + nflag + "' value='" + jammasuk + "'>\r\n";
			data += "<input type='hidden' name='i_menitmasuk" + nflag + "' id='i_menitmasuk" + nflag + "' value='" + menitmasuk + "'>\r\n";
			data += "<input type='hidden' name='i_jampulang" + nflag + "' id='i_jampulang" + nflag + "' value='" + jampulang + "'>\r\n";
			data += "<input type='hidden' name='i_menitpulang" + nflag + "' id='i_menitpulang" + nflag + "' value='" + menitpulang + "'>\r\n";
			data += "<input type='hidden' name='i_keterangan" + nflag + "' id='i_keterangan" + nflag + "' value='" + keterangan + "'>\r\n";
			data += "<td align='center'>" + tahun + "-" + bulan + "-" + tanggal + "</td>\r\n";
			data += "<td align='left'>" + nip + "</td>\r\n";
			data += "<td align='left'>" + nama + "</td>\r\n";
			data += "<td align='center'>" + jammasuk + ":" + menitmasuk + "</td>\r\n";
			data += "<td align='center'>" + jampulang + ":" + menitpulang + "</td>\r\n";
			data += "<td align='left'>" + keterangan + "</td>\r\n";
			data += "<td align='left'><input type='button' value='hapus' onclick='hapusLembur(" + nflag + ")'></td>\r\n";
			data += "</tr>\r\n";
			$("#tabLembur").append(data);
			
			if ($("#flagrow" + nflag).length > 0)
			{
				$("#flagrow" + nflag).val(1);
			}
			else
			{
				data = "<input type='hidden' name='flagrow" + nflag + "' id='flagrow" + nflag + "' value='1'>\r\n";
				$("#mainForm").append(data);
				$("#nflagrow").val(nflag + 1);
			}
			
			$("#nip0").val("");
			$("#nama0").html("");
			$("#jammasuk0").val("");
			$("#menitmasuk0").val("");
			$("#jampulang0").val("");
			$("#menitpulang0").val("");
			$("#keterangan0").val("")
        },
        error: function(xhr, ajaxOptions, thrownError)
		{
			alert(xhr.responseText);
			isok = false;
        }
    });
}

validateSave = function()
{
	var nrow = $("#tabLembur tr").length - 1;
	if (nrow == 0)
	{
		alert("Anda belum memasukkan data lembur pegawai!");
		return false;
	}
	
	return confirm("Data sudah benar?");
}

function pilihPegawai()
{
	var addr = "../pegawai/pilihpegawai.php";
    newWindow(addr, 'PilihPegawai','550','550','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function AcceptPegawai(nip, nama)
{
	$("#nip0").val(nip);
	$("#nama0").html(nama);
	$("#flagnip0").val(1);
}
